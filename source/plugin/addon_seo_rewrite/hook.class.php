<?php

/**
 * Copyright 2001-2099 1314ѧϰ��.
 * This is NOT a freeware, use is subject to license terms
 * $Id: hook.class.php 1075 2018-01-18 23:45:45Z zhuge $
 * Ӧ���ۺ����⣺http://www.1314study.com/services.php?mod=issue
 * Ӧ����ǰ��ѯ��QQ 15326940
 * Ӧ�ö��ƿ�����QQ 643306797
 * �����Ϊ 1314ѧϰ����www.1314study.com�� ����������ԭ�����, ����ӵ�а�Ȩ��
 * δ�������ù������ۡ�������ʹ�á��޸ģ����蹺������ϵ���ǻ����Ȩ��
 */

if(!defined('IN_DISCUZ')) {
exit('Access Denied');
}
class plugin_addon_seo_rewrite {
    function common(){
				global $_G;
				if($_G['cache']['plugin']['addon_seo_rewrite']['study_radio']){
						include_once libfile('function/core', 'plugin/addon_seo_rewrite/source');
				}
		}
		function global_usernav_extra1(){
			global $_G;
			if($_G['cache']['plugin']['addon_seo_rewrite']['study_radio']){
				if(CURSCRIPT == 'home' && CURMODULE == 'space' && $_GET['do'] == 'thread'){
					addon_seo_rewrite_multipage();
				}
			}
			return '';
		}
}

class plugin_addon_seo_rewrite_forum extends plugin_addon_seo_rewrite {
	
	function forumdisplay_thread_output(){
		global $_G;
		if($_G['cache']['plugin']['addon_seo_rewrite']['study_radio']){
			addon_seo_rewrite_dispose($_G['forum_threadlist']);
		}
		return array();
	}
	
	function guide_top_output(){
		global $_G, $data, $view;
		if($_G['cache']['plugin']['addon_seo_rewrite']['study_radio']){
			addon_seo_rewrite_dispose($data[$view]['threadlist']);
		}
		return '';
	}
}

//Copyright 2001-2099 1314ѧϰ��.
//This is NOT a freeware, use is subject to license terms
//$Id: hook.class.php 1521 2018-01-18 15:45:45Z zhuge $
//Ӧ���ۺ����⣺http://www.1314study.com/services.php?mod=issue
//Ӧ����ǰ��ѯ��QQ 15326940
//Ӧ�ö��ƿ�����QQ 643306797
//�����Ϊ 1314ѧϰ����www.1314study.com�� ����������ԭ�����, ����ӵ�а�Ȩ��
//δ�������ù������ۡ�������ʹ�á��޸ģ����蹺������ϵ���ǻ����Ȩ��