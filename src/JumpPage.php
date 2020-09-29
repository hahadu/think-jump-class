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
 *  | Description:   thinkPHP6的页面跳转类
 *  +----------------------------------------------------------------------
 **/

namespace Hahadu\JumpPage;
use think\facade\Db;
use think\facade\View;
use Hahadu\CooleAdmin\model\StatusCode;

class JumpPage{
    /****
     * @param int $code 页面状态码
     * @return array 状态码信息
     */
    public static function status_code($code){
        $status_data = Db::name('status_code')->getByCode($code);
        $result = [
            'code' => $status_data['code'],
            'status' => $status_data['status'],
            'describe' => $status_data['describe'],
            'waitSecond' => $status_data['wait_second'],
        ];
        return $result;
    }

    /****
     * @param int $code 页面状态码
     * @param string|null $jumpUrl 要跳转的页面
     * @param int|null $waitSecond 跳转等待时间
     * @return string
     */
    public static function jumpPage($code,$jumpUrl = null,$waitSecond = null){
        $result = self::status_code($code);
        $result['jumpUrl'] = isset($jumpUrl)?url($jumpUrl)->build():url('/'.config('app.default_app'))->build(); //设置跳转链
        $result['waitSecond'] = isset($waitSecond)?$waitSecond:$result['waitSecond'];
        View::assign($result);
        return View::fetch(__DIR__.'/Tpl/jump.html');
    }
}