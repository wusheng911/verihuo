<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ApiClient;
use App\Models\Node;
use App\Models\ContentCategory;
use App\Models\Content;
use DB;


class NewsController extends Controller
{
    public function __construct(Request $request)
    {
        $request->session()->put("platform", "news");
    }    
    
    /**
     * 首页
     * 
     * @return type
     */
    public function home(Request $request) 
    {
        $message_type = config('constants.PROMPT_INFO');
        $message = '';
        
        if ($request->session()->has('promptMessage')) {
            $message_type = $request->session()->get('promptMessage.message_type');
            $message = $request->session()->get('promptMessage.message');
        }        
      
        $adpositions = [];
        $api_client = new ApiClient();
        if ($api_client->get('/api/adpositions?adcode=Mobile|Home|')) {
            $adpositions = $api_client->getBody();
        }     
        $nodes = Node::adPositionsToNodes($adpositions);  
        
        //get queen category
        $queen_category = [];
        if ($api_client->get("/api/categories/" . config('chaohun.art_parent_category_5'))) {
            $queen_category = $api_client->getBody();
        }
        

        return view('mobile.index', compact('nodes', 'adpositions', 'queen_category', 'message_type', 'message'));        
    }
  
    /**
     * 
     * @param type $id Category or SubCategory Id
     * @return Response
     */
    public function news(Request $request, $id)
    {      
        if (empty($id)) {
            $id = '1';
        }
      
        //get category info
        $category = [];
        $api_client = new ApiClient();
        if ($api_client->get("/api/categories/$id")) {
            $category = $api_client->getBody();
        }
        $newsCategory = ContentCategory::find($id); 
        $seoDescription = "";
        $seoKeywords = "";
        $seoTitle = "";
        if(!empty($newsCategory))
        {
            $seoDescription = $newsCategory->seo_description;
            $seoKeywords = $newsCategory->seo_keywords;
            $seoTitle = $newsCategory->seo_title;
        }
        $adpositions = [];
        $api_client = new ApiClient();
        if ($api_client->get('/api/adpositions?adcode=Mobile|News|')) {
            $adpositions = $api_client->getBody();
        }     
        $nodes = Node::adPositionsToNodes($adpositions);         
                
        if (count($category) > 0) {
            if ((intval($category['pid']) > 0) ||
              !isset($category['child']) || (count($category['child']) == 0)) {
              
                $sort = $request->has("sort")?$request->input("sort"):"post_at";
              
                $contents = DB::table('contents')
                    ->select(['id', 'content_category_id', 'subtitle', 'title', 'description', 'image', 'image_4_3'])
                    ->where('content_category_id', '=', $id)
                    ->orderBy($sort, 'desc')
                    ->paginate(config('chaohun.art_list_count'));

                $status = [
                    "latest" => '',
                    'hot' => ''
                ];
                switch($sort) {
                    case "post_at":
                        $status["latest"] = "selected";
                        break;
                    case "view_count":
                        $status["hot"] = "selected";
                        break;
                    default:
                        ;
                } 
                                
                return view('mobile.newsMore', ['category' => $category, 
                    'contents' => ($contents)?$contents:[], 
                    'sort' => $sort, 
                    'status' => $status,
                    'nodes' => $nodes,
                    'adpositions' => $adpositions,
                    'seoTitle' => $seoTitle,
                    'seoKeywords' => $seoKeywords,
                    'seoDescription' => $seoDescription]);
            }
        }
                
        return view('mobile.news', compact('nodes', 'category', 'adpositions','seoDescription','seoTitle','seoKeywords'));
    }
}
