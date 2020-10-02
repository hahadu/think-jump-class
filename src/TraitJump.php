<?php
namespace Hahadu\ThinkJumpPage;
use Hahadu\ThinkJumpPage\JumpPage;
use think\facade\Config;
use think\Response;

trait TraitJump{
    private $jumpPage;
    public function __construct(){
        $this->jumpPage = new JumpPage();
    }

    protected function success($msg='',string $url=null,int $waitSecond=3){
        return  $this->jumpPage->success($msg,$url,$waitSecond)->send();
    }
    protected function error($msg='',string $url=null,int $waitSecond=3){
        return  $this->jumpPage->error($msg,$url,$waitSecond)->send();
    }
    protected function redirect($url, $time = 0, $msg = ''){
        return  $this->jumpPage->redirect($url,$time,$msg);
    }
    protected function ajaxReturn(int $code=302,$msg='',int $waitSecond=3){

        return  $this->jumpPage->ajaxReturn($code,$msg,$waitSecond);
    }

}