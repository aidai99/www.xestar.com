<?php

defined('IN_DISCUZ') or exit('Powered by Hymanwu.Com');

class hwh_colortype_main {

    public $_G,$cfg = array();
    public $in_mobile,$app_id = 'hwh_colortype';

    public function __construct() {
        global $_G;
        $this->_G = $_G;
        $this->cfg = $_G['cache']['plugin'][$this->app_id];
        $this->in_mobile = defined('IN_MOBILE') ? 1 : 0;
    }

    public function _get_html() {
        global $threadlist;
        if ($this->cfg['global']) {
            $colors_code = $this->cfg['global_code'];
        } else {
            $cache_path = DISCUZ_ROOT.'data/sysdata/cache_plugin_'.$this->app_id.'.php';
            $cache = file_exists($cache_path) ? include $cache_path : array();
            $colors_code = $cache[$this->_G['fid']];
            if (!count($cache)) {
                $colors_code_arr = C::t('#'.$this->app_id.'#hwh_colortype')->fetch_first($this->_G['fid']);
                $colors_code = $colors_code_arr['code'];
            }
        }

        $type = $this->_G['forum']['threadtypes']['types'];

        if ($type && $colors_code) {
            $colors = array_filter(explode(',',$colors_code));
            $colors = count($colors)>count($type) ? array_slice($colors,0,count($type)) : $colors;
            $type   = count($type)>count($colors) ? array_slice($type,0,count($colors),true) : $type;
            $colors = array_combine(array_keys($type),$colors);
            foreach ($colors as $k => $v) {
                $css.='.hwh_typeid_'.$k.'{color:'.$v.'!important;}.hwh_nav_typeid_'.$k.'{border-bottom:1px solid '.$v.'!important;}';
            }
            include template($this->app_id.':index');
            return $html;
        }else{
            return '';
        }
    }

}

class plugin_hwh_colortype extends hwh_colortype_main {
}

class plugin_hwh_colortype_forum extends plugin_hwh_colortype{

    public function forumdisplay_threadlist_bottom_output(){
        return $this->_get_html();
    }

}

class mobileplugin_hwh_colortype extends hwh_colortype_main {
}

class mobileplugin_hwh_colortype_forum extends mobileplugin_hwh_colortype{
    public function forumdisplay_bottom_mobile_output(){
        return $this->_get_html();
    }
}

?>