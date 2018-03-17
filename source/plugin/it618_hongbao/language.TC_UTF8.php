<?php
/**
 *	折翼天使资源社区：www.zheyitianshi.com
 *	YMG6_COMpyright 插件設計：<a href="http://www.zheyitianshi.com" target="_blank" title="專業Discuz!應用及周邊提供商">www.zheyitianshi.com</a>
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
$it618_hongbao_lang['s1'] = '抱歉，請先登錄！';
$it618_hongbao_lang['s2'] = '抱歉，參數有誤！';
$it618_hongbao_lang['s3'] = '抱歉，我現有的';
$it618_hongbao_lang['s4'] = '數量只有';
$it618_hongbao_lang['s5'] = '，不夠用來發紅包！';
$it618_hongbao_lang['s6'] = '抱歉，總金額/紅包個數的平均值必須為整數，不能是小數！';
$it618_hongbao_lang['s7'] = '抱歉，總金額/紅包個數的平均值必須是大于';
$it618_hongbao_lang['s8'] = '的整數！';
$it618_hongbao_lang['s9'] = '抱歉，您所在用戶組沒有搶紅包權限！';
$it618_hongbao_lang['s10'] = '抱歉，紅包已搶完！';
$it618_hongbao_lang['s11'] = '抱歉，您已搶過一次紅包了，每個會員只能搶一次紅包！';
$it618_hongbao_lang['s12'] = '抱歉，此紅包是口令紅包，需要您的回帖有口令內容，才可以搶紅包，請先用口令回帖！';
$it618_hongbao_lang['s13'] = '恭喜您，搶到了一個價值 ';
$it618_hongbao_lang['s14'] = ' 的紅包！';
$it618_hongbao_lang['s15'] = '拼手氣';
$it618_hongbao_lang['s16'] = '普通';
$it618_hongbao_lang['s17'] = '紅包個數：';
$it618_hongbao_lang['s18'] = '紅包類型：';
$it618_hongbao_lang['it618'] = 'i11iiiilll1ililllilill1ilil11il111il111lilii111ill';
$it618_hongbao_lang['s19'] = '紅包均價：';
$it618_hongbao_lang['s20'] = '紅包口令：';
$it618_hongbao_lang['s21'] = '類型：';
$it618_hongbao_lang['s22'] = '搶到了一個價值';
$it618_hongbao_lang['s23'] = '的紅包';
$it618_hongbao_lang['s24'] = '搶到了一個';
$it618_hongbao_lang['s25'] = '記錄數：';
$it618_hongbao_lang['s26'] = '上一頁';
$it618_hongbao_lang['s27'] = '下一頁';
$it618_hongbao_lang['s28'] = '抱歉，這些主題(主題編號：';
$it618_hongbao_lang['s29'] = ')有紅包不能刪除！可以先在后臺刪除紅包，再刪除主題。';
$it618_hongbao_lang['s30'] = '抱歉，此主題有紅包不能刪除！';
$it618_hongbao_lang['s31'] = '紅包';
$it618_hongbao_lang['s32'] = '余額：';
$it618_hongbao_lang['s33'] = '還有';
$it618_hongbao_lang['s34'] = '個';
$it618_hongbao_lang['s35'] = '管理紅包';
$it618_hongbao_lang['s36'] = '發紅包';
$it618_hongbao_lang['s37'] = '抱歉，您所在的用戶組沒有發紅包的權限！';
$it618_hongbao_lang['s38'] = '抱歉，此帖子已發紅包了，請刷新頁面管理紅包！';
$it618_hongbao_lang['s39'] = '添加金額：';
$it618_hongbao_lang['s40'] = '我現有';
$it618_hongbao_lang['s41'] = '剩余金額：';
$it618_hongbao_lang['s42'] = '剩余個數：';
$it618_hongbao_lang['s43'] = '紅包金額：';
$it618_hongbao_lang['s44'] = '紅包個數：';
$it618_hongbao_lang['s45'] = '添加個數：';
$it618_hongbao_lang['s46'] = '拼手氣紅包';
$it618_hongbao_lang['s47'] = '普通紅包';
$it618_hongbao_lang['s48'] = '提示：如果是拼手氣紅包金額是隨機的';
$it618_hongbao_lang['s49'] = '抱歉，紅包總金額不能小于';
$it618_hongbao_lang['s50'] = '抱歉，紅包總金額不能大于';
$it618_hongbao_lang['s51'] = '天前';
$it618_hongbao_lang['s52'] = '小時前';
$it618_hongbao_lang['s53'] = '分鐘前';
$it618_hongbao_lang['s54'] = '秒前';
$it618_hongbao_lang['s55'] = '口令保密';
$it618_hongbao_lang['s56'] = '刪除紅包';
$it618_hongbao_lang['s57'] = '抱歉，您沒有刪除紅包的權限！';
$it618_hongbao_lang['s58'] = '紅包刪除成功！';
$it618_hongbao_lang['s59'] = '紅包口令給帖子主人設置成保密的，請您想辦法找到口令！';
$it618_hongbao_lang['s60'] = '<font color=red>{hbtypename}</font> 已有<font color=green><b>{usercount}</b></font>個會員搶到了紅包，還剩余<font color=red><b>{hbcount}</b></font>個紅包，總金額為<font color=red><b>{hbmoney}</b></font>{creditname}';
$it618_hongbao_lang['s61'] = '已有';
$it618_hongbao_lang['s62'] = '個會員搶到了紅包';
$it618_hongbao_lang['s63'] = '抱歉，紅包金額或個數要大于0的整數！';
$it618_hongbao_lang['s64'] = '抱歉，當前版塊沒有發紅包的權限！';


//it618_hongbao_lang(\d+)} it618_hongbao_lang['t$1']}
$it618_hongbao_lang['t1'] = '紅包口令：';
$it618_hongbao_lang['t2'] = '注意：紅包口令可以自由修改，也可以不需要口令，如果有口令，搶紅包前必須先回帖，帖子內容必須包含口令。 從上次修改時間開始算起，超過有效時間自動歸還剩余紅包金額！';
$it618_hongbao_lang['t3'] = '確定';
$it618_hongbao_lang['t4'] = '取消';
$it618_hongbao_lang['t5'] = '抱歉，金額必須為大于0的整數！';
$it618_hongbao_lang['t6'] = '抱歉，個數必須為大于0的整數！';
$it618_hongbao_lang['t7'] = '抱歉，請選擇紅包積分類型！';
$it618_hongbao_lang['t8'] = '抱歉，紅包口令字數至少2個！';
$it618_hongbao_lang['t9'] = '抱歉，紅包口令字數最多68個！';
$it618_hongbao_lang['t10'] = '發紅包成功！';
$it618_hongbao_lang['t11'] = '紅包修改成功！';
$it618_hongbao_lang['t12'] = '您輸入了';
$it618_hongbao_lang['t13'] = '個字！';
$it618_hongbao_lang['t14'] = '抱歉，有效時間必須為大于12的整數！';
$it618_hongbao_lang['t15'] = '有效時間：';
$it618_hongbao_lang['t16'] = '小時';
$it618_hongbao_lang['t17'] = '確定要刪除此紅包嗎，此操作不可逆？刪除后自動歸還紅包剩余金額，同時搶紅包記錄也會刪除！';
$it618_hongbao_lang['t18'] = '上次修改時間：';
$it618_hongbao_lang['t19'] = '已有';
$it618_hongbao_lang['t20'] = '現在發帖時發紅包';
$it618_hongbao_lang['t21'] = '需要時再在帖子內發紅包';

$YMG6_COMpyright='插件設計：<a href="http://www.zheyitianshi.com" target="_blank" title="專業Discuz!應用及周邊提供商">www.zheyitianshi.com</a>';

?>