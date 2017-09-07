<?php
/**
 * Created by PhpStorm.
 * User: wanghenshuai
 * Date: 16/10/18
 * Time: 上午10:20
 */

namespace App\Http\Controllers\Admin\Shop;

use App\Models\Shop\Attribute;
use App\Models\Shop\AttributeValue;
use App\Models\Shop\Category;
use App\Models\Shop\Sku;
use App\Models\Shop\SkuAttribute;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AttributeValueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.shop.attributevalue.list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $attributeList = Attribute::all();
        return view('admin.shop.attributevalue.edit',["attributeList"=>$attributeList]);
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
        $attributeValue = $request->input('attributeValue');
        $attributeValue['created_at'] = date('Y-m-d H:i:s');
        $attributeValue['updated_at'] = $attributeValue['created_at'];
        DB::table("shop_attribute_values")->insert($attributeValue);
        $attributeValue = DB::table("shop_attribute_values")->orderBy('created_at','desc')->first();
        return $this->edit($attributeValue->id);
    }
    public function addValue(Request $request){
        Log::info($request->input('shop_attribute_id'));
        $shopAttributeId = $request->input('shop_attribute_id');
        $pos = $request->input('pos');
        $value = $request->input('value');
        $status = $request->input('status');
        $time = date('Y-m-d H:i:s');
        $arr=array('value'=>$value,"pos"=>$pos,"shop_attribute_id"=>$shopAttributeId,"status"=>$status,'created_at'=>$time,'updated_at'=>$time);
        DB::table("shop_attribute_values")->insert($arr);
        return response()->json("添加属性值成功");
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

        $attributeValue = DB::table("shop_attribute_values")->where("id",$id)->first();
        if(!empty($attributeValue)){
            $attributeList = Attribute::all();
            return view('admin.shop.attributevalue.edit',['attributeValue'=>$attributeValue,"attributeList"=>$attributeList]);
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
        $attrs = DB::table("shop_attribute_values")->where("id",">",0)->skip($start)->take($length)->get();

        $count = count($attrs);
        // Log::info('count::'.$count);

        //$attrs = array_splice($attrs,$start,$length);

        $json = ['recordsTotal' => $count,
            'recordsFiltered' => $count,
            'draw' => $draw,
            'data' => $attrs];

        // Log::info(response()->json($json));
        return response()->json($json);
    }

    public function listJsonById(Request $request){
        $draw = (int) $request->input('draw');
        $start = (int) $request->input('start');
        $length = (int) $request->input('length');
        $search =  $request->input('search');
        $id = (int) $request->input('id');
            $attrs = AttributeValue::where("shop_attribute_id",$id)->orderBy('id','desc')->get();
        $count = count($attrs);
         Log::info("目标ID".$id);

        //$attrs = array_splice($attrs,$start,$length);

        $json = ['recordsTotal' => $count,
            'recordsFiltered' => $count,
            'draw' => $draw,
            'data' => $attrs];

        // Log::info(response()->json($json));
        return response()->json($json);
    }
    public function getAttributeValue(Request $request){
        Log::info('接收到数据'.$request);
        $id = (int) $request->input('id');
        $attributeValue = DB::table("shop_attribute_values")->where("id",$id)->first();
        if(!empty($attributeValue)){
            return response()->json($attributeValue);
        }
        return response()->json('未发现'.$id.'对应属性值');
    }
    public function editAttributeValue(Request $request){

        Log::info($request);
        $id = (int) $request->input('id');
        $pos = $request->input('pos');
        $value = $request->input('value');
        $status = $request->input('status');
        $time = date('Y-m-d H:i:s');
        $attributeValue = DB::table("shop_attribute_values")->where("id",$id)->first();
        if(!empty($attributeValue)){
            $arr=array('value'=>$value,"pos"=>$pos,"status"=>$status,'updated_at'=>$time);
            DB::table("shop_attribute_values")->where("id",$id)->update($arr);
            $aimAttributeId = $attributeValue->shop_attribute_id;
            return response()->json($aimAttributeId);
        }
        return response()->json("编辑属性失败");

    }
    public function delAttributeValue(Request $request){
        Log::info('删除分类属性');
        $id = (int) $request->input('id');
        $json =[];
        $value = 1;
        $message ='';
        if($id>0){
                $attrValue = AttributeValue::find($id);
                if(!empty($attrValue)){
                    $attrId = $attrValue->shop_attribute_id;
                    $attrCnt = Count(AttributeValue::where('shop_attribute_id',$attrId)->get());
                        if($attrCnt>1){
                            $attrValue->delete();
                            $value = 0;
                            $message = '删除属性值成功';
                        }else{
                            $value = 1;
                            $message = '不能删除最后一个属性值';
                        }
                }else{
                    $value = 1;
                    $message = '未发现ID:'.$id.'所对应的属性值';
                }
        }else{
            $value = 1;
            $message = '非法ID:'.$id;
        }
        $json=['value'=>$value,'message'=>$message];
        return response()->json($json);
    }
}