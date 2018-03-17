<?php

defined('IN_DISCUZ') && defined('IN_ADMINCP') or exit('Powered by Hymanwu.Com');

$sql = <<<EOF

CREATE TABLE IF NOT EXISTS `pre_hwh_colortype` (
  `fid` mediumint(8) NOT NULL,
  `code` varchar(255) NOT NULL
) ENGINE=MyISAM;

EOF;

runquery($sql);
$finish = TRUE;

?>