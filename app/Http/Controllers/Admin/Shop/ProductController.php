<?php

namespace App\Http\Controllers\Admin\Shop;

use App\Models\Shop\OrderSku;
use DB;
use File;
use JavaScript;
use Log;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Shop\ProductImage;
use App\Models\Shop\Product;
use App\Models\Shop\Category;
use App\Models\Shop\Attribute;
use App\Models\Shop\SkuAttribute;
use App\Models\Shop\AttributeValue;
use App\Models\Shop\Sku;
use App\Models\Shop\Cart;

class ProductController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //商品分类属性ID
        $productCategories = Category::all();
        //dd($productCategory);
        $CategoryLevels = [];
        foreach ($productCategories as $key=>$productCategory) {
            if($productCategory->level == 0) {
                $CategoryLevels[$key] = $productCategory;
                $level2 = Category::where('pid',$productCategory->id)->get();//dd($level2);
                if(!empty($level2)) {
                    $CategoryLevels[$key]['child'] = $level2;//dd($CategoryLevels);
                    foreach($level2 as $k=>$v) {
                        //dd($v);
                        $level3 = Category::where('pid',$v->id)->get();
                        if(!empty($level3)) {
                            $CategoryLevels[$key]['child'][$k]['child'] = $level3;
                            //dd($CategoryLevels);
                        }
                    }
                }
            }
        }
        //dd($CategoryLevels);
        return view('admin.shop.manageproducts.index',['productCategories'=>$CategoryLevels]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $product = new Product();
        //$productCategories = Category::where('pid',0)->where('pid','null')->get();
        $productCategories = Category::all();//dd($p);
        JavaScript::put([
            //'level_attributes' => action('Admin\Shop\ProductController@getLevelAttributes'),
            'sku' => ''
        ]);
        return view('admin.shop.manageproducts.edit',['product'=>$product,'productCategories'=>$productCategories]);
    }

    /**
     * 保存新建商品信息
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $product = [''];
        $product = $request->product;
        //dd($product['category']);
        foreach ($product['category'] as $key=>$value) {
            if($value != '') $product_category = $value;
        }
        //dd($product);

        //保存商品基本数据
        $newProduct = Product::create([
                        'merchant_id' => 1,
                        'shop_category_id' => $product_category,
                        'name' => $product['name'],
                        'html_info' => $product['html_info'],
                        'info' => $product['info'],
                        'sn' => $product['sn'],
                        'area' => $product['area'],
                        'package' => $product['package'],
                        'show_price' => $product['show_price'],
                        'show_min_price' => $product['show_min_price'],
                        'show_max_price' => $product['show_max_price'],
                        'status' => $product['status'],
                        'is_available' => $product['is_available']
                    ]);
        if($newProduct) {
            Log::info("new product id  ".$newProduct->id);
        }
        $id = $newProduct->id;

        //保存商品图片
        $mobileImages = $request['product']['image']['mobile'];
        //$this->saveProductImage($mobileImages,$id,'mobile');
        $this->saveProductImage($mobileImages,$id,'common');
        //$pcImages = $request['product']['image']['pc'];
        //$this->saveProductImage($pcImages,$id,'pc');

        //保存商品规格属性
        $skus = $request->input('sku');
        if(!empty($skus)) {
            foreach ($skus as $key=>$sku) {
                $attribute_values_array = explode(',', $key);
                sort($attribute_values_array);
                $attribute_values = implode(',', $attribute_values_array);
                $sku = SKU::create([
                            'product_id' => $id,
                            'merchant_id' => 1,
                            'product_name' => $product['name'],
                            'sn' => $sku['sn']['0'],
                            'quantity' => $sku['quantity']['0'],
                            'price' => $sku['price']['0'],
                            'sale_attribute_values' => $attribute_values
                       ]);

                //保存sell属性关联关系
                $sku_id = $sku->id;
                foreach ($attribute_values_array as $key=>$attribute_value_id) {
                    $attribute = AttributeValue::where('id',$attribute_value_id)->first();
                    $attribute_id = $attribute->shop_attribute_id;
                    SkuAttribute::create([
                        'sku_id' => $sku_id,
                        'product_id' => $id,
                        'attribute_value_id' => $attribute_value_id,
                        'attribute_id' => $attribute_id,
                        'is_sell' => 1,
                    ]);
                }
            }

            //保存base属性关联关系
            $baseAttributes = $product['base_attribute'];
            foreach ($baseAttributes as $key=>$baseAttribute) {
                $base_attribute_id = $key;
                foreach ($baseAttribute as $base_attribute_value_id) {
                    SkuAttribute::create([
                        'sku_id' => 0,
                        'product_id' => $id,
                        'attribute_value_id' => $base_attribute_value_id,
                        'attribute_id' => $base_attribute_id,
                        'is_sell' => 0,
                    ]);
                }
            }
        }
        if(!empty($request->backlist)) {
            return redirect()->to(action('Admin\Shop\ProductController@index'));
        } else {
            //return back();
            return redirect()->action('Admin\Shop\ProductController@edit',[$id]);
        }
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
        $product = Product::find($id);
        //$productImages = $product->images;
        $productImages = ProductImage::where('product_id',$id)
            ->where('device',1)
            ->get();
        //dd($productImages);
        $pImage = [];
        foreach ($productImages as $key=>$productImage){
            $pImage[$productImage->pos] = $productImage->image;
        }

        //商品分类属性ID
        $productCategory = Category::find($product->shop_category_id);
        $CategoryLevel = [];
        if(empty($productCategory)) {
            $productCategories = 'invalid';
            $jsProductCategoryId = "invalid";
        } else {
            $productCategories = '';
            $jsProductCategoryId = $productCategory->id;
            if($productCategory->level == 0) {
                $CategoryLevel[0] = $productCategory;
            } elseif($productCategory->level == 1) {
                $CategoryLevel[0] = $productCategory->parent;
                $CategoryLevel[1] = $productCategory;
            } elseif($productCategory->level == 2) {
                $CategoryLevel[0] = $productCategory->parent->parent;
                $CategoryLevel[1] = $productCategory->parent;
                $CategoryLevel[2] = $productCategory;
            } elseif($productCategory->level == 3) {
                $CategoryLevel[0] = $productCategory->parent->parent->parent;
                $CategoryLevel[1] = $productCategory->parent->parent;
                $CategoryLevel[2] = $productCategory->parent;
                $CategoryLevel[3] = $productCategory;
            }
        }
        //商品分类属性ID
        $productSkus = Sku::where('product_id',$id)->get();
        $productAttributes = [];
        $sku = [];
        foreach ($productSkus as $key=>$productAttribute) {
            $pa = explode(',', $productAttribute->sale_attribute_values);
            $productAttributes = array_merge($pa, $productAttributes);
            $sku[$productAttribute->sale_attribute_values]['price'] = $productAttribute->price;
            $sku[$productAttribute->sale_attribute_values]['quantity'] = $productAttribute->quantity;
            $sku[$productAttribute->sale_attribute_values]['sn'] = $productAttribute->sn;
            $sku[$productAttribute->sale_attribute_values]['id'] = $productAttribute->id;
        }

        //base属性
        $base_attributes = SkuAttribute::where('product_id',$id)
            ->where('sku_id',0)
            ->lists('attribute_value_id');

        //dd($base_attributes);
        JavaScript::put([
            'productCategory' => $jsProductCategoryId,
            'productAttributes' => array_values(array_unique($productAttributes)),
            'sku' => $sku,
            'base_attributes' => $base_attributes
        ]);
        //dd($productCategories);
        return view('admin.shop.manageproducts.edit',['product' => $product,'productImages'=>$pImage,'productCategory'=>$CategoryLevel,'productCategories'=>$productCategories]);
    }

    /**
     * 更新商品信息
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(empty($id)) return;
        $product = [''];
        $product = $request->product;
        //保存商品基本数据
        Product::where('id',$id)->update([
            'name' => $product['name'],
            'html_info' => $product['html_info'],
            'info' => $product['info'],
            'sn' => $product['sn'],
            'area' => $product['area'],
            'package' => $product['package'],
            'show_price' => $product['show_price'],
            'show_min_price' => $product['show_min_price'],
            'show_max_price' => $product['show_max_price'],
            'status' => $product['status'],
            'is_available' => $product['is_available']
        ]);
        Log::info("update product id  ".$id);

        //保存商品图片
        $mobileImages = $request['product']['image']['mobile'];
        //$this->saveProductImage($mobileImages,$id,'mobile');
        $this->saveProductImage($mobileImages,$id,'common');
        //$pcImages = $request['product']['image']['pc'];
        //$this->saveProductImage($pcImages,$id,'pc');


        //保存商品规格属性
        $skus = $request->input('sku');
        $originalSkuId = Sku::where('product_id',$id)->lists('id')->toArray();
        $formSkuId = [];
        if(!empty($skus)) {
            foreach ($skus as $key=>$sku) {
                $attribute_values_array = explode(',', $key);
                sort($attribute_values_array);
                $attribute_values = implode(',', $attribute_values_array);

                //更新sku信息
                $skuId = key($sku['sn']);
                Sku::updateOrCreate(['id'=>$skuId],[
                    'merchant_id' => 1,
                    'product_id' => $id,
                    'product_name' => $product['name'],
                    'sn' => $sku['sn'][$skuId],
                    'quantity' => $sku['quantity'][$skuId],
                    'price' => $sku['price'][$skuId],
                    'sale_attribute_values' => $attribute_values
                ]);

                //软删除sell属性
                if($skuId != 0) $formSkuId[] = $skuId;

                //更新sell属性关联关系
                $sku = Sku::where('sale_attribute_values',$attribute_values)->first();
                $sku_id = $sku->id;
                Log::info('update sku id  '.$sku_id);
                foreach ($attribute_values_array as $key=>$attribute_value_id) {
                    $attribute = AttributeValue::find($attribute_value_id);
                    if($attribute) {
                        $attribute_id = $attribute->shop_attribute_id;
                    } else {
                        Log::info('shop attribute id  '.$attribute->shop_attribute_id);
                    }
                    SkuAttribute::updateOrCreate(
                        [
                            'sku_id' => $sku_id,
                            'product_id' => $id,
                            'attribute_value_id' => $attribute_value_id,
                            'attribute_id' => $attribute_id,
                            'is_sell' => 1,
                        ],
                        [
                            'sku_id' => $sku_id,
                            'product_id' => $id,
                            'attribute_value_id' => $attribute_value_id,
                            'attribute_id' => $attribute_id,
                            'is_sell' => 1,
                        ]
                    );
                }

                //更新base属性关联关系
                $baseAttributes = $product['base_attribute'];//dd($baseAttributes);
                $originalBaseAttributes = SkuAttribute::where('product_id', $id)
                    ->where('sku_id', 0)
                    ->lists('attribute_value_id')
                    ->toArray();
                foreach ($baseAttributes as $key=>$baseAttribute) {
                    $base_attribute_id = $key;
                    foreach ($baseAttribute as $base_attribute_value_id) {
                        $baseAttributeValues[] = $base_attribute_value_id;
                        if(!in_array($base_attribute_value_id,$originalBaseAttributes)) {
                            SkuAttribute::create([
                                'sku_id' => 0,
                                'product_id' => $id,
                                'attribute_value_id' => $base_attribute_value_id,
                                'attribute_id' => $base_attribute_id,
                                'is_sell' => 0,
                            ]);
                        }
                    }
                }

                //软删除没有的关联关系
                foreach ($originalBaseAttributes as $originalBaseAttribute) {
                    if(!in_array($originalBaseAttribute,$baseAttributeValues)) {
                        SkuAttribute::where('product_id', $id)
                            ->where('attribute_value_id',$originalBaseAttribute)
                            ->where('sku_id', 0)
                            ->delete();
                    }
                }
            }
        }

        //软删除SKU记录
        $delSkuId = array_merge(array_diff($formSkuId,$originalSkuId),array_diff($originalSkuId,$formSkuId));
        Sku::whereIn('id',$delSkuId)->delete();

        //软删除相关的关联关系记录
        SkuAttribute::whereIn('sku_id',$delSkuId)->delete();

        if(!empty($request->backlist)) {
            return redirect()->to(action('Admin\Shop\ProductController@index'));
        } else {
            return back();
        }
    }

    /**
     * 复制商品并创建新商品
     * @param $id 商品ID
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function copyProduct($id) {
        $product = Product::find($id);
        $product->id = '';
        $productCategories = Category::all();
        $productImages = ProductImage::where('product_id',$id)
            ->where('device',2)
            ->get();
        $pImage = [];
        foreach ($productImages as $key=>$productImage){
            $pImage[$productImage->pos] = $productImage->image;
        }
        JavaScript::put([
            'sku' => ''
        ]);
        return view('admin.shop.manageproducts.edit',['product' => $product,'productImages'=>$pImage,'productCategories'=>$productCategories]);
    }

    /**
     * 获取所有商品
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function listJson(Request $request){
        //dd(Product::all());

        $draw = (int) $request->input('draw');
        $start = (int) $request->input('start');
        $length = (int) $request->input('length');

        $sParams = $sCategory = $sAvailable = $sStatus = $sName = '';
        $search =  $request->input('search');//dd($search);
        if(!empty($search['value'])) {
            $sParams =  explode(',',$search['value']);
            $sCategory = $sParams[0];
            $sAvailable = $sParams[1];
            $sStatus = $sParams[2];
            $sName = $sParams[3];
            //搜索所有子分类
            $currentCategory = Category::find($sCategory);
            $categorise = [];//dd($currentCategory);
            if(!empty($sCategory) && $currentCategory) {
                $categorise[] = $currentCategory->id;
                $childCategories = $currentCategory->children;
                foreach ($childCategories as $childCategory) {
                    $categorise[] = $childCategory->id;
                    if(!empty($childCategory->children)) {
                        foreach ($childCategory->children as $level3) {
                            $categorise[] = $level3->id;
                        }
                    }
                }
            }
        };
        $data = [];
        $count = Product::all()->count();
        if(!empty($sCategory) && $sAvailable=='' && $sStatus==''){
            $products = Product::skip($start)
                ->whereIn('shop_category_id', $categorise)
                ->take($length)
                ->orderBy('id', 'desc')
                ->get();
        } elseif(empty($sCategory) && $sAvailable!='' && $sStatus==''){
            $products = Product::skip($start)
                ->where('is_available', $sAvailable)
                ->take($length)
                ->orderBy('id', 'desc')
                ->get();
        } elseif(empty($sCategory) && $sAvailable=='' && $sStatus!=''){
            $products = Product::skip($start)
                ->where('status', $sStatus)
                ->take($length)
                ->orderBy('id', 'desc')
                ->get();
        } elseif(!empty($sCategory) && $sAvailable!='' && $sStatus==''){
            $products = Product::skip($start)
                ->whereIn('shop_category_id', $categorise)
                ->where('is_available', $sAvailable)
                ->take($length)
                ->orderBy('id', 'desc')
                ->get();
        } elseif(!empty($sCategory) && $sAvailable!='' && $sStatus!='') {
            $products = Product::skip($start)
                ->whereIn('shop_category_id', $categorise)
                ->where('is_available', $sAvailable)
                ->where('status', $sStatus)
                ->take($length)
                ->orderBy('id', 'desc')
                ->get();
        } elseif(!empty($sName)) {
            $products = Product::skip($start)->where('name', 'LIKE', "%$sName%")
                ->take($length)
                ->orderBy('id', 'desc')
                ->get();
        } else {
            $products = Product::skip($start)->take($length)->orderBy('id', 'desc')->get();
        }
        foreach ($products as $key => $product) {
            $productCategory = Category::find($product->shop_category_id);
            //dd($productCategory);
            $productQuantity = Sku::where('product_id',$product->id)->lists('quantity')->sum();//->reduce();
            $data[$key]['id'] = $product->id;
            $data[$key]['name'] = $product->name;
            if(!empty($productCategory->parent)) {
                if(!empty($productCategory->parent->parent)) {
                    $data[$key]['shop_category'] = $productCategory->parent->parent->name.'->'.$productCategory->parent->name.'->'.$productCategory->name;
                } else {
                    $data[$key]['shop_category'] = $productCategory->parent->name.'->'.$productCategory->name;
                }
            } else {
                $data[$key]['shop_category'] = $productCategory ? $productCategory->name : '无效分类';
            }
            if($product->is_available == 1) {
                $data[$key]['is_available'] = '是';
            } else {
                $data[$key]['is_available'] = '否';
            }
            if($product->status == 0 ){
                $data[$key]['status'] = '无效商品';
            } elseif($product->status == 1) {
                $data[$key]['status'] = '未审核';
            } else {
                $data[$key]['status'] = '已审核';
            }

            $data[$key]['show_price'] = $product->show_price;
            $data[$key]['nums'] = $productQuantity;
            $data[$key]['action'] = '';
        }
        $json = [
            'recordsTotal' => $count,
            'recordsFiltered' => $count,
            'draw' => $draw,
            'data'=>$data
        ];
        //dd($data);
        return response()->json($json);
    }

    /**
     * 保存商品图片及其位置,删除老的商品图片
     *
     * @param array $images
     * @param int $id
     * @param string $device
     */
    public function saveProductImage($images,$id,$device)
    {
        //$img_position = explode(",",$request['product']['img_position']);//dd($img_position);
        /*if($device == 'mobile' ) {
            $device_tag = 1;
        } else {
            $device_tag = 2;
        }*/
        $device_tag = 2;
        foreach ($images as $key=>$image) {
            //图片位置
            $pos = $key+1;
            if(!empty($image) && strpos($image, '/files/shop/product/'.$id.'/'.$device.'/'.$pos) === false) {
                $image = urldecode($image);
                $image = $this->saveCoverImage($image,$id,$device,$pos);
                $oldImage = ProductImage::where('product_id',$id)
                    ->where('pos',$pos)
                    ->where('device',$device_tag)
                    ->get();
                $mTotal = $oldImage->count();//dd($mTotal);
                if($mTotal == 1) {
                    ProductImage::where('product_id',$id)
                        ->where('pos',$pos)
                        ->whereIn('device',[1,2])
                        ->update(['image'=>$image]);
                } else {
                    //ProductImage::create(['product_id'=>$id,'image'=>$image,'pos'=>$pos,'device'=>$device_tag]);
                    ProductImage::create(['product_id'=>$id,'image'=>$image,'pos'=>$pos,'device'=>'1']);
                    ProductImage::create(['product_id'=>$id,'image'=>$image,'pos'=>$pos,'device'=>'2']);
                }
            } elseif(empty($image)) {
                $mTotal = ProductImage::where('product_id',$id)
                    ->where('pos',$pos)
                    ->where('device',$device_tag)
                    ->get()
                    ->count();
                if($mTotal == 1) {
                    ProductImage::where('product_id',$id)
                        ->where('pos',$pos)
                        //->where('device',$device_tag)
                        ->forceDelete();
                }
            }
            if($pos ==1 ) ProductImage::where('product_id',$id)->where('pos',$pos)->update(['is_first'=>1]);
        }

        //删除老的商品图片
        foreach ($images as $key=>$image) {
            $pos = $key+1;
            $imageFile = '/files/shop/product/'.$id.'/'.$device.'/'.$pos;
            if(empty($image)) {
                //删除没有商品图片的文件夹
                if(file_exists(public_path(ltrim($imageFile,'/')))) {
                    File::deleteDirectory(public_path(ltrim($imageFile,'/')));
                }
            }
        }
        /*$match_files = File::glob(public_path('files/shop/product/'.$id.'/'.$device.'/cover_image_square_*'));
        $product_images = ProductImage::where('product_id',$id)
            ->where('device',$device_tag)
            ->lists('image')
            ->toArray();
        foreach($product_images as $key=>$product_image) {
            $product_images[$key] = public_path(ltrim($product_image, '/'));
        }
        if($match_files !== false) {
            $deleteFiles = array_merge(array_diff($match_files,$product_images),array_diff($product_images,$match_files));
            foreach($deleteFiles as $file) {
                if(file_exists($file))
                    File::delete($file);
            }
        }*/
    }

    /**
     * 复制上传的商品图片到对应文件夹里面，并返回该图片的路径
     *
     * @param string $path
     * @param int $id
     * @param string $device 设备类型
     * @param int $pos
     * @return string
     */
    protected function saveCoverImage($path,$id,$device,$pos){
        $ext = File::extension($path);
        $name = File::name($path);
        $newName = 'cover_image_square_'.md5($name).date('_Ymd_His').'.'.$ext;
        $dir = '/files/shop/product/'.$id.'/'.$device.'/'.$pos;
        $product_image_path = $dir.'/'.$newName;

        //在已有的图片位置更新图片时，删除旧图片
        if(strpos($path,'/files/shop/product/'.$id.'/'.$device) === false && File::exists(public_path(ltrim($path, '/')))) {
            File::deleteDirectory(public_path(ltrim($dir,'/')));
        }

        if(!File::exists(public_path(ltrim($dir, '/')))){
            File::makeDirectory(public_path(ltrim($dir, '/')), 0755, true);
        }
        //产品图片互换位置时移动图片
        if(strpos($path,'/files/shop/product/'.$id.'/'.$device) !== false && File::exists(public_path(ltrim($path, '/')))) {
            File::move(public_path(ltrim($path, '/')),public_path(ltrim($product_image_path, '/')));
        }
        //dd(file_exists(public_path('files/product/'.$id.'/'.$newName)));
        if(!file_exists(public_path($product_image_path))) File::copy(public_path(ltrim($path,'/')), public_path(ltrim($product_image_path,'/')));
        //dd($product_image_path);
        return $product_image_path;
    }

    /**
     * ajax获取商品分类属性
     *
     * @param int $id
     * @return json
     *
     */
    public function ajaxGetCategoryAttributes($id) {
        $id = (int)$id;
        //基本属性
        $CategoryAttributes['is_base'] = Category::find($id)
            ->attributes()
            ->where('is_base',1)
            ->where('pid',0)
            ->get();
        $LevelAttributes = $level = [];
        foreach($CategoryAttributes['is_base'] as $key=>$CategoryAttribute) {
            $baseAttributes = AttributeValue::where('status',1)->where('shop_attribute_id', $CategoryAttribute->id)->get();
            $attributeIds = [];
            foreach ($baseAttributes as $k => $baseAttribute) {
                $attribute = Attribute::where('status',1)->where('name', $baseAttribute->value)->first();
                $baseAttribute->parent_id = '';
                if (!empty($attribute)) $attributeIds[$key][$k] = $attribute->id;
            }
            $level[$key][0] = $baseAttributes;
            if (!empty($attributeIds)) {
                $level = $this->getLevelAttribute($attributeIds[$key],$key,1,$level);
            }
            $CategoryAttribute->level = $level[$key];

        }
        //销售属性
        $CategoryAttributes['is_sell'] = Category::find($id)
            ->attributes()
            ->where('is_sell',1)
            ->get();
        foreach($CategoryAttributes['is_sell'] as $key=>$CategoryAttribute) {
            $sellAttributes = AttributeValue::where('status',1)->where('shop_attribute_id',$CategoryAttribute->id)->get();
            $CategoryAttribute->value = $sellAttributes;
        }
        //dd($CategoryAttributes);
        return response()->json($CategoryAttributes);
    }

    /**
     * @param $attributeIds 属性ID
     * @param $key 循环标示
     * @param $i 循环标示
     * @param $level 属性等级
     * @return mixed
     */
    public function getLevelAttribute($attributeIds,$key,$i,$level){
        unset($levelAttributes);
        $levelAttributeIds = [];
        $levelAttributes = AttributeValue::where('status',1)->whereIn('shop_attribute_id',$attributeIds)->get();
        foreach($levelAttributes as $levelAttribute) {
            $parentAttribute = Attribute::where('status',1)->find($levelAttribute->shop_attribute_id);
            $levelAttribute->parent_id = AttributeValue::where('status',1)->where('value', $parentAttribute->name)->first()->id;
            $level2Attribute = Attribute::where('status',1)->where('name', $levelAttribute->value)->first();
            if(!empty($level2Attribute)) $levelAttributeIds[] = $level2Attribute->id;
        }
        //if($i==2) dd($level);
        $level[$key][$i] = $levelAttributes;
        //if($i==2) dd($levelAttributeIds);
        if(!empty($levelAttributeIds)) {
            $i++;
            $level = $this->getLevelAttribute($levelAttributeIds,$key,$i,$level);
        }
        return $level;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $status = 1;
        $errorMessage = '';
        /*$orderSku = OrderSku::where('product_id',$id)->first();
       //$cartProduct = Cart::where('product_id',$id)->get();
      if(!$cartProduct->isEmpty()) {
           $errorMessage = "该商品已在购物车中请勿删除！";
           $status = 0;
       }
       if(!empty($orderSku)) {
           $order = Product::find($orderSku->order_id);
       }
       if($order->status==1 || $order->status==3 || $order->status==4) {
               $errorMessage = "该商品在订单$order->sn 中还未完成请勿删除！";
               $status = 0;
       } else {
           Product::find($id)->delete();
           Log::info("delete product id  ".$id);
           Sku::where('product_id',$id)->delete();
           SkuAttribute::where('product_id',$id)->delete();
       }*/

        Product::find($id)->delete();
        Log::info("delete product id  ".$id);
        Sku::where('product_id',$id)->delete();
        SkuAttribute::where('product_id',$id)->delete();
        $json = ['status'=>$status,'errorMessage'=>$errorMessage];
        return response()->json($json);
    }
}
