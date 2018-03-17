<?php

/**
 * Copyright 2001-2099 1314学习网.
 * This is NOT a freeware, use is subject to license terms
 * $Id: hook.class.php 1075 2018-01-18 23:45:45Z zhuge $
 * 应用售后问题：http://www.1314study.com/services.php?mod=issue
 * 应用售前咨询：QQ 15326940
 * 应用定制开发：QQ 643306797
 * 本插件为 1314学习网（www.1314study.com） 独立开发的原创插件, 依法拥有版权。
 * 未经允许不得公开出售、发布、使用、修改，如需购买请联系我们获得授权。
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

//Copyright 2001-2099 1314学习网.
//This is NOT a freeware, use is subject to license terms
//$Id: hook.class.php 1521 2018-01-18 15:45:45Z zhuge $
//应用售后问题：http://www.1314study.com/services.php?mod=issue
//应用售前咨询：QQ 15326940
//应用定制开发：QQ 643306797
//本插件为 1314学习网（www.1314study.com） 独立开发的原创插件, 依法拥有版权。
//未经允许不得公开出售、发布、使用、修改，如需购买请联系我们获得授权。