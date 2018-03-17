<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$act = addslashes($_GET['act']);
$formhash =  addslashes($_GET['formhash'])? addslashes($_GET['formhash']):'';
loadcache('plugin');
global $_G, $lang;

if ($act == 'register') {
    
    if ($formhash == FORMHASH && $_GET['phoneregistersubmit']) {
        $phone = addslashes($_GET['phone']);
        $seccode = addslashes($_GET['seccode']);
        $username = addslashes($_GET['username']);
        $password = addslashes($_GET['password']);
        
        global $_G;
        $_config = $_G['cache']['plugin']['jzsjiale_sms'];
        
        
        if(!$_config['g_openmobileregister']){
            echo json_encode(array('code' => -1,'data' => 'notopenmobile'));
            exit;
        }
        
        if(empty($phone)){
            echo json_encode(array('code' => -1,'data' => 'phonenull'));
            exit;
        }
        
        if (!preg_match("/^1[34578]{1}\d{9}$/", $phone)) {
            echo json_encode(array('code' => -1,'data' => 'bind_phone_error'));
            exit;
        }
        
        if(empty($seccode)){
            echo json_encode(array('code' => -1,'data' => 'seccodenull'));
            exit;
        }
        
        if(empty($username)){
            echo json_encode(array('code' => -1,'data' => 'usernamenull'));
            exit;
        }
        
        if(empty($password)){
            echo json_encode(array('code' => -1,'data' => 'passwordnull'));
            exit;
        }
        
        $codeinfo = C::t('#jzsjiale_sms#jzsjiale_sms_code')->fetchfirst_by_phone_and_seccode($phone,$seccode);
        if ($codeinfo) {
            if ((TIMESTAMP - $codeinfo[dateline]) > $_config['g_youxiaoqi']) {
                C::t('#jzsjiale_sms#jzsjiale_sms_code')->deleteby_seccode_and_phone($phone,$seccode);
                //C::t('#jzsjiale_sms#jzsjiale_sms_smslist')->deleteby_seccode_and_phone($phone,$seccode);
                echo json_encode(array('code' => -1,'data' => 'err_seccodeguoqi'));
                exit;
            }
        } else {
            echo json_encode(array('code' => -1,'data' => 'err_seccodeerror'));
            exit;
        }
        
        if($_config['g_tongyiuser']){
            //20170805 add start
            $phoneuser =  C::t('#jzsjiale_sms#jzsjiale_sms_member')->fetch_by_mobile($phone);
            //20170805 add end
        }else{
            $phoneuser = C::t('#jzsjiale_sms#jzsjiale_sms_user')->fetch_by_phone($phone);
        }
        
        if(!empty($phoneuser)){
            echo json_encode(array('code' => -1,'data' => 'phonecunzai'));
            exit;
        }
        
        $username = iconv('UTF-8', CHARSET.'//ignore', urldecode($username));
        $email = "reg_".substr($phone,0,3).time().substr($phone,7,4)."@null.null";
        
        $user = C::t('common_member')->fetch_by_username($username);
        if($user){
            echo json_encode(array('code' => -1,'data' => 'usernamecunzai'));
            exit;
        }
        
        $profile = array (
            "mobile" => $phone,
        );
        require_once DISCUZ_ROOT.'./source/plugin/jzsjiale_sms/uc.inc.php';
        $uid = UC::regist($username,$password,$email,$profile);
        if (!is_numeric($uid)) {
            echo json_encode(array('code' => -1,'data' => 'registererror'));
            exit;
        }
        
        
        $userinfo = C::t('#jzsjiale_sms#jzsjiale_sms_user')->fetch_by_uid($uid);
         
        
        if ($uid && $phone && $seccode && !$userinfo) {
        
            //weibaochitongbuxianshanchu
            C::t('#jzsjiale_sms#jzsjiale_sms_user')->deletebyuid($_G['uid']);
            
            $data = array(
                'uid' => $_G['uid'],
                'username' => $_G['username'],
                'phone' => $codeinfo['phone'],
                'dateline' => TIMESTAMP
            );
            
            C::t('#jzsjiale_sms#jzsjiale_sms_user')->insert($data, true);
            
            C::t('common_member_profile')->update($_G['uid'], array('mobile'=> $phone));
            
            C::t('#jzsjiale_sms#jzsjiale_sms_code')->deleteby_seccode_and_phone($phone,$seccode);
            echo json_encode(array(
                'code' => 200,
                'data' => 'registersuccess'
            ));
            exit();
        
        }else{
            C::t('#jzsjiale_sms#jzsjiale_sms_code')->deleteby_seccode_and_phone($phone,$seccode);
            echo json_encode(array(
                'code' => 200,
                'data' => 'registersuccess_phoneerror'
            ));
            exit();
        }
        
    }else{
        global $_G;
        $_config = $_G['cache']['plugin']['jzsjiale_sms'];
        $mobilecolor = !empty($_config['g_mobilecolor'])?$_config['g_mobilecolor']:"#4eabe8";
        if(!$_G['uid'] && $_config['g_openmobileregister']){
            include template('jzsjiale_sms:register');
        }else{
            dheader("Location: home.php?mod=space&do=profile&uid=".$_G['uid']);
            dexit();
        }
        
    }
}elseif ($act == 'phonemimalogin') {
    
   
    require_once libfile('function/misc');
    loaducenter();
   
    require_once libfile('function/member');
    
    if ($formhash == FORMHASH && $_GET['phoneloginsubmit']) {
        $phone = addslashes($_GET['phone']);
        $phone_password = daddslashes($_GET['phone_password']);
        
        global $_G;
        $_config = $_G['cache']['plugin']['jzsjiale_sms'];
        
        
        if(!$_config['g_openmobilelogin']){
            echo json_encode(array('code' => -1,'data' => 'notopenmobile'));
            exit;
        }
        
        if(empty($phone)){
            echo json_encode(array('code' => -1,'data' => 'phonenull'));
            exit;
        }
        
        if (!preg_match("/^1[34578]{1}\d{9}$/", $phone)) {
            echo json_encode(array('code' => -1,'data' => 'bind_phone_error'));
            exit;
        }
        
        if(empty($phone_password)){
            echo json_encode(array('code' => -1,'data' => 'passwordnull'));
            exit;
        }
        
        if (strlen($phone_password)<6) {
            echo json_encode(array('code' => -1,'data' => 'password6'));
            exit;
        }
        
        
        //20170816 start
        if($_config['g_isopenmimaimgcode']){
            if(empty($_GET['seccodeverify']) || empty($_GET['seccodehash'])){
                echo json_encode(array('code' => -1,'data' => 'paramerror'));
                exit;
            }
            if (!check_seccode($_GET['seccodeverify'], $_GET['seccodehash'])) {
                echo json_encode(array('code' => -1,'data' => 'seccode_invalid'));
                exit;
            }
        }
        
        //20170816 end
        
       
        $_G['uid'] = $_G['member']['uid'] = 0;
        $_G['username'] = $_G['member']['username'] = $_G['member']['password'] = '';
        
        
        if($_config['g_tongyiuser']){
            //20170805 add start
            $userinfo =  C::t('#jzsjiale_sms#jzsjiale_sms_member')->fetch_by_mobile($phone);
            if(empty($userinfo['mobile'])){
                echo json_encode(array('code' => -1,'data' => 'err_weibangding'));
                exit;
            }
            //20170805 add end
        }else{
            $userinfo = C::t('#jzsjiale_sms#jzsjiale_sms_user')->fetch_by_phone($phone);
            if(!empty($userinfo)){
                if(empty($userinfo['username'])){
                    C::t('#jzsjiale_sms#jzsjiale_sms_user')->deletebyid($userinfo['id']);
                    $userinfo = array();
                    echo json_encode(array('code' => -1,'data' => 'err_weibangding'));
                    exit;
                }
            }
            
            if(empty($userinfo)){
                   echo json_encode(array('code' => -1,'data' => 'err_weibangding'));
                   exit;
            }
        }
        
        
        $member = getuserbyuid($userinfo['uid'], 1);
        if (!$member || empty($member['uid'])) {
            C::t('#jzsjiale_sms#jzsjiale_sms_user')->deletebyphone($phone);
            echo json_encode(array('code' => -1,'data' => 'err_weibangding'));
            exit;
        }
        
        if(file_exists(DISCUZ_ROOT.'./source/plugin/jzsjiale_sms/uc.inc.php')){
            require_once DISCUZ_ROOT.'./source/plugin/jzsjiale_sms/uc.inc.php';
        }else{
            echo json_encode(array('code' => -1,'data' => 'err_systemerror'));
            exit();
        }
        
        
        
        $questionid = daddslashes($_GET['questionid']);
        $answer = daddslashes($_GET['answer']);
       
        if(intval($questionid) > 0 && empty($answer)){
            echo json_encode(array('code' => -1,'data' => 'answernull'));
            exit;
        }
        if(intval($questionid) == 0){
            $questionid = "";
            $answer = "";
        }
        
        
        $uid = UC::logincheck($member['username'],$phone_password,$questionid,$answer);
        
        
        
        if (!is_numeric($uid)) {
            
            if($uid == "too_many_errors"){
                echo json_encode(array('code' => -1,'data' => 'logintoomanyerror'));
                exit;
            }else{
                echo json_encode(array('code' => -1,'data' => 'loginerror'));
                exit;
            }
            
        }
        
        if($uid == -2) {
            echo json_encode(array('code' => -1,'data' => 'mimaerror'));
            exit;
        } elseif($uid == -3) {
            echo json_encode(array('code' => -1,'data' => 'answerset'));
            exit;
        } elseif($uid <= 0 && $uid != -2 && $uid != -3) {
            echo json_encode(array('code' => -1,'data' => 'loginerror'));
            exit;
        }
        
        if($member['uid'] != $uid){
            echo json_encode(array('code' => -1,'data' => 'loginerror'));
            exit;
        }
        
        
        if ($member['_inarchive']) {
            C::t('common_member_archive')->move_to_master($member['uid']);
        }
        
        setloginstatus($member, $_GET['cookietime'] ? 2592000 : 0);
        checkfollowfeed();
        if ($_G['group']['forcelogin']) {
            if ($_G['group']['forcelogin'] == 1) {
                clearcookies();
                echo json_encode(array('code' => -1,'data' => 'err_location_login_force_qq'));
                exit;
            } elseif ($_G['group']['forcelogin'] == 2 && $_GET['loginfield'] != 'email') {
                clearcookies();
                echo json_encode(array('code' => -1,'data' => 'err_location_login_force_mail'));
                exit;
            }
        }
        if ($_G['member']['lastip'] && $_G['member']['lastvisit']) {
            dsetcookie('lip', $_G['member']['lastip'] . ',' . $_G['member']['lastvisit']);
        }
        C::t('common_member_status')->update($_G['uid'], array('lastip' => $_G['clientip'], 'port' => $_G['remoteport'], 'lastvisit' => TIMESTAMP, 'lastactivity' => TIMESTAMP));
        $ucsynlogin = $_G['setting']['allowsynlogin'] ? uc_user_synlogin($_G['uid']) : '';
        
        
        
        if ($_G['member']['adminid'] != 1) {
            if ($_G['setting']['accountguard']['loginoutofdate'] && $_G['member']['lastvisit'] && TIMESTAMP - $_G['member']['lastvisit'] > 90 * 86400) {
                C::t('common_member')->update($_G['uid'], array('freeze' => 2));
                C::t('common_member_validate')->insert(array(
                'uid' => $_G['uid'],
                'submitdate' => TIMESTAMP,
                'moddate' => 0,
                'admin' => '',
                'submittimes' => 1,
                'status' => 0,
                'message' => '',
                'remark' => '',
                ), false, true);
                manage_addnotify('verifyuser');
                echo json_encode(array('code' => -1,'data' => 'err_location_login_outofdate'));
                exit;
                //showmessage('location_login_outofdate', 'home.php?mod=spacecp&ac=profile&op=password&resend=1', array('type' => 1), array('showdialog' => true, 'striptags' => false, 'locationtime' => true));
            }
        }
        
        $param = array(
            'username' => $_G['member']['username'],
            'usergroup' => $_G['group']['grouptitle'],
            'uid' => $_G['member']['uid'],
            'groupid' => $_G['groupid'],
            'syn' => $ucsynlogin ? 1 : 0
        );
        $extra = array(
            'showdialog' => true,
            'locationtime' => true,
            'extrajs' => $ucsynlogin
        );
        $loginmessage = $_G['groupid'] == 8 ? 'login_succeed_inactive_member' : 'login_succeed';
        $location = $_G['groupid'] == 8 ? 'home.php?mod=space&do=profile&uid='.$_G['uid'] : dreferer();
        
        
        if($_config['g_mtiaozhuanhome']){
            @include_once './data/sysdata/cache_domain.php';
            
            $location = $domain['defaultindex'];
        }
        
        
        echo json_encode(array('code' => 200,'data' => 'loginsuccess','url' => $location));
        exit;
        //showmessage($loginmessage, $location, $param, $extra);
        
        
        
    }else{
        global $_G;
        $_config = $_G['cache']['plugin']['jzsjiale_sms'];
        $mobilecolor = !empty($_config['g_mobilecolor'])?$_config['g_mobilecolor']:"#4eabe8";
        if(!$_G['uid'] && $_config['g_openmobilelogin']){
   
            if($_config['g_phonemimalogin']){
                include template('jzsjiale_sms:loginmima');
            }else{
                include template('jzsjiale_sms:login');
            }
        }else{
            
            $ucsynlogin = $_G['setting']['allowsynlogin'] ? uc_user_synlogin($_G['uid']) : '';
            
            $location = "home.php?mod=space&do=profile&uid=".$_G['uid'];
            if($_config['g_mtiaozhuanhome']){
                @include_once './data/sysdata/cache_domain.php';
            
                $location = $domain['defaultindex'];
            }
            dheader("Location: ".$location);
            dexit();
            
        }
        
    }
}elseif ($act == 'login') {
    
   
    require_once libfile('function/misc');
    loaducenter();
   
    require_once libfile('function/member');
    
    if ($formhash == FORMHASH && $_GET['phoneloginsubmit']) {
        $phone = addslashes($_GET['phone']);
        $seccode = addslashes($_GET['seccode']);
        
        global $_G;
        $_config = $_G['cache']['plugin']['jzsjiale_sms'];
        
        
        if(!$_config['g_openmobilelogin']){
            echo json_encode(array('code' => -1,'data' => 'notopenmobile'));
            exit;
        }
        
        if(empty($phone)){
            echo json_encode(array('code' => -1,'data' => 'phonenull'));
            exit;
        }
        
        if (!preg_match("/^1[34578]{1}\d{9}$/", $phone)) {
            echo json_encode(array('code' => -1,'data' => 'bind_phone_error'));
            exit;
        }
        
        if(empty($seccode)){
            echo json_encode(array('code' => -1,'data' => 'seccodenull'));
            exit;
        }
        
        
        $codeinfo = C::t('#jzsjiale_sms#jzsjiale_sms_code')->fetchfirst_by_phone_and_seccode($phone,$seccode);
        if ($codeinfo) {
            if ((TIMESTAMP - $codeinfo[dateline]) > $_config['g_youxiaoqi']) {
                C::t('#jzsjiale_sms#jzsjiale_sms_code')->deleteby_seccode_and_phone($phone,$seccode);
                //C::t('#jzsjiale_sms#jzsjiale_sms_smslist')->deleteby_seccode_and_phone($phone,$seccode);
                echo json_encode(array('code' => -1,'data' => 'err_seccodeguoqi'));
                exit;
            }
        } else {
            echo json_encode(array('code' => -1,'data' => 'err_seccodeerror'));
            exit;
        }
        
        
        
        $_G['uid'] = $_G['member']['uid'] = 0;
        $_G['username'] = $_G['member']['username'] = $_G['member']['password'] = '';
        
        
        if($_config['g_tongyiuser']){
            //20170805 add start
            $userinfo =  C::t('#jzsjiale_sms#jzsjiale_sms_member')->fetch_by_mobile($phone);
            if(empty($userinfo['mobile'])){
                echo json_encode(array('code' => -1,'data' => 'err_weibangding'));
                exit;
            }
            //20170805 add end
        }else{
            $userinfo = C::t('#jzsjiale_sms#jzsjiale_sms_user')->fetch_by_phone($phone);
            if(!empty($userinfo)){
                if(empty($userinfo['username'])){
                    C::t('#jzsjiale_sms#jzsjiale_sms_user')->deletebyid($userinfo['id']);
                    $userinfo = array();
                    echo json_encode(array('code' => -1,'data' => 'err_weibangding'));
                    exit;
                }
            }
            
            if(empty($userinfo)){
                   echo json_encode(array('code' => -1,'data' => 'err_weibangding'));
                   exit;
            }
        }
        
        
        
        
        
        $member = getuserbyuid($userinfo['uid'], 1);
        if (!$member || empty($member['uid'])) {
            C::t('#jzsjiale_sms#jzsjiale_sms_user')->deletebyphone($phone);
            echo json_encode(array('code' => -1,'data' => 'err_weibangding'));
            exit;
        }
        if ($member['_inarchive']) {
            C::t('common_member_archive')->move_to_master($member['uid']);
        }
        
        setloginstatus($member, $_GET['cookietime'] ? 2592000 : 0);
        checkfollowfeed();
        if ($_G['group']['forcelogin']) {
            if ($_G['group']['forcelogin'] == 1) {
                clearcookies();
                echo json_encode(array('code' => -1,'data' => 'err_location_login_force_qq'));
                exit;
            } elseif ($_G['group']['forcelogin'] == 2 && $_GET['loginfield'] != 'email') {
                clearcookies();
                echo json_encode(array('code' => -1,'data' => 'err_location_login_force_mail'));
                exit;
            }
        }
        if ($_G['member']['lastip'] && $_G['member']['lastvisit']) {
            dsetcookie('lip', $_G['member']['lastip'] . ',' . $_G['member']['lastvisit']);
        }
        C::t('common_member_status')->update($_G['uid'], array('lastip' => $_G['clientip'], 'port' => $_G['remoteport'], 'lastvisit' => TIMESTAMP, 'lastactivity' => TIMESTAMP));
        $ucsynlogin = $_G['setting']['allowsynlogin'] ? uc_user_synlogin($_G['uid']) : '';
        
        
        
        if ($_G['member']['adminid'] != 1) {
            if ($_G['setting']['accountguard']['loginoutofdate'] && $_G['member']['lastvisit'] && TIMESTAMP - $_G['member']['lastvisit'] > 90 * 86400) {
                C::t('common_member')->update($_G['uid'], array('freeze' => 2));
                C::t('common_member_validate')->insert(array(
                'uid' => $_G['uid'],
                'submitdate' => TIMESTAMP,
                'moddate' => 0,
                'admin' => '',
                'submittimes' => 1,
                'status' => 0,
                'message' => '',
                'remark' => '',
                ), false, true);
                manage_addnotify('verifyuser');
                echo json_encode(array('code' => -1,'data' => 'err_location_login_outofdate'));
                exit;
                //showmessage('location_login_outofdate', 'home.php?mod=spacecp&ac=profile&op=password&resend=1', array('type' => 1), array('showdialog' => true, 'striptags' => false, 'locationtime' => true));
            }
        }
        
        $param = array(
            'username' => $_G['member']['username'],
            'usergroup' => $_G['group']['grouptitle'],
            'uid' => $_G['member']['uid'],
            'groupid' => $_G['groupid'],
            'syn' => $ucsynlogin ? 1 : 0
        );
        $extra = array(
            'showdialog' => true,
            'locationtime' => true,
            'extrajs' => $ucsynlogin
        );
        $loginmessage = $_G['groupid'] == 8 ? 'login_succeed_inactive_member' : 'login_succeed';
        $location = $_G['groupid'] == 8 ? 'home.php?mod=space&do=profile&uid='.$_G['uid'] : dreferer();
        
        C::t('#jzsjiale_sms#jzsjiale_sms_code')->deleteby_seccode_and_phone($phone,$seccode);
        
        if($_config['g_mtiaozhuanhome']){
            @include_once './data/sysdata/cache_domain.php';
        
            $location = $domain['defaultindex'];
        }
        
        echo json_encode(array('code' => 200,'data' => 'loginsuccess','url' => $location));
        exit;
        //showmessage($loginmessage, $location, $param, $extra);
        
        
        
    }else{
        
        global $_G;
        $_config = $_G['cache']['plugin']['jzsjiale_sms'];
        $mobilecolor = !empty($_config['g_mobilecolor'])?$_config['g_mobilecolor']:"#4eabe8";
        if(!$_G['uid'] && $_config['g_openmobilelogin']){
   
            if($_config['g_phonemimalogin']){
                include template('jzsjiale_sms:loginmima');
            }else{
                include template('jzsjiale_sms:login');
            }
        }else{
            
            $ucsynlogin = $_G['setting']['allowsynlogin'] ? uc_user_synlogin($_G['uid']) : '';
            
            $location = "home.php?mod=space&do=profile&uid=".$_G['uid'];
            if($_config['g_mtiaozhuanhome']){
                @include_once './data/sysdata/cache_domain.php';
            
                $location = $domain['defaultindex'];
            }
            dheader("Location: ".$location);
            dexit();
            
        }
        
    }
}elseif ($act == 'lostpw') {
    
    if ($formhash == FORMHASH && $_GET['phonelostpwsubmit']) {
        
        $phone = addslashes($_GET['phone']);
        $seccode = addslashes($_GET['seccode']);
        
        global $_G;
        $_config = $_G['cache']['plugin']['jzsjiale_sms'];
        
       
        if(!$_config['g_openmobilezhaohui']){
            echo json_encode(array('code' => -1,'data' => 'notopenmobile'));
            exit;
        }
        
        if(empty($phone)){
            echo json_encode(array('code' => -1,'data' => 'phonenull'));
            exit;
        }
        
        if (!preg_match("/^1[34578]{1}\d{9}$/", $phone)) {
            echo json_encode(array('code' => -1,'data' => 'bind_phone_error'));
            exit;
        }
        
        if(empty($seccode)){
            echo json_encode(array('code' => -1,'data' => 'seccodenull'));
            exit;
        }
        
        
        $codeinfo = C::t('#jzsjiale_sms#jzsjiale_sms_code')->fetchfirst_by_phone_and_seccode($phone,$seccode);
        if ($codeinfo) {
            if ((TIMESTAMP - $codeinfo[dateline]) > $_config['g_youxiaoqi']) {
                C::t('#jzsjiale_sms#jzsjiale_sms_code')->deleteby_seccode_and_phone($phone,$seccode);
                //C::t('#jzsjiale_sms#jzsjiale_sms_smslist')->deleteby_seccode_and_phone($phone,$seccode);
                echo json_encode(array('code' => -1,'data' => 'err_seccodeguoqi'));
                exit;
            }
        } else {
            echo json_encode(array('code' => -1,'data' => 'err_seccodeerror'));
            exit;
        }
        
        
        if($_config['g_tongyiuser']){
            //20170805 add start
            $userinfo =  C::t('#jzsjiale_sms#jzsjiale_sms_member')->fetch_by_mobile($phone);
            if(empty($userinfo['mobile'])){
                echo json_encode(array('code' => -1,'data' => 'err_weibangding'));
                exit;
            }
            //20170805 add end
        }else{
            $userinfo = C::t('#jzsjiale_sms#jzsjiale_sms_user')->fetch_by_phone($phone);
            if(!empty($userinfo)){
                if(empty($userinfo['username'])){
                    C::t('#jzsjiale_sms#jzsjiale_sms_user')->deletebyid($userinfo['id']);
                    $userinfo = array();
                    echo json_encode(array('code' => -1,'data' => 'err_weibangding'));
                    exit;
                    
                }
            }
            
            if(empty($userinfo)){
                echo json_encode(array('code' => -1,'data' => 'err_weibangding'));
                exit;
            }
        }
        
        
        
        $member = getuserbyuid($userinfo['uid'], 1);
        if (!$member || empty($member['uid'])) {
            C::t('#jzsjiale_sms#jzsjiale_sms_user')->deletebyphone($phone);
            
            echo json_encode(array('code' => -1,'data' => 'err_getpasswd_account_notmatch'));
            exit;
        }elseif ($member['adminid'] == 1 || $member['adminid'] == 2) {
            
            echo json_encode(array('code' => -1,'data' => 'err_getpasswd_account_invalid'));
            exit;
        }
        
         
        
        $table_ext = $member['_inarchive'] ? '_archive' : '';
        $idstring = random(6);
        C::t('common_member_field_forum' . $table_ext)->update($member['uid'], array('authstr' => "$_G[timestamp]\t1\t$idstring"));
        require libfile('function/member');
        $sign = make_getpws_sign($member['uid'], $idstring);
        
        C::t('#jzsjiale_sms#jzsjiale_sms_code')->deleteby_seccode_and_phone($phone,$seccode);
        
        //$location = $_G['siteurl']."member.php?mod=getpasswd&uid=".$member['uid']."&id=".$idstring."&sign=".$sign;
        
        $location = "plugin.php?id=jzsjiale_sms:mobile&act=getpasswd&uid=".$member['uid']."&idstring=".$idstring."&sign=".$sign;
        
        //showmessage('getpasswd_send_succeed', "member.php?mod=getpasswd&uid=".$member['uid']."&id=".$idstring."&sign=".$sign, array(), array('showdialog' => 0, 'locationtime' => true));
        echo json_encode(array('code' => 200,'data' => 'lostpwsuccess','url' => $location));
        exit;
        
    }else{
        global $_G;
        $_config = $_G['cache']['plugin']['jzsjiale_sms'];
        
        $mobilecolor = !empty($_config['g_mobilecolor'])?$_config['g_mobilecolor']:"#4eabe8";
        if(!$_G['uid'] && $_config['g_openmobilezhaohui']){
            include template('jzsjiale_sms:lostpw');
        }else{
        
            $ucsynlogin = $_G['setting']['allowsynlogin'] ? uc_user_synlogin($_G['uid']) : '';
            dheader("Location: home.php?mod=space&do=profile&uid=".$_G['uid']);
            dexit();
        
        }
    }
    
}elseif ($act == 'bangding') {
    
    if ($formhash == FORMHASH && $_GET['phonebangdingsubmit']) {
        
        $phone = addslashes($_GET['phone']);
        $seccode = addslashes($_GET['seccode']);
        $username = addslashes($_GET['username']);
        $password = addslashes($_GET['password']);
        
        global $_G;
        $_config = $_G['cache']['plugin']['jzsjiale_sms'];
        
        
        if(!$_config['g_openmobilebangding']){
            echo json_encode(array('code' => -1,'data' => 'notopenmobile'));
            exit;
        }
        
        if(empty($phone)){
            echo json_encode(array('code' => -1,'data' => 'phonenull'));
            exit;
        }
        
        if (!preg_match("/^1[34578]{1}\d{9}$/", $phone)) {
            echo json_encode(array('code' => -1,'data' => 'bind_phone_error'));
            exit;
        }
        
        if(empty($seccode)){
            echo json_encode(array('code' => -1,'data' => 'seccodenull'));
            exit;
        }
        
        
        if(!$_G['uid']){
            if(empty($username)){
                echo json_encode(array('code' => -1,'data' => 'usernamenull'));
                exit;
            }
            
            if(empty($password)){
                echo json_encode(array('code' => -1,'data' => 'passwordnull'));
                exit;
            }
            
            
            $username = iconv('UTF-8', CHARSET.'//ignore', urldecode($username));
             
            $user = C::t('common_member')->fetch_by_username($username);
            if(empty($user)){
                echo json_encode(array('code' => -1,'data' => 'error_nouser'));
                exit;
            }
            
            $uid = $user['uid'];
            
            loaducenter();
            list($result) = uc_user_login($uid, $password, 1, 0);
            if ($result < 0){
                echo json_encode(array('code' => -1,'data' => 'err_mima'));
                exit;
            }
        }else{
            $uid = $_G['uid'];
            $username = $_G['username'];
        }
        
        
        
        $codeinfo = C::t('#jzsjiale_sms#jzsjiale_sms_code')->fetchfirst_by_phone_and_seccode($phone,$seccode);
        if ($codeinfo) {
            if ((TIMESTAMP - $codeinfo[dateline]) > $_config['g_youxiaoqi']) {
                C::t('#jzsjiale_sms#jzsjiale_sms_code')->deleteby_seccode_and_phone($phone,$seccode);
                //C::t('#jzsjiale_sms#jzsjiale_sms_smslist')->deleteby_seccode_and_phone($phone,$seccode);
                echo json_encode(array('code' => -1,'data' => 'err_seccodeguoqi'));
                exit;
            }
        } else {
            echo json_encode(array('code' => -1,'data' => 'err_seccodeerror'));
            exit;
        }
        
        if($_config['g_tongyiuser']){
            //20170805 add start
            $phoneuser =  C::t('#jzsjiale_sms#jzsjiale_sms_member')->fetch_by_mobile($phone);
            if(!empty($phoneuser)){
                echo json_encode(array('code' => -1,'data' => 'phonecunzai'));
                exit;
            }
            //weibaochitongbuxianshanchu
            C::t('#jzsjiale_sms#jzsjiale_sms_user')->deletebyuid($uid);
            
            $data = array(
                'uid' => $uid,
                'username' => $username,
                'phone' => $phone,
                'dateline' => TIMESTAMP
            );
            
            C::t('#jzsjiale_sms#jzsjiale_sms_user')->insert($data,true);
            
            if(C::t('common_member_profile')->update($uid, array('mobile'=> $phone))){
                C::t('#jzsjiale_sms#jzsjiale_sms_code')->deleteby_seccode_and_phone($phone,$seccode);
                echo json_encode(array('code' => 200,'data' => 'bangdingsuccess'));
                exit;
            }else{
                C::t('#jzsjiale_sms#jzsjiale_sms_code')->deleteby_seccode_and_phone($phone,$seccode);
                echo json_encode(array('code' => -1,'data' => 'bangdingerror'));
                exit;
            }
            
        }else{
            $phoneuser = C::t('#jzsjiale_sms#jzsjiale_sms_user')->fetch_by_phone($phone);
            
            if(!empty($phoneuser)){
                echo json_encode(array('code' => -1,'data' => 'phonecunzai'));
                exit;
            }
            
            $userinfo = C::t('#jzsjiale_sms#jzsjiale_sms_user')->fetch_by_username($username);
            if(!empty($userinfo)){
                echo json_encode(array('code' => -1,'data' => 'err_yibangding'));
                exit;
            }
            
            //weibaochitongbuxianshanchu
            C::t('#jzsjiale_sms#jzsjiale_sms_user')->deletebyuid($uid);
            
            $data = array(
                'uid' => $uid,
                'username' => $username,
                'phone' => $phone,
                'dateline' => TIMESTAMP
            );
            
            if(C::t('#jzsjiale_sms#jzsjiale_sms_user')->insert($data,true)){
            
                C::t('common_member_profile')->update($uid, array('mobile'=> $phone));
            
                C::t('#jzsjiale_sms#jzsjiale_sms_code')->deleteby_seccode_and_phone($phone,$seccode);
                echo json_encode(array('code' => 200,'data' => 'bangdingsuccess'));
                exit;
            }else{
                C::t('#jzsjiale_sms#jzsjiale_sms_code')->deleteby_seccode_and_phone($phone,$seccode);
                echo json_encode(array('code' => -1,'data' => 'bangdingerror'));
                exit;
            }
        }
        
        
    }else{
        global $_G;
        $_config = $_G['cache']['plugin']['jzsjiale_sms'];
        
        $mobilecolor = !empty($_config['g_mobilecolor'])?$_config['g_mobilecolor']:"#4eabe8";
        if((!$_G['uid'] || $_GET['qiangzhibangding'] == 'yes') && $_config['g_openmobilebangding']){
            if($_G['uid']){
                
                $isbangdingphoneok = false;
                if($_config['g_tongyiuser']){
                    //20170805 add start
                    $isbangdingphone =  C::t('#jzsjiale_sms#jzsjiale_sms_member')->fetch_by_uid($_G['uid']);
                    
                    if(!empty($isbangdingphone['mobile'])){
                        $isbangdingphoneok = true;
                    }
                    //20170805 add end
                }else{
                    $isbangdingphone = C::t('#jzsjiale_sms#jzsjiale_sms_user')->fetch_by_uid($_G['uid']);
                    if(!empty($isbangdingphone)){
                        $isbangdingphoneok = true;
                    }
                }
                
                if($isbangdingphoneok){
                    $ucsynlogin = $_G['setting']['allowsynlogin'] ? uc_user_synlogin($_G['uid']) : '';
                    dheader("Location: home.php?mod=space&do=profile&uid=".$_G['uid']);
                    dexit();
                }else{
                    include template('jzsjiale_sms:bangdinglogin');
                }
            }else{
                include template('jzsjiale_sms:bangding');
            }
            
        }else{
        
            $ucsynlogin = $_G['setting']['allowsynlogin'] ? uc_user_synlogin($_G['uid']) : '';
            dheader("Location: home.php?mod=space&do=profile&uid=".$_G['uid']);
            dexit();
        
        }
    }
    
}elseif ($act == 'tiaokuan') {
    
    global $_G;
    $_config = $_G['cache']['plugin']['jzsjiale_sms'];
    
    $mobilecolor = !empty($_config['g_mobilecolor'])?$_config['g_mobilecolor']:"#4eabe8";
    $g_tiaokuan = $_config['g_tiaokuan'];
    include template('jzsjiale_sms:tiaokuan');
    
}elseif ($act == 'getpasswd') {
    require libfile('function/member');
    if($_GET['uid'] && $_GET['idstring'] && $_GET['sign'] === make_getpws_sign($_GET['uid'], $_GET['idstring'])) {
        global $_G;
        $_config = $_G['cache']['plugin']['jzsjiale_sms'];
        
        $discuz_action = 141;
        
        
        $member = getuserbyuid($_GET['uid'], 1);
        $table_ext = isset($member['_inarchive']) ? '_archive' : '';
        $member = array_merge(C::t('common_member_field_forum'.$table_ext)->fetch($_GET['uid']), $member);
        list($dateline, $operation, $idstring) = explode("\t", $member['authstr']);
        
        if($dateline < TIMESTAMP - 86400 * 3 || $operation != 1 || $idstring != $_GET['idstring']) {
          
            if($formhash == FORMHASH && $_GET['getpwsubmit']){
                echo json_encode(array('code' => -1,'data' => 'getpasswdillegal'));
                exit;
            }else{
                showmessage('getpasswd_illegal');
            }
            
            
        }
        
        if ($formhash == FORMHASH && $_GET['getpwsubmit'] && $_GET['newpasswd1'] == $_GET['newpasswd2']) {
            
            if($_GET['newpasswd1'] != addslashes($_GET['newpasswd1'])) {
                echo json_encode(array('code' => -1,'data' => 'profilepasswd_illegal'));
                exit;
            }
            if(strlen($_GET['newpasswd1']) < 6) {
                echo json_encode(array('code' => -1,'data' => 'password6'));
                exit;
            }
            
            loaducenter();
            uc_user_edit(addslashes($member['username']), $_GET['newpasswd1'], $_GET['newpasswd1'], addslashes($member['email']), 1, 0);
            $password = md5(random(10));
            
            if(isset($member['_inarchive'])) {
                C::t('common_member_archive')->move_to_master($member['uid']);
            }
            C::t('common_member')->update($_GET['uid'], array('password' => $password));
            C::t('common_member_field_forum')->update($_GET['uid'], array('authstr' => ''));

          
            $location = "member.php?mod=logging&action=login&mobile=2";
            /*
            if($_config['g_mtiaozhuanhome']){
                @include_once './data/sysdata/cache_domain.php';
                
                $location = $domain['defaultindex'];
            }
            */
        
            echo json_encode(array('code' => 200,'data' => 'getpasswdsucceed','url' => $location));
            exit;
        }else{
            $hashid = $_GET['idstring'];
            $uid = $_GET['uid'];
            $mobilecolor = !empty($_config['g_mobilecolor'])?$_config['g_mobilecolor']:"#4eabe8";
            include template('jzsjiale_sms:getpasswd');
        }
        
    }else{
        showmessage('parameters_error');
    }
    
}

?>