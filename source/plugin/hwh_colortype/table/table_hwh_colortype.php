<?php

defined('IN_DISCUZ') or exit('Powered by Hymanwu.Com');

class table_hwh_colortype extends discuz_table{

	public function __construct(){
		$this->table = 'hwh_colortype';
		parent::__construct();
	}

	public function fetch_first($fid){
		return DB::fetch_first('SELECT * FROM %t WHERE fid=%d',array($this->table,$fid));
	}

	public function fetch_all(){
		return DB::fetch_all('SELECT * FROM %t',array($this->table));
	}

	public function delete_all(){
		return DB::query('TRUNCATE TABLE %t',array($this->table));
	}

}

?>