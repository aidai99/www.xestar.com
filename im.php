<?php
/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: group.php 31307 2012-08-10 02:10:56Z zhengqingpeng $
 */

define('APPTYPEID', 3);
define('CURSCRIPT', 'im');
define('IN_MOBILE_API', 1);

require './source/class/class_core.php';

$discuz = C::app();


$discuz->init();
loadcache('plugin');


require './source/plugin/cis_weixin/function.php';
require './template/cis_app/touch/core/immwa.php';

$IMCORE=true;

include immwa_mod($_GET['mod']);

?>