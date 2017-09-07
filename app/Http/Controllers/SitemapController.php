<?php

/**
 * This is the controller to render sitemap or generate sitemap xml file.
 * It uses "RoumenDamianoff/laravel-sitemap" plugin to create sitemap file.
 * Please do not update this module and please do not run following command:
 * php artisan vendor:publish --provider="Roumen\Sitemap\SitemapServiceProvider"
 * Since in order to support baidu sitemap format we made some change to the xml.php,
 * run the above command will overwrite customer code.
 * 
 * Author: JiaJun
 */


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ApiClient;
use App\Models\ContentTag;
use Carbon;
use App;
use URL;


class SitemapController extends Controller
{    
    public static function buildSitemap()
    {
        // create new sitemap object
        $sitemap = App::make("sitemap");
                
        //add home page
        $sitemap->add(URL::to("/"), Carbon\Carbon::now()->toW3cString(), '1.0', 'always');
        
        //get article category info
        $categories = [];
        $api_client = new ApiClient();
        if ($api_client->get("/api/categories")) {
            $categories = $api_client->getBody();
        }
        
        //add article categories
        foreach($categories as $parent_cat) {
            $sitemap->add(URL::to("/news/{$parent_cat['id']}"), Carbon\Carbon::now()->toW3cString(), '0.8', 'daily');
            if (isset($parent_cat['child'])) {
                foreach ($parent_cat['child'] as $child_cat) {
                    $sitemap->add(URL::to("/news/{$child_cat['id']}"), Carbon\Carbon::now()->toW3cString(), '0.8', 'daily');
                }
            }
        }
        
        //add tags
        $content_tags = ContentTag::all();
        foreach($content_tags as $content_tag) {
            $sitemap->add(URL::to("/content/searchbytag/{$content_tag->id}"), Carbon\Carbon::now()->toW3cString(), '0.9', 'hourly');           
        }
        
        //add articles
        $articles = [];
        $api_client = new ApiClient();
        if ($api_client->get("/api/articles")) {
            $articles = $api_client->getBody();
        }        
       
        // add articles to the sitemap (url, date, priority, freq)
        foreach($articles as $article) {
            $updated_at = new Carbon\Carbon($article['updated_at']);
            $article['updated_at'] = $updated_at->toW3cString();
            $sitemap->add(URL::to("/view/article/{$article['id']}"), $article['updated_at'], '0.8', 'daily');
        }
        
        return $sitemap;
              
    }
    
    
    public function render(Request $request) 
    {
        $sitemap = self::buildSitemap();
        return $sitemap->render('xml');
    }
    
    public function store(Request $request)
    {
        $sitemap = self::buildSitemap();
        $sitemap->model->setUseStyles(false);
        $sitemap->store('xml', 'sitemap');  
        return "SUCCESS";
    }
    
    
}