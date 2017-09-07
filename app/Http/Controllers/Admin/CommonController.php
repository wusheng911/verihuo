<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
class CommonController extends Controller
{
	   


	public function upimg(){



		$file = Input::file('file');

		$fileTypes = array('image/gif','image/jpeg','image/pjpeg','image/png','image/jpg');
		if(in_array($file->getMimeType(),$fileTypes)) {
		
		if($file -> isValid()){ 
			$extension=$file->getClientOriginalExtension();
			$newName=date('YmdHis').'.'.$extension;
			$path= $file ->move('files/school/temp/',$newName);
			$filePath = '/files/school/temp/'.$newName;
			return $filePath;
		}

		}
		else {
			   	return '文件格式不合法';
		}


	}


    public function upimgteacher(){



        $file = Input::file('file');

        $fileTypes = array('image/gif','image/jpeg','image/pjpeg','image/png','image/jpg');
        if(in_array($file->getMimeType(),$fileTypes)) {

            if($file -> isValid()){
                $extension=$file->getClientOriginalExtension();
                $newName=date('YmdHis').'.'.$extension;
                $path= $file ->move('files/teacher/temp/',$newName);
                $filePath = '/files/teacher/temp/'.$newName;
                return $filePath;
            }

        }
        else {
            return '文件格式不合法';
        }


    }

    public function upimgcustomer(){



        $file = Input::file('file');

        $fileTypes = array('image/gif','image/jpeg','image/pjpeg','image/png','image/jpg');
        if(in_array($file->getMimeType(),$fileTypes)) {

            if($file -> isValid()){
                $extension=$file->getClientOriginalExtension();
                $newName=date('YmdHis').'.'.$extension;
                $path= $file ->move('files/customer/temp/',$newName);
                $filePath = '/files/customer/temp/'.$newName;
                return $filePath;
            }

        }
        else {
            return '文件格式不合法';
        }


    }


}
