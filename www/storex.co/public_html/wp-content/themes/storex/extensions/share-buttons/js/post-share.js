jQuery(document).ready(function() {
	jQuery('body').on('click','.pt-post-share',function(event){
	"use strict";
		service = jQuery(this).data("service");
		post_id = jQuery(this).data("postid");
		wrapper = jQuery(this);
		jQuery.ajax({
			type: "post",
			url: ajax_var.url,
			data: "action=pt_post_share_count&nonce="+ajax_var.nonce+"&pt_post_share_count=&post_id="+post_id+"&service="+service,
			success: function(count){
				wrapper.find('.sharecount').empty().html("("+count+")");
			}
		});
	});
});
