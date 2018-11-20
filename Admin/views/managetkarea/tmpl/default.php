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

$sel = null;
$id = -1;
if( !count( $this->row ) ) {
	$sel = array(
		'name' => '', 'type' => 0, 'charge' => 0.0, 'min_cost' => 0.0, 'published' => 1, 'content' => '', 'attributes' => ''
	);
} else {
	$sel = $this->row;
	$id = $sel['id'];
}

$content = json_decode($sel['content']);
$attributes = json_decode($sel['attributes']);

$vik = new VikApplication(VersionListener::getID());

?>

<form name="adminForm" action="index.php" method="post" enctype="multipart/form-data" id="adminForm">

	<div></div>

	<div class="span12">

		<!-- DETAILS FIELDSET -->

		<div class="span6">
			<?php echo $vik->openFieldset(JText::_('VRTKAREAFIELDSET1'), 'form-horizontal'); ?>
				
				<!-- NAME - Text -->
				<?php echo $vik->openControl(JText::_('VRMANAGETKAREA1').'*:'); ?>
					<input type="text" name="name" class="required" value="<?php echo $sel['name']; ?>" size="40"/>
				<?php echo $vik->closeControl(); ?>

				<!-- TYPE - Dropdown -->
				<?php
				$elements = array(
					$vik->initOptionElement('', '', $sel['type'] == 0)
				);
				for( $i = 1; $i <= 3; $i++ ) {
					array_push($elements, $vik->initOptionElement($i, JText::_('VRTKAREATYPE'.$i), $sel['type'] == $i));
				}
				?>
				<?php echo $vik->openControl(JText::_('VRMANAGETKAREA2').'*:'); ?>
					<?php echo $vik->dropdown('type', $elements, 'vr-type-sel', 'required'); ?>
					<?php echo $vik->createPopover(array(
						"title" 	=> JText::_('VRMANAGETKAREA2'),
						"content"	=> JText::_('VRMANAGETKAREA2_HELP'),
					)); ?>
				<?php echo $vik->closeControl(); ?>
				
				<!-- CHARGE - Number -->
				<?php echo $vik->openControl(JText::_('VRMANAGETKAREA4').':'); ?>
					<input type="number" name="charge" value="<?php echo $sel['charge']; ?>" size="4" step="any"/>
					&nbsp;<?php echo cleverdine::getCurrencySymb(true); ?>
					<?php echo $vik->createPopover(array(
						"title" 	=> JText::_('VRMANAGETKAREA4'),
						"content"	=> JText::_('VRMANAGETKAREA4_HELP'),
					)); ?>
				<?php echo $vik->closeControl(); ?>

				<!-- MIN COST - Number -->
				<?php echo $vik->openControl(JText::_('VRMANAGETKAREA18').':'); ?>
					<input type="number" name="min_cost" value="<?php echo $sel['min_cost']; ?>" size="4" step="any"/>
					&nbsp;<?php echo cleverdine::getCurrencySymb(true); ?>
					<?php echo $vik->createPopover(array(
						"title" 	=> JText::_('VRMANAGETKAREA18'),
						"content"	=> JText::_('VRMANAGETKAREA18_HELP'),
					)); ?>
				<?php echo $vik->closeControl(); ?>
				
				<!-- PUBLISHED - Radio Button -->
				<?php
				$elem_yes = $vik->initRadioElement('', JText::_('VRYES'), $sel['published']==1);
				$elem_no = $vik->initRadioElement('', JText::_('VRNO'), $sel['published']==0);
				?>
				<?php echo $vik->openControl(JText::_('VRMANAGETKAREA3').':'); ?>
					<?php echo $vik->radioYesNo('published', $elem_yes, $elem_no, false); ?>
				<?php echo $vik->closeControl(); ?>
				
			
			<?php echo $vik->closeFieldset(); ?>
		</div>

		<!-- ATTRIBUTES FIELDSET -->

		<div class="span6" id="vr-attributes-fieldset" style="<?php echo (($sel['type'] == 1 || $sel['type'] == 2) ? '' : 'display: none'); ?>">
			<?php echo $vik->openFieldset(JText::_('VRTKAREAFIELDSET3'), 'form-horizontal'); ?>

				<!-- COLOR - Text -->
				<?php echo $vik->openControl(JText::_('VRMANAGETKAREA10').':'); ?>
					<input type="text" name="color" id= "vrattrcolor" value="<?php echo (isset($attributes->color) ? $attributes->color : '#FF0000'); ?>" class="vr-attribute-field" readonly/>
					<a href="javascript: void(0);" id="vrcolorpicker" style="margin-left: 10px;">
						<i class="fa fa-eyedropper big"></i>
					</a>
				<?php echo $vik->closeControl(); ?>

				<!-- STROKE COLOR - Text -->
				<?php echo $vik->openControl(JText::_('VRMANAGETKAREA14').':'); ?>
					<input type="text" name="strokecolor" id= "vrattrstrokecolor" value="<?php echo (isset($attributes->strokecolor) ? $attributes->strokecolor : '#FF0000'); ?>" class="vr-attribute-field" readonly/>
					<a href="javascript: void(0);" id="vrstrokecolorpicker" style="margin-left: 10px;">
						<i class="fa fa-eyedropper big"></i>
					</a>
				<?php echo $vik->closeControl(); ?>

				<!-- STROKE WEIGHT - Number -->
				<?php echo $vik->openControl(JText::_('VRMANAGETKAREA15').':'); ?>
					<input type="number" name="strokeweight" id= "vrattrstrokeweight" value="<?php echo (isset($attributes->strokeweight) ? $attributes->strokeweight : 2); ?>" class="vr-attribute-field" min="0" max="10"/>
				<?php echo $vik->closeControl(); ?>

				<!-- DISPLAY SHAPES - Radio Button -->
				<?php
				$elem_yes = $vik->initRadioElement('', JText::_('VRYES'), false, 'onClick="DISPLAY_SHAPES=1;refreshVisibleMap();"');
				$elem_no = $vik->initRadioElement('', JText::_('VRNO'), true, 'onClick="DISPLAY_SHAPES=0;refreshVisibleMap();"');
				?>
				<?php echo $vik->openControl(JText::_('VRMANAGETKAREA12').':'); ?>
					<?php echo $vik->radioYesNo('display_shapes', $elem_yes, $elem_no, false); ?>
					<?php echo $vik->createPopover(array(
						"title" 	=> JText::_('VRMANAGETKAREA12'),
						"content" 	=> JText::_('VRMANAGETKAREA12_HELP'),
					)); ?>
				<?php echo $vik->closeControl(); ?>

			<?php echo $vik->closeFieldset(); ?>
		</div>

		<!-- ZIP CONTENTS FIELDSET -->

		<div class="span6 vr-delivery-contents" id="vr-contents-fieldset3" style="<?php echo ($sel['type'] == 3 ? '' : 'display: none'); ?>">
			<?php echo $vik->openFieldset(JText::_('VRTKAREAFIELDSET2'), 'form-horizontal'); ?>
				
				<div class="vr-delivery-contents-wrapper">
					<div class="vr-zip-container">
						<?php if( $sel['type'] == 3 ) {

							foreach( $content as $i => $zip ) { ?>

								<div class="control" id="vrzip<?php echo $i; ?>">
									<span>
										<label><?php echo JText::_('VRTKZIPPLACEHOLDER1'); ?>:</label>
										<input type="text" name="from_zip[]" value="<?php echo $zip->from; ?>" />
									</span>
									<span>
										<label><?php echo JText::_('VRTKZIPPLACEHOLDER2'); ?>:</label>
										<input type="text" name="to_zip[]" value="<?php echo $zip->to; ?>" />
									</span>
									<span>
										<a href="javascript: void(0);" class="" onClick="removeZipField(<?php echo $i; ?>);">
											<i class="fa fa-times big"></i>
										</a>
									</span>
								</div>

							<?php }

						} ?>
					</div>
				</div>

				<div class="btn-toolbar">
					
					<div class="btn-group pull-left">
						<button type="button" class="btn" onClick="addZipField();">
							<?php echo JText::_('VRMANAGECONFIG29'); ?>
						</button>
					</div>

				</div>

			<?php echo $vik->closeFieldset(); ?>
		</div>

	</div>

	<div class="span12">

		<!-- MAP FIELDSET -->

		<div class="span6" id="vr-map-fieldset" style="<?php echo (($sel['type'] == 1 || $sel['type'] == 2) ? '' : 'display: none'); ?>">
			<?php echo $vik->openFieldset(JText::_('VRTKAREAFIELDSET4'), 'form-horizontal'); ?>
				<div class="control-group"><div id="googlemap" style="width:100%;height:500px;"></div></div>
			<?php echo $vik->closeFieldset(); ?>
		</div>

		<!-- POLYGON CONTENTS FIELDSET -->

		<div class="span6 vr-delivery-contents" id="vr-contents-fieldset1" style="<?php echo ($sel['type'] == 1 ? '' : 'display: none'); ?>">
			<?php echo $vik->openFieldset(JText::_('VRTKAREAFIELDSET2'), 'form-horizontal'); ?>
				
				<div class="vr-delivery-contents-wrapper">
					<div class="vr-polygon-container">
						<?php if( $sel['type'] == 1 ) {

							foreach( $content as $i => $point ) { ?>

								<div class="control" id="vrpoint<?php echo $i; ?>">
									<span>
										<span class="vrtk-entryvar-sortbox"></span>
									</span>
									<span>
										<a href="javascript: void(0);" onClick="drawPolygonSelectedMarker(<?php echo $i; ?>, this);" class="vr-point-marker pression">
											<i class="fa fa-map-marker big"></i>
										</a>
									</span>
									<span>
										<label><?php echo JText::_('VRMANAGETKAREA7'); ?>:</label>
										<input type="number" name="polygon_latitude[]" value="<?php echo (isset($point->latitude) ? $point->latitude : ''); ?>" id="vrpointlat<?php echo $i; ?>" class="vr-polygon-point" step="any" data-id="<?php echo $i; ?>"/>
									</span>
									<span>
										<label><?php echo JText::_('VRMANAGETKAREA8'); ?>:</label>
										<input type="number" name="polygon_longitude[]" value="<?php echo (isset($point->longitude) ? $point->longitude : ''); ?>" id="vrpointlng<?php echo $i; ?>" class="vr-polygon-point" step="any" data-id="<?php echo $i; ?>"/>
									</span>
									<span>
										<a href="javascript: void(0);" onClick="setPolygonPointInspector(<?php echo $i; ?>, this);" class="vr-point-inspector pression">
											<i class="fa fa-dot-circle-o big"></i>
										</a>
									</span>
									<span>
										<a href="javascript: void(0);" onClick="CURR_POLYGON_POINT=<?php echo $i; ?>;getUserCoordinates(polygonCoordHandler);">
											<i class="fa fa-location-arrow big"></i>
										</a>
									</span>
									<span>
										<a href="javascript: void(0);" class="" onClick="removePolygonPoint(<?php echo $i; ?>);">
											<i class="fa fa-times big"></i>
										</a>
									</span>
								</div>

							<?php }

						} ?>
					</div>
				</div>

				<div class="btn-toolbar">

					<div class="btn-group pull-left">
						<button type="button" class="btn" onClick="addPolygonPoint();">
							<?php echo JText::_('VRMANAGETKAREA11'); ?>
						</button>
						<?php echo $vik->createPopover(array(
							"title" 	=> JText::_('VRTKAREATYPE1'),
							"content" 	=> JText::_('VRTKAREA_POLYGON_LEGEND_HELP'),
						)); ?>
					</div>

					<div class="btn-group pull-right">
						<button type="button" class="btn" onClick="togglePolygonCoordinates();">
							<?php echo JText::_('VRMANAGETKAREA13'); ?>
						</button>
					</div>

				</div>

			<?php echo $vik->closeFieldset(); ?>
		</div>

		<!-- CIRCLE CONTENTS FIELDSET -->

		<div class="span6 vr-delivery-contents" id="vr-contents-fieldset2" style="<?php echo ($sel['type'] == 2 ? '' : 'display: none'); ?>">
			<?php echo $vik->openFieldset(JText::_('VRTKAREAFIELDSET2'), 'form-horizontal'); ?>
				
				<!-- CENTER - Number -->
				<?php echo $vik->openControl(JText::_('VRMANAGETKAREA6').'*:'); ?>
					<input type="number" name="center_latitude" value="<?php echo (isset($content->center) ? $content->center->latitude : ''); ?>" step="any" placeholder="<?php echo JText::_('VRMANAGETKAREA7'); ?>" class="vr-circle-field <?php echo ($sel['type'] == 2 ? 'required' : ''); ?>"/>
					<input type="number" name="center_longitude" value="<?php echo (isset($content->center) ? $content->center->longitude : ''); ?>" step="any" placeholder="<?php echo JText::_('VRMANAGETKAREA8'); ?>" class="vr-circle-field <?php echo ($sel['type'] == 2 ? 'required' : ''); ?>"/>
					<a href="javascript: void(0);" onClick="getUserCoordinates(circleCoordHandler);" style="margin-left: 10px;">
						<i class="fa fa-location-arrow big"></i>
					</a>
					<?php echo $vik->createPopover(array(
						"title" 	=> JText::_('VRMANAGETKAREA6'),
						"content" 	=> JText::_('VRTKAREA_CIRCLE_LATLNG_HELP'),
					)); ?>
				<?php echo $vik->closeControl(); ?>

				<!-- RADIUS - Number -->
				<?php echo $vik->openControl(JText::_('VRMANAGETKAREA9').'*:'); ?>
					<input type="number" name="radius" value="<?php echo (isset($content->radius) ? $content->radius : ''); ?>" step="any" class="vr-circle-field <?php echo ($sel['type'] == 2 ? 'required' : ''); ?>"/>
					&nbsp;km.
				<?php echo $vik->closeControl(); ?>

			<?php echo $vik->closeFieldset(); ?>
		</div>

	</div>

	<div class="span12" id="vr-polygon-legend" style="<?php echo ($sel['type'] == 1 ? '' : 'display: none;'); ?>">
		<div class="vr-deliveryarea-legend">

			<span>
				<i class="fa fa-ellipsis-v big"></i> <?php echo JText::_('VRTKAREALEGEND1'); ?>
			</span>

			<span>
				<i class="fa fa-map-marker big"></i> <?php echo JText::_('VRTKAREALEGEND2'); ?>
			</span>

			<span>
				<i class="fa fa-dot-circle-o big"></i> <?php echo JText::_('VRTKAREALEGEND3'); ?>
			</span>

			<span>
				<i class="fa fa-location-arrow big"></i> <?php echo JText::_('VRTKAREALEGEND4'); ?>
			</span>

			<span>
				<i class="fa fa-times big"></i> <?php echo JText::_('VRTKAREALEGEND5'); ?>
			</span>

		</div>
	</div>
	
	<input type="hidden" name="id" value="<?php echo $id; ?>"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="option" value="com_cleverdine"/>
</form>

<script type="text/javascript">

	jQuery(document).ready(function(){

		jQuery('#vr-type-sel').select2({
			minimumResultsForSearch: -1,
			placeholder: '<?php echo addslashes(JText::_('VRTKAREATYPE0')); ?>',
			allowClear: false,
			width: 300
		});

		jQuery('#vr-type-sel').on('change', function(){
			var val = jQuery(this).val();

			if( val == 1 || val == 2 ) {
				jQuery('#vr-attributes-fieldset, #vr-map-fieldset').show();
			} else if( val == 3 ) {
				jQuery('#vr-attributes-fieldset, #vr-map-fieldset').hide();
			}

			if( val == 2 ) {
				jQuery('.vr-circle-field').addClass('required');
			} else {
				jQuery('.vr-circle-field').removeClass('required');
			}

			jQuery('.vr-delivery-contents').hide();
			jQuery('#vr-contents-fieldset'+val).show();

			if( val == 1 ) {
				jQuery('#vr-polygon-legend').show();
			} else {
				jQuery('#vr-polygon-legend').hide();
			}

		});

		jQuery("#adminForm .required").on("blur", function(){
			if( jQuery(this).val().length > 0 ) {
				jQuery(this).removeClass("vrrequired");
			} else {
				jQuery(this).addClass("vrrequired");
			}
		});

		// colorpicker

		var COLOR_TMP = null;

		jQuery('#vrcolorpicker').ColorPicker({
			color: jQuery('#vrattrcolor').val(),
			onShow: function(){
				COLOR_TMP = jQuery('#vrattrcolor').val();
			},
			onChange: function (hsb, hex, rgb) {
				jQuery('#vrattrcolor').val('#'+hex.toUpperCase());
			},
			onHide: function(){

				if( jQuery('#vrattrstrokecolor').val() == COLOR_TMP ) {
					jQuery('#vrattrstrokecolor').val(jQuery('#vrattrcolor').val());
				}
				
				refreshVisibleMap();

			}
		});

		jQuery('#vrstrokecolorpicker').ColorPicker({
			color: jQuery('#vrattrstrokecolor').val(),
			onChange: function (hsb, hex, rgb) {
				jQuery('#vrattrstrokecolor').val('#'+hex.toUpperCase());
			},
			onHide: function(){
				
				refreshVisibleMap();

			}
		});

		makeSortable();

	});

	function makeSortable() {
		jQuery( ".vr-polygon-container" ).sortable({
			revert: true,
			stop: function(){
				
				refreshVisibleMap();

			}
		});
		//jQuery( ".vr-polygon-container" ).disableSelection();
	}

	function vrValidateFields() {
		var ok = true;
		jQuery("#adminForm .required:input").each(function(){
			var val = jQuery(this).val();
			if( val !== null && val.length > 0 ) {
				jQuery(this).removeClass("vrrequired");
			} else {
				jQuery(this).addClass("vrrequired");
				ok = false;
			}
		});
		return ok;
	}

	Joomla.submitbutton = function(task) {
		if( task.indexOf('save') !== -1 ) {
			if( vrValidateFields() ) {
				Joomla.submitform(task, document.adminForm);	
			}
		} else {
			Joomla.submitform(task, document.adminForm);
		}
	}

	// MAP UTILS

	var ANIMATION = false;

	jQuery(document).ready(function(){

		<?php if( $sel['type'] == 1 ) { ?>
			google.maps.event.addDomListener(window, 'load', initializePolygonMap);
		<?php } else if( $sel['type'] == 2 ) { ?>
			google.maps.event.addDomListener(window, 'load', initializeCircleMap);
		<?php } ?>

		jQuery('.vr-circle-field, .vr-attribute-field').on('change', function(){
			changeCircleContents(
				jQuery('input[name="center_latitude"]').val(),
				jQuery('input[name="center_longitude"]').val(),
				jQuery('input[name="radius"]').val()
			);
		});

		jQuery('input[type="number"]').on('mousewheel', function(){
			jQuery(this).blur();
		})

		jQuery('.vr-polygon-point, .vr-attribute-field').on('change', function(){
			
			refreshVisibleMap();

		});

	});

	function getUserCoordinates(handler) {
		// Try HTML5 geolocation
		if( navigator.geolocation ) {
			navigator.geolocation.getCurrentPosition(function(position) {

				handler(position.coords.latitude, position.coords.longitude);
				
			}, function(err) {
				switch(err.code) {
					case err.PERMISSION_DENIED:
						alert("User denied the request for Geolocation.");
						break;
					case err.POSITION_UNAVAILABLE:
						alert("Location information is unavailable (HTTPS may be required).");
						break;
					case err.TIMEOUT:
						alert("The request to get user location timed out.");
						break;
					default:
						alert("An unknown error occurred.");
				}
			});
		} else {
			alert("Your browser does not support Geolocation.");
		}

	}

	var MAP_SHAPES = <?php echo json_encode($this->shapes); ?>;
	var DISPLAY_SHAPES = 0;

	function fillMapShapes(map) {

		if( !DISPLAY_SHAPES ) {
			return;
		}

		var shapes = [];
		var coords = [];

		for( var i = 0; i < MAP_SHAPES.length; i++ ) {

			if( MAP_SHAPES[i].id != <?php echo intval($id); ?> ) {

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

					shapes.push( 
						new google.maps.Circle({
							strokeColor: MAP_SHAPES[i].attributes.strokecolor,
							strokeOpacity: 0.5,
							strokeWeight: MAP_SHAPES[i].attributes.strokeweight,
							fillColor: MAP_SHAPES[i].attributes.color,
							fillOpacity: 0.20,
							map: map,
							center: new google.maps.LatLng(MAP_SHAPES[i].content.center.latitude, MAP_SHAPES[i].content.center.longitude),
							radius: MAP_SHAPES[i].content.radius * 1000,
							clickable: false
						})
					);

				}

			}

		}

	}

	function refreshVisibleMap() {

		var type = jQuery('#vr-type-sel').val();

		if( type == "1" ) {
			initializePolygonMap();
		} else if( type == "2" ) {
			initializeCircleMap();
		}

	}

	// POLYGON HANDLER

	var polyMap = null;

	function initializePolygonMap() {

		var prop = {
			zoom: 12,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		}

		if( polyMap !== null ) {
			prop = {
				zoom: polyMap.getZoom(),
				center: polyMap.getCenter(),
				mapTypeId: google.maps.MapTypeId.ROADMAP
			}	
		}
		
		polyMap = new google.maps.Map(document.getElementById("googlemap"), prop);

		// get bounds handler
		var markerBounds = new google.maps.LatLngBounds();
		var position = null;

		// Define the LatLng coordinates for the polygon's path.
		var shape_coordinates = getPolygonPoints();

		for( var i = 0; i < shape_coordinates.length; i++ ) {
			position = new google.maps.LatLng(shape_coordinates[i].lat, shape_coordinates[i].lng);
			
			markerBounds.extend(position);
		}

		var fillColor = jQuery('input[name="color"]').val();
		if( !fillColor.length ) {
			fillColor = '#FF0000';
		}

		var strokeColor = jQuery('input[name="strokecolor"]').val();
		if( !strokeColor.length ) {
			strokeColor = '#FF0000';
		}

		var strokeWeight = parseInt(jQuery('input[name="strokeweight"]').val());
		if( isNaN(strokeWeight) ) {
			strokeWeight = 2;
		}

		// FILL ALL AREAS
		fillMapShapes(polyMap);
		///

		// Construct the polygon.
		var shape = null;

		if( shape_coordinates.length > 1 ) {

			shape = new google.maps.Polygon({
				paths: shape_coordinates,
				strokeColor: strokeColor,
				strokeOpacity: 0.8,
				strokeWeight: strokeWeight,
				fillColor: fillColor,
				fillOpacity: 0.35,
				clickable: false
			});
			
		} else if( shape_coordinates.length > 0 ) {
			shape = new google.maps.Marker({
				position: shape_coordinates[0]
			});

			if( ANIMATION ) {
				shape.setAnimation(google.maps.Animation.DROP);
			}
		}

		if( shape !== null ) {
			shape.setMap(polyMap);
		}

		if( !ANIMATION ) {
			polyMap.fitBounds(markerBounds);
			polyMap.setCenter(markerBounds.getCenter());

			if( shape !== null ) {
				ANIMATION = true;
			}
		}

		if( MARKER_SPOT !== null ) {
				var lat = parseFloat(jQuery('#vrpointlat'+MARKER_SPOT).val());
				var lng = parseFloat(jQuery('#vrpointlng'+MARKER_SPOT).val());

				if( !isNaN(lat) && !isNaN(lng) ) {
				shape = new google.maps.Marker({
					position: {
						lat: lat,
						lng: lng
					}
				});

				shape.setAnimation(google.maps.Animation.DROP);
				shape.setMap(polyMap);

				polyMap.setCenter(shape.position);
			}
		} 

		polyMap.addListener('click', function(e) {
			polygonMapClickListener(e.latLng.lat(), e.latLng.lng());
		});
		
	}

	var POLYGON_POINTS_COUNT = <?php echo ($sel['type'] == 1 ? count($content) : 0); ?>;

	function addPolygonPoint() {

		jQuery('.vr-polygon-container').append('<div class="control" id="vrpoint'+POLYGON_POINTS_COUNT+'">\n'+
			'<span>\n'+
				'<span class="vrtk-entryvar-sortbox"></span>\n'+
			'</span>\n'+
			'<span>\n'+
				'<a href="javascript: void(0);" onClick="drawPolygonSelectedMarker('+POLYGON_POINTS_COUNT+', this);" class="vr-point-marker pression">\n'+
					'<i class="fa fa-map-marker big"></i>\n'+
				'</a>\n'+
			'</span>\n'+
			'<span>\n'+
				'<label><?php echo addslashes(JText::_('VRMANAGETKAREA7')); ?>:</label>\n'+
				'<input type="number" name="polygon_latitude[]" value="" id="vrpointlat'+POLYGON_POINTS_COUNT+'" class="vr-polygon-point" step="any" data-id="'+POLYGON_POINTS_COUNT+'"/>\n'+
			'</span>\n'+
			'<span>\n'+
				'<label><?php echo addslashes(JText::_('VRMANAGETKAREA8')); ?>:</label>\n'+
				'<input type="number" name="polygon_longitude[]" value="" id="vrpointlng'+POLYGON_POINTS_COUNT+'" class="vr-polygon-point" step="any" data-id="'+POLYGON_POINTS_COUNT+'"/>\n'+
			'</span>\n'+
			'<span>\n'+
				'<a href="javascript: void(0);" onClick="setPolygonPointInspector('+POLYGON_POINTS_COUNT+', this);" class="vr-point-inspector pression">\n'+
					'<i class="fa fa-dot-circle-o big"></i>\n'+
				'</a>\n'+
			'</span>\n'+
			'<span>\n'+
				'<a href="javascript: void(0);" onClick="CURR_POLYGON_POINT='+POLYGON_POINTS_COUNT+';getUserCoordinates(polygonCoordHandler);">\n'+
					'<i class="fa fa-location-arrow big"></i>\n'+
				'</a>\n'+
			'</span>\n'+
			'<span>\n'+
				'<a href="javascript: void(0);" class="" onClick="removePolygonPoint('+POLYGON_POINTS_COUNT+');">\n'+
					'<i class="fa fa-times big"></i>\n'+
				'</a>\n'+
			'</span>\n'+
		'</div>\n');

		setPolygonPointInspector(POLYGON_POINTS_COUNT, jQuery('.vr-point-inspector').last());

		jQuery('#vrpointlat'+POLYGON_POINTS_COUNT+', #vrpointlng'+POLYGON_POINTS_COUNT).on('change', function(){
			refreshVisibleMap();
		});

		makeSortable();

		POLYGON_POINTS_COUNT++;
	}

	function removePolygonPoint(id) {
		jQuery('#vrpoint'+id).remove();

		initializePolygonMap();
	}

	function togglePolygonCoordinates() {

		if( jQuery('.vr-polygon-container').is(':visible') ) {
			jQuery('.vr-polygon-container').slideUp();
		} else {
			jQuery('.vr-polygon-container').slideDown();
		}

	}

	var CURR_POLYGON_POINT = -1;

	function polygonCoordHandler(lat, lng) {

		if( CURR_POLYGON_POINT < 0 ) {
			return;
		}

		var curr_lat = jQuery('#vrpointlat'+CURR_POLYGON_POINT).val();
		
		var r = true;

		if( curr_lat.length ) {
			r = confirm('<?php echo addslashes(JText::_('VRTKAREAUSERPOSITION')); ?>');
		}
		
		if( r ) {

			jQuery('#vrpointlat'+CURR_POLYGON_POINT).val(lat);
			jQuery('#vrpointlng'+CURR_POLYGON_POINT).val(lng);

			initializePolygonMap();
		}
	}

	function getPolygonPoints() {
		var points = [];

		var p = null, value = null;

		jQuery('.vr-polygon-point').each(function(k, v){
			if( k%2 == 0 ) {
				p = {};
				p.lat = parseFloat(jQuery(v).val());
			} else {
				p.lng = parseFloat(jQuery(v).val());

				if( !isNaN(p.lat) && !isNaN(p.lng) ) {
					points.push(p);
				}
			}
		});

		return points;
	}

	POLYGON_POINT_INSPECT = null;

	function setPolygonPointInspector(i, elem) {
		if( POLYGON_POINT_INSPECT == i ) {
			POLYGON_POINT_INSPECT = null;
			jQuery(elem).removeClass('clicked');
		} else {
			POLYGON_POINT_INSPECT = i;
			jQuery('.vr-point-inspector').removeClass('clicked');
			jQuery(elem).addClass('clicked');
		}
	}

	function polygonMapClickListener(lat, lng) {
		if( POLYGON_POINT_INSPECT === null ) {
			return;
		}

		CURR_POLYGON_POINT = POLYGON_POINT_INSPECT;

		polygonCoordHandler(lat, lng);
	}

	var MARKER_SPOT = null;

	function drawPolygonSelectedMarker(id, elem) {

		if( MARKER_SPOT == id ) {
			MARKER_SPOT = null;
			jQuery(elem).removeClass('clicked');
		} else {
			MARKER_SPOT = id;
			jQuery('.vr-point-marker').removeClass('clicked');
			jQuery(elem).addClass('clicked');
		}

		refreshVisibleMap();
	}

	// CIRCLE HANDLER
	
	var CIRCLE = {
		lat: 	null,
		lng: 	null,
		radius: 0
	};

	<?php if( $sel['type'] == 2 && isset($content->center) ) { ?>
		CIRCLE.lat 		= <?php echo floatval($content->center->latitude); ?>;
		CIRCLE.lng 		= <?php echo floatval($content->center->longitude); ?>;
		CIRCLE.radius 	= <?php echo floatval($content->radius); ?>;
	<?php } ?>
	
	var circleMap = null;

	function initializeCircleMap() {

		var prop = {
			zoom: 12,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		}

		if( circleMap !== null ) {
			prop = {
				zoom: circleMap.getZoom(),
				mapTypeId: google.maps.MapTypeId.ROADMAP
			}	
		}
		
		var coord = new google.maps.LatLng(CIRCLE.lat,CIRCLE.lng);

		prop.center = coord;
		
		circleMap = new google.maps.Map(document.getElementById("googlemap"), prop);
		
		var marker = new google.maps.Marker({
			position: coord
		});

		if( ANIMATION ) {
			marker.setAnimation(google.maps.Animation.DROP);
			ANIMATION = true;
		}
			
		marker.setMap(circleMap);

		// FILL ALL AREAS
		fillMapShapes(circleMap);
		///

		var fillColor = jQuery('input[name="color"]').val();
		if( !fillColor.length ) {
			fillColor = '#FF0000';
		}

		var strokeColor = jQuery('input[name="strokecolor"]').val();
		if( !strokeColor.length ) {
			strokeColor = fillColor;
		}

		var strokeWeight = parseInt(jQuery('input[name="strokeweight"]').val());
		if( isNaN(strokeWeight) ) {
			strokeWeight = 2;
		}

		var cityCircle = new google.maps.Circle({
			strokeColor: strokeColor,
			strokeOpacity: 0.8,
			strokeWeight: strokeWeight,
			fillColor: fillColor,
			fillOpacity: 0.35,
			map: circleMap,
			center: coord,
			radius: CIRCLE.radius*1000,
			clickable: false
		});

		circleMap.addListener('click', function(e) {
			circleCoordHandler(e.latLng.lat(), e.latLng.lng());
		});

	}
	
	function changeCircleContents(lat, lng, radius) {
		CIRCLE.lat 		= parseFloat(lat);
		CIRCLE.lng 		= parseFloat(lng);
		CIRCLE.radius 	= parseFloat(radius);

		if( CIRCLE.lat.length == 0 || CIRCLE.lng.length == 0 ) {
			return;
		}

		initializeCircleMap();
	}

	function circleCoordHandler(lat, lng) {
		
		var r = true;

		if( CIRCLE.lat !== null && !isNaN(CIRCLE.lat) ) {
			r = confirm('<?php echo addslashes(JText::_('VRTKAREAUSERPOSITION')); ?>');
		}
		
		if( r ) {

			jQuery('input[name="center_latitude"]').val(lat);
			jQuery('input[name="center_longitude"]').val(lng);

			jQuery('.vr-circle-field').trigger('change');
		}
	}

	// ZIP HANDLER

	var ZIP_COUNT = <?php echo ($sel['type'] == 3 ? count($content) : 0); ?>;

	function addZipField() {

		jQuery('.vr-zip-container').append('<div class="control" id="vrzip'+ZIP_COUNT+'">\n'+
			'<span>\n'+
				'<label><?php echo addslashes(JText::_('VRTKZIPPLACEHOLDER1')); ?>:</label>\n'+
				'<input type="text" name="from_zip[]" value="" />\n'+
			'</span>\n'+
			'<span>\n'+
				'<label><?php echo addslashes(JText::_('VRTKZIPPLACEHOLDER2')); ?>:</label>\n'+
				'<input type="text" name="to_zip[]" value="" />\n'+
			'</span>\n'+
			'<span>\n'+
				'<a href="javascript: void(0);" class="" onClick="removeZipField('+ZIP_COUNT+');">\n'+
					'<i class="fa fa-times big"></i>\n'+
				'</a>\n'+
			'</span>\n'+
		'</div>\n');

		ZIP_COUNT++;
	}

	function removeZipField(id) {
		jQuery('#vrzip'+id).remove();
	}

</script>