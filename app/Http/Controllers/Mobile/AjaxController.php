<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Shop\Cart;
use App\Models\Shop\Sku;
use Illuminate\Http\Request;
use App\Services\ApiClient;
use Log;
use DB;


class AjaxController extends Controller
{
    public function loadArticle(Request $request, $id) 
    {        
        $params = [];
        if ($request->has('pos')) {
            $params['pos'] = $request->input('pos');
        }
        
        $query = http_build_query($params);
        $query = empty($query)?"":"?$query";
        $api_client = new ApiClient();
        if ($api_client->get("/api/articles/{$id}{$query}")) {
            $article = $api_client->getBody();
            
            //解决显示文章的样式兼容性问题 adjust width attribute for img tag        
            $replaced_content=preg_replace('/(<img.+)(style=\".+\")(.+\/>)/i',"\${1} \${3}",$article['content']);
            //adjust width attribute for video tag
            $replaced_content=preg_replace('/(<video.+)(width=\"(\d+|none)\")/i',"\${1} width='100%;'",$replaced_content);
            $article['content'] = $replaced_content;
        } else {
            Log::error("AJAX load article failed with request: /api/article/{$id}{$query}");
            return response()->json(['error' => '无法加载文章!'], 500);
        }

        return $article;
    }
  
    /**
     * 加载更多评论
     * 
     * @return type
     */
    public function loadComments(Request $request, $id) 
    {
        $offset = $request->has("offset")?$request->input("offset"):Config("chaohun.art_comment_count");
        $count  = $request->has("count")?$request->input("count"):Config("chaohun.art_comment_count");
      
        $options = [
            "offset" => $offset,
            "count" => $count
        ];
        
        $comments = [];
        
        $api_client = new ApiClient();
        if ($api_client->get("/api/artcomments/loadbyarticle/{$id}?offset={$offset}&count={$count}")) {
            $comments = $api_client->getBody();
        } else {
            Log::error("AJAX load comments failed with request:", $api_client->getUrl());
            return response()->json(['error' => '无法加载更多评论!'], 500);
        }

        return view('mobile.elements.artCommentsMore', compact('comments'));        
    }
    
    /**
     * 发表评论或回复评论
     * example: curl -X POST -d 'pid=2&user_id=1&content=非常不错的文章啊!' http://localhost/m/artcomment/2
     * 
     * @param string $id article id
     * @return string HTML for an article comment
     * 
     */
    public function addComment(Request $request, $id) 
    {
        $options = [];        
        if ($request->has('pid')) {
            $options['pid'] = $request->has('pid')?$request->input('pid'):null;
        }      
        
        $options['user_id'] = $request->has('user_id')?$request->input('user_id'):null;
        $options['content'] = $request->has('content')?$request->input('content'):"";
        $options['article_id'] = $id;
        
        $api_client = new ApiClient();
        
        $comment = [];
      
        if (!$api_client->post('/api/artcomments', $options)) {
            Log::error("Create Comment failed with API Request: " . $api_client->getUrl());
            return response()->json(['error' => '增加评论失败!'], 500);
        } else {
            $comment = $api_client->getBody();
        }
        
        return view('mobile.elements.artComment', compact('comment'));         
    }  
    
    /**
    * 获取点赞总数保存到数据库
    * example: curl -X POST -d 'votes=0' http://localhost/m/ajax/updateartcomment/2
    * 
    * @param string $id article id
    * @return string HTML for an article comment
    * 
    */    
    public function updateArtComment(Request $request, $id) 
    { 
        $comment = [];
        $options = [];
        
        if ($request->has('votes')) {
            $options['votes'] = $request->input('votes');
        }      
                
        $api_client = new ApiClient();
        
        if (!$api_client->put("/api/artcomments/$id", $options)) {
            Log::error("Update artComment failed with API Request: " . $api_client->getUrl());
            return response()->json(['error' => '更新评论失败!'], 500);
        } else {
            $comment = $api_client->getBody();
        }        

        return $comment;     
    }
    
    /***
     * 删除购物车
     * 
     * example: curl -X DELETE http://localhost/m/ajax/deletecarts/1,2,3
     */
    public function deleteCartItems(Request $request, $ids)
    {   
        $total_quantity = $request->session()->get("Customer.cart_quantity");
        $api_client = new ApiClient();

        if (!$api_client->delete("/api/carts/" . $ids)) {
            Log::error("Delete cart items failed with API Request " . $api_client->getUrl() . ":" . $api_client->getErrorMessage());
            return response()->json(['error' => '删除购物车失败!'], 500);
        } else {
            $delete_count = $api_client->getBody();
            
            //更新session中的购物车货品数量
            $total_quantity = $total_quantity - $delete_count[0];
            $request->session()->put("Customer.cart_quantity", ($total_quantity >=0)?$total_quantity:0);
        }    
        
        return $request->session()->get("Customer.cart_quantity");
    } 
    
    /**
     * 增加或减少购物车中的商品数量, URL中包含2个参数:
     * $id: cart_id 购物车id
     * $quantity: 新的quantity数量
     * 
     * @param Request $request
     * @return type
     */
    public function changeCartQuantity(Request $request, $id){
        $quantity = $request->has('quantity')?$request->input('quantity'):null;
        
        $quantity_diff = 0;
        $options = [];
        
        if ($quantity > 0) {
            $options['quantity'] = $quantity;
        }      
                
        $api_client = new ApiClient();
        
        if (!$api_client->put("/api/carts/$id", $options)) {
            Log::error("Update Cart failed with API Request " . $api_client->getUrl() . ":" . $api_client->getErrorMessage());
            return response()->json(['error' => $api_client->getErrorMessage()], 500);
        } else {
            $result = $api_client->getBody();
            $quantity_diff = $result[0];    
        }             
        
        if ($quantity_diff != 0) {
            $total_quantity = $request->session()->get("Customer.cart_quantity");
            //更新session中的购物车货品数量
            $total_quantity = $total_quantity + $quantity_diff;
            $request->session()->put("Customer.cart_quantity", ($total_quantity >=0)?$total_quantity:0);  
            return $request->session()->get("Customer.cart_quantity");
        } else {
            return 0;
        }
    } 
    
    /**
     * 获取地区
     * 
     * @return type
     */
    public function getZones(Request $request) 
    {
        $type = $request->has("type")?$request->input("type"):null;
        $parent_id = $request->has("parent_id")?$request->input("parent_id"):null;

        $options = [
            "type" => $type,
            "parent_id" => $parent_id
        ];
                      
        $result = "";
        $zones = [];
        
        $api_client = new ApiClient();
        if ($api_client->get("/api/addresses/getzones?type={$type}&parent_id={$parent_id}")) {
            $zones = $api_client->getBody();
                        
            foreach($zones as $zone) {
                $zone_id = isset($zone["zone_id"])?$zone["zone_id"]:"";
                $result .= "<li data-id=\"{$zone["id"]}\" data-type=\"{$type}\" data-zoneid=\"{$zone_id}\">{$zone["name"]}</li>";
            }
        } else {
            Log::error("AJAX load zones failed with request " . $api_client->getUrl() . ":" . $api_client->getErrorMessage());
            return response()->json(['error' => '无法加载地区！'], 500);
        }
        
        return $result;        
    } 
        
    /***
     * 下单时选择一个地址, 存入session，页面返回时直接从session中调用
     * 
     * @return string 如果成功返回id, 如果失败返回null
     */
    public function pickAddress(Request $request)
    {
        $id = $request->has("id")?$request->input("id"):null;
        if ($id) {
            $order = $request->session()->get("Order");            
            if ($order && count($order) > 0) {
                $request->session()->put("Order.address_id", $id);
                return $id;
            }
        }
        
        return null;
    }
    
    /**
     * 判断交易是否完成（用户是否付款）如果有未完成的订单，则返回0,交易成功则返回1
     * 
     * @param Request $request
     * @param type $ids
     * 
     * @return string 如果有未完成的订单，则返回0,交易成功则返回1
     */
    public function isOrderCompleted(Request $request)
    {         
        $ids = $request->has('ids')?$request->input('ids'):'';
        $user_id = $request->has('user_id')?$request->input('user_id'):'';        
        
        
        if (!empty($ids) && !empty($user_id)) {
            $api_client = new ApiClient();

            if (!$api_client->get("/api/orders?ids={$ids}&user_id={$user_id}")) {
                Log::error("check order complete failed with API Request " . $api_client->getUrl() . ":" . $api_client->getErrorMessage());
                return response()->json(['error' => '验证订单状态失败!'], 500);
            } else {
                $orders = $api_client->getBody();

                if (!empty($orders)) {
                    foreach($orders as $order) {
                        if ($order["paid_status"] != 2) {
                            return "0";
                        }
                    }
                    return "1";
                }                
            } 
        }
        
        return "0";        
    }
        
/**
     * 添加新地址
     * Example: curl -X POST -d 'zone_id=383&user_id=15&consignee_name=max&consignee_phone=16324256347
     * &details=%E4%B8%AD%E5%B1%B1%E4%B8%9C%E8%B7%AF111%E5%8F%B7&is_default=0' http://localhost/m/ajax/addaddress
     * 
     * @param string $id article id
     * @return string HTML for an article comment
     * 
     */
    public function addAddress(Request $request) 
    {        
        $options = [];        
        $options['zone_id'] = $request->has('zone_id')?$request->input('zone_id'):null;
        $options['user_id'] = $request->has('user_id')?$request->input('user_id'):null;
        $options['consignee_name'] = $request->has('consignee_name')?$request->input('consignee_name'):"";
        $options['consignee_phone'] = $request->has('consignee_phone')?$request->input('consignee_phone'):"";
        $options['details'] = $request->has('details')?$request->input('details'):"";
        $options['is_default'] = $request->has('is_default')?$request->input('is_default'):0;
        
        $api_client = new ApiClient();
        $address = [];
      
        if (!$api_client->post('/api/addresses', $options)) {
            Log::error("Create Comment failed with API Request " . $api_client->getUrl() . ":" . $api_client->getErrorMessage());
            return response()->json(['error' => '增加地址失败!'], 500);
        } else {
            $address = $api_client->getBody();
        }
        
        return $address;         
    } 
    
    /**
     * 更新地址
     * Example: curl -X POST -d 'zone_id=2&user_id=1&consignee_name=abc&
     * consignee_phone=123&details=xyz&is_default=1' http://localhost/m/ajax/updateaddress/$id
     * 
     * @param string $id article id
     * @return string HTML for an article comment
     * 
     */
    public function updateAddress(Request $request, $id) 
    {        
        $options = [];       
        $options['zone_id'] = $request->has('zone_id')?$request->input('zone_id'):null;
        $options['user_id'] = $request->has('user_id')?$request->input('user_id'):null;
        $options['consignee_name'] = $request->has('consignee_name')?$request->input('consignee_name'):"";
        $options['consignee_phone'] = $request->has('consignee_phone')?$request->input('consignee_phone'):"";
        $options['details'] = $request->has('details')?$request->input('details'):"";
        $options['is_default'] = $request->has('is_default')?$request->input('is_default'):0;
        
        $api_client = new ApiClient();        
        $address = [];
      
        if (!$api_client->put("/api/addresses/{$id}", $options)) {
            Log::error("Create Comment failed with API Request " . $api_client->getUrl() . ":" . $api_client->getErrorMessage());
            return response()->json(['error' => '更新地址失败!'], 500);
        } else {
            $address = $api_client->getBody();
        }
        
        return $address;         
    }  
    
    /***
     * 删除地址
     * 
     * example: curl -X DELETE http://localhost/m/ajax/deleteaddresses/1,2,3
     */
    public function deleteAddresses(Request $request, $ids)
    {   
        $api_client = new ApiClient();

        if (!$api_client->delete("/api/addresses/" . $ids)) {
            Log::error("Add to cart failed with API Request " . $api_client->getUrl() . ":" . $api_client->getErrorMessage());
            return response()->json(['error' => '删除地址失败!'], 500);
        } else {
            $delete_ids = $api_client->getBody();
        }    
        
        return $delete_ids;
    }        
    
    
}