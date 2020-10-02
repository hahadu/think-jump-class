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
 *  | Version:   v2.0.0
 *  +----------------------------------------------------------------------
 **/

namespace Hahadu\ThinkJumpPage;
use think\facade\Config;
use think\facade\Db;
use think\facade\Request;
use think\Response;

class JumpPage{
    private static $_tpl_path =__DIR__.'/Tpl/jump.tpl';
    private $tpl_path;
    public function __construct(){
        if(!empty(Config::get('jumpPage.dispatch_tpl'))){
            $this->tpl_path = Config::get('jumpPage.dispatch_tpl');
        }else{
            $this->tpl_path = __DIR__.'/Tpl/jump.tpl';
        }
    }
    /****
     * @param int $code 页面状态码
     * @return array 状态码信息
     */
    public static function status_code($code){
        $status_data = Db::name('status_code')
            ->field('code,status,describe,wait_second')
            ->getByCode($code);
        return $status_data;
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
            'status' => $status_code_data['status'],
            'describe' => $status_code_data['describe'],
        ];
        if(!isset($jumpUrl)){
            $result['jumpUrl'] = (null != Request::server('HTTP_REFERER'))?Request::server('HTTP_REFERER'):url('/'.config('app.default_app'))->build();
        }else{
            $result['jumpUrl'] = url($jumpUrl)->build();
        }
        $result['waitSecond'] = isset($waitSecond)?$waitSecond:$status_code_data['wait_second'];
        return view( self::$_tpl_path,$result);
    }

    /****
     * 兼容旧版本的跳转方法
     * @param string $msg
     * @param string|null $url
     * @param int $waitSecond
     */
    public function success($msg='',string $jumpUrl=null,int $waitSecond=3){
        $result = [
            'code' => 302,
            'status' => 1,
            'describe' => $msg,
            'waitSecond' => $waitSecond,
            'jumpUrl' => isset($jumpUrl)?url($jumpUrl)->build():url('/'.config('app.default_app'))->build(),
        ];

        //dump($this->tpl_path);
        return view($this->tpl_path,$result);
    }

    /****
     * 兼容旧版本的跳转方法
     * @param string $msg
     * @param string|null $url
     */
    public function error($msg='',string $jumpUrl=null,int $waitSecond=3){
        $result = [
            'code' => 302,
            'status' => 0,
            'describe' => $msg,
            'waitSecond' => $waitSecond,
            'jumpUrl' => isset($jumpUrl)?url($jumpUrl)->build():url('/'.config('app.default_app'))->build(),
        ];
        return view( $this->tpl_path,$result);
    }

    /****
     * 功能完善中。。。
     * @param string $msg  提示信息
     * @param int|null $code 页面的态码
     * @param int $waitSecond
     */
    public function ajaxReturn(int $code=302,$msg='',int $waitSecond=3){
        $result = [
            'code' => $code,
          //  'status' => 4,
            'describe' => $msg,
          //  'waitSecond' => $waitSecond,
        ];
        return Response::create($result,'json')->send();
    }

    /****
     * 兼容旧版本URL重定向
     * 功能完善中。。。。
     * @param string $url 重定向的URL地址
     * @param integer $time 重定向的等待时间（秒）
     * @param string $msg 重定向前的提示信息
     * @return void|string
     */
    public function redirect($url, $time = 0, $msg = '')
    {
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
            return $str;
        }
    }
}