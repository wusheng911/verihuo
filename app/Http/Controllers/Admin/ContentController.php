<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use Log;
use File;
use JavaScript;
use App\Models\Content;
use App\Models\ContentTag;
use App\Http\Requests;
use App\Models\ContentCategory;
use App\Http\Controllers\Controller;
use App\Jobs\GenerateContentTags;

class ContentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contents = Content::all();
        return view('admin.content.list', ['contents' => $contents]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $content = new Content();
        return view('admin.content.edit', ['content' => $content]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    	$backtype =(int) $request['other']['backtype'];

        $inputs = $request->input('content');
        $inputTags = isset($inputs['tags']) ? $inputs['tags'] : [];
        unset($inputs['tags']);

        $content = Content::create($inputs);
        if($content->id){
            $content->tags()->sync($inputTags);
        }
        $this->saveCoverImage($content, $content->id);

        if($backtype==0){
        	return redirect()->action('Admin\ContentController@index');
        }else{
        	return redirect()->action('Admin\ContentController@edit', [$content->id]);
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $content = Content::find($id);
        return view('admin.content.show', ['content' => $content]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $json = [
            'contentid' => $id,
        ];
        JavaScript::put($json);
        $content = Content::find($id);
        return view('admin.content.edit', ['content' => $content]);
        //
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
        
        $backtype =(int) $request['other']['backtype'];
        $inputs = $request->input('content');
        $inputTags = isset($inputs['tags']) ? $inputs['tags'] : [];
        unset($inputs['tags']);
        $inputs['need_logined'] = isset($inputs['need_logined']) ? 1 : 0;

        $content = Content::find((int) $id);
        if($content){
            $content->update($inputs);
            $content->tags()->sync($inputTags);
        }

        $this->saveCoverImage($inputs, $id);
        if($backtype==0){
        	return redirect()->action('Admin\ContentController@index');
        }else{
            return redirect()->action('Admin\ContentController@edit', $id);;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $json = ['id'=>$id + 1];
        Log::info(response()->json($json));
        $content = Content::find($id);
        $content->delete();
        return response()->json($json);
        //
    }

    /**
     * list json by ajax
     *
     */
    public function listJson(Request $request){
        $aimType = 1;
        $draw = (int) $request->input('draw');
        $start = (int) $request->input('start');
        $length = (int) $request->input('length');
        $search =  $request->input('search');
        $sid = $request->input('sid');
        $stitle = $request->input('stitle');
        $scategory = $request->input('scategory');
        if(!empty($search['value'])){
            $skey = $search['value'];
            $contents = Content::where('type',$aimType)->where('title', 'LIKE', "%$skey%")->skip($start)->take($length)->get();
        }elseif(!empty($stitle)){
            $contents = Content::where('type',$aimType)->where('title', 'LIKE', "%$stitle%")->skip($start)->take($length)->get();
        }elseif(!empty($sid)){
            $contents = Content::where('type',$aimType)->where('id',$sid)->skip($start)->take($length)->get();
        }elseif(!empty($scategory)){
            $category = ContentCategory::where('title', 'LIKE', "%$scategory%")->first();
            if(!empty($category)){
                $pid = $category->id;
                $contents = Content::where('type',$aimType)->where('content_category_id',$pid)->skip($start)->take($length)->get();
            }else{
                $contents = Content::where('type',$aimType)->skip($start)->take($length)->get();
            }
            //$contents = Content::where('title', 'LIKE', "%$skey%")->skip($start)->take($length)->get();
        }else{
            $contents = Content::where('type',$aimType)->skip($start)->take($length)->orderBy('id','desc')->get();
        }

        $count = Content::all()->count();
        $tmpContents = array();
        
        //用pid暂存分类名称
        foreach ($contents as $key => $value){
            $value->tags;
            $pid = $value->content_category_id;
            $category = ContentCategory::find($pid);
            if(!empty($category)){
                $value->pid = $category->title;
            }else{
                $value->pid = '--';
            }
            $tmpContents[$key] = $value;
        }
        $json = ['recordsTotal' => $count,
                 'recordsFiltered' => $count,
                 'draw' => $draw,
                 'data' => $tmpContents];

        return response()->json($json);
    }

    public function getAllContents(){
        $contents = Content::all();
        return response()->json($contents);
    }
    protected function saveCoverImage($content, $id){
        if(empty($id)) return;
        
        $keys = ['image', 'image_4_3'];
        try{
            foreach($keys as $key){
                if(!empty($content[$key])){
                    $path = urldecode($content[$key]);
                    if(strpos($path, '/files/article/') !== false){
                        continue;
                    }
                    $ext = File::extension($path);
                    $name = File::name($path);
                    if($key == 'image'){
                        $newName = 'cover_image_square_'.md5($name).date('_Ymd_His').'.'.$ext;
                    }else{
                        $newName = 'cover_'.$key.'_'.md5($name).date('_Ymd_His').'.'.$ext;
                    }
                    $dir = '/files/article/'.$id;
                    $content[$key] = $dir.'/'.$newName;
                    if(!File::exists(public_path(ltrim($dir, '/')))){
                        File::makeDirectory(public_path(ltrim($dir, '/')), 0755, true);
                    }
                    // delete old image
                    if($key == 'image'){
                        $match_files = File::glob(public_path('files/article/'.$id.'/cover_image_square_*'));
                    } else {
                        $match_files = File::glob(public_path('files/article/'.$id.'/cover_'.$key.'*'));
                    }
                    if($match_files !== false) {
                        foreach($match_files as $file) {
                            if(file_exists($file))
                                File::delete($file);
                        }
                    }
                    File::copy(public_path(ltrim($path,'/')), public_path(ltrim($content[$key],'/')));
                    Content::where(['id'=>$id])->update([$key=>$content[$key]]);
                }
            }
        }catch(Exception $e){
            Log::error($e->message);
        }
    }
    protected function getArticlesListByName(Request $request){
        $name = $request->name;
        $key = (int) $name;
        if((string)$key == $name){
            $contents = Content::where('id',$key)->get();
        }else{
            $contents = Content::where('title', 'LIKE', "%$name%")->get();
        }
        
        return response()->json($contents);
    }
}
