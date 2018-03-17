<?php
if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}
$micxp_settgin=$_G['cache']['plugin']['micxp_xzpush'];
$gids = (array)unserialize($micxp_settgin['M_groups']);
if(submitcheck('formhash')){
    if (!in_array($_G['groupid'], $gids)) {
        helper_output::xml(lang('plugin/micxp_xzpush','noqx'));
    }
    $baidu_api='http://data.zz.baidu.com/urls?appid='.$micxp_settgin[M_appid].'&token='.$micxp_settgin[M_token].'&type=realtime';
    if(isset($_GET['isoriginal']) && !empty($_GET['isoriginal'])){
        $baidu_api='http://data.zz.baidu.com/urls?appid='.$micxp_settgin[M_appid].'&token='.$micxp_settgin[M_token].'&type=realtime,original';
    }
    $url=daddslashes($_GET['pushurl']);
    
    $urls = array(
        $url
    );
    $ch = curl_init();
    $options =  array(
        CURLOPT_URL => $baidu_api,
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POSTFIELDS => implode("\n", $urls),
        CURLOPT_HTTPHEADER => array('Content-Type: text/plain'),
    );
    curl_setopt_array($ch, $options);
    $result = curl_exec($ch);
    //{"success_realtime":1,"remain_realtime":99}
    $result_arr=json_decode($result,true);
    
    if($result_arr['success_realtime']){
        $returnmsg=lang('plugin/micxp_xzpush','push_message',array('1'=>$result_arr['success_realtime'],'2'=>$result_arr['remain_realtime']));
        helper_output::xml($returnmsg);
    }
    
    helper_output::xml(dhtmlspecialchars($result));
}