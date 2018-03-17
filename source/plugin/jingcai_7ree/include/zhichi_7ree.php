<?php
/*
	ID: jingcai_7ree
	[www.7ree.com] (C)2007-2017 7ree.com.
	This is NOT a freeware, use is subject to license terms
	Update: 2017/12/1 14:24
	Agreement: http://addon.discuz.com/?@7.developer.doc/agreement_7ree_html
	More Plugins: http://addon.discuz.com/?@7ree
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

	$usergroup_7ree = $jingcai_7ree_var['usergroup_7ree'] ? unserialize($jingcai_7ree_var['usergroup_7ree']) : array();
	if(in_array($_G['groupid'] , $usergroup_7ree)) showmessage("jingcai_7ree:php_lang_groupban_7ree");
	
	$type_7ree = intval($_GET['daxiao_7ree']);
	$main_id_7ree = intval($_GET['main_id_7ree']);
	$win_7ree = trim(daddslashes(dhtmlspecialchars($_GET['win_7ree'])));
    
	if (!submitcheck('submit_7ree', 1)) exit('Access Denied @ 7ree');

	if($jingcai_7ree_var['refreshtime_7ree']){
			$refreshtime_7ree = isset($_COOKIE['refreshtime_7ree']) ? intval($_COOKIE['refreshtime_7ree']) : 0;
			if ($refreshtime_7ree == 0){
		    	setcookie('refreshtime_7ree', $_G['timestamp']);
			}elseif ($refreshtime_7ree > $_G['timestamp'] - $jingcai_7ree_var['refreshtime_7ree']){
		    	showmessage ('jingcai_7ree:php_lang_error_refreshtime_7ree');
			}
	}
	$this_race_main_7ree = DB::fetch_first("SELECT * FROM ".DB::table('jingcai_main_7ree')." WHERE main_id_7ree ='{$main_id_7ree}'");
	
	if($this_race_main_7ree['fengpan_7ree']){
		showmessage("抱歉，该赛事已封盘，无法参与竞猜，请返回。");
	}
	
    if($jingcai_7ree_var['daylimit_7ree']){
    		$blimittime_7ree=strtotime(date("Y-m-d"));
    		//根据分类计算次数
			//$thisfenlei2_7ree = DB::result_first("SELECT fenlei2_7ree FROM ".DB::table('jingcai_main_7ree')." WHERE main_id_7ree ='{$main_id_7ree}'");
			$thisfenlei2_7ree = $this_race_main_7ree['fenlei2_7ree'];
    		$my24cishu_7ree = DB::result_first("SELECT COUNT(log_id_7ree) FROM ".DB::table('jingcai_log_7ree')." l
												LEFT JOIN ".DB::table('jingcai_main_7ree')." m ON l.main_id_7ree = m.main_id_7ree
    											WHERE m.fenlei2_7ree='{$thisfenlei2_7ree}' AND uid_7ree = $_G[uid] AND log_time_7ree > $blimittime_7ree");
    		if($my24cishu_7ree && $my24cishu_7ree > $jingcai_7ree_var['daylimit_7ree']) showmessage('jingcai_7ree:php_lang_24xiaoshixianzhi_7ree');
    }
    
	$stoptime_7ree = $jingcai_7ree_var['stoptime_7ree'] ? $_G['timestamp'] - $jingcai_7ree_var['stoptime_7ree']*60 : $_G['timestamp'];
	$time_7ree = $this_race_main_7ree['time_7ree'];
    if($time_7ree < $stoptime_7ree) showmessage('jingcai_7ree:php_lang_yijingkaishi_7ree',"");

	if(!$jingcai_7ree_var['duplicate_7ree']){
		if(DB::result_first("SELECT mywin_7ree FROM ".DB::table('jingcai_log_7ree')." WHERE main_id_7ree = '$main_id_7ree' AND type_7ree = '$type_7ree' AND uid_7ree='{$_G[uid]}'")) showmessage('jingcai_7ree:php_lang_chenggongjingcai_7ree',"plugin.php?id=jingcai_7ree:jingcai_7ree");
	}
	$myodds_7ree = intval($_GET['myodds_7ree']);
	//支持积分倍数检查
	if($myodds_7ree<$jingcai_7ree_var['beishu_7ree'] || $myodds_7ree%$jingcai_7ree_var['beishu_7ree']){
			showmessage(lang('plugin/jingcai_7ree', 'php_lang_jingcaibeshu1_7ree').$jingcai_7ree_var['beishu_7ree'].lang('plugin/jingcai_7ree', 'php_lang_jingcaibeshu2_7ree'),"");
	}

	$minreward_7ree = $this_race_main_7ree['reward_7ree'];
	if ($myodds_7ree < $minreward_7ree) showmessage(lang('plugin/jingcai_7ree', 'php_lang_error_zuidi1_7ree').$exttitle_7ree.lang('plugin/jingcai_7ree', 'php_lang_error_zuidi2_7ree'),"");
	if ($myodds_7ree > $cost_7ree && $cost_7ree) showmessage(lang('plugin/jingcai_7ree', 'php_lang_error_zuigao1_7ree').$cost_7ree.$exttitle_7ree.lang('plugin/jingcai_7ree', 'php_lang_error_zuigao2_7ree'),"");

	if($jingcai_7ree_var['coston_7ree']){
			//手续费计算
			$shouxufei_7ree = $jingcai_7ree_var['shouxufei_7ree'] ? round((1+$jingcai_7ree_var['shouxufei_7ree']/100)*$myodds_7ree) : $myodds_7ree;
			if(DB::result_first("SELECT {$extname_7ree} FROM ".DB::table('common_member_count')." WHERE uid='{$_G[uid]}'") < $shouxufei_7ree) showmessage('jingcai_7ree:php_lang_jifenbuzu_7ree',"");
			DB::query("UPDATE ".DB::table('common_member_count')." SET {$extname_7ree} = {$extname_7ree} - {$shouxufei_7ree} WHERE uid='{$_G[uid]}' LIMIT 1");
    }
    //参与实时赔率记录

    if($jingcai_7ree_var['rewardodds_7ree'] && !$type_7ree){
    	if($win_7ree=='a'){
    		$teamodds_7ree=$this_race_main_7ree['aodds_7ree'];
    	}elseif($win_7ree=='b'){
    		$teamodds_7ree=$this_race_main_7ree['bodds_7ree'];
    	}else{
    		$teamodds_7ree=$this_race_main_7ree['codds_7ree'];
    	}
    }else{
    		$teamodds_7ree=0;
    }

    
    
	DB::query("INSERT INTO ".DB::table('jingcai_log_7ree')." (
				uid_7ree,
				username_7ree,
				myodds_7ree,
				teamodds_7ree,
				mywin_7ree,
				log_time_7ree,
				main_id_7ree,
				type_7ree
				) VALUES (
				'{$_G[uid]}',
				'$_G[username]',
				'$myodds_7ree',
				'$teamodds_7ree',
				'$win_7ree',
				'$_G[timestamp]',
				'$main_id_7ree',
				'$type_7ree'
				)");
    $log_id_7ree = DB::insert_id();
    //查找上线
    $shangxian_7ree = DB::result_first("SELECT fromuid_7ree FROM ".DB::table('jingcai_tuiguang_7ree')." WHERE uid_7ree = '{$_G[uid]}'");
    if($shangxian_7ree && $jingcai_7ree_var['fenchengbili_7ree']){
		    $fencheng_7ree = ceil(($myodds_7ree * $jingcai_7ree_var['fenchengbili_7ree'])/100);
		    DB::query("UPDATE ".DB::table('common_member_count')." SET {$extname_7ree} = {$extname_7ree} + {$fencheng_7ree} WHERE uid='$shangxian_7ree' LIMIT 1");
			DB::query("INSERT INTO ".DB::table('jingcai_fencheng_7ree')." (
						uid_7ree,
						fromuid_7ree,
						odds_7ree,
						fencheng_7ree,
						time_7ree,
						main_id_7ree,
						log_id_7ree
						) VALUES (
						'$_G[uid]',
						'$shangxian_7ree',
						'$myodds_7ree',
						'$fencheng_7ree',
						'$_G[timestamp]',
						'$main_id_7ree',
						'$log_id_7ree'
						)");
	}
	$tid = intval($_GET['tid_7ree']);

		if($jingcai_7ree_var['fid_7ree'] && $tid){
		        require_once DISCUZ_ROOT.'./source/function/function_forum.php'; 
				require_once DISCUZ_ROOT.'./source/function/function_post.php'; 
				$thisrace_7ree = DB::fetch_first("SELECT * FROM ".DB::table('jingcai_main_7ree')." WHERE main_id_7ree = '$main_id_7ree'");
				
				if($type_7ree){
						if($win_7ree=='a'){
								$myteam_7ree = "[b]".lang('plugin/jingcai_7ree', 'php_lang_da_7ree').$thisrace_7ree['daxiao_7ree']."[/b]";
						}elseif($win_7ree=='b'){
								$myteam_7ree = "[b]".lang('plugin/jingcai_7ree', 'php_lang_xiao_7ree').$thisrace_7ree['daxiao_7ree']."[/b]";
				        }
				}else{
						if($win_7ree=='a'){
								$myteam_7ree = "[b]".$thisrace_7ree['aname_7ree']."[/b]".lang('plugin/jingcai_7ree', 'php_lang_post2_7ree');
						}elseif($win_7ree=='b'){
								$myteam_7ree = "[b]".$thisrace_7ree['bname_7ree']."[/b]".lang('plugin/jingcai_7ree', 'php_lang_post2_7ree');
						}else{
								$myteam_7ree = "[b]".lang('plugin/jingcai_7ree', 'php_lang_pingju_7ree')."[/b]"; 
						}
				}

				if($jingcai_7ree_var['newrank_7ree']){
						if($jingcai_7ree_var['showext_7ree']){
								$post_7ree = lang('plugin/jingcai_7ree', 'php_lang_post1_7ree').$myteam_7ree.lang('plugin/jingcai_7ree', 'php_lang_post3_7ree')."[b]".$myodds_7ree.$exttitle_7ree."[/b]";
						}else{
								$post_7ree = lang('plugin/jingcai_7ree', 'php_lang_post1_7ree').$myteam_7ree.lang('plugin/jingcai_7ree', 'php_lang_post3_7ree')."[b]".$exttitle_7ree."[/b]";
						}
				}else{
						$post_7ree = lang('plugin/jingcai_7ree', 'php_lang_postmsg1_7ree')."[b]".$myodds_7ree.$exttitle_7ree."[/b]";
				}

				
				$pid = insertpost(array(
							'fid' => $jingcai_7ree_var['fid_7ree'],
							'tid' => $tid,
							'first' => '0',
							'author' => $_G['username'],
							'authorid' => $_G['uid'],
							'subject' => $subject_7ree,
							'dateline' => $_G['timestamp'],
							'message' => $post_7ree,
							'useip' => $_G['clientip'],
							'invisible' => $pinvisible,
							'anonymous' => $isanonymous,
							'usesig' => $usesig,
							'htmlon' => $htmlon,
							'bbcodeoff' => $bbcodeoff,
							'smileyoff' => $smileyoff,
							'parseurloff' => $parseurloff,
							'attachment' => '0',
							'tags' => $tagstr,
							'replycredit' => 0,
							'status' => (defined('IN_MOBILE') ? 8 : 0)
			));
		//更新主题回复数量
		DB::query("UPDATE ".DB::table('forum_thread')." SET lastposter='$_G[username]', lastpost='$_G[timestamp]', replies=replies+1 WHERE tid='$tid' AND fid='$jingcai_7ree_var[fid_7ree]'", 'UNBUFFERED');
		}
		
		if($jingcai_7ree_var['noticeon_7ree']){
			    $thisrace_7ree = DB::fetch_first("SELECT * FROM ".DB::table('jingcai_main_7ree')." WHERE main_id_7ree = '$main_id_7ree'");
				$pmnotice_7ree = lang('plugin/jingcai_7ree', 'php_lang_guanzhumsg1_7ree').$_G['username'].lang('plugin/jingcai_7ree', 'php_lang_guanzhumsg2_7ree').$thisrace_7ree['racename_7ree'].$thisrace_7ree['aname_7ree']."VS".$thisrace_7ree['bname_7ree'].lang('plugin/jingcai_7ree', 'php_lang_guanzhumsg3_7ree');
				$query = DB::query("SELECT * FROM ".DB::table('jingcai_guanzhu_7ree')." WHERE touid_7ree = '{$_G[uid]}' LIMIT 100");
				while($result = DB::fetch($query)) {
					if($result['uid_7ree']) notification_add($result['uid_7ree'], 'system', $pmnotice_7ree, $notevar, 1);
				}

	    }

//////////////////////////动态赔率计算(结束)///////////////////////////////////
	$race_7ree = DB::fetch_first("SELECT * FROM ".DB::table('jingcai_main_7ree')." WHERE main_id_7ree = '$main_id_7ree'");
	$anum_7ree = DB::fetch_first("SELECT COUNT(*) AS num_7ree, SUM(myodds_7ree) AS odds_7ree FROM ".DB::table('jingcai_log_7ree')." WHERE main_id_7ree = '$main_id_7ree' AND mywin_7ree='a' AND type_7ree = 0");
	$bnum_7ree = DB::fetch_first("SELECT COUNT(*) AS num_7ree, SUM(myodds_7ree) AS odds_7ree FROM ".DB::table('jingcai_log_7ree')." WHERE main_id_7ree = '$main_id_7ree' AND mywin_7ree='b' AND type_7ree = 0");
	$cnum_7ree = DB::fetch_first("SELECT COUNT(*) AS num_7ree, SUM(myodds_7ree) AS odds_7ree FROM ".DB::table('jingcai_log_7ree')." WHERE main_id_7ree = '$main_id_7ree' AND mywin_7ree='c' AND type_7ree = 0");
    
    $num_7ree['num_7ree']=$anum_7ree['num_7ree']+$bnum_7ree['num_7ree']+$cnum_7ree['num_7ree'];
    $num_7ree['odds_7ree']=$anum_7ree['odds_7ree']+$bnum_7ree['odds_7ree']+$cnum_7ree['odds_7ree']; 
    
    if($jingcai_7ree_var['peilvrenshu_7ree']){
    		$dongtai_onoff_7ree = $num_7ree['num_7ree']>=$jingcai_7ree_var['peilvrenshu_7ree'] && $num_7ree['odds_7ree'] ? 1 : 0;
    }else{
    		$dongtai_onoff_7ree = $num_7ree['odds_7ree'] ? 1 : 0;
    }
    
    if($_G['uid'] && $race_7ree['odd_dynamic_7ree'] && $race_7ree['odd_ratio_7ree'] && $dongtai_onoff_7ree){
   	
     $race_7ree['aodds_7ree'] = $anum_7ree['odds_7ree'] ? sprintf("%.2f",$num_7ree['odds_7ree']*$race_7ree['odd_ratio_7ree']/$anum_7ree['odds_7ree']) : $race_7ree['aodds_7ree'];
   	
     $race_7ree['bodds_7ree'] = $bnum_7ree['odds_7ree'] ? sprintf("%.2f",$num_7ree['odds_7ree']*$race_7ree['odd_ratio_7ree']/$bnum_7ree['odds_7ree']) : $race_7ree['bodds_7ree'];
  	
     $race_7ree['codds_7ree'] = $race_7ree['codds_7ree'] && $cnum_7ree['odds_7ree'] ? sprintf("%.2f",$num_7ree['odds_7ree']*$race_7ree['odd_ratio_7ree']/$cnum_7ree['odds_7ree']) : $race_7ree['codds_7ree'];

	if($race_7ree['max_odd_7ree']){
	 	$race_7ree['aodds_7ree'] = min($race_7ree['aodds_7ree'],$race_7ree['max_odd_7ree']);
	 	$race_7ree['bodds_7ree'] = min($race_7ree['bodds_7ree'],$race_7ree['max_odd_7ree']);
	 	$race_7ree['codds_7ree'] = $race_7ree['codds_7ree'] ? min($race_7ree['codds_7ree'],$race_7ree['max_odd_7ree']) : 0;
	}
	if($race_7ree['min_odd_7ree']){
	 	$race_7ree['aodds_7ree'] = max($race_7ree['aodds_7ree'],$race_7ree['min_odd_7ree']);
	 	$race_7ree['bodds_7ree'] = max($race_7ree['bodds_7ree'],$race_7ree['min_odd_7ree']);
	 	$race_7ree['codds_7ree'] =  $race_7ree['codds_7ree'] ? max($race_7ree['codds_7ree'],$race_7ree['min_odd_7ree']): 0;
	}

     DB::fetch_first("UPDATE ".DB::table('jingcai_main_7ree')." SET
						  aodds_7ree =  '$race_7ree[aodds_7ree]',
						  bodds_7ree =  '$race_7ree[bodds_7ree]',
						  codds_7ree =  '$race_7ree[codds_7ree]'
						  WHERE main_id_7ree = '$main_id_7ree'");
    }
//////////////////////////动态赔率计算(结束)///////////////////////////////////

	showmessage('jingcai_7ree:php_lang_zhichichenggong_7ree',"plugin.php?id=jingcai_7ree:jingcai_7ree");
	
?>