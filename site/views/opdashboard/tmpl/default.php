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

$operator = $this->operator;
$user = JFactory::getUser();

$itemid = JFactory::getApplication()->input->get('Itemid', 0, 'uint');

$date_format = cleverdine::getDateFormat();
$time_format = cleverdine::getTimeFormat();
$dt_format = $date_format.' '.$time_format;
$curr_symb = cleverdine::getCurrencySymb();
$symb_pos = cleverdine::getCurrencySymbPosition();

$nowdf = $date_format;
$nowdf = str_replace( 'd', '%d', $nowdf );
$nowdf = str_replace( 'm', '%m', $nowdf );
$nowdf = str_replace( 'Y', '%Y', $nowdf );

// RESTAURANT

$continuos = $this->continuos;
$shifts = $this->shifts;

$min_intervals = cleverdine::getMinuteIntervals();
$avg_stay = cleverdine::getAverageTimeStay();
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

$session = JFactory::getSession();
$room_active = $session->get('active-room', '', 'vre');
if( empty($room_active) ) {
	$room_active = (count($this->rooms) > 0 ? $this->rooms[0]['id'] : -1);
}

$selected_ts = cleverdine::createTimestamp($this->dateFilter, 0, 0);
$now = time();

$_DAYS = array(
	'VRJQCALSUN',
	'VRJQCALMON',
	'VRJQCALTUE',
	'VRJQCALWED',
	'VRJQCALTHU',
	'VRJQCALFRI',
	'VRJQCALSAT',
);
$_MONTHS = array(
	'VRMONTHONE',
	'VRMONTHTWO',
	'VRMONTHTHREE',
	'VRMONTHFOUR',
	'VRMONTHFIVE',
	'VRMONTHSIX',
	'VRMONTHSEVEN',
	'VRMONTHEIGHT',
	'VRMONTHNINE',
	'VRMONTHTEN',
	'VRMONTHELEVEN',
	'VRMONTHTWELVE',
);

$date_title = "";
if( $selected_ts <= $now && $now < $selected_ts+86400 ) {
	$date_title = JText::_('VRTODAY');
} else if( $selected_ts <= $now-86400 && $now < $selected_ts+86400*2 ) {
	$date_title = JText::_('VRYESTERDAY');
} else if( $selected_ts <= $now+86400 && $now < $selected_ts) {
	$date_title = JText::_('VRTOMORROW');
} else {
	$date = getdate($selected_ts);
	$date_title = JText::_($_DAYS[$date['wday']])." ".$date['mday']." ".JText::_($_MONTHS[$date['mon']-1])." ".$date['year'];
}

$date = getdate($selected_ts);
$prev_day = date($date_format, mktime(0, 0, 0, $date['mon'], $date['mday']-1, $date['year']));
$next_day = date($date_format, mktime(0, 0, 0, $date['mon'], $date['mday']+1, $date['year']));

if (!$this->isTmpl)
{
	// jQuery datepicker
	$vik = new VikApplication();
	$vik->attachDatepickerRegional();
}

$date_past_label = JText::_('VRDATEPAST');

?>

<?php if( !$this->isTmpl ) { ?>
	<div class="vrfront-manage-titlediv">
		<h2><?php echo JText::_('VROVERSIGHTMENUITEM2'); ?></h2>
		<?php echo cleverdine::getToolbarLiveMap($operator); ?>
	</div>
<?php } ?>

<?php if( $this->isTmpl ) {

	// start catching html
	ob_start();	

} ?>

<form action="<?php echo JRoute::_("index.php?option=com_cleverdine&task=opdashboard&Itemid=$itemid"); ?>" method="post" name="opdashboard" id="vropdashboard">
	
	<!-- RESTAURANT -->
	
	<div class="vr-dashboard-wrapper">
		
		<div class="vrfront-manage-headerdiv">
		
			<div class="vrfront-manage-actionsdiv">
				
				<div class="vrfront-manage-btn move-right">
					<input type="text" name="datefilter" id="vrdatefilter" class="vr-calendar-icon" size="20" value="<?php echo $this->dateFilter; ?>" placeholder="<?php echo JText::_('VROPRESDATEFILTER'); ?>"/>
				</div>
				
			</div>
			
		</div> 

		<div class="vr-dashboard-box">

			<div class="vrdash-title"><i class="fa fa-calendar"></i>&nbsp;<?php echo JText::_('VROVERVIEW'); ?></div>

			<div class="vrdash-container">

				<div class="vrdash-tab-head">
					<?php foreach( $this->rooms as $room ) { ?>
						<div class="vrdash-tab-button room-tab">
							<a href="javascript: void(0);" onClick="switchOverviewDashboardTab(<?php echo $room['id']; ?>, this);" class="<?php echo ($room['id'] == $room_active ? 'active' : ''); ?>">
								<?php echo $room['name'].($room['closed'] ? ' <i class="fa fa-ban big" style="color:#900;"></i>' : ''); ?>
							</a>
						</div>
					<?php } ?>
				</div>
		
				<?php foreach( $this->rooms as $room ) { ?>
						
					<div class="vr-dash-roomcont" id="vr-dash-roomcont<?php echo $room['id']; ?>" style="<?php echo ($room['id'] == $room_active ? '' : 'display:none;'); ?>">
						
						<div class="vr-dash-roomcont-title">
							<div class="title-left">
								<a href="<?php echo JRoute::_("index.php?option=com_cleverdine&task=opdashboard&Itemid=$itemid&datefilter=$prev_day"); ?>">
									<i class="fa fa-chevron-left big"></i>
								</a>
							</div>
							<div class="title-middle">
								<?php echo $date_title.($room['closed'] ? ' <span style="color:#900;">('.JText::_('VRCLOSED').')</span>' : ''); ?>
							</div>
							<div class="title-right">
								<a href="<?php echo JRoute::_("index.php?option=com_cleverdine&task=opdashboard&Itemid=$itemid&datefilter=$next_day"); ?>">
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
											$ts = cleverdine::createTimestamp($this->dateFilter, $time['hour'], $time['min']);
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
													$link_href = JRoute::_("index.php?option=com_cleverdine&from=dash&task=quickres&date=".$this->dateFilter."&hourmin=".$time['hour'].":".$time['min']."&people=".$table['min']."&idt=".$table['id']."&Itemid=$itemid");
												} else {
													$class = "red";
													$title = $date_past_label;
												}

											} else {

												$class = ($info['status'] == 'CONFIRMED' ? 'green' : 'orange');

												if( count($info_ids) == 1 ) {
													$title = $info['purchaser_nominative'];

													$link_href = JRoute::_("index.php?option=com_cleverdine&from=dash&task=editres&cid[]=".$info['id']."&Itemid=$itemid");
												} else {
													$title = implode(', ', $purchasers_name);

													$link_href = JRoute::_("index.php?option=com_cleverdine&from=dash&task=editres&cid[]=".implode('&cid[]=', $info_ids)."&Itemid=$itemid");

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
												<a href="<?php echo $link_href; ?>" title="<?php echo $title; ?>" data-original-title="<?php echo $title; ?>" class="vr-sight-cal-box" <?php echo $link_target; ?>>
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
	
	<script>
		jQuery(function(){
			
			var sel_format = "<?php echo $date_format; ?>";
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
		
			jQuery("#vrdatefilter:input").datepicker({
				dateFormat: new Date().format,
			});
			
			jQuery('#vrdatefilter').on('change', function(){
				document.opdashboard.submit();
			});
			
		});
	
		<?php if( $this->isTmpl ) { ?>

			//jQuery('.tooltip.fade').remove();
			jQuery('.vr-sight-cal-box').tooltip({delay: 1000});
			
		<?php } ?>
	</script>
	
	<input type="hidden" name="option" value="com_cleverdine">
	<input type="hidden" name="task" value="opdashboard">
	<input type="hidden" name="Itemid" value="<?php echo $itemid; ?>">
</form>

<?php if( !$this->isTmpl ) { ?>

<script type="text/javascript">
	
	jQuery(document).ready(function(){
		setInterval("refreshDashboard()", <?php echo cleverdine::getDashRefreshTime()*1000; ?>);

		jQuery('.vr-sight-cal-box').tooltip({
			delay: 1000
		});
	});

	function switchOverviewDashboardTab(page, elem) {
		jQuery('.vrdash-tab-button.room-tab a').removeClass('active');
		jQuery(elem).addClass('active');
		
		jQuery('.vr-dash-roomcont').hide();
		jQuery('#vr-dash-roomcont'+page).show();
		
		storeLastRoomActive(page);
	}
	
	function refreshDashboard() {
		jQuery.noConflict();
		
		var jqxhr = jQuery.ajax({
			type: "POST",
			url: "<?php echo JRoute::_('index.php?option=com_cleverdine&task=opdashboard&datefilter='.$this->dateFilter.'&Itemid='.$itemid.'&tmpl=component'); ?>",
			data: {}
		}).done(function(resp){ 
			resp = jQuery.parseJSON(resp);

			jQuery('#vropdashboard').replaceWith(resp);
			// restores
			jQuery('#vrdatefilter').val('<?php echo $this->dateFilter; ?>');
		});
	}
	
	function storeLastRoomActive(id) {
		jQuery.noConflict();
		
		var jqxhr = jQuery.ajax({
			type: "POST",
			url: "<?php echo JRoute::_("index.php?option=com_cleverdine&task=store_active_room&tmpl=component&Itemid=$itemid"); ?>",
			data: { 
				room: id,
			}
		}).done(function(resp){
			
		}).fail(function(resp){
			
		});
	}
		
</script>

<?php } else {

	// get html caught
	$contents = ob_get_contents();
	// sto catching
	ob_end_clean();

	echo json_encode(array($contents));

	// the exit at the end of this file may cause an error on the html encoding
	// if you are facing this kind of issue, try to comment the line below
	exit;

} ?> 