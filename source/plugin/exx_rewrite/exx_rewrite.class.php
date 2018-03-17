<?php

/*
 *折翼天使资源社区：www.zheyitianshi.com
 *更多商业插件/模版折翼天使资源社区 就在折翼天使资源社区
 *本资源来源于网络收集,仅供个人学习交流，请勿用于商业用途，并于下载24小时后删除!
 *如果侵犯了您的权益,请及时告知我们,我们即刻删除!
 */
 
if(!defined('IN_DISCUZ')) {
  exit('Access Denied');
}
class plugin_exx_rewrite{
	function global_header(){
		global $_G;
		$var = $_G['cache']['plugin']['exx_rewrite'];
		$section = empty($var['yhz']) ? array() : unserialize($var['yhz']);
		if(in_array($_G['groupid'],$section)){
			$url='';
			$urls=$_SERVER['REQUEST_URI']; //针对liunx等非Windows的服务器
			$urla=$_SERVER["HTTP_X_REWRITE_URL"];//针对Windows的服务器
			$_GET['page']= $_GET['page'] ? intval($_GET['page']) : 1;
			if(((strpos($urls, '?') !== FALSE) && !$urla) || ($urla && (strpos($urla, '?') !== FALSE))) {
				if($_G['tid'] && $_G['fid'] && $_G['mod']=='viewthread' && $var['tnr'] && (in_array('forum_viewthread', $_G['setting']['rewritestatus']))){
					$url=rewriteoutput('forum_viewthread', 1, '', $_G['tid'], 1, '', '');
				}elseif($_G['fid'] && !$_G['tid'] && $_G['mod']=='forumdisplay' && $var['tlb'] && (in_array('forum_forumdisplay', $_G['setting']['rewritestatus']))){
					$url=rewriteoutput('forum_forumdisplay', 1, '', $_G['fid'], $page);
				}elseif($_GET['aid'] && $_G['catid'] && $_G['mod']=='view' && $var['wnr'] && (in_array('portal_article', $_G['setting']['rewritestatus']))){
					$url=rewriteoutput('portal_article', 1, '', $_GET['aid']);
				}elseif(!$_G['tid'] && $_G['fid'] && $_G['mod']=='group' && $var['qlb'] && (in_array('group_group', $_G['setting']['rewritestatus']))){
					$url=rewriteoutput('group_group', 1, '', $_G['fid'], 1, '', '');
				}
				if($url){
					Header("HTTP/1.1 301 Moved Permanently");
					Header("Location: ".$_G['siteurl'].$url);
					exit();
				}	
			}
		}
	}
}