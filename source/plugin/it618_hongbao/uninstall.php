<?php
/**
 *	������ʹ��Դ������www.zheyitianshi.com
 *	YMG6_COMpyright �����ƣ�<a href="http://www.zheyitianshi.com" target="_blank" title="רҵDiscuz!Ӧ�ü��ܱ��ṩ��">www.zheyitianshi.com</a>
 */
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

DB::query("DROP TABLE IF EXISTS pre_it618_hongbao_main");
DB::query("DROP TABLE IF EXISTS pre_it618_hongbao_item");
DB::query("DROP TABLE IF EXISTS pre_it618_hongbao_user");
DB::query("DROP TABLE IF EXISTS pre_it618_hongbao_findtid");

//DEFAULT CHARSET=gbk;
$finish = TRUE;
?>