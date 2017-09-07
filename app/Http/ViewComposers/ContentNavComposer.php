<?php
/**
 * Created by PhpStorm.
 * User: wanghenshuai
 * Date: 17/1/10
 * Time: 下午2:54
 */

namespace App\Http\ViewComposers;
use App\Models\Content;
use App\Services\ApiClient;
use App\Models\ContentTag;
use Illuminate\View\View;
use App\Models\AdPosition;
use App\Models\ContentCategory;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Api\CategoryController;

class ContentNavComposer
{
    public function __construct()
    {
    }

    public function compose(View $view)
    {
        $topId = 0;
        $maxSubItem = 20;
        $aid = 0 ;
        $articleId = 0;
        $path='';
        //dd(app('request'));
        $path = app()->request->path();
        if($path == '/'){
            $topId = 0;
            $aid = 0 ;
        }
	$articleTags = array();
        if(stripos($path,'news')!==false){
            $tmpArr = explode("/",$path);
            $categoryId = array_pop($tmpArr);
            $aid = (int) $categoryId;
            $homeController = new HomeController();
            $topId = $homeController->getTopCategoryId($aid);
        }
        if(stripos($path,'view/article')!==false){
            $tmpArr = explode("/",$path);
            $articleId = (int) array_pop($tmpArr);
            $articleCategoryId =0;
            $article = Content::find($articleId);

            if(!empty($article)){
                $articleCategoryId = (int) $article->content_category_id;
                $aid = $articleCategoryId;
 		$articleTags = $article->tags;
                $homeController = new HomeController();
                $topId = $homeController->getTopCategoryId($aid);
            }
        }
        if($topId == config('chaohun.art_parent_category_1')){
            $topId = 1;
        }elseif($topId == config('chaohun.art_parent_category_2')){
            $topId =2;
        }elseif($topId == config('chaohun.art_parent_category_3')){
            $topId = 3;
        }elseif($topId == config('chaohun.art_parent_category_4')){
            $topId = 4;
        }elseif($topId == config('chaohun.art_parent_category_5')){
            $topId = 5;
        }
        $category = ContentCategory::find($aid);
        $childs = array();
        $tags = array();
        $homeTags=array();
        if(!empty($category)){
            $objTags = $category->tags;

            foreach ($objTags as $key=>$value){
                $tags[] = array('name'=>$value->name,'id'=>$value->id);
            }
            $categorys = ContentCategory::where('pid',$aid)->get();
            if(!empty($categorys)){
                foreach ($categorys as $key => $value){
                    $childs[] = array('id'=>$value->id,'name'=>$value->title);
                }
            }
            if(count($tags)<$maxSubItem){
                $brotherCategorys = ContentCategory::where('pid',$category->pid)->get();
                if(!empty($brotherCategorys)){
                    foreach ($brotherCategorys as $brotherKey => $brotherValue){
                        if($brotherValue->id != $category->id){
                            $brotherTags = $brotherValue->tags;
                            if(!empty($brotherTags)){
                                foreach ($brotherTags as $tagKey => $tagValue){
                                    $tags[] = array('name'=>$tagValue->name,'id'=>$tagValue->id);
                                    if(count($tags)>=$maxSubItem){
                                        break 2;
                                    }
                                }
                            }

                        }
                    }
                }
            }
        }elseif ($aid == 0 || empty($category)){

            $ads = AdPosition::getAdPositions("PC|Home|Tag|A");
            $ads = json_decode(json_encode($ads), true);
            if($ads){
            // $api_clientesn = new ApiClient();
            // if($api_clientesn->get("/api/adpositions?adcode=PC|Home|Tag|A")){
            //     $ads = $api_clientesn->getBody();

                foreach ($ads as $key=>$value) {
                    $tmpAdCode = $value['adposition_code'];

                    $tmpAdCodeStrArr = explode("A",$tmpAdCode);
                    $tmpAdCodeId = (int) array_pop($tmpAdCodeStrArr);
                    $localAttributes = $value['nodes'][0]['localAttributes'];
                    if(!empty($localAttributes)){
                        if(array_key_exists('label',$localAttributes )&& array_key_exists('link',$localAttributes )){
                            $label = $localAttributes['label']['values'][1]['value'];
                            $link = $localAttributes['link']['values'][1]['value'];
                            $homeTags[$tmpAdCodeId] = array('name'=>$label,'link'=>$link);
                        }
                    }
                }
            }
            $tmpTags = ContentTag::where('id','<',$maxSubItem)->get();
            if(!empty($tmpTags)){
                foreach($tmpTags as $key=>$value){
                    $tags[] = array('name'=>$value->name,'id'=>$value->id);
                }
            }
        }
        $childCnt = count($childs);
        $contentNav = array('topId'=>$topId,'tags'=>$tags,'childs'=>$childs,'childCnt'=>$childCnt,'homeTags'=>$homeTags,'articleTags'=>$articleTags);
        $view->with('contentNav', $contentNav);
    }



}
