<?php
/**
 * @version    $Id$
 * @package    IG PageBuilder
 * @author     InnoGears Team <support@innogears.com>
 * @copyright  Copyright (C) 2012 innogears.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.innogears.com
 * Technical Support:  Feedback - http://www.innogears.com
 */

if ( ! class_exists( 'IG_Audio' ) ) :

/**
 * Create Audio Player element,
 * User can choose file from local server or
 * from other sources like Souncloud
 *
 * @package  IG PageBuilder Shortcodes
 * @since    1.0.0
 */
class IG_Audio extends IG_Pb_Shortcode_Element {
	/**
	 * Constructor
	 *
	 * @return  void
	 */
	public function __construct() {
		parent::__construct();

		add_action( 'wp_ajax_validate_file', array( &$this, 'validate_file' ) );
	}

	/**
	 * Configure shortcode.
	 *
	 * @return  void
	 */
	public function element_config() {
		$this->config['shortcode'] = strtolower( __CLASS__ );
		$this->config['name']      = __( 'Audio', IGPBL );
		$this->config['cat']       = __( 'Media', IGPBL );
		$this->config['icon']      = 'icon-paragraph-text';

		// Define exception for this shortcode
		$this->config['exception'] = array(
			'admin_assets' => array(
				// Shortcode initialization
				'audio.js',
			),

			'frontend_assets' => array(
				// Bootstrap 3
				'ig-pb-bootstrap-css',
				'ig-pb-bootstrap-js',

				// Media Element
				'mediaelement-css',
				'mediaelement-js',
				'ig-pb-mediaelement-js',
			),
		);

		// Use Ajax to speed up element settings modal loading speed
		$this->config['edit_using_ajax'] = true;
	}

	/**
	 * Define shortcode settings.
	 *
	 * @return  void
	 */
	public function element_items() {
		$this->items = array(
			// Audio source dropdown list on top.
			'generalaction' => array(
				'settings'  => array(
					'id'    => 'general_action',
					'class' => 'general-action no-label pull-left',
				),
				array(
					'id'         => 'audio_sources',
					'type'       => 'select',
					'has_depend' => '1',
					'std'        => 'local',
					'options'    => array(
						'local'      => __( 'Local file', IGPBL ),
						'soundcloud' => __( 'SoundCloud', IGPBL )
					),
					'exclude_class' => array( 'form-control' )
				)
			),
			// Content tab.
			'content' => array(
				array(
					'name'    => __( 'Element Title', IGPBL ),
					'id'      => 'el_title',
					'type'    => 'text_field',
					'class'   => 'input-sm',
					'std'     => __( '', IGPBL ),
					'role'    => 'title',
					'tooltip' => __( 'Set title for current element for identifying easily', IGPBL )
				),
				array(
					'id'         => 'audio_source_link',
					'name'       => __( 'Audio link', IGPBL ),
					'type'       => 'text_append',
					'type_input' => 'text',
					'dependency' => array( 'audio_sources', '=', 'soundcloud' ),
					'class'      => 'span6 input-sm',
					'tooltip'    => __( 'Insert your audio link from SoundCloud or local file', IGPBL ),
					'specific_class' => 'input-group col-xs-12',
				),
				array(
					'id'          => 'audio_source_local',
					'name'        => __( 'File URL', IGPBL ),
					'type'        => 'select_media',
					'filter_type' => 'audio',
					'media_type'  => 'video',
					'class'       => 'input-sm',
					'dependency'  => array( 'audio_sources', '=', 'local' ),
				),
			),
			// Styling tab .
			'styling' => array(
				array(
					'type' => 'preview',
				),
				// SoundCloud parameters
				array(
					'name'                   => __( 'Dimension', IGPBL ),
					'container_class'        => 'combo-group',
					'dependency'             => array( 'audio_sources', '=', 'soundcloud' ),
					'type'                   => 'dimension',
					'id'                     => 'audio_dimension',
					'extended_ids'           => array( 'audio_dimension_width', 'audio_dimension_height' ),
					'audio_dimension_width'  => array( 'std' => '500' ),
					'audio_dimension_height' => array( 'std' => '80' ),
					'tooltip'                => __( 'Set width and height of element', IGPBL ),
				),
				array(
					'name'       => __( 'Color', IGPBL ),
					'id'         => 'audio_color',
					'type'       => 'color_picker',
					'std'        => '#FF6600',
					'dependency' => array( 'audio_sources', '=', 'soundcloud' ),
					'hide_value' => true,
					'tooltip' => __( 'Select color for Play Button', IGPBL ),
				),
				array(
					'name'            => __( 'Elements', IGPBL ),
					'id'              => 'audio_elements',
					'type'            => 'checkbox',
					'class'           => 'jsn-column-item  checkbox',
					'container_class' => 'jsn-columns-container jsn-columns-count-two',
					'dependency'      => array( 'audio_sources', '=', 'soundcloud' ),
					'std'             => 'artwork__#__download_button__#__share_button__#__bpm__#__play_count__#__comments',
					'options'         => array(
						'artwork'         => __( 'Artwork', IGPBL ),
						'download_button' => __( 'Download Button', IGPBL ),
						'share_button'    => __( 'Share Button', IGPBL ),
						'bpm'             => __( 'BPM', IGPBL ),
						'play_count'      => __( 'Play Count', IGPBL ),
						'comments'        => __( 'Comments', IGPBL )
					),
					'tooltip' => __( 'Tick elements you want to display on the player', IGPBL ),
				),
				array(
					'name'       => __( 'Auto Play', IGPBL ),
					'id'         => 'audio_auto_play',
					'type'       => 'radio',
					'std'        => '0',
					'dependency' => array( 'audio_sources', '=', 'soundcloud' ),
					'options'    => array(
						'1' => __( 'Yes', IGPBL ),
						'0' => __( 'No', IGPBL )
					),
					'tooltip' => __( 'Auto play the audio', IGPBL ),
				),
				array(
					'name'       => __( 'Start Track', IGPBL ),
					'id'         => 'audio_start_track',
					'type'       => 'text_number',
					'dependency' => array( 'audio_sources', '=', 'soundcloud' ),
					'class'      => 'input-mini',
					'tooltip' => __( 'Choosing track to start playing', IGPBL ),
				),
				/**
				 * Parameters for local audio
				 */
				array(
					'name'                         => __( 'Dimension', IGPBL ),
					'container_class'              => 'combo-group',
					'dependency'                   => array( 'audio_sources', '=', 'local' ),
					'id'                           => 'audio_local_dimension',
					'type'                         => 'dimension',
					'extended_ids'                 => array( 'audio_local_dimension_width', 'audio_local_dimension_height' ),
					'audio_local_dimension_width'  => array( 'std' => '500' ),
					'audio_local_dimension_height' => array( 'std' => '30' ),
					'tooltip'                      => __( 'Set width and height of element', IGPBL ),
				),
				array(
					'name'            => __( 'Elements', IGPBL ),
					'id'              => 'audio_local_elements',
					'type'            => 'checkbox',
					'class'           => 'jsn-column-item checkbox',
					'container_class' => 'jsn-columns-container jsn-columns-count-two',
					'dependency'      => array( 'audio_sources', '=', 'local' ),
					'std'             => 'play_button__#__current_time__#__time_rail__#__track_duration__#__volume_button__#__volume_slider',
					'options'         => array(
						'play_button'    => __( 'Play/Pause Button', IGPBL ),
						'current_time'   => __( 'Current Time', IGPBL ),
						'time_rail'      => __( 'Time Rail', IGPBL ),
						'track_duration' => __( 'Track Duration', IGPBL ),
						'volume_button'  => __( 'Volume Button', IGPBL ),
						'volume_slider'  => __( 'Volume Slider', IGPBL )
					),
					'tooltip' => __( 'Tick elements you want to display on the player', IGPBL ),
				),
				array(
					'name'         => __( 'Start volume', IGPBL ),
					'id'           => 'audio_local_start_volume',
					'type'         => 'text_append',
					'type_input'   => 'number',
					'class'        => 'jsn-input-number input-mini',
					'parent_class' => 'combo-item',
					'std'          => '80',
					'append'       => '%',
					'dependency'   => array( 'audio_sources', '=', 'local' ),
					'validate'     => 'number',
					'tooltip' => __( 'Set start volumn for the audio player', IGPBL ),
				),
				array(
					'name'       => __( 'Loop', IGPBL ),
					'id'         => 'audio_local_loop',
					'type'       => 'radio',
					'std'        => 'false',
					'dependency' => array( 'audio_sources', '=', 'local' ),
					'options'    => array(
						'true'  => __( 'Yes', IGPBL ),
						'false' => __( 'No', IGPBL )
					),
					'tooltip' => __( 'Whether to repeat playing or not', IGPBL ),
				),

				// Basic audio parameters
				array(
					'name'    => __( 'Alignment', IGPBL ),
					'id'      => 'audio_alignment',
					'type'    => 'select',
					'class'   => 'input-sm',
					'std'     => 'center',
					'options' => array(
						'0'      => __( 'No Alignment', IGPBL ),
						'left'   => __( 'Left', IGPBL ),
						'right'  => __( 'Right', IGPBL ),
						'center' => __( 'Center', IGPBL )
					),
					'tooltip' => __( 'Setting position: right, left, center, inherit parent style', IGPBL ),
				),
				array(
					'name'            => __( 'Margin', IGPBL ),
					'container_class' => 'combo-group',
					'id'              => 'audio_margin',
					'type'            => 'margin',
					'extended_ids'    => array( 'audio_margin_top', 'audio_margin_right', 'audio_margin_bottom', 'audio_margin_left' ),
						'audio_margin_top'    => array( 'std' => '10' ),
						'audio_margin_bottom' => array( 'std' => '10' ),
                        'tooltip'             => __( 'Set margin size', IGPBL ),
				),
			)
		);
	}

	/**
	 * Generate HTML code from shortcode content.
	 *
	 * @param   array   $atts     Shortcode attributes.
	 * @param   string  $content  Current content.
	 *
	 * @return  string
	 */
	public function element_shortcode_full( $atts = null, $content = null ) {
		if ( $atts['audio_sources'] == 'soundcloud' ) {
			$atts['audio_dimension_width'] = $atts['audio_dimension_width'] ? $atts['audio_dimension_width'] : '100%';
			$arr_params                    = ( shortcode_atts( $this->config['params'], $atts ) );
			if ( empty( $arr_params['audio_source_link'] ) ){
				$html_element = "<p class='jsn-bglabel'>" . __( 'No audio source selected', IGPBL ) . '</p>';
			} else {
				$html_element = $this->generate_sound_cloud( $arr_params );
			}
		} else if ( $atts['audio_sources'] == 'local' ) {
			$atts['audio_local_dimension_width'] = $atts['audio_local_dimension_width'] ? $atts['audio_local_dimension_width'] : '100%';
			$arr_params                          = ( shortcode_atts( $this->config['params'], $atts ) );
			if ( empty( $arr_params['audio_source_local'] ) ){
				$html_element = "<p class='jsn-bglabel'>" . __( 'No audio source selected', IGPBL ) . '</p>';
			} else {
				$html_element = $this->generate_local_files( $arr_params );
			}
		}
		return $this->element_wrapper( $html_element, $arr_params );
	}

	/**
	 * Method to generate HTML code for SoundCloud.
	 *
	 * @param   array  $params  Shortcode parameters.
	 *
	 * @return  string  HTML code.
	 */
	private function generate_sound_cloud( $params ) {
		$random_id = IG_Pb_Utils_Common::random_string();

		// Proceed embed code dimensions
		$_w = $params['audio_dimension_width'];
		$_h = $params['audio_dimension_height'] ? $params['audio_dimension_height'] : '';
		$_w = ' width="' . $_w . '" ';
		$_h = $_h ? ' height="' . $_h . '" ' : '';

		$params['audio_elements'] = explode( '__#__', $params['audio_elements'] );

		// Container style
		$container_class = ( isset($params['audio_container_style'] ) && $params['audio_container_style'] != '0') ? $params['audio_container_style'] . ' ' : '';

		$container_style = $object_style = '';

		if ( $params['audio_alignment'] === 'right' ) {
			$object_style .= 'float:right;';
			$container_class .= 'clearafter pull-right';
		} else if ( $params['audio_alignment'] === 'center' ) {
			$object_style .= 'margin: 0 auto;';
			$container_style .= 'margin: 0 auto;';
		} else if ( $params['audio_alignment'] === 'left' ) {
			$object_style .= 'float:left;';
			$container_class .= 'clearafter pull-left';
		}

		// Genarate Container class
		$container_class = $container_class ? ' class="' . $container_class . '" ' : '';

		$container_style .= ( isset($params['audio_margin_left'] ) && $params['audio_margin_left'] != '') ? 'margin-left:' . $params['audio_margin_left'] . 'px;' : '';
		$container_style .= ( isset($params['audio_margin_top'] ) && $params['audio_margin_top'] != '') ? 'margin-top:' . $params['audio_margin_top'] . 'px;' : '';
		$container_style .= ( isset($params['audio_margin_right'] ) && $params['audio_margin_right'] != '') ? 'margin-right:' . $params['audio_margin_right'] . 'px;' : '';
		$container_style .= ( isset($params['audio_margin_bottom'] ) && $params['audio_margin_bottom'] != '') ? 'margin-bottom:' . $params['audio_margin_bottom'] . 'px;' : '';
		$container_style = $container_style ? ' style=" ' . $container_style . ' " ' : '';

		$embed = '<div ' . $container_class . $container_style . '>';
		$embed .= '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" id="' . $random_id . '" style="' . $object_style . '"';
		$embed .= $_w . $_h;
		$embed .= '>';

		// Generate soundcloud URL with parameters
		$source_url = 'http://player.soundcloud.com/player.swf?url=';
		$source_url .= $params['audio_source_link'];

		$source_url .= $params['audio_color'] ? '&color=' . str_replace( '#', '', $params['audio_color'] ) : '';
		$source_url .= in_array( 'artwork', $params['audio_elements'] ) ? '&show_artwork=true' : '&show_artwork=false';
		$source_url .= in_array( 'download_button', $params['audio_elements'] ) ? '&download=true' : '&download=false';
		$source_url .= in_array( 'share_button', $params['audio_elements'] ) ? '&sharing=true' : '&sharing=false';
		$source_url .= in_array( 'bpm', $params['audio_elements'] ) ? '&show_bpm=true' : '&show_bpm=false';
		$source_url .= in_array( 'play_count', $params['audio_elements'] ) ? '&show_playcount=true' : '&show_playcount=false';
		$source_url .= in_array( 'comments', $params['audio_elements'] ) ? '&show_comments=true' : '&show_comments=false';
		$source_url .= $params['audio_auto_play'] ? '&auto_play=true' : '&auto_play=false';
		$source_url .= $params['audio_start_track'] ? '&start_track=' . ( (int ) $params['audio_start_track'] - 1) : '';

		// Combine HTML
		$embed .= '<param name="movie"
					value="' . $source_url . '">
					</param>';

		$embed .= '<param name="allowscriptaccess" value="always"></param>';

		$embed .= '<embed allowscriptaccess="always"
					' . $_w . $_h . '
					src="' . $source_url . '"
					type="application/x-shockwave-flash"
					name="' . $random_id . '"></embed>';
		$embed .= '</object>';
		$embed .= '</div>';

		return $embed;
	}

	/**
	 * Generate HTML for local audio player.
	 *
	 * @param   array  $params  Shortcode parameters.
	 *
	 * @return  string  HTML code.
	 */
	function generate_local_files( $params ) {
		$random_id            = IG_Pb_Utils_Common::random_string();
		$audio_size           = array();
		$audio_size['width']  = ' width="' . $params['audio_local_dimension_width'] . '" ';
		$audio_size['height'] = ( $params['audio_local_dimension_height'] != '' ) ? ' height="' . $params['audio_local_dimension_height'] . '" ' : '';

		$player_options = '{';
		$player_options .= ( $params['audio_local_start_volume'] != '' ) ? 'startVolume: ' . ( int ) $params['audio_local_start_volume'] / 100 . ',' : '';
		$player_options .= ( $params['audio_local_loop'] != '' ) ? 'loop: ' . $params['audio_local_loop'] . ',' : '';


		if ( ! isset( $params['audio_local_player_image'] ) ) {
			$_player_color = isset( $params['audio_local_player_color'] ) ? '$( ".mejs-mediaelement, .mejs-controls", audio_container ).css( "background", "none repeat scroll 0 0 ' . $params['audio_local_player_color'] . '" );' : '';
		} else {
			$_player_color = isset( $params['audio_local_player_color'] ) ? '$( ".mejs-mediaelement, .mejs-controls", audio_container ).css( "background", "url(\'' . $params['audio_local_player_image'] . '\' ) repeat scroll 0 0 ' . $params['audio_local_player_color'] . '");' : '';
		}

		$_progress_bar_color = isset( $params['audio_local_progress_color'] ) ? '$( ".mejs-time-loaded, .mejs-horizontal-volume-current", audio_container ).css( "background", "none repeat scroll 0 0 ' . $params['audio_local_progress_color'] . '" );' : '';

		$params['audio_local_elements'] = explode( '__#__', $params['audio_local_elements'] );
		$player_elements = '';
		$player_elements .= in_array( 'play_button', $params['audio_local_elements'] ) ? '' : '$( ".mejs-playpause-button", audio_container ).hide();';
		$player_elements .= in_array( 'current_time', $params['audio_local_elements'] ) ? '' : '$( ".mejs-currenttime-container", audio_container ).hide();';
		$player_elements .= in_array( 'time_rail', $params['audio_local_elements'] ) ? '' : '$( ".mejs-time-rail", audio_container ).hide();';
		$player_elements .= in_array( 'track_duration', $params['audio_local_elements'] ) ? '' : '$( ".mejs-duration-container", audio_container ).hide();';
		$player_elements .= in_array( 'volume_button', $params['audio_local_elements'] ) ? '' : '$( ".mejs-volume-button", audio_container ).hide();';
		$player_elements .= in_array( 'volume_slider', $params['audio_local_elements'] ) ? '' : '$( ".mejs-horizontal-volume-slider", audio_container ).hide();';

		$container_class = $container_style = '';
		if ( $params['audio_alignment'] === 'right' ) {
			$player_elements .= 'audio_container.css( "float", "right" )';
			$container_class .= 'clearafter pull-right';
		} else if ( $params['audio_alignment'] === 'center' ) {
			$container_style .= 'margin: 0 auto;';
			$player_elements .= 'audio_container.css( "margin", "0 auto" )';
		} else if ( $params['audio_alignment'] === 'left' ) {
			$player_elements .= 'audio_container.css( "float", "left" )';
			$container_class .= 'clearafter pull-left';
		}
		// Genarate Container class
		$container_class .= ' ig-' . $random_id . ' ' . $container_class;
		$container_class = $container_class ? ' class="' . $container_class . '" ' : '';

		$player_options .= 'success: function( mediaElement, domObject ){

			var audio_container	= $( domObject ).parents( ".mejs-container" );
			' . $player_elements . '
		},';
		$player_options .= 'keyActions:[]}';

		$script = '
		<script type="text/javascript">
			jQuery( document ).ready( function ($ ){
			
				new MediaElementPlayer("#' . $random_id . '",
					' . $player_options . '
				);
			});
		</script>';
		$fixed_css = '';
		if ( isset( $params['audio_local_dimension_height'] ) && $params['audio_local_dimension_height'] != '' ) {
			$fixed_css = "<style type='text/css'>.jsn-bootstrap3 .ig-element-audio .ig-{$random_id} .mejs-container {
	min-height: {$params['audio_local_dimension_height']}px; 
}</style>";
		}
		
		$container_style .= ( isset( $params['audio_margin_left'] ) && $params['audio_margin_left'] != '' ) ? 'margin-left:' . $params['audio_margin_left'] . 'px;' : '';
		$container_style .= ( isset( $params['audio_margin_top'] ) && $params['audio_margin_top'] != '' ) ? 'margin-top:' . $params['audio_margin_top'] . 'px;' : '';
		$container_style .= ( isset( $params['audio_margin_right'] ) && $params['audio_margin_right'] != '' ) ? 'margin-right:' . $params['audio_margin_right'] . 'px;' : '';
		$container_style .= ( isset( $params['audio_margin_bottom'] ) && $params['audio_margin_bottom'] != '' ) ? 'margin-bottom:' . $params['audio_margin_bottom'] . 'px;' : '';
		$container_style .= ( isset( $params['audio_local_dimension_width'] ) && $params['audio_local_dimension_width'] != '' ) ? 'width:' . $params['audio_local_dimension_width'] . 'px' : '';
		$container_style = $container_style ? ' style=" ' . $container_style . ' " ' : '';

		// Define the media type
		$src = str_replace( ' ', '+', $params['audio_source_local'] );
		$source = '<source type="%s" src="%s" />';
		$type = wp_check_filetype( $src );
		$source = sprintf( $source, $type['type'], esc_url( $src ) );

		return $fixed_css . $script . '<div ' . $container_class . $container_style . '>
								<audio controls="controls" preload="none" ' . $audio_size['width'] . $audio_size['height'] . ' id="' . $random_id . '" src="' . $src . '" >
									' . $source . '
								</audio>
							</div>';
	}

	/**
	 * Enqueue custom asset for front-end.
	 *
	 * @return  void
	 */
	public function custom_assets_frontend() {
		parent::custom_assets_frontend();
		if ( file_exists( ABSPATH . 'wp-includes/js/mediaelement/wp-mediaelement.js' ) ) {
			wp_enqueue_style( 'mediaelement' );
			// re- register mediaelement player js to avoid conflict in admin
			if ( is_admin() ) {
				if ( file_exists( ABSPATH . 'wp-includes/js/mediaelement/mediaelement-and-player.min.js' ) ) {
					IG_Init_Assets::load( 'ig-pb-mediaelement-js', get_site_url() . '/wp-includes/js/mediaelement/mediaelement-and-player.min.js' );
				} else {
					IG_Init_Assets::load( 'ig-pb-mediaelement-js', get_site_url() . '/wp-includes/js/mediaelement/mediaelement-and-player.js' );
				}
			} else {
				wp_enqueue_script( 'mediaelement' );
			}
		}
	}

	/**
	 * Method to check if file existed on SoundCloud.
	 *
	 * @return  void
	 */
	function validate_file() {
		$file_url = $_POST['file_url'];
		$api_url  = 'http://api.soundcloud.com/resolve.format?consumer_key=apigee&url=' . $file_url;
		$html     = wp_remote_get( $api_url );
		if ( isset($html['body']) ) {
			$html = $html['body'];
		} else {
			$html = '';
		}

		if ( $html && strpos( $html, 'error' ) === false ) {
			$data = array();
			$data['type'] = '';
			$content = '';
			$res = simplexml_load_string( $html );

			if ( (string ) $res->kind === 'user' ) {
				$content .= __( 'Username', IGPBL ) . ': <b>' . ( string ) $res->username . '</b><br>';
				$content .= __( 'Country', IGPBL ) . ': <b>' . ( string ) $res->country . '</b><br>';
				$content .= __( 'Full Name', IGPBL ) . ' : <b>' . ( string ) $res->{'full-name'} . '</b><br>';
				$content .= __( 'Description', IGPBL ) . ' : <b>' . ( string ) $res->description . '</b><br>';
				$data['type'] = 'list';
			} else if ( (string ) $res->kind === 'track' ) {
				// Render Duration displaying
				$_duration = $res->duration;
				$_seconds  = round( $_duration / 1000 );
				$_minutes  = round( $_seconds / 60 );
				$_hours    = round( $_seconds / 3600 );
				$_odd_sec  = ( $_seconds - $_minutes * 60 );

				$_duration_str = '';
				if ( $_hours >= 1 && $_hours < 10 ) {
					$_duration_str .= '0' . $_hours . ':';
				} else if ( $_hours >= 10 ) {
					$_duration_str .= $_hours . ':';
				}

				if ( $_minutes >= 1 && $_minutes < 10 ) {
					$_duration_str .= '0' . $_minutes . ':';
				} else if ( $_minutes >= 10 ) {
					$_duration_str .= $_minutes . ':';
				} else {
					$_duration_str .= '00:';
				}

				if ( $_odd_sec >= 1 && $_odd_sec < 10 ) {
					$_duration_str .= '0' . $_odd_sec;
				} else if ( $_minutes >= 10 ) {
					$_duration_str .= $_odd_sec;
				} else {
					$_duration_str .= '00';
				}

				$content .= __( 'Title', IGPBL ) . ': <b>' . ( string ) $res->title . '</b><br>';
				$content .= __( 'Genre', IGPBL ) . ': <b>' . ( string ) $res->genre . '</b><br>';
				$content .= __( 'User', IGPBL ) . ' : <b>' . ( string ) $res->user->username . '</b><br>';
				$content .= __( 'Format', IGPBL ) . ' : <b>' . ( string ) $res->{'original-format'} . '</b><br>';
				$content .= __( 'Duration', IGPBL ) . ' : <b>' . ( string ) $_duration_str . '</b><br>';
			} else if ( (string ) $res->kind === 'playlist' ) {
				$_duration = $res->duration;
				$_seconds = round( $_duration / 1000 );
				$_minutes = round( $_seconds / 60 );
				$_hours = round( $_seconds / 3600 );
				$_odd_sec = ( $_seconds - $_minutes * 60 );

				$_duration_str = '';
				if ( $_hours >= 1 && $_hours < 10 ) {
					$_duration_str .= '0' . $_hours . ':';
				} else if ( $_hours >= 10 ) {
					$_duration_str .= $_hours . ':';
				}

				if ( $_minutes >= 1 && $_minutes < 10 ) {
					$_duration_str .= '0' . $_minutes . ':';
				} else if ( $_minutes >= 10 ) {
					$_duration_str .= $_minutes . ':';
				} else {
					$_duration_str .= '00:';
				}

				if ( $_odd_sec >= 1 && $_odd_sec < 10 ) {
					$_duration_str .= '0' . $_odd_sec;
				} else if ( $_minutes >= 10 ) {
					$_duration_str .= $_odd_sec;
				} else {
					$_duration_str .= '00';
				}

				$content .= __( 'Title', IGPBL ) . ': <b>' . ( string ) $res->title . '</b><br>';
				$content .= __( 'Username', IGPBL ) . ' : <b>' . ( string ) $res->user->username . '</b><br>';
				$content .= __( 'Duration', IGPBL ) . ' : <b>' . ( string ) $_duration_str . '</b><br>';

				$res->description = wp_trim_words( (string ) $res->description, 20 );
				$content          .= __( 'Description', IGPBL ) . ' : <b>' . $res->description . '</b><br>';
				$data['type']     = 'list';
			}

			$data['content'] = $content;
			exit( json_encode( $data ) );
		}
		exit( 'false' );
	}
}

endif;
