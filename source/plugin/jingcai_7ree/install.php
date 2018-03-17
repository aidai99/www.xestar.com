<?php

/*
	ID: jingcai_7ree
	[www.7ree.com] (C)2007-2017 7ree.com.
	This is NOT a freeware, use is subject to license terms
	Update: 2017/10/25 19:48
	Agreement: http://addon.discuz.com/?@7.developer.doc/agreement_7ree_html
	More Plugins: http://addon.discuz.com/?@7ree
*/




if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}


$sql = <<<EOF

CREATE TABLE IF NOT EXISTS `pre_jingcai_saicheng_7ree` (
  `scid_7ree` mediumint(8) NOT NULL auto_increment,
  `scname_7ree` varchar(255) NOT NULL,
  `time_7ree` int(10) NOT NULL,
  `fenlei1_7ree` varchar(100) NOT NULL,
  `fenlei2_7ree` varchar(100) NOT NULL,
  `aname_7ree` varchar(255) NOT NULL,
  `bname_7ree` varchar(255) NOT NULL,
  `alogo_7ree` varchar(255) NOT NULL,
  `blogo_7ree` varchar(255) NOT NULL,
  `arate_7ree`  float unsigned NOT NULL,
  `brate_7ree`  float unsigned NOT NULL,
  PRIMARY KEY  (`scid_7ree`)
) ENGINE=MyISAM;


CREATE TABLE IF NOT EXISTS `pre_jingcai_main_7ree` (
  `main_id_7ree` mediumint(8) unsigned NOT NULL auto_increment,
  `scid_7ree` mediumint(8) NOT NULL,
  `tid_7ree` mediumint(8) NOT NULL,
  `racename_7ree` varchar(200) NOT NULL,
  `fenlei1_7ree` varchar(100) NOT NULL,
  `fenlei2_7ree` varchar(100) NOT NULL,
  `reward_7ree` mediumint(5) unsigned NOT NULL,
  `add_7ree` varchar(250) NOT NULL,
  `time_7ree` int(10) unsigned NOT NULL,
  `aname_7ree` varchar(200) NOT NULL,
  `bname_7ree` varchar(200) NOT NULL,
  `alogo_7ree` varchar(250) NOT NULL,
  `blogo_7ree` varchar(250) NOT NULL,
  `amessage_7ree` text NOT NULL,
  `bmessage_7ree` text NOT NULL,
  `aodds_7ree` float unsigned NOT NULL,
  `bodds_7ree` float unsigned NOT NULL,
  `codds_7ree` float unsigned NOT NULL,
  `daodds_7ree` float NOT NULL,
  `xiaoodds_7ree` float NOT NULL,
  `max_odd_7ree` float NOT NULL,
  `min_odd_7ree` float NOT NULL,
  `odd_ratio_7ree` float NOT NULL,
  `odd_dynamic_7ree` tinyint(1) NOT NULL,
  `rangqiufang_7ree` tinyint(1) NOT NULL,
  `rangqiuway_7ree` varchar(50) NOT NULL,
  `racemessage_7ree` text NOT NULL,
  `win_7ree` varchar(1) NOT NULL,
  `ashot_7ree` int(6) NOT NULL,
  `bshot_7ree` int(6) NOT NULL,
  `daxiao_7ree` varchar(100) default NULL,
  `daxiaowin_7ree` varchar(1) NOT NULL,
  `match_7ree` int(10) NOT NULL,
  PRIMARY KEY  (`main_id_7ree`)
) ENGINE=MyISAM;



CREATE TABLE IF NOT EXISTS `pre_jingcai_log_7ree` (
  `log_id_7ree` mediumint(8) unsigned NOT NULL auto_increment,
  `uid_7ree` mediumint(8) unsigned NOT NULL,
  `username_7ree` varchar(100) NOT NULL,
  `type_7ree` tinyint(1) NOT NULL,
  `myodds_7ree` mediumint(8) NOT NULL,
  `teamodds_7ree` float NOT NULL,
  `mywin_7ree` varchar(1) NOT NULL,
  `log_time_7ree` int(10) unsigned NOT NULL,
  `log_reward_7ree` mediumint(8) unsigned NOT NULL,
  `main_id_7ree` mediumint(8) unsigned NOT NULL,
  `point_7ree` int(10) NOT NULL,
  PRIMARY KEY  (`log_id_7ree`)
) ENGINE=MyISAM;


CREATE TABLE IF NOT EXISTS `pre_jingcai_payment_7ree` (
  `id_7ree` int(8) NOT NULL auto_increment,
  `uid_7ree` int(8) NOT NULL,
  `touid_7ree` int(8) NOT NULL,
  `payment_7ree` mediumint(8) NOT NULL,
  `lid_7ree` int(10) NOT NULL,
  `time_7ree` int(10) NOT NULL,
  PRIMARY KEY  (`id_7ree`)
) ENGINE=MyISAM;


CREATE TABLE IF NOT EXISTS `pre_jingcai_guanzhu_7ree` (
  `id_7ree` int(8) NOT NULL auto_increment,
  `uid_7ree` int(8) NOT NULL,
  `touid_7ree` int(8) NOT NULL,
  `toname_7ree` varchar(20) NOT NULL,
  PRIMARY KEY  (`id_7ree`)
) ENGINE=MyISAM;


CREATE TABLE IF NOT EXISTS `pre_jingcai_member_7ree` (
  `id_7ree` int(10) NOT NULL auto_increment,
  `uid_7ree` mediumint(8) NOT NULL,
  `username_7ree` varchar(20) NOT NULL,
  `type_7ree` varchar(255) NOT NULL,
  `a_changci_7ree` mediumint(8) NOT NULL,
  `y_changci_7ree` mediumint(8) NOT NULL,
  `m_changci_7ree` mediumint(8) NOT NULL,
  `w_changci_7ree` mediumint(8) NOT NULL,
  `m2_changci_7ree` mediumint(8) NOT NULL,
  `w2_changci_7ree` mediumint(8) NOT NULL,
  `a_caidui_7ree` mediumint(8) NOT NULL,
  `y_caidui_7ree` mediumint(8) NOT NULL,
  `m_caidui_7ree` mediumint(8) NOT NULL,
  `w_caidui_7ree` mediumint(8) NOT NULL,
  `m2_caidui_7ree` mediumint(8) NOT NULL,
  `w2_caidui_7ree` mediumint(8) NOT NULL,
  `a_yingli_7ree` int(10) NOT NULL,
  `y_yingli_7ree` int(10) NOT NULL,
  `m_yingli_7ree` int(10) NOT NULL,
  `w_yingli_7ree` int(10) NOT NULL,
  `m2_yingli_7ree` int(10) NOT NULL,
  `w2_yingli_7ree` int(10) NOT NULL,
  `a_jingli_7ree` int(10) NOT NULL,
  `y_jingli_7ree` int(10) NOT NULL,
  `m_jingli_7ree` int(10) NOT NULL,
  `w_jingli_7ree` int(10) NOT NULL,
  `m2_jingli_7ree` int(10) NOT NULL,
  `w2_jingli_7ree` int(10) NOT NULL,
  `a_mingzhong_7ree` float NOT NULL,
  `y_mingzhong_7ree` float NOT NULL,
  `m_mingzhong_7ree` float NOT NULL,
  `w_mingzhong_7ree` float NOT NULL,
  `m2_mingzhong_7ree` float NOT NULL,
  `w2_mingzhong_7ree` float NOT NULL,
  `a_mzlrank_7ree` mediumint(8) NOT NULL,
  `y_mzlrank_7ree` mediumint(8) NOT NULL,
  `m_mzlrank_7ree` mediumint(8) NOT NULL,
  `w_mzlrank_7ree` mediumint(8) NOT NULL,
  `m2_mzlrank_7ree` mediumint(8) NOT NULL,
  `w2_mzlrank_7ree` mediumint(8) NOT NULL,
  `zdly_7ree` mediumint(8) NOT NULL,
  `zdlyrank_7ree` mediumint(8) NOT NULL,
  `last_main_id_7ree` mediumint(8) NOT NULL,
  PRIMARY KEY  (`id_7ree`),
  KEY `uid_7ree` (`uid_7ree`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `pre_jingcai_fencheng_7ree` (
  `id_7ree` int(10) NOT NULL auto_increment,
  `uid_7ree` int(10) NOT NULL,
  `fromuid_7ree` int(10) NOT NULL,
  `main_id_7ree` int(10) NOT NULL,
  `log_id_7ree` int(10) NOT NULL,
  `odds_7ree` mediumint(8) NOT NULL,
  `fencheng_7ree` int(10) NOT NULL,
  `time_7ree` int(10) NOT NULL,
  PRIMARY KEY  (`id_7ree`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `pre_jingcai_tmp_7ree` (
  `fromuid_7ree` int(10) NOT NULL,
  `ip_7ree` varchar(15) NOT NULL,
  KEY `ip_7ree` (`ip_7ree`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `pre_jingcai_tuiguang_7ree` (
  `id_7ree` int(10) NOT NULL auto_increment,
  `fromuid_7ree` int(10) NOT NULL,
  `uid_7ree` int(10) NOT NULL,
  `ip_7ree` varchar(15) NOT NULL,
  `time_7ree` int(10) NOT NULL,
  PRIMARY KEY  (`id_7ree`)
) ENGINE=MyISAM;

EOF;

runquery($sql);



/*
$pluginid = 'jingcai_7ree';
$Hooks = array('forumdisplay_sideBar');
$data = array();
foreach ($Hooks as $Hook) {
		$data[] = array($Hook => array('plugin' => $pluginid, 'include' => 'api.class.php', 'class' => $pluginid.'_api', 'method' => $Hook));
}
require_once DISCUZ_ROOT . './source/plugin/wechat/wechat.lib.class.php';
*/


$finish = TRUE;




?>