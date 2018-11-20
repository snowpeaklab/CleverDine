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
JHtml::_('behavior.calendar');

$filters = $this->filters;

$nowdf = cleverdine::getDateFormat(true);
$nowdf = str_replace( 'd', '%d', $nowdf );
$nowdf = str_replace( 'm', '%m', $nowdf );
$nowdf = str_replace( 'Y', '%Y', $nowdf );

$date_format = cleverdine::getDateFormat(true);
$time_format = cleverdine::getTimeFormat(true);

$dt_format = $date_format.' '.$time_format;

$curr_symb 	= cleverdine::getCurrencySymb(true);
$symb_pos 	= cleverdine::getCurrencySymbPosition(true);

$last_ids = array( 0, 0 );
if( count($this->latestReservations) ) {
	$last_ids[0] = intval($this->latestReservations[0]['id']);
}
if( count($this->latestTkOrders) ) {
	$last_ids[1] = intval($this->latestTkOrders[0]['id']);
}

// RESTAURANT

$continuos 	= $this->continuos;
$shifts 	= $this->shifts;

//$min_intervals = cleverdine::getMinuteIntervals(true);
$min_intervals = $this->filters[1];
$avg_stay = cleverdine::getAverageTimeStay(true);
$worktimes = array();

if( count( $continuos ) == 2 ) { // CONTINUOS WORK TIME
	
	if( $continuos[0] <= $continuos[1] ) {
		for( $i = $continuos[0]; $i <= $continuos[1]; $i++ ) {
			
			for( $min = 0; $min < 60; $min+=$min_intervals ) {
				array_push($worktimes, array("hour" => $i, "min" => $min));
			}
		}
	} else {
		for( $i = 0; $i <= $continuos[1]; $i++ ) {  
			for( $min = 0; $min < 60; $min+=$min_intervals ) {
				array_push($worktimes, array("hour" => $i, "min" => $min));
			}
		}
		
		for( $i = $continuos[0]; $i <= 23; $i++ ) {
			for( $min = 0; $min < 60; $min+=$min_intervals ) {
				array_push($worktimes, array("hour" => $i, "min" => $min));
			}
		}
	}
} else { // SHIFTS WORK HOURS
	for( $k = 0, $n = count($shifts); $k < $n; $k++ ) {
		
		for( $_app = $shifts[$k]['from']; $_app <= $shifts[$k]['to']; $_app+=$min_intervals ) {
			$_hour = intval($_app/60);
			$_min = $_app%60;
			array_push($worktimes, array("hour" => $_hour, "min" => $_min));
		}
		
	}
}

$selected_ts = cleverdine::createTimestamp($filters[0], 0, 0, true);
$now = time();

$date_title = "";
if( $selected_ts <= $now && $now < $selected_ts+86400 ) {
	$date_title = JText::_('VRTODAY');
} else if( $selected_ts <= $now-86400 && $now < $selected_ts+86400*2 ) {
	$date_title = JText::_('VRYESTERDAY');
} else if( $selected_ts <= $now+86400 && $now < $selected_ts) {
	$date_title = JText::_('VRTOMORROW');
} else {
	$date = getdate($selected_ts);
	$date_title = JText::_('VRDAY'.($date['wday'] == 0 ? 7 : $date['wday']))." ".$date['mday']." ".JText::_('VRMONTH'.$date['mon'])." ".$date['year'];
}

$date_past_label = JText::_('VRDATEPAST');

$reservations_allowed = cleverdine::isReservationsAllowed(true);
$tk_reservations_allowed = cleverdine::isTakeAwayReservationsAllowed(true);

$date = getdate($selected_ts);
$prev_day = date($date_format, mktime(0, 0, 0, $date['mon'], $date['mday']-1, $date['year']));
$next_day = date($date_format, mktime(0, 0, 0, $date['mon'], $date['mday']+1, $date['year']));

// dashboard properties

$session = JFactory::getSession();

$dashboard_properties = $session->get('dashboard-properties', '', 'vre');
if( empty($dashboard_properties) ) {
	$dashboard_properties = array('restaurant' => 1, 'takeaway' => 1, 'section' => 1, 'room' => 0);
}

$rest_on_dash = cleverdine::isOnDashboard(true);
$take_on_dash = cleverdine::isTakeAwayEnabled(true);

if( $dashboard_properties['section'] == 1 && !$rest_on_dash ) {
	$dashboard_properties['section'] = 2;
}
if( $dashboard_properties['section'] == 2 && !$take_on_dash ) {
	$dashboard_properties['section'] = 1;
}

if( empty($dashboard_properties['room']) && count($this->rooms) ) {
	$dashboard_properties['room'] = $this->rooms[0]['id'];
}

$dash_refresh_time = cleverdine::getDashRefreshTime(true);

//

$vik = new VikApplication();

?>

<?php
if( $this->isTmpl ) {
	ob_start();
}
?>

<form action="index.php?option=com_cleverdine" method="post" name="adminForm" id="adminForm">

	<div class="vr-dash-main">

		<?php if( $rest_on_dash & $take_on_dash ) { ?>

			<div class="vrdash-tab-head">
				<div class="vrdash-tab-button section-tab">
					<a href="javascript: void(0);" onClick="switchDashboardSection(1, this);" class="<?php echo ($dashboard_properties['section'] == 1 ? 'active' : ''); ?>">
						<strong><?php echo JText::_('VRMANAGECONFIGTITLE1'); ?></strong>
					</a>
				</div>
				<div class="vrdash-tab-button section-tab">
					<a href="javascript: void(0);" onClick="switchDashboardSection(2, this);" class="<?php echo ($dashboard_properties['section'] == 2 ? 'active' : ''); ?>">
						<strong><?php echo JText::_('VRMANAGECONFIGTITLE2'); ?></strong>
					</a>
				</div>
			</div>

		<?php } ?>

		<div class="vrdash-refresh-time">
			<a href="javascript: void(0);" onclick="toggleDashboardListener();">
				<?php echo JText::_('VRREFRESHIN'); ?>&nbsp;<span id="refresh-time">--</span>
			</a>
		</div>
	
		<!-- RESTAURANT -->
		
		<?php if( $rest_on_dash ) { ?>

			<div class="vr-dash-section" id="vr-dash-section1" style="<?php echo ($dashboard_properties['section'] == 1 ? '' : 'display:none;'); ?>">
		
				<div class="vr-dashboard-wrapper">
					
					<div class="btn-toolbar vr-btn-toolbar">
						<div class="btn-group pull-left">
							<?php if( $reservations_allowed ) { ?>
								<button type="button" class="btn btn-danger" onClick="openStatusReservationsDialog('stop_incoming_reservations', 'dialog-confirm');">
									<?php echo JText::_('VRSTOPINCOMINGRES'); ?>
								</button>
							<?php } else { ?>
								<button type="button" class="btn btn-success" onClick="openStatusReservationsDialog('start_incoming_reservations', 'dialog-confirm');">
									<?php echo JText::_('VRSTARTINCOMINGRES'); ?>
								</button>
							<?php } ?>
						</div>

						<?php
						$all_min_int = array(10, 15, 30, 60);
						$elements = array();

						foreach( $all_min_int as $m ) {
							$elements[] = $vik->initOptionElement($m, $m.' '.JText::_('VRSHORTCUTMINUTE'), $m == $min_intervals);
						}
						?>
						<div class="btn-group pull-right">
							<div class="vr-toolbar-setfont">
								<?php echo $vik->dropdown('minint', $elements, 'vr-minint-sel'); ?>
							</div>
						</div>

						<div class="btn-group pull-right vr-toolbar-setfont">
							<?php
							$attr = array();
							$attr['class'] 		= 'vrdatefilter';
							$attr['onChange'] 	= 'document.adminForm.submit();';
							echo $vik->calendar($filters[0], 'datefilter', 'vrdatefilter', null, $attr); ?></td>
						</div>
					</div>

					<!-- RESTAURANT OVERVIEW -->

					<div class="vr-dashboard-box">

						<div class="vrdash-title"><i class="fa fa-calendar"></i>&nbsp;<?php echo JText::_('VROVERVIEW'); ?></div>

						<div class="vrdash-container">

							<div class="vrdash-tab-head">
								<?php foreach( $this->rooms as $room ) { ?>
									<div class="vrdash-tab-button room-tab">
										<a href="javascript: void(0);" onClick="switchOverviewDashboardTab(<?php echo $room['id']; ?>, this);" class="<?php echo ($room['id'] == $dashboard_properties['room'] ? 'active' : ''); ?>">
											<?php echo $room['name'].($room['closed'] ? ' <i class="fa fa-ban big" style="color:#900;"></i>' : ''); ?>
										</a>
									</div>
								<?php } ?>
							</div>
					
							<?php foreach( $this->rooms as $room ) { ?>
									
								<div class="vr-dash-roomcont" id="vr-dash-roomcont<?php echo $room['id']; ?>" style="<?php echo ($room['id'] == $dashboard_properties['room'] ? '' : 'display:none;'); ?>">
									
									<div class="vr-dash-roomcont-title">
										<div class="title-left">
											<a href="index.php?option=com_cleverdine&datefilter=<?php echo $prev_day; ?>">
												<i class="fa fa-chevron-left big"></i>
											</a>
										</div>
										<div class="title-middle">
											<?php echo $date_title.($room['closed'] ? ' <span style="color:#900;">('.JText::_('VRROOMSTATUSCLOSED').')</span>' : ''); ?>
										</div>
										<div class="title-right">
											<a href="index.php?option=com_cleverdine&datefilter=<?php echo $next_day; ?>">
												<i class="fa fa-chevron-right big"></i>
											</a>
										</div>
									</div>
									
									<div class="vr-dash-roomcont-table">
										<table class="vr-dashboard-overview-table">
											<thead>
												<th>&nbsp;</th>
												<?php foreach($worktimes as $time) { ?>
													<th><?php echo date($time_format, mktime($time['hour'], $time['min'], 0, 1, 1, 2000)); ?></th>
												<?php } ?>
											</thead>

											<?php foreach($room['tables'] as $table ) { ?>
												<tr>
													<td class="first"><?php echo $table['name']." (".$table['min']."-".$table['max'].")"; ?></td>
													<?php foreach($worktimes as $time) { 
														$ts = cleverdine::createTimestamp($filters[0], $time['hour'], $time['min']);
														$info = null;
														
														$info_ids = array();
														$purchasers_name = array();
														$all_statuses = array();

														if( !empty($this->bookings[$table['id']]) ) {
															for( $i = 0; $i < count($this->bookings[$table['id']]); $i++ ) {

																$stay_time = (int) $this->bookings[$table['id']][$i]['stay_time'];
																if (empty($stay_time)) {
																	$stay_time = $avg_stay;
																}
																$stay_time *= 60;

																if( $this->bookings[$table['id']][$i]['checkin_ts'] <= $ts && $ts < $this->bookings[$table['id']][$i]['checkin_ts']+$stay_time ) {
																	if( $info === null ) {
																		$info = $this->bookings[$table['id']][$i];
																	}

																	$info_ids[] 		= $this->bookings[$table['id']][$i]['id'];
																	$purchasers_name[] 	= $this->bookings[$table['id']][$i]['purchaser_nominative'];

																	$s = $this->bookings[$table['id']][$i]['status'];
																	if( !array_key_exists($s, $all_statuses) ) {
																		$all_statuses[$s] = 0;
																	}
																	$all_statuses[$s]++;
																}
															} 
														}
														
														$class = "";
														$style = "";
														$title = "";
														$link_href = "javascript:void(0);";
														$link_target = "";

														if( $info === null ) {

															if( $ts > $now ) {
																$link_href = "index.php?option=com_cleverdine&task=newreservation&date=".$filters[0]."&hourmin=".$time['hour'].":".$time['min']."&people=".$table['min']."&idt=".$table['id']."&from=restaurant";
															} else {
																$class = "red";
																$title = $date_past_label;
															}

														} else {

															$class = ($info['status'] == 'CONFIRMED' ? 'green' : 'orange');

															if( count($info_ids) == 1 ) {
																$title = $info['purchaser_nominative'];

																$link_href = "index.php?option=com_cleverdine&task=editreservation&cid[]=".$info['id']."&from=restaurant";
															} else {
																$title = implode(', ', $purchasers_name);

																$link_href = "index.php?option=com_cleverdine&task=reservations&cid[]=".implode('&cid[]=', $info_ids);

																$link_target = 'target="_blank"';

																if( ($gradient = cleverdine::getCssGradientFromStatuses($all_statuses)) ) {
																	$class = '';
																	$style = $gradient;
																}
															}

															if( $info['checkin_ts'] == $ts ) {
																$class .= ' first-cell';
															} else {
																$class .= ' contiguous-cell';
																$info['rescode'] = ''; // do not display res codes for contiguous cells
															}
														} ?>
														
														<td class="<?php echo $class; ?>" style="<?php echo $style; ?>">
															<a href="<?php echo $link_href; ?>" title="<?php echo $title; ?>" data-original-title="<?php echo $title; ?>" class="vr-sight-cal-box <?php echo (strlen($title) ? 'hasTooltip' : ''); ?>" <?php echo $link_target; ?>>
																<?php if( count($info_ids) == 1 && $info['rescode'] > 0 ) { ?>
																	<?php if( empty($info['code_icon']) ) {
																		echo $info['code'];
																	} else { ?>
																		<img src="<?php echo JUri::root().'components/com_cleverdine/assets/media/'.$info['code_icon']; ?>"/>
																	<?php } ?>
																<?php } ?>
															</a>
														</td>
														
													<?php } ?>
												</tr>
											<?php } ?>
										</table>
									</div>
										
								</div>
									
							<?php } ?>

						</div>
					</div>
					
				</div>
				
				<!-- RESTAURANT RESERVATIONS -->
				
				<?php
				$restaurant_active_tab = $dashboard_properties['restaurant'];
				?>
				
				<div class="vr-dashboard-box">

					<div class="vrdash-title"><i class="fa fa-shopping-basket"></i>&nbsp;<?php echo JText::_('VRMENURESERVATIONS'); ?></div>

					<div class="vrdash-container">
						<div class="vrdash-tab-head">
							<div class="vrdash-tab-button restaurant-tab">
								<a href="javascript: void(0);" onClick="switchRestaurantDashboardTab(1, this);" class="<?php echo ($restaurant_active_tab == 1 ? 'active' : ''); ?>">
									<?php echo JText::_('VRDASHLATESTRESERVATIONS'); ?>
								</a>
							</div>
							<div class="vrdash-tab-button restaurant-tab">
								<a href="javascript: void(0);" onClick="switchRestaurantDashboardTab(2, this);" class="<?php echo ($restaurant_active_tab == 2 ? 'active' : ''); ?>">
									<?php echo JText::_('VRDASHINCOMINGRESERVATIONS'); ?>
								</a>
							</div>
							<div class="vrdash-tab-button restaurant-tab">
								<a href="javascript: void(0);" onClick="switchRestaurantDashboardTab(3, this);" class="<?php echo ($restaurant_active_tab == 3 ? 'active' : ''); ?>">
									<?php echo JText::_('VRDASHCURRENTRESERVATIONS'); ?>
								</a>
							</div>
						</div>
						
						<table id="vrdash-restaurant-list1" class="vr-incoming-table restaurant-list listener" style="<?php echo ($restaurant_active_tab != 1 ? 'display:none;' : ''); ?>">
							<th class="vrdashtabtitle" width="75" style="text-align: left;"><?php echo JText::_('VRMANAGERESERVATION1'); ?></th>
							<th class="vrdashtabtitle" width="100" style="text-align: center;"><?php echo JText::_('VRMANAGERESERVATION21'); ?></th>
							<th class="vrdashtabtitle" width="100" style="text-align: center;"><?php echo JText::_('VRMANAGERESERVATION3'); ?></th>
							<th class="vrdashtabtitle" width="75" style="text-align: center;"><?php echo JText::_('VRMANAGERESERVATION4'); ?></th>
							<th class="vrdashtabtitle" width="75" style="text-align: center;"><?php echo JText::_('VRMANAGERESERVATION5'); ?></th>
							<th class="vrdashtabtitle" width="150" style="text-align: center;"><?php echo JText::_('VRMANAGERESERVATION17'); ?></th>
							<th class="vrdashtabtitle" width="100" style="text-align: center;"><?php echo JText::_('VRMANAGERESERVATION12'); ?></th>
							
							<?php foreach( $this->latestReservations as $r ) { ?>
								<tr class="<?php echo ( ( $this->isTmpl && $this->ajaxParams['from'][0] < $r['id'] ) ? 'vrdashrowhighlight' : ''); ?>">
									<td><?php echo $r['id']; ?> - <a href="index.php?option=com_cleverdine&task=printorders&tmpl=component&cid[]=<?php echo $r['id']; ?>" target="_blank"><i class="fa fa-print"></i></a></td>
									<td style="text-align: center;"><?php echo ($r['created_on'] != -1 ? cleverdine::formatTimestamp($dt_format, $r['created_on']) : ''); ?></td>
									<td style="text-align: center;"><a href="index.php?option=com_cleverdine&task=editreservation&cid[]=<?php echo $r['id']; ?>" target="_blank"><?php echo date( $dt_format, $r['checkin_ts'] ); ?></a></td>
									<td style="text-align: center;"><?php echo $r['people']; ?></td>
									<td style="text-align: center;"><?php echo $r['tname']; ?></td>
									<td style="text-align: center;"><?php echo $r['purchaser_nominative']; ?></td>
									<td style="text-align: center;" class="<?php echo 'vrreservationstatus'.strtolower($r['status']); ?>"><?php echo JText::_('VRRESERVATIONSTATUS'.$r['status']); ?></td>
								</tr>
							<?php } ?>
						</table>
						
						<table id="vrdash-restaurant-list2" class="vr-incoming-table restaurant-list listener" style="<?php echo ($restaurant_active_tab != 2 ? 'display:none;' : ''); ?>">
							<th class="vrdashtabtitle" width="75" style="text-align: left;"><?php echo JText::_('VRMANAGERESERVATION1'); ?></th>
							<th class="vrdashtabtitle" width="100" style="text-align: center;"><?php echo JText::_('VRMANAGERESERVATION21'); ?></th>
							<th class="vrdashtabtitle" width="100" style="text-align: center;"><?php echo JText::_('VRMANAGERESERVATION3'); ?></th>
							<th class="vrdashtabtitle" width="75" style="text-align: center;"><?php echo JText::_('VRMANAGERESERVATION4'); ?></th>
							<th class="vrdashtabtitle" width="75" style="text-align: center;"><?php echo JText::_('VRMANAGERESERVATION5'); ?></th>
							<th class="vrdashtabtitle" width="150" style="text-align: center;"><?php echo JText::_('VRMANAGERESERVATION17'); ?></th>
							<th class="vrdashtabtitle" width="100" style="text-align: center;"><?php echo JText::_('VRMANAGERESERVATION12'); ?></th>
							
							<?php foreach( $this->incomingReservations as $r ) { ?>
								<tr class="<?php echo ( ( $this->isTmpl && $this->ajaxParams['from'][0] < $r['id'] ) ? 'vrdashrowhighlight' : ''); ?>">
									<td><?php echo $r['id']; ?> - <a href="index.php?option=com_cleverdine&task=printorders&tmpl=component&cid[]=<?php echo $r['id']; ?>" target="_blank"><i class="fa fa-print"></i></a></td>
									<td style="text-align: center;"><?php echo ($r['created_on'] != -1 ? cleverdine::formatTimestamp($dt_format, $r['created_on']) : ''); ?></td>
									<td style="text-align: center;"><a href="index.php?option=com_cleverdine&task=editreservation&cid[]=<?php echo $r['id']; ?>" target="_blank"><?php echo date( $dt_format, $r['checkin_ts'] ); ?></a></td>
									<td style="text-align: center;"><?php echo $r['people']; ?></td>
									<td style="text-align: center;"><?php echo $r['tname']; ?></td>
									<td style="text-align: center;"><?php echo $r['purchaser_nominative']; ?></td>
									<td style="text-align: center;" class="<?php echo 'vrreservationstatus'.strtolower($r['status']); ?>"><?php echo JText::_('VRRESERVATIONSTATUS'.$r['status']); ?></td>
								</tr>
							<?php } ?>
						</table>

						<table id="vrdash-restaurant-list3" class="vr-incoming-table restaurant-list listener" style="<?php echo ($restaurant_active_tab != 3 ? 'display:none;' : ''); ?>">
							<th class="vrdashtabtitle" width="75" style="text-align: left;"><?php echo JText::_('VRMANAGERESERVATION1'); ?></th>
							<th class="vrdashtabtitle" width="100" style="text-align: center;"><?php echo JText::_('VRMANAGERESERVATION3'); ?></th>
							<th class="vrdashtabtitle" width="100" style="text-align: center;"><?php echo JText::_('VRMANAGERESERVATION24'); ?></th>
							<th class="vrdashtabtitle" width="75" style="text-align: center;"><?php echo JText::_('VRMANAGERESERVATION4'); ?></th>
							<th class="vrdashtabtitle" width="75" style="text-align: center;"><?php echo JText::_('VRMANAGERESERVATION5'); ?></th>
							<th class="vrdashtabtitle" width="150" style="text-align: center;"><?php echo JText::_('VRMANAGERESERVATION17'); ?></th>
							<th class="vrdashtabtitle" width="75" style="text-align: center;"><?php echo JText::_('VRMANAGERESERVATION19'); ?></th>

							<?php foreach( $this->currentReservations as $r ) { 
								$stay_time = (int) $r['stay_time'];
								if (empty($stay_time)) {
									$stay_time = $avg_stay;
								}
								$stay_time *= 60;
								?>
								<tr class="<?php echo ( ( $this->isTmpl && $this->ajaxParams['from'][0] < $r['id'] ) ? 'vrdashrowhighlight' : ''); ?>">
									<td><?php echo $r['id']; ?> - <a href="index.php?option=com_cleverdine&task=printorders&tmpl=component&cid[]=<?php echo $r['id']; ?>" target="_blank"><i class="fa fa-print"></i></a></td>
									<td style="text-align: center;"><a href="index.php?option=com_cleverdine&task=editreservation&cid[]=<?php echo $r['id']; ?>" target="_blank"><?php echo cleverdine::formatTimestamp($dt_format, $r['checkin_ts']); ?></a></td>
									<td style="text-align: center;"><?php echo cleverdine::formatTimestamp($dt_format, $r['checkin_ts']+$stay_time); ?></td>
									<td style="text-align: center;"><?php echo $r['people']; ?></td>
									<td style="text-align: center;"><?php echo $r['tname']; ?></td>
									<td style="text-align: center;"><?php echo $r['purchaser_nominative']; ?></td>
									<td style="text-align: center;">
										<?php if( empty($r['code_icon']) ) {
											echo (!empty($r['code']) ? $r['code'] : '--');
										} else { ?>
											<img src="<?php echo JUri::root().'components/com_cleverdine/assets/media/'.$r['code_icon']; ?>" title="<?php echo $r['code']; ?>" />
										<?php } ?>
									</td>
								</tr>
							<?php } ?>
						</table>
					</div>
				</div>

			</div>
		
		<?php } ?>
		
		<!-- TAKEAWAY ORDERS -->
		
		<?php if( $take_on_dash ) { ?>

			<div class="vr-dash-section" id="vr-dash-section2" style="<?php echo ($dashboard_properties['section'] == 2 ? '' : 'display:none;'); ?>">

				<div class="btn-toolbar vr-btn-toolbar">
					<div class="btn-group pull-left">
						<?php if( $tk_reservations_allowed ) { ?>
							<button type="button" class="btn btn-danger" onClick="openStatusReservationsDialog('stop_incoming_tkreservations', 'tk-dialog-confirm');">
								<?php echo JText::_('VRSTOPINCOMINGORD'); ?>
							</button>
						<?php } else { ?>
							<button type="button" class="btn btn-success" onClick="openStatusReservationsDialog('start_incoming_tkreservations', 'tk-dialog-confirm');">
								<?php echo JText::_('VRSTARTINCOMINGORD'); ?>
							</button>
						<?php } ?>
					</div>
				</div>
				
				<?php
				$takeaway_active_tab = $dashboard_properties['takeaway'];
				?>
				
				<div class="vr-dashboard-box">

					<div class="vrdash-title"><i class="fa fa-shopping-basket"></i>&nbsp;<?php echo JText::_('VRMENUTAKEAWAYRESERVATIONS'); ?></div>

					<div class="vrdash-container">
						<div class="vrdash-tab-head">
							<div class="vrdash-tab-button takeaway-tab">
								<a href="javascript: void(0);" onClick="switchTakeawayDashboardTab(1, this);" class="<?php echo ($takeaway_active_tab == 1 ? 'active' : ''); ?>">
									<?php echo JText::_('VRDASHLATESTTKORDERS'); ?>
								</a>
							</div>
							<div class="vrdash-tab-button takeaway-tab">
								<a href="javascript: void(0);" onClick="switchTakeawayDashboardTab(2, this);" class="<?php echo ($takeaway_active_tab == 2 ? 'active' : ''); ?>">
									<?php echo JText::_('VRDASHINCOMINGTKORDERS'); ?>
								</a>
							</div>
							<div class="vrdash-tab-button takeaway-tab">
								<a href="javascript: void(0);" onClick="switchTakeawayDashboardTab(3, this);" class="<?php echo ($takeaway_active_tab == 3 ? 'active' : ''); ?>">
									<?php echo JText::_('VRDASHCURRENTTKORDERS'); ?>
								</a>
							</div>
						</div>
						
						<table id="vrdash-takeaway-list1" class="vr-incoming-table takeaway-list listener" style="<?php echo ($takeaway_active_tab != 1 ? 'display:none;' : ''); ?>">
							<th class="vrdashtabtitle" width="75" style="text-align: left;"><?php echo JText::_('VRMANAGETKRES1'); ?></th>
							<th class="vrdashtabtitle" width="100" style="text-align: center;"><?php echo JText::_('VRMANAGETKRES28'); ?></th>
							<th class="vrdashtabtitle" width="100" style="text-align: center;"><?php echo JText::_('VRMANAGETKRES3'); ?></th>
							<th class="vrdashtabtitle" width="75" style="text-align: center;"><?php echo JText::_('VRMANAGETKRES4'); ?></th>
							<th class="vrdashtabtitle" width="75" style="text-align: center;"><?php echo JText::_('VRMANAGETKRES8'); ?></th>
							<th class="vrdashtabtitle" width="150" style="text-align: center;"><?php echo JText::_('VRMANAGETKRES24'); ?></th>
							<th class="vrdashtabtitle" width="100" style="text-align: center;"><?php echo JText::_('VRMANAGETKRES9'); ?></th>
							
							<?php foreach( $this->latestTkOrders as $r ) { ?>
								<tr class="<?php echo ( ( $this->isTmpl && $this->ajaxParams['from'][1] < $r['id'] ) ? 'vrdashrowhighlight' : ''); ?>">
									<td><?php echo $r['id']; ?> - <a href="index.php?option=com_cleverdine&task=tkprintorders&tmpl=component&cid[]=<?php echo $r['id']; ?>" target="_blank"><i class="fa fa-print"></i></a></td>
									<td style="text-align: center;"><?php echo ($r['created_on'] != -1 ? cleverdine::formatTimestamp($dt_format, $r['created_on']) : ''); ?></td>
									<td style="text-align: center;"><a href="index.php?option=com_cleverdine&task=edittkreservation&cid[]=<?php echo $r['id']; ?>" target="_blank"><?php echo date( $dt_format, $r['checkin_ts'] ); ?></a></td>
									<td style="text-align: center;"><?php echo JText::_($r['delivery_service'] ? 'VRMANAGETKRES14' : 'VRMANAGETKRES15'); ?></td>
									<td style="text-align: center;"><?php echo cleverdine::printPriceCurrencySymb($r['total_to_pay'], $curr_symb, $symb_pos); ?></td>
									<td style="text-align: center;"><?php echo $r['purchaser_nominative']; ?></td>
									<td style="text-align: center;" class="<?php echo 'vrreservationstatus'.strtolower($r['status']); ?>"><?php echo JText::_('VRRESERVATIONSTATUS'.$r['status']); ?></td>
								</tr>
							<?php } ?>
						</table>
						
						<table id="vrdash-takeaway-list2" class="vr-incoming-table takeaway-list listener" style="<?php echo ($takeaway_active_tab != 2 ? 'display:none;' : ''); ?>">
							<th class="vrdashtabtitle" width="75" style="text-align: left;"><?php echo JText::_('VRMANAGETKRES1'); ?></th>
							<th class="vrdashtabtitle" width="100" style="text-align: center;"><?php echo JText::_('VRMANAGETKRES28'); ?></th>
							<th class="vrdashtabtitle" width="100" style="text-align: center;"><?php echo JText::_('VRMANAGETKRES3'); ?></th>
							<th class="vrdashtabtitle" width="75" style="text-align: center;"><?php echo JText::_('VRMANAGETKRES4'); ?></th>
							<th class="vrdashtabtitle" width="75" style="text-align: center;"><?php echo JText::_('VRMANAGETKRES8'); ?></th>
							<th class="vrdashtabtitle" width="150" style="text-align: center;"><?php echo JText::_('VRMANAGETKRES24'); ?></th>
							<th class="vrdashtabtitle" width="75" style="text-align: center;"><?php echo JText::_('VRMANAGETKRES26'); ?></th>
							<th class="vrdashtabtitle" width="100" style="text-align: center;"><?php echo JText::_('VRMANAGETKRES9'); ?></th>
							
							<?php foreach( $this->incomingTkOrders as $r ) { 

								$route_obj = json_decode($r['route']);

								$route_details = '';
								$keys = array('distancetext' => 'road', 'durationtext' => 'clock-o');

								foreach( $keys as $k => $icon ) {
									if( !empty($route_obj->$k) ) {

										$route_details .= '<i class="fa fa-'.$icon.'" style="margin-right:5px;margin-left: 15px;"></i>'.$route_obj->$k;
									}
								}

								?>
								<tr class="<?php echo ( ( $this->isTmpl && $this->ajaxParams['from'][1] < $r['id'] ) ? 'vrdashrowhighlight' : ''); ?>">
									<td>
										<?php echo $r['id']; ?>&nbsp;-&nbsp;<a href="index.php?option=com_cleverdine&task=tkprintorders&tmpl=component&cid[]=<?php echo $r['id']; ?>" target="_blank"><i class="fa fa-print"></i></a>&nbsp;&nbsp;<a href="javascript: void(0);" onClick="vrToggleOrderDetails(this, true);"><i class="fa fa-bars"></i></a>
									</td>
									<td style="text-align: center;"><?php echo ($r['created_on'] != -1 ? cleverdine::formatTimestamp($dt_format, $r['created_on']) : ''); ?></td>
									<td style="text-align: center;"><a href="index.php?option=com_cleverdine&task=edittkreservation&cid[]=<?php echo $r['id']; ?>" target="_blank">
										<?php if( ($r['checkin_ts']-time()) < 3600 ) {
											echo cleverdine::formatTimestamp($dt_format, $r['checkin_ts']);
										} else {
											echo date($dt_format, $r['checkin_ts']);
										} ?>
									</a></td>
									<td style="text-align: center;"><?php echo JText::_($r['delivery_service'] ? 'VRMANAGETKRES14' : 'VRMANAGETKRES15'); ?></td>
									<td style="text-align: center;"><?php echo cleverdine::printPriceCurrencySymb($r['total_to_pay'], $curr_symb, $symb_pos); ?></td>
									<td style="text-align: center;">
										<?php echo $r['purchaser_nominative']; ?>
									</td>
									<td style="text-align: center;">
										<?php if( empty($r['code_icon']) ) {
											echo (!empty($r['code']) ? $r['code'] : '--');
										} else { ?>
											<img src="<?php echo JUri::root().'components/com_cleverdine/assets/media/'.$r['code_icon']; ?>" title="<?php echo $r['code']; ?>" />
										<?php } ?>
									</td>
									<td style="text-align: center;" class="<?php echo 'vrreservationstatus'.strtolower($r['status']); ?>"><?php echo JText::_('VRRESERVATIONSTATUS'.$r['status']); ?></td>
								</tr>

								<tr class="vrdash-details-row" data-id="<?php echo $r['id']; ?>" style="<?php echo (in_array($r['id'], $this->ajaxParams['details_list'][1]) ? '' : 'display: none;'); ?>">
									<td style="text-align: left;" colspan="7">
										<?php if( isset($route_obj->origin) && strlen($route_obj->origin) ) { ?>
											<span style="margin-right:5px;">
												<i class="fa fa-map-pin" style="margin-right:5px;"></i><?php echo $route_obj->origin; ?><i class="fa fa-long-arrow-right" style="margin-left:5px;"></i>
											</span>
										<?php } ?>
										<?php if( strlen($r['purchaser_address']) ) { ?>
											<span style="margin-right:5px;"><?php echo $r['purchaser_address']; ?></span>
										<?php } ?>
										<?php if( strlen($route_details) ) { ?>
											<span style="margin-right:5px;"><?php echo $route_details; ?></span>
										<?php } ?>
										<span style="margin-right: 5px;">
											<i class="fa fa-fire" style="margin-right:5px;margin-left:15px;"></i><?php echo JText::sprintf('VRTKRESITEMSINCART', $r['items_preparation_count'], $r['items_count']); ?>
										</span>
									</td>

									<td style="text-align: center;">
										<a href="javascript: void(0);" onClick="vrToggleOrderDetails(this, false);">
											<i class="fa fa-close"></i>
										</a>
									</td>
								</tr>

							<?php } ?>
						</table>

						<table id="vrdash-takeaway-list3" class="vr-incoming-table takeaway-list listener" style="<?php echo ($takeaway_active_tab != 3 ? 'display:none;' : ''); ?>">
							<th class="vrdashtabtitle" width="75" style="text-align: left;"><?php echo JText::_('VRMANAGETKRES1'); ?></th>
							<th class="vrdashtabtitle" width="100" style="text-align: center;"><?php echo JText::_('VRMANAGETKRES28'); ?></th>
							<th class="vrdashtabtitle" width="100" style="text-align: center;"><?php echo JText::_('VRMANAGETKRES3'); ?></th>
							<th class="vrdashtabtitle" width="75" style="text-align: center;"><?php echo JText::_('VRMANAGETKRES4'); ?></th>
							<th class="vrdashtabtitle" width="75" style="text-align: center;"><?php echo JText::_('VRMANAGETKRES8'); ?></th>
							<th class="vrdashtabtitle" width="150" style="text-align: center;"><?php echo JText::_('VRMANAGETKRES24'); ?></th>
							<th class="vrdashtabtitle" width="75" style="text-align: center;"><?php echo JText::_('VRMANAGETKRES26'); ?></th>
							
							<?php foreach( $this->currentTkOrders as $r ) { 

								$route_obj = json_decode($r['route']);

								$route_details = '';
								$keys = array('distancetext' => 'road', 'durationtext' => 'clock-o');

								foreach( $keys as $k => $icon ) {
									if( !empty($route_obj->$k) ) {

										$route_details .= '<i class="fa fa-'.$icon.'" style="margin-right:5px;margin-left: 15px;"></i>'.$route_obj->$k;
									}
								}

								?>
								<tr class="<?php echo ( ( $this->isTmpl && $this->ajaxParams['from'][1] < $r['id'] ) ? 'vrdashrowhighlight' : ''); ?>">
									<td>
										<?php echo $r['id']; ?>&nbsp;-&nbsp;<a href="index.php?option=com_cleverdine&task=tkprintorders&tmpl=component&cid[]=<?php echo $r['id']; ?>" target="_blank"><i class="fa fa-print"></i></a>&nbsp;&nbsp;<a href="javascript: void(0);" onClick="vrToggleOrderDetails(this, true);"><i class="fa fa-bars"></i></a>
									</td>
									<td style="text-align: center;"><?php echo ($r['created_on'] != -1 ? cleverdine::formatTimestamp($dt_format, $r['created_on']) : ''); ?></td>
									<td style="text-align: center;"><a href="index.php?option=com_cleverdine&task=edittkreservation&cid[]=<?php echo $r['id']; ?>" target="_blank">
										<?php if( ($r['checkin_ts']-time()) < 3600 ) {
											echo cleverdine::formatTimestamp($dt_format, $r['checkin_ts']);
										} else {
											echo date($dt_format, $r['checkin_ts']);
										} ?>
									</a></td>
									<td style="text-align: center;"><?php echo JText::_($r['delivery_service'] ? 'VRMANAGETKRES14' : 'VRMANAGETKRES15'); ?></td>
									<td style="text-align: center;"><?php echo cleverdine::printPriceCurrencySymb($r['total_to_pay'], $curr_symb, $symb_pos); ?></td>
									<td style="text-align: center;">
										<?php echo $r['purchaser_nominative']; ?>
									</td>
									<td style="text-align: center;">
										<?php if( empty($r['code_icon']) ) {
											echo (!empty($r['code']) ? $r['code'] : '--');
										} else { ?>
											<img src="<?php echo JUri::root().'components/com_cleverdine/assets/media/'.$r['code_icon']; ?>" title="<?php echo $r['code']; ?>" />
										<?php } ?>
									</td>
								</tr>

								<tr class="vrdash-details-row" data-id="<?php echo $r['id']; ?>" style="<?php echo (in_array($r['id'], $this->ajaxParams['details_list'][1]) ? '' : 'display: none;'); ?>">
									<td style="text-align: left;" colspan="6">
										<?php if( isset($route_obj->origin) && strlen($route_obj->origin) ) { ?>
											<span style="margin-right:5px;">
												<i class="fa fa-map-pin" style="margin-right:5px;"></i><?php echo $route_obj->origin; ?><i class="fa fa-long-arrow-right" style="margin-left:5px;"></i>
											</span>
										<?php } ?>
										<?php if( strlen($r['purchaser_address']) ) { ?>
											<span style="margin-right:5px;"><?php echo $r['purchaser_address']; ?></span>
										<?php } ?>
										<?php if( strlen($route_details) ) { ?>
											<span style="margin-right:5px;"><?php echo $route_details; ?></span>
										<?php } ?>
										<span style="margin-right: 5px;">
											<i class="fa fa-fire" style="margin-right:5px;margin-left:15px;"></i><?php echo JText::sprintf('VRTKRESITEMSINCART', $r['items_preparation_count'], $r['items_count']); ?>
										</span>
									</td>

									<td style="text-align: center;">
										<a href="javascript: void(0);" onClick="vrToggleOrderDetails(this, false);">
											<i class="fa fa-close"></i>
										</a>
									</td>
								</tr>

							<?php } ?>
						</table>
					</div>
				</div>

			</div>
		
		<?php } ?>

	</div>
	
	<?php if( $this->isTmpl ) { ?>
		<script type="text/javascript">
			_LAST_ID_ = <?php echo json_encode($last_ids); ?>;
			if( _LAST_ID_[0] > <?php echo $this->ajaxParams['last'][0]; ?> || _LAST_ID_[1] > <?php echo $this->ajaxParams['last'][1]; ?> ) {
				playNotificationSound();
			}
			
			jQuery('.tooltip.fade').remove();
			jQuery('.vr-sight-cal-box').tooltip();
			
			if (jQuery('#vrdatefilter').length > 0) {
				Calendar.setup({
					// Id of the input field
					inputField: "vrdatefilter",
					// Format of the input field
					ifFormat: "<?php echo $nowdf; ?>",
					// Trigger for the calendar (button ID)
					button: "vrdatefilter_img",
					// Alignment (defaults to "B1")
					align: "Tl",
					singleClick: true,
					firstDay: 0
				});

				jQuery('#vrdatefilter').on('change', function(){
					document.adminForm.submit();
				});
			}

			jQuery('#vr-minint-sel').select2({
				minimumResultsForSearch: -1,
				allowClear: false,
				width: 150
			});

			jQuery('#vr-minint-sel').on('change', function(){
				document.adminForm.submit();
			});
		</script>
	<?php } ?>
	
	<input type="hidden" name="task" value="restaurant">
</form>

<?php if( !$this->isTmpl ) { ?>

<audio preload="true" id="vr-notification-audio">
	<source src="<?php echo JUri::root().'administrator/components/com_cleverdine/assets/audio/notification.mp3'; ?>" type="audio/mpeg" />
</audio>

<?php
	$tomorrow = getdate();
	$tomorrow = mktime(0, 0, 0, $tomorrow['mon'], $tomorrow['mday']+1, $tomorrow['year']);
?>

<div id="dialog-confirm" title="<?php echo JText::_(($reservations_allowed ? 'VRSTOPINCOMINGRES' : 'VRSTARTINCOMINGRES'));?>" style="display: none;">
	<p>
		<span class="ui-icon ui-icon-locked" style="float: left; margin: 0 7px 20px 0;"></span>
		<?php if( $reservations_allowed ) { ?>
			<span><?php echo JText::sprintf('VRSTOPRESDIALOGMESSAGE', date($date_format, $tomorrow)); ?></span>
		<?php } else { ?>
			<span><?php echo JText::_('VRSTARTRESDIALOGMESSAGE'); ?></span>
		<?php } ?>
	</p>
</div>

<div id="tk-dialog-confirm" title="<?php echo JText::_(($tk_reservations_allowed ? 'VRSTOPINCOMINGORD' : 'VRSTARTINCOMINGORD'));?>" style="display: none;">
	<p>
		<span class="ui-icon ui-icon-locked" style="float: left; margin: 0 7px 20px 0;"></span>
		<?php if( $tk_reservations_allowed ) { ?>
			<span><?php echo JText::sprintf('VRSTOPORDDIALOGMESSAGE', date($date_format, $tomorrow)); ?></span>
		<?php } else { ?>
			<span><?php echo JText::_('VRSTARTORDDIALOGMESSAGE'); ?></span>
		<?php } ?>
	</p>
</div>

<script type="text/javascript">

	var _LAST_ID_ = <?php echo json_encode($last_ids); ?>;
	var _FROM_ID_ = <?php echo json_encode($last_ids); ?>;

	const DASH_REFRESH_TIME = <?php echo $dash_refresh_time; ?>;
	const DASH_REFRESH_STEPS = 1;
	var DASH_REFRESH_COUNT = 0;
	var DASH_THREAD = null;
	
	jQuery(document).ready(function(){
		
		startDashboardListener();

		jQuery('#vr-minint-sel').select2({
			minimumResultsForSearch: -1,
			allowClear: false,
			width: 150
		});

		jQuery('#vr-minint-sel').on('change', function(){
			document.adminForm.submit();
		});
	});

	function startDashboardListener() {
		if( DASH_THREAD ) {
			stopDashboardListener();
		}

		updateRemainingTime( DASH_REFRESH_TIME - DASH_REFRESH_COUNT, '' );

		DASH_THREAD = setInterval("refreshDashboardListener()", DASH_REFRESH_STEPS*1000);
	}

	function stopDashboardListener() {
		clearInterval(DASH_THREAD);

		DASH_THREAD = null;
	}

	function toggleDashboardListener() {
		if( DASH_THREAD ) {
			stopDashboardListener();
		} else {
			startDashboardListener();
		}
	}

	function refreshDashboardListener() {
		DASH_REFRESH_COUNT += DASH_REFRESH_STEPS;

		updateRemainingTime( DASH_REFRESH_TIME - DASH_REFRESH_COUNT, '' );

		if( DASH_REFRESH_COUNT >= DASH_REFRESH_TIME ) {
			DASH_REFRESH_COUNT = 0;

			stopDashboardListener();

			refreshDashboard();
		}
	}

	function updateRemainingTime(remaining_time, label) {

		if( remaining_time < 60 ) {
			
			if( remaining_time > 0 ) {

				label += (label.length > 0 ? ' & ' : '')+remaining_time+' <?php echo addslashes(JText::_('VRSHORTCUTSECOND')); ?>';

			} else if( !label.length ) {
				label = '0 <?php echo addslashes(JText::_('VRSHORTCUTSECOND')); ?>';
			}

			jQuery('#refresh-time').text(label);

		} else {
			updateRemainingTime( remaining_time%60, label+(parseInt(remaining_time/60))+' <?php echo addslashes(JText::_('VRSHORTCUTMINUTE')); ?>' );
		}

	}
	
	function refreshDashboard() {
		jQuery.noConflict();
		
		var tk_details_list = [];

		jQuery('.vrdash-details-row').each(function(){
			if( jQuery(this).is(':visible') ) {
				tk_details_list.push(jQuery(this).data('id'));
			}
		});

		var jqxhr = jQuery.ajax({
			type: "POST",
			url: "index.php?option=com_cleverdine&tmpl=component&task=restaurant",
			data: { 
				datefilter: "<?php echo $filters[0]; ?>", 
				from_id: _FROM_ID_[0],
				last_id: _LAST_ID_[0],
				from_tk_id: _FROM_ID_[1],
				last_tk_id: _LAST_ID_[1],
				tk_details_list: tk_details_list
			}
		}).done(function(resp){ 

			resp = jQuery.parseJSON(resp)[0];

			jQuery('#adminForm').replaceWith(resp);
			// restores
			jQuery('#vrdatefilter').val('<?php echo $filters[0]; ?>');
			
			jQuery('.vr-incoming-table.restaurant-list.listener tr').on('click', function(){
				jQuery(this).removeClass('vrdashrowhighlight');
				var row_id = jQuery(this).find("td:first").html().split(' - ');
				row_id = row_id[0];
				if( row_id > _FROM_ID_[0] ) {
					_FROM_ID_[0] = row_id;
				}
			});
			
			jQuery('.vr-incoming-table.takeaway-list.listener tr').on('click', function(){
				jQuery(this).removeClass('vrdashrowhighlight');
				var row_id = jQuery(this).find("td:first").html().split(' - ');
				row_id = row_id[0];
				if( row_id > _FROM_ID_[1] ) {
					_FROM_ID_[1] = row_id;
				}
			});

			startDashboardListener();

		}).fail(function(){

			startDashboardListener();

		});
	}
	
	function playNotificationSound() {
		document.getElementById('vr-notification-audio').play();
	}

	function openStatusReservationsDialog(action, dialog) {
		jQuery( "#"+dialog ).dialog({
			resizable: false,
			width: 480,
			height:240,
			modal: true,
			buttons: {
				"<?php echo JText::_('VRRENEWSESSIONCONFOK'); ?>": function() {
					jQuery( this ).dialog( "close" );
					Joomla.submitform(action, document.adminForm);
				},
				"<?php echo JText::_('VRRENEWSESSIONCONFCANC'); ?>": function() {
					jQuery( this ).dialog( "close" );
				}
			}
		});
	}
	
	function storeDashboardProperties() {
		jQuery.noConflict();
		
		var jqxhr = jQuery.ajax({
			type: "POST",
			url: "index.php?option=com_cleverdine&task=store_dashboard_properties&tmpl=component",
			data: {
				r_page: DASHBOARD_PROPERTIES.restaurant, 
				t_page: DASHBOARD_PROPERTIES.takeaway,
				s_page: DASHBOARD_PROPERTIES.section,
				room: 	DASHBOARD_PROPERTIES.room
			}
		}).done(function(resp){
			
		}).fail(function(resp){
			
		});
	}
	
	var DASHBOARD_PROPERTIES = <?php echo json_encode($dashboard_properties); ?>;

	function switchDashboardSection(page, elem) {
		jQuery('.vrdash-tab-button.section-tab a').removeClass('active');
		jQuery(elem).addClass('active');
		
		jQuery('.vr-dash-section').hide();
		jQuery('#vr-dash-section'+page).show();
		
		DASHBOARD_PROPERTIES.section = page;
		storeDashboardProperties();
	}

	function switchOverviewDashboardTab(page, elem) {
		jQuery('.vrdash-tab-button.room-tab a').removeClass('active');
		jQuery(elem).addClass('active');
		
		jQuery('.vr-dash-roomcont').hide();
		jQuery('#vr-dash-roomcont'+page).show();
		
		DASHBOARD_PROPERTIES.room = page;
		storeDashboardProperties();
	}
	
	function switchRestaurantDashboardTab(page, elem) {
		jQuery('.vrdash-tab-button.restaurant-tab a').removeClass('active');
		jQuery(elem).addClass('active');
		
		jQuery('.vr-incoming-table.restaurant-list').hide();
		jQuery('#vrdash-restaurant-list'+page).show();
		
		DASHBOARD_PROPERTIES.restaurant = page;
		storeDashboardProperties();
	}
	
	function switchTakeawayDashboardTab(page, elem) {
		jQuery('.vrdash-tab-button.takeaway-tab a').removeClass('active');
		jQuery(elem).addClass('active');
		
		jQuery('.vr-incoming-table.takeaway-list').hide();
		jQuery('#vrdash-takeaway-list'+page).show();
		
		DASHBOARD_PROPERTIES.takeaway = page;
		storeDashboardProperties();
	}

	function vrToggleOrderDetails(link, next) {
		var row = jQuery(link).closest('tr')

		if( next ) {
			row = row.next();
		}

		row.toggle();
	}
		
</script>

<?php } else {
	$content = ob_get_contents();
	ob_end_clean();

	echo json_encode(array($content));

	// the exit at the end of this file may cause error on the html encoding
	// if you are facing this kind of issue, try to comment the line below
	exit;
} ?> 
