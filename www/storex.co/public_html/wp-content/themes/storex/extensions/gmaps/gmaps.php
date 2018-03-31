<?php 

class PT_Gmaps{
	
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
		//add_action( 'wp_enqueue_scripts', array(&$this, 'print_scripts_styles'));
		add_shortcode( 'gmap', array(&$this, 'gmap_short') );
	}
	
	function print_scripts_styles(){
		wp_enqueue_script( 'gmapslib', 'http://maps.google.com/maps/api/js?sensor=true', array('jquery'), '1.0', array(), true );
		wp_enqueue_script( 'gmaps', get_template_directory_uri() .'/extensions/gmaps/js/gmaps.js', array('jquery'), '0.4.3', true);
		wp_enqueue_script( 'gmaps-helper', get_template_directory_uri() .'/extensions/gmaps/js/helper.js', array('jquery'), '1.0', true);
	}
	
	function gmap_short($atts){
		extract(shortcode_atts( array(
			'data' => "address:New York - NY 10017",
			'width' => '300px',
			'height' => '150px',
 		), $atts ) );
 		
 		$html = '';
 		
 		$html = '<div class="gmaps"><div style="width:'.$width.'; height:'.$height.';" data-gmap="'.$data.'"></div></div>';
 		
 		$this->print_scripts_styles();
 		
 		return $html;
	}
	
}

$pt_gmaps = PT_Gmaps::getInstance();

