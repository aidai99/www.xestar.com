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

class plugin_defaultavatar {
	function pluginCache($cacheName,$varName,$data,$isarray){
		@require_once libfile('function/cache');
		if($isarray){
			$cacheArray .= "\$$varName=".arrayeval($data).";\n";
			writetocache($cacheName, $cacheArray);
		}else{
			$cacheArray .= "\$$varName=".$data.";\n";
			writetocache($cacheName, $cacheArray);
		}
	}
	
	function createAvatar($img,$dir,$size){
		$image_size = getimagesize($img);
		$width=array('big'=>200,'middle'=>120,'small'=>48);
		$from=imagecreatefromjpeg($img);
		if($image_size[0]>$image_size[1]){
			$w=$width[$size]*$image_size[1]/$image_size[0];
			$h=$width[$size];
		}elseif($image_size[0]<$image_size[1]){
			$w=$width[$size];
			$h=$width[$size]*$image_size[0]/$image_size[1];
		}else{
			$w=$width[$size];
			$h=$width[$size];	
		}
		$new=imagecreatetruecolor($w,$h);
		imagecopyresized($new,$from,0,0,0,0,$w,$h,$image_size[0],$image_size[1]);
		imagejpeg($new,$dir);
	}

	function checkAvatarDir($dir){
		$root=DISCUZ_ROOT.'/uc_server/data/avatar/';
		$dir1 = substr($dir, 0, 3);
		$dir2 = substr($dir, 3, 2);
		$dir3 = substr($dir, 5, 2);
		$root.=$dir1.'/';
		if(!is_dir($root)) mkdir($root);
		$root.=$dir2.'/';
		if(!is_dir($root)) mkdir($root);
		$root.=$dir3.'/';
		if(!is_dir($root)) mkdir($root);
		return $root;
	}	
}

class plugin_defaultavatar_member extends plugin_defaultavatar{
	function register_input(){
		global $_G;
		$vars = $_G['cache']['plugin']['defaultavatar'];
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
		include template('defaultavatar:defaultavatar');
		return $return;		
	}
	
	function register_avatar($param){
		global $_G;
		if(submitcheck('regsubmit')&&!$_GET['avatar']){
			showmessage('defaultavatar:error_tip');
		}
	}
	
	function register_message($param){
		global $_G,$index;
		if(!$index) $index++;
		$uid=$param['param'][2]['uid'];
		if($_GET['avatar']&&$uid&&$index==1){
			$dir=sprintf("%09d",$uid);
			$root=$this->checkAvatarDir($dir);
			$this->createAvatar(DISCUZ_ROOT.'./source/plugin/defaultavatar/avatar/'.$_GET['avatar'],$root.substr($dir,-2).'_avatar_big.jpg','big');
			$this->createAvatar(DISCUZ_ROOT.'./source/plugin/defaultavatar/avatar/'.$_GET['avatar'],$root.substr($dir,-2).'_avatar_middle.jpg','middle');
			$this->createAvatar(DISCUZ_ROOT.'./source/plugin/defaultavatar/avatar/'.$_GET['avatar'],$root.substr($dir,-2).'_avatar_small.jpg','small');	
			DB::update('common_member',array('avatarstatus'=>1),array('uid'=>$uid));
			$index++;
		}
	}	
}

?>