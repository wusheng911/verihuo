<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Services\ApiResponse;
use App\Models\Content;
use Exception;
use DB;
use Log;


class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $offset = $request->has('offset')?$request->input('offset'):0;
            $count = $request->has('count')?$request->input('count'):0;
            $contents = null;
            
            $query_str = "select id, content_category_id, subtitle, title, description, " .
                "image, image_4_3, need_logined, view_count, created_at, updated_at " . 
                "from contents order by id ";
            
            if (intval($count) > 0) {
                $query_str .= "limit ?, ?";
                $contents = DB::select($query_str, [$offset, $count]);
            } else {
                $contents = DB::select($query_str);
            }

            $response = ApiResponse::get(false, json_decode(json_encode($contents), True));           
        } catch (\Exception $ex) {
            $response = ApiResponse::get(true, ['message' => $ex->getMessage()]);
        }
        
        return $response;
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
    public function show(Request $request, $id)
    {     
        try {
            $article = Content::find($id);
                        
            if ($article == false) {
                $response = ApiResponse::get(true, ['message' => "Article $id Not Found."]);                  
            } else {         
                if ($request->has('pos')) {
                    $article->content = substr($article->content, $request->input('pos'));
                }
              
                $response = ApiResponse::get(false, $article->toArray());
            }           
        } catch (\Exception $ex) {
            $response = ApiResponse::get(true, ['message' => $ex->getMessage()]);            
        }
        
        return $response;
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
     * Update the specified resource in storage. Example:
     * curl -X PUT -d 'view_count=449' http://localhost/api/articles/474
     *
     * @param \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {      
        try {
            $article = Content::find($id);
            
            if ($request->input('view_count')) {
              $article->view_count = $request->input('view_count');
            }
            
            $article->save();
            $response = ApiResponse::get(false, ['message' => 'Article updated.']);
            
        } catch (Exception $ex) {
            $repsonse = ApiResponse::get(true, ['message' => $ex->getMessage()]);
        } 
        
        return $response;
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
    
    /**
     * Get the related Articles 
     * 
     * @param \Illuminate\Http\Request  $request
     * @param string $id article id
     */
    public function related(Request $request, $id) 
    {
        try {
            $offset = $request->has("offset")?$request->input("offset"):0;
            $count = $request->has("count")?$request->input("count"):Config('chaohun.art_related_count');
          
            $query = "select id, content_category_id, title, subtitle, description, " .
                "votes, type, image, image_4_3, view_count, show_author, post_at " .
                "from contents where id <> ? and content_category_id in " .
                "(select content_category_id from contents where id = ?) order by post_at desc limit ?, ?";

            $related_articles = DB::select($query, [$id, $id, $offset, $count]);
             
            $response = ApiResponse::get(false, $related_articles);
 
        } catch (Exception $ex) {
            $response = ApiResponse::get(true, ['message' => $ex->getMessage()]);
        }      
        return $response;
    }    
}
