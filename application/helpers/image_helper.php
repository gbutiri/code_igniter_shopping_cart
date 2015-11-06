<?php 
function do_initial_crop($media_file) {
	$file_url = '/pub/mini-upload-form/uploads/' . $media_file->media_code;
	$file_loc = $_SERVER['DOCUMENT_ROOT'] . '/' . $file_url;
	$upload_folder = $_SERVER['DOCUMENT_ROOT'] . '/pub/mini-upload-form/uploads/thumbs';
	$ext = strtolower(pathinfo($file_loc, PATHINFO_EXTENSION));
	
	// get original file sizes.
	list($src_w, $src_h) = getimagesize($file_loc);
	
	$dst_w = 360;
	$dst_h = $dst_w * (3/4);
	$mid = $media_file->company_media_id;

	$newPhotoFile = strtolower($upload_folder . '/' . $mid . '.' . $ext);
	
	$dst_image = imagecreatetruecolor($dst_w, $dst_h);
	
	switch ($ext) {
		case "jpg":
			$src_image = imagecreatefromjpeg($file_loc);
			break;
		case "png":
			$src_image = imagecreatefrompng($file_loc);
			break;
		case "gif":
			$src_image = imagecreatefromgif($file_loc);
			break;
	}
	
	$dst_x = 0;
	$dst_y = 0;

	$src_ratio = $src_w / $src_h;
	$dst_ratio = $dst_w / $dst_h;

	if ($src_ratio > $dst_ratio) {
		$shrinkRatio = $dst_h / $src_h ;
		$dst_w = $src_w*$shrinkRatio;
		$diffOffset = (($dst_w-360)/2);
		$dst_x = -$diffOffset;
	} elseif ($src_ratio < $dst_ratio) {
		$shrinkRatio = $dst_w / $src_w ;
		$dst_h = $src_h*$shrinkRatio;
		$diffOffset = (($dst_h-270)/2);
		$dst_y = -$diffOffset;
	}
	
	$src_x = 0;
	$src_y = 0;

	imagecopyresampled($dst_image, $src_image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);
	imagejpeg($dst_image, $newPhotoFile, 75);
}

function do_cropping($media_file,$mid,$x,$y,$w,$h) {

	
	$file_url = '/pub/mini-upload-form/uploads/' . $media_file->media_code;
	$file_loc = $_SERVER['DOCUMENT_ROOT'] . '/' . $file_url;
	$upload_folder = $_SERVER['DOCUMENT_ROOT'] . '/pub/mini-upload-form/uploads/thumbs';
	$ext = strtolower(pathinfo($file_loc, PATHINFO_EXTENSION));
	
	list($src_w, $src_h) = getimagesize($file_loc);
	
	$dst_w = 360;
	$dst_h = $dst_w * (3/4);

	$newPhotoFile = strtolower($upload_folder . '/' . $mid . '.' . $ext);
	
	$dst_image = imagecreatetruecolor($dst_w, $dst_h);
	
	switch ($ext) {
		case "jpg":
			$src_image = imagecreatefromjpeg($file_loc);
			break;
		case "png":
			$src_image = imagecreatefrompng($file_loc);
			break;
		case "gif":
			$src_image = imagecreatefromgif($file_loc);
			break;
	}
	
	$dst_x = 0;
	$dst_y = 0;
	$src_x = 0;
	$src_y = 0;

	$src_x=$x;
	$src_y=$y;
	$src_w=$w;
	$src_h=$h;

	imagecopyresampled($dst_image, $src_image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);
	imagejpeg($dst_image, $newPhotoFile, 75);

	
	
}

?>