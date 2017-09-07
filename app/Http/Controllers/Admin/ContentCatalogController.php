<?php

namespace App\Http\Controllers\Admin;

use DB;
use Log;
use File;
use stdClass;
use App\Http\Requests;
use App\Models\Content;
use App\Models\ContentCategory;
use App\Http\Controllers\ImageCrop;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Redirect;

class ContentCatalogController extends Controller
{
	//切割文章头图 4:3
	function cuttingImage(){
		$maxCnt = 100;
		$topPath='files/article';
		$lastItem = Content::where('image_4_3','<>','')->orderBy('id','desc')->first();
		$cnt =0;
		$conditionStr = 'image_4_3 等于 null';
		$otherType = array();
		$errorPath = array();
		$otherPath = array();
		if(empty($lastItem)){
			$firstItem = Content::where('id','>',0)->first();
		}else{
			$firstItem = Content::find(((int) $lastItem->id) +1 );
		}
		
		while (!empty($firstItem)){
            $skip = Content::where('id', '<', $firstItem->id)->count();
			$contents = Content::where([])->orderBy('id', 'asc')->skip($skip)->take($maxCnt)->get();
			foreach ($contents as $key =>$content){
				var_dump($content->id.'<br>');
				try {
					$imgPath = ltrim($content->image, '/');
					$imgPath4_3 = ltrim($content->image_4_3,'/');
					if(strpos($imgPath, $topPath)!==false){
						$tmpArr = explode(".",$imgPath);
						if(count($tmpArr)>1){
							$type = $tmpArr[count($tmpArr)-1];
							if($type == 'jpg' || $type == 'png'){
								if(file_exists($imgPath)){
									$imgSize = getimagesize($imgPath);
									$imgPath4_3 = str_replace('square','4_3',$imgPath);
									$aimW = (int) $imgSize[0];
									$aimH = (int) $imgSize[1];
									$aimW = (int) $aimW * 4 / 3;
									if($type == 'jpg'){
										$src_im = imagecreatefromjpeg($imgPath);
									}else if($type == 'png'){
										$src_im = imagecreatefrompng($imgPath);
									}else if($type == 'gif'){
										$src_im = imagecreatefromgif($imgPath);
									}
									$dst_im = imagecreatetruecolor($aimW,$aimH);
									$boo = imagecopyresized( $dst_im, $src_im, 0, 0, 0, (int) $aimH / 8,(int) $aimW * 4 / 3, $aimH, $aimW,(int) $aimH * 3 / 4 );
									if($boo){
										var_dump('ID为'.$content->id.'的文章4:3头图保存成功<br>');
										Log::info('ID为'.$content->id.'的文章4:3头图保存成功<br>');
										if($type == 'jpg'){
											$boo = imagejpeg($dst_im,$imgPath4_3);
										}else if($type == 'png'){
											$boo = imagepng($dst_im,$imgPath4_3);
										}else if($type == 'gif'){
											$boo = imagegif($dst_im,$imgPath4_3);
										}
										if($boo){
											$content->image_4_3 = '/'.$imgPath4_3;
											$content->save();
											$cnt++;
											var_dump('写入数据库成功，路径为'.$content->image_4_3.'<br>');
											Log::info('写入数据库成功，路径为'.$content->image_4_3.'<br>');
										}
									}
									imagedestroy($dst_im);
									imagedestroy($src_im);
								}else{
									$content->image_4_3 = '';
									$content->save();
									var_dump('**********目标文件不存在，或4:3格式图片已经存在，无法复制图片。文章ID'.$content->id.'路径'.$imgPath.'<br>');
									Log::info('**********目标文件不存在，或4:3格式图片已经存在，无法复制图片<br>');
								}
							}else{
								if(empty($otherType[$type])){
									$otherType[$type] = 1;
								}else{
									$otherType[$type]+=1;
								}
								$content->image_4_3 = $content->image;
								$content->save();
								Log::info('**********ID为'.$content->id.'的文章头图格式为'.$type.'.,程序无法处理。<br>');
								var_dump('**********ID为'.$content->id.'的文章头图格式为'.$type.'.,程序无法处理。<br>');
							}
						}else{
							if(count($tmpArr)>0){
								$content->image_4_3 = $content->image;
							}else{
								$content->image_4_3 = '';
							}
							$content->save();
							Log::info('**********ID为'.$content->id.'的文章头图路径错误，错误路径为：'.$imgPath.'<br>');
							var_dump('**********ID为'.$content->id.'的文章头图路径错误，错误路径为：'.$imgPath.'<br>');
							$errorPath[$content->id] = $content->image;
							
						}
					}else{
						$content->image_4_3 = $content->image;
						$content->save();
						Log::info('**********ID为'.$content->id.'的文章头图文件夹路径错误(不是在'.$topPath.'路径下)错误路径为：'.$imgPath.'<br>');
						var_dump('**********ID为'.$content->id.'的文章头图文件夹路径错误(不是在'.$topPath.'路径下)错误路径为：'.$imgPath.'<br>');
						$ohterPaht[$content->id] = $content->image;
					}
				} catch (Exception $e) {
					$content->image_4_3 = $content->image;
					$content->save();
					Log::info('!!!!!!!!!!ID为'.$content->id.'的文章切图过程中发生错误，错误为<br>'.$e);
					var_dump('!!!!!!!!!!ID为'.$content->id.'的文章切图过程中发生错误，错误为<br>'.$e);
				}
				$lastItem = $content;
			}
			//$lastItem = Content::where('image_4_3','<>','')->orderBy('id','desc')->first();
			$firstItem = Content::find(((int) $lastItem->id) +1);
		}
		Log::info('全部完成，共切图'.$cnt.'张，');
		var_dump('全部完成，共切图'.$cnt.'张，');
	}
	
	
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	$categoryData = ContentCatalogController::getCategoryIndex();
        return view('admin.contentcatalog.index',['categoryData'=>$categoryData]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $contentcategory = new ContentCategory();
        $categoryData = ContentCatalogController::getCategoryIndex();
       // return view()->share('categoryData',$categoryData);
        return view('admin.contentcatalog.create', ['contentcategory' => $contentcategory,'categoryData'=>$categoryData]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    	$contentcategory = ContentCategory::create($request->input('contentcategory'));
        return redirect()->action('Admin\ContentCatalogController@show', [$contentcategory->id]);
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
        $contentcategory = ContentCategory::find($id);
        if(empty($contentcategory)){
        	return Redirect::to(action('Admin\ContentCatalogController@index'));
        }
        if(!empty($contentcategory->pid) && $contentcategory->pid>0){
        	$parentCategory = ContentCategory::find($contentcategory->pid);
        }
        if(empty($parentCategory)){
        	$contentcategory->ptitle ='默认为顶级分类';
        }else{
        	$contentcategory->ptitle = $parentCategory->title;
        }
        $count = ContentCategory::where('pid', (int) $id)->count();
        $contentcategory->childrencount = $count;
        $categoryData = ContentCatalogController::getCategoryIndex();
        return view('admin.contentcatalog.show', ['contentcategory' => $contentcategory,'categoryData'=>$categoryData]);
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
        
        $contentcategory = ContentCategory::find($id);
        $parentCategory = ContentCategory::find($contentcategory->pid);
        if(empty($parentCategory)){
        	$contentcategory->ptitle ='默认为顶级分类';
        }else{
        	$contentcategory->ptitle = $parentCategory->title;
        }
        $count = ContentCategory::where('pid', (int) $id)->count();
        $contentcategory->childrencount = $count;
        $categoryData = ContentCatalogController::getCategoryIndex();
        return view('admin.contentcatalog.edit', ['contentcategory' => $contentcategory,'categoryData'=>$categoryData]);
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
    	 ContentCategory::where('id', (int) $id)
            ->update($request->input('contentcategory'));
         return Redirect::to(action('Admin\ContentCatalogController@show', ['id'=>$id]));
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
        $category = ContentCategory::find($id);
    	$category->delete();
    }
    
    public function childrenListJson(){
    	$id = $_GET['id'];
    	$count = ContentCategory::where('pid', (int) $id)->count();
    	if($count==0){
    		$this->destroy($id);
    	}
    	return $count;
    }
    public function getChildernCategorys(){
    	
    	$categorys =  ContentCategory::where('pid', (int) $_GET['id'])->get();
    	$rt = array();
    	foreach ($categorys as $key=>$value){
    		$rt[$key] = $value;
    	}
    	return response()->json($rt);
    }
    public function getParentCategorys(){
    	$arr = array(array($_GET['id']));
    	$rc = $this->getParentCategoryList((int) $_GET['id'],$arr);
    	return response()->json($rc);
    }
    public function getParentCategoryList($id,$arr = array()){
    	 $contentcategory = ContentCategory::find($id);
    	 if(!empty($contentcategory)){
    	 	 $parentCategory = ContentCategory::find((int) $contentcategory->pid);
    	 }
        
         if(!empty($parentCategory)){
         	array_push($arr[0], $contentcategory->pid);
         	$categorys =  ContentCategory::where('pid',$parentCategory->id)->get();
         	$categoryList = array();
    		foreach ($categorys as $key=>$value){
    			$categoryList[$key] = $value;
    		}
    		array_push($arr,$categoryList);
    		$parentCategoryId = (int) $parentCategory->id;
         if($parentCategoryId == $id){
    			$arr = array(array($_GET['id']));
    			$categorys =  ContentCategory::where('pid', 0)->get();
    			$categoryList = array();
    			foreach ($categorys as $key=>$value){
    				$categoryList[$key] = $value;
    			}
    			array_push($arr,$categoryList);
    		}else if($parentCategoryId !=0){
    			$arr = $this->getParentCategoryList($parentCategoryId,$arr);
    		}else{
    			$categorys =  ContentCategory::where('pid', 0)->get();
    			$categoryList = array();
    			foreach ($categorys as $key=>$value){
    				$categoryList[$key] = $value;
    			}
    			array_push($arr,$categoryList);
    		}
         }else{
         	$categorys =  ContentCategory::where('pid', 0)->get();
    			$categoryList = array();
    			foreach ($categorys as $key=>$value){
    				$categoryList[$key] = $value;
    			}
    			array_push($arr,$categoryList);
         }
    	return $arr;
    }
    
	
	//获取文章分类信息  
	static public function getCategoryIndex(){
		$totalCategory = array();
		$totalData = array();
		$firstCategory = ContentCategory::where('pid',0)->get();
		foreach ($firstCategory as $firstKey=>$firstValue){
			$firstId = $firstValue->id;
			$totalData[$firstValue->id] = array();
			$totalData[$firstValue->id]['title'] = $firstValue->title;
			$totalData[$firstValue->id]['href'] = action('Admin\ContentCatalogController@show', ['id'=>$firstId]);
			$totalCategory[$firstId] = array();
			$secondCategory = ContentCategory::where("pid",$firstId)->get();
			foreach ($secondCategory as $secondKey=>$secondValue){
				$secondId = $secondValue->id;
				$totalData[$secondValue->id] = array();
				$totalData[$secondValue->id]['title'] = $secondValue->title;
				$totalData[$secondValue->id]['href'] = action('Admin\ContentCatalogController@show', ['id'=>$secondId]);
				$totalCategory[$firstId][$secondId] = array();
				$thirdCategory = ContentCategory::where('pid',$secondId)->get();
				foreach ($thirdCategory as $thirdKey=>$thirdValue){
					$thirdId = $thirdValue->id;
					$totalData[$thirdValue->id] = array();
					$totalData[$thirdValue->id]['title'] = $thirdValue->title;
					$totalData[$thirdValue->id]['href'] = action('Admin\ContentCatalogController@show', ['id'=>$thirdId]);
					$totalCategory[$firstId][$secondId][$thirdId] = true;
				}
			}
		}
		return [$totalCategory,$totalData];
	}
	
	
}
