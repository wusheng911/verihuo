<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\Content;
use App\Models\AdPosition;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		  
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
	public function article(Request $request, $id){
        $article = Content::find((int) $id);
		return view('pc.article',['article'=>$article]);
	}
	public function getArticleList(Request $request){
		$blogKvs = [];
        $kvs = AdPosition::getAdPositions("PC|Blog|KV");
        $blogKvs = json_decode(json_encode($kvs), true);

		$articleList = Content::where('id','>',0)->where('type','=',1)->paginate(5);
		$special = Content::where('id','>',0)->where('type','=',2)->get();
		return view('pc.blog',['datas'=>$articleList,'special'=>$special,'blogKvs'=>$blogKvs]);
	}
}
