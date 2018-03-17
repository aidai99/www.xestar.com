<?php

/*
	[Cis!] (C)2005-2013
	This is NOT a freeware, use is subject to license terms

	$Id: install.php 2014-04-01 $
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

function immwa_random($length, $numeric = 0) {
	PHP_VERSION < '4.2.0' && mt_srand((double)microtime() * 1000000);
	if($numeric) {
		$hash = sprintf('%0'.$length.'d', mt_rand(0, pow(10, $length) - 1));
	} else {
		$hash = '';
		$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
		$max = strlen($chars) - 1;
		for($i = 0; $i < $length; $i++) {
			$hash .= $chars[mt_rand(0, $max)];
		}
	}
	return $hash;
}
	
function immwa_generate_key() {
	$random = immwa_random(32);
	$info = md5($_SERVER['SERVER_SOFTWARE'].$_SERVER['SERVER_NAME'].$_SERVER['SERVER_ADDR'].$_SERVER['SERVER_PORT'].$_SERVER['HTTP_USER_AGENT'].time());
	$return = array();
	for($i=0; $i<32; $i++) {
		$return[$i] = $random[$i].$info[$i];
	}
	return implode('', $return);
}
$loginkey=substr(immwa_generate_key(), 0, 8);

$sql = <<<EOF

DROP TABLE IF EXISTS pre_cis_weixin;
CREATE TABLE `pre_cis_weixin` (
  `id` int(10) NOT NULL auto_increment,
  `type` varchar(255) NOT NULL,
  `back` mediumtext NOT NULL,
  `dateline` int(10) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;


DROP TABLE IF EXISTS pre_cis_weixin_apps;
CREATE TABLE `pre_cis_weixin_apps` (
  `siteid` varchar(8) NOT NULL,
  `app` varchar(100) NOT NULL,
  `appkey` varchar(100) NOT NULL,
  `state` tinyint(1) NOT NULL default '0',
  `adddate` int(10) NOT NULL,
  PRIMARY KEY  (`siteid`)
) ENGINE=MyISAM;


DROP TABLE IF EXISTS pre_cis_weixin_immwalog;
CREATE TABLE `pre_cis_weixin_immwalog` (
  `lid` mediumint(8) NOT NULL auto_increment,
  `mid` char(8) NOT NULL,
  `logtime` int(10) NOT NULL,
  `return` smallint(6) NOT NULL default '0',
  PRIMARY KEY  (`lid`)
) ENGINE=MyISAM;



DROP TABLE IF EXISTS pre_cis_weixin_styles;
CREATE TABLE `pre_cis_weixin_styles` (
  `sid` mediumint(9) NOT NULL auto_increment,
  `name` varchar(80) NOT NULL,
  `list` mediumint(8) NOT NULL default '0',
  `default` tinyint(1) NOT NULL default '0',
  `canuse` tinyint(1) NOT NULL default '0',
  `var` mediumtext NOT NULL,
  PRIMARY KEY  (`sid`)
) ENGINE=MyISAM;


DROP TABLE IF EXISTS pre_cis_weixin_uc;
CREATE TABLE `pre_cis_weixin_uc` (
  `uid` int(10) NOT NULL,
  `style` mediumint(8) NOT NULL default '0',
  `logintype` int(1) NOT NULL default '0',
  `openid` varchar(200) default NULL,
  PRIMARY KEY  (`uid`)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS pre_cis_weixin_hack;
CREATE TABLE `pre_cis_weixin_hack` (
  `id` char(32) NOT NULL,
  `type` varchar(20) NOT NULL default 'plugin',
  `name` char(32) NOT NULL,
  `dir` char(32) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;


REPLACE INTO `pre_common_setting` VALUES ('mobilelogin', '1');
REPLACE INTO `pre_common_setting` VALUES ('logintype', '1');
REPLACE INTO `pre_common_setting` VALUES ('loginkey', '$loginkey');
REPLACE INTO `pre_common_setting` VALUES ('liststyle', '1');
REPLACE INTO `pre_common_setting` VALUES ('nofids', '');
REPLACE INTO `pre_common_setting` VALUES ('needpic', '1');
REPLACE INTO `pre_common_setting` VALUES ('userurl', '1');
REPLACE INTO `pre_common_setting` VALUES ('indexvar', '1');

REPLACE INTO `pre_cis_weixin_styles` VALUES (1, 'Green', 1, 1, 1, 'a:43:{s:2:"b1";s:7:"#5ecc68";s:2:"b2";s:7:"#f1f1f1";s:2:"b3";s:4:"#fff";s:2:"b4";s:7:"#f6f6f6";s:2:"b5";s:7:"#bce4c2";s:2:"b6";s:7:"#f8eec5";s:2:"b7";s:4:"#333";s:2:"b8";s:7:"#fcfcfc";s:2:"b9";s:7:"#5ecc68";s:3:"b10";s:7:"#66ccff";s:3:"b11";s:7:"#f54f43";s:3:"b12";s:4:"#eee";s:3:"b13";s:4:"#f60";s:3:"b14";s:4:"#999";s:3:"b15";s:4:"#f00";s:3:"b16";s:7:"#5abaf0";s:3:"b17";s:4:"#f60";s:3:"b18";s:62:"url(template/cis_touch/touch/style/bg_1.png) no-repeat #5cb86b";s:3:"b19";s:62:"url(template/cis_touch/touch/style/bg_2.png) no-repeat #5abaf0";s:2:"o1";s:7:"#e5e5e5";s:2:"o2";s:4:"#ddd";s:2:"o3";s:7:"#eaeaea";s:2:"o4";s:7:"#a6deab";s:2:"o5";s:7:"#85ddff";s:2:"o6";s:7:"#5cb86b";s:2:"o7";s:7:"#58b4ec";s:2:"o8";s:7:"#a7e2ac";s:2:"o9";s:4:"#ddd";s:3:"o10";s:7:"#b9e5c0";s:3:"o11";s:7:"#abd9b2";s:2:"c1";s:4:"#333";s:2:"c2";s:4:"#999";s:2:"c3";s:4:"#ccc";s:2:"c4";s:4:"#f30";s:2:"c5";s:7:"#0086CE";s:2:"c6";s:4:"#fff";s:2:"c7";s:4:"#333";s:2:"c8";s:4:"#fff";s:2:"c9";s:4:"#fff";s:3:"c10";s:4:"#fff";s:3:"c11";s:4:"#fff";s:3:"c12";s:7:"#9ac6a0";s:3:"c13";s:4:"#fff";}');
REPLACE INTO `pre_cis_weixin_styles` VALUES (2, 'Black', 2, 0, 1, 'a:43:{s:2:"b1";s:7:"#222222";s:2:"b2";s:7:"#333438";s:2:"b3";s:7:"#3b3c40";s:2:"b4";s:7:"#292a2e";s:2:"b5";s:7:"#292a2e";s:2:"b6";s:7:"#292a2e";s:2:"b7";s:7:"#000000";s:2:"b8";s:7:"#222222";s:2:"b9";s:7:"#4f9e4d";s:3:"b10";s:7:"#ffdc76";s:3:"b11";s:7:"#f54f43";s:3:"b12";s:7:"#292a2e";s:3:"b13";s:4:"#f60";s:3:"b14";s:7:"#292a2e";s:3:"b15";s:4:"#f60";s:3:"b16";s:7:"#3b92b9";s:3:"b17";s:7:"#f54f43";s:3:"b18";s:57:"url(template/cis_touch/touch/style/bg_1_b.png) no-repeat ";s:3:"b19";s:57:"url(template/cis_touch/touch/style/bg_2_b.png) no-repeat ";s:2:"o1";s:4:"#333";s:2:"o2";s:7:"#303030";s:2:"o3";s:7:"#494a4e";s:2:"o4";s:7:"#773d19";s:2:"o5";s:7:"#9a8567";s:2:"o6";s:7:"#2f323b";s:2:"o7";s:7:"#27100b";s:2:"o8";s:7:"#333333";s:2:"o9";s:7:"#494a4e";s:3:"o10";s:7:"#494a4e";s:3:"o11";s:7:"#494a4e";s:2:"c1";s:7:"#d6d6d6";s:2:"c2";s:4:"#ccc";s:2:"c3";s:4:"#aaa";s:2:"c4";s:7:"#ffdc76";s:2:"c5";s:7:"#ffdc75";s:2:"c6";s:4:"#fff";s:2:"c7";s:4:"#fff";s:2:"c8";s:4:"#fff";s:2:"c9";s:4:"#000";s:3:"c10";s:4:"#fff";s:3:"c11";s:7:"#d6d6d8";s:3:"c12";s:7:"#acacae";s:3:"c13";s:4:"#fff";}');
REPLACE INTO `pre_cis_weixin_styles` VALUES (3, 'White', 3, 0, 1, 'a:43:{s:2:"b1";s:7:"#00B9F4";s:2:"b2";s:7:"#f1f1f1";s:2:"b3";s:4:"#fff";s:2:"b4";s:7:"#f6f6f6";s:2:"b5";s:7:"#eefdfd";s:2:"b6";s:7:"#f8eec5";s:2:"b7";s:4:"#333";s:2:"b8";s:7:"#fcfcfc";s:2:"b9";s:7:"#0099cc";s:3:"b10";s:7:"#339900";s:3:"b11";s:7:"#f54f43";s:3:"b12";s:4:"#eee";s:3:"b13";s:4:"#f60";s:3:"b14";s:4:"#999";s:3:"b15";s:4:"#f00";s:3:"b16";s:7:"#5abaf0";s:3:"b17";s:4:"#f60";s:3:"b18";s:56:"url(template/cis_touch/touch/style/bg_1_l.png) no-repeat";s:3:"b19";s:56:"url(template/cis_touch/touch/style/bg_2_l.png) no-repeat";s:2:"o1";s:7:"#e5e5e5";s:2:"o2";s:4:"#ddd";s:2:"o3";s:7:"#eaeaea";s:2:"o4";s:7:"#a5e5e4";s:2:"o5";s:7:"#c9dee8";s:2:"o6";s:7:"#5dacb8";s:2:"o7";s:7:"#cee7f7";s:2:"o8";s:7:"#5ad6fd";s:2:"o9";s:4:"#ddd";s:3:"o10";s:7:"#def5f6";s:3:"o11";s:7:"#d4f0f1";s:2:"c1";s:4:"#333";s:2:"c2";s:4:"#999";s:2:"c3";s:4:"#ccc";s:2:"c4";s:4:"#f30";s:2:"c5";s:7:"#0086CE";s:2:"c6";s:4:"#fff";s:2:"c7";s:4:"#333";s:2:"c8";s:4:"#fff";s:2:"c9";s:4:"#fff";s:3:"c10";s:4:"#fff";s:3:"c11";s:4:"#fff";s:3:"c12";s:7:"#87c7c9";s:3:"c13";s:4:"#fff";}');
REPLACE INTO `pre_cis_weixin_styles` VALUES (4, 'Blue', 4, 0, 1, 'a:43:{s:2:"b1";s:7:"#E42B1D";s:2:"b2";s:7:"#f1f1f1";s:2:"b3";s:4:"#fff";s:2:"b4";s:7:"#f6f6f6";s:2:"b5";s:7:"#faefee";s:2:"b6";s:7:"#f8eec5";s:2:"b7";s:4:"#333";s:2:"b8";s:7:"#fcfcfc";s:2:"b9";s:7:"#cd6860";s:3:"b10";s:7:"#66ccff";s:3:"b11";s:7:"#f54f43";s:3:"b12";s:4:"#eee";s:3:"b13";s:4:"#f60";s:3:"b14";s:4:"#999";s:3:"b15";s:4:"#f00";s:3:"b16";s:7:"#5abaf0";s:3:"b17";s:4:"#f60";s:3:"b18";s:56:"url(template/cis_touch/touch/style/bg_1_r.png) no-repeat";s:3:"b19";s:57:"url(template/cis_touch/touch/style/bg_2_r.png) no-repeat ";s:2:"o1";s:7:"#e5e5e5";s:2:"o2";s:4:"#ddd";s:2:"o3";s:7:"#eaeaea";s:2:"o4";s:7:"#f4f3e6";s:2:"o5";s:7:"#efe8e1";s:2:"o6";s:7:"#dfd1c5";s:2:"o7";s:7:"#ece2d9";s:2:"o8";s:7:"#f04c3f";s:2:"o9";s:4:"#ddd";s:3:"o10";s:7:"#f1e6e5";s:3:"o11";s:7:"#e8dcdb";s:2:"c1";s:4:"#333";s:2:"c2";s:4:"#999";s:2:"c3";s:4:"#ccc";s:2:"c4";s:4:"#f30";s:2:"c5";s:7:"#0086CE";s:2:"c6";s:4:"#fff";s:2:"c7";s:4:"#333";s:2:"c8";s:4:"#fff";s:2:"c9";s:4:"#fff";s:3:"c10";s:4:"#fff";s:3:"c11";s:4:"#fff";s:3:"c12";s:7:"#c88f8b";s:3:"c13";s:4:"#fff";}');
REPLACE INTO `pre_cis_weixin_styles` VALUES (5, 'Red', 5, 0, 1, 'a:43:{s:2:"b1";s:4:"#ddd";s:2:"b2";s:7:"#f6f6f6";s:2:"b3";s:4:"#fff";s:2:"b4";s:7:"#f6f6f6";s:2:"b5";s:7:"#f6f6f6";s:2:"b6";s:7:"#f8eec5";s:2:"b7";s:7:"#EDEDEF";s:2:"b8";s:7:"#EDEDEF";s:2:"b9";s:7:"#636283";s:3:"b10";s:7:"#F58767";s:3:"b11";s:7:"#f54f43";s:3:"b12";s:7:"#EDEDEF";s:3:"b13";s:4:"#f60";s:3:"b14";s:4:"#999";s:3:"b15";s:4:"#f00";s:3:"b16";s:7:"#5abaf0";s:3:"b17";s:4:"#f60";s:3:"b18";s:56:"url(template/cis_touch/touch/style/bg_1_w.png) no-repeat";s:3:"b19";s:56:"url(template/cis_touch/touch/style/bg_2_w.png) no-repeat";s:2:"o1";s:7:"#e5e5e5";s:2:"o2";s:7:"#DDDDDD";s:2:"o3";s:7:"#eaeaea";s:2:"o4";s:7:"#efe7cb";s:2:"o5";s:7:"#e7e1cf";s:2:"o6";s:7:"#ecdfc6";s:2:"o7";s:7:"#f5efe3";s:2:"o8";s:7:"#DDDDDD";s:2:"o9";s:4:"#ddd";s:3:"o10";s:4:"#eee";s:3:"o11";s:7:"#eaeaea";s:2:"c1";s:4:"#000";s:2:"c2";s:4:"#999";s:2:"c3";s:4:"#ccc";s:2:"c4";s:4:"#f30";s:2:"c5";s:7:"#0086CE";s:2:"c6";s:4:"#000";s:2:"c7";s:4:"#000";s:2:"c8";s:4:"#fff";s:2:"c9";s:4:"#fff";s:3:"c10";s:4:"#fff";s:3:"c11";s:4:"#333";s:3:"c12";s:4:"#333";s:3:"c13";s:4:"#fff";}');

EOF;




runquery($sql);


$finish = TRUE;

?>