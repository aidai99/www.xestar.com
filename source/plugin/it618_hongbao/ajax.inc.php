<?php
/**
 *	折翼天使资源社区：www.zheyitianshi.com
 *	YMG6_COMpyright 插件设计：<a href="http://www.zheyitianshi.com" target="_blank" title="专业Discuz!应用及周边提供商">www.zheyitianshi.com</a>
 */
 
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

require_once DISCUZ_ROOT.'./source/plugin/it618_hongbao/function.func.php';

if($_GET['formhash']!=FORMHASH)return;

$uid=$_G['uid'];
if($_GET['ac']=="hongbao"){
	if($uid<=0){
		echo $it618_hongbao_lang['s1'];
	}else{
		if(lang('plugin/it618_hongbao', $it618_hongbao_lang['it618'])!=$it618_hongbao_lang['version'])return;
		$it618_jfid=intval($_GET['it618_jfid']);
		$it618_tid=intval($_GET['it618_tid']);
		$jfname=$_G['setting']['extcredits'][$it618_jfid]['title'];
		$it618_count=intval($_GET['it618_count']);
		$it618_money=intval($_GET['it618_money']);
		if($it618_count<=0||$it618_money<=0){
			echo $it618_hongbao_lang['s63'];return;
		}
		
		if(lang('plugin/it618_hongbao', $it618_hongbao_lang['it618'])!=$it618_hongbao_lang['version'])return;
		$creditnum=DB::result_first("select extcredits".$it618_jfid." from ".DB::table('common_member_count')." where uid=".$uid);
		if($creditnum<$it618_money){
			echo $it618_hongbao_lang['s3'].$jfname.$it618_hongbao_lang['s4'].$creditnum.$it618_hongbao_lang['s5'];return;
		}
		
		$hongbao_groups = unserialize($it618_hongbao["hongbao_groups"]);
		$groupid=DB::result_first("select groupid from ".DB::table('common_member')." where uid=".$_G['uid']);
		if(!in_array($groupid, $hongbao_groups)){
			echo $it618_hongbao_lang['s37'];return;
		}
		
		$hongbao_forums = unserialize($it618_hongbao["hongbao_forums"]);
		if(!in_array($_GET['fid'], $hongbao_forums)){
			echo $it618_hongbao_lang['s64'];return;
		}
						
		if(lang('plugin/it618_hongbao', $it618_hongbao_lang['it618'])!=$it618_hongbao_lang['version'])return;
		if($_GET['type']==1){
			set_time_limit (0);
			ignore_user_abort(true);
			$flagkm=0;$times=0;
			while($flagkm==0){
				if(DB::result_first("select count(1) from ".DB::table('it618_hongbao_work')." where it618_tid=".$it618_tid)==0){
					$flagkm=1;
				}
				if($flagkm==0){
					sleep(1);
					$times=$times+1;
				}
				if($times>30){
					delwork($it618_tid);
				}
			}
			if(lang('plugin/it618_hongbao', $it618_hongbao_lang['it618'])!=$it618_hongbao_lang['version'])return;
			C::t('#it618_hongbao#it618_hongbao_work')->insert(array(
				'it618_iswork' => 1,
				'it618_tid' => $it618_tid
			), true);
			
			$it618_hongbao_main=DB::fetch_first("SELECT * FROM ".DB::table('it618_hongbao_main')." where it618_tid=".$it618_tid);
			$tidhongbaocount = DB::result_first("SELECT COUNT(1) FROM ".DB::table('it618_hongbao_item')." WHERE it618_tid=".$it618_tid);
			$tidhongbaomoney = DB::result_first("SELECT SUM(it618_money) FROM ".DB::table('it618_hongbao_item')." WHERE it618_tid=".$it618_tid);
			$tidhongbaocount=$it618_hongbao_main['it618_count']-$tidhongbaocount;
			$tidhongbaomoney=$it618_hongbao_main['it618_money']-$tidhongbaomoney;
			if(lang('plugin/it618_hongbao', $it618_hongbao_lang['it618'])!=$it618_hongbao_lang['version'])return;
			$it618_count=$tidhongbaocount+$it618_count;
			$it618_money=$tidhongbaomoney+$it618_money;
		}
		
		if(file_exists(DISCUZ_ROOT.'./source/plugin/it618_credits/hongbaoset.php')){
			require_once DISCUZ_ROOT.'./source/plugin/it618_credits/hongbaoset.php';
		}
		
		if($_GET['it618_isrand']==2){
			if(intval($it618_money/$it618_count)!=($it618_money/$it618_count)){
				if($_GET['type']==1)delwork($it618_tid);
				echo $it618_hongbao_lang['s6'];return;
			}
		}
		
		if($hongbao_isok==1){
			if(lang('plugin/it618_hongbao', $it618_hongbao_lang['it618'])!=$it618_hongbao_lang['version'])return;
			if($_GET['it618_isrand']==2){
				if(intval($hongbao_credit[$it618_jfid])<1)$hongbao_credit[$it618_jfid]=1;
				if($it618_money/$it618_count<$hongbao_credit[$it618_jfid]){
					if($_GET['type']==1)delwork($it618_tid);
					if(lang('plugin/it618_hongbao', $it618_hongbao_lang['it618'])!=$it618_hongbao_lang['version'])return;
					echo $it618_hongbao_lang['s7'].($hongbao_credit[$it618_jfid]-1).$it618_hongbao_lang['s8'];return;
				}
			}else{
				if($hongbao_credit[$it618_jfid]==1){
					$tmpcredit=1;
				}
				if($it618_money/$it618_count<($hongbao_credit[$it618_jfid]+$tmpcredit)){
					if($_GET['type']==1)delwork($it618_tid);
					echo $it618_hongbao_lang['s7'].($hongbao_credit[$it618_jfid]+$tmpcredit-1).$it618_hongbao_lang['s8'];return;
				}
			}
			
			$hongbao_moneytmp1=$it618_hongbao['hongbao_money1'];
			$hongbao_moneytmp2=$it618_hongbao['hongbao_money2'];
		}else{
			if(lang('plugin/it618_hongbao', $it618_hongbao_lang['it618'])!=$it618_hongbao_lang['version'])return;
			if($_GET['it618_isrand']==2){
				if(intval($it618_hongbao['hongbao_price'])<1)$it618_hongbao['hongbao_price']=1;
				if($it618_money/$it618_count<$it618_hongbao['hongbao_price']){
					if($_GET['type']==1)delwork($it618_tid);
					if(lang('plugin/it618_hongbao', $it618_hongbao_lang['it618'])!=$it618_hongbao_lang['version'])return;
					echo $it618_hongbao_lang['s7'].($it618_hongbao['hongbao_price']-1).$it618_hongbao_lang['s8'];return;
				}
			}else{
				if($it618_hongbao['hongbao_price']==1){
					$tmpcredit=1;
				}
				if($it618_money/$it618_count<($it618_hongbao['hongbao_price']+$tmpcredit)){
					if($_GET['type']==1)delwork($it618_tid);
					echo $it618_hongbao_lang['s7'].($it618_hongbao['hongbao_price']+$tmpcredit-1).$it618_hongbao_lang['s8'];return;
				}
			}
			
			$hongbao_moneytmp1=$it618_hongbao['hongbao_money1'];
			$hongbao_moneytmp2=$it618_hongbao['hongbao_money2'];
		}
		
		$it618_count=intval($_GET['it618_count']);
		$it618_money=intval($_GET['it618_money']);

		if($_GET['type']==1){
			$it618_count=$it618_hongbao_main['it618_count']+$it618_count;
			$it618_money=$it618_hongbao_main['it618_money']+$it618_money;
			
			if($hongbao_moneytmp2!=0&&$it618_money>$hongbao_moneytmp2){
				delwork($it618_tid);
				echo $it618_hongbao_lang['s50'].$hongbao_moneytmp2.$jfname;return;
			}
			
			if(lang('plugin/it618_hongbao', $it618_hongbao_lang['it618'])!=$it618_hongbao_lang['version'])return;
			C::t('#it618_hongbao#it618_hongbao_main')->update($it618_hongbao_main["id"],array(
				'it618_count' => $it618_count,
				'it618_money' => $it618_money,
				'it618_isbm' => $_GET['it618_isbm'],
				'it618_timecount' => $_GET['it618_timecount'],
				'it618_time' => $_G['timestamp'],
				'it618_code' => it618_hongbao_utftogbk($_GET["it618_code"]),
				'it618_state' => 1
			));
			if(lang('plugin/it618_hongbao', $it618_hongbao_lang['it618'])!=$it618_hongbao_lang['version'])return;
			C::t('common_member_count')->increase($uid, array(
				'extcredits'.$it618_jfid => (0-intval($_GET['it618_money'])))
			);
			
			sethbcount($uid);
			delwork($it618_tid);
			echo 'ok';
			
		}else{
			if($hongbao_moneytmp1!=0&&$it618_money<$hongbao_moneytmp1){
				echo $it618_hongbao_lang['s49'].$hongbao_moneytmp1.$jfname;return;
			}
			
			$id = C::t('#it618_hongbao#it618_hongbao_main')->insert(array(
				'it618_tid' => $_GET['it618_tid'],
				'it618_uid' => $uid,
				'it618_jfid' => $it618_jfid,
				'it618_isrand' => $_GET['it618_isrand'],
				'it618_count' => $it618_count,
				'it618_money' => $it618_money,
				'it618_isbm' => $_GET['it618_isbm'],
				'it618_timecount' => $_GET['it618_timecount'],
				'it618_time' => $_G['timestamp'],
				'it618_code' => it618_hongbao_utftogbk($_GET["it618_code"]),
				'it618_state' => 1
			), true);
			if(lang('plugin/it618_hongbao', $it618_hongbao_lang['it618'])!=$it618_hongbao_lang['version'])return;
			if($id>0){
				C::t('common_member_count')->increase($uid, array(
					'extcredits'.$it618_jfid => (0-$it618_money))
				);
				
				sethbcount($uid);
				echo 'ok';
			}
		}
	}
}

if($_GET['ac']=="gethongbao"){
	if($uid<=0){
		echo $it618_hongbao_lang['s1'];
	}else{
		$it618_tid=intval($_GET['it618_tid']);
		$hongbao_getgroups = unserialize($it618_hongbao["hongbao_getgroups"]);
		$groupid=DB::result_first("select groupid from ".DB::table('common_member')." where uid=".$uid);
		if(!in_array($groupid, $hongbao_getgroups)){
			echo $it618_hongbao_lang['s9'];return;
		}
		if(lang('plugin/it618_hongbao', $it618_hongbao_lang['it618'])!=$it618_hongbao_lang['version'])return;
		if(($it618_hongbao_main=DB::fetch_first("SELECT * FROM ".DB::table('it618_hongbao_main')." WHERE it618_tid=".$it618_tid))){
			set_time_limit (0);
			ignore_user_abort(true);
			$flagkm=0;$times=0;
			while($flagkm==0){
				if(DB::result_first("select count(1) from ".DB::table('it618_hongbao_work')." where it618_tid=".$it618_tid)==0){
					$flagkm=1;
				}
				if($flagkm==0){
					sleep(1);
					$times=$times+1;
				}
				if($times>30){
					delwork($it618_tid);
				}
			}
			if(lang('plugin/it618_hongbao', $it618_hongbao_lang['it618'])!=$it618_hongbao_lang['version'])return;
			C::t('#it618_hongbao#it618_hongbao_work')->insert(array(
				'it618_iswork' => 1,
				'it618_tid' => $it618_tid
			), true);
			if(lang('plugin/it618_hongbao', $it618_hongbao_lang['it618'])!=$it618_hongbao_lang['version'])return;
			$tidhongbaocount = DB::result_first("SELECT COUNT(1) FROM ".DB::table('it618_hongbao_item')." WHERE it618_tid=".$it618_tid);
			$tidhongbaomoney = DB::result_first("SELECT SUM(it618_money) FROM ".DB::table('it618_hongbao_item')." WHERE it618_tid=".$it618_tid);
			$tidhongbaocount=$it618_hongbao_main['it618_count']-$tidhongbaocount;
			$tidhongbaomoney=$it618_hongbao_main['it618_money']-$tidhongbaomoney;
			if($tidhongbaocount<=0){
				delwork($it618_tid);echo $it618_hongbao_lang['s10'];return;
			}
			
			if(DB::result_first("SELECT COUNT(1) FROM ".DB::table('it618_hongbao_item')." WHERE it618_uid=".$uid." and it618_tid=".$it618_tid)>0){
				delwork($it618_tid);echo $it618_hongbao_lang['s11'];return;
			}
			if(lang('plugin/it618_hongbao', $it618_hongbao_lang['it618'])!=$it618_hongbao_lang['version'])return;
			if($it618_hongbao_main['it618_code']!=''){
				if(DB::result_first("SELECT COUNT(1) FROM ".DB::table('forum_post')." WHERE tid=".$it618_tid." and authorid=".$uid." and message LIKE '%".addcslashes(addslashes($it618_hongbao_main['it618_code']),'%_')."%'")==0){
					if($it618_hongbao_main['it618_isbm']==0){
						$tmpcode='code0it618_split'.$it618_hongbao_lang['s12'];
					}else{
						$tmpcode='code1it618_split'.$it618_hongbao_lang['s12']."\n\n".$it618_hongbao_lang['s59'];
					}
					delwork($it618_tid);echo $tmpcode;return;
				}
			}
			if(lang('plugin/it618_hongbao', $it618_hongbao_lang['it618'])!=$it618_hongbao_lang['version'])return;
			if($it618_hongbao_main['it618_isrand']==1){
				$it618_money=$tidhongbaomoney/$tidhongbaocount;
				if($it618_money>1){
					$safe_total=($tidhongbaomoney-$tidhongbaocount)/$tidhongbaocount;
					$it618_money=intval(mt_rand($safe_total*50,$safe_total*150)/100);
				}
				if($tidhongbaocount==1){
					$it618_money=$tidhongbaomoney;
					C::t('#it618_hongbao#it618_hongbao_main')->update($it618_hongbao_main["id"],array(
						'it618_state' => 0
					));
				}
			}else{
				$it618_money=$tidhongbaomoney/$tidhongbaocount;
			}
			if(lang('plugin/it618_hongbao', $it618_hongbao_lang['it618'])!=$it618_hongbao_lang['version'])return;
			$id = C::t('#it618_hongbao#it618_hongbao_item')->insert(array(
				'it618_tid' => $it618_tid,
				'it618_uid' => $uid,
				'it618_money' => $it618_money,
				'it618_time' => $_G['timestamp']
			), true);
			sethbcount($uid);
			
			if($id>0){
				C::t('common_member_count')->increase($uid, array(
					'extcredits'.$it618_hongbao_main['it618_jfid'] => $it618_money)
				);
				
				$hongbao_creditname=$_G['setting']['extcredits'][$it618_hongbao_main['it618_jfid']]['title'];
				delwork($it618_tid);echo 'okit618_split'.$it618_hongbao_lang['s13'].$it618_money.' '.$hongbao_creditname.$it618_hongbao_lang['s14'];
			}
				
		}else{
			echo $it618_hongbao_lang['s2'];return;
		}
  

	}
}

if($_GET['ac']=="delhongbao"){
	if($uid<=0){
		echo $it618_hongbao_lang['s1'];
	}else{
		$it618_tid=intval($_GET['it618_tid']);
		
		set_time_limit (0);
		ignore_user_abort(true);
		$flagkm=0;$times=0;
		while($flagkm==0){
			if(DB::result_first("select count(1) from ".DB::table('it618_hongbao_work')." where it618_tid=".$it618_tid)==0){
				$flagkm=1;
			}
			if($flagkm==0){
				sleep(1);
				$times=$times+1;
			}
			if($times>30){
				delwork($it618_tid);
			}
		}
		if(lang('plugin/it618_hongbao', $it618_hongbao_lang['it618'])!=$it618_hongbao_lang['version'])return;
		C::t('#it618_hongbao#it618_hongbao_work')->insert(array(
			'it618_iswork' => 1,
			'it618_tid' => $it618_tid
		), true);
		
		$hongbao_delpower=explode(",",$it618_hongbao['hongbao_delpower']);
		if(!in_array($uid, $hongbao_delpower)){
			echo $it618_hongbao_lang['s57'];delwork($it618_tid);return;
		}
		
		if(($it618_hongbao_main=DB::fetch_first("SELECT * FROM ".DB::table('it618_hongbao_main')." WHERE it618_tid=".$it618_tid))){
			$tidhongbaomoney = DB::result_first("SELECT SUM(it618_money) FROM ".DB::table('it618_hongbao_item')." WHERE it618_tid=".$it618_tid);
			$tidhongbaomoney=$it618_hongbao_main['it618_money']-$tidhongbaomoney;
	
			if(lang('plugin/it618_hongbao', $it618_hongbao_lang['it618'])!=$it618_hongbao_lang['version'])return;
			C::t('common_member_count')->increase($it618_hongbao_main['it618_uid'], array(
				'extcredits'.$it618_hongbao_main['it618_jfid'] => $tidhongbaomoney)
			);
			
			sethbcount($it618_hongbao_main['it618_uid']);
			
			DB::query("delete from ".DB::table('it618_hongbao_item')." where it618_tid=".$it618_tid);
			DB::query("delete from ".DB::table('it618_hongbao_main')." where it618_tid=".$it618_tid);
			
			echo 'okit618_split'.$it618_hongbao_lang['s58'];delwork($it618_tid);return;
		}else{
			echo $it618_hongbao_lang['s2'];delwork($it618_tid);return;
		}
	}
	
}

if($_GET['ac']=="hongbaomain"){
	$it618_tid=intval($_GET['it618_tid']);
	hbtimeout($it618_tid);
	if(($it618_hongbao_main=DB::fetch_first("SELECT * FROM ".DB::table('it618_hongbao_main')." WHERE it618_tid=".$it618_tid))){
		$tidhongbaocount = DB::result_first("SELECT COUNT(1) FROM ".DB::table('it618_hongbao_item')." WHERE it618_tid=".$it618_tid);
		$tidhongbaomoney = DB::result_first("SELECT SUM(it618_money) FROM ".DB::table('it618_hongbao_item')." WHERE it618_tid=".$it618_tid);
		$tidhongbaocount=$it618_hongbao_main['it618_count']-$tidhongbaocount;
		$tidhongbaomoney=$it618_hongbao_main['it618_money']-$tidhongbaomoney;
		$tidprice=intval($tidhongbaomoney/$tidhongbaocount);
		$hongbao_creditname=$_G['setting']['extcredits'][$it618_hongbao_main['it618_jfid']]['title'];
		if(lang('plugin/it618_hongbao', $it618_hongbao_lang['it618'])!=$it618_hongbao_lang['version'])return;
		if($it618_hongbao_main['it618_isrand']==1)$tmpname=$it618_hongbao_lang['s15'];else $tmpname=$it618_hongbao_lang['s16'];
		if(lang('plugin/it618_hongbao', $it618_hongbao_lang['it618'])!=$it618_hongbao_lang['version'])return;
		$hongbao_delpower=explode(",",$it618_hongbao['hongbao_delpower']);
		if(in_array($uid, $hongbao_delpower)){
			$deltmp='<a href="javascript:" onclick="delhongbao('.$it618_hongbao_main['it618_tid'].')">'.$it618_hongbao_lang['s56'].'</a>';
		}
		if($_GET['wap']!=1){
			if($it618_hongbao_main['it618_isbm']==1){
				$it618_isbm=$it618_hongbao_lang['s55'];
			}else{
				$it618_isbm='<br /><textarea class="it618_code" id="it618_code" onclick="this.select()" readonly="readonly">'.$it618_hongbao_main['it618_code'].'</textarea>';
			}
			echo '<div class="hongbaobtn" onclick="gethongbao()"></div><font color=red><b><font color=green>'.$tidhongbaomoney.'</font><font color=#999>/</font>'.$it618_hongbao_main['it618_money'].'</b></font> <font color=green>'.$hongbao_creditname.'</font><br /><font color=#999>'.$it618_hongbao_lang['s17'].'</font><font color=green>'.$tidhongbaocount.'</font><font color=#999>/</font><font color=red>'.$it618_hongbao_main['it618_count'].'</font><br /><font color=#999>'.$it618_hongbao_lang['s18'].'</font>'.$tmpname.'<br /><font color=#999>'.$it618_hongbao_lang['s19'].'</font><font color=green>'.$tidprice.'</font> <font color=green>'.$hongbao_creditname.'</font><br /><font color=#999>'.$it618_hongbao_lang['s20'].'</font>'.$it618_isbm.'<br>'.$deltmp;
		}else{
			if($it618_hongbao_main['it618_isbm']==1){
				$it618_isbm=$it618_hongbao_lang['s55'];
			}else{
				$it618_isbm='<input type="text" class="it618_code" id="it618_code" onclick="this.select()" readonly="readonly" value="'.$it618_hongbao_main['it618_code'].'">';
			}
			echo '<div class="hongbaobtn" onclick="gethongbao()"></div><font color=red><b><font color=green>'.$tidhongbaomoney.'</font><font color=#999>/</font>'.$it618_hongbao_main['it618_money'].'</b></font> <font color=green>'.$hongbao_creditname.'</font><br /><font color=#999>'.$it618_hongbao_lang['s17'].'</font><font color=green>'.$tidhongbaocount.'</font><font color=#999>/</font><font color=red>'.$it618_hongbao_main['it618_count'].'</font> '.$tmpname.'<br /><font color=#999>'.$it618_hongbao_lang['s19'].'</font><font color=green>'.$tidprice.'</font> <font color=green>'.$hongbao_creditname.'</font><br><span style="float:right">'.$deltmp.'</span><font color=#999>'.$it618_hongbao_lang['s20'].'</font>'.$it618_isbm;
		}
	}
}

if($_GET['ac']=="hongbaolist_get"){
	if($_GET['wap']!=1)$ppp = 12;else $ppp = 12;
	$page = max(1, intval($_GET['page']));
	$startlimit = ($page - 1) * $ppp;
	if(lang('plugin/it618_hongbao', $it618_hongbao_lang['it618'])!=$it618_hongbao_lang['version'])return;
	$it618_tid=intval($_GET['it618_tid']);
	if(lang('plugin/it618_hongbao', $it618_hongbao_lang['it618'])!=$it618_hongbao_lang['version'])return;
	$sql='1';
	if($_GET['ac1']=='thread'){
		$sql='it618_tid='.$it618_tid;
		$it618_hongbao_main=DB::fetch_first("SELECT * FROM ".DB::table('it618_hongbao_main')." WHERE it618_tid=".$it618_tid);
		$hongbao_creditname=$_G['setting']['extcredits'][$it618_hongbao_main['it618_jfid']]['title'];
	}
	if(lang('plugin/it618_hongbao', $it618_hongbao_lang['it618'])!=$it618_hongbao_lang['version'])return;
	$query = DB::query("SELECT * FROM ".DB::table('it618_hongbao_item')." WHERE $sql order by id desc LIMIT $startlimit, $ppp");
	while($it618_hongbao_item = DB::fetch($query)) {
		if($_GET['ac1']=='thread'){
			$username=DB::result_first("SELECT username FROM ".DB::table('common_member')." WHERE uid=".$it618_hongbao_item['it618_uid']);
			if($username=='')$username='test'.mt_rand(1,10000);
			if($_GET['wap']!=1){
				$hongbaolist_get.='<tr>
				<td width="458"><a href="'.it618_hongbao_rewriteurl('home.php?mod=space&uid='.$it618_hongbao_item['it618_uid']).'" target="_blank">'.$username.'</a> '.$it618_hongbao_lang['s22'].' <font color=red>'.$it618_hongbao_item['it618_money'].'</font> <font color=green>'.$hongbao_creditname.'</font> '.$it618_hongbao_lang['s23'].'</td>
				<td align="right"><font color=#999>'.it618_hongbao_gettime($it618_hongbao_item['it618_time']).'</font></td>
				</tr>';
			}else{
				$hongbaolist_get.='<tr>
				<td><a href="'.it618_hongbao_rewriteurl('home.php?mod=space&uid='.$it618_hongbao_item['it618_uid']).'" target="_blank">'.$username.'</a> '.$it618_hongbao_lang['s24'].' <font color=red>'.$it618_hongbao_item['it618_money'].'</font> <font color=green>'.$hongbao_creditname.'</font> '.$it618_hongbao_lang['s23'].' <font color=#999>'.it618_hongbao_gettime($it618_hongbao_item['it618_time']).'</font></td>
				</tr>';
			}
			$funname='hongbaolist_get';
		}
	}
	if(lang('plugin/it618_hongbao', $it618_hongbao_lang['it618'])!=$it618_hongbao_lang['version'])return;
	$count = DB::result_first("select count(1) from ".DB::table('it618_hongbao_item')." WHERE $sql");
	if($_GET['wap']!=1){
		$multipage = multi($count, $ppp, $page, $_G['siteurl']."plugin.php?id=it618_hongbao:ajax");
		$multipage=str_replace("href=","name=",$multipage);
		$multipage=str_replace("name=","href='javascript:' onclick='".$funname."(this.name)' name=",$multipage);
		$multipage=str_replace("onclick='".$funname."(this.name)' ".'name="custompage"','',$multipage);
		$multipage=str_replace('window.location=',$funname.'(',$multipage);
		$multipage=str_replace('this.value;','this.value);',$multipage);
		if(lang('plugin/it618_hongbao', $it618_hongbao_lang['it618'])!=$it618_hongbao_lang['version'])return;
		if($count>0)$tmpcountstr='<span style="float:right;margin-top:6px">'.$it618_hongbao_lang['s61'].'<font color=red><b>'.$count.'</b></font>'.$it618_hongbao_lang['s62'].'</span>';
		echo $hongbaolist_get.'it618_split'.$multipage.$tmpcountstr;
	}else{
		if($count<=$ppp){
			$pagecount=1;
		}else{
			$pagecount=ceil($count/$ppp);
		}

		if($pagecount>1){
			$n=1;
			while($n<=$pagecount){
				$tmpurl=$_G['siteurl']."plugin.php?id=it618_hongbao:ajax&page=".$n;
				if($page==$n)$tmpselect=' selected="selected"';else $tmpselect='';
				$curpage.='<option value="'.$tmpurl.'"'.$tmpselect.'>'.$n.'/'.$pagecount.'</option>';
				$n=$n+1;
			}
			$curpage='<select class="pageselect" onchange="'.$funname.'(this.value)">'.$curpage.'</select>';
			if(lang('plugin/it618_hongbao', $it618_hongbao_lang['it618'])!=$it618_hongbao_lang['version'])return;
			if($page==1){
				$pagepre='<a class="it618btn btn-weak btn-disabled" >'.$it618_hongbao_lang['s26'].'</a>';
				if($pagecount>1){
					$tmpurl=$_G['siteurl']."plugin.php?id=it618_hongbao:ajax&page=2";
					$pagenext='<a href="javascript:" class="it618btn btn-weak" onclick="'.$funname.'(\''.$tmpurl.'\')">'.$it618_hongbao_lang['s27'].'</a>';
				}else{
					$pagenext='<a class="it618btn btn-weak btn-disabled">'.$it618_hongbao_lang['s27'].'</a>';
				}
			}elseif($page<$pagecount){
				$tmpurl=$_G['siteurl']."plugin.php?id=it618_hongbao:ajax&page=".($page-1);
				$pagepre='<a href="javascript:" class="it618btn btn-weak" onclick="'.$funname.'(\''.$tmpurl.'\')">'.$it618_hongbao_lang['s26'].'</a>';
				$tmpurl=$_G['siteurl']."plugin.php?id=it618_hongbao:ajax&page=".($page+1);
				$pagenext='<a href="javascript:" class="it618btn btn-weak" onclick="'.$funname.'(\''.$tmpurl.'\')">'.$it618_hongbao_lang['s27'].'</a>';
			}else{
				$tmpurl=$_G['siteurl']."plugin.php?id=it618_hongbao:ajax&page=".($page-1);
				$pagepre='<a href="javascript:" class="it618btn btn-weak" onclick="'.$funname.'(\''.$tmpurl.'\')">'.$it618_hongbao_lang['s26'].'</a>';
				$pagenext='<a class="it618btn btn-weak btn-disabled" >'.$it618_hongbao_lang['s27'].'</a>';
			}
			$multipage=$pagepre.' '.$curpage.' '.$pagenext;
		}
		
		if($count>0)$tmpcountstr=$it618_hongbao_lang['s61'].'<font color=red><b>'.$count.'</b></font>'.$it618_hongbao_lang['s62'];
		echo $hongbaolist_get.'it618_split<span style="float:right;">'.$tmpcountstr.' '.$multipage.'</span>';
	}

}

function delwork($tid){
	DB::query("delete from ".DB::table('it618_hongbao_work')." where it618_tid=".$tid);
}
//From:www_YMG6_COM
?>