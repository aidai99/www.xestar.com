<?php 

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

/*嵌入点类*/
class plugin_reward {
	
	function  __construct() {
		
	}
}

class plugin_reward_forum extends plugin_reward{
	 
	function viewthread_useraction() {
		global $_G;
        $reward = $_G['cache']['plugin']['reward'];
		$reward['plate'] = unserialize($reward['plate']);
		$item;
		if($_G['fid'] && in_array($_G['fid'], $reward['plate'])){
			$extcredits = $_G['setting']['extcredits'][$reward['paytype']];
			$condition['tid'] = array(intval($_GET['tid']));
			$count = C::t('#reward#reward_log')->count_by_search($condition);
			foreach(C::t('#reward#reward_log')->fetch_all_by_search($condition,0,9) as $value){
				$item .= '<a href="home.php?mod=space&uid='.$value['uid'].'&do=profile" target="_blank" title="'.$value['username'].'"><dl><p>'.$extcredits['title'].'</p><b>'.$value['money'].'</b></dl>'.avatar($value['uid'],'small').'</a>';
			}
			//$bgcolor = $reward['bgcolor'] ? $reward['bgcolor'] : '#FCAD30';
			//$fontcolor = $reward['fontcolor'] ? $reward['fontcolor'] : '#FFF';
			include 'template/reward.htm';
			
			return $html;
		}
	}
	
}

/*嵌入点类*/
class mobileplugin_reward {
	
	function  __construct() {
		
	}
}

class mobileplugin_reward_forum extends mobileplugin_reward{
	
	function viewthread_postbottom_mobile() {
		global $_G;
		$reward = $_G['cache']['plugin']['reward'];
		$reward['plate'] = unserialize($reward['plate']);

		if($_G['fid'] && in_array($_G['fid'], $reward['plate'])){
			$extcredits = $_G['setting']['extcredits'][$reward['paytype']];
			$condition['tid'] = array(intval($_GET['tid']));
			$count = C::t('#reward#reward_log')->count_by_search($condition);
			foreach(C::t('#reward#reward_log')->fetch_all_by_search($condition,0,5) as $value){
				$item .= '<a href="home.php?mod=space&uid='.$value['uid'].'&do=profile" title="'.$value['username'].'">'.avatar($value['uid'],'small').'</a>';
			}
			if($count){
			    $item .= '<a href="plugin.php?id=reward:misc&act=log&tid='.$_GET['tid'].'" class="log">'.lang('plugin/reward','RewardLog').'</a>';	
			}
			$bgcolor = $reward['bgcolor'] ? $reward['bgcolor'] : '#FCAD30';
			$fontcolor = $reward['fontcolor'] ? $reward['fontcolor'] : '#FFF';
			include 'template/touch/reward.htm';
			return $html;
		}
	}
	
}
?>