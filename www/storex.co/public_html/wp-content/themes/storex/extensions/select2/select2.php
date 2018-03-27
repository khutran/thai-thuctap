<?php 

class PT_Select2{
	
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
	
	function init(){
		add_action( 'wp_enqueue_scripts', array(&$this, 'print_scripts_styles'));
	}
	
	function print_scripts_styles(){
		wp_enqueue_script( 'select2', get_template_directory_uri() .'/extensions/select2/js/select2.min.js', array('jquery'), '3.5.2', true);
		wp_enqueue_script( 'select2-helper', get_template_directory_uri() .'/extensions/select2/js/helper.js', array('jquery'), '1.0', true);
		wp_enqueue_style( 'select2', get_template_directory_uri() .'/extensions/select2/css/select2.css', true);
	}	

}

$pt_select2 = PT_Select2::getInstance();