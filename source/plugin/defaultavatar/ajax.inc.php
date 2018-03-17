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
$vars = $_G['cache']['plugin']['defaultavatar'];
include template('common/header_ajax');
$config=array();
if(file_exists(DISCUZ_ROOT.'./data/sysdata/cache_defaultavatar_config.php')){
	@require_once DISCUZ_ROOT.'./data/sysdata/cache_defaultavatar_config.php';
}
$avatars=$config;
foreach($avatars as $k=>$avatar){
	if(!$avatars[$k]['status']) unset($avatars[$k]);
}
if($vars['rand']){
	$vars['randnum']=intval($vars['randnum']);
	if($vars['randnum']&&$vars['randnum']<count($avatars)){
		$rand_keys=array_rand($avatars,$vars['randnum']);
		$a=array();
		foreach($rand_keys as $k=>$v){
			$a[]=$avatars[$v];
		
		}
		$avatars=$a;
	}
}
include template('defaultavatar:ajax');
include template('common/footer_ajax');
?>