<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\CategoryController;

use PhpParser\Node\Stmt\Label;

use App\Models\NodeAttributeType;

use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Services\ApiClient;
use App\Models\Node;
use App\Models\ContentCategory;
use App\Models\Content;
use DB;
use Illuminate\Support\Facades\Redirect;
use App\Models\ContentComment;

class ContentCategoryController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }
	
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {  
    	
    }

	public function news($id){
	    $tc1 = config('chaohun.art_parent_category_1');
        $tc2 = config('chaohun.art_parent_category_2');
        $tc3 = config('chaohun.art_parent_category_3');
        $tc4 = config('chaohun.art_parent_category_4');
    	$id = (int) $id;
        $categoryTitle ='';
        $categoryKeywords = '';
		$categoryDescription='';
        $validateId = DB::table('content_categories')
            ->where('id', $id)
            ->orWhere('pid', $id)
            ->get();
    	if(count($validateId)>0){
            $api_clientes = new ApiClient();
            $contentCategoryTops = array();
            $contentCategoryTopsNodes = array();

            $categoryLevel = 0;
    	    if($id==$tc1 || $id==$tc2 || $id==$tc3 || $id==$tc4){
                if($api_clientes->get('/api/adpositions?adcode=PC|News|A')){
                    $contentCategoryTops = $api_clientes->getBody();
                    if(!empty($contentCategoryTops)){
                        $contentCategoryTopsNodes = Node::adPositionsToNodes($contentCategoryTops);
                    }
                }
                $categoryLevel = 1;
                $categories = ContentCategory::where('pid',$id)->get();
                $tmpChildCategory = DB::table('content_categories')->where('pid',$id)->first();
				$tmpCategory = DB::table('content_categories')->where('id',$id)->first();
                if(!empty($tmpCategory)){
                    $categoryTitle = $tmpCategory->seo_title;
                    $categoryKeywords = $tmpCategory->seo_keywords;
					$categoryDescription = $tmpCategory->seo_description;
                }


                if(!empty($categories)) {
                    $topcats = array();
                    foreach ($categories as $category) {
                        $cg = array();
                        $cg['id'] = $category->id;
                        $cg['name'] = $category->title;
                        $cg['type'] = 'm-info-content m-info-content';
                        $topcats[] = $cg;
                    }
                    $homeC = new HomeController();
                    $catAds = $homeC->setCategoryAdposition($topcats);
                    $catAds = array_merge($catAds,$contentCategoryTops);
                    $nodes = Node::adPositionsToNodes($catAds);
                    $nodes = array_merge($nodes,$contentCategoryTopsNodes);
                    return view('pc.contentcategory.news', compact('nodes', 'topcats', 'id','categoryTitle','categoryKeywords','contentCategoryTops','categoryLevel','categoryDescription'));
                }
            } else {
                if($api_clientes->get('/api/adpositions?adcode=PC|News|B')){
                    $contentCategoryTops = $api_clientes->getBody();
                    if(!empty($contentCategoryTops)){
                        $contentCategoryTopsNodes = Node::adPositionsToNodes($contentCategoryTops);
                    }
                }
                $categoryLevel = 2;
                $tmpCategory = DB::table('content_categories')->where('id',$id)->first();
                if(!empty($tmpCategory)){
                    $categoryTitle=$tmpCategory->seo_title;
                    $categoryKeywords = $tmpCategory->seo_keywords;
					$categoryDescription = $tmpCategory->seo_description;

                }

                $category = ContentCategory::find($id);
                $cg = array();
                $cg['id']= $id;
                $cg['name'] = $category->title;
                $cg['type'] ='m-info-content m-info-content';
                $topcats[] = $cg;
                $homeC = new HomeController();
                $catAds = $homeC->setCategoryAdposition($topcats);
                $nodes = Node::adPositionsToNodes($catAds);
                $nodes = array_merge($nodes,$contentCategoryTopsNodes);
                $category_arcticle_row = (int)(config('settings.category_arcticle_row'));
                $row = isset($category_arcticle_row) ? $category_arcticle_row :1 ;
                $num = $row*5;
                $articlesDatas = Content::where('content_category_id',$id)->orderBy('post_at', 'desc')->paginate($num);
                $articles = $this->getActicles($articlesDatas);
                return view('pc.contentcategory.news',compact('nodes','topcats','id','articles','categoryTitle','categoryKeywords','contentCategoryTops','categoryLevel','categoryDescription'));
            }
        } else {
    	    return Redirect::to(action('HomeController@index'));
        }
    }

    /**
     * 分类下文章输出到页面之前部分数据处理
     *
     * @param resource
     * @return resource
     */
    public function getActicles($articles) {
        foreach ($articles as $key=>$article) {
            //文章发布时间
            $article->post_at = date('Y年m月d', strtotime($article->post_at));
            //文章评论
            $article->comment = ContentComment::where('content_id',$article->id)->count();
            //文章推荐
            $article->recommend = ContentComment::where('content_id',$article->id)->sum('votes');
        }
        return $articles;
    }
}
