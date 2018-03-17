<?php echo '折翼天使资源社区正版插件保护！下载获取正版插件模板请访问折翼天使资源社区官网：www.zheyitianshi.com';exit;?>
<!--{if !$fetch}-->
<p class="pbm bbda xi2">{$tiptip}</p>
<br />
<a href="javascript:;" onclick="showWindow('wechat_bind1', 'plugin.php?id=xigua_login:login')"><img src="source/plugin/xigua_login/static/wechat_bind.png" class="qq_bind" align="absmiddle"></a>
<!--{else}-->
<p class="pbm bbda xi1">{lang wechat:wechat_spacecp_bind_title}</p>
<br />

<!--{if $fetch['isregister']}-->
<h2>
    <a href="javascript:;" onclick="display('unbind');" class="xi2">{lang wechat:wechat_spacecp_setpw}</a>
</h2>

<form id="wechatform" method="post" autocomplete="off" action="plugin.php?id=xigua_login:xgbind">
    <input type="hidden" name="formhash" value="{FORMHASH}" />
    <div class="password">
        <table cellspacing="0" cellpadding="0" class="tfm">
            <tr>
                <th><label>{lang wechat:wechat_spacecp_pw}</label></th>
                <td><input type="password" name="newpassword1" size="30" class="px p_fre" tabindex="1" /></td>
                <td class="tipcol"></td>
            </tr>
            <tr>
                <th><label>{lang wechat:wechat_spacecp_repw}</label></th>
                <td><input type="password" name="newpassword2" size="30" class="px p_fre" tabindex="2" /></td>
                <td class="tipcol"></td>
            </tr>
            <tr>
                <th></th>
                <td>
                    <button type="submit" name="resetpwsubmit" value="yes" class="pn pnc"><strong>{lang submit}</strong></button>
                </td>
            </tr>
        </table>
    </div>
</form>
<br />
<!--{/if}-->

<h2>
    <a href="javascript:;" onclick="display('unbind');" class="xi2">{lang wechat:wechat_spacecp_unbind}</a>
</h2>

<div id="unbind" style="display:none;">
    <form id="wechatform" method="post" autocomplete="off" action="plugin.php?id=xigua_login:xgbind">
        <input type="hidden" name="formhash" value="{FORMHASH}" />
        <!--{if $fetch['isregister']}-->
        <p class="mtm mbm">
            {lang wechat:wechat_spacecp_setpw_first}
        </p>
        <!--{else}-->
        <p class="mtm mbm">
            {lang wechat:wechat_spacecp_unbind}
        </p>
        <button type="submit" name="unbindsubmit" value="yes" class="pn pnc"><strong>{lang wechat:wechat_spacecp_unbind_button}</strong></button>
        <!--{/if}-->
    </form>
</div>

</form>
<!--{/if}-->