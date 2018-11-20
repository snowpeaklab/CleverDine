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

if( !$this->ACCESS ) {
	exit;
}

JHtml::_('behavior.keepalive');

$operator = $this->operator;

$refresh_ms = 90000; // 90 seconds
$enable_refresh = true;

$itemid = JFactory::getApplication()->input->get('Itemid', 0, 'uint');

$rooms = $this->rooms;
$selectedRoomId = $this->selectedRoomId;
$tables = $this->tables;
$roomSize = $this->roomSize;
$rows = $this->reservationTableOnDate;
$shared_occurrency = $this->allSharedTablesOccurrency;
$currentReservations = $this->currentReservations;

$filters = $this->filters;
$shifts = $this->shifts;
$continuos = $this->continuos;
$min_intervals = cleverdine::getMinuteIntervals();
$min_people = cleverdine::getMinimumPeople();
$max_people = cleverdine::getMaximumPeople();

$_hour_min_exp = explode(':', $filters['hourmin']);
$filters['hourmin'] = intval($_hour_min_exp[0]).':'.intval($_hour_min_exp[1]);

$selected_room_image = '';
for( $i = 0; $i < count($rooms) && strlen($selected_room_image) == 0; $i++ ) {
	if( $rooms[$i]['id'] == $selectedRoomId ) {
		$selected_room_image = $rooms[$i]['image'];
	}
}

// START SELECT HOURS

$select_hours = '<select name="hourmin" class="vrsearchhour" id="vrselecthour">';
$select_hours .= '<option value="">--</option>';

$time_f = cleverdine::getTimeFormat();

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
		
		if( $shifts[$k]['showlabel'] ) {
			$select_hours .= '<optgroup class="vrsearchoptgrouphour" label="'.$shifts[$k]["label"].'">';
		}
		
		for( $_app = $shifts[$k]['from']; $_app <= $shifts[$k]['to']; $_app+=$min_intervals ) {
			$_hour = intval($_app/60);
			$_min = $_app%60;
			$select_hours .= '<option '.(($_hour.':'.$_min == $filters['hourmin']) ? 'selected="selected"' : "").' value="'.$_hour.':'.$_min.'">'.date($time_f, mktime($_hour,$_min,0,1,1,2000)).'</option>';
		}
		
		if( $shifts[$k]['showlabel'] ) {
			$select_hours .= '</optgroup>';
		}
	}
}

$select_hours .= '</select>';

// END SELECT HOURS

// START SELECT PEOPLE

$select_people = '<select class="vrsearchpeople" name="people" id="vrselectpeople">';
for( $p = $min_people; $p <= $max_people; $p++ ) {
	$select_people .= '<option '.(($p == $filters["people"]) ? 'selected="selected"' : "").'value="'.$p.'">'.$p.'</option>';
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

$js_res_array = array();

$now = time();
$avg = cleverdine::getAverageTimeStay();

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
	
	$tables[$i]['id_reservation'] = array();
	$tables[$i]['rescode'] = 0;
	$tables[$i]['code'] = '';
	$tables[$i]['code_icon'] = '';
	
	$found = false;
	for( $j = 0; $j < count($currentReservations) && !$found; $j++ ) {
		if( $tables[$i]['id'] == $currentReservations[$j]['id_table'] ) {
			array_push( $tables[$i]['id_reservation'], $currentReservations[$j]['id'] );
			if( !$tables[$i]['multi_res'] ) {
				if( !empty($currentReservations[$j]['code']) ) {
					$tables[$i]['rescode'] = $currentReservations[$j]['rescode'];
					$tables[$i]['code'] = $currentReservations[$j]['code'];
					$tables[$i]['code_icon'] = $currentReservations[$j]['code_icon'];
				} else {
					$tables[$i]['code'] = '--';
				}

				$stay_time = (int) $currentReservations[$j]['stay_time'];
				if (empty($stay_time)) {
					$stay_time = $avg;
				}
				$stay_time *= 60;
				
				$time_left = '';
				if( $currentReservations[$j]['checkin_ts'] <= $now && $now < $currentReservations[$j]['checkin_ts']+$stay_time ) {
					$time_left = $currentReservations[$j]['checkin_ts']+$stay_time-$now;
					if( $time_left < 60 ) {
						$time_left = JText::sprintf('VRRESTIMELEFTSEC', $time_left);
					} else {
						$time_left = JText::sprintf('VRRESTIMELEFTMIN', ceil($time_left/60));
					}
				}
				
				$js_res_array[$currentReservations[$j]['id']] = array(
					'tname' => $tables[$i]['name'],
					'time' => date($time_f, $currentReservations[$j]['checkin_ts']),
					'custname' => $currentReservations[$j]['custname'],
					'custmail' => $currentReservations[$j]['custmail'],
					'custphone' => $currentReservations[$j]['custphone'],
					'timeleft' => $time_left
				);
				
				$found = true;
			}
		}
	}
}
// END CALCULATE

$date_f = cleverdine::getDateFormat(true);

// jQuery datepicker
$vik = new VikApplication();
$vik->attachDatepickerRegional();

$code_icon_path = JUri::root().'components'.DIRECTORY_SEPARATOR.'com_cleverdine'.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR;

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

<div class="vroversighthead">
	<h2><?php echo JText::sprintf('VRLOGINOPERATORHI', $operator['firstname']); ?></h2>
	<?php echo cleverdine::getToolbarLiveMap($operator); ?>
</div>

<form name="oversightform" action="<?php echo JRoute::_('index.php?option=com_cleverdine'); ?>" method="post" enctype="multipart/form-data" id="vroversightform" class="vrfront-manage-form">

<?php if( count( $rooms ) == 0 ) { ?>
	<p><?php echo JText::_('VRNOROOM');?></p>
<?php } else { ?>

	<div id="vrmapinputsdiv">
		<?php if( count($rooms) > 1 ) { ?>
			<div id="vrselectedroomdiv">
				<?php foreach( $rooms as $r ) { ?>
					<div class="vroversight-room-block <?php echo ($selectedRoomId == $r['id'] ? 'vroversight-room-selected' : ''); ?>">
						<a href="javascript: void(0);" class="vroversight-room-link" onClick="vrRoomClicked(<?php echo $r['id']; ?>);"><?php echo $r['name']; ?></a>
					</div>
				<?php } ?>
			</div>
		<?php } ?>
			
		<div id="vrsearchinputdiv">
			<div id="vrdatefilterdiv">
				<label for="vrdatefilter"><b><?php echo JText::_('VRMAPSDATESEARCH');?></b></label>
				<input type="text" name="datefilter" id="vrdatefield" size="20" value="<?php echo $filters['date']; ?>"/>
			</div>
			
			<div id="vrselecthoursdiv">
				<label for="vrselecthour"><b><?php echo JText::_('VRMAPSTIMESEARCH');?></b></label>
				<?php echo $select_hours; ?> 		
			</div>
			
			<div id="vrselectpeoplediv">
				<label for="vrselectpeople"><b><?php echo JText::_('VRMAPSPEOPLESEARCH');?></b></label>
				<?php echo $select_people; ?> 
			</div>
			
			<div id="vrsubmitfinddiv">
				<button type="submit" id="vrsubmitfind"><?php echo JText::_('VRMAPSSUBMITSEARCH');?></button>
			</div>
		</div>
		
		<!--
		<div id="vrchangetooltip">
			<span id="vrchangetptextsp"><?php echo JText::_('VRMAPCHANGETABLETOOLTIP'); ?></span>
			<span id="vrchangetpcancsp" onClick="disableChangeTable();"></span>
		</div>
		-->
		
		<?php if( !$this->timeOk ) { ?>
			<div class="vroversight-notime-warning"><?php echo JText::_('VRMAPSNOTIMEWARNING'); ?></div>
		<?php } else { ?>
			<div class="vroversight-current-details">
				<?php
				   $ts = cleverdine::createTimestamp($filters['date'], $filters['hour'], $filters['min']);
				   $dt = explode('-', date('D-n-d-Y-'.$time_f, $ts)); // week day - month num - day - year - time
				   $num_text_arr = array("ONE", "TWO", "THREE", "FOUR", "FIVE", "SIX", "SEVEN", "EIGHT", "NINE", "TEN", "ELEVEN", "TWELVE");
				?>
				<span class="vroversight-current-date"><?php echo mb_substr(JText::_('VRJQCAL'.strtoupper($dt[0])), 0, 3, 'UTF-8')." ".$dt[2].", ".JText::_('VRMONTH'.$num_text_arr[$dt[1]-1])." ".$dt[3]; ?></span>
				<span class="vroversight-current-time"><?php echo $dt[4]; ?></span>
				<span class="vroversight-current-people"><?php echo "x ".$filters['people']; ?></span>
				
				<div class="vroversight-nowlink-div">
					<a href="<?php echo JRoute::_('index.php?option=com_cleverdine&view=oversight'); ?>" class="vroversight-now-link"><?php echo JText::_('VRNOWBUTTON'); ?></a>
				</div>
			</div>
		<?php } ?>
	
	</div>
		
	<?php if( $selectedRoomId != -1 ) { ?>
		<div class="vroversight-map-scroll" style="overflow: hidden;position: relative;width: 100%;border: 1px solid #ccc;">
			<div class="vroversight-map-container" style="display: block;overflow-x:auto;overflow-y:hidden">
				<div id="tcontainer">
				<?php 
				for( $i = 0, $n = count($tables); $i < $n; $i++ ) {
		
					$prop = json_decode($tables[$i]["design_data"], true);
						
					$tableAvailable = $tables[$i]['available'];
					$tableOccurrency = $tables[$i]['occurrency'];
						
					$actionCommand = "";
					$id = $tables[$i]['id'];
					
					if( $this->timeOk ) {
						if( $tableAvailable == 1 ) {
							$newres_url = JRoute::_('index.php?option=com_cleverdine&task=quickres&date='.$filters['date'].'&hourmin='.$filters['hourmin'].'&people='.$filters['people'].'&idt='.$id, false);
							$actionCommand = '<a class="vrnewreslink" href="'.$newres_url.'" style="display: block;">'.JText::_('VRMAPNEWRESBUTTON').'</a>';
							
							if( $tableOccurrency != 0 ) {
								$details_url = 'index.php?option=com_cleverdine&task=editres';
								foreach( $tables[$i]['id_reservation'] as $idr ) {
									$details_url .= '&cid[]='.$idr;
								}
								$details_url = JRoute::_($details_url, true);
								// shared table > shows only the last reservation found
								$actionCommand .= '<a href="'.$details_url.'" class="vrtdetailslink" style="display: block;">'.JText::_('VRMAPDETAILSBUTTON').'</a>';
							}
						//} else if( $tables[$i]['min_capacity'] <= $filters['people'] && $filters['people'] <= $tables[$i]['max_capacity'] ) {
						} else if( count($tables[$i]['id_reservation']) > 0 ) {
							if( $tableOccurrency == 0 && $tables[$i]['min_capacity'] <= $filters['people'] && $filters['people'] <= $tables[$i]['max_capacity'] ) {
								$actionCommand = '<a class="vrchangetablelink" href="javascript: void(0);" onClick="changeTableActionPressed('.$id.');" style="display: block;">'.JText::_('VRMAPCHANGETABLEBUTTON').'</a>';
							}

							$details_url = 'index.php?option=com_cleverdine&task=editres';
							foreach( $tables[$i]['id_reservation'] as $idr ) {
								$details_url .= '&cid[]='.$idr;
							}
							$details_url = JRoute::_($details_url, true);
						   
							//$details_url = JRoute::_('index.php?option=com_cleverdine&task=editres&cid[]='.$tables[$i]['id_reservation'][0]);
							$actionCommand .= '<a href="'.$details_url.'" class="vrtdetailslink" style="display: block;">'.JText::_('VRMAPDETAILSBUTTON').'</a>';
						}
					}
					
					$x = $prop["pos"]["left"];
					$y = $prop["pos"]["top"];
					$w = $prop["size"]["width"];
					$h = $prop["size"]["height"];
					$bgc = $prop["bgcolor"];
					$rot = $prop["rotation"];
					
					$inline_css = "cursor: pointer;
					margin-top: ".$y."px;
					margin-left: ".$x."px;
					width: ".$w."px;
					height: ".$h."px;
					".($bgc != -1 ? "background-color: #$bgc;" : "")."
					transform: rotate(".$rot."deg);
					-ms-transform: rotate(".$rot."deg);
					-webkit-transform: rotate(".$rot."deg);";
					
					$classes = "";
					
					if( $tables[$i]['available'] == 1 && $this->timeOk ) {
						if( $tables[$i]['occurrency'] == 0 ) {
							// FULL AVAILABLE
							$classes = "vrtgreen";
						} else {
							// NOT FULL AVAILABLE
							$classes = "vrtorange";
						}
					} else {
						// NOT AVAILABLE
						$classes = "vrtred";
					}
					
					$res_assoc = "";
					if( count($tables[$i]['id_reservation']) > 0 ) {
						$res_assoc = implode(',', $tables[$i]['id_reservation']);
					}
					
					?>
					<div class="vrtable <?php echo $classes; ?>" style="<?php echo $inline_css; ?>" id="vrt<?php echo $tables[$i]["id"]; ?>" onClick="vrTablePressed(<?php echo $tables[$i]["id"]; ?>)">
						<span class="vrttitle<?php echo ((strlen( $tables[$i]['name'] ) > 10 ) ? 'smaller' : ''); ?>" id="vrttitle<?php echo $tables[$i]["id"];?>"><?php echo $tables[$i]["name"]; ?></span>
						<div class="vrtamblinks">
							<?php echo $actionCommand."\n"; ?>
						</div>
						<?php if( $tables[$i]['multi_res'] == 1 ) { ?>
							<div class="vrtoccurrency"><?php echo $tables[$i]['occurrency']; ?></div>
						<?php } ?>
						<div class="vrtcapacity"><?php echo $tables[$i]['min_capacity'].'-'.$tables[$i]['max_capacity']; ?></div>
						<?php if( !empty($tables[$i]['code']) ) { ?>
							<div class="vrtrescode" id="vrtrescode<?php echo $tables[$i]['id_reservation'][0]; ?>" onClick="vrReservationStatusPressed(<?php echo $tables[$i]['rescode']; ?>,<?php echo $tables[$i]['id_reservation'][0]; ?>);">
								<?php if( empty($tables[$i]['code_icon']) ) { ?>
									<span class="vrtrescodelabel"><?php echo $tables[$i]['code']; ?></span>
								<?php } else { ?>
									<img src="<?php echo $code_icon_path.$tables[$i]['code_icon']; ?>" title="<?php echo $tables[$i]['code']; ?>"/>
								<?php } ?>
							</div>
						<?php } ?>
						<input type="hidden" id="vrtresassoc<?php echo $tables[$i]['id']; ?>" value="<?php echo $res_assoc; ?>"/>
					</div>
				<?php } ?>
				
				<div class="vrrescodedialog" style="display: none;">
					<div class="vrrescodeheaderdialog">
						<div class="vrrescodeheadertop">
							  <div class="vrrescodeheaderback"><?php echo JText::_('VRBACK'); ?></div>
							  <div class="vrrescodeheadertime">
								  <span class="vrrescodeheadertimelabel"></span>
								  <span class="vrrescodeheaderexpand">
									  <a href="" target="_blank" class="vroversight-expandlink"></a>
								  </span>
							  </div>
						</div>
						<div class="vrrescodeheadertable"></div>
					</div>
					<div class="vrrescodeheaderinfodialog">
						<div class="vrrescodeheaderinfoname"></div>
						<div class="vrrescodeheaderinfomail"></div>
						<div class="vrrescodeheaderinfophone"></div>
					</div>
					<div class="vrrescodeheaderspacerdialog">
						
					</div>
					<div class="vrrescodelist">
						<div class="vrrescodeblock" onClick="vrSubmitReservationCode(0);" id="vrrescodeblock0">
							<div class="vrrescodeblockimage"></div>
							<div class="vrrescodeblockname">--</div>
						</div>
						<?php foreach( $this->allResCodes as $c ) { ?>
							<div class="vrrescodeblock" onClick="vrSubmitReservationCode(<?php echo $c['id']; ?>);" id="vrrescodeblock<?php echo $c['id']; ?>">
								<div class="vrrescodeblockimage">
									<?php if( !empty($c['icon']) ) { ?>
										<img src="<?php echo $code_icon_path.$c['icon']; ?>" />
									<?php } ?>
								</div>
								<div class="vrrescodeblockname"><?php echo $c['code']; ?></div>
							</div>
						<?php } ?>
					</div>
				</div>
				
				</div>
			</div>
		</div>
	<?php } ?>
		
<?php } ?>

	<div class="vroversight-reservations-block">
		 <div class="vroversight-reservations-head">
			<span class="vroversight-reservations-tab <?php echo ($this->selectedResTab == 1 ? 'vroversight-selected-tab' : ''); ?>" id="vrheadtab1" onClick="vrListTabPressed(1);"><?php echo JText::_('VRLISTRESCURRENT'); ?></span>
			<span class="vroversight-reservations-tab <?php echo ($this->selectedResTab == 2 ? 'vroversight-selected-tab' : ''); ?>" id="vrheadtab2" onClick="vrListTabPressed(2);"><?php echo JText::_('VRLISTRESUPCOMING'); ?></span>
		</div>
		<div class="vroversight-reservations-content">
			<div class="vroversight-reservations-list" style="<?php echo ($this->selectedResTab == 1 ? '' : 'display: none;'); ?>" id="vrlisttab1">
				<div class="vroversight-reservations-titleshead">
					<span class="vroversight-reservations-title"><?php echo JText::_('VRLISTRESTITLE1'); ?></span>
					<span class="vroversight-reservations-title"><?php echo JText::_('VRLISTRESTITLE2'); ?></span>
					<span class="vroversight-reservations-title"><?php echo JText::_('VRLISTRESTITLE3'); ?></span>
					<span class="vroversight-reservations-title"><?php echo JText::_('VRLISTRESTITLE7'); ?></span>
					<span class="vroversight-reservations-title"><?php echo JText::_('VRLISTRESTITLE4'); ?></span>
					<span class="vroversight-reservations-title"><?php echo JText::_('VRLISTRESTITLE6'); ?></span>
				</div>
				<div class="vroversight-reservations-body" id="vrbodyreslist1">
					<?php foreach( $this->nowReservations as $r ) {

						$stay_time = (int) $r['stay_time'];
						if (empty($stay_time)) {
							$stay_time = $avg;
						}
						$stay_time *= 60;

						$checkout = $r['checkin_ts']+$stay_time-time();
						if( $checkout < 60 ) {
							$checkout .= " ".JText::_('VRSECSHORT');
						} else {
							$checkout = ceil($checkout/60)." ".JText::_('VRMINSHORT'); 
						}
						?>
						<div class="vroversight-reservation-row">
							<span class="vroversight-resrow-time"><?php echo date($time_f, $r['checkin_ts']); ?></span>
							<span class="vroversight-resrow-table"><?php echo $r['tname']; ?></span>
							<span class="vroversight-resrow-people"><?php echo $r['people']; ?></span>
							<span class="vroversight-resrow-people"><?php echo $r['purchaser_nominative']; ?></span>
							<span class="vroversight-resrow-checkout"><?php echo $checkout; ?></span>
							<span class="vroversight-resrow-status" id="vrlinestatus<?php echo $r['id']; ?>" onClick="vrReservationStatusPressed(<?php echo $r['rescode']; ?>,<?php echo $r['id']; ?>);"><?php 
								if( $r['rescode'] > 0 ) {
									if( !empty($r['codeicon']) ) {
										?><img src="<?php echo $code_icon_path.$r['codeicon']; ?>" title="<?php echo $r['codename']; ?>"/><?php
									} else {
										echo $r['codename'];
									}
								} else {
									echo '--';
								}
							?></span>
						</div>
					<?php } ?>
				</div>
			</div>
			<div class="vroversight-reservations-list" style="<?php echo ($this->selectedResTab == 2 ? '' : 'display: none;'); ?>" id="vrlisttab2">
				<div class="vroversight-reservations-titleshead">
					<span class="vroversight-reservations-title"><?php echo JText::_('VRLISTRESTITLE1'); ?></span>
					<span class="vroversight-reservations-title"><?php echo JText::_('VRLISTRESTITLE2'); ?></span>
					<span class="vroversight-reservations-title"><?php echo JText::_('VRLISTRESTITLE3'); ?></span>
					<span class="vroversight-reservations-title"><?php echo JText::_('VRLISTRESTITLE7'); ?></span>
					<span class="vroversight-reservations-title"><?php echo JText::_('VRLISTRESTITLE5'); ?></span>
					<span class="vroversight-reservations-title"><?php echo JText::_('VRLISTRESTITLE6'); ?></span>
				</div>
				<div class="vroversight-reservations-body" id="vrbodyreslist2">
					<?php foreach( $this->upcomingReservations as $r ) { 
						$checkout = $r['checkin_ts']-time();
						if( $checkout < 60 ) {
							$checkout .= " ".JText::_('VRSECSHORT');
						} else {
							$checkout = ceil($checkout/60)." ".JText::_('VRMINSHORT'); 
						}
						?>
						<div class="vroversight-reservation-row">
							<span class="vroversight-resrow-time"><?php echo date($time_f, $r['checkin_ts']); ?></span>
							<span class="vroversight-resrow-table"><?php echo $r['tname']; ?></span>
							<span class="vroversight-resrow-people"><?php echo $r['people']; ?></span>
							<span class="vroversight-resrow-people"><?php echo $r['purchaser_nominative']; ?></span>
							<span class="vroversight-resrow-checkout"><?php echo $checkout; ?></span>
							<span class="vroversight-resrow-status" id="vrlinestatus<?php echo $r['id']; ?>" onClick="vrReservationStatusPressed(<?php echo $r['rescode']; ?>,<?php echo $r['id']; ?>);"><?php 
								if( $r['rescode'] > 0 ) {
									if( !empty($r['codeicon']) ) {
										?><img src="<?php echo $code_icon_path.$r['codeicon']; ?>" title="<?php echo $r['codename']; ?>"/><?php
									} else {
										echo $r['codename'];
									}
								} else {
									echo '--';
								}
							?></span>
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
	
	<input type="hidden" name="selectedroom" value="<?php echo $selectedRoomId; ?>" id="vrselecetedroom"/>
	<input type="hidden" name="view" value="oversight"/>
	<input type="hidden" name="option" value="com_cleverdine"/>
</form>

<script>

var map_width = <?php echo $roomSize['width']; ?>;
var map_height = <?php echo $roomSize['height']; ?>;

jQuery( document ).ready(function() {
	jQuery("#tcontainer").css( {"height": map_height+"px" } );
	var room_image = '<?php echo $selected_room_image; ?>';
	if( room_image.length > 0 ) {
		jQuery('#tcontainer').css("background-image", "url('<?php echo JUri::root().'components/com_cleverdine/assets/media/'; ?>"+room_image+"')");
	} else {
		jQuery('#tcontainer').css("background-image", "");
	}
});

jQuery( document ).ready(function() {
	map_width += 20;
	map_height += 20;
	
	var _w = map_width;
	var _tc_w = parseInt( jQuery('.vroversight-map-container').css('width').replace('px','') );
	
	if( _w < _tc_w ) {
		_w = _tc_w;
	} 
	
	jQuery('#tcontainer').css( {width: _w+'px', height: map_height+'px' } );
	jQuery('.vroversight-map-container').scroll(function(){
		var offset = jQuery('.vroversight-map-container').scrollLeft();
		
		jQuery('#tcontainer').css('margin-left', '-'+offset+'px' );
		
		if( map_width+offset < _tc_w ) {
			map_width = _tc_w-offset;
		}
		
		jQuery('#tcontainer').css('width', (map_width+offset)+'px' );
	});

});

var lastSelectedId = -1;
var canChange = false;
var codePressed = false;
var time_valid = <?php echo ( $this->timeOk ? 1 : 0 ); ?>;

function changeTableActionPressed(id) {
	jQuery('.vrtred').addClass('vrtopacity');
	jQuery('#vrt'+id).removeClass('vrtopacity');
	jQuery('.vrtamblinks').hide();
	lastSelectedId = id;
	//canChange = true;
	//jQuery('#vrchangetooltip').fadeIn('normal');
}

function vrTablePressed(id) {
	if( !time_valid ) return;
	
	if( canChange ) {
		if( id != lastSelectedId ) {
			if( !jQuery('#vrt'+id).hasClass('vrtred') ) {
				canChange = false;
				window.location.href = 'index.php?option=com_cleverdine&task=changetable&Itemid=<?php echo $itemid; ?>&date=<?php echo $filters['date']; ?>&hourmin=<?php echo $filters['hourmin']; ?>&people=<?php echo $filters['people']; ?>&oldid='+lastSelectedId+'&newid='+id;
			}
		} else {
			disableChangeTable();
		}
	} else if( codePressed ) {
		codePressed = false;
	} else {
		if( lastSelectedId != -1 ) {
			canChange = true;
		} else if( !jQuery('#vrt'+id).hasClass('vrtred') ) { // reserve
			window.location.href = 'index.php?option=com_cleverdine&task=quickres&Itemid=<?php echo $itemid; ?>&date=<?php echo $filters['date']; ?>&hourmin=<?php echo $filters['hourmin']; ?>&people=<?php echo $filters['people']; ?>&idt='+id;
		} else { // details
			var res_assoc = jQuery('#vrtresassoc'+id).val();
			if( res_assoc.length > 0 ) {
				res_assoc = res_assoc.replace(/,/g, '&cid[]=');
				window.location.href = 'index.php?option=com_cleverdine&task=editres&Itemid=<?php echo $itemid; ?>&cid[]='+res_assoc;
			}
		}
	}
}

function disableChangeTable() {
	lastSelectedId = -1;
	canChange = false;
	jQuery('.vrtred').removeClass('vrtopacity');
	
	jQuery('.vrtamblinks').show();
	//Query('#vrchangetooltip').fadeOut('normal');
}

var LAST_ROOM_SELECTED = <?php echo $selectedRoomId; ?>

function vrRoomClicked(id_room) {
	if( id_room != LAST_ROOM_SELECTED ) {
		jQuery('#vrselecetedroom').val(id_room);
		document.oversightform.submit();
	}
}

jQuery(function(){
	
	var closingDays = <?php echo json_encode(cleverdine::getClosingDays()); ?>;
	
	var sel_format = "<?php echo $date_f; ?>";
	var df_separator = sel_format[1];

	sel_format = sel_format.replace(new RegExp("\\"+df_separator, 'g'), "");

	if( sel_format == "Ymd") {
		Date.prototype.format = "yy"+df_separator+"mm"+df_separator+"dd";
	} else if( sel_format == "mdY" ) {
		Date.prototype.format = "mm"+df_separator+"dd"+df_separator+"yy";
	} else {
		Date.prototype.format = "dd"+df_separator+"mm"+df_separator+"yy";
	}

	var today = new Date();
	
	jQuery( document ).ready(function(){
		jQuery('#vrdatefield:input').on('change', function(){
			vrUpdateWorkingShifts()
		});
	});

	jQuery("#vrdatefield:input").datepicker({
		dateFormat: today.format,
		beforeShowDay: setupCalendar
	});

	function setupCalendar(date) {
		
		var enabled = false;
		var clazz = "";
		var ignore_cd = 0;
		
		enabled = true;
		if( ignore_cd == 0 ) {
			for( var i = 0; i < closingDays.length; i++ ) {
				var _d = getDate( closingDays[i]['date'] );
				
				if( closingDays[i]['freq'] == 0 ) {
					if( _d.valueOf() == date.valueOf() ) {
						return [false,""];
					}
				} else if( closingDays[i]['freq'] == 1 ) {
					if( _d.getDay() == date.getDay() ) {
						return [false,""];
					}
				} else if( closingDays[i]['freq'] == 2 ) {
					if( _d.getDate() == date.getDate() ) {
						return [false,""];
					} 
				} else if( closingDays[i]['freq'] == 3 ) {
					if( _d.getDate() == date.getDate() && _d.getMonth() == date.getMonth() ) {
						return [false,""];
					} 
				}
			}
		}
		
		return [enabled,clazz];
	}
	
	function getDate(day) {
		var formats = today.format.split(df_separator);
		var date_exp = day.split(df_separator);
		
		var _args = new Array();
		for( var i = 0; i < formats.length; i++ ) {
			_args[formats[i]] = parseInt( date_exp[i] );
		}
		
		return new Date( _args['yy'], _args['mm']-1, _args['dd'] );
	}
	
	function contains(arr,key) {
		for( var i = 0; i < arr.length; i++ ) {
			if( arr[i] == key ) {
				return true;
			}
		}
		
		return false;
	}
	
});

// RES CODES

var last_id_res = -1;
var last_res_code = -1;

var reservationMap = <?php echo json_encode($js_res_array); ?>;

// DESKTOP
jQuery(document).mouseup(function (e) {
	var container = jQuery(".vrrescodedialog");
	var links = jQuery(".vrrescodelink");

	if( !container.is(e.target) && container.has(e.target).length === 0 && !links.is(e.target) && links.has(e.target).length === 0 ) {
		vrDisposeResCodeDialog();
	}
});

// TABLET, MOBILE
/*
jQuery(document).bind('touchend', function (e) {
	var container = jQuery(".vrrescodedialog");
	var links = jQuery(".vrrescodelink");

	if( !container.is(e.target) && container.has(e.target).length === 0 && !links.is(e.target) && links.has(e.target).length === 0 ) {
		vrDisposeResCodeDialog();
	}
});
*/

jQuery(document).ready(function(){
	jQuery('.vrrescodeheadertable').click(function(){
		var sibling = jQuery('.vrrescodeheaderinfodialog');
		if( sibling.is(':visible') ) {
			sibling.slideUp();
		} else {
			sibling.slideDown();
		}
	});
	
	jQuery('.vrrescodeheaderback').click(function(){
		vrDisposeResCodeDialog();
	});;
});

function vrReservationStatusPressed(id_code, id_res) {
	codePressed = true;
	
	vrUpdateTableReservation(id_code, id_res)
	
	jQuery('.vrrescodedialog').show();
	
	last_id_res = id_res; 
	last_res_code = id_code;
	
} 

function vrUpdateTableReservation(id_code, id_res) {
	var user = reservationMap[id_res];
	
	if( jQuery.isEmptyObject(user) ) {
		return false;
	}
	
	// user infos header dialog
	jQuery('.vrrescodeheadertimelabel').html(user['time']);
	jQuery('.vrrescodeheadertable').html(user['tname']);
	jQuery('.vrrescodeheaderinfoname').html(user['custname']);
	jQuery('.vrrescodeheaderinfomail').html(user['custmail']);
	jQuery('.vrrescodeheaderinfophone').html(user['custphone']);
	if( user['timeleft'].length > 0 ) {
		jQuery('.vrrescodeheaderspacerdialog').html('<span class="vrrescode-time-line">'+user['timeleft']+'</span>');
	} else {
		jQuery('.vrrescodeheaderspacerdialog').html('');
	}
	
	jQuery('.vroversight-expandlink').prop('href', 'index.php?option=com_cleverdine&task=editres&Itemid=<?php echo $itemid; ?>&cid[]='+id_res);
	//
	
	jQuery('.vrrescodeblock').removeClass('vrcodeblockselected');
	jQuery('#vrrescodeblock'+id_code).addClass('vrcodeblockselected');
	
	return true;
}

function vrDisposeResCodeDialog() {
	jQuery('.vrrescodedialog').hide();
	last_id_res = -1;
	
	jQuery('.vrrescodelink').removeClass('vrcodelinkselected');
}

function vrSubmitReservationCode(new_code) {
	if( last_res_code == new_code ) {
		return;
	}
	
	var res_id = last_id_res;
	
	vrDisposeResCodeDialog();
	
	var last_html = jQuery('#vrtrescode'+res_id).html();
	//jQuery('#vrtrescode'+res_id).html('');
	
	var status_line = jQuery('#vrlinestatus'+res_id);
	
	jQuery.noConflict();
	
	var jqxhr = jQuery.ajax({
		type: "POST",
		url: "<?php echo JRoute::_('index.php?option=com_cleverdine&task=change_reservation_code&tmpl=component&type=1'); ?>",
		data: { id: res_id, new_code: new_code }
	}).done(function(resp){
		var obj = jQuery.parseJSON(resp); 
		if( obj[0] == 1 ) {
			jQuery('#vrtrescode'+res_id).replaceWith(obj[1]);
			status_line.replaceWith(obj[2]);
		} else {
			jQuery('#vrtrescode'+res_id).html(last_html);
			alert(obj[1]);
		}
	}).fail(function(resp){
		jQuery('#vrtrescode'+res_id).html(last_html);
		console.log(resp);
	});
}

// REFRESH MAP

var FILTER_ROOM = <?php echo (!empty($selectedRoomId) ? $selectedRoomId : -1); ?>;
var FILTER_DATE = '<?php echo (!empty($filters['date']) ? $filters['date'] : ''); ?>';
var FILTER_TIME = '<?php echo (!empty($filters['hourmin']) && $this->timeOk ? $filters['hourmin'] : ''); ?>';
var FILTER_PEOPLE = <?php echo (!empty($filters['people']) ? $filters['people'] : 2); ?>;

var _REFRESH_MS_ = <?php echo $refresh_ms; ?>;
var _ENABLE_REFRESH_ = <?php echo ($enable_refresh ? 1 : 0); ?>;

jQuery(document).ready(function() {
	if( _ENABLE_REFRESH_ ) {
		setInterval("vrRefreshLiveMap()", _REFRESH_MS_);
	}
});

function vrRefreshLiveMap() {
	
	if( FILTER_ROOM == -1 || FILTER_DATE.length == 0 || FILTER_TIME.length == 0 ) {
		return;
	}
	
	jQuery.noConflict();
	
	var jqxhr = jQuery.ajax({
		type: "POST",
		url: "<?php echo JRoute::_('index.php?option=com_cleverdine&task=refresh_live_map&tmpl=component'); ?>",
		data: { selectedroom: FILTER_ROOM, date: FILTER_DATE, hourmin: FILTER_TIME, people: FILTER_PEOPLE }
	}).done(function(resp){
		var obj = jQuery.parseJSON(resp); 
		if( obj[0] == 1 ) {
			jQuery.each(obj[1], function(key, val){
				vrRenderTableObject(val);
			});
			
			if( last_id_res != -1 ) {
				if( !vrUpdateTableReservation(last_res_code, last_id_res) ) {
					vrDisposeResCodeDialog();
				}
			}
			
			vrChangeCurrentTime(obj[2], obj[3]);
			
			jQuery('#vrbodyreslist1').html(obj[4][0]);
			jQuery('#vrbodyreslist2').html(obj[4][1]);
			
		} else if( obj[1].length > 0 ){
			alert(obj[1]);
		}
	}).fail(function(resp){
		console.log(resp);
	});
}

function vrRenderTableObject(table) {
	
	// UPDATE TABLE STATUS CLASS
	jQuery('#vrt'+table['id']).removeClass('vrtgreen vrtred vrtyellow');
	jQuery('#vrt'+table['id']).addClass(table['class']);
	
	// UPDATE TABLE ACTION COMMANDS
	jQuery('#vrt'+table['id']+' .vrtamblinks').html(table['action_command']);
	
	// UPDATE RESERVATION CODE
	if( table['code'].length > 0 ) {
		jQuery('#vrt'+table['id']+" .vrtrescode").replaceWith(table['code_html']);
	} else {
		jQuery('#vrt'+table['id']+" .vrtrescode").remove();
	}
	
	// UPDATE TABLE-RESERVATIONS ASSOC
	jQuery('vrtresassoc'+table['id']).val(table['res_assoc']);
	
	// UPDATE TABLE CUSTOMER INFO
	if( table['id_reservation'].length == 1 ) {
		reservationMap[table['id_reservation'][0]] = {
			tname: table['name'],
			time: table['time'],
			custname: table['customer']['name'],
			custmail: table['customer']['mail'],
			custphone: table['customer']['phone'],
			timeleft: table['timeleft']
		};
		
		if( last_id_res == table['id_reservation'][0] ) {
			last_res_code = table['rescode'];
		}
	}
}

function vrChangeCurrentTime(hour_min, date_text) {
	console.log("refreshed map for time: ["+hour_min+"]");
	jQuery('#vrselecthour').val(hour_min);
	
	date_text = date_text.split('|');
	jQuery('.vroversight-current-date').html(date_text[0]);
	jQuery('.vroversight-current-time').html(date_text[1]);
	jQuery('.vroversight-current-people').html(date_text[2]);
}

function vrListTabPressed(tab) {
	jQuery('.vroversight-reservations-tab').removeClass('vroversight-selected-tab');
	jQuery('#vrheadtab'+tab).addClass('vroversight-selected-tab');
	
	jQuery('.vroversight-reservations-list').hide();
	jQuery('#vrlisttab'+tab).show();
	jQuery('html,body').animate( {scrollTop: (jQuery('.vroversight-reservations-content').first().offset().top-5)}, {duration:'slow'} );
	
	jQuery.noConflict();
	
	var jqxhr = jQuery.ajax({
		type: "POST",
		url: "<?php echo JRoute::_('index.php?option=com_cleverdine&task=change_tab_res_list&tmpl=component'); ?>",
		data: { tab: tab }
	}).done(function(){
		
	}).fail(function(){
		
	});
}

function vrUpdateWorkingShifts() {
	jQuery('#vrselecthour').prop('disabled', true);
	
	jQuery.noConflict();
	
	var jqxhr = jQuery.ajax({
		type: "POST",
		url: "index.php",
		data: {
			option: "com_cleverdine",
			task: "get_working_shifts",
			date: jQuery('#vrdatefield').val(),
			hourmin: jQuery('#vrselecthour').val(),
			tmpl: "component" 
		}
	}).done(function(resp){
		var obj = jQuery.parseJSON(resp); 
		
		if( obj[0] && obj[1].length > 0 ) {
			jQuery('#vrselecthour').html(obj[1]);
		}
		
		jQuery('#vrselecthour').prop('disabled', false);
		
	}).fail(function(resp){
		jQuery('#vrselecthour').prop('disabled', false);
	});
}

</script>