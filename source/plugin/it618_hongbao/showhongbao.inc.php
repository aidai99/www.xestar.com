<?php
 /**
 *	折翼天使资源社区：www.zheyitianshi.com
 *	YMG6_COMpyright 插件设计：<a href="http://www.zheyitianshi.com" target="_blank" title="专业Discuz!应用及周边提供商">www.zheyitianshi.com</a>
 */
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

global $_G;

$it618_hongbao = $_G['cache']['plugin']['it618_hongbao'];
require_once DISCUZ_ROOT.'./source/plugin/it618_hongbao/lang.func.php';

$it618_tid=intval($_GET['it618_tid']);

if($it618_tid>0){
	$hongbao_groups=(array)unserialize($it618_hongbao['hongbao_groups']);
	if(!in_array($_G['groupid'], $hongbao_groups)){
		showmessage($it618_hongbao_lang['s37'], '', array(), array('alert' => 'info'));
	}
	
	if($_GET['type']==0){
		if(DB::result_first("SELECT COUNT(1) FROM ".DB::table('it618_hongbao_main')." WHERE it618_tid=".$it618_tid)>0){
			showmessage($it618_hongbao_lang['s38'], '', array(), array('alert' => 'info'));
		}
	}else{
		if(DB::result_first("SELECT COUNT(1) FROM ".DB::table('it618_hongbao_main')." WHERE it618_tid=".$it618_tid." and it618_uid=".$_G['uid'])==0){
			showmessage($it618_hongbao_lang['s2'], '', array(), array('alert' => 'info'));
		}
	}
}

$wap=$_GET['wap'];

if($_GET['type']>0){
	$t=$it618_hongbao_lang['s35'];
	$it618_hongbao_main=DB::fetch_first("SELECT * FROM ".DB::table('it618_hongbao_main')." where it618_tid=".$it618_tid);
	$tidhongbaocount = DB::result_first("SELECT COUNT(1) FROM ".DB::table('it618_hongbao_item')." WHERE it618_tid=".$it618_tid);
	$tidhongbaomoney = DB::result_first("SELECT SUM(it618_money) FROM ".DB::table('it618_hongbao_item')." WHERE it618_tid=".$it618_tid);
	$tidhongbaocount=$it618_hongbao_main['it618_count']-$tidhongbaocount;
	$tidhongbaomoney=$it618_hongbao_main['it618_money']-$tidhongbaomoney;
	
	if($it618_hongbao_main['it618_isbm']==1)$it618_isbm_checked='checked="checked"';else $it618_isbm_checked="";
	
	$creditnum=DB::result_first("select extcredits".$it618_hongbao_main['it618_jfid']." from ".DB::table('common_member_count')." where uid=".$_G['uid']);
	$moneystr='<tr><td style="text-align:right"><font color="red">*</font>'.$it618_hongbao_lang['s39'].'</td><td><input type="text" class="txt" id="it618_money" name="it618_money" style="width:80px;color:red;font-weight:bold" onkeyup="getsum('.$tidhongbaocount.','.$tidhongbaomoney.')" /> <font color=green>'.$_G['setting']['extcredits'][$it618_hongbao['hongbao_credit']]['title'].'</font> '.$it618_hongbao_lang['s40'].'<font color=red>'.$creditnum.'</font><font color=green>'.$_G['setting']['extcredits'][$it618_hongbao_main['it618_jfid']]['title'].'</font><input type="hidden" value="'.$it618_hongbao['hongbao_credit'].'" name="it618_jfid"></td></tr>';
	
	if($wap==1){
		$tmp='<tr><td colspan="2">'.$it618_hongbao_lang['s41'].'<font color=red>'.$tidhongbaomoney.'</font><font color=green>'.$_G['setting']['extcredits'][$it618_hongbao_main['it618_jfid']]['title'].'</font> '.$it618_hongbao_lang['s42'].'<font color=red>'.$tidhongbaocount.'</font></td></tr>
			  <tr><td colspan="2">'.$it618_hongbao_lang['s43'].'<font color=red><span id="allhongbaomoney">'.$tidhongbaomoney.'</span></font><font color=green>'.$_G['setting']['extcredits'][$it618_hongbao_main['it618_jfid']]['title'].'</font> '.$it618_hongbao_lang['s44'].'<font color=red><span id="allhongbaocount">'.$tidhongbaocount.'</font></td></tr>
		';
	}else{
		$tmp='<tr><td colspan="2"><span style="float:right">'.$it618_hongbao_lang['s43'].'<font color=red><span id="allhongbaomoney">'.$tidhongbaomoney.'</span></font><font color=green>'.$_G['setting']['extcredits'][$it618_hongbao_main['it618_jfid']]['title'].'</font> '.$it618_hongbao_lang['s44'].'<font color=red><span id="allhongbaocount">'.$tidhongbaocount.'</font></span>&nbsp;&nbsp;'.$it618_hongbao_lang['s41'].'<font color=red>'.$tidhongbaomoney.'</font><font color=green>'.$_G['setting']['extcredits'][$it618_hongbao_main['it618_jfid']]['title'].'</font> '.$it618_hongbao_lang['s42'].'<font color=red>'.$tidhongbaocount.'</font></td></tr>';
	}
	$it618_hongbao_str='
		'.$tmp.'
		'.$moneystr.'
		<tr><td style="text-align:right"><font color="red">*</font>'.$it618_hongbao_lang['s45'].'</td><td><input type="text" class="txt" id="it618_count" name="it618_count" style="width:80px;color:red;font-weight:bold" onkeyup="getsum('.$tidhongbaocount.','.$tidhongbaomoney.')"/></td></tr>
		';

}else{
	$t=$it618_hongbao_lang['s36'];
	if (file_exists(DISCUZ_ROOT.'./source/plugin/it618_credits/hongbaoset.php')){
		require_once DISCUZ_ROOT.'./source/plugin/it618_credits/hongbaoset.php';
	}

	if($hongbao_isok==1){
		for($i=1;$i<=8;$i++){
			$creditnum=DB::result_first("select extcredits".$i." from ".DB::table('common_member_count')." where uid=".$_G['uid']);
			
			if($hongbao_credit[$i]!=0&&$_G['setting']['extcredits'][$i]['title']!=''){
				$it618_jfid.='<option value="'.$i.'">'.$_G['setting']['extcredits'][$i]['title'].' ('.$creditnum.')</option>';
			}
		}
		$moneystr='<tr><td style="text-align:right"><font color="red">*</font>'.$it618_hongbao_lang['s43'].'</td><td><input type="text" class="txt" id="it618_money" name="it618_money" style="width:80px;color:red;font-weight:bold" /> <select id="it618_jfid" name="it618_jfid">'.$it618_jfid.'</select></td></tr>';
	}else{
		$creditnum=DB::result_first("select extcredits".$it618_hongbao['hongbao_credit']." from ".DB::table('common_member_count')." where uid=".$_G['uid']);
		$moneystr='<tr><td style="text-align:right"><font color="red">*</font>'.$it618_hongbao_lang['s43'].'</td><td><input type="text" class="txt" id="it618_money" name="it618_money" style="width:80px;color:red;font-weight:bold" /> <font color=green>'.$_G['setting']['extcredits'][$it618_hongbao['hongbao_credit']]['title'].'</font> '.$it618_hongbao_lang['s40'].'<font color=red>'.$creditnum.'</font><font color=green>'.$_G['setting']['extcredits'][$it618_hongbao['hongbao_credit']]['title'].'</font><input type="hidden" value="'.$it618_hongbao['hongbao_credit'].'" name="it618_jfid"></td></tr>';
	}
	
	$it618_hongbao_str='
		<tr><td style="text-align:right"><font color="red">*</font>'.$it618_hongbao_lang['s18'].'</td><td><select name="it618_isrand"><option value="1">'.$it618_hongbao_lang['s46'].'</option><option value="2">'.$it618_hongbao_lang['s47'].'</option></select></td></tr>
		'.$moneystr.'
		<tr><td style="text-align:right"><font color="red">*</font>'.$it618_hongbao_lang['s44'].'</td><td><input type="text" class="txt" id="it618_count" name="it618_count" style="width:80px;color:red;font-weight:bold"/></td></tr>
		';
}



$it618_timecount=$it618_hongbao_main['it618_timecount'];
if($it618_timecount==''){
	$it618_timecount=24;
}else{
	if($it618_hongbao_main['it618_state']>0){
		$it618_time=date('Y-m-d H:i:s', $it618_hongbao_main['it618_time']);
		$timecount=sprintf("%.2f", ($_G['timestamp']-$it618_hongbao_main['it618_time'])/3600);
	}
}

$_G['mobiletpl'][IN_MOBILE]='/';
include template('it618_hongbao:showhongbao');
?>