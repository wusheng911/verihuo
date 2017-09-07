<?php

namespace App\Http\Controllers\Admin;

use Log;
use Redirect;

use App\Models\Content;
use App\Models\ContentTag;
use App\Models\ContentCategory;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Jobs\GenerateContentTags;

use Illuminate\Http\Request;

class ContentTagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.contenttag.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
    }

    public function updateCategory(Request $request, $id)
    {
        $backtype =(int) $request['other']['backtype'];
        $cat_tags = $request->input('content_category_tags'); 
        try{
            $cat = ContentCategory::find((int)$id);
            $cat->tags()->sync($cat_tags);
            if($backtype==0){
                return Redirect::to(action('Admin\ContentTagController@index'));
            }else{
                return redirect()->action('Admin\ContentTagController@categoryIndex', [$id]);
            }
        }catch(\Exception $e){
            dd('33', $e);
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
        //
    }

    public function categoryIndex(Request $request, $id){
        $category = ContentCategory::find($id);
        $temp = $category->tags;
        $checkeds = $temp->reduce(function($c, $a){ return array_merge($c, [$a->id]); }, []);
        $tags = ContentTag::orderBy('name', 'asc')->get();
        foreach($tags as $tag){
            $tag->cgroup = strtoupper(pinyin_abbr($tag->name)[0]);
        }
        $alphabets = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $chars = str_split($alphabets);
        $catTags = [];
        foreach($chars as $char){
            $catTags[$char] = $tags->reduce(function($c, $a)use($char){
                if($a->cgroup == $char){
                    return array_merge($c, [$a]);
                }else{
                    return $c;
                }
            }, []);
        }
        return view('admin.contenttag.category', compact('category', 'tags', 'catTags', 'checkeds'));
    }

    /**
     * new content tag by ajax
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function ajaxNewTag(Request $request)
    {
        $json = [
            'status'=>false,
        ];
        $name = $request->input('name');
        $name = trim($name);
        if(empty($name)) {
            $json['message'] = '输入文本为空';
        }else{
            $json['newName'] = $name;
            if(!ContentTag::where(['name'=>$name])->first()){
                ContentTag::insert(['name'=>$name]);
                $json['status'] = true;
            }
            $json['status'] = true;
        }
        return response()->json($json);
    }

    public function ajaxUpdateTag(Request $request)
    {
        $json = [
            'status'=>false,
        ];
        $name = trim($request->input('name'));

        $id = (int) $request->input('id');
        $tag = ContentTag::find($id);
        if($tag){
            $tag->name = $name;
            $tag->save();
            $json['status'] = true;
            $json['newName'] = $name;
        }
        return response()->json($json);
    }

    public function ajaxDeleteTag(Request $request)
    {
        $id = (int) $request->input('id');
        $json = [
            'status'=>false,
        ];
        $tag = ContentTag::find($id);
        if($tag){
            $tag->delete();
            $json['status'] = true;
        }
        return response()->json($json);
    }

    public function ajaxReGenerateTags(Request $request){

        $json = [
            'status' => false,
        ];
        $contents = Content::get();
        foreach($contents as $content){
            dispatch(new GenerateContentTags($content));
        }
        $json['status'] = true;
        return response()->json($json);
    }


    public function listTagsHtml(Request $request){

        $tags = ContentTag::all();
        $text = $request->input('content');
        $inTags = [];
        foreach ($tags as $tag) {
            if (strpos($text, $tag->name) !== false) {
                $inTags[] = $tag;
            }
        }

        $id = $request->input('id');
        $content = Content::find((int) $id);
        if(!empty($content->tags)){
            foreach($content->tags as $i){
                foreach($inTags as $j) {
                    if($i->id == $j->id){
                        $j->checked = 1;
                    }
                }
            }
        }

        if(!empty($inTags)){
            return view()->make('admin.contenttag.listtags', ['tags'=> $inTags]);
        }else{
            return '<text class="text-red"> 没有可选的标签</text>';
        }
    }

    /**
     * list json by ajax
     *
     */
    public function listJson(Request $request){
        $draw = (int) $request->input('draw');
        $start = (int) $request->input('start');
        $length = (int) $request->input('length');
        $search =  $request->input('search');
        if(!empty($search['value'])){
            $skey = $search['value'];
            $tags = ContentTag::where('name', 'LIKE', "%$skey%")->orderBy('id', 'desc')->skip($start)->take($length)->get();
        }else{
            $tags = ContentTag::skip($start)->take($length)->orderBy('id','desc')->get();
        }

        $count = ContentTag::all()->count();
        $tmpContents = array();
        
        $json = ['recordsTotal' => $count,
                 'recordsFiltered' => $count,
                 'draw' => $draw,
                 'data' => $tags];

        return response()->json($json);
    }

    
    public function categoryListJson(Request $request){
        $draw = (int) $request->input('draw');
        $start = (int) $request->input('start');
        $length = (int) $request->input('length');
        $search =  $request->input('search');
        if(!empty($search['value'])){
            $skey = $search['value'];
            $cats = ContentCategory::where('title', 'LIKE', "%$skey%")->orderBy('id', 'desc')->skip($start)->take($length)->get();
        }else{
            $cats = ContentCategory::skip($start)->take($length)->orderBy('id','desc')->get();
        }

        $count = ContentCategory::all()->count();
        $tmpContents = array();
        
          
        foreach($cats as $cat){
            $a = $cat->tags;
        }

        $json = ['recordsTotal' => $count,
                 'recordsFiltered' => $count,
                 'draw' => $draw,
                 'data' => $cats];

        return response()->json($json);
    }
}
