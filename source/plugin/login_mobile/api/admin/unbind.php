<?php
if (!defined('IN_DISCUZ')) {
    exit('Access Denied');
}
$res = C::t("#login_mobile#mobile_login_connection")->unbind();
AdminApi::result($res);
?>
