<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_reward_log extends discuz_table
{
	public function __construct() {
        
		$this->_table = 'reward_log';
		$this->_pk    = 'id';
        $this->_pre_cache_key = 'reward_log_';
		parent::__construct();
	}
    
	public function search_condition($conditions) {
		foreach ($conditions as $field => $val){
			$val[1] = !$val[1] ? '=' : $val[1] ;
			$wherearr[] = DB::field($field, $val[0],$val[1]);
		}
		$wheretxt = !empty($wherearr) && is_array($wherearr) ? ' WHERE '.implode(' AND ', $wherearr) : '';
		return $wheretxt;	
	}
    
	public function fetch_by_uid_tid($uid, $tid){
		if($uid && $tid){
	        return DB::fetch_first("SELECT * FROM %t WHERE uid=%d AND tid=%d", array($this->_table, $uid, $tid));
		}
	}
	
	public function count_by_search($conditions) {
		return DB::result_first("SELECT COUNT(*) FROM %t %i", array($this->_table, $this->search_condition($conditions)));
	}

	public function fetch_all_by_search($conditions, $start = 0, $limit = 25, $order = 'dateline', $sort = 'DESC') {
		$_order = $order ? 'ORDER BY '.DB::order($order, $sort) : '';
		return DB::fetch_all("SELECT * FROM %t %i ".$_order.DB::limit($start, $limit),array($this->_table,$this->search_condition($conditions)));
	}

}
?>