<?php

namespace App\Http\Controllers\Admin\Shop;


use App\Models\Shop\AttributeValue;
use App\Models\Shop\Brand;
use App\Models\Shop\Product;
use JavaScript;
use App\Models\Shop\Attribute;
use App\Models\Shop\Category;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $path = config('chaohun.admin_prefix');
        $brands = Brand::all();
        $categorys = Category::all();
        $json = [
            'path' => $path,
            'categorys'=>$categorys,
        ];
        JavaScript::put($json);
        return view('admin.shop.category.list',['brands'=>$brands]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        //$this->getAllCategoryList();
        $categorys = Category::all();
        $path = config('chaohun.admin_prefix');
        $json = [
            'path' => $path,
            'categorys'=>$categorys,
        ];
        JavaScript::put($json);

        return view('admin.shop.category.create');
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
        $category = $request->input('category');
        Log::info($category);
        //$maxPosObj = Category::orderBy('pos','desc')->first();
        $category['created_at'] = date('Y-m-d H:i:s');
        $category['updated_at'] = $category['created_at'];
        if($category['level']==""){
            $category['level']=0;
        }
        $category = Category::create($category);

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
        $categorys = Category::all();
        $path = config('chaohun.admin_prefix');
        $json = [
            'path' => $path,
            'categorys'=>$categorys,
        ];
        JavaScript::put($json);

        $id = (int) $id;
        $parentAttributeList = Attribute::where('shop_category_id',$id)->get();
        $category = Category::find($id);
        $categoryList = Category::all();
        $categorys = $categoryList;
        $path = config('chaohun.admin_prefix');
        $json = [
            'path' => $path,
            'categorys'=>$categorys,
            'category'=>$category,
        ];
        JavaScript::put($json);
        if(!empty($category)){
            return view('admin.shop.category.edit',['category'=>$category,'parentAttributeList'=>$parentAttributeList,"categoryList"=>$categoryList]);
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
        $newCategory = $request->input('category');
        $prveCategory = Category::where('id', (int) $id)->first();
        Log::info($newCategory);
        $prveCategoryLevel =(int) $prveCategory->level;
        $newCategoryLevel =(int) $newCategory['level'];
        $disLevel =(int) $newCategoryLevel - $prveCategoryLevel;
        Category::where('id', (int) $id)
            ->update($newCategory);
        if($disLevel!=0){
            $this->changeCategoryLevel($id, $disLevel);
        }
        return $this->edit($id);
    }

    public function changeCategoryLevel($pid,$disLevel){
        $categorys = Category::where('pid',(int) $pid)->get();
        foreach ($categorys as $key=>$value){
             $arr = array('id'=>$value->id,'pid'=>$value->pid,'level'=>((int) $value->level+$disLevel),'pos'=>$value->pos,
                'name'=>$value->name,'description'=>$value->description,'image'=>$value->image,
                'updated_at'=>date('Y-m-d H:i:s'));
            Category::where('id', (int) $value->id)->update($arr);
            $this->changeCategoryLevel($value->id, $disLevel);
        }
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
    public function getAllCategoryList(){
        $cats = Category::all();
        $lists = array();

        if(!empty($cats)){
            foreach ($cats as $key => $value){
                if(!array_key_exists($value->pid,$lists)){
                    $lists[$value->pid] = array(array('id'=>$value->id,'name'=>$value->name));
                }else{
                    array_push($lists[$value->pid], array('id'=>$value->id,'name'=>$value->name));
                }
            }
        }
    }

    public function listJson(Request $request){
        $draw = (int) $request->input('draw');
        $start = (int) $request->input('start');
        $length = (int) $request->input('length');
        $search =  $request->input('search');
        $arr=array();
        $attrs = Category::where("id",">",0)->where('level',0)->orderBy('id','desc')->skip($start)->take($length)->get();
        foreach ($attrs as $key=>$value){
            Log::info('哼哼哈嘿');
            $this->getTreeCategory($value,$arr);
        }

        $count = count($attrs);
        $json = ['recordsTotal' => $count,
            'recordsFiltered' => $count,
            'draw' => $draw,
            'data' => $arr];

         Log::info($arr);
        return response()->json($json);
    }
    public function getTreeCategory($category,&$arr){
        Log::info('名称'.$category->name);
        array_push($arr,$category);
       $categorys =  Category::where("pid",$category->id)->orderBy('id','desc')->get();
        $cnt = count($categorys);
        foreach ($categorys as $key=>$value){
            if($key+1 == $cnt){
                $value->islast = 1;
            }else{
                $value->islast = 0;
            }
            $this->getTreeCategory($value,$arr);
        }
    }
    public function getChildCategory(Request $request){
        $id = (int) $request->input('id');
        $categorys = Category::where("pid",$id)->orderBy('id','desc')->get();

        // Log::info(response()->json($json));
        return response()->json($categorys);
    }
    public function createCategory(Request $request){
        Log::info($request);
        $pos = $request->input('pos');
        $name = $request->input('name');
        $description = $request->input('description');
        $pid = (int) $request->input('pid');
        $bids = $request->input('bids');
        $parentCategory = Category::find($pid);
        if(!empty($parentCategory)){
            $level = (int) $parentCategory->level + 1;
            $category= array('pos'=>$pos,'name'=>$name,'level'=>$level,'description'=>$description,'pid'=>$pid);
        }else{
            $category= array('pos'=>$pos,'name'=>$name,'level'=>0,'description'=>$description,'pid'=>0);
        }
        $time = date('Y-m-d H:i:s');
        $category['created_at'] = $time;
        $category['updated_at'] = $time;
        Category::create($category);
        $category = Category::orderBy('updated_at','desc')->first();
        $attribute= array('pid'=>0,'shop_category_id'=>$category->id,'level'=>0,
            'name'=>'品牌','show_name'=>'品牌','required'=>1,'is_base'=>1,'is_sell'=>0,'is_optional'=>0,'status'=>1,'pos'=>1,'created_at'=>$time,'updated_at'=>$time);
        Attribute::create($attribute);
        if(count($bids)>0){
            foreach ($bids as $key=>$value){
                $brand = Brand::find($value);
                if(!empty($brand)){
                    $attribute = Attribute::orderBy('updated_at','desc')->first();
                    $attributeValue = array('shop_attribute_id'=>$attribute->id,'value'=>$brand->name,'status'=>1,'pos'=>1);
                    AttributeValue::create($attributeValue);
                }
            }
        }

        $categorys = Category::all();

        return response()->json($categorys);
    }
    public function delCategory(Request $request){
        $id =(int) $request->input('id');
        $value = 0;
        $message = "";
        $json = [];
        if($id>0){
            $this->delCategoryAndChild($id);
            $value = 0;
            $message = '删除分类成功';
        }else{
            $value = 1;
            $message = '无效ID:'.$id;
        }
        $json = ['value'=>$value,'message'=>$message];
        return response()->json($json);
    }
    public function delCategoryAndChild($id){
        $category = Category::find($id);
        if(!empty($category)){
            $categorys = Category::where('pid',$category->id)->get();
            foreach($categorys as $key=>$value){
                $this->delCategoryAndChild($value->id);
            }
            $category->delete();
        }
    }
}
