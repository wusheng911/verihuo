<?php

namespace App\Http\Controllers;

use Log;
use Auth;
use Carbon\Carbon;
use Exception;
use App\Models\Article;
use App\Models\Content;
use App\Models\ContentTag;
use App\Models\ContentCategory;
use App\Models\Node;
use App\Services\ApiClient;
use App\Services\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class ViewController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function article(Request $request, $id)
    {
        $api_client = new ApiClient();                
        $article = [];
        $related_articles = [];
        $comments = [];
        $article = Content::find((int) $id);
        
        if(empty($article) || $article->post_at > Carbon::now()){

            return abort(404, '文章不存在');
        }
        
        if ((intval($article['need_logined']) > 0) && Auth::guest()) {
            Helpers::setJumpUrl("/view/article/{$id}");
            return redirect('/customer/login')->with('promptMessage', 
                ['message_type' => config('constants.PROMPT_WARNING'), 'message' => '您需要先登录才能查看这篇文章!']);
        } 
        
        //导航
        $category_code = ContentCategory::getCategoryCodeById($article['content_category_id'], false);
                
        //increase view_count
        if (!$api_client->put('/api/articles/'.$id, ['view_count' => intval($article['view_count'])+1])) {
            Log::error("view_count updated failed with API Request: " . $api_client->getUrl());
        }
        
        //comments
        if ($api_client->get('/api/artcomments/loadbyarticle/' . $id)) {
            $comments = $api_client->getBody();
        } else {
            Log::error("get related comments failed with API Request: " . $api_client->getUrl());
        }
        
        //related items
        if ($api_client->get('/api/articles/related/'.$id)) {
            $related_articles = $api_client->getBody();
        } else {
            Log::error("Get related items failed with API Request: " . $api_client->getUrl());
        }
        
        $adpositions = [];
        $api_client = new ApiClient();
        if ($api_client->get('/api/adpositions?adcode=Mobile|Article|')) {
            $adpositions = $api_client->getBody();
        }     
        $nodes = Node::adPositionsToNodes($adpositions);  
        
        //文章图片过多时, 先显示部分内容              
        $display_content = $article['content']; 
        $rest_rate = 0;
        $position = $full_length = strlen($display_content);
        try {
            // 新建一个Dom实例
            $html = str_get_html($article['content']); 
            $image_tags = $html->find("img");
                        
            if (isset($image_tags) && count($image_tags) > (config('chaohun.art_loadmore_startpoint')+1)) {
                $node = $image_tags[intval(config('chaohun.art_loadmore_startpoint'))];
                while ($node->parentNode() != $html->root) {
                    $node = $node->parentNode();
                }
                $text = $node->outertext();            
                $pos_r = [];
                $offset = 0;                
                //get all matched positions
                while(true) {
                    $pos = strpos($article['content'], $text, $offset);
                    if ($pos > 0) {
                        $offset = $pos+1;
                        array_push($pos_r, $pos);
                    } else {
                        break;
                    }
                }
                                
                //set default position
                $position = (count($pos_r) > 0)?$pos_r[0]:$full_length;
                                
                //find a suitable position, which is > 25%
                if (count($pos_r) > 1) {
                    foreach($pos_r as $key => $pos) {
                        if ($key == end($pos_r)) {
                            $position = $pos;
                        } else {
                            if (intval($pos) > ($full_length * 0.25)) {
                                $position = $pos;
                                break;
                            } else {
                                continue;
                            }
                        }
                    } 
                }                
                //the rate of the rest content
                $rest_rate = round((intval($full_length - $position) * 100) / intval($full_length), 1);
                $display_content = substr($article['content'], 0, $position);
            }
        } catch(\Exception $ex) {
            Log::error("Failed to parse article $id with simple html dom, " . $ex->getMessage());
        }
        
        //解决显示文章的样式兼容性问题 adjust width attribute for img tag        
        $display_content=preg_replace('/(<img.+)(style=\".+\")(.+\/>)/i',"\${1} \${3}",$display_content);
        //adjust width attribute for video tag
        $display_content=preg_replace('/(<video.+)(width=\"(\d+|none)\")/i',"\${1} width='100%;'",$display_content);
        $article['content'] = $display_content;
        $article['rest_rate'] = $rest_rate;
        $article['position'] = $position;
            
        return view('mobile.article', ['article' => $article,
            'category_code' => $category_code, 
            'related_articles' => $related_articles, 
            'comments' => $comments,
            'nodes' => $nodes,
            'adpositions' => $adpositions]);        
    }

    public function articleForPc(Request $request, $id){

        $article = Content::find((int) $id);

        if(empty($article) || $article->post_at > Carbon::now()){
            return abort(404, '文章不存在');
        }

        $breadcrumb = collect([]);

        if ((intval($article['need_logined']) > 0) && Auth::guest()) {
            Helpers::setJumpUrl("/view/article/{$id}");
            return redirect('/customer/login')->with('promptMessage', 
                ['type' => config('constants.PROMPT_WARNING'), 'content' => '您需要先登录才能查看这篇文章!']);
        } 

        if($article->contentcategory){
            $breadcrumb = $article->contentcategory->getAncestors()->reverse();
        }
        $tags = ContentTag::limit(20)->get();
        $guesslikes = $this->guesslike($article);

        $adpositions = [];
        $api_client = new ApiClient();
        if ($api_client->get('/api/adpositions?adcode=PC|Article|')) {
            $adpositions = $api_client->getBody();
        }     
        $nodes = Node::adPositionsToNodes($adpositions);  

        // comments
        $article->comments = $article->comments()->orderby('id', 'desc')->get();
        if(!($request->hasCookie('user_ip'))) {
            $article->increment('view_count');
        }

        //同一ip访问同一文章在指定时间内只更新一次,默认值指定时间为1分钟
        $view_count_time = (config('settings.view_count_time') != null) ? config('settings.view_count_time') : 1;
        $response = new Response(view('pc.article', ['article' => $article,
                                                    'breadcrumb' => $breadcrumb,
                                                    'adpositions' => $adpositions,
                                                    'nodes' => $nodes,
                                                    'tags' => $tags,
                                                    'guesslikes' => $guesslikes]));
        $response->withCookie(cookie('user_ip', $request->ip(), $view_count_time));
        return $response;
    }

    /**
     * Display a listing of the resource.
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function artComments(Request $request, $id)
    {
        $api_client = new ApiClient();                
        $article = $comments = [];
        if ($api_client->get('/api/articles/'.$id)) {
            $article = $api_client->getBody();
        } 
        
        if (!isset($article['content'])) {
            throw new Exception("找不到文章!");
        }

        //导航
        $category_code = ContentCategory::getCategoryCodeById($article['content_category_id'], false);
                       
        //comments
        if ($api_client->get('/api/artcomments/loadbyarticle/' . $id)) {
            $comments = $api_client->getBody();
        } else {
            Log::error("get related comments failed with API Request: " . $api_client->getUrl());
        }
        
        return view('mobile.artComments', compact('article' ,'category_code', 'comments'));        
    }
    
    
    /***
     * 商品详情页
     * 
     */
    public function showProduct(Request $request, $id) 
    {  
        $product=$product_skus=$skus=$attributes=$category=$carts=null;
        $sku_count = 0;
        $api_client = new ApiClient();
        if ($api_client->get('/api/products/' . $id)) {
            $product = $api_client->getBody();   
            
            $api_client = new ApiClient();
            if ($api_client->get("/api/skus?product_id=$id")) {
                $product_skus = $api_client->getBody();
                if ($product_skus['skus']) {
                    foreach($product_skus['skus'] as $sku) {
                        $sku_count = $sku_count + intval($sku["quantity"]);
                    }
                }
                $skus=$product_skus['skus'];
                $shop_attrs = $product_skus['shop_attributes'];
            }
            
            $api_client = new ApiClient();
            if ($api_client->get("/api/shopcategories/{$product['shop_category_id']}")) {
                $category = $api_client->getBody();
            }            
        } else {
            $product = null;
        }
                
        return view('pc.shop.product', compact('product', 'skus', 'sku_count', 
            'shop_attrs', 'category'));         
    }
       
    
    /*
     * 根据tag匹配可能喜欢的文章
     */
    protected function guesslike($article){

        if(empty($article->tags)){
            return Content::limit(4)->get();
        }
        $ret = collect([]);
        foreach($article->tags as $tag){
            $ret = $ret->merge(Content::where('type',1)->where('title', 'like', "%{$tag->name}%")
                               ->where('id', '<>', $article->id)->get());
        }
        $get = collect([]);
        $ids = [$article->id];
        foreach($ret as $v){
            if(!in_array($v->id, $ids)){
                $ids[] = $v->id;
                $get->push($v);
            }
        }
        $ret = $get->shuffle()->slice(0, 4);
        if($ret->count() < 4){
            $ret = $ret->merge(Content::limit(4 - $ret->count())->get());
        }
        return $ret;
    }

    /*
     * 文章关键字搜索
     */
    public function tagSearchForPc($tag) {
        $adpositions = [];
        $api_client = new ApiClient();
        if ($api_client->get('/api/adpositions?adcode=PC|TagSearch')) {
            $adpositions = $api_client->getBody();
        }
        $nodes = Node::adPositionsToNodes($adpositions);
        $s_tag = trim($tag);
        $tags = ContentTag::find($s_tag);
        if(!empty($tags)) {
            $s_tag = $tags->name;
        }
        $tags = ContentTag::getGroupTags($s_tag);
        $articles = ContentTag::getArticles($s_tag);
        $adjacents=2;//省略号分页偏移的数量
        return view('pc.tagsearch',compact('s_tag','tags','articles','adjacents','nodes'));
    }

    /**
     * @return string
     */
    public function tagIdAjax($tag)
    {
        $s_tag = trim($tag);
        $tagcount = ContentTag::where('name', $s_tag)->count();
        $tags = ContentTag::where('name', $s_tag)->first();
        if($tagcount>0) {
            $s_tag = $tags->id;
        }
        return $s_tag;
    }
}
