<?php 

class storex_ShareButtons 
{
	public function storex_getAll() {
		$included_socialnets = array(
			'facebook', 
			'twitter', 
			'pinterest', 
			'google', 
			'mail'
		);
		foreach ($included_socialnets as $soc_net) {
			$button_array[] = self::buildSocialButton($soc_net);
		}
		return '<div class="social-links"><span class="share-title">'.esc_html__( 'Share', 'storex' ).':</span>'.implode('', $button_array).'</div>';
	}

	private $new_window = true;
	private $included_socialnets = array('facebook', 'twitter', 'pinterest', 'google', 'mail');
	private $charmap = array(
			'facebook' => 'facebook',
			'twitter' => 'twitter',
			'pinterest' => 'pinterest',
			'google' => 'google-plus',
			'mail' => 'envelope'
	);
	private $titlemap = array(
		'facebook' => 'Share this article on Facebook',
		'twitter' => 'Share this article on Twitter',
		'pinterest' => 'Share an image of this article on Pinterest',
		'google' => 'Share this article on Google+',
		'mail' => 'Email this article to a friend',
	);

	private function buildSocialButton($this_one) {
		$new_window = true;
		$charmap = array(
			'facebook' => 'facebook',
			'twitter' => 'twitter',
			'pinterest' => 'pinterest',
			'google' => 'google-plus',
			'mail' => 'envelope'
		);
		if ($this_one != 'mail' && $this->new_window == true)
			$target =  ' target="_blank"';
		else $target = '';

		return '<div class="pt-post-share '.$this->charmap[$this_one].'" data-service="'.$this_one.'" data-postID="'.get_the_ID().'">
					<a href="'.$this->getSocialUrl($this_one).'"'.$target.'>
						<i class="fa fa-'.$this->charmap[$this_one].'" title="'.$this->titlemap[$this_one].'"></i>
					</a>
					<span class="sharecount">('.$this->getShareCount($this_one).')</span>
				</div>';
	}

	private function getSocialUrl($service) {
		global $post;
		$text = urlencode("A great post: ".$post->post_title);
		$escaped_url = urlencode(get_permalink());
		$image = has_post_thumbnail( $post->ID ) ? wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' ) : null;

		switch ($service) {
			case "twitter" :
				$api_link = 'https://twitter.com/intent/tweet?source=webclient&amp;original_referer='.$escaped_url.'&amp;text='.$text.'&amp;url='.$escaped_url;
				break;

			case "facebook" :
				$api_link = 'https://www.facebook.com/sharer/sharer.php?u='.$escaped_url;
				break;

			case "google" :
				$api_link = 'https://plus.google.com/share?url='.$escaped_url;
				break;

			case "pinterest" :
				if (isset($image)) {
					$api_link = 'http://pinterest.com/pin/create/bookmarklet/?media='.$image[0].'&amp;url='.$escaped_url.'&amp;title='.get_the_title().'&amp;description='.esc_html( $post->post_excerpt );
				}
				else {
					$api_link = "javascript:void((function(){var%20e=document.createElement('script');e.setAttribute('type','text/javascript');e.setAttribute('charset','UTF-8');e.setAttribute('src','http://assets.pinterest.com/js/pinmarklet.js?r='+Math.random()*99999999);document.body.appendChild(e)})());";
				}
				break;

			case "mail" :
				$subject = esc_html__('Check this!', 'storex');
				$body = esc_html__('See more at: ', 'storex');
				$api_link = 'mailto:?subject='.str_replace('&amp;','%26',rawurlencode($subject)).'&body='.str_replace('&amp;','%26',rawurlencode($body).$escaped_url);
				break;
		}

		return $api_link;
	}

	private function getShareCount($service) {
		$count = get_post_meta( get_the_ID(), "_post_".$service."_shares", true ); // get post shares
		if( empty( $count ) ) {
			add_post_meta( get_the_ID(), "_post_".$service."_shares", 0, true ); // create post shares meta if not exist
			$count = 0;
		} 
		return $count;
	}
}

/* Frontend output */
function storex_share_buttons_output() {
	if (!is_feed() && !is_home()) {
		$my_buttons = new storex_ShareButtons;
		$out = $my_buttons->storex_getAll();
	}
	echo $out;
}

/* Enqueue scripts & styles */
function storex_share_scripts() {
	wp_enqueue_script( 'storex_share_post', get_template_directory_uri().'/extensions/share-buttons/js/post-share.js', array('jquery'),'1.0', true);
	wp_localize_script( 'storex_share_post', 'ajax_var', array(
		'url' => admin_url( 'admin-ajax.php' ),
		'nonce' => wp_create_nonce( 'ajax-nonce' )
		)
	);
}
add_action( 'storex_init', 'storex_share_scripts' );

function storex_share_styles() { 
	wp_enqueue_style( 'storex-share-buttons', get_template_directory_uri() .'/extensions/share-buttons/css/pt-share-buttons.css', true);
}
add_action('wp_enqueue_scripts', 'storex_share_styles');

/* Share post counters */
add_action( 'wp_ajax_nopriv_pt_post_share_count', 'storex_post_share_count' );
add_action( 'wp_ajax_pt_post_share_count', 'storex_post_share_count' );

function storex_post_share_count() {
	$nonce = $_POST['nonce'];
    if ( ! wp_verify_nonce( $nonce, 'ajax-nonce' ) )
        die ( 'Nope!' );
	
	if ( isset( $_POST['pt_post_share_count'] ) ) {
	
		$post_id = $_POST['post_id']; // post id
		$service = $_POST['service'];
		$post_like_count = get_post_meta( $post_id, "_post_".$service."_shares", true ); // post like count
		
		if ( function_exists ( 'wp_cache_post_change' ) ) { // invalidate WP Super Cache if exists
			$GLOBALS["super_cache_enabled"]=1;
			wp_cache_post_change( $post_id );
		}
		update_post_meta( $post_id, "_post_".$service."_shares", ++$post_like_count ); // +1 count post meta
		echo $post_like_count; // update count on front end
	}
	
	exit;
}
