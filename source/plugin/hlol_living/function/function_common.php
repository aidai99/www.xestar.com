<?php

/**
 * 翰林在线 [ 专业开发各种Discuz!插件 ]
 *
 * Copyright (c) 2017-2018 http://www.hallym.cn All Rights Reserved.
 *
 * Author: 风清扬 <itboycenter@qq.com>
 *
 * $Id: function_common.php 2018-1-2 12:45:11Z 风清扬 $
 */

if (!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

if (!function_exists('currentlang')) {

    function currentlang() {
        $charset = strtoupper(CHARSET);
        if ($charset == 'GBK') {
            return 'SC_GBK';
        } elseif ($charset == 'BIG5') {
            return 'TC_BIG5';
        } elseif ($charset == 'UTF-8') {
            global $_G;
            if ($_G['config']['output']['language'] == 'zh_cn') {
                return 'SC_UTF8';
            } elseif ($_G['config']['output']['language'] == 'zh_tw') {
                return 'TC_UTF8';
            }
        } else {
            return '';
        }
    }

}

if (!function_exists('hlolrmdir')) {

    function hlolrmdir($dir, $fileext = 'html') {
        if ($dir === '.' || $dir === '..' || strpos($dir, '..') !== false) {
            return false;
        }
        if (substr($dir, -1) === "/") {
            $dir = substr($dir, 0, -1);
        }
        if (!file_exists($dir) || !is_dir($dir)) {
            return false;
        } elseif (!is_readable($dir)) {
            return false;
        } else {
            if (($dirobj = dir($dir))) {
                while (false !== ($file = $dirobj->read())) {
                    if ($file != "." && $file != "..") {
                        $path = $dirobj->path . "/" . $file;
                        if (is_dir($path)) {
                            hlolrmdir($path);
                        } else {
                            unlink($path);
                        }
                    }
                }
                $dirobj->close();
            }
            rmdir($dir);
            return true;
        }
        return false;
    }

}

?>