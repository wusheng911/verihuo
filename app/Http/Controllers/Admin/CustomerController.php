<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use File;
use JavaScript;
use App\Models\Customer;

use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;

use Excel;




use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    //
    public function index()
    {
        //
        $path = config('constants.admin_prefix',"admin");
        $json = [
           'path' => $path,
       ];
       JavaScript::put($json);
        return view('admin.customer.list');


    }


    public function create()
    {
        //
        return view('admin.customer.edit');



    }



    public function store(Request $request)
    {
        //
        $customer = $request->input('customer');
        $userid = $request->input('userid');



        if(!$customer['portrait']){
            echo "<script>alert('头像不能为空');window.location.href='/admin/customer/create'</script>";
 return false;
 }
        $phone= DB::table('customers')->where('phone', $customer['phone'])->get();



        if($phone){
            echo "<script>alert('此电话号已经存在');window.location.href='/admin/customer/create'</script>";
            return false;
        }

        $email= DB::table('customers')->where('email', $customer['email'])->get();



        if($phone){
            echo "<script>alert('此email已经存在');window.location.href='/admin/customer/create'</script>";
            return false;
        }

       /* if(array_key_exists('level', $customer)){
            if($customer['level'] == 'on'){
                $customer['level'] = 1;
            }else{
                $customer['level'] = 0;
            }
        }else{
            $customer['level'] = 0;
        }*/

        DB::table('customers')->insert($customer);


        $id= DB::table('customers')->where('portrait', $customer['portrait'])->first();

        $id=$id->id;
        $dir = '/files/customer/'.$id;

        if(!File::exists(public_path(ltrim($dir, '/')))){
            File::makeDirectory(public_path(ltrim($dir, '/')), 0755, true);
        }


        $file=$customer['portrait'];//旧目录
        $array=explode('/',$file);
        $fileName=$array[4];

        $newFile='files/customer/'.$id.'/'.$userid.$fileName; //新目录
        $oldfile=  'files/customer/temp/'.$fileName;
        copy($oldfile,$newFile); //拷贝到新目录
        unlink($oldfile); //删除旧目录下的文件
        $portrait='/'.$newFile;
        $arr=array('portrait'=>$portrait);
        Customer::where('id', $id)
            ->update($arr);




        return $this->index();


    }


    public function listJson(Request $request){

        Log::info('受到请求信息');
        $draw = (int) $request->input('draw');
        $start = (int) $request->input('start');
        $length = (int) $request->input('length');
        $search =  $request->input('search');

        $customer = Customer::where('id',">",0)->skip($start)->take($length)->get();

        $count = Customer::all()->count();


        $json = ['recordsTotal' => $count,
            'recordsFiltered' => $count,
            'draw' => $draw,
            'data' => $customer];

        return response()->json($json);
    }




    public function edit($id)
    {
        //
        $id = (int) $id;
        $teachersmaster= DB::table('teachers')->where('level', '1')->orderBy('name', 'asc')->get();
        $teachers= DB::table('teachers')->where('level', '0')->orderBy('name', 'asc')->get();


        $teacher_customers=DB::table('teacher_customer')->where('customer_id', $id)->select('teacher_id')->get();
        $teacher_customer=array();
        foreach ($teacher_customers as $value){
            $teacher_customer[]=$value->teacher_id;
        }

        $customer_info1=DB::table('customer_info1')->where('customer_id', $id)->first();



/*

        $dir = '/files/customer/'.$id;
        if(!File::exists(public_path(ltrim($dir, '/')))){
            File::makeDirectory(public_path(ltrim($dir, '/')), 0755, true);
        }

*/
        if($id>0){
            $customer = Customer::find($id);



            if(!empty($customer)){
                return view('admin.customer.edit',['customer'=>$customer,'teachers'=>$teachers,'teachersmaster'=>$teachersmaster,'teacher_customer'=>$teacher_customer,'customer_info1'=>$customer_info1]);
            }else{
                return $this->index();
            }
        }else{
            return $this->index();
        }




    }





    public function update(Request $request, $id)
    {
        //
        $teacher_customerarr = $request->input('teacher_customer');
        print_r($teacher_customerarr);

        $customer_info1=DB::table('customer_info1')->where('customer_id', $id)->first();


        $arr = $request->input('customer');
        $arrinfo1 = $request->input('customer_info1');
       /* if(!$arr['portrait']){
            echo "<script>alert('portrait图片不能为空');history.go(-1);</script>";
            return false;
        }
        $oldPath = '';
        $customer = Customer::find($id);*/


       /*     if(array_key_exists('level', $arr)){
                  if($arr['level'] == 'on'){
                      $arr['level'] = 1;
                  }else{
                      $arr['level'] = 0;
                  }
              }else{
                  $arr['level'] = 0;
              }*/


      $customer = Customer::find($id);



/*

            $file=$arr['portrait'];//旧目录




        if($customer->portrait !== $arr['portrait']){



            $array=explode('/',$file);
            $fileName=$array[4];

            $newFile='files/customer/'.$id.'/'.$userid.$fileName; //新目录
            $oldfile=  'files/customer/temp/'.$fileName;

            copy($oldfile,$newFile); //拷贝到新目录
            unlink($oldfile); //删除旧目录下的文件
            $portrait='/'.$newFile;
            $arr['portrait']=$portrait;

            }
*/
        Customer::where('id', (int) $id)
            ->update($arr);
        if($customer_info1){
        DB::table('customer_info1')->where('customer_id', (int) $id)
            ->update($arrinfo1);
        }else{
            $arrinfo1['customer_id']=$id;
            DB::table('customer_info1')->insert($arrinfo1);
        }

        DB::table('teacher_customer')->where('customer_id', $id)->delete();
if($teacher_customerarr){
        foreach ($teacher_customerarr as $teacher_id){

            DB::table('teacher_customer')->insert(array('teacher_id'=>$teacher_id,'customer_id'=>$id));

        }
}


        return redirect('/admin/customer');
    }



    public function delete(Request $request, $id)
    {



        Customer::find($id)->delete();
        return redirect('/admin/customer');

    }





    public function phpexcel(){

        if(strtoupper($_SERVER['REQUEST_METHOD'])=="POST"){
            $inputFileName = "files/customer/temp/" . $_FILES["file"]["name"];
            echo $inputFileName;
            move_uploaded_file($_FILES["file"]["tmp_name"],$inputFileName);
            ini_set('memory_limit', '1024M');
            $objReader = \PHPExcel_IOFactory::createReader('Excel2007');
            $objPHPExcel = $objReader->load($inputFileName);
            $objWorksheet = $objPHPExcel->getActiveSheet();
            $highestRow= $objWorksheet->getHighestRow();
            $highestColumn = $objWorksheet->getHighestColumn();

            for($cols =2 ;$cols<=$highestRow;$cols++){
                $name =(string)$objWorksheet->getCellByColumnAndRow(0,$cols)->getValue();//A
                $portrait =(string)$objWorksheet->getCellByColumnAndRow(1,$cols)->getValue();//B
                $introduction =(string)$objWorksheet->getCellByColumnAndRow(2,$cols)->getValue();//C
                $type =(string)$objWorksheet->getCellByColumnAndRow(3,$cols)->getValue();//D
                $data_array = array("name"=>$name,"portrait"=>$portrait,"introduction"=>$introduction,"type"=>$type);
                //print_r($data_array);
                DB::table('customers')->insert($data_array);
            }
            return redirect('/admin/customer');

        }

        return view('admin.customer.phpexcel');
    }









}
