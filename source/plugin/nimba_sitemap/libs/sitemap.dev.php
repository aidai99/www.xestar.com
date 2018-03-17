<?php
/*
 * 主页：http://addon.discuz.com/?@ailab
 * 人工智能实验室：Discuz!应用中心十大优秀开发者！
 * 插件定制 联系QQ594941227
 * From www.ailab.cn
 */
 
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
ini_set('memory_limit', '500M');
set_time_limit (0);
function sitemap_auto(){
	//组织参数
	global $_G;
	loadcache('plugin');			
	$var = $_G['cache']['plugin']['nimba_sitemap'];
	$open =$var['open'];
    $https =$var['https'];
	$htmlmade =$var['htmlmade'];
	$num=$var['num'];
	$filename =str_replace('.xml','',trim($var['filename']));
	$url =trim($var['web_root']);
	$web_root=empty($url) ? $_G['siteurl']:$url;
	$charset='utf-8';
	$ban=unserialize($var['ban']);
	if(count($ban)==0) $notin='';
	else $notin='and a.fid not in('.dimplode($ban).')';
	$show=array(0,0,0);
	$urls=unserialize($var['urls']);
	if(in_array('1',$urls)) $show[0]=1;	
	if(in_array('2',$urls)) $show[1]=1;	
	if(in_array('3',$urls)) $show[2]=1;	
	$cycle='weekly';
	//开始记录更新时间
	$last=time();
	@require_once libfile('function/cache');
	$cacheArray .= "\$last=".$last.";\n";
	writetocache('nimba_sitemap_log', $cacheArray);	
	//更新地图
	get_sitemap($filename,$web_root,$cycle,$charset,$notin,$show,$open,$num,$htmlmade,$https);//生成地图
	return '1';//返回值仅作调试用
}//获取上次更新时间并自动更新
	 
function _isrewrite($item){
	global $_G;
	/*
	portal_topic
	portal_article
	forum_forumdisplay
	forum_viewthread
	group_group
	home_space
	home_blog
	forum_archiver
	*/
	$rewritestatus = $_G['setting']['rewritestatus'];
	$rewriterule = $_G['setting']['rewriterule'];
	if(in_array($item,$rewritestatus)){
		return $rewriterule[$item];
	}else{
		return false;
	}	
}
//echo _isrewrite('forum_viewthread');	

function subdomain($item){//查询后台设置的应用域名
	/*	
	portal
	forum
	group
	home
	mobile
	default
	*/
	global $_G;
	$url =trim($_G['cache']['plugin']['nimba_sitemap']['mysite']);
	$domain = $_G['setting']['domain'];
	if($domain['app'][$item]){
		$return = $domain['app'][$item];
	}else{
		$return = $domain['app']['default'];
	}
	if(empty($return)){
		$return =$url;
	}
	return $return;	
}

function _getForumDomain($fid){
	@include DISCUZ_ROOT.'./data/sysdata/cache_domain.php';
	foreach($domain['list'] as $url=>$info){
		if($info['id']==$fid&&$info['idtype']=='forum'){
			return $url;
			break;
		}
	}
	return '';
}

function get_sitemap($filename,$web_root,$cycle,$charset,$notin,$show,$open,$num,$htmlmade=0,$https=false){
	global $_G;
	require_once DISCUZ_ROOT.'./source/discuz_version.php';
	if(strtolower(substr(DISCUZ_VERSION,0,2))=='x2'||strtolower(substr(DISCUZ_VERSION,0,2))=='x1') $htmlmade=0;//低版本不支持	
	loadcache('plugin');
	$base=array('weekly','always','hourly','daily','weekly','monthly','yearly','never');
	
	//$web_root 网站目录
	$urlsum=array(0,0,0);
	$maps=array();
	if(file_exists(DISCUZ_ROOT.'source/plugin/nimba_sitemap/sitemap.f.php')){
		$num=intval($num);
	}else{
		$num=min(10000,intval($num));
	}
	/***********************************************************************************************/
	//网站地图sitemap.xml
	$start="<?xml version=\"1.0\" encoding=\"$charset\"?>\n";
	$start.="<urlset\n";
	$start.="xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\"\n";
	$start.="xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"\n";
	$start.="xsi:schemaLocation=\"http://www.sitemaps.org/schemas/sitemap/0.9\n";
	$start.="http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd\">\n";
	$end="</urlset>\n";

	if($show[0]==1){//论坛帖子
		$rank = $_G['cache']['plugin']['nimba_sitemap']['rank1'];
		$rank=empty($rank)? 0.8:$rank;
		$cycle=$base[intval($_G['cache']['plugin']['nimba_sitemap']['change1'])];
		$querys = DB::query("SELECT a.fid,a.tid,a.lastpost FROM ".DB::table('forum_thread')." a inner join ".DB::table('forum_forum')." b on a.fid=b.fid where a.displayorder>=0 $notin ORDER BY a.tid DESC  LIMIT 0,$num");
		$isrewrite=_isrewrite('forum_viewthread');
		$subdomain=subdomain('forum');
		while($threadfid = DB::fetch($querys)){
			if($urlsum[0]>=$num) break;
			$forumDomain=_getForumDomain($threadfid['fid']);
			if(!$forumDomain) $forumDomain=$subdomain;			
			if(!empty($isrewrite)) $link='http://'.$forumDomain.$web_root.str_replace(array('{fid}','{tid}','{page}','{prevpage}'),array($threadfid['fid'],$threadfid['tid'],1,1),$isrewrite);//静态规则
			else $link='http://'.$forumDomain.$web_root.'forum.php?mod=viewthread&amp;tid='.$threadfid['tid'];//动态规则,xml中&要换成&amp;
			$riqi=date("Y-m-d",$threadfid['lastpost']);
			$map=array('link'=>$link,'priority'=>$rank,'riqi'=>$riqi,'cycle'=>$cycle);
			$maps[]=$map;
			$urlsum[0]++;
		}
	}	

	if($show[1]==1){//论坛版块
		$rank = $_G['cache']['plugin']['nimba_sitemap']['rank2'];
		$rank=empty($rank)? 0.8:$rank;	
		$cycle=$base[intval($_G['cache']['plugin']['nimba_sitemap']['change2'])];
		$isrewrite=_isrewrite('forum_forumdisplay');
		$subdomain=subdomain('forum');
		$querys = DB::query("SELECT a.fid,a.domain FROM ".DB::table('forum_forum')." a where a.type='forum' and status=1 $notin ORDER BY a.fid DESC  LIMIT 0,$num");
		while($threadfid = DB::fetch($querys)){
			if($urlsum[1]+$urlsum[0]>=$num) break;
			if($threadfid['domain']&&$_G['setting']['domain']['root']['forum']) $subdomain=$threadfid['domain'].'.'.$_G['setting']['domain']['root']['forum'];//版块域名
			if(!empty($_G['setting']['forumkeys'][$threadfid['fid']])) $threadfid['fid']= $_G['setting']['forumkeys'][$threadfid['fid']];//板块别名
			if(!empty($isrewrite)) $link='http://'.$subdomain.$web_root.str_replace(array('{fid}','{page}'),array($threadfid['fid'],1),$isrewrite);//静态规则
			else $link='http://'.$subdomain.$web_root.'forum.php?mod=forumdisplay&amp;fid='.$threadfid['fid'];//动态规则,xml中&要换成&amp;
			$riqi=date("Y-m-d",time());
			$map=array('link'=>$link,'priority'=>$rank,'riqi'=>$riqi,'cycle'=>$cycle);
			$maps[]=$map;
			$urlsum[1]++;
		}
	}

	if($show[2]==1){//门户文章
		$rank = $_G['cache']['plugin']['nimba_sitemap']['rank3'];
		$rank=empty($rank)? 0.8:$rank;	
		$cycle=$base[intval($_G['cache']['plugin']['nimba_sitemap']['change3'])];
		$isrewrite=_isrewrite('portal_article');
		$subdomain=subdomain('portal');
		if($htmlmade) $querys = DB::query("SELECT aid,dateline,htmlmade,htmlname,htmldir FROM ".DB::table('portal_article_title')." ORDER BY aid DESC  LIMIT 0,$num");
		else $querys = DB::query("SELECT aid,dateline FROM ".DB::table('portal_article_title')." ORDER BY aid DESC  LIMIT 0,$num");//低版本
		while($threadfid = DB::fetch($querys)){
			if($urlsum[2]+$urlsum[1]+$urlsum[0]>=$num) break;
			if($htmlmade&&$threadfid['htmlmade']){
				$link='http://'.$subdomain.$web_root.$threadfid['htmldir'].$threadfid['htmlname'].'.html';//HTML静态生成
			}else{			
				if(!empty($isrewrite)) $link='http://'.$subdomain.$web_root.str_replace(array('{id}','{page}'),array($threadfid['aid'],1),$isrewrite);//静态规则
				else $link='http://'.$subdomain.$web_root.'portal.php?mod=view&amp;aid='.$threadfid['aid'];//动态规则,xml中&要换成&amp;
			}
			$riqi=date("Y-m-d",$threadfid['dateline']);
			$map=array('link'=>$link,'priority'=>$rank,'riqi'=>$riqi,'cycle'=>$cycle);
			$maps[]=$map;
			$urlsum[2]++;
		}
	}
 	$sitemap='';
	
	if(count($maps)>$num) $maps=array_slice($maps,0,$num);
	foreach($maps as $k=>$map){
		$sitemap.="<url>\n";
		$sitemap.="<loc>".$map['link']."</loc>\n";
		$sitemap.="<lastmod>".$map['riqi']."</lastmod>\n";
		$sitemap.="<changefreq>".$map['cycle']."</changefreq>\n";
		$sitemap.="<priority>".$map['priority']."</priority>\n";		
		$sitemap.="</url>\n";
	}
	if($https){
		$sitemap=str_replace('http://','https://',$sitemap);
	}
	
	$sitemap=$start.$sitemap.$end;

	$fp = fopen(DISCUZ_ROOT.'/'.$filename.'.xml','w');
	fwrite($fp,$sitemap);
	fclose($fp);
	return $urlsum;
}
?>