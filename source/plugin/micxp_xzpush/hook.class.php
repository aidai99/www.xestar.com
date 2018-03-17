<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class plugin_micxp_xzpush {

}

class plugin_micxp_xzpush_forum extends plugin_micxp_xzpush{
    
    function viewthread_postfooter_output(){
        global $_G, $postlist;
        $micxp_settgin=$_G['cache']['plugin']['micxp_xzpush'];
        $fids = (array)unserialize($micxp_settgin['M_forums']);
        $gids = (array)unserialize($micxp_settgin['M_groups']);
        if (!in_array($_G['fid'], $fids) || !in_array($_G['groupid'], $gids)) {
            return ;
        }
        $return=array();
        foreach($postlist as $k => $post) {
            if($post['first'] == 1 && $_G['page']==1) {
                $return[]='<a class="xzpush" style="background: url(source/plugin/micxp_xzpush/static/images/xz.png) no-repeat 4px 50%;
}" href="javascript:;" onclick="showWindow(\'xzpush\', \'plugin.php?id=micxp_xzpush:window&type=thread&tid='.$post[tid].'\', \'get\', 0)">'.lang('plugin/micxp_xzpush','xzpush').'</a>';
            } else{
                $return[]='';
            } 
        }
        return $return;
    }
    
}
class plugin_micxp_xzpush_group extends plugin_micxp_xzpush{
    
    function viewthread_postfooter_output(){
        
        global $_G, $postlist;
        $micxp_settgin=$_G['cache']['plugin']['micxp_xzpush'];
        if($micxp_settgin['M_isgroup']){
            
            $gids = (array)unserialize($micxp_settgin['M_groups']);
            if (!in_array($_G['groupid'], $gids)) {
                return ;
            }
            $return=array();
            foreach($postlist as $k => $post) {
                if($post['first'] == 1 && $_G['page']==1) {
                    $return[]='<a class="xzpush" style="background: url(source/plugin/micxp_xzpush/static/images/xz.png) no-repeat 4px 50%;
    }" href="javascript:;" onclick="showWindow(\'xzpush\', \'plugin.php?id=micxp_xzpush:window&type=group&tid='.$post[tid].'\', \'get\', 0)">'.lang('plugin/micxp_xzpush','xzpush').'</a>';
                } else{
                    $return[]='';
                }
            }
            return $return;
        }
    }
    
}

class plugin_micxp_xzpush_portal extends plugin_micxp_xzpush{
    
    function view_article_subtitle(){
        global $_G;
        $micxp_settgin=$_G['cache']['plugin']['micxp_xzpush'];
        if($micxp_settgin['M_isportal']){
            $micxp_settgin['M_uid']=trim($micxp_settgin['M_uid']);
            $uids=explode(',', $micxp_settgin['M_uid']);
            $uids=array_filter($uids);
            $uids=array_unique(array_filter);
            if($_G['adminid']==1 || in_array( $_G['uid'], $uids)){
                
               return '<span class="pipe">|</span><a class="xzpush" href="javascript:;" onclick="showWindow(\'xzpush\', \'plugin.php?id=micxp_xzpush:window&type=portal&aid='.$_GET[aid].'\', \'get\', 0)">'.lang('plugin/micxp_xzpush','xzpush').'</a>';
            }
            
            
        }
        
    }
}

?>