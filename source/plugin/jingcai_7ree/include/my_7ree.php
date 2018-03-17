<?php
/*
	ID: jingcai_7ree
	[www.7ree.com] (C)2007-2017 7ree.com.
	This is NOT a freeware, use is subject to license terms
	Update: 2017/8/4 14:03
	Agreement: http://addon.discuz.com/?@7.developer.doc/agreement_7ree_html
	More Plugins: http://addon.discuz.com/?@7ree
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

	if($_GET['op_7ree'] == "get"){
			if($_GET['formhash'] <> FORMHASH) showmessage("Access Deined. @ 7ree.com");
			$log_id_7ree = intval($_GET['log_id_7ree']);
			
		    if($log_id_7ree) $mylog_7ree = DB::fetch_first("SELECT l.*, m.* FROM ".DB::table('jingcai_log_7ree')." l LEFT JOIN ".DB::table('jingcai_main_7ree')." m ON l.main_id_7ree = m.main_id_7ree WHERE l.log_id_7ree = '$log_id_7ree'");
		    
			if($mylog_7ree['log_reward_7ree']) showmessage('jingcai_7ree:php_lang_yilingqu_7ree',"plugin.php?id=jingcai_7ree:jingcai_7ree&ac_7ree=1");
			
			if($mylog_7ree['type_7ree']){
				if ($mylog_7ree['daxiaowin_7ree'] != $mylog_7ree['mywin_7ree']) showmessage('jingcai_7ree:php_lang_buzhengque_7ree',"plugin.php?id=jingcai_7ree:jingcai_7ree&ac_7ree=1");
			}else{
				if ($mylog_7ree['win_7ree'] != $mylog_7ree['mywin_7ree']) showmessage('jingcai_7ree:php_lang_buzhengque_7ree',"plugin.php?id=jingcai_7ree:jingcai_7ree&ac_7ree=1");
			} 
			
			if($mylog_7ree['type_7ree']){
				if($mylog_7ree['win_7ree']=="a"){
						$odds_7ree = 'daodds_7ree';
				}elseif($mylog_7ree['win_7ree']=="b"){
						$odds_7ree = 'xiaoodds_7ree';
				}
			}else{
				$odds_7ree = $mylog_7ree['mywin_7ree']."odds_7ree";
			} 
			$mylog_7ree['plan_reward_7ree'] = round($mylog_7ree['teamodds_7ree']?$mylog_7ree['myodds_7ree']*$mylog_7ree['teamodds_7ree']:$mylog_7ree['myodds_7ree']*$mylog_7ree[$odds_7ree]);

		    DB::query("UPDATE ".DB::table('jingcai_log_7ree')." SET log_reward_7ree = '{$mylog_7ree[plan_reward_7ree]}' WHERE log_id_7ree = '$log_id_7ree' AND uid_7ree='{$_G[uid]}'");

			DB::query("UPDATE ".DB::table('common_member_count')." SET {$extname_7ree} = {$extname_7ree} + {$mylog_7ree[plan_reward_7ree]} WHERE uid='{$mylog_7ree[uid_7ree]}' LIMIT 1");
			
			if($jingcai_7ree_var['noticeon_7ree']){
			$pmnotice_7ree = lang('plugin/jingcai_7ree', 'php_lang_lingqumsg1_7ree').$mylog_7ree['racename_7ree'].lang('plugin/jingcai_7ree', 'php_lang_lingqumsg2_7ree').$mylog_7ree['plan_reward_7ree'].$exttitle_7ree.lang('plugin/jingcai_7ree', 'php_lang_lingqumsg3_7ree');
			notification_add($mylog_7ree['uid_7ree'], 'system', $pmnotice_7ree, $notevar, 1);
		    }
			showmessage('jingcai_7ree:php_lang_chenggonglingqu_7ree',"plugin.php?id=jingcai_7ree:jingcai_7ree&ac_7ree=1");
			
			}elseif($_GET['op_7ree'] == "quit"){
			if($_GET['formhash'] <> FORMHASH) showmessage("Access Deined. @ 7ree.com");
			$log_id_7ree = intval($_GET['log_id_7ree']);
			
		    if($log_id_7ree) $mylog_7ree = DB::fetch_first("SELECT l.*, m.* FROM ".DB::table('jingcai_log_7ree')." l LEFT JOIN ".DB::table('jingcai_main_7ree')." m ON l.main_id_7ree = m.main_id_7ree WHERE l.log_id_7ree = '$log_id_7ree'");
		    
			if($mylog_7ree['mywin_7ree']<>'d'){
			DB::query("UPDATE ".DB::table('common_member_count')." SET {$extname_7ree} = {$extname_7ree} + '$mylog_7ree[myodds_7ree]' WHERE uid='{$mylog_7ree[uid_7ree]}' LIMIT 1");
			DB::query("UPDATE ".DB::table('jingcai_log_7ree')." SET mywin_7ree = 'd' WHERE log_id_7ree = '$log_id_7ree' LIMIT 1");
			if($jingcai_7ree_var['noticeon_7ree']){
				$pmnotice_7ree = lang('plugin/jingcai_7ree', 'php_lang_quitmsg1_7ree').$mylog_7ree['racename_7ree'].lang('plugin/jingcai_7ree', 'php_lang_quitmsg2_7ree').$mylog_7ree['myodds_7ree'].$exttitle_7ree.lang('plugin/jingcai_7ree', 'php_lang_quitmsg3_7ree');
				notification_add($mylog_7ree['uid_7ree'], 'system', $pmnotice_7ree, $notevar, 1);
				}
				showmessage('jingcai_7ree:php_lang_chenggongfanhuan_7ree',"plugin.php?id=jingcai_7ree:jingcai_7ree&ac_7ree=1");
				}	
	}else{
		
		//判断是否安装了开放竞猜插件，如果开启再模版中提供跳转按钮
		$opengsdb_7ree = DB::result_first("SHOW TABLES LIKE '".DB::table('x7ree_opengs_main')."'");
		
		
		
		
		
		if($_GET['type_7ree']){
			$type_7ree=dhtmlspecialchars(trim($_GET['type_7ree']));
			$type_where_7ree = "AND m.fenlei2_7ree='$type_7ree'";
			$rank_type_7ree = "AND type_7ree='$type_7ree'";
		}else{
			$type_where_7ree = "";
			$rank_type_7ree = "AND type_7ree=''";
		}
		
		    $page = max(1, intval($_GET['page']));
			$startpage = ($page - 1) * 20;
		    $querynum = DB::result_first("SELECT Count(*) FROM ".DB::table('jingcai_log_7ree')." l 
		    								LEFT JOIN ".DB::table('jingcai_main_7ree')." m ON l.main_id_7ree = m.main_id_7ree
		    								WHERE l.uid_7ree = '{$_G[uid]}' {$type_where_7ree}");
			$query = DB::query("SELECT l.*,m.* FROM ".DB::table('jingcai_log_7ree')." l 
											LEFT JOIN ".DB::table('jingcai_main_7ree')." m ON l.main_id_7ree = m.main_id_7ree
											WHERE  l.uid_7ree = '{$_G[uid]}' AND m.racename_7ree<>'' {$type_where_7ree} ORDER BY l.log_id_7ree DESC LIMIT {$startpage}, 20");
			while($result = DB::fetch($query)) {
				$result['log_time2_7ree']=gmdate("Y-m-d H:i", $result['log_time_7ree'] + $_G['setting']['timeoffset'] * 3600);
				$result['log_time_7ree']=gmdate("m-d", $result['log_time_7ree'] + $_G['setting']['timeoffset'] * 3600);
				$result['scname_7ree']=DB::result_first("SELECT scname_7ree FROM ".DB::table('jingcai_saicheng_7ree')." WHERE scid_7ree='{$result[scid_7ree]}'");

				for($i=0;$i<COUNT($titlebg_array);$i++){
					if($titlebg_array2[$i][0] == $result['racename_7ree']) $result['titlebg_7ree'] = $titlebg_array2[$i][1];
				}

				
				
				if($result['type_7ree']){
						if($result['mywin_7ree'] == $result['daxiaowin_7ree']){
							if($result['daxiaowin_7ree']=="a"){
									$odds_7ree = 'daodds_7ree';
							}elseif($result['daxiaowin_7ree']=="b"){
									$odds_7ree = 'xiaoodds_7ree';
							}
							$result['plan_reward_7ree'] = round($result['myodds_7ree'] * $result[$odds_7ree]) ;
						}else{
							$result['plan_reward_7ree'] = "0";
						}		
				}else{
						if($result['mywin_7ree'] == $result['win_7ree']){
							$odds_7ree = $result['mywin_7ree']."odds_7ree";
							//$result['plan_reward_7ree'] = round($result['myodds_7ree'] * $result[$odds_7ree]) ;
							$result['plan_reward_7ree'] = round($result['teamodds_7ree']?$result['myodds_7ree']*$result['teamodds_7ree']:$result['myodds_7ree']*$result[$odds_7ree]);
						}else{
							$result['plan_reward_7ree'] = "0";
						}
				}
				if($jingcai_7ree_var['autoon_7ree'] && $_G['uid']){
						if($result['win_7ree']=='d'){
						    if($result['mywin_7ree']<>'d' && $result['log_reward_7ree']==0){
								DB::query("UPDATE ".DB::table('common_member_count')." SET {$extname_7ree} = {$extname_7ree} + '$result[myodds_7ree]' WHERE uid='{$result[uid_7ree]}' LIMIT 1");
									DB::query("UPDATE ".DB::table('jingcai_log_7ree')." SET mywin_7ree = 'd' WHERE log_id_7ree = '$result[log_id_7ree]' LIMIT 1");
									$result['mywin_7ree']='d';
									if($jingcai_7ree_var['noticeon_7ree']){
											$pmnotice_7ree = lang('plugin/jingcai_7ree', 'php_lang_quitmsg1_7ree').$result['racename_7ree'].lang('plugin/jingcai_7ree', 'php_lang_quitmsg2_7ree').$result['myodds_7ree'].$exttitle_7ree.lang('plugin/jingcai_7ree', 'php_lang_quitmsg3_7ree');
											notification_add($result['uid_7ree'], 'system', $pmnotice_7ree, $notevar, 1);
									}
						    }
						}elseif(in_array($result['win_7ree'],array('a','b','c'))){
						    if($result['log_reward_7ree']==0){
							    if(($result['type_7ree'] && $result['mywin_7ree']==$result['daxiaowin_7ree'])||(!$result['type_7ree'] && $result['mywin_7ree']==$result['win_7ree'])){
							    		    DB::query("UPDATE ".DB::table('jingcai_log_7ree')." SET log_reward_7ree = '{$result[plan_reward_7ree]}' WHERE log_id_7ree = '$result[log_id_7ree]' AND uid_7ree='{$_G[uid]}'");
											DB::query("UPDATE ".DB::table('common_member_count')." SET {$extname_7ree} = {$extname_7ree} + {$result[plan_reward_7ree]} WHERE uid='{$result[uid_7ree]}' LIMIT 1");
				                            $result['log_reward_7ree']=$result['plan_reward_7ree'];
											if($jingcai_7ree_var['noticeon_7ree']){
													$pmnotice_7ree = lang('plugin/jingcai_7ree', 'php_lang_lingqumsg1_7ree').$result['racename_7ree'].lang('plugin/jingcai_7ree', 'php_lang_lingqumsg2_7ree').$result['plan_reward_7ree'].$exttitle_7ree.lang('plugin/jingcai_7ree', 'php_lang_lingqumsg3_7ree');
													notification_add($result['uid_7ree'], 'system', $pmnotice_7ree, $notevar, 1);
			    							}
							    }
						    }
						}
				}
				$loglist_7ree[] = $result;
			}
			    $multipage = multi($querynum, 20, $page, "plugin.php?id=jingcai_7ree:jingcai_7ree&ac_7ree=1&type_7ree=".$type_7ree );
			
			}
			 
			 if($_G['uid'] && $_GET['page']<2){
			 	memberinfo_count_7ree($_G['uid']);
			 	
			 	$myinfo_7ree = DB::fetch_first("SELECT * FROM ".DB::table('jingcai_member_7ree')." WHERE uid_7ree = '{$_G[uid ]}' {$rank_type_7ree}");
			 	$myext_7ree = DB::result_first("SELECT {$extname_7ree} FROM ".DB::table('common_member_count')." WHERE uid='{$_G[uid]}'");
			 }

?>