<?php
/*
	[www.7ree.com] (C)2007-2017 7ree.com.
	This is NOT a freeware, use is subject to license terms
	Update: 2017/6/10 12:28
	Agreement: http://addon.discuz.com/?@7.developer.doc/agreement_7ree_html
	More Plugins: http://addon.discuz.com/?@7ree
*/


if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

function data_update_7ree($dbname_7ree,$valuearray_7ree,$wherearray_7ree,$imagearray_7ree,$folder_7ree){//新增、更新,$imagearray_7ree=array('DB字段名'=>$_FILES['表单名'])为附件图片字段名
		global $_G;
		if(!$dbname_7ree || !COUNT($valuearray_7ree)) return FALSE;

		if(COUNT($imagearray_7ree)){
			$subname_7ree='';
			$imgarray_7ree=array();
			foreach($imagearray_7ree as $key => $thisimage_7ree){
				$subname_7ree = $_G['uid'].'_'.$key;
				$valuearray_7ree[$key]=upload_file_7ree($thisimage_7ree,$folder_7ree,0,$subname_7ree);
				$imgdbnamearray_7ree[] = $key;
			}
		}
		if(COUNT($wherearray_7ree)){//更新
				$where_7ree = '';
				foreach($wherearray_7ree as $key => $thiswhere_7ree){
						if($where_7ree){
							$where_7ree = $where_7ree." AND ".$key."='".$thiswhere_7ree."' ";
						}else{
							$where_7ree = " WHERE ".$key."='".$thiswhere_7ree."' ";
						}
				}
				//查询是否有附件
				if(COUNT($imgdbnamearray_7ree)){
				$imagedb_7ree = implode(" , ",$imgdbnamearray_7ree);
					$thisquery_7ree = DB::fetch_first("SELECT ".$imagedb_7ree." FROM ".DB::table($dbname_7ree).$where_7ree);
					if(COUNT($thisquery_7ree)){
						//附件删除
						foreach($thisquery_7ree as $thisimg_7ree){
								if(file_exists($folder_7ree.'/'.$thisimg_7ree)){
										unlink($folder_7ree.'/'.$thisimg_7ree);
								}
						}
					}
				}
				//附件上传和更新
				DB::update($dbname_7ree, $valuearray_7ree, $wherearray_7ree);
			
		}else{//新增
				//附件上传
				DB::insert($dbname_7ree, $valuearray_7ree);
				$infoid_7ree = DB::insert_id();
		}


}


function data_delete_7ree($dbname_7ree,$wherearray_7ree,$imagearray_7ree,$folder_7ree){//删除,$imagearray_7ree为附件图片字段名
		global $_G;
		if(!$dbname_7ree || !COUNT($wherearray_7ree)) return FALSE;
		$where_7ree = '';
		foreach($wherearray_7ree as $key => $thiswhere_7ree){
				if($where_7ree){
					$where_7ree = $where_7ree." AND ".$key."='".$thiswhere_7ree."' ";
				}else{
					$where_7ree = " WHERE ".$key."='".$thiswhere_7ree."' ";
				}
		}
		//查询是否有附件
		if(COUNT($imagearray_7ree)){
		$image_7ree = implode(" , ",$imagearray_7ree);
			$thisquery_7ree = DB::fetch_first("SELECT ".$image_7ree." FROM ".DB::table($dbname_7ree).$where_7ree);
			if(COUNT($thisquery_7ree)){
				//附件删除
				foreach($thisquery_7ree as $thisimg_7ree){
						if(file_exists($folder_7ree.'/'.$thisimg_7ree)){
								unlink($folder_7ree.'/'.$thisimg_7ree);
						}
				}
			}
		}
		//数据删除
		DB::query("DELETE FROM ".DB::table($dbname_7ree)." ".$where_7ree);
		return TRUE;
}


function extcredit_7ree($extcredit_7ree,$cost_7ree,$uid_7ree,$code_7ree,$id_7ree){//用户积分检测及扣除, $code_7ree为日志记录参数,$id_7ree为日志必填id
		global $_G;
			$uid_7ree = $uid_7ree ? $uid_7ree : $_G['uid'];
			$membercount_7ree = getuserprofile('extcredits'.$extcredit_7ree);
			if($membercount_7ree < $cost_7ree){
			 		return FALSE;
			 }else{
					updatemembercount($uid_7ree, array($extcredit_7ree => "-".$cost_7ree), false, $code_7ree,$id_7ree);
					return TRUE;
			 }
}


function upload_file_7ree($files_7ree,$folder_7ree,$maximgsize_7ree,$subname_7ree){//图片上传
		$file_type = $files_7ree['type'];
		$file_size = $files_7ree['size'];
		$file_type_arr = array('image/gif','image/png','image/x-png','image/jpg','image/jpeg','image/pjpeg');
		if(!in_array($file_type,$file_type_arr)) exit("The file type ($files_7ree[type]) only can be: png,jpeg,jpg,gif");
		$maximgsize_7ree = $maximgsize_7ree ? $maximgsize_7ree : 8192;
		if($file_size > 1024*$maximgsize_7ree) exit("This image is too big.");
		$knamearray = explode(".",$files_7ree["name"]);
		$kname      = $knamearray[count($knamearray)-1];
		$rand_str   = date("YmdHis");
		$file_name  = $subname_7ree."_".$rand_str.".".$kname;
		$savepath = $folder_7ree."/";
		if( !is_dir($savepath) ) mkdir($savepath);
		$upfile = $savepath.$file_name;
		if(!move_uploaded_file($files_7ree['tmp_name'],$upfile)) {
			    if(!copy($files_7ree['tmp_name'],$upfile)){
			        exit("Upload error, please check your file type or folder access.");
			    }else{
			        @unlink($files_7ree['tmp_name']);
			        return $file_name;
			    }
		}else{
		  		return $file_name;
		}
}
?>