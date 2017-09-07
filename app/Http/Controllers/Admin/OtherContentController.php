<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use Log;
use File;
use App\Models\Content;
use App\Models\ContentTag;
use App\Http\Requests;
use App\Models\ContentCategory;
use App\Http\Controllers\Controller;
use App\Jobs\GenerateContentTags;

class OtherContentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.othercontent.list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $content = new Content();
        return view('admin.othercontent.edit', ['content' => $content]);
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
        $content = $request->input('content');
        $content['post_at'] = date('Y-m-d H:i:s');
        $content = Content::create($content);

        dispatch(new GenerateContentTags($content));
        if($backtype==0){
            return redirect()->action('Admin\OtherContentController@index');
        }else{
            return redirect()->action('Admin\OtherContentController@edit', [$content->id]);
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
        return view('admin.othercontent.show', ['content' => $content]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $content = Content::find($id);
        return view('admin.othercontent.edit', ['content' => $content]);
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
        Content::where('id', (int) $id)
            ->update($inputs);
        //$this->saveCoverImage($inputs, $id);
        dispatch(new GenerateContentTags(Content::find((int) $id)));
        if($backtype==0){
            return redirect()->action('Admin\OtherContentController@index');
        }else{
            return redirect()->action('Admin\OtherContentController@edit', $id);;
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
        $aimType = 2;
        $draw = (int) $request->input('draw');
        $start = (int) $request->input('start');
        $length = (int) $request->input('length');
        $search =  $request->input('search');
        $sid = $request->input('sid');
        $stitle = $request->input('stitle');
        $stype = $request->input('stype');
        if(!empty($search['value'])){
            $skey = $search['value'];
            $contents = Content::where('type',$aimType)->where('title', 'LIKE', "%$skey%")->skip($start)->take($length)->get();
        }elseif(!empty($stitle)){
            $contents = Content::where('type',$aimType)->where('title', 'LIKE', "%$stitle%")->skip($start)->take($length)->get();
        }elseif(!empty($sid)){
            $contents = Content::where('type',$aimType)->where('id',$sid)->skip($start)->take($length)->get();
        }elseif(!empty($stype)){
                $contents = Content::where('type',$stype)->skip($start)->take($length)->get();
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
}
