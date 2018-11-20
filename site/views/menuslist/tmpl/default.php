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

$special_days = $this->specialDays;
$shifts = $this->shifts;

$menus = $this->menus;

$last_values = $this->lastValues;

$show_form = $this->showSearchForm;

$date_format = cleverdine::getDateFormat();
$time_format = cleverdine::getTimeFormat();

for( $i = 0, $n = count( $special_days ); $i < $n; $i++ ) {
	if( $special_days[$i]['start_ts'] != -1 ) {
		$special_days[$i]['start_ts'] = date( $date_format, $special_days[$i]['start_ts'] );
		$special_days[$i]['end_ts'] = date( $date_format, $special_days[$i]['end_ts'] );
	}	
	
	$special_days[$i]['days_filter'] = (strlen($special_days[$i]['days_filter']) > 0 ? explode( ', ', $special_days[$i]['days_filter'] ) : array() );
}

// START SELECT HOURS
$select_shifts = "";
if( count( $shifts ) > 0 ) {
	$select_shifts = '<select name="shift" class="vre-tinyselect" id="vrselectshifts">';
	$select_shifts .= '<option value="">--</option>';
	foreach( $shifts as $_s ) {
		$name = $_s['label'];
		if( !$_s['showlabel'] || empty($name) ) {
			$name = date($time_format, mktime($_s['from_hour'], $_s['from_min'], 0, 1, 1, 2000))." - ".
			date($time_format, mktime($_s['to_hour'], $_s['to_min'], 0, 1, 1, 2000));
		}
		$select_shifts .= '<option '.(($last_values['shift'] == intval($_s['from']/60).'-'.intval($_s['to']/60)) ? 'selected="selected"' : "").' value="'.intval($_s['from']/60).'-'.intval($_s['to']/60).'">'.$name.'</option>';
	}
	$select_shifts .= '</select>'; 
}
// END SELECT HOURS

// jQuery datepicker
$vik = new VikApplication();
$vik->attachDatepickerRegional();

$closing_days = cleverdine::getClosingDays();

$desc_maximum_length = 180;

?>

<div class="vrmenuslistform" id="vrmenuslistform" >
	<form action="<?php echo JRoute::_('index.php?option=com_cleverdine&view=menuslist'); ?>" method="GET">
		<?php if( $show_form ) { ?>
		<div class="vrmenusfieldsdiv">
			<div class="vrmenufielddatediv">
				<label for="vrcalendar"><?php echo JText::_('VRORDERDATETIME'); ?>:</label>
				<input type="text" name="date" value="<?php echo $last_values["date"]; ?>" id="vrcalendar"/>
			</div>
			<?php if( count( $shifts ) > 0 ) { ?>
				<div class="vrmenufieldshiftdiv">
					<label for="vrselectshifts"><?php echo JText::_('VRWORKINGSHIFT'); ?>:</label>
					<div class="vrmenufieldselectdiv vre-tinyselect-wrapper">
						<?php echo $select_shifts; ?>
					</div>
				</div>
			<?php } ?>
			<button type="submit" class="vrmenufieldsubmit"><?php echo JText::_('VRMENUSEARCH'); ?></button>
		</div>
		<?php } ?>
		
		<div class="vrmenuslistcont">
			<?php if( count( $menus ) == 0 && strlen( $last_values['date'] ) > 0 ) { ?>
				<div class="vrmenusondatenoaverr">
					<?php echo JText::_('VRMENUSEARCHNOAVERR'); ?>
				</div>
			<?php } else if( count( $menus ) > 0 ) { // show menus blocks 
				foreach( $menus as $m ) {
					
					$m['name'] = cleverdine::translate($m['id'], $m, $this->translatedMenus, 'name', 'name');
					$m['description'] = cleverdine::translate($m['id'], $m, $this->translatedMenus, 'description', 'description');
					 
					if( empty($m['image']) || !file_exists(JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_cleverdine'.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.$m['image']) ) {
						$m['image'] = 'menu_default_icon.jpg';   
					}
					
					$desc = $m['description'];
					if( strlen(strip_tags($m['description'])) > $desc_maximum_length ) {
						$desc = mb_substr(strip_tags($m['description']), 0, $desc_maximum_length, 'UTF-8')."...";
					}
					
					$url = JRoute::_('index.php?option=com_cleverdine&view=menudetails'.
						(!empty($last_values['date']) ? '&date='.$last_values['date'] : '').
						(!empty($last_values['shift']) ? '&shift='.$last_values['shift'] : '').
						'&id='.$m['id'], false);
						
				?>
				<div class="vrmenublock">
					<div class="vrmenublock-menu">
						<div class="vrmenublockimage">
							<a href="<?php echo $url; ?>">
								<img src="<?php echo JUri::root().'components/com_cleverdine/assets/media/'.$m['image']; ?>" />
							</a>
						</div>
						<div class="vrmenublockname">
							<a href="<?php echo $url; ?>"><?php echo $m['name']; ?></a>
						</div>
						<div class="vrmenublockdesc"><?php echo $desc; ?></div>
						<div class="vrmenublockshifts">
							<?php if( count($this->continuos) == 0 ) { ?>
								<?php foreach( $m['working_shifts'] as $s ) { 
									$from = array( 'hour' => (int)$s['from']/60, 'min' => $s['from']%60 );
									$to = array( 'hour' => (int)$s['to']/60, 'min' => $s['to']%60 );
									
									$time_text = date($time_format, mktime($from['hour'], $from['min'], 0, 1, 1, 2000)).' - '.date($time_format, mktime($to['hour'], $to['min'], 0, 1, 1, 2000));
									$shift_name = $time_text;
									
									if( $s['showlabel'] && !empty($s['label']) ) {
										$shift_name = $s['label'];
									}
									?>
									<span class="vrmenublockworksh">
										<span class="vrmenublockworkshname"><?php echo $shift_name; ?></span>
										<span class="vrmenublockworkshtime"><?php echo $time_text; ?></span>
									</span>
								<?php } ?>
							<?php } else {
								
								$time_text = date($time_format, mktime($this->continuos[0], 0, 0, 1, 1, 2000))." - ".date($time_format, mktime($this->continuos[1], 0, 0, 1, 1, 2000));
								$shift_name = $time_text;
								?>
								<span class="vrmenublockworksh">
									<span class="vrmenublockworkshname"><?php echo $shift_name; ?></span>
									<span class="vrmenublockworkshtime"><?php echo $time_text; ?></span>
								</span>
							<?php } ?>
						</div>
					</div>
				</div>
			<?php }
			} ?>
		</div>
		
		<input type="hidden" name="view" value="menuslist"/>
		
	</form>
</div>

<script>
jQuery(function(){
	
	var closingDays = <?php echo json_encode($closing_days); ?>;
	
	var specialDays = <?php echo json_encode($special_days); ?>;

	jQuery( document ).ready(function(){
		jQuery('#vrcalendar:input').on('change', function(){
			vrUpdateWorkingShifts()
		});
	});
	
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

	jQuery("#vrcalendar:input").datepicker({
		minDate: today,
		dateFormat: today.format,
		beforeShowDay: setupCalendar
	});

	function setupCalendar(date) {
		
		var enabled = false;
		var clazz = "";
		var ignore_cd = 0;
	
		for( var i = 0; i < specialDays.length && !enabled; i++ ) {
			if( specialDays[i]['start_ts'] == -1 ) {
				if( specialDays[i]['days_filter'].length == 0 ) {
					if( specialDays[i]['markoncal'] == 1 ) {
						clazz = "vrtdspecialday";
					}
					ignore_cd = specialDays[i]['ignoreclosingdays'];
				} else if( contains( specialDays[i]['days_filter'], date.getDay() ) ) {
					if( specialDays[i]['markoncal'] == 1 ) {
						clazz = "vrtdspecialday";
					}
					ignore_cd = specialDays[i]['ignoreclosingdays'];
				}
			}
			
			_ds = getDate(specialDays[i]['start_ts']);
			_de = getDate(specialDays[i]['end_ts']);
			
			if( _ds.valueOf() <= date.valueOf() && date.valueOf() <= _de.valueOf() ) {
				if( specialDays[i]['days_filter'].length == 0 ) {
					if( specialDays[i]['markoncal'] == 1 ) {
						clazz = "vrtdspecialday";
					}
					ignore_cd = specialDays[i]['ignoreclosingdays'];
				} else if( contains( specialDays[i]['days_filter'], date.getDay() ) ) {
					if( specialDays[i]['markoncal'] == 1 ) {
						clazz = "vrtdspecialday";
					}
					ignore_cd = specialDays[i]['ignoreclosingdays'];
				}
			}
		}
		
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
	
	jQuery('.vrmenublockworkshname').hover(function(){
		jQuery('.vrmenublockworkshname').removeClass('vrmenublockworkhighlight');
		jQuery('.vrmenublockworkshtime').removeClass('vrmenublockworkexploded');
		jQuery(this).addClass('vrmenublockworkhighlight');
		jQuery(this).siblings().each(function(){
			jQuery(this).addClass('vrmenublockworkexploded');
		});
	}, function(){
		jQuery('.vrmenublockworkshname').removeClass('vrmenublockworkhighlight');
		jQuery('.vrmenublockworkshtime').removeClass('vrmenublockworkexploded');
	});
	
	function vrUpdateWorkingShifts() {
		jQuery('#vrselectshifts').prop('disabled', true);
		
		jQuery.noConflict();
		
		var jqxhr = jQuery.ajax({
			type: "POST",
			url: "index.php",
			data: { option: "com_cleverdine", task: "get_working_shifts", date: jQuery('#vrcalendar').val(), onlynames: 1, tmpl: "component" }
		}).done(function(resp){
			var obj = jQuery.parseJSON(resp); 
			
			if( obj[0] && obj[1].length > 0 ) {
				jQuery('#vrselectshifts').html('<option value="">--</option>\n'+obj[1]);
			}
			
			jQuery('#vrselectshifts').prop('disabled', false);
			
		}).fail(function(resp){
			jQuery('#vrselectshifts').prop('disabled', false);
		});
	}
	
});

</script>

