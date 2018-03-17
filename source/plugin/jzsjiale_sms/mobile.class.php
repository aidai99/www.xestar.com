<?php


if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class mobileplugin_jzsjiale_sms {
   
    function common() {
        global $_G;
        $_config = $_G['cache']['plugin']['jzsjiale_sms'];
        
        $userinfo = array();
        $istiaozhuan = false;
        
        
        
        if($_config['g_tongyiuser']){
            //20170805 add start
            $userinfo =  C::t('#jzsjiale_sms#jzsjiale_sms_member')->fetch_by_uid($_G['uid']);
        
            if(empty($userinfo['mobile'])){
                $istiaozhuan = true;
            }
            //20170805 add end
        }else{
            $userinfo = C::t('#jzsjiale_sms#jzsjiale_sms_user')->fetch_by_uid($_G['uid']);
        
            if(!$userinfo){
                $istiaozhuan = true;
            }
        }
  
        $groupid = $_G['groupid'];
        $basescript = $_G['basescript'];
        if ($_G['uid'] && $_config['g_mqiangzhibangding'] && in_array($groupid, (array) unserialize($_config['g_qiangzhigroups'])) && $istiaozhuan && ($basescript == "portal" || $basescript == "forum" || $basescript == "group" )){
            showmessage($_config['g_mqiangzhitip'], 'plugin.php?id=jzsjiale_sms:mobile&act=bangding&qiangzhibangding=yes', 'succeed');
             
        }
    }
}

class mobileplugin_jzsjiale_sms_member extends mobileplugin_jzsjiale_sms
{
    function register()
    {
        global $_G;
        $_config = $_G['cache']['plugin']['jzsjiale_sms'];
      
        $formhash =  addslashes($_GET['formhash'])? addslashes($_GET['formhash']):'';
        
        if($formhash == FORMHASH){
            return;
        }
        if(!$_config['g_openmobileregister']){
            return;
        }
        
        //member.php?mod=register&mobile=2&phoneregister=no
        // && $_GET["phoneregister"] != "no"
        if((isset($_GET["mobile"]) || $this->is_mobile())) {
            $url ="plugin.php?id=jzsjiale_sms:mobile&act=register";
            header("Location: $url");
            die(0);
            
        }
    }
    
    function logging()
    {
        global $_G;
        $_config = $_G['cache']['plugin']['jzsjiale_sms'];
    
        if($_G['setting']['bbclosed']){
            return;
        }
        
        if(!$_config['g_openmobilelogin']){
            return;
        }
      
        if((isset($_GET["mobile"]) || $this->is_mobile()) && $_config['g_mmorenphonelogin'] && isset($_GET["action"]) && $_GET["action"]=="login" && $_GET["phonelogin"] != "no" && $_GET["loginsubmit"] != "yes" && $_GET["inajax"] != "yes") {
            $url ="plugin.php?id=jzsjiale_sms:mobile&act=login";
            header("Location: $url");
            die(0);
        }else{
            return;
        }
    }
    
    
    function logging_bottom_mobile() {
        global $_G;
        $_config = $_G['cache']['plugin']['jzsjiale_sms'];
    
        if (!$_config['g_openmobilelogin']){
            return "";
        }
        if (!$_config['g_isopenmobileloginbtn']){
            return "";
        }
        include_once template('jzsjiale_sms:loginbtn');
        return $loginbtn;
    }
    
    function is_mobile() {
        if ( empty($_SERVER['HTTP_USER_AGENT']) ) {
            $is_mobile = false;
        } elseif ( strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile') !== false // many mobile devices (all iPhone, iPad, etc.)
            || strpos($_SERVER['HTTP_USER_AGENT'], 'Android') !== false
            || strpos($_SERVER['HTTP_USER_AGENT'], 'Silk/') !== false
            || strpos($_SERVER['HTTP_USER_AGENT'], 'Kindle') !== false
            || strpos($_SERVER['HTTP_USER_AGENT'], 'BlackBerry') !== false
            || strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mini') !== false
            || strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mobi') !== false ) {
            $is_mobile = true;
        } else {
            $is_mobile = false;
        }
    
        return $is_mobile;
    }
    
   
}

?>