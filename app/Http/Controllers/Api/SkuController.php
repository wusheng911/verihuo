<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Services\ApiResponse;
use Illuminate\Database\Query\Builder;
use App\Models\Shop\Sku;
use App\Models\Shop\SkuAttribute;
use DB;


class SkuController extends Controller
{
    /**
     * Display a listing of the 库存 for a specific product
     * @param $sku_id:         get sku by sku id
     * @param $product_id:     get sku by product id
     * @param $is_sell:        if set is_sell to "1", then only get sellable sku attributes
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $product_id = $request->has('product_id')?$request->input('product_id'):'';
            $sku_id = $request->has('sku_id')?$request->input('sku_id'):'';
            $is_sell = $request->has('is_sell')?$request->input('is_sell'):'0';
            
            $query_build = null;
            if (!empty($product_id)) {
                $query_build = Sku::where('skus.product_id', '=', $product_id);
            } else {   
                if (!empty($sku_id)) {
                    $query_build = Sku::where('skus.id', "=", $sku_id);                    
                } else {
                    $response = ApiResponse::get(true, ['message' => '缺少参数！']);
                    return;
                }
                
            }            

            //先获取产品相关的所有sku
            $skus = /*$query_build->join("products", "skus.product_id", "=", "products.id")
                ->select("skus.id",
                    "skus.product_id",
                    "skus.merchant_id",
                    "skus.product_name",
                    "skus.sn",
                    "skus.barcode",
                    "skus.quantity",
                    "skus.price",
                    "skus.prime_price",
                    "skus.deleted_at",
                    "skus.created_at",
                    "skus.updated_at",
                    "products.is_available as is_available_product",
                    "products.status as product_status") */
                $query_build->with(['skuAttributes' => function($query) use ($is_sell) {
                    if (strtolower(($is_sell) == "1")) {
                        $query->where("is_sell", "=", "1");
                    }                       
                }])
                ->get();

            //获取产品的所有与sku_id关联的shop_attribute_value
            $shop_attribute_values = array();                
            foreach($skus as $sku) {
                if ($sku->skuAttributes) {                        
                    foreach($sku->skuAttributes as $sku_attribute) {
                        if ($sku_attribute->attribute_value_id) {                                
                            if (isset($shop_attribute_values[$sku_attribute->attribute_value_id])) {
                                array_push($shop_attribute_values[$sku_attribute->attribute_value_id]['sku_ids'],
                                    $sku_attribute->sku_id);
                                $shop_attribute_values[$sku_attribute->attribute_value_id]['sku_ids'] = array_unique($shop_attribute_values[$sku_attribute->attribute_value_id]['sku_ids']);
                            } else {
                                $shop_attribute_values[$sku_attribute->attribute_value_id] = [
                                    'sku_ids' => [$sku_attribute->sku_id],
                                    'is_sell' => $sku_attribute->is_sell
                                ];
                            }
                        }
                    }
                }
            }
                        
            //get product_id by sku_id
            if (!empty($sku_id)) {
                foreach($skus as $sku) {
                    $product_id = $sku["product_id"];
                    break;
                }
            }

            //获取产品的所有sku_id=0的（其他）属性
            if (empty($is_sell)) {
                $other_sku_attributes = SkuAttribute::where("product_id", "=", $product_id)
                    ->where("sku_id", "=", 0)
                    ->get();

                if ($other_sku_attributes) {
                    foreach($other_sku_attributes as $other_sku_attribute) {
                        if ($other_sku_attribute->attribute_value_id) {
                            if (isset($shop_attribute_values[$other_sku_attribute->attribute_value_id])) {
                                array_push($shop_attribute_values[$other_sku_attribute->attribute_value_id]['sku_ids'],
                                    $other_sku_attribute->sku_id);
                                $shop_attribute_values[$other_sku_attribute->attribute_value_id]['sku_ids'] = array_unique($shop_attribute_values[$other_sku_attribute->attribute_value_id]['sku_ids']);
                            } else {
                                $shop_attribute_values[$other_sku_attribute->attribute_value_id] = [
                                    'sku_ids' => [$other_sku_attribute->sku_id],
                                    'is_sell' => $other_sku_attribute->is_sell
                                ];
                            }                                
                        }                        
                    }
                } 
            }

            //再根据sku关联的shop_attribute_values获取所有的shop_attrubte+shop_attribute_value组合
            $shop_attributes = DB::table("shop_attribute_values")
                ->whereIn("shop_attribute_values.id", array_keys($shop_attribute_values))
                ->join("shop_attributes", "shop_attribute_values.shop_attribute_id", "=", "shop_attributes.id")
                ->select("shop_attributes.id as shop_attribute_id", 
                    "shop_attributes.pid as shop_attribute_pid",
                    DB::raw('getShopAttrParentList(shop_attributes.pid) as parent_list'),
                    "shop_attributes.level as shop_attribute_level",
                    "shop_attributes.name as shop_attribute_name",
                    "shop_attributes.show_name as shop_attribute_showname",
                    "shop_attributes.pos as shop_attribute_pos", 
                    "shop_attribute_values.id as value_id",
                    "shop_attribute_values.value as value", 
                    "shop_attribute_values.pos as value_pos",
                    "shop_attributes.is_sell as is_sell", 
                    "shop_attributes.is_base as is_base",
                    "shop_attributes.is_optional as is_optional")
                ->orderBy("shop_attributes.pos", "asc")
                ->orderBy("shop_attribute_values.pos", "asc")
                ->get();

            $regroup_shop_attributes = [];
            if ($shop_attributes) {
                foreach($shop_attributes as $key => $shop_attr) {
                    if ($regroup_shop_attributes) {
                        $last_attr = end($regroup_shop_attributes);
                        //如果当前shop_attribute_value和前一个shop_attribute_value属于同一个shop_attribute
                        if ($last_attr["shop_attribute_id"] == $shop_attr->shop_attribute_id) {                                
                            $regroup_shop_attributes[$shop_attr->shop_attribute_id]["shop_attribute_values"][$shop_attr->value_id] =
                            [
                                "value_id" => $shop_attr->value_id,
                                "value" => $shop_attr->value,
                                "value_pos" => $shop_attr->value_pos,
                                "sku_ids" => $shop_attribute_values[$shop_attr->value_id]['sku_ids']                                    
                            ];
                        } else { //否则添加一个新的shop_attribute
                            $regroup_shop_attributes[$shop_attr->shop_attribute_id] = 
                            [
                                "shop_attribute_id"=> $shop_attr->shop_attribute_id,
                                "shop_attribute_pid"=> $shop_attr->shop_attribute_pid,
                                "parent_list" => $shop_attr->parent_list,
                                "shop_attribute_level"=> $shop_attr->shop_attribute_level,
                                "shop_attribute_name"=> $shop_attr->shop_attribute_name,
                                "shop_attribute_showname"=> $shop_attr->shop_attribute_showname,
                                "shop_attribute_pos"=> $shop_attr->shop_attribute_pos,
                                "is_sell" =>$shop_attribute_values[$shop_attr->value_id]['is_sell'],
                                "is_base" => $shop_attr->is_base,
                                "is_optional" => $shop_attr->is_optional,
                                "shop_attribute_values" => [
                                    $shop_attr->value_id => [
                                        "value_id" => $shop_attr->value_id,
                                        "value" => $shop_attr->value,
                                        "value_pos" => $shop_attr->value_pos,
                                        "sku_ids" => $shop_attribute_values[$shop_attr->value_id]['sku_ids']
                                    ]
                                ]
                            ];
                        }
                    } else {
                        $regroup_shop_attributes[$shop_attr->shop_attribute_id] = 
                        [
                            "shop_attribute_id"=> $shop_attr->shop_attribute_id,
                            "shop_attribute_pid"=> $shop_attr->shop_attribute_pid,
                            "parent_list" => $shop_attr->parent_list,
                            "shop_attribute_level"=> $shop_attr->shop_attribute_level,                                
                            "shop_attribute_name"=> $shop_attr->shop_attribute_name,
                            "shop_attribute_showname"=> $shop_attr->shop_attribute_showname,
                            "shop_attribute_pos"=> $shop_attr->shop_attribute_pos,
                            "is_sell" =>$shop_attribute_values[$shop_attr->value_id]['is_sell'],
                            "is_base" => $shop_attr->is_base,
                            "is_optional" => $shop_attr->is_optional,
                            "shop_attribute_values" => [
                                $shop_attr->value_id => [
                                    "value_id" => $shop_attr->value_id,
                                    "value" => $shop_attr->value,
                                    "value_pos" => $shop_attr->value_pos,
                                    "sku_ids" => $shop_attribute_values[$shop_attr->value_id]['sku_ids']
                                ]
                            ]
                        ];
                    }
                } 
            }    
                

                                
            //标注有父子关系的属性
            foreach($regroup_shop_attributes as $key => &$shop_attribute) {
                if (isset($shop_attribute["parent_list"]) and $shop_attribute["parent_list"] != 0) {
                    $parents = explode(',', $shop_attribute["parent_list"]);
                    foreach($parents as $parent) {                            
                        $regroup_shop_attributes[$parent]["has_child"] = 1;
                    }
                }
            }

            $product_skus = [
                "skus" => $skus,
                "shop_attributes" => $regroup_shop_attributes
            ];

            $response = ApiResponse::get(false, $product_skus);                    
            
        } catch (\Exception $ex) {
            $response = ApiResponse::get(true, ['message' => $ex->getMessage()]);
        }
        
        return $response;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource. To save request time, parameter: 
     * 
     * $no_details: indicates not return the html_info field which contains a lot of characters.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {     
        try {
            $sku = Sku::find($id);
                        
            if ($sku == false) {
                $response = ApiResponse::get(true, ['message' => "Sku $id Not Found."]);                  
            } else {                       
                $response = ApiResponse::get(false, $sku->toArray());
            }           
        } catch (\Exception $ex) {
            $response = ApiResponse::get(true, ['message' => $ex->getMessage()]);            
        }
        
        return $response;
    }  

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage. Example:
     * curl -X PUT -d 'view_count=449' http://localhost/api/products/474
     *
     * @param \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {      

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    
    /**
     * Get the related Goods
     * 
     * @param \Illuminate\Http\Request  $request
     * @param string $id good id
     */
    public function related(Request $request, $id) 
    {

    }    
}
