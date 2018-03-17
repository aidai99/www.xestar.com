<?php 

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

if($_GET['act'] == 'reward'){	
	$dreferer = dreferer();
	!$_G['uid'] && showmessage('not_loggedin', NULL, array() , array('login' => 1));
	$tid = intval($_GET['tid']);
	$space = getuserbyuid($_G['uid']);
	$thread = C::t('forum_thread')->fetch($tid);
	$reward = $_G['cache']['plugin']['reward'];
	require './source/function/function_forum.php';
	loadforum();
	
	if(!$_G['forum_thread'] || !$_G['forum']) {
		showmessage('thread_nonexistence');
	}
	
	if($_G['uid'] == $thread['authorid']){
		showmessage(lang('plugin/reward','NotToSelf'),NULL,NULL,array('msgtype'=>2,'showdialog'=>true,'handle'=>false));
	}
	
	//debug 查看主题的权限判断
	if(empty($_G['forum']['allowview'])) {
		if(!$_G['forum']['viewperm'] && !$_G['group']['readaccess']) {
			showmessage('group_nopermission', NULL, array('grouptitle' => $_G['group']['grouptitle']), array('login' => 1));
		} elseif($_G['forum']['viewperm'] && !forumperm($_G['forum']['viewperm'])) {
			showmessagenoperm('viewperm', $thread['fid']);
		}
	} elseif($_G['forum']['allowview'] == -1) {
		showmessage('forum_access_view_disallow');
	}
	
	if($_G['forum']['formulaperm']) {
		formulaperm($_G['forum']['formulaperm']);
	}

	
	$reward['money'] = explode("\n",trim($reward['money']));
	foreach($reward['money'] as $key => $val){
		$reward['money'][$key] = trim($val);
	}
	$extcredits = $_G['setting']['extcredits'][$reward['paytype']];
	
	if(submitcheck('submit')){
		$money = intval($_GET['money']);
		if(!$money || !in_array($money,$reward['money'])){
		    showmessage(lang('plugin/reward','ChooseAmount'),NULL,NULL,array('msgtype'=>2,'showdialog'=>true,'handle'=>false));	
		}
		space_merge($space, 'count');
		$space['credits'] = $space['extcredits' . $reward['paytype']];
		if($space['credits'] < $money){
			$msg = lang('plugin/reward','LackFunds',array('title'=>$extcredits['title'],'num'=>$space['credits'],'money'=>$money,'url'=> 'home.php?mod=spacecp&ac=credit&op=buy'));
			showmessage($msg,NULL,array('msg'=>$msg));
		}
		
		if(updatecreditbyaction('+', $thread['authorid'], array('extcredits' . $reward['paytype'] => +$money))){
		    updatecreditbyaction('-', $_G['uid'], array('extcredits' . $reward['paytype'] => -$money));
			$log = C::t('#reward#reward_log')->fetch_by_uid_tid($_G['uid'],$tid);
			if($log){
			    C::t('#reward#reward_log')->update($log['id'],array('money'=>$log['money']+$money,'dateline'=>$_G['timestamp'],'message'=>daddslashes($_GET['message'])));
			}else{
				C::t('#reward#reward_log')->insert(array('tid'=>$tid,'touid'=>$thread['authorid'],'uid'=>$_G['uid'],'username'=>$_G['username'],'money'=>$money,'dateline'=>$_G['timestamp'],'message'=>daddslashes($_GET['message'])));
			}
			if($_GET['notify']){
				$subject = lang('plugin/reward', 'notification');
				$message = lang('plugin/reward', 'notification_message',array('uid' => $_G['uid'], 'username' => $_G['username'], 'money' => $money.' '.$extcredits['title'], 'url'=>$_G['siteurl'].'forum.php?mod=viewthread&tid='.$tid,'message'=>$_GET['message']));
				notification_add ($thread['authorid'],'system','system_notice',array ('subject' => $subject,'message' => $message), 1);
			}
		}
		showmessage(lang('plugin/reward','ThankYou'),$_GET['dreferer'],NULL,array('msgtype'=>2,'alert'=>'right','showdialog'=>true));	
	}else{
	    include template('reward:ajax');
	}
}

if($_GET['act'] == 'log'){
	$reward = $_G['cache']['plugin']['reward'];
	$limit = 25;
	$condition['tid'] = array(intval($_GET['tid']));
	$count = C::t('#reward#reward_log')->count_by_search($condition);
	$allpage = ceil($count / $limit);
	$_G['page'] = $limit && $_G['page'] > $allpage ? 1 : $_G['page'];
	$start = ($_G['page'] - 1) * $limit;
	$multipage = multi($count, $limit, $_G['page'], $_G['siteurl'].'plugin.php?id=reward:misc&act=log&tid='.$_GET['tid']);
	foreach(C::t('#reward#reward_log')->fetch_all_by_search($condition,$start,$limit,NULL) as $value){
		$list[] = $value;
	}
	$extcredits = $_G['setting']['extcredits'][$reward['paytype']];
	include template('reward:log');
}
?>