<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>跳转提示{if $status==4}:{$code}{/if}</title>
    <style type="text/css">
        *{ padding: 0; margin: 0; }
        body{ background: #fff; font-family: '微软雅黑'; color: #333; font-size: 16px; }
        .system-message{ padding: 24px 48px; }
        .system-message h1{ font-size: 100px; font-weight: normal; line-height: 120px; margin-bottom: 12px; color:#999;}
        .system-message .jump{ padding-top: 10px}
        .system-message .jump a{ color: #099;text-decoration:none;}
        .system-message .jump b{ color: #099;text-decoration:none;}
        .system-message .footer a{ color: #666; left: 30px;text-decoration:none;}
        .system-message .footer {
            width: auto;text-decoration:none;
        }
        .system-message .success,.system-message .error{ line-height: 1.8em; font-size: 36px; color:#F93; }
        .system-message .detail{ font-size: 12px; line-height: 20px; margin-top: 12px; display:none}
    </style>
</head>
<body>
<div class="system-message">
    {switch $status }
    {case 0 }
    <h1 align="center">:(</h1>
    <p class="error" align="center">{$describe}</p>
    {/case}
    {case 1 }
        <h1 align="center">:)</h1>
        <p class="success" align="center">{$describe}</p>
    {/case}
    {case 5 }
        <h1 align="center">warning</h1>
        <p class="error" align="center">{$describe}</p>
    {/case}
    {case 4 }
        <h1 align="center">{$code}</h1>
        <p class="error" align="center">{$describe}</p>
    {/case}
    {default /}
       <h1 align="center">:)</h1>
       <p class="success" align="center">{$describe}</p>
    {/switch}

    <p class="detail"></p>
    <p class="jump" align="center">
        页面自动 <a id="href" href="{$jumpUrl}">跳转</a> 等待时间： <b id="wait">{$waitSecond}</b>
    </p>
    <div class="system-message">
        <p class="footer" align="right"><a href="http://www.imagevd.com" target="_blank">IMAGEDesign</a>&copy 版权所有</p>
    </div>
</div>
<script type="text/javascript">
    (function(){
        var wait = document.getElementById('wait'),href = document.getElementById('href').href;
        var interval = setInterval(function(){
            var time = --wait.innerHTML;
            if(time <= 0) {
                if (window.frames.length != parent.frames.length) {
                    var index = parent.layer.getFrameIndex(window.name);
                    parent.layer.close(index);
                    window.parent.location.reload();
                }else{
                    location.href = href;
                    clearInterval(interval);
                }
            };
        }, 1000);
    })();
</script>
</body>
</html>
