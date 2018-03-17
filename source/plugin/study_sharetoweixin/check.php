<?php
/*
 *  201311162014I1LiEIZ5
 * 
 * From www.zheyitianshi.com
 */
if(!defined('IN_ADMINCP')) {
	exit('Access Denied');
}
$addonid = $pluginarray['plugin']['identifier'].'.plugin';
$array = cloudaddons_getmd5($addonid);
if(cloudaddons_open('&mod=app&ac=validator&addonid='.$addonid.($array !== false ? '&rid='.$array['RevisionID'].'&sn='.$array['SN'].'&rd='.$array['RevisionDateline'] : '')) === '1') {
	
}
//From:www.zheyitianshi.com
?>