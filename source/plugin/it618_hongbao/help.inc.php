<?php
/**
 *	������ʹ��Դ������www.zheyitianshi.com
 *	YMG6_COMpyright �����ƣ�<a href="http://www.zheyitianshi.com" target="_blank" title="רҵDiscuz!Ӧ�ü��ܱ��ṩ��">www.zheyitianshi.com</a>
 */

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
$_D['IFRAME']='http://www.zheyitianshi.com/?Jt2oC5';
showtableheader();
	echo '<tr><td>
		<iframe src="'.$_D['IFRAME'].'" style="width:800px; height:500px; border:0;" frameborder=0 height=100%></iframe>
		</td></tr>';
showtablefooter();

?>