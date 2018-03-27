<?php
/**
 * Plumtree Search
 *
 * Configurable search widget, set custom input text and submit button text.
 *
 * @author StartBox Extended By TransparentIdeas
 * @package StartBox
 * @subpackage Widgets
 * @since 0.01
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_action( 'widgets_init', create_function( '', 'register_widget( "pt_search_widget");' ) );

class pt_search_widget extends WP_Widget {
	
	public function __construct() {
		parent::__construct(
	 		'pt_search_widget', // Base ID
			esc_html__('PT Search', 'storex'), // Name
			array('description' => esc_html__( "Plum Tree special widget. A search form for your site.", "storex" ), ) 
		);
	}

	public function form($instance) {
		$defaults = array(
			'title' => 'Search Field',
			'search-input' => 'Text here...',
			'search-button' => 'Find'
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
	?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e( 'Title: ', 'storex' ) ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e( 'Input Text: ', 'storex' ) ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('search-input') ); ?>" name="<?php echo esc_attr( $this->get_field_name('search-input') ); ?>" type="text" value="<?php echo esc_attr( $instance['search-input'] ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e( 'Button Title Text: ', 'storex' ) ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('search-button') ); ?>" name="<?php echo esc_attr( $this->get_field_name('search-button') ); ?>" type="text" value="<?php echo esc_attr( $instance['search-button'] ); ?>" />
		</p>
	<?php
	}

	public function update($new_instance, $old_instance) {
		$instance = $old_instance;

		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['search-input'] = strip_tags( $new_instance['search-input'] );
		$instance['search-button'] = strip_tags( $new_instance['search-button'] );

		return $instance;
	}

	public function widget($args, $instance) {
		extract($args);

		$title = apply_filters('widget_title', $instance['title'] );
		$text = ( isset($instance['search-input']) ? $instance['search-input'] : 'Text here...' );
		$button = ( isset($instance['search-button']) ? $instance['search-button'] : 'Find' );

		echo $before_widget;
		if ($title) { echo $before_title . $title . $after_title; }
	?>
	
		<div class="search-wrapper">
			<div class="show-search" title="Click to show search-field"><i class="fa fa-search"></i></div>
			<div id="pt-searchform-container">
				<form class="pt-searchform" method="get" action="<?php echo esc_url( home_url() ); ?>">
					<input id="s" name="s" type="text" class="searchtext" value="" title="<?php echo esc_attr( $text ); ?>" placeholder="<?php echo esc_attr( $text ); ?>" tabindex="1" />
					<input id="searchsubmit" type="submit" class="search-button" value="<?php echo esc_attr( $button ); ?>" title="Click to search" tabindex="2" />
				</form>
			</div>
		</div>
		
		<script type="text/javascript">
		jQuery(document).ready(function($){
			$(window).load(function(){
			
			function SearchPT(container, s_click, s_width){
			
				var position='closed';
				var closedWidth = $(container).width();
				
				function srchAnimate(){
					if(position != 'opened'){
						$(container).animate({
						width:s_width}, 400, 'easeInOutQuad', function(){ 
										position = 'opened';
									})
						$('#pt-searchform-container').addClass('open');
					}
					else {
						$(container).animate({
						width:closedWidth}, 400, 'easeInOutQuad', function(){ 
										position = 'closed';
									})
						$('#pt-searchform-container').removeClass('open');
					}
				};
				
				$(s_click).click(srchAnimate);
			};
			
			SearchPT('#pt-searchform-container', '.show-search', 350);
			
			});
		});
		</script>

	<?php
		echo $after_widget;
	}
}
