<?php

class PT_Isotope1{
	
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
		
		wp_enqueue_script( 'isotope', get_template_directory_uri() .'/extensions/isotope/js/jquery.isotope.min.js', array('jquery'), '1.5.25', true);
		wp_enqueue_script( 'isotope-init', get_template_directory_uri() .'/extensions/isotope/js/isotope-init.js', array('jquery'), '1.0', true);
	
	}
}

$pt_isotope = PT_Isotope1::getInstance();

