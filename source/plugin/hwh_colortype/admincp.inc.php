<?php

defined('IN_DISCUZ') && defined('IN_ADMINCP') or exit('Powered by Hymanwu.Com');
define('APP_ID','hwh_colortype');
define('URL_FORM','plugins&operation=config&do='.$do.'&identifier='.$plugin['identifier'].'&pmod=admincp');
define('URL_BACK','action='.URL_FORM);
loadcache('forums');
global $_G;

$find = array(
    'resource/template/',
    'resource/developer/',
    'resource/plugin/',
    'resource/event/',
    'image/scrolltop.png',
);
$replace = array(
    'http://addon.discuz.com/resource/template/',
    'http://addon.discuz.com/resource/developer/',
    'http://addon.discuz.com/resource/plugin/',
    'http://addon.discuz.com/resource/event/',
    'http://addon.discuz.com/image/scrolltop.png',
);
echo '<style type="text/css">.current font{color:#FFF;}#header,.a_tb,.devcat,.share,#footer{display:none!important;}</style>';

if (in_array(currentlang(),array('TC_BIG5','TC_UTF8'))) {
    $info_str = array(
        '&#x81E8;&#x6642;&#x7DE8;&#x8F2F;',
        '&#x53EF;&#x7528;',
        '&#x4E0A;&#x7D1A;&#x7248;&#x584A;',
        '&#x7248;&#x584A;&#x540D;&#x7A31;',
        '&#x984F;&#x8272;&#x914D;&#x7F6E;&#x4EE3;&#x78BC; <span class="lightfont">(&#x683C;&#x5F0F;&#x70BA;:#CC33FF,#F00,#FFCC33,#CC6666...)</span>',
        '&#x984F;&#x8272;&#x63D0;&#x53D6;',
        '&#x4FDD;&#x5B58;&#x6210;&#x529F;'
        );
} else {
    $info_str = array(
        '&#x4E34;&#x65F6;&#x7F16;&#x8F91;',
        '&#x53EF;&#x7528;',
        '&#x4E0A;&#x7EA7;&#x7248;&#x5757;',
        '&#x7248;&#x5757;&#x540D;&#x79F0;',
        '&#x989C;&#x8272;&#x914D;&#x7F6E;&#x4EE3;&#x7801; <span class="lightfont">(&#x683C;&#x5F0F;&#x4E3A;:#CC33FF,#F00,#FFCC33,#CC6666...)</span>',
        '&#x989C;&#x8272;&#x63D0;&#x53D6;',
        '&#x4FDD;&#x5B58;&#x6210;&#x529F;'
        );
}

switch ($_GET['a']) {

    case 'setting':
        if (!submitcheck('submit')) {

            $cache_path = DISCUZ_ROOT.'data/sysdata/cache_plugin_'.APP_ID.'.php';
            $cache = file_exists($cache_path) ? include $cache_path : array();
            if (!count($cache)) {
                $all_data = C::t('#'.APP_ID.'#hwh_colortype')->fetch_all();
                foreach ($all_data as $v) {
                    $cache[$v['fid']] = $v['code'];
                }
            }

            foreach($_G['cache']['forums'] as $fid => $forum) {
                if($forum['type'] != 'group') {
                    $forums[$fid]['forumname'] = $forum['name'];
                    $forums[$fid]['type'] = $forum['type'];
                    $forums[$fid]['fup'] = $forum['fup'];
                }
                    $forums_cat[$fid]['forumname'] = $forum['name'];
            }

            showformheader(URL_FORM.'&a=setting');
            showtableheader('<span style="float:left;margin-right:10px;">'.$info_str[5].'</span><input id="c1_v" type="text" class="txt" style="float:left; width:100px;" onchange="updatecolorpreview(\'c1\')"><input id="c1" onclick="c1_frame.location=\'static/image/admincp/getcolor.htm?c1|c1_v\';showMenu({\'ctrlid\':\'c1\'})" type="button" class="colorwd" value="" style="background: "><span id="c1_menu" style="display: none"><iframe name="c1_frame" src="" frameborder="0" width="210" height="148" scrolling="no"></iframe></span> <span style="margin:0 10px;">'.$info_str[0].'</span><input value="" size="100">');
            showsubtitle(array(
                $info_str[1],
                $info_str[2],
                '',
                $info_str[3],
                $info_str[4],
                ));
            foreach ($forums as $k=>$v) {
                $level = $v['type']=='sub' ? '&#x25CF; ' : '';
                $checked = in_array($k,array_keys($cache)) ? ' checked="checked"' : '';

                showtablerow(
                    '',
                array('class="td25"','class="td24"','align="right" class="lightfont"'),
                array(
                '<input type="checkbox" name="fid['.$k.']" value="'.$k.'"'.$checked.'>',
                '<span style="color:#2366A8">'.$level.$forums_cat[$v['fup']]['forumname'].'</span>',
                '(fid:'.$k.')',
                $v['forumname'],
                '<input name="code['.$k.']" value=\''.$cache[$k].'\' size="100">'
                ));
            }
            showsubmit('submit','submit','select_all');
            echo '<script type="text/JavaScript">document.getElementsByName("chkall")[0].setAttribute("onclick","checkAll(\'prefix\', this.form, \'fid\')");</script>';//wtf cann't set field!
            showtablefooter();
            showformfooter();
        }else{

            C::t('#'.APP_ID.'#hwh_colortype')->delete_all();

            foreach ($_GET['fid'] as $v) {
                $code = $_GET['code'][$v];
                if ($code) {
                    $cache[$v] = $_GET['code'][$v];
                    $data = array(
                        'fid'=>$v,
                        'code'=>$_GET['code'][$v]
                    );
                    C::t('#'.APP_ID.'#hwh_colortype')->insert($data);
                }
            }
            $cache_array .= "return ".arrayeval($cache).";\n";
            writetocache('plugin_'.APP_ID,$cache_array);

            cpmsg($info_str[6],dreferer(),'succeed');
        }

        break;

    case 'store':
    default:
        $html = str_replace($find, $replace, dfsockopen('http://addon.discuz.com/?@19547.developer'));
        echo iconv('gbk',CHARSET,$html);
    break;

}

?>