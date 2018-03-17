<?php
/**
 * 折翼天使资源社区源码论坛 全网首发 http://www.zheyitianshi.com
 * From www.zheyitianshi.com
 */

if(!defined('IN_DISCUZ')) {
    exit('http://127.0.0.1/');
}
class plugin_study_sharetoweixin {
		function common(){
				global $_G;
				if(CURSCRIPT == 'forum' && CURMODULE == 'viewthread'){
						if(empty($_GET['tid']) && !empty($_GET['amp;tid'])){
								$splugin_setting = $_G['cache']['plugin']['study_sharetoweixin'];
								if($splugin_setting['study_dealurl']){
										$_GET['tid'] = $_GET['amp;tid'];
										loadforum();
								}
						}
				}
		}
}

class plugin_study_sharetoweixin_forum extends plugin_study_sharetoweixin {
		function viewthread_postbottom_output(){
				global $_G, $postlist;
				$return = array();
				if(strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false){
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
						include template('study_sharetoweixin:wxjs');
						$return[0] = $wxjs;
				}
				return $return;
		}
		
		function viewthread_sidebottom_output(){
				return $this->_share_qrcode(1);
		}
		
		function viewthread_useraction(){
				return $this->_share_qrcode(2, '');
		}
		
		function viewthread_postfooter(){
				return $this->_share_qrcode(3);
		}
		
		function viewthread_title_extra_output(){
				return $this->_share_qrcode(4, '');
		}
		
		function _share_qrcode($place, $return = array()){
				global $_G;
				if(!$_G['inajax'] && $_G['page'] == 1){
						$splugin_setting = $_G['cache']['plugin']['study_sharetoweixin'];
						if($splugin_setting['study_place'] == $place){
								$study_fids = (array)unserialize($splugin_setting['study_fids']);
								if(in_array($_G['fid'], $study_fids)){
										if($place == 1){
												$qrcode_url = $this->_study_sharetoweixin_get_qrcode_url($_G['tid']);
												$return[0] = '<div style="text-align:center">
												<a href="javascript:;" onclick="showWindow(\'study_sharetoweixin\', \'plugin.php?id=study_sharetoweixin\')"><img src="source/plugin/study_sharetoweixin/images/title_wechat.png" height="27" width="138"></a>
												<a href="javascript:;" onclick="showWindow(\'study_sharetoweixin_qrcode\', \'plugin.php?id=study_sharetoweixin&qrcode=1&tid='.$_G['tid'].'&auth='.$this->_study_sharetoweixin_authkey($_G['tid']).'\', \'get\', -1);return false;" title="&#x70B9;&#x51FB;&#x653E;&#x5927;"><img src="'.$qrcode_url.'" alt="qrcode" width="140" height="140"/></a>
												<a href="javascript:;" onclick="showWindow(\'study_sharetoweixin\', \'plugin.php?id=study_sharetoweixin\')"><img src="source/plugin/study_sharetoweixin/images/help.png" height="27" width="138"></a>
												</div>';
										}elseif($place == 2){
												$return = '<a href="javascript:;" id="ak_rate" onclick="showWindow(\'study_sharetoweixin_qrcode\', \'plugin.php?id=study_sharetoweixin&qrcode=1&tid='.$_G['tid'].'&auth='.$this->_study_sharetoweixin_authkey($_G['tid']).'\', \'get\', -1);return false;"><i><img src="source/plugin/study_sharetoweixin/images/weixin_logo.png" alt="&#x5206;&#x4EAB;&#x5230;&#x5FAE;&#x4FE1;">&#x5206;&#x4EAB;&#x5230;&#x5FAE;&#x4FE1;</i></a>';
										}elseif($place == 3){
												$return[0] = '<a style="background: url(source/plugin/study_sharetoweixin/images/weixin_logo.png) no-repeat 4px 50%;" href="javascript:;" onclick="showWindow(\'study_sharetoweixin_qrcode\', \'plugin.php?id=study_sharetoweixin&qrcode=1&tid='.$_G['tid'].'&auth='.$this->_study_sharetoweixin_authkey($_G['tid']).'\', \'get\', -1);return false;">&#x5206;&#x4EAB;&#x5230;&#x5FAE;&#x4FE1;</a>';
										}elseif($place == 4){
												$qrcode_url = $this->_study_sharetoweixin_get_qrcode_url($_G['tid']);
												include_once template('study_sharetoweixin:qrcode4');
										}
								}
						}
				}
				return $return;
		}

		function _study_sharetoweixin_get_qrcode_url($tid){
				global $_G;
				$splugin_setting = $_G['cache']['plugin']['study_sharetoweixin'];
				$_info = array();
				$_info['uid'] = $_G['uid'];
				$_info['tid'] = $tid;
				$_info['timestamp'] = $_G['timestamp'];
				$_info = base64_encode(serialize($_info));
				$_md5Check = substr(md5($_info.md5($_G['config']['security']['authkey'].'Powe'.'red b'.'y ww'.'w.131'.'4st'.'udy.co'.'m')), 8, 8);
				$threadurl = $_G['siteurl'].'forum.php?mod=viewthread&tid='.$tid.'&mobile=yes&s_info='.$_info.'&s_md5check='.$_md5Check;
				$splugin_setting['study_dwz2'] && $threadurl = $this->_dwz($threadurl);
				$threadurl = urlencode($threadurl);
				$qrcode_api = array(
					 '1' => 'http://chart.googleapis.com/chart?cht=qr&chs=150x150&choe=UTF-8&chld=L|2&chl=',
					 '2' => 'https://chart.googleapis.com/chart?cht=qr&chs=150x150&choe=UTF-8&chld=L|2&chl=',
					 '3' => 'http://b.bshare.cn/barCode?site=weixin&url=',
					 '4' => 'http://s.jiathis.com/qrcode.php?url=',
				);
				$study_qrcode_api = intval($splugin_setting['study_qrcode_api']);
				$qrcode_url = $qrcode_api[$study_qrcode_api].$threadurl;
				return $qrcode_url;
		}
		
		function _dwz($url){
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, "http://www.zheyitianshi.com/create.php");
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				$data = array('url' => $url);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
				$strRes = curl_exec($ch);
				curl_close($ch);
				$arrResponse = json_decode($strRes, true);
				if($arrResponse['tinyurl']){
					return $arrResponse['tinyurl'];
				}else{
					return $url;
				}
		}
		function _study_sharetoweixin_authkey($tid){
				global $_G;
				return substr(md5(md5(intval($tid).$_G['uid'].$_G['authkey']).$_G['formhash']), 8, 8);
		}
}
//From:www.zheyitianshi.com
?>