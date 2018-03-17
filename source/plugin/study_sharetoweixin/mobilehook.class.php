<?php
/**
 * 折翼天使资源社区源码论坛 全网首发 http://www.zheyitianshi.com
 * From www.zheyitianshi.com
 */

if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}
class mobileplugin_study_sharetoweixin {
		function common(){
				global $_G;
				if(CURSCRIPT == 'forum' && CURMODULE == 'viewthread'){
						$splugin_setting = $_G['cache']['plugin']['study_sharetoweixin'];
						$study_fids = (array)unserialize($splugin_setting['study_fids']);
						
						if(empty($_GET['tid']) && !empty($_GET['amp;tid'])){
								$splugin_setting = $_G['cache']['plugin']['study_sharetoweixin'];
								if($splugin_setting['study_dealurl']){
										$_GET['tid'] = $_GET['amp;tid'];
										$_GET['s_info'] = $_GET['s_info'] ? $_GET['s_info'] : $_GET['amp;s_info'];
										$_GET['s_md5check'] = $_GET['s_md5check'] ? $_GET['s_md5check'] : $_GET['amp;s_md5check'];
										loadforum();
								}
						}
						
						if($splugin_setting['study_award'] && in_array($_G['fid'], $study_fids)){
								$md5check = substr(md5($_GET['s_info'].md5($_G['config']['security']['authkey'].'Powered by www.zheyitianshi.com')), 8, 8);
								if(!empty($_GET['s_info']) && $_GET['s_md5check'] == $md5check){
										
										if(!$_G['wechat']['setting']) {
											$_G['wechat']['setting'] = unserialize($_G['setting']['mobilewechat']);
										}
										$_G['wechat']['setting']['wsq_allow'] = 0;
										
										$s_info = (array)unserialize(base64_decode($_GET['s_info']));
										$shareuid = intval($s_info['uid']);
										if($_G['tid'] && $_G['tid'] == $s_info['tid'] && $shareuid){
												$tid = intval($_G['tid']);
												$count_log = DB::fetch_first("SELECT * FROM ".DB::table('study_sharetoweixin_count')." WHERE tid = '$tid' AND uid = '$shareuid' ORDER BY tid DESC");
												if(empty($count_log['id'])){
														$count_log = array(
															'uid' => $shareuid,
															'tid' => $tid,
															'number' => 0,
															'status' => 0,
															'dateline' => intval($s_info['timestamp']),
														);
														$count_log['id'] = DB::insert('study_sharetoweixin_count', $count_log, true);
												}
												$count_id = intval($count_log['id']);
												if(empty($count_id)) {
														return '';
												}
												$splugin_setting['study_number'] = $splugin_setting['study_number'] ? $splugin_setting['study_number'] : 5;
												if(!$splugin_setting['study_finishstop'] || $count_log['number'] <= $splugin_setting['study_number']){
														if(!$splugin_setting['study_onlymember'] || $_G['uid']){
																$uid = intval($_G['uid']);
																$ip = daddslashes($_G['clientip']);
																if($uid){
																	$view_log = DB::fetch_first("SELECT * FROM ".DB::table('study_sharetoweixin_view')." WHERE tid = '$tid' AND (uid = '$uid' OR ip = '$ip') ORDER BY dateline DESC");
																}else{
																	$view_log = DB::fetch_first("SELECT * FROM ".DB::table('study_sharetoweixin_view')." WHERE tid = '$tid' AND ip = '$ip' ORDER BY dateline DESC");
																}
																if(empty($view_log['id'])){
																		$data = array(
																			'uid' => $uid,
																			'tid' => $tid,
																			'ip' => $ip,
																			'dateline' => intval($_G['timestamp']),
																		);
																		DB::insert('study_sharetoweixin_view', $data);
																		$count_log['number']++;
																		DB::query("UPDATE ".DB::table('study_sharetoweixin_count')." SET `number` = `number` +1 WHERE id='$count_id'");
																}
														}
												}
												
												if($count_id && !$count_log['status'] && $count_log['number'] >= $splugin_setting['study_number']){
														DB::update('study_sharetoweixin_count', array('status' => 1, 'overtime' => intval($_G['timestamp'])), "id='$count_id'");

														$splugin_setting['study_day_num'] = $splugin_setting['study_day_num'] ? $splugin_setting['study_day_num'] : 5;
														$extid = intval($splugin_setting['study_extcredit']);
														$study_ext_num = intval($splugin_setting['study_ext_num']);
														$todaytime = strtotime(date('Y-m-d', intval($_G['timestamp'])));
														$count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('study_sharetoweixin_count')." WHERE uid = '$shareuid' AND overtime > '$todaytime' AND status = '1' ORDER BY overtime DESC");
														if($count <= $splugin_setting['study_day_num'] && $extid && $study_ext_num){
																updatemembercount($shareuid, array('extcredits'.$extid => $study_ext_num), false);
																notification_add($shareuid, 'study_sharetoweixin', '&#x606D;&#x559C;&#xFF0C;&#x4F60;&#x5206;&#x4EAB;&#x5230;&#x5FAE;&#x4FE1;&#x7684;&#x5E16;&#x5B50;&#x88AB;&#x591A;&#x4EBA;&#x6D4F;&#x89C8;&#xFF0C;&#x5956;&#x52B1;&#x4F60; '.$study_ext_num.' '.$_G['setting']['extcredits'][$extid]['title'].'&#xFF0C;<a href="forum.php?mod=viewthread&tid='.intval($s_info['tid']).'" target="_blank">&#x70B9;&#x51FB;&#x67E5;&#x770B;&#x5E16;&#x5B50;</a>', '', 1);
														}
												}
										}
								}
						}
				}
		}
}

class mobileplugin_study_sharetoweixin_forum extends mobileplugin_study_sharetoweixin{
	function viewthread_postbottom_mobile_output(){
			global $_G, $postlist;
			$return = array();
			if(strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false || $_GET['msg'] == 'ok'){
					$dataForWeixin = array();
					$splugin_setting = $_G['cache']['plugin']['study_sharetoweixin'];
					$tid = intval($_G['tid']);
					$tableid = getattachtableid($tid);
					$attach = DB::fetch_first("SELECT * FROM " . DB::table('forum_attachment_'.$tableid)." WHERE tid ='$tid' AND isimage in('1','-1') ORDER BY aid DESC");
					if($attach['attachment']){
							if($attach['remote']) {
									$dataForWeixin['MsgImg'] = $_G['setting']['ftp']['attachurl'].'forum/'.$attach['attachment'];
							} else {
									$dataForWeixin['MsgImg'] = $_G['siteurl'].$_G['setting']['attachurl'].'forum/'.$attach['attachment'];
							}
					}
					if(empty($dataForWeixin['MsgImg'])){
							$dataForWeixin['MsgImg'] = avatar($_G['thread']['authorid'], 'middle', TRUE, FALSE, TRUE);
							$check_file_exists_url = $_G['setting']['ucenterurl'].'/avatar.php?uid='.$_G['thread']['authorid'].'&size=middle&type=virtual&check_file_exists=1';
							if(!file_get_contents($check_file_exists_url)){
									$dataForWeixin['MsgImg'] = (stripos($splugin_setting['study_default_image'], 'http://') !== FALSE ? '' : $_G['siteurl']).$splugin_setting['study_default_image'];
							}
					}
					foreach($postlist as $post){
							if($post['first']){
									$thread = $post;
									break;
							}
					}
					if($thread){
							$dataForWeixin['title'] = $thread['subject'];
							$dataForWeixin['desc'] = str_replace(array("\r", "\n"), array('', ''), messagecutstr(strip_tags($thread['message']), 160));
					}else{
							$dataForWeixin['desc'] = $dataForWeixin['title'] = $_G['thread']['subject'];
					}
					
					//source/plugin/study_sharetoweixin/images/msgimg
					$msgimgid = rand(1, 50);
					include template('study_sharetoweixin:wxjs');
					$return[0] = $wxjs;
			}
			foreach($postlist as $key => $post) {
				$postlist[$key] = $this->_parseattach($post);
			}
			return $return;
	}
	
	function _parseattach($post) {
		global $_G;
		if(!empty($post['attachments']) && is_array($post['attachments'])) {
			foreach($post['attachments'] as $aid => $attach) {
				$post['message'] = preg_replace('/<img id="aimg_'.$aid.'" src="forum\.php\?mod=image&aid='.$aid.'&size=([^"]*?)"/i', '<img  src="'.$attach['url'].$attach['attachment'].'"', $post['message']);
			}
		}
		return $post;
	}
}
$funcs = array();
foreach($_G['setting']['hookscriptmobile']['global']['common']['funcs']['common'] as $value){
	if($value[0] == 'study_sharetoweixin'){
		$funcs[] = $value;
		break;
	}
}
foreach($_G['setting']['hookscriptmobile']['global']['common']['funcs']['common'] as $value){
	if(!$value[0] != 'study_sharetoweixin'){
		$funcs[] = $value;
	}
}
$_G['setting']['hookscriptmobile']['global']['common']['funcs']['common'] = $funcs;

?>