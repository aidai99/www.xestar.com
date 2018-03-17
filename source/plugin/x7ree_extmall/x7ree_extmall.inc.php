<?php
/*
	(C)2006-2017 www.7ree.com
	This is NOT a freeware, use is subject to license terms
	Update: 2017/12/19 17:13
	Agreement: http://addon.discuz.com/?@7.developer.doc/agreement_7ree_html
	More Plugins: http://addon.discuz.com/?@7ree
*/
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$vars_7ree = $_G['cache']['plugin']['x7ree_extmall'];

$navtitle = $vars_7ree['navtitle_7ree'];

if(!$vars_7ree['agreement_7ree']) showmessage('x7ree_extmall:php_lang_agree_7ree');

$code_7ree = intval($_GET['code_7ree']);
$id_7ree = intval($_GET['id_7ree']);
$folder_7ree = 'source/plugin/x7ree_extmall/upload_img_7ree';

require_once DISCUZ_ROOT.'./source/plugin/x7ree_extmall/include/function_7ree.php';

$exttitle_7ree = $_G['setting']['extcredits'][$vars_7ree['ext_7ree']][title];

if(in_array($code_7ree, array("1","2","3","4","5","6","7","8","9","11") ) && !$_G['uid'] ) showmessage('not_loggedin', NULL, array(), array('login' => 1));

if(!$_G['adminid'] && in_array($code_7ree, array("3","4","5","6","7","8","9","11") )) showmessage( "Access Deined @7ree" );


if(!$id_7ree && in_array($code_7ree, array("1","6","10"))) showmessage( "ERROR,Missing required parameter.@7ree" );

if(!submitcheck('submit_7ree') && (in_array($code_7ree, array("5")))) showmessage("Access Deined @7ree");

/*
if (($_GET['formhash'] <> FORMHASH) && (in_array($_GET['code'], array("4","12")))) showmessage('Access Deined @7ree');
*/

$fenlei_array = explode(',', trim($vars_7ree['fenlei_7ree']));

if(!$code_7ree){//积分商城首页
	$fenlei_7ree = intval($_GET['fenlei_7ree']);
	if($fenlei_7ree>0){
		$fenlei2_7ree = max($fenlei_7ree-1,0);
		$fenlei_name = $fenlei_array[$fenlei2_7ree];
		$fenlei_where_7ree = $fenlei_name ? "WHERE fenlei_7ree = '{$fenlei_name}'" : '';
		echo($fenlei_where_7ree);
	}
	$page = max(1, intval($_GET['page']));
	$startpage = ( $page - 1 ) * 20;
	$querynum = DB::result_first( "SELECT Count(*) FROM ".DB::table('extmall_goods_7ree')." {$fenlei_where_7ree}");
	$query = DB::query( "SELECT * FROM ".DB::table('extmall_goods_7ree')." {$fenlei_where_7ree} ORDER BY displayorder_7ree DESC, id_7ree DESC LIMIT {$startpage},20" );
	while ($list_table = DB::fetch($query)){
			$list_7ree[]=$list_table;
	}
	$multipage = multi($querynum, 20, $page, "plugin.php?id=x7ree_extmall:x7ree_extmall" );
	
	
}elseif($code_7ree==1){//积分兑换详情页

	if($id_7ree){
			$thisgoods_7ree=DB::fetch_first("SELECT * FROM ".DB::table('extmall_goods_7ree')." WHERE id_7ree='$id_7ree'");
			if(!$thisgoods_7ree) showmessage("抱歉，查询的商品不存在。");
			//重复兑换检测
			$mylog_7ree = DB::result_first("SELECT id_7ree FROM ".DB::table('extmall_log_7ree')." WHERE gid_7ree='$id_7ree' AND uid_7ree='$_G[uid]'");
			if(submitcheck('submit_7ree')){//提交兑换动作
					if($vars_7ree['norepeat_7ree']){
							if($mylog_7ree) showmessage("抱歉，您已经兑换了此商品，请勿重复兑换。");
					}
					//提交地址信息过滤与检测
					$realname = dhtmlspecialchars(trim($_GET['realname']));
					$mobile = dhtmlspecialchars(trim($_GET['mobile']));
					$address = dhtmlspecialchars(trim($_GET['address']));
					$qq= dhtmlspecialchars(trim($_GET['qq']));
					$site = dhtmlspecialchars(trim($_GET['site']));
					if(!$realname || !$mobile ) showmessage("姓名、电话、地址都必须填写，请返回修改。");
					if($thisgoods_7ree['num_7ree']<1) showmessage("抱歉商品库存不足，无法兑换，请返回看看其他商品。");

					//积分检测扣除
					if(extcredit_7ree($vars_7ree['ext_7ree'],$thisgoods_7ree['cost_7ree'],$_G['uid'],'REO',7)){
							//库存数量减少
							DB::query("UPDATE ".DB::table('extmall_goods_7ree')." SET num_7ree = num_7ree -1 WHERE id_7ree='$id_7ree'");
							//个人信息更新
							DB::query("UPDATE ".DB::table('common_member_profile')." SET realname='$realname', mobile='$mobile', address='$address',qq='$qq',site='$site' WHERE uid='$_G[uid]'");
							//个人兑换日志写入
							$valuearray_7ree=array(
													'gid_7ree'=>$thisgoods_7ree['id_7ree'],
													'uid_7ree'=>$_G['uid'],
													'user_7ree'=>$_G['username'],
													'ip_7ree'=>$_G['clientip'],
													'cost_7ree'=>$thisgoods_7ree['cost_7ree'],
													'time_7ree'=>$_G['timestamp'],
							);
							data_update_7ree('extmall_log_7ree',$valuearray_7ree,array(),array(),'');
							
							showmessage("恭喜，您成功兑换了商品现在返回。","plugin.php?id=x7ree_extmall:x7ree_extmall&code_7ree=2");
					}else{
							showmessage("抱歉，您的积分不足，无法兑换。");
					}

			}else{//页面
					$thisgoods_7ree['detail_7ree'] = str_replace(PHP_EOL,"<br>", $thisgoods_7ree['detail_7ree']);
					$myaddress_7ree=DB::fetch_first("SELECT realname,mobile,address,site,qq FROM ".DB::table('common_member_profile')." WHERE uid='$_G[uid]'");
			}

	}


}elseif($code_7ree==2){//我的商品页

	$page = max(1, intval($_GET['page']));
	$startpage = ( $page - 1 ) * 20;
	$querynum = DB::result_first( "SELECT Count(*) FROM ".DB::table('extmall_log_7ree')." WHERE uid_7ree='$_G[uid]'");
	$query = DB::query( "SELECT * FROM ".DB::table('extmall_log_7ree')." WHERE uid_7ree='$_G[uid]' ORDER BY id_7ree DESC LIMIT {$startpage},20" );
	while ($list_table = DB::fetch($query)){
			$list_table['time_7ree']=dgmdate($list_table['time_7ree'],'u');
			$list_table['name_7ree']=DB::result_first("SELECT name_7ree FROM ".DB::table('extmall_goods_7ree')." WHERE id_7ree='{$list_table[gid_7ree]}'");
			$list_7ree[]=$list_table;
	}
	$multipage = multi($querynum, 20, $page, "plugin.php?id=x7ree_extmall:x7ree_extmall&code_7ree=2" );


}elseif($code_7ree==3){//管理首页：商品列表

	$page = max(1, intval($_GET['page']));
	$startpage = ( $page - 1 ) * 20;
	$querynum = DB::result_first( "SELECT Count(*) FROM ".DB::table('extmall_goods_7ree'));
	$query = DB::query( "SELECT * FROM ".DB::table('extmall_goods_7ree')." ORDER BY id_7ree DESC LIMIT {$startpage},20" );
	while ($list_table = DB::fetch($query)){
			$list_7ree[]=$list_table;
	}
	$multipage = multi($querynum, 20, $page, "plugin.php?id=x7ree_extmall:x7ree_extmall&code_7ree=3" );
	

}elseif($code_7ree==4){//管理页面：新增、编辑

	if($id_7ree){
			$thisgoods_7ree=DB::fetch_first("SELECT * FROM ".DB::table('extmall_goods_7ree')." WHERE id_7ree='$id_7ree'");
	}

}elseif($code_7ree==5){//管理功能：提交商品

	//录入过滤
	$name_7ree = dhtmlspecialchars(trim($_GET['name_7ree']));
	$fenlei_7ree = dhtmlspecialchars(trim($_GET['fenlei_7ree']));
	$detail_7ree = dhtmlspecialchars(trim($_GET['detail_7ree']));
	$price_7ree = floatval($_GET['price_7ree']);
	$cost_7ree = intval($_GET['cost_7ree']);
	$num_7ree = intval($_GET['num_7ree']);
	$displayorder_7ree = intval($_GET['displayorder_7ree']);
	if(!$name_7ree) showmessage("商品名称必须填写，请返回修改。");
	
	$valuearray_7ree = array(
								'name_7ree'=>$name_7ree,
								'fenlei_7ree'=>$fenlei_7ree,
								'detail_7ree'=>$detail_7ree,
								'cost_7ree'=>$cost_7ree,
								'price_7ree'=>$price_7ree,
								'num_7ree'=>$num_7ree,
								'displayorder_7ree'=>$displayorder_7ree,
							);

	$imagearray_7ree = $_FILES['pic_7ree']['size'] ? array('pic_7ree'=>$_FILES['pic_7ree']): array();

	if($id_7ree){//提交编辑
		$wherearray_7ree = array('id_7ree'=>$id_7ree);
		data_update_7ree('extmall_goods_7ree',$valuearray_7ree,$wherearray_7ree,$imagearray_7ree,$folder_7ree);
	}else{//提交新增
		data_update_7ree('extmall_goods_7ree',$valuearray_7ree,array(),$imagearray_7ree,$folder_7ree);
	}
	//入库函数

	showmessage("恭喜，您成功提交了商品，现在返回。","plugin.php?id=x7ree_extmall:x7ree_extmall&code_7ree=3");

}elseif($code_7ree==6){//管理功能：删除商品
	$wherearray_7ree = array('id_7ree'=>$id_7ree);
	$imagearray_7ree = array('pic_7ree');
	data_delete_7ree('extmall_goods_7ree',$wherearray_7ree,$imagearray_7ree,$folder_7ree);
	showmessage("恭喜，您成功删除了商品，现在返回。","plugin.php?id=x7ree_extmall:x7ree_extmall&code_7ree=3");

}elseif($code_7ree==7){//管理功能：日志列表

	$page = max(1, intval($_GET['page']));
	$startpage = ( $page - 1 ) * 20;
	$querynum = DB::result_first( "SELECT Count(*) FROM ".DB::table('extmall_log_7ree'));
	$query = DB::query( "SELECT * FROM ".DB::table('extmall_log_7ree')." ORDER BY id_7ree DESC LIMIT {$startpage},20" );
	while ($list_table = DB::fetch($query)){
			$list_table['time_7ree']=dgmdate($list_table['time_7ree'],'u');
			$list_table['address_7ree']=DB::fetch_first("SELECT realname,mobile,address,qq,site FROM ".DB::table('common_member_profile')." WHERE uid='$list_table[uid_7ree]]'");
			$list_table['name_7ree']=DB::result_first("SELECT name_7ree FROM ".DB::table('extmall_goods_7ree')." WHERE id_7ree='{$list_table[gid_7ree]}'");
			$list_7ree[]=$list_table;
	}
	$multipage = multi($querynum, 20, $page, "plugin.php?id=x7ree_extmall:x7ree_extmall&code_7ree=7" );
	


	
}elseif($code_7ree==8){//管理功能：删除日志




}elseif($code_7ree==9){//管理功能：退货列表
	$page = max(1, intval($_GET['page']));
	$startpage = ( $page - 1 ) * 20;
	$querynum = DB::result_first( "SELECT Count(*) FROM ".DB::table('extmall_log_7ree')." WHERE status_7ree>0");
	$query = DB::query( "SELECT * FROM ".DB::table('extmall_log_7ree')." WHERE status_7ree>0 ORDER BY id_7ree DESC LIMIT {$startpage},20" );
	while ($list_table = DB::fetch($query)){
			$list_table['time_7ree']=dgmdate($list_table['time_7ree'],'u');
			$list_table['address_7ree']=DB::fetch_first("SELECT realname,mobile,address FROM ".DB::table('common_member_profile')." WHERE uid='$list_table[uid_7ree]]'");
			$list_table['name_7ree']=DB::result_first("SELECT name_7ree FROM ".DB::table('extmall_goods_7ree')." WHERE id_7ree='{$list_table[gid_7ree]}'");
			$list_7ree[]=$list_table;
	}
	$multipage = multi($querynum, 20, $page, "plugin.php?id=x7ree_extmall:x7ree_extmall&code_7ree=9" );
	
	
}elseif($code_7ree==10){//会员退货申请
	$valuearray_7ree = array('status_7ree'=>1);
	$wherearray_7ree = array('id_7ree'=>$id_7ree);
	data_update_7ree('extmall_log_7ree',$valuearray_7ree,$wherearray_7ree);
	showmessage("退货申请成功，请耐心等待我们联络您办理后续事宜，现在返回。","plugin.php?id=x7ree_extmall:x7ree_extmall&code_7ree=2");
}elseif($code_7ree==11){//退货审核
	$do_7ree = intval($_GET['do_7ree']);
	if($do_7ree==1){//通过
		$valuearray_7ree = array('status_7ree'=>2);
		//积分返还给原会员，并记录到财务系统
		$thislog_7ree = DB::fetch_first("SELECT gid_7ree, uid_7ree, cost_7ree FROM ".DB::table('extmall_log_7ree')." WHERE id_7ree='{$id_7ree}'");
		//商品数量返还+1
		DB::query("UPDATE ".DB::table('extmall_goods_7ree')." SET num_7ree = num_7ree+1 WHERE id_7ree = '{$thislog_7ree[gid_7ree]}' LIMIT 1");
		if($thislog_7ree){
				updatemembercount($thislog_7ree['uid_7ree'], array($vars_7ree['ext_7ree'] => $thislog_7ree['cost_7ree']), false, 'REP',7);
		}else{
				showmessage('error##7ree@@bad uid.');
		}
	}elseif($do_7ree==2){//驳回
		$valuearray_7ree = array('status_7ree'=>3);
	}else{
		showmessage("错误##未定义的操作类型，请返回。");
	}
	showmessage("操作完成，现在返回。","plugin.php?id=x7ree_extmall:x7ree_extmall&code_7ree=9");

}else{
	showmessage("Undefined Operation @ 7ree.com");
}

	include template('x7ree_extmall:x7ree_extmall');
?>