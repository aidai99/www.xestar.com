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

//ֻ�����ֻ�����
//if(!defined('IN_MOBILE') && $_G['adminid']!=1) showmessage('����ͨ���ֻ��ͻ��˷��ʾ���ϵͳ��');

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
	
	//���´���
	$dating_url_7ree = "plugin.php?id=jingcai_7ree:jingcai_7ree";
	//���ھ���
	$wangqiurl_7ree = "plugin.php?id=jingcai_7ree:jingcai_7ree&finish_7ree=1";
	//�ҵľ���
	$wdjc_url_7ree = "plugin.php?id=jingcai_7ree:jingcai_7ree&ac_7ree=1";
	//�ҵĹ�ע
	$wdgz_url_7ree = "plugin.php?id=jingcai_7ree:jingcai_7ree&ac_7ree=7";
	//��������
	$xiangqing_url_7ree = "plugin.php?id=jingcai_7ree:jingcai_7ree&sp_7ree=more&main_id_7ree=";
	//���¼�¼
	$jilu_url_7ree = "plugin.php?id=jingcai_7ree:jingcai_7ree&ac_7ree=10&uid_7ree=";
	//���¸���
	$gaoshou_url_7ree = "plugin.php?id=jingcai_7ree:jingcai_7ree&ac_7ree=14&main_id_7ree=";
	//��ӯ�����а�
	$zyl_url_7ree = "plugin.php?id=jingcai_7ree:jingcai_7ree&ac_7ree=6&how_7ree=1";
	//��ӯ�����а�
	$jyl_url_7ree = "plugin.php?id=jingcai_7ree:jingcai_7ree&ac_7ree=6&how_7ree=2";
	//��Ӯ�������а�
	$cangci_url_7ree = "plugin.php?id=jingcai_7ree:jingcai_7ree&ac_7ree=6&how_7ree=3";
	//���������а�
	$mingzhong_url_7ree = "plugin.php?id=jingcai_7ree:jingcai_7ree&ac_7ree=6&how_7ree=4";
	//�ص���ʤ���а�
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

}elseif($_GET['ac_7ree'] =="7"){//�ҵĹ�ע

	require_once DISCUZ_ROOT.'./source/plugin/jingcai_7ree/include/wodeguanzhu_7ree.php';

}elseif($_GET['ac_7ree'] =="8"){//�ӹ�ע

	require_once DISCUZ_ROOT.'./source/plugin/jingcai_7ree/include/jiaguanzhu_7ree.php';

}elseif($_GET['ac_7ree'] =="9"){//ȡ����ע

	require_once DISCUZ_ROOT.'./source/plugin/jingcai_7ree/include/quguanzhu_7ree.php';

}elseif($_GET['ac_7ree'] =="10"){//�鿴��ϸ

	require_once DISCUZ_ROOT.'./source/plugin/jingcai_7ree/include/mingxi_7ree.php';


}elseif($_GET['ac_7ree'] =="11"){//��Ա��ռ�¼

	require_once DISCUZ_ROOT.'./source/plugin/jingcai_7ree/include/qingkong_7ree.php';

}elseif($_GET['ac_7ree'] =="12"){//���β鿴֧��

	require_once DISCUZ_ROOT.'./source/plugin/jingcai_7ree/include/chakanzhifu_7ree.php';

}elseif($_GET['ac_7ree'] =="13"){//�Ƽ����³���

	require_once DISCUZ_ROOT.'./source/plugin/jingcai_7ree/include/tuituijian_7ree.php';

}elseif($_GET['ac_7ree'] =="14"){//���ξ�������

	require_once DISCUZ_ROOT.'./source/plugin/jingcai_7ree/include/xiangqing_7ree.php';

}elseif($_GET['ac_7ree'] =="15"){

	require_once DISCUZ_ROOT.'./source/plugin/jingcai_7ree/include/gengxin_7ree.php';

}elseif($_GET['ac_7ree'] =="16"){//�ҵ��ƹ�

	require_once DISCUZ_ROOT.'./source/plugin/jingcai_7ree/include/wodetuiguang_7ree.php';

}elseif($_GET['ac_7ree'] =="17"){//�ҵķֳ� 

	require_once DISCUZ_ROOT.'./source/plugin/jingcai_7ree/include/wodefencheng_7ree.php';

}elseif($_GET['ac_7ree'] =="18"){//�鿴����

	require_once DISCUZ_ROOT.'./source/plugin/jingcai_7ree/include/wodeshouyi_7ree.php';


}elseif($_GET['ac_7ree'] =="19"){//�������༭����

	require_once DISCUZ_ROOT.'./source/plugin/jingcai_7ree/include/newsaicheng_7ree.php';
	
}else{

	showmessage("Undefined Operation @ 7ree.com");

}


include template('jingcai_7ree:jingcai_index_7ree');
?>