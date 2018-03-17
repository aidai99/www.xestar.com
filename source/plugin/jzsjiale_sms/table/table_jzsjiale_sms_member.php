<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_jzsjiale_sms_member extends discuz_table
{
	public function __construct() {
		$this->_table = 'common_member_profile';
		$this->_pk    = 'uid';
		parent::__construct();
	}

	
	public function fetch_by_mobile($phone) {
	    $user = array();
	    if($phone) {
	        $user = DB::fetch_first('SELECT * FROM %t WHERE mobile=%s', array($this->_table, $phone));
	    }
	    return $user;
	}
   
	public function fetch_by_uid($uid) {
	    $user = array();
	    if($uid) {
	        $user = DB::fetch_first('SELECT * FROM %t WHERE uid=%d', array($this->_table, $uid));
	    }
	    return $user;
	}
	
	public function fetch_member_by_uid($uid) {
	    $user = array();
	    if($uid) {
	        $user = DB::fetch_first('SELECT * FROM '.DB::table('common_member').' WHERE uid='.$uid);
	    }
	    return $user;
	}
	
	public function fetch_all_with_username_and_mobile($keyword="",$start = 0, $limit = 0, $sort = '') {
	    $dzuserlist = array();
	    $wheresql = " 1=1 ";
	    if(!empty($keyword)) {
	        $wheresql .=  " and sf.mobile like '%".$keyword."%' or s.username like '%".$keyword."%' ";  
	    }
	    $dzuserlist = DB::fetch_all("SELECT sf.uid as uid,sf.mobile as phone,s.username as username,s.regdate as dateline
				FROM ".DB::table('common_member_profile')." sf
				RIGHT JOIN ".DB::table('common_member')." s USING(uid)
	            WHERE ".$wheresql.($sort ? ' ORDER BY sf.mobile desc,s.uid '. $sort : '').DB::limit($start, $limit));
	   
	    return $dzuserlist;
	}

	public function count_all_with_username_and_mobile($keyword="") {

	    $wheresql = " 1=1 ";
	    if(!empty($keyword)) {
	        $wheresql .=  " and sf.mobile like '%".$keyword."%' or s.username like '%".$keyword."%' ";  
	    }
	     $count = (int) DB::result_first("SELECT count(*)
				FROM ".DB::table('common_member_profile')." sf
				RIGHT JOIN ".DB::table('common_member')." s USING(uid)
	            WHERE ".$wheresql);
	
	    return $count;
	}
	
	
	public function fetch_all_with_username_and_mobile_chachong($start = 0, $limit = 0, $sort = '') {
	    $dzuserlist = array();
	 
	    $dzuserchongfulist = DB::fetch_all("SELECT mobile FROM ".DB::table('common_member_profile')." WHERE mobile <> '' GROUP BY mobile having count(1)>1");
	
	    $mobiles = array();
    	foreach ($dzuserchongfulist as $dzuserdata){
    	    if(!empty($dzuserdata['mobile'])){
    	        $mobiles[]=$dzuserdata['mobile'];
    	    }
    	    
    	}
    	
    	if (!empty($mobiles)){
    	    $dzuserlist = DB::fetch_all("SELECT sf.uid as uid,sf.mobile as phone,s.username as username,s.regdate as dateline
				FROM ".DB::table('common_member_profile')." sf
				RIGHT JOIN ".DB::table('common_member')." s USING(uid)
	            WHERE sf.mobile in (".dimplode($mobiles).") ".($sort ? ' ORDER BY sf.mobile desc,s.uid '. $sort : '').DB::limit($start, $limit));
    	     
    	}
    	
	    return $dzuserlist;
	}
	
	public function count_all_with_username_and_mobile_chachong() {
	
	    $count = 0;
	    $dzuserchongfulist = DB::fetch_all("SELECT mobile FROM ".DB::table('common_member_profile')." WHERE mobile <> '' GROUP BY mobile having count(1)>1");
	
	    $mobiles = array();
    	foreach ($dzuserchongfulist as $dzuserdata){
    	    if(!empty($dzuserdata['mobile'])){
    	        $mobiles[]=$dzuserdata['mobile'];
    	    }
    	}
    	
    	if (!empty($mobiles)){
    	    $count = (int) DB::result_first("SELECT count(*)
    				FROM ".DB::table('common_member_profile')." sf
    				RIGHT JOIN ".DB::table('common_member')." s USING(uid)
    	            WHERE sf.mobile in (".dimplode($mobiles).") ");
    	}
	    return $count;
	}
}

?>