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

class table_keke_chongzhi_orderlog extends discuz_table {

	public function __construct() {
		$this->_table = 'keke_chongzhi_orderlog';
		$this->_pk    = 'orderid';

		parent::__construct();
	}
	
	public function count_by_all($uid=0,$state=0) {
		$where=$uid ? ' AND uid=%d' : '';
		$where.=$state ? ' AND state=1' : '';
		return DB::result_first("SELECT count(1) FROM %t WHERE orderid>0 ".$where, array($this->_table,$uid));
	}
	
	public function fetch_all_by_all($uid=0,$state=0,$startlimit,$ppp) {
		$where=$uid ? ' AND uid='.intval($uid) : '';
		$where.=$state ? ' AND state=1' : '';
		return DB::fetch_all("SELECT * FROM %t WHERE orderid>0 ".$where." order by time desc LIMIT %d,%d", array($this->_table,$startlimit,$ppp));
	}
	
	public function sum_by_uid($uid) {
		return DB::result_first("SELECT sum(money) FROM %t WHERE uid=%d AND state=1", array($this->_table,$uid));
	}
	
	public function del_oder() {
		return DB::query("delete FROM %t where state=0",array($this->_table));
	}
}

?>