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

JHtml::_('behavior.modal');

$rooms = $this->rooms;
$selectedRoomId = $this->selectedRoomId;
$tables = $this->tables;
$roomHeight = $this->roomHeight;
$rows = $this->reservationTableOnDate;
$shared_occurrency = $this->allSharedTablesOccurrency;

$filters 	= $this->filters;
$shifts 	= $this->shifts;
$continuos 	= $this->continuos;

$min_intervals 	= cleverdine::getMinuteIntervals(true);
$min_people 	= cleverdine::getMinimumPeople(true);
$max_people 	= cleverdine::getMaximumPeople(true);

// START SELECT HOURS

$select_hours = '<select name="hourmin" id="vrhour">';

$time_f = cleverdine::getTimeFormat(true);

if( count( $continuos ) == 2 ) { // CONTINUOS WORK TIME
	
	if( $continuos[0] <= $continuos[1] ) {
		for( $i = $continuos[0]; $i <= $continuos[1]; $i++ ) {
			
			for( $min = 0; $min < 60; $min+=$min_intervals ) {
				$select_hours .= '<option '.(($i.':'.$min == $filters["hourmin"]) ? 'selected="selected"' : "").' value="'.$i.':'.$min.'">'.date($time_f, mktime($i,$min,0,1,1,2000)).'</option>';
			}
		}
	} else {
		for( $i = 0; $i <= $continuos[1]; $i++ ) {	
			for( $min = 0; $min < 60; $min+=$min_intervals ) {
				$select_hours .= '<option '.(($i.':'.$min == $filters["hourmin"]) ? 'selected="selected"' : "").' value="'.$i.':'.$min.'">'.date($time_f, mktime($i,$min,0,1,1,2000)).'</option>';
			}
		}
		
		for( $i = $continuos[0]; $i <= 23; $i++ ) {
			for( $min = 0; $min < 60; $min+=$min_intervals ) {
				$select_hours .= '<option '.(($i.':'.$min == $filters["hourmin"]) ? 'selected="selected"' : "").' value="'.$i.':'.$min.'">'.date($time_f, mktime($i,$min,0,1,1,2000)).'</option>';
			}
		}
	}
} else { // SHIFTS WORK HOURS
	for( $k = 0, $n = count($shifts); $k < $n; $k++ ) {
		
		$select_hours .= '<optgroup class="vrsearchoptgrouphour" label="'.$shifts[$k]["label"].'">';
		
		for( $_app = $shifts[$k]['from']; $_app <= $shifts[$k]['to']; $_app+=$min_intervals ) {
			$_hour = intval($_app/60);
			$_min = $_app%60;
			$select_hours .= '<option '.(($_hour.':'.$_min == $filters['hourmin']) ? 'selected="selected"' : "").' value="'.$_hour.':'.$_min.'">'.date($time_f, mktime($_hour,$_min,0,1,1,2000)).'</option>';
		}
		
		$select_hours .= '</optgroup>';
	}
}

$select_hours .= '</select>';

// END SELECT HOURS

// START SELECT PEOPLE

$select_people = '<select name="people" id="vrpeople">';
for( $p = $min_people; $p <= $max_people; $p++ ) {
	$select_people .= '<option '.(($p == $filters["people"]) ? 'selected="selected"' : "").'value="'.$p.'">'.$p.' '.strtolower(JText::_($p > 1 ? 'VRMAPSPEOPLESEARCH' : 'VRPERSON')).'</option>';
}
$select_people .= '</select>';

// END SELECT PEOPLE

// SETTING AVAILABLE TABLES
for( $i = 0, $n = count($tables); $i < $n; $i++ ) {
	$found = 0;
	for( $j = 0, $m = count($rows); $j < $m && $found == 0; $j++ ) {
		$found = ( ( $rows[$j]['tid'] == $tables[$i]['id'] ) ? 1 : 0 );
	}
	
	$tables[$i]['available'] = $found;
}
// END SET

// CALCULATING OCCURRENCY IN SHARED TABLE
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
// END CALCULATE

$nowdf = cleverdine::getDateFormat(true);
$nowdf = str_replace( 'd', '%d', $nowdf );
$nowdf = str_replace( 'm', '%m', $nowdf );
$nowdf = str_replace( 'Y', '%Y', $nowdf );

$room_closed = false;
$room_index = 0;
for( $room_index; $room_index < count($rooms) && $rooms[$room_index]['id'] != $selectedRoomId; $room_index++ );

$ts = cleverdine::createTimestamp($filters['date'], 0, 0);

if( $room_index < count($rooms) ) {
	if( $rooms[$room_index]['is_closed'] ) {
		$room_closed = true;
	}
}

$vik = new VikApplication(VersionListener::getID());

?>

<style>
.vrtgreen{
	padding: 4px 9px; 
	border: 2px solid green; 
	border-radius: 3px; 
}

.vrtorange{
	padding: 4px 9px; 
	border: 2px solid orange; 
	border-radius: 3px; 
}

.vrtred{
	padding: 4px 9px; 
	border: 2px solid red; 
	border-radius: 3px; 
}
</style>

<form name="adminForm" action="index.php?option=com_cleverdine" method="post" enctype="multipart/form-data" id="adminForm">

<?php if( count( $rooms ) == 0 ) { ?>
	<p><?php echo JText::_('VRNOROOM');?></p>
<?php } else { ?>

	<div class="btn-toolbar" id="filter-bar">
		<div class="btn-group pull-left">
			<div class="vr-toolbar-setfont">
				<?php 
				$elements = array(
					$vik->initOptionElement('', '', false)
				);
					
				foreach( $this->rooms as $row ) {
					$rname = $row['name']; 
					if( $row['is_closed'] ) {
						$rname .= ' ('.JText::_('VRROOMSTATUSCLOSED').')';
					}
					array_push($elements, $vik->initOptionElement($row['id'], $rname, $row['id']==$selectedRoomId));
				}
				
				echo $vik->dropdown('selectedroom', $elements, 'vrselectedroom', '', 'onChange="document.adminForm.submit();"'); ?>
			</div>
		</div>
		
		<div class="btn-group pull-right">
			<button type="submit" class="btn"><?php echo JText::_('VRMAPSSUBMITSEARCH'); ?></button>
		</div>
		
		<div class="btn-group pull-right">
			<div class="vr-toolbar-setfont">
				<?php echo $select_people; ?>
			</div>
		</div>
		
		<div class="btn-group pull-right">
			<div class="vr-toolbar-setfont">
				<?php echo $select_hours; ?>
			</div>
		</div>
		
		<div class="btn-group pull-right vr-toolbar-setfont">
			<?php
			$attr = array();
			$attr['class'] 		= 'vrdatefilter';
			$attr['data-title'] = JText::_('VRMAPSDATESEARCH');
			$attr['onChange']	= "vrUpdateWorkingShifts();";

			echo $vik->calendar($filters['date'], 'datefilter', 'vrdatefilter', null, $attr);
			?>
		</div>
	</div>
		
	<div id="vrchangetooltip">
		<span id="vrchangetptextsp"><?php echo JText::_('VRMAPCHANGETABLETOOLTIP'); ?></span>
		<span id="vrchangetpcancsp">
			<a href="javascript: void(0);" onclick="disableChangeTable();">
				<i class="fa fa-times"></i>
			</a>
		</span>
	</div>
	
		
	<?php if( $selectedRoomId != -1 ) { ?>
		<div id="tcontainer">
		
		<?php 
		for( $i = 0, $n = count($tables); $i < $n; $i++ ) {

			$prop = json_decode($tables[$i]["design_data"], true);
				
			$tableAvailable = $tables[$i]['available'];
			$tableOccurrency = $tables[$i]['occurrency'];
				
			$actionCommand = "";
			$id = $tables[$i]['id'];
				
			if( $tableAvailable == 1 ) {
				if( $tableOccurrency == 0 ) {
					$actionCommand = '<a class="vrnewreslink" href="index.php?option=com_cleverdine&task=newreservation&date='.$filters['date'].'&hourmin='.$filters['hourmin'].'&people='.$filters['people'].'&idt='.$id.'&from=maps" style="display: block;">'. JText::_('VRMAPNEWRESBUTTON').'</a>';
				} else {
					$actionCommand = '<a class="vrnewreslink" href="index.php?option=com_cleverdine&task=newreservation&date='.$filters['date'].'&hourmin='.$filters['hourmin'].'&people='.$filters['people'].' &idt='.$id.'&from=maps" style="display: block;">'.JText::_('VRMAPNEWRESBUTTON').'</a>';
					$actionCommand .= '<a href="index.php?option=com_cleverdine&task=detailsinfo&date='.$filters['date'].'&hourmin='.$filters['hourmin'].'&people='.$filters['people'].'&table='.$id.'"';
					$actionCommand .= ' class="vrtdetailslink" target="_blank" style="display: block;">'.JText::_('VRMAPDETAILSBUTTON').'</a>';
				}
			} else if( !$room_closed && $tables[$i]['min_capacity'] <= $filters['people'] && $filters['people'] <= $tables[$i]['max_capacity'] ) {
				if( $tableOccurrency == 0 ) {
					$actionCommand = '<a class="vrchangetablelink" href="javascript: void(0);" onClick="changeTableActionPressed('.$id.');" style="display: block;">'.JText::_('VRMAPCHANGETABLEBUTTON').'</a>';
				}
				$actionCommand .= '<a href="index.php?option=com_cleverdine&task=detailsinfo&date='.$filters['date'].'&hourmin='.$filters['hourmin'].'&people='.$filters['people'].'&table='.$id.'"';
				$actionCommand .= ' class="vrtdetailslink" target="_blank" style="display: block;">'.JText::_('VRMAPDETAILSBUTTON').'</a>';
			}
			
			?>
			<div class="vrtable" style="cursor: pointer;" id="vrt<?php echo $tables[$i]["id"]; ?>" onClick="changeTable(<?php echo $tables[$i]["id"]; ?>)">
				<span class="vrttitle<?php echo ((strlen( $tables[$i]['name'] ) > 10 ) ? 'smaller' : ''); ?>" id="vrttitle<?php echo $tables[$i]["id"];?>"><?php echo $tables[$i]["name"]; ?></span>
				<div class="vrtamblinks">
					<?php echo $actionCommand."\n"; ?>
				</div>
				<?php if( $tables[$i]['multi_res'] == 1 ) { ?>
					<div class="vrtoccurrency"><?php echo $tables[$i]['occurrency']; ?></div>
				<?php } ?>
				<div class="vrtcapacity"><?php echo $tables[$i]['min_capacity'].'-'.$tables[$i]['max_capacity']; ?></div>
			</div>
						
			<script>
			jQuery(function(){
				
				var tableAvailable = <?php echo $tables[$i]['available']; ?>;
				var tableOccurrency = <?php echo $tables[$i]['occurrency']; ?>;

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
					backgroundColor:"#"+bgc
				});

				if( bgc != -1 ) {
					jQuery("#vrt"+<?php echo $tables[$i]["id"]; ?>).css('backgroundColor','#'+bgc);
				}

				jQuery("#vrt<?php echo $tables[$i]["id"]; ?>").css('transform', 'rotate('+rot+'deg)');
				jQuery("#vrt<?php echo $tables[$i]["id"]; ?>").css('-ms-transform', 'rotate('+rot+'deg)');
				jQuery("#vrt<?php echo $tables[$i]["id"]; ?>").css('-webkit-transform', 'rotate('+rot+'deg)');
				
				if( tableAvailable == 1 ) {
					if( tableOccurrency == 0 ) {
						// FULL AVAILABLE
						jQuery("#vrt<?php echo $tables[$i]["id"]; ?>").addClass('vrtgreen');
					} else {
						// NOT FULL AVAILABLE
						jQuery("#vrt<?php echo $tables[$i]["id"]; ?>").addClass('vrtorange');
					}
				} else {
					// NOT AVAILABLE
					jQuery("#vrt<?php echo $tables[$i]["id"]; ?>").addClass('vrtred');
				}

			});
		</script>
		<?php } ?>
		</div>
	<?php } ?>
		
<?php } ?>
	
	<input type="hidden" name="task" value="maps"/>
	<input type="hidden" name="option" value="com_cleverdine"/>
</form>

<script>
	
Joomla.submitbutton = function(task) {
	var selId = document.getElementById('vrselectedroom').value;
	if( selId != '-1' ) {
		Joomla.submitform(task, document.adminForm);
	} else {
		alert('<?php echo JText::_('VRMAPSBEFORECHOOSEROOM'); ?>');
	}
}

jQuery( document ).ready(function() {

	jQuery("#tcontainer").css( {"height": "<?php echo $roomHeight; ?>px" } );

	jQuery('#vrselectedroom').select2({
		placeholder: '--',
		allowClear: true,
		width: 300
	});

	jQuery('#vrhour, #vrpeople').select2({
		minimumResultsForSearch: -1,
		allowClear: false,
		width: 100
	});

});

var lastSelectedId = -1;
var canChange = false;

function changeTableActionPressed(id) {
	lastSelectedId = id;
	canChange = true;
	
	jQuery('#vrchangetooltip').fadeIn();
}

function changeTable(id) {
	if( canChange ) {
		if( id != lastSelectedId ) {
			canChange = false;
			window.location.href = 'index.php?option=com_cleverdine&task=changetable&date=<?php echo $filters['date']; ?>&hourmin=<?php echo $filters['hourmin']; ?>&people=<?php echo $filters['people']; ?>&oldid='+lastSelectedId+'&newid='+id;
		} 
	}
}

function disableChangeTable() {
	lastSelectedId = -1;
	canChange = false;
	jQuery('#vrchangetooltip').hide();
}

function vrUpdateWorkingShifts() {
	jQuery('#vrhour').prop('disabled', true);
	
	jQuery.noConflict();
	
	var jqxhr = jQuery.ajax({
		type: "POST",
		url: "index.php",
		data: { 
			option: "com_cleverdine",
			task: "get_working_shifts",
			date: jQuery('#vrdatefilter').val(),
			hourmin: jQuery('#vrhour').val(),
			tmpl: "component"
		}
	}).done(function(resp){
		var obj = jQuery.parseJSON(resp); 
		
		if( obj[0] && obj[1].length > 0 ) {
			jQuery('#vrhour').html(obj[1]);
		}
		
		jQuery('#vrhour').prop('disabled', false);
		
	}).fail(function(resp){
		jQuery('#vrhour').prop('disabled', false);
	});
}

</script>