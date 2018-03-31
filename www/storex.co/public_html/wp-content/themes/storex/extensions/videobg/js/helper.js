jQuery(document).ready(function($){
	
	'use strict';
	
	var vidbgs = $('[data-function="videobg"]');
	
	vidbgs.each(function(){
		var v_mp4 = $(this).data('mp4');
		var v_ogv = $(this).data('ogv');
		var v_webm = $(this).data('webm');
		var v_poster = $(this).data('poster');
		
		$(this).height($(this).height());
		$(this).prepend('<div class="v-overlay"></div>');
		$(this).videoBG({
			mp4: v_mp4,
			ogv: v_ogv,
			webm: v_webm,
			poster: v_poster,
			scale:true,
			zIndex:0
		});
		
	});
	
});












