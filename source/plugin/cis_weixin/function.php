<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

function is_weixin(){
  if(strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false){
		return true;
	}
}
function setlinkagearray($array){
	foreach($array as $value){
		if(!$value['foptionid']){
			$linkage['first'][$value['optionid']]=$value;
		}
		if($value['count']=='2'){
			$linkage['second'][$value['foptionid']][$value['optionid']]=$value;
		}
		if($value['count']=='3'){
			$linkage['third'][$value['foptionid']][$value['optionid']]=$value;
		}
	}
	return $linkage;
}
/*setcookie*/
function cis_setcookie($var, $value = '', $life = 0, $prefix = 1, $httponly = false) {

	global $_G;

	$config = $_G['config']['cookie'];

	$var = $_G['setting']['authkey'].'_'.$var;
	
	$_COOKIE[$var] = $value;

	if($value == '' || $life < 0) {
		$value = '';
		$life = -1;
	}
	if(defined('IN_MOBILE')) {
		$httponly = false;
	}

	$life = $life > 0 ? getglobal('timestamp') + $life : ($life < 0 ? getglobal('timestamp') - 31536000 : 0);
	$path = $httponly && PHP_VERSION < '5.2.0' ? $config['cookiepath'].'; HttpOnly' : $config['cookiepath'];

	$secure = $_SERVER['SERVER_PORT'] == 443 ? 1 : 0;
	if(PHP_VERSION < '5.2.0') {
		setcookie($var, $value, $life, $path, $config['cookiedomain'], $secure);
	} else {
		setcookie($var, $value, $life, $path, $config['cookiedomain'], $secure, $httponly);
	}
}

/*get_bro*/
function cis_get_bro(){
	global $_G;
	
	if(strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false){
		$bro='weixin';
	}elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'MQQBrowser') !== false){
		$bro='qq';
	}elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'immwa') !== false){
	  $bro='immwa';
	}elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'UCBrowser') !== false){
		$bro='uc';
	}elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'QQ/') !== false){
		$bro='q';
	}
	if($_G['setting']['cis_app_ua']){
		$cis_app_ua=explode(',',$_G['setting']['cis_app_ua']);
		foreach($cis_app_ua as $ua){
			if(strpos($_SERVER['HTTP_USER_AGENT'], $ua) !== false){
				$bro=$ua;
			}
		}
	}
	return $bro;
}


/*date*/
function cis_date($time,$s='d'){

	$offset = getglobal('member/timeoffset');
	$sysoffset = getglobal('setting/timeoffset');
	$offset = $offset == 9999 ? ($sysoffset ? $sysoffset : 0) : $offset;
	$time = $time + $offset * 3600;
	$day=gmdate($s,$time);
	return $day;
}

function cis_login($user){
	global $userseting,$settings,$_G;
	
	$logintype=$userseting['logintype']?$userseting['logintype']:$settings['logintype'];
	
	dsetcookie('sid', $_G['sid'], 86400);
	dsetcookie('auth', authcode("{$user['password']}\t{$user['uid']}", 'ENCODE'), 86400, 1, true);
	
	cis_setcookie('uid', $user['uid'], 2592000);
	cis_setcookie('username',$user['username'], 2592000);
	cis_setcookie('logintype',$logintype, 2592000);
	
	DB::query("UPDATE ".DB::table('common_session')." SET `uid` = '$user[uid]',`username` = '$user[username]',`groupid` = '$user[groupid]' WHERE sid='$_G[sid]'");
	
}

function cis_thumb($pic,$width,$height,$type=1){
	global $_G;
	
	$file=str_replace('data/attachment/', '', $pic);
	$dir=getglobal('setting/attachdir').$file;
  
	if(!file_exists($dir.'.'.$width.'_'.$height.'.jpg')){
		
		require_once libfile('class/image');
		$img = new image;
		$thumb=$img->Thumb($dir,  './immwa/'.$file.'.'.$width.'_'.$height.'.jpg', $width, $height, 'fixwr');
		
		if($thumb){
			$return= 'data/attachment/immwa/'.$file.'.'.$width.'_'.$height.'.jpg';
		}
	}else{
		$return= 'data/attachment/immwa/'.$file.'.'.$width.'_'.$height.'.jpg';
	}
	if($type==1){
		echo $return;
	}else{
		return $return;
	}
}

function cis_get_urlcontent($url){
	
	if (function_exists('file_get_contents')) {
		$file_content = @file_get_contents($url);
	} elseif (function_exists('curl_init')) {
		$curl_handle = curl_init();
		curl_setopt($curl_handle, CURLOPT_URL, $url);
		curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT,2);
		curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($curl_handle, CURLOPT_FAILONERROR,1);
		curl_setopt($curl_handle, CURLOPT_USERAGENT, 'Trackback Spam Check');
		$file_content = curl_exec($curl_handle);
		curl_close($curl_handle);
	} elseif (ini_get('allow_url_fopen') && ($file = @fopen($url, 'rb'))){


		$i = 0;
		while (!feof($file) && $i++ < 1000) {
		$file_content .= strtolower(fread($file, 4096));
		}
		fclose($file);	
	} else {
		$file_content = '';
	}
	return $file_content;
}

function cis_post($data, $url) {
	if (!function_exists('curl_init')) {
		return '';
	}
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
  curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $tmp = curl_exec($ch);
  if (curl_errno($ch)) {
    return curl_error($ch);
  }
  curl_close($ch);
  return $tmp;
}

/*========================================TEMP========================================*/
/*
$mid:file
$app:name
$type:style plugin
$terminal:pc mobile
*/
function immwa($mid,$app,$type='temp'){
	global $_G;

	$objfile=immwa_include($mid,$app,$type);
	
	if(!file_exists($objfile)) {
		immwa_api($mid,$app,$type);
	}
	return $objfile;
}

function immwa_include($mid,$appid,$type='temp'){
	global $_G;
	$app=DB::fetch_first("SELECT * FROM ".DB::table('cis_weixin_apps')." WHERE app='$appid'");
	if($app['appkey']){
		if($type=='core'){
			$objname=$_G['style']['styleid'].'_'.$_G['style']['styleid'].'_common_touch';
		}else{
			$objname=substr(md5($mid.$app['appkey']),substr($app['adddate'],9,1),8);
		}
		return DISCUZ_ROOT.'./data/template/'.$objname.'.tpl.php';
	}
}
/**/
function immwa_temp($var, $objfile) {
	//read
	if(empty($template)) {
		$template = $var['content'];
		$template = str_replace('<?exit?>', '', $template);
	}
	
	//parse
	$var_regexp = "((\\\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*(\-\>)?[a-zA-Z0-9_\x7f-\xff]*)(\[[a-zA-Z0-9_\-\.\"\'\[\]\$\x7f-\xff]+\])*)";
	$const_regexp = "([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)";

	$template = preg_replace("/([\n\r]+)\t+/s", "\\1", $template);
	$template = preg_replace("/\<\!\-\-\{(.+?)\}\-\-\>/s", "{\\1}", $template);
	
	
	$template = str_replace("{LF}", "<?=\"\\n\"?>", $template);
	$template = str_replace("{LD}", "<?=\"{\"?>", $template);
	$template = str_replace("{RD}", "<?=\"}\"?>", $template);

	$template = preg_replace("/(\\\$[a-zA-Z0-9_\[\]\'\"\$\x7f-\xff]+)\.([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)/s", "\\1['\\2']", $template);
	$template = preg_replace("/\{(\\\$[a-zA-Z0-9_\[\]\'\"\$\.\x7f-\xff]+)\}/s", "<?=\\1?>", $template);


	
	$template = ltrim($template);
	$template = "<? if(!defined('IN_DISCUZ')) exit('Access Denied'); {$headeradd}?>\n$template";

	
	
	
  $template = preg_replace_callback("/[\n\r\t]*\{eval\s+(.+?)\s*\}[\n\r\t]*/is","parse_template_callback_evaltags",$template);
              
	
	$template = preg_replace("/\{$const_regexp\}/s", "<?=\\1?>", $template);
	$template = preg_replace("/ \?\>[\n\r]*\<\? /s", " ", $template);
	
	$template = preg_replace("/\<\?(\s{1})/is", "<?php\\1", $template);
  $template = preg_replace("/\<\?\=(.+?)\?\>/is", "<?php echo \\1;?>", $template);
	
	//write
	$template = trim($template);
	
	if (CHARSET != 'gbk') {
		$template = diconv($template,'gbk',CHARSET);
	}
	if(!empty($template)) {
		immwa_writefile($objfile, $template, 'text', 'w', 0);
	}
}

function immwa_lang($var){
	
	global $_G;
	$vars = explode(':', $var);
	$path=$_G['basescript'];
	if($_G['tid']){
		$path='forum';
	}
	$language['inner'] = lang('template');
	
	if(!isset($language['inner'][$var])) {
		foreach(lang($path.'/template') as $k => $v) {
			$language['inner'][$k] = $v;
		}
	}
	if (CHARSET != 'gbk') {
		$language['inner'][$var] = diconv($language['inner'][$var],CHARSET,'gbk');
	}
	
	return $language['inner'][$var];
}

/*block*/
function immwa_stripblock($var, $s) {
	$s = str_replace('\\"', '"', $s);
	$s = preg_replace("/<\?=\\\$(.+?)\?>/", "{\$\\1}", $s);
	preg_match_all("/<\?=(.+?)\?>/e", $s, $constary);
	$constadd = '';
	$constary[1] = array_unique($constary[1]);
	foreach($constary[1] as $const) {
		$constadd .= '$__'.$const.' = '.$const.';';
	}
	$s = preg_replace("/<\?=(.+?)\?>/", "{\$__\\1}", $s);
	$s = str_replace('?>', "\n\$$var .= <<<EOF\n", $s);
	$s = str_replace('<?', "\nEOF;\n", $s);
	return "<?\n$constadd\$$var = <<<EOF\n".$s."\nEOF;\n?>";
}

/**/
function immwa_avatartags($parameter) {
	$parameter = stripslashes($parameter);
	$parameter = preg_replace("/<\?=\\\$(.+?)\?>/", "\$\\1", $parameter);
	$return = "<?php echo avatar($parameter);?>";
	return $return;
}

/**/
function immwa_addquote($var) {
	return str_replace("\\\"", "\"", preg_replace("/\[([a-zA-Z0-9_\-\.\x7f-\xff]+)\]/s", "[\\1]", $var));
}

/**/
function immwa_stripvtags($expr, $statement='') {
	$expr = str_replace("\\\"", "\"", preg_replace("/\<\?\=(\\\$.+?)\?\>/s", "\\1", $expr));
	$statement = str_replace("\\\"", "\"", $statement);
	return $expr.$statement;
}

function parse_template_callback_evaltags($matches) {
	return immwa_stripvtags('<?php '.$matches[1].'; ?>','');
}
function parse_template_callback_languagevar($matches) {
	return immwa_lang($matches[1]);
}


/*========================================IMMWA========================================*/
/**/

include DISCUZ_ROOT.'./source/plugin/cis_weixin/core.php';

//srealpath
function immwa_srealpath($path) {
	$path = str_replace('./', '', $path);
	if(DIRECTORY_SEPARATOR == '\\') {
		$path = str_replace('/', '\\', $path);
	} elseif(DIRECTORY_SEPARATOR == '/') {
		$path = str_replace('\\', '/', $path);
	}
	return $path;
}

function output_im() {

	global $_G;
	$content = ob_get_contents();
	ob_end_clean();
	ob_start();
	if('utf-8' != CHARSET) {
		$content = diconv($content, CHARSET, 'utf-8');
	}
	return $content;
}

?>