<?php

/* 
 * 前端调用API RestFul的接口类. 
 * 
 * 
 */

namespace App\Services;

use GuzzleHttp;
use Log;

class ApiClient 
{ 
  /***
   * The domain name of api server
   * 
   * @var string
   */
  private $apiServer;
  
  /**
   * The real http client be used.
   * 
   * @var GuzzleHttp\Client 
   * 
   */
  private $client;
  
  /**
   * The response is sucess or failed 
   * 
   * @var bool 
   * 
   */
  private $success;
  
  /**
   * The status code of response
   * 
   * @var integer
   */
  private $statusCode;
  
  /**
   * The response url 
   * 
   * @var string  
   * 
   */
  private $url;
  
  
  /**
   * The response content
   * 
   * @var array 
   */
  private $body = array();
  
  /**
   * Decode the response
   * 
   * @param string $result The original json response in following structure 
   * array (
   *    'error' => 'true'/'false',   响应成功还是出错
   *    'results' = > $result,       响应的内容, 如果出错,则输出 array('message' => $message), $message包含了错误信息
   *    'url' => $url                当前请求的url
   * ) 
   */
  private function analyzeResponse($result) {
    
      $this->statusCode = intval($result->getStatusCode());
      
      if ($this->statusCode == 200) {
        
          $body = $result->getBody();
          
          $res = json_decode($body, true);
                     
          $this->success = ($res['error'] == false)?true:false;
          $this->url = $res['url'];
          $this->body = $res['results'];              
      } else {
          $this->success = false;
      }
  }
  
  /***
   * Get the real response content 
   * 
   * @return array The result of the response content
   */
  public function getBody() {
      return $this->body;
  }
  
  /***
   * Get the request url
   * 
   * @return string The request url
   */
  public function getUrl() {
      return $this->url;
  }
  
  /**
   * 构造函数
   * 
   */
  public function __construct() 
  {
      $this->client = new GuzzleHttp\Client();
      
      $this->apiServer = Config('chaohun.api_host');
  }
    
  public function get($uri, array $options = []) 
  {
      $str = (stripos($uri, '/') === 0)?$this->apiServer . $uri : $uri;
      Log::info("APIClient GET Request::{$str}, Options:" . PHP_EOL . print_r($options, true));          
      $result = $this->client->get($str, $options);
      Log::info("APIClient GET Response Status:: " . $result->getStatusCode());      
      $this->analyzeResponse($result);
        
      return $this->success;
  }

  public function head($uri, array $options = []) 
  {
      $str = (stripos($uri, '/') === 0)?$this->apiServer . $uri : $uri;
      return $this->client->head($str, $options);
  }
  
  public function put($uri, array $options = []) 
  { 
      $str = (stripos($uri, '/') === 0)?$this->apiServer . $uri : $uri;
      
      Log::info("APIClient PUT Request::{$str}, Options:" . PHP_EOL . print_r(['form_params' => $options], true));
      $result = $this->client->put($str, ['form_params' => $options]);
      Log::info("APIClient PUT Response Status:: " . $result->getStatusCode());
      Log::info("APIClient PUT Response Body::" . PHP_EOL . print_r((array) $result->getBody(), true));
      $this->analyzeResponse($result);
      return $this->success;    
  }
  
  public function post($uri, array $options = []) 
  {
      $str = (stripos($uri, '/') === 0)?$this->apiServer . $uri : $uri;
      Log::info("APIClient POST Request::{$str}, Options:" . PHP_EOL . print_r(['form_params' => $options], true));
      $result = $this->client->post($str, ['form_params' => $options]);
      Log::info("APIClient POST Response Status:: " . $result->getStatusCode());
      Log::info("APIClient POST Response Body::" . PHP_EOL . print_r((array) $result->getBody(), true));
      $this->analyzeResponse($result);
        
      return $this->success;  
  }
  
  public function patch($uri, array $options = []) 
  {
      $str = (stripos($uri, '/') === 0)?$this->apiServer . $uri : $uri;
      return $this->client->patch($str, $options);
  }
  
  public function delete($uri, array $options = []) 
  {
      $str = (stripos($uri, '/') === 0)?$this->apiServer . $uri : $uri;
      $result = $this->client->delete($str, $options);
      $this->analyzeResponse($result);
        
      return $this->success;  
  } 
  
  public function getErrorMessage()
  {
      $result = "";
      
      if (!$this->success) {
          $body = $this->body;
          if (isset($body["message"])) {
              $result = $body["message"];
          }   
      }      
      return $result;
  }
}
 
/* ApiClient in singleton

class ApiClient 
{
  /**
   * @var Singleton reference to singleton instance
   *
  private static $instance;
  
  /**
   *
   * @var The real http client be used
   *
  private static $client;
  
  /**
   * 通过延迟加载（用到时才加载）获取实例
   * 
   * @return self
   *
  public static function getInstance() 
  {
      if (null === $this->instance) {
          $this->instance == new ApiClient;
      }
      return $this->instance;
  }
  
  /**
   * 构造函数私有，不允许在外部实例化
   * 
   *
  private function __construct() 
  {
      $this->client = new Client();
  }
  
  /**
   * 防止对象实例被克隆
   * 
   * @return void
   *
  private function __clone() 
  {  
  }
  
  /**
   * 防止被反序列化
   * 
   * @return void
   *
  private function __wakeup() 
  { 
  }
  
  public function get($uri, array $options = []) 
  {
      return $this->client->get($uri, $options);
  }

  public function head($uri, array $options = []) 
  {
      return $this->client->head($uri, $options);
  }
  
  public function put($uri, array $options = []) 
  {
      return $this->client->put($uri, $options);
  }
  
  public function post($uri, array $options = []) 
  {
      return $this->client->post($uri, $options);
  }
  
  public function patch($uri, array $options = []) 
  {
      return $this->client->patch($uri, $options);
  }
  
  public function delete($uri, array $options = []) 
  {
      return $this->client->delete($uri, $options);
  } 
}



 */