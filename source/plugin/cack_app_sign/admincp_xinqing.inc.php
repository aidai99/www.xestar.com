<?php

if (!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
    exit('Access Denied');
}
include 'source/plugin/cack_app_sign/lang/'.currentlang().'.php';
global $pluginid;

if($_GET['mod'] == 'del') {
    DB::delete('cack_app_sign_xinqing', array('xid' => intval($_GET['xid'])));
    cpmsg($signcplang[8], 'action=plugins&operation=config&do='.$pluginid.'&identifier=cack_app_sign&pmod=admincp_xinqing', 'succeed');
}

if (!submitcheck('bqsubmit')) {
    $signxinqing = DB::fetch_all("SELECT * FROM %t ORDER BY displayorder", array("cack_app_sign_xinqing"));
    showformheader('plugins&operation=config&do=' . $pluginid . '&identifier=cack_app_sign&pmod=admincp_xinqing', 'bqsubmit');
    showtableheader($signcplang[9].$signcplang[14]);
    showsubtitle(array('display_order', 'xid' , $signcplang[10], $signcplang[11] , "", $signcplang[7], ''), 'header', array('', '', ''));
    foreach ($signxinqing as $value) {
        showtablerow('class="data"', array('class="td25"', 'width="20"', 'width="80"', 'width="200"', 'width="80"', 'width="80"'), array(
            "<input type=\"text\" class=\"td25\" name=\"displayorder[$value[xid]]\" value=\"$value[displayorder]\">",
            "$value[xid]",
			"<input type=\"text\" name=\"name[$value[xid]]\" value=\"$value[name]\" class=\"td80\">",
            "<input type=\"text\" size=\"80\" name=\"pic[$value[xid]]\" value=\"$value[pic]\" class=\"td150\"><img src=\"source/plugin/cack_app_sign/images/$value[pic]\" style=\"height: 30px;\">",
			"",
            "<a href='" . ADMINSCRIPT . "?action=plugins&operation=config&do=$pluginid&identifier=cack_app_sign&pmod=admincp_xinqing&mod=del&xid=$value[xid]'>".$signcplang[7]."</a>",
                 "<input type=\"hidden\" name=\"xid[$value[xid]]\" value=\"$value[xid]\">"
        ));
    }
        showtablerow('id="addnew"', array('width="100"', 'width="100"', 'width="200"', 'width="100"', 'width="100"', 'width="100"', 'width="100"'), array("", "<div><a href=\"###\" onclick=\"addnewrecord(this)\" class=\"addtr\">".$signcplang[12]."</a></div>", "", "", "", "")
        );
    showtablefooter();
    showsubmit('bqsubmit');
    showformfooter();
    print <<<EOF
	<script type="text/JavaScript">
	var rowtypedata = [
		[[1,'<input type="text"  class="td25" name="newdisplayorder[]" value="0" />', 'td25'],
		[1,'', 'td100'],
		[1,'<input type="text" size="80" class="td80"  name="newname[]" />', 'td100'],
		[1,'<input type="text" class="td150" name="newpic[]"/>', ''], 
		[1,'', 'td100'],
		[1, '<div><a href="javascript:;" class="deleterow" onClick="deleterow(this)">$signcplang[7]</a></div>', '']],
	];
	var newrows = document.getElementsByClassName("data").length;	
	function addnewrecord(v) {
		newrows++;
		addrow(v, 0);
		
	}
				
	</script>
				
	<style>
	.td150 {width:150px;}
	.td100 {width:100px;}
    .td80 {width:80px;}
	</style>
EOF;
} else {
    if (is_array(dhtmlspecialchars($_GET['name'])) && is_array(dhtmlspecialchars($_GET['pic']))) {
        foreach (dhtmlspecialchars($_GET['name']) as $key => $name) {
            if (empty($name)) {
                continue;
            }
            DB::update('cack_app_sign_xinqing', array('displayorder' => dhtmlspecialchars($_GET['displayorder'][$key]), 'name' => $name, 'pic' => dhtmlspecialchars($_GET['pic'][$key])), array('xid'=>intval($_GET['xid'][$key])));
        }
        C::t('common_setting')->update('cack_app_sign_xinqing', $data);
    }
    if (is_array(dhtmlspecialchars($_GET['newname'])) && is_array(dhtmlspecialchars($_GET['newpic']))) {
        $data = array();
        foreach (dhtmlspecialchars($_GET['newname']) as $key => $newname) {
            if (empty($newname)) {
                continue;
            }
            DB::insert('cack_app_sign_xinqing', array('displayorder' => dhtmlspecialchars($_GET['newdisplayorder'][$key]), 'name' => dhtmlspecialchars($newname), 'pic' => dhtmlspecialchars($_GET['newpic'][$key])));
        }
    }
    cpmsg($signcplang[13], 'action=plugins&operation=config&do=' . $pluginid . '&identifier=cack_app_sign&pmod=admincp_xinqing', 'succeed');
}