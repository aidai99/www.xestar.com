<?php

/**
 * 翰林在线 [ 专业开发各种Discuz!插件 ]
 *
 * Copyright (c) 2017-2018 http://www.hallym.cn All Rights Reserved.
 *
 * Author: 风清扬 <itboycenter@qq.com>
 *
 * $Id: hlol.form.php 2017-12-15 10:45:11Z 风清扬 $
 */

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

function hlolgetfileurl($url){
    global $_G;
    $pic_url = "";
    if(!empty($url)){
        if(!preg_match('/^http/', $url) ){
            if(strpos($value['picurl'], 'source/plugin/hlol_living/') === FALSE){
                $pic_url = (preg_match('/^http/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'hlolliving/'.$url;
            }else{
                $picurl = $value['picurl'];
            }
        }else{
            $pic_url = $url;
        }
    }
    return $pic_url;
}

function loadeditorjs(){
    if (CHARSET == 'gbk'){
        echo '<script charset="gbk" src="source/plugin/hlol_editor/editor/kindeditor-min.js" reload="true"></script>';
        echo '<script src="source/plugin/hlol_editor/editor/lang/zh_CN.js" type="text/javascript" reload="true"></script>';
    }else{
        echo '<script charset="gbk" src="source/plugin/hlol_editor/editor/kindeditor-min.js" reload="true"></script>';
        echo '<script src="source/plugin/hlol_editor/editor/lang/zh_CN_UTF8.js" type="text/javascript" reload="true"></script>';
    }
    return;
}

function hlolshownavheader(){
    showtableheader();
    echo '<tr><th colspan="15" class="itemtitle" style="background-color: #FFF7D7;"><ul class="tab1" style="margin-bottom: 0px;">';
    return;
}
function hlolshownavli($title,$link,$cur = false,$_blank = false){
    if($cur){
        echo '<li class="current"><a><span>'.$title.'</span></a></li>';
    }else{
        if($_blank){
            echo '<li><a style="color: #FA6A03;margin-left: 10px;border-radius: 5px;border: 1px solid #FA6A03;" href="'.$link.'" target="_blank"><span>'.$title.'</span></a></li>';
        }else{
            echo '<li><a style="color: #FA6A03;margin-left: 10px;border-radius: 5px;border: 1px solid #FA6A03;" href="'.$link.'"><span>'.$title.'</span></a></li>';
        }
    }
    return;
}
function hlolshownavfooter(){
    echo '</ul></th></tr>';
    showtablefooter();
    return;
}

function hloluploadFile($name = "",$old_file =""){
    global $_G;
    if($_FILES[$name]['tmp_name']) {
        $upload = new hlolliving_upload();
        if(!getimagesize($_FILES[$name]['tmp_name']) || !$upload->init($_FILES[$name], 'hlolliving', random(3, 1), random(8)) || !$upload->save()) {
            cpmsg($upload->errormessage(), '', 'error');
        }
        $file_url = $upload->attach['attachment'];
        if(!empty($old_file)){
            if(file_exists($_G['setting']['attachdir'].'hlolliving/'.$old_file)){
                @unlink($_G['setting']['attachdir'].'hlolliving/'.$old_file);
            }
        }
    } else {
        $file_url = addslashes($_GET[$name]);
    }
    return $file_url;
}

function hlolcreateInput($config = array()){
    $outStr = '';
    $options = array(
        'title'  => 'title',
        'name'   => 'name',
        'value'  => '',
        'msg'   => '',
        'size'   => 40,
    );
    $options = array_merge($options, $config);

    $outStr = '<tr class="header"><th>'.$options['title'].'</th><th></th></tr>';
    $outStr.= '<tr><td width="300"><input name="'.$options['name'].'" type="text" value="'.$options['value'].'" size="'.$options['size'].'" /></td><td>'.$options['msg'].'</td></tr>';

    echo $outStr;
    return;
}

function hlolcreateCalendar($config = array()){
    $hlolSysOffset = getglobal('setting/timeoffset');
    $outStr = '';
    $options = array(
        'title'  => 'title',
        'name'   => 'name',
        'value'  => '',
        'msg'   => '',
        'size'   => 40,
    );
    $options = array_merge($options, $config);
    
    if(!empty($options['value'])){
        $options['value'] = dgmdate($options['value'],"Y-m-d H:i:s",$hlolSysOffset);
    }

    $outStr = '<tr class="header"><th>'.$options['title'].'</th><th></th></tr>';
    $outStr.= '<tr><td width="300"><input name="'.$options['name'].'" type="text" value="'.$options['value'].'" onclick="showcalendar(event, this, 1)" size="'.$options['size'].'" /></td><td>'.$options['msg'].'</td></tr>';

    echo $outStr;
    return;
}
 
function hlolcreateTextarea($config = array()){
    $outStr = '';
    $options = array(
        'title'  => 'title',
        'name'   => 'name',
        'value'  => '',
        'msg'   => '',
        'rows'   => 6,
        'cols'   => 40,
    );
    $options = array_merge($options, $config);
    
    $options['value'] = stripslashes($options['value']);

    $outStr = '<tr class="header"><th>'.$options['title'].'</th><th></th></tr>';
    $outStr.= '<tr><td width="300"><textarea rows="'.$options['rows'].'" name="'.$options['name'].'" cols="'.$options['cols'].'" >'.$options['value'].'</textarea></td><td>'.$options['msg'].'</td></tr>';

    echo $outStr;
    return;
}

function hlolcreateText($config = array()){
    $outStr = '';
    $options = array(
        'title'  => 'title',
        'name'   => 'name',
        'value'  => '',
        'msg'   => '',
    );
    $options = array_merge($options, $config);
    
    $options['value'] = stripslashes($options['value']);

    $outStr = '<tr class="header"><th>'.$options['title'].'</th><th>'.$options['msg'].'</th></tr>';
    $outStr.= '<tr><td width="300" colspan="2"><textarea name="'.$options['name'].'" id="'.$options['name'].'" style="width:700px;height:300px">'.$options['value'].'</textarea></td></tr>';
$jsStr = <<<EOF
<script type="text/javascript" reload="true">
        KindEditor.ready(function(K) {
              window.editor = KindEditor.create('#{$options['name']}');
        });
</script>
EOF;
    echo $outStr;
    echo $jsStr;
    return;
}

function hlolcreateRadio($config = array()){
    $outStr = '';
    $options = array(
        'title'  => 'title',
        'name'   => 'name',
        'value'  => '',
        'msg'   => '',
        'width' => '300',
        'item'   => array(),
    );
    $options = array_merge($options, $config);

    $outStr = '<tr class="header"><th>'.$options['title'].'</th><th></th></tr>';
    $outStr.= '<tr><td width="'.$options['width'].'">';
    foreach ($options['item'] as $key => $value){
        if($key == $options['value']){
            $outStr.= '<input type="radio" name="'.$options['name'].'" value="'.$key.'" checked>'.$value.'&nbsp;';
        }else{
            $outStr.= '<input type="radio" name="'.$options['name'].'" value="'.$key.'" >'.$value.'&nbsp;';
        }
    }
    $outStr.= '</td><td>'.$options['msg'].'</td></tr>';

    echo $outStr;
    return;
}

function hlolcreateSelect($config = array()){
    $outStr = '';
    $options = array(
        'title'     => 'title',
        'name'      => 'name',
        'value'     => '',
        'msg'      => '',
        'item'      => array(),
    );
    $options = array_merge($options, $config);

    $outStr = '<tr class="header"><th>'.$options['title'].'</th><th></th></tr>';
    $outStr.= '<tr><td width="300"><select name="'.$options['name'].'" >';
    foreach ($options['item'] as $key => $value){
        if($key == $options['value']){
            $outStr.=  '<option value="'.$key.'" selected>'.$value.'</option>';
        }else{
            $outStr.=  '<option value="'.$key.'">'.$value.'</option>';
        }
    }
    $outStr.= '</select></td><td>'.$options['msg'].'</td></tr>';

    echo $outStr;
    return;
}

function hlolcreateFile($config = array()){
    global $_G;
    $outStr = '';
    $options = array(
        'title'  => 'title',
        'name'   => 'name',
        'value'  => '',
        'msg'   => '',
        'size'   => 40,
    );
    $options = array_merge($options, $config);
    
    if(!empty($options['value'])){
        if(!preg_match('/^http/', $options['value']) ){
            if(strpos($options['value'], 'source/plugin/hlol_living/') === FALSE){
                $pic_url = (preg_match('/^http/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'hlolliving/'.$options['value'];
            }else{
                $pic_url = $options['value'];
            }
        }else{
            $pic_url = $options['value'];
        }
        $options['msg'] = $options['msg'].'<br/><a target="_blank" href="'.$pic_url.'"><img src="'.$pic_url.'" width="100" /></a>';
    }
    showsetting($options['title'],$options['name'], $options['value'], 'filetext',0,0,$options['msg']);
    echo $outStr;
    return;
}

?>