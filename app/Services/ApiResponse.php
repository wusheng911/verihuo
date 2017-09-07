<?php

/*
 * 封装Api Response类. 返回的Response必须符合以下格式:
 * array (
 *    'error' => 'true'/'false',   响应成功还是出错
 *    'results' = > $result,       响应的内容, 如果出错,则输出 array('message' => $message), $message包含了错误信息
 *    'url' => $url                当前请求的url
 * ) 
 * 
 */

namespace App\Services;

use Request;


/**
 * Description of ApiResponse
 *
 * @author Zhu JiaJun 
 */
class ApiResponse {
  
  /***
   * 封装Response的函数, 规范了Response的格式. 
   * 
   * @param bool $error true/false
   * @param array $results 响应内容
   * 
   * @return array 
   */
  public static function get($error, array $results) {
      return array (
          'error' => $error,
          'results' => $results,
          'url' => Request::path()
      );
  }
}
