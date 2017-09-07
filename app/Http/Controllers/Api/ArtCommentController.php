<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Services\ApiResponse;
use App\Models\ContentComment;
use Exception;
use DB;

class ArtCommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * Example: curl -X POST -d 'pid=2&article_id=2&user_id=1&content=超赞的文章!' http://localhost/api/artcomments
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {      
        try {
            $comment = new ContentComment;

            if ($request->has('pid')) {
                $pid = $request->input('pid');
                
                $parent_comment = ContentComment::find($pid);
                
                if ($parent_comment) {
                    if ($request->input('user_id') == $parent_comment->user_id) {
                        throw new Exception("您不能回复自己的评论!");
                    } else {
                        $comment->pid = $pid;
                    }
                }
            }

            $comment->content_id = $request->input('article_id');
            $comment->user_id = $request->input('user_id');
            $comment->body = $request->input('content');

            $comment->save();    
            
            return $this->show($comment->id);
          
        } catch (Exception $ex) {
            $response = ApiResponse::get(true, ['message' => $ex->getMessage()]);
            return $response;
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
        try {            
            $query = "select a.*, getParentList(a.pid) as 'pid_list', b.user_name " .
                "from content_comments a left join customers b on a.user_id=b.id where a.id=? ";

            $comments = DB::select($query, [$id]);
                        
            if ($comments) {
                $comment = array_shift($comments);
              
                $parent_ids = isset($comment->pid_list) ? explode(',', $comment->pid_list) : [];

                if (count($parent_ids) > 0) {
                    $query = "select a.*, b.user_name from content_comments a left join customers b " .
                      "on a.user_id = b.id where a.id in (" . 
                        implode(',', $parent_ids) . ")";

                    $parent_comments = DB::select($query);                   
                    $this->loadParent($comment, $parent_comments);                                      
                }
                
                //this flag tell front-end whether the current comment is the last comment
                $comment->is_last = $this->isLastComment($comment, $comment->content_id);
            } 
            $response = ApiResponse::get(false, (array) $comment);
        } catch (Exception $ex) {
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
     * Update the specified resource in storage.
     * curl -X PUT -d 'votes=449' http://localhost/api/artcomments/474
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $comment = ContentComment::find($id);
            
            if ($request->input('votes')) {
              $comment->votes = $request->input('votes');
            }
            
            $comment->save();
            $response = ApiResponse::get(false, $comment->toArray());
            
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
     * Load related comments by article id
     * 
     * @param string $id article id
     */    
    public function loadByArticle(Request $request, $id) 
    {
        try {
            $offset = $request->has("offset")?$request->input("offset"):0;
            $count = $request->has("count")?$request->input("count"):Config('chaohun.art_comment_count');
            
            $query = "select a.*, getParentList(a.pid) as 'pid_list', b.user_name " .
                "from content_comments a left join customers b on a.user_id=b.id where a.content_id=? " . 
                "order by a.created_at desc limit ?, ?";

            $comments = DB::select($query, [$id, $offset, $count]);
            
            if ($comments) {
                $parent_ids = [];
                
                //get total comment count for this article
                $total_count = ContentComment::where('content_id', $id)->count();


                foreach($comments as $comment) {
                    $comment->total_count = $total_count;
                    if ($comment->pid_list) {
                        $parent_ids = array_merge($parent_ids, explode(',', $comment->pid_list));
                    }
                }

                if (count($parent_ids) > 0) {
                    $query = "select a.*, b.user_name from content_comments a left join customers b " .
                        "on a.user_id=b.id where a.id in (" . 
                        implode(',', array_unique($parent_ids)) . ")";

                    $parent_comments = DB::select($query);

                    foreach($comments as $comment) {
                        $this->loadParent($comment, $parent_comments);
                    }                  
                }
                
                //this flag tell front-end whether the current comment is the last comment
                $end_comment = end($comments);
                $end_comment->is_last = $this->isLastComment($end_comment, $id);
            } 
            $response = ApiResponse::get(false, $comments);
        } catch (Exception $ex) {
            $response = ApiResponse::get(true, ['message' => $ex->getMessage()]);
        }   
        
        return $response;              
    }
    
    /**
     * A recursive function to fill parents for comment
     * 
     * @param \stdClass $comment
     * @param array $parent_comments
     * 
     * @return array comment
     */
    private function loadParent(\stdClass & $comment, array $parent_comments) 
    {
        if (intval($comment->pid) > 0) {
            $pid = $comment->pid;
            $parent_container = array_filter($parent_comments, 
                function($v) use ($pid) { return $v->id == $pid;}
            );

            $comment->parent = array_pop($parent_container);
            
            if ($comment->parent) {
                $this->loadParent($comment->parent, $parent_comments);
            }  
            return $comment;
        }        
        
        return $comment;
    }
    
    /**
     * Check if the comment is the last comment of the article
     * 
     * @param \stdClass $comment the comment model
     * @param integer $content_id the article id
     */
    private function isLastComment(\stdClass $comment, $content_id)
    {
        if (isset($comment->content_id) && $content_id > 0)
        {
            //look for last comment
            $query = "select * from content_comments where content_id=? order by created_at asc limit 1";
            $last_comment = DB::select($query, [$content_id]);
            
            if ($comment->id == $last_comment[0]->id) {
                return true;
            }   
        }
        
        return false;
    }
    
   
}
