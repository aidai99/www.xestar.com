<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$sql = <<<EOF
DROP TABLE IF EXISTS  cdb_cack_app_sign_xinqing;
DROP TABLE IF EXISTS  cdb_cack_app_sign_log;
EOF;
runquery($sql);

$finish = TRUE;
?>