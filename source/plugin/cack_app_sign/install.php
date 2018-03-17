<?php
if (!defined('IN_DISCUZ')) {
    exit('Access Denied');
}
include 'source/plugin/cack_app_sign/lang/'.currentlang().'.php';
$sql = <<<EOF
DROP TABLE IF EXISTS  cdb_cack_app_sign_xinqing;
CREATE TABLE IF NOT EXISTS `cdb_cack_app_sign_xinqing` (
  `xid` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `pic` varchar(255) NOT NULL,
  `displayorder` int(11) NOT NULL,
  PRIMARY KEY (`xid`)
) ENGINE=MyISAM;
INSERT INTO `cdb_cack_app_sign_xinqing` (`xid`, `name`, `pic`, `displayorder`) VALUES
(2, '$signsqllang[1]', '1.gif', 1),
(3, '$signsqllang[2]', '4.gif', 2),
(4, '$signsqllang[3]', '8.gif', 3),
(5, '$signsqllang[4]', '9.gif', 4),
(6, '$signsqllang[5]', '13.gif', 5);

CREATE TABLE IF NOT EXISTS `cdb_cack_app_sign_log` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) NOT NULL,
  `username` varchar(120) NOT NULL,
  `signtime` int(10) NOT NULL,
  `xid` int(10) NOT NULL,
  `extcredits` int(10) NOT NULL,
  `jifen` int(10) NOT NULL,
  `content` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM;
EOF;
runquery($sql);
$finish = TRUE;
?>