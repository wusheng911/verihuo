<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use File;
use JavaScript;
use App\Models\VClass;

use App\Http\Requests;
use App\Http\Controllers\Controller;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ClassController extends Controller
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
        return view('admin.class.list');
    }

    public function create()
    {
        //
        return view('admin.class.edit');
    }



    public function store(Request $request)
    {
        //
        $class = $request->input('class');
        DB::table('classes')->insert($class);

        //print_r($class);

        return $this->index();


    }


    public function listJson(Request $request){

        Log::info('受到请求信息');
        $draw = (int) $request->input('draw');
        $start = (int) $request->input('start');
        $length = (int) $request->input('length');
        $search =  $request->input('search');

        $vclass = VClass::where('id',">",0)->skip($start)->take($length)->get();

        $count = VClass::all()->count();


        $json = ['recordsTotal' => $count,
            'recordsFiltered' => $count,
            'draw' => $draw,
            'data' => $vclass];

        return response()->json($json);
    }




    public function edit($id)
    {
        //

        $id = (int) $id;
        if($id>0){
            $class = VClass::find($id);


            if(!empty($class)){
                return view('admin.class.edit',['class'=>$class]);
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
        $arr = $request->input('class');
        $oldPath = '';
        $class = VClass::find($id);

        if(array_key_exists('status', $arr)){
            if($arr['status'] == 'on'){
                $arr['status'] = 1;
            }else{
                $arr['status'] = 0;
            }
        }else{
            $arr['status'] = 0;
        }

        VClass::where('id', (int) $id)
            ->update($arr);
        $class = VClass::find($id);

        return redirect('/admin/class');
    }



    public function delete(Request $request, $id)
    {



        VClass::find($id)->delete();
        return redirect('/admin/class');

    }






}
