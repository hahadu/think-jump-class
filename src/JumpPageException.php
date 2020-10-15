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
 *  | Date: 2020/10/15 下午1:01
 *  +----------------------------------------------------------------------
 *  | Description:   think-jump-class 异常处理类
 *  +----------------------------------------------------------------------
 **/

namespace Hahadu\ThinkJumpPage;
use Hahadu\ThinkJumpPage\JumpPage;
use think\facade\Config;
use think\facade\Request;
use Throwable;


class JumpPageException extends \Exception
{
    protected $status;
    protected $waitSecond;
    private $tpl_path;
    private $title;
    private $response_code;
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {

        parent::__construct($message, $code, $previous);
        if(isset($message) and is_int($message)){
            $exception = JumpPage::status_code($message);
            $this->message = $exception['message'];
            $this->code = $exception['code'];
            $this->title = $exception['title'];
            $this->status = $exception['status'];
            $this->waitSecond = $exception['wait_second'];
            $this->response_code = $exception['response_code'];
        }
        if(!empty(Config::get('jumpPage.dispatch_tpl'))){
            $this->tpl_path = Config::get('jumpPage.dispatch_tpl');
        }else{
            $this->tpl_path = __DIR__.'/Tpl/jump.tpl';
        }

    }

    public function JumpError($jumpUrl=null){
        $result = [
            'code' => $this->code,
            'title' => $this->title,
            'status' => $this->status,
            'message' => $this->message,
            'waitSecond' => $this->waitSecond,
            'response_code' => $this->response_code,
        ];
        http_response_code($this->response_code);
        if(!isset($jumpUrl)){
            $result['jumpUrl'] = (null != Request::server('HTTP_REFERER'))?Request::server('HTTP_REFERER'):url('/'.config('app.default_app'))->build();
        }else{
            $result['jumpUrl'] = url($jumpUrl)->build();
        }
        return view( $this->tpl_path,$result);
    }
/*
    public function errorMessage(){
        //$code = JumpPage::status_code();

    }
*/
}