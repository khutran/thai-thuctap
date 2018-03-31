<?php

class PT_Owl_Carousel{
	
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
		
		wp_enqueue_script( 'owl-carousel', get_template_directory_uri() .'/extensions/owl-carousel/js/owl.carousel.min.js', array('jquery'), '1.0', true);
		wp_enqueue_script( 'owl-init', get_template_directory_uri() .'/extensions/owl-carousel/js/owl-init.js', array('jquery'), '1.0', true);
		wp_enqueue_style( 'owl-style', get_template_directory_uri() .'/extensions/owl-carousel/css/owl.carousel.css' );
		wp_enqueue_style( 'owl-transitions', get_template_directory_uri() .'/extensions/owl-carousel/css/owl.transitions.css' );
	
	}
}

$pt_owl_carousel = PT_Owl_Carousel::getInstance();

