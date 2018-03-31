<?php

class PT_MagnificPopup{
	
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
		wp_enqueue_script( 'magnific-popup', get_template_directory_uri() . '/extensions/magnific/js/jquery.magnific-popup.min.js', array('jquery'), '1.0', true);
		wp_enqueue_script( 'magnific-popup-helper', get_template_directory_uri() .'/extensions/magnific/js/helper.js', array('jquery'), '1.0', true);
		wp_enqueue_style( 'magnific-popup', get_template_directory_uri() .'/extensions/magnific/css/magnific-popup.css', array(), '1.0');
	}	
}

$pt_magnific = PT_MagnificPopup::getInstance();

/* Adding magnific popup only if attached to image file */

add_filter( 'post_gallery', 'pt_default_gallery', 10, 2);
function pt_default_gallery($output , $attr) {
	if ( ! empty( $attr['link'] ) && 'file' === $attr['link'] )
		add_filter('gallery_style', 'pt_add_gallery_class');
}
function pt_add_gallery_class($output){
	$new_output =  str_replace( 'gallery galleryid', 'gallery magnific-gallery galleryid', $output);
	return $new_output;
};
