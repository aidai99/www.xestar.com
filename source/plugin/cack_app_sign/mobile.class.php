<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
class mobileplugin_cack_app_sign {
	function global_header_mobile(){
		include 'source/plugin/cack_app_sign/lang/'.currentlang().'.php';
		global $_G;
		$_C=$_G['cache']['plugin']['cack_app_sign'];
		if($_C['kqqjqdan']){
			if($_G[uid]){
				$qdlogquery = DB::query("SELECT * FROM ".DB::table('cack_app_sign_log')." where uid=".$_G[uid]." order by signtime desc LIMIT 0,1");
				while($cack = DB::fetch($qdlogquery)) {
					$qdlogsc[] = $cack;
				}
			}
			if(date('Ymd',$qdlogsc[0][signtime]) ==  date('Ymd',time()) && $_C['qdhqjqdan']){
			}else{
				return '<a href="plugin.php?id=cack_app_sign" style="background: linear-gradient(to right, '.$_C['uibjsz'].' , '.$_C['uibjsy'].');position: fixed;z-index: 10;border-radius: 100px;text-align: center;'.$_C['qdhqjqdancss'].'">'.$signlang[10].'</a>';
			}
		}
	}
}
?>