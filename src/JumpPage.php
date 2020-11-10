<?php
/**
 *  +----------------------------------------------------------------------
 *  | Created by  hahadu (a low phper and coolephp)
 *  +----------------------------------------------------------------------
 *  | Copyright (c) 2020. [hahadu] All rights reserved.
 *  +----------------------------------------------------------------------
 *  | SiteUrl: https://github.com/hahadu
 *  +----------------------------------------------------------------------
 *  | Author: hahadu <582167246@qq.com>
 *  +----------------------------------------------------------------------
 *  | Date: 2020/9/29 下午2:20
 *  +----------------------------------------------------------------------
 *  | Description:   thinkPHP6的页面跳转娄
 *  +----------------------------------------------------------------------
 *  | Version:   v3.0.0
 *  +----------------------------------------------------------------------
 **/

namespace Hahadu\ThinkJumpPage;
use think\facade\Config;
use think\facade\Db;
use think\facade\Request;
use think\Response;

/****
 * thinkphp6 页面跳转
 * @package Hahadu\ThinkJumpPage
 */
class JumpPage{
    private static $_tpl_path =__DIR__.'/Tpl/jump.tpl';
    private $tpl_path;
    private static $ajax_type='json';
    public function __construct(){
        if(!empty(Config::get('jumpPage.dispatch_tpl'))){
            $this->tpl_path = Config::get('jumpPage.dispatch_tpl');
            self::$_tpl_path = Config::get('jumpPage.dispatch_tpl');
        }else{
            $this->tpl_path = __DIR__.'/Tpl/jump.tpl';
        }
        self::$ajax_type = Config::get('jumpPage.ajax_type');
        if(!empty(Config::get('jumpPage.ajax_type'))){
            self::$ajax_type = Config::get('jumpPage.ajax_type');
        }
    }

    /****
     * @param int $code 页面状态码
     * @return array 状态码信息
     */
    public static function status_code($code){
        $status_data = Db::name('status_code')
            ->field('code,status,title,message,response_code,wait_second')
            ->getByCode($code);
        return $status_data;
    }
    public static function init(){
        if(!empty(Config::get('jumpPage')))
            return new static();
    }

    /****
     * @param int $code 页面状态码
     * @param string|array|null $jumpUrl 要跳转的页面
     * @param int|null $waitSecond 跳转等待时间
     * @return string
     */
    public static function jumpPage($code,$jumpUrl = null,$waitSecond = null){
        $status_code_data = self::status_code($code);
        $result = [
            'code' => $status_code_data['code'],
            'title' => $status_code_data['title'],
            'status' => $status_code_data['status'],
            'message' => $status_code_data['message'],
        ];
        http_response_code($status_code_data['response_code']);
        static::init() ;
        if(Config::get('jumpPage.ajax')){
            switch (self::$ajax_type){
                case 'jsonp':
                    return jsonp($result);
                    break;
                case 'array':
                    return $result;
                    break;
                case 'xml':
                    return xml($result);
                default:
                    return json($result);
                    break;
            }
        }
        if(!isset($jumpUrl)){
            $result['jumpUrl'] = (strstr(Request::server('HTTP_REFERER'),Request::server('HTTP_HOST')))?Request::server('HTTP_REFERER'):url('/'.config('app.default_app'))->build();
        }else{
            $result['jumpUrl'] = url($jumpUrl)->build();
        }
        $result['waitSecond'] = isset($waitSecond)?$waitSecond:$status_code_data['wait_second'];
        return view( self::$_tpl_path,$result);
    }

    /****
     * 兼容旧版本的跳转方法
     * @param string $msg
     * @param string|null $jumpUrl
     * @param int $waitSecond
     */
    public function success($msg='',string $jumpUrl=null,int $waitSecond=3){
        $result = [
            'code' => 301,
            'status' => 1,
            'title' => ':)',
            'message' => $msg,
            'waitSecond' => $waitSecond,
            'response_code' => 301
        ];
        http_response_code($result['response_code']);
        if(!isset($jumpUrl)){
            $result['jumpUrl'] = (null != Request::server('HTTP_REFERER'))?Request::server('HTTP_REFERER'):url('/'.config('app.default_app'))->build();
        }else{
            $result['jumpUrl'] = url($jumpUrl)->build();
        }

        return view($this->tpl_path,$result);
    }

    /****
     * 兼容旧版本的跳转方法
     * @param string $msg
     * @param int $waitSecond
     * @param string|null $jumpUrl
     */
    public function error($msg='',$jumpUrl=null, $waitSecond=3){
        $result = [
            'code' => 301,
            'status' => 0,
            'title' => ':)',
            'message' => $msg,
            'waitSecond' => $waitSecond,
            'response_code' => 301
        ];
        http_response_code($result['response_code']);
        if(!isset($jumpUrl)){
            $result['jumpUrl'] = (null != Request::server('HTTP_REFERER'))?Request::server('HTTP_REFERER'):url('/'.config('app.default_app'))->build();
        }else{
            $result['jumpUrl'] = url($jumpUrl)->build();
        }

        return view( $this->tpl_path,$result);
    }

    /****
     * 功能完善中。。。
     * @param string $msg  提示信息
     * @param int|null $code 页面的态码
     * @param int $waitSecond
     */
    public function ajaxReturn(int $code=1,$msg='',int $waitSecond=3){
        $result = [
            'code' => $code,
            //  'status' => 4,
            'message' => $msg,
            'waitSecond' => $waitSecond,
        ];
        return json_encode($result);
    }

    /****
     * 兼容旧版本URL重定向
     * 功能完善中。。。。
     * @param string $jumpUrl 重定向的URL地址
     * @param integer $time 重定向的等待时间（秒）
     * @param string $msg 重定向前的提示信息
     * @return void|string
     */
    public function redirect($jumpUrl, $time = 0, $msg = '')
    {
        if(!isset($jumpUrl)){
            $url = (null != Request::server('HTTP_REFERER'))?Request::server('HTTP_REFERER'):url('/'.config('app.default_app'))->build();
        }else{
            $url = url($jumpUrl)->build();
        }
        http_response_code(301);

        //多行URL地址支持
        $url = str_replace(array("\n", "\r"), '', $url);
        if (empty($msg)) {
            $msg = "系统将在{$time}秒之后自动跳转到{$url}！";
        }
        if (!headers_sent()) {
            // redirect
            if (0 === $time) {
                header('Location: ' . $url);
            } else {
                header("refresh:{$time};url={$url}");
                return ($msg);
            }
            exit();
        } else {
            $str = "<meta http-equiv='Refresh' content='{$time};URL={$url}'>";
            if (0 != $time) {
                $str .= $msg;
            }
            exit($str);
        }
    }
}