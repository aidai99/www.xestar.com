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
$pics=array(
	'avatar_1403071383.jpg',
	'avatar_1403071725.jpg',
	'avatar_1403071735.jpg',
	'avatar_1403071746.jpg',
	'avatar_1403071764.jpg',
	'avatar_1403071782.jpg',
	'avatar_1403071798.jpg',
	'avatar_1403071814.jpg',
	'avatar_1403071832.jpg',
	'avatar_1403071844.jpg',
);
$config=array();
foreach($pics as $k=>$pic){
	if(file_exists(DISCUZ_ROOT.'./source/plugin/defaultavatar/avatar/default/'.$pic)){
		copy(DISCUZ_ROOT.'./source/plugin/defaultavatar/avatar/default/'.$pic,DISCUZ_ROOT.'./source/plugin/defaultavatar/avatar/'.$pic);
		$config[]=array('name'=>$pic,'status'=>1);
	}
}
if(count($config)){
	@require_once libfile('function/cache');
	$cacheArray .= "\$config=".arrayeval($config).";\n";
	writetocache('defaultavatar_config', $cacheArray);
}

$finish = TRUE;

?>