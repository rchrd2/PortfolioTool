<?php
/********************************************************************************
 * Example Configuration
 *******************************************************************************/ 

$config['example'] = array(
	/*
	 * This is where you build your site with content
	 */ 
	'content_dir' => dirname(__FILE__).'/example/content',
	/*
	 * This is where the generated site gets stored to
	 */ 
	'output_dir' => dirname(__FILE__).'/example/output',
	/*
	 * This is the directory of the theme to use
	 */ 
	'theme_dir' => dirname(__FILE__).'/themes/rchrd',
	/*
	 * This is the base url to prepend to urls in the generated site
	 */ 
	'baseurl' => '',
	/*
	 * This is the path on the server for rsyncing
	 */ 
	'server_dir' => 'example@example.com:/home/example/sites/example.com/public/',
);
