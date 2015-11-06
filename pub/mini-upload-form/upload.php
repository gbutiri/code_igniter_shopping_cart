<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config.php');

// A list of permitted file extensions
$allowed = array('png', 'jpg', 'gif','zip');

if(isset($_FILES['upl']) && $_FILES['upl']['error'] == 0){

	$extension = pathinfo($_FILES['upl']['name'], PATHINFO_EXTENSION);

	if(!in_array(strtolower($extension), $allowed)){
		echo json_encode(array('status' => 'error'));
		exit;
	}

	if (!is_dir('uploads')) {mkdir('uploads');}
	
	if(move_uploaded_file($_FILES['upl']['tmp_name'], 'uploads/' . $_FILES['upl']['name'])){
		
		$coid = (int)$_POST['coid'];
		$islogo = isset($_POST['islogo']);
		$isactive = isset($_POST['isactive']);
		$filename = isset($_FILE['']);
		
		// TODO -> create tht thumb 
		
		// assign image to database.
		define('_MEDIA_TYPE_PHOTO',1); // 
		$sql_m = "INSERT INTO company_media SET 
			company_id = ?, 
			is_logo = ?,
			is_active = ?,
			media_type = ?,
			media_code = ?
			";
		$media_id = sql_run_get_id($sql_m, 'iiiis', array(
			$coid,
			$islogo,
			$isactive,
			_MEDIA_TYPE_PHOTO,
			$_FILES['upl']['name'])
		);
		
		include(_DOCROOT . '/modules/pages/companies/companies-tmpl.php');

		ob_start();
		$sql_mf = "SELECT * FROM company_media WHERE company_media_id = ?";
		$file_data = sql_get($sql_mf, 'i', array($media_id));
		$file = $file_data[0];
		render_media_thumb($file);
		$html = ob_get_contents();
		ob_end_clean();
		
		echo json_encode(array(
			'status' => 'success',
			//'post' => $_POST,
			'file' => 'uploads/' . $_FILES['upl']['name'],
			'media_id' => $media_id,
			'appends' => array('#company_media_files' => $html)
		));
		exit(0);
	}
}

echo json_encode(array('status' => 'error'));
exit;