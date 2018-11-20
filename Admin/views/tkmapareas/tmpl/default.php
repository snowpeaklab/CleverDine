<?php
/** 
 * @package   	cleverdine
 * @subpackage 	com_cleverdine
 * @author    	Snowpeak Labs // Wood Box Media
 * @copyright 	Copyright (C) 2018 Wood Box Media. All Rights Reserved.
 * @license  	http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @link 		https://woodboxmedia.co.uk
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

$vik = new VikApplication(VersionListener::getID());

?>

<div class="control-group"><div id="googlemap" class="gm-fixed"></div></div>

<div class="vr-map-address-box">

	<input type="text" name="address" value="" id="vraddress" autocomplete="off" size="64" placeholder="<?php echo JText::_('VRTKMAPTESTADDRESS'); ?>"/>

	<div class="vr-map-address-box-response" style="display: none;"></div>

</div>

<script type="text/javascript">

	var MAP_SHAPES = <?php echo json_encode($this->shapes); ?>;
	
	jQuery(document).ready(function(){

		google.maps.event.addDomListener(window, 'load', initializeMap);

		jQuery('#vraddress').on('change', function(){

			evaluateCoordinatesFromAddress(jQuery(this).val());

		});

		var response = jQuery('.vr-map-address-box-response');

		jQuery('#vraddress').on('input propertychange paste', function(){
			if( response.is(':visible') ) {
				response.slideUp();
			}
		});

	});

	var map = null;
	var marker = null;

	function initializeMap() {
		
		map = new google.maps.Map(document.getElementById("googlemap"), {
			zoom: 12,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		});

		// get bounds handler
		var markerBounds = new google.maps.LatLngBounds();

		// Define the LatLng coordinates for the polygon's path.

		var shapes = [];
		var coords = [];

		for( var i = 0; i < MAP_SHAPES.length; i++ ) {

			if( MAP_SHAPES[i].type == 1 ) {

				coords = [];
				for( var j = 0; j < MAP_SHAPES[i].content.length; j++ ) {
					coords.push({
						lat: parseFloat(MAP_SHAPES[i].content[j].latitude),
						lng: parseFloat(MAP_SHAPES[i].content[j].longitude)
					});
				}

				shapes.push(
					new google.maps.Polygon({
						paths: coords,
						strokeColor: MAP_SHAPES[i].attributes.strokecolor,
						strokeOpacity: 0.5,
						strokeWeight: MAP_SHAPES[i].attributes.strokeweight,
						fillColor: MAP_SHAPES[i].attributes.color,
						fillOpacity: 0.20,
						map: map,
						clickable: false
					})
				);

			} else if( MAP_SHAPES[i].type == 2 ) {

				coords = [{
					lat: MAP_SHAPES[i].content.center.latitude, lng: MAP_SHAPES[i].content.center.longitude
				}]

				shapes.push( 
					new google.maps.Circle({
						strokeColor: MAP_SHAPES[i].attributes.strokecolor,
						strokeOpacity: 0.5,
						strokeWeight: MAP_SHAPES[i].attributes.strokeweight,
						fillColor: MAP_SHAPES[i].attributes.color,
						fillOpacity: 0.20,
						map: map,
						center: coords[0],
						radius: MAP_SHAPES[i].content.radius * 1000,
						clickable: false
					})
				);

			}

			for( var k = 0; k < coords.length; k++ ) {
				markerBounds.extend(new google.maps.LatLng(coords[k].lat, coords[k].lng));
			}

		}

		map.fitBounds(markerBounds);
		map.setCenter(markerBounds.getCenter());
		
	}

	function evaluateCoordinatesFromAddress(address) {

		if( marker !== null ) {
			marker.setMap(null);
		}

		if( address.length == 0 ) {
			return;
		}

		var geocoder = new google.maps.Geocoder();

		var coord = null;

		geocoder.geocode({'address': address}, function(results, status) {
			if( status == "OK" ) {
				coord = {
					"lat": results[0].geometry.location.lat(),
					"lng": results[0].geometry.location.lng(),
				};

				var zip = '';
				jQuery.each(results[0].address_components, function(){
					if( this.types[0] == "postal_code") {
						zip = this.short_name;
					}
				});

				marker = new google.maps.Marker({
					position: coord,
				});
				marker.setAnimation(google.maps.Animation.DROP);
				marker.setMap(map);

				map.setCenter(marker.position);

				getLocationDeliveryInfo(coord, zip);
			}
		});
	}

	function getLocationDeliveryInfo(coord, zip) {

		jQuery.noConflict();
	
		var jqxhr = jQuery.ajax({
			type: "POST",
			url: "index.php?option=com_cleverdine&task=get_location_delivery_info&tmpl=component",
			data: { lat: coord.lat, lng: coord.lng, zip: zip }
		}).done(function(resp){
			var obj = jQuery.parseJSON(resp);

			jQuery('.vr-map-address-box-response').html(obj);
			jQuery('.vr-map-address-box-response').slideDown();

		}).fail(function(){
			alert('<?php echo addslashes(JText::_('VRSYSTEMCONNECTIONERR')); ?>');
		});

	}
	
</script>
