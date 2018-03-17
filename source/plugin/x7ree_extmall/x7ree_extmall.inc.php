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

if(!$code_7ree){//�����̳���ҳ
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
	
	
}elseif($code_7ree==1){//���ֶһ�����ҳ

	if($id_7ree){
			$thisgoods_7ree=DB::fetch_first("SELECT * FROM ".DB::table('extmall_goods_7ree')." WHERE id_7ree='$id_7ree'");
			if(!$thisgoods_7ree) showmessage("��Ǹ����ѯ����Ʒ�����ڡ�");
			//�ظ��һ����
			$mylog_7ree = DB::result_first("SELECT id_7ree FROM ".DB::table('extmall_log_7ree')." WHERE gid_7ree='$id_7ree' AND uid_7ree='$_G[uid]'");
			if(submitcheck('submit_7ree')){//�ύ�һ�����
					if($vars_7ree['norepeat_7ree']){
							if($mylog_7ree) showmessage("��Ǹ�����Ѿ��һ��˴���Ʒ�������ظ��һ���");
					}
					//�ύ��ַ��Ϣ��������
					$realname = dhtmlspecialchars(trim($_GET['realname']));
					$mobile = dhtmlspecialchars(trim($_GET['mobile']));
					$address = dhtmlspecialchars(trim($_GET['address']));
					$qq= dhtmlspecialchars(trim($_GET['qq']));
					$site = dhtmlspecialchars(trim($_GET['site']));
					if(!$realname || !$mobile ) showmessage("�������绰����ַ��������д���뷵���޸ġ�");
					if($thisgoods_7ree['num_7ree']<1) showmessage("��Ǹ��Ʒ��治�㣬�޷��һ����뷵�ؿ���������Ʒ��");

					//���ּ��۳�
					if(extcredit_7ree($vars_7ree['ext_7ree'],$thisgoods_7ree['cost_7ree'],$_G['uid'],'REO',7)){
							//�����������
							DB::query("UPDATE ".DB::table('extmall_goods_7ree')." SET num_7ree = num_7ree -1 WHERE id_7ree='$id_7ree'");
							//������Ϣ����
							DB::query("UPDATE ".DB::table('common_member_profile')." SET realname='$realname', mobile='$mobile', address='$address',qq='$qq',site='$site' WHERE uid='$_G[uid]'");
							//���˶һ���־д��
							$valuearray_7ree=array(
													'gid_7ree'=>$thisgoods_7ree['id_7ree'],
													'uid_7ree'=>$_G['uid'],
													'user_7ree'=>$_G['username'],
													'ip_7ree'=>$_G['clientip'],
													'cost_7ree'=>$thisgoods_7ree['cost_7ree'],
													'time_7ree'=>$_G['timestamp'],
							);
							data_update_7ree('extmall_log_7ree',$valuearray_7ree,array(),array(),'');
							
							showmessage("��ϲ�����ɹ��һ�����Ʒ���ڷ��ء�","plugin.php?id=x7ree_extmall:x7ree_extmall&code_7ree=2");
					}else{
							showmessage("��Ǹ�����Ļ��ֲ��㣬�޷��һ���");
					}

			}else{//ҳ��
					$thisgoods_7ree['detail_7ree'] = str_replace(PHP_EOL,"<br>", $thisgoods_7ree['detail_7ree']);
					$myaddress_7ree=DB::fetch_first("SELECT realname,mobile,address,site,qq FROM ".DB::table('common_member_profile')." WHERE uid='$_G[uid]'");
			}

	}


}elseif($code_7ree==2){//�ҵ���Ʒҳ

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


}elseif($code_7ree==3){//������ҳ����Ʒ�б�

	$page = max(1, intval($_GET['page']));
	$startpage = ( $page - 1 ) * 20;
	$querynum = DB::result_first( "SELECT Count(*) FROM ".DB::table('extmall_goods_7ree'));
	$query = DB::query( "SELECT * FROM ".DB::table('extmall_goods_7ree')." ORDER BY id_7ree DESC LIMIT {$startpage},20" );
	while ($list_table = DB::fetch($query)){
			$list_7ree[]=$list_table;
	}
	$multipage = multi($querynum, 20, $page, "plugin.php?id=x7ree_extmall:x7ree_extmall&code_7ree=3" );
	

}elseif($code_7ree==4){//����ҳ�棺�������༭

	if($id_7ree){
			$thisgoods_7ree=DB::fetch_first("SELECT * FROM ".DB::table('extmall_goods_7ree')." WHERE id_7ree='$id_7ree'");
	}

}elseif($code_7ree==5){//�����ܣ��ύ��Ʒ

	//¼�����
	$name_7ree = dhtmlspecialchars(trim($_GET['name_7ree']));
	$fenlei_7ree = dhtmlspecialchars(trim($_GET['fenlei_7ree']));
	$detail_7ree = dhtmlspecialchars(trim($_GET['detail_7ree']));
	$price_7ree = floatval($_GET['price_7ree']);
	$cost_7ree = intval($_GET['cost_7ree']);
	$num_7ree = intval($_GET['num_7ree']);
	$displayorder_7ree = intval($_GET['displayorder_7ree']);
	if(!$name_7ree) showmessage("��Ʒ���Ʊ�����д���뷵���޸ġ�");
	
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

	if($id_7ree){//�ύ�༭
		$wherearray_7ree = array('id_7ree'=>$id_7ree);
		data_update_7ree('extmall_goods_7ree',$valuearray_7ree,$wherearray_7ree,$imagearray_7ree,$folder_7ree);
	}else{//�ύ����
		data_update_7ree('extmall_goods_7ree',$valuearray_7ree,array(),$imagearray_7ree,$folder_7ree);
	}
	//��⺯��

	showmessage("��ϲ�����ɹ��ύ����Ʒ�����ڷ��ء�","plugin.php?id=x7ree_extmall:x7ree_extmall&code_7ree=3");

}elseif($code_7ree==6){//�����ܣ�ɾ����Ʒ
	$wherearray_7ree = array('id_7ree'=>$id_7ree);
	$imagearray_7ree = array('pic_7ree');
	data_delete_7ree('extmall_goods_7ree',$wherearray_7ree,$imagearray_7ree,$folder_7ree);
	showmessage("��ϲ�����ɹ�ɾ������Ʒ�����ڷ��ء�","plugin.php?id=x7ree_extmall:x7ree_extmall&code_7ree=3");

}elseif($code_7ree==7){//�����ܣ���־�б�

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
	


	
}elseif($code_7ree==8){//�����ܣ�ɾ����־




}elseif($code_7ree==9){//�����ܣ��˻��б�
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
	
	
}elseif($code_7ree==10){//��Ա�˻�����
	$valuearray_7ree = array('status_7ree'=>1);
	$wherearray_7ree = array('id_7ree'=>$id_7ree);
	data_update_7ree('extmall_log_7ree',$valuearray_7ree,$wherearray_7ree);
	showmessage("�˻�����ɹ��������ĵȴ���������������������ˣ����ڷ��ء�","plugin.php?id=x7ree_extmall:x7ree_extmall&code_7ree=2");
}elseif($code_7ree==11){//�˻����
	$do_7ree = intval($_GET['do_7ree']);
	if($do_7ree==1){//ͨ��
		$valuearray_7ree = array('status_7ree'=>2);
		//���ַ�����ԭ��Ա������¼������ϵͳ
		$thislog_7ree = DB::fetch_first("SELECT gid_7ree, uid_7ree, cost_7ree FROM ".DB::table('extmall_log_7ree')." WHERE id_7ree='{$id_7ree}'");
		//��Ʒ��������+1
		DB::query("UPDATE ".DB::table('extmall_goods_7ree')." SET num_7ree = num_7ree+1 WHERE id_7ree = '{$thislog_7ree[gid_7ree]}' LIMIT 1");
		if($thislog_7ree){
				updatemembercount($thislog_7ree['uid_7ree'], array($vars_7ree['ext_7ree'] => $thislog_7ree['cost_7ree']), false, 'REP',7);
		}else{
				showmessage('error##7ree@@bad uid.');
		}
	}elseif($do_7ree==2){//����
		$valuearray_7ree = array('status_7ree'=>3);
	}else{
		showmessage("����##δ����Ĳ������ͣ��뷵�ء�");
	}
	showmessage("������ɣ����ڷ��ء�","plugin.php?id=x7ree_extmall:x7ree_extmall&code_7ree=9");

}else{
	showmessage("Undefined Operation @ 7ree.com");
}

	include template('x7ree_extmall:x7ree_extmall');
?>