<!DOCTYPE html>
<html>
<head>
	<META http-equiv="Content-Type" content="text/html; charset=utf-8">
	<META NAME="AUTHOR" CONTENT="Richard Caceres">
	<META NAME="COPYRIGHT" CONTENT="&copy; 2012 Richard Caceres">
	<META NAME="GOOGLEBOT" CONTENT="NOARCHIVE">
	<base href="<?=BASEURL;?>/"/>
	<link href="<?=BASEURL;?>/css/screen.css" rel="stylesheet" type="text/css" charset="utf-8">
	<link href="<?=BASEURL;?>/css/styles.css" rel="stylesheet" type="text/css" charset="utf-8">
	<link href='http://fonts.googleapis.com/css?family=PT+Mono' rel='stylesheet' type='text/css'>

	<title>Richard Caceres<?= isset($project['title']) ? ' | ' . $project['title'] : '';?></title>
</head>


<body>
	<div class="container">

		<div class="column span-3" id="leftcol-portfolio">Richard C&aacute;ceres &mdash; Timeline of work

<div class="content_menu">
<?php
/*
 * This generates the menu 
 */ 

$content_map = array_reverse($content_map, true);

foreach($content_map as $category => $projects) {

	echo $category;

	for($i = 0; $i < count($projects); $i++) {
		
		if(true || $i == 0) {
			echo "\t";
		}
	
		if(isset($project) && $project['file_path'] == $projects[$i]['file_path']) {
			echo "<span class='active'>" . $projects[$i]['title'] . "</span>";

		} else {
			echo "<a href='".BASEURL.'/'.$projects[$i]['file_path']."'>" . $projects[$i]['title'] . "</a>";
		}
		
		if(true || ($i + 1) == count($projects)) {
			echo "\n";
		}
	
	}
	
	echo "\n";
	
}

?>
</div>
<br>
<div>Richard C&aacute;ceres is an artist who creates 
experimental systems for musical expression.</div>
<div>
Contact: <script type="text/javascript">
	user = "me"; site = "rchrd.net";
	document.write('<a href=\"mailto:' + user + '@' + site + '\">');
	document.write(user + '@' + site + '</a>');
</script>
</div>

</div>
		<div class="content column span-5 last">
			
			<?php if( ! isset($project)):?>

			<div class="mediabox">
				<table>
					<tr>
						<td>
							<div class="last">
								<img src="<?=BASEURL;?>/img/2012-manuscript-paper-Manuscript-paper.jpg"/>
							</div>
						</td>
					</tr>
				</table>
			</div>
			
			<div class="project_body"></div>				
				
			<?php else:?>
			
				<!--<span class="active"><?=$project['title'];?></span><br><br>-->
				
				<?php if(count($project['media']['image_files']) > 0):?>
				
				<div class="mediabox">
					<table>
						<tr>
					<?php 
					$num_images = count($project['media']['image_files']);
					for($i = 0; $i < $num_images; $i++) {
						
						list($file_path_full, $new_name) = $project['media']['image_files'][$i];
						
						/*
						 * parse the image size to print it in the html (makes for faster rendering)
						 */ 
						$imagesize = getimagesize(OUTPUT_DIR.'/media/'.$new_name);
						
						$web_path = BASEURL . '/media/' . $new_name;
						
						if(($i + 1) == $num_images) {
							echo '<td><div class="last"><img '.$imagesize[3].' src="'.$web_path.'"/></div></td>';
						} else {
							echo '<td><div><img '.$imagesize[3].' src="'.$web_path.'"/></div></td>';
						}
						
						
					}
					?>
						</tr>
					</table>
				</div>
				<?php endif;?>
				
				
				<?php if(count($project['media']['audio_files']) > 0):?>
				<div class="audio">
					<?php 
					
					for($i = 0; $i < count($project['media']['audio_files']); $i++) {
						
						list($file_path_full, $new_name) = $project['media']['audio_files'][$i]; 

						if(strtolower(pathinfo($new_name, PATHINFO_EXTENSION) == 'mp3')) {
							echo '<div class="waveform" style="background-image:url(\''.BASEURL . '/media/' . $new_name.'.png\');"></div>';
						}
						// echo '<audio 
						// 	width="492" 
						// 	height="36" 
						// 	style="width:492px;height:36px;background:url('.BASEURL . '/media/' . $new_name.'.png)" 
						// 	controls="controls">
						// 		<source type="audio/mp3" src="'.BASEURL . '/media/' . $new_name.'" />
						// 	</audio>';
					}
					
					?>
				</div>
				<?php endif;?>
				
				<div class="project_body">
				<?php
				/*
				 * This only does nl2br if there is no html
				 */ 
				if( strlen(strip_tags($project['body'])) == strlen($project['body']) ) {
					echo nl2br($project['body']);
				} else {
					echo $project['body'];
				}
				?>
				</div>
	
				<?php 
				/*
				 * Print out associated links (from weblock files or from in the meta xml
				 */ 
				if(count($project['link']) > 0):?>
				<div class="links">
					<div class="links_label">Links:</div>
					<?php 
					for($i = 0; $i < count($project['link']); $i++) {
						if( is_array($project['link'][$i]) ) {
							echo '<div class="link"><a href="'.$project['link'][$i][1].'">'.$project['link'][$i][0].'</a></div>';
						} else {
							echo '<div class="link"><a href="'.$project['link'][$i].'">'.$project['link'][$i].'</a></div>';	
						}
					}
					?>
				</div>
				<?php endif;?>
			
				<?php
				/*
				 * Print out downloads
				 */ 
				if(count($project['media']['download_files']) > 0):?>
				<div class="links">
					<div class="links_label">Additional Files:</div>
					<?php 
					for($i = 0; $i < count($project['media']['download_files']); $i++) {
						echo '<div class="link"><a href="'.BASEURL . '/media/' . $project['media']['download_files'][$i][1].'">'.basename($project['media']['download_files'][$i][0]).'</a></div>';	
					}
					?>
				</div>
				<?php endif;?>	

			
				<?php
				/*
				 * Print out materials
				 */ 
				if( isset($project['materials']) && strlen($project['materials']) > 0 ) {
					echo '<div class="materials"><span class="materials_label">Materials: </span><span class="materials_text">'.$project['materials'].'</span></div>';
				}
				?>
				
				<?php
				/*
				 * Print out date
				 */ 
				if( isset($project['date']) && strlen($project['date']) > 0 ) {
					echo '<div class="date">'.$project['date'].'</div>';
				}
				?>	
		
			
			<?php endif;?>

		</div>
	</div>
</body>
</html>
