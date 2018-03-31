<?php
/*
	Declare supported social media websites

	By default, the Social Icons Widget provides a variety of popular social media websites. Developers can easily add more social media websites by creating a filter in the active theme's functions.php file like such:

		`function add_new_icons($icon_list) {
			$icon_list['Full Website Name'] = 'full-website-id';
		 
			return $icon_list;
		}
		add_filter('social_icon_accounts', 'add_new_icons');`

	The full-website-id should reflect the name of the image you create in each of the icon folder sizes, or in your custom icon directory. It is also used to populate the class field of the icon when the widget displays. The Social Icon Widget looks for .gif, .jpg, .jpeg, and .png in order and returns the first extention it finds.
*/

	global $social_accounts;
	$social_accounts = array(
		'Behance' => 'behance',
		'Dribbble' => 'dribbble',
		'Facebook' => 'facebook',
		'Flickr' => 'flickr',
		'Foursquare' => 'foursquare',
		'GitHub' => 'github',
		'Google+' => 'google-plus',
		'Instagram' => 'instagram',
		'LinkedIn' => 'linkedin',
		'Pinterest' => 'pinterest',
		'RSS Feed' => 'rss',
		'SoundCloud' => 'soundcloud',
		'Tumblr' => 'tumblr',
		'Twitter' => 'twitter',
		'Vimeo' => 'vimeo-square',
		'YouTube' => 'youtube',
	);

	if( has_filter('social_icon_accounts') ) {
		$social_accounts = apply_filters('social_icon_accounts', $social_accounts);
	}
?>