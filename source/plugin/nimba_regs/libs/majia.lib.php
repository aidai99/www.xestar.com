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
		if($_G['setting']['whosonlinestatus']&&$detailstatus&&$membercount&&$num>0){//�����߻�Ա�б���ʾ��ʱ������$num�������û�
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