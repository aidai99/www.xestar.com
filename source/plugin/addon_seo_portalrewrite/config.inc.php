<?php

/**
 * Copyright 2001-2099 1314ѧϰ��.
 * This is NOT a freeware, use is subject to license terms
 * $Id: config.inc.php 4250 2018-01-07 22:53:24Z zhuge $
 * Ӧ���ۺ����⣺http://www.1314study.com/services.php?mod=issue
 * Ӧ����ǰ��ѯ��QQ 15326940
 * Ӧ�ö��ƿ�����QQ 643306797
 * �����Ϊ 1314ѧϰ����www.1314study.com�� ����������ԭ�����, ����ӵ�а�Ȩ��
 * δ�������ù������ۡ�������ʹ�á��޸ģ����蹺������ϵ���ǻ����Ȩ��
 */

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
exit('5DCADF3C-1458-2C42-6DBC-5DED736231F5');
}
define('STUDY_MANAGE_URL', 'plugins&operation=config&do='.$pluginid.'&identifier='.dhtmlspecialchars($_GET['identifier']).'&pmod=rewrite');                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   $_statInfo = array();$_statInfo['pluginName'] = $plugin['identifier'];$_statInfo['pluginVersion'] = $plugin['version'];$_statInfo['bbsVersion'] = DISCUZ_VERSION;$_statInfo['bbsRelease'] = DISCUZ_RELEASE;$_statInfo['timestamp'] = TIMESTAMP;$_statInfo['bbsUrl'] = $_G['siteurl'];$_statInfo['SiteUrl'] = 'http://xestar.com/';$_statInfo['ClientUrl'] = 'https://www.xestar.com/';$_statInfo['SiteID'] = '5DCADF3C-1458-2C42-6DBC-5DED736231F5';$_statInfo['bbsAdminEMail'] = $_G['setting']['adminemail'];
loadcache('plugin');
$sxm9_az5 = "1314ѧϰ��";
$splugin_setting = $_G['cache']['plugin']['addon_seo_portalrewrite'];
$splugin_lang = lang('plugin/addon_seo_portalrewrite');
$type1314 = in_array($_GET['type1314'], array('config', 'icon', 'category', 'slide', 'rewrite', 'seo')) ? $_GET['type1314'] : 'config';
$splugin_setting['0'] = array('0' => '2018011611SS3SnQsV1c', '1' => '73969','2' => '1515362401', '3' => 'http://xestar.com/', '4' => 'https://www.xestar.com/', '5' => '5DCADF3C-1458-2C42-6DBC-5DED736231F5', '6' => '851D8E9C-23C7-22AD-B02B-CAC32CB34CCB', '7' => '22b0dc2f39f38de71f18636304be0482');
require_once libfile('include/config', 'plugin/addon_seo_portalrewrite/source');

//Copyright 2001-2099 1314ѧϰ��.
//This is NOT a freeware, use is subject to license terms
//$Id: config.inc.php 4696 2018-01-07 14:53:24Z zhuge $
//Ӧ���ۺ����⣺http://www.1314study.com/services.php?mod=issue
//Ӧ����ǰ��ѯ��QQ 15326940
//Ӧ�ö��ƿ�����QQ 643306797
//�����Ϊ 1314ѧϰ����www.1314study.com�� ����������ԭ�����, ����ӵ�а�Ȩ��
//δ�������ù������ۡ�������ʹ�á��޸ģ����蹺������ϵ���ǻ����Ȩ��