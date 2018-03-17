<?php echo '折翼天使资源社区商业插件保护！下载获取正版插件请访问折翼天使资源社区官网：www.zheyitianshi.com';exit;?>
<!--{template common/header}-->
<h3 class="flb">
    <em id="return_$_GET['handlekey']"><!--{if $_G['uid']}-->{lang wechat:wechat_bind}<!--{else}-->{lang wechat:wechat_login}<!--{/if}--></em>
			<span>
				<a href="javascript:;" class="flbc" onclick="clearTimeout(wechat_checkST);hideWindow('$_GET['handlekey']')" title="{lang close}">{lang close}</a>
			</span>
</h3>
<div class="c" align='center'>
    <img src="$qrcodeurl" onerror="this.error=null;this.src='$qrcodeurl2'" />
    <br />
    $tiptip2
    $redirect_uri
</div>
<script>
var wechat_checkST = null, wechat_checkCount = 0;
function wechat_checkstart() {
    wechat_checkST = setTimeout(function () {wechat_check()}, 2000);
}
function wechat_check() {
    var x = new Ajax();
    x.get('plugin.php?id=xigua_login:login&check=$codeenc', function(s, x) {
        s = trim(s);
        if(s != 'done') {
            if(s == '1') {
                wechat_checkstart();
            }
            wechat_checkCount++;
            if(wechat_checkCount >= 100) {
                clearTimeout(wechat_checkST);
                hideWindow('$_GET['handlekey']');
            }
        } else {
            clearTimeout(wechat_checkST);
            window.location.href = window.location.href;
        }
    });
}
wechat_checkstart();
</script>
<!--{template common/footer}-->