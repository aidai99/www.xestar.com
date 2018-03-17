<?php
/*
	ID: jingcai_7ree
	[www.7ree.com] (C)2007-2017 7ree.com.
	This is NOT a freeware, use is subject to license terms
	Update: 2017/10/25 19:48
	Agreement: http://addon.discuz.com/?@7.developer.doc/agreement_7ree_html
	More Plugins: http://addon.discuz.com/?@7ree
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
function settlement_7ree($main_id_7ree){
		global $_G; 
		$jingcai_7ree_var = $_G['cache']['plugin']['jingcai_7ree'];
		$return_7ree = FALSE;
		$extname_7ree = "extcredits".$jingcai_7ree_var['extcredit_7ree'];
		$exttitle_7ree = $_G['setting']['extcredits'][$jingcai_7ree_var['extcredit_7ree']][title];
		$main_id_7ree = intval($main_id_7ree);
		if(!$main_id_7ree) return $return_7ree;
		
		$query = DB::query("SELECT l.*, m.* FROM ".DB::table('jingcai_log_7ree')." l
											  LEFT JOIN ".DB::table('jingcai_main_7ree')." m ON l.main_id_7ree = m.main_id_7ree
											  WHERE l.log_reward_7ree=0 AND l.main_id_7ree = ".$main_id_7ree);
		
		while($thisitem_7ree = DB::fetch($query)){
				$countlist_7ree[]=$thisitem_7ree[uid_7ree];
				//奖励积分计算
				if($thisitem_7ree['type_7ree']){
						if($thisitem_7ree['mywin_7ree'] == $thisitem_7ree['daxiaowin_7ree']){
								if($thisitem_7ree['daxiaowin_7ree']=="a"){
										$odds_7ree = 'daodds_7ree';
								}elseif($thisitem_7ree['daxiaowin_7ree']=="b"){
										$odds_7ree = 'xiaoodds_7ree';
								}
								$thisitem_7ree['plan_reward_7ree'] = round($thisitem_7ree['myodds_7ree'] * $thisitem_7ree[$odds_7ree]);
						}else{
								$thisitem_7ree['plan_reward_7ree'] = "0";
						}
				}else{
						if($thisitem_7ree['mywin_7ree'] == $thisitem_7ree['win_7ree']){
							$odds_7ree = $thisitem_7ree['mywin_7ree']."odds_7ree";
							//$thisitem_7ree['plan_reward_7ree'] = round($thisitem_7ree['myodds_7ree'] * $thisitem_7ree[$odds_7ree]) ;
							$thisitem_7ree['plan_reward_7ree'] = round($thisitem_7ree['teamodds_7ree']?$thisitem_7ree['myodds_7ree']*$thisitem_7ree['teamodds_7ree']:$thisitem_7ree['myodds_7ree']*$thisitem_7ree[$odds_7ree]);
						}else{
							$thisitem_7ree['plan_reward_7ree'] = "0";
						}
				}
				//会员积分增加
					if($thisitem_7ree['win_7ree']=='d'){
						if($thisitem_7ree['mywin_7ree']<>'d' && $thisitem_7ree['log_reward_7ree']==0){//领取日志更新 论坛短信通知
							        DB::query("UPDATE ".DB::table('common_member_count')." SET {$extname_7ree} = {$extname_7ree} + '$thisitem_7ree[myodds_7ree]' WHERE uid='{$thisitem_7ree[uid_7ree]}' LIMIT 1");
									DB::query("UPDATE ".DB::table('jingcai_log_7ree')." SET mywin_7ree = 'd' WHERE log_id_7ree = '$thisitem_7ree[log_id_7ree]' LIMIT 1");
						if($jingcai_7ree_var['noticeon_7ree']){
									$pmnotice_7ree = lang('plugin/jingcai_7ree', 'php_lang_quitmsg1_7ree').$thisitem_7ree['racename_7ree'].lang('plugin/jingcai_7ree', 'php_lang_quitmsg2_7ree').$thisitem_7ree['myodds_7ree'].$exttitle_7ree.lang('plugin/jingcai_7ree', 'php_lang_quitmsg3_7ree');
									notification_add($thisitem_7ree['uid_7ree'], 'system', $pmnotice_7ree, $notevar, 1);
						}
					}
				}elseif(in_array($thisitem_7ree['win_7ree'],array('a','b','c'))){
					if($thisitem_7ree['log_reward_7ree']==0){
					if(($thisitem_7ree['type_7ree'] && $thisitem_7ree['mywin_7ree']==$thisitem_7ree['daxiaowin_7ree'])||(!$thisitem_7ree['type_7ree'] && $thisitem_7ree['mywin_7ree']==$thisitem_7ree['win_7ree'])){//领取日志更新 论坛短信通知
							DB::query("UPDATE ".DB::table('jingcai_log_7ree')." SET log_reward_7ree = '{$thisitem_7ree[plan_reward_7ree]}' WHERE log_id_7ree = '$thisitem_7ree[log_id_7ree]' AND uid_7ree='$thisitem_7ree[uid_7ree]'");
							DB::query("UPDATE ".DB::table('common_member_count')." SET {$extname_7ree} = {$extname_7ree} + {$thisitem_7ree[plan_reward_7ree]} WHERE uid='{$thisitem_7ree[uid_7ree]}' LIMIT 1");
							if($jingcai_7ree_var['noticeon_7ree']){
										$pmnotice_7ree = lang('plugin/jingcai_7ree', 'php_lang_lingqumsg1_7ree').$thisitem_7ree['racename_7ree'].lang('plugin/jingcai_7ree', 'php_lang_lingqumsg2_7ree').$thisitem_7ree['plan_reward_7ree'].$exttitle_7ree.lang('plugin/jingcai_7ree', 'php_lang_lingqumsg3_7ree');
										notification_add($thisitem_7ree['uid_7ree'], 'system', $pmnotice_7ree, $notevar, 1);
			    			}
						}
					}
				}
		}

		

		//会员数据更新
		
		if(COUNT($countlist_7ree)){
			$countlist_7ree=array_unique($countlist_7ree);
			foreach($countlist_7ree as $key=>$countvalue_7ree){
				memberinfo_count_7ree($countvalue_7ree);
			}
		}
		$return_7ree = TRUE;
		return $return_7ree;
}


function memberinfo_count_7ree($uid_7ree){
		global $_G; 
		$jingcai_7ree_var = $_G['cache']['plugin']['jingcai_7ree'];
		$return_7ree = FALSE;
		if(!$uid_7ree){
				$uid_7ree = $_G['uid'];
		        $username_7ree = $_G['username'];
		}else{
				$username_7ree = DB::result_first("SELECT username FROM ".DB::table('common_member')." WHERE uid = ".$uid_7ree);
		}

		if($uid_7ree){
			
			
			if($jingcai_7ree_var['sortdata_7ree']){//判断是否按照赛事分类读取详细数据
				//读取分类
				$class_7ree=array();
				$fenlei_7ree =  str_replace("\n","|||",$jingcai_7ree_var['fenlei_7ree']);
				$fenlei_array =  explode('|||', $fenlei_7ree);
				foreach($fenlei_array as $key=>$fenlei_value){
						$fenlei_array2[$key] = explode(',',trim($fenlei_value));
						array_shift($fenlei_array2[$key]);
				}
				$class_7ree=merge_array($fenlei_array2);
				$class_7ree[]='';
			}

			//循环读取会员全部赛事分类的数据
			//判断会员各个分类是否已有日志记录
			$query = DB::query("SELECT id_7ree,type_7ree FROM ".DB::table('jingcai_member_7ree')." WHERE uid_7ree = ".$uid_7ree);
			while($log_table = DB::fetch($query)){
					$userlog_7ree[$log_table['type_7ree']]=$log_table['id_7ree'];
			}

			foreach($class_7ree as $class_value_7ree){
					$updatevalue_7ree = array();
					$wherevalue_7ree = array();
					$thisinfo_7ree = array();
					$thisinfo_7ree = get_count_7ree($uid_7ree,$class_value_7ree);
					$updatevalue_7ree = array('uid_7ree' => $uid_7ree,'username_7ree' => $username_7ree);
					$updatevalue_7ree = $updatevalue_7ree + $thisinfo_7ree;
					
					
					if(isset($userlog_7ree[$class_value_7ree])){
						$wherevalue_7ree = array('uid_7ree' => $uid_7ree,'type_7ree' => $class_value_7ree);
						DB::update('jingcai_member_7ree', $updatevalue_7ree, $wherevalue_7ree);
					}else{
						DB::insert('jingcai_member_7ree', $updatevalue_7ree);
					}

					unset($updatevalue_7ree);
					unset($wherevalue_7ree);
			}
			$return_7ree = TRUE;
		}

		return $return_7ree;

}

function get_count_7ree($uid_7ree,$type_7ree=''){
		global $_G; 
		$return_7ree=array();
		if(!$uid_7ree) return $return_7ree;
		$jingcai_7ree_var = $_G['cache']['plugin']['jingcai_7ree'];
		
		if($type_7ree){
			$type_7ree=daddslashes(dhtmlspecialchars(trim($type_7ree)));
			$type_where_7ree = "AND m.fenlei2_7ree='$type_7ree'";
			$rank_type_7ree = "AND type_7ree='$type_7ree'";
		}else{
			$type_where_7ree = "";
			$rank_type_7ree = "AND type_7ree=''";
		}
		
				$time_y_7ree = " AND l.log_time_7ree>".gettime_7ree(5);
				$time_q_7ree = " AND l.log_time_7ree>".gettime_7ree(4);
				$time_m_7ree = " AND l.log_time_7ree>".gettime_7ree(3);
				$time_w_7ree = " AND l.log_time_7ree>".gettime_7ree(2);
				$time_m2_7ree = " AND l.log_time_7ree>".gettime_7ree(6)."  AND l.log_time_7ree<".gettime_7ree(3);
				$time_w2_7ree = " AND l.log_time_7ree>".gettime_7ree(7)."  AND l.log_time_7ree<".gettime_7ree(2);


		//上榜场次最低要求
			if($jingcai_7ree_var['rankrq_fenlei_7ree']){
					$rankrq_7ree = $jingcai_7ree_var['rankrq_7ree'] ? $jingcai_7ree_var['rankrq_7ree'] : 0;
					$rankrq_fenlei_array =  str_replace("\n","|||",$jingcai_7ree_var['rankrq_fenlei_7ree']);
					$rankrq_fenlei_array2 =  explode('|||', $rankrq_fenlei_array );
					foreach($rankrq_fenlei_array2 as $rankrq_fenlei_array2_value){
							$rankrq_fenlei_7ree = explode('=',trim($rankrq_fenlei_array2_value));
							if($rankrq_fenlei_7ree[0]==$type_7ree){
								$rankrq_7ree = $rankrq_fenlei_7ree[1];
								break;
							}
					}
			}
				//本季度数据统计
				$a_member_7ree = DB::fetch_first("SELECT COUNT(l.log_id_7ree) AS changci_7ree,
														SUM(l.log_reward_7ree) AS yingli_7ree,
														SUM(l.myodds_7ree) AS allodds_7ree
														FROM ".DB::table('jingcai_log_7ree')." l LEFT JOIN  ".DB::table('jingcai_main_7ree')." m
														ON m.main_id_7ree = l.main_id_7ree
														WHERE m.win_7ree<>'' AND l.uid_7ree='$uid_7ree' AND l.mywin_7ree<>'d' {$type_where_7ree} {$time_q_7ree}");
				$a_member_7ree['jingli_7ree'] = $a_member_7ree['yingli_7ree'] -  $a_member_7ree['allodds_7ree'];
				$a_caidui_7ree = DB::result_first("SELECT COUNT(*) FROM ".DB::table('jingcai_log_7ree')." l LEFT JOIN  ".DB::table('jingcai_main_7ree')." m ON m.main_id_7ree = l.main_id_7ree 
													WHERE l.uid_7ree='$uid_7ree' AND l.mywin_7ree<>'d' AND l.log_reward_7ree>0 {$type_where_7ree} {$time_q_7ree}");

				$a_mingzhong_7ree = $a_member_7ree['changci_7ree'] ? sprintf("%.2f", ($a_caidui_7ree/$a_member_7ree['changci_7ree'])*100) : 0;
				//showmessage('a_caidui_7ree='.$a_caidui_7ree.'; changci_7ree='.$a_member_7ree['changci_7ree'].'; a_mingzhong_7ree='.$a_mingzhong_7ree);
				if(!$rankrq_7ree || $a_member_7ree['changci_7ree']>=$rankrq_7ree){
					$a_mzlrank_7ree = DB::result_first("SELECT COUNT(*) FROM ".DB::table('jingcai_member_7ree')." WHERE uid_7ree<>'$uid_7ree' AND a_changci_7ree>=$rankrq_7ree AND a_mingzhong_7ree>$a_mingzhong_7ree {$rank_type_7ree}")+1;
				}else{
					$a_mzlrank_7ree = 0;
				}
		        //年数据统计
				$y_member_7ree = DB::fetch_first("SELECT COUNT(l.log_id_7ree) AS changci_7ree, 
														SUM(l.log_reward_7ree) AS yingli_7ree,
														SUM(l.myodds_7ree) AS allodds_7ree
														FROM ".DB::table('jingcai_log_7ree')." l LEFT JOIN  ".DB::table('jingcai_main_7ree')." m
														ON m.main_id_7ree = l.main_id_7ree
														WHERE m.win_7ree<>'' AND l.uid_7ree='$uid_7ree' AND l.mywin_7ree<>'d' {$type_where_7ree} {$time_y_7ree}");
				$y_member_7ree['jingli_7ree'] = $y_member_7ree['yingli_7ree'] -  $y_member_7ree['allodds_7ree'];
				$y_caidui_7ree = DB::result_first("SELECT COUNT(*) FROM ".DB::table('jingcai_log_7ree')." l LEFT JOIN  ".DB::table('jingcai_main_7ree')." m ON m.main_id_7ree = l.main_id_7ree
														WHERE l.uid_7ree='$uid_7ree' AND l.mywin_7ree<>'d' AND l.log_reward_7ree>0 {$type_where_7ree} {$time_y_7ree}");
				$y_mingzhong_7ree = $y_member_7ree['changci_7ree'] ? sprintf("%.2f", ($y_caidui_7ree/$y_member_7ree['changci_7ree'])*100) : 0;
				if(!$rankrq_7ree || $y_member_7ree['changci_7ree']>=$rankrq_7ree){
					$y_mzlrank_7ree = DB::result_first("SELECT COUNT(*) FROM ".DB::table('jingcai_member_7ree')." WHERE uid_7ree<>'$uid_7ree' AND y_changci_7ree>=$rankrq_7ree AND y_mingzhong_7ree>$y_mingzhong_7ree {$rank_type_7ree}")+1;
				}else{
					$y_mzlrank_7ree = 0;
				}
		        //月数据统计
				$m_member_7ree = DB::fetch_first("SELECT COUNT(l.log_id_7ree) AS changci_7ree, 
														SUM(l.log_reward_7ree) AS yingli_7ree,
														SUM(l.myodds_7ree) AS allodds_7ree
														FROM ".DB::table('jingcai_log_7ree')." l LEFT JOIN  ".DB::table('jingcai_main_7ree')." m
														ON m.main_id_7ree = l.main_id_7ree
														WHERE m.win_7ree<>'' AND l.uid_7ree='$uid_7ree' AND l.mywin_7ree<>'d' {$type_where_7ree} {$time_m_7ree}");
				$m_member_7ree['jingli_7ree'] = $m_member_7ree['yingli_7ree'] -  $m_member_7ree['allodds_7ree'];
				$m_caidui_7ree = DB::result_first("SELECT COUNT(*) FROM ".DB::table('jingcai_log_7ree')." l LEFT JOIN  ".DB::table('jingcai_main_7ree')." m ON m.main_id_7ree = l.main_id_7ree
														WHERE l.uid_7ree='$uid_7ree' AND l.mywin_7ree<>'d' AND l.log_reward_7ree>0 {$type_where_7ree} {$time_m_7ree}");
				$m_mingzhong_7ree = $m_member_7ree['changci_7ree'] ? sprintf("%.2f", ($m_caidui_7ree/$m_member_7ree['changci_7ree'])*100) : 0;
				if(!$rankrq_7ree || $m_member_7ree['changci_7ree']>=$rankrq_7ree){
					$m_mzlrank_7ree = DB::result_first("SELECT COUNT(*) FROM ".DB::table('jingcai_member_7ree')." WHERE uid_7ree<>'$uid_7ree' AND m_changci_7ree>=$rankrq_7ree AND m_mingzhong_7ree>$m_mingzhong_7ree {$rank_type_7ree}")+1;
				}else{
					$m_mzlrank_7ree = 0;
				}
		        //周数据统计
				$w_member_7ree = DB::fetch_first("SELECT COUNT(l.log_id_7ree) AS changci_7ree, 
														SUM(l.log_reward_7ree) AS yingli_7ree,
														SUM(l.myodds_7ree) AS allodds_7ree
														FROM ".DB::table('jingcai_log_7ree')." l LEFT JOIN  ".DB::table('jingcai_main_7ree')." m
														ON m.main_id_7ree = l.main_id_7ree
														WHERE m.win_7ree<>'' AND l.uid_7ree='$uid_7ree' AND l.mywin_7ree<>'d' {$type_where_7ree} {$time_w_7ree}");
				$w_member_7ree['jingli_7ree'] = $w_member_7ree['yingli_7ree'] -  $w_member_7ree['allodds_7ree'];
				$w_caidui_7ree = DB::result_first("SELECT COUNT(*) FROM ".DB::table('jingcai_log_7ree')." l LEFT JOIN  ".DB::table('jingcai_main_7ree')." m ON m.main_id_7ree = l.main_id_7ree
														WHERE l.uid_7ree='$uid_7ree' AND l.mywin_7ree<>'d' AND l.log_reward_7ree>0 {$type_where_7ree} {$time_w_7ree}");
				$w_mingzhong_7ree = $w_member_7ree['changci_7ree'] ? sprintf("%.2f", ($w_caidui_7ree/$w_member_7ree['changci_7ree'])*100) : 0;
				if(!$rankrq_7ree || $w_member_7ree['changci_7ree']>=$rankrq_7ree){
					$w_mzlrank_7ree = DB::result_first("SELECT COUNT(*) FROM ".DB::table('jingcai_member_7ree')." WHERE uid_7ree<>'$uid_7ree' AND w_changci_7ree>=$rankrq_7ree AND w_mingzhong_7ree>$w_mingzhong_7ree {$rank_type_7ree}")+1;
				}else{
					$w_mzlrank_7ree = 0;
				}
				
				
				
				
		        //上月数据统计
				$m2_member_7ree = DB::fetch_first("SELECT COUNT(l.log_id_7ree) AS changci_7ree, 
														SUM(l.log_reward_7ree) AS yingli_7ree,
														SUM(l.myodds_7ree) AS allodds_7ree
														FROM ".DB::table('jingcai_log_7ree')." l LEFT JOIN  ".DB::table('jingcai_main_7ree')." m
														ON m.main_id_7ree = l.main_id_7ree
														WHERE m.win_7ree<>'' AND l.uid_7ree='$uid_7ree' AND l.mywin_7ree<>'d' {$type_where_7ree} {$time_m2_7ree}");
				$m2_member_7ree['jingli_7ree'] = $m2_member_7ree['yingli_7ree'] -  $m2_member_7ree['allodds_7ree'];
				$m2_caidui_7ree = DB::result_first("SELECT COUNT(*) FROM ".DB::table('jingcai_log_7ree')." l LEFT JOIN  ".DB::table('jingcai_main_7ree')." m ON m.main_id_7ree = l.main_id_7ree
														WHERE l.uid_7ree='$uid_7ree' AND l.mywin_7ree<>'d' AND l.log_reward_7ree>0 {$type_where_7ree} {$time_m2_7ree}");
				$m2_mingzhong_7ree = $m2_member_7ree['changci_7ree'] ? sprintf("%.2f", ($m2_caidui_7ree/$m2_member_7ree['changci_7ree'])*100) : 0;
				if(!$rankrq_7ree || $m2_member_7ree['changci_7ree']>=$rankrq_7ree){
					$m2_mzlrank_7ree = DB::result_first("SELECT COUNT(*) FROM ".DB::table('jingcai_member_7ree')." WHERE uid_7ree<>'$uid_7ree' AND m2_changci_7ree>=$rankrq_7ree AND m2_mingzhong_7ree>$m2_mingzhong_7ree {$rank_type_7ree}")+1;
				}else{
					$m2_mzlrank_7ree = 0;
				}
		        //上周数据统计
				$w2_member_7ree = DB::fetch_first("SELECT COUNT(l.log_id_7ree) AS changci_7ree, 
														SUM(l.log_reward_7ree) AS yingli_7ree,
														SUM(l.myodds_7ree) AS allodds_7ree
														FROM ".DB::table('jingcai_log_7ree')." l LEFT JOIN  ".DB::table('jingcai_main_7ree')." m
														ON m.main_id_7ree = l.main_id_7ree
														WHERE m.win_7ree<>'' AND l.uid_7ree='$uid_7ree' AND l.mywin_7ree<>'d' {$type_where_7ree} {$time_w2_7ree}");
				$w2_member_7ree['jingli_7ree'] = $w2_member_7ree['yingli_7ree'] -  $w2_member_7ree['allodds_7ree'];
				$w2_caidui_7ree = DB::result_first("SELECT COUNT(*) FROM ".DB::table('jingcai_log_7ree')." l LEFT JOIN  ".DB::table('jingcai_main_7ree')." m ON m.main_id_7ree = l.main_id_7ree
														WHERE l.uid_7ree='$uid_7ree' AND l.mywin_7ree<>'d' AND l.log_reward_7ree>0 {$type_where_7ree} {$time_w2_7ree}");
				$w2_mingzhong_7ree = $w_member_7ree['changci_7ree'] ? sprintf("%.2f", ($w2_caidui_7ree/$w2_member_7ree['changci_7ree'])*100) : 0;
				if(!$rankrq_7ree || $w2_member_7ree['changci_7ree']>=$rankrq_7ree){
					$w2_mzlrank_7ree = DB::result_first("SELECT COUNT(*) FROM ".DB::table('jingcai_member_7ree')." WHERE uid_7ree<>'$uid_7ree' AND w2_changci_7ree>=$rankrq_7ree AND w2_mingzhong_7ree>$w2_mingzhong_7ree {$rank_type_7ree}")+1;
				}else{
					$w2_mzlrank_7ree = 0;
				}


				//重点推荐连胜数据计算
				//上次连胜数据读取
				$this_rank_7ree = DB::fetch_first("SELECT * FROM ".DB::table('jingcai_member_7ree')." WHERE uid_7ree = '{$uid_7ree}' {$rank_type_7ree}");
				//echo("SELECT * FROM ".DB::table('jingcai_member_7ree')." WHERE uid_7ree = '{$uid_7ree}' {$rank_type_7ree}"."<br>");
				if($this_rank_7ree){
						//查询会员最近一次重点竞猜
						$new_log_7ree = DB::fetch_first("SELECT l.* FROM ".DB::table('jingcai_log_7ree')." l LEFT JOIN  ".DB::table('jingcai_main_7ree')." m
													ON m.main_id_7ree = l.main_id_7ree
													WHERE m.win_7ree NOT IN('','d') AND l.uid_7ree = '{$uid_7ree}' AND l.point_7ree > 0 {$type_where_7ree}
													ORDER BY l.log_id_7ree DESC");
						if($new_log_7ree['log_reward_7ree']>0){//最近一场比赛猜对，计算连胜场次
							//连胜数据更新的条件：最近一次重点竞猜main-id不能<=用户数据表中记录的main-id；
							//如果符合更新条件，则判断，最近一次重点竞猜是否正确，如果正确+1，如果错误归0；
							if($this_rank_7ree['last_main_id_7ree']<$new_log_7ree['main_id_7ree']){
								$zdly_7ree=$this_rank_7ree['zdly_7ree']+1;
							}else{
								$zdly_7ree=$this_rank_7ree['zdly_7ree'];
							}
							
						}else{//最近一场比赛猜错，连胜场次归零
							$zdly_7ree=0;
						}
						$last_main_id_7ree=$new_log_7ree['main_id_7ree'] ? $new_log_7ree['main_id_7ree'] : 0;
						//重点连胜排名计算
						$zdlyrank_7ree = DB::result_first("SELECT COUNT(*) FROM ".DB::table('jingcai_member_7ree')." WHERE uid_7ree<>'$uid_7ree' AND zdly_7ree>$zdly_7ree {$rank_type_7ree}")+1;
				}
				//echo($type_7ree.":zdly=".$zdly_7ree."//mid=".$last_main_id_7ree."<br>");
				
				$return_7ree=array(
									'type_7ree' => $type_7ree,
									'a_changci_7ree' => $a_member_7ree['changci_7ree'],
									'a_caidui_7ree' => $a_caidui_7ree,
									'a_yingli_7ree' => $a_member_7ree['yingli_7ree'],
									'a_jingli_7ree' => $a_member_7ree['jingli_7ree'],
									'a_mingzhong_7ree' => $a_mingzhong_7ree,
									'a_mzlrank_7ree' => $a_mzlrank_7ree,
									'y_changci_7ree' => $y_member_7ree['changci_7ree'],
									'y_caidui_7ree' => $y_caidui_7ree,
									'y_yingli_7ree' => $y_member_7ree['yingli_7ree'],
									'y_jingli_7ree' => $y_member_7ree['jingli_7ree'],
									'y_mingzhong_7ree' => $y_mingzhong_7ree,
									'y_mzlrank_7ree' => $y_mzlrank_7ree,
									'm_changci_7ree' => $m_member_7ree['changci_7ree'],
									'm_caidui_7ree' => $m_caidui_7ree,
									'm_yingli_7ree' => $m_member_7ree['yingli_7ree'],
									'm_jingli_7ree' => $m_member_7ree['jingli_7ree'],
									'm_mingzhong_7ree' => $m_mingzhong_7ree,
									'm_mzlrank_7ree' => $m_mzlrank_7ree,
									'm2_changci_7ree' => $m2_member_7ree['changci_7ree'],
									'm2_caidui_7ree' => $m2_caidui_7ree,
									'm2_yingli_7ree' => $m2_member_7ree['yingli_7ree'],
									'm2_jingli_7ree' => $m2_member_7ree['jingli_7ree'],
									'm2_mingzhong_7ree' => $m2_mingzhong_7ree,
									'm2_mzlrank_7ree' => $m2_mzlrank_7ree,
									'w_changci_7ree' => $w_member_7ree['changci_7ree'],
									'w_caidui_7ree' => $w_caidui_7ree,
									'w_yingli_7ree' => $w_member_7ree['yingli_7ree'],
									'w_jingli_7ree' => $w_member_7ree['jingli_7ree'],
									'w_mingzhong_7ree' => $w_mingzhong_7ree,
									'w_mzlrank_7ree' => $w_mzlrank_7ree,
									'w2_changci_7ree' => $w2_member_7ree['changci_7ree'],
									'w2_caidui_7ree' => $w2_caidui_7ree,
									'w2_yingli_7ree' => $w2_member_7ree['yingli_7ree'],
									'w2_jingli_7ree' => $w2_member_7ree['jingli_7ree'],
									'w2_mingzhong_7ree' => $w2_mingzhong_7ree,
									'w2_mzlrank_7ree' => $w2_mzlrank_7ree,
									'zdly_7ree' => $zdly_7ree,
									'last_main_id_7ree' => $last_main_id_7ree,
				);
		        return $return_7ree;
}


function gettime_7ree($format_7ree){
		global $_G;
		$jingcai_7ree_var = $_G['cache']['plugin']['jingcai_7ree'];
		$return_7ree = 0;

		switch ($format_7ree){
		case 1://本日
		  	$return_7ree = mktime(0,0,0,gmdate("m",$_G[timestamp] + $_G[setting][timeoffset] * 3600),gmdate("d",$_G[timestamp] + $_G[setting][timeoffset] * 3600),gmdate("Y",$_G[timestamp] + $_G[setting][timeoffset] * 3600));
		  	break;
		case 2://本周
		  	$return_7ree = mktime(0, 0, 0, gmdate("m",strtotime("last Monday") + $_G[setting][timeoffset] * 3600),date("d",strtotime("last Monday") + $_G[setting][timeoffset] * 3600),date("Y",strtotime("last Monday") + $_G[setting][timeoffset] * 3600));
		  	break;
		case 3://本月
  			$return_7ree = mktime(0,0,0,gmdate("m",$_G[timestamp] + $_G[setting][timeoffset] * 3600),1,gmdate("Y",$_G[timestamp] + $_G[setting][timeoffset] * 3600));
  			break;
		case 4://本季度
			$season = ceil((gmdate("n",$_G[timestamp] + $_G[setting][timeoffset] * 3600))/3);
			$return_7ree = mktime(0, 0, 0,$season*3-3+1,1,date('Y'));
		  	break;
		case 5://本年度
		  	$return_7ree = mktime(0,0,0,1,1,gmdate("Y",$_G[timestamp] + $_G[setting][timeoffset] * 3600));
 		 	break;
		case 6://上月
		  	$return_7ree = strtotime(gmdate('Y',$_G[timestamp] + $_G[setting][timeoffset] * 3600).'-'.(gmdate('m',$_G[timestamp] + $_G[setting][timeoffset] * 3600)-1).'-01');
 		 	break;
		case 7://上周
		  	$return_7ree = mktime(0, 0, 0, gmdate("m",strtotime("last Monday") + $_G[setting][timeoffset] * 3600),date("d",strtotime("last Monday") + $_G[setting][timeoffset] * 3600),date("Y",strtotime("last Monday") + $_G[setting][timeoffset] * 3600))-86400*7;
 		 	break;
		default:
 		 	$return_7ree = mktime(0,0,0,gmdate("m",$_G[timestamp] + $_G[setting][timeoffset] * 3600),gmdate("d",$_G[timestamp] + $_G[setting][timeoffset] * 3600),gmdate("Y",$_G[timestamp] + $_G[setting][timeoffset] * 3600));
		}

		return $return_7ree;

}

function merge_array($array){
	return call_user_func_array('array_merge',$array);
}

?>