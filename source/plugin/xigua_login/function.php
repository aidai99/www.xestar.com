<?php
/**
 * Created by www.zheyitianshi.com.
 * User: 折翼天使资源社区
 * Date: 2016/3/11
 * Time: 0:03
 */
function xg_register($username, $avatar, $auto = 1) {
    global $_G, $config;
    if(!$username) {
        return 0;
    }
    if(!$_G['wechat']['setting']) {
        $_G['wechat']['setting'] = unserialize($_G['setting']['mobilewechat']);
    }

    loaducenter();
    $groupid = $config['groupid'] ? $config['groupid'] : $_G['setting']['newusergroupid'];

    if($_GET['password']=trim($_GET['password'])){
        if($_GET['password'] != addslashes($_GET['password'])) {
            showmessage('profile_passwd_illegal');
        }
    }
    $password = $_GET['password'] ? md5($_GET['password']) : md5(random(10));

    $email = strtolower(random(12)).'@wechat.com';

    if($auto){
        $usernamelen = dstrlen($username);
        if($usernamelen < 3) {
            $username = $username.mt_rand(1000, 9999);
        }
        if($usernamelen > 15) { //uc_client/model/user.php //uc_server/model/user.php check_username 限制 15 ==>25
            $username = cutstr($username, 15, '');
        }

        if(C::t('common_member')->fetch_by_username($username) || uc_user_checkname($username)!='1'){
            $username =  cutstr($username, 13, '').mt_rand(1, 99);
            if($config['confilt']){
                return '-9999';
            }
        }
    }

    $censorexp = '/^('.str_replace(array('\\*', "\r\n", ' '), array('.*', '|', ''), preg_quote(($_G['setting']['censoruser'] = trim($_G['setting']['censoruser'])), '/')).')$/i';
    $_G['setting']['censoruser'] && @preg_replace($censorexp, '_', $username);

    $sms = '';
    @include_once DISCUZ_ROOT. 'source/discuz_version.php';
    if(DISCUZ_VERSION == 'F1.0'){
        $sms = '189'.mt_rand(10000000, 99999999);
        $uid = uc_user_register_new(addslashes($username), $password, $email, $sms, '', '', $_G['clientip']);
    }else{
        $uid = uc_user_register(addslashes($username), $password, $email, '', '', $_G['clientip']);
    }
    if($uid <= 0) {
        if($uid == -1) {
            return $uid;
//            showmessage('profile_username_illegal');
        } elseif($uid == -2) {
            return $uid;
//            showmessage('profile_username_protect');
        } elseif($uid == -3) {
            return $uid;
//            showmessage('profile_username_duplicate');
        } elseif($uid == -4) {
            showmessage('profile_email_illegal');
        } elseif($uid == -5) {
            showmessage('profile_email_domain_illegal');
        } elseif($uid == -6) {
            showmessage('profile_email_duplicate');
        } else {
            showmessage($sms.'_'.$uid.'_undefined_action');
        }
        return $uid;
    }

    $init_arr = array('credits' => explode(',', $_G['setting']['initcredits']), 'emailstatus' => 1);
    if($sms) {
        C::t('common_member')->insert_new($uid, $username, $password, $email, $sms, $_G['clientip'], $groupid, $init_arr);
    }else{
        C::t('common_member')->insert($uid, $username, $password, $email, $_G['clientip'], $groupid, $init_arr);
    }


    if($_G['setting']['regctrl'] || $_G['setting']['regfloodctrl']) {
        C::t('common_regip')->delete_by_dateline($_G['timestamp']-($_G['setting']['regctrl'] > 72 ? $_G['setting']['regctrl'] : 72)*3600);
        if($_G['setting']['regctrl']) {
            C::t('common_regip')->insert(array('ip' => $_G['clientip'], 'count' => -1, 'dateline' => $_G['timestamp']));
        }
    }

    if($_G['setting']['regverify'] == 2) {
        C::t('common_member_validate')->insert(array(
            'uid' => $uid,
            'submitdate' => $_G['timestamp'],
            'moddate' => 0,
            'admin' => '',
            'submittimes' => 1,
            'status' => 0,
            'message' => '',
            'remark' => '',
        ), false, true);
        manage_addnotify('verifyuser');
    }

    setloginstatus(array(
        'uid' => $uid,
        'username' => $username,
        'password' => $password,
        'groupid' => $groupid,
    ), 0);

    C::t('common_member_status')->update($uid, array('lastip'=>$_G['clientip'], 'lastvisit'=>TIMESTAMP, 'lastactivity' => TIMESTAMP));
    $ucsynlogin = '';
    if($_G['setting']['allowsynlogin']) {
        loaducenter();
        $ucsynlogin = uc_user_synlogin($uid);
    }

    //统计
    include_once libfile('function/stat');
    updatestat('register');
    xg_syncAvatar($uid, $avatar);

    if(!function_exists('build_cache_userstats')) {
        require_once libfile('cache/userstats', 'function');
    }
    build_cache_userstats();

    return $uid;
}

function xg_syncAvatar($uid, $avatar) {

    if(!$uid || !$avatar) {
        return false;
    }

    if(!$content = dfsockopen($avatar)) {
        return false;
    }

    $tmpFile = DISCUZ_ROOT.'./data/avatar/'.TIMESTAMP.random(6);
    file_put_contents($tmpFile, $content);

    if(!is_file($tmpFile)) {
        return false;
    }

    $result = uploadUcAvatar1::upload($uid, $tmpFile);
    unlink($tmpFile);

    C::t('common_member')->update($uid, array('avatarstatus'=>'1'));

    return $result;
}

class uploadUcAvatar1 {

    /**
     * 上传至uc头像
     */
    public static function upload($uid, $localFile) {

        global $_G;
        if(!$uid || !$localFile) {
            return false;
        }

        list($width, $height, $type, $attr) = getimagesize($localFile);
        if(!$width) {
            return false;
        }

        if($width < 10 || $height < 10 || $type == 4) {
            return false;
        }

        $imageType = array(1 => '.gif', 2 => '.jpg', 3 => '.png');
        $fileType = $imageType[$type];
        if(!$fileType) {
            $fileType = '.jpg';
        }
        $avatarPath = $_G['setting']['attachdir'];
        $tmpAvatar = $avatarPath.'./temp/upload'.$uid.$fileType;
        file_exists($tmpAvatar) && @unlink($tmpAvatar);
        file_put_contents($tmpAvatar, file_get_contents($localFile));

        if(!is_file($tmpAvatar)) {
            return false;
        }

        $tmpAvatarBig = './temp/upload'.$uid.'big'.$fileType;
        $tmpAvatarMiddle = './temp/upload'.$uid.'middle'.$fileType;
        $tmpAvatarSmall = './temp/upload'.$uid.'small'.$fileType;

        $image = new image;
        if($image->Thumb($tmpAvatar, $tmpAvatarBig, 200, 250, 1) <= 0) {
            $tmpAvatarBig = str_replace($avatarPath, '', $tmpAvatar);
        }
        if($image->Thumb($tmpAvatar, $tmpAvatarMiddle, 120, 120, 1) <= 0) {
            $tmpAvatarMiddle = str_replace($avatarPath, '', $tmpAvatar);
        }
        if($image->Thumb($tmpAvatar, $tmpAvatarSmall, 48, 48, 2) <= 0) {
            $tmpAvatarSmall = str_replace($avatarPath, '', $tmpAvatar);
        }

        $tmpAvatarBig = $avatarPath.$tmpAvatarBig;
        $tmpAvatarMiddle = $avatarPath.$tmpAvatarMiddle;
        $tmpAvatarSmall = $avatarPath.$tmpAvatarSmall;

        $avatar1 = self::byte2hex(file_get_contents($tmpAvatarBig));
        $avatar2 = self::byte2hex(file_get_contents($tmpAvatarMiddle));
        $avatar3 = self::byte2hex(file_get_contents($tmpAvatarSmall));

        $extra = '&avatar1='.$avatar1.'&avatar2='.$avatar2.'&avatar3='.$avatar3;
        $result = self::uc_api_post_ex('user', 'rectavatar', array('uid' => $uid), $extra);

        if(!$avatar3 || !$result){
            $avatartype = '';
            $bigavatarfile = DISCUZ_ROOT.'./uc_server/data/avatar/'.self::get_avatar($uid, 'big', $avatartype);
            $middleavatarfile = DISCUZ_ROOT.'./uc_server/data/avatar/'.self::get_avatar($uid, 'middle', $avatartype);
            $smallavatarfile = DISCUZ_ROOT.'./uc_server/data/avatar/'.self::get_avatar($uid, 'small', $avatartype);
            file_put_contents($bigavatarfile, file_get_contents($tmpAvatarBig));
            file_put_contents($middleavatarfile, file_get_contents($tmpAvatarMiddle));
            file_put_contents($smallavatarfile, file_get_contents($tmpAvatarSmall));

        }


        @unlink($tmpAvatar);
        @unlink($tmpAvatarBig);
        @unlink($tmpAvatarMiddle);
        @unlink($tmpAvatarSmall);

        return true;
    }
    function get_avatar($uid, $size = 'big', $type = '') {
        $size = in_array($size, array('big', 'middle', 'small')) ? $size : 'big';
        $uid = abs(intval($uid));
        $uid = sprintf("%09d", $uid);
        $dir1 = substr($uid, 0, 3);
        $dir2 = substr($uid, 3, 2);
        $dir3 = substr($uid, 5, 2);
        $typeadd = $type == 'real' ? '_real' : '';
        return  $dir1.'/'.$dir2.'/'.$dir3.'/'.substr($uid, -2).$typeadd."_avatar_$size.jpg";
    }
    public static function byte2hex($string) {
        $buffer = '';
        $value = unpack('H*', $string);
        $value = str_split($value[1], 2);
        $b = '';
        foreach($value as $k => $v) {
            $b .= strtoupper($v);
        }

        return $b;
    }

    public static function uc_api_post_ex($module, $action, $arg = array(), $extra = '') {
        $s = $sep = '';
        foreach($arg as $k => $v) {
            $k = urlencode($k);
            if(is_array($v)) {
                $s2 = $sep2 = '';
                foreach($v as $k2 => $v2) {
                    $k2 = urlencode($k2);
                    $s2 .= "$sep2{$k}[$k2]=".urlencode(uc_stripslashes($v2));
                    $sep2 = '&';
                }
                $s .= $sep.$s2;
            } else {
                $s .= "$sep$k=".urlencode(uc_stripslashes($v));
            }
            $sep = '&';
        }
        $postdata = uc_api_requestdata($module, $action, $s, $extra);
        return uc_fopen2(UC_API.'/index.php', 500000, $postdata, '', TRUE, UC_IP, 20);
    }
}