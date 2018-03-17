<?php

/**
 * 
 * 折翼天使资源社区www.zheyitianshi.com
 * 克米设计工作室 版权所有 http://www.zheyitianshi.com
 * 专业论坛首页及风格制作, 页面设计美化, 数据搬家/升级, 程序二次开发, 网站效果图设计, 页面标准DIV+CSS生成, 各类大中小型企业网站设计...
 * 我们致力于为企业提供优质网站建设、网站推广、网站优化、程序开发、域名注册、虚拟主机等服务，
 * 一流设计和解决方案为企业量身打造适合自己需求的网站运营平台，最大限度地使企业在信息时代稳握无限商机。
 *
 *   QQ群：626530746
 * 
 */
 
if(!defined('IN_DISCUZ')) {exit('Access Denied');}
$finish = TRUE;
class plugin_comiis_authorpost { }
class plugin_comiis_authorpost_forum extends plugin_comiis_authorpost{
    function viewthread_sidebottom_output(){
		global $_G, $postlist;
		$return = $plugindata = $forum = array();
		$list = $order = '';
		if(is_array($postlist)){
			foreach($postlist as $temp) {
				if($temp['first']==1){
					$plugindata = $_G['cache']['plugin']['comiis_authorpost'];
					$forum = empty($plugindata['open']) ? array() : unserialize($plugindata['open']);
					if(!is_array($forum)) $forum = array();
					$vips = empty($plugindata['vip']) ? array() : unserialize($plugindata['vip']);
					if(!is_array($vips)) $vips = array();
					if((empty($forum[0]) || in_array($_G['fid'], $forum)) && (empty($vips[0]) || in_array($temp['groupid'], $vips)) && ($temp['threads'] >= $plugindata['minthreads'] || !empty($plugindata['tids']))){//From www.zheyitianshi.com
						$tids = !empty($plugindata['tids']) ? explode(',', $plugindata['tids']) : array();
						if($tids){
							$filteradd = 'tid IN ('.dimplode($tids).')';
						}else{
							$filteradd = "authorid='".intval($temp['authorid'])."'";
						}
						$order = in_array($plugindata['where'], array('dateline','lastpost','views','replies')) ? $plugindata['where'] : 'dateline';
						$query=DB::query("SELECT tid, subject FROM ".DB::table('forum_thread')." where {$filteradd} and tid <>'".intval($temp['tid'])."' ORDER BY {$order} DESC  LIMIT ".intval($plugindata['number']));
						while($list_temp=DB::fetch($query)){
							$list.= '<li><a href="forum.php?mod=viewthread&tid='.$list_temp['tid'].'" title="'.$list_temp['subject'].'" target="_blank">'.cutstr($list_temp['subject'], $plugindata['titlelen'], '').'</a></li>';
						}//Fr  om www.m  oqu8.com
						$return[]='
							<style>
							.pls .comiis_authorpost{padding:10px 8px;}
							.pls .comiis_authorpost .b{font-weight:700;padding-left:2px;}
							.pls .comiis_authorpost ul{background:'.$plugindata['bgcolor'].';border:1px solid '.$plugindata['divcolor'].';border-radius:4px;padding:2px 6px;}
							.pls .comiis_authorpost .mt5{margin:5px 0 0 0;}
							.pls .comiis_authorpost .s2{color:'.$plugindata['titlecolor'].';}
							.pls .comiis_authorpost li{line-height:24px;height:24px;overflow:hidden;text-indent:0;color:'.$plugindata['listcolor'].';padding-left:10px;background:url(source/plugin/comiis_authorpost/comiis_dot.gif) no-repeat 2px 10px;}
							.pls .comiis_authorpost li a{color:'.$plugindata['listcolor'].';margin-left:3px;}
							.pls .comiis_authorpost p a{color:'.$plugindata['morecolor'].';}
							</style>
							<div class="comiis_authorpost cl"><span class="s2 b">'.$plugindata['title'].'</span>
								<ul class="mt5">'.$list.'</ul>
								<P class="mt5 b"><a href="'.($plugindata['all_url'] ? $plugindata['all_url'] : 'home.php?mod=space&uid='.$temp['authorid'].'&do=thread&from=space').'" target="_blank">'.$plugindata['more'].'</a></p>
							</div>
						';//From w  ww.www.zheyitianshi.com
						return $return;
					}
				}
			}
		}
	}
}
?>