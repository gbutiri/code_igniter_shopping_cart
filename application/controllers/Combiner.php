<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Combiner extends CI_Controller {

	public function combine() {
		$args = func_get_args();
		$type = $args[0];
		$new_args = array_shift($args);
		//var_dump($args);
		//exit(0);
		$files = implode('/',$args);
		$data['type'] = $type;
		$data['files'] = $files;

		$cache 	  = true;
		$cachedir = $_SERVER['DOCUMENT_ROOT'] . '/pub/cache';
		$cssdir   = $_SERVER['DOCUMENT_ROOT'] . '/';
		$jsdir    = $_SERVER['DOCUMENT_ROOT'] . '/';

		switch ($type) {
			case 'css':
				$base = realpath($cssdir);
				break;
			case 'javascript':
				$base = realpath($jsdir);
				break;
			default:
				// $this->output->set_header("HTTP/1.0 503 Not Implemented");
				header ("HTTP/1.0 503 Not Implemented");
				exit;
		};
				
		$elements = explode(',', $files);
		$elements[0] = '/' . $elements[0];

		// Determine last modification date of the files
		$lastmodified = 0;

		while (list(,$element) = each($elements)) {
			$path = realpath($base . '/' . $element);

			if (($type == 'javascript' && substr($path, -3) != '.js') || 
				($type == 'css' && substr($path, -4) != '.css')) {
				// $this->output->set_header("HTTP/1.0 403 Forbidden");
				header ("HTTP/1.0 403 Forbidden");
				exit;	
			}

			if (substr($path, 0, strlen($base)) != $base || !file_exists($path)) {
				header ("HTTP/1.0 404 Not Found");
				// $this->output->set_header("HTTP/1.0 404 Not Found");
				exit;
			}
			
			$lastmodified = max($lastmodified, filemtime($path));
		}
		// Send Etag hash
		$hash = $lastmodified . '-' . md5($files);
		//$this->output->set_header("Etag: \"" . $hash . "\"");
		header ("Etag: \"" . $hash . "\"");
			//var_dump(isset($_SERVER['HTTP_IF_NONE_MATCH']) && 
			//stripslashes($_SERVER['HTTP_IF_NONE_MATCH']) == '"' . $hash . '"');
			//exit(0);

		if (isset($_SERVER['HTTP_IF_NONE_MATCH']) && 
			stripslashes($_SERVER['HTTP_IF_NONE_MATCH']) == '"' . $hash . '"') 
		{
			// Return visit and no modifications, so do not send anything
			//$this->output->set_header("HTTP/1.0 304 Not Modified");
			//$this->output->set_header('Content-Length: 0');
			header ("HTTP/1.0 304 Not Modified");
			header ('Content-Length: 0');
			exit(0);
		} 
		else 
		{
			// First time visit or files were modified
			if ($cache) 
			{
				// Determine supported compression method
				$gzip = strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip');
				$deflate = strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'deflate');

				// Determine used compression method
				$encoding = $gzip ? 'gzip' : ($deflate ? 'deflate' : 'none');

				// Check for buggy versions of Internet Explorer
				if (!strstr($_SERVER['HTTP_USER_AGENT'], 'Opera') && 
					preg_match('/^Mozilla\/4\.0 \(compatible; MSIE ([0-9]\.[0-9])/i', $_SERVER['HTTP_USER_AGENT'], $matches)) {
					$version = floatval($matches[1]);
					
					if ($version < 6)
						$encoding = 'none';
						
					if ($version == 6 && !strstr($_SERVER['HTTP_USER_AGENT'], 'EV1')) 
						$encoding = 'none';
				}
				
				// Try the cache first to see if the combined files were already generated
				$cachefile = 'cache-' . $hash . '.' . $type . ($encoding != 'none' ? '.' . $encoding : '');
				
				if (file_exists($cachedir . '/' . $cachefile)) {
					if ($fp = fopen($cachedir . '/' . $cachefile, 'rb')) {
						if ($encoding != 'none') {
							header ("Content-Encoding: " . $encoding);
							//$this->output->set_header("Content-Encoding: " . $encoding);
						}
					
						header ("Pragma:public");
						header ("Cache-Control: max-age=604800");
						header ("Content-Type: text/" . $type . '');
						header ("Content-Length: " . filesize($cachedir . '/' . $cachefile));
						//$this->output->set_header("Cache-Control: max-age=604800");
						//$this->output->set_header("Content-Type: text/" . $type . '');
						//$this->output->set_header("Content-Length: " . filesize($cachedir . '/' . $cachefile));
			
						fpassthru($fp);
						fclose($fp);
						exit;
					}
				}
			}

			// Get contents of the files
			$contents = '';
			reset($elements);
			while (list(,$element) = each($elements)) {
				$path = realpath($base . '/' . $element);
				$contents .= "\n\n" . file_get_contents($path);
			}

			// Send Content-Type
			header ("Content-Type: text/" . $type);
			header ("Pragma:public");
			//$this->output->set_header("Content-Type: text/" . $type . '');
			
			if (isset($encoding) && $encoding != 'none') 
			{
				// Send compressed contents
				//var_dump('here',$contents);
				$contents = gzencode($contents, 9, $gzip ? FORCE_GZIP : FORCE_DEFLATE);
				header ("Content-Encoding: " . $encoding);
				header ('Content-Length: ' . strlen($contents));
				//$this->output->set_header("Content-Encoding: " . $encoding);
				//$this->output->set_header('Content-Length: ' . strlen($contents));
				// $data['contents'] = $contents;
				echo $contents;
				// $this->load->view('combiner/css',$data);
			} 
			else 
			{
				// Send regular contents
				header ('Content-Length: ' . strlen($contents));
				//$this->output->set_header('Content-Length: ' . strlen($contents));
				//$data['contents'] = $contents;
				echo $contents;
				// $this->load->view('combiner/css',$data);
			}

			// Store cache
			if ($cache) {
				if ($fp = fopen($cachedir . '/' . $cachefile, 'wb')) {
					fwrite($fp, $contents);
					fclose($fp);
				}
			}
		}	


		
	}
	
}
