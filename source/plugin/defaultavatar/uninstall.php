<?php
/*
 * Ӧ��������ҳ��http://addon.discuz.com/?@ailab
 * �˹�����ʵ���ң�Discuz!Ӧ������ʮ�����㿪���ߣ�
 * ������� ��ϵQQ594941227
 * From www.ailab.cn
 */
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
C::t('common_setting')->delete('defaultavatar');
updatecache('setting');
if(file_exists(DISCUZ_ROOT.'./data/sysdata/cache_defaultavatar_config.php')){
	@unlink(DISCUZ_ROOT.'./data/sysdata/cache_defaultavatar_config.php');
}
$finish = TRUE;
?>