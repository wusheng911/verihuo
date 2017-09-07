<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use File;
use JavaScript;
use App\Models\Teacher;

use App\Http\Requests;
use Illuminate\Support\Facades\Input;
//use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Controller;

use Excel;




use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TeacherController extends Controller
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
        return view('admin.teacher.list');
    }

    public function create()
    {
        //
        return view('admin.teacher.edit');



    }



    public function store(Request $request)
    {
        //
        $teacher = $request->input('teacher');
        $userid = $request->input('userid');


        if($teacher['password']){
            $teacher['password']=bcrypt($teacher['password']);
        }else{
            unset($teacher['password']);

        }



     if(!$teacher['phone'])
    {unset($teacher['phone']);}
     else{
         $phone= DB::table('teachers')->where('phone', $teacher['phone'])->get();
         if($phone){
         echo "<script>alert('此电话号已经存在');window.history.go(-1)</script>";
            return false;
         }
        }




        if(!$teacher['email'])
        {echo "<script>alert('email不能为空');window.history.go(-1)</script>";}
        else{
            $email= DB::table('teachers')->where('email', $teacher['email'])->get();
            if($email){
            echo "<script>alert('此email已经存在');window.history.go(-1)</script>";
            return false;
            }
        }

        if(array_key_exists('level', $teacher)){
            if($teacher['level'] == 'on'){
                $teacher['level'] = 1;
            }else{
                $teacher['level'] = 0;
            }
        }else{
            $teacher['level'] = 0;
        }

        DB::table('teachers')->insert($teacher);

        if(!$teacher['portrait']){

        }else{
        $id= DB::table('teachers')->where('portrait', $teacher['portrait'])->first();

        $id=$id->id;
        $dir = '/files/teacher/'.$id;

        if(!File::exists(public_path(ltrim($dir, '/')))){
            File::makeDirectory(public_path(ltrim($dir, '/')), 0755, true);
        }


        $file=$teacher['portrait'];//旧目录
        $array=explode('/',$file);
        $fileName=$array[4];

        $newFile='files/teacher/'.$id.'/'.$userid.$fileName; //新目录
        $oldfile=  'files/teacher/temp/'.$fileName;
        copy($oldfile,$newFile); //拷贝到新目录
        unlink($oldfile); //删除旧目录下的文件
        $portrait='/'.$newFile;
        $arr=array('portrait'=>$portrait);
        Teacher::where('id', $id)
            ->update($arr);
        }



        return $this->index();


    }


    public function listJson(Request $request){

        Log::info('受到请求信息');
        $draw = (int) $request->input('draw');
        $start = (int) $request->input('start');
        $length = (int) $request->input('length');
        $search =  $request->input('search');

        $teacher = Teacher::where('id',">",0)->skip($start)->take($length)->get();

        $count = Teacher::all()->count();


        $json = ['recordsTotal' => $count,
            'recordsFiltered' => $count,
            'draw' => $draw,
            'data' => $teacher];

        return response()->json($json);
    }




    public function edit($id)
    {
        //

        $id = (int) $id;

        $dir = '/files/teacher/'.$id;
        if(!File::exists(public_path(ltrim($dir, '/')))){
            File::makeDirectory(public_path(ltrim($dir, '/')), 0755, true);
        }


        if($id>0){
            $teacher = Teacher::find($id);


            if(!empty($teacher)){
                return view('admin.teacher.edit',['teacher'=>$teacher]);
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
        $arr = $request->input('teacher');

        if($arr['password']){
            $arr['password']=bcrypt($arr['password']);
        }else{
            unset($arr['password']);

        }
        $userid = $request->input('userid');


        if(array_key_exists('level', $arr)){
            if($arr['level'] == 'on'){
                $arr['level'] = 1;
            }else{
                $arr['level'] = 0;
            }
        }else{
            $arr['level'] = 0;
        }


        if(!$arr['portrait']){

        }else{

        $oldPath = '';
        $teacher = Teacher::find($id);




        $teacher = Teacher::find($id);





            $file=$arr['portrait'];//旧目录




        if($teacher->portrait !== $arr['portrait']){



            $array=explode('/',$file);
            $fileName=$array[4];

            $newFile='files/teacher/'.$id.'/'.$userid.$fileName; //新目录
            $oldfile=  'files/teacher/temp/'.$fileName;

            copy($oldfile,$newFile); //拷贝到新目录
            unlink($oldfile); //删除旧目录下的文件
            $portrait='/'.$newFile;
            $arr['portrait']=$portrait;

            }

        }

        Teacher::where('id', (int) $id)
            ->update($arr);



        return redirect('/admin/teacher');
    }



    public function delete(Request $request, $id)
    {



        Teacher::find($id)->delete();
        return redirect('/admin/teacher');

    }





    public function phpexcel(){

        if(strtoupper($_SERVER['REQUEST_METHOD'])=="POST"){
            $inputFileName = "files/teacher/temp/" . $_FILES["file"]["name"];
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
                DB::table('teachers')->insert($data_array);
            }
            return redirect('/admin/teacher');

        }

        return view('admin.teacher.phpexcel');
    }









}
