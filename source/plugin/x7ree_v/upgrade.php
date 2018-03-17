<?php

/*
	(C)2006-2017 www.7ree.com
	This is NOT a freeware, use is subject to license terms
	Update: 2017/8/13 17:39
	Agreement: http://addon.discuz.com/?@7.developer.doc/agreement_7ree_html
	More Plugins: http://addon.discuz.com/?@7ree
*/



if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}


function testfield_7ree($dbfield_7ree, $dbtable_7ree) {
	$field_7ree=array();
	$query=DB::query('SHOW COLUMNS FROM '.DB::table($dbtable_7ree));
	while ($row = mysql_fetch_array($query,MYSQL_ASSOC)) {
		$field_7ree[]=$row['Field'];
	}
	return in_array($dbfield_7ree,$field_7ree) ? TRUE:FALSE;
}



if(!testfield_7ree('view_7ree','x7ree_v_main')) {
	$sql="ALTER TABLE  `pre_x7ree_v_main` ADD  `view_7ree` MEDIUMINT( 8 ) NOT NULL ,
ADD  `zan_7ree` MEDIUMINT( 8 ) NOT NULL ,
ADD  `fav_7ree` MEDIUMINT( 8 ) NOT NULL";
	runquery($sql);
}

if(!testfield_7ree('discuss_7ree','x7ree_v_main')) {
	$sql="ALTER TABLE  `pre_x7ree_v_main` ADD  `discuss_7ree` MEDIUMINT( 8 ) NOT NULL ,
ADD  `cost_7ree` MEDIUMINT( 8 ) NOT NULL";
	runquery($sql);
}



$sql = <<<EOF

CREATE TABLE IF NOT EXISTS `pre_x7ree_v_discuss` (
  `id_7ree` mediumint(8) NOT NULL auto_increment,
  `uid_7ree` mediumint(8) NOT NULL,
  `user_7ree` varchar(50) NOT NULL,
  `vid_7ree` mediumint(8) NOT NULL,
  `time_7ree` int(10) NOT NULL,
  `zan_7ree` mediumint(8) NOT NULL,
  `message_7ree` text NOT NULL,
  PRIMARY KEY  (`id_7ree`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `pre_x7ree_v_buylog` (
  `id_7ree` mediumint(8) NOT NULL auto_increment,
  `uid_7ree` mediumint(8) NOT NULL,
  `user_7ree` varchar(50) NOT NULL,
  `vid_7ree` mediumint(8) NOT NULL,
  `time_7ree` int(10) NOT NULL,
  `cost_7ree` mediumint(8) NOT NULL,
  PRIMARY KEY  (`id_7ree`)
) ENGINE=MyISAM;

EOF;

runquery($sql);

$finish = TRUE;




?>