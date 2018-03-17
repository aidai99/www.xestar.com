<?php

defined('IN_DISCUZ') && defined('IN_ADMINCP') or exit('Powered by Hymanwu.Com');
@unlink(DISCUZ_ROOT.'data/sysdata/cache_plugin_hwh_colortype.php');

$sql = <<<EOF

DROP TABLE IF EXISTS `pre_hwh_colortype` ;

EOF;

runquery($sql);
$finish = TRUE;