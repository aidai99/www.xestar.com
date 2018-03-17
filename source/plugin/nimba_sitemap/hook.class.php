<?php
/*
 * ��ҳ��http://addon.discuz.com/?@ailab
 * �˹�����ʵ���ң�Discuz!Ӧ������ʮ�����㿪���ߣ�
 * ������� ��ϵQQ594941227
 * From www.ailab.cn
 */
 
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
include 'libs/sitemap.dev.php';
class plugin_nimba_sitemap {
 	function global_footer(){
	    loadcache('plugin');
		global $_G;
		$var = $_G['cache']['plugin']['nimba_sitemap'];
		$auto =$var['auto'];
		if($auto==1){
			$date=intval($var['cycle']);
			$date=empty($date)? 864000:$date;
			$time=time();
			@require_once DISCUZ_ROOT.'./source/discuz_version.php';
			if(DISCUZ_VERSION=='X2'){
				$filepath=DISCUZ_ROOT.'./data/cache/cache_nimba_sitemap_log.php';
			}else{
				$filepath=DISCUZ_ROOT.'./data/sysdata/cache_nimba_sitemap_log.php';
			}
			if(file_exists($filepath)){
				@require_once $filepath;
				if(($time-intval($last))>$date){//���� ��ʼ�Զ����µ�ͼ
					$data=sitemap_auto();
				}
			}else{//�½���ͼ
				$data=sitemap_auto();
			}
        }
	}
	function global_footerlink() {
	    loadcache('plugin');
		global $_G;
		$link= $_G['cache']['plugin']['nimba_sitemap']['link'];
		if($link){
			$title=lang('plugin/nimba_sitemap','appname');	
			return '<span class="pipe">|</span><a href="sitemap.xml" target="_blank" title="'.$title.'">'.$title.'</a>';
		}
	}	
} 
?>