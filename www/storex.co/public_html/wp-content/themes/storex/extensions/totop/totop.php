<?php

class PT_Totop{
	
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
		wp_enqueue_script('totop', get_template_directory_uri() .'/extensions/totop/js/jquery.ui.totop.min.js', array('jquery'), '1.2', true);
		wp_enqueue_script('totop-helper', get_template_directory_uri() .'/extensions/totop/js/helper.js', array('jquery', 'totop'), '1.0', true);
	}
}

$pt_totop = PT_Totop::getInstance();