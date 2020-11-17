<?php
namespace Hahadu\ThinkJumpPage;
use Hahadu\ThinkJumpPage\JumpPage;
use think\facade\Config;
use think\Response;

trait TraitJump{
    private function _init_jump_class(){
        return new JumpPage();
    }
    protected function success($msg='',string $url=null,int $waitSecond=3){
        return  $this->_init_jump_class()->success($msg,$url,$waitSecond);
    }
    protected function error($msg='',string $url=null,int $waitSecond=3){
        return  $this->_init_jump_class()->error($msg,$url,$waitSecond);
    }
    protected function redirect($url, $time = 0, $msg = ''){
        return  $this->_init_jump_class()->redirect($url,$time,$msg);
    }
    protected function ajaxReturn(int $code=302,$msg='',int $waitSecond=3){
        return  $this->_init_jump_class()->ajaxReturn($code,$msg,$waitSecond);
    }

}