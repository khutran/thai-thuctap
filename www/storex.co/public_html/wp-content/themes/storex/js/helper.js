/*
	Content:
	1. Cart hover effect
	2. Star rating update
	3. Shipping-calculator-form
	4. List/Grid Switcher for Shop page
	5. Primary navigation animation
	6. Product dropdown filters animation
*/

/* 1. Cart hover effect (start)*/ 
	jQuery(document).ready(function($){
			$(window).load(function(){
			'use strict';
				var settings = {
				    interval: 100,
				    timeout: 100,
				    over: mousein_triger,
				    out: mouseout_triger
					};
					
				function mousein_triger(){
					var add_height = $(this).find('.widget_shopping_cart_content').outerHeight();
					$(this).addClass('hovered').find('.widget_shopping_cart_content').fadeIn(300, "easeInSine");
				}
				function mouseout_triger() {
					$(this).removeClass('hovered').find('.widget_shopping_cart_content').fadeOut(300, "easeOutSine");
				}

				$('header .widget_shopping_cart').hoverIntent(settings);

		});
	});
/* 1. Cart hover effect (end)*/ 

/* 2. Star rating update (start)*/ 
	jQuery(document).ready(function($){
		$(window).load(function(){
		'use strict';
		$('p.stars span').replaceWith( '<span><a href="#" class="star-5">5</a><a href="#" class="star-4">4</a><a href="#" class="star-3">3</a><a href="#" class="star-2">2</a><a href="#" class="star-1">1</a></span>' );

    });
});
/* 2. Star rating update (end)*/

/* 3. Shipping-calculator-form show(start)*/ 
	jQuery(document).ready(function($){
		$(window).load(function(){ 
		'use strict';
		var ship_cart = $('.shipping-calculator-form');
			if(ship_cart.length > 0){
				ship_cart.show();
			}
		})
	});
/* 3. Shipping-calculator-form show(end)*/ 

/* 4. List/Grid Switcher for Shop page (start)*/ 
	jQuery(document).ready(function($){
		'use strict';
		$('.pt-view-switcher').on( 'click', 'span', function(e) {
			e.preventDefault();
			if ( (e.currentTarget.className == 'pt-grid active') || (e.currentTarget.className == 'pt-list active') ) {
				return false;
			}

			var iso_container = $('[data-isotope=container]');
			var iso_object = $('[data-isotope=container]').data('isotope');

			iso_container.css({'opacity': '0', 'transition':'none'}).before('<div class="switcher-animation-wrapper"></div>');
	
			if ( $(this).hasClass('pt-grid') && $(this).not('.active') ) {
				$('.pt-view-switcher .pt-list').removeClass('active');
				$('.pt-view-switcher .pt-grid').addClass('active');
                iso_container.find('.isotope-item').each(function(){
                    $(this).removeClass('list-view');
                });
                iso_container.imagesLoaded( function() {
					iso_object.layout();
				});
            }

            if ( $(this).hasClass('pt-list') && $(this).not('.active') ) {
                $('.pt-view-switcher .pt-grid').removeClass('active');
                $('.pt-view-switcher .pt-list').addClass('active');
                iso_container.find('.isotope-item').each(function(){
                    $(this).addClass('list-view');
                    $(this).find('.inner-product-content').css({
					"width": 'auto',
					"height": 'auto',
					});
                });
                iso_container.imagesLoaded( function() {
					iso_object.layout();
				});
            }
			 
			 iso_container.isotope( 'on', 'layoutComplete', function() {
                iso_container.css({'opacity':'1', 'transition':'opacity .3s ease-in-out'});
                $(".switcher-animation-wrapper").remove();
            });
        });
	});
/* 4. List/Grid Switcher for Shop page (end)*/ 

/* 5. Primary navigation animation (start)*/
jQuery(document).ready(function($){
    "use strict";
        $('.primary-nav li').has('ul').mouseover(function(){
            $(this).children('ul').css('visibility','visible');
            }).mouseout(function(){
            $(this).children('ul').css('visibility','hidden');
    });
});
/* 5. Primary navigation animation (end)*/

/* 6. Product dropdown filters animation (start)*/
	jQuery(document).ready(function($){
		'use strict';
		/* Product dropdown filters animation */
        var settings = {
            interval: 100,
            timeout: 200,
            over: mousein_triger,
            out: mouseout_triger
        };

        function mousein_triger(){
            $(this).find('.filters-group').css('visibility', 'visible');
            $(this).addClass('hovered');
        }
        function mouseout_triger() {
            $(this).removeClass('hovered');
            $(this).find('.filters-group').delay(300).queue(function() {
                $(this).css('visibility', 'hidden').dequeue();
            });
        }

        $('#filters-sidebar .dropdown-filters').hoverIntent(settings);
			});
/* 6. Product dropdown filters animation (end)*/



/* 7. Cattegory colappse (start)*/ 
	jQuery(document).ready(function($){
			$(window).load(function(){
			if($('ul.collapse-categories li').hasClass('current-cat')){
				
				$('ul.collapse-categories > li.current-cat > a.show-children').removeClass('collapsed');
				$('ul.collapse-categories  li.current-cat > ul.children').removeClass('collapse').addClass('in');
			}
		});
	});
/* 7.  Cattegory colappse(end)*/ 