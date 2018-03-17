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
			cpmsg('&#x786E;&#x5B9A;&#x6E05;&#x9664;&#x8BB0;&#x5F55;&#xFF0C;&#x672C;&#x64CD;&#x4F5C;&#x4E0D;&#x53EF;&#x9006;', 'action=plugins&operation=config&do='.$pluginid.'&identifier=stud'.'y_sharetow'.'eixin&pmod=sharelog&truncate=1314&delete=1314&formhash='.$_G['formhash'], 'form', array(), '', TRUE, ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=stud'.'y_sharetow'.'eixin&pmod=sharelog');
		}
		DB::query("TRUNCATE TABLE  ".DB::table('study_sharetoweixin_view'));
		cpmsg('&#x8BB0;&#x5F55;&#x6E05;&#x9664;&#x6210;&#x529F;', 'action=plugins&operation=config&do='.$pluginid.'&identifier=stud'.'y_sharetow'.'eixin&pmod=sharelog', 'succeed');
}else{
		loadcache(plugin);
		$splugin_setting = $_G['cache']['plugin']['study_sharetoweixin_view'];
		
		showtableheader();
		
		$num = DB::result_first("SELECT COUNT(*) FROM ".DB::table('study_sharetoweixin_view')." ");
		$page = intval($_G['page']);
		$limit = 10;
		$max = 1000;
		$page = ($page-1 > $num/$limit || $page > $max) ? 1 : $page;
		$start_limit = ($page - 1) * $limit;
		$multipage = multi($num, $limit, $page, ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=study_sharetoweixin&pmod=sharelog', $max);
		
		$query = DB::query("SELECT * FROM ".DB::table('study_sharetoweixin_view')." ORDER BY id DESC limit $start_limit,$limit");
		$sharelogs = $logtids = array();
		while($sharelog = DB::fetch($query)) {
				if($sharelog['tid']){
					$sharelog['tid'] = intval($sharelog['tid']);
					$logtids[$sharelog['tid']] = $sharelog['tid'];
				}
				$sharelogs[] = dhtmlspecialchars($sharelog);
		}
		if(empty($sharelogs)){
				echo '<td colspan="6" align="center" style="color:red;font-size:25px"><b>&#x6CA1;&#x6709;&#x8BB0;&#x5F55;</b></td>';
		}else{
				showsubtitle(array('&#x5E16;&#x5B50;', '&#x7528;&#x6237;&#x540D;', 'IP-&#x5730;&#x5740;', '&#x64CD;&#x4F5C;&#x65F6;&#x95F4;'));
				
				$intids = implode(',', $logtids);
				if($intids){
						$query = DB::query("SELECT * FROM ".DB::table('forum_thread')." WHERE tid in($intids) ORDER BY tid DESC");
						while($thread = DB::fetch($query)) {
								$threads[$thread['tid']] = $thread;
						}
				}
				
				foreach($sharelogs as $sharelog){
						$member = getuserbyuid($sharelog['uid']);
						echo '<tbody><tr>
						<td><a href="forum.php?mod=viewthread&tid='.$sharelog['tid'].'" target="_blank">'.($threads[$sharelog['tid']]['subject'] ? $threads[$sharelog['tid']]['subject'] : 'tid:'.$sharelog['tid']).'</a></td>
						<td>'.($sharelog['uid'] ? '<a href="home.php?mod=space&uid='.$sharelog['uid'].'" target="_blank">'.$member['username'].'</a>' : '&#x6E38;&#x5BA2;').'</td>
						<td>'.$sharelog['ip'].convertip($sharelog['ip']).'</td>
						<td>'.dgmdate($sharelog['dateline'], 'u', '9999', getglobal('setting/dateformat').' H:i:s').'</td>
						</tr>
						</tbody>';
				}
		}
		echo '<tr>
		<td align="left">
			<a href="'.ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=study_sharetoweixin&pmod=sharelog&delete=1314&formhash='.$_G['formhash'].'" style="color: red;font-weight: 600;font-size: 16px;">&#x6E05;&#x7A7A;&#x8BB0;&#x5F55;</a>
		</td>
		<td colspan="3" align="right">
			'.$multipage.'
		</td>
		</tr>';
		showtablefooter();
}	
?>