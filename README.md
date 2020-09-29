# think-jump-class
基于thinkphp6的页面跳转模块

安装：composer require hahadu/think-jump-class

使用：
```
return \Hahadu\ThinkJumpPage\JumpPage::jumpPage($code,'/index',3); 
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

第二个参数是跳转的URL,为空默认为默认应用的url

第三个参数是等待时间（秒），默认为’wait_second‘的值

创建跳转状态码数据表：

delete_time需要根据你的软删除设置DEFAULT值
```
--
-- 表的结构 `status_code`
--

CREATE TABLE `status_code` (
  `id` int(11) NOT NULL,
  `code` int(32) NOT NULL,
  `status` int(10) NOT NULL DEFAULT 1 COMMENT '状态1:success,0:error',
  `describe` varchar(50) NOT NULL,
  `wait_second` int(20) NOT NULL DEFAULT 3 COMMENT '跳转等待时间',
  `delete_time` int(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='状态码';

```


