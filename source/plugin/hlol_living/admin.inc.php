<?php

/**
 * 翰林在线 [ 专业开发各种Discuz!插件 ]
 *
 * Copyright (c) 2017-2018 http://www.hallym.cn All Rights Reserved.
 *
 * Author: 风清扬 <itboycenter@qq.com>
 *
 * $Id: admin.inc.php 2017-12-14 10:45:11Z 风清扬 $
 */

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$Lang = $scriptlang['hlol_living'];
$adminBaseUrl = ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=hlol_living&pmod=admin'; 
$adminListUrl = 'action=plugins&operation=config&do='.$pluginid.'&identifier=hlol_living&pmod=admin';
$adminFromUrl = 'plugins&operation=config&do='.$pluginid.'&identifier=hlol_living&pmod=admin';
$uSiteUrl = urlencode($_G['siteurl']);

include DISCUZ_ROOT.'./source/plugin/hlol_living/class/hlol.form.php';
include DISCUZ_ROOT.'./source/plugin/hlol_living/class/hlolliving.upload.php';

if($_GET['mod'] == 'index'){
    include DISCUZ_ROOT.'./source/plugin/hlol_living/admin/index.inc.php';
}else if($_GET['mod'] == 'help'){
    include DISCUZ_ROOT.'./source/plugin/hlol_living/admin/help.inc.php';
}else{
    include DISCUZ_ROOT.'./source/plugin/hlol_living/admin/index.inc.php';
}

?>