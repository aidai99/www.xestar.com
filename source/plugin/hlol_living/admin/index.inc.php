<?php

/**
 * 翰林在线 [ 专业开发各种Discuz!插件 ]
 *
 * Copyright (c) 2017-2018 http://www.hallym.cn All Rights Reserved.
 *
 * Author: 风清扬 <itboycenter@qq.com>
 *
 * $Id: index.inc.php 2018-01-30 9:45:11Z 风清扬 $
 */

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$modBaseUrl = $adminBaseUrl.'&mod=index';
$modListUrl = $adminListUrl.'&mod=index';
$modFromUrl = $adminFromUrl.'&mod=index';

if($_GET['act'] == 'add'){

    if(submitcheck('submit')){
        $insertData = array();
        $insertData = __get_post_data();
        C::t('#hlol_living#hlol_living')->insert($insertData);
        cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    }else{
        __create_nav_html();
        showformheader($modFromUrl.'&act=add','enctype');
        showtableheader();
		__create_info_html();
        showsubmit('submit', 'submit');
        showtablefooter();
        showformfooter();
    }
    
}else if($_GET['act'] == 'edit'){

    $livingInfo = C::t('#hlol_living#hlol_living')->fetch_by_id($_GET['id']);
    if(submitcheck('submit')){
        $updateData = array();
        $updateData = __get_post_data($livingInfo);
        C::t('#hlol_living#hlol_living')->update($livingInfo['id'],$updateData);
        cpmsg($Lang['act_success'], $modListUrl, 'succeed');
    }else{
        __create_nav_html();
        showformheader($modFromUrl.'&act=edit&id='.$_GET['id'],'enctype');
        showtableheader();
        __create_info_html($livingInfo);
        showsubmit('submit', 'submit');
        showtablefooter();
        showformfooter();
    }

}else if($_GET['formhash'] == FORMHASH && $_GET['act'] == 'del'){
    
    C::t('#hlol_living#hlol_living')->delete_by_id($_GET['id']);
    cpmsg($Lang['act_success'], $modListUrl, 'succeed');

}else{

    $page = intval($_GET['page'])>0? intval($_GET['page']):1;
    $where = "1";
    $pagesize = 10;
    $start = ($page-1)*$pagesize;	
	$count = C::t('#hlol_living#hlol_living')->fetch_all_count("{$where}");
    $livingList = C::t('#hlol_living#hlol_living')->fetch_all_list("{$where}"," ORDER BY fsort ASC,id DESC ",$start,$pagesize);

	showtableheader();
	echo '<tr><th colspan="15" class="partition">' . $Lang['visitintro'] . '</th></tr>';
	echo '<tr><td  class="tipsblock" s="1"><ul id="tipslis">';
	echo '<li><font color="#fd0d0d">' . $Lang['visiturl'] . '</font></li>';
	echo '</ul></td></tr>';
	showtablefooter();
	__create_nav_html();
	showtableheader();
    echo '<tr class="header">';
    echo '<th>' . $Lang['id'].'/'.$Lang['fsort'] . '</th>';
	echo '<th>' . $Lang['catname'] . '</th>';
    echo '<th>' . $Lang['title'] . '</th>';
	echo '<th>' . $Lang['intro'] . '</th>';
    echo '<th>' . $Lang['pic'] . '</th>';
    echo '<th>' . $Lang['link'] . '</th>';
	echo '<th>' . $Lang['status'] . '</th>';
	echo '<th>' . $Lang['operation'] . '</th>';
    echo '</tr>';
    foreach ($livingList as $key => $value) {
        if(!preg_match('/^http/', $value['pic']) ){
            $pic = (preg_match('/^http/', $_G['setting']['attachurl']) ? '' : $_G['siteurl']).$_G['setting']['attachurl'].'hlolliving/'.$value['pic'];
        }else{
            $pic = $value['pic'];
        }
		if($value['catname'] == '1'){
			$catname = $Lang['navzhibo'];
		}else if($value['catname'] == '2'){
			$catname = $Lang['navhuifang'];
		}else if($value['catname'] == '3'){
			$catname = $Lang['navyugao'];
		}else{
			$catname = $Lang['adfocus'];
		}

        echo '<tr>';
        echo '<td>' . $value['id'].'/'.$value['fsort'] . '</td>';
		echo '<td>' . $catname . '</td>';
        echo '<td>' . $value['title'] . '</td>';
		echo '<td>' . $value['intro'] . '</td>';
        echo '<td><img src="'.$pic.'" width="40" /></td>';
		echo '<td>' . $value['link'] . '</td>';
		echo '<td>';
		if($value['status'] == 1){
			echo $Lang['yes'];
        }else{
            echo $Lang['no'];
        }
		echo'</td>';
        echo '<td>';
        echo '<a href="'.$modBaseUrl.'&act=edit&id='.$value['id'].'&formhash='.FORMHASH.'">' . $Lang['edit']. '</a>&nbsp;|&nbsp;';
        echo '<a href="javascript:void(0);" onclick="del_confirm(\''.$modBaseUrl.'&act=del&id='.$value['id'].'&formhash='.FORMHASH.'\');">' . $Lang['delete'] . '</a>';
        echo '</td>';
        echo '</tr>';
    }
	showtablefooter();

	$modBasePageUrl = $modBaseUrl;
    $multi = multi($count, $pagesize, $page, $modBasePageUrl);	
    showsubmit('', '', '', '', $multi, false);
    
    $jsstr = <<<EOF
<script type="text/javascript">
function del_confirm(url){
  var r = confirm("{$Lang['makesure_del_msg']}")
  if (r == true){
    window.location = url;
  }else{
    return false;
  }
}
</script>
EOF;
    echo $jsstr;

}

function __get_post_data($infoArr = array()){
    $data = array();

	$catname       = isset($_GET['catname'])? intval($_GET['catname']):1;
	$title           = isset($_GET['title'])? addslashes($_GET['title']):'';
	$intro           = isset($_GET['intro'])? addslashes($_GET['intro']):'';
    $link           = isset($_GET['link'])? addslashes($_GET['link']):'';
    $fsort       = isset($_GET['fsort'])? intval($_GET['fsort']):10;
	$status       = isset($_GET['status'])? intval($_GET['status']):1;
    
    $pic = "";
    if($_GET['act'] == 'add'){
        $pic        = hloluploadFile("pic");
    }else if($_GET['act'] == 'edit'){
        $pic        = hloluploadFile("pic",$infoArr['pic']);
    }

	$data['catname']    = $catname;
	$data['title']      = $title;
	$data['intro']      = $intro;
    $data['pic']     = $pic;
    $data['link']       = $link;
    $data['fsort']      = $fsort;
	$data['status']      = $status;

    return $data;
}

function __create_info_html($infoArr = array()){
    global $Lang;
    $options = array(
        'catname'        => 1,
        'title'          => '',
		'intro'          => '',
        'pic'         => '',
        'link'           => '',
        'fsort'          => 10,
		'status'          => 1,
    );
    $options = array_merge($options, $infoArr);

	$catname_select_item = array(99=>$Lang['adfocus'],1=>$Lang['navzhibo'],2=>$Lang['navhuifang'],3=>$Lang['navyugao']);
    hlolcreateRadio(array('title'=>$Lang['catname'],'name'=>'catname','value'=>$options['catname'],'msg'=>'','item'=>$catname_select_item));
	hlolcreateInput(array('title'=>$Lang['title'],'name'=>'title','value'=>$options['title'],'msg'=>''));
	hlolcreateTextarea(array('title'=>$Lang['intro'],'name'=>'intro','value'=>$options['intro'],'msg'=>''));
	hlolcreateFile(array('title'=>$Lang['pic'],'name'=>'pic','value'=>$options['pic'],'msg'=>$Lang['picmsg']));
	hlolcreateInput(array('title'=>$Lang['link'],'name'=>'link','value'=>$options['link'],'msg'=>$Lang['linkmsg']));
    hlolcreateInput(array('title'=>$Lang['fsort'],'name'=>'fsort','value'=>$options['fsort'],'msg'=>''));
	$status_select_item = array(1=>$Lang['yes'],2=>$Lang['no']);
    hlolcreateRadio(array('title'=>$Lang['status'],'name'=>'status','value'=>$options['status'],'msg'=>'','item'=>$status_select_item));

    return;
}

function __create_nav_html($infoArr = array()){
    global $Lang,$modBaseUrl,$adminBaseUrl;
    hlolshownavheader();
    if($_GET['act'] == 'add'){
        hlolshownavli($Lang['list'],$modBaseUrl,false);
        hlolshownavli($Lang['add'],$modBaseUrl."&act=add",true);
    }else if($_GET['act'] == 'edit'){
        hlolshownavli($Lang['list'],$modBaseUrl,false);
        hlolshownavli($Lang['add'],$modBaseUrl."&act=add",false);
        hlolshownavli($Lang['edit'],"",true);
    }else{
        hlolshownavli($Lang['list'],$modBaseUrl,true);
        hlolshownavli($Lang['add'],$modBaseUrl."&act=add",false);
    }
    hlolshownavfooter();
}

?>