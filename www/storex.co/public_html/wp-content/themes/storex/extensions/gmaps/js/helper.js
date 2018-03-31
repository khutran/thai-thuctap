jQuery(document).ready(function($){ 
	"use strict";
	var maps = $('[data-gmap]');
	maps.each(function(){
		
		var params = ($(this).data('gmap')).split(/\s*,\s*/);
		var container = $(this);
		var options = {};
		var obj = [];
		$.each(params, function(index, item){
			obj[$.trim(item.split(':')[0])] = $.trim(item.split(':')[1]);
			$.extend(options, obj);
		});
		
		var init = {el:this};
		
		options.lat = (parseFloat(options.lat) ? parseFloat(options.lat) : 41.895465 );
		options.lng = (parseFloat(options.lng) ? parseFloat(options.lng) : 12.482324 );
		options.zoom = (parseInt(options.zoom, 10) ? parseInt(options.zoom, 10) : 5 );
		options.zoomControl = ($.trim(options.zoomControl) == "true" ? true : true);
		options.scrollwheel = ($.trim(options.scrollwheel) == "true" ? true : false);
		options.panControl = ($.trim(options.panControl) == "true" ? true : false);
		options.streetViewControl = ($.trim(options.streetViewControl) == "true" ? true : false);
		options.mapTypeControl = ($.trim(options.mapTypeControl) == "true" ? true : false);
		options.overviewMapControl = ($.trim(options.overviewMapControl) == "true" ? true : false);
		options.saturation = (parseInt(options.saturation, 10) ? parseInt(options.saturation, 10) : 0 );
		options.lightness = (parseInt(options.lightness, 10) ? parseInt(options.lightness, 10) : 0 );
		options.bubbletext = options.bubbletext;
		
		$.extend(init, options);

		console.log(options);
		
        var map = new GMaps(init);
                
        var styles = [
            {
              stylers: [
                { saturation: options.saturation }
              ]
            }, {
                featureType: "road",
                elementType: "geometry",
                stylers: [
                    { lightness: options.lightness },
                    { visibility: "simplified" }
              ]
            }, {
                featureType: "road",
                elementType: "labels",
                stylers: [
                    { visibility: "off" }
              ]
            }
        ];

        var image = '../images/map_marker.png';
        
        (function(){
			if (options.address != undefined){
				GMaps.geocode({
				  address: options.address,
				  callback: function(results, status){
				    if(status=='OK'){
				      var latlng = results[0].geometry.location;
				      map.setCenter(latlng.lat(), latlng.lng());
				      map.drawOverlay({
				        lat: latlng.lat(),
				        lng: latlng.lng(),
				        content: "<div class='map-bubble'>"+options.bubbletext+"</div>",
				      });
				      /*map.addMarker({
		                lat: latlng.lat(),
		                lng: latlng.lng(),
		              });*/
				    }
				  }
				});
			}
		})();

        map.addStyle({
            styledMapName:"Styled Map",
            styles: styles,
            mapTypeId: "map_style"  
        });
        
        map.setStyle("map_style");
				
	});

}); 