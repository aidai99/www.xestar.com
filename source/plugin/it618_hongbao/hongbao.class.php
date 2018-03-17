<?php
/**
 *	折翼天使资源社区：www.zheyitianshi.com
 *	YMG6_COMpyright 插件设计：<a href="http://www.zheyitianshi.com" target="_blank" title="专业Discuz!应用及周边提供商">www.zheyitianshi.com</a>
 */
 
if(!defined('IN_DISCUZ')) {
  exit('Access Denied');
}

class plugin_it618_hongbao {
	
	function global_header(){
		global $_G,$it618_hongbao_lang;
		$it618_hongbao = $_G['cache']['plugin']['it618_hongbao'];
		$hongbao_forums = unserialize($it618_hongbao["hongbao_forums"]);
		
		if($_G['uid']>0&&$_G['fid']>0){
			if($_GET['mod']=="post"&&$_GET['action']=="newthread"&&!(isset($_GET['special'])||isset($_GET['specialextra']))&&in_array($_GET['fid'], $hongbao_forums)){
				$hongbao_groups = unserialize($it618_hongbao["hongbao_groups"]);
				$groupid=DB::result_first("select groupid from ".DB::table('common_member')." where uid=".$_G['uid']);
				if(in_array($groupid, $hongbao_groups)){
					include template('it618_hongbao:hongbaopost');
					return $it618_hongbao_block;
				}
			}
		}
		
		if($_G['uid']>0&&$_G['tid']>0){
			$query = DB::query("SELECT * FROM ".DB::table('it618_hongbao_main')." WHERE it618_tid=0 and it618_uid=".$_G['uid']);
			while($it618_hongbao_main =DB::fetch($query)) {
				$forum_thread=DB::fetch_first("SELECT * FROM ".DB::table('forum_thread')." WHERE tid=".$_G['tid']);
	
				if($forum_thread['subject']==$it618_hongbao_main['it618_subject']&&$forum_thread['authorid']==$_G['uid']){
					DB::query("update ".DB::table('it618_hongbao_main')." set it618_tid=".$_G['tid']." WHERE id=".$it618_hongbao_main['id']);
					
					C::t('common_member_count')->increase($_G['uid'], array(
						'extcredits'.$it618_hongbao_main['it618_jfid'] => (0-$it618_hongbao_main['it618_money']))
					);
					
					require_once DISCUZ_ROOT.'./source/plugin/it618_hongbao/function.func.php';
					sethbcount($_G['uid']);
				}
			}
			
			DB::query("delete from ".DB::table('it618_hongbao_main')." WHERE it618_tid=0 and it618_uid=".$_G['uid']);
		}
	}
	
	function common() {
		global $_G,$it618_hongbao_lang;
		$it618_hongbao = $_G['cache']['plugin']['it618_hongbao'];
		require_once DISCUZ_ROOT.'./source/plugin/it618_hongbao/lang.func.php';
		
		if($_GET['mod']=="topicadmin"&&$_GET['action']=="moderate"&&$_GET['operation']=="delete"){
			$tids="#";
			foreach($_GET['moderate'] as $key => $tid) {
				$tid=intval($tid);
				if(DB::result_first("SELECT count(1) FROM ".DB::table('it618_hongbao_main')." WHERE it618_tid=".$tid)>0){
					$tids.=",".$tid;
			    }
				//DB::query("delete FROM ".DB::table('it618_hongbao_main')." where it618_tid=".$tid);
				//DB::query("delete FROM ".DB::table('it618_hongbao_item')." where it618_tid=".$tid);
			}
			$tids=str_replace("#,","",$tids);
			if($tids!="#")showmessage($it618_hongbao_lang['s28'].$tids.$it618_hongbao_lang['s29'], '', array(), array('alert' => 'info'));
		}
		
		if($_GET['mod']=="post"&&$_GET['action']=="edit"&&$_GET['delete']==1){
			$tid=intval($_GET['tid']);
			if(DB::result_first("SELECT count(1) FROM ".DB::table('it618_hongbao_main')." WHERE it618_tid=".$tid)>0){
				showmessage($it618_hongbao_lang['s30'], '', array(), array('alert' => 'info'));
			}
		}

		if($_GET['mod']=="post"&&$_GET['action']=="newthread"&&$_GET['hongbaopost']==1){
			
			$it618_jfid=intval($_GET['it618_jfid']);
			$jfname=$_G['setting']['extcredits'][$it618_jfid]['title'];
			$it618_count=intval($_GET['it618_count']);
			$it618_money=intval($_GET['it618_money']);
			if($it618_count<=0||$it618_money<=0){
				showmessage($it618_hongbao_lang['s63'], '', array(), array('alert' => 'info'));
			}
			
			if(lang('plugin/it618_hongbao', $it618_hongbao_lang['it618'])!=$it618_hongbao_lang['version'])return;
			$creditnum=DB::result_first("select extcredits".$it618_jfid." from ".DB::table('common_member_count')." where uid=".$_G['uid']);
			if($creditnum<$it618_money){
				showmessage($it618_hongbao_lang['s3'].$jfname.$it618_hongbao_lang['s4'].$creditnum.$it618_hongbao_lang['s5'], '', array(), array('alert' => 'info'));
			}
			
			$hongbao_groups = unserialize($it618_hongbao["hongbao_groups"]);
			$groupid=DB::result_first("select groupid from ".DB::table('common_member')." where uid=".$_G['uid']);
			if(!in_array($groupid, $hongbao_groups)){
				showmessage($it618_hongbao_lang['s37'], '', array(), array('alert' => 'info'));
			}
			
			$hongbao_forums = unserialize($it618_hongbao["hongbao_forums"]);
			if(!in_array($_GET['fid'], $hongbao_forums)){
				showmessage($it618_hongbao_lang['s64'], '', array(), array('alert' => 'info'));
			}
			
			if(file_exists(DISCUZ_ROOT.'./source/plugin/it618_credits/hongbaoset.php')){
				require_once DISCUZ_ROOT.'./source/plugin/it618_credits/hongbaoset.php';
			}
			
			if($_GET['it618_isrand']==2){
				if(intval($it618_money/$it618_count)!=($it618_money/$it618_count)){
					showmessage($it618_hongbao_lang['s6'], '', array(), array('alert' => 'info'));
				}
			}
			
			if($hongbao_isok==1){
				if(lang('plugin/it618_hongbao', $it618_hongbao_lang['it618'])!=$it618_hongbao_lang['version'])return;
				if($_GET['it618_isrand']==2){
					if(intval($hongbao_credit[$it618_jfid])<1)$hongbao_credit[$it618_jfid]=1;
					if($it618_money/$it618_count<$hongbao_credit[$it618_jfid]){
						if(lang('plugin/it618_hongbao', $it618_hongbao_lang['it618'])!=$it618_hongbao_lang['version'])return;
						showmessage($it618_hongbao_lang['s7'].($hongbao_credit[$it618_jfid]-1).$it618_hongbao_lang['s8'], '', array(), array('alert' => 'info'));
					}
				}else{
					if($hongbao_credit[$it618_jfid]==1){
						$tmpcredit=1;
					}
					if($it618_money/$it618_count<($hongbao_credit[$it618_jfid]+$tmpcredit)){
						showmessage($it618_hongbao_lang['s7'].($hongbao_credit[$it618_jfid]+$tmpcredit-1).$it618_hongbao_lang['s8'], '', array(), array('alert' => 'info'));
					}
				}
				
				$hongbao_moneytmp1=$it618_hongbao['hongbao_money1'];
				$hongbao_moneytmp2=$it618_hongbao['hongbao_money2'];
			}else{
				if(lang('plugin/it618_hongbao', $it618_hongbao_lang['it618'])!=$it618_hongbao_lang['version'])return;
				if($_GET['it618_isrand']==2){
					if(intval($it618_hongbao['hongbao_price'])<1)$it618_hongbao['hongbao_price']=1;
					if($it618_money/$it618_count<$it618_hongbao['hongbao_price']){
						if(lang('plugin/it618_hongbao', $it618_hongbao_lang['it618'])!=$it618_hongbao_lang['version'])return;
						showmessage($it618_hongbao_lang['s7'].($it618_hongbao['hongbao_price']-1).$it618_hongbao_lang['s8'], '', array(), array('alert' => 'info'));
					}
				}else{
					if($it618_hongbao['hongbao_price']==1){
						$tmpcredit=1;
					}
					if($it618_money/$it618_count<($it618_hongbao['hongbao_price']+$tmpcredit)){
						showmessage($it618_hongbao_lang['s7'].($it618_hongbao['hongbao_price']+$tmpcredit-1).$it618_hongbao_lang['s8'], '', array(), array('alert' => 'info'));
					}
				}
				
				$hongbao_moneytmp1=$it618_hongbao['hongbao_money1'];
				$hongbao_moneytmp2=$it618_hongbao['hongbao_money2'];
			}
			
			$it618_count=intval($_GET['it618_count']);
			$it618_money=intval($_GET['it618_money']);
	

			if($hongbao_moneytmp1!=0&&$it618_money<$hongbao_moneytmp1){
				showmessage($it618_hongbao_lang['s49'].$hongbao_moneytmp1.$jfname, '', array(), array('alert' => 'info'));
			}
			
			$id = C::t('#it618_hongbao#it618_hongbao_main')->insert(array(
				'it618_tid' => 0,
				'it618_subject' => $_GET['subject'],
				'it618_uid' => $_G['uid'],
				'it618_jfid' => $it618_jfid,
				'it618_isrand' => $_GET['it618_isrand'],
				'it618_count' => $it618_count,
				'it618_money' => $it618_money,
				'it618_isbm' => $_GET['it618_isbm'],
				'it618_timecount' => $_GET['it618_timecount'],
				'it618_time' => $_G['timestamp'],
				'it618_code' => $_GET["it618_code"],
				'it618_state' => 1
			), true);

		}
	}

}

class plugin_it618_hongbao_forum extends plugin_it618_hongbao{
	
	function forumdisplay_thread_subject_output(){
		global $_G,$it618_hongbao_lang;
		require_once DISCUZ_ROOT.'./source/plugin/it618_hongbao/lang.func.php';
		$it618_hongbao = $_G['cache']['plugin']['it618_hongbao'];
		$hongbao_forums = unserialize($it618_hongbao["hongbao_forums"]);

		if(!in_array($_G[fid], $hongbao_forums)) return array();
		
		$thread_subject=array();
		$threadlist = $_G['forum_threadlist'];
		foreach($threadlist as $id => $thread){
			if(($it618_hongbao_main=DB::fetch_first("SELECT * FROM ".DB::table('it618_hongbao_main')." WHERE it618_tid=".$thread[tid]))){
				$hongbao_listtips='';

				$tidhongbaocount1 = DB::result_first("SELECT COUNT(1) FROM ".DB::table('it618_hongbao_item')." WHERE it618_tid=".$thread[tid]);
				$tidhongbaomoney = DB::result_first("SELECT SUM(it618_money) FROM ".DB::table('it618_hongbao_item')." WHERE it618_tid=".$thread[tid]);
				$tidhongbaocount=$it618_hongbao_main['it618_count']-$tidhongbaocount1;
				$tidhongbaomoney=$it618_hongbao_main['it618_money']-$tidhongbaomoney;
				
				if($it618_hongbao_main['it618_isrand']==1)$tmpname=$it618_hongbao_lang['s15'];else $tmpname=$it618_hongbao_lang['s16'];
				
				$tmpname=$tmpname.$it618_hongbao_lang['s31'];
				
				$hongbao_creditname=$_G['setting']['extcredits'][$it618_hongbao_main['it618_jfid']]['title'];
				
				$hongbao_listtips='- <font color=#999>'.$it618_hongbao_lang['s60'].'</font>';
				$hongbao_listtips=str_replace("{hbtypename}",$tmpname,$hongbao_listtips);
				$hongbao_listtips=str_replace("{usercount}",$tidhongbaocount1,$hongbao_listtips);
				$hongbao_listtips=str_replace("{hbcount}",$tidhongbaocount,$hongbao_listtips);
				$hongbao_listtips=str_replace("{hbmoney}",$tidhongbaomoney,$hongbao_listtips);
				$hongbao_listtips=str_replace("{creditname}",$hongbao_creditname,$hongbao_listtips);

				$thread_subject[$id]=$hongbao_listtips;
			}
		}

		return $thread_subject;
	}
	
	function viewthread_postheader_output(){
		global $_G,$it618_hongbao_lang,$postlist;
		require_once DISCUZ_ROOT.'./source/plugin/it618_hongbao/lang.func.php';
		$it618_hongbao = $_G['cache']['plugin']['it618_hongbao'];
		$hongbao_groups = unserialize($it618_hongbao["hongbao_groups"]);
		$hongbao_forums = unserialize($it618_hongbao["hongbao_forums"]);
		
		if($_G[tid]=='')return array();
		$tmpcount=DB::result_first("SELECT COUNT(1) FROM ".DB::table('it618_hongbao_main')." WHERE it618_tid=".$_G['tid']);
		if(!in_array($_G[fid], $hongbao_forums)&&$tmpcount==0) return array();
		
		$hongbao_isok=1;
		if (file_exists(DISCUZ_ROOT.'./source/plugin/it618_credits/hongbaoset.php')){
			require_once DISCUZ_ROOT.'./source/plugin/it618_credits/hongbaoset.php';
		}
		
		if($hongbao_isok==1){
			$tmpcount=DB::result_first("SELECT COUNT(1) FROM ".DB::table('it618_hongbao_main')." WHERE it618_tid=".$_G['tid']);
			$n=1;
			foreach($postlist as $id => $post) {
				if($postlist[$id]['first']==1){
					$groupid=DB::result_first("select groupid from ".DB::table('common_member')." where uid=".$_G['uid']);

					if(in_array($groupid, $hongbao_groups)){
						
						if($tmpcount>0){
							$tmptitle=$it618_hongbao_lang['s35'];
						}else{
							$tmptitle=$it618_hongbao_lang['s36'];
							$tmpjs='';
							if($n==1)$tmpjs='<SCRIPT src="source/plugin/it618_hongbao/js/jquery.js" type=text/javascript></SCRIPT>';
							$n=$n+1;
						}
						
						$flag=0;
						if(DB::result_first("select count(1) from ".DB::table('common_plugin')." where identifier='it618_credits'")==0&&$tmpcount>0){
							$flag=1;
						}
						
						if(DB::result_first("SELECT COUNT(1) FROM ".DB::table('it618_hongbao_main')." WHERE it618_tid=".$_G['tid']." and it618_uid=".$_G['uid'])==0&&$tmpcount>0){
							$flag=1;
						}
						
						if($flag==0&&$postlist[$id]['authorid']==$_G['uid']){
							$tmparr[]=$tmpjs.'<span style="float:right;font-size:13px;margin-right:6px;cursor:pointer;margin-top:-1px" onclick="showWindow(\'it618_showhongbao\',\''.$_G['siteurl'].'plugin.php?id=it618_hongbao:showhongbao&it618_tid='.$_G['tid'].'&fid='.$_G['fid'].'&type='.$tmpcount.'\');"><img src="source/plugin/it618_hongbao/images/logo.png" height="18" style="vertical-align:middle;margin-top:-3px;margin-right:3px">'.$tmptitle.'</span>';
						}
						
						
					}else{
						$tmparr[]='';	
					}
					
					if(($it618_hongbao_main=DB::fetch_first("SELECT * FROM ".DB::table('it618_hongbao_main')." WHERE it618_tid=".$_G[tid]))){
						
						include template('it618_hongbao:hongbao');
						
						if($it618_hongbao['hongbao_pcstyle']==1){
							$post['message']=$it618_hongbao_block.$post['message'];
						}else{
							$post['message']=$post['message'].'<br>'.$it618_hongbao_block;
						}
	
						$postlist[$id] =$post;
					}
				}
			}
			
			return $tmparr;
		}
	}

}
//From:www_YMG6_COM
?>