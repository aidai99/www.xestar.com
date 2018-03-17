<?php
/*
 *折翼天使资源社区：www.zheyitianshi.com
 *更多商业插件/模版折翼天使资源社区 就在折翼天使资源社区
 *本资源来源于网络收集,仅供个人学习交流，请勿用于商业用途，并于下载24小时后删除!
 *如果侵犯了您的权益,请及时告知我们,我们即刻删除!
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_keke_chongzhi_credit extends discuz_table
{
	public function __construct() {
		$this->_table = 'keke_chongzhi_credit';
		$this->_pk = 'creditid';
		parent::__construct();
	}
	
	public function fetchall_credit() {
		$data=DB::fetch_all("SELECT * FROM %t", array($this->_table));
		foreach($data as $val){
			$ret[$val['creditid']]=$val;
		}
		return $ret;
	}
	
	
}

?>