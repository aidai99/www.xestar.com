<?php

/**
 * Copyright 2001-2099 1314学习网.
 * This is NOT a freeware, use is subject to license terms
 * $Id: hook.class.php 720 2016-07-02 13:32:28Z zhuge $
 * 应用售后问题：http://www.1314study.com/services.php?mod=issue
 * 应用售前咨询：QQ 15326940
 * 应用定制开发：QQ 643306797
 * 本插件为 1314学习网（www.1314study.com） 独立开发的原创插件, 依法拥有版权。
 * 未经允许不得公开出售、发布、使用、修改，如需购买请联系我们获得授权。
 */

if (!defined('IN_DISCUZ')) {
exit('Access Denied');
}
class plugin_freeaddon_seo_360sitemapauto {

    function global_footer() {
        global $_G;
        if($_G['inshowmessage']) {
					return '';
				}
       	$splugin_setting = $_G['cache']['plugin']['freeaddon_seo_360sitemapauto'];
       	return $splugin_setting['js'] ? $splugin_setting['js'] : '';
    }

}

class mobileplugin_freeaddon_seo_360sitemapauto{
	
	function global_footer_mobile() {
      global $_G;
      if($_G['inshowmessage']) {
				return '';
			}
     	$splugin_setting = $_G['cache']['plugin']['freeaddon_seo_360sitemapauto'];
     	return $splugin_setting['js'] ? $splugin_setting['js'] : '';
  }
  
}


//Copyright 2001-2099 1314学习网.
//This is NOT a freeware, use is subject to license terms
//$Id: hook.class.php 1165 2016-07-02 05:32:28Z zhuge $
//应用售后问题：http://www.1314study.com/services.php?mod=issue
//应用售前咨询：QQ 15326940
//应用定制开发：QQ 643306797
//本插件为 1314学习网（www.1314study.com） 独立开发的原创插件, 依法拥有版权。
//未经允许不得公开出售、发布、使用、修改，如需购买请联系我们获得授权。