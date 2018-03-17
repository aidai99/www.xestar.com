<?php
/**
 *	折翼天使资源社区：www.zheyitianshi.com
 *	YMG6_COMpyright 插件设计：<a href="http://www.zheyitianshi.com" target="_blank" title="专业Discuz!应用及周边提供商">www.zheyitianshi.com</a>
 */
 
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

global $_G,$it618_hongbao;

$it618_hongbao = $_G['cache']['plugin']['it618_hongbao'];

function sethbcount($tmpuid){
	$it618_postcount=DB::result_first("select count(1) from ".DB::table('it618_hongbao_main')." where it618_uid=".$tmpuid);
	$it618_getcount=DB::result_first("select count(1) from ".DB::table('it618_hongbao_item')." where it618_uid=".$tmpuid);
	
	if($it618_hongbao_ph=DB::fetch_first("SELECT * FROM ".DB::table('it618_hongbao_ph')." WHERE it618_uid=".$tmpuid)){
		C::t('#it618_hongbao#it618_hongbao_ph')->update($it618_hongbao_ph["id"],array(
			'it618_postcount' => $it618_postcount,
			'it618_getcount' => $it618_getcount
		));
	}else{
		$id = C::t('#it618_hongbao#it618_hongbao_ph')->insert(array(
			'it618_uid' => $tmpuid,
			'it618_postcount' => $it618_postcount,
			'it618_getcount' => $it618_getcount
		), true);
	}
}

function hbtimeout($it618_tid){
	global $_G;
	if($it618_hongbao_main=DB::fetch_first("select * from ".DB::table('it618_hongbao_main')." where it618_state>0 and it618_tid=".$it618_tid)){
		$timecount=($_G['timestamp']-$it618_hongbao_main['it618_time'])/3600;
		if($timecount>$it618_hongbao_main['it618_timecount']){
			$tidhongbaomoney1 = DB::result_first("SELECT SUM(it618_money) FROM ".DB::table('it618_hongbao_item')." WHERE it618_tid=".$it618_tid);
			$tidhongbaomoney=$it618_hongbao_main['it618_money']-$tidhongbaomoney1;
			
			$tidhongbaocount1 = DB::result_first("SELECT COUNT(1) FROM ".DB::table('it618_hongbao_item')." WHERE it618_tid=".$it618_tid);
			$tidhongbaocount=$it618_hongbao_main['it618_count']-$tidhongbaocount1;
			
			C::t('#it618_hongbao#it618_hongbao_main')->update($it618_hongbao_main["id"],array(
				'it618_count' => $tidhongbaocount1,
				'it618_money' => $tidhongbaomoney1,
				'it618_state' => 0
			));
	
			C::t('common_member_count')->increase($it618_hongbao_main['it618_uid'], array(
				'extcredits'.$it618_hongbao_main['it618_jfid'] => $tidhongbaomoney)
			);
		}
	}
}

function it618_hongbao_rewriteurl($fl_html){
	global $_G;
	if($_G['cache']['plugin']['it618_hongbao']['rewriteurl']==1){
		//	home.php?mod=space&uid=5
		//	space-uid-5.html
		$tmparr=explode("home.php?mod=space",$fl_html);
		if(count($tmparr)>1){
			$fl_html="";
			foreach($tmparr as $key => $tmp){
				if(strpos($tmp,"uid=")==1){
					$tmp=str_replace("&uid=","space-uid-",$tmp);
					$tmparr1=explode('"',$tmp,2);
					$fl_html.=$tmparr1[0].'.html"'.$tmparr1[1];
				}else{
					$fl_html.=$tmp;
				}
			}
		}

	}
	return $fl_html;
}

function it618_hongbao_getusername($uid){
	return DB::result_first("select username from ".DB::table('common_member')." where uid=".$uid);
}

function it618_hongbao_utftogbk($strcontent){
	$strcontent=dhtmlspecialchars($strcontent);
	
	$s1 = iconv('utf-8','gbk',$strcontent);
	$s0 = iconv('gbk','utf-8',$s1);
	if($s0 == $strcontent){
		$tmpstr = $s1;
	}else{
		$tmpstr = $strcontent;
	}
	
	if(CHARSET=='gbk'){
		return $tmpstr;
	}else{
		return it618_hongbao_gbktoutf($strcontent);
	}
}

function it618_hongbao_gbktoutf($strcontent){
	$s1 = iconv('utf-8','gbk',$strcontent);
	$s0 = iconv('gbk','utf-8',$s1);
	if($s0 == $strcontent){
		$tmpstr = $s1;
	}else{
		$tmpstr = $strcontent;
	}

	return iconv('gbk','utf-8', $tmpstr);
}

function it618_hongbao_gettime($it618_time){
	global $_G;
	$timecount=intval(($_G['timestamp']-$it618_time)/3600);
	
	if($timecount>24*10){
		$timestr=date('Y-m-d', $it618_time);
	}elseif($timecount>24){
		$timecount=intval($timecount/24);
		$timestr='<font color="red">'.$timecount.''.it618_hongbao_getlang('s51').'</font>';
	}elseif($timecount>=1){
		$timestr='<font color="red">'.$timecount.''.it618_hongbao_getlang('s52').'</font>';
	}else{
		$timecount=intval(($_G['timestamp']-$it618_time)/60);
		if($timecount>=1){
			$timestr='<font color="red">'.$timecount.''.it618_hongbao_getlang('s53').'</font>';
		}else{
			$timecount=intval(($_G['timestamp']-$it618_time));
			if($timecount==0)$timecount=1;
			$timestr='<font color="red">'.$timecount.''.it618_hongbao_getlang('s54').'</font>';
		}
	}
	
	return $timestr;
}

function hongbao_is_mobile(){ 
	global $_GET;
	if(isset($_GET['pc'])) return false;
	
	$user_agent = $_SERVER['HTTP_USER_AGENT']; 
	$mobile_agents = Array("240x320","acer","acoon","acs-","abacho","ahong","airness","alcatel","amoi","android","anywhereyougo.com","applewebkit/525","applewebkit/532","asus","audio","au-mic","avantogo","becker","benq","bilbo","bird","blackberry","blazer","bleu","cdm-","compal","coolpad","danger","dbtel","dopod","elaine","eric","etouch","fly ","fly_","fly-","go.web","goodaccess","gradiente","grundig","haier","hedy","hitachi","htc","huawei","hutchison","inno","ipad","ipaq","ipod","jbrowser","kddi","kgt","kwc","lenovo","lg ","lg2","lg3","lg4","lg5","lg7","lg8","lg9","lg-","lge-","lge9","longcos","maemo","mercator","meridian","micromax","midp","mini","mitsu","mmm","mmp","mobi","mot-","moto","nec-","netfront","newgen","nexian","nf-browser","nintendo","nitro","nokia","nook","novarra","obigo","palm","panasonic","pantech","philips","phone","pg-","playstation","pocket","pt-","qc-","qtek","rover","sagem","sama","samu","sanyo","samsung","sch-","scooter","sec-","sendo","sgh-","sharp","siemens","sie-","softbank","sony","spice","sprint","spv","symbian","tablet","talkabout","tcl-","teleca","telit","tianyu","tim-","toshiba","tsm","up.browser","utec","utstar","verykool","virgin","vk-","voda","voxtel","vx","wap","wellco","wig browser","wii","windows ce","wireless","xda","xde","zte"); 
	$is_mobile = false; 
	foreach ($mobile_agents as $device) { 
	if (stristr($user_agent, $device)) { 
	$is_mobile = true; 
	break; 
	} 
	} 
	return $is_mobile; 
}
//WWW-FX8-CO
?>