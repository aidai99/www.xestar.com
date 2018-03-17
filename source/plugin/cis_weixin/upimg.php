<?php

function immwa_upload_img( $file,$width='828', $height='9999',$max=2048){
	global $_G;

	$file['size'] > ($max * 1024) && showmessage('file_size_overflow', '', array('size' => $max * 1024));
	$upload = new discuz_upload();
	$uploadtype = 'immwa';

	if(!is_array($file) || empty($file) || !$upload->is_upload_file($file['tmp_name']) || trim($file['name']) == '' || $file['size'] == 0) {
		$upload->attach = array();
		$upload->errorcode = -1;
		return false;
	} else {
    $upload->type = discuz_upload::check_dir_type($uploadtype);
		
		$upload->extid = intval($_G['timestamp']);
		$upload->forcename = '';

		$file['size'] = intval($file['size']);
		$file['name'] =  trim($file['name']);
		$file['thumb'] = '';
		$file['ext'] = $upload->fileext($file['name']);

		$file['name'] =  dhtmlspecialchars($file['name'], ENT_QUOTES);
		if(dstrlen($file['name']) > 90) {
			$file['name'] = cutstr($file['name'], 80, '').'.'.$file['ext'];
		}
		$file['isimage'] = $upload->is_image_ext($file['ext']);
		$file['extension'] = $upload->get_target_extension($file['ext']);
		$file['attachdir'] = immwa_get_target_dir($upload->type);
		$file['attachment'] = $file['attachdir'].$upload->get_target_filename($upload->type, $upload->extid, $upload->forcename).'.'.$file['extension'];
		$file['target'] = getglobal('setting/attachdir').'./'.$upload->type.'/'.$file['attachment'];
		$upload->attach = & $file;
		$upload->errorcode = 0;
	}
	if(!$upload->save()) {
		cpmsg($upload->errormessage(), '', 'error');
	}

	if($file['isimage']){
		require_once libfile('class/image');
		$img = new image;
		$h=$height=='9999'?$upload->attach['imageinfo'][1]:$height;
		$w=$upload->attach['imageinfo'][0]>$width?$width:$upload->attach['imageinfo'][0];
		
		$pic=$img->Thumb($upload->attach['target'], './'.$uploadtype.'/'.$upload->attach['attachment'], $w, $h, 'fixwr');
		return $upload->attach['attachment'];
	}else{
		return $upload->attach['attachment'];
	}
}

function immwa_get_target_dir($type, $check_exists = true) {

	$subdir = $subdir1 = $subdir2 = '';
	$subdir1 = date('Ym');
	$subdir2 = date('d');
	$subdir = $subdir1.'/'.$subdir2.'/';
	$check_exists && discuz_upload::check_dir_exists($type, $subdir1, $subdir2);
	return $subdir;
}

?>