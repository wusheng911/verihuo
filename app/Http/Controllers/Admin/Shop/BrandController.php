<?php

namespace App\Http\Controllers\Admin\Shop;

use App\Models\Shop\Brand;
use Illuminate\Http\Request;

use File;
use JavaScript;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $path = config('chaohun.admin_prefix');
        $json = [
            'path' => $path,
        ];
        JavaScript::put($json);
        return view('admin.shop.brand.list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.shop.brand.create');
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
        $brand = $request->input('brand');
        Brand::create($brand);
        $brand = Brand::orderBy('created_at','desc')->first();
        if(!empty($brand)){
            $id = $brand->id;
            if($brand->logo!=''){
                $newPath = $this->saveCoverImage($brand->logo,$id);
                $brand->logo = $newPath;
                $brand->save();
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
        
        $brand = Brand::find($id);
        if(!empty($brand)){
            return view('admin.shop.brand.edit',['brand'=>$brand]);
        }else{
            $this->index();
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
        $brand = Brand::find($id);
        $oldPath='';
        if(!empty($brand)){
            $oldPath = $brand->logo;
        }else{
            return $this->index();
        }
        $arr = $request->input('brand');
        Brand::where('id', (int) $id)
            ->update($request->input('brand'));
        $brand = Brand::find($id);
        if(!empty($brand)){
            if($arr['logo']!=$oldPath){
                if($arr['logo']!=''){
                    $newPath = $this->saveCoverImage($brand->logo, $id);
                    $brand->logo = $newPath;
                    $brand->save();
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
        $draw = (int) $request->input('draw');
        $start = (int) $request->input('start');
        $length = (int) $request->input('length');
        $search =  $request->input('search');
        $brands = Brand::where("id",">",0)->orderBy('id','desc')->skip($start)->take($length)->get();


        $count = count($brands);
        $json = ['recordsTotal' => $count,
            'recordsFiltered' => $count,
            'draw' => $draw,
            'data' => $brands];

        Log::info($brands);
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
        $newName = 'cover_image_square_'.md5($name).date('_Ymd_His').'.'.$ext;
        $dir = '/files/shop/brand/'.$id;
        $brand_image_path = $dir.'/'.$newName;
        if(!File::exists(public_path(ltrim($dir, '/')))){
            File::makeDirectory(public_path(ltrim($dir, '/')), 0755, true);
        }
        $match_files = File::glob(public_path('files/shop/brand/'.$id.'/cover_image_square_*'));
        if($match_files !== false) {
            foreach($match_files as $file) {
                if(file_exists($file)){
                    File::delete($file);
                }
            }
        }
        //dd(public_path('files/product/'.$id.'/'.$newName));
        //dd(file_exists(public_path('files/product/'.$id.'/'.$newName)));
        if(!file_exists(public_path('/files/shop/brand/'.$id.'/'.$newName))) File::copy(public_path(ltrim($path,'/')), public_path(ltrim('/files/shop/brand/'.$id.'/'.$newName,'/')));
        return '/files/shop/brand/'.$id.'/'.$newName;
    }
}
