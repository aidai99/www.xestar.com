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
loadcache('plugin');
$langvars=lang('plugin/defaultavatar');
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
//
if(file_exists(DISCUZ_ROOT.'./source/plugin/defaultavatar/libs/upload.lib.php')){
	@require_once DISCUZ_ROOT.'./source/plugin/defaultavatar/libs/upload.lib.php';
}else{
	showformheader("plugins&operation=config&identifier=defaultavatar&pmod=upload&op=addtask","enctype=\"multipart/form-data\" onsubmit=\"retrun false;\"");
	showtableheader($langvars['avatar_new_title'], 'nobottom');	
	showsetting($langvars['avatar_new'], 'avatar','', 'file','',0,$langvars['avatar_new_tips'].'<br><font color="red">'.$langvars['avatar_new_tips_error'].'</font>','onclick="alert(\''.$langvars['avatar_new_tips_error'].'\');return false;"');
	showsubmit('addsubmit');
	showtablefooter();
	showformfooter();
}

if(submitcheck('submit')){
	foreach($config as $k=>$v){
		if(isset($_POST['delete']['key_'.$k])){
			@unlink(DISCUZ_ROOT.'./source/plugin/defaultavatar/avatar/'.$v['name']);
			unset($config[$k]);
		}else{
			if(isset($_POST['select'][$k])) $config[$k]['status']=1;
			else{
				$config[$k]['status']=0;
			}
		}
	}
	updateImgCache($config);
	cpmsg($langvars['ok'],'action=plugins&operation=config&identifier=defaultavatar&pmod=upload', 'succeed');
}else{
	showformheader('plugins&operation=config&identifier=defaultavatar&pmod=upload');
	showtableheader($langvars['config_title'], 'nobottom');
	showsubtitle(array($langvars['key'],$langvars['pic'],$langvars['dateline'],$langvars['status']));
	krsort($config);
	foreach($config as $k=>$pic){
		if($pic['status']) $check='checked';
		else $check='';
		if(substr($pic['name'],0,5)=='auto_'){
			$f_name=substr($pic['name'],38);
			$dateline=intval(str_replace('.jpg','',$f_name));
		}else{
			$dateline=intval(str_replace(array('avatar_','.jpg'),'',$pic['name']));
		}
		showtablerow('', array('class="td25"', 'class="td_k"', 'class="td_l"'), array(
			"<input class=\"checkbox\" type=\"checkbox\" name=\"delete[key_".$k."]\" value=\"1\">",
			'<img width="48" src="'.$_G['siteurl'].'source/plugin/defaultavatar/avatar/'.$pic['name'].'">',
			dgmdate($dateline,'Y-m-d H:i:s'),
			"<input class=\"checkbox\" type=\"checkbox\" name=\"select[".$k."]\" value=\"".$k."\" $check>",
		));
	}
	showsubmit('submit',$langvars['submit'],$langvars['del'].'<input class="checkbox" type="checkbox" name="groupall" onclick="checkAll(\'prefix\', this.form, \'delete\', \'groupall\')">', '','');
	showtablefooter();
	showformfooter();
}

function updateImgCache($config){
	@require_once libfile('function/cache');
	$cacheArray .= "\$config=".arrayeval($config).";\n";
	writetocache('defaultavatar_config', $cacheArray);
}
?>