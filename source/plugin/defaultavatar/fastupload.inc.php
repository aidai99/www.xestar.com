<?php
/*
 * 应用中心主页：http://addon.discuz.com/?@ailab
 * 人工智能实验室：Discuz!应用中心十大优秀开发者！
 * 插件定制 联系QQ594941227
 * From www.ailab.cn
 */
 
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
$langvar=lang('plugin/defaultavatar');
$config=array();
if(!file_exists(DISCUZ_ROOT.'./data/sysdata/cache_defaultavatar_config.php')){//upgrade
	if($_G['setting']['defaultavatar']&&$_G['setting']['defaultavatar']!='a:0:{}'){
		$config=unserialize($_G['setting']['defaultavatar']);
	}else{
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
		foreach($pics as $k=>$pic){
			if(file_exists(DISCUZ_ROOT.'./source/plugin/defaultavatar/avatar/default/'.$pic)){
				copy(DISCUZ_ROOT.'./source/plugin/defaultavatar/avatar/default/'.$pic,DISCUZ_ROOT.'./source/plugin/defaultavatar/avatar/'.$pic);
				$config[]=array('name'=>$pic,'status'=>1);
			}
		}
	}
	if($config&&count($config)){
		updateImgCache($config);
	}
}else{
	@require_once DISCUZ_ROOT.'./data/sysdata/cache_defaultavatar_config.php';
}
if($_GET['op']=='update'){
	if(!file_exists(DISCUZ_ROOT.'./source/plugin/defaultavatar/libs/upload.lib.php')){
		cpmsg($langvar['update_error'],'action=plugins&operation=config&identifier=defaultavatar&pmod=fastupload', 'succeed');
	}else{
		scanfile();
		updateImgCache($config);
		cpmsg($langvar['update_ok'],'action=plugins&operation=config&identifier=defaultavatar&pmod=fastupload', 'succeed');
	}
}else{
	showtableheader($langvar['fastupload']);
	showtablerow('', array('class="td25"', 'class="td_k"', 'class="td_l"'), array(
		$langvar['tip_1']
	));
	showtablerow('', array('class="td25"', 'class="td_k"', 'class="td_l"'), array(
		$langvar['tip_2']
	));
	showtablerow('', array('class="td25"', 'class="td_k"', 'class="td_l"'), array(
		$langvar['tip_3']
	));
	if(!file_exists(DISCUZ_ROOT.'./source/plugin/defaultavatar/libs/upload.lib.php')){
		showtablerow('', array('class="td25"', 'class="td_k"', 'class="td_l"'), array(
			'<font color="red">'.$langvar['tip_4'].'</font>'
		));
	}	
	showtablerow('', array('class="td25"', 'class="td_k"', 'class="td_l"'), array(
		'<a href="'.ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=defaultavatar&pmod=fastupload&op=update"><strong>'.$langvar['update'].'</strong></a>'
	));
	
	showtablefooter();
}

function scanfile(){
	global $_G,$config;
	$dir = DISCUZ_ROOT.'./source/plugin/defaultavatar/fastupload/';
	$handle=opendir($dir); 
	$avatar=array();
	while(false!==($file=readdir($handle))){ 
		if(substr_count(strtolower($file),'.jpg')){
			$r=@rename($dir.$file,DISCUZ_ROOT.'./source/plugin/defaultavatar/avatar/auto_'.md5($file).'_'.time().'.jpg');
			if($r) $config[]=array('name'=>'auto_'.md5($file).'_'.time().'.jpg','status'=>1);
		}
	}	
}

function updateImgCache($config){
	@require_once libfile('function/cache');
	$cacheArray .= "\$config=".arrayeval($config).";\n";
	writetocache('defaultavatar_config', $cacheArray);
}
?>