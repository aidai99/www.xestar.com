<?php
/*
	[www.7ree.com] (C)2007-2017 7ree.com.
	This is NOT a freeware, use is subject to license terms
	Update: 2017/8/19 15:10
	Agreement: http://addon.discuz.com/?@7.developer.doc/agreement_7ree_html
	More Plugins: http://addon.discuz.com/?@7ree
*/
// 柒瑞积分提现系统

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$vars_7ree = $_G['cache']['plugin']['x7ree_cash'];

//财务管理用户组
$caiwugids_7ree = $vars_7ree['caiwugids_7ree'] ? unserialize($vars_7ree['caiwugids_7ree']) : array();


//$navtitle = $vars_7ree['navtitle_7ree'];
$navtitle = "积分提现";
$exttitle_7ree = $_G['setting']['extcredits'][$vars_7ree['ext_7ree']][title];
//if(!$vars_7ree['agreement_7ree']) showmessage('x7ree_download:php_lang_agree_7ree');


$code_7ree = intval($_GET['code_7ree']);
$id_7ree = intval($_GET['id_7ree']);




//是否已登录操作检测
if(!$_G['uid']) {
	showmessage('not_loggedin', NULL, array(), array('login' => 1));
}
//用户组检测
if($vars_7ree['group_7ree']){
				$group_7ree = unserialize($vars_7ree['group_7ree']);
				if(!in_array($_G['groupid'],$group_7ree) && $_G['adminid']<>1) showmessage('抱歉，您所在用户组无法进行提现操作，请返回。');
}

//管理员操作检测
if(in_array($code_7ree,array('3','4','5')) && ($_G['adminid']<>1 && !in_array($_G['groupid'],$caiwugids_7ree))) showmessage('Access Denied @ 7ree');

//id丢失检测
if(in_array($code_7ree,array('4','5')) && !$id_7ree) showmessage("ERROR@7REE, MISS ID.");


//submit检测
if (in_array($code_7ree,array('1')) && !submitcheck('submit_7ree')) showmessage('Access Denied @ 7ree');

//del检测
if(in_array($code_7ree,array('4','5')) && $_GET['formhash'] <> FORMHASH) showmessage("Access Deined @ 7ree");



$pagenum_7ree = 20;

if(!$code_7ree){//提现申请页面
	$membercount_7ree = getuserprofile('extcredits'.$vars_7ree['ext_7ree']);


}elseif($code_7ree==1){//提交申请动作
		$ext_7ree = intval($_GET['ext_7ree']);
		//$cash_7ree = round(floatval($_GET['cash_7ree']),2);
		$type_7ree = intval($_GET['type_7ree']);
		$account_7ree = dhtmlspecialchars(trim($_GET['account_7ree']));
		$tel_7ree = dhtmlspecialchars(trim($_GET['tel_7ree']));
		//根据积分计算提现数量
		$cash_7ree = round(($ext_7ree*($vars_7ree['rate_7ree']/100)),2);
		if(!$ext_7ree || !$cash_7ree || !$type_7ree || !$account_7ree || !$tel_7ree) showmessage("抱歉，提现信息不完整，请返回完善后再提交。");

		$mytest_7ree = DB::fetch_first("SELECT COUNT(*) AS count_7ree, SUM(ext_7ree) AS sum_7ree FROM ".DB::table('x7ree_cash')." WHERE uid_7ree='$_G[uid]' AND time_7ree>$_G[timestamp]-86400");
		//频率检测
		if($vars_7ree['maxnum_7ree']){
			if($mytest_7ree['count_7ree']>$vars_7ree['maxnum_7ree']) showmessage("抱歉，您24小时内提现次数已达最大，请稍后再试。");
		}
		//每日提现总额度检测
		if($vars_7ree['maxext_7ree']){
			if($mytest_7ree['sum_7ree']>$vars_7ree['maxext_7ree']) showmessage("抱歉，您24小时内提现总积分已达最大，请稍后再试。");
		}
		//积分数量是否足够检测
		$membercount_7ree = getuserprofile('extcredits'.$vars_7ree['ext_7ree']);
		if($membercount_7ree < $ext_7ree){
				showmessage('抱歉，您的积分不够，请返回。');
		}else{//积分扣除
				updatemembercount($_G['uid'], array($vars_7ree['ext_7ree'] => "-".$ext_7ree), false, 'REM',7);
		}

		//日志入库
		$insertvalue_7ree = array(
							'uid_7ree' => $_G['uid'],
							'user_7ree' => $_G['username'],
							'time_7ree'=> $_G['timestamp'],
							'ext_7ree'=> $ext_7ree,
							'cash_7ree'=> $cash_7ree,
							'type_7ree'=> $type_7ree,
							'account_7ree'=> $account_7ree,
							'tel_7ree'=> $tel_7ree
		);
		DB::insert('x7ree_cash', $insertvalue_7ree);
		$id_7ree = DB::insert_id();
		showmessage('恭喜，您已成功申请了提现，请等待审核。',"plugin.php?id=x7ree_cash:x7ree_cash&code_7ree=2");


}elseif($code_7ree==2){//提现进度用户查询页面
			$page = max(1, intval($_GET['page']));
			$startpage = ($page - 1) * 20;
			$querynum = DB::result_first("SELECT Count(*) FROM ".DB::table('x7ree_cash')." WHERE uid_7ree = '{$_G[uid]}'");
			$query = DB::query("SELECT * FROM ".DB::table('x7ree_cash')." WHERE uid_7ree = '{$_G[uid]}' ORDER BY id_7ree DESC LIMIT {$startpage}, {$pagenum_7ree}");
			while($result = DB::fetch($query)) {
				$result['time_7ree']=gmdate("m-d H:i", $result['time_7ree'] + $_G['setting']['timeoffset'] * 3600);
				$result['cash_7ree'] = sprintf("%.2f", $result['cash_7ree']);
				$list_7ree[] = $result;
			}
			$multipage = multi($querynum, $pagenum_7ree, $page, "plugin.php?id=x7ree_cash:x7ree_cash&code_7ree=2" );



}elseif($code_7ree==3){//提现进度管理页面
			$page = max(1, intval($_GET['page']));
			$startpage = ($page - 1) * 20;
			$querynum = DB::result_first("SELECT Count(*) FROM ".DB::table('x7ree_cash'));
			$query = DB::query("SELECT * FROM ".DB::table('x7ree_cash')." ORDER BY id_7ree DESC LIMIT {$startpage}, {$pagenum_7ree}");
			while($result = DB::fetch($query)) {
				$result['time_7ree']=gmdate("Y-m-d H:i", $result['time_7ree'] + $_G['setting']['timeoffset'] * 3600);
				$result['cash_7ree'] = sprintf("%.2f", $result['cash_7ree']);
				$list_7ree[] = $result;
			}
			$multipage = multi($querynum, $pagenum_7ree, $page, "plugin.php?id=x7ree_cash:x7ree_cash&code_7ree=3" );


}elseif($code_7ree==4){//提现审核动作

		DB::query("UPDATE ".DB::table('x7ree_cash')." SET status_7ree=1 WHERE id_7ree='{$id_7ree}'");
		showmessage('恭喜，您已确认审核成功这条提现。',"plugin.php?id=x7ree_cash:x7ree_cash&code_7ree=3");

}elseif($code_7ree==5){//拒绝审核动作
		$backinfo_7ree = DB::fetch_first("SELECT uid_7ree,ext_7ree FROM ".DB::table('x7ree_cash')." WHERE id_7ree='{$id_7ree}'");
		updatemembercount($backinfo_7ree['uid_7ree'], array($vars_7ree['ext_7ree'] => $backinfo_7ree['ext_7ree']), false, 'REN',7);
		DB::query("UPDATE ".DB::table('x7ree_cash')." SET status_7ree=2 WHERE id_7ree='{$id_7ree}'");
		showmessage('驳回操作成功。',"plugin.php?id=x7ree_cash:x7ree_cash&code_7ree=3");

//}elseif($code_7ree==6){



}else{
		showmessage("Undefined Operation @ 7ree.com");
}

		include template('x7ree_cash:x7ree_cash');

?>