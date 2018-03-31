jQuery(document).ready(function($) {
	'use strict';
	
		function center(number,sync2){
			var sync2visible = sync2.data("owlCarousel").owl.visibleItems;
			var num = number;
			var found = false;
			for(var i in sync2visible){
				if(num === sync2visible[i]){
					var found = true;
				}
			}
			 
			if(found===false){
				if(num>sync2visible[sync2visible.length-1]){
					sync2.trigger("owl.goTo", num - sync2visible.length+2)
				}else{
					if(num - 1 === -1){
						num = 0;
					}
					sync2.trigger("owl.goTo", num);
				}
			} else if(num === sync2visible[sync2visible.length-1]){
				sync2.trigger("owl.goTo", sync2visible[1])
			} else if(num === sync2visible[0]){
				sync2.trigger("owl.goTo", num-1)
			}
		}
	
		function afterOWLinit() {
		// adding A to div.owl-page
			$('.owl-controls .owl-page').append('<div class="item-link"></div>');
			var paginatorsLink = $('.owl-controls .item-link');
			/**
			* this.owl.userItems - it's your HTML <div class="item"><img src="http://www.ow...t of us"></div>
			*/
			$.each(this.owl.userItems, function (i) {
				$(paginatorsLink[i])
				// i - counter
				// Give some styles and set background image for pagination item
				.css({
				'background': 'url(' + $(this).find('img').attr('src') + ') center center no-repeat',
				'-webkit-background-size': 'cover',
				'-moz-background-size': 'cover',
				'-o-background-size': 'cover',
				'background-size': 'cover'
				})
				// set Custom Event for pagination item
				.click(function () {
					$(this).trigger('owl.goTo', i);
				});
			});
		} 
		
	/* Owl Object */
	var owlContainer = $('[data-owl=container]');

	$(owlContainer).each(function(){
		/* Variables */
		var owlSlidesQty = $(this).data('owl-slides');
		var owlType = $(this).data('owl-type');
		var owlTransition = $(this).data('owl-transition');
		if ( owlSlidesQty !== 1 ) { 
			owlTransition = false; 
		}
		var owlNavigation = $(this).data('owl-navi');
		var owlPagination = $(this).data('owl-pagi');

		/* Simple Carousel */
		if ( owlType == 'simple' ) {
			/* One Slide Gallery */
			if ( owlSlidesQty == 1 ) {
		        $(this).owlCarousel({
					navigation : owlNavigation,
					navigationText : ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
					pagination : owlPagination,
					slideSpeed : 300,
					paginationSpeed : 400,
					singleItem : true,
					transitionStyle : owlTransition,
		    	});
			};
		};

		/* Carousel with thumbs */
		if ( owlType == 'with-thumbs' ) {
			var sync1 = $(this);
			var sync2 = $(this).parent().find('[data-owl-thumbs="container"]');

			 
			sync2.on("click", ".owl-item", function(e){
				e.preventDefault();
				var number = $(this).data("owlItem");
				sync1.trigger("owl.goTo",number);
			});

			sync1.owlCarousel({
				singleItem : true,
				slideSpeed : 300,
				paginationSpeed : 400,
				navigation : true,
				navigationText : ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
				pagination : false,
				afterAction : function(el){
					var current = this.currentItem;
					sync2
						.find(".owl-item")
						.removeClass("synced")
						.eq(current)
						.addClass("synced")
						if(sync2.data("owlCarousel") !== undefined){
							center(current,sync2)
						}
				},
				responsiveRefreshRate : 200,
				transitionStyle : owlTransition,
			});
			 
			sync2.owlCarousel({
				items : 4,
				pagination : false,
				responsiveRefreshRate : 100,
				afterInit : function(el){
					el.find(".owl-item").eq(0).addClass("synced");
				}
			});
			 
		};

		/* Simple Carousel with icon-pagination */
		if ( owlType == 'with-icons' ) {
		    $(this).owlCarousel({
				navigation : owlNavigation, // Show next and prev buttons
				pagination : owlPagination,
				slideSpeed : 300,
				paginationSpeed : 400,
				singleItem : true,
				transitionStyle : owlTransition,
				afterInit: afterOWLinit
		    });
		};

    });
	
});