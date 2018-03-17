<?php

/*
	(C)2006-2017 www.7ree.com
	This is NOT a freeware, use is subject to license terms
	Update: 2017/5/19 22:46
	Agreement: http://addon.discuz.com/?@7.developer.doc/agreement_7ree_html
	More Plugins: http://addon.discuz.com/?@7ree
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}


		

class block_rank7ree extends discuz_block {
	var $setting = array();
	
	function block_rank7ree() {
		global $_G;
		@include 'lang_7ree.'.currentlang().'.php';
 		$vars_7ree = $_G['cache']['plugin']['jingcai_7ree'];

		if($vars_7ree['sortdata_7ree']){//判断是否按照赛事分类读取详细数据
				//读取分类
				$class_7ree=array();
				$fenlei_7ree =  str_replace("\n","|||",$vars_7ree['fenlei_7ree']);
				$fenlei_array =  explode('|||', $fenlei_7ree);

				foreach($fenlei_array as $key=>$fenlei_value){
						$fenlei_array2[$key] = explode(',',trim($fenlei_value));
						array_shift($fenlei_array2[$key]);
				}

				$class_7ree=$this->merge_array($fenlei_array2);

				$fenlei_diy[]=array(0,$lang_7ree['buxian']);
				foreach($class_7ree as $key=>$class_value){
					$fenlei_diy[]=array($key+1,$class_value);
				}
		}

		$this->setting = array(
			'fenlei_7ree' => array(
				'title' => $lang_7ree['fenlei2'],
				'type' => 'select',
				'value' => $fenlei_diy,
			),
			'type_7ree' => array(
				'title' => $lang_7ree['paihangleixing'],
				'type' => 'select',
				'value' => array(
							array(1,$lang_7ree['paihangbang1']),
							array(2,$lang_7ree['paihangbang2']),
							array(3,$lang_7ree['paihangbang3']),
							array(4,$lang_7ree['paihangbang4']),
							array(5,$lang_7ree['paihangbang5'])
						   ),
			),
			'cycle_7ree' => array(
				'title' => $lang_7ree['zhouqi'],
				'type' => 'select',
				'value' => array(
							array(1,$lang_7ree['zhouqi1']),
							array(2,$lang_7ree['zhouqi2']),
							array(3,$lang_7ree['zhouqi3']),
							array(4,$lang_7ree['zhouqi4'])
						   ),
			),

		);

	}
	
	function name() {
		@include 'lang_7ree.'.currentlang().'.php';
		$return_7ree = $lang_7ree['yingyongshuju'];
		return $return_7ree;
	}
	

	function blockclass() {
		@include 'lang_7ree.'.currentlang().'.php';
		return array('rank7ree', "$lang_7ree[paihangbang]");
	}

	function fields() {
		@include 'lang_7ree.'.currentlang().'.php';
		return array(
					'id_7ree' => array('name' => $lang_7ree['xuhao'], 'formtype' => 'text', 'datatype' => 'string'),
					'url_7ree' => array('name' => $lang_7ree['lianjie'], 'formtype' => 'text', 'datatype' => 'string'),
					'username_7ree' => array('name' => $lang_7ree['username'], 'formtype' => 'text', 'datatype' => 'string'),
					'uid_7ree' => array('name' =>'UID', 'formtype' => 'text', 'datatype' => 'string'),
					'rank_7ree' => array('name' => $lang_7ree['rank'], 'formtype' => 'text', 'datatype' => 'string'),
					'data_7ree' => array('name' => $lang_7ree['data'], 'formtype' => 'text', 'datatype' => 'string'),
			);
	}

	function getsetting() {
		global $_G;
		$settings = $this->setting;
		return $settings;
	}

	function getdata($style, $parameter) {
		global $_G;
		$vars_7ree = $_G['cache']['plugin']['jingcai_7ree'];
		$exttitle_7ree = $_G['setting']['extcredits'][$vars_7ree['extcredit_7ree']][title];
        @include 'lang_7ree.'.currentlang().'.php';
        $parameter = $this->cookparameter($parameter);
        
        $startrow	= 0;
        $items		= isset($parameter['items']) ? intval($parameter['items']) : 10;

        if($parameter['type_7ree']==1){//总盈利 
			$var_how_7ree = "yingli_7ree";     
        }elseif($parameter['type_7ree']==2){//净盈利 
			$var_how_7ree = "jingli_7ree";
        }elseif($parameter['type_7ree']==3){//猜赢场次 
			$var_how_7ree = "caidui_7ree";   
         }elseif($parameter['type_7ree']==4){//命中率 
			$var_how_7ree = "mingzhong_7ree"; 
        } 

		if($parameter['cycle_7ree']==1){ //全部时间
			$var_time_7ree = "a_";
	 	}elseif($parameter['cycle_7ree']==2){ //本年
			$var_time_7ree = "y_";
		}elseif($parameter['cycle_7ree']==3){ //本月
			$var_time_7ree = "m_";
		}elseif($parameter['cycle_7ree']==4){//本周
			$var_time_7ree = "w_";
		}  
    $rankorder_7ree = $var_time_7ree.$var_how_7ree;
    if($parameter['type_7ree']!=5){
		$rank_orderby_7ree = " ORDER BY ".$rankorder_7ree." DESC ";
	}else{
		$rank_orderby_7ree = " ORDER BY zdly_7ree DESC ";
	}
	
//上榜场次最低要求
/*
 	if($parameter['cycle_7ree']==1){ //本季
 		 	$where_rankrq_7ree = $vars_7ree['rankrq_7ree'] ? " WHERE a_changci_7ree>=".$vars_7ree['rankrq_7ree'] : "";
 	}elseif($parameter['cycle_7ree']==2){ //本年
 		 	$where_rankrq_7ree = $vars_7ree['rankrq_7ree'] ? " WHERE y_changci_7ree>=".$vars_7ree['rankrq_7ree'] : "";
	}elseif($parameter['cycle_7ree']==3){ //本月
 		 	$where_rankrq_7ree = $vars_7ree['rankrq_7ree'] ? " WHERE m_changci_7ree>=".$vars_7ree['rankrq_7ree'] : "";
	}elseif($parameter['cycle_7ree']==4){//本周
 		 	$where_rankrq_7ree = $vars_7ree['rankrq_7ree'] ? " WHERE w_changci_7ree>=".$vars_7ree['rankrq_7ree'] : "";
	}else{
			$where_rankrq_7ree = $vars_7ree['rankrq_7ree'] ? " WHERE y_changci_7ree>=".$vars_7ree['rankrq_7ree'] : "";
	}
*/
	
//数据分类筛选
    if($parameter['fenlei_7ree']<>0){
				if($vars_7ree['sortdata_7ree']){//判断是否按照赛事分类读取详细数据
						//读取分类
						$class_7ree=array();
						$fenlei_7ree =  str_replace("\n","|||",$vars_7ree['fenlei_7ree']);
						$fenlei_array =  explode('|||', $fenlei_7ree);

						foreach($fenlei_array as $key=>$fenlei_value){
								$fenlei_array2[$key] = explode(',',trim($fenlei_value));
								array_shift($fenlei_array2[$key]);
						}

						$class_7ree=$this->merge_array($fenlei_array2);

						$fenlei_diy[]=array(0,$lang_7ree['buxian']);
						foreach($class_7ree as $key=>$class_value){
							$fenlei_diy[]=array($key+1,$class_value);
						}
				}
				$where_fenlei_7ree = " AND type_7ree='".$fenlei_diy[$parameter[fenlei_7ree]][1]."' ";
        }else{
				$where_fenlei_7ree = " AND type_7ree='' ";
    }
	
	
//上榜场次最低要求
	$rankrq_7ree = $vars_7ree['rankrq_7ree'] ? $vars_7ree['rankrq_7ree'] : 1;
	if($vars_7ree['rankrq_fenlei_7ree']){
			$rankrq_fenlei_array =  str_replace("\n","|||",$vars_7ree['rankrq_fenlei_7ree']);
			$rankrq_fenlei_array2 =  explode('|||', $rankrq_fenlei_array );
			foreach($rankrq_fenlei_array2 as $rankrq_fenlei_array2_value){
					$rankrq_fenlei_7ree = explode('=',trim($rankrq_fenlei_array2_value));
					//只需判断当前用户组的具体限制数量
					if($rankrq_fenlei_7ree[0]==$type_7ree){
						$rankrq_7ree = $rankrq_fenlei_7ree[1];
						break;
					}
			}
	}


 	if($parameter['cycle_7ree']==1){ //本季度
 		 	$where_rankrq_7ree = $rankrq_7ree ? " WHERE a_changci_7ree>=".$rankrq_7ree : "";
 	}elseif($parameter['cycle_7ree']==2){ //本年
 		 	$where_rankrq_7ree = $rankrq_7ree ? " WHERE y_changci_7ree>=".$rankrq_7ree : "";
	}elseif($parameter['cycle_7ree']==3){ //本月
 		 	$where_rankrq_7ree = $rankrq_7ree ? " WHERE m_changci_7ree>=".$rankrq_7ree : "";
	}elseif($parameter['cycle_7ree']==4){//本周
 		 	$where_rankrq_7ree = $rankrq_7ree ? " WHERE w_changci_7ree>=".$rankrq_7ree : "";
	}else{//默认
 		 	$where_rankrq_7ree = $rankrq_7ree ? " WHERE y_changci_7ree>=".$rankrq_7ree : "";
	}
	



		$bannedids = !empty($parameter['bannedids']) ? explode(',', $parameter['bannedids']) : array();
		if($bannedids) {
			$where_ban = 'AND id_7ree NOT IN ('.dimplode($bannedids).')';
		}

		$list = array();
        $rank_7ree = 1;
		$query = DB::query("SELECT * FROM ".DB::table('jingcai_member_7ree')." $where_rankrq_7ree $where_ban $where_fenlei_7ree GROUP BY uid_7ree $rank_orderby_7ree LIMIT $startrow, $items");

		while($data = DB::fetch($query)) {

	        if($parameter['type_7ree']==1){//总盈利 
				$data_7ree = $data[$rankorder_7ree].$exttitle_7ree;   
	        }elseif($parameter['type_7ree']==2){//净盈利 
				$data_7ree = $data[$rankorder_7ree].$exttitle_7ree;
	        }elseif($parameter['type_7ree']==3){//猜赢场次 
				$data_7ree = $data[$rankorder_7ree].$lang_7ree['changci']; 
	         }elseif($parameter['type_7ree']==4){//命中率 
				$data_7ree = $data[$rankorder_7ree]."%"; 
	         }elseif($parameter['type_7ree']==5){//命中率 
				$data_7ree = $data['zdly_7ree'].$lang_7ree['chang']; 
	        }
			if($parameter['type_7ree']!=5 || $data['zdly_7ree']>0){
			$list[] = array(
					'id' => $data['id_7ree'],
					'title' => $data['username_7ree']." | ".$data_7ree,
					'url' => 'plugin.php?id=jingcai_7ree:jingcai_7ree&ac_7ree=10&uid_7ree='.$data['uid_7ree'],
					'fields' => array(
								'id_7ree' => $data['id_7ree'],
								'url_7ree' => 'plugin.php?id=jingcai_7ree:jingcai_7ree&ac_7ree=10&uid_7ree='.$data['uid_7ree'].'&fenlei2_7ree='.$fenlei_diy[$parameter[fenlei_7ree]][1],
								'username_7ree' => $data['username_7ree'],
								'uid_7ree' => $data['uid_7ree'],
								'rank_7ree' => $rank_7ree,
								'data_7ree' => $data_7ree,
	             
					)
				);
			$rank_7ree = $rank_7ree + 1;
			}
		}

		return array('html' => '', 'data' => $list);
	}

	function merge_array($array){
		return call_user_func_array('array_merge',$array);
	}


}

?>