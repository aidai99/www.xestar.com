<?php
/*
 *折翼天使资源社区：www.zheyitianshi.com
 *更多商业插件/模版折翼天使资源社区 就在折翼天使资源社区
 *本资源来源于网络收集,仅供个人学习交流，请勿用于商业用途，并于下载24小时后删除!
 *如果侵犯了您的权益,请及时告知我们,我们即刻删除!
 */


if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
$table1 = DB::table("keke_chongzhi_orderlog");
$sql = <<<EOF
DROP TABLE IF EXISTS `$table1`;
EOF;

runquery($sql);
$finish = true;
?>
?>