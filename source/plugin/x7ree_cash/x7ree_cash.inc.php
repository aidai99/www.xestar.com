<?php
/*
	[www.7ree.com] (C)2007-2017 7ree.com.
	This is NOT a freeware, use is subject to license terms
	Update: 2017/8/19 15:10
	Agreement: http://addon.discuz.com/?@7.developer.doc/agreement_7ree_html
	More Plugins: http://addon.discuz.com/?@7ree
*/
// �����������ϵͳ

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$vars_7ree = $_G['cache']['plugin']['x7ree_cash'];

//��������û���
$caiwugids_7ree = $vars_7ree['caiwugids_7ree'] ? unserialize($vars_7ree['caiwugids_7ree']) : array();


//$navtitle = $vars_7ree['navtitle_7ree'];
$navtitle = "��������";
$exttitle_7ree = $_G['setting']['extcredits'][$vars_7ree['ext_7ree']][title];
//if(!$vars_7ree['agreement_7ree']) showmessage('x7ree_download:php_lang_agree_7ree');


$code_7ree = intval($_GET['code_7ree']);
$id_7ree = intval($_GET['id_7ree']);




//�Ƿ��ѵ�¼�������
if(!$_G['uid']) {
	showmessage('not_loggedin', NULL, array(), array('login' => 1));
}
//�û�����
if($vars_7ree['group_7ree']){
				$group_7ree = unserialize($vars_7ree['group_7ree']);
				if(!in_array($_G['groupid'],$group_7ree) && $_G['adminid']<>1) showmessage('��Ǹ���������û����޷��������ֲ������뷵�ء�');
}

//����Ա�������
if(in_array($code_7ree,array('3','4','5')) && ($_G['adminid']<>1 && !in_array($_G['groupid'],$caiwugids_7ree))) showmessage('Access Denied @ 7ree');

//id��ʧ���
if(in_array($code_7ree,array('4','5')) && !$id_7ree) showmessage("ERROR@7REE, MISS ID.");


//submit���
if (in_array($code_7ree,array('1')) && !submitcheck('submit_7ree')) showmessage('Access Denied @ 7ree');

//del���
if(in_array($code_7ree,array('4','5')) && $_GET['formhash'] <> FORMHASH) showmessage("Access Deined @ 7ree");



$pagenum_7ree = 20;

if(!$code_7ree){//��������ҳ��
	$membercount_7ree = getuserprofile('extcredits'.$vars_7ree['ext_7ree']);


}elseif($code_7ree==1){//�ύ���붯��
		$ext_7ree = intval($_GET['ext_7ree']);
		//$cash_7ree = round(floatval($_GET['cash_7ree']),2);
		$type_7ree = intval($_GET['type_7ree']);
		$account_7ree = dhtmlspecialchars(trim($_GET['account_7ree']));
		$tel_7ree = dhtmlspecialchars(trim($_GET['tel_7ree']));
		//���ݻ��ּ�����������
		$cash_7ree = round(($ext_7ree*($vars_7ree['rate_7ree']/100)),2);
		if(!$ext_7ree || !$cash_7ree || !$type_7ree || !$account_7ree || !$tel_7ree) showmessage("��Ǹ��������Ϣ���������뷵�����ƺ����ύ��");

		$mytest_7ree = DB::fetch_first("SELECT COUNT(*) AS count_7ree, SUM(ext_7ree) AS sum_7ree FROM ".DB::table('x7ree_cash')." WHERE uid_7ree='$_G[uid]' AND time_7ree>$_G[timestamp]-86400");
		//Ƶ�ʼ��
		if($vars_7ree['maxnum_7ree']){
			if($mytest_7ree['count_7ree']>$vars_7ree['maxnum_7ree']) showmessage("��Ǹ����24Сʱ�����ִ����Ѵ�������Ժ����ԡ�");
		}
		//ÿ�������ܶ�ȼ��
		if($vars_7ree['maxext_7ree']){
			if($mytest_7ree['sum_7ree']>$vars_7ree['maxext_7ree']) showmessage("��Ǹ����24Сʱ�������ܻ����Ѵ�������Ժ����ԡ�");
		}
		//���������Ƿ��㹻���
		$membercount_7ree = getuserprofile('extcredits'.$vars_7ree['ext_7ree']);
		if($membercount_7ree < $ext_7ree){
				showmessage('��Ǹ�����Ļ��ֲ������뷵�ء�');
		}else{//���ֿ۳�
				updatemembercount($_G['uid'], array($vars_7ree['ext_7ree'] => "-".$ext_7ree), false, 'REM',7);
		}

		//��־���
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
		showmessage('��ϲ�����ѳɹ����������֣���ȴ���ˡ�',"plugin.php?id=x7ree_cash:x7ree_cash&code_7ree=2");


}elseif($code_7ree==2){//���ֽ����û���ѯҳ��
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



}elseif($code_7ree==3){//���ֽ��ȹ���ҳ��
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


}elseif($code_7ree==4){//������˶���

		DB::query("UPDATE ".DB::table('x7ree_cash')." SET status_7ree=1 WHERE id_7ree='{$id_7ree}'");
		showmessage('��ϲ������ȷ����˳ɹ��������֡�',"plugin.php?id=x7ree_cash:x7ree_cash&code_7ree=3");

}elseif($code_7ree==5){//�ܾ���˶���
		$backinfo_7ree = DB::fetch_first("SELECT uid_7ree,ext_7ree FROM ".DB::table('x7ree_cash')." WHERE id_7ree='{$id_7ree}'");
		updatemembercount($backinfo_7ree['uid_7ree'], array($vars_7ree['ext_7ree'] => $backinfo_7ree['ext_7ree']), false, 'REN',7);
		DB::query("UPDATE ".DB::table('x7ree_cash')." SET status_7ree=2 WHERE id_7ree='{$id_7ree}'");
		showmessage('���ز����ɹ���',"plugin.php?id=x7ree_cash:x7ree_cash&code_7ree=3");

//}elseif($code_7ree==6){



}else{
		showmessage("Undefined Operation @ 7ree.com");
}

		include template('x7ree_cash:x7ree_cash');

?>