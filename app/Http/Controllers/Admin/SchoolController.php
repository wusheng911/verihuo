<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use File;
use JavaScript;
use App\Models\School;

use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;

use Excel;




use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SchoolController extends Controller
{
    //
    public function index()
    {
        //
        //define("admin", "admin");
        $path = config('constants.admin_prefix',"admin");
        $json = [
            'path' => $path,
        ];
        JavaScript::put($json);
        return view('admin.school.list');
    }

    public function create()
    {
        //
        return view('admin.school.edit');



    }



    public function store(Request $request)
    {
        //
        $school = $request->input('school');
        $userid = $request->input('userid');


        if(array_key_exists('type', $school)){
            if($school['type'] == '1'){
                $school['type'] = 1;
            }else{
                $school['type'] = 0;
            }
        }else{
            $school['type'] = 0;
        }

        DB::table('schools')->insert($school);

        if(!$school['logo']){

        }else{
        $id= DB::table('schools')->where('logo', $school['logo'])->first();

        $id=$id->id;
        $dir = '/files/school/'.$id;

        if(!File::exists(public_path(ltrim($dir, '/')))){
            File::makeDirectory(public_path(ltrim($dir, '/')), 0755, true);
        }


        $file=$school['logo'];//旧目录
        $array=explode('/',$file);
        $fileName=$array[4];

        $newFile='files/school/'.$id.'/'.$userid.$fileName; //新目录
        $oldfile=  'files/school/temp/'.$fileName;
        copy($oldfile,$newFile); //拷贝到新目录
        unlink($oldfile); //删除旧目录下的文件
        $logo='/'.$newFile;
        $arr=array('logo'=>$logo);
        School::where('id', $id)
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

        $school = School::where('id',">",0)->skip($start)->take($length)->get();

        $count = School::all()->count();


        $json = ['recordsTotal' => $count,
            'recordsFiltered' => $count,
            'draw' => $draw,
            'data' => $school];

        return response()->json($json);
    }




    public function edit($id)
    {
        //

        $id = (int) $id;

        $dir = '/files/school/'.$id;
        if(!File::exists(public_path(ltrim($dir, '/')))){
            File::makeDirectory(public_path(ltrim($dir, '/')), 0755, true);
        }


        if($id>0){
            $school = School::find($id);


            if(!empty($school)){
                return view('admin.school.edit',['school'=>$school]);
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

        $arr = $request->input('school');


        $userid = $request->input('userid');
        if(!$arr['logo']){

        }else{

            $oldPath = '';
            $school = School::find($id);


            $file=$arr['logo'];//旧目录




            if($school->logo !== $arr['logo']){



                $array=explode('/',$file);
                $fileName=$array[4];

                $newFile='files/school/'.$id.'/'.$userid.$fileName; //新目录
                $oldfile=  'files/school/temp/'.$fileName;

                copy($oldfile,$newFile); //拷贝到新目录
                unlink($oldfile); //删除旧目录下的文件
                $logo='/'.$newFile;
                $arr['logo']=$logo;

            }

        }



        if(array_key_exists('type', $arr)){
            if($arr['type'] == '1'){
                $arr['type'] = 1;
            }else{
                $arr['type'] = 0;
            }
        }else{
            $arr['type'] = 0;
        }




        School::where('id', (int) $id)
            ->update($arr);





        return redirect('/admin/school');
    }



    public function delete(Request $request, $id)
    {



        School::find($id)->delete();
        return redirect('/admin/school');

    }





    public function phpexcel(){

        if(strtoupper($_SERVER['REQUEST_METHOD'])=="POST"){
            $inputFileName = "files/school/temp/" . $_FILES["file"]["name"];
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
                $logo =(string)$objWorksheet->getCellByColumnAndRow(1,$cols)->getValue();//B
                $introduction =(string)$objWorksheet->getCellByColumnAndRow(2,$cols)->getValue();//C
                $type =(string)$objWorksheet->getCellByColumnAndRow(3,$cols)->getValue();//D
                $data_array = array("name"=>$name,"logo"=>$logo,"introduction"=>$introduction,"type"=>$type);
                //print_r($data_array);
                DB::table('schools')->insert($data_array);
            }
            return redirect('/admin/school');

        }

        return view('admin.school.phpexcel');
    }









}
