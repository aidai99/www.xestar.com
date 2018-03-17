<?php
/**
 * Created by www.zheyitianshi.com.
 * User: 折翼天使资源社区
 * Date: 2016/3/10
 * Time: 21:35
 */
if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}
//ini_set('display_errors', 1);
//error_reporting(E_ALL ^ E_NOTICE);
if(function_exists('match412')){
    function match412(array $match) {
        return strlen($match[0]) >= 4 ? '' : $match[0];
    }
}

if(!function_exists('filterEmoji12')){
    function filterEmoji12($str){
        $str = preg_replace_callback( '/./u', 'match412', $str);

        $str = diconv($str, 'utf-8', 'gbk');
        $str = diconv($str, 'gbk', 'utf-8');

        $str = safe_replace($str, 1);
        return $str;
    }
}

if(!function_exists('current_url')) {
    function current_url() {
        $sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
        $php_self = $_SERVER['PHP_SELF'] ? safe_replace($_SERVER['PHP_SELF']) : safe_replace($_SERVER['SCRIPT_NAME']);
        $path_info = isset($_SERVER['PATH_INFO']) ? safe_replace($_SERVER['PATH_INFO']) : '';
        $relate_url = isset($_SERVER['REQUEST_URI']) ? safe_replace($_SERVER['REQUEST_URI']) : $php_self.(isset($_SERVER['QUERY_STRING']) ? '?'.safe_replace($_SERVER['QUERY_STRING']) : $path_info);
        return $sys_protocal.(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '').$relate_url;
    }
}
if(!function_exists('safe_replace')) {
    function safe_replace($string, $empty = 0) {
        $string = str_replace('%20','',$string);
        $string = str_replace('%27','',$string);
        $string = str_replace('%2527','',$string);
        $string = str_replace('*','',$string);
        $string = str_replace('"', ($empty ?'': '&quot;'),$string);
        $string = str_replace("'",'',$string);
        $string = str_replace('"','',$string);
        $string = str_replace(';','',$string);
        $string = str_replace('<',($empty ?'': '&lt;'),$string);
        $string = str_replace('>',($empty ?'': '&gt;'),$string);
        $string = str_replace("{",'',$string);
        $string = str_replace('}','',$string);
        $string = str_replace('\\','',$string);
        return $string;
    }
}

if(!function_exists('xgetUserInfoByopenId')){
    // *************** Followers info ******************
    function xgetUserInfoByopenId($wechat_client, $openid )
    {
        $info = $wechat_client->getUserInfoById($openid);

        if(!$info || $info['errcode']){
            $appid = WX_APPID;
            $appsecret = WX_APPSECRET;

            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";
            $json = dfsockopen($url);
            if (!$json) {
                $json = file_get_contents($url);
            }
            $access_data = json_decode($json, true);
            $access_token = $access_data['access_token'];

            if ($access_token) {
                savecache('wechatat_' . $appid, array(
                    'token' => $access_token,
                    'expiration' => time() + 6200,
                ));
            }

            $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access_token&openid=$openid&lang=en";
            $json = dfsockopen($url);
            if (!$json) {
                $json = file_get_contents($url);
            }
            $info = json_decode($json, true);
            if(!$info || $info['errcode']){
                return array();
            }
        }
        return $info;
    }
}

include_once libfile('function/cache');
include_once libfile('function/member');
@include_once DISCUZ_ROOT.'./source/plugin/mobile/qrcode.class.php';
include_once DISCUZ_ROOT. 'source/plugin/wechat/wechat.lib.class.php';

$config = $_G['cache']['plugin']['xigua_login'];
$authkey = $_G['config']['security']['authkey'];
define('WX_APPID', trim($config['appid']));
define('WX_APPSECRET', trim($config['appsecert']));
define('IN_WECHAT', strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false);

$cookie = substr(md5('wechatdd'.$_G['siteurl'].WX_APPID), 0, 8);
$openid = !empty($_G['cookie'][$cookie]) ? authcode($_G['cookie'][$cookie], 'DECODE', $authkey) : '';
$wechat_client = new WeChatClient(WX_APPID, WX_APPSECRET);

if(strpos($_GET['backreferer'], 'logout') !== false){
    $_GET['backreferer'] = $_G['siteurl'];
}
if(!$_GET['backreferer']){
    $_GET['backreferer'] = dreferer();
}

if(isset($_GET['check'])) {
    $code = authcode(base64_decode($_GET['check']), 'DECODE', $authkey);
    if($code) {
        $authcode = C::t('#wechat#mobile_wechat_authcode')->fetch_by_code($code);
        if($authcode['status']) {
            require_once libfile('function/member');
            $member = getuserbyuid($authcode['uid'], 1);
            setloginstatus($member, 1296000);

            C::t('common_member_status')->update($authcode['uid'], array('lastip'=>$_G['clientip'], 'lastvisit'=>TIMESTAMP, 'lastactivity' => TIMESTAMP));
            $ucsynlogin = '';
            if($_G['setting']['allowsynlogin']) {
                loaducenter();
                $ucsynlogin = uc_user_synlogin($authcode['uid']);
            }

            dsetcookie('wechat_ticket', '', -1);
            $echostr = 'done';
        } else {
            $echostr = '1';//json_encode($authcode);
        }
    } else {
        $echostr = '-1';
    }

    if(!ob_start($_G['gzipcompress'] ? 'ob_gzhandler' : null)) {
        ob_start();
    }

    if($echostr === 'done'){
        C::t('#wechat#mobile_wechat_authcode')->delete($authcode['sid']);
    }

    include template('common/header_ajax');
    echo $echostr;
    include template('common/footer_ajax');
    exit;
}

if(IN_WECHAT){

    if($config['rhref'] = trim($config['rhref'])){
        $jieurl = strpos($config['rhref'], 'http://') ===false ? "http://".$config['rhref'] : $config['rhref'];
        if(strpos($jieurl, 'r.html')===false){
            $jieurl = rtrim($jieurl, '/').'/source/plugin/xigua_login/r.html';
        }

        /*info*/
        $basert = urlencode($_G['siteurl'].'plugin.php?id=xigua_login:login&c='.urlencode($_GET['c']).'&oauth=yes&backreferer='.urlencode($_GET['backreferer']));
        $redirect_uri = "$jieurl?appid={$config['appid']}&redirect_uri={$basert}&response_type=code&scope=snsapi_userinfo&state=#wechat_redirect";

        /*base*/
        $basert = urlencode($_G['siteurl'].'plugin.php?id=xigua_login:login&c='.urlencode($_GET['c']).'&oauthbase=yes&backreferer='.urlencode($_GET['backreferer']));
        $redirect_base = "$jieurl?appid={$config['appid']}&redirect_uri={$basert}&response_type=code&scope=snsapi_base&state=#wechat_redirect";

    }else{
        $redirect_uri = $wechat_client->getOAuthConnectUri($_G['siteurl'].'plugin.php?id=xigua_login:login&c='.urlencode($_GET['c']).'&oauth=yes&backreferer='.urlencode($_GET['backreferer']), '', 'snsapi_userinfo');
        $redirect_base = $wechat_client->getOAuthConnectUri($_G['siteurl'].'plugin.php?id=xigua_login:login&c='.urlencode($_GET['c']).'&oauthbase=yes&backreferer='.urlencode($_GET['backreferer']), '', 'snsapi_base');
    }

    if(!$openid) {
        if(empty($_GET['oauthbase'])) {
            dheader('location: '.$redirect_base);
        } else {
            $tockeninfo = $wechat_client->getAccessTokenByCode($_GET['code']);

            $openid = $tockeninfo['openid'];
            if(!$openid){
//                dheader('Location: plugin.php?id=xigua_login:login&backreferer='.urlencode($_GET['backreferer']));
                var_dump($tockeninfo);exit;
            }
            dsetcookie($cookie, authcode($openid, 'ENCODE', $authkey), 7100);
        }
    }

    $authcode = C::t('#wechat#mobile_wechat_authcode')->fetch_by_code(authcode(base64_decode($_GET['c']), 'DECODE', $authkey));
//    print_r($authcode);
//    print_r($_GET['c']);

    $succeed = 0;
    if(!$openid){
        var_dump('openid is empty!');exit;
    }
    $fetch = C::t('#wechat#common_member_wechat')->fetch_by_openid($openid); //检查是否绑定

    //虽然有绑定信息但是用户已经不存在的情况 开始
    if($fetch['uid']){
        $member_tmp = getuserbyuid($fetch['uid']);
        if(!$member_tmp){
            $fetch = array();
            C::t('#wechat#common_member_wechat')->delete($fetch['uid']);
        }
    }
    //虽然有绑定信息但是用户已经不存在的情况 结束

    if(($uid = $fetch['uid'])){ //绑定了
        if(!$authcode['uid']){ //未登录状态
            C::t('#wechat#mobile_wechat_authcode')->update($authcode['sid'], array('uid' => $uid, 'status' => 1));
            $succeed = 1;
        }else if($authcode['uid'] != $fetch['uid']){ //当前微信绑定的不是自己号
            showmessage(lang('plugin/xigua_login', 'tiptip3'));
        }else{ //登录了 且 绑定的是自己的号 那还扫什么？
            C::t('#wechat#mobile_wechat_authcode')->update($authcode['sid'], array('uid' => $uid, 'status' => 1));
            $succeed = 1;
        }
    }else{  //未绑定
        if($authcode['uid'] || ($_G['uid']&&$_GET['bindfast'])) {  //bind
            $uid = $_GET['bindfast'] ? $_G['uid'] : $authcode['uid'];
            $member = getuserbyuid($uid, 1);
            if($member){
                WeChatHook::bindOpenId($uid, $openid, 0);
                C::t('#wechat#mobile_wechat_authcode')->update($authcode['sid'], array('uid' => $uid, 'status' => 1));
                $succeed = 1;
            }
        } else {  // reg and bind

            if(empty($_GET['oauth'])){
                $userinfo = xgetUserInfoByopenId($wechat_client, $openid);
            }

            if(!$userinfo['nickname']){
                if(empty($_GET['oauth'])) {
                    dheader('location: '.$redirect_uri);
                } else {
                    $tockeninfo = $wechat_client->getAccessTokenByCode($_GET['code']);
                    $access_token = $tockeninfo['access_token'];
                    $openid = $tockeninfo['openid'];
                    if(!$openid){
                        dheader('Location: plugin.php?id=xigua_login:login&backreferer='.urlencode($_GET['backreferer']));
                        var_dump($tockeninfo);exit;
                    }
                    dsetcookie($cookie, authcode($openid, 'ENCODE', $authkey), 7100);
                    dsetcookie('accessn', authcode($access_token, 'ENCODE', $authkey), 7100);
                }
                $userinfo = $wechat_client->getUserInfoByAuth(authcode($_G['cookie']['accessn'], 'DECODE', $authkey), $openid);
                if($userinfo['errcode']){
                    dsetcookie($cookie, '', -1);
                    dsetcookie('accessn', '', -1);
                    exit(json_encode($userinfo));
                }
            }
            $userinfo['nickname'] = filterEmoji12($userinfo['nickname']);
            foreach ($userinfo as $index => $item) {
                $userinfo[$index] = diconv($userinfo[$index], 'utf-8');
            }

if($_G['cache']['plugin']['xigua_login']['wxmiao']){
            include_once DISCUZ_ROOT.'source/plugin/xigua_login/function.php';
            $userinfo['nickname'] = str_replace(array(" ", "\t", "\n", "\r"), '', $userinfo['nickname']);
            $uid = xg_register($userinfo['nickname'], $userinfo['headimgurl'], 1);

if($uid == '-9999' || $uid == -1|| $uid == -2|| $uid == -3){  //rename hack
    dsetcookie('reg_nickname', authcode($userinfo['nickname'], 'ENCODE', $authkey), 7100);
    dsetcookie('reg_headimgurl', authcode($userinfo['headimgurl'], 'ENCODE', $authkey), 7100);
    dsetcookie('reg_sid', authcode($authcode['sid'], 'ENCODE', $authkey), 7100);
    $url = $_GET['backreferer'] ? $_GET['backreferer']: ($config['weret'] ? $config['weret'] : $_G['siteurl']."home.php?mod=space&uid=$uid&do=profile&mycenter=1&mobile=2");
    dsetcookie('backreferer', $url, 7100);
    dheader('Location: plugin.php?id=xigua_login:reg&has=1');
    exit;
}
            if($uid){
                WeChatHook::bindOpenId($uid, $openid, 1);
                C::t('#wechat#mobile_wechat_authcode')->update($authcode['sid'], array('uid' => $uid, 'status' => 1));
                $succeed = 1;
            }
}else{
                dsetcookie('reg_nickname', authcode($userinfo['nickname'], 'ENCODE', $authkey), 7100);
                dsetcookie('reg_headimgurl', authcode($userinfo['headimgurl'], 'ENCODE', $authkey), 7100);
                dsetcookie('reg_sid', authcode($authcode['sid'], 'ENCODE', $authkey), 7100);
                $url = $_GET['backreferer'] ? $_GET['backreferer']: ($config['weret'] ? $config['weret'] : $_G['siteurl']."home.php?mod=space&uid=$uid&do=profile&mycenter=1&mobile=2");
                dsetcookie('backreferer', $url, 7100);
                dheader('Location: plugin.php?id=xigua_login:reg');
}
        }
    }
    if($succeed){
        require_once libfile('function/member');
        $member = getuserbyuid($uid, 1);
        setloginstatus($member, 1296000);

        C::t('common_member_status')->update($uid, array('lastip'=>$_G['clientip'], 'lastvisit'=>TIMESTAMP, 'lastactivity' => TIMESTAMP));
        $ucsynlogin = '';
        if($_G['setting']['allowsynlogin']) {
            loaducenter();
            $ucsynlogin = uc_user_synlogin($uid);
        }

        $url = $_GET['backreferer'] ? $_GET['backreferer']: ($config['weret'] ? $config['weret'] : $_G['siteurl']."home.php?mod=space&uid=$uid&do=profile&mycenter=1&mobile=2");


        if($ucsynlogin){
            loadcache('usergroup_'.$member['groupid']);
            $_G['group'] = $_G['cache']['usergroup_'.$member['groupid']];
            $_G['group']['grouptitle'] = $_G['cache']['usergroup_'.$_G['groupid']]['grouptitle'];
            $param = array('username' => $member['username'], 'usergroup' => $_G['group']['grouptitle']);
            if($config['showscmsg']){
                showmessage('login_succeed', $url, $param, array('extrajs' => $ucsynlogin));
            }
        }

        if($_GET['bindfast']){
            showmessage(lang('plugin/xigua_login', 'bind_success'), $url);
        }

    }else{
        $url = $_G['siteurl'];
    }

    if(strpos($url, 'oauthbase=yes')!==false || strpos($url, 'oauth=yes')!==false){
        $url = $_G['siteurl'];
    }
    dheader("Location: $url");

}else{

    $code = $i = 0;
    do {
        $code = rand(100000, 999999);
        $codeexists = C::t('#wechat#mobile_wechat_authcode')->fetch_by_code($code);
        $i++;
    } while($codeexists && $i < 10);

    $codeenc = urlencode(base64_encode(authcode($code, 'ENCODE', $authkey)));
    C::t('#wechat#mobile_wechat_authcode')->insert(array('sid' => $_G['cookie']['saltkey'], 'uid' => $_G['uid'], 'code' => $code, 'createtime' => TIMESTAMP), 0, 1);
    if(!discuz_process::islocked('clear_wechat_authcode')) {
        C::t('#wechat#mobile_wechat_authcode')->delete_history();
        discuz_process::unlock('clear_wechat_authcode');
    }

    $repath = './source/plugin/xigua_login/cache/';
    $bindurl = $_G['siteurl'] .'plugin.php?id=xigua_login:login&c='.$codeenc;
    $qrfile = $repath.$code.'.png';

    if(class_exists('QRcode')){
        QRcode::png($bindurl, DISCUZ_ROOT.$qrfile, QR_ECLEVEL_L, 5);
        $qrcodeurl = $_G['siteurl'] . $qrfile;
        $qrcodeurl2 = 'http://qr.liantu.com/api.php?&w=240&bg=ffffff&fg=333333&text='.urlencode($bindurl);
    }else{
        $qrcodeurl = 'http://qr.liantu.com/api.php?&w=240&bg=ffffff&fg=333333&text='.urlencode($bindurl);
        $qrcodeurl2 = $qrcodeurl;
    }
    $tiptip = lang('plugin/xigua_login', 'tiptip2');
    include_once template('xigua_login:wechat_qrcode');
}