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

JHtml::_('behavior.keepalive');

$room = $this->room;
$tables = $this->tables;

$graphics_properties = json_decode($room['graphics_properties'], true);
$call_store_function = 0;

if( empty($graphics_properties) ) {
	$graphics_properties = RestaurantsHelper::getDefaultGraphicsProperties();
	$call_store_function = 1;
}

$gp_input = array(
	// options
	'prefix'       => array('label' => 'VRMAPGPINPREFIX', 'input' => 'text', 'default' => 't'),
	'people'       => array('label' => 'VRMAPGPINPEOPLE', 'input' => 'number', 'default' => 4),
	// properties
	'start_x'       => array('label' => 'VRMAPGPINSTARTX', 'input' => 'number', 'default' => 40),
	'start_y'       => array('label' => 'VRMAPGPINSTARTY', 'input' => 'number', 'default' => 40),
	'minwidth'      => array('label' => 'VRMAPGPINMINWIDTH', 'input' => 'number', 'default' => 90),
	'minheight'     => array('label' => 'VRMAPGPINMINHEIGHT', 'input' => 'number', 'default' => 90),
	'wpp'           => array('label' => 'VRMAPGPINWPP', 'input' => 'number', 'default' => 45),
	'hpp'           => array('label' => 'VRMAPGPINHPP', 'input' => 'number', 'default' => 45),
	'hor_spacing'   => array('label' => 'VRMAPGPINHORSP', 'input' => 'number', 'default' => 60),
	'ver_spacing'   => array('label' => 'VRMAPGPINVERSP', 'input' => 'number', 'default' => 60),
	'color'         => array('label' => 'VRMAPGPINCOLOR', 'input' => 'text', 'default' => '#a1988d'),
	'mapwidth'      => array('label' => 'VRMAPGPINMAPWIDTH', 'input' => 'number', 'default' => 800),
	'mapheight'     => array('label' => 'VRMAPGPINMAPHEIGHT', 'input' => 'number', 'default' => 500),
	'display_next'  => array('label' => 'VRMAPGPINISNEXT', 'input' => 'checkbox', 'default' => 1),
);

$last_position = array('x' => 0, 'y' => 0);
foreach( $tables as $t ) {
	$attr = json_decode($t['design_data'], true);
	if( $attr['pos']['top'] > $last_position['y'] || ( $attr['pos']['top'] == $last_position['y'] && $attr['pos']['left'] > $last_position['x'] ) ) {
		$last_position['x'] = $attr['pos']['left'];
		$last_position['y'] = $attr['pos']['top'];
	}
}

?>	

<script>
var idCounter = 1;

function rgb2hex(rgb) {
	rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
	return (  hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]) );
}

function hex(x) {
	return ('0' + parseInt(x).toString(16)).slice(-2);
}

</script>

<div style="margin-bottom: 10px;">

	<div class="btn-toolbar vr-btn-toolbar">

		<div class="btn-group pull-left">
			<button type="button" class="btn" id="vraddtable">
				<i class="fa fa-plus"></i>&nbsp;&nbsp;<?php echo JText::_('VRMAPADDTABLEBUTTON'); ?>
			</button>
		</div>
		<div class="btn-group pull-left">
			<button type="button" class="btn" id="vrclonetable">
				<i class="fa fa-clone"></i>&nbsp;&nbsp;<?php echo JText::_('VRMAPCLONETABLEBUTTON'); ?>
			</button>
		</div>
		<div class="btn-group pull-left">
			<button type="button" class="btn" id="vrvaligntable">
				<i class="fa fa-arrows-h"></i>&nbsp;&nbsp;<?php echo JText::_('VRMAPHALIGNTABLEBUTTON'); ?>
			</button>
		</div>
		<div class="btn-group pull-left">
			<button type="button" class="btn" id="vrhaligntable">
				<i class="fa fa-arrows-v"></i>&nbsp;&nbsp;<?php echo JText::_('VRMAPVALIGNTABLEBUTTON'); ?>
			</button>
		</div>

		<div class="btn-group pull-right">
			<button type="button" class="btn" id="vrgrproplink">
				<i class="fa fa-wrench"></i>&nbsp;&nbsp;<?php echo JText::_('VRMAPPROPERTIESBUTTON'); ?>
			</button>
		</div>

	</div>

	<!-- graphics properties modal box -->
	<div class="vrgrpropmodal" style="display: none;">
		<h3><?php echo JText::_('VRMAPPROPERTIESBUTTON'); ?></h3>
		<table>
			<?php foreach( $gp_input as $key => $prop ) { ?>
				<tr>
					<td width="120">
						<label for="vrgp<?php echo $key; ?>"><?php echo JText::_($prop['label']); ?></label>
					</td>
					<td>
						<?php if( $prop['input'] == "number" ) { ?>
							<input type="number" id="vrgp<?php echo $key; ?>" value="<?php echo $graphics_properties[$key]; ?>" min="1" max="1000000"/>
						<?php } else if( $prop['input'] == "text" ) { ?>
							<input type="text" id="vrgp<?php echo $key; ?>" value="<?php echo $graphics_properties[$key]; ?>" size="9"/>
						<?php } else if( $prop['input'] == "checkbox" ) { ?>
							<input type="checkbox" id="vrgp<?php echo $key; ?>" value="1" <?php echo ($graphics_properties[$key] == 1 ? 'checked="checked"' : ''); ?> />
						<?php } ?>
					</td>
				</tr>
			<?php } ?>
		</table>
		<div class="vrgpropmodalsep">
			<input type="checkbox" value="1" id="vrgpapplyallbox"/>
			<label for="vrgpapplyallbox" style="display: inline-block;"><?php echo JText::_('VRMAPAPPLYALLBUTTON'); ?></label>
		</div>
		<div class="vrgrpropmodalboxbuttonsdiv">
			<div class="float-left">
				<button type="button" class="btn btn-warning" onclick="restoreGraphicsPropertiesModal();">
					<i class="fa fa-undo"></i>&nbsp;&nbsp;<?php echo JText::_('VRMAPGPRESTOREBUTTON'); ?>
				</button>
			</div>
			<div class="float-right">
				<button type="button" class="btn btn-danger" onclick="closeGraphicsPropertiesModal(0);">
					<i class="fa fa-times"></i>&nbsp;&nbsp;<?php echo JText::_('VRMAPCONFIRMCANCELBUTTON'); ?>
				</button>
				<button type="button" class="btn btn-success" onclick="closeGraphicsPropertiesModal(1);">
					<i class="fa fa-check"></i>&nbsp;&nbsp;<?php echo JText::_('VRMAPAPPLYBUTTON'); ?>
				</button>
			</div>
		</div>
	</div>
	<!-- end graphics properties modal box -->
</div>

<div id="vractiontooltip"></div>

<div id="tcontainer">
	<div class="vrcontwseparator"></div>
</div>

<span class="vrmap-incdec-button">
	<a href="javascript: void(0);" id="vrinctcheight">
		<i class="fa fa-plus-circle"></i>&nbsp;<?php echo JText::_('VRMAPSIZEINCBUTTON'); ?>
	</a>
</span>
<span class="vrmap-incdec-button">
	<a href="javascript: void(0);" id="vrdectcheight">
		<i class="fa fa-minus-circle"></i>&nbsp;<?php echo JText::_('VRMAPSIZEDECBUTTON'); ?>
	</a>
</span>
<span id="vrtcsizesp"><?php echo JText::_('VRMAPSIZELABEL'); ?>:</span>
	
<form name="adminForm" action="index.php" method="post" enctype="multipart/form-data" id="adminForm">
	<div id="vrdata">
		
		<?php 
		for( $i = 0, $n = count($tables); $i < $n; $i++ ) {

			$prop = json_decode($tables[$i]["design_data"], true);
			
			?><script>
			
			jQuery(function(){
				var vrdata = jQuery("#vrdata");
				var content = jQuery("#tcontainer");
				var off = content.offset();
				
				var titleClass = "vrttitle";
				if( name.length > 10 ) {
					titleClass = "vrttitlesmaller";
				}
		
				content.append(
						'<div class="vrtable" id="vrt'+idCounter+'" onClick="actionPressedTable('+idCounter+');">'+
							'<div class="vrclosebox" id="vrtcb'+idCounter+'" onClick="closeTable('+idCounter+')">X</div>'+
							'<span class="'+titleClass+'" id="vrttitle'+idCounter+'"><?php echo $tables[$i]["name"]; ?></span>'+
							'<div class="vractionsdiv">'+
							'<span class="vrtoptionlink"><a href="javascript: void(0);" style="display: block;" onClick="showOptionsVRTModalBox('+idCounter+')">'+
								'<i class="fa fa-cog medium-big"></i> <?php echo JText::_('VRMAPOPTIONSBUTTON'); ?>'+
							'</a></span>'+
							'<span class="vrtproplink"><a href="javascript: void(0);" style="display: block;" onClick="showPropertiesVRTModalBox('+idCounter+')">'+
								'<i class="fa fa-briefcase medium-big"></i> <?php echo JText::_('VRMAPPROPERTIESBUTTON'); ?>'+
							'</a></span>'+
							'</div>'+
							'<div id="vrtomb'+idCounter+'"class="vrtomodalbox">'+
								createOptionsModalBox(idCounter)+
							'</div>'+
							'<div id="vrtpmb'+idCounter+'"class="vrtpmodalbox">'+
								createPropertiesModalBox(idCounter)+
							'</div>'+
						'</div>');
				
			
				jQuery(".vrtable").resizable({
					stop: function(event, ui) {
						jQuery("#"+jQuery(this).attr('id')+"size").attr('value', ui.size.width + '_' + ui.size.height);
						var realid = jQuery(this).attr('id').replace("vrt", "");
						jQuery("#vrtpmbsizew"+realid).val(""+ui.size.width);
						jQuery("#vrtpmbsizeh"+realid).val(""+ui.size.height);

						recalculateModalBoxPosition(realid);
					}
				});
				
				jQuery(".vrtable").draggable({
					containment: "#tcontainer",
					scroll: false,
					stop: function(event, ui) {
						jQuery("#"+jQuery(this).attr('id')+"pos").attr('value', ui.position.left + '_' + ui.position.top);
						var realid = jQuery(this).attr('id').replace("vrt", "");
						jQuery("#vrtpmbposx"+realid).val(""+ui.position.left);
						jQuery("#vrtpmbposy"+realid).val(""+ui.position.top);

						recalculateModalBoxPosition(realid);
					}
				}); 

				/* CURSOR */

				jQuery(".vrtable").mouseenter(function(){
					mouseEnter(jQuery(this).attr('id'));
				});
				
				jQuery(".vrtable").mouseleave(function(){
					mouseLeave();
				});

				// SETTING 

				var x   = <?php echo $prop["pos"]["left"]; ?>+11+off.left;
				var y   = <?php echo $prop["pos"]["top"]; ?>+11+off.top;
				var w   = <?php echo $prop["size"]["width"]; ?>;
				var h   = <?php echo $prop["size"]["height"]; ?>;
				var rot = <?php echo $prop["rotation"]; ?>;
				var bgc = "<?php echo $prop["bgcolor"]; ?>";

				jQuery("#vrt"+idCounter).css({
					top:y+"px",
					left:x+"px",
					width:w,
					height:h,
				});

				if( bgc != -1 && bgc.indexOf( "rgb" ) != 0 ) {
					jQuery("#vrt"+idCounter).css('backgroundColor', '#'+bgc);
				} else {
					rgb = jQuery("#vrt"+idCounter).css('backgroundColor');
					bgc = rgb2hex(rgb);
				}

				jQuery("#vrt"+idCounter).css('transform', 'rotate('+rot+'deg)');
				jQuery("#vrt"+idCounter).css('-ms-transform', 'rotate('+rot+'deg)');
				jQuery("#vrt"+idCounter).css('-webkit-transform', 'rotate('+rot+'deg)');

				vrdata.append('<input type="hidden" name="vrt_pos[]" id="vrt'+idCounter+'pos" value="' + x + '_' + y + '"/>');
				vrdata.append('<input type="hidden" name="vrt_size[]" id="vrt'+idCounter+'size" value="' + w + '_' + h + '"/>');
				vrdata.append('<input type="hidden" name="vrt_name[]" id="vrt'+idCounter+'name" value="<?php echo $tables[$i]["name"]; ?>"/>');
				vrdata.append('<input type="hidden" name="vrt_seats[]" id="vrt'+idCounter+'seats" value="<?php echo $tables[$i]["min_capacity"] . '_' . $tables[$i]["max_capacity"]; ?>"/>');
				vrdata.append('<input type="hidden" name="vrt_multires[]" id="vrt'+idCounter+'multires" value="<?php echo $tables[$i]["multi_res"]; ?>"/>');
				vrdata.append('<input type="hidden" name="vrt_rot[]" id="vrt'+idCounter+'rot" value="' + rot + '"/>');
				vrdata.append('<input type="hidden" name="vrt_bgc[]" id="vrt'+idCounter+'bgc" value="' + bgc + '"/>');
				vrdata.append('<input type="hidden" name="vrt_id[]" id="vrt'+idCounter+'id" value="<?php echo $tables[$i]["id"]; ?>"/>');

				initPropertiesModalBox(idCounter);

				idCounter++;
				
			});
			</script><?php
		}
		?>
	</div>
	
	<input type="hidden" name="offset" id="offset" value=""/>
		
	<input type="hidden" name="id" value="<?php echo $room["id"]; ?>"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="option" value="com_cleverdine"/>
</form>

<script>

var vrdata = jQuery("#vrdata");
var cont = jQuery("#tcontainer");
		
var isModalOpened = false;
var isClone = false;
var isHorAlign = false;
var isVerAlign = false;

var alignObj = new Array();

var graphics_properties = <?php echo json_encode($graphics_properties); ?>;
var gp_input = <?php echo json_encode($gp_input); ?>;
var last_table_position = <?php echo json_encode($last_position); ?>;

var call_store_function = <?php echo ($call_store_function ? 1 : 0); ?>;

jQuery(function(){

	var contOffset = cont.offset();

	jQuery("#offset").attr('value', contOffset.left + '_' + contOffset.top);

	/* ADD TABLE */
	
	jQuery("#vraddtable").click(function(){

		disableClone();
		
		var titleClass = "vrttitle";
		if( name.length > 10 ) {
			titleClass = "vrttitlesmaller";
		}
		
		cont.append(
			'<div class="vrtable" id="vrt'+idCounter+'" onClick="actionPressedTable('+idCounter+');">'+
				'<div class="vrclosebox" id="vrtcb'+idCounter+'" onClick="closeTable('+idCounter+')">X</div>'+
				'<span class="'+titleClass+'" id="vrttitle'+idCounter+'">'+graphics_properties['prefix']+idCounter+'</span>'+
				'<div class="vractionsdiv">'+
				'<span class="vrtoptionlink"><a href="javascript: void(0);" style="display: block;" onClick="showOptionsVRTModalBox('+idCounter+')">\n'+
					'<i class="fa fa-cog medium-big"></i> <?php echo JText::_('VRMAPOPTIONSBUTTON'); ?>'+
				'</a></span>'+
				'<span class="vrtproplink"><a href="javascript: void(0);" style="display: block;" onClick="showPropertiesVRTModalBox('+idCounter+')">'+
					'<i class="fa fa-briefcase medium-big"></i> <?php echo JText::_('VRMAPPROPERTIESBUTTON'); ?>'+
				'</a></span>'+
				'</div>'+
				'<div id="vrtomb'+idCounter+'"class="vrtomodalbox">'+
					createOptionsModalBox(idCounter)+
				'</div>'+
				'<div id="vrtpmb'+idCounter+'"class="vrtpmodalbox">'+
					createPropertiesModalBox(idCounter)+
				'</div>'+
			'</div>');
		
		jQuery(".vrtable").resizable({
			stop: function(event, ui) {
				jQuery("#"+jQuery(this).attr('id')+"size").attr('value', ui.size.width + '_' + ui.size.height);
				var realid = jQuery(this).attr('id').replace("vrt", "");
				jQuery("#vrtpmbsizew"+realid).val(""+ui.size.width);
				jQuery("#vrtpmbsizeh"+realid).val(""+ui.size.height);

				recalculateModalBoxPosition(realid);
			}
		});
		
		jQuery(".vrtable").draggable({
			containment: "#tcontainer",
			scroll: false,
			stop: function(event, ui) {
				jQuery("#"+jQuery(this).attr('id')+"pos").attr('value', ui.position.left + '_' + ui.position.top);
				var realid = jQuery(this).attr('id').replace("vrt", "");
				jQuery("#vrtpmbposx"+realid).val(""+ui.position.left);
				jQuery("#vrtpmbposy"+realid).val(""+ui.position.top);

				recalculateModalBoxPosition(realid);
			}
		});

		/* CURSOR */

		jQuery(".vrtable").mouseenter(function(){
			mouseEnter(jQuery(this).attr('id'));
		});
		
		jQuery(".vrtable").mouseleave(function(){
			mouseLeave();
		});
		
		// get here the rectangle of the table
		var rect = getNextTablePosition();
		// apply offset to the table location
		var left = rect[0]+contOffset.left+11;
		var top = rect[1]+contOffset.top+11;

		jQuery("#vrt"+idCounter).css({
			left:     left+"px",
			top:      top+"px",
			width:    rect[2]+"px",
			height:   rect[3]+"px",
			backgroundColor: graphics_properties["color"]
		});
		
		// update last table position
		last_table_position["x"] = rect[0];
		last_table_position["y"] = rect[1];
		// refresh future table
		refreshFutureTable();

		var rgb = jQuery("#vrt"+idCounter).css('backgroundColor');
		var bgc = rgb2hex(rgb);

		//var size = jQuery("#vrt"+idCounter).css(["width","height"]);
		//var tWidth = size["width"].replace('px', '');
		//var tHeight = size["height"].replace('px', ''); 
		 
		vrdata.append('<input type="hidden" name="vrt_pos[]" id="vrt' + idCounter + 'pos" value="' + left + '_' + top + '"/>');
		vrdata.append('<input type="hidden" name="vrt_size[]" id="vrt' + idCounter + 'size" value="' + rect[2] + '_' + rect[3] + '"/>');
		vrdata.append('<input type="hidden" name="vrt_name[]" id="vrt' + idCounter + 'name" value="' + graphics_properties['prefix']+idCounter + '"/>');
		vrdata.append('<input type="hidden" name="vrt_seats[]" id="vrt' + idCounter + 'seats" value="2_'+graphics_properties['people']+'"/>');
		vrdata.append('<input type="hidden" name="vrt_multires[]" id="vrt'+idCounter+'multires" value="0"/>');
		vrdata.append('<input type="hidden" name="vrt_rot[]" id="vrt' + idCounter + 'rot" value="0"/>');
		vrdata.append('<input type="hidden" name="vrt_bgc[]" id="vrt' + idCounter + 'bgc" value="' + bgc + '"/>');
		vrdata.append('<input type="hidden" name="vrt_id[]" id="vrt' + idCounter + 'id" value="-1"/>');
		
		idCounter++;
	});
	
	/* CLONE TABLE */
	jQuery("#vrclonetable").click(function(){
		isClone = !isClone;
		if( isClone ) {
			cont.css('cursor','crosshair');
			enableClone();
		} else {
			cont.css('cursor','auto');
			disableClone();
		}
	});

	/* HORIZONTAL ALIGN TABLE */
	jQuery("#vrhaligntable").click(function(){
		isHorAlign = !isHorAlign;
		if( isHorAlign ) {
			cont.css('cursor','crosshair');
			enableHorAlign();
		} else {
			cont.css('cursor','auto');
			disableHorAlign();
		}
	});	
	
	/* VERTICAL ALIGN TABLE */
	jQuery("#vrvaligntable").click(function(){
		isVerAlign = !isVerAlign;
		if( isVerAlign ) {
			cont.css('cursor','crosshair');
			enableVerAlign();
		} else {
			cont.css('cursor','auto');
			disableVerAlign();
		}
	});	
	
	/* GRAPHICS PROPERTIES */
	jQuery("#vrgrproplink").click(function(){
		if( !jQuery(".vrgrpropmodal").is(":visible") ) {
			openGraphicsPropertiesModal();
		} else {
			closeGraphicsPropertiesModal(0);
		}
	});	

	/* PANEL SIZE */
	jQuery( document ).ready(function() {
		jQuery("#tcontainer").css( {"height": "<?php echo $graphics_properties['mapheight']; ?>px" } );
		
		if( graphics_properties['display_next'] ) {
		   jQuery("#tcontainer").append(createFutureTable());
		   refreshFutureTable();
		}
		
		updateMapWidthSeparator();
		
		if( call_store_function ) {
			storeGraphicsProperties();
		}
		
	});
	
	jQuery("#vrinctcheight").click(function(){
		jQuery("#tcontainer").css("height","+=50");
		
		graphics_properties['mapheight'] += 50;
		jQuery('#vrgpmapheight').val(graphics_properties['mapheight']);
		
		updateMapWidthSeparator();
	});

	jQuery("#vrdectcheight").click(function(){
		jQuery("#tcontainer").css("height","-=50");
		
		graphics_properties['mapheight'] -= 50;
		jQuery('#vrgpmapheight').val(graphics_properties['mapheight']);
		
		updateMapWidthSeparator();
	});	
});

// OPTIONS MODAL BOX 

jQuery.noConflict();

function showOptionsVRTModalBox(id) {

	if( isModalOpened ) return;

	document.getElementById('vrt'+id).style.zIndex = 1000;

	var deg = jQuery("#vrt"+id+"rot").attr("value");

	jQuery("#vrtomb"+id).css('transform', 'rotate(-'+deg+'deg)');
	jQuery("#vrtomb"+id).css('-ms-transform', 'rotate(-'+deg+'deg)');
	jQuery("#vrtomb"+id).css('-webkit-transform', 'rotate(-'+deg+'deg)');
	
	initOptionsModalBox(id);

	isModalOpened = true;
	
	var modal_prop = jQuery("#vrtomb"+id).css(["width","height"]);
	var table_prop = jQuery("#vrt"+id).css(["width","height"]);

	var m_w = parseFloat( modal_prop["width"].replace("px", "") );
	var m_h = parseFloat( modal_prop["height"].replace("px", "") );

	var t_w = parseFloat( table_prop["width"].replace("px", "") );
	var t_h = parseFloat( table_prop["height"].replace("px", "") );

	jQuery("#vrtomb"+id).css({left: (t_w/2 - m_w/2) + "px", top: (t_h/2 - m_h/2) + "px"});
	jQuery("#vrtomb"+id).fadeIn();
}

function closeOptionsVRTModalBox(id,save) {
	isModalOpened = false;
	if( save ) {
		applyOptionsChanges(id);
	}
	//jQuery("#vrtomb"+id).fadeOut("slow");
	jQuery("#vrtomb"+id).hide();
	document.getElementById('vrt'+id).style.zIndex = 0;
}

function applyOptionsChanges(id) {
	var name = jQuery.trim( jQuery("#vrtombname"+id).val() );
	var min_c = parseInt( jQuery("#vrtombminc"+id).val() );
	var max_c = parseInt( jQuery("#vrtombmaxc"+id).val() );
	var multi_res = jQuery("#vrtombmultires"+id).is(':checked');

	if( name.length > 0 ) {
		jQuery("#vrt"+id+"name").attr("value",name);
	}

	if( min_c <= max_c ) {
		jQuery("#vrt"+id+"seats").attr("value",min_c+"_"+max_c);
	}

	jQuery("#vrt"+id+"multires").attr("value",((multi_res)?"1":"0"));

	var titleClass = "vrttitle";
	if( name.length > 10 ) {
		titleClass = "vrttitlesmaller";
	}
	jQuery("#vrttitle"+id).text(name);
	jQuery("#vrttitle"+id).removeClass("vrttitle");
	jQuery("#vrttitle"+id).removeClass("vrttitlesmaller");
	jQuery("#vrttitle"+id).addClass(titleClass);

	
}

function createOptionsModalBox(id) {
	return '<h3>&nbsp;Options</h3>'+
	'<table><tr><td width="100" class="vrombcolumn"><?php echo JText::_('VRMANAGETABLE1');?>:</td><td><input type="text" id="vrtombname'+id+'" value="" size="10"/></td></tr><tr><td width="100" class="vrombcolumn"><?php echo JText::_('VRMANAGETABLE2');?>:</td><td><input type="number" id="vrtombminc'+id+'" value="" size="4" min="1" max="99"/></td></tr><tr><td width="100" class="vrombcolumn"><?php echo JText::_('VRMANAGETABLE3');?>:</td><td><input type="number" id="vrtombmaxc'+id+'" value="" size="4" min="1" max="99"/></td></tr><tr><td width="200" class="vrombcolumn"><?php echo JText::_('VRMANAGETABLE12');?>:</td><td><input type="checkbox" id="vrtombmultires'+id+'" value="1" /></td></tr></table>'+
	'<div class="vromodalboxbuttonsdiv">'+
	'<a class="vrocancellink" href="javascript: void(0);" onClick="closeOptionsVRTModalBox('+id+','+false+')">'+
		'<i class="fa fa-times" style="color:#900;"></i> <?php echo JText::_('VRMAPCONFIRMCANCELBUTTON'); ?>'+
	'</a>'+
	'<b>&nbsp;</b>'+
	'<a class="vroapplylink" href="javascript: void(0);" onClick="closeOptionsVRTModalBox('+id+','+ true+')">'+
		'<i class="fa fa-check" style="color:#090;"></i> <?php echo JText::_('VRMAPAPPLYBUTTON'); ?>'+
	'</a>'+
	'</div>';
}

function initOptionsModalBox(id) {
	var name  = jQuery("#vrt"+id+"name").attr("value");
	var seats = jQuery("#vrt"+id+"seats").attr("value").split("_");
	var multi_res = jQuery("#vrt"+id+"multires").attr("value");

	jQuery("#vrtombname"+id).val(''+name);
	jQuery("#vrtombminc"+id).val(''+seats[0]);
	jQuery("#vrtombmaxc"+id).val(''+seats[1]);
	jQuery("#vrtombmultires"+id).prop('checked', ((multi_res == 1) ? true : false));
}

var m_x = 0;
var m_y = 0;
var left_right;

// PROPERTIES MODAL BOX 

function showPropertiesVRTModalBox(id) {

	if( isModalOpened ) return;
	
	document.getElementById('vrt'+id).style.zIndex = 1000;
	
	initPropertiesModalBox(id);

	isModalOpened = true;
	
	recalculateModalBoxPosition(id);
	
	jQuery("#vrtpmb"+id).fadeIn();
}

function closePropertiesVRTModalBox(id,save) {
	isModalOpened = false;
	
	if( save ) {
		applyPropertiesChanges(id);
	} else {
		restoreTableProperties(id);
	}
	//jQuery("#vrtpmb"+id).fadeOut("slow");
	jQuery("#vrtpmb"+id).hide();
	document.getElementById('vrt'+id).style.zIndex = 0;
}

function applyPropertiesChanges(id) {
	jQuery( "#vrt"+id+"pos" ).attr("value", parseInt(jQuery("#vrtpmbposx"+id).val())+"_"+parseInt(jQuery("#vrtpmbposy"+id).val()));
	jQuery( "#vrt"+id+"size" ).attr("value", parseInt(jQuery("#vrtpmbsizew"+id).val())+"_"+parseInt(jQuery("#vrtpmbsizeh"+id).val()));
	jQuery( "#vrt"+id+"rot" ).attr("value", jQuery("#vrtpmbrotat"+id).val());
	jQuery( "#vrt"+id+"bgc" ).attr("value", jQuery("#vrtpmbbackg"+id).attr("value"));
}

function restoreTableProperties(id) {
	var loc  = jQuery( "#vrt"+id+"pos"  ).attr("value").split("_");
	var size = jQuery( "#vrt"+id+"size" ).attr("value").split("_");
	var deg  = jQuery( "#vrt"+id+"rot"  ).attr("value");
	var bgc  = jQuery(" #vrt"+id+"bgc"  ).attr("value");

	jQuery("#vrt"+id).css({
		left:            loc[0]+"px",
		top:             loc[1]+"px",
		width:           size[0]+"px",
		height:          size[1]+"px",
		backgroundColor: "#"+bgc,
	});
	
	tableRotationChangedDeg(id,deg);
	
}

function createPropertiesModalBox(id) {
	return '<h3>&nbsp;Properties</h3>'+
	'<table><tr><td width="100" class="vrombcolumn"><?php echo JText::_('VRMANAGETABLE5');?>:</td><td><input type="number" id="vrtpmbposx'+id+'" value="" size="4" min="0" max="9999" onChange="tablePosXChanged('+id+');"/></td><td width="100" class="vrombcolumn"><?php echo JText::_('VRMANAGETABLE6');?>:</td><td><input type="number" id="vrtpmbposy'+id+'" value="" size="4" min="0" max="9999" onChange="tablePosYChanged('+id+');"/></td></tr><tr><td width="100" class="vrombcolumn"><?php echo JText::_('VRMANAGETABLE7');?>:</td><td><input type="number" id="vrtpmbsizew'+id+'" value="" size="4" min="0" max="9999" onChange="tableWidthChanged('+id+');"/></td><td width="100" class="vrombcolumn"><?php echo JText::_('VRMANAGETABLE8');?>:</td><td><input type="number" id="vrtpmbsizeh'+id+'" value="" size="4" min="0" max="9999" onChange="tableHeightChanged('+id+');"/></td></tr><tr><td width="100" class="vrombcolumn"><?php echo JText::_('VRMANAGETABLE9');?>:</td><td><input type="number" id="vrtpmbrotat'+id+'" value="" size="4" min="0" max="3600" onChange="tableRotationChanged('+id+');"/></td></tr><tr><td width="100" class="vrombcolumn"><?php echo JText::_('VRMANAGETABLE10');?>:</td><td colspan="3"><button type="button" id="vrtpmbbgbutton'+id+'" class="btn"><i class="fa fa-eyedropper"></i> <?php echo JText::_('VRMANAGETABLE11');?></button></td><td type="hidden" id="vrtpmbbackg'+id+'" value=""></td></tr></table>'+
	'<div class="vrpmodalboxbuttonsdiv">'+
	'<a class="vrpcancellink" href="javascript: void(0);" onClick="closePropertiesVRTModalBox('+id+','+false+')">'+
		'<i class="fa fa-times" style="color:#900;"></i> <?php echo JText::_('VRMAPCONFIRMCANCELBUTTON'); ?>'+
	'</a>'+
	'<b>&nbsp;</b>'+
	'<a class="vrpapplylink" href="javascript: void(0);" onClick="closePropertiesVRTModalBox('+id+','+ true+')">'+
		'<i class="fa fa-check" style="color:#090;"></i> <?php echo JText::_('VRMAPAPPLYBUTTON'); ?>'+
	'</a>'+
	'</div>';
}

function initPropertiesModalBox(id) {
	
	var location  = jQuery("#vrt"+id+"pos").attr("value").split("_");
	var dimension = jQuery("#vrt"+id+"size").attr("value").split("_");
	var rotation = jQuery("#vrt"+id+"rot").attr("value");
	var bgcolor = jQuery("#vrt"+id+"bgc").attr("value");

	jQuery(  "#vrtpmbposx"+id ).val(  ""+location[0] );
	jQuery(  "#vrtpmbposy"+id ).val(  ""+location[1] );
	jQuery( "#vrtpmbsizew"+id ).val( ""+dimension[0] );
	jQuery( "#vrtpmbsizeh"+id ).val( ""+dimension[1] );
	jQuery( "#vrtpmbrotat"+id ).val(     ""+rotation );
	jQuery( "#vrtpmbbackg"+id ).attr("value",""+bgcolor );

	jQuery("#vrtpmbbgbutton"+id).ColorPicker({
		color: '#'+bgcolor,
		onChange: function (hsb, hex, rgb) {
			jQuery("#vrtpmbbackg"+id).attr("value",hex);
			jQuery("#vrt"+id).css('backgroundColor','#'+hex);
		}
	});
}

function tablePosXChanged(id) {
	jQuery("#vrt"+id).css('left',jQuery("#vrtpmbposx"+id).val()+"px");
}

function tablePosYChanged(id) {
	jQuery("#vrt"+id).css('top',jQuery("#vrtpmbposy"+id).val()+"px");
}

function tableWidthChanged(id) {
	jQuery("#vrt"+id).css('width',jQuery("#vrtpmbsizew"+id).val()+"px");
	recalculateModalBoxPosition(id);
}

function tableHeightChanged(id) {
	jQuery("#vrt"+id).css('height',jQuery("#vrtpmbsizeh"+id).val()+"px");
	recalculateModalBoxPosition(id);
}

function tableRotationChanged(id) {
	tableRotationChangedDeg(id,Math.abs(jQuery("#vrtpmbrotat"+id).val()));
}

function tableRotationChangedDeg(id,deg) {
	jQuery("#vrt"+id).css('transform', 'rotate('+deg+'deg)');
	jQuery("#vrt"+id).css('-ms-transform', 'rotate('+deg+'deg)');
	jQuery("#vrt"+id).css('-webkit-transform', 'rotate('+deg+'deg)');
	
	jQuery("#vrtpmb"+id).css('transform', 'rotate(-'+deg+'deg)');
	jQuery("#vrtpmb"+id).css('-ms-transform', 'rotate(-'+deg+'deg)');
	jQuery("#vrtpmb"+id).css('-webkit-transform', 'rotate(-'+deg+'deg)');

	var rad = Math.PI*parseInt(deg)/180.0;

	var size = jQuery("#vrtpmb"+id).css(["width","height"]);
	size["width"] = parseInt(size["width"].replace("px",""));
	size["height"] = parseInt(size["height"].replace("px",""));

	var x = m_x - left_right*Math.abs(Math.sin(rad))*((size["width"]-size["height"])/2);

	jQuery("#vrtpmb"+id).css('left',x+"px");
}

function recalculateModalBoxPosition(id) {
	var modal_prop = jQuery("#vrtpmb"+id).css(["width","height"]);
	var table_prop = jQuery("#vrt"+id).css(["left","width","height"]);

	m_x = 0;
	m_y = 0;
	var m_w = parseFloat( modal_prop["width"].replace("px", "") );
	var m_h = parseFloat( modal_prop["height"].replace("px", "") );

	var t_x = parseFloat( table_prop["left"].replace("px", "") );
	var t_w = parseFloat( table_prop["width"].replace("px", "") );
	var t_h = parseFloat( table_prop["height"].replace("px", "") );

	// 80 = padding left (=40px) + padding right (=40px) 
	if( t_x+t_w+10+m_w < (jQuery("#tcontainer").width()+80) ) {
		m_x = t_w+10;
		left_right = 1;
	} else {
		m_x = (-m_w-10);
		left_right = -1;
	}

	m_y = t_h/2-m_h/2;

	jQuery("#vrtpmb"+id).css({left: m_x + "px", top: m_y + "px"});

	tableRotationChanged(id);
}

function closeTable(id) {
	jQuery( "#dialog-confirm" ).dialog({
		resizable: false,
		height: 220,
		modal: true,
		buttons: {
			"<?php echo JText::_('VRMAPCONFIRMREMOVEBUTTON'); ?>": function() {
				jQuery( this ).dialog( "close" );
				removeTable(id);
			},
			"<?php echo JText::_('VRMAPCONFIRMCANCELBUTTON'); ?>": function() {
				jQuery( this ).dialog( "close" );
			}
		}
	});
}

function removeTable(idToRemove) {
	var storedId = parseInt(jQuery('#vrt'+idToRemove+'id').val());
	
	jQuery('#vrt'+idToRemove).remove();
	jQuery('#vrt'+idToRemove+'name').remove();
	jQuery('#vrt'+idToRemove+'seats').remove();
	jQuery('#vrt'+idToRemove+'multi_res').remove();
	jQuery('#vrt'+idToRemove+'pos').remove();
	jQuery('#vrt'+idToRemove+'size').remove();
	jQuery('#vrt'+idToRemove+'rot').remove();
	jQuery('#vrt'+idToRemove+'bgc').remove();
	jQuery('#vrt'+idToRemove+'id').remove();

	if(storedId != -1) {
		vrdata.append('<input type="hidden" name="tablesremoved[]" value="'+storedId+'"/>');
	}
	
	// graphics properties
	recalculateLastPosition();
	refreshFutureTable();
	
}

function actionPressedTable(action_id) {

	if( isClone ) {
		var idToClone = action_id;
		
		var name = jQuery("#vrt"+idToClone+"name").attr("value");
		var seats = jQuery("#vrt"+idToClone+"seats").attr("value").split("_");
		var multi_res = jQuery("#vrt"+idToClone+"multires").attr("value");
		var pos = jQuery("#vrt"+idToClone+"pos").attr("value").split("_");
		var size = jQuery("#vrt"+idToClone+"size").attr("value").split("_");
		var deg = jQuery("#vrt"+idToClone+"rot").attr("value");
		var bgc = jQuery("#vrt"+idToClone+"bgc").attr("value");

		pos[0] = parseInt(pos[0])+10;
		pos[1] = parseInt(pos[1])+10;
		
		var titleClass = "vrttitle";
		if( name.length > 10 ) {
			titleClass = "vrttitlesmaller";
		}
		
		cont.append(
				'<div class="vrtable" id="vrt'+idCounter+'" onClick="actionPressedTable('+idCounter+');">'+
					'<div class="vrclosebox" id="vrtcb'+idCounter+'" onClick="closeTable('+idCounter+')">X</div>'+
					'<span class="'+titleClass+'" id="vrttitle'+idCounter+'">#'+name+'</span>'+
					'<div class="vractionsdiv">'+
					'<span class="vrtoptionlink"><a href="javascript: void(0);" style="display: block;" onClick="showOptionsVRTModalBox('+idCounter+')">'+
						'<i class="fa fa-cog medium-big"></i> <?php echo JText::_('VRMAPOPTIONSBUTTON'); ?>'+
					'</a></span>'+
					'<span class="vrtproplink"><a href="javascript: void(0);" style="display: block;" onClick="showPropertiesVRTModalBox('+idCounter+')">'+
						'<i class="fa fa-briefcase medium-big"></i> <?php echo JText::_('VRMAPPROPERTIESBUTTON'); ?>'+
					'</a></span>'+
					'</div>'+
					'<div id="vrtomb'+idCounter+'"class="vrtomodalbox">'+
						createOptionsModalBox(idCounter)+
					'</div>'+
					'<div id="vrtpmb'+idCounter+'"class="vrtpmodalbox">'+
						createPropertiesModalBox(idCounter)+
					'</div>'+
				'</div>');
			
		jQuery(".vrtable").resizable({
			stop: function(event, ui) {
				jQuery("#"+jQuery(this).attr('id')+"size").attr('value', ui.size.width + '_' + ui.size.height);
				var realid = jQuery(this).attr('id').replace("vrt", "");
				jQuery("#vrtpmbsizew"+realid).val(""+ui.size.width);
				jQuery("#vrtpmbsizeh"+realid).val(""+ui.size.height);

				recalculateModalBoxPosition(realid);
			}
		});
		
		jQuery(".vrtable").draggable({
			containment: "#tcontainer",
			scroll: false,
			stop: function(event, ui) {
				jQuery("#"+jQuery(this).attr('id')+"pos").attr('value', ui.position.left + '_' + ui.position.top);
				var realid = jQuery(this).attr('id').replace("vrt", "");
				jQuery("#vrtpmbposx"+realid).val(""+ui.position.left);
				jQuery("#vrtpmbposy"+realid).val(""+ui.position.top);

				recalculateModalBoxPosition(realid);
			}
		});
	
		/* CURSOR */
	
		jQuery(".vrtable").mouseenter(function(){
			mouseEnter(jQuery(this).attr('id'));
		});
		
		jQuery(".vrtable").mouseleave(function(){
			mouseLeave();
		});

		jQuery("#vrt"+idCounter).css({
			top:pos[1]+"px",
			left:pos[0]+"px",
			width:size[0]+"px",
			height:size[1]+"px",
			backgroundColor: "#"+bgc
		});
		
		jQuery("#vrt"+idCounter).css('transform', 'rotate('+deg+'deg)');
		jQuery("#vrt"+idCounter).css('-ms-transform', 'rotate('+deg+'deg)');
		jQuery("#vrt"+idCounter).css('-webkit-transform', 'rotate('+deg+'deg)');
		 
		vrdata.append('<input type="hidden" name="vrt_pos[]" id="vrt' + idCounter + 'pos" value="' + pos[0] + '_' + pos[1] + '"/>');
		vrdata.append('<input type="hidden" name="vrt_size[]" id="vrt' + idCounter + 'size" value="' + size[0] + '_' + size[1] + '"/>');
		vrdata.append('<input type="hidden" name="vrt_name[]" id="vrt' + idCounter + 'name" value="' + name + '">');
		vrdata.append('<input type="hidden" name="vrt_seats[]" id="vrt' + idCounter + 'seats" value="'+ seats[0] + '_' + seats[1] + '"/>');
		vrdata.append('<input type="hidden" name="vrt_multires[]" id="vrt'+idCounter+'multires" value="' + multi_res + '"/>');
		vrdata.append('<input type="hidden" name="vrt_rot[]" id="vrt' + idCounter + 'rot" value="' + deg + '"/>');
		vrdata.append('<input type="hidden" name="vrt_bgc[]" id="vrt' + idCounter + 'bgc" value="' + bgc + '"/>');
		vrdata.append('<input type="hidden" name="vrt_id[]" id="vrt' + idCounter + 'id" value="-1"/>');

		disableClone();
		cont.css('cursor','auto');
		swapTableCursor(idCounter);
		
		initPropertiesModalBox(idCounter);
		
		idCounter++;

	} else if( isHorAlign ) {
		
		var pos = jQuery("#vrt"+action_id+"pos").attr("value").split("_");
		
		var tab = new Array( action_id, pos[0], pos[1] );
		
		alignObj[alignObj.length] = tab;
		
		if( alignObj.length == 1 ) {
			jQuery('#vrt'+alignObj[0][0]).addClass('vrtalignsel');
		} else if( alignObj.length == 2 ) {
			// DO ALIGNMENT
			jQuery('#vrt'+alignObj[0][0]).css('top', alignObj[1][2]+'px');
			jQuery('#vrt'+alignObj[0][0]+'pos').val( alignObj[0][1] + '_' + alignObj[1][2] );
			
			disableHorAlign();
		}
		
	} else if( isVerAlign ) {
		
		var pos = jQuery("#vrt"+action_id+"pos").attr("value").split("_");
		
		var tab = new Array( action_id, pos[0], pos[1] );
		
		alignObj[alignObj.length] = tab;
		
		if( alignObj.length == 1 ) {
			jQuery('#vrt'+alignObj[0][0]).addClass('vrtalignsel');
		} else if( alignObj.length == 2 ) {
			// DO ALIGNMENT
			jQuery('#vrt'+alignObj[0][0]).css('left', alignObj[1][1]+'px');
			jQuery('#vrt'+alignObj[0][0]+'pos').val( alignObj[1][1] + '_' + alignObj[0][2] );
			
			disableVerAlign();
		}
		
	}
}

function swapTableCursor(id) {
	var crs = "move";
	if( isClone || isHorAlign || isVerAlign ) {
		crs = "crosshair";
	}
	jQuery("#vrt"+id).css('cursor',crs);
}

function enableClone() {
	isClone = true;
	// activate div tooltip 
	jQuery('#vractiontooltip').text('<?php echo JText::_('VRMAPCLONETABLETOOLTIP'); ?>');
	jQuery('#vractiontooltip').show();

	jQuery('#vrclonetable').addClass('active');
	
	disableHorAlign();
	disableVerAlign();
}

function disableClone() {
	isClone = false;
	// deactivate div tooltip 
	if( !isHorAlign && !isVerAlign ) {
		jQuery('#vractiontooltip').hide();
	}

	jQuery('#vrclonetable').removeClass('active');
}

function enableHorAlign() {
	isHorAlign = true;
	alignObj = new Array();
	
	// activate div tooltip 
	jQuery('#vractiontooltip').text('<?php echo JText::_('VRMAPHALIGNTABLETOOLTIP'); ?>');
	jQuery('#vractiontooltip').show();

	jQuery('#vrhaligntable').addClass('active');
	
	disableClone();
	disableVerAlign();
}

function disableHorAlign() {
	isHorAlign = false;
	jQuery('.vrtalignsel').removeClass('vrtalignsel');
	
	// deactivate div tooltip 
	if( !isClone && !isVerAlign ) {
		jQuery('#vractiontooltip').hide();
	}

	jQuery('#vrhaligntable').removeClass('active');
}

function enableVerAlign() {
	isVerAlign = true;
	alignObj = new Array();
	
	// activate div tooltip 
	jQuery('#vractiontooltip').text('<?php echo JText::_('VRMAPVALIGNTABLETOOLTIP'); ?>');
	jQuery('#vractiontooltip').show();

	jQuery('#vrvaligntable').addClass('active');
	
	disableClone();
	disableHorAlign();
}

function disableVerAlign() {
	isVerAlign = false;
	jQuery('.vrtalignsel').removeClass('vrtalignsel');
	
	// deactivate div tooltip 
	if( !isClone && !isHorAlign ) {
		jQuery('#vractiontooltip').hide();
	}

	jQuery('#vrvaligntable').removeClass('active');
}

function mouseEnter(id) {
	var crs = "move";
	if( isClone || isHorAlign || isVerAlign ) {
		crs = "crosshair";
	} 
	
	if( isHorAlign ) {
		displayGhost(0,id);
	} else if( isVerAlign ) {
		displayGhost(1,id);
	}
	
	jQuery(this).css('cursor',crs);
}

function mouseLeave() {
	jQuery('.vrtghost').remove();
}

function displayGhost(type, id) {
	if( alignObj.length == 1 ) {
		cont.append('<div class="vrtghost"></div>');
		
		var pos = jQuery("#"+id+"pos").attr("value").split("_");
		
		var size = jQuery("#vrt"+alignObj[0][0]+"size").attr("value").split("_");
		var deg = jQuery("#vrt"+alignObj[0][0]+"rot").attr("value");
		
		var ghost_loc = new Array();
		
		if( type == 0 ) {
			// horizontal
			ghost_loc[0] = alignObj[0][1];
			ghost_loc[1] = pos[1];
		} else {
			// vertical
			ghost_loc[0] = pos[0];
			ghost_loc[1] = alignObj[0][2];
		}
		
		jQuery('.vrtghost').css({
			top:ghost_loc[1]+"px",
			left:ghost_loc[0]+"px",
			width:size[0]+"px",
			height:size[1]+"px",
		});
		
		jQuery(".vrtghost").css('transform', 'rotate('+deg+'deg)');
		jQuery(".vrtghost").css('-ms-transform', 'rotate('+deg+'deg)');
		jQuery(".vrtghost").css('-webkit-transform', 'rotate('+deg+'deg)');
	}
}

/**
 * calculates and returns the rectangle (coordinates and size) of the future table
 * 
 * return array(x, y, w, h)
 */
function getNextTablePosition() {
	var width = Math.max( graphics_properties["minwidth"], (graphics_properties["people"]-2)/2*graphics_properties["wpp"] );
	var height = Math.max( graphics_properties["minheight"], graphics_properties["hpp"] );
				
	//var left = contOffset.left+11;
	var left = last_table_position["x"];
	//var top = contOffset.top+11;
	var top = last_table_position["y"];
	
	if( left == 0 && top == 0 ) {
		return new Array(graphics_properties["start_x"], graphics_properties["start_y"], width, height);
	}
	
	if( left+graphics_properties["hor_spacing"]+width*2 > graphics_properties["mapwidth"] ) {
		if( last_table_position["x"] != 0 ) {
			top += height+graphics_properties["ver_spacing"];
		}
		left = graphics_properties["start_x"];
	} else {
		left +=  width+graphics_properties["hor_spacing"];
	}
	
	return new Array(left, top, width, height);
}

/**
 * future table
 */

function createFutureTable() {
	return '<div class="vrfuturetable" id="vrfuturetable"></div>';
}

function refreshFutureTable() {
	if( graphics_properties['display_next'] ) {
		var rect = getNextTablePosition();
		
		var offset = cont.offset();
		
		jQuery("#vrfuturetable").css({
			left: (offset.left+11+rect[0])+"px",
			top:  (offset.top+11+rect[1])+"px",
			width: rect[2]+"px",
			height: rect[3]+"px"
		});
	}
}

/**
 * GRAPHICS PROPERTIES MODAL
 */

var old_table_prefix = '';

function openGraphicsPropertiesModal() {
	jQuery(".vrgrpropmodal").fadeIn();
}

function closeGraphicsPropertiesModal(save) {
	jQuery(".vrgrpropmodal").hide();
	
	old_table_prefix = graphics_properties['prefix'];
	
	if( save ) {
		jQuery.each(graphics_properties, function(key, val) {
			if( gp_input[key]['input'] == 'number' ) {
				graphics_properties[key] = parseInt(jQuery("#vrgp"+key).val());
			} else if( gp_input[key]['input'] == 'text' ) {
				graphics_properties[key] = jQuery("#vrgp"+key).val();
			} else if( gp_input[key]['input'] == 'checkbox' ) { 
				graphics_properties[key] = (jQuery("#vrgp"+key).is(":checked") ? 1 : 0);
			}
		});
		
		applyGraphicsProperties();
		storeGraphicsProperties();
		
		if( jQuery("#vrgpapplyallbox").is(":checked")) {
			applyAllGraphicsProperties();
		}
	} else {
		jQuery.each(graphics_properties, function(key, val) {
			if( gp_input[key]['input'] == 'number' || gp_input[key]['input'] == 'text' ) {
				jQuery("#vrgp"+key).val(val);
			} else if( gp_input[key]['input'] == 'checkbox' ) { 
				jQuery("#vrgp"+key).prop("checked", (val ? true : false));
			}
		});
	}
	
	jQuery("#vrgpapplyallbox").prop("checked", false);
	
}

function restoreGraphicsPropertiesModal() {
	jQuery.each(graphics_properties, function(key, val) {
		if( gp_input[key]['input'] == 'number' || gp_input[key]['input'] == 'text' ) {
			jQuery("#vrgp"+key).val(gp_input[key]['default']);
		} else if( gp_input[key]['input'] == 'checkbox' ) { 
			jQuery("#vrgp"+key).prop("checked", (gp_input[key]['default'] ? true : false));
		}
	});
}

function storeGraphicsProperties() {
	
	jQuery.noConflict();
		
	var jqxhr = jQuery.ajax({
		type: "POST",
		url: "index.php",
		data: { 
			option: "com_cleverdine", 
			task: "store_graphics_properties", 
			id_room: <?php echo $room["id"]; ?>,
			prefix:         graphics_properties["prefix"],
			people:         graphics_properties["people"],
			start_x:        graphics_properties["start_x"],
			start_y:        graphics_properties["start_y"],
			minwidth:       graphics_properties["minwidth"],
			minheight:      graphics_properties["minheight"],
			wpp:            graphics_properties["wpp"],
			hpp:            graphics_properties["hpp"],
			hor_spacing:    graphics_properties["hor_spacing"],
			ver_spacing:    graphics_properties["ver_spacing"],
			color:          graphics_properties["color"],
			mapwidth:       graphics_properties["mapwidth"],
			mapheight:      graphics_properties["mapheight"],
			display_next:   graphics_properties["display_next"],
			tmpl: "component" }
	}).done(function(){ 
		
	}).fail(function(){
		
	});
	
}

function applyGraphicsProperties() {
	if( graphics_properties["display_next"] ) {
		jQuery("#vrfuturetable").show();
		 
		refreshFutureTable();
	} else {
		jQuery("#vrfuturetable").hide();
	}
	
	cont.css('height', graphics_properties['mapheight']+"px");
	updateMapWidthSeparator();
}

function recalculateLastPosition() {
	last_table_position["x"] = 0;
	last_table_position["y"] = 0;
	jQuery(".vrtable").each(function(){
		var pos = jQuery(this).position();
		pos.left -= (cont.offset().left+11);
		pos.top -= (cont.offset().top+11);
		if( pos.top > last_table_position["y"] || ( pos.top == last_table_position["y"] && pos.left > last_table_position["x"] ) ) {
			last_table_position["x"] = pos.left;
			last_table_position["y"] = pos.top;
		}
	});
}

function updateMapWidthSeparator() {
	jQuery(".vrcontwseparator").css({
		left: (cont.offset().left+11+graphics_properties['mapwidth'])+"px",
		height: graphics_properties['mapheight']+"px", 
	});
}

function applyAllGraphicsProperties() {
	last_table_position["x"] = 0;
	last_table_position["y"] = 0;
	var rect;
	var contOffset = cont.offset();
	jQuery(".vrtable").each(function(){
		rect = getNextTablePosition();
		// apply offset to the table location
		var left = rect[0]+contOffset.left+11;
		var top = rect[1]+contOffset.top+11;

		jQuery(this).css({
			left:     left+"px",
			top:      top+"px",
			width:    rect[2]+"px",
			height:   rect[3]+"px",
			backgroundColor: graphics_properties["color"]
		});
		
		// check bgc of table
		var rgb = jQuery(this).css('backgroundColor');
		var bgc = rgb2hex(rgb);
		
		jQuery("#"+this.id+"pos").val(left+"_"+top);
		jQuery("#"+this.id+"size").val(rect[2]+"_"+rect[3]);
		jQuery("#"+this.id+"bgc").val(bgc);
		
		// update color from colopicker
		jQuery("#vrtpmbbgbutton"+this.id.split("vrt")[1]).ColorPickerSetColor(bgc);
		
		// update last table position
		last_table_position["x"] = rect[0];
		last_table_position["y"] = rect[1];
		
		// title
		var title_id = "#vrttitle"+this.id.split("vrt")[1];
		var html = jQuery(title_id).html().replace(old_table_prefix, '');
		jQuery("#"+this.id+"name").val(graphics_properties['prefix']+html);
		jQuery(title_id).html(graphics_properties['prefix']+html);
	});
	
	// refresh future table
	refreshFutureTable();
}

</script>

<div id="dialog-confirm" title="<?php echo JText::_('VRMAPCONFIRMREMOVETABLETITLE');?>" style="display: none;">
	  <p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span><?php echo JText::_('VRMAPCONFIRMREMOVETABLETEXT'); ?></p>
</div>