<?php
/*
	ID: jingcai_7ree
	[www.7ree.com] (C)2007-2017 7ree.com.
	This is NOT a freeware, use is subject to license terms
	Update: 2017/8/3 16:18
	Agreement: http://addon.discuz.com/?@7.developer.doc/agreement_7ree_html
	More Plugins: http://addon.discuz.com/?@7ree
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

				if($_GET['formhash'] <> FORMHASH) showmessage("Access Deined. @ 7ree.com");
			    $uid_7ree=intval($_GET['uid_7ree']); 
			    $lid_7ree=intval($_GET['lid_7ree']);
			    if(!$uid_7ree) showmessage("ERROR## Miss uid @ 7ree");
			    if(!$lid_7ree) showmessage("ERROR## Miss lid @ 7ree");
			    $payselect_7ree = DB::result_first("SELECT point_7ree FROM ".DB::table('jingcai_log_7ree')." WHERE log_id_7ree='$lid_7ree'");
			    $payext_7ree = $payselect_7ree ? $jingcai_7ree_var['viewcost2_7ree'] : $jingcai_7ree_var['viewcost_7ree'];
			    
				//或者直接检测扣分是否足够
				if(DB::result_first("SELECT {$viewextname_7ree} FROM ".DB::table('common_member_count')." WHERE uid='{$_G[uid]}'") < $payext_7ree) showmessage('jingcai_7ree:php_lang_jifenbuzu_7ree',"");
				DB::query("UPDATE ".DB::table('common_member_count')." SET {$viewextname_7ree} = {$viewextname_7ree} - {$payext_7ree} WHERE uid='{$_G[uid]}' LIMIT 1");
			    //返还扣除税率后积分给被查看者
				$payment2user_7ree = round($payext_7ree*(1-$jingcai_7ree_var['viewtax_7ree'])); 
			    DB::query("UPDATE ".DB::table('common_member_count')." SET {$viewextname_7ree} = {$viewextname_7ree} + {$payment2user_7ree} WHERE uid='{$uid_7ree}' LIMIT 1");

				DB::query("INSERT INTO ".DB::table('jingcai_payment_7ree')." SET uid_7ree='{$_G[uid]}', touid_7ree = '$uid_7ree', time_7ree = '$_G[timestamp]', payment_7ree='$payext_7ree', lid_7ree='$lid_7ree'");
				showmessage('jingcai_7ree:php_lang_payok_7ree',"plugin.php?id=jingcai_7ree:jingcai_7ree&ac_7ree=10&uid_7ree={$uid_7ree}");


?>