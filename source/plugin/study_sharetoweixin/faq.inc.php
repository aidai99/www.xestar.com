<?php
/**
 * ������ʹ��Դ����Դ����̳ ȫ���׷� http://www.zheyitianshi.com
 * From www.zheyitianshi.com
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$identifier = CURMODULE;
$splugin_setting = $_G['cache']['plugin'][$identifier];
$splugin_lang = lang('plugin/'.$identifier);
include template($identifier.':faq');
?>