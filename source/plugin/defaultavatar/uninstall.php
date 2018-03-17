<?php
/*
 * 应用中心主页：http://addon.discuz.com/?@ailab
 * 人工智能实验室：Discuz!应用中心十大优秀开发者！
 * 插件定制 联系QQ594941227
 * From www.ailab.cn
 */
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
C::t('common_setting')->delete('defaultavatar');
updatecache('setting');
if(file_exists(DISCUZ_ROOT.'./data/sysdata/cache_defaultavatar_config.php')){
	@unlink(DISCUZ_ROOT.'./data/sysdata/cache_defaultavatar_config.php');
}
$finish = TRUE;
?>