<?php

/**
 * Copyright 2001-2099 1314学习网.
 * This is NOT a freeware, use is subject to license terms
 * $Id: class_admin.php 1502 2018-01-07 22:53:24Z zhuge $
 * 应用售后问题：http://www.1314study.com/services.php?mod=issue
 * 应用售前咨询：QQ 15326940
 * 应用定制开发：QQ 643306797
 * 本插件为 1314学习网（www.1314study.com） 独立开发的原创插件, 依法拥有版权。
 * 未经允许不得公开出售、发布、使用、修改，如需购买请联系我们获得授权。
 */

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
exit('http://xestar.com/');
}
class addon_seo_portalrewrite_admin{
	function template($file, $templateid = 0, $tpldir = '', $gettplfile = 0, $primaltpl = '') {
	    $file = 'addon_seo_portalrewrite:admin/' . $file;
	    return template($file, $templateid, $tpldir, $gettplfile, $primaltpl);
	}
	
	function subtitle($menus, $type = '', $op = ''){
		if(is_array($menus)) {
			if(!$op){
					$actives[$type] = ' class="active"';
					showtableheader('','study_tb');
					$s .='<div class="study_tab study_tab_min">';
					foreach($menus as $k => $menu){
							$s .= '<a href="'.ADMINSCRIPT.'?action='.STUDY_MANAGE_URL.'&type1314='.$menu[1].'" '.$actives[$menu[1]].'><i></i><ins></ins>'.$menu[0].'</a>';
					}                                           
					$s .= '</div>';
					showtablerow('', array(''), array($s));
					showtablefooter();
			}else{
					$actives[$op] = ' class="current" ';
					showtableheader('', 'study_tb');
					$s = '<div class="study_tab_mid"><ul class="tab1">';
					foreach($menus as $k => $menu){
							$s .= '
							<li '.$actives[$menu[1]].'>
							<a href="'.ADMINSCRIPT.'?action='.STUDY_MANAGE_URL.'&type1314='.$type.'&op='.$menu[1].'">
							<span>'.$menu[0].'</span>
							</a>
							</li>';
					}
					$s .= '</ul></div>';
					//echo "\n".'<tr><th style="height:5px; padding:5px 0 0;"></th></tr>';
					showtitle($s);
					showtablefooter();
			}
		}
	}
}

//Copyright 2001-2099 1314学习网.
//This is NOT a freeware, use is subject to license terms
//$Id: class_admin.php 1949 2018-01-07 14:53:24Z zhuge $
//应用售后问题：http://www.1314study.com/services.php?mod=issue
//应用售前咨询：QQ 15326940
//应用定制开发：QQ 643306797
//本插件为 1314学习网（www.1314study.com） 独立开发的原创插件, 依法拥有版权。
//未经允许不得公开出售、发布、使用、修改，如需购买请联系我们获得授权。