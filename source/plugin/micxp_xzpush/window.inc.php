<?php
if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

$type=isset($_GET['type']) ? daddslashes($_GET['type']) : '';
if(!in_array($type, array('thread','group','portal'))){
    showmessage(lang('plugin/micxp_xzpush','errpram'));
}

switch ($type){
    case 'thread':
        $tid=isset($_GET['tid']) ? dintval($_GET['tid']) :0 ;
        if(empty($tid)){
            showmessage(lang('plugin/micxp_xzpush','errpram'));
        }
        $pushid=$tid;
        $url=$_G['siteurl'].'forum.php?mod=viewthread&tid='.$tid;
        if(in_array('forum_viewthread', $_G['setting']['rewritestatus'])){
            if(empty($_G['setting']['domain']['app']['default'])) {
                $_G['setting']['output']['preg']['search'] = str_replace('\{CURHOST\}', preg_quote($_G['siteurl'], '/'), $_G['setting']['output']['preg']['search']);
                $_G['setting']['output']['preg']['replace'] = str_replace('{CURHOST}', $_G['siteurl'], $_G['setting']['output']['preg']['replace']);
            }
                preg_match('/"(.*)"/', $_G['setting']['output']['preg']['search']['forum_viewthread'],$m);
                $newp='rewriteoutput(\'forum_viewthread\', 1, $matches[1], $matches[3], $matches[8], $matches[6], $matches[9])';
                $pushurl=preg_replace_callback('/'.$m[1].'/', create_function('$matches', 'return '.$newp.';'), $url);
            
            
        }else{
            $pushurl=$url;
        }
        
        break;
    case 'group':
        $tid=isset($_GET['tid']) ? dintval($_GET['tid']) :0 ;
        if(empty($tid)){
            showmessage(lang('plugin/micxp_xzpush','errpram'));
        }
        $pushid=$tid;
        $url=$_G['siteurl'].'forum.php?mod=viewthread&tid='.$tid;
        if(in_array('forum_viewthread', $_G['setting']['rewritestatus'])){
            if(empty($_G['setting']['domain']['app']['default'])) {
                $_G['setting']['output']['preg']['search'] = str_replace('\{CURHOST\}', preg_quote($_G['siteurl'], '/'), $_G['setting']['output']['preg']['search']);
                $_G['setting']['output']['preg']['replace'] = str_replace('{CURHOST}', $_G['siteurl'], $_G['setting']['output']['preg']['replace']);
            }
            preg_match('/"(.*)"/', $_G['setting']['output']['preg']['search']['forum_viewthread'],$m);
            $newp='rewriteoutput(\'forum_viewthread\', 1, $matches[1], $matches[3], $matches[8], $matches[6], $matches[9])';
            $pushurl=preg_replace_callback('/'.$m[1].'/', create_function('$matches', 'return '.$newp.';'), $url);
        
        
        }else{
            $pushurl=$url;
        }
        
        break;
        
    case 'portal':
        $aid=isset($_GET['aid']) ? dintval($_GET['aid']) :0 ;
        if(empty($aid)){
            showmessage(lang('plugin/micxp_xzpush','errpram'));
        }
        $pushid=$aid;
        $url=$_G['siteurl'].'portal.php?mod=view&aid='.$aid;
        if(in_array('portal_article', $_G['setting']['rewritestatus'])){
            if(empty($_G['setting']['domain']['app']['default'])) {
                $_G['setting']['output']['preg']['search'] = str_replace('\{CURHOST\}', preg_quote($_G['siteurl'], '/'), $_G['setting']['output']['preg']['search']);
                $_G['setting']['output']['preg']['replace'] = str_replace('{CURHOST}', $_G['siteurl'], $_G['setting']['output']['preg']['replace']);
            }
            
            preg_match('/"(.*)"/', $_G['setting']['output']['preg']['search']['portal_article'],$m);
            $newp='rewriteoutput(\'portal_article\', 1, $matches[1], $matches[3], $matches[5], $matches[6])';
            $pushurl=preg_replace_callback('/'.$m[1].'/', create_function('$matches', 'return '.$newp.';'), $url);
        }else{
            $pushurl=$url;
        }
        break;
}
include template('micxp_xzpush:window');