<?php

namespace App\Http\Controllers;

use Log;
use Auth;
use Exception;
use Illuminate\Http\Request;
use App\Customer;
use App\Models\Article;
use App\Models\Content;
use App\Models\ContentTag;
use App\Models\ContentComment;
use App\Models\ContentCategory;
use App\Services\Helpers;

class CommentController extends Controller
{
    public function ajaxAddComment(Request $request){
        // mock user TODO
        $json = [];
        $user = Helpers::getCurrentUser();
        Log::info($user);
        if(!empty($user)){
            $cc = new ContentComment();
            $text = trim($request->comment);
            $articleId = $request->articleId;
            $commentId = $request->commentId;
            $cc->user_id = $user['id'];
            if(!empty($text)){
                $cc->body = $text;
                if($articleId){
                    $cc->content_id = $articleId;
                    $json['url'] = url('/view/article/'.$articleId);
                    $json['status'] = 'success';
                }
                if($commentId){
                    $pc = ContentComment::find($commentId);
                    $cc->content_id = $pc->content_id;
                    $cc->pid = $commentId;
                    $json['status'] = 'success';
                    $json['url'] = url('/view/article/'.$pc->content_id);
                }
            }
            $cc->save();
        } else {
            $json['status'] = "need_login";
            $json['msg'] = "未登陆";
        }
        return response()->json($json);
    }

    public function ajaxThumbsupComment(Request $request){
        $id = $request->input('id');
        $json = ['status' => 'failed'];
        $cc = ContentComment::find($id);
        if($cc){
            $cc->increment('votes');
            $json['status'] = 'success';
            $json['votes'] = $cc->votes;
        }
        return response()->json($json);
    }

}
