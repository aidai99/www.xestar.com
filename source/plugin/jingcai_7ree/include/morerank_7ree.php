<?php
/*
	ID: jingcai_7ree
	[www.7ree.com] (C)2007-2017 7ree.com.
	This is NOT a freeware, use is subject to license terms
	Update: 2017/10/25 20:02
	Agreement: http://addon.discuz.com/?@7.developer.doc/agreement_7ree_html
	More Plugins: http://addon.discuz.com/?@7ree
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

	$_GET['how_7ree'] = intval($_GET['how_7ree']);
	$_GET['how_7ree'] = in_array($_GET['how_7ree'],array('1','2','3','4','5')) ? $_GET['how_7ree'] : 1;
	$_GET['cycle_7ree'] = intval($_GET['cycle_7ree']);
	$_GET['cycle_7ree'] = in_array($_GET['cycle_7ree'],array('1','2','3','4','5','6')) ? $_GET['cycle_7ree'] : 1;
	$type_7ree = dhtmlspecialchars(trim($_GET['type_7ree']));
	$type_where_7ree = "WHERE type_7ree ='".$type_7ree."'";

    if($_G['uid']){
		$query = DB::query("SELECT touid_7ree FROM ".DB::table('jingcai_guanzhu_7ree')." WHERE uid_7ree = '{$_G[uid]}'");
			while($result = DB::fetch($query)) {
			$myguanzhulist_7ree[]=$result['touid_7ree'];
		}
    }
    
 //ɸѡ������ʱ������
 //���������������


 	if($_GET['how_7ree']==1){//��ӯ�� 
			$var_how_7ree = "yingli_7ree"; 
	}elseif($_GET['how_7ree']==2){//��ӯ�� 
			$var_how_7ree = "jingli_7ree"; 
	}elseif($_GET['how_7ree']==3){//��Ӯ����
			$var_how_7ree = "caidui_7ree";
 	}elseif($_GET['how_7ree']==4){//������
			$var_how_7ree = "mingzhong_7ree";
	} 
	if($_GET['cycle_7ree']==1){ //������
		$var_time_7ree = "a_";
 	}elseif($_GET['cycle_7ree']==2){ //����
		$var_time_7ree = "y_";
	}elseif($_GET['cycle_7ree']==3){ //����
		$var_time_7ree = "m_";
	}elseif($_GET['cycle_7ree']==4){//����
		$var_time_7ree = "w_";
	}elseif($_GET['cycle_7ree']==5){ //����
		$var_time_7ree = "m2_";
	}elseif($_GET['cycle_7ree']==6){//����
		$var_time_7ree = "w2_";
	} 
	
	if($_GET['how_7ree']==5){//�ص��Ƽ���Ӯ���а�
		$rank_orderby_7ree = " ORDER BY zdly_7ree DESC ";
	}else{
		$rank_orderby_7ree = " ORDER BY ".$var_time_7ree.$var_how_7ree." DESC ";
	}



//�ϰ񳡴����Ҫ��
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


 	if($_GET['cycle_7ree']==1){ //������
 		 	$where_rankrq_7ree = $rankrq_7ree ? " AND a_changci_7ree>=".$rankrq_7ree : "";
 	}elseif($_GET['cycle_7ree']==2){ //����
 		 	$where_rankrq_7ree = $rankrq_7ree ? " AND y_changci_7ree>=".$rankrq_7ree : "";
	}elseif($_GET['cycle_7ree']==3){ //����
 		 	$where_rankrq_7ree = $rankrq_7ree ? " AND m_changci_7ree>=".$rankrq_7ree : "";
	}elseif($_GET['cycle_7ree']==4){//����
 		 	$where_rankrq_7ree = $rankrq_7ree ? " AND w_changci_7ree>=".$rankrq_7ree : "";
	}elseif($_GET['cycle_7ree']==5){//����
 		 	$where_rankrq_7ree = $rankrq_7ree ? " AND m2_changci_7ree>=".$rankrq_7ree : "";
	}elseif($_GET['cycle_7ree']==6){//����
 		 	$where_rankrq_7ree = $rankrq_7ree ? " AND w2_changci_7ree>=".$rankrq_7ree : "";
	} 
	
	if($_GET['how_7ree']==5){
			//$where_rankrq_7ree = $where_rankrq_7ree." AND zdly_7ree >0 ";
			$where_rankrq_7ree = " AND zdly_7ree >0 ";
	}

	$query = DB::query("SELECT * FROM ".DB::table('jingcai_member_7ree')." {$type_where_7ree} {$where_rankrq_7ree} GROUP BY uid_7ree {$rank_orderby_7ree} LIMIT 100");
	while($result = DB::fetch($query)) {
	

 		//�Ƿ��ѹ�ע�ж�
		$result['guanzhu_7ree'] = in_array($result['uid_7ree'],$myguanzhulist_7ree) ? 1 : 0;

			
	 	if($_GET['how_7ree']==1){//��ӯ�� 
					$result['log_num_7ree'] = $result[$var_time_7ree.'caidui_7ree'];
					$result['sum_reward_7ree'] = $result[$var_time_7ree.'yingli_7ree'];
		}elseif($_GET['how_7ree']==2){//��ӯ�� 
					$result['log_num_7ree'] = $result[$var_time_7ree.'changci_7ree'];
					$result['sum_reward_7ree'] = $result[$var_time_7ree.'jingli_7ree'];
		}elseif($_GET['how_7ree']==3){//��Ӯ����
					$result['log_num_7ree'] = $result[$var_time_7ree.'caidui_7ree'];
					$result['sum_reward_7ree'] = $result[$var_time_7ree.'yingli_7ree'];
	 	}elseif($_GET['how_7ree']==4){//������
					$result['log_num_7ree'] = $result[$var_time_7ree.'caidui_7ree'];
					$result['hitrate_7ree'] = $result[$var_time_7ree.'mingzhong_7ree'];
	 	}elseif($_GET['how_7ree']==5){//�ص���Ӯ
				$result['log_num_7ree'] = $result[$var_time_7ree.'caidui_7ree'];
				$result['zdly_7ree'] = $result['zdly_7ree'];
		} 	
		$toplist_7ree[] = $result;
		
	}

?>