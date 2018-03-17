<?php
if (!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
    exit('Access Denied');
}
showtips(lang('plugin/'.$plugin['identifier'], '_contact_text'), 'tips', TRUE, lang('plugin/'.$plugin['identifier'], '_contact'));
showtips(lang('plugin/'.$plugin['identifier'], '_business_text'), 'tips2', TRUE, lang('plugin/'.$plugin['identifier'], '_business'));