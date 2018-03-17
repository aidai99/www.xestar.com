<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$act = addslashes($_GET['act']);
$formhash =  addslashes($_GET['formhash'])? addslashes($_GET['formhash']):'';
loadcache('plugin');
global $_G, $lang;

require_once DISCUZ_ROOT.'./source/plugin/jzsjiale_sms/smstools.inc.php';


if (empty($act)) {
    global $_G;
    $_config = $_G['cache']['plugin']['jzsjiale_sms'];
    
    if (! $_config['g_openpclogin']) {
        showmessage(lang('plugin/jzsjiale_sms', 'err_gongnengweikaiqi'));
    }
        
    $navtitle = lang('plugin/jzsjiale_sms', 'loginpopuptitle');
    require_once libfile('function/misc');
    loaducenter();
    if ($_G['uid']) {
        $referer = dreferer();
        $ucsynlogin = $_G['setting']['allowsynlogin'] ? uc_user_synlogin($_G['uid']) : '';
        $param = array('username' => $_G['member']['username'], 'usergroup' => $_G['group']['grouptitle'], 'uid' => $_G['member']['uid']);
        showmessage('login_succeed', $referer ? $referer : './', $param, array('showdialog' => 1, 'locationtime' => true, 'extrajs' => $ucsynlogin));
    }
    require_once libfile('function/member');

    if (submitcheck('loginsubmit')) {
        $phone = daddslashes($_GET['phone_login']);
        $seccode = daddslashes($_GET['seccode']);
        if (!$phone || !$seccode){
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
        
        C::t('#jzsjiale_sms#jzsjiale_sms_code')->deleteby_seccode_and_phone($phone,$seccode);
        
        $_G['uid'] = $_G['member']['uid'] = 0;
        $_G['username'] = $_G['member']['username'] = $_G['member']['password'] = '';
        
        if($_config['g_tongyiuser']){
            //20170805 add start
            $userinfo =  C::t('#jzsjiale_sms#jzsjiale_sms_member')->fetch_by_mobile($phone);
            if(empty($userinfo['mobile'])){
                showmessage(lang('plugin/jzsjiale_sms', 'err_weibangding'));
            }
            //20170805 add end
        }else{
            $userinfo = C::t('#jzsjiale_sms#jzsjiale_sms_user')->fetch_by_phone($phone);
            if(!empty($userinfo)){
                if(empty($userinfo['username'])){
                    C::t('#jzsjiale_sms#jzsjiale_sms_user')->deletebyid($userinfo['id']);
                    showmessage(lang('plugin/jzsjiale_sms', 'err_weibangding'.$userinfo['id']));
                    $userinfo = array();
                }
            }
            if(empty($userinfo)){
                showmessage(lang('plugin/jzsjiale_sms', 'err_weibangding'));
            }
        }
        
   
        $member = getuserbyuid($userinfo['uid'], 1);
        if (!$member || empty($member['uid'])) {
            C::t('#jzsjiale_sms#jzsjiale_sms_user')->deletebyphone($phone);
            showmessage(lang('plugin/jzsjiale_sms', 'err_weibangding'));
        }
        if ($member['_inarchive']) {
            C::t('common_member_archive')->move_to_master($member['uid']);
        }
        setloginstatus($member, $_GET['cookietime'] ? 2592000 : 0);
        checkfollowfeed();
        if ($_G['group']['forcelogin']) {
            if ($_G['group']['forcelogin'] == 1) {
                clearcookies();
                showmessage('location_login_force_qq');
            } elseif ($_G['group']['forcelogin'] == 2 && $_GET['loginfield'] != 'email') {
                clearcookies();
                showmessage('location_login_force_mail');
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
                showmessage('location_login_outofdate', 'home.php?mod=spacecp&ac=profile&op=password&resend=1', array('type' => 1), array('showdialog' => true, 'striptags' => false, 'locationtime' => true));
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
        $location = $_G['groupid'] == 8 ? 'home.php?mod=space&do=home' : dreferer();
        showmessage($loginmessage, $location, $param, $extra);
    } else {
        
        
        if($_config['g_phonemimalogin']){
            include template('jzsjiale_sms:loginmima');
        }else{
            include template('jzsjiale_sms:login');
        }
        
    }
}elseif ($act == 'phonemimalogin') {
    global $_G;
    $_config = $_G['cache']['plugin']['jzsjiale_sms'];
    
    if (! $_config['g_openpclogin']) {
        showmessage(lang('plugin/jzsjiale_sms', 'err_gongnengweikaiqi'));
    }
    
    if (! $_config['g_phonemimalogin']) {
        showmessage(lang('plugin/jzsjiale_sms', 'err_notopenphonemimalogin'));
    }
        
    $navtitle = lang('plugin/jzsjiale_sms', 'loginpopuptitle');
    require_once libfile('function/misc');
    loaducenter();
    if ($_G['uid']) {
        $referer = dreferer();
        $ucsynlogin = $_G['setting']['allowsynlogin'] ? uc_user_synlogin($_G['uid']) : '';
        $param = array('username' => $_G['member']['username'], 'usergroup' => $_G['group']['grouptitle'], 'uid' => $_G['member']['uid']);
        showmessage('login_succeed', $referer ? $referer : './', $param, array('showdialog' => 1, 'locationtime' => true, 'extrajs' => $ucsynlogin));
    }
    require_once libfile('function/member');

    if (submitcheck('loginsubmit')) {
        $phone = daddslashes($_GET['phone_login']);
        $phone_password = daddslashes($_GET['phone_password']);
        
        if(empty($phone)){
            showmessage(lang('plugin/jzsjiale_sms', 'phonenull'));
        }
        
        if (!preg_match("/^1[34578]{1}\d{9}$/", $phone)) {
            showmessage(lang('plugin/jzsjiale_sms', 'bind_phone_error'));
        }
        
        if(empty($phone_password)){
            showmessage(lang('plugin/jzsjiale_sms', 'passwordnull'));
        }
        
        if (strlen($phone_password)<6) {
            showmessage(lang('plugin/jzsjiale_sms', 'password6'));
        }
        
        
        //20170816 start
        if($_config['g_isopenmimaimgcode']){
            if(empty($_GET['seccodeverify']) || empty($_GET['seccodehash'])){
                showmessage(lang('plugin/jzsjiale_sms', 'paramerror'));
               
            }
            if (!check_seccode($_GET['seccodeverify'], $_GET['seccodehash'])) {
                showmessage(lang('plugin/jzsjiale_sms', 'seccode_invalid'));
                
            }
        }
        
        //20170816 end
        
        
        $_G['uid'] = $_G['member']['uid'] = 0;
        $_G['username'] = $_G['member']['username'] = $_G['member']['password'] = '';
        
        if($_config['g_tongyiuser']){
            //20170805 add start
            $userinfo =  C::t('#jzsjiale_sms#jzsjiale_sms_member')->fetch_by_mobile($phone);
            if(empty($userinfo['mobile'])){
                showmessage(lang('plugin/jzsjiale_sms', 'err_weibangding'));
            }
            //20170805 add end
        }else{
            $userinfo = C::t('#jzsjiale_sms#jzsjiale_sms_user')->fetch_by_phone($phone);
            if(!empty($userinfo)){
                if(empty($userinfo['username'])){
                    C::t('#jzsjiale_sms#jzsjiale_sms_user')->deletebyid($userinfo['id']);
                    showmessage(lang('plugin/jzsjiale_sms', 'err_weibangding'.$userinfo['id']));
                    $userinfo = array();
                }
            }
            
            if(empty($userinfo)){
                showmessage(lang('plugin/jzsjiale_sms', 'err_weibangding'));
            }
        }
        
        
        $member = getuserbyuid($userinfo['uid'], 1);
        if (!$member || empty($member['uid'])) {
            C::t('#jzsjiale_sms#jzsjiale_sms_user')->deletebyphone($phone);
            showmessage(lang('plugin/jzsjiale_sms', 'err_weibangding'));
        }
        
        
        
        if(file_exists(DISCUZ_ROOT.'./source/plugin/jzsjiale_sms/uc.inc.php')){
            require_once DISCUZ_ROOT.'./source/plugin/jzsjiale_sms/uc.inc.php';
        }else{
            showmessage(lang('plugin/jzsjiale_sms', 'err_systemerror'));
        }
        
        
        $questionid = daddslashes($_GET['questionid']);
        $answer = daddslashes($_GET['answer']);
         
        if(intval($questionid) > 0 && empty($answer)){
            showmessage(lang('plugin/jzsjiale_sms', 'answernull'));
        }
        if(intval($questionid) == 0){
            $questionid = "";
            $answer = "";
        }
        
        
        $uid = UC::logincheck($member['username'],$phone_password,$questionid,$answer);
        
        if (!is_numeric($uid)) {
        
            if($uid == "too_many_errors"){
                showmessage(lang('plugin/jzsjiale_sms', 'logintoomanyerror'));
            }else{
                showmessage(lang('plugin/jzsjiale_sms', 'loginerror'));
            }
        
        }
        
        if($uid == -2) {
            showmessage(lang('plugin/jzsjiale_sms', 'mimaerror'));
        } elseif($uid == -3) {
            showmessage(lang('plugin/jzsjiale_sms', 'answerset'));
        } elseif($uid <= 0 && $uid != -2 && $uid != -3) {
            showmessage(lang('plugin/jzsjiale_sms', 'loginerror'));
        }
        
        if($member['uid'] != $uid){
            showmessage(lang('plugin/jzsjiale_sms', 'loginerror'));
        }
        
        

        if ($member['_inarchive']) {
            C::t('common_member_archive')->move_to_master($member['uid']);
        }
        setloginstatus($member, $_GET['cookietime'] ? 2592000 : 0);
        checkfollowfeed();
        if ($_G['group']['forcelogin']) {
            if ($_G['group']['forcelogin'] == 1) {
                clearcookies();
                showmessage('location_login_force_qq');
            } elseif ($_G['group']['forcelogin'] == 2 && $_GET['loginfield'] != 'email') {
                clearcookies();
                showmessage('location_login_force_mail');
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
                showmessage('location_login_outofdate', 'home.php?mod=spacecp&ac=profile&op=password&resend=1', array('type' => 1), array('showdialog' => true, 'striptags' => false, 'locationtime' => true));
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
        $location = $_G['groupid'] == 8 ? 'home.php?mod=space&do=home' : dreferer();
        showmessage($loginmessage, $location, $param, $extra);
    } else {
        include template('jzsjiale_sms:loginmima');
    }
}elseif ($act == 'sendseccode') {
    
    if ($formhash == FORMHASH) {
  
        global $_G;
        $_config = $_G['cache']['plugin']['jzsjiale_sms'];
        $g_accesskeyid = $_config['g_accesskeyid'];
        $g_accesskeysecret = $_config['g_accesskeysecret'];
        $webbianma = $_G['charset'];
        //$g_xiane = $_config['g_xiane'];
        $g_xiane = !empty($_config['g_xiane'])?$_config['g_xiane']:10;
        
        $g_templateid = "";
        $g_sign = "";
        $type = intval($_GET[type]);
        //type 0ceshi 1zhuce 2shenfenyanzheng 3denglu 4xiugaimima
        
        
        if(empty($g_accesskeyid)){
            echo json_encode(array('code' => -1,'data' => 'peizhierror'));
            exit;
        }
        if(empty($g_accesskeysecret)){
            echo json_encode(array('code' => -1,'data' => 'peizhierror'));
            exit;
        }
        
        
        if($type == 1){
            $g_openregister = $_config['g_openregister'];
            
            if(!$g_openregister){
                echo json_encode(array('code' => -1,'data' => 'notopenregister'));
                exit;
            }else{
                $g_templateid = $_config['g_registerid'];
                $g_sign = $_config['g_registersign'];
            }
        }elseif($type == 2){
            $g_openyanzheng = $_config['g_openyanzheng'];
            
            if(!$g_openyanzheng){
                echo json_encode(array('code' => -1,'data' => 'notopenyanzheng'));
                exit;
            }else{
                $g_templateid = $_config['g_yanzhengid'];
                $g_sign = $_config['g_yanzhengsign'];
            }
        }elseif($type == 3){
            $g_openlogin = $_config['g_openlogin'];
            
            if(!$g_openlogin){
                echo json_encode(array('code' => -1,'data' => 'notopenlogin'));
                exit;
            }else{
                $g_templateid = $_config['g_loginid'];
                $g_sign = $_config['g_loginsign'];
            }
        }elseif($type == 4){
            $g_openmima = $_config['g_openmima'];
            
            if(!$g_openmima){
                echo json_encode(array('code' => -1,'data' => 'notopenmima'));
                exit;
            }else{
                $g_templateid = $_config['g_mimaid'];
                $g_sign = $_config['g_mimasign'];
            }
        }else{
            $g_openyanzheng = $_config['g_openyanzheng'];
            
            if(!$g_openyanzheng){
                echo json_encode(array('code' => -1,'data' => 'notopenyanzheng'));
                exit;
            }else{
                $g_templateid = $_config['g_yanzhengid'];
                $g_sign = $_config['g_yanzhengsign'];
            }
        }
        
        
        
        $phone = addslashes($_GET['phone']);
        if(empty($phone)){
            echo json_encode(array('code' => -1,'data' => 'paramerror'));
            exit;
        }
        if (!preg_match("/^1[34578]{1}\d{9}$/", $phone)) {
            echo json_encode(array('code' => -1,'data' => 'bind_phone_error'));
            exit;
        }
        
        if($_config['g_isopenimgcode']){
            if(empty($_GET['seccodeverify']) || empty($_GET['seccodehash'])){
                echo json_encode(array('code' => -1,'data' => 'paramerror'));
                exit;
            }
            if (!check_seccode($_GET['seccodeverify'], $_GET['seccodehash'])) {
                echo json_encode(array('code' => -1,'data' => 'seccode_invalid'));
                exit;
            }
        }
        
        
        if($_config['g_tongyiuser']){
            //20170805 add start
            $user =  C::t('#jzsjiale_sms#jzsjiale_sms_member')->fetch_by_mobile($phone);
            //20170805 add end
        }else{
            $user = C::t('#jzsjiale_sms#jzsjiale_sms_user')->fetch_by_phone($phone);
        }
  
       
        if ($user && ($type == 1 || $type == 2)) {
            echo json_encode(array('code' => -1,'data' => 'err_phonebind'));
            exit;
        }
        if (!$user && ($type == 3 || $type == 4)) {
            echo json_encode(array('code' => -1,'data' => 'err_nouser'));
            exit;
        }
        
        
        
        $phonesendcount = C::t('#jzsjiale_sms#jzsjiale_sms_smslist')->count_by_phone_day($phone);
        
        if($phonesendcount >= $g_xiane){
            echo json_encode(array('code' => -1,'data' => 'err_seccodexiane'));
            exit;
        }
        
        
        
        if(empty($g_templateid)){
            echo json_encode(array('code' => -1,'data' => 'peizhierror'));
            exit;
        }
        if(empty($g_sign)){
            echo json_encode(array('code' => -1,'data' => 'peizhierror'));
            exit;
        }
        
        $code = generate_code();
        
        if(empty($code) || $code == null){
            echo json_encode(array('code' => -1,'data' => 'generatecodeerror'));
            exit;
        }
        
        $g_product = $_config['g_product'];
        $sms_param_array = array();
        $sms_param_array['code']=(string)$code;
        $sms_param_array['product']=!empty($g_product)?$g_product:'';
        
        $sms_param_array['product'] = getbianma($sms_param_array['product'],$webbianma);
        
        $sms_param = json_encode($sms_param_array);
        
        
        $g_sign=getbianma($g_sign,$webbianma);
        
        //quoqishijian
        $g_youxiaoqi = $_config['g_youxiaoqi'];
        if(empty($g_youxiaoqi)){
            $g_youxiaoqi = 600;
        }
        //echo "====".date('Y-m-d H:i:s',strtotime("+".$g_youxiaoqi." second"));exit;
        $expire = strtotime("+".$g_youxiaoqi." second");
        
        $uid = $_G['uid'];
        
       
        $retdata = "";
        $phonecode = C::t('#jzsjiale_sms#jzsjiale_sms_code')->fetchfirst_by_phone($phone);
        if ($phonecode) {
            if (($phonecode['dateline'] + 60) > TIMESTAMP) {
                echo json_encode(array('code' => -1,'data' => 'err_seccodefasong'));
                exit;
            } else {
                $smstools = new SMSTools();
                $smstools->__construct($g_accesskeyid, $g_accesskeysecret);
                $retdata = $smstools->smssend($code,$expire,$type,$uid,$phone,$g_sign,$g_templateid,$sms_param);
            }
        } else {
                $smstools = new SMSTools();
                $smstools->__construct($g_accesskeyid, $g_accesskeysecret);
                $retdata = $smstools->smssend($code,$expire,$type,$uid,$phone,$g_sign,$g_templateid,$sms_param);
        }
      

        switch ($retdata){
            case 'success':
                echo json_encode(array('code' => 200,'data' => 'smssuccess'));
                break;
            case 'isv.MOBILE_NUMBER_ILLEGAL':
                echo json_encode(array('code' => -1,'data' => 'isvMOBILE_NUMBER_ILLEGAL'));
                break;
            case 'isv.BUSINESS_LIMIT_CONTROL':
                echo json_encode(array('code' => -1,'data' => 'isvBUSINESS_LIMIT_CONTROL'));
                break;
            case 'error':
                echo json_encode(array('code' => -1,'data' => 'smserror'));
                break;
            default:
                echo json_encode(array('code' => -1,'data' => 'smserror'));
                break;
        }
        
    } else {
        include template('jzsjiale_sms:sendseccode');
    }
}elseif ($act == 'lostpw') {
    global $_G;
    $_config = $_G['cache']['plugin']['jzsjiale_sms'];
 
    if (!$_config['g_openpczhaohui']) {
        showmessage(lang('plugin/jzsjiale_sms', 'err_gongnengweikaiqi'));
    }
    if (submitcheck('lostpwsubmit')) {
        $phone = addslashes($_GET['phone_zhaohui']);
        $seccode = addslashes($_GET['seccode']);
        if (! $phone || ! $seccode) {
            showmessage(lang('plugin/jzsjiale_sms', 'paramerror'));
        }
        
        if (! preg_match("/^1[34578]{1}\d{9}$/", $phone)) {
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
        
        
        
        C::t('#jzsjiale_sms#jzsjiale_sms_code')->deleteby_seccode_and_phone($phone,$seccode);
        
        if($_config['g_tongyiuser']){
            //20170805 add start
            $userinfo =  C::t('#jzsjiale_sms#jzsjiale_sms_member')->fetch_by_mobile($phone);
            if(empty($userinfo['mobile'])){
                showmessage(lang('plugin/jzsjiale_sms', 'err_weibangding'));
            }
        }else{
            $userinfo = C::t('#jzsjiale_sms#jzsjiale_sms_user')->fetch_by_phone($phone);
            if(!empty($userinfo)){
                if(empty($userinfo['username'])){
                    C::t('#jzsjiale_sms#jzsjiale_sms_user')->deletebyid($userinfo['id']);
                    showmessage(lang('plugin/jzsjiale_sms', 'err_weibangding'.$userinfo['id']));
                    $userinfo = array();
                }
            }
            
            if(empty($userinfo)){
                showmessage(lang('plugin/jzsjiale_sms', 'err_weibangding'));
            }
        }
        
        
        $member = getuserbyuid($userinfo['uid'], 1);
        if (!$member || empty($member['uid'])) {
            C::t('#jzsjiale_sms#jzsjiale_sms_user')->deletebyphone($phone);
            showmessage('getpasswd_account_notmatch');
        }elseif ($member['adminid'] == 1 || $member['adminid'] == 2) {
            showmessage('getpasswd_account_invalid');
        }
        
       
        
        $table_ext = $member['_inarchive'] ? '_archive' : '';
        $idstring = random(6);
        C::t('common_member_field_forum' . $table_ext)->update($member['uid'], array('authstr' => "$_G[timestamp]\t1\t$idstring"));
        require libfile('function/member');
        $sign = make_getpws_sign($member['uid'], $idstring);
        showmessage('getpasswd_send_succeed', "member.php?mod=getpasswd&uid=".$member['uid']."&id=".$idstring."&sign=".$sign, array(), array('showdialog' => 0, 'locationtime' => true));
    }
}

function getbianma($data, $webbianma = "gbk")
{
    if ($webbianma == "gbk") {
        $data = diconv($data, 'GB2312', 'UTF-8');
    }
    $data = isset($data) ? trim(htmlspecialchars($data, ENT_QUOTES)) : '';
    return $data;
}

function generate_code($length = 6)
{
    return rand(pow(10, ($length - 1)), pow(10, $length) - 1);
}
?>