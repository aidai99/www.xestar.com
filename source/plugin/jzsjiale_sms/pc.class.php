<?php


if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
loadcache('plugin');
global $_G, $lang;
class plugin_jzsjiale_sms {

    public function global_header() {
        global $_G;
    
        $_config = $_G['cache']['plugin']['jzsjiale_sms'];
        
        if ($_config['g_isopenjs']){
            include_once template('jzsjiale_sms:jsbuchong');
            return $jsbuchong;
        }
        
        
    }
    
    function global_login_extra() {
        global $_G;
        $_config = $_G['cache']['plugin']['jzsjiale_sms'];
    
        if (!$_config['g_openpclogin']){
            return "";
        }
        include_once template('jzsjiale_sms:loginbtn');
        return $loginbtn;
    }
    
    function logging_method() {
        global $_G;
        $_config = $_G['cache']['plugin']['jzsjiale_sms'];
    
        if (!$_config['g_openpclogin']){
            return "";
        }
        include_once template('jzsjiale_sms:loginbtn2');
        return $loginbtn2;
    }
    
    function register_logging_method() {
        global $_G;
        $_config = $_G['cache']['plugin']['jzsjiale_sms'];
    
        if (!$_config['g_openpclogin']){
            return "";
        }
        include_once template('jzsjiale_sms:loginbtn2');
        return $loginbtn2;
    }
    public function global_usernav_extra1()
    {
        global $_G;
        $_config = $_G['cache']['plugin']['jzsjiale_sms'];
    
        if (!$_config['g_openpcbangding']){
            return "";
        }
        
        if (!$_G['uid']){
            return "";
        }
       
        
        if($_config['g_tongyiuser']){
            //20170805 add start
            $phonebangding =  C::t('#jzsjiale_sms#jzsjiale_sms_member')->fetch_by_uid($_G["uid"]);
            if (empty($phonebangding['mobile'])) {
                $extra1str = "<span class='pipe'>|</span><a href='".$_G['siteurl']."home.php?mod=spacecp&ac=plugin&id=jzsjiale_sms:home'><img src='".$_G['siteurl']."source/plugin/jzsjiale_sms/static/images/sjbd.png' align='absmiddle' style='border-radius:2px;'/></a>&nbsp;";
                return $extra1str;
            }
            //20170805 add end
        }else{
            $phonebangding = C::t("#jzsjiale_sms#jzsjiale_sms_user")->fetch_by_uid($_G["uid"]);
            if (empty($phonebangding)) {
                $extra1str = "<span class='pipe'>|</span><a href='".$_G['siteurl']."home.php?mod=spacecp&ac=plugin&id=jzsjiale_sms:home'><img src='".$_G['siteurl']."source/plugin/jzsjiale_sms/static/images/sjbd.png' align='absmiddle' style='border-radius:2px;'/></a>&nbsp;";
                return $extra1str;
            }
        }
        
        
        return "";
    }
    
    function common() {
     
        global $_G;
        $_config = $_G['cache']['plugin']['jzsjiale_sms'];
        
        $jzsjiale_sms_seccode = daddslashes(getcookie('jzsjiale_sms_seccode'));
        $jzsjiale_sms_phone = daddslashes(getcookie('jzsjiale_sms_phone'));
        
        $userinfo = array();
        $istiaozhuan = false;
        
        $isregok = false;
        if($_config['g_tongyiuser']){
            //20170805 add start
            $userinfo =  C::t('#jzsjiale_sms#jzsjiale_sms_member')->fetch_by_uid($_G['uid']);
            
            if(empty($userinfo['mobile'])){
                $istiaozhuan = true;
            }
            if ($_G['uid'] && $jzsjiale_sms_seccode && $jzsjiale_sms_phone && empty($userinfo['mobile'])) {
            
                $isregok = true;
            }
            //20170805 add end
        }else{
            $userinfo = C::t('#jzsjiale_sms#jzsjiale_sms_user')->fetch_by_uid($_G['uid']);
            
            if(!$userinfo){
                $istiaozhuan = true;
            }
            if ($_G['uid'] && $jzsjiale_sms_seccode && $jzsjiale_sms_phone && !$userinfo) {
            
                $isregok = true;
            }
        }
        
        if($isregok){
            $codeinfo = C::t('#jzsjiale_sms#jzsjiale_sms_code')->fetchfirst_by_phone_and_seccode($jzsjiale_sms_phone,$jzsjiale_sms_seccode);
            
            if(!empty($codeinfo)){
                
                //weibaochitongbuxianshanchu
                C::t('#jzsjiale_sms#jzsjiale_sms_user')->deletebyuid($_G['uid']);
                
                $data = array(
                    'uid' => $_G['uid'],
                    'username' => $_G['username'],
                    'phone' => $codeinfo['phone'],
                    'dateline' => TIMESTAMP
                );
            
                C::t('#jzsjiale_sms#jzsjiale_sms_user')->insert($data,true);
            
                C::t('common_member_profile')->update($_G['uid'], array('mobile'=> $codeinfo['phone']));
            
                C::t('#jzsjiale_sms#jzsjiale_sms_code')->deleteby_seccode_and_phone($jzsjiale_sms_phone,$jzsjiale_sms_seccode);
            
                $istiaozhuan = false;
                
                dsetcookie('jzsjiale_sms_phone');
                dsetcookie('jzsjiale_sms_seccode');
            }
        }
      
    
        
        //qiangzhibangding
        $groupid = $_G['groupid'];
        $basescript = $_G['basescript'];
        if ($_G['uid'] && $_config['g_pcqiangzhibangding'] && in_array($groupid, (array) unserialize($_config['g_qiangzhigroups'])) && $istiaozhuan && ($basescript == "portal" || $basescript == "forum" || $basescript == "group" )){
            showmessage($_config['g_pcqiangzhitip'], 'home.php?mod=spacecp&ac=plugin&id=jzsjiale_sms:home', 'succeed');
       
        }
        
    }
    

}

class plugin_jzsjiale_sms_member extends plugin_jzsjiale_sms
{
    
    function register_input_output() {
        global $_G;
        $_config = $_G['cache']['plugin']['jzsjiale_sms'];
     
        
        if (!$_config['g_openpcregister']) {
            return;
        }
        include_once template('jzsjiale_sms:register');
        return $register;
    }
    
 
    function register_code()
    {
        
        global $_G;
        $_config = $_G['cache']['plugin']['jzsjiale_sms'];
        
        if (!$_config['g_openpcregister']) {
            return;
        }
        
        if($_G['uid']){
            return;
        }
        
        if (isset($_GET["inajax"]) && $_GET["inajax"]==1) {
            $phone = addslashes($_GET['phone_reg']);
            $seccode = addslashes($_GET['seccode']);
            if (!$phone || !$seccode) {
                showmessage(lang('plugin/jzsjiale_sms', 'paramerror'));
            }
           
            if (!preg_match("/^1[34578]{1}\d{9}$/", $phone)) {
                showmessage(lang('plugin/jzsjiale_sms', 'bind_phone_error'));
            }
            
            $codeinfo = C::t('#jzsjiale_sms#jzsjiale_sms_code')->fetchfirst_by_phone_and_seccode($phone,$seccode);
            if ($codeinfo) {
                if ((TIMESTAMP - $codeinfo[dateline]) > $_config['g_youxiaoqi']) {
                    C::t('#jzsjiale_sms#jzsjiale_sms_code')->deleteby_seccode_and_phone($phone,$seccode);
                    //C::t('#jzsjiale_sms#jzsjiale_sms_smslist')->deleteby_seccode_and_phone($phone,$seccode);
                    showmessage(lang('plugin/jzsjiale_sms', 'err_seccodeguoqi'));
                }
            } else {
                showmessage(lang('plugin/jzsjiale_sms', 'err_seccodeerror'));
            }
            
            
            if($_config['g_tongyiuser']){
                //20170805 add start
                $phoneuser =  C::t('#jzsjiale_sms#jzsjiale_sms_member')->fetch_by_mobile($phone);
                //20170805 add end
            }else{
                $phoneuser = C::t('#jzsjiale_sms#jzsjiale_sms_user')->fetch_by_phone($phone);
            }
            
            
            if(!empty($phoneuser)){
                showmessage(lang('plugin/jzsjiale_sms', 'phonecunzai'));
            }
            
            
            
            dsetcookie('jzsjiale_sms_seccode', $seccode, 600);
            dsetcookie('jzsjiale_sms_phone', $phone, 600);
           
        }
    }
    
    function logging_code() {
        global $_G;
        $_config = $_G['cache']['plugin']['jzsjiale_sms'];

        if($_G['uid']){
            return;
        }
        
        if($_G['setting']['bbclosed']){
            return;
        }
        
        if($_config['g_openpczhaohui'] && !$_G['uid'] && empty($_GET['popup']) && isset($_GET['viewlostpw']) && !$_GET['byemail'] && $_GET['loginsubmit'] != 'yes' && $_GET['loginsubmit'] != 'true' && $_GET['lssubmit'] != 'yes' && empty($_GET['auth'])){
            include template('jzsjiale_sms:lostpw');
            exit;
        }
     
        if($_config['g_openpczhaohui'] && !$_G['uid'] && !empty($_GET['popup']) && $_GET['popup']=='no' && isset($_GET['viewlostpw']) && !$_GET['byemail'] && $_GET['loginsubmit'] != 'yes' && $_GET['loginsubmit'] != 'true' && $_GET['lssubmit'] != 'yes' && empty($_GET['auth'])){
            include template('jzsjiale_sms:lostpw2');
            exit;
        }
        
        if(!$_config['g_phonemimalogin'] && $_config['g_openpclogin'] && !$_G['uid'] && !empty($_GET['phonelogin']) && $_GET['phonelogin'] == "yes" && empty($_GET['infloat']) && $_GET['loginsubmit'] != 'yes' && $_GET['loginsubmit'] != 'true' && $_GET['lssubmit'] != 'yes' && empty($_GET['auth']) && !isset($_GET['viewlostpw'])){
            include template('jzsjiale_sms:login2');
            exit;
        }
   
        
        if(!$_config['g_phonemimalogin'] && $_config['g_openpclogin'] && !$_G['uid'] && $_config['g_pcmorenphonelogin'] && $_GET['phonelogin'] != "no" && empty($_GET['infloat']) && $_GET['loginsubmit'] != 'yes' && $_GET['loginsubmit'] != 'true' && $_GET['lssubmit'] != 'yes' && empty($_GET['auth']) && !isset($_GET['viewlostpw'])){
            include template('jzsjiale_sms:login2');
            exit;
        }
        
        if(!$_config['g_phonemimalogin'] && $_config['g_openpclogin'] && !$_G['uid'] && $_config['g_pcmorenphonelogin'] && $_GET['phonelogin'] != "no" && !empty($_GET['infloat']) && $_GET['loginsubmit'] != 'yes' && $_GET['loginsubmit'] != 'true' && $_GET['lssubmit'] != 'yes' && empty($_GET['auth']) && !isset($_GET['viewlostpw'])){
            include template('jzsjiale_sms:login');
            exit;
        }
        
        
        
        if($_config['g_phonemimalogin'] && $_config['g_openpclogin'] && !$_G['uid'] && !empty($_GET['phonelogin']) && $_GET['phonelogin'] == "yes" && empty($_GET['infloat']) && $_GET['loginsubmit'] != 'yes' && $_GET['loginsubmit'] != 'true' && $_GET['lssubmit'] != 'yes' && empty($_GET['auth']) && !isset($_GET['viewlostpw'])){
            include template('jzsjiale_sms:login2mima');
            exit;
        }
         
        if($_config['g_phonemimalogin'] && $_config['g_openpclogin'] && !$_G['uid'] && $_config['g_pcmorenphonelogin'] && $_GET['phonelogin'] != "no" && empty($_GET['infloat']) && $_GET['loginsubmit'] != 'yes' && $_GET['loginsubmit'] != 'true' && $_GET['lssubmit'] != 'yes' && empty($_GET['auth']) && !isset($_GET['viewlostpw'])){
            include template('jzsjiale_sms:login2mima');
            exit;
        }

        if($_config['g_phonemimalogin'] && $_config['g_openpclogin'] && !$_G['uid'] && $_config['g_pcmorenphonelogin'] && $_GET['phonelogin'] != "no" && !empty($_GET['infloat']) && $_GET['loginsubmit'] != 'yes' && $_GET['loginsubmit'] != 'true' && $_GET['lssubmit'] != 'yes' && empty($_GET['auth']) && !isset($_GET['viewlostpw'])){
            include template('jzsjiale_sms:loginmima');
            exit;
        }
    }
   
}

class plugin_jzsjiale_sms_home extends plugin_jzsjiale_sms {

    function spacecp_profile_bottom_output() {
        global $_G;
        $_config = $_G['cache']['plugin']['jzsjiale_sms'];

        if (!$_G['uid']){
            return;
        }
        
        if (!$_config['g_openpcbangding']){
            return;
        }
        

        if($_config['g_openpcbangding']) {
            include_once template('jzsjiale_sms:profilemobile');
            return $profilemobile;
        }

    }

}
?>