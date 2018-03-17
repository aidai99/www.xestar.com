<?php

/**
 * 折翼天使资源社区源码论坛 全网首发 http://www.zheyitianshi.com
 * From www.zheyitianshi.com
 */
 
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

require_once ('pluginvar.func.php');

if($_GET['delete'] == '1314' && $_GET['formhash'] && $_GET['formhash'] == $_G['formhash']){
		if(!$_GET['truncate']) {
			cpmsg('&#x786E;&#x5B9A;&#x6E05;&#x9664;&#x5956;&#x52B1;&#x6570;&#x636E;&#xFF0C;&#x672C;&#x64CD;&#x4F5C;&#x4E0D;&#x53EF;&#x9006;&#xFF0C;&#x6570;&#x636E;&#x6E05;&#x9664;&#x540E;&#x4F1A;&#x91CD;&#x65B0;&#x8FDB;&#x884C;&#x7EDF;&#x8BA1;&#x548C;&#x5956;&#x52B1;', 'action=plugins&operation=config&do='.$pluginid.'&identifier=stud'.'y_sharetow'.'eixin&pmod=countlog&truncate=1314&delete=1314&formhash='.$_G['formhash'], 'form', array(), '', TRUE, ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=stud'.'y_sharetow'.'eixin&pmod=countlog');
		}
		DB::query("TRUNCATE TABLE  ".DB::table('study_sharetoweixin_count'));
		cpmsg('&#x7EDF;&#x8BA1;&#x6570;&#x636E;&#x6E05;&#x9664;&#x6210;&#x529F;', 'action=plugins&operation=config&do='.$pluginid.'&identifier=stud'.'y_sharetow'.'eixin&pmod=countlog', 'succeed');
}else{
		loadcache(plugin);
		$splugin_setting = $_G['cache']['plugin']['study_sharetoweixin_count'];
		
		showtableheader();
		
		$num = DB::result_first("SELECT COUNT(*) FROM ".DB::table('study_sharetoweixin_count')." ");
		$page = intval($_G['page']);
		$limit = 10;
		$max = 1000;
		$page = ($page-1 > $num/$limit || $page > $max) ? 1 : $page;
		$start_limit = ($page - 1) * $limit;
		$multipage = multi($num, $limit, $page, ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=study_sharetoweixin&pmod=countlog', $max);
		
		$query = DB::query("SELECT * FROM ".DB::table('study_sharetoweixin_count')." ORDER BY status ASC,id DESC limit $start_limit,$limit");
		$countlogs = $logtids = array();
		while($countlog = DB::fetch($query)) {
				if($countlog['tid']){
					$countlog['tid'] = intval($countlog['tid']);
					$logtids[$countlog['tid']] = $countlog['tid'];
				}
				$countlogs[] = dhtmlspecialchars($countlog);
		}
		if(empty($countlogs)){
				echo '<td colspan="6" align="center" style="color:red;font-size:25px"><b>&#x6CA1;&#x6709;&#x8BB0;&#x5F55;</b></td>';
		}else{
				showsubtitle(array('&#x5E16;&#x5B50;', '&#x5206;&#x4EAB;&#x8005;', '&#x6709;&#x6548;&#x67E5;&#x770B;&#x6B21;&#x6570;', '&#x72B6;&#x6001;', '&#x5206;&#x4EAB;&#x65F6;&#x95F4;'));
				
				$intids = implode(',', $logtids);
				if($intids){
						$query = DB::query("SELECT * FROM ".DB::table('forum_thread')." WHERE tid in($intids) ORDER BY tid DESC");
						while($thread = DB::fetch($query)) {
								$threads[$thread['tid']] = $thread;
						}
				}
				
				foreach($countlogs as $countlog){
						$member = getuserbyuid($countlog['uid']);
						echo '<tbody><tr>
						<td><a href="forum.php?mod=viewthread&tid='.$countlog['tid'].'" target="_blank">'.($threads[$countlog['tid']]['subject'] ? $threads[$countlog['tid']]['subject'] : 'tid:'.$countlog['tid']).'</a></td>
						<td>'.($countlog['uid'] ? '<a href="home.php?mod=space&uid='.$countlog['uid'].'" target="_blank">'.$member['username'].'</a>' : '&#x6E38;&#x5BA2;').'</td>
						<td>'.$countlog['number'].'</td>
						<td>'.($countlog['status'] == 1 ? '<span  style="color: red;font-weight: 600;">&#x5DF2;&#x5956;&#x52B1;&#xFF08;'.dgmdate($countlog['overtime'], 'u', '9999', getglobal('setting/dateformat').' H:i:s').'&#xFF09;</span>' : '&#x672A;&#x8FBE;&#x5230;&#x5956;&#x52B1;&#x8981;&#x6C42;').'</td>
						<td>'.dgmdate($countlog['dateline'], 'u', '9999', getglobal('setting/dateformat').' H:i:s').'</td>
						</tr>
						</tbody>';
				}
		}
		echo '<tr>
		<td align="left">
			<a href="'.ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=study_sharetoweixin&pmod=countlog&delete=1314&formhash='.$_G['formhash'].'" style="color: red;font-weight: 600;font-size: 16px;">&#x6E05;&#x7A7A;&#x8BB0;&#x5F55;</a>
		</td>
		<td colspan="4" align="right">
			'.$multipage.'
		</td>
		</tr>';
		showtablefooter();
}	
?>