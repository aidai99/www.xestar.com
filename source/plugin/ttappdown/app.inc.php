<?php


if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
         global $_G;
        $setting=$_G['cache']['plugin']['ttappdown'];
		  $info=$setting['info'];
		  $logo=$setting['logo'];
		  $title=$setting['title'];
		  $version=$setting['version'];
		  $android=$setting['android'];
		  $ios=$setting['ios'];
		  $code=$setting['code'];
		  $codetitle=$setting['codetitle'];
		  $copyright=$setting['copyright'];
		  $img1=$setting['img1'];
		  $img2=$setting['img2'];
		  $img3=$setting['img3'];
		  $img4=$setting['img4'];
		  $url=$_G['siteurl'];
		  $color=$setting['color'];;
		  if($info){
			 include template('ttappdown:down'); 
		  }  
?>