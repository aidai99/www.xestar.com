<?php
/*
	(C)2006-2017 www.7ree.com
	This is NOT a freeware, use is subject to license terms
	Update: 2017/10/3 1:24
	Agreement: http://addon.discuz.com/?@7.developer.doc/agreement_7ree_html
	More Plugins: http://addon.discuz.com/?@7ree
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$vars_7ree = $_G['cache']['plugin']['x7ree_v'];

$navtitle = $vars_7ree['navtitle_7ree'];


if(!$vars_7ree['agreement_7ree']) showmessage('x7ree_v:php_lang_agree_7ree');

$code_7ree = intval($_GET['code_7ree']);
$id_7ree = intval($_GET['id_7ree']);
$folder_7ree = 'source/plugin/x7ree_v/upload_img_7ree';

require_once DISCUZ_ROOT.'./source/plugin/x7ree_v/include/function_7ree.php';

$exttitle_7ree = $_G['setting']['extcredits'][$vars_7ree['ext_7ree']][title];

$fenlei_7ree = explode(',',trim($vars_7ree['fenlei_7ree']));

$gid_7ree = $vars_7ree['gid_7ree'] ? unserialize($vars_7ree['gid_7ree']) : array();
$postgid_7ree = in_array($_G['groupid'],$gid_7ree);


if(in_array($code_7ree, array("2","3","4","5","6","7","8")) && !$_G['uid']) showmessage('not_loggedin', NULL, array(), array('login' => 1));

if(in_array($code_7ree,array("4","5","6")) && !$_G['adminid']) showmessage( "Access Deined @7ree" );

if(in_array($code_7ree,array("1","6")) && !$id_7ree) showmessage( "ERROR,Missing required parameter.@7ree" );

if(in_array($code_7ree,array("5","7")) && !submitcheck('submit_7ree')) showmessage("Access Deined @7ree");

if(in_array($code_7ree,array("6")) && $_GET['formhash']<>FORMHASH) showmessage('Access Deined @7ree');



if(!$code_7ree){//v首页


	if($_GET['searchkey_7ree']){
			$searchkey_7ree = dhtmlspecialchars(trim($_GET['searchkey_7ree']));
			$searchwhere_7ree = "AND name_7ree LIKE  '%{$searchkey_7ree}%'";
	}

	$fenlei = INTVAL($_GET['fenlei']);
	$fenlei_key = max(0,$fenlei-1);
	$fenlei_where_7ree = $fenlei ? "AND fenlei_7ree='{$fenlei_7ree[$fenlei_key]}'":"";

	if($vars_7ree['orderby_7ree']=='1'){//发布顺序
		$orderby_7ree='ORDER BY id_7ree DESC';
	}elseif($vars_7ree['orderby_7ree']=='2'){//随机排序
		$orderby_7ree='ORDER BY rand()';
	}else{
		$orderby_7ree='ORDER BY id_7ree DESC';
	}

	$page = max(1, intval($_GET['page']));
	$startpage = ( $page - 1 ) * 20;
	$querynum = DB::result_first( "SELECT Count(*) FROM ".DB::table('x7ree_v_main')." WHERE status_7ree=1 {$fenlei_where_7ree} {$searchwhere_7ree}");
	$query = DB::query( "SELECT * FROM ".DB::table('x7ree_v_main')." WHERE status_7ree=1 {$fenlei_where_7ree} {$searchwhere_7ree} {$orderby_7ree} LIMIT {$startpage},20" );
	while ($list_table = DB::fetch($query)){
			$list_table['time_7ree']=dgmdate($list_table['time_7ree'],'u');
			$list_7ree[]=$list_table;
	}
	$multipage = multi($querynum, 20, $page, "plugin.php?id=x7ree_v:x7ree_v&fenlei={$fenlei}&searchkey_7ree={$searchkey_7ree}" );



}elseif($code_7ree==1){//v详情页
	$v_7ree = DB::fetch_first("SELECT * FROM ".DB::table('x7ree_v_main')." WHERE id_7ree='$id_7ree' AND status_7ree=1");
	if(!$v_7ree) showmessage('x7ree_v:php_lang_err_missv_7ree');
	
	$navtitle =$v_7ree['name_7ree'].' - '.$navtitle;
	
	$v_7ree['detail_7ree'] = str_replace(PHP_EOL,"<br>", $v_7ree['detail_7ree']);
	$v_7ree['time_7ree']=dgmdate($v_7ree['time_7ree'],'u');
	$v_7ree['avatar_7ree']=avatar($v_7ree['uid_7ree'],small);
	$v_7ree['chatavt_7ree'] = str_replace('<img','<img class="chatavatar_7ree" ',$v_7ree['avatar_7ree']);
	
	//会员是否已付费判断，如已付费或无需付费则解析，否则只显示封面；
	if($_G['uid']){
		//vip用户组免费判定
		$vipgids_7ree = $vars_7ree['vipgids_7ree'] ? unserialize($vars_7ree['vipgids_7ree']) : array();
		if(in_array($_G['groupid'],$vipgids_7ree)){
			$needpay_7ree = FALSE;
		}else{
				$pay_7ree = DB::result_first("SELECT * FROM ".DB::table('x7ree_v_buylog')." WHERE vid_7ree='$id_7ree' AND uid_7ree='$_G[uid]'");
				if($pay_7ree>0){
					$needpay_7ree = FALSE;
				}else{
					$needpay_7ree = TRUE;
				}
		}
	}

	if(defined('IN_MOBILE')){
			$height_7ree='200px';
	}else{
			$height_7ree='400px';
	}
	//视频地址解析
	require_once DISCUZ_ROOT.'./source/plugin/x7ree_v/include/analysis_7ree.php';
	$showplay_7ree=analysis_7ree($v_7ree['url_7ree'],$height_7ree);


	$query = DB::query( "SELECT * FROM ".DB::table('x7ree_v_main')." WHERE status_7ree=1 ORDER BY id_7ree DESC LIMIT 10" );
	while ($list_table = DB::fetch($query)){
			$list_table['time_7ree']=dgmdate($list_table['time_7ree'],'u');
			$list_7ree[]=$list_table;
	}
	
	if($_G['uid']){//播放次数增加
		DB::query("UPDATE ".DB::table('x7ree_v_main')." SET view_7ree=view_7ree+1 WHERE id_7ree ='$id_7ree' LIMIT 1");
	}
	
	//评论读取
	$page = max(1, intval($_GET['page']));
	$startpage = ( $page - 1 ) * 20;
	$querynum = DB::result_first( "SELECT Count(*) FROM ".DB::table('x7ree_v_discuss')." WHERE vid_7ree='$v_7ree[id_7ree]'");
	$query = DB::query( "SELECT * FROM ".DB::table('x7ree_v_discuss')." WHERE vid_7ree='$v_7ree[id_7ree]' ORDER BY id_7ree DESC LIMIT {$startpage},20" );
	while ($query_table = DB::fetch($query)){
			$query_table['time_7ree']=dgmdate($query_table['time_7ree'],'u');
			$query_table['avatar_7ree'] = avatar($query_table['uid_7ree'], small);
			$query_table['avatar_7ree'] = str_replace('<img','<img class="discussavatar_7ree" ',$query_table['avatar_7ree']);

			$discuzlist_7ree[]=$query_table;
	}

	$multipage = multi($querynum, 20, $page, "plugin.php?id=x7ree_v:x7ree_v&code_7ree=1&id_7ree=".$v_7ree['id_7ree']);
	
	

}elseif($code_7ree==2){//发布，编辑

	if(!$postgid_7ree) showmessage('x7ree_v:php_lang_err_denied_7ree');

	if($id_7ree){
		$v_7ree = DB::fetch_first("SELECT * FROM ".DB::table('x7ree_v_main')." WHERE id_7ree = ".$id_7ree);
		$uid_7ree=$v_7ree['uid_7ree'];
		$user_7ree=$v_7ree['user_7ree'];
		if($_G['adminid']){
			$backurl_7ree='plugin.php?id=x7ree_v:x7ree_v&code_7ree=4';
		}else{
		$backurl_7ree='plugin.php?id=x7ree_v:x7ree_v&code_7ree=3';
		}
	}else{
		$uid_7ree=$_G['uid'];
		$user_7ree=$_G['username'];
		$backurl_7ree='plugin.php?id=x7ree_v:x7ree_v&code_7ree=2';
	}
	
	if(submitcheck('submit_7ree')){
		
	//录入过滤
	$name_7ree = dhtmlspecialchars(trim($_GET['name_7ree']));
	$detail_7ree = dhtmlspecialchars(trim($_GET['detail_7ree']));
	$fenlei_7ree = dhtmlspecialchars(trim($_GET['fenlei_7ree']));
	$url_7ree = dhtmlspecialchars(trim($_GET['url_7ree']));
	$cost_7ree = intval($_GET['cost_7ree']);

	if(!$name_7ree) showmessage('x7ree_v:php_lang_err_missname_7ree');
	if(!$url_7ree) showmessage('x7ree_v:php_lang_err_missurl_7ree');
	
	$valuearray_7ree = array(
								'name_7ree'=>$name_7ree,
								'detail_7ree'=>$detail_7ree,
								'fenlei_7ree'=>$fenlei_7ree,
								'cost_7ree'=>$cost_7ree,
								'url_7ree'=>$url_7ree,
								'uid_7ree'=>$uid_7ree,
								'user_7ree'=>$user_7ree,
								'time_7ree'=>$_G['timestamp'],
							);

	$imagearray_7ree = $_FILES['pic_7ree']['size'] ? array('pic_7ree'=>$_FILES['pic_7ree']): array();

	if($id_7ree){//提交编辑
		$wherearray_7ree = array('id_7ree'=>$id_7ree);
		data_update_7ree('x7ree_v_main',$valuearray_7ree,$wherearray_7ree,$imagearray_7ree,$folder_7ree);
	}else{//提交新增
		data_update_7ree('x7ree_v_main',$valuearray_7ree,array(),$imagearray_7ree,$folder_7ree);
	}
	
	
		showmessage('x7ree_v:php_lang_ok_submit_7ree',$backurl_7ree);
	}
	
}elseif($code_7ree==3){//我的v

	$page = max(1, intval($_GET['page']));
	$startpage = ( $page - 1 ) * 20;
	$querynum = DB::result_first( "SELECT Count(*) FROM ".DB::table('x7ree_v_main')." WHERE uid_7ree='$_G[uid]'");
	$query = DB::query( "SELECT * FROM ".DB::table('x7ree_v_main')." WHERE uid_7ree='$_G[uid]' ORDER BY id_7ree DESC LIMIT {$startpage},20" );
	while ($list_table = DB::fetch($query)){
			$list_table['time_7ree']=dgmdate($list_table['time_7ree'],'u');
			$list_7ree[]=$list_table;
	}
	$multipage = multi($querynum, 20, $page, "plugin.php?id=x7ree_v:x7ree_v&code_7ree=3" );
	
}elseif($code_7ree==4){//前台管理v

	$page = max(1, intval($_GET['page']));
	$startpage = ( $page - 1 ) * 20;
	$querynum = DB::result_first( "SELECT Count(*) FROM ".DB::table('x7ree_v_main'));
	$query = DB::query( "SELECT * FROM ".DB::table('x7ree_v_main')." ORDER BY id_7ree DESC LIMIT {$startpage},20" );
	while ($list_table = DB::fetch($query)){
			$list_table['time_7ree']=dgmdate($list_table['time_7ree'],'u');
			$list_7ree[]=$list_table;
	}
	$multipage = multi($querynum, 20, $page, "plugin.php?id=x7ree_v:x7ree_v&code_7ree=4" );
	
}elseif($code_7ree==5){//批量审核v动作
		if(count($_GET['mod_7ree'])){
			$mod_7ree = dintval((array)$_GET['mod_7ree'], true);
			if($vars_7ree['ext_7ree'] && $vars_7ree['reward_7ree']){
					$query = DB::query( "SELECT uid_7ree FROM ".DB::table('x7ree_v_main')." WHERE id_7ree IN(".dimplode($mod_7ree).") AND status_7ree=0 ORDER BY id_7ree DESC LIMIT 100" );
					while ($list_table = DB::fetch($query)){
						updatemembercount($list_table['uid_7ree'], array($vars_7ree['ext_7ree'] => $vars_7ree['reward_7ree']));
					}
			}
			DB::query("UPDATE ".DB::table('x7ree_v_main')." SET status_7ree = 1 WHERE id_7ree IN(".dimplode($mod_7ree).")");
			showmessage('x7ree_v:php_lang_ok_check_7ree',"plugin.php?id=x7ree_v:x7ree_v&code_7ree=4");
		}else{
			showmessage('x7ree_v:php_lang_err_ckok_7ree',"plugin.php?id=x7ree_v:x7ree_v&code_7ree=4");

		}
		showmessage('x7ree_v:php_lang_ok_edit_7ree',"plugin.php?id=x7ree_v:x7ree_v&code_7ree=4");
	
}elseif($code_7ree==6){//删除v动作
		$wherearray_7ree = array('id_7ree'=>$id_7ree);
		$imagearray_7ree = array('pic_7ree');
		data_delete_7ree('x7ree_v_main',$wherearray_7ree,$imagearray_7ree,$folder_7ree);
		showmessage('x7ree_v:php_lang_ok_del_7ree',"plugin.php?id=x7ree_v:x7ree_v&code_7ree=4");
	
}elseif($code_7ree==7){//发表评论
	//录入过滤
	$message_7ree = dhtmlspecialchars(trim($_GET['message_7ree']));
	if(!$message_7ree){
		showmessage('x7ree_v:php_lang_err_mgs_7ree');
	}
	
	$valuearray_7ree = array(
								'vid_7ree'=>$id_7ree,
								'message_7ree'=>$message_7ree,
								'uid_7ree'=>$_G['uid'],
								'user_7ree'=>$_G['username'],
								'time_7ree'=>$_G['timestamp'],
							);
	data_update_7ree('x7ree_v_discuss',$valuearray_7ree,array());
	$discussnum_7ree=DB::result_first("SELECT COUNT(*) FROM ".DB::table('x7ree_v_discuss')." WHERE vid_7ree='$id_7ree'");
	$updatevalue_7ree=array('discuss_7ree'=>$discussnum_7ree);
	$wherevalue_7ree=array('id_7ree'=>$id_7ree);
	data_update_7ree('x7ree_v_main',$updatevalue_7ree,$wherevalue_7ree);
	showmessage('x7ree_v:php_lang_ok_discuz_7ree',"plugin.php?id=x7ree_v:x7ree_v&code_7ree=1&id_7ree=".$id_7ree);
	
}elseif($code_7ree==8){//会员付费动作
	$v_7ree = DB::fetch_first("SELECT * FROM ".DB::table('x7ree_v_main')." WHERE id_7ree='$id_7ree'");

	if($v_7ree['cost_7ree']){//积分检测与消耗
		if(extcredit_7ree($vars_7ree['ext_7ree'],$v_7ree['cost_7ree'],$_G['uid'])){
			//积分受让给发布者
			updatemembercount($v_7ree['uid_7ree'],array($vars_7ree['ext_7ree'] => $v_7ree['cost_7ree']));
			//写入购买日志
			$valuearray_7ree = array(
										'vid_7ree'=>$id_7ree,
										'cost_7ree'=>$v_7ree['cost_7ree'],
										'uid_7ree'=>$_G['uid'],
										'user_7ree'=>$_G['username'],
										'time_7ree'=>$_G['timestamp'],
									);
			data_update_7ree('x7ree_v_buylog',$valuearray_7ree,array());
			showmessage('x7ree_v:php_lang_ok_pay_7ree',"plugin.php?id=x7ree_v:x7ree_v&code_7ree=1&id_7ree=".$id_7ree);
		}else{
		showmessage('##ERROR@7REE Bad Operation.');
		}
	}


}else{
	showmessage("Undefined Operation @ 7ree.com");
}

	include template('x7ree_v:x7ree_v');
?>