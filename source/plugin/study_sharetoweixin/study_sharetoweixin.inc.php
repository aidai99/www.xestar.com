<?php
/**
 * 折翼天使资源社区源码论坛 全网首发 http://www.zheyitianshi.com
 * From www.zheyitianshi.com
 */

if(!defined('IN_DISCUZ')) {
   exit('http://127.0.0.1/');
}
require DISCUZ_ROOT . './source/plugin/study_sharetoweixin/function_core.php';
$splugin_setting = $_G['cache']['plugin']['study_sharetoweixin'];
if($_GET['qrcode']){
		$tid = intval($_GET['tid']);
		$qrcode_url = _study_sharetoweixin_get_qrcode_url($tid);
		include template('study_sharetoweixin:s_qrcode');
}else{
		include template('study_sharetoweixin:s_help');
}

?>