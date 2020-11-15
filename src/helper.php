<?php
/**
 *  +----------------------------------------------------------------------
 *  | Created by  hahadu (a low phper and coolephp)
 *  +----------------------------------------------------------------------
 *  | Copyright (c) 2020. [hahadu] All rights reserved.
 *  +----------------------------------------------------------------------
 *  | SiteUrl: https://github.com/hahadu/wechat
 *  +----------------------------------------------------------------------
 *  | Author: hahadu <582167246@qq.com>
 *  +----------------------------------------------------------------------
 *  | Date: 2020/11/4 下午10:21
 *  +----------------------------------------------------------------------
 *  | Description:   think-jump-class
 *  +----------------------------------------------------------------------
 **/

use Hahadu\ThinkJumpPage\JumpPage;

if(!function_exists('jump_page')){
    /****
     * 页面跳转
     * @param int $code 状态码
     * @param string|null $jumpUrl 跳转url
     * @param int|null $waitSecond 跳转等待时间
     */
    function jump_page($code, $jumpUrl = null, $waitSecond = null){
        return JumpPage::jumpPage($code,$jumpUrl,$waitSecond);
    }
}
if(!function_exists('status_code')){
    function status_code($code){
        $result = JumpPage::status_code($code);
        return $result;
    }
}

