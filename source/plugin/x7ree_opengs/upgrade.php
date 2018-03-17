<?php

/*
	[www.7ree.com] (C)2007-2017 7ree.com.
	This is NOT a freeware, use is subject to license terms
	Update: 2017/9/16 18:36
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


if (!testfield_7ree('fenlei_7ree','x7ree_opengs_main')) {
	$sql="ALTER TABLE  `pre_x7ree_opengs_main` ADD  `fenlei_7ree` VARCHAR( 255 ) NOT NULL AFTER  `name_7ree`";
	runquery($sql);
}





$finish = TRUE;




?>