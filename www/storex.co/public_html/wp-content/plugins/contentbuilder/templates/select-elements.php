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
 * @todo : Popover to select Element
 */

global $Ig_Pb, $Ig_Pb_Shortcodes, $Ig_Sc_By_Providers_Name;

// Arrray of element objects
$elements = $Ig_Pb->get_elements();

if ( empty ( $elements ) || empty ( $elements['element'] ) ) {
	_e( 'You have not install Free or Pro Shortcode package.' );
} else {

	$elements_html = array(); // HTML button of a shortcode
	$categories    = array(); // array of shortcode category

	foreach ( $elements['element'] as $element ) {
		// don't show sub-shortcode
		if ( ! isset( $element->config['name'] ) ) {
			continue;
		}

		// get shortcode category

		$category = ''; // category name of this shortcode
		if ( ! empty( $Ig_Pb_Shortcodes[$element->config['shortcode']] ) ) {
			$category_name = $Ig_Pb_Shortcodes[$element->config['shortcode']]['provider']['name'] | '';
			$category      = strtolower( str_replace( ' ', '', $category_name ) );
			if ( ! array_key_exists( $category, $categories ) ) {
				$categories[$category] = $category_name;
			}
		}

		$elements_html[] = $element->element_button( $category );
	}
	?>
	<div id="ig-add-element" class="ig-add-element add-field-dialog" style="display: none;">
		<div class="popover top" style="display: block;">
			<div class="arrow"></div>
			<h3 class="popover-title"><?php _e( 'Select Element', IGPBL ); ?></h3>

			<div class="popover-content">
				<div class="jsn-elementselector">
					<div class="jsn-fieldset-filter">
						<fieldset>
							<div class="pull-left">
								<select id="jsn_filter_element" class="jsn-filter-button input-large">
									<optgroup label="<?php _e( 'Page Elements', IGPBL ) ?>">
	<?php
	// Reorder the Categories of Elements
	$categories_order = array();
	$categories_order['all'] = __( 'All Elements', IGPBL );

	// add Standard Elements as second option
	$standard_el = __( 'Standard Elements', IGPBL );
	$key = array_search( $standard_el, $categories );
	$categories_order[$key] = $standard_el;

	unset( $key );

	// Sort other options by alphabetical order
	asort( $categories );
	$categories_order = array_merge( $categories_order, $categories );

	foreach ( $categories_order as $category => $name ) {
		$selected = ( $name == __( 'Standard Elements', IGPBL ) ) ? 'selected' : '';
		printf( '<option value="%s" %s>%s</option>', esc_attr( $category ), $selected, esc_html( $name ) );
	}
	?>
									</optgroup>
									<option value="shortcode"><?php _e( 'PageBuilder Shortcode', IGPBL ) ?></option>
								</select>
							</div>
							<div class="pull-right jsn-quick-search" role="search">
								<input type="text" class="input form-control jsn-quicksearch-field" placeholder="<?php _e( 'Search', IGPBL ); ?>...">
								<a href="javascript:void(0);" title="<?php _e( 'Clear Search', IGPBL ); ?>" class="jsn-reset-search" id="reset-search-btn"><i class="icon-remove"></i></a>
							</div>
						</fieldset>
					</div>
					<!-- Elements -->
					<ul class="jsn-items-list">
	<?php
	// shortcode elements
	foreach ( $elements_html as $idx => $element ) {
		echo balanceTags( $element );
	}

	// widgets
	global $Ig_Pb_Widgets;
	foreach ( $Ig_Pb_Widgets as $wg_class => $config ) {
		$extra_                    = $config['extra_'];
		$config['edit_using_ajax'] = true;
		echo balanceTags( IG_Pb_Shortcode_Element::el_button( $extra_, $config ) );
	}
	?>
						<!-- Generate text area to add element from raw shortcode -->
						<li class="jsn-item full-width" data-value='raw' data-sort='shortcode'>
							<textarea id="raw_shortcode"></textarea>

							<div class="text-center rawshortcode-container">
								<button class="shortcode-item btn btn-success" data-shortcode="raw" id="rawshortcode-add"><?php _e( 'Add Element', IGPBL ); ?></button>
							</div>
						</li>
					</ul>
					<p style="text-align:center"><?php echo esc_html( __( 'Want to add more elements?', IGPBL ) ); ?>&nbsp;<a target="_blank" href="<?php echo esc_url( admin_url( 'admin.php?page=ig-pb-addons' ) ); ?>"><?php echo esc_html( __( 'Check add-ons.', IGPBL ) ); ?></a>
					</p>
				</div>
			</div>
		</div>
	</div>

	<?php
}