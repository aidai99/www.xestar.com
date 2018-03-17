<?php

/*
	(C)2006-2017 www.7ree.com
	This is NOT a freeware, use is subject to license terms
	Update: 2017/12/6 13:38
	Agreement: http://addon.discuz.com/?@7.developer.doc/agreement_7ree_html
	More Plugins: http://addon.discuz.com/?@7ree
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}



class block_jingcai7ree extends discuz_block {
	var $setting = array();
	
	function block_jingcai7ree() {
		global $_G;
		@include 'lang_7ree.'.currentlang().'.php';
 		$vars_7ree = $_G['cache']['plugin']['jingcai_7ree'];
		$fenlei_7ree =  str_replace("\n","|||",$vars_7ree['fenlei_7ree']);
		$fenlei_array =  explode('|||', $fenlei_7ree);
		foreach($fenlei_array as $fenlei_value){
				$fenlei_array2[] = explode(',',trim($fenlei_value));
		}
		$fenlei1_7ree[]=array('[all]',$lang_7ree['buxian']);
		$fenlei2_7ree[]=array('[all]',$lang_7ree['buxian']);
		foreach($fenlei_array2 as $fenlei_value2){
			foreach($fenlei_value2 as $key=>$fenlei_value3){
				if($key==0){
					$fenlei1_7ree[]=array($fenlei_value3,$fenlei_value3);
				}else{
					$fenlei2_7ree[]=array($fenlei_value3,$fenlei_value3);
				}
			}
		
		}

		$this->setting = array(
			'ing_7ree' => array(
				'title' => $lang_7ree[zhuangtai],
				'type' => 'select',
				'value' => array(
							array(1,$lang_7ree['quanbu']),
							array(2,$lang_7ree['jinxingzhong']),
							array(3,$lang_7ree['yijieshu'])
				),
			),
			'fenlei1_7ree' => array(
				'title' => $lang_7ree['fenlei1'],
				'type' => 'mselect',
				'value' => $fenlei1_7ree,
			),
			'fenlei2_7ree' => array(
				'title' => $lang_7ree['fenlei2'],
				'type' => 'mselect',
				'value' => $fenlei2_7ree,
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
		return array('jigncai7ree', "$lang_7ree[qiruijingcai]");
	}

	function fields() {
		@include 'lang_7ree.'.currentlang().'.php';
		return array(
					'id_7ree' => array('name' => $lang_7ree['xuhao'], 'formtype' => 'text', 'datatype' => 'string'),
					'title_7ree' => array('name' => $lang_7ree['mingcheng'], 'formtype' => 'text', 'datatype' => 'string'),
					'url_7ree' => array('name' => $lang_7ree['lianjie'], 'formtype' => 'text', 'datatype' => 'string'),
					'aname_7ree' => array('name' => $lang_7ree['ateam'], 'formtype' => 'text', 'datatype' => 'string'),
					'bname_7ree' => array('name' => $lang_7ree['bteam'], 'formtype' => 'text', 'datatype' => 'string'),
					'alogo_7ree' => array('name' => $lang_7ree['ateam'].'logo', 'formtype' => 'text', 'datatype' => 'string'),
					'blogo_7ree' => array('name' => $lang_7ree['bteam'].'logo', 'formtype' => 'text', 'datatype' => 'string'),
					'arate_7ree' => array('name' => $lang_7ree['ateam'].'rate', 'formtype' => 'text', 'datatype' => 'string'),
					'brate_7ree' => array('name' => $lang_7ree['bteam'].'rate', 'formtype' => 'text', 'datatype' => 'string'),
					'time_7ree' => array('name' => $lang_7ree['shijian'],  'formtype' => 'date', 'datatype' => 'date'),
					'jieguo_7ree' => array('name' => $lang_7ree['jieguo'], 'formtype' => 'title', 'datatype' => 'string'),
					'canyu_7ree' => array('name' => $lang_7ree['canyu'], 'formtype' => 'title', 'datatype' => 'string'),
					'fenlei1_7ree' => array('name' => $lang_7ree['fenlei1'], 'formtype' => 'text', 'datatype' => 'string'),
					'fenlei2_7ree' => array('name' => $lang_7ree['fenlei2'], 'formtype' => 'text', 'datatype' => 'string'),


			);
	}

	function getsetting() {
		global $_G;
		$settings = $this->setting;
		return $settings;
	}

	function getdata($style, $parameter) {
		global $_G;
        @include 'lang_7ree.'.currentlang().'.php';
        $parameter = $this->cookparameter($parameter);
        
        $startrow = 0;
        $items = isset($parameter['items']) ? intval($parameter['items']) : 10;

        if($parameter['ing_7ree']==1){
        	$where_ing = "WHERE time2_7ree > 0";
        }elseif($parameter['ing_7ree']==2){
        	$where_ing = "WHERE time2_7ree > $_G[timestamp]";
        }elseif($parameter['ing_7ree']==3){ 
        	$where_ing = "WHERE time2_7ree < $_G[timestamp]";
        }else{
        	$where_ing = "WHERE time2_7ree > 0";
        }

        $fenlei1_7ree = array();
        $fenlei1_where = '';
		if(!empty($parameter['fenlei1_7ree'])) {
			$fenlei1_7ree = $parameter['fenlei1_7ree'];
			$fenlei1_where = $fenlei1_7ree[0]=='[all]' ? '' : 'AND fenlei1_7ree IN ('.dimplode($fenlei1_7ree).')';
		}

        $fenlei2_7ree = array();
        $fenlei2_where = '';
		if(!empty($parameter['fenlei2_7ree'])) {
			$fenlei2_7ree = $parameter['fenlei2_7ree'];
			$fenlei2_where = $fenlei2_7ree[0]=='[all]' ? '' : 'AND fenlei2_7ree IN ('.dimplode($fenlei2_7ree).')';
		}

		$bannedids = !empty($parameter['bannedids']) ? explode(',', $parameter['bannedids']) : array();
		if($bannedids) {
			$where_ban = 'AND main_id_7ree NOT IN ('.dimplode($bannedids).')';
		}

		$list = array();

		$query = DB::query("SELECT * FROM ".DB::table('jingcai_saicheng_7ree')." $where_ing $where_ban $fenlei1_where $fenlei2_where ORDER BY time_7ree ASC LIMIT $startrow, $items");

		while($data = DB::fetch($query)) {

			if($data['win_7ree']=='a'){
					$jieguo_7ree = $data['aname_7ree'].$lang_7ree['huosheng'];
			}elseif($data['win_7ree']=='b'){
					$jieguo_7ree = $data['bname_7ree'].$lang_7ree['huosheng'];
			}elseif($data['win_7ree']=='c'){
					$jieguo_7ree = $lang_7ree['pingju'];
			}else{
					$jieguo_7ree = $lang_7ree['weijiexiao'];
			}

			if($data['time_7ree']>$_G['timestamp']){
			        $canyu_7ree =  $lang_7ree['canyu'];
			}elseif($data['time_7ree']<$_G['timestamp']){
			        $canyu_7ree =  $lang_7ree['chakan'];
			}else{
			        $canyu_7ree =  $lang_7ree['chakan'];
			}

			$list[] = array(
				'id' => $data['main_id_7ree'],
				'title' => cutstr(str_replace('\\\'', '&#39;', addslashes($data['racename_7ree'])), 40, '').": ".$data['aname_7ree']." vs ".$data['bname_7ree'],
				'url' => 'plugin.php?id=jingcai_7ree:jingcai_7ree&sp_7ree=more&main_id_7ree='.$data['main_id_7ree'],
				'fields' => array(
							'id_7ree' => $data['main_id_7ree'],
							'url_7ree' => 'plugin.php?id=jingcai_7ree:jingcai_7ree&sp_7ree=more&main_id_7ree='.$data['main_id_7ree'],
							'title_7ree' => cutstr(str_replace('\\\'', '&#39;', addslashes($data['racename_7ree'])), 40, ''),
							//'time_7ree' => dgmdate($data['time_7ree'],'u'),
							'time_7ree' => $data['time_7ree'],
							'aname_7ree' => $data['aname_7ree'],
							'bname_7ree' => $data['bname_7ree'],
							'alogo_7ree' => $data['alogo_7ree'],
							'blogo_7ree' => $data['blogo_7ree'],
							'arate_7ree' => $data['arate_7ree'],
							'brate_7ree' => $data['brate_7ree'],
                            'jieguo_7ree' => $jieguo_7ree,
                            'canyu_7ree' => $canyu_7ree,
				)
			);
		}

		return array('html' => '', 'data' => $list);
	}



}

?>