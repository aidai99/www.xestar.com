<?php
/**
 * Created by www.zheyitianshi.com.
 * User: www.zheyitianshi.com
 * Date: 2016/11/3
 * Time: 12:04
 */
ini_set('display_errors', 0);
error_reporting(0);
function current_url() {
    $sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
    $php_self = $_SERVER['PHP_SELF'] ? safe_replace($_SERVER['PHP_SELF']) : safe_replace($_SERVER['SCRIPT_NAME']);
    $path_info = isset($_SERVER['PATH_INFO']) ? safe_replace($_SERVER['PATH_INFO']) : '';
    $relate_url = isset($_SERVER['REQUEST_URI']) ? safe_replace($_SERVER['REQUEST_URI']) : $php_self.(isset($_SERVER['QUERY_STRING']) ? '?'.safe_replace($_SERVER['QUERY_STRING']) : $path_info);
    return $sys_protocal.(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '').$relate_url;
}
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

$code = isset($_GET['code'])?$_GET['code']:'';
$appid = isset($_GET['appid']) ? $_GET['appid'] : '';
$scope = isset($_GET['scope']) ? $_GET['scope'] : '';
$redirect_uri = $_GET['redirect_uri'];
if(!$scope){
    $scope = 'snsapi_base';
}
$state = isset($_GET['state']) ? $_GET['state'] : '';
$curlurl = current_url();
$redirecturl = '';


if(!$code){
    $url = http_build_query(array(
        'appid' => $appid,
        'redirect_uri' => $curlurl,
        'response_type' => 'code',
        'scope' => $scope,
        'state' => $state,
    ));
    $redirecturl = "https://open.weixin.qq.com/connect/oauth2/authorize?$url#wechat_redirect";
}else{
    $url = http_build_query(array(
        'code' => $code,
        'state' => $state,
    ));
    if(strpos($redirect_uri, '?')!==false){
        $dot = '&';
    }else{
        $dot = '?';
    }

    $redirecturl = "{$redirect_uri}{$dot}{$url}";
}
header("location: $redirecturl");
exit;