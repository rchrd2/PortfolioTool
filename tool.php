<?php
/**
 * Generate this
 * @author Richard
 * @version $Id$
 */ 

if (PHP_SAPI !== 'cli') { exit; }



/********************************************************************************
 * Configuration
 *******************************************************************************/ 

//$content_dir = dirname(__FILE__).'/../content';
$content_dir  = '/Volumes/W. Eugene Smith/projects-documentation';
$output_dir  = dirname(__FILE__).'/../public';
$theme_dir   = dirname(__FILE__).'/themes/rchrd';

//define('BASEURL', 'http://sites/net.rchrd.dotdotslash/dotdotslash.rchrd.net/sites/portfolio.rchrd.net/public');
//define('BASEURL', 'http://portfolio.rchrd.net');
define('BASEURL', '');
define('OUTPUT_DIR', $output_dir);

/********************************************************************************
 * LOAD XML SERIALIZER
 *******************************************************************************/ 
set_include_path(get_include_path() . PATH_SEPARATOR . 'lib/PEAR');
require_once "XML/Serializer.php";
require_once "XML/Unserializer.php";
require_once "XML/Util.php";


/********************************************************************************
 * Main program
 *******************************************************************************/ 

$help_message = 
'/**
 * Help Message: (empty)
 * 
 */
';

$options     = getopt("ma:g:h");

$tool        = new PortfolioTool();

if(isset($options['m'])) {
	$tool->copy_media = true;
}

$tool->generate($content_dir, $theme_dir, $output_dir);


class PortfolioTool {
	
	public $copy_media = false;
			
	public function __construct() {}
	
	public function generate($content_dir, $theme_dir, $output_dir) {
		
		$content_map = $this->_generateContentMap($content_dir);
		$this->_generateOutput($content_map, $theme_dir, $output_dir);
	}
	
	
	/*
	 * Content Map Format:
	 * 	 array(
			'2012' => array:Projects
	 * 
	 */ 
	protected function _generateContentMap($content_dir) {
		$files = $this->_readDirectoryRecursive($content_dir);
		
		//var_dump($files);exit;
		
		$content_map = array();

		$category = '';
		
		for($i = 0; $i < count($files); $i++) {

			/*
			 * The first directly level specifies the menu categories
	 		 */

			if( ! is_array($files[$i]) && is_dir($files[$i]) ) {
				
				$category = trim(
					str_replace($content_dir, '', $files[$i]), '/');
				
				$content_map[$category] = array();
								
			} else if( is_array($files[$i]) ) {
				
				 /*
				  * The second level specifies the actual content
				  */		
				
				for($j = 0; $j < count($files[$i]); $j++) {
					
					/*
					 * We construct an array of data to be used in the render process
					 */ 
					
					//var_dump( $files[$i][$j] );
					if( is_array($files[$i][$j]) ) {
					
						$content_map[$category][] = $this->_generateProjectData(
							$content_dir,
							$files[$i][$j]
						);
					
					}
					
				}
								
			}
			
		}
		
		/*
		 * The last step is to sort the content_map by date
		 */ 
		foreach($content_map as $key => $value) {
		
			usort($content_map[$key], function($a, $b) {
				// var_dump($a);
				// var_dump($b);
				// exit;
        		return @strtotime($a['date']) < @strtotime($b['date']);
    		});
			
		}
		
		
		
		//var_dump($content_map);exit;
				
		return $content_map;
	
	}
	
	/**
	 * Takes an array of a project directory tree and generates project data
	 * aka "where the magic happens"
	 * 
	 * @param $content_dir:String the root of the content dir
	 * @param $protected:Array an array of files in the project dir
	 */ 
	protected function _generateProjectData($content_dir, $project_dir) {
		//var_dump($project_dir);
		
		/*
		 * Default project data structure
		 */ 
		$project_data = array(
			'file_path' => null,
			'body' => null,
			'date' => null,
			'title' => null,
			'links' => null,
			'materials' => null,
			'media' => array(
				'image_files'    => array(),
				'video_files'    => array(),
				'audio_files'    => array(),
				'download_files' => array(),
			)
		);
			
		
		/*
		 * Get it in an array so things are consistant 
		 */ 
		if( ! is_array($project_dir)) {
			$project_dir = array($project_dir);
		}
		
		
		$project_root = dirname($project_dir[0]);
		$project_root_relative = trim(str_replace($content_dir, '', $project_root), '/');
		
		/*
		 * The name of the file is the root . '.html'
		 */ 
		$project_data['file_path'] = 
			trim(
				$project_root_relative . '.html',
				'/'
			);;
		
		/*
		 * Look for and parse meta.xml file
		 */ 
		if( file_exists( $project_root . '/meta.xml' ) ) {
			
			$project_data = array_merge(
				$project_data, 
				$this->_parseMetaFile($project_root . '/meta.xml'));
			
		}
		
		
		/*
		 * Get the media from the directory. It goes to another function
		 * so that it can be recursive.
		 */ 
		$this->_parseProjectDirFile($project_dir, $project_data, $project_root, $project_root_relative);
		
		
		//echo "Project_dir\n";
		//var_dump($project_dir);
		
		//var_dump($project_data);

		return $project_data;
		
	}
	
	protected function _parseProjectDirFile($file, &$project_data, $project_root, $project_root_relative) {

		if( is_array($file) ) {

			foreach($file as $file1) {
				
				$this->_parseProjectDirFile($file1, $project_data, $project_root, $project_root_relative);
			}
			
		} else {
			
			$extension     = strtolower(pathinfo($file, PATHINFO_EXTENSION));
			$file_relative = trim(str_replace($project_root, '', $file), '/');
			$new_name      = str_replace(
				array('/', '#', ' ', '?', "'", '"'), 
				array('-', '' , '' , '' , '' , ''), 
				$project_root_relative . '/' . $file_relative);

			//echo $file_relative . "\n";
		
			if( in_array( $extension, array('png', 'gif', 'jpg', 'jpeg') )) {
				$project_data['media']['image_files'][] = array($file, $new_name, getimagesize($file));
			} else if ( in_array($extension, array('mov', 'mv4', 'flv') )) {
				$project_data['media']['video_files'][] = array($file, $new_name);
			} else if ( in_array($extension, array('mp3', 'wav', 'aif', 'midi') )) {
				$project_data['media']['audio_files'][] = array($file, $new_name);
			} else if ( in_array($extension, array('weblock') )) {
				/*
				 * Parse the weblock file and add it to the links array
				 */ 
				
				
			} else if ( in_array($extension, array('xml') )) {
				/*
				 * Do not accept xml files, because of the meta file.
				 */ 
			} else {
				$project_data['media']['download_files'][] = array($file, $new_name);
			}
			
		}
	}
	
	protected function _parseMetaFile($file_path) {
		/*
		 * Read the xml
		 */ 
		$ser = new XML_Unserializer();
		//$ser->setOption('complexType', 'object');
		$ser->unserialize($file_path, TRUE);
		$result = $ser->getUnserializedData();
		//var_dump($result);
		return $result;
	}
	
	protected function _generateOutput($content_map, $theme_dir, $output_dir) {
		
		/*
		 * Clean input vars
		 */ 
		$theme_dir = rtrim($theme_dir, '/');
		$output_dir = rtrim($output_dir, '/');
		
		/*
		 * We copy data from the them folder into this output folder
		 */ 
		
		$cmd = sprintf('/bin/cp -r %s %s', $theme_dir . '/public/', $output_dir . '/');
		exec($cmd);
		
		
		/*
		 * Render a default index.html file
		 */
		$output_path = $output_dir . '/index.html';
		$output_html = $this->_renderView(
			$theme_dir . '/master.php',
			array(
				'project' => null, 
				'content_map' => $content_map
			)
		);
		if( ! file_exists( dirname($output_path) ) ) {
			mkdir( dirname($output_path), 0777, true );
		}
		file_put_contents($output_path, $output_html);
		
		
		/*
		 * array( $filepath => $filedata )
		 */ 

				
		foreach($content_map as $category => $projects) {
		
			foreach($projects as $project_data) {
				

				/*
				 * Copy media over to media directory
				 */ 

				if($this->copy_media == true) {

					//echo "Copying media\n";

					if( ! file_exists( $output_dir . '/media') ) {
						mkdir( $output_dir . '/media', 0777, true );
					}

					foreach($project_data['media'] as $media_type => $media_array) {
					
						foreach($media_array as &$media_info) {
						
							list($file_path_full, $new_name) = $media_info;
						
							$new_file_path = $output_dir . '/media/' . $new_name;
						
							if($media_type == 'image_files') {
								
								// $im = new Imagick($file_path_full);
								// $im->resizeImage(
								// 	492, 
								// 	492,
								// 	Imagick::FILTER_CATROM,
								// 	0.65,
								// 	true);
								// $im->setCompressionQuality(92);
								// $im->writeImage($new_file_path);
								// $im->destroy();
								
								// Get new sizes
								
								
								$resize_result = $this->_image_resize(
									$file_path_full, 
									$new_file_path, 
									492, 
									492, 
									false
								);
								
								if( true !== $resize_result ) {
									copy($file_path_full, $new_file_path);
								}
								
							} else if ($media_type == 'audio_files') {
								
								$type = pathinfo($file_path_full, PATHINFO_EXTENSION);
								if($type == 'mp3') {
									//copy($file_path_full, $new_file_path);
									$this->_generate_waveform($file_path_full, $new_file_path);
								}
								
							}
						
						}
					
					}
				
				}
				
				
				/*
				 * Render this project through the master view.
				 */ 
				$output_path = $output_dir . '/' . $project_data['file_path'];
				$output_html = $this->_renderView(
					$theme_dir . '/master.php',
					array(
						'project' => $project_data, 
						'content_map' => $content_map
					)
				);

				/*
				 * Write the html file
				 */ 
				if( ! file_exists( dirname($output_path) ) ) {
					mkdir( dirname($output_path), 0777, true );
				}
				file_put_contents($output_path, $output_html);
				
			}
			
		}
		
		
	}
	
	
	/**
	 * Helper function for plopping data into a view
	 */ 
	protected function _renderView($view_path, $data) {
		ob_start();

		if(is_array($data) && count($data) > 0) {
			extract($data, EXTR_OVERWRITE | EXTR_REFS);
		}

		if($view_path != null && file_exists($view_path)) {
			include $view_path;
		}
		$content = ob_get_contents(); ob_end_clean();

		return $content;
	}

	/**
	 * Helper for reading a directory recursively
	 */ 
	protected function _readDirectoryRecursive($content_dir) {
		$Found = array();
		$content_dir = rtrim($content_dir, '/') . '/';
		if(is_dir($content_dir)) {
			try {
				$Resource = opendir($content_dir);
				while(false !== ($Item = readdir($Resource))) {
					$preg_result = array();
					if($Item == "." || $Item == ".." || preg_match_all('/^[\.-]+.*$/i', $Item, $preg_result, PREG_SET_ORDER) > 0) {
						continue;
					}
					if(is_dir($content_dir . $Item)) {
						$Found[] = $content_dir . $Item;
						$Found[] = $this->_readDirectoryRecursive($content_dir . $Item);
					} else {
						$Found[] = $content_dir . $Item;
					}
				}
			} catch(Exception $e) {}		
		}
		return $Found;		
	}
	

	protected function _image_resize($src, $dst, $width, $height, $crop=0) {
		//printf("_image_resize(%s, %s)", $src, $dst);

		if( ! list($w, $h) = getimagesize($src)) {
			return "Unsupported picture type!";
		}

		$type = strtolower(pathinfo($src, PATHINFO_EXTENSION));
		
		if($type == 'jpeg') {
			$type = 'jpg';
		}
		switch($type){
			case 'bmp': $img = imagecreatefromwbmp($src); break;
			case 'gif': $img = imagecreatefromgif($src); break;
			case 'jpg': $img = imagecreatefromjpeg($src); break;
			case 'png': $img = imagecreatefrompng($src); break;
			default : 
				return "Unsupported picture type!";
		}

		// resize
		if($crop){
			if($w < $width or $h < $height) {
				return "Picture is too small!";
			}
		    $ratio = max($width/$w, $height/$h);
		    $h = $height / $ratio;
		    $x = ($w - $width / $ratio) / 2;
		    $w = $width / $ratio;
		  }
		  else{
		    if($w < $width and $h < $height) {
				return "Picture is too small!";
			}
			
			if( $h > $w ) {
				$width = ($height * $w) / $h;
			} else {
				$ratio = min($width/$w, $height/$h);
				$width = $w * $ratio;
				$height = $h * $ratio;
			}
			
			$x = 0;
		}
    	
		$new = imagecreatetruecolor($width, $height);
    	
		// preserve transparency
		if($type == "gif" or $type == "png") {
			imagecolortransparent($new, imagecolorallocatealpha($new, 0, 0, 0, 127));
			imagealphablending($new, false);
			imagesavealpha($new, true);
		}
    	
		imagecopyresampled($new, $img, 0, 0, $x, 0, $width, $height, $w, $h);
    	
		switch($type){
			case 'bmp': imagewbmp($new, $dst); break;
			case 'gif': imagegif($new, $dst); break;
			case 'jpg': imagejpeg($new, $dst, 92); break;
			case 'png': imagepng($new, $dst, 2); break;
		}
		return true;
	}
	
	protected function _generate_waveform($file_path_full, $new_file_path) {
		$type = pathinfo($file_path_full, PATHINFO_EXTENSION);
		
		$waveform_filepath = $new_file_path . '.png';
		
		$wav2png_path = '/Users/richard/Sites/net.rchrd.dotdotslash/dotdotslash.rchrd.net/sites/mp3.rchrd.net/lib/wav2png/beschulz-wav2png-a03eb7b/bin/Darwin/wav2png';
		$bg_color = 'FFFFFF00';
		$fg_color = '000000FF';
		$fg_color = '999999FF';

		$width = '492';
		$height = '21';
		
		if($type == 'mp3' || $type == 'wav') {

//--norm=-10 \

			$cmd = sprintf(
"/usr/local/bin/sox \
 \
%s \
-r 246 -b 16 \
-c 1 \
-t wav - gain -5 \
| %s \
--foreground-color=%s \
--background-color=%s \
--width=%s \
--height=%s \
--db-scale \
-o %s /dev/stdin",
				escapeshellarg($file_path_full),
				$wav2png_path,
				$fg_color,
				$bg_color,
				$width,
				$height,
				escapeshellarg($waveform_filepath));
			
			echo $cmd . "\n";
			
			exec($cmd);

		} 

	}
}


