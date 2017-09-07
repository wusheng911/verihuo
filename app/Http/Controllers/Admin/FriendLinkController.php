<?php

namespace App\Http\Controllers\Admin;

use File;
use JavaScript;
use App\Models\FriendLink;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FriendLinkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $path = config('chaohun.admin_prefix');
        $json = [
            'path' => $path,
        ];
        JavaScript::put($json);
        return view('admin.friendlink.list');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.friendlink.edit');
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
        $friendlink = $request->input('friendlink');
        $friendlink = FriendLink::create($friendlink);
        $friendlink = FriendLink::orderBy('created_at','desc')->first();
        if(!empty($friendlink)){
            $id = $friendlink->id;
            if($friendlink->logo!=''){
                $newPath = $this->saveCoverImage($friendlink->logo,$id);
                $friendlink->logo = $newPath;
                $friendlink->save();
            }
        }
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

        $id = (int) $id;
        if($id>0){
            $friendLink = FriendLink::find($id);
            if(!empty($friendLink)){
                return view('admin.friendlink.edit',['friendLink'=>$friendLink]);
            }else{
                return $this->index();
            }
        }else{
            return $this->index();
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
        $arr = $request->input('friendlink');
        $oldPath = '';
        $friendLink = FriendLink::find($id);
        if(!empty($friendLink)){
            $oldPath = $friendLink->logo;
        }
        if(array_key_exists('status', $arr)){
            if($arr['status'] == 'on'){
                $arr['status'] = 1;
            }else{
                $arr['status'] = 0;
            }
        }else{
            $arr['status'] = 0;
        }
        FriendLink::where('id', (int) $id)
            ->update($arr);
        $friendLink = FriendLink::find($id);
        if(!empty($friendLink)){
            if($arr['logo']!=$oldPath){
                if($arr['logo']!=''){
                    $newPath = $this->saveCoverImage($friendLink->logo,$id);
                    $friendLink->logo = $newPath;
                    $friendLink->save();
                }
            }
        }
        return $this->index();
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

        Log::info('受到请求信息');
        $draw = (int) $request->input('draw');
        $start = (int) $request->input('start');
        $length = (int) $request->input('length');
        $search =  $request->input('search');

        $frinedLinks = FriendLink::where('id',">",0)->skip($start)->take($length)->orderBy('pos')->get();

        $count = FriendLink::all()->count();


        $json = ['recordsTotal' => $count,
            'recordsFiltered' => $count,
            'draw' => $draw,
            'data' => $frinedLinks];

        return response()->json($json);
    }

    /**
     * 复制上传的商品图片到对应文件夹里面，并返回该图片的路径
     *
     * @param string $path
     * @param int $id
     * @param string $device 设备类型
     * @return string
     */
    protected function saveCoverImage($path,$id){
        if(!file_exists(public_path($path))) return $path;
        $ext = File::extension($path);
        $name = File::name($path);
        $newName = 'cover_image_square_'.md5($name).'.'.$ext;
        $dir = '/files/friendlink/'.$id;
        $brand_image_path = $dir.'/'.$newName;
        if(!File::exists(public_path(ltrim($dir, '/')))){
            File::makeDirectory(public_path(ltrim($dir, '/')), 0755, true);
        }
        $match_files = File::glob(public_path('files/friendlink/'.$id.'/cover_image_square_*'));
        if($match_files !== false) {
            foreach($match_files as $file) {
                if(file_exists($file)){
                    File::delete($file);
                }
            }
        }
        //dd(public_path('files/product/'.$id.'/'.$newName));
        //dd(file_exists(public_path('files/product/'.$id.'/'.$newName)));
        if(!file_exists(public_path('/files/friendlink/'.$id.'/'.$newName))) File::copy(public_path(ltrim($path,'/')), public_path(ltrim('/files/friendlink/'.$id.'/'.$newName,'/')));
        return '/files/friendlink/'.$id.'/'.$newName;
    }

}
