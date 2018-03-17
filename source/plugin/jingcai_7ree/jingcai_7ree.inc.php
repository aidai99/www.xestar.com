<?php
/*
	ID: jingcai_7ree
	[www.7ree.com] (C)2007-2017 7ree.com.
	This is NOT a freeware, use is subject to license terms
	Update: 2017/12/25 17:53
	Agreement: http://addon.discuz.com/?@7.developer.doc/agreement_7ree_html
	More Plugins: http://addon.discuz.com/?@7ree
*/
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
require_once DISCUZ_ROOT.'./source/plugin/jingcai_7ree/include/function_7ree.php'; 

$jingcai_7ree_var = $_G['cache']['plugin']['jingcai_7ree'];

if(!$jingcai_7ree_var['agreement_7ree']) showmessage('jingcai_7ree:php_lang_agree_7ree');

//只允许手机访问
//if(!defined('IN_MOBILE') && $_G['adminid']!=1) showmessage('请您通过手机客户端访问竞猜系统。');

$navtitle = $jingcai_7ree_var['navtitle_7ree'];

$metakeywords = $jingcai_7ree_var['keywords_7ree'];
$metadescription = $jingcai_7ree_var['description_7ree'];

$zuqiu_7ree = lang('plugin/jingcai_7ree','php_lang_zuqiu_7ree');


$titlebg_7ree =  str_replace("\n","|||",$jingcai_7ree_var['titlebg_7ree']);
$titlebg_array =  explode('|||', $titlebg_7ree);
foreach($titlebg_array as $titlebg_value){
		$titlebg_array2[] = explode('=',trim($titlebg_value));
}
$defaultbg_7ree = $titlebg_array2[0][1];

$zhichilist_7ree = explode(',', $jingcai_7ree_var['zhichilist_7ree']);

$_GET['ac_7ree'] = intval($_GET['ac_7ree']);
$_GET['sp_7ree'] = daddslashes(dhtmlspecialchars($_GET['sp_7ree']));
	
$extname_7ree = "extcredits".$jingcai_7ree_var['extcredit_7ree'];
$exttitle_7ree = $_G['setting']['extcredits'][$jingcai_7ree_var['extcredit_7ree']][title];
$notice_7ree = $jingcai_7ree_var['notice_7ree'];
$width_7ree = $jingcai_7ree_var['width_7ree'];
$height_7ree = $jingcai_7ree_var['height_7ree'];
$cost_7ree = $jingcai_7ree_var['cost_7ree'];

$viewextname_7ree = "extcredits".$jingcai_7ree_var['viewext_7ree'];
$viewexttitle_7ree = $_G['setting']['extcredits'][$jingcai_7ree_var['viewext_7ree']][title];


$isadmins_7ree = (in_array($_G['uid'],explode(',',$jingcai_7ree_var['admins_7ree']))) ? 1 : 0;

$fenlei_7ree =  str_replace("\n","|||",$jingcai_7ree_var['fenlei_7ree']);
$fenlei_array =  explode('|||', $fenlei_7ree);


foreach($fenlei_array as $fenlei_value){
		$fenlei_array2[] = explode(',',trim($fenlei_value));
}

$rangqiu_7ree =  explode(",",$jingcai_7ree_var['rangqiu_7ree']);

if(!$_G['uid'] && in_array($_GET['ac_7ree'],array("1","2","3","4","7","8","9","10","11","12","13","15","16","17","18"))) showmessage('not_loggedin', NULL, array(), array('login' => 1));
if(!($_G['adminid']==1 || $isadmins_7ree) && in_array($_GET['ac_7ree'],array("2","3","15"))) showmessage('Permission denied. @7ree', NULL, 'NOPERM');

$fenlei1_7ree = $_GET['fenlei1_7ree'] ? daddslashes(dhtmlspecialchars(trim($_GET['fenlei1_7ree']))) : '';
$fenlei2_7ree = $_GET['fenlei2_7ree'] ? daddslashes(dhtmlspecialchars(trim($_GET['fenlei2_7ree']))) : '';



$rewrite_config_7ree = DISCUZ_ROOT.'source/plugin/jingcai_7ree/rewrite_config_7ree.inc.php';
if (file_exists($rewrite_config_7ree) && $jingcai_7ree_var['rewrite_7ree']){
	@include $rewrite_config_7ree;
}else{
	
	//竞猜大厅
	$dating_url_7ree = "plugin.php?id=jingcai_7ree:jingcai_7ree";
	//往期竞猜
	$wangqiurl_7ree = "plugin.php?id=jingcai_7ree:jingcai_7ree&finish_7ree=1";
	//我的竞猜
	$wdjc_url_7ree = "plugin.php?id=jingcai_7ree:jingcai_7ree&ac_7ree=1";
	//我的关注
	$wdgz_url_7ree = "plugin.php?id=jingcai_7ree:jingcai_7ree&ac_7ree=7";
	//竞猜详情
	$xiangqing_url_7ree = "plugin.php?id=jingcai_7ree:jingcai_7ree&sp_7ree=more&main_id_7ree=";
	//竞猜记录
	$jilu_url_7ree = "plugin.php?id=jingcai_7ree:jingcai_7ree&ac_7ree=10&uid_7ree=";
	//竞猜高手
	$gaoshou_url_7ree = "plugin.php?id=jingcai_7ree:jingcai_7ree&ac_7ree=14&main_id_7ree=";
	//总盈利排行榜单
	$zyl_url_7ree = "plugin.php?id=jingcai_7ree:jingcai_7ree&ac_7ree=6&how_7ree=1";
	//净盈利排行榜单
	$jyl_url_7ree = "plugin.php?id=jingcai_7ree:jingcai_7ree&ac_7ree=6&how_7ree=2";
	//猜赢场次排行榜单
	$cangci_url_7ree = "plugin.php?id=jingcai_7ree:jingcai_7ree&ac_7ree=6&how_7ree=3";
	//命中率排行榜单
	$mingzhong_url_7ree = "plugin.php?id=jingcai_7ree:jingcai_7ree&ac_7ree=6&how_7ree=4";
	//重点连胜排行榜单
	$liansheng_url_7ree = "plugin.php?id=jingcai_7ree:jingcai_7ree&ac_7ree=6&how_7ree=5";
	
}



if(!$_GET['ac_7ree']){


	if (!$_GET['sp_7ree']){
		require_once DISCUZ_ROOT.'./source/plugin/jingcai_7ree/include/hall_7ree.php';
	}elseif($_GET['sp_7ree'] == "more"){
		require_once DISCUZ_ROOT.'./source/plugin/jingcai_7ree/include/page_7ree.php';
	}

}elseif($_GET['ac_7ree'] =="1"){ 

    require_once DISCUZ_ROOT.'./source/plugin/jingcai_7ree/include/my_7ree.php';

}elseif($_GET['ac_7ree'] =="2"){

	require_once DISCUZ_ROOT.'./source/plugin/jingcai_7ree/include/option_7ree.php';

}elseif($_GET['ac_7ree'] =="3"){

	require_once DISCUZ_ROOT.'./source/plugin/jingcai_7ree/include/addrace_7ree.php';

}elseif($_GET['ac_7ree'] =="4"){

	require_once DISCUZ_ROOT.'./source/plugin/jingcai_7ree/include/zhichi_7ree.php';

}elseif($_GET['ac_7ree'] =="5"){
	
	require_once DISCUZ_ROOT.'./source/plugin/jingcai_7ree/include/newrank_7ree.php';

}elseif($_GET['ac_7ree'] =="6"){

	require_once DISCUZ_ROOT.'./source/plugin/jingcai_7ree/include/morerank_7ree.php';

}elseif($_GET['ac_7ree'] =="7"){//我的关注

	require_once DISCUZ_ROOT.'./source/plugin/jingcai_7ree/include/wodeguanzhu_7ree.php';

}elseif($_GET['ac_7ree'] =="8"){//加关注

	require_once DISCUZ_ROOT.'./source/plugin/jingcai_7ree/include/jiaguanzhu_7ree.php';

}elseif($_GET['ac_7ree'] =="9"){//取消关注

	require_once DISCUZ_ROOT.'./source/plugin/jingcai_7ree/include/quguanzhu_7ree.php';

}elseif($_GET['ac_7ree'] =="10"){//查看明细

	require_once DISCUZ_ROOT.'./source/plugin/jingcai_7ree/include/mingxi_7ree.php';


}elseif($_GET['ac_7ree'] =="11"){//会员清空记录

	require_once DISCUZ_ROOT.'./source/plugin/jingcai_7ree/include/qingkong_7ree.php';

}elseif($_GET['ac_7ree'] =="12"){//场次查看支付

	require_once DISCUZ_ROOT.'./source/plugin/jingcai_7ree/include/chakanzhifu_7ree.php';

}elseif($_GET['ac_7ree'] =="13"){//推荐竞猜场次

	require_once DISCUZ_ROOT.'./source/plugin/jingcai_7ree/include/tuituijian_7ree.php';

}elseif($_GET['ac_7ree'] =="14"){//场次竞猜详情

	require_once DISCUZ_ROOT.'./source/plugin/jingcai_7ree/include/xiangqing_7ree.php';

}elseif($_GET['ac_7ree'] =="15"){

	require_once DISCUZ_ROOT.'./source/plugin/jingcai_7ree/include/gengxin_7ree.php';

}elseif($_GET['ac_7ree'] =="16"){//我的推广

	require_once DISCUZ_ROOT.'./source/plugin/jingcai_7ree/include/wodetuiguang_7ree.php';

}elseif($_GET['ac_7ree'] =="17"){//我的分成 

	require_once DISCUZ_ROOT.'./source/plugin/jingcai_7ree/include/wodefencheng_7ree.php';

}elseif($_GET['ac_7ree'] =="18"){//查看收益

	require_once DISCUZ_ROOT.'./source/plugin/jingcai_7ree/include/wodeshouyi_7ree.php';


}elseif($_GET['ac_7ree'] =="19"){//新增、编辑赛程

	require_once DISCUZ_ROOT.'./source/plugin/jingcai_7ree/include/newsaicheng_7ree.php';
	
}else{

	showmessage("Undefined Operation @ 7ree.com");

}


include template('jingcai_7ree:jingcai_index_7ree');
?>