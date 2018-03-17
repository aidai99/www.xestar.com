<?php

/**
 * 翰林在线 [ 专业开发各种Discuz!插件 ]
 *
 * Copyright (c) 2017-2018 http://www.hallym.cn All Rights Reserved.
 *
 * Author: 风清扬 <itboycenter@qq.com>
 *
 * $Id: hlol_living.inc.php 2017-12-14 09:45:11Z 风清扬 $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$option = $_G['cache']['plugin']['hlol_living'];
$switch = $option['offset'];
$navtitle = $option['navtitle'];
$metakeywords = $option['keywords'];
$metadescription = $option['description'];
$go_title = $option['go_title'];
$go_pic = $option['go_pic'];
$go_link = $option['go_link'];

if($switch != 1){
	showmessage(lang('plugin/hlol_living', 'closemessage'));
	exit();
}

$isGbk = false;
if (CHARSET == 'gbk') $isGbk = true;

if($_GET['mod'] == 'index'){
    include DISCUZ_ROOT.'./source/plugin/hlol_living/module/index.inc.php';
}else if($_GET['mod'] == 'zhibo'){
    include DISCUZ_ROOT.'./source/plugin/hlol_living/module/zhibo.inc.php';
}else if($_GET['mod'] == 'huifang'){
    include DISCUZ_ROOT.'./source/plugin/hlol_living/module/huifang.inc.php';
}else if($_GET['mod'] == 'yugao'){
    include DISCUZ_ROOT.'./source/plugin/hlol_living/module/yugao.inc.php';
}else{
    include DISCUZ_ROOT.'./source/plugin/hlol_living/module/index.inc.php';
}

?>