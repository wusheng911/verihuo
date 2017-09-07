<?php

namespace App\Http\Controllers\Admin\Visit;

use JavaScript;
use Log;
use Illuminate\Http\Request;

use File;
use Carbon\Carbon;
use App\Services\Helpers;
use App\Models\Visitor;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class VisitorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$json = [
			'admin' => config('chaohun.admin_prefix','admin'),
            'sendCodeUrl' => '/'.config('chaohun.admin_prefix','admin').'/visit/visitor/sendcode'
		];
		JavaScript::put($json);
        return view('admin.visitor.list');
        //
    }
    /**
     * show the form for creating a new resource.
     *
     * @return \illuminate\http\response
     */
    public function create()
    {
		$json = [
			'admin' => config('chaohun.admin_prefix','admin'),
		];
		JavaScript::put($json);
        return view('admin.visitor.create');
        //
    }

    /**
     * store a newly created resource in storage.
     *
     * @param  \illuminate\http\request  $request
     * @return \illuminate\http\response
     */
    public function store(request $request)
    {
        $inputs = $request->input('visitor');
        if(array_key_exists('level',$inputs))
        {
            $inputs['level'] = 1;
        }else{
            $inputs['level'] = 0;
        }
        /* $inputs['code'] = $this->random(); */
        /* $inputs['code'] = ''; */
        $visitor = visitor::create($inputs);
        return $this->index();
        //
    }

    /**
     * display the specified resource.
     *
     * @param  int  $id
     * @return \illuminate\http\response
     */
    public function show($id)
    {
        //
    }

    /**
     * show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \illuminate\http\response
     */
    public function edit($id)
    {
        //
		$json = [
			'admin' => config('chaohun.admin_prefix','admin'),
		];
		JavaScript::put($json);
        $visitor = Visitor::find($id);
        return view('admin.visitor.edit',['visitor'=>$visitor]);
    }

    /**
     * update the specified resource in storage.
     *
     * @param  \illuminate\http\request  $request
     * @param  int  $id
     * @return \illuminate\http\response
     */
    public function update(request $request, $id)
    {
        //
        $inputs = $request->input('visitor');
        if(array_key_exists('level',$inputs))
        {
            $inputs['level'] = 1;
        }else{
            $inputs['level'] = 0;
        }
        $visitor = Visitor::find($id);
        if($visitor){
            $visitor->update($inputs);
        }
        /* return $this->index(); */
		$json = [
			'admin' => config('chaohun.admin_prefix','max'),
		];
		JavaScript::put($json);
        return redirect()->action('Admin\Visit\VisitorController@index');
    }

    /**
     * remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \illuminate\http\response
     */
    public function destroy($id)
    {
        $json = '删除成功';
        $visitor = Visitor::find($id);
        $visitor->delete();
        return response()->json($json);
    }

    public function sendCode() {
        $json=[
            'status' => 0,
            'msg' => ''
        ];
        $id = (int) request()->input('id');

        $visitor = Visitor::find($id);
        if($visitor){
            if(strlen($visitor->phone) == 11){
                $code = $visitor->code;
                list($rt, $msg) = $this->sendInviteCode($visitor);
                if($rt){
                    $visitor->update(['codesent_at' => Carbon::now()]);
                    $json['status'] = 1;
                }else{
                    $json['msg'] = $msg;
                }
            }
        }

        return response()->json($json);
    }
    
    public function random(){
        $num = rand(100000,999999);
        return $num;
    }
    public function listJson(request $request){
    	$draw = (int) $request->input('draw');
        $start = (int) $request->input('start');
        $length = (int) $request->input('length');
        $search =  $request->input('search');
        if(!empty($search['value'])){
            $skey = $search['value'];
            $visitors = Visitor::where('name', 'LIKE', "%$skey%")->get();
        }else{
            $visitors = Visitor::all();
        }
        $count = count($visitors);
        $tmpVisitors = array();
        
        foreach ($visitors as $key => $value){
            if($value->level == 1)
            {
                $value->level = '是';
            }else{
                $value->level = '';
            }

            $dir = '/files/visit/visitor/'.$value->id;
            $qr_i = $dir.'/qr_invitation.jpg';
            if(File::exists(public_path(ltrim($qr_i)))){
                $value->checked = true;
            }else{
                $value->checked = false;
            }
            $tmpVisitors[$key] = $value;
        }
        $tmpVisitors = array_reverse($tmpVisitors);
        $tmpVisitors = array_splice($tmpVisitors,$start,$length);

        $json = ['recordsTotal' => $count,
                 'recordsFiltered' => $count,
                 'draw' => $draw,
                 'data' => $tmpVisitors];
       // Log::info(response()->json($json));
        return response()->json($json);
    }
    public function createSingleCode(Request $request)
    {
        $tmpCodes = Visitor::pluck('code');
        $codes = array();
        foreach($tmpCodes as $key=>$value)
        {
            $codes[] = $value;
        }
        $str = $this->random();
        for($i=100000;$i<999999;$i++)
        {
            if(!in_array($str, $codes))
            {
                break;
            }
        }
        $json = $str;
        Log::info($json);
        return response()->json($json);
    }
    public function createCode(Request $request)
    {
        $tmpCodes = Visitor::pluck('code');
        $codes = array();
        foreach($tmpCodes as $key=>$value)
        {
            $codes[] = $value;
        }
        $visitors = Visitor::where('code','')->get();
        foreach($visitors as $key=>$value)
        {
           $str = $this->random();
            for($i=100000;$i<999999;$i++)
            {
                if(!in_array($str, $codes)){
                    array_push($codes, $str);
                    break;
                }
            }
           $value->code = $str;
           $value->save();
        }
        $json = 1;
        return response()->json($json);
    }
    public function uploadFilePage(Request $request)
    {
        return view('admin.visitor.loadfile'); 
    }
    public function uploadFile(Request $request)
    {
        if($_FILES["file"]["error"] > 0)
          {
              echo "Error: " . $_FILES["file"]["error"] . "<br />";
          }
        else
          {
            $file = fopen($_FILES["file"]["tmp_name"],'r');           
            $blank = '';
            for($i = 0;$i<0;$i++)
            {
                $blank = $blank."&nbsp";
            }
            if(!empty($file))
            {
                $datas = array();
                $datas = fgetcsv($file);
                fclose($file);
                $str = $datas[0];
                $str = mb_convert_encoding($str, 'UTF-8', 'gb18030');
                $datas = explode("\r", $str);
                if(count($datas)>0)
                {
                    array_shift($datas);
                }
                $successDatas=array();
                $errorDatas=array();
                $mession ="";

                foreach($datas as $key=>$value)
                {
                    $tmpStr = explode(";",$value);           
                    $name = array_key_exists("0",$tmpStr)? $tmpStr[0]:"";
                    $phone = array_key_exists("1",$tmpStr)? $tmpStr[1]:"";
                    $type = array_key_exists("2",$tmpStr)? $tmpStr[2]:"";
                    if($name == "" && $phone == "")
                    {
                        continue;
                    }
                    if($name == ""){
                        $name = $phone;
                    }
                    $visitorByPhone = Visitor::where('phone',$phone)->get();
                    $visitorByName = Visitor::where('name',$name)->get();

                    if(count($visitorByPhone)==0)
                    {
                        $visitor = new Visitor();
                        $visitor->name = $name;
                        $visitor->phone = $phone;
                        if($type == "Y")
                        {
                            $visitor->level = 1;
                        }
                        $visitor->save();
                        $successDatas[] = array('name'=>$visitor->name,'phone'=>$visitor->phone);
                    }else{
                        if(count($visitorByName) == 0)
                        {
                            $visitor = new Visitor();
                            $visitor->name = $name;
                            $visitor->phone = $phone;
                            if($type == "Y")
                            {
                                $visitor->level = 1;
                            }
                            $visitor->save();
                            $successDatas[] = array('name'=>$visitor->name,'phone'=>$visitor->phone);
                        }else{
                            $errorDatas[] = array('name'=>$name,'phone'=>$phone);
                        }
                    } 
                }
                
                if(count($errorDatas)!=0)
                {
                    $mession =$mession.'不能导入的数据'.count($errorDatas).'条,原因：已存在<br/>';
                    foreach($errorDatas as $ekey=>$evalue)
                    {
                        $mession = $mession.$evalue['name'].','.$blank.$evalue['phone'].'; <br/>';
                    }
                    $mession = $mession.'<br/><br/><br/>';
                }
                
                if(count($successDatas)>0)
                {
                    $mession =$mession.'成功导入的数据'.count($successDatas).'条<br/>';
                    foreach($successDatas as $skey=>$svalue)
                    {
                        $mession =$mession.$svalue['name'].','.$blank.$svalue['phone'].'; <br/>';
                    }
                }
            }else{
                var_dump('没找到文件');
                exit;
            }
            echo $mession;
          }
    }
    public function vaildVisitorCodeUnique(Request $request)
    {
        $code = trim($request->input('code'));
        $id = (int) $request->input('id');
        $value = 0;
        if($id>0)
        {
            if(Visitor::where('id', '<>', $id)->where('code', '=', $code)->count() < 1)
            {
                $value = 1;
            }
        }else{
            if(Visitor::where('code',$code)->count()<1)
            {
               $value = 1; 
            }
        }
        return response()->json($value);
    }

    public function down(Request $request)
    {
        $visitors = Visitor::all();
        $data = array("名称","电话","邀请码","是否VIP","简介","入场时间","创建时间","更新时间");
        $str ="";
        foreach($data as $key=>$value)
        {
            $str = $str . $value . "\t";
        }
        $str = $str . "\r";
        foreach($visitors as $key=>$value)
        {
            $isVip = "";
            if($value->level)
            {
               $isVip = '是'; 
            }

            $str = $str . $value->name . "\t";
            $str = $str . $value->phone . "\t";
            $str = $str . $value->code . "\t";
            $str = $str . $isVip . "\t";
            $str = $str . $value->intro . "\t";
            $str = $str . $value->entered_at . "\t";
            $str = $str . $value->created_at . "\t";
            $str = $str . $value->updated_at . "\t";
            $str = $str . "\r";
        }
          $filename = "427嘉宾信息";
          header("Content-type: text/csv");
          header('Cache-Control:must-revalidate,post-check=0,pre-check=0');    
          header("Content-Disposition: attachment; filename={$filename}.xls");
          header("Pragma: no-cache"); 
          header("Expires: 0"); 
          $this->outputCSV($str);
          exit;
    }

    public function outputCSV($data) {
        $outputBuffer = fopen("php://output", 'w');
        $data = iconv('utf-8', 'gbk', $data);
        fwrite($outputBuffer,$data);
        fclose($outputBuffer);
    }

    public function template(Request $request)
    {
          $str = "姓名"."\t"."电话"."\t"."是否VIP"."\r"."王立新"."\t"."13788954405"."\t"."是"."\r"."boyce"."\t"."12345678909"."\t";
          $filename = "427嘉宾信息模板";
          header("Content-type: text/csv");
          header('Cache-Control:must-revalidate,post-check=0,pre-check=0');    
          header("Content-Disposition: attachment; filename={$filename}.csv");
          header("Pragma: no-cache"); 
          header("Expires: 0"); 
          $this->outputCSV($str);
          exit;
    }

    protected function sendInviteCode($visitor) {
        $sms_name = "";
        $sms_passord = "";
        $md5_sms_password = "";

        $message = "您好{$visitor->name}，欢迎您参加《Party》。您的验证码为：{$visitor->code}，请点击 www.51chaohun.com/v427 输入验证码进行Check In。如您自驾前来，我们为您提供了两个备选停车地址：1.苗江路地下停车场（靠近当代艺术博物馆）2.南车站路585号";

        try {
            $result = [];
            $mobile_number = $visitor->phone;
            /* 获取API URL */
            $sms_url = "http://106.ihuyi.com/webservice/sms.php?method=Submit";
            //密码可以使用明文密码或使用32位MD5加密
            $post_arr['account'] = $sms_name;
            $post_arr['password'] = $md5_sms_password;
            $post_arr['mobile'] = $mobile_number;
            $post_arr['content'] = $message;
            $post_data = http_build_query($post_arr);

            $get = Helpers::post($post_data, $sms_url);
            $gets = Helpers::xmlToArray($get);

            if ($gets['SubmitResult']['code'] == 2) {
                //success
                $result = ['code' => 2,
                           'mobile_number' => $mobile_number,
                           'security_code' => time()];
                Log::info(var_export($result, true));

                // $post_arr['content'] = $message2;
                // $post_data = http_build_query($post_arr);
                // Helpers::post($post_data, $sms_url);
                return [true, '成功'];
            } else {
                //failed, return error message
                $result = ['msg' => $gets['SubmitResult']['msg']];
                Log::info(var_export($result, true));
                return [false, $result];
            } 
                
        } catch (Exception $ex) {
            Log::info(var_export($ex, true));
            return [false, '网关调用异常'];
        } 
    }
}
