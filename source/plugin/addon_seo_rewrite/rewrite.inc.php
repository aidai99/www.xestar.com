<?php

/**
 * Copyright 2001-2099 1314ѧϰ��.
 * This is NOT a freeware, use is subject to license terms
 * $Id: rewrite.inc.php 4243 2018-01-18 23:45:45Z zhuge $
 * Ӧ���ۺ����⣺http://www.1314study.com/services.php?mod=issue
 * Ӧ����ǰ��ѯ��QQ 15326940
 * Ӧ�ö��ƿ�����QQ 643306797
 * �����Ϊ 1314ѧϰ����www.1314study.com�� ����������ԭ�����, ����ӵ�а�Ȩ��
 * δ�������ù������ۡ�������ʹ�á��޸ģ����蹺������ϵ���ǻ����Ȩ��
 */

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
exit('Access Denied');
}
define('STUDY_MANAGE_URL', 'plugins&operation=config&do='.$pluginid.'&identifier='.dhtmlspecialchars($_GET['identifier']).'&pmod=rewrite');                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   $_statInfo = array();$_statInfo['pluginName'] = $plugin['identifier'];$_statInfo['pluginVersion'] = $plugin['version'];$_statInfo['bbsVersion'] = DISCUZ_VERSION;$_statInfo['bbsRelease'] = DISCUZ_RELEASE;$_statInfo['timestamp'] = TIMESTAMP;$_statInfo['bbsUrl'] = $_G['siteurl'];$_statInfo['SiteUrl'] = 'http://xestar.com/';$_statInfo['ClientUrl'] = 'https://www.xestar.com/';$_statInfo['SiteID'] = '5DCADF3C-1458-2C42-6DBC-5DED736231F5';$_statInfo['bbsAdminEMail'] = $_G['setting']['adminemail'];
$kq26f9k4 = "7010";
loadcache('plugin');
$splugin_setting = $_G['cache']['plugin']['addon_seo_rewrite'];/*1.3.1.4.ѧ.ϰ.��*/
$splugin_lang = lang('plugin/addon_seo_rewrite');
$type1314 = in_array($_GET['type1314'], array('config', 'icon', 'category', 'slide', 'rewrite', 'seo')) ? $_GET['type1314'] : 'config';
$splugin_setting['0'] = array('0' => '2018011611M4m6A46zYx', '1' => '62812','2' => '1516352401', '3' => 'http://xestar.com/', '4' => 'https://www.xestar.com/', '5' => '5DCADF3C-1458-2C42-6DBC-5DED736231F5', '6' => '851D8E9C-23C7-22AD-B02B-CAC32CB34CCB', '7' => 'f2451f39f01356975c59f20a45e86193');
require_once libfile('include/rewrite', 'plugin/addon_seo_rewrite/source');

//Copyright 2001-2099 1314ѧϰ��.
//This is NOT a freeware, use is subject to license terms
//$Id: rewrite.inc.php 4690 2018-01-18 15:45:45Z zhuge $
//Ӧ���ۺ����⣺http://www.1314study.com/services.php?mod=issue
//Ӧ����ǰ��ѯ��QQ 15326940
//Ӧ�ö��ƿ�����QQ 643306797
//�����Ϊ 1314ѧϰ����www.1314study.com�� ����������ԭ�����, ����ӵ�а�Ȩ��
//δ�������ù������ۡ�������ʹ�á��޸ģ����蹺������ϵ���ǻ����Ȩ��