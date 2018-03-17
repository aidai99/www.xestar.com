<?php

if (!defined('IN_DISCUZ')) {
    exit('Access Denied');
}


loadcache('plugin');
global $_G, $lang;

$formhash =  addslashes($_GET['formhash'])? addslashes($_GET['formhash']):'';

if ($formhash == FORMHASH) {

    global $_G;
    $_config = $_G['cache']['plugin']['jzsjiale_sms'];
    
    if($_GET['jiechubangding']){
        loaducenter();
        list($result) = uc_user_login($_G['uid'], $_GET['password'], 1, 0);
        if ($result < 0){
            showmessage(plang('mimaerror'));
        }
        
        if($_config['g_tongyiuser']){
            
            if($_config['g_jiebangnewphone']){
                $phone = daddslashes($_GET['phone']);
                $seccode = daddslashes($_GET['seccode']);
                if (!$phone || !$seccode){
                    showmessage(plang('paramerror'));
                }
                if (!preg_match("/^1[34578]{1}\d{9}$/", $phone)) {
                    showmessage(plang('bind_phone_error'));
                }
                $codeinfo = C::t('#jzsjiale_sms#jzsjiale_sms_code')->fetchfirst_by_phone_and_seccode($phone,$seccode);
                if ($codeinfo) {
                    if ((TIMESTAMP - $codeinfo[dateline]) > $_config['g_youxiaoqi']) {
                        C::t('#jzsjiale_sms#jzsjiale_sms_code')->deleteby_seccode_and_phone($phone,$seccode);
                        //C::t('#jzsjiale_sms#jzsjiale_sms_smslist')->deleteby_seccode_and_phone($phone,$seccode);
                        showmessage(plang('err_seccodeguoqi'));
                    }
                } else {
                    showmessage(plang('err_seccodeerror'));
                }
                
                C::t('#jzsjiale_sms#jzsjiale_sms_code')->deleteby_seccode_and_phone($phone,$seccode);
                
                $phoneuser =  C::t('#jzsjiale_sms#jzsjiale_sms_member')->fetch_by_mobile($phone);
                if(!empty($phoneuser)){
                    showmessage(plang('phonecunzai'));
                }
                
                $member = C::t('common_member')->fetch_by_username($_G['username']);
                
                if(empty($member)){
                    showmessage(plang('nousername'));
                }
                //weibaochitongbuxianshanchu
                C::t('#jzsjiale_sms#jzsjiale_sms_user')->deletebyuid($_G['uid']);
                
                $data = array(
                    'uid' => $_G['uid'],
                    'username' => $_G['username'],
                    'phone' => $phone,
                    'dateline' => TIMESTAMP
                );
                
                
                C::t('#jzsjiale_sms#jzsjiale_sms_user')->insert($data,true);
                
                if(C::t('common_member_profile')->update($_G['uid'], array('mobile'=> $phone))){
                    showmessage(plang('chongxinbangdingok'), dreferer(), array(), array('alert' => 'right', 'locationtime' => true, 'msgtype' => 2, 'showdialog' => true, 'showmsg' => true));
                }else{
                    showmessage(plang('jiechuerror'));
                }
            }else{
                //20170805 add start
                C::t('#jzsjiale_sms#jzsjiale_sms_user')->deletebyuid($_G['uid']);
                if(C::t('common_member_profile')->update($_G['uid'], array('mobile'=> ''))){
                    showmessage(plang('jiechuok'), dreferer(), array(), array('alert' => 'right', 'locationtime' => true, 'msgtype' => 2, 'showdialog' => true, 'showmsg' => true));
                }else{
                    showmessage(plang('jiechuerror'));
                }
                //20170805 add end
            }
            
        }else{
            
            if($_config['g_jiebangnewphone']){
                $phone = daddslashes($_GET['phone']);
                $seccode = daddslashes($_GET['seccode']);
                if (!$phone || !$seccode){
                    showmessage(plang('paramerror'));
                }
                if (!preg_match("/^1[34578]{1}\d{9}$/", $phone)) {
                    showmessage(plang('bind_phone_error'));
                }
                $codeinfo = C::t('#jzsjiale_sms#jzsjiale_sms_code')->fetchfirst_by_phone_and_seccode($phone,$seccode);
                if ($codeinfo) {
                    if ((TIMESTAMP - $codeinfo[dateline]) > $_config['g_youxiaoqi']) {
                        C::t('#jzsjiale_sms#jzsjiale_sms_code')->deleteby_seccode_and_phone($phone,$seccode);
                        //C::t('#jzsjiale_sms#jzsjiale_sms_smslist')->deleteby_seccode_and_phone($phone,$seccode);
                        showmessage(plang('err_seccodeguoqi'));
                    }
                } else {
                    showmessage(plang('err_seccodeerror'));
                }
            
                C::t('#jzsjiale_sms#jzsjiale_sms_code')->deleteby_seccode_and_phone($phone,$seccode);
            
                $phoneuser =  C::t('#jzsjiale_sms#jzsjiale_sms_member')->fetch_by_mobile($phone);
                if(!empty($phoneuser)){
                    showmessage(plang('phonecunzai'));
                }
            
                $member = C::t('common_member')->fetch_by_username($_G['username']);
            
                if(empty($member)){
                    showmessage(plang('nousername'));
                }
                //weibaochitongbuxianshanchu
                C::t('#jzsjiale_sms#jzsjiale_sms_user')->deletebyuid($_G['uid']);
            
                $data = array(
                    'uid' => $_G['uid'],
                    'username' => $_G['username'],
                    'phone' => $phone,
                    'dateline' => TIMESTAMP
                );
            
            
                if(C::t('#jzsjiale_sms#jzsjiale_sms_user')->insert($data,true)){
                    C::t('common_member_profile')->update($_G['uid'], array('mobile'=> $phone));
                    showmessage(plang('chongxinbangdingok'), dreferer(), array(), array('alert' => 'right', 'locationtime' => true, 'msgtype' => 2, 'showdialog' => true, 'showmsg' => true));
                }else{
                    showmessage(plang('jiechuerror'));
                }
            }else{
                if(C::t('#jzsjiale_sms#jzsjiale_sms_user')->deletebyuid($_G['uid'])){
                    C::t('common_member_profile')->update($_G['uid'], array('mobile'=> ''));
                    showmessage(plang('jiechuok'), dreferer(), array(), array('alert' => 'right', 'locationtime' => true, 'msgtype' => 2, 'showdialog' => true, 'showmsg' => true));
                }else{
                    showmessage(plang('jiechuerror'));
                }
            }
            
        }
        
    }
    
    
    $phone = daddslashes($_GET['phone']);
    $seccode = daddslashes($_GET['seccode']);
    if (!$phone || !$seccode){
        showmessage(plang('paramerror'));
    }
    if (!preg_match("/^1[34578]{1}\d{9}$/", $phone)) {
        showmessage(plang('bind_phone_error'));
    }

    loaducenter();
    list($result) = uc_user_login($_G['uid'], $_GET['password'], 1, 0);
    if ($result < 0){
        showmessage(plang('mimaerror'));
    }

    
    
    $codeinfo = C::t('#jzsjiale_sms#jzsjiale_sms_code')->fetchfirst_by_phone_and_seccode($phone,$seccode);
    if ($codeinfo) {
        if ((TIMESTAMP - $codeinfo[dateline]) > $_config['g_youxiaoqi']) {
            C::t('#jzsjiale_sms#jzsjiale_sms_code')->deleteby_seccode_and_phone($phone,$seccode);
            //C::t('#jzsjiale_sms#jzsjiale_sms_smslist')->deleteby_seccode_and_phone($phone,$seccode);
            showmessage(plang('err_seccodeguoqi'));
        }
    } else {
        showmessage(plang('err_seccodeerror'));
    }

    C::t('#jzsjiale_sms#jzsjiale_sms_code')->deleteby_seccode_and_phone($phone,$seccode);
    
    
    if($_config['g_tongyiuser']){
        //20170805 add start
        $phoneuser =  C::t('#jzsjiale_sms#jzsjiale_sms_member')->fetch_by_mobile($phone);
        if(!empty($phoneuser)){
            showmessage(plang('phonecunzai'));
        }
        
        $member = C::t('common_member')->fetch_by_username($_G['username']);
        
        if(empty($member)){
            showmessage(plang('nousername'));
        }
        //weibaochitongbuxianshanchu
        C::t('#jzsjiale_sms#jzsjiale_sms_user')->deletebyuid($_G['uid']);
        
        $data = array(
            'uid' => $_G['uid'],
            'username' => $_G['username'],
            'phone' => $phone,
            'dateline' => TIMESTAMP
        );
        
        C::t('#jzsjiale_sms#jzsjiale_sms_user')->insert($data,true);
            
        if(C::t('common_member_profile')->update($_G['uid'], array('mobile'=> $phone))){
            showmessage(plang('bangdingok'), dreferer(), array(), array('alert' => 'right', 'locationtime' => true, 'msgtype' => 2, 'showdialog' => true, 'showmsg' => true));
        }else{
            showmessage(plang('bangdingerror'));
        }
        //20170805 add end
    }else{
        $phoneuser = C::t('#jzsjiale_sms#jzsjiale_sms_user')->fetch_by_phone($phone);
        
        if(!empty($phoneuser)){
            showmessage(plang('phonecunzai'));
        }
        $userinfo = C::t('#jzsjiale_sms#jzsjiale_sms_user')->fetch_by_username($_G['username']);
        if(!empty($userinfo)){
            showmessage(plang('err_yibangding'));
        }
        
        $member = C::t('common_member')->fetch_by_username($_G['username']);
        
        if(empty($member)){
            showmessage(plang('nousername'));
        }
        
        //weibaochitongbuxianshanchu
        C::t('#jzsjiale_sms#jzsjiale_sms_user')->deletebyuid($_G['uid']);
        
        $data = array(
            'uid' => $_G['uid'],
            'username' => $_G['username'],
            'phone' => $phone,
            'dateline' => TIMESTAMP
        );
        
        if(C::t('#jzsjiale_sms#jzsjiale_sms_user')->insert($data,true)){
        
            C::t('common_member_profile')->update($_G['uid'], array('mobile'=> $phone));
        
            showmessage(plang('bangdingok'), dreferer(), array(), array('alert' => 'right', 'locationtime' => true, 'msgtype' => 2, 'showdialog' => true, 'showmsg' => true));
        }else{
            showmessage(plang('bangdingerror'));
        }
    }
    
    
}else{
    global $_G;
    $_config = $_G['cache']['plugin']['jzsjiale_sms'];
    if($_config['g_tongyiuser']){
        //20170805 add start
        $userinfo =  C::t('#jzsjiale_sms#jzsjiale_sms_member')->fetch_by_uid($_G['uid']);
        if(!empty($userinfo)){
            $userinfo['phone'] = $userinfo['mobile'];
            if(empty($userinfo['mobile'])){
                $userinfo = array();
            }
        }
        //20170805 add end
    }else{
        $userinfo = C::t('#jzsjiale_sms#jzsjiale_sms_user')->fetch_by_username($_G['username']);
        if(!empty($userinfo)){
            if(empty($userinfo['phone'])){
                C::t('#jzsjiale_sms#jzsjiale_sms_user')->deletebyid($userinfo['id']);
                $userinfo = array();
            }
        }
    }
    
}


function plang($str) {
    return lang('plugin/jzsjiale_sms', $str);
}
?>