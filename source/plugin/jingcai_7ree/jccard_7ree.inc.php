<?php
/*
	ID: jingcai_7ree
	[www.7ree.com] (C)2007-2016 7ree.com.
	This is NOT a freeware, use is subject to license terms
	Update: 2016/9/6 11:15
	Agreement: http://addon.discuz.com/?@7.developer.doc/agreement_7ree_html
	More Plugins: http://addon.discuz.com/?@7ree
*/		
	if(!defined('IN_DISCUZ')) {
		exit('Access Denied');
	}

	$jingcai_7ree_var = $_G['cache']['plugin']['jingcai_7ree'];
	$cost_7ree = $jingcai_7ree_var['cost_7ree'];
	$team= intval($_GET['team']);

	if(!$jingcai_7ree_var['agreement_7ree']) showmessage('jingcai_7ree:php_lang_agree_7ree');
	$mid_7ree = intval($_GET['mid_7ree']);
	$optype_7ree = intval($_GET['optype_7ree']);
	if(!$_G['uid']) showmessage('not_loggedin', NULL, array(), array('login' => 1));
	
    $race_7ree = DB::fetch_first("SELECT * FROM ".DB::table('jingcai_main_7ree')." WHERE main_id_7ree = '{$mid_7ree}'");
    
    $saicheng_7ree = DB::fetch_first("SELECT * FROM ".DB::table('jingcai_saicheng_7ree')." WHERE scid_7ree = '{$race_7ree[scid_7ree]}'");
    
	if($team==1){
		$win_7ree='a';
		$teamname_7ree=$race_7ree['aname_7ree'];
		$teamodds_7ree=$race_7ree['aodds_7ree'];
	}elseif($team==2){
		$win_7ree='b';
		$teamname_7ree=$race_7ree['bname_7ree'];
		$teamodds_7ree=$race_7ree['bodds_7ree'];
	}

    /*调试代码*/
    //if(!$race_7ree) showmessage("Bad MainID #7ree_error.");
    $race_7ree['open_7ree'] = $race_7ree['time_7ree'] > $_G['timestamp'] ? 1 : 0;
    $race_7ree['time_7ree'] = gmdate("Y-m-d H:i", $race_7ree['time_7ree'] + $_G['setting']['timeoffset'] * 3600);
	$navtitle = $jingcai_7ree_var['navtitle_7ree'];
	$navtitle = $race_7ree['racename_7ree'].' | '.$navtitle ;
	$extname_7ree = "extcredits".$jingcai_7ree_var['extcredit_7ree'];
	$exttitle_7ree = $_G['setting']['extcredits'][$jingcai_7ree_var['extcredit_7ree']][title];
	//竞猜是否过期的检测
    /*调试代码*/
    //if(!$race_7ree['open_7ree']) showmessage('jingcai_7ree:php_lang_msg_guoqi_7ree');
	if(!$jingcai_7ree_var['duplicate_7ree']){
		if(DB::result_first("SELECT mywin_7ree FROM ".DB::table('jingcai_log_7ree')." WHERE main_id_7ree = '$main_id_7ree' AND type_7ree = '$type_7ree' AND uid_7ree='{$_G[uid]}'")) showmessage('jingcai_7ree:php_lang_chenggongjingcai_7ree',"plugin.php?id=jingcai_7ree:jingcai_7ree");
	}
	
	//动态赔率计算
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
	
	
	
	
	
	include template('jingcai_7ree:jingcai_card_7ree');
?>