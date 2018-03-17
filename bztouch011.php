<?php
    define('CURSCRIPT', 'chuan');
    define('CURMODULE', 'chuan');
    require './source/class/class_core.php';
    $discuz = & discuz_core::instance();
    $discuz->cachelist = $cachelist;
    $discuz->init();
    loadcache('diytemplatename');
    $navtitle = 'BZ1';
    $metakeywords = 'BZ2';
    $metadescription ='BZ3';
    include template('diy:portal/indexlist');
?>