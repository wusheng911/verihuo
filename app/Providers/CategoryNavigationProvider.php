<?php

namespace App\Providers;

use App\Http\Controllers\HomeController;

use Illuminate\Support\ServiceProvider;

class CategoryNavigationProvider extends ServiceProvider
{
	/**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

        // Register blade directives
        $this->bladeDirectives();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Register the blade directives
     *
     * @return void
     */
    private function bladeDirectives()
    {
    	//
        \Blade::directive('nav', function($expression) {
        	var_dump($expression);
        	$expression = substr($expression,1,strlen($expression)-2); 
        	$id =  $expression;
        	$data = HomeController::getNavData();
        	$data = array_reverse($data);
        	$index=0;
        	
        	$href = action('HomeController@gradeone',null);
        	$str = "<div class='nav-container'><div class='nav-icon'><a class='nav-a' href='/'><img src='/assets/img/nav-icon.jpg'></a></div>'";
        	foreach ($data as $key=>$value){
        		$aimid = $value['id'];
        		$str =$str."<a href='$href/$aimid' ><div id='nav-item-$key' onMouseOver='onMyMouseOver(this)'  onMouseOut='onMyMouseOut(this)' class='nav-item nav-item$index'>";
        		
        		$str =$str."<span class='nav-a nav-a$index'>".$value['name']."</span>";
        		
        		$tmpStr = "<div id='underline-$index' class='nav-underline nav-underline$index'></div>";
        		$str = $str."</div></a>";
        		$index++;
        	}
        	$str=$str."</div>";
        	$str = $str."<div id='sub-container' class='nav-sub'>";
        	foreach ($data as $key=>$value){
        		$input= "<input id='input$key' type='hidden' value='";
        		$childs = $value['childs'];
        		foreach ($childs as $childKey =>$childValue){
        			$str = $str."<div id='sub-item' class='nav-sub-item'>";
        			$name = $childValue['name'];
        			$path = $href."/".$childKey;
        			$str = $str."<a href='$path'>$name</a></div>";
        			$input= $input.$name.",";
        			$input= $input.$path.",";
        		}
        		$input = $input."'>";
        		$str= $str.$input;
        	}
        	$str = $str."</div>";
        	return $str;
        });
        
        \Blade::directive('endnav', function($expression) {
        });
    }
}