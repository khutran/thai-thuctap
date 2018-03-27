<?php
/**
 * @version    $Id$
 * @package    IG PageBuilder
 * @author     InnoGears Team <support@www.innogears.com>
 * @copyright  Copyright (C) 2012 www.innogears.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.www.innogears.com
 * Technical Support:  Feedback - http://www.www.innogears.com
 */

/**
 * @todo : Related shortcode functions
 */

if ( ! class_exists( 'IG_Pb_Helper_Shortcode' ) ) {

	class IG_Pb_Helper_Shortcode {

		static $pattern = '';
		static $group_shortcodes = array( 'group', 'group_table', 'table' );
		static $item_html_template = array(
			'icon' => "<i class='_IG_STD_'></i>",
		);
		static $notice = array();

		/**
		 * Get list of (element + layout) shortcodes from shortcode folders
		 * @return array
		 */
		public static function ig_pb_shortcode_tags() {

			// Do action to register addon providers
			do_action( 'ig_pb_addon' );

			// Get list of providers
			global $Ig_Sc_Providers;
			$Ig_Sc_Providers = apply_filters(
				'ig_pb_provider',
				self::register_provider()
			);

			// compatibility check
			self::compatibility_check();

			// show notice
			add_action( 'admin_notices', array( __CLASS__, 'show_notice' ), 100 );

			// Save providers info to transient, use for unistall
			set_transient( '_ig_pb_providers', serialize( $Ig_Sc_Providers ) );

			// Get list of shortcode directories
			$sc_path = self::shortcode_dirs();

			foreach ( $sc_path as $path ) {
				self::autoload_shortcodes( $path );

				// List files and directories in shortcodes directory
				$excludes = array( '.', '..' );
				$dir_entries = scandir( $path );
				foreach ( $dir_entries as $entry ) {

					// Add folder of each shortcode to autoload list
					if ( is_dir( $path . '/' . $entry ) && ! in_array( $entry, $excludes ) ) {
						IG_Loader::register( $path . '/' . $entry , 'IG_' );
					}
				}
			}

			// Get list of shortcodes
			$shortcodes = self::shortcodes_list( $sc_path );

			return $shortcodes;
		}

		/**
		 * Check compatibility of Addons vs Core
		 *
		 * @global type $Ig_Sc_Providers
		 */
		public static function compatibility_check() {

			global $Ig_Sc_Providers;
			$providers = $Ig_Sc_Providers;

			// get current version of core
			$core_version = IG_Pb_Helper_Functions::get_plugin_info( IG_PB_FILE, 'Version' );

			foreach ( $providers as $dir => $provider ) {
				if ( ! empty ( $provider['file'] ) && ! empty ( $provider['path'] ) ) {

					$addon_file = $provider['file'];

					// get value of core version required
					$core_required = IG_Pb_Addon::core_version_requied_value( $provider, $addon_file );

					if ( $core_required ) {
						// addon plugin name
						$addon_name = IG_Pb_Helper_Functions::get_plugin_info( $provider['file_path'], 'Name' );

						$compatibility = IG_Pb_Addon::compatibility_handle( $core_required, $core_version, $addon_file );

						if ( ! $compatibility ) {
							// remove provider from list
							unset ( $Ig_Sc_Providers[$dir] );

							// show notice
							self::$notice[] = IG_Pb_Addon::show_notice( array( 'addon_name' => $addon_name, 'core_required' => $core_required ), 'core_required' );
						}
					}
				}
			}
		}

		/**
		 * Show notice on top of admin pages
		 */
		public static function show_notice() {
			if ( self::$notice ) {
				echo balanceTags( implode( '', self::$notice ) );
			}
		}

		/**
		 * Autoload shortcodes & sub shortcodes
		 *
		 * @param string $path
		 */
		public static function autoload_shortcodes( $path ) {
			$items   = substr_count( $path, '/item' );
			$postfix = str_repeat( 'Item_', $items );
			// autoload shortcodes
			IG_Loader::register( $path, 'IG_' . $postfix );
		}

		/**
		 * Set information for InnoGears provider
		 *
		 * @return type
		 */
		public static function register_provider() {
			return array(
				plugin_dir_path( IG_PB_FILE ) =>
					array(
						'path'             => IG_PB_PATH,
						'uri'              => IG_PB_URI,
						'name'             => 'InnoGears layout',
						'shortcode_dir'    => array( IG_PB_LAYOUT_PATH ), //array( IG_PB_LAYOUT_PATH, IG_PB_ELEMENT_PATH ),
						'js_shortcode_dir' => array(
							'path' => IG_PB_PATH . 'assets/innogears/js/shortcodes',
							'uri'  => IG_PB_URI . 'assets/innogears/js/shortcodes',
						),
					)
			);
		}

		/**
		 * Get provider name & path of a shortcode directory
		 *
		 * @param type $shortcode_dir
		 *
		 * @return type
		 */
		public static function get_provider( $shortcode_dir ) {
			global $Ig_Sc_Providers;
			$providers = $Ig_Sc_Providers;
			foreach ( $providers as $dir => $provider ) {
				foreach ( (array) $provider['shortcode_dir'] as $dir ) {
					if ( strpos( $shortcode_dir, $dir ) !== false ) {
						return array(
							'name' => $provider['name'],
							'dir'  => $dir,
						);
					}
				}
			}
		}

		/**
		 * Get info of provider of the shortcode
		 *
		 * @global type $Ig_Sc_Providers , $Ig_Sc_By_Providers
		 *
		 * @param type  $shortcode_name
		 * @param type  $shortcode_by_providers
		 *
		 * @return type
		 */
		public static function get_provider_info( $shortcode_name, $info ) {
			global $Ig_Sc_Providers;
			global $Ig_Sc_By_Providers;
			$providers              = $Ig_Sc_Providers;
			$shortcode_by_providers = $Ig_Sc_By_Providers;
			foreach ( $shortcode_by_providers as $provider_dir => $shortcodes ) {
				// find shortcode in what directory
				if ( in_array( $shortcode_name, $shortcodes ) ) {
					// find provider of that directory
					foreach ( $providers as $dir => $provider ) {
						foreach ( (array) $provider['shortcode_dir'] as $dir ) {
							if ( $provider_dir == $dir ) {
								return $Ig_Sc_Providers[$provider['path']][$info];
							}
						}
					}
				}
			}
		}

		/**
		 * Get shortcode directories of providers
		 *
		 * @return type
		 */
		public static function shortcode_dirs() {
			global $Ig_Sc_Providers;
			$providers      = $Ig_Sc_Providers;
			$shortcode_dirs = array();
			foreach ( $providers as $provider ) {
				$shortcode_dirs = array_merge( $shortcode_dirs, (array) $provider['shortcode_dir'] );
			}

			return $shortcode_dirs;
		}

		/**
		 * Get shortcodes in shortcode directories
		 *
		 * @param array $sc_path
		 *
		 * @return array
		 */
		public static function shortcodes_list( $sc_path ) {
			if ( empty( $sc_path ) ) {
				return NULL;
			}
			if ( ! is_array( $sc_path ) ) {
				$sc_path = array( $sc_path );
			}
			// array of shortcodes by shortcode path
			global $Ig_Sc_By_Providers;

			// array of shortcodes by provider name
			global $Ig_Sc_By_Providers_Name;

			// get list of directory by directory level
			$level_dirs = array();
			foreach ( $sc_path as $path ) {
				$level_dirs[substr_count( $path, '/*' )][] = $path;

				// List files and directories in shortcodes directory
				$excludes = array( '.', '..' );
				$dir_entries = scandir( $path );
				foreach ( $dir_entries as $entry ) {

					// path to a shortcode directory
					$shortcode_path = $path . '/' . $entry;

					if ( is_dir( $shortcode_path ) && ! in_array( $entry, $excludes ) ) {

						// Add path to shortcode folder to 1st level array
						$level_dirs[1][] = $shortcode_path;

						// check if exist /item folder (contains sub shortcodes), add it to 2rd level array
						if ( is_dir( $shortcode_path . '/item' ) ) {
							$level_dirs[2][] = $shortcode_path . '/item';
						}
					}
				}
			}

			// store all shortcodes
			$shortcodes = array();

			// traverse over array of path to get shortcode information
			foreach ( $level_dirs as $level => $dirs ) {
				foreach ( $dirs as $dir ) {
					// provider info
					$parent_path = str_replace( '/item', '', $dir );
					$provider    = self::get_provider( $parent_path );
					// shortcode info
					$type       = ( $dir == IG_PB_LAYOUT_PATH ) ? 'layout' : 'element';
					$this_level = ( intval( $level ) > 0 ) ? ( intval( $level ) - 1 ) : intval( $level );
					$append     = str_repeat( 'item_', $this_level );

					// get file .php from directory of each shortcode

                    if ( is_array(glob( $dir . '/*.php' )) ) {
                     if ( count( (array) glob( $dir . '/*.php' ) ) > 0 ) {
                            foreach ( glob( $dir . '/*.php' ) as $file ) {
                                // Skip including main initialization file
                                if ( 'main.php' != substr( $file, - 8 ) ) {
                                    $p                           = pathinfo( $file );
                                    $element                     = str_replace( '-', '_', $p['filename'] );
                                    $shortcode_name              = 'ig_' . $append . $element;
                                    $shortcodes[$shortcode_name] = array( 'type' => $type, 'provider' => $provider );

                                    $Ig_Sc_By_Providers[$provider['dir']][]       = $shortcode_name;
                                    $Ig_Sc_By_Providers_Name[$provider['name']][] = $shortcode_name;
                                }
                            }
                        }
                    }
				}
			}

			return $shortcodes;
		}

		/**
		 * Extract shortcode params from string
		 * Ex: [param-tag=h3&param-text=Your+heading+text&param-font=custom]
		 *
		 * @param type $param_str
		 *
		 * @return array
		 */
		static function extract_params( $param_str, $str_shortcode = '' ) {
			$param_str = stripslashes( $param_str );

			// Get shortcode name & parameters
			preg_match_all( '/\[[^\s"]+\s+([A-Za-z0-9_-]+=\"[^"\']*\"\s*)*\s*\]/', $param_str, $rg_sc_params );
			if ( empty( $rg_sc_params[0] ) ) {
				return '';
			}
			$sc_name_params = ! empty( $rg_sc_params[0][0] ) ? $rg_sc_params[0][0] : $rg_sc_params[0];

			$params    = array();
			// get params of shortcode
			preg_match_all( '/[A-Za-z0-9_-]+=\"[^"\']*\"/u', $sc_name_params, $tmp_params, PREG_PATTERN_ORDER );
			foreach ( $tmp_params[0] as $param_value ) {
				$output = array();
				preg_match_all( '/([A-Za-z0-9_-]+)=\"([^"\']*)\"/u', $param_value, $output, PREG_SET_ORDER );
				foreach ( $output as $item ) {
					if ( ! in_array( $item[1], array( 'disabled_el', 'css_suffix' ) ) || ! isset ( $params[$item[1]] ) ) {
						$params[$item[1]] = urldecode( $item[2] );
					}
				}
			}
			$pattern = get_shortcode_regex();
			preg_match_all( '/' . $pattern . '/s', $param_str, $tmp_params, PREG_PATTERN_ORDER );
			$content                      = isset( $tmp_params[5][0] ) ? trim( $tmp_params[5][0] ) : '';
			$content                      = preg_replace( '/rich_content_param-[a-z_]+=/', '', $content );
			$params['_shortcode_content'] = $content;

			return $params;
		}

		/**
		 * Join params to shortcode structure string
		 *
		 * @param array  $params
		 * @param string $shortcode_name
		 * @param string $content string between shortcode
		 *                        open and close tags
		 *
		 * @return string
		 */
		static function join_params( $params, $shortcode_name, $content = '' ) {
			$shortcode_structure = '[' . $shortcode_name;
			if ( is_array( $params ) && count( $params ) ) {
				foreach ( $params as $k => $param ) {
					$shortcode_structure .= ' ' . $k . '="' . $param . '"';
				}
			}
			$shortcode_structure .= ']';
			$shortcode_structure .= $content;
			$shortcode_structure .= '[/' . $shortcode_name . ']';

			return $shortcode_structure;
		}

		/**
		 * Generate options list of shortcode (from $this->items array) OR get value of a option
		 *
		 * @param array       $arr             ($this->items)
		 * @param string|null $paramID         (get std of a option by ID)
		 * @param array       $new_values      (set std for some options ( "pram id" => "new std value" ))
		 * @param bool        $assign_content  (set $option['std'] = $new_values['_shortcode_content'] of option which has role = 'content')
		 * @param bool        $extract_content (get $option['std'] of option which has role = 'content')
		 * @param string      $extract_title   (get $option['std'] of option which has role|role_2 = 'title')
		 *
		 * @return array
		 */
		static function generate_shortcode_params( &$arr, $paramID = NULL, $new_values = NULL, $assign_content = FALSE, $extract_content = FALSE, $extract_title = '' ) {
			$params = array();
			if ( $arr ) {
				foreach ( $arr as $tab => &$options ) {
					foreach ( $options as &$option ) {
						$type          = isset( $option['type'] ) ? $option['type'] : '';
						$option['std'] = ! isset( $option['std'] ) ? '' : $option['std'];

						// option has role = 'content'
						if ( isset( $option['role'] ) && $option['role'] == 'content' ) {

							// set std of this option
							if ( $assign_content ) {
								if ( ! empty( $new_values ) && isset( $new_values['_shortcode_content'] ) ) {
									$option['std'] = $new_values['_shortcode_content'];
								}
							}

							// get std of this option
							if ( $extract_content ) {
								$params['extract_shortcode_content'][$option['id']] = $option['std'];
							} else {
								// remove option which role = content from shortcode structure ( except option which has another role: title )
								if ( ! ( ( isset( $option['role'] ) && $option['role'] == 'title' ) || ( isset( $option['role_2'] ) && $option['role_2'] == 'title' ) || ( isset( $option['role'] ) && $option['role'] == 'title_prepend' ) ) ) {
									unset( $option );
									continue;
								}
							}
						}
						if ( $type != 'preview' ) {

							// single option : $option['type'] => string
							if ( ! is_array( $type ) ) {

								// if is not parent/nested shortcode
								if ( ! in_array( $type, self::$group_shortcodes ) ) {

									// default content
									if ( empty( $new_values ) ) {
										if ( ! empty( $paramID ) ) {
											if ( $option['id'] == $paramID ) {
												return $option['std'];
											}
										} else {
											if ( isset( $option['id'] ) ) {
												$params[$option['id']] = $option['std'];
											}
										}
									} // there are new values
									else {
										if ( isset( $option['id'] ) && array_key_exists( $option['id'], $new_values ) ) {
											$option['std'] = $new_values[$option['id']];
										}
									}

									// extract title for element like Table
									if ( ! empty( $extract_title ) ) {
										// default std
										if ( strpos( $option['std'], IG_Pb_Utils_Placeholder::get_placeholder( 'index' ) ) !== false ) {
											$option['std']           = $extract_title;
											$params['extract_title'] = $extract_title;
										} else {
											if ( ( isset( $option['role'] ) && $option['role'] == 'title' ) || ( isset( $option['role_2'] ) && $option['role_2'] == 'title' ) ) {
												if ( $option['role'] == 'title' ) {
													$params['extract_title'] = $option['std'];
												} else {
													$params['extract_title'] = IG_Pb_Utils_Common::slice_content( $option['std'] );
												}
											} else {
												if ( ( isset( $option['role'] ) && $option['role'] == 'title_prepend' ) && ! empty( $option['title_prepend_type'] ) && ! empty( $option['std'] ) ) {
													$params['extract_title'] = IG_Pb_Utils_Placeholder::remove_placeholder( self::$item_html_template[$option['title_prepend_type']], 'standard_value', $option['std'] ) . $params['extract_title'];
												}
											}
										}
									}
								} // nested shortcode
								else {
									// default content
									if ( empty( $new_values ) ) {
										foreach ( $option['sub_items'] as &$sub_items ) {
											$sub_items['std'] = ! isset( $sub_items['std'] ) ? '' : $sub_items['std'];

											if ( ! empty( $paramID ) ) {
												if ( $sub_items['id'] == $paramID ) {
													return $sub_items['std'];
												}
											} else {
												$params['sub_items_content'][$option['sub_item_type']][] = $sub_items;
											}
										}
									} // there are new values
									else {
										$count_default = count( $option['sub_items'] );
										$count_real    = isset( $new_values['sub_items_content'][$option['sub_item_type']] ) ? count( $new_values['sub_items_content'][$option['sub_item_type']] ) : 0;
										if ( $count_real > 0 ) {
											// there are new sub items
											if ( $count_default < $count_real ) {
												for ( $index = $count_default; $index < $count_real; $index ++ ) {
													$option['sub_items'][$index] = array( 'std' => '' );
												}
											} // some sub items are deleted
											else {
												if ( $count_default > $count_real ) {
													for ( $index = $count_real; $index < $count_default; $index ++ ) {
														unset( $option['sub_items'][$index] );
													}
												}
											}

											// update content for sub items
											array_walk( $option['sub_items'], array( 'IG_Pb_Helper_Functions', 'ig_arr_walk_subsc' ), $new_values['sub_items_content'][$option['sub_item_type']] );
										}
									}
								}
							} // nested options : $option['type'] => Array of options
							else {
								// default content
								if ( empty( $new_values ) ) {
									foreach ( $option['type'] as &$sub_options ) {
										$sub_options['std'] = ! isset( $sub_options['std'] ) ? '' : $sub_options['std'];

										if ( ! empty( $paramID ) ) {
											if ( $sub_options['id'] == $paramID ) {
												return $sub_options['std'];
											}
										} else {
											$params[$sub_options['id']] = $sub_options['std'];
										}
									}
								} // there are new values
								else {
									array_walk( $option['type'], array( 'IG_Pb_Helper_Functions', 'ig_arr_walk' ), $new_values );
								}
							}

							if ( isset( $option['extended_ids'] ) ) {
								foreach ( $option['extended_ids'] as $_id ) {
									$params[$_id] = isset( $option[$_id]['std'] ) ? $option[$_id]['std'] : '';
								}
							}
						}
					}
				}
			}

			return $params;
		}

		/**
		 * Generate shortcode structure from array of params and name of shortcode
		 *
		 * @param type $shortcode_name
		 * @param type $params
		 *
		 * @return type
		 */
		static function generate_shortcode_structure( $shortcode_name, $params, $content = '' ) {
			$shortcode_structure = "[$shortcode_name ";

			$arr            = array();
			$exclude_params = array( 'sub_items_content', 'extract_shortcode_content' );
			foreach ( $params as $key => $value ) {
				if ( ! in_array( $key, $exclude_params ) && $key != '' ) {
					$arr[$key] = $value;
				}
			}

			// get content of param which has: role = content
			if ( ! empty( $params['extract_shortcode_content'] ) ) {
				foreach ( $params['extract_shortcode_content'] as $paramId => $std ) {
					unset( $arr[$paramId] );
					$content = $std;
				}
			}

			foreach ( $arr as $key => $value ) {
				$shortcode_structure .= "$key=\"$value\" ";
			}
			$shortcode_structure .= ']';
			$shortcode_structure .= $content;
			$shortcode_structure .= "[/$shortcode_name]";

			return $shortcode_structure;
		}

		/**
		 * Get Shortcode class from shortcode name
		 *
		 * @param type $shortcode_name
		 *
		 * @return type
		 */
		static function get_shortcode_class( $shortcode_name ) {
			$shortcode_name = str_replace( 'ig_', 'IG_', $shortcode_name );
			$shortcode      = str_replace( '_', ' ', $shortcode_name );
			$class          = ucwords( $shortcode );
			$class          = str_replace( ' ', '_', $class );

			return $class;
		}

		/**
		 * Return shortcode name without 'ig_' prefix
		 *
		 * @param type $ig_shortcode_name
		 *
		 * @return type
		 */
		static function shortcode_name( $ig_shortcode_name ) {
			return str_replace( 'ig_', '', $ig_shortcode_name );
		}

		/**
		 * Removes wordpress autop and invalid nesting of p tags, as well as br tags
		 *
		 * @param string $content html content by the wordpress editor
		 *
		 * @return string $content
		 */
		static function remove_autop( $content ) {
			$shortcode_tags = array();
			$tagregexp      = join( '|', array_map( 'preg_quote', $shortcode_tags ) );

			// opening tag
			$content = preg_replace( "/(<p>)?\[($tagregexp)(\s[^\]]+)?\](<\/p>|<br\s\/>)?/", '[$2$3]', $content );

			// closing tag
			$content = preg_replace( "/(<p>)?\[\/($tagregexp)](<\/p>|<br\s\/>)?/", '[/$2]', $content );

			// find ig shortcode, initialize shortcode class
			if ( is_admin() ) {
				// ig shortcode list
				$tagregexp = self::regex_ig_sc_tag();

				// find ig shortcode
				$regex   = "\\[\\/($tagregexp)\\]";
				preg_match( "/$regex/s", $content, $matches, PREG_OFFSET_CAPTURE );
				if( $matches ) {
					// get shortcode tag
					$matched_sc = $matches[1][0] | '';
					if ( ! empty( $matched_sc ) ) {
						// get shortcode class name
						$sc_class = self::get_shortcode_class( $matched_sc );

						// initialize class
						if ( class_exists( $sc_class ) ) {
							$sc_object = new $sc_class();
						}
					}
				}
			}

			$content = do_shortcode( shortcode_unautop( $content ) );
			$content = preg_replace( '#^<\/p>|^<br\s?\/?>|<p>$|<p>\s*(&nbsp;)?\s*<\/p>#', '', $content );

			return ( $content );
		}

		/**
		 * Generate shortcode pattern ( for Ig shortcodes only )
		 * @global type $shortcode_tags
		 * @return pattern which contains only shortcodes of IG PageBuilder
		 */
		public static function shortcodes_pattern( $tags = '' ) {
			global $Ig_Pb_Shortcodes;
			global $shortcode_tags;
			$shortcode_tags_clone = $shortcode_tags;
			$shortcode_tags       = empty( $tags ) ? ( ! empty ( $Ig_Pb_Shortcodes ) ? $Ig_Pb_Shortcodes : IG_Pb_Helper_Shortcode::ig_pb_shortcode_tags() ) : $tags;
			$pattern              = get_shortcode_regex();
			$shortcode_tags       = $shortcode_tags_clone;

			return "/$pattern/s";
		}

		/**
		 * List of IG shortcode tags, seperating by '|', to use in regular expression
		 *
		 * @global array $Ig_Pb_Shortcodes
		 * @global string $sc_tag
		 * @return string
		 */
		public static function regex_ig_sc_tag( $sc_tag = '' ) {
			if ( empty( $sc_tag ) ) {
				global $Ig_Pb_Shortcodes;
				$ig_shortcode_tags = ! empty ( $Ig_Pb_Shortcodes ) ? $Ig_Pb_Shortcodes : IG_Pb_Helper_Shortcode::ig_pb_shortcode_tags();
				$tagnames          = array_keys( $ig_shortcode_tags );
			} else {
				$tagnames = (array) $sc_tag;
			}
			$tagregexp         = join( '|', array_map( 'preg_quote', $tagnames ) );

			return $tagregexp;
		}

		/**
		 * Regex to get opening tag & parameters of a shortcode
		 *
		 * @param string $tagregexp The shortcode tags list, seperating by '|'
		 * @return string
		 */
		public static function regex_sc_tag_open( $tagregexp ) {
			// replace opening tag
			$regex   = '\\[' // Opening bracket
				. '(\\[?)' // 1: Optional second opening bracket for escaping shortcodes: [[tag]]
				. "($tagregexp)" // 2: Shortcode name
				. '(?![\\w-])' // Not followed by word character or hyphen
				. '(' // 3: Unroll the loop: Inside the opening shortcode tag
				. '[^\\]\\/]*' // Not a closing bracket or forward slash
				. '(?:'
				. '\\/(?!\\])' // A forward slash not followed by a closing bracket
				. '[^\\]\\/]*' // Not a closing bracket or forward slash
				. ')*?'
				. ')'
				. '(?:'
				. '(\\/)' // 4: Self closing tag ...
				. '\\]' // ... and closing bracket
				. '|'
				. '\\]' // Closing bracket
				. ')'
				. '(\\]?)'; // 6: Optional second closing brocket for escaping shortcodes: [[tag]]

			return $regex;
		}

		/**
		 * Remove all Ig shortcodes from content
		 *
		 * @param string $content The content which contain shortcodes
		 * @param string $sc_tag The shortcode tag want to remove
		 *
		 * @return string Content without shortcode tags
		 */
		public static function remove_ig_shortcodes( $content, $sc_tag = '' ) {
			// ig shortcode list
			$tagregexp = self::regex_ig_sc_tag( $sc_tag );

			// ig shortcode regex
			$regex = self::regex_sc_tag_open( $tagregexp );

			$content = preg_replace( "/$regex/s", '<p>', $content );

			// replace closing tag
			$regex   = "\\[\\/($tagregexp)\\]";
			$content = preg_replace( "/$regex/s", '</p>', $content );

			// remove redundant p tag
			$content = preg_replace( '/(<p>)+/', '<p>', $content );
			$content = preg_replace( '/(<\/p>)+/', '</p>', $content );
			$content = preg_replace( '/(<p>\s*<\/p>)+/', '', $content );

			return $content;
		}

		/**
		 * Split string by regular expression, then replace nodes by string ( [wrapper string]node content[/wrapper string] )
		 *
		 * @param type $pattern
		 * @param type $content
		 * @param type $content_flag
		 * @param type $append_
		 *
		 * @return type string
		 */
		private static function wrap_content( $pattern, $content, $content_flag, $append_ ) {
			$nodes      = preg_split( $pattern, $content, - 1, PREG_SPLIT_OFFSET_CAPTURE );
			$idx_change = 0;
			foreach ( $nodes as $node ) {
				$replace   = $node[0];
				$empty_str = self::check_empty_( $content );
				if ( strlen( trim( $replace ) ) && strlen( trim( $empty_str ) ) ) {
					$offset       = intval( $node[1] ) + $idx_change;
					$replace_html = $replace;

					$content     = substr_replace( $content, str_replace( $content_flag, $replace_html, $append_ ), $offset, strlen( $replace ) );
					$idx_change += strlen( $append_ ) - strlen( $content_flag ) - ( strlen( $replace ) - strlen( $replace_html ) );
				}
			}

			return $content;
		}

		/**
		 * Check if string is empty (no real content)
		 *
		 * @param type $content
		 *
		 * @return type
		 */
		public static function check_empty_( $content ) {
			$content = preg_replace( '/<p[^>]*?>/', '', $content );
			$content = preg_replace( '/<\/p>/', '', $content );
			$content = preg_replace( '/["\']/', '', $content );
			$content = str_replace( '&nbsp;', '', $content );

			return $content;
		}

		/**
		 * Rebuild pagebuilder from Shortcode content
		 *
		 * @param type $content
		 * @param type $column : whether this content is wrapped by a column or not
		 * @param type $refine : true only first time call
		 *
		 * @return type IG PageBuilder content for Admin
		 */
		public static function do_shortcode_admin( $content = '', $column = false, $refine = false ) {
			if ( empty( $content ) ) {
				return '';
			}
			// check if Free Shortcode Plugin is not installed
			global $shortcode_tags;
			if ( ! array_key_exists( 'ig_text', $shortcode_tags ) ) {
				return __( 'You have not activated <b>"IG Free Shortcodes"</b> plugin. Please activate it before using PageBuilder.', IGPBL );
			}

			$content = trim( $content );

			$content_flag = 'X';
			if ( $refine ) {
				// remove duplicator wrapper
				$row_start = '\[ig_row';
				$col_start = '\[ig_column';
				$row_end   = '\[\/ig_row\]';
				$col_end   = '\[\/ig_column\]';
				$content   = preg_replace( "/$row_start([^($row_start)|($col_start)]*)$col_start/", '[ig_row][ig_column', $content );
				$content   = preg_replace( "/$col_end([^($row_end)|($col_end)]*)$row_end/", '[/ig_column][/ig_row]', $content );

				// wrap alone shortcode ( added in Classic Editor )
				$pattern = self::shortcodes_pattern( array( 'ig_row' => '', 'ig_column' => '' ) );
				$append_ = "[ig_row][ig_column]{$content_flag}[/ig_column][/ig_row]";
				$content = self::wrap_content( $pattern, $content, $content_flag, $append_ );
			}

			// wrap alone text
			self::$pattern = self::shortcodes_pattern();

			$pattern = self::$pattern;
			$append_ = $column ? "[ig_text]{$content_flag}[/ig_text]" : "[ig_row][ig_column][ig_text]{$content_flag}[/ig_text][/ig_column][/ig_row]";
			$content = self::wrap_content( $pattern, $content, $content_flag, $append_ );

			return preg_replace_callback( self::$pattern, array( 'self', 'do_shortcode_tag' ), $content );
		}

		public static function do_shortcode_tag( $m ) {

			// allow [[foo]] syntax for escaping a tag
			if ( $m[1] == '[' && $m[6] == ']' ) {
				return substr( $m[0], 1, - 1 );
			}

			$tag     = $m[2];
			$content = isset( $m[5] ) ? trim( $m[5] ) : '';

			return call_user_func( array( 'self', 'shortcode_to_pagebuilder' ), $tag, $content, $m[0], $m[3] );
		}

		/**
		 * Return html structure of shortcode in Page Builder area
		 *
		 * @param type $shortcode_name
		 * @param type $attr
		 * @param type $content
		 */
		public static function shortcode_to_pagebuilder( $shortcode_name, $content = '', $shortcode_data = '', $shortcode_params = '' ) {
			$class = IG_Pb_Helper_Shortcode::get_shortcode_class( $shortcode_name );

			if ( class_exists( $class ) ) {
				global $Ig_Pb;
				$elements = $Ig_Pb->get_elements();
				$instance = isset( $elements['element'][strtolower( $class )] ) ? $elements['element'][strtolower( $class )] : null;
				if ( ! is_object( $instance ) ) {
					$instance = new $class();
				}
				$instance->init_element();
				$el_title = '';
				if ( $class != 'IG_Widget' ) {
					// extract param of shortcode ( now for column )
					if ( isset( $instance->config['extract_param'] ) ) {
						parse_str( trim( $shortcode_params ), $output );
						foreach ( $instance->config['extract_param'] as $param ) {
							if ( isset( $output[$param] ) ) {
								$instance->params[$param] = IG_Pb_Utils_Common::remove_quotes( $output[$param] );
							}
						}
					}

					// get content in pagebuilder of shortcode: Element Title must always first option of Content tab
					if ( isset( $instance->items['content'] ) && isset( $instance->items['content'][0] ) ) {
						$title = $instance->items['content'][0];
						if ( $title['role'] == 'title' ) {
							$params   = shortcode_parse_atts( $shortcode_params );
							$el_title = ! empty( $params[$title['id']] ) ? $params[$title['id']] : __( '(Untitled)', IGPBL );
						}
					}
				} else {
					$widget_info                   = IG_Pb_Helper_Shortcode::extract_widget_params( $shortcode_data );
					$el_title                      = ! empty( $widget_info['title'] ) ? $widget_info['title'] : '';
					$params                        = IG_Pb_Helper_Shortcode::extract_params( $shortcode_data );
					$instance->config['shortcode'] = $params['widget_id'];
					$instance->config['el_type']   = 'widget';
				}

				$shortcode_view = $instance->element_in_pgbldr( $content, $shortcode_data, $el_title );

				return $shortcode_view[0];
			}
		}

		/**
		 * Extract sub-shortcode content of a shortcode
		 *
		 * @param type $content
		 * @param type $recursion
		 *
		 * @return type
		 */
		public static function extract_sub_shortcode( $content = '', $recursion = false ) {
			if ( empty( self::$pattern ) ) {
				self::$pattern = self::shortcodes_pattern();
			}
			preg_match_all( self::$pattern, $content, $out );
			if ( $recursion ) {
				return self::extract_sub_shortcode( $out[5][0] );
			}

			// categorize sub shortcodes content
			$sub_sc_tags = array();

			// sub sortcodes content
			$sub_sc_data = $out[0];

			foreach ( $sub_sc_data as $sc_data ) {

				// get shortcode name
				preg_match( '/\[([^\s\]]+)/', $sc_data, $matches );
				if ( $matches ) {
					$sc_class                 = self::get_shortcode_class( $matches[1] );
					$sub_sc_tags[$sc_class][] = $sc_data;
				}
			}

			return $sub_sc_tags;
		}

		/**
		 * Merge Shortcode Content & Sub Shortcode Content
		 *
		 * @param type $shortcode_content
		 * @param type $sub_shortcode_content
		 *
		 * @return type
		 */
		public static function merge_shortcode_content( $shortcode_content, $sub_shortcode_content ) {
			if ( empty( self::$pattern ) ) {
				self::$pattern = self::shortcodes_pattern();
			}
			preg_match_all( self::$pattern, $shortcode_content, $out );

			$merge_shortcode                      = array();
			$merge_shortcode['shortcode_tag']     = "[{$out[2][0]}";
			$merge_shortcode['shortcode_params']  = "{$out[3][0]}]";
			$merge_shortcode['shortcode_content'] = $sub_shortcode_content;
			$merge_shortcode['shortcode_tag_end'] = "[/{$out[2][0]}]";
			$merge_shortcode                      = implode( '', $merge_shortcode );

			return stripslashes( $merge_shortcode );
		}

		/**
		 * Extract setting params of Widget Form
		 *
		 * @param type $params
		 *
		 * @return type
		 */
		public static function extract_widget_params( $params ) {
			$params = urldecode( $params );
			$params = preg_replace( '/\[ig_widget\s+([A-Za-z0-9_-]+=\"[^"\']*\"\s*)*\s*\]/', '', $params );
			// replace: widget-pages[][title]=Pages 1 => title=Pages 1
			$params = preg_replace( '/([a-z-_])+\[\]\[([^\[\]]+)\]/', '$2', $params );
			$params = str_replace( '[/ig_widget]', '', $params );
			parse_str( $params, $instance );

			return $instance;
		}

		/**
		 * Do shortcode & Return final html output for frontend
		 *
		 * @param type $content
		 */
		public static function doshortcode_content( $ig_pagebuilder_content ) {
			// remove placeholder text which was inserted to &lt; and &gt;
			$ig_pagebuilder_content = IG_Pb_Utils_Placeholder::remove_placeholder( $ig_pagebuilder_content, 'wrapper_append', '' );
			$ig_pagebuilder_content = preg_replace_callback( '/\[ig_widget\s+([A-Za-z0-9_-]+=\"[^"\']*\"\s*)*\s*\](.*)\[\/ig_widget\]/Us', array( 'self', 'widget_content' ), $ig_pagebuilder_content );

			$output = do_shortcode( $ig_pagebuilder_content );

			return $output;
		}

		/**
		 * Replace widget shortcode by Widget output
		 *
		 * @param type $widget_shortcode
		 *
		 * @return type
		 */
		public static function widget_content( $widget_shortcode ) {
			$widget_contents = urldecode( $widget_shortcode[0] );
			// get widget class
			$element = IG_Pb_Helper_Shortcode::extract_params( $widget_contents );
			if ( empty( $element['widget_id'] ) ) {
				return '';
			}
			$widget = $element['widget_id'];
			// get widget settings parameters
			$instance = IG_Pb_Helper_Shortcode::extract_widget_params( $widget_contents );
			$args     = array( 'widget_id' => strtolower( $widget ) );
			// fix problem of woocommerce
			global $woocommerce;
			if ( isset ( $woocommerce ) && empty ( $woocommerce->query ) ) {
				$woocommerce->query = new WC_Query();
			}
			// ouput
			ob_start();
			the_widget( $widget, $instance, $args );
			$widget_content = ob_get_clean();

			return $widget_content;
		}

		/**
		 * Render HTML code for shortcode's parameter type
		 * (used in shortcode setting modal)
		 *
		 * @param string $type Type name
		 * @param string $element
		 *
		 * @return string HTML
		 */
		public static function render_parameter( $type, $element = '', $extra_params = null ) {
			$type_string = self::ucname( $type );
			$class       = 'IG_Pb_Helper_Html_' . $type_string;
			if ( class_exists( $class ) ) {
				return call_user_func( array( $class, 'render' ), $element, $extra_params );
			}

			return false;
		}

		/**
		 * Move this function to a common file
		 *
		 * @param string $string
		 *
		 * @return string
		 */
		public static function ucname( $string ) {
			$string = ucwords( strtolower( $string ) );

			foreach ( array( '-', '\'' ) as $delimiter ) {
				if ( strpos( $string, $delimiter ) !== false ) {
					$string = implode( $delimiter, array_map( 'ucfirst', explode( $delimiter, $string ) ) );
				}
			}

			return $string;
		}

		/**
		 * Method to get only Styling attributes from shortcode content
		 *
		 * @param string $shortcode_name
		 * @param string $shorcode_content
		 *
		 * @return array
		 */
		public static function get_styling_atts( $shortcode_name, $shortcode_content ) {
			// Get the preconfigured styling setting params of shortcode
			$shortcode_class        = self::get_shortcode_class( $shortcode_name );
			$shortcode              = new $shortcode_class;
			$default_styling_params = isset( $shortcode->items['styling'] ) ? $shortcode->items['styling'] : array();
			$styling_atts           = array();

			if ( count( $default_styling_params ) ) {
				// Get inputted params array from shortcode content
				$extracted_params = self::extract_params( $shortcode_content );

				foreach ( $default_styling_params as $param ) {
					if ( $param['id'] && isset ( $extracted_params[$param['id']] ) ) {
						$styling_atts[$param['id']] = $extracted_params[$param['id']];
					}

					// Incase the param has more than 1 values
					// then loop all the values.
					if ( is_array( $param['type'] ) ) {
						foreach ( $param['type'] as $sub_param ) {
							if ( $sub_param['id'] && isset ( $extracted_params[$sub_param['id']] ) ) {
								$styling_atts[$sub_param['id']] = $extracted_params[$sub_param['id']];
							}
						}
					}
				}
			}

			return $styling_atts;
		}

	}

}