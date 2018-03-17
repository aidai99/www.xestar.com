<?php
/**
 *	折翼天使资源社区：www.zheyitianshi.com
 *	YMG6_COMpyright 插件设计：<a href="http://www.zheyitianshi.com" target="_blank" title="专业Discuz!应用及周边提供商">www.zheyitianshi.com</a>
 */
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
global $it618_hongbao_lang;

function it618_hongbao_getlang($langid){
	global $it618_hongbao_lang;
	return $it618_hongbao_lang[$langid];
}

$it618_hongbao_lang['version']='v1.6';
$it618_hongbao_lang['s1'] = '抱歉，请先登录！';
$it618_hongbao_lang['s2'] = '抱歉，参数有误！';
$it618_hongbao_lang['s3'] = '抱歉，我现有的';
$it618_hongbao_lang['s4'] = '数量只有';
$it618_hongbao_lang['s5'] = '，不够用来发红包！';
$it618_hongbao_lang['s6'] = '抱歉，总金额/红包个数的平均值必须为整数，不能是小数！';
$it618_hongbao_lang['s7'] = '抱歉，总金额/红包个数的平均值必须是大于';
$it618_hongbao_lang['s8'] = '的整数！';
$it618_hongbao_lang['s9'] = '抱歉，您所在用户组没有抢红包权限！';
$it618_hongbao_lang['s10'] = '抱歉，红包已抢完！';
$it618_hongbao_lang['s11'] = '抱歉，您已抢过一次红包了，每个会员只能抢一次红包！';
$it618_hongbao_lang['s12'] = '抱歉，此红包是口令红包，需要您的回帖有口令内容，才可以抢红包，请先用口令回帖！';
$it618_hongbao_lang['s13'] = '恭喜您，抢到了一个价值 ';
$it618_hongbao_lang['s14'] = ' 的红包！';
$it618_hongbao_lang['s15'] = '拼手气';
$it618_hongbao_lang['s16'] = '普通';
$it618_hongbao_lang['s17'] = '红包个数：';
$it618_hongbao_lang['s18'] = '红包类型：';
$it618_hongbao_lang['it618'] = 'i11iiiilll1ililllilill1ilil11il111il111lilii111ill';
$it618_hongbao_lang['s19'] = '红包均价：';
$it618_hongbao_lang['s20'] = '红包口令：';
$it618_hongbao_lang['s21'] = '类型：';
$it618_hongbao_lang['s22'] = '抢到了一个价值';
$it618_hongbao_lang['s23'] = '的红包';
$it618_hongbao_lang['s24'] = '抢到了一个';
$it618_hongbao_lang['s25'] = '记录数：';
$it618_hongbao_lang['s26'] = '上一页';
$it618_hongbao_lang['s27'] = '下一页';
$it618_hongbao_lang['s28'] = '抱歉，这些主题(主题编号：';
$it618_hongbao_lang['s29'] = ')有红包不能删除！可以先在后台删除红包，再删除主题。';
$it618_hongbao_lang['s30'] = '抱歉，此主题有红包不能删除！';
$it618_hongbao_lang['s31'] = '红包';
$it618_hongbao_lang['s32'] = '余额：';
$it618_hongbao_lang['s33'] = '还有';
$it618_hongbao_lang['s34'] = '个';
$it618_hongbao_lang['s35'] = '管理红包';
$it618_hongbao_lang['s36'] = '发红包';
$it618_hongbao_lang['s37'] = '抱歉，您所在的用户组没有发红包的权限！';
$it618_hongbao_lang['s38'] = '抱歉，此帖子已发红包了，请刷新页面管理红包！';
$it618_hongbao_lang['s39'] = '添加金额：';
$it618_hongbao_lang['s40'] = '我现有';
$it618_hongbao_lang['s41'] = '剩余金额：';
$it618_hongbao_lang['s42'] = '剩余个数：';
$it618_hongbao_lang['s43'] = '红包金额：';
$it618_hongbao_lang['s44'] = '红包个数：';
$it618_hongbao_lang['s45'] = '添加个数：';
$it618_hongbao_lang['s46'] = '拼手气红包';
$it618_hongbao_lang['s47'] = '普通红包';
$it618_hongbao_lang['s48'] = '提示：如果是拼手气红包金额是随机的';
$it618_hongbao_lang['s49'] = '抱歉，红包总金额不能小于';
$it618_hongbao_lang['s50'] = '抱歉，红包总金额不能大于';
$it618_hongbao_lang['s51'] = '天前';
$it618_hongbao_lang['s52'] = '小时前';
$it618_hongbao_lang['s53'] = '分钟前';
$it618_hongbao_lang['s54'] = '秒前';
$it618_hongbao_lang['s55'] = '口令保密';
$it618_hongbao_lang['s56'] = '删除红包';
$it618_hongbao_lang['s57'] = '抱歉，您没有删除红包的权限！';
$it618_hongbao_lang['s58'] = '红包删除成功！';
$it618_hongbao_lang['s59'] = '红包口令给帖子主人设置成保密的，请您想办法找到口令！';
$it618_hongbao_lang['s60'] = '<font color=red>{hbtypename}</font> 已有<font color=green><b>{usercount}</b></font>个会员抢到了红包，还剩余<font color=red><b>{hbcount}</b></font>个红包，总金额为<font color=red><b>{hbmoney}</b></font>{creditname}';
$it618_hongbao_lang['s61'] = '已有';
$it618_hongbao_lang['s62'] = '个会员抢到了红包';
$it618_hongbao_lang['s63'] = '抱歉，红包金额或个数要大于0的整数！';
$it618_hongbao_lang['s64'] = '抱歉，当前版块没有发红包的权限！';


//it618_hongbao_lang(\d+)} it618_hongbao_lang['t$1']}
$it618_hongbao_lang['t1'] = '红包口令：';
$it618_hongbao_lang['t2'] = '注意：红包口令可以自由修改，也可以不需要口令，如果有口令，抢红包前必须先回帖，帖子内容必须包含口令。 从上次修改时间开始算起，超过有效时间自动归还剩余红包金额！';
$it618_hongbao_lang['t3'] = '确定';
$it618_hongbao_lang['t4'] = '取消';
$it618_hongbao_lang['t5'] = '抱歉，金额必须为大于0的整数！';
$it618_hongbao_lang['t6'] = '抱歉，个数必须为大于0的整数！';
$it618_hongbao_lang['t7'] = '抱歉，请选择红包积分类型！';
$it618_hongbao_lang['t8'] = '抱歉，红包口令字数至少2个！';
$it618_hongbao_lang['t9'] = '抱歉，红包口令字数最多68个！';
$it618_hongbao_lang['t10'] = '发红包成功！';
$it618_hongbao_lang['t11'] = '红包修改成功！';
$it618_hongbao_lang['t12'] = '您输入了';
$it618_hongbao_lang['t13'] = '个字！';
$it618_hongbao_lang['t14'] = '抱歉，有效时间必须为大于12的整数！';
$it618_hongbao_lang['t15'] = '有效时间：';
$it618_hongbao_lang['t16'] = '小时';
$it618_hongbao_lang['t17'] = '确定要删除此红包吗，此操作不可逆？删除后自动归还红包剩余金额，同时抢红包记录也会删除！';
$it618_hongbao_lang['t18'] = '上次修改时间：';
$it618_hongbao_lang['t19'] = '已有';
$it618_hongbao_lang['t20'] = '现在发帖时发红包';
$it618_hongbao_lang['t21'] = '需要时再在帖子内发红包';

$YMG6_COMpyright='插件设计：<a href="http://www.zheyitianshi.com" target="_blank" title="专业Discuz!应用及周边提供商">www.zheyitianshi.com</a>';

?>