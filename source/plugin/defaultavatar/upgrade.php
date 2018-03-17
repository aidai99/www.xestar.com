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
if(!file_exists(DISCUZ_ROOT.'./data/sysdata/cache_defaultavatar_config.php')){//upgrade
	$config=array();
	if($_G['setting']['defaultavatar']&&$_G['setting']['defaultavatar']!='a:0:{}'){
		$config=unserialize($_G['setting']['defaultavatar']);
	}
	if(count($config)){
		@require_once libfile('function/cache');
		$cacheArray .= "\$config=".arrayeval($config).";\n";
		writetocache('defaultavatar_config', $cacheArray);
	}
}
$finish = TRUE;
?>