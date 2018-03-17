<?php
/*
	ID: jingcai_7ree
	[www.7ree.com] (C)2007-2017 7ree.com.
	This is NOT a freeware, use is subject to license terms
	Update: 2017/8/3 16:24
	Agreement: http://addon.discuz.com/?@7.developer.doc/agreement_7ree_html
	More Plugins: http://addon.discuz.com/?@7ree
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

    $uid_7ree=intval($_GET['uid_7ree']);
    if(!$uid_7ree) showmessage("ERROR## Miss uid @ 7ree");
    
    if($_G['uid'] && $_G['adminid']==1){//更新数据
    	memberinfo_count_7ree($uid_7ree);
    }

    if($fenlei2_7ree<>''){
    	$fenlei2_where_7ree =" AND m.fenlei2_7ree='{$fenlei2_7ree}'";
    }
	$memberrank_7ree = DB::fetch_first("SELECT * FROM ".DB::table('jingcai_member_7ree')." WHERE uid_7ree = '{$uid_7ree}' AND type_7ree='{$fenlei2_7ree}'");


	//本分类最低查看次数计算
		if($jingcai_7ree_var['viewfreenum_fenlei_7ree']){
				$viewfreenum_fenlei_array =  str_replace("\n","|||",$jingcai_7ree_var['viewfreenum_fenlei_7ree']);
				$viewfreenum_fenlei_array2 =  explode('|||', $viewfreenum_fenlei_array );
				foreach($viewfreenum_fenlei_array2 as $viewfreenum_fenlei_array2_value){
						$viewfreenum_fenlei_array2_value = explode('=',trim($viewfreenum_fenlei_array2_value));
						$viewfreenum_fenlei_7ree[$viewfreenum_fenlei_array2_value[0]] = $viewfreenum_fenlei_array2_value[1];
				}
		}



    $page = max(1, intval($_GET['page']));
	$startpage = ($page - 1) * 20;
    $querynum = DB::result_first("SELECT Count(*) FROM ".DB::table('jingcai_log_7ree')." l 
    								LEFT JOIN ".DB::table('jingcai_main_7ree')." m ON l.main_id_7ree = m.main_id_7ree 
    								WHERE racename_7ree<>'' AND l.uid_7ree = '{$uid_7ree}' {$fenlei2_where_7ree}");
	$query = DB::query("SELECT l.*, l.type_7ree AS ltype_7ree, m.* FROM ".DB::table('jingcai_log_7ree')." l 
									LEFT JOIN ".DB::table('jingcai_main_7ree')." m ON l.main_id_7ree = m.main_id_7ree  
									WHERE racename_7ree<>'' AND l.uid_7ree = '{$uid_7ree}' $fenlei2_where_7ree
									ORDER BY l.log_id_7ree DESC LIMIT {$startpage}, 20");
	while($result = DB::fetch($query)) {
		$result['log_time2_7ree']=gmdate("Y-m-d H:i", $result['log_time_7ree'] + $_G['setting']['timeoffset'] * 3600);
		$result['log_time_7ree']=gmdate("m-d", $result['log_time_7ree'] + $_G['setting']['timeoffset'] * 3600);
		
		for($i=0;$i<COUNT($titlebg_array);$i++){
			if($titlebg_array2[$i][0] == $result['racename_7ree']) $result['titlebg_7ree'] = $titlebg_array2[$i][1];
		}
		//已支付查询
		if($_G['uid']){
			$result['viewpay_7ree']=DB::result_first("SELECT id_7ree FROM ".DB::table('jingcai_payment_7ree')." WHERE touid_7ree='{$result[uid_7ree]}' AND uid_7ree = '{$_G[uid]}' AND lid_7ree='{$result[log_id_7ree]}'");
			
			if(!$result['viewpay_7ree']){//如无支付记录，需判断是否需要付费
				 //根据排行确认被查看会员是否需要付费
				$thisrank_7ree = array();
			    $thisrank_7ree = DB::fetch_first("SELECT * FROM ".DB::table('jingcai_member_7ree')." WHERE uid_7ree = '{$uid_7ree}' AND type_7ree='$result[fenlei2_7ree]'");
					$vfnum_fenlei_7ree = $viewfreenum_fenlei_7ree[$result[fenlei2_7ree]] ? $viewfreenum_fenlei_7ree[$result[fenlei2_7ree]] : $jingcai_7ree_var['viewfreenum_7ree'];
			        if($thisrank_7ree['a_mzlrank_7ree']<=$vfnum_fenlei_7ree && $thisrank_7ree['a_changci_7ree']>=$jingcai_7ree_var['rankrq_7ree']){
						$result['needpay_7ree'] = 1;
						$result['free_7ree'] = 0;
			    	}else{
						$result['needpay_7ree'] = 0;
						$result['free_7ree'] = 1;
			    	}

			}

		}

		$loglist_7ree[] = $result;
	}
	    $multipage = multi($querynum, 20, $page, "plugin.php?id=jingcai_7ree:jingcai_7ree&ac_7ree=10&uid_7ree=$uid_7ree&fenlei2_7ree=$fenlei2_7ree" );
		$mymultipage_7ree = $multipage."      [<a href='http://www.7ree.com' target='_blank'><font color=gray>Powered by 7ree.com</font></a>]";

?>