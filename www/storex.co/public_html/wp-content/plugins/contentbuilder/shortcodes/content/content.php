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

if ( ! class_exists( 'IG_Content' ) ) :

/**
 * Base class for content processing shortcode.
 *
 * @since  1.0.0
 */
class IG_Content extends IG_Pb_Shortcode_Element {
	public $total_post = null;

	public function __construct() {
		parent::__construct();
	}

	public function element_config() {
		$this->config['shortcode'] = strtolower( __CLASS__ );
	}

	public function element_items() {

	}

	public function element_shortcode_full( $atts = null, $content = null ) {
		return '';
	}

	/**
	 * Query Post Entry
	 *
	 * @global type $post
	 * @param type $ig_cl_source
	 * @param type $arr_ids
	 * @param type $limit
	 * @param type $offset
	 * @param type $orderby
	 * @param type $order
	 * @return type
	 */
	public function querypost( $ig_cl_source, $arr_ids, $limit, $offset, $orderby, $order ) {
		$data = array();

		// filter post type
		$args = array('post_type' => array($ig_cl_source), 'offset' => intval( $offset ) );
		// filter post by ID
		if ( count( $arr_ids ) ) {
			$args['post__in'] = $arr_ids;
		}
		// limit
		// unlimit post at the first running time, to get post count for paging
		if ( ! empty( $limit ) && is_int( $limit ) && $offset ) {
			$args['posts_per_page'] = $limit;
		} else {
			$args['posts_per_page'] = -1;
		}

		// order and orderby
		if ( $orderby AND $order AND $orderby != 'no_order' ) {
			$base_order = array('title', 'parent', 'date', 'name', 'comment_count');
			$args['order'] = $order;
			if ( in_array( $orderby, $base_order ) ) {
				$args['orderby'] = $orderby;
			} else {
				$args['orderby']  = ($orderby == 'price') ? 'meta_value_num' : 'meta_value';
				$args['meta_key'] = '_' . $orderby;
			}
		}
		$the_query = new WP_Query( $args );
		// The Loop
		while ( $the_query->have_posts() ) {
			$the_query->the_post();
			global $post;
			$data[] = $post;
		}
		// return limited post at the first running time
		if ( ! $offset ) {
			$this->total_post = $the_query->post_count;
			if ( ! empty( $limit ) && is_int( $limit ) ) {
				$data = array_slice( $data, 0, $limit );
			}
		}
		wp_reset_postdata();
		return $data;
	}

	/**
	 * Return HTML block for each post (use for Content List)
	 *
	 * @param type $item
	 * @param type $html_element
	 * @param boolean $li_end: whether returned html includes </li> or not
	 * @param string $pre_title: string prepends to title
	 * @param string $title_field: title property of item object; such as post_title, title
	 * @return type
	 */
	public static function output_post( $item, &$html_element, $li_end = true, $pre_title = '', $title_field = 'post_title' ) {
		$link         = get_permalink( $item->ID );
		$title        = $pre_title . (( $item->$title_field ) ? $item->$title_field : __( '(no title)', IGPBL ) );
		$html_element .= "<li><a target='_blank' href='{$link}'>{$title}</a>";
		$html_element .= $li_end ? '</li>' : '';
		return $html_element;
	}

	/**
	 * Return HTML block for each taxonomy (use for Content List)
	 *
	 * @param type $item
	 * @param type $ig_cl_source
	 * @param type $html_element
	 * @param boolean $li_end: whether returned html includes </li> or not
	 * @param string $pre_title: string prepends to title
	 * @return type
	 */
	public static function output_taxonomy( $item, $ig_cl_source, &$html_element, $li_end = true, $pre_title = '' ) {
		$link         = get_term_link( $item, $ig_cl_source );
		$title        = $pre_title . (( $item->name ) ? $item->name : __( '(no title)', IGPBL ) );
		$html_element .= "<li><a target='_blank' href='{$link}'>{$title}</a>";
		$html_element .= $li_end ? '</li>' : '';
		return $html_element;
	}

	/**
	 * Generate HTML structure content
	 *
	 * @global type $wpdb
	 * @param type $configs
	 * @param type $atts
	 * @param type $content
	 * @param type $post_contents: if true, it will return array of post objects. otherwise, it will return content, which is wrapped in li, ul
	 * @return string
	 */
	public function element_shortcode_( $configs, $atts = null, $content = null, $contentclips = false ) {
		global $wpdb;
		$html_element = $item_filter = '';
		$data         = $arr_ids = $source = array();
		if ($atts == null)
			$shortcode_params = shortcode_atts( $configs, $atts );
		else
			$shortcode_params = $atts;
		extract( $shortcode_params );

		if ( intval( $cl_depth_level ) == 0 OR empty( $ig_cl_source ) )
			return '';

		$arr_post_types = array();
		foreach ( IG_Pb_Helper_Type::get_post_types() as $slug => $name ) {
			$arr_post_types[] = $slug;
		}
		$arr_taxonomies = array();
		foreach ( IG_Pb_Helper_Type::get_term_taxonomies() as $slug => $name ) {
			$arr_taxonomies[] = $slug;
		}

		// progress order by
		$orderby = $order = $offset = null;

		if ( isset( $ig_cl_orderby ) AND isset( $ig_cl_order ) AND $ig_cl_orderby != 'no_order' ) {
			$orderby = $ig_cl_orderby;
			$order   = ( $ig_cl_order == 'desc' ) ? 'desc' : 'asc';
		}
		$limit          = isset($total_items) ? intval( $total_items ) : null;
		$items_per_page = isset($items_per_page) ? intval( $items_per_page ) : null;
		if ( isset( $items_per_page ) && isset( $limit ) && $items_per_page < $limit ) {
			$limit = $items_per_page;
			$offset = 0;
		}
		if ( isset( $contentclips_page ) ) {
			$offset = $items_per_page * ($contentclips_page - 1);
			$limit = intval( $total_items ) - $items_per_page * ( $contentclips_page - 1 );
			if ($limit > $items_per_page)
				$limit = $items_per_page;
		}

		$arr_has_parent = IG_Pb_Helper_Type::_get_exclude_taxonomies();
		$is_parent = in_array( $ig_cl_source, $arr_has_parent );
		// source is not a parent item
		if ( ! $is_parent ) {
			// source = single entries
			if ( in_array( $ig_cl_source, $arr_post_types ) ) {
				// with filter value
				if ( ! empty( $item_filter ) ) {
					IG_Pb_Helper_Type::post_by_termid($item_filter, $arr_ids, $source);
				}

				if ( ! empty( $item_filter ) && ! count( $arr_ids ) ) {
					return null;
				}
				// query post entry
				$data = $this->querypost( $ig_cl_source, $arr_ids, $limit, $offset, $orderby, $order );

				if ( count( $data ) ) {
					$html_element = '<ul>';
					foreach ( $data as $i => $item ) {
						$html_element = self::output_post( $item, $html_element );
					}
					$html_element .= '</ul>';
				}
			}
			// source = taxonomies
			elseif ( in_array( $ig_cl_source, $arr_taxonomies ) ) {
				// has filter items
				if ( $item_filter ) {
					$arr_filters = explode( ',', $item_filter );
					// query post entry
					$data        = get_terms( $ig_cl_source, array( 'hide_empty' => false, 'orderby' => 'name' ) );
					if ( count( $data ) ) {
						$html_element = '<ul>';
						if ( $item_filter == 'root' ) {
							foreach ( $data as $i => $item ) {
								$html_element = self::output_taxonomy( $item, $ig_cl_source, $html_element );
							}
						} else {
							foreach ( $data as $i => $item ) {
								if ( in_array( $item->term_id, $arr_filters ) ) {
									$html_element = self::output_taxonomy( $item, $ig_cl_source, $html_element );
								}
							}
						}
						$html_element .= '</ul>';
					}
				}
				// no filter item
				else {
					$args = array( 'hide_empty' => false );
					if ( $orderby AND $order ) {
						$args['orderby'] = $orderby;
						$args['order']   = $order;
					} else {
						$args['orderby'] = 'name';
					}
					if (isset($limit))
						$args['number'] = $limit;
					$data = get_terms( $ig_cl_source, $args );

					if ( count( $data ) ) {
						$html_element = '<ul>';
						foreach ( $data as $i => $item ) {
							$html_element = self::output_taxonomy( $item, $ig_cl_source, $html_element );
						}
						$html_element .= '</ul>';
					}
				}
			}
		}
		// source is a parent item
		else {
			// source = single entries
			if ( in_array( $ig_cl_source, $arr_post_types ) ) {
				$data = $data_post = $this->querypost( $ig_cl_source, $arr_ids, $limit, $offset, $orderby, $order );
				$level = $index = 0;
				if ( count( $data ) ) {
					$html_element = '<ul>';
					if ( $limit ) {
						foreach ( $data as $i => $item ) {
							$html_element = self::output_post( $item, $html_element );
						}
					} else {
						foreach ( $data as $i => $item ) {
							if ( $item->ID == $item_filter OR ( $item_filter == 'root' AND $item->post_parent == 0 ) ) {
								unset($data[$i]);
								if ( ! isset( $data_content ) )
									$data_content = array();
								$data_content[] = $item;
								$html_element   = self::output_post( $item, $html_element, false );
								$index          = '';
								self::_recur_tree( $html_element, $data, $item->ID, $level, $cl_depth_level, '1', '', $index, '', $data_content );
								$html_element   .= '</li>';
							}
						}
					}
					$html_element .= '</ul>';
				}
			}
			// source = taxonomies
			else if ( in_array( $ig_cl_source, $arr_taxonomies ) ) {
				if ( $limit ) {
					$args = array( 'hide_empty' => false );
					if ( $orderby AND $order ) {
						$args['orderby'] = $orderby;
						$args['order']   = $order;
					} else {
						$args['orderby'] = 'name';
					}
					$args['number'] = $limit;
					$data = get_terms( $ig_cl_source, $args );

					$html_element = '<ul>';
					foreach ( $data as $i => $item ) {
						unset( $data[ $i ] );
						$html_element = self::output_taxonomy( $item, $ig_cl_source, $html_element );
					}
					$html_element .= '</ul>';
				} else {
					$data         = get_terms( $ig_cl_source, array( 'hide_empty' => false, 'orderby' => 'name' ) );
					$item_filter  = isset( $item_filter ) ? $item_filter : 'root';
					$level        = 0;
					$html_element = '<ul>';
					foreach ( $data as $i => $item ) {
						if ( $item->term_id == $item_filter OR ( $item_filter == 'root' AND $item->parent == 0 ) ) {
							unset( $data[ $i ] );
							$html_element = self::output_taxonomy( $item, $ig_cl_source, $html_element, false );
							self::_recur_tree( $html_element, $data, $item->term_id, $level, $cl_depth_level, '-1', $ig_cl_source );
							$html_element .= '</li>';
						}
					}
					$html_element .= '</ul>';
				}
			}
			// is Menu item
			else if ( $ig_cl_source == 'nav_menu_item' ) {
				if ( isset( $item_filter ) ) {
					// process for menu case
					$menu_start_from = isset( $ig_cl_menu_start_from ) ? $ig_cl_menu_start_from : '';
					if ( $menu_start_from != 'root' ) {
						$menu_start_from = intval( $menu_start_from );
					} else {
						$menu_start_from = '0';
					}
					$level = 0;
					$data  = wp_get_nav_menu_items( $item_filter, array( 'update_post_term_cache' => false ) );

					$html_element = '<ul>';
					if ( $data ) {
						foreach ( $data as $i => $item ) {
							unset( $data[ $i ] );
							if ( ( $menu_start_from == 0 AND $item->menu_item_parent == $menu_start_from ) OR ( $menu_start_from != 0 AND $item->ID == $menu_start_from ) ) {
								$html_element = self::output_post( $item, $html_element, false, '', 'title' );
								self::_recur_tree( $html_element, $data, $item->ID, $level, $cl_depth_level, '0', '', $index, '' );
								$html_element .= '</li>';
							}
						}
					}

					$html_element .= '</ul>';
				}
			}
		}

		if ( $contentclips )
			return array( 'data' => isset( $data_content ) ? $data_content : $data, 'source' => $source );

		return $html_element;
	}

	/**
	 * recursive tree traversal
	 *
	 * @param string $html_element
	 * @param array $items
	 * @param string $id
	 * @param int $level
	 * @param int $max_lvl
	 * @param bool $is_post_type
	 * @param string $ig_cl_source (optional)
	 * @param int $index (optional)
	 * @param int $limit (optional)
	 * @param array $data_content: array to store selected item
	 *
	 * @return string
	 */
	private static function _recur_tree( &$html_element, &$items, $id, $level, $max_lvl, $is_post_type = '1', $ig_cl_source = '', &$index, $limit = 0, &$data_content = array() ) {
		$level++;
		if ( $is_post_type == '1' ) {
			if ( ! empty( $limit ) ) {
				if ( $index < $limit ) {
					$allow_ul = false;
					foreach ( $items as $j => $item ) {
						if ( $item->post_parent == $id ) {
							$allow_ul = true;
						}
					}
					if ( $allow_ul AND $max_lvl > $level ) {
						$html_element .= '<ul>';
					}
					foreach ( $items as $i => $item ) {
						if ( $item->post_parent == $id AND $max_lvl > $level AND $index < $limit ) {
							unset($items[$i]);
							$data_content[] = $item;
							$html_element   = self::output_post( $item, $html_element, false, str_repeat( '', $level ) );
							$index++;
							self::_recur_tree( $html_element, $items, $item->ID, $level, $max_lvl, '1', '', $index, $limit );
							$html_element   .= '</li>';
						}
					}
					if ( $allow_ul AND $max_lvl > $level ) {
						$html_element .= '</ul>';
					}
				}
			} else {
				$allow_ul = false;
				foreach ( $items as $j => $item ) {
					if ( $item->post_parent == $id ) {
						$allow_ul = true;
					}
				}
				if ( $allow_ul AND $max_lvl > $level ) {
					$html_element .= '<ul>';
				}
				foreach ( $items as $i => $item ) {
					if ( $item->post_parent == $id AND $max_lvl > $level ) {
						unset($items[$i]);
						$data_content[] = $item;
						$html_element = self::output_post( $item, $html_element, false, str_repeat( '', $level ) );
						self::_recur_tree( $html_element, $items, $item->ID, $level, $max_lvl, '1', '', $index, '' );
						$html_element .= '</li>';
					}
				}
				if ( $allow_ul AND $max_lvl > $level ) {
					$html_element .= '</ul>';
				}
			}
		} else if ( $is_post_type == '-1' ) {
			$allow_ul = false;
			foreach ( $items as $j => $item ) {
				if ( $item->parent == $id ) {
					$allow_ul = true;
				}
			}
			if ( $allow_ul AND $max_lvl > $level ) {
				$html_element .= '<ul>';
			}
			foreach ( $items as $i => $item ) {
				if ( $item->parent == $id AND $max_lvl > $level ) {
					unset( $items[ $i ] );
					$html_element = self::output_taxonomy( $item, $ig_cl_source, $html_element, false, str_repeat( '', $level ) );
					self::_recur_tree( $html_element, $items, $item->term_id, $level, $max_lvl, '-1', $ig_cl_source );
					$html_element .= '</li>';
				}
			}
			if ( $allow_ul AND $max_lvl > $level ) {
				$html_element .= '</ul>';
			}
		} else if ( $is_post_type == '0' ) {
			$allow_ul = false;
			foreach ( $items as $j => $item ) {
				if ( $item->menu_item_parent == $id ) {
					$allow_ul = true;
				}
			}
			if ( $allow_ul AND $max_lvl > $level ) {
				$html_element .= '<ul>';
			}
			foreach ( $items as $i => $item ) {
				if ( $item->menu_item_parent == $id AND $max_lvl > $level ) {
					unset($items[$i]);
					$html_element = self::output_post( $item, $html_element, false, str_repeat( '', $level ), 'title' );
					self::_recur_tree( $html_element, $items, $item->ID, $level, $max_lvl, '0', '', $index, '' );
					$html_element .= '</li>';
				}
			}
			if ( $allow_ul AND $max_lvl > $level ) {
				$html_element .= '</ul>';
			}
		}
	}
}

endif;
