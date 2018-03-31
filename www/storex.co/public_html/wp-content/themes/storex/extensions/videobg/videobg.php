<?php

class PT_Videobg{
	
	private static $instance;
	
	private function __construct(){
		$this->init();
	}
	
	static function getInstance(){
	
		if (self::$instance == null) { 
			self::$instance = new self();
		} 
	
		return self::$instance;
	}
	
	private function init(){
		add_action( 'wp_enqueue_scripts', array(&$this, 'print_scripts_styles'));
	}
	
	function print_scripts_styles(){
		
		wp_enqueue_script( 'videobg', get_template_directory_uri() .'/extensions/videobg/js/jquery.videoBG.js', array('jquery'), '1.5');
		wp_enqueue_script( 'videobg-init', get_template_directory_uri() .'/extensions/videobg/js/helper.js', array('jquery'), '1.0');
	
	}

}

$pt_videobg = PT_Videobg::getInstance();

