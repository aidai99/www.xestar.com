<?php
/**
 *	������ʹ��Դ������www.zheyitianshi.com
 *	YMG6_COMpyright �����ƣ�<a href="http://www.zheyitianshi.com" target="_blank" title="רҵDiscuz!Ӧ�ü��ܱ��ṩ��">www.zheyitianshi.com</a>
 */
 
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
global $IsCredits;

if(DB::result_first("select count(1) from ".DB::table('common_plugin')." where identifier='it618_credits'")>0){
	$IsCredits=1;
}

if($_SERVER['HTTP_HOST']=='localhost'){
	$language = 'language.php';
}else{
	$language = 'language.'.currentlang().'.php';
}

require_once DISCUZ_ROOT.'./source/plugin/it618_hongbao/'.$language;
?>