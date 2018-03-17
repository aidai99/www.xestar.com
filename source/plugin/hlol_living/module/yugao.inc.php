<?php

/**
 * 翰林在线 [ 专业开发各种Discuz!插件 ]
 *
 * Copyright (c) 2017-2018 http://www.hallym.cn All Rights Reserved.
 *
 * Author: 风清扬 <itboycenter@qq.com>
 *
 * $Id: yugao.inc.php 2017-12-16 15:45:11Z 风清扬 $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$page = intval($_GET['page'])>0? intval($_GET['page']):1;
$where = "catname=3 and status=1";
$pagesize = 30;
$start = ($page-1)*$pagesize;	
$count = C::t('#hlol_living#hlol_living')->fetch_all_count("{$where}");
$livingList = C::t('#hlol_living#hlol_living')->fetch_all_list("{$where}"," ORDER BY fsort ASC,id DESC ",$start,$pagesize);
$adfocus = C::t('#hlol_living#hlol_living')->fetch_all_list("status=1 and catname=99"," ORDER BY fsort ASC,id DESC ");

$navname = lang('plugin/hlol_living', 'navyugao').':';
$navtype = 'yugao';
include template("hlol_living:index");

?>