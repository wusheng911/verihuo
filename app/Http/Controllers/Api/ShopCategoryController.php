<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Request;
use DB;
use App\Services\ApiResponse;
use App\Models\Shop\Category;
use Exception;


class ShopCategoryController extends Controller
{
    /**
     * Display a listing of the resource
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {       
        try {          
            $all = Category::select("*")->orderBy("pid", "asc")->orderBy("pos","asc")->get();
            $parents = $all->filter(function ($item) {
                return $item->pid == 0;
            });
            
            $parents = $parents->each(function ($item) use ($all) {
                $item->mobile_link = "/shop/category/{$item->id}";
                $item->child = $all->filter(function($subject) use ($item) {
                    return $subject->pid == $item->id;
                });
                $item->child->each(function($child_item) {
                    $child_item->mobile_link = "/shop/category/{$child_item->id}";
                });
            });
            $response = ApiResponse::get(false, $parents->toArray()); 
            
        } catch (Exception $ex) {
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
    public function show($id)
    {
        try {
            $categories = DB::table('shop_categories')
                ->where('id', '=', $id)
                ->orWhere(function ($query) use($id) {
                    $query->where('pid', '=', $id);
                })
                ->orderBy("pos","asc")
                ->get();
                              
                  
            $category = array_filter($categories, function ($item) use($id) {
                return $item->id == $id;
            });
            
            if (count($category) < 1) {
                throw new Exception("Can not find category by id: $id");
            }
            
            $category = array_shift($category);            
            $category->mobile_link = "/categories/{$id}";
            
            //if $id is a parent level category
            if (count($categories) > 1) {
                $category->child = array_filter($categories, function($item) use($id) {
                    return $item->pid == $id;
                });                
                
                foreach($category->child as $child) {
                    $child->mobile_link = "/categories/{$child->id}";
                }
            }
           
            $response = ApiResponse::get(false, json_decode(json_encode($category), True));           
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
}
