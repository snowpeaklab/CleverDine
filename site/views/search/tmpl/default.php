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

$args = $this->args;
$rows = $this->rows;
$attempt = $this->attempt;
$bef_h = $this->befHints;
$aft_h = $this->aftHints;
$selected_room = $this->lastSelectedRoom;
$tables = $this->allRoomTables;
$shared_occurrency = $this->allSharedTablesOccurrency;
$all_rooms = $this->allRooms;

$roomHidden = 'style="display: none;"';
$mapHidden = 'display: none;';
$js_step = 0;

$resrequirements = (int)cleverdine::getReservationRequirements();

$availableRooms = array();
$tablePerRoom = array();

$room_occurrency = array();

for( $i = 0, $n = count( $rows ); $i < $n; $i++ ) {
	if( empty( $room_occurrency[ $rows[$i]['rid'] ] ) ) {
		$room_occurrency[ $rows[$i]['rid'] ] = 1;
		$availableRooms[count($availableRooms)] = array( $rows[$i]['rid'], $rows[$i]['rname'] );
		$tablePerRoom[count($tablePerRoom)] = array( $rows[$i]['rid'], $rows[$i]['tid'] );
	}
}

if( $selected_room == -1 && count( $availableRooms ) > 0 ) {
	$selected_room = $availableRooms[0][0];
} else if( count( $availableRooms ) > 0 ) {
	$roomHidden = "";
	if( $resrequirements == 0 ) {
		$mapHidden = "";
	}
	$js_step = 1;
}

$select_room = '<select class="vre-tinyselect" id="vrroomselect" name="room" onChange="roomSelectionChanged()">';
foreach( $availableRooms as $room ) {
	$select_room .= '<option value="'.$room[0].'" '.(($room[0] == $selected_room) ? 'selected="selected"' : '').' >'.$room[1].'</option>'; 
}
$select_room .= '</select>';

// SETTING AVAILABLE TABLES
for( $i = 0, $n = count($tables); $i < $n; $i++ ) {
	$found = 0;
	for( $j = 0, $m = count($rows); $j < $m && $found == 0; $j++ ) {
		$found = ( ( $rows[$j]['tid'] == $tables[$i]['id'] ) ? 1 : 0 );
	}
	
	$tables[$i]['available'] = $found;
}
// END SET 

$vr_session_data = array(
	'ARGS' => $args,
	'ROWS' => $rows,
	'ATTEMPT' => $attempt,
	'BEFORE_HINTS' => $bef_h,
	'AFTER_HINTS' => $aft_h
);

$session = JFactory::getSession();
$session->set('vr_session_data', $vr_session_data);

$ts = cleverdine::createTimestamp($args['date'], $args['hour'], $args['min']);

for( $i = 0, $n = count($tables); $i < $n; $i++ ) {
	$occurrency = 0;
	for( $j = 0, $m = count($shared_occurrency); $j < $m; $j++ ) {
		if( $shared_occurrency[$j]['id'] == $tables[$i]['id'] )	{
			$occurrency = $shared_occurrency[$j]['curr_capacity'];
			break;
		}
	}
	
	$tables[$i]['occurrency'] = $occurrency;
}

$tableSelected = "''";

$js_tablePerRoom = array();

if( $attempt != 3 ) {
	if( $resrequirements == 1 ) {
		$tableSelected = $tablePerRoom[0][1];
	} else if( $resrequirements == 2 ) {
		$tableSelected = $rows[0]['tid'];
	} 
	$js_tablePerRoom = json_encode($tablePerRoom);
}

$shared_table_found = ( count( $rows ) > 0 && $rows[count($rows)-1]['multi_res'] == 1 );

$room_key = $selected_room;
if( $room_key == -1 && count($availableRooms) > 0 ) {
	$room_key = $availableRooms[0]['id'];
}

/*$room_description = "";
$found = 0;
for( $i = 0; $i < count( $all_rooms ) && $found == 0; $i++ ) {
	if( $all_rooms[$i]['id'] == $room_key ) {
		$room_description = $all_rooms[$i]['description'];
		$found = 1;
	}
}*/

$all_rooms_desc = array();
foreach( $all_rooms as $rm ) {
	if( empty( $all_rooms_desc[$rm['id']] ) ) {
		$all_rooms_desc[$rm['id']] = $rm['description'];
	}
}

$selected_room_image = '';
for( $i = 0; $i < count($all_rooms) && strlen($selected_room_image) == 0; $i++ ) {
	if( $all_rooms[$i]['id'] == $selected_room ) {
		$selected_room_image = $all_rooms[$i]['image'];
	}
}

?>

<script>
	var tableSelected = <?php echo $tableSelected ?>;
	var tablePerRoom = <?php echo $js_tablePerRoom; ?>;

	var res_req = <?php echo $resrequirements; ?>;
	var step = <?php echo $js_step; ?>;
	if( res_req == 2 ) {
		step = 1;
	}
	
	var map_width = 0;
	var map_height = 0;
</script>

<div class="vrstepbardiv">

	<div class="vrstepactive">
		<div class="vrstep-inner">
			<a href="<?php echo JRoute::_('index.php?option=com_cleverdine&view=restaurants'); ?>">
				<span class="vrsteptitle"><?php echo JText::_('VRSTEPONETITLE'); ?></span>
				<span class="vrstepsubtitle"><?php echo JText::_('VRSTEPONESUBTITLE'); ?></span>
			</a>
		</div>
	</div>
	
	<div class="vrstepactive step-current">
		<div class="vrstep-inner">
			<span class="vrsteptitle"><?php echo JText::_('VRSTEPTWOTITLE'); ?></span>
			<span class="vrstepsubtitle">
				<?php echo ($resrequirements == 0 ? JText::_('VRSTEPTWOSUBTITLEZERO') : ($resrequirements == 1 ? JText::_('VRSTEPTWOSUBTITLEONE') : JText::_('VRSTEPTWOSUBTITLETWO'))); ?>
			</span>
		</div>
	</div>
	
	<div class="vrstep">
		<div class="vrstep-inner">
			<span class="vrsteptitle"><?php echo JText::_('VRSTEPTHREETITLE'); ?></span>
			<span class="vrstepsubtitle"><?php echo JText::_('VRSTEPTHREESUBTITLE'); ?></span>
		</div>
	</div>

</div>

<div class="vrreservationform" id="vrresultform">
<form action="<?php echo JRoute::_('index.php?option=com_cleverdine&task=search'); ?>"  id="vrresform" name="vrresform" method="GET">

	<?php 
	/**
	 * ATTEMPT #1
	 * One or more tables with no multiple reservations are found.
	 * Book the table.
	 * 
	 * ATTEMPT #2
	 * One or more table with multiple reservations are found.
	 * Shows modal box to confirm the table booking.
	 * If confirm.YES: book the table.
	 * If confirm.NO: redirect to the same page and elaborate hints.
	 * 
	 * ATTEMPT #3
	 * Noone empty table.
	 * Shows hints of free table in the next hours. 
	 */
	?>
	
	<div class="vrresultsummarydiv">
		<div class="vrresultsuminnerdiv" id="vrresultsumdivdate">
			<span class="vrresultsumlabelsp" id="vrresultsumspanlbldate"><?php echo JText::_('VRDATE'); ?></span>
			<span class="vrresultsumvaluesp" id="vrresultsumspanvaldate"><?php echo date(cleverdine::getDateFormat(), $ts); ?></span>
		</div>
		
		<div class="vrresultsuminnerdiv" id="vrresultsumdivhour">
			<span class="vrresultsumlabelsp" id="vrresultsumspanlblhour"><?php echo JText::_('VRTIME'); ?></span>
			<span class="vrresultsumvaluesp" id="vrresultsumspanvalhour"><?php echo date(cleverdine::getTimeFormat(), $ts); ?></span>
		</div>
		
		<div class="vrresultsuminnerdiv" id="vrresultsumdivpeople">
			<span class="vrresultsumlabelsp" id="vrresultsumspanlblpeople"><?php echo JText::_('VRPEOPLE'); ?></span>
			<span class="vrresultsumvaluesp" id="vrresultsumspanvalpeople"><?php echo $args["people"]; ?></span>
		</div>
	</div>
	
	<?php //if( $attempt != 1 ) {
		if( $attempt == 3 ) {
		
		?>
		<div class="vrresultbookdiv vrfault">
			<span><?php echo JText::_('VRRESNOSINGTABLEFOUND'); ?></span>
		</div>
		<?php 
		
		$_h = array( $bef_h[0], $bef_h[1], $ts, $aft_h[1], $aft_h[0] );	
		
		$curr_time = explode( ':', date('H:i', time()) );
		$curr_time = cleverdine::createTimestamp( $args['date'], $curr_time[0], $curr_time[1] );
		$curr_day = date( cleverdine::getDateFormat(), time() );
		// EXCLUDE HINTS BEFORE CURRENT TIME
		if( $curr_day == $args['date'] ) {
			for( $i = 0; $i < count($_h); $i++ ) {
				if( $_h[$i] < $curr_time ) {
					$_h[$i] = -1;
				}
			}
		}
		// END
		
		$_ok_count = 0;
		foreach( $_h as $v ) {
			if( $v != -1 ) {
				$_ok_count++;
			}
		}
		
		?><div class="vrhintsouterdiv"><?php
		if( $_ok_count > 1 ) {
			?>
			<div class="vrresultbooktrydiv">
			<?php echo JText::_('VRRESTRYHINTS'); ?>
			</div>
			<div class="vrresulttruehintsdiv">
			<?php 
			for( $i = 0, $n = count($_h); $i < $n; $i++ ) {
				if( $_h[$i] != -1 ) {
					?> 
					<?php if( $_h[$i] != $ts ) { ?>
						<div class="vrresulthintsdiv">
							<a class="vrresulthintsbutton" href="<?php echo JRoute::_('index.php?option=com_cleverdine&task=search&hourmin='.date('H:i', $_h[$i]).'&date='.$args["date"].'&people='.$args["people"] ); ?>" id="vrtry<?php echo $i; ?>"><?php echo date(cleverdine::getTimeFormat(), $_h[$i]); ?></a>
						</div>
					<?php } else { ?>
						<div class="vrresultdisabledhintsdiv">
							<span class="vrresulthintsdisabledbutton" id="vrtry<?php echo $i; ?>"><?php echo date(cleverdine::getTimeFormat(), $ts)?></span>
						</div>
					<?php } ?>
					<?php
				}
			}
			?>
			</div>
			<?php 
		} else {
			?>
			<div class="vrresultfalsehintdiv">
				<p><a href="<?php echo JRoute::_('index.php?option=com_cleverdine&view=restaurants'); ?>"><?php echo JText::_('VRRESNOTABLESSELECTNEWDATES'); ?></a></p>
			</div>
			<?php
		}
		?></div><?php
		
	} 
	
	?><div id="vrbookingborderdiv" class="vrbookingouterdiv"><?php
	
	/*if( $attempt == 2 ) { 
	?>
		<div class="vrresultbookotherwisediv">
			<?php echo JText::sprintf('VRRESMULTITABLEFOUND', date( cleverdine::getTimeFormat(), $ts ) ); ?>
		</div>
		
		<div class="vrseparatordiv"></div>
	<?php
	}*/
	
	//if( $attempt == 1 && $js_step == 0 ) {
	if( $attempt != 3 && $js_step == 0 ) {
		
		$_text = sprintf( JText::_('VRSUCCESSMESSSEARCH'), $args['people'] );
		if( $resrequirements == 0 ) {
			$_text .= ' ' . JText::_('VRMESSNOWCHOOSETABLE');
		} else if( $resrequirements == 1 ) {
			$_text .= ' ' . JText::_('VRMESSNOWCHOOSEROOM');
		}

		?>
		<div class="vrresultbookdiv vrsuccess" id="vrsearchsuccessdiv">
			<span><?php echo $_text; ?></span>
		</div>
		<?php 
	}
	
	?>
	
	<div id="vrchooseroomouterdiv" <?php echo $roomHidden; ?>>
		<span id="vrchooseroomsp"><?php echo JText::_('VRCHOOSEROOM'); ?></span>
		<div id="vrchooseroomdiv" class="vre-tinyselect-wrapper">
			<?php echo $select_room; ?>
		</div>
	</div>
	
	<?php if( $room_key != -1 && strlen( trim( strip_tags( $all_rooms_desc[$room_key] ) ) ) > 0 ) { ?>
		<div id="vrroomdescriptionactiondiv" <?php echo $roomHidden; ?>>
			<a id="vrroomdescriptionactionlink" onClick="changeRoomDescriptionDisplay();"><?php echo JText::_('VRSHOWDESCRIPTION'); ?></a>
		</div>
		<div id="vrroomdescriptiondiv" style="display: none;">
			<?php echo $all_rooms_desc[$room_key]; ?>
		</div>
	<?php } ?>
	
	<?php 
		if( $shared_table_found == 1 && $resrequirements == 0 ) { // shows share legend
			?><div id="vrlegendsharedtablediv" <?php echo $roomHidden; ?>><?php echo JText::_('VRLEGENDSHAREDTABLE'); ?></div><?php
		}
	?>
	
	<div id="vrtscrollablediv">
		<div id="vrtcouterdiv">
			<div id="tcontainer" style="<?php echo $mapHidden; ?> <?php echo (strlen($selected_room_image) ? 'background-image:url('.JUri::root().'components/com_cleverdine/assets/media/'.$selected_room_image.');' : ''); ?>">
				<?php 
					for( $i = 0, $n = count($tables); $i < $n; $i++ ) {
		
						$prop = json_decode($tables[$i]["design_data"], true);
						
					?><script>
						
						var tableAvailable = <?php echo $tables[$i]['available']; ?>;
						var cursor = 'pointer';
						if( tableAvailable == 0 ) {
							cursor = 'default';
						}
						
						</script>
						
						<div class="vrtable" onClick="selectTable(<?php echo $tables[$i]['id']; ?>,'<?php echo $tables[$i]['name']; ?>',<?php echo $tables[$i]['available']; ?>);" id="vrt<?php echo $tables[$i]["id"]; ?>">
							<span id="vrttitle<?php echo $tables[$i]["id"]; ?>"><?php echo $tables[$i]["name"]; ?></span>
							<?php if( $tables[$i]['multi_res'] == 1 ) { ?>
								<div class="vrtoccurrency"><?php echo $tables[$i]['occurrency']; ?></div>
							<?php } ?>
						</div>
		
						<script>
						var x = <?php echo $prop["pos"]["left"]; ?>;
						var y = <?php echo $prop["pos"]["top"]; ?>;
						var w = <?php echo $prop["size"]["width"]; ?>;
						var h = <?php echo $prop["size"]["height"]; ?>;
						var rot = <?php echo $prop["rotation"]; ?>;
						var bgc = "<?php echo $prop["bgcolor"]; ?>";
		
						jQuery("#vrt"+<?php echo $tables[$i]["id"]; ?>).css({
							marginTop:y+"px",
							marginLeft:x+"px",
							width:w,
							height:h,
							backgroundColor:"#"+bgc,
							cursor:cursor
						});
	
						jQuery("#vrt<?php echo $tables[$i]["id"]; ?>").css('transform', 'rotate('+rot+'deg)');
						jQuery("#vrt<?php echo $tables[$i]["id"]; ?>").css('-ms-transform', 'rotate('+rot+'deg)');
						jQuery("#vrt<?php echo $tables[$i]["id"]; ?>").css('-webkit-transform', 'rotate('+rot+'deg)');

						if( tableAvailable == 0 ) {
							jQuery("#vrt<?php echo $tables[$i]["id"]; ?>").addClass('vrtopacity');
							jQuery("#vrt<?php echo $tables[$i]["id"]; ?>").attr('title','<?php echo JText::_('VRTNOTAVAILABLE');?>');
						}

						if( x+w > map_width ) {
							map_width = x+w;
						}

						if( y+h > map_height ) {
							map_height = y+h;
						}
			
					</script>
					<?php } ?>
			</div>
		</div>
	</div>
	
	<?php if( $attempt != 3 ) { ?>
		<div class="vryourtablediv">
			<span id="vrbooknoselsp" style="display: none;"></span>
			<span id="vrbooktabselsp" style="display: none;"></span>
		</div>
	<?php } ?>
	
	<?php if( count($this->menusList) > 0 && $attempt != 3 ) { 
		$image_path = 'components'.DIRECTORY_SEPARATOR.'com_cleverdine'.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR; 

		$desc_maximum_length = 180;
		
		$checked = false;
		?>
		
		<div class="vrsearchmenucont" style="display: none;">
			<div class="vrsearchmenutitle"><?php echo JText::_('VRSEARCHCHOOSEMENU'); ?></div>
			<div class="vrsearchmenulist">
				<?php foreach( $this->menusList as $m ) { 
					$m['name'] = cleverdine::translate($m['id'], $m, $this->translatedMenus, 'name', 'name');
					
					if( empty($m['image']) || !file_exists(JPATH_SITE.DIRECTORY_SEPARATOR.$image_path.$m['image']) ) {
						$m['image'] = 'menu_default_icon.jpg';   
					}
					
					$url = JRoute::_('index.php?option=com_cleverdine&view=menudetails&id='.$m['id'], false);
					?>
					<div class="vrsearchmenudetails">
						<div class="vrsearchmenuinnerdetails">
							<div class="vrsearchmenuimage">
								<a href="<?php echo $url; ?>" target="_blank">
									<img src="<?php echo JUri::root().'components/com_cleverdine/assets/media/'.$m['image']; ?>" />
								</a>
							</div>
							<div class="vrsearchmenuname">
								<span><?php echo $m['name']; ?></span>
							</div>
							<div class="vrsearchmenufoot">
								<span class="vrsearchmenufootleft" id="vrmenufootquant<?php echo $m['id']; ?>">0</span>
								<span class="vrsearchmenufootright">
									<a href="javascript: void(0);" class="vrsearchmenuaddlink" onClick="vrAddMenuButton(<?php echo $m['id']; ?>);" id="vrmenuaddlink<?php echo $m['id']; ?>"></a>
									<a href="javascript: void(0);" class="vrsearchmenudellink vrsearchlinkdisabled" onClick="vrRemoveMenuButton(<?php echo $m['id']; ?>);" id="vrmenudellink<?php echo $m['id']; ?>"></a>
								</span>
							</div>
						</div>
					</div>
				<?php 
					$checked = true;
				} ?>
			</div>
		</div>
		
		<div class="vryourmenusdiv">
			<span id="vrbookmenuselsp" style="display: none;"><?php echo JText::sprintf('VRSEARCHCHOOSEMENUSTATUS', '0/'.$args['people']); ?></span>
		</div>
		
	<?php } ?>
	
	<?php 
	if( $attempt != 3 ) {

		$text_continue_button_key = 'VRCONTINUEBUTTON'.$resrequirements;
		$class_continue_button = 'vrresultbookbuttonfind';
		if( $js_step == 1 ) {
			$text_continue_button_key = 'VRCONTINUE';
			$class_continue_button = 'vrresultbookbuttoncontinue';
		} else if( $resrequirements == 0 && $attempt == 2 ) {
			$text_continue_button_key = 'VRCONTINUEBUTTONMULTI0';
		}
		?>
		
		<div class="vrbookcontinuebuttoncont">
			<button type="button" id="vrbookcontinuebutton" class="<?php echo $class_continue_button; ?>" onClick="continueBooking(<?php echo $rows[0]['tid']; ?>);">
				<?php echo JText::_($text_continue_button_key); ?>
			</button>
		</div>
	<?php
	}
	?>
	<input type="hidden" id="isRoomChanged" name="isRoomChanged" value="0"/>
	
	<input type="hidden" value="com_cleverdine" name="option"/>
	<input type="hidden" value="search" name="task"/>
	
	</div>
	
</form>
</div>

<?php 
if( $attempt != 3 ) {
?>
<form action="<?php echo JRoute::_('index.php?option=com_cleverdine&task=confirmres'); ?>"  id="vrconfirmform" name="vrconfirmform" method="POST">
				
	<input type="hidden" value="<?php echo $args['date']; ?>" id="vrcalendar" name="date" />
	<input type="hidden" value="<?php echo $args['hourmin']; ?>" id="vrhourmin" name="hourmin" />
	<input type="hidden" value="<?php echo $args['people']; ?>" id="vrpeople" name="people" />
	<input type="hidden" value="" id="vrinputtable" name="table" />
		
	<input type="hidden" value="com_cleverdine" name="option"/>
	<input type="hidden" value="confirmres" name="task" id="idtask"/>
	
</form>
<?php
}
?>

<script>

	var allRoomDesc = <?php echo json_encode($all_rooms_desc); ?>;
	
	var menusCount = <?php echo count($this->menusList); ?>;

	function continueBooking(id_table) {
		jQuery(function(){
			if( step == 0 ) {
				jQuery('#vrchooseroomouterdiv').fadeIn('normal');
				jQuery('#vrroomdescriptionactiondiv').fadeIn('normal');
				jQuery('#vrlegendsharedtablediv').fadeIn('normal');
				jQuery('#vrsearchsuccessdiv').fadeOut('normal');
			
				if( res_req == 0 ) {
					jQuery('#vrtcouterdiv').addClass('vrtcouterdivborder');
					jQuery('#tcontainer').fadeIn('normal');
					jQuery('html,body').animate( {scrollTop: (jQuery('#vrchooseroomdiv').offset().top-5)}, {duration:'slow'} );
				}

				jQuery('#vrbookcontinuebutton').html('<?php echo addslashes(JText::_('VRCONTINUE')); ?>');
				jQuery('#vrbookcontinuebutton').addClass('vrresultbookbuttoncontinue');
				jQuery('#vrbookcontinuebutton').removeClass('vrresultbookbuttonfind');

				jQuery('#vrbookingborderdiv').removeClass('vrbookingborderdiv');
				
				step++;
			} else if( step == 1 ) {
				if( tableSelected != '' ) { 
					jQuery('#vrinputtable').val(tableSelected);
					if( menusCount == 0 ) {
					   jQuery('#vrconfirmform').submit();
					} else {
						jQuery('.vrsearchmenucont, #vrbookmenuselsp').fadeIn();
						jQuery('html,body').animate( {scrollTop: (jQuery('.vrsearchmenutitle').offset().top-50)}, {duration:'slow'} );
						step++;
					}
				} else {
					jQuery('#vrbooknoselsp').text('<?php echo addslashes(JText::_('VRERRCHOOSETABLEFIRST')); ?>');
					jQuery('#vrbooknoselsp').fadeIn('normal').delay(2000).fadeOut('normal');
				}
			} else if( step == 2 ) {
				if( total_menus_selected == total_people ) {
					jQuery('#vrconfirmform').submit();
				} else {
					jQuery('#vrbookmenuselsp').addClass('vrbookmenunopeople').delay(2000).queue(function(next){
						jQuery(this).removeClass('vrbookmenunopeople');
						next();
					});
				}
			}
		});
	}
	
	function tryHints(button_id) {
		jQuery(function(){
			var hourmin = jQuery('#vrtry'+button_id).val();
			jQuery('#vrhourmin').val(hourmin);
		});

		document.getElementById('vrtryform').submit();
	}

	function roomSelectionChanged() {
		if(res_req == 0 ) {
			jQuery('#isRoomChanged').val("1");
			document.vrresform.submit();
		} else {
			var roomid = jQuery('#vrroomselect').val();
			for( var i = 0; i < tablePerRoom.length; i++ ) {
				if( tablePerRoom[i][0] == roomid ) {
					tableSelected = tablePerRoom[i][1];
					break;
				}
			}
			
			jQuery('#vrroomdescriptiondiv').html(allRoomDesc[roomid]);
		}
	}

	jQuery( document ).ready(function() {
		map_width += 20;
		map_height += 20;
		
		var _w = map_width;
		var _tc_w = parseInt( jQuery('#vrtcouterdiv').css('width').replace('px','') );
		
		if( _w < _tc_w ) {
			_w = _tc_w;
		} 
		
		jQuery('#tcontainer').css( {width: _w+'px', height: map_height+'px' } );
		jQuery('#vrtcouterdiv').scroll(function(){
			var offset = jQuery('#vrtcouterdiv').scrollLeft();
			
			if( map_width+offset < _tc_w ) {
				map_width = _tc_w-offset;
			}
			
			jQuery('#tcontainer').css('margin-left', '-'+offset*2+'px' );
			jQuery('#tcontainer').css('width', (map_width+offset)+'px' );
		});

		if( step == 0 ) {
			jQuery('#vrbookingborderdiv').addClass('vrbookingborderdiv');
		}

		if( res_req == 0 && step == 1 ) {
			jQuery('#vrtcouterdiv').addClass('vrtcouterdivborder');
			jQuery('html,body').animate( {scrollTop: (jQuery('#vrchooseroomdiv').offset().top-5)}, {duration:'slow'} );
		}
	});

	function selectTable(id, tableName, tableAvailable) {
		if( tableAvailable == 1 ) {
			tableSelected = id;
	
			jQuery('.vrtable').addClass('vrtunselected');
			jQuery('.vrtable').removeClass('vrtselected');
			jQuery('#vrt'+tableSelected).removeClass('vrtunselected');
			jQuery('#vrt'+tableSelected).addClass('vrtselected');
	
			jQuery('#vrbooknoselsp').css('display', 'none');
	
			jQuery('#vrbooktabselsp').text( '<?php echo addslashes( JText::_('VRYOURTABLESEL') ); ?>'.replace( '%s', tableName ) );
			jQuery('#vrbooktabselsp').fadeIn('normal');
		}
	}
	
	var isRoomVisible = false;
	
	function changeRoomDescriptionDisplay() {
		if( !isRoomVisible ) {
			jQuery('#vrroomdescriptiondiv').show();
			jQuery('#vrroomdescriptionactionlink').text('<?php echo addslashes(JText::_('VRHIDEDESCRIPTION')); ?>');
		} else {
			jQuery('#vrroomdescriptiondiv').hide();
			jQuery('#vrroomdescriptionactionlink').text('<?php echo addslashes(JText::_('VRSHOWDESCRIPTION')); ?>');
		}
		
		isRoomVisible = !isRoomVisible;
	}
	
	//
	
	var total_menus_selected = 0;
	var total_people = <?php echo $args['people']; ?>;
	
	function vrAddMenuButton(id_menu) {
		if( total_menus_selected < total_people ) { 
			var new_q = 1;
			if( jQuery('#vrmenu_sel'+id_menu).length > 0 ) {
				new_q = parseInt(jQuery('#vrmenu_sel'+id_menu).val())+1;
				jQuery('#vrmenu_sel'+id_menu).val( new_q );
			} else {
				jQuery('#vrconfirmform').append('<input type="hidden" name="menus['+id_menu+']" value="1" id="vrmenu_sel'+id_menu+'"/>');
			}
			
			total_menus_selected++;
			
			updateMenuStatus(id_menu, new_q);
		}
		
		if( total_menus_selected == total_people ) {
			jQuery('#vrbookmenuselsp').addClass('vrbookmenuokpeople');
			jQuery('.vrsearchmenuaddlink').addClass('vrsearchlinkdisabled');
			jQuery('html,body').animate( {scrollTop: (jQuery('#vrbookcontinuebutton').offset().top-5)}, {duration:'slow'} );
		}
		jQuery('#vrmenudellink'+id_menu).removeClass('vrsearchlinkdisabled');
	}
	
	function vrRemoveMenuButton(id_menu) {
		if( total_menus_selected > 0 && jQuery('#vrmenu_sel'+id_menu).length > 0 ) {
			var new_q = parseInt(jQuery('#vrmenu_sel'+id_menu).val())-1;
			if( new_q > 0 ) {
				jQuery('#vrmenu_sel'+id_menu).val( new_q );
			} else {
				jQuery('#vrmenu_sel'+id_menu).remove();
				jQuery('#vrmenudellink'+id_menu).addClass('vrsearchlinkdisabled');
			}
			   
			total_menus_selected--;
			
			updateMenuStatus(id_menu, new_q);
		}
		
		jQuery('.vrsearchmenuaddlink').removeClass('vrsearchlinkdisabled');
	}
	
	var text = '';
	
	function updateMenuStatus(id_menu_changed, quantity) {	  
		  jQuery('#vrmenufootquant'+id_menu_changed).html(quantity);
		  
		  text = '<?php echo addslashes(JText::_('VRSEARCHCHOOSEMENUSTATUS')); ?>';
		  text = text.replace('%s', total_menus_selected+'/'+total_people);
		  
		  jQuery('#vrbookmenuselsp').html(text);
	}
	
</script>