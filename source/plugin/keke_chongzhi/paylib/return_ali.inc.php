<?php
/*
 *折翼天使资源社区：www.zheyitianshi.com
 *更多商业插件/模版折翼天使资源社区 就在折翼天使资源社区
 *本资源来源于网络收集,仅供个人学习交流，请勿用于商业用途，并于下载24小时后删除!
 *如果侵犯了您的权益,请及时告知我们,我们即刻删除!
 */


define('IN_API', true);
define('CURSCRIPT', 'api');
define('DISABLEXSSCHECK', true);

require_once '../../../../source/class/class_core.php';
$discuz = C::app();
$discuz->init();
loadcache('plugin');
global $_G;
$keke_chongzhi = $_G['cache']['plugin']['keke_chongzhi'];
require_once("alipay/alipay.config.php");
require_once("alipay/alipay_notify.class.php");
$returl=str_replace('source/plugin/keke_chongzhi/paylib/', '',$_G['siteurl']);

//计算得出通知验证结果
$alipayNotify = new AlipayNotify($alipay_config);
$verify_result = $alipayNotify->verifyReturn();
if($verify_result) {
	$out_trade_no = $_GET['out_trade_no'];
	//支付宝交易号
	$trade_no = $_GET['trade_no'];
	//交易状态
	$trade_status = $_GET['trade_status'];
    if($_GET['trade_status'] == 'TRADE_FINISHED' || $_GET['trade_status'] == 'TRADE_SUCCESS') {
		$url=$keke_chongzhi['tz']? $keke_chongzhi['tz'] : $returl.'plugin.php?id=keke_chongzhi&p=my';
		Header("HTTP/1.1 303 See Other"); 
		Header("Location: $url"); 
		exit;		
    }
	echo "success<br />";
}
else {
    echo "fail";
}
?>