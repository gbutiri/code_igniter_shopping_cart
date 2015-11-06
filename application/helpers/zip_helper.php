<?php 
function getZipInfo($inZip, $echo = false) {
	$inZip = urlencode($inZip);
	$url = "http://maps.googleapis.com/maps/api/geocode/json?address=".$inZip."&sensor=true";
	$zip_obj = file_get_contents($url);
	
	// echo ( $zip_obj ) ;
	$zip_obj = json_decode($zip_obj);
	
	$output = '';
	if (isset($zip_obj->results[0])) {
		$latitude = $zip_obj->results[0]->geometry->location->lat;
		$longitude = $zip_obj->results[0]->geometry->location->lng;
		
		//var_dump($zip_obj);
		$formatted_address = $zip_obj->results[0]->formatted_address;
		foreach ($zip_obj->results as $zips) {
			$zipcode="";
			$city="";
			$street_number="";
			$street="";
			$city2="";
			$state="";
			$state_abbr="";
			$country="";
			$country_abbr="";
			foreach ($zips->address_components as $add_comp) {
				// var_dump ($add_comp);
				if (isset($add_comp->types[0])) {
					switch ($add_comp->types[0]) {
						case "postal_code":
							$zipcode = $add_comp->long_name;
							// var_dump("postcode: ".$zipcode);
							break;
						case "locality":
							$city = $add_comp->long_name;
							// var_dump("city: ".$city);
							break;
						case "street_number":
							$street_number = $add_comp->long_name;
							// var_dump("city: ".$city);
							break;
						case "route":
							$street = $add_comp->long_name;
							// var_dump("city: ".$city);
							break;
						case "administrative_area_level_2":
							$city2 = $add_comp->long_name;
							// var_dump("city2: ".$city2);
							break;
						case "administrative_area_level_1":
							$state = $add_comp->long_name;
							// var_dump("state: ".$state);
							$state_abbr = $add_comp->short_name;
							// var_dump("state abbr: ".$state_abbr);
							break;
						case "country":
							$country = $add_comp->long_name;
							// var_dump("country: ".$country);
							$country_abbr = $add_comp->short_name;
							// var_dump("country abbr: ".$country_abbr);
							break;
					}
					
				}
			}
			if ($echo) {
				$output .= '<div><a href="javascript:fillHidden(\''.$city.'\',\''.$state.'\',\''.$zipcode.'\',\''.$country.'\');void(0);">'.$city.' '.$city2.' '.$state.' '.$zipcode.' '.$country.'</a></div>';
			} elseif ($output === '') {
				$output = array(
					"city" => $city,
					"city2" => $city2,
					"street_number" => isset($street_number) ? $street_number : '',
					"street" => isset($street) ? $street : '',
					"state" => isset($state) ? $state : '',
					"state_abbr" => isset($state_abbr) ? $state_abbr : '',
					"country_abbr" => isset($country_abbr) ? $country_abbr : '',
					"zip" => isset($zipcode) ? $zipcode : '',
					"country" => isset($country) ? $country : '',
					"latitude" => isset($latitude) ? $latitude : 0,
					"longitude" => isset($longitude) ? $longitude : 0,
				);
			}
			$output['formatted_address'] = $formatted_address;
		}
	}
	if ($echo) {
		echo $output;
	} else {
		return $output;
	}
}

function check_permission() {
	global $_USER, $_UTYPE, $_SECTION, $_AREA, $_PAGE;
	
	/*
	var_dump(array(
		'_USER' => $_USER, 
		'_UTYPE' => $_UTYPE, 
		'_SECTION' => $_SECTION, 
		'_AREA' => $_AREA, 
		'_PAGE' => $_PAGE
	));
	*/
	// utype vs section
	switch ($_SECTION) {
		case 'dash':
			if ($_USER['limited']) {
				$allowed_areas = array('login/','index/','companies/','locations/');
			} else {
				$allowed_areas = array('login/','index/','companies/','locations/','users/','jobs/','scripts/');
			}
			break;
		case 'hr':
			$allowed_areas = array('login/','index/','locations/','jobs/','company/','payments/');
			break;
		case 'recruiter':
			$allowed_areas = array('login/','index/','my-users/','my-companies/','my-jobs/','payments/');
			break;
		case 'employee':
			$allowed_areas = array('login/','index/','my-applications/','payments/');
			break;
		default:
			
	}
	
	//var_dump($allowed_areas);
	//exit(0);
	
	if (!in_array($_AREA,$allowed_areas)) {
		header('location: /' . $_UTYPE);
	}
}
?>