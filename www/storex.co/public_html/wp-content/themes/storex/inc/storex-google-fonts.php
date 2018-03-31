<?php

function theme_slug_fonts_url() {
	$fonts_url = '';
	$lato= esc_html_x( 'on', 'Lato font: on or off', 'theme-slug' );
	$lora = esc_html_x( 'on', 'Lora font: on or off', 'theme-slug' );
	$open_sans = esc_html_x( 'on', 'Open Sans font: on or off', 'theme-slug' );

	if ( 'off' !== $lato || 'off' !== $lora || 'off' !== $open_sans ) {
		$font_families = array();
 
		if ( 'off' !== $lato ) {
			$font_families[] = 'Lato:100,300,400,700,800,900,400italic';
		}
 
		if ( 'off' !== $lora ) {
			$font_families[] = 'Lora:400,700,400italic';
		}
	
		if ( 'off' !== $open_sans ) {
			$font_families[] = 'Open Sans:700italic,400,800,600';
		}
	}

	$query_args = array(
		'family' => urlencode( implode( '|', $font_families ) ),
		'subset' => urlencode( 'latin,latin-ext' )
);

$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );

return $fonts_url;
}

function theme_slug_scripts_styles() {
wp_enqueue_style( 'theme-slug-fonts', theme_slug_fonts_url(), array(), null );
}
add_action( 'wp_enqueue_scripts', 'theme_slug_scripts_styles' );


