<?php
/*
 * 主页：http://addon.discuz.com/?@ailab
 * 人工智能实验室：Discuz!应用中心十大优秀开发者！
 * 插件定制 联系QQ594941227
 * From www.ailab.cn
 */
 
if(!defined('IN_ADMINCP')) {
	exit('Access Denied');
}
if(file_exists(DISCUZ_ROOT.'./data/sysdata/cache_nimba_sitemap_log.php')) @unlink(DISCUZ_ROOT.'./data/sysdata/cache_nimba_sitemap_log.php');
$finish = TRUE;

?>