<?php


if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
class plugin_cack_app_sign {
    function global_usernav_extra2() {
		include 'source/plugin/cack_app_sign/lang/'.currentlang().'.php';
		global $_G;
		if($_G[uid]){
			$qdlogquery = DB::query("SELECT * FROM ".DB::table('cack_app_sign_log')." where uid=".$_G[uid]." order by signtime desc LIMIT 0,1");
			while($cack = DB::fetch($qdlogquery)) {
				$qdlogsc[] = $cack;
			}
		}
		$_C=$_G['cache']['plugin']['cack_app_sign'];
		if(date('Ymd',$qdlogsc[0][signtime]) ==  date('Ymd',time())){
			return '<span class="pipe">|</span><a href="javascript:;" onclick="showWindow(\'cacksign\', \'plugin.php?id=cack_app_sign:pcajax\', \'get\', 0)">'.$_C[dnbyqan].'</a>';
		}else{
			return '<span class="pipe">|</span><a href="javascript:;" onclick="showWindow(\'cacksign\', \'plugin.php?id=cack_app_sign:pcajax\', \'get\', 0)">'.$_C[dnbqdnn].'</a>';
		}
    }
	function global_footer() {
		global $_G;
		if($_G['cache']['plugin']['cack_app_sign']['dnbqdk'] && $_G[uid]){
			if($_G[uid]){
				$qdlogquery = DB::query("SELECT * FROM ".DB::table('cack_app_sign_log')." where uid=".$_G[uid]." order by signtime desc LIMIT 0,1");
				while($cack = DB::fetch($qdlogquery)) {
					$qdlogsc[] = $cack;
				}
			}
			if(date('Ymd',$qdlogsc[0][signtime]) ==  date('Ymd',time())){
			}else{
				return '<script type="text/javascript">showWindow(\'cacksign\', \'plugin.php?id=cack_app_sign:pcajax&'.FORMHASH.'\');</script>';
			}
		}
	}
}

?>