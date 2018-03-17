<?php
/*
 * 主页：http://addon.discuz.com/?@ailab
 * 人工智能实验室：Discuz!应用中心十大优秀开发者！
 * 插件定制 联系QQ594941227
 * From www.ailab.cn
 */
 
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
		if($_G['setting']['whosonlinestatus']&&$detailstatus&&$membercount&&$num>0){//当在线会员列表显示的时候，增加$num个在线用户
			$data=C::t('#nimba_regs#nimba_member')->fetch_all_by_range(rand(0,10),$num);
			$now=time();
			$count=0;
			foreach($data as $k=>$user){
				$user['lastactivity']=date('H:i',rand($now-86400,$now));
				$user['icon']='online_member.gif';
				$whosonline[]=$user;
				$count++;
			}
			$membercount+=$count;
			$onlinenum+=$count;
			$onlineinfo[0]=$onlineinfo[0]>$onlinenum? $onlineinfo[0]:$onlinenum;
		}else{
			if($membercount) $membercount+=$num;
			if($onlinenum) $onlinenum+=$num;
			$onlineinfo[0]=$onlineinfo[0]>$onlinenum? $onlineinfo[0]:$onlinenum;
		}
?>