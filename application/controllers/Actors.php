<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Actors extends CI_Controller {
	
	public function index()
	{
		// /search.html/distance-15-44102
		
		$sql = "SELECT * FROM signup WHERE 1=1 ";
		
		$args = func_get_args();
		foreach ($args as $param) {
			$params = explode('-',$param);
			var_dump($params);
			$key = $params[0];
			
			switch ($key) {
				case "height":
					$sql .= $this->build_height($params);
					break;
				case "weight":
					$sql .= $this->build_weight($params);
					break;
				case "gender":
					$sql .= " AND gender = '" . $params[1] . "'";
					break;
				case "distance":
					$sql .= $this->build_distance($params);
					break;
			}
		}
		
		echo('<pre>' . $sql . '</pre>');
		//$this->load->view('front-page/index.html');
	}
	
	private function build_distance($params) {
		$this->load->helper('zip');
		$this->load->helper('radius');
		
		$distance = $params[1];
		$zip = $params[2];
		
		$zip_info = getZipInfo($zip);
		if ($zip_info != '') {
			var_dump($zip_info);
			
			$zcdRadius = new RadiusAssistant($zip_info['latitude'], $zip_info['longitude'], $distance);
                    $minLat = $zcdRadius->MinLatitude();
                    $maxLat = $zcdRadius->MaxLatitude();
                    $minLong = $zcdRadius->MinLongitude();
                    $maxLong = $zcdRadius->MaxLongitude();
			$minLat = $zcdRadius->MinLatitude();
			$maxLat = $zcdRadius->MaxLatitude();
			$minLong = $zcdRadius->MinLongitude();
			$maxLong = $zcdRadius->MaxLongitude();
			
			$sql = " AND Latitude >= " . $minLat . " ".
                        " AND Latitude <= " . $maxLat . " ".
                        " AND Longitude >= " . $minLong . " ".
                        " AND Longitude <= " . $maxLong . " ";
			return $sql;
		} else {
			return '';
		}
		
		
	}
	
	private function build_height($params) {
		$from = isset($params[1]) ? $params[1] : 0;
		$to = isset($params[2]) ? $params[2] : 99;
		
		$sql = " AND height BETWEEN $from AND $to ";
		return $sql;
	}
	
	private function build_weight($params) {
		$from = isset($params[1]) ? $params[1] : 0;
		$to = isset($params[2]) ? $params[2] : 99;
		
		$sql = " AND weight BETWEEN $from AND $to ";
		return $sql;
	}
	
}
?>