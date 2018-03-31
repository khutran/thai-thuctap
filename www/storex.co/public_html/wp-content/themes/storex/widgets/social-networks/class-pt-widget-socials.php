<?php
/*
 * Storex Social Networks
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_action( 'widgets_init', create_function( '', 'register_widget( "pt_socials_widget" );' ) );

class pt_socials_widget extends WP_Widget {
	
	function __construct() {
		parent::__construct(
			'pt-socials-widget', // Base ID
			esc_html__('PT Social Icons', 'storex'), // Widget Name
			array(
				'classname' => 'pt-socials-widget',
				'description' => 'Plum Tree special widget. Displays a list of social media website icons and a link to your profile.',
			),
			array(
				'width' => 600,
			)
		);

		// Register Stylesheets
		add_action('admin_print_styles', array($this, 'register_admin_styles'));
		add_action('wp_enqueue_scripts', array($this, 'register_widget_styles'));

		include(trailingslashit( get_template_directory() ).'/widgets/social-networks/social-networks.php');
	}
	public function form( $instance ) {

		global $social_accounts;
		$data = array();
		foreach ($social_accounts as $site => $id) {
			if($instance[$id] == '') { $data[$id] = 'http://'; }
			else { $data[$id] = $instance[$id]; }
		}

		$data['title'] = $instance['title'];
		$data['icon_size'] = $instance['icon_size'];
		$data['show_title'] = $instance['show_title'];
		$data['layout_type'] = $instance['layout_type'];

	?>
		<div class="social_icons_widget">

			<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e('Title:', 'storex'); ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr($data['title']); ?>" /></p>

			<?php
			$icon_sizes = array(
				'Small (16px)' => 'small',
				'Medium (24px)' => 'medium',
				'Large (32px)' => 'large',
			);
			?>

			<p class="icon_options"><label for="<?php echo $this->get_field_id('icon_size'); ?>"><?php esc_html_e('Icon Size:', 'storex'); ?></label>
				<select id="<?php echo $this->get_field_id('icon_size'); ?>" name="<?php echo $this->get_field_name('icon_size'); ?>">
				<?php
				foreach($icon_sizes as $option => $value) :

					if(esc_attr($data['icon_size'] == $value)) { $selected = ' selected="selected"'; }
					else { $selected = ''; }
				?>
				
					<option value="<?php echo $value; ?>"<?php echo $selected; ?>><?php echo $option; ?></option>
				
				<?php endforeach; ?>
				</select>
			</p>

			<?php if(esc_attr($data['show_title'] == 'show')) { $checked = ' checked="checked"'; } else { $checked = ''; } ?>
			<p class="label_options"><input type="checkbox" id="<?php echo $this->get_field_id('show_title'); ?>" name="<?php echo $this->get_field_name('show_title'); ?>" value="show"<?php echo $checked; ?> /> <label for="<?php echo $this->get_field_id('show_title'); ?>"><?php esc_html_e('Hide Title', 'storex'); ?></label></p>

			<?php if(esc_attr($data['layout_type'] == 'inline')) { $checked = ' checked="checked"'; } else { $checked = ''; } ?>
			<p class="label_options"><input type="checkbox" id="<?php echo $this->get_field_id('layout_type'); ?>" name="<?php echo $this->get_field_name('layout_type'); ?>" value="inline"<?php echo $checked; ?> /> <label for="<?php echo $this->get_field_id('layout_type'); ?>"><?php esc_html_e('Inline Mode', 'storex'); ?></label></p>

			<ul class="social_accounts">
				<?php foreach ($social_accounts as $site => $id) : ?>
					<li><label for="<?php echo $this->get_field_id($id); ?>" class="<?php echo $id; ?>"><?php echo $site; ?>:</label>
						<input class="widefat" type="text" id="<?php echo $this->get_field_id($id); ?>" name="<?php echo $this->get_field_name($id); ?>" value="<?php echo esc_attr($data[$id]); ?>" /></li>
				<?php endforeach; ?>
			</ul>

		</div>
		<?php 
	}

	public function update($new_instance, $old_instance) {
		global $social_accounts;
		$instance = array();

		foreach ($social_accounts as $site => $id) {
			$instance[$id] = $new_instance[$id];
		}

		$instance['title'] = $new_instance['title'];
		$instance['icon_size'] = $new_instance['icon_size'];
		$instance['show_title'] = $new_instance['show_title'];
		$instance['layout_type'] = $new_instance['layout_type'];

		return $instance;
	}

	public function widget( $args, $instance ) {

		global $social_accounts;
		extract($args);

		$title = empty($instance['title']) ? 'Follow Us' : apply_filters('widget_title', $instance['title']);
		$icon_size = empty($instance['icon_size']) ? 'small' : $instance['icon_size'];
		$show_title = $instance['show_title'];
		$layout_type = $instance['layout_type'];

		echo $before_widget;

		if($show_title == '') {
			echo $before_title;
			echo $title;
			echo $after_title;
		}

		if($layout_type == 'inline') { $ul_class = 'inline-mode '; }
		else { $ul_class = ''; }
		
		$ul_class .= 'icons-'.$icon_size;

		echo '<ul class="'.$ul_class.'">'; 

		foreach($social_accounts as $title => $id) : 
			if($instance[$id] != '' && $instance[$id] != 'http://') :
				$url = $instance[$id];

				echo '<li class="social-network">';
				echo '<a href="'.$instance[$id].'" target="_blank" title="'.esc_html__('Connect us on ', 'storex').$title.'"><i class="fa fa-'.$id.'"></i></a>';
				echo '</li>';
									
			endif;
		endforeach; 

		echo '</ul>'; 

		echo $after_widget;
	}

	function register_admin_styles() {
		wp_enqueue_style( 'plumtree-social-icons-widget-admin', get_template_directory_uri() .'/widgets/social-networks/css/widget_socials_admin.css', true);
	}

	function register_widget_styles() {
		wp_enqueue_style( 'social-icons-widget-widget', get_template_directory_uri() .'/widgets/social-networks/css/widget_socials.css', true);
	}

}
