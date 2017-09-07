<?php

namespace App\Http\Controllers\Admin;

use DB;
use JavaScript;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class GeneralSettingController extends Controller
{

    /**
     * show system general settings
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $path = config('chaohun.admin_prefix');
        $json = [
            'path' => $path,
        ];
        JavaScript::put($json);
        $data = DB::table('settings')->lists('value','name');
        return view('admin.generalsetting.list',$data);
    }

    /**
     * save system general settings
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request) {
        $settings = $request->setting;
        $updateData = $createData = array();
        $settingName = DB::table('settings')->lists('name');
        $settingData = DB::table('settings')->lists('value','name');
        $i = 1;
        foreach ($settings as $key=>$value) {
            if(is_array($value)) $value = json_encode($value);
            if(in_array($key,$settingName)) {
                if($settingData[$key] != $value) $updateData[$key] = $value;
            } else {
                $createData[$i]['name'] = $key;
                $createData[$i]['value'] = $value;
                $createData[$i]['created_at'] = date('Y-m-d H:i:s');
                //$createData[$i]['updated_at'] = $createData[$i]['created_at'];
                $i++;
            }
        }
        if(count($updateData)) {
            foreach ($updateData as $key=>$value) {
                DB::table('settings')->where('name',$key)->update(array('value'=>$value,'updated_at'=>date('Y-m-d H:i:s')));
            }
        }
        DB::table('settings')->insert($createData);
        return redirect()->action('Admin\GeneralSettingController@index')->with('message', '配置保存成功！');
    }
}
