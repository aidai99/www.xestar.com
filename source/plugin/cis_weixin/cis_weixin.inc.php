<?php
if (!defined('IN_DISCUZ') && !defined('IN_WEIXINAPP')) {
  exit('Access Denied');
}

loadcache('plugin');
$cis_weixin=$_G['cache']['plugin']['cis_weixin'];

$lang=CHARSET=='gbk'?'gbk':'utf8';
include DISCUZ_ROOT.'./source/plugin/cis_weixin/'.$lang.'.php';
include DISCUZ_ROOT.'./source/plugin/cis_weixin/function.php';

$_GET['mod']=$_GET['mod']?addslashes($_GET['mod']):'';
$_G['immwa']['attachment']='data/attachment/immwa';

if($_GET['mod']!='immwa_interface'){
	$settings=array();
	$settings['userurl']=$_G['setting']['userurl'];
	$settings['indexvar']=$_G['setting']['indexvar'];
	$settings['needpic']=$_G['setting']['needpic'];
	$settings['nofids']=$_G['setting']['nofids'];
	$settings['liststyle']=$_G['setting']['liststyle'];
	$settings['loginkey']=$_G['setting']['loginkey'];
	$settings['mobilelogin']=$_G['setting']['mobilelogin'];
	$settings['logintype']=$_G['setting']['logintype'];
	
	/*userseting*/
	$userseting=$_G['uid']?DB::fetch_first('SELECT * FROM '.DB::table('cis_weixin_uc')." WHERE uid='$_G[uid]'"):'';
}
if($_GET['mod']=='immwa_interface'){
  
	file_get_contents("php://input");
	if($_POST){
		$post['mid']=addslashes($_POST['mid']);
		$post['app']=addslashes($_POST['app']);
		$post['content']=$_POST['content'];
		$post['lid']=daddslashes($_POST['lid']);
		$post['logtime']=addslashes($_POST['logtime']);
		
		$log=DB::fetch_first('SELECT * FROM '.DB::table('cis_weixin_immwalog')." WHERE lid='$post[lid]' AND logtime='$post[logtime]'");
		if($log){
			DB::query("UPDATE ".DB::table('cis_weixin_immwalog')." SET `return` = '1000' WHERE `lid`='$post[lid]'");
			if($post['content']){
				$objfile=immwa_include($post['mid'],$post['app']);
        immwa_temp($post, $objfile);
			}
		}
	}
	
}elseif($_GET['mod']=='admin'){
	if(!$_G['uid'] || $_G['member']['adminid']!=1){
		$showmessage=array($cislang[1]);
	}else{
		$_GET['item']=$_GET['item']?addslashes($_GET['item']):'';
		$_GET['action']=$_GET['action']?addslashes($_GET['action']):'';
		$_GET['do']=$_GET['do']?addslashes($_GET['do']):'';

		if($_GET['item']=='immwa'){
			$_GET['hackid']=$_GET['hackid']?addslashes($_GET['hackid']):'';
			
			if($_GET['hackid']){
				$hack=DB::fetch_first('SELECT * FROM '.DB::table('cis_weixin_hack')." WHERE id='$_GET[hackid]'");
				if($hack['type']=='template' || !$hack){
					include DISCUZ_ROOT.'./template/'.$_GET['hackid'].'/app/manage.php';
				}else{
					include DISCUZ_ROOT.'./source/plugin/'.$hack['dir'].'/manage.php';
				}
			}else{

				$query = DB::query("SELECT * FROM ".DB::table('cis_weixin_hack'));
				while($value = DB::fetch($query)) {
					$hacks[$value['id']]=$value;
					$hacks_dir[]=$value['dir'];
				}

				$apps=unserialize(cis_get_urlcontent('http://www.immwa.com/apps.php'));
				
				$skindir = DISCUZ_ROOT.'./template';
				$skinsdir = dir($skindir);
				
				while($entry = $skinsdir->read()) {
					if(!in_array($entry, array('.', '..')) && is_dir($skindir.'/'.$entry) && !in_array($entry, $hacks_dir) && file_exists($skindir.'/'.$entry.'/app/manage.php')) {
						require $skindir.'/'.$entry.'/app/manage.php';
					}
				}
				foreach($apps as $k=>$v){
					$v['name']=CHARSET!='gbk'?diconv($value,'gbk',CHARSET):$v['name'];
					if(!$hacks[$k] && !$skins[$k]){
						$value['id']=$k;
						$value['type']=$v['type'];
						$value['name']=$v['name'];
						$newapp[]=$value;
					}
				}
			}
		}elseif($_GET['item']=='mobile'){
			if($_GET['action']=='style'){
				if(in_array($_GET['do'],array('add','edit'))){
					$_GET['sid']=$_GET['sid']?addslashes($_GET['sid']):'';
					if($_GET['sid']){
						$style=DB::fetch_first("SELECT * FROM ".DB::table('cis_weixin_styles')." WHERE sid='$_GET[sid]'");
						if(!$style){
							$showmessage=array($cislang[2]);
						}else{
							$style['var']=dunserialize($style['var']);
						}
					}
					if(submitcheck('postsubmit')){
						foreach($_POST as $key=>$var){
							$post[$key]=addslashes($var);
						}
						$post['var']=serialize(array(
							'b1'=>$post['b1'],
							'b2'=>$post['b2'],
							'b3'=>$post['b3'],
							'b4'=>$post['b4'],
							'b5'=>$post['b5'],
							'b6'=>$post['b6'],
							'b7'=>$post['b7'],
							'b8'=>$post['b8'],
							'b9'=>$post['b9'],
							'b10'=>$post['b10'],
							'b11'=>$post['b11'],
							'b12'=>$post['b12'],
							'b13'=>$post['b13'],
							'b14'=>$post['b14'],
							'b15'=>$post['b15'],
							'b16'=>$post['b16'],
							'b17'=>$post['b17'],

							'o1'=>$post['o1'],
							'o2'=>$post['o2'],
							'o3'=>$post['o3'],
							'o6'=>$post['o6'],
							'o9'=>$post['o9'],
							'o10'=>$post['o10'],
							'o11'=>$post['o11'],

							'c1'=>$post['c1'],
							'c2'=>$post['c2'],
							'c3'=>$post['c3'],
							'c4'=>$post['c4'],
							'c5'=>$post['c5'],
							'c6'=>$post['c6'],
							'c7'=>$post['c7'],
							'c8'=>$post['c8'],
							'c9'=>$post['c9'],
							'c10'=>$post['c10'],
							'c11'=>$post['c11'],
							'c12'=>$post['c12'],
							'c13'=>$post['c13']
						));
						
						if(!$post['name']){
							$showmessage=array($cislang[3]);
						}else{
							if($style){
								DB::query("UPDATE ".DB::table('cis_weixin_styles')." SET `name` = '$post[name]',`var` = '$post[var]' WHERE `sid`='$_GET[sid]'");
								$showmessage=array($cislang[4],'plugin.php?id=cis_weixin&mod=admin&item=mobile&action=style');
							}else{
								DB::query("INSERT INTO ".DB::table('cis_weixin_styles')." (`name`,`var`) VALUES ('$post[name]','$post[var]')");
								$showmessage=array($cislang[5],'plugin.php?id=cis_weixin&mod=admin&item=mobile&action=style');
							}
						}
			
					}
				}elseif($_GET['do']=='delete'){
					$_GET['sid']=$_GET['sid']?addslashes($_GET['sid']):'';
					
					$style=DB::fetch_first("SELECT * FROM ".DB::table('cis_weixin_styles')." WHERE sid='$_GET[sid]'");
					if(!$style){
						$showmessage=array($cislang[6]);
					}elseif($style['default']){
						$showmessage=array($cislang[7]);
					}else{
						DB::query("DELETE FROM ".DB::table('cis_weixin_styles')." WHERE sid='$_GET[sid]'");
						$showmessage=array($cislang[8],'plugin.php?id=cis_weixin&mod=admin&item=mobile&action=style');						
					}
					
				}else{
					$query = DB::query("SELECT * FROM ".DB::table('cis_weixin_styles')." ORDER BY `list` ASC ");
					while($value = DB::fetch($query)) {
						$styles[$value['sid']]=$value;
					}
					if(submitcheck('postsubmit')){
						foreach($_POST['list'] as $key=>$var){
							$list=addslashes($var);
							$sid=$_POST['sid'][$key];
							$default=$sid==$_POST['default']?'1':'0';
							$canuse=$_POST['canuse'][$key];
							DB::query("UPDATE ".DB::table('cis_weixin_styles')." SET `list` = '$list',`canuse` = '$canuse',`default` = '$default' WHERE `sid`='$sid'");
						}
						$showmessage=array($cislang[8],'plugin.php?id=cis_weixin&mod=admin&item=mobile&action=style');
					}
				}
			}elseif($_GET['action']=='touch'){
				if(submitcheck('postsubmit')){
					$_POST['nofids']=trim($_POST['nofids']);

		      C::t('common_setting')->update('userurl', $_POST['userurl']);
					C::t('common_setting')->update('indexvar', $_POST['indexvar']);
					C::t('common_setting')->update('needpic', $_POST['needpic']);
					C::t('common_setting')->update('nofids', $_POST['nofids']);
					C::t('common_setting')->update('liststyle', $_POST['liststyle']);
					require_once libfile('function/cache');
		      updatecache('setting');
					$showmessage=array($cislang[9],'plugin.php?id=cis_weixin&mod=admin&item=mobile&action=touch');
				}
				
			}else{
				if(submitcheck('postsubmit')){
					C::t('common_setting')->update('mobilelogin', $_POST['mobilelogin']);
					C::t('common_setting')->update('logintype', $_POST['logintype']);
					require_once libfile('function/cache');
          updatecache('setting');
					$showmessage=array($cislang[9],'plugin.php?id=cis_weixin&mod=admin&item=mobile');
				}
			}
		}else{
			if(in_array($_GET['action'],array('add','edit'))){
				$_GET['siteid']=$_GET['siteid']?addslashes($_GET['siteid']):'';
				if($_GET['siteid']){
					$app=DB::fetch_first("SELECT * FROM ".DB::table('cis_weixin_apps')." WHERE siteid='$_GET[siteid]'");
					if(!$app){
						$showmessage=array($cislang[10]);
					}
				}
				if(submitcheck('postsubmit')){
					$post['siteid']=addslashes($_POST['siteid']);
					$post['app']=addslashes($_POST['app']);
					$post['appkey']=addslashes($_POST['appkey']);
		
					if(!$post['siteid'] || !$post['app'] || !$post['appkey']){
						$showmessage=array($cislang[11]);
					}else{
						if($app){
							DB::query("UPDATE ".DB::table('cis_weixin_apps')." SET `siteid` = '$post[siteid]',`app` = '$post[app]',`appkey` = '$post[appkey]' WHERE `siteid`='$_GET[siteid]'");
							$showmessage=array($cislang[12],'plugin.php?id=cis_weixin&mod=admin');
						}else{
							DB::query("INSERT INTO ".DB::table('cis_weixin_apps')." (`siteid`,`app`,`appkey`,`adddate`) VALUES ('$post[siteid]','$post[app]','$post[appkey]','$_G[timestamp]')");
							$showmessage=array($cislang[13],'plugin.php?id=cis_weixin&mod=admin');
						}
					}
				}
			}elseif($_GET['action']=='delete'){
				$_GET['siteid']=$_GET['siteid']?addslashes($_GET['siteid']):'';
				
				$app=DB::fetch_first("SELECT * FROM ".DB::table('cis_weixin_apps')." WHERE siteid='$_GET[siteid]'");
				if(!$app){
					$showmessage=array($cislang[14]);
				}else{
					DB::query("DELETE FROM ".DB::table('cis_weixin_apps')." WHERE siteid='$_GET[siteid]'");
					$showmessage=array($cislang[8],'plugin.php?id=cis_weixin&mod=admin');						
				}
	
			}else{
				$_GET['show']=$_GET['show']?addslashes($_GET['show']):'';
				$size='30';
				$page = addslashes($_GET['page'])?addslashes($_GET['page']):1;
				
				$cut=($page>1)?($page-1)*$size:'0';
				
				$sql = array();
				$wherearr = array();
				
				$sql['select'] = 'SELECT *';
				$sql['from'] ='FROM '.DB::table('cis_weixin_apps');
	
				/*abnormal*/
				if($_GET['show']=='abnormal'){
					$wherearr[] = "state = '1'";
				}
				/*ban*/
				if($_GET['show']=='ban'){
					$wherearr[] = "state = '2'";
				}
				/*order*/
				$sql['order'] = 'ORDER BY adddate DESC';
				
				/*limit*/
				$sql['limit'] = 'LIMIT '.$cut.','.$size;
				
				/*sqlstring*/
				if(!empty($wherearr)) $sql['where'] = ' WHERE '.implode(' AND ', $wherearr);
				$sqlstring = $sql['select'].' '.$sql['from'].' '.$sql['where'].' '.$sql['order'].' '.$sql['limit'];
				
	
				/*listcount*/
				$num_sql='SELECT COUNT(*) '.$sql['from'].$sql['where'];
				$listcount = DB::result_first($num_sql);
				
				$urlstr='plugin.php?id=cis_weixin&mod=admin';
				
				
				if($listcount) {
					/*multipage*/
					$multipage = multi($listcount, $size, $page, $urlstr, 1000);
					/*list*/
					$query = DB::query($sqlstring);
					while($value = DB::fetch($query)) {
						$list[]=$value;
					}
				}
			}
		}
		include template('cis_weixin:admin');		
		
	}

}elseif($_GET['mod']=='uc'){
	$navtitle=$cislang[16];
  if(!$_G['uid']){
		showmessage($cislang[17], '', array(), array('login' => true));
	}else{
		if(submitcheck('postsubmit')){
      $referer=$_GET['referer']?$_GET['referer']:dreferer();
			if($userseting){
				DB::query("UPDATE ".DB::table('cis_weixin_uc')." SET `logintype` = '$_POST[logintype]',`style` = '$_POST[style]' WHERE `uid`='$_G[uid]'");
			}else{
				DB::query("INSERT INTO ".DB::table('cis_weixin_uc')." (`uid`,`style`,`logintype`) VALUES ('$_G[uid]','$_POST[style]','$_POST[logintype]')");
			}
			showmessage($cislang[9],$referer, array(), array('showdialog' => 1, 'locationtime' => true));
		}		
	}
  include template('cis_weixin:uc');
}elseif($_GET['mod']=='login'){
	$uid=$_COOKIE[$settings['loginkey'].'_uid'];
	$username=$_COOKIE[$settings['loginkey'].'_username'];
	$logintype=$_COOKIE[$settings['loginkey'].'_logintype'];

	
	if(!$uid){
		dheader('Location:member.php?mod=logging&action=login');
	}elseif($_G['uid']){
		dheader("Location:home.php?mod=space&uid=$user[uid]&do=profile&mycenter=1");
	}else{
		if(submitcheck('postsubmit')){
			if($logintype=='3'){
				require_once libfile('function/member');
				
				$result=userlogin($username,$_POST['pass']);
				if($result['member']['uid']){
				  cis_login($result['member']);
				  showmessage($cislang[18],$_GET['referer'], array(), array('showdialog' => 1, 'locationtime' => true));	
				}else{
					showmessage($cislang[19]);	
				}				
			}else{
				$user=DB::fetch_first("SELECT * FROM ".DB::table('common_member')." WHERE uid='$uid'");
				cis_login($user);
				showmessage($cislang[20],$_GET['referer'], array(), array('showdialog' => 1, 'locationtime' => true));								
			}
		}		
	}
	
	include template('cis_weixin:login');
}else{
	if(defined('IN_MOBILE')) {
		dheader('Location:'.'forum.php?mod=guide');
	}else{
		include template('cis_weixin:weixin');
	}
	
}

?>