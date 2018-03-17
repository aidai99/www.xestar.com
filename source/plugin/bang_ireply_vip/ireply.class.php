<?php
/*
	普通商业版
*/
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}


class plugin_bang_ireply_vip {

	var $value = array();

	function global_footer() {
		global $_G;
		include DISCUZ_ROOT.'./data/plugindata/bang_ireply_time.inc.php';
		$irp_config=daddslashes($_G['cache']['plugin']['bang_ireply_vip']);
		$h=date(H);
		$t=time();
		$irjg=$irp_config['irjg']*60;
		if (!isset($pretime)) {
		   $pretime=0;
		}

		if($irp_config['switch'] and ($t-$pretime)>$irjg and $h<$irp_config['time_max'] and $h>$irp_config['time_min'] and function_exists('insertpost'))
		//if($irp_config['switch'] and function_exists('insertpost'))
		{
			$fidCount=8;
			$fp=fopen(DISCUZ_ROOT.'./data/plugindata/bang_ireply_time.inc.php','wb');
			if ($fp)
			{
				fwrite($fp,'<?php $pretime='.$t.' ?>');
			}
			fclose($fp);
			$irp_extctype=$irp_config['extctype']?$irp_config['extctype']:1;
			$irp_extcnum=$irp_config['extcnum']?$irp_config['extcnum']:1;
			$irp_t_num=($irp_config['it_min']&&$irp_config['it_max'])?rand($irp_config['it_min'],$irp_config['it_max']):1;
			$irp_t_time=$irp_config['ir_time']?$irp_config['ir_time']:1800;
			$irp_i_sign=$irp_config['i_sign']?$irp_config['i_sign']:1;
			$irp_i_ubb=$irp_config['i_ubb']?$irp_config['i_ubb']:0;
			$irp_r_num=($irp_config['ir_min']&&$irp_config['ir_max'])?rand($irp_config['ir_min'],$irp_config['ir_max']):1;
			$irp_u_num=$irp_t_num*$irp_r_num;
			$t_views=$irp_config['click_max']?rand($irp_config['click_min']+1,$irp_config['click_max']):1;
			$irp_timestamp=TIMESTAMP;

			$irp_members=array();
			if($irp_config['user_id']){
				$irp_config['user_id']=trim(trim($irp_config['user_id']),",");
				$irp_u_limited="where uid in (".$irp_config['user_id'].")";
				//echo "SELECT uid,username FROM ".DB::table('common_member')." {$irp_u_limited} order by rand() limit ".$irp_u_num;

				$irp_query=DB::query("SELECT uid,username FROM ".DB::table('common_member')." {$irp_u_limited} order by rand()");
				while($irp_mem=DB::fetch($irp_query)){
					$irp_members[$irp_mem['uid']]=addslashes($irp_mem['username']);
					//echo $irp_mem['uid']."---".$irp_mem['username']."<br>";
				}

			}else{
				$out=preg_replace('/\\\"/','"',$irp_config['iu_groups']);
				$irp_limitusergroup=unserialize($out);
				foreach ($irp_limitusergroup as $irp_value){
					$irp_ug_limited = $irp_ug_limited ? $irp_ug_limited.','.$irp_value : $irp_value;
				}
				$irp_u_limited=$irp_ug_limited?" where groupid IN (".$irp_ug_limited.")":"";



				$irp_query=DB::query("SELECT uid,username FROM ".DB::table('common_member')." {$irp_u_limited} order by rand() limit ".$irp_u_num);
				while($irp_mem=DB::fetch($irp_query)){
					$irp_members[$irp_mem['uid']]=addslashes($irp_mem['username']);
				}

			}






			$irp_threads=array();

			$t_date_limit="";
			if($irp_config['in_date'])
			{
				$t_date_limit=$irp_config['iq_data']?" AND (dateline>".($irp_timestamp-$irp_config['in_date']*86400)." or dateline<".($irp_timestamp-$irp_config['iq_data']*86400).")":" AND dateline>".($irp_timestamp-$irp_config['in_date']*86400);

			}
			else
			{
				$t_date_limit=$irp_config['iq_data']?" AND dateline<".($irp_timestamp-$irp_config['iq_data']*86400):"";
			}
			$i_post_max=$irp_config['i_post_max']?" AND replies<".$irp_config['i_post_max']:"";

			$out=preg_replace('/\\\"/','"',$irp_config['i_forums']);
			$irp_limitforum=unserialize($out);
			$fidList="";
			foreach ($irp_limitforum as $v){
				if($fidList==""){
					$fidList="$v";
				}else{
					$fidList=$fidList.",".$v;
				}
			}

			if($irp_config['tid_id']){
				$irp_config['tid_id']=trim(trim($irp_config['tid_id']),",");
				//$thr_query=DB::query("SELECT tid,fid FROM ".DB::table('forum_thread')." where tid in (".$irp_config['tid_id'].")");
				$irp_forum_limited = " AND tid in (".$irp_config['tid_id'].")";
			}else{
				$irp_forum_limited = " AND fid IN (".$fidList.")";
			}
			$db_i_orderby="";
			switch ($irp_config['i_orderby']) {
			  case '1':
				$db_i_orderby=" order by rand()";
				break;
			  case '2':
				$db_i_orderby=" order by tid desc";
				break;
			  case '3':
				$db_i_orderby=" order by replies asc";
				break;
			  case '4':
				$db_i_orderby=" order by views asc";
				break;
			  default:
				$db_i_orderby=" order by rand()";
				break;
			}
			//exit("SELECT tid,fid FROM ".DB::table('forum_thread')." where closed=0 {$i_post_max} {$t_date_limit} {$irp_forum_limited} {$db_i_orderby} limit ".$irp_t_num);
			$thr_query=DB::query("SELECT tid,fid FROM ".DB::table('forum_thread')." where closed=0 {$i_post_max} {$t_date_limit} {$irp_forum_limited} {$db_i_orderby} limit ".$irp_t_num);

			while($irp_thr=DB::fetch($thr_query)){
				$irp_threads[$irp_thr['tid']]=$irp_thr['fid'];
				//echo '<br>tid='.$irp_thr['tid'].'-----------fid='.$irp_thr['fid'];
			}




			$irp_postdatas=array();
			$irp_postdatas= preg_split('/[\r\n]+/', $irp_config['list_post']);

			//exit();
			foreach($irp_threads as $irp_tid=>$irp_fid)
			{
				$irp_nums=array();
				if($irp_r_num>1)
				{$irp_nums=array_rand($irp_postdatas,$irp_r_num);}
				else
				{$irp_nums[]=array_rand($irp_postdatas);}
				foreach($irp_nums as $irp_num)
				{
					$irp_postdata=NULL;
					$irp_postdata=addslashes($irp_postdatas[$irp_num]);
					$irp_timestamp=TIMESTAMP-rand(0,$irp_t_time);
					$irp_uid=array_rand($irp_members);
					$irp_user=$irp_members[$irp_uid];
					$irp_subject=NULL;
					$irp_useip=rand(1,255).".".rand(956,202).".".rand(100,135).".".rand(1,200);
					$pid = insertpost(array('tid' => $irp_tid,'first' => '0','author' => $irp_user,'authorid' => $irp_uid,'subject' => $irp_subject,'dateline' => $irp_timestamp,'message' => $irp_postdata,'useip' => $irp_useip,'invisible' => '0','anonymous' => '0','usesig' => $irp_i_sign,'htmlon' => '0','bbcodeoff' => $irp_i_ubb,'smileyoff' => '0','parseurloff' => '0','attachment' => '0',));
					$irp_query=DB::query("SELECT tid,subject FROM ".DB::table('forum_thread')." where tid='$irp_tid'");
					$irp_thread=DB::fetch($irp_query);
					$irp_lastpost = "$irp_thread[tid]\t".addslashes($irp_thread['subject'])."\t$irp_timestamp\t$irp_user";
					updatemembercount($irp_uid, array($irp_extctype => $irp_extcnum));
					DB::query("UPDATE ".DB::table('common_member_count')." SET posts=posts+1 WHERE uid='$irp_uid'", 'UNBUFFERED');
					DB::query("UPDATE ".DB::table('common_member_status')." SET lastip='$irp_useip',lastvisit='$irp_timestamp',lastactivity='$irp_timestamp',lastpost='$irp_timestamp' WHERE uid='$irp_uid'", 'UNBUFFERED');
					DB::query("UPDATE ".DB::table('forum_forum')." SET posts=posts+1,todayposts=todayposts+1,lastpost='$irp_lastpost' WHERE fid='$irp_fid'", 'UNBUFFERED');
					DB::query("UPDATE ".DB::table('forum_thread')." SET replies=replies+1,views=views+'$t_views',lastposter='$irp_user', lastpost='$irp_timestamp' WHERE tid='$irp_tid'", 'UNBUFFERED');
				}
			}
		}
	}

}



?>