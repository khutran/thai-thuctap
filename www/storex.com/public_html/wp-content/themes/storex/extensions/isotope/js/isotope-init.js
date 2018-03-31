jQuery(document).ready(function($) {
	"use strict";

    var isotopeContainer = $('[data-isotope=container]');
    var isotopeFilters = $('[data-isotope=filters]');

    isotopeContainer.each(function(){
        var isotopeLayout = $(this).data('isotope-layout').toLowerCase();
        var isotopeElements = $(this).data('isotope-elements');
		var layout='';
        switch(isotopeLayout){
            case 'fitrows'          : layout = 'fitRows'; break;
            case 'masonry'          : layout = 'masonry'; break;
            case 'vertical'         : layout = 'vertical'; break;
			default                 : layout = 'fitRows'; break;
        }
    
        // initialize Isotope after all images have loaded
        var container = $(this).imagesLoaded( function() {
            /* Add isotope special class */
            container.find('.'+isotopeElements).each(function(){
                $(this).addClass('isotope-item');
            });

            /* Init Isotope */
            container.isotope({
                itemSelector : '.isotope-item',
                layoutMode : layout,            
            });
        });
    });

    /* Isotope filters */
    if (isotopeFilters) {
        // store filter for each group
        var filters = {};

        isotopeFilters.on( 'click', '.filter', function() {
            // get all available filters
            var all_filters = {};
            isotopeFilters.each(function(){
                $(this).children().each(function(){
                    filter = $(this).attr('data-filter');
                    if ( ($.inArray(filter, all_filters) == -1) && filter!='' ) all_filters[filter] = 0;
                });
            });
            var updated_counters = {};
            var filtered_elements = {};

            var $this = $(this);
            // get group key
            var buttonGroup = $this.parents('.filters-group');
            var filterGroup = buttonGroup.attr('data-filter-group');
            // set filter for group
            filters[ filterGroup ] = $this.attr('data-filter');
            // combine filters
            var filterValue = '';
            for ( var prop in filters ) {
                filterValue += filters[ prop ];
            }
            // set filter for Isotope
            isotopeContainer.isotope({ filter: filterValue });
            // get filtered elements            
            var filtered = isotopeContainer.data('isotope').filteredItems;
            var filtered_elements = jQuery.map( filtered, function( a ) {
                return a.element;
            });
            // get updated counters
            var updated_counters = all_filters;
             //console.log(updated_counters);
            $.each( updated_counters, function (key, value) {
                //console.log(key+' '+value);
                $.each( filtered_elements, function (i, dom_element) {
                    
                    if( ($(dom_element).filter(key).length)!=0 ) {
                        value++;
                        updated_counters[key] = value;
                        //console.log(key+' '+value);
                    };
                });
            });
            // update counters
            $.each( updated_counters, function (key, value) {
                $('.filter').each(function(){
                    if ( $(this).attr('data-filter') == key ) {
                        $(this).find('.counter').text(value);
                    };
                });
            });
        });

        // change is-checked class on buttons
        $('.filters-group').each( function( i, buttonGroup ) {
            var buttonGroup = $( buttonGroup );
            buttonGroup.on( 'click', '.filter', function() {
                buttonGroup.find('.is-checked').removeClass('is-checked');
                $( this ).addClass('is-checked');
            });
        });

        // Portfolio, Gallery special select trigger
        var select = $('ul.filters-group');

        select.find('[data-filter]').click(function(){
            var selected_selector = select.find('.selected');
             selected_selector.removeClass('selected');
             $(this).addClass('selected');
            var filters =$(this).attr('data-filter');
            
            if(filters!='*')

            isotopeContainer.isotope({ filter: filters });        
 
            else isotopeContainer.isotope({ filter: '*' });
            
            return false;
        });

    };
});

