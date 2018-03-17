<?php

if (!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
    exit('Access Denied');
}
include 'source/plugin/cack_app_sign/lang/'.currentlang().'.php';
global $pluginid;

if($_GET['mod'] == 'del') {
    DB::delete('cack_app_sign_log', array('id' => intval($_GET['id'])));
    cpmsg($signcplang[8], 'action=plugins&operation=config&do='.$pluginid.'&identifier=cack_app_sign&pmod=admincp_signlog', 'succeed');
}

if (!submitcheck('bqsubmit')) {
    $signlog = DB::fetch_all("SELECT * FROM %t where signtime>".strtotime(date('Ymd',time()))." ORDER BY signtime", array("cack_app_sign_log"));
    showformheader('plugins&operation=config&do=' . $pluginid . '&identifier=cack_app_sign&pmod=admincp_signlog', 'bqsubmit');
    showtableheader($signcplang[2]);
    showsubtitle(array($signcplang[3] ,$signcplang[15] , $signcplang[4], $signcplang[5] , $signcplang[6] , "", $signcplang[7], ''), 'header', array('', '', ''));
    foreach ($signlog as $value) {
		$jrlogkey = $jrlogkey+1;
        showtablerow('class="data"', array('class="td25"', 'width="60"', 'width="60"', 'width="80"', 'width="200"', 'width="200"', 'width="80"', 'width="80"'), array(
            "$jrlogkey",
			date('H:i:s',$value[signtime]),
			"$value[username]",
            $value[jifen].$_G[setting][extcredits][$value[extcredits]][title],
			"$value[content]",
			"",
            "<a href='" . ADMINSCRIPT . "?action=plugins&operation=config&do=$pluginid&identifier=cack_app_sign&pmod=admincp_signlog&mod=del&id=$value[id]'>".$signcplang[7]."</a>",
            ""
        ));
    }
	
    showtablefooter();
    showformfooter();
	if(!$signlog){
		echo $signcplang[1];
	}
    print <<<EOF
EOF;
}