<?php
if(!defined('IN_DISCUZ')) exit('Access Denied');

class mobileplugin_gctx_spider {

	function global_footer_mobile(){
		global $_G, $navtitle;

		$lang = lang('plugin/gctx_spider');

		$seting		= $_G['cache']['plugin']['gctx_spider'];
		$isopen = $seting['isopen'];
		if($isopen != 1){
			return;
		}
		$port		= $_SERVER["SERVER_PORT"];//端口
		$useragent	= strtolower($_SERVER['HTTP_USER_AGENT']);//userAGENT
		
		$REQUEST_SCHEME = isset($_SERVER['REQUEST_SCHEME']) ? $_SERVER['REQUEST_SCHEME'] : (strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https')  === false ? 'http' : 'https');

		$url		= $REQUEST_SCHEME."://".$_SERVER["SERVER_NAME"];
		$ip			= $_SERVER['REMOTE_ADDR'];


		if ($port != 80 && $port != 443) {
			$url .= ":".$port;
		}
		$url .= $_SERVER['REQUEST_URI'] ? $_SERVER['REQUEST_URI'] : ($_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME']);

		$spidername = array(
			1	=> 'googlebot',
			2	=> 'baiduspider',
			4	=> 'yodaobot',
			5	=> 'youdaobot',
			6	=> 'yahoo! slurp china',
			7	=> 'yahoo',
			8	=> 'sogou spider',
			9	=> 'sogou web spider',
			10	=> 'sosospider',
			11	=> 'sosoimagespider',
			12	=> '360spider',
			13	=> 'qihoobot',
			14	=> 'bingbot',
		);

		$spiderkey="";
		foreach ($spidername AS $key => $value) {
			if (strpos($useragent, $value) !== false) {
				$spiderkey = $key;
				break;
			}
		}
		
		if ($spiderkey) {
			date_default_timezone_set("Etc/GMT-8");
			$data = array(
				'spidername'	=> $spiderkey,
				'ip'			=> $ip,
				'addtime'		=> time(),
				'title'			=> $lang['mobile'].$navtitle,
				'url'			=> $url,
			);

			DB::insert('gctx_spider', $data);
		}
	}
}
?>