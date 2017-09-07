<?php

namespace App\Http\Controllers\Admin\Shop;

use App\Models\Shop\Product;
use App\Models\Shop\Sku;
use App\Models\Shop\SkuAttribute;
use JavaScript;
use App\Models\Shop\Attribute;
use App\Models\Shop\AttributeValue;
use App\Models\Shop\Category;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $categorys = Category::all();
        $path = config('chaohun.admin_prefix');
        $json = [
            'path' => $path,
            'categorys'=>$categorys,
        ];
        JavaScript::put($json);
        return view('admin.shop.attribute.list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $categoryList = Category::all();
        return view('admin.shop.attribute.edit',['categoryList'=>$categoryList]);
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
        $attribute = $request->input('attribute');
        $attribute['created_at'] = date('Y-m-d H:i:s');
        $attribute['updated_at'] = $attribute['created_at'];
        $category = Attribute::create($attribute);
        $categoryList = Category::all();
        $id = Attribute::orderBy('created_at','desc')->first()->id;
        return $this->index();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        $id = (int) $id;
        $attribute = Attribute::find($id);
        if(!empty($attribute)){
            $parentAttributeList = Attribute::where('shop_category_id',$attribute->shop_category_id)->get();
            $categoryList = Category::all();
            return view('admin.shop.attribute.edit',['attribute'=>$attribute,"categoryList"=>$categoryList]);
        }else{
            return $this->create();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        Attribute::where('id', (int) $id)
            ->update($request->input('attribute'));
        return $this->edit($id);
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

    public function listJson(Request $request){
        $draw = (int) $request->input('draw');
        $start = (int) $request->input('start');
        $length = (int) $request->input('length');
        $search =  $request->input('search');
        $id = (int) $request->input('id');
        $arr=array();
        if($id>0){
            $attrs = Attribute::where("id",">",0)->where('shop_category_id',$id)->where('level',0)->orderBy('id','desc')->skip($start)->take($length)->get();
        }else{
            $attrs = Attribute::where("id",">",0)->where('level',0)->orderBy('id','desc')->skip($start)->take($length)->get();
        }

        foreach ($attrs as $key=>$value){
            $this->getTreeAttribute($value,$arr);
        }
        $attrs = $arr;
        $count = count($attrs);
        foreach ($attrs as $key=>$value){
            $category = Category::find($value->shop_category_id);
            if(!empty($category)){
                $attrs[$key]->shop_category_id = $category->name;
            }else{
                $attrs[$key]->shop_category_id = '--';
            }
            $parentAttr = Attribute::find($value->pid);
            if(!empty($parentAttr)){
                $attrs[$key]->pid = $parentAttr->name;
            }else{
                $attrs[$key]->pid = '--';
            }
            if($value->required == 0){
                $value->required = '否';
            }else{
                $value->required = '是';
            }
            if($value->is_sell == 1){
                $value->is_sell = '销售';
            }else{
                $value->is_sell='基础';
            }
        }

        $json = ['recordsTotal' => $count,
            'recordsFiltered' => $count,
            'draw' => $draw,
            'data' => $attrs];

        // Log::info(response()->json($json));
        return response()->json($json);
    }

    public function getTreeAttribute($attribute,&$arr){
        Log::info('名称'.$attribute->name);
        array_push($arr,$attribute);
        $attributes =  Attribute::where("pid",$attribute->id)->orderBy('id','desc')->get();
        $cnt = count($attributes);
        foreach ($attributes as $key=>$value){
            if($key+1 == $cnt){
                $value->islast = 1;
            }else{
                $value->islast = 0;
            }
            $this->getTreeAttribute($value,$arr);
        }
    }

    public function addAttribute(Request $request){
        $attributeValueId = (int) $request->input('id');
        Log::info('接收到的ID'.$attributeValueId);
        if($attributeValueId>0){
           $attributeValue = DB::table("shop_attribute_values")->where("id",$attributeValueId)->first();
            if(!empty($attributeValue)){
                $attributeId =(int) $attributeValue->shop_attribute_id;
                $attribute = Attribute::find($attributeId);
                if(!empty($attribute)){

                    $time = date('Y-m-d H:i:s');
                    $categoryId = $attribute->shop_category_id;
                    $level = (int) $attribute->level +1;

                    $aimAttribute = Attribute::where('shop_category_id',$categoryId)->where('pid',$attributeId)->where('name',$attributeValue->value)->get();

                    if(count($aimAttribute)<1){
                        $arr = array('is_base'=>$attribute->is_base,'is_sell'=>$attribute->is_sell,
                            'required'=>$attribute->required,'is_optional'=>$attribute->is_optional,
                            'show_name'=>$attributeValue->value,
                            'status'=>$attribute->status,'level'=>$level,'pid'=>$attributeId,'shop_category_id'=>$categoryId,
                            'name'=>$attributeValue->value,'created_at'=>$time,'updated_at'=>$time);
                        Attribute::create($arr);
                        return response()->json(array('id'=>$attributeId));
                    }else{
                        return response()->json(array('id'=>-1));
                    }
                }
            }
        }


    }
    public function addChildAttribute(Request $request){
        Log::info($request);
        $id = (int) $request->input('id');
        $aimAttribute = Attribute::find($id);
        if(!empty($aimAttribute)){
            $categoryId = $aimAttribute->shop_category_id;
            $level = (int) $aimAttribute->level + 1;
            $pos = $request->input('pos');
            $name = $request->input('name');
            $status = $request->input('status');

            $showName = $request->input('show_name');
            $required = $request->input('required');
            $isBase = $request->input('is_base');
            $isSell = $request->input('is_sell');
            $isOptional = $request->input('is_optional');
            $time = date('Y-m-d H:i:s');
            $attribute=array('pid'=>$id,'shop_category_id'=>$categoryId,'name'=>$name,'level'=>$level,'pos'=>$pos,'show_name'=>$showName,'status'=>$status,'created_at'=>$time,'updated_at'=>$time,'required'=>$required,'is_base'=>$isBase,'is_sell'=>$isSell,'is_optional'=>$isOptional);
            Attribute::create($attribute);
            return response()->json("创建子属性成功");
        }

    }
    public function editAttribute(Request $request){

        Log::info($request);
        $id = $request->input('id');
        $pos = $request->input('pos');
        $name = $request->input('name');
        $status = $request->input('status');
        $status = 1;
        $showName = $request->input('show_name');
        $required = $request->input('required');
        $isBase = $request->input('is_base');
        $isSell = $request->input('is_sell');
        $isOptional = $request->input('is_optional');
        $time = date('Y-m-d H:i:s');
        $attribute = Attribute::find($id);
        if(!empty($attribute)){
            $arr=array('name'=>$name,"pos"=>$pos,"show_name"=>$showName,"status"=>$status,'updated_at'=>$time,'required'=>$required,'is_base'=>$isBase,'is_sell'=>$isSell,'is_optional'=>$isOptional);
            Attribute::find($id)->update($arr);
            return response()->json("编辑属性成功");
        }
        return response()->json("编辑属性失败");

    }
    public function getAttribute(Request $request){
        Log::info('接收到数据'.$request);
        $id = $request->input('id');
        $attribute = Attribute::find($id);
        if(!empty($attribute)){
            return response()->json($attribute);
        }
        return response()->json('未发现'.$id.'对应属性');
    }
    public function createAttribute(Request $request){
        $pos = $request->input('pos');
        $name = $request->input('name');
        $status = $request->input('status');
        $status = 1;
        $showName = $request->input('show_name');
        $required = $request->input('required');
        $isBase = (int) $request->input('is_base');
        $isSell = (int) $request->input('is_sell');
        $isOptional = (int) $request->input('is_optional');
        $shopCategoryId = (int) $request->input('shop_category_id');
        Log::info($request);
        $attribute= array('pos'=>$pos,'name'=>$name,'show_name'=>$showName,'status'=>$status,'required'=>$required,'is_base'=>$isBase,'is_sell'=>$isSell,
            'is_optional'=>$isOptional,'shop_category_id'=>$shopCategoryId,'level'=>0);
        $attribute['created_at'] = date('Y-m-d H:i:s');
        $attribute['updated_at'] = $attribute['created_at'];

        if($isSell){
            $category = Category::find($shopCategoryId);
            if(!empty($category)){
                $bortherAttributes = Attribute::where('shop_category_id',$shopCategoryId)->where('is_sell',1)->get();
                foreach ($bortherAttributes as $key=>$value){
                    $this->delAttributeAbout($value->id);
                }
            }
        }
        $category = Attribute::create($attribute);
        Log::info('我是一个并');
        return response()->json('创建新属性成功');
    }

    //删除属性相关数据,产品以及SKU(仅限于销售属性)
    public function delAttributeAbout($id){
        $attribute = Attribute::find($id);
        if(!empty($attribute)){
            $isSell = $attribute->is_sell;
            $skuAttrs = SkuAttribute::where('attribute_id',$id)->where('is_sell',$isSell)->get();
            foreach ($skuAttrs as $key=>$value){
                if($isSell){
                    $product = Product::find($value->product_id);
                    if(!empty($product)){
                        $product->is_available = 0;
                        $product->save();
                    }
                    $sku = Sku::find($value->sku_id);
                    if(!empty($sku)){
                        $sku->delete();
                    }
                }
                $value->delete();
            }
        }
    }
    public function findChildAttributeDelAbout($id){
        $attribute = Attribute::find($id);
        if(!empty($attribute)){
            $childs = Attribute::where('pid',$id)->get();
            foreach ($childs as $key=>$value){
                $this->delAttributeAbout($value->id);
                $this->findChildAttributeDelAbout($value->id);
            }
        }
    }

    public function delAllChildAttribute($id){
        $attribute = Attribute::find($id);
        if(!empty($attribute)){
            $childs = Attribute::where('pid',$id)->get();
            foreach ($childs as $key=>$value){
                $this->delAllChildAttribute($value->id);
            }
            $attrValues = AttributeValue::where('shop_attribute_id',$attribute->id)->get();
            foreach ($attrValues as $tmpKey=>$tmpValue){
                $tmpValue->delete();
            }
            $attribute->delete();
        }
    }
    public function delAttribute(Request $request){
        $id = (int) $request->input('id');
        $value = 1;
        $message = '';
        $json = [];
        if($id>0)
        {
            $attribute = Attribute::find($id);
            if(!empty($attribute)){
                if($attribute->is_sell){
                    $bortherAttributes = Attribute::where('shop_category_id',$attribute->shop_category_id)->where('level',$attribute->level)->where('is_sell',1)->get();
                    foreach ($bortherAttributes as $key=>$attr){
                        $this->delAttributeAbout($attr->id);
                        //销售属性没有子属性
                        //$this->findChildAttributeDelAbout($attr->id);
                    }
                }else{
                    $this->delAttributeAbout($attribute->id);
                    $this->findChildAttributeDelAbout($attribute->id);
                }

                $this->delAllChildAttribute($attribute->id);
                $message = '删除分类成功';
                $value = 0;
            }else{
                $value = 1;
                $message = '未发现ID:'.$id.'对应的属性';
            }
        }else{
            $value = 1;
            $message = '无效ID:'.$id;
        }
        Log::info('删除属性成功,返回数据:'.$value.'和:'.$message);
        $json = ['value'=>$value,'message'=>$message];
        return response()->json($json);
    }
    public function attributeInfo(Request $request){
        $id = (int) $request->input('id');
        $value = 0;
        $message='';
        $childs = [];
        $skus = [];
        $products = [];
        $attribute=null;
        $isLast = false;
        if($id>0){
            $attribute=Attribute::find($id);
            if(!empty($attribute)){
                if($attribute->level <1 && $attribute->is_sell){
                    $cntAttributes = Attribute::where('is_sell',1)->where('shop_category_id',$attribute->shop_category_id)->where('level',0)->get();
                    if(count($cntAttributes) == 1){
                        $isLast = true;
                    }
                }
                $childs = Attribute::where('pid',$attribute->id)->get();
                $tmpSkuAttrs =SkuAttribute::where('attribute_id',$attribute->id)->get();
                foreach ($tmpSkuAttrs as $key=>$skuAttr){
                    $skus[] = Sku::find($skuAttr->sku_id);
                    $products[] = Product::find($skuAttr->product_id);
                }
                $value = 0;
                $message='拿到对应的数据:子属性,商品,sku';
            }else{
                $value = 1;
                $message = '未发现ID:'.$id.'对应的属性';
            }
        }else{
            $value = 1;
            $message = '非法ID:'.$id;
        }
        $json = ['value'=>$value,'message'=>$message,'childs'=>$childs,'products'=>$products,'skus'=>$skus,'attr'=>$attribute,'isLast'=>$isLast];
        return response()->json($json);
    }
}
