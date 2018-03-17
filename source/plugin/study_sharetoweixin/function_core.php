<?php
/**
 * 折翼天使资源社区源码论坛 全网首发 http://www.zheyitianshi.com
 * From www.zheyitianshi.com
 */

if(!defined('IN_DISCUZ')) {
   exit('http://127.0.0.1/');
}

function _study_sharetoweixin_get_qrcode_url($tid){
		global $_G;
		$splugin_setting = $_G['cache']['plugin']['study_sharetoweixin'];
		$_info = array();
		$_info['uid'] = $_G['uid'];
		$_info['tid'] = $tid;
		$_info['timestamp'] = $_G['timestamp'];
		$_info = base64_encode(serialize($_info));
		$_md5Check = substr(md5($_info.md5($_G['config']['security']['authkey'].'Pow'.'ered by ww'.'w.131'.'4stu'.'dy.co'.'m')), 8, 8);
		$threadurl = $_G['siteurl'].'forum.php?mod=viewthread&tid='.$tid.'&s_info='.$_info.'&s_md5check='.$_md5Check;
		$splugin_setting['study_dwz2'] && $threadurl = _dwz($threadurl);
		$threadurl = urlencode($threadurl);
		$qrcode_api = array(
			 '1' => 'http://chart.googleapis.com/chart?cht=qr&chs=281x281&choe=UTF-8&chld=L|2&chl=', 
			 '2' => 'https://chart.googleapis.com/chart?cht=qr&chs=281x281&choe=UTF-8&chld=L|2&chl=', 
			 '3' => 'http://b.bshare.cn/barCode?site=weixin&url=',
			 '4' => 'http://s.jiathis.com/qrcode.php?url=',
		);
		$study_qrcode_api = intval($splugin_setting['study_qrcode_api']);
		$qrcode_url = $qrcode_api[$study_qrcode_api].$threadurl;
		return $qrcode_url;
}
function _dwz($url){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://www.zheyitianshi.com/create.php");
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$data = array('url' => $url);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		$strRes = curl_exec($ch);
		curl_close($ch);
		$arrResponse = json_decode($strRes, true);
		if($arrResponse['tinyurl']){
			return $arrResponse['tinyurl'];
		}else{
			return $url;
		}
}
function _study_sharetoweixin_authkey($tid){
		global $_G;
		return substr(md5(md5(intval($tid).$_G['uid'].$_G['authkey']).$_G['formhash']), 8, 8);
}
//From:www.zheyitianshi.com
?>

