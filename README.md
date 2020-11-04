# think-jump-class
基于thinkphp6的页面跳转模块

安装：composer require hahadu/think-jump-class

* 支持自定义状态码
* 支持layer弹窗自动关闭
* 兼容tp旧版本的跳转写法（$this->success）

10.15更新：
* V3.0版
* - 1、新增：自定义http_response_code
* - 2、新增：自定义title
* - 3、新增：Exception
* - 4、修复：mysql保留关键字冲突



使用：
```
return \Hahadu\ThinkJumpPage\JumpPage::jumpPage($code,'/index',3); 
```
或者：
```
return \Hahadu\ThinkJumpPage\JumpPage::jumpPage($code,$url,$wait_second)->send();
```
推荐打包助手函数
```
function jump($code,$url='',$wait_second=null){
   return \Hahadu\ThinkJumpPage\JumpPage::jumpPage($code,$url,$wait_second);
}
jump($code)->send(); 
```
jumpPage()有三个参数

第一个参数是页面状态码

第二个参数是跳转的URL,为空默认为默认应用的url,如果是站内跳转的默认跳转回上一页

第三个参数是等待时间（秒），默认为’wait_second‘的值

* 自定义跳转模板文件：comopser安装后会自动在config生成一个jumpPage.php的配置文件
* 修改JumpPage中的'dispatch_tpl'值为你的配置文件路径即可


创建跳转状态码数据表：

delete_time需要根据你的软删除设置DEFAULT值
```
--
-- 表的结构 `status_code`
--

CREATE TABLE `du_status_code` (
  `id` int(11) NOT NULL,
  `code` int(32) NOT NULL COMMENT '页面查询状态码',
  `status` int(10) NOT NULL DEFAULT 1 COMMENT '状态1:success,0:error',
  `message` varchar(50) NOT NULL COMMENT '页面状态说明',
  `title` varchar(200) DEFAULT ':(' COMMENT '页面h1内容',
  `response_code` int(10) DEFAULT 301 COMMENT '页面跳转码http_response_code',
  `wait_second` int(20) NOT NULL DEFAULT 3 COMMENT '跳转等待时间',
  `delete_time` int(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='状态码';

```

* 兼容thinkPHP旧版本的$this->success()，$this->error()等方法：

* 在需要跳转的控制器中引入’\Hahadu\ThinkJumpPage\TraitJump‘：
```
namespace app\controller;

class HomeNavController extends AdminBaseController
{
    use \Hahadu\ThinkJumpPage\TraitJump; //引入TraitJump
    public function index (){
        $this->success('操作成功','/index/index'); 
    }

}
```




