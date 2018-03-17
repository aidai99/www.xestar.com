<?php
/*
	(C)2006-2017 www.7ree.com
	This is NOT a freeware, use is subject to license terms
	Update: 2017/10/12 14:27
	Agreement: http://addon.discuz.com/?@7.developer.doc/agreement_7ree_html
	More Plugins: http://addon.discuz.com/?@7ree
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$vars_7ree = $_G['cache']['plugin']['x7ree_opengs'];

$navtitle = $vars_7ree['navtitle_7ree'];
$folder_7ree = 'source/plugin/x7ree_opengs/upload_img_7ree';


if(!$vars_7ree['agreement_7ree']) showmessage('x7ree_opengs:php_lang_agree_7ree');

$code_7ree = intval($_GET['code_7ree']);
$id_7ree = intval($_GET['id_7ree']);



require_once DISCUZ_ROOT.'./source/plugin/x7ree_opengs/include/function_7ree.php';

$exttitle_7ree = $_G['setting']['extcredits'][$vars_7ree['ext_7ree']][title];

$fenlei_7ree = explode(',', $vars_7ree['fenlei_7ree']);
//print_r($fenlei_7ree);

if(in_array($code_7ree,array("1","9","10","11","12")) && !$id_7ree) showmessage( "ERROR,Missing required parameter.@7ree" );

if(in_array($code_7ree, array("2","4","5","6","7","8","9","10","11","12")) && !$_G['uid']) showmessage('not_loggedin', NULL, array(), array('login' => 1));

if(in_array($code_7ree,array("4","5","6","7","8","9","10","11")) && !$_G['adminid']) showmessage( "Access Deined @7ree" );

if(in_array($code_7ree,array("8","9")) && !submitcheck('submit_7ree')) showmessage("Access Deined @7ree");

if(in_array($code_7ree,array("10","11","12")) && $_GET['formhash']<>FORMHASH) showmessage('Access Deined @7ree');

if(!$code_7ree){//首页

	$finish_7ree = intval($_GET['finish_7ree']);
	$fenlei_get = $_GET['fenlei_7ree'] ? daddslashes(dhtmlspecialchars(trim($_GET['fenlei_7ree']))) : '';
	
	if($finish_7ree){
		$finish_where_7ree="WHERE time_7ree<$_G[timestamp]";
	}else{
		$finish_where_7ree="WHERE time_7ree>=$_G[timestamp]";
	}
	if($fenlei_get){
		$fenlei_where_7ree="AND fenlei_7ree='$fenlei_get'";
	}else{
		$fenlei_where_7ree="";
	}

	$page = max(1, intval($_GET['page']));
	$startpage = ( $page - 1 ) * 20;
	$querynum = DB::result_first( "SELECT Count(*) FROM ".DB::table('x7ree_opengs_main')." {$finish_where_7ree} {$fenlei_where_7ree}");
	$query = DB::query( "SELECT * FROM ".DB::table('x7ree_opengs_main')." {$finish_where_7ree} {$fenlei_where_7ree} ORDER BY id_7ree DESC LIMIT {$startpage},20" );
	while ($list_table = DB::fetch($query)){
			$list_table['time_7ree']=gmdate("Y-m-d H:i", $list_table['time_7ree'] + $_G['setting']['timeoffset'] * 3600);
			$list_7ree[]=$list_table;
	}
	$multipage = multi($querynum, 20, $page, "plugin.php?id=x7ree_opengs:x7ree_opengs&finish_7ree=".$finish_7ree);



}elseif($code_7ree==1){//详情页
	if($id_7ree){
			$this_7ree = DB::fetch_first("SELECT * FROM ".DB::table('x7ree_opengs_main')." WHERE id_7ree='$id_7ree'");
			if(!$this_7ree){
				showmessage('x7ree_opengs:php_lang_noext_7ree');
			}
			
			
			$defaultzc_7ree = explode(',',$vars_7ree['defaultzc_7ree']);

			$ing_7ree = $this_7ree['time_7ree']>$_G['timestamp'] ? TRUE : FALSE;
			
			$this_7ree['time_7ree']=gmdate('Y-m-d H:i',$this_7ree['time_7ree']+$_G['setting']['timeoffset']*3600);
			$query = DB::query("SELECT * FROM  ".DB::table('x7ree_opengs_option')." WHERE rid_7ree='$this_7ree[id_7ree]' ORDER BY id_7ree LIMIT 20");
			while($result = DB::fetch($query)) {
				$list_7ree[] = $result;
			}
			$option_count_7ree = COUNT($list_7ree);
			//统计总参与人数和总支持积分
			$tongji_7ree = DB::fetch_first("SELECT SUM(ext_7ree) AS allext_7ree, COUNT(*) AS allcount_7ree FROM ".DB::table('x7ree_opengs_log')." WHERE rid_7ree='$this_7ree[id_7ree]'");

			//赛果查询
			if($this_7ree['oid_7ree']){
				$this_7ree['option_7ree'] = DB::fetch_first("SELECT * FROM ".DB::table('x7ree_opengs_option')." WHERE id_7ree='$this_7ree[oid_7ree]'");
			}

			//我的竞猜查询
			if($_G['uid']){
				$myoption_7ree = DB::fetch_first("SELECT * FROM ".DB::table('x7ree_opengs_log')." WHERE uid_7ree='$_G[uid]' AND rid_7ree='$id_7ree'");
				if($myoption_7ree){
						$myoption_7ree['option_7ree'] = DB::fetch_first("SELECT * FROM ".DB::table('x7ree_opengs_option')." WHERE id_7ree='$myoption_7ree[oid_7ree]'");
				}
			}

	}


}elseif($code_7ree==2){//我的竞猜


	if($vars_7ree['ext_7ree']){
						$cashcount_7ree = getuserprofile('extcredits'.$vars_7ree['ext_7ree']);
						$exttitle_7ree = $_G['setting']['extcredits'][$vars_7ree['ext_7ree']][title];
						$avatar_7ree=avatar($_G['uid'],small);
						$thistime_7ree = gmdate("Y-m-d H:i", $_G['timestamp'] + $_G['setting']['timeoffset'] * 3600);
	}

	//盈亏计算
	$yingkui_7ree = DB::fetch_first("SELECT SUM(l.ext_7ree) AS all_ext_7ree, SUM(l.reward_7ree) AS all_reward_7ree 
									FROM ".DB::table('x7ree_opengs_log')." l LEFT JOIN 
									".DB::table('x7ree_opengs_main')." m ON m.id_7ree = l.rid_7ree 
									WHERE l.uid_7ree='$_G[uid]' AND m.oid_7ree>0 AND m.time_7ree>='$_G[timestamp]'");
	$yingli_7ree = $yingkui_7ree['all_reward_7ree']-$yingkui_7ree['all_ext_7ree'];
	

	$page = max(1, intval($_GET['page']));
	$startpage = ( $page - 1 ) * 20;
	$querynum = DB::result_first( "SELECT Count(*) FROM ".DB::table('x7ree_opengs_log')." WHERE uid_7ree='$_G[uid]'");
	$query = DB::query( "SELECT * FROM ".DB::table('x7ree_opengs_log')." WHERE uid_7ree='$_G[uid]' ORDER BY id_7ree DESC LIMIT {$startpage},20" );
	while ($list_table = DB::fetch($query)){
			$list_table['time_7ree']=dgmdate($list_table['time_7ree'],'u');
			$list_table['name_7ree']=DB::result_first("SELECT name_7ree FROM ".DB::table('x7ree_opengs_main')." WHERE id_7ree='$list_table[rid_7ree]'");
			$list_table['option_7ree']=DB::result_first("SELECT option_7ree FROM ".DB::table('x7ree_opengs_option')." WHERE id_7ree='$list_table[oid_7ree]'");
			$list_7ree[]=$list_table;
	}
	$multipage = multi($querynum, 20, $page, "plugin.php?id=x7ree_opengs:x7ree_opengs&code_7ree=2" );


}elseif($code_7ree==3){//排行榜

}elseif($code_7ree==4){//管理首页

}elseif($code_7ree==5){//新增，编辑页面

	//查询是否存在jingcai数据表，如果有则读取有效赛事列表
	$jingcaidb_7ree = DB::result_first("SHOW TABLES LIKE '".DB::table('jingcai_main_7ree')."'");
	if($jingcaidb_7ree){
			$query = DB::query("SELECT * FROM  ".DB::table('jingcai_main_7ree')." WHERE time_7ree>$_G[timestamp] ORDER BY main_id_7ree LIMIT 200");
			while($result = DB::fetch($query)) {
				$result['time_7ree']=gmdate("Y-m-d H:i", $result['time_7ree'] + $_G['setting']['timeoffset'] * 3600);
				$jingcailist_7ree[] = $result;
			}
	}
	
	if($id_7ree){
			$this_7ree = DB::fetch_first("SELECT * FROM ".DB::table('x7ree_opengs_main')." WHERE id_7ree='$id_7ree'");
			$this_7ree['time_7ree']=gmdate('Y-m-d H:i',$this_7ree['time_7ree']+$_G['setting']['timeoffset']*3600);
			$query = DB::query("SELECT * FROM  ".DB::table('x7ree_opengs_option')." WHERE rid_7ree='$this_7ree[id_7ree]' ORDER BY id_7ree LIMIT 20");
			while($result = DB::fetch($query)) {
				$list_7ree[] = $result;
			}
			$option_count_7ree = COUNT($list_7ree);
	}
	
	$option_count_7ree = MAX(1,$option_count_7ree+1);

	
}elseif($code_7ree==6){//管理竞猜列表

	$page = max(1, intval($_GET['page']));
	$startpage = ( $page - 1 ) * 20;
	$querynum = DB::result_first( "SELECT Count(*) FROM ".DB::table('x7ree_opengs_main'));
	$query = DB::query( "SELECT * FROM ".DB::table('x7ree_opengs_main')." ORDER BY id_7ree DESC LIMIT {$startpage},20" );
	while ($list_table = DB::fetch($query)){
			$list_table['time_7ree']=dgmdate($list_table['time_7ree'],'u');
			$list_7ree[]=$list_table;
	}
	$multipage = multi($querynum, 20, $page, "plugin.php?id=x7ree_opengs:x7ree_opengs&code_7ree=6" );
	


}elseif($code_7ree==7){//管理日志列表

	$page = max(1, intval($_GET['page']));
	$startpage = ( $page - 1 ) * 20;
	$querynum = DB::result_first( "SELECT Count(*) FROM ".DB::table('x7ree_opengs_log'));
	$query = DB::query( "SELECT * FROM ".DB::table('x7ree_opengs_log')." ORDER BY id_7ree DESC LIMIT {$startpage},20" );
	while ($list_table = DB::fetch($query)){
			$list_table['time_7ree']=dgmdate($list_table['time_7ree'],'u');
			$list_table['name_7ree']=DB::result_first("SELECT name_7ree FROM ".DB::table('x7ree_opengs_main')." WHERE id_7ree='$list_table[rid_7ree]'");
			$list_table['option_7ree']=DB::result_first("SELECT option_7ree FROM ".DB::table('x7ree_opengs_option')." WHERE id_7ree='$list_table[oid_7ree]'");
			$list_7ree[]=$list_table;
	}
	$multipage = multi($querynum, 20, $page, "plugin.php?id=x7ree_opengs:x7ree_opengs&code_7ree=7" );


}elseif($code_7ree==8){//提交新增赛事动作

	$mid_7ree = intval($_GET['mid_7ree']);
	$name_7ree = dhtmlspecialchars(trim($_GET['name_7ree']));
	$fenlei_7ree = dhtmlspecialchars(trim($_GET['fenlei_7ree']));
	$time_7ree = dhtmlspecialchars(trim($_GET['time_7ree']));
	$time_7ree = $time_7ree ? strtotime($time_7ree) : 0;
	$pic_7ree = dhtmlspecialchars(trim($_GET['pic_7ree']));
	$detail_7ree = dhtmlspecialchars(trim($_GET['detail_7ree']));
	
	if(!$name_7ree){
		showmessage('x7ree_opengs:php_lang_noname_7ree');
	}
	if(!$time_7ree){
		showmessage('x7ree_opengs:php_lang_notime_7ree');
	}
	
	$option_num_7ree = COUNT($_GET['option_7ree']);
	if($option_num_7ree<2){
		showmessage('x7ree_opengs:php_lang_no2option_7ree');
	}


	//赛事信息提交
	$valuearray_7ree = array('mid_7ree'=>$mid_7ree,
							 'name_7ree'=>$name_7ree,
							 'fenlei_7ree'=>$fenlei_7ree,
							 'time_7ree'=>$time_7ree,
							 'detail_7ree'=>$detail_7ree,
					);
	$imagearray_7ree = $_FILES['pic_7ree']['size'] ? array('pic_7ree'=>$_FILES['pic_7ree']): array();
	
	$rid_7ree = data_update_7ree('x7ree_opengs_main',$valuearray_7ree,array(),$imagearray_7ree,$folder_7ree);
	

	//赛果选项信息提交
	for($i=0;$i<$option_num_7ree;$i++){
		$option_7ree[$i]=dhtmlspecialchars(trim($_GET['option_7ree'][$i]));
		$odds_7ree[$i]=floatval($_GET['odds_7ree'][$i]);
		if(!$option_7ree[$i] || !$odds_7ree[$i]){
			continue; 
		}
		$option_value_7ree = array('rid_7ree'=>$rid_7ree,
									'option_7ree'=>$option_7ree[$i],
									'odds_7ree'=>$odds_7ree[$i]
							);
		data_update_7ree('x7ree_opengs_option',$option_value_7ree);
	}



	showmessage('x7ree_opengs:php_lang_addok_7ree',"plugin.php?id=x7ree_opengs:x7ree_opengs&code_7ree=6");
	
}elseif($code_7ree==9){//提交编辑赛事动作

	if(!$id_7ree){
		showmessage('ERROR@7REE ### MISS ID.');
	}
	
	$mid_7ree = intval($_GET['mid_7ree']);
	$name_7ree = dhtmlspecialchars(trim($_GET['name_7ree']));
	$fenlei_7ree = dhtmlspecialchars(trim($_GET['fenlei_7ree']));
	$time_7ree = dhtmlspecialchars(trim($_GET['time_7ree']));
	$time_7ree = $time_7ree ? strtotime($time_7ree) : 0;
	$pic_7ree = dhtmlspecialchars(trim($_GET['pic_7ree']));
	$detail_7ree = dhtmlspecialchars(trim($_GET['detail_7ree']));
	$result_7ree = intval($_GET['result_7ree']);
	
	if(!$name_7ree){
		showmessage('x7ree_opengs:php_lang_noname_7ree');
	}
	if(!$time_7ree){
		showmessage('x7ree_opengs:php_lang_notime_7ree');
	}
	
	$option_num_7ree = COUNT($_GET['option_7ree']);


	for($i=0;$i<$option_num_7ree;$i++){
		$option_7ree[$i]=dhtmlspecialchars(trim($_GET['option_7ree'][$i]));
		$odds_7ree[$i]=floatval($_GET['odds_7ree'][$i]);
		$oid_7ree[$i]=intval($_GET['oid_7ree'][$i]);
	}


	//赛事信息编辑提交
	$valuearray_7ree = array('mid_7ree'=>$mid_7ree,
							 'name_7ree'=>$name_7ree,
							 'fenlei_7ree'=>$fenlei_7ree,
							 'time_7ree'=>$time_7ree,
							 'detail_7ree'=>$detail_7ree,
							 'oid_7ree'=>$result_7ree,
					);
	$imagearray_7ree = $_FILES['pic_7ree']['size'] ? array('pic_7ree'=>$_FILES['pic_7ree']): array();
	$wherearray_7ree = array('id_7ree'=>$id_7ree);
	data_update_7ree('x7ree_opengs_main',$valuearray_7ree,$wherearray_7ree,$imagearray_7ree,$folder_7ree);
	
	

	//赛果选项信息编辑提交
	for($i=0;$i<$option_num_7ree;$i++){
		$option_7ree[$i]=dhtmlspecialchars(trim($_GET['option_7ree'][$i]));
		$odds_7ree[$i]=floatval($_GET['odds_7ree'][$i]);
		$oid_7ree[$i]=floatval($_GET['oid_7ree'][$i]);
		if(!$option_7ree[$i] || !$odds_7ree[$i]){
			continue; 
		}
		$option_value_7ree = array('rid_7ree'=>$id_7ree,
									'option_7ree'=>$option_7ree[$i],
									'odds_7ree'=>$odds_7ree[$i]
							);
		
		if($oid_7ree[$i]){
			$wherearray_7ree = array('id_7ree'=>$oid_7ree[$i]);
			data_update_7ree('x7ree_opengs_option',$option_value_7ree,$wherearray_7ree);
		}else{
			data_update_7ree('x7ree_opengs_option',$option_value_7ree);
		}
	}


	//如果揭晓赛果，则同步结算会员奖励
	if($result_7ree){
			$this_7ree = DB::fetch_first("SELECT * FROM ".DB::table('x7ree_opengs_main')." WHERE id_7ree='$id_7ree'");
			if($this_7ree['time_7ree']<$_G['timestamp']){//只结算已完场的赛事
				$query = DB::query("SELECT * FROM ".DB::table('x7ree_opengs_log')." WHERE rid_7ree='$id_7ree' AND oid_7ree='$result_7ree' AND reward_7ree=0 ORDER BY id_7ree LIMIT 2000");
				while ($list_table = DB::fetch($query)){
						if($list_table['uid_7ree'] && $list_table['ext_7ree'] && $list_table['odds_7ree']){
							$reward_7ree = round($list_table['ext_7ree'] * $list_table['odds_7ree']);
							//增加积分
							updatemembercount($list_table['uid_7ree'], array($vars_7ree['ext_7ree'] => $reward_7ree), false);
							//更新领取日志
							$log_value_7ree = array('reward_7ree'=>$reward_7ree,);
							$wherearray_7ree = array('id_7ree'=>$list_table['id_7ree']);
							data_update_7ree('x7ree_opengs_log',$log_value_7ree,$wherearray_7ree);
						}
						$list_123[]=$list_table;
				}

			}

	
	}
	
	

	showmessage('x7ree_opengs:php_lang_editok_7ree',"plugin.php?id=x7ree_opengs:x7ree_opengs&code_7ree=6");

}elseif($code_7ree==10){//删除赛事

	if($id_7ree){
		$wherearray_option_7ree=array('rid_7ree'=>$id_7ree);
		$wherearray_main_7ree=array('id_7ree'=>$id_7ree);
		$imagearray_7ree = array('pic_7ree');
		data_delete_7ree('x7ree_opengs_option',$wherearray_option_7ree);
		data_delete_7ree('x7ree_opengs_main',$wherearray_main_7ree,$imagearray_7ree,$folder_7ree);
	}

	showmessage('x7ree_opengs:php_lang_delok_7ree',"plugin.php?id=x7ree_opengs:x7ree_opengs&code_7ree=6");
}elseif($code_7ree==11){//删除日志


	showmessage('x7ree_opengs:php_lang_editok2_7ree',"plugin.php?id=x7ree_opengs:x7ree_opengs&code_7ree=7");
	
}elseif($code_7ree==12){//会员参与竞猜动作

	//用户组权限检查
	$bangid_7ree = $vars_7ree['gids_7ree'] ? unserialize($vars_7ree['gids_7ree']) : array();
	if($vars_7ree['gids_7ree']){
		if(in_array($_G['groupid'],$bangid_7ree)){
				showmessage('x7ree_opengs:php_lang_bangids_7ree');
		}
	}

	$this_7ree = DB::fetch_first("SELECT * FROM ".DB::table('x7ree_opengs_main')." WHERE id_7ree='$id_7ree'");
	if(!$this_7ree){
		showmessage('x7ree_opengs:php_lang_noext_7ree');
	}
	//截止时间检测
	if($this_7ree['time_7ree']<$_G['timestamp']){
			showmessage('x7ree_opengs:php_lang_finished_7ree');
	}
	
	//重复竞猜检测（先判定后台参数是否需要检查）
	if(!$vars_7ree['duoci_7ree']){
		$repeat_7ree = DB::result_first("SELECT id_7ree FROM ".DB::table('x7ree_opengs_log')." WHERE uid_7ree='$_G[uid]' AND rid_7ree='$id_7ree'");
		if($repeat_7ree){
				showmessage('x7ree_opengs:php_lang_yicanyu_7ree');
		}
	}

	$ext_7ree = intval($_GET['ext_7ree']);
	if(!$ext_7ree){
		showmessage('x7ree_opengs:php_lang_err_zhichifen_7ree');
	}
	
	//支持积分最大值，最小值范围判断。
	if($ext_7ree>$vars_7ree['extmax_7ree']){
		showmessage('x7ree_opengs:php_lang_err_jifentaida_7ree', '', array('extmax_7ree'=>$vars_7ree['extmax_7ree'].$exttitle_7ree));
	}elseif($ext_7ree<$vars_7ree['extmin_7ree']){
		showmessage('x7ree_opengs:php_lang_err_jifentaidi_7ree', '', array('extmin_7ree'=>$vars_7ree['extmin_7ree'].$exttitle_7ree));
	}


	if($vars_7ree['duoxuan_7ree']){//多选

		if(!COUNT($_GET['option_7ree'])){
			showmessage('x7ree_opengs:php_lang_err_xuanjieguo_7ree');
		}else{
			$option_7ree = dintval($_GET['option_7ree'], true);
			$extall_7ree=$ext_7ree*COUNT($_GET['option_7ree']);
		}

	}else{//单选
		$option_7ree = intval($_GET['option_7ree']);
		if(!$option_7ree){
			showmessage('x7ree_opengs:php_lang_err_xuanjieguo_7ree');
		}else{
			$extall_7ree=$ext_7ree;
		}

	}


	//减少积分
	if(extcredit_7ree($vars_7ree['ext_7ree'],$extall_7ree,$_G['uid'])){
			//记录日志
			if($vars_7ree['duoxuan_7ree']){//多选
				foreach($option_7ree AS $option_value){
					$odds_7ree = DB::result_first("SELECT odds_7ree FROM ".DB::table('x7ree_opengs_option')." WHERE id_7ree='$option_value'");
					$log_value_7ree = array('rid_7ree'=>$id_7ree,
										'oid_7ree'=>$option_value,
										'odds_7ree'=>$odds_7ree,
										'uid_7ree'=>$_G['uid'],
										'user_7ree'=>$_G['username'],
										'ext_7ree'=>$ext_7ree,
										'time_7ree'=>$_G['timestamp'],
										);
					data_update_7ree('x7ree_opengs_log',$log_value_7ree);
				}
			}else{
				$odds_7ree = DB::result_first("SELECT odds_7ree FROM ".DB::table('x7ree_opengs_option')." WHERE id_7ree='$option_7ree'");
				$log_value_7ree = array('rid_7ree'=>$id_7ree,
									'oid_7ree'=>$option_7ree,
									'odds_7ree'=>$odds_7ree,
									'uid_7ree'=>$_G['uid'],
									'user_7ree'=>$_G['username'],
									'ext_7ree'=>$ext_7ree,
									'time_7ree'=>$_G['timestamp'],
									);
				data_update_7ree('x7ree_opengs_log',$log_value_7ree);
			}
	}else{
			showmessage('x7ree_opengs:php_lang_err_jifenbuzu_7ree');
	}
	

	$from_7ree=dhtmlspecialchars(trim($_GET['from_7ree']));
	$backurl_7ree = $from_7ree=='jc' ? "plugin.php?id=jingcai_7ree:jingcai_7ree&sp_7ree=more&main_id_7ree=".$this_7ree['mid_7ree'] : "plugin.php?id=x7ree_opengs:x7ree_opengs&code_7ree=1&id_7ree=".$id_7ree;
	showmessage('x7ree_opengs:php_lang_canyuok_7ree',$backurl_7ree);
}else{
	showmessage("Undefined Operation @ 7ree.com");
}

	include template('x7ree_opengs:x7ree_opengs');
?>
