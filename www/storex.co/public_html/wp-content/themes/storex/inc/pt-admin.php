<?php 
/*  Loading the admin Panel  */
$panel = new PTPanel();
$panel->panelName = 'Storex Theme Settings';

$pt_global = new PanelSectionFactory('pt-global', 'Site Settings', array(3, 1), 'Set global site options in this panel');
$pt_store = new PanelSectionFactory('pt-store', 'Store Settings', array(1, 1), 'Modify sites Store output');
$pt_typography = new PanelSectionFactory('pt-typography', 'Fonts & Colors Settings', array(2, 0), 'Modify Color scheme & fonts for main elements');
$pt_layout = new PanelSectionFactory('pt-layout', 'Layout Settings', array(1, 0), 'Set global layout options for pages in this panel');
$pt_blog = new PanelSectionFactory('pt-blog', 'Blog Settings', array(3, 2), 'Modify sites Blog output');

$panel->addSection($pt_global);
$panel->addSection($pt_layout);
$panel->addSection($pt_typography);
$panel->addSection($pt_blog);
$panel->addSection($pt_store);

/*  Adding Google fonts  */

/*  Site Settings Forms  */
$site_layout_option = OptionFactory::create('site_layout_id',
	'site_layout',
	FieldType::$RADIOBUTTON,
	'pt-global',
	'Select layout for site',
	array(
		'description' => '',
		'required' => false,
		'default' => 'wide',
		'options' => array(
			'wide'  => 'Wide',
			'boxed' => 'Boxed',
		)
), false);

$totop_button_option = OptionFactory::create('to_top_button_id',
	'to_top_button',
	FieldType::$ONOFF,
	'pt-global',
	'Enable "Scroll to Top" button?',
		array(
		'default' => 'off',
		'description' => 'If "ON" appears in bottom right corner of site'
	),
false);

$back_to_button_option = OptionFactory::create('back_to_home_button_id',
	'back_to_home_button',
	FieldType::$ONOFF,
	'pt-global',
	'Disable "Back to Home" button?',
		array(
		'default' => 'on',
		'description' => 'If "ON" "Back to Home" button appears at the right-top corner of your site'
	),
false);

$stycky_menu_option = OptionFactory::create('stycky_menu_id',
	'stycky_menu',
	FieldType::$ONOFF,
	'pt-global',
	'ON/OFF Stycky Menu',
		array(
		'default' => 'on',
		'description' => 'If you want use sticky menu'
	),
false);

$header_top_panel_option = OptionFactory::create('header_top_panel_id',
	'header_top_panel',
	FieldType::$ONOFF,
	'pt-global',
	'Header top panel view switcher',
		array(
		'default' => 'on',
		'description' => 'Switch to "Off" if you don&rsquo;t want to use header top panel'
	),
false);

$top_panel_bg_option = OptionFactory::create('top_panel_bg_id',
	'top_panel_bg',
	FieldType::$COLORPICKER,
	'pt-global',
	'Set background color of header top panel',
	array('required' => false,
		  'description' => 'Default: #3c4a55' ), 
false);

$logo_upload_option = OptionFactory::create('site_logo_id',
	'site_logo',
	FieldType::$MEDIAUPLOAD,
	'pt-global',
	'Choose logo image',
	array('required' => false), 
false);

$header_bg_header_option = OptionFactory::create('header_bg_color_id',
	'header_bg_color',
	FieldType::$COLORPICKER,
	'pt-global',
	'Choose background color for header',
	array('required' => false), 
false);

$logo_position_option = OptionFactory::create('site_logo_position_id',
	'site_logo_position',
	FieldType::$RADIOBUTTON,
	'pt-global',
	'Select logo position',
	array(
		'description' => 'You have to set Logo position in header',
		'required' => false,
		'default' => 'Left',
		'options' => array(
			'left'  => 'Left',
			'right' => 'Right',
			'center' => 'Center'
		)
), false);

$top_panel_info_option = OptionFactory::create('top_panel_info_id', 
	'top_panel_info', 
	FieldType::$TEXTAREA, 
	'pt-global', 
	'Enter info contents', 
	array('description' => 'Info appears at center of headers top panel'), 
false);

$site_breadcrumbs_option = OptionFactory::create('site_breadcrumbs_id',
	'site_breadcrumbs',
	FieldType::$ONOFF,
	'pt-global',
	'Site breadcrumbs switcher',
		array(
		'default' => 'on',
		'description' => 'Switch to "Off" if you don&rsquo;t want to use breadcrumbs'
	),
false);

	/*  Footer Top Settings Output  (start)*/
$site_footer_top_background_option = OptionFactory::create('site_footer_top_background_option_id',
	'site_footer_top_background_option',
	FieldType::$COLORPICKER,
	'pt-global',
	'Set background color of the top area of the footer',
	array('required' => false,
		  'description' => 'Default: #f2f5f8' ), 
false);
	/*  Footer Top Settings Output  (end)*/

	/*  Footer Middle Settings Output  (start)*/
$site_footer_middle_background_option = OptionFactory::create('site_middle_background_option_id',
	'site_middle_background_option',
	FieldType::$COLORPICKER,
	'pt-global',
	'Set background color of the middle area of the footer',
	array('required' => false,
		  'description' => 'Default: #3c4a55' ), 
false);

$bg_img_footer_middle_upload_option = OptionFactory::create('footer_bg_img_id',
	'footer_bg_img',
	FieldType::$MEDIAUPLOAD,
	'pt-global',
	'Choose background image of the middle area of the footer',
	array('required' => false), 
false);

$bg_footer_img_position_option = OptionFactory::create('footer_bg_img_position_id',
	'footer_bg_img_position',
	FieldType::$RADIOBUTTON,
	'pt-global',
	'Select background position of the middle area of the footer',
	array(
		'description' => 'You have to set background image of the middle area of the footer',
		'required' => false,
		'default' => 'Right',
		'options' => array(
			'right' => 'Right',
			'left'  => 'Left'
		)
), false);
	/*  Footer Middle Settings Output  (end)*/

	/*  Footer Bootom Settings Output  (start)*/
$site_footer_bottom_background_option = OptionFactory::create('site_footer_bottom_background_option_id',
	'site_footer_bottom_background_option',
	FieldType::$COLORPICKER,
	'pt-global',
	'Set footer background color of the bottom area of the footer',
	array('required' => false,
		  'description' => 'Default: #353D43' ), 
false);

$site_copyright_option = OptionFactory::create('site_copyright_id', 
	'site_copyright', 
	FieldType::$TEXTAREA, 
	'pt-global', 
	'Enter sites copyright', 
	array('description' => 'Enter copyright (appears at the bottom of site)'), 
false);
	/*  Footer Bootom Settings Output  (end)*/
	
/*  Site Settings Output  */
$global_content = '<div class="wrapper container-fluid"><div class="row-fluid">'
.'<div class="span6">'
.'<h1>Global Theme Options</h1>'
.$stycky_menu_option
.$site_layout_option
.$site_breadcrumbs_option
.$back_to_button_option
.$totop_button_option
.'<h1>Header Options</h1>'
.$header_bg_header_option
.'<h2>Top Panel Options</h2>'
.$header_top_panel_option
.$top_panel_bg_option
.$top_panel_info_option
.'<h2>Logo Options</h2>'
.$logo_upload_option
.$logo_position_option
.'</div>'
.'<div class="span6">'
.'<h1 class="options-block">Footer Options</h1>'
.'<h2>Footer Top Area Options</h2>'
.$site_footer_top_background_option
.'<h2>Footer Middle Area Options</h2>'
.$site_footer_middle_background_option
.$bg_img_footer_middle_upload_option
.$bg_footer_img_position_option
.'<h2>Footer Bottom Area Options</h2>'
.$site_footer_bottom_background_option
.$site_copyright_option
.'</div>'
.'</div></div>';


/*  Typography Settings Forms  */
$main_menu_color_option = OptionFactory::create('main_menu_color_id',
	'main_menu_color',
	FieldType::$COLORPICKER,
	'pt-global',
	'Set text color of Main Menu',
	array('required' => false,
		  'description' => 'Default: #3c4a55' ), 
false);

$main_menu_hover_color_option = OptionFactory::create('main_menu_hover_color_id',
	'main_menu_hover_color',
	FieldType::$COLORPICKER,
	'pt-global',
	'Set text color on hover of Main Menu',
	array('required' => false,
		  'description' => 'Default: #00aeef' ), 
false);

$top_panel_color_option = OptionFactory::create('top_panel_color_text_id',
	'top_panel_color_text',
	FieldType::$COLORPICKER,
	'pt-global',
	'Set text color of header top panel',
	array('required' => false,
		  'description' => 'Default: #c4d0dd' ), 
false);

$top_panel_link_option = OptionFactory::create('top_header_link_color_id',
	'top_panel_link_color',
	FieldType::$COLORPICKER,
	'pt-global',
	'Set color for links of header top panel',
	array('required' => false,
		  'description' => 'Default: #c4d0dd' ), 
false);

$top_panel_link_hover_option = OptionFactory::create('top_header_link_color_hover_id',
	'top_panel_link_color_hover',
	FieldType::$COLORPICKER,
	'pt-global',
	'Set color on hover for links of header top panel',
	array('required' => false,
		  'description' => 'Default: #ffffff' ), 
false);


$main_color_option = OptionFactory::create('main_color_id',
	'main_color',
	FieldType::$COLORPICKER,
	'pt-typography',
	'Set global text color',
	array('required' => false,
		  'description' => 'Default: #000000' ), 
false);

$footer_color_option = OptionFactory::create('footer_color_id',
	'footer_color',
	FieldType::$COLORPICKER,
	'pt-typography',
	'Set footer text color',
	array('required' => false,
		  'description' => 'Default: #c4d0dd' ), 
false);

$footer_font_color_link  = OptionFactory::create('footer_link_color_hover_id',
	'footer_link_color_hover',
	FieldType::$COLORPICKER,
	'pt-typography',
	'Set footer link color on hover',
	array('required' => false,
		  'description' => 'Default: #f2f5f8' ), 
false);

$footer_color_link_hover_option = OptionFactory::create('footer_link_hover_id',
	'footer_link_hover',
	FieldType::$COLORPICKER,
	'pt-typography',
	'Set footer link color on hover',
	array('required' => false,
		  'description' => 'Default: #f2f5f8' ), 
false);

$headings_content_option = OptionFactory::create('headings_content_id',
	'headings_content',
	FieldType::$COLORPICKER,
	'pt-typography',
	'Set color for headings in content zone',
	array('required' => false,
		  'description' => 'Default: #ffffff'), 
false);

$headings_sidebar_option = OptionFactory::create('headings_sidebar_id',
	'headings_sidebar',
	FieldType::$COLORPICKER,
	'pt-typography',
	'Set color for headings in sidebar zone',
	array('required' => false,
		  'description' => 'Default: #000'), 
false);

$headings_footer_option = OptionFactory::create('headings_footer_id',
	'headings_footer',
	FieldType::$COLORPICKER,
	'pt-typography',
	'Set color for headings in footer zone',
	array('required' => false,
		  'description' => 'Default: #000'), 
false);

$link_color_option = OptionFactory::create('link_color_id',
	'link_color',
	FieldType::$COLORPICKER,
	'pt-typography',
	'Set color for links',
	array('required' => false,
		'description' => 'Default: #454544'), 
false);

$link_color_hover_option = OptionFactory::create('link_color_hover_id',
	'link_color_hover',
	FieldType::$COLORPICKER,
	'pt-typography',
	'Set link color on hover',
	array('required' => false,
		'description' => 'Default: #7790B1'), 
false);

$button_color_option = OptionFactory::create('button_color_id',
	'button_color',
	FieldType::$COLORPICKER,
	'pt-typography',
	'Set bg color for buttons',
	array('required' => false,
		'description' => 'Default: #454544'), 
false);

$button_color_hover_option = OptionFactory::create('button_color_hover_id',
	'button_color_hover',
	FieldType::$COLORPICKER,
	'pt-typography',
	'Set button bg color on hover',
	array('required' => false,
		'description' => 'Default: #849EC1'), 
false);

$button_color_text_option = OptionFactory::create('button_color_text_id',
	'button_color_text',
	FieldType::$COLORPICKER,
	'pt-typography',
	'Set button text color',
	array('required' => false,
		'description' => 'Default: #FFF'), 
false);

$logo_color_option = OptionFactory::create('logo_color_id',
	'logo_color',
	FieldType::$COLORPICKER,
	'pt-typography',
	'Set logo color',
	array('required' => false,
		  'description' => 'Use this option if you have text logo. Default: #000' ), 

false);

$site_custom_colors_option = OptionFactory::create('site_custom_colors_id',
	'site_custom_colors',
	FieldType::$ONOFF,
	'pt-typography',
	'Enable custom colors and fonts?',
		array(
		'default' => 'off',
		'description' => ''
	),
false);
/*  Typography Settings Output  */
$typography_content = '<div class="wrapper container-fluid"><div class="row-fluid">'
.'<div class="span6">'
.'<h1>Advanced Color Options</h1>'
.$site_custom_colors_option
.'<h2>Menu Font Options</h2>'
.$main_menu_color_option
.$main_menu_hover_color_option
.'<h2>Global Font Options</h2>'
.$main_color_option
.$top_panel_color_option
.$footer_color_option
.'<h2>Headings Font Options</h2>'
.$headings_content_option
.$headings_sidebar_option
.$headings_footer_option
.'</div>'
.'<div class="span6">'
.'<h1>Links and Buttons</h1>'
.$top_panel_link_option
.$top_panel_link_hover_option
.$link_color_option
.$link_color_hover_option
.$button_color_option
.$button_color_hover_option
.$button_color_text_option
.$footer_font_color_link
.$footer_color_link_hover_option
.'</div>'
.'</div></div>';


/*  Layout Settings Forms  */
$frontpage_layout_option = OptionFactory::create('home_layout_id',
	'front_layout',
	FieldType::$COLLECTION,
	'pt-layout',
	'Set Front page layout',
	array(
		'required' => false,
		'description' => 'Specify the location of sidebars about the content on the front page',
		'default' => 'two-col-right',
		'options'   => array(
			array('value' => 'one-col', 'label' => '1 Column (no sidebars)', 'icon' => get_template_directory_uri().'/assets/one-col.png'),
			array('value' => 'two-col-left', 'label' => '2 Columns, sidebar on left', 'icon' => get_template_directory_uri().'/assets/two-col-left.png'),
			array('value' => 'two-col-right', 'label' => '2 Columns, sidebar on right', 'icon' => get_template_directory_uri().'/assets/two-col-right.png') )
), false);

$page_layout_option = OptionFactory::create('page_layout_id',
	'page_layout',
	FieldType::$COLLECTION,
	'pt-layout',
	'Set global layout for Pages',
	array(
		'required' => false,
		'description' => 'Specify the location of sidebars about the content on the Pages of your site',
		'default' => 'two-col-right',
		'options'   => array(
			array('value' => 'one-col', 'label' => '1 Column (no sidebars)', 'icon' => get_template_directory_uri().'/assets/one-col.png'),
			array('value' => 'two-col-left', 'label' => '2 Columns, sidebar on left', 'icon' => get_template_directory_uri().'/assets/two-col-left.png'),
			array('value' => 'two-col-right', 'label' => '2 Columns, sidebar on right', 'icon' => get_template_directory_uri().'/assets/two-col-right.png') )
), false);

$blog_layout_option = OptionFactory::create('blog_layout_id',
	'blog_layout',
	FieldType::$COLLECTION,
	'pt-layout',
	'Set Blog page layout',
	array(
		'required' => false,
		'description' => 'Specify the location of sidebars about the content on the Blog page',
		'default' => 'two-col-right',
		'options'   => array(
			array('value' => 'one-col', 'label' => '1 Column (no sidebars)', 'icon' => get_template_directory_uri().'/assets/one-col.png'),
			array('value' => 'two-col-left', 'label' => '2 Columns, sidebar on left', 'icon' => get_template_directory_uri().'/assets/two-col-left.png'),
			array('value' => 'two-col-right', 'label' => '2 Columns, sidebar on right', 'icon' => get_template_directory_uri().'/assets/two-col-right.png') )
), false);

$single_layout_option = OptionFactory::create('single_layout_id',
	'single_layout',
	FieldType::$COLLECTION,
	'pt-layout',
	'Set Single post view layout',
	array(
		'required' => false,
		'description' => 'Specify the location of sidebars about the content on the single posts',
		'default' => 'two-col-right',
		'options'   => array(
			array('value' => 'one-col', 'label' => '1 Column (no sidebars)', 'icon' => get_template_directory_uri().'/assets/one-col.png'),
			array('value' => 'two-col-left', 'label' => '2 Columns, sidebar on left', 'icon' => get_template_directory_uri().'/assets/two-col-left.png'),
			array('value' => 'two-col-right', 'label' => '2 Columns, sidebar on right', 'icon' => get_template_directory_uri().'/assets/two-col-right.png') )
), false);

$shop_layout_option = OptionFactory::create('shop_layout_id',
	'shop_layout',
	FieldType::$COLLECTION,
	'pt-layout',
	'Set Products page (Shop page) layout',
	array(
		'required' => false,
		'default' => 'two-col-right',
		'description' => 'Specify the location of sidebars about the content on the products page',
		'options'   => array(
			array('value' => 'one-col', 'label' => '1 Column (no sidebars)', 'icon' => get_template_directory_uri().'/assets/one-col.png'),
			array('value' => 'two-col-left', 'label' => '2 Columns, sidebar on left', 'icon' => get_template_directory_uri().'/assets/two-col-left.png'),
			array('value' => 'two-col-right', 'label' => '2 Columns, sidebar on right', 'icon' => get_template_directory_uri().'/assets/two-col-right.png') )
), false);

$product_layout_option = OptionFactory::create('product_layout_id',
	'product_layout',
	FieldType::$COLLECTION,
	'pt-layout',
	'Set Single Product pages layout',
	array(
		'required' => false,
		'default' => 'two-col-right',
		'description' => 'Specify the location of sidebars about the content on the single product pages',
		'options'   => array(
			array('value' => 'one-col', 'label' => '1 Column (no sidebars)', 'icon' => get_template_directory_uri().'/assets/one-col.png'),
			array('value' => 'two-col-left', 'label' => '2 Columns, sidebar on left', 'icon' => get_template_directory_uri().'/assets/two-col-left.png'),
			array('value' => 'two-col-right', 'label' => '2 Columns, sidebar on right', 'icon' => get_template_directory_uri().'/assets/two-col-right.png') )
), false);

/*  Layout Settings Output  */
$layout_content = '<div class="wrapper container-fluid"><div class="row-fluid">'
.'<div class="span6">'
.'<h1>Blog Layout Options</h1>'
.$frontpage_layout_option
.$page_layout_option
.$blog_layout_option
.$single_layout_option
//.$taxonomy_layout_option
.'</div>'
.'<div class="span6">'
.'<h1>Store Layout Options</h1>'
.$shop_layout_option
.$product_layout_option
.'</div>'
.'</div></div>';


/*  Blog Settings  */
$blog_layout_option = OptionFactory::create('blog_frontend_layout_id',
	'blog_frontend_layout',
	FieldType::$RADIOBUTTON,
	'pt-blog',
	'Select layout for blog',
	array(
		'description' => '',
		'required' => false,
		'default' => 'list',
		'options' => array(
			'list'  => 'List',
			'grid' => 'Pinterest'
		)
), false);

$blog_grid_columns_option = OptionFactory::create('blog_grid_columns_id',
	'blog_grid_columns',
	FieldType::$RADIOBUTTON,
	'pt-blog',
	'Select number of columns for Blog "grid layout" or "isotope layout"',
	array(
		'description' => '',
		'required' => false,
		'default' => 'cols-3',
		'options' => array(
			'cols-2'  => '2 Columns',
			'cols-3' => '3 Columns',
		)
), false);

$blog_pagination_option = OptionFactory::create('blog_pagination_id',
	'blog_pagination',
	FieldType::$RADIOBUTTON,
	'pt-blog',
	'Select pagination view for blog page',
	array(
		'description' => '',
		'required' => false,
		'default' => 'numeric',
		'options' => array(
			'infinite'  => 'Infinite blog',
			'numeric' => 'Numeric pagination',
		)
), false);

$post_pagination_option = OptionFactory::create('post_pagination_id',
	'post_pagination',
	FieldType::$ONOFF,
	'pt-blog',
	'Single post Prev/Next navigation output switcher',
		array(
		'default' => 'on',
		'description' => 'Switch to "Off" if you don&rsquo;t want to use single post navigation'
	),
false);

$blog_tag_option = OptionFactory::create('blog_tag_id',
	'blog_tag',
	FieldType::$ONOFF,
	'pt-blog',
	'Show post tag',
		array(
		'default' => 'off',
		'description' => 'Switch to "Off" if you don&rsquo;t want to use show post tags'
	),
false);

$post_breadcrumbs_option = OptionFactory::create('post_breadcrumbs_id',
	'post_breadcrumbs',
	FieldType::$ONOFF,
	'pt-blog',
	'Single post breadcrumbs switcher',
		array(
		'default' => 'off',
		'description' => 'Switch to "Off" if you don&rsquo;t want to use breadcrumbs on Single post view'
	),
false);

$blog_share_buttons_option = OptionFactory::create('blog_share_buttons_id',
	'blog_share_buttons',
	FieldType::$ONOFF,
	'pt-blog',
	'Single post share buttons output switcher',
		array(
		'default' => 'off',
		'description' => 'Switch to "Off" if you don&rsquo;t want to use share buttons'
	),
false);

$blog_read_more_text_option = OptionFactory::create('blog_read_more_text_id', 
	'blog_read_more_text', 
	FieldType::$TEXT, 
	'pt-blog', 
	'Enter text for "Read More" button', 
	array('description' => ''), 
false);

$post_show_related_option = OptionFactory::create('post_show_related_id',
	'post_show_related',
	FieldType::$ONOFF,
	'pt-blog',
	'Single post Related Posts output switcher',
		array(
		'default' => 'off',
		'description' => 'Switch to "On" if you don&rsquo;t want to show related posts'
	),
false);

$comments_pagination_option = OptionFactory::create('comments_pagination_id',
	'comments_pagination',
	FieldType::$RADIOBUTTON,
	'pt-blog',
	'Select pagination type for comments',
	array(
		'description' => '',
		'required' => false,
		'default' => 'numeric',
		'options' => array(
			'newold'  => 'Newer/Older pagination',
			'numeric' => 'Numeric pagination',
		)
), false);

$show_gallery_carousel_option = OptionFactory::create('show_gallery_carousel_id',
	'show_gallery_carousel',
	FieldType::$ONOFF,
	'pt-blog',
	'Carousel for Gallery posts on blog page',
		array(
		'default' => 'on',
		'description' => 'Switch to "Off" if you don&rsquo;t want to show carousel for gallery posts'
	),
false);

$gallery_carousel_effect_option = OptionFactory::create('gallery_carousel_effect_id', 
	'gallery_carousel_effect', 
	FieldType::$SELECT, 
	'pt-blog', 
	'Select transition effect for Gallery post Carousel', array(
		'requiered' => false,
		'description' => '',
		'options' => array(
			'fade' => 'Fade',
			'backSlide' => 'Back Slide',
			'goDown' => 'Go Down',
			'fadeUp' => 'Fade Up',
	)
), false);

/*  Blog Settings Output  */
$blog_content = '<div class="wrapper container-fluid"><div class="row-fluid">'
.'<div class="span6">'
.'<h1>Blog Features</h1>'
.$blog_pagination_option
.$blog_read_more_text_option
.$blog_layout_option
.$blog_grid_columns_option
.$blog_tag_option
.'<h2>Post type Gallery Options</h2>'
.$show_gallery_carousel_option
.'</div>'
.'<div class="span6">'
.'<h1>Single Post Features</h1>'
.$post_pagination_option
.$blog_share_buttons_option
.$post_breadcrumbs_option
.$post_show_related_option
.$comments_pagination_option
.'</div>'
.'</div></div>';


/*  Store Settings  */
$show_related_products_option = OptionFactory::create('show_related_products_id',
'show_related_products',
FieldType::$ONOFF,
'pt-store',
'Single product related products output switcher',
array(
'default' => 'on',
'description' => ''
),
false
);

$related_products_qty_option = OptionFactory::create('related_products_qty_id', 
'related_products_qty', 
FieldType::$SELECT, 
'pt-store', 
'Select how many Related Products to show on Single product page', array(
'requiered' => false,
'description' => '',
'options' => array(
			'2' => '2 products',
			'3' => '3 products',
			'4' => '4 products',
	)
), false);

$store_breadcrumbs_option = OptionFactory::create('store_breadcrumbs_id',
		'store_breadcrumbs',
		FieldType::$ONOFF,
		'pt-store',
		'Store Breadcrumbs view switcher',
			array(
			'default' => 'on',
			'description' => 'Switch to "Off" if you don&rsquo;t want to use breadcrumbs on store page'
		),
false);

$products_hover_animation_option_ = OptionFactory::create('products_hover_animation_id_',
	'products_hover_animation_',
	FieldType::$ONOFF,
	'pt-store',
	'Use animation for product on hover',
	array(
			'default' => 'on',
			'description' => 'Switch to "Off" if you don&rsquo;t want to use animation for product on hover',
	),
false);

$use_product_image_gallery = OptionFactory::create('product_image_gallery_id',
		'use_product_image_gallery',
		FieldType::$ONOFF,
		'pt-store',
		'Wether to use sliding gallery',
			array(
			'default' => 'on',
			'description' => 'Switch to "Off" if you don&rsquo;t want to use image gallery on the products page'
		)
);

$store_banner_option = OptionFactory::create('store_banner_id',
		'store_banner',
		FieldType::$ONOFF,
		'pt-store',
		'Store Banner view switcher',
			array(
			'default' => 'on',
			'description' => 'Switch to "Off" if you don&rsquo;t want to use banner on store page'
		),
false);

$store_banner_img_option = OptionFactory::create('store_banner_img_id',
	'store_banner_img',
	FieldType::$MEDIAUPLOAD,
	'pt-store',
	'Upload banner image',
	array('required' => false), 
false);

$store_banner_title_option = OptionFactory::create('store_banner_title_id', 
	'store_banner_title', 
	FieldType::$TEXT, 
	'pt-store', 
	'Enter a title for banner', 
		array('description' => ''), 
false);

$store_banner_description_option = OptionFactory::create('store_banner_description_id', 
	'store_banner_description', 
	FieldType::$TEXT, 
	'pt-store', 
	'Enter a description for banner', 
		array('description' => ''), 
false);

$store_banner_custom_bg_option = OptionFactory::create('store_banner_custom_bg_id',
	'store_banner_custom_bg',
	FieldType::$MEDIAUPLOAD,
	'pt-store',
	'Upload custom background img for store banner',
	array('required' => false), 
false);

$store_banner_img_color_option = OptionFactory::create('store_banner_bg_color_id',
	'store_banner_custom_bg_color',
	FieldType::$COLORPICKER,
	'pt-global',
	'Set background color  for store banner',
	array('required' => false,
		  'description' => 'Default: #00aeef' ), 
false);

$use_pt_images_slider_option = OptionFactory::create('use_pt_images_slider_id',
    'use_pt_images_slider',
    FieldType::$ONOFF,
    'pt-shop',
    'Use custom images output on Single product page',
    array(
        'default' => 'on',
        'description' => 'Turning on custom image carousel on single product page'
    ),
    false
);

$product_slider_type_option = OptionFactory::create('product_slider_type_id',
	'product_slider_type',
	FieldType::$RADIOBUTTON,
	'pt-store',
	'Choose slider type for images on Single product page',
	array(
		'required' => false,
		'default' => 'simple-slider',
		'options' => array(
			'simple-slider'  => 'Slider',
			'slider-with-popup' => 'Slider with pop-up gallery',
			'slider-with-thumbs'  => 'Slider with thumbnails',
		)
), false);

$store_per_page_option = OptionFactory::create('store_per_page_id', 
	'store_per_page', 
	FieldType::$NUMBER, 
	'pt-store', 
	'Enter number of products to show on Store page', 
		array('description' => ''), 
false);
$store_columns_option = OptionFactory::create('store_columns_id',
	'shop_columns',
	FieldType::$RADIOBUTTON,
	'pt-store',
	'Select product quantity per row on Store page',
	array(
		'required' => false,
		'default' => '3',
		'options' => array(
			'3'  => '3 Products',
			'4' => '4 Products',
		)
), false);
$cart_count = OptionFactory::create('cart_count_id',
    'cart_count',
    FieldType::$ONOFF,
    'pt-shop',
    'Show number of products in the cart ON/OFF',
    array(
        'default' => 'off',
        'description' => 'Switch to "ON" if you want to show a a number of products currently in the cart'
    ),
    false
);

$product_slider_effect_option = OptionFactory::create('product_slider_effect_id', 
	'product_slider_effect', 
	FieldType::$SELECT, 
	'pt-global', 
	'Select transition effect for Product Images Carousel', array(
		'requiered' => false,
		'description' => '',
		'options' => array(
			'fade' => 'Fade',
			'backSlide' => 'Back Slide',
			'goDown' => 'Go Down',
			'fadeUp' => 'Fade Up',
	)
), false);

$pt_shares_for_product_option = OptionFactory::create('pt_shares_for_product_id',
    'pt_shares_for_product',
    FieldType::$ONOFF,
    'pt-store',
    'Single product share buttons output switcher',
    array(
        'default' => 'on',
        'description' => ''
    ),
    false
);

$list_grid_switcher_option = OptionFactory::create('list_grid_switcher_id',
	'list_grid_switcher',
	FieldType::$ONOFF,
	'pt-store',
	'List/Grid products switcher',
		array(
		'default' => 'on',
		'description' => 'Switch to "Off" if you don&rsquo;t want to use switcher on products page'
	),
false);

$store_banner_button_bg_option= OptionFactory::create('store_banner_button_bg_color_id',
	'store_banner_button_bg',
	FieldType::$COLORPICKER,
	'pt-global',
	'Set background color  for store banner button',
	array('required' => false,
		  'description' => 'Default: #30BDF2' ), 
false);

$store_banner_button_text_option = OptionFactory::create('store_banner_button_text_id', 
	'store_banner_button_text', 
	FieldType::$TEXT, 
	'pt-store', 
	'Enter banner button text', 
		array('description' => ''), 
false);

$store_banner_url_option = OptionFactory::create('store_banner_url_id', 
	'store_banner_url', 
	FieldType::$TEXT, 
	'pt-store', 
	'Enter an url for banner', 
		array('description' => 'Where you&rsquo;d like the banner to link to. If you leave this field blank the banner will not link anywhere.'), 
false);
$filters_sidebar_option = OptionFactory::create('filters_sidebar_id',
	'filters_sidebar',
	FieldType::$ONOFF,
	'pt-store',
	'Add special sidebar for filters on Store page?',
		array(
		'default' => 'on',
		'description' => 'Switch to "Off" if you don&rsquo;t want to use special sidebar on products page'
	),
false);
/*  Store Settings Output  */
$store_content = '<div class="wrapper container-fluid"><div class="row-fluid">'
.'<div class="span6">'
.'<h1>Shop parameters</h1>'
.$cart_count
.$store_breadcrumbs_option
.$store_per_page_option
.$store_columns_option
.$list_grid_switcher_option
.$products_hover_animation_option_
//.$product_pagination_option
//.$use_product_image_gallery
 .'<h1>Store banner parameters</h1>'
.$store_banner_option
.$store_banner_img_color_option
.$store_banner_custom_bg_option
.$store_banner_img_option
.$store_banner_title_option
.$store_banner_description_option
.$store_banner_button_bg_option
.$store_banner_button_text_option
.$store_banner_url_option
.$filters_sidebar_option
.'<br /><br />'
.'</div>'

.'<div class="span6">'
.'<h1>Single Product template options</h1>'
.$use_pt_images_slider_option
.$product_slider_type_option
.$product_slider_effect_option
.$pt_shares_for_product_option
.$show_related_products_option
.$related_products_qty_option
.'</div>'
.'</div></div>';


$pt_global->setContent($global_content);
$pt_layout->setContent($layout_content);
$pt_typography->setContent($typography_content);
$pt_blog->setContent($blog_content);
$pt_store->setContent($store_content);


/*$pt_header->setContent($header_content);

$pt_blog->setContent($blog_content);
*/
