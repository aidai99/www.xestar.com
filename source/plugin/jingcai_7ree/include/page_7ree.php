<?php
/*
	ID: jingcai_7ree
	[www.7ree.com] (C)2007-2017 7ree.com.
	This is NOT a freeware, use is subject to license terms
	Update: 2017/8/4 15:59
	Agreement: http://addon.discuz.com/?@7.developer.doc/agreement_7ree_html
	More Plugins: http://addon.discuz.com/?@7ree
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
    $main_id_7ree = intval($_GET['main_id_7ree']);
    $race_7ree = DB::fetch_first("SELECT * FROM ".DB::table('jingcai_main_7ree')." WHERE main_id_7ree = '$main_id_7ree'");
    if(!$race_7ree) showmessage("Bad MainID #7ree_error.");
    $race_7ree['open_7ree'] = $race_7ree['time_7ree']-$jingcai_7ree_var['stoptime_7ree']*60 > $_G['timestamp'] ? 1 : 0;
    $race_7ree['time2_7ree'] = $race_7ree['time_7ree']-$jingcai_7ree_var['stoptime_7ree']*60 > $_G['timestamp'] ? ($race_7ree['time_7ree']-$jingcai_7ree_var['stoptime_7ree']*60)* 1000 : 0;
    $race_7ree['time_7ree'] = gmdate("Y-m-d H:i", $race_7ree['time_7ree'] + $_G['setting']['timeoffset'] * 3600);

    if($race_7ree['time2_7ree']){
    	$next_mid_7ree = DB::result_first("SELECT main_id_7ree FROM ".DB::table('jingcai_main_7ree')." WHERE main_id_7ree > '$main_id_7ree' AND time_7ree -$jingcai_7ree_var[stoptime_7ree] > $_G[timestamp] ORDER BY main_id_7ree DESC");
    }
    require_once libfile('function/discuzcode');
    $race_7ree['racemessage_7ree'] = discuzcode($race_7ree['racemessage_7ree']);
    

    $navtitle = $navtitle.' - '.$race_7ree['racename_7ree'] ;

    if($_G['uid']){
		$myext_7ree = DB::result_first("SELECT {$extname_7ree} FROM ".DB::table('common_member_count')." WHERE uid='{$_G[uid]}'");
		$mywin_7ree = DB::fetch_first("SELECT mywin_7ree,myodds_7ree FROM ".DB::table('jingcai_log_7ree')." WHERE main_id_7ree = '$main_id_7ree' AND uid_7ree='{$_G[uid]}' AND type_7ree = 0");
		if($race_7ree['daxiao_7ree']) $mywin2_7ree = DB::fetch_first("SELECT mywin_7ree,myodds_7ree FROM ".DB::table('jingcai_log_7ree')." WHERE main_id_7ree = '$main_id_7ree' AND uid_7ree='{$_G[uid]}' AND type_7ree = 1");
    }
    
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
    
    
    
    $page = max(1, intval($_GET['page']));
	$startpage = ($page - 1) * 20;
	$querynum = DB::result_first("SELECT Count(*) FROM ".DB::table('jingcai_log_7ree')." WHERE main_id_7ree = '$main_id_7ree'");
	$query = DB::query("SELECT l.*,m.* FROM ".DB::table('jingcai_log_7ree')." l LEFT JOIN ".DB::table('jingcai_main_7ree')." m ON l.main_id_7ree = m.main_id_7ree  WHERE racename_7ree<>'' AND l.main_id_7ree ='$main_id_7ree' ORDER BY l.log_id_7ree DESC LIMIT {$startpage}, 20");
	while($result = DB::fetch($query)) {
		$result['log_time_7ree']=gmdate("Y-m-d H:i", $result['log_time_7ree'] + $_G['setting']['timeoffset'] * 3600);
		$newjingcailist_7ree[] = $result;
	}
	    $multipage = multi($querynum, 20, $page, "plugin.php?id=jingcai_7ree:jingcai_7ree&sp_7ree=more&main_id_7ree=".$main_id_7ree );
    
    
     if($race_7ree['tid_7ree']){
     		require_once libfile('function/discuzcode');
			$query= DB::query("SELECT author,authorid,dateline,message FROM ".DB::table('forum_post')." WHERE first = 0 AND tid = '{$race_7ree['tid_7ree']}' ORDER BY pid LIMIT 200");
			while($result = DB::fetch($query)) {
				$result['dateline']=dgmdate($result['dateline'],'u');
				$result['avatar_7ree']=avatar($result['authorid'],small);
				$result['message'] = discuzcode($result['message']);
				$reply_7ree[] = $result;
			}
     }
     
     
	if($jingcai_7ree_var['history_7ree']){
			$query = DB::query("SELECT * FROM ".DB::table('jingcai_main_7ree')." WHERE main_id_7ree<$main_id_7ree AND win_7ree<>'' ORDER BY main_id_7ree DESC LIMIT 20");
			while($result = DB::fetch($query)) {
				$result['time_7ree']=gmdate("Y-m-d H:i", $result['time_7ree'] + $_G['setting']['timeoffset'] * 3600);
				$lishijingcailist_7ree[] = $result;
			}
	}

	$opengsdb_7ree = DB::result_first("SHOW TABLES LIKE '".DB::table('x7ree_opengs_main')."'");
	if($opengsdb_7ree){
			$query = DB::query("SELECT * FROM ".DB::table('x7ree_opengs_main')." WHERE mid_7ree = '$main_id_7ree' ORDER BY id_7ree LIMIT 10");
			while($result = DB::fetch($query)) {
				$result['time_7ree']=gmdate("Y-m-d H:i", $result['time_7ree'] + $_G['setting']['timeoffset'] * 3600);
				//读取我的开放竞猜参与情况
				if($_G['uid']){
					$result['myoid_7ree']=DB::result_first("SELECT oid_7ree FROM ".DB::table('x7ree_opengs_log')." WHERE uid_7ree='$_G[uid]' AND rid_7ree='$result[id_7ree]'");
				}
				//读取开放竞猜选项
				$query2 = DB::query("SELECT * FROM ".DB::table('x7ree_opengs_option')." WHERE rid_7ree = '$result[id_7ree]' ORDER BY id_7ree LIMIT 20");
				while($optionresult = DB::fetch($query2)) {
					$result['option_7ree'][]=$optionresult;
				}
				$opengslist_7ree[] = $result;
			}
	}

?>