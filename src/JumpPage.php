<?php
namespace Hahadu\ThinkJumpPage;
use think\facade\View;
use Hahadu\CooleAdmin\model\StatusCode;

class JumpPage{
    /****
     * @param int $code 页面状态码
     * @return array 状态码信息
     */
    public static function status_code($code){
        $status_code = new StatusCode;
        $status_data = $status_code::getByCode($code);
        $result = [
            'code' => $status_data['code'],
            'status' => $status_data['status'],
            'describe' => $status_data['describe'],
            'waitSecond' => $status_data['waitSecond'],
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