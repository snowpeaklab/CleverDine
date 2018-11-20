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
$continuos = $this->continuos;

$sel = $this->lastValues;

$min_intervals = cleverdine::getMinuteIntervals();
$min_people = cleverdine::getMinimumPeople();
$max_people = cleverdine::getMaximumPeople();

$large_party_label = cleverdine::isShowLargePartyLabel();
$large_party_url = cleverdine::getLargePartyURL();

$date_format = cleverdine::getDateFormat();

for( $i = 0, $n = count( $special_days ); $i < $n; $i++ ) {
	if( $special_days[$i]['start_ts'] != -1 ) {
		$special_days[$i]['start_ts'] = date( $date_format, $special_days[$i]['start_ts'] );
		$special_days[$i]['end_ts'] = date( $date_format, $special_days[$i]['end_ts'] );
	}	
	
	$special_days[$i]['days_filter'] = (strlen($special_days[$i]['days_filter']) > 0 ? explode( ', ', $special_days[$i]['days_filter'] ) : array() );
}

// START SELECT HOURS

$select_hours = '<select name="hourmin" class="vre-tinyselect" id="vrhour">';

$time_f = cleverdine::getTimeFormat();

if( count( $continuos ) == 2 ) { // CONTINUOS WORK TIME
	
	if( $continuos[0] <= $continuos[1] ) {
		for( $i = $continuos[0]; $i <= $continuos[1]; $i++ ) {
			
			for( $min = 0; $min < 60; $min+=$min_intervals ) {
				$select_hours .= '<option '.(($i.':'.$min == $sel["hourmin"]) ? 'selected="selected"' : "").' value="'.$i.':'.$min.'">'.date($time_f, mktime($i,$min,0,1,1,2000)).'</option>';
			}
		}
	} else {
		for( $i = 0; $i <= $continuos[1]; $i++ ) {	
			for( $min = 0; $min < 60; $min+=$min_intervals ) {
				$select_hours .= '<option '.(($i.':'.$min == $sel["hourmin"]) ? 'selected="selected"' : "").' value="'.$i.':'.$min.'">'.date($time_f, mktime($i,$min,0,1,1,2000)).'</option>';
			}
		}
		
		for( $i = $continuos[0]; $i <= 23; $i++ ) {
			for( $min = 0; $min < 60; $min+=$min_intervals ) {
				$select_hours .= '<option '.(($i.':'.$min == $sel["hourmin"]) ? 'selected="selected"' : "").' value="'.$i.':'.$min.'">'.date($time_f, mktime($i,$min,0,1,1,2000)).'</option>';
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
			$select_hours .= '<option '.(($_hour.':'.$_min == $sel['hourmin']) ? 'selected="selected"' : "").' value="'.$_hour.':'.$_min.'">'.date($time_f, mktime($_hour,$_min,0,1,1,2000)).'</option>';
		}
		
		if( $shifts[$k]['showlabel'] ) {
			$select_hours .= '</optgroup>';
		}
	}
}

$select_hours .= '</select>';

// END SELECT HOURS

// START SELECT PEOPLE

$select_people = '<select class="vre-tinyselect" name="people" id="vrpeople">';
for( $p = $min_people; $p <= $max_people; $p++ ) {
	$select_people .= '<option '.(($p == $sel["people"]) ? 'selected="selected"' : "").'value="'.$p.'">'.$p.'</option>';
}
if( $large_party_label ) {
	$select_people .= '<option value="-1">'.JText::_('VRLARGEPARTYLABEL').'</option>';
}
$select_people .= '</select>';

// END SELECT PEOPLE

$resrequirements = cleverdine::getReservationRequirements();

// jQuery datepicker
$vik = new VikApplication();
$vik->attachDatepickerRegional();

$closing_days = cleverdine::getClosingDays();

?>

<div class="vrstepbardiv">

	<div class="vrstepactive step-current">
		<div class="vrstep-inner">
			<span class="vrsteptitle"><?php echo JText::_('VRSTEPONETITLE'); ?></span>
			<span class="vrstepsubtitle"><?php echo JText::_('VRSTEPONESUBTITLE'); ?></span>
		</div>
	</div>
	
	<div class="vrstep">
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

<div class="vrreservationform" id="vrsearchform" >
	<form action="<?php echo JRoute::_('index.php?option=com_cleverdine&task=search'); ?>" method="GET">
		<fieldset class="vrformfieldset">
			<legend><?php echo JText::_('VRMAKEARESERVATION'); ?></legend>
			<div class="vrsearchinputdiv">
				<label class="vrsearchinputlabel" for="vrcalendar"><?php echo JText::_('VRDATE'); ?></label>
				<div class="vrsearchentryinput"><input class="vrsearchdate" type="text" value="<?php echo $sel["date"]; ?>" id="vrcalendar" name="date" size="20"/></div>
			</div>
			<div class="vrsearchinputdiv">
				<label class="vrsearchinputlabel" for="vrhour"><?php echo JText::_('VRTIME'); ?></label>
				<div class="vrsearchentryselect vre-tinyselect-wrapper"><?php echo $select_hours; ?></div>
			</div>
			<div class="vrsearchinputdiv">
				<label class="vrsearchinputlabel" for="vrpeople"><?php echo JText::_('VRPEOPLE'); ?></label>
				<div class="vrsearchentryselectsmall vre-tinyselect-wrapper"><?php echo $select_people; ?></div>
			</div>
			<div class="vrsearchinputdiv">
				<button type="submit" class="vrsearchsubmit"><?php echo JText::_('VRFINDATABLE'); ?></button>
			</div>
			
			<input type="hidden" value="com_cleverdine" name="option"/>
			<input type="hidden" value="search" name="task"/>
		</fieldset>
	</form>
</div>

<script>
jQuery(function(){
	
	var closingDays = <?php echo json_encode($closing_days); ?>;
	
	var specialDays = <?php echo json_encode($special_days); ?>;

	jQuery( document ).ready(function(){
		jQuery('#vrcalendar:input').on('change', function(){
			vrUpdateWorkingShifts();
		});
		
		<?php if( $large_party_label ) { ?>
			jQuery('#vrpeople').on('change', function(){
				if( jQuery(this).val() == '-1' ) {
					document.location.href = '<?php echo JRoute::_($large_party_url); ?>';
				}
			});
		<?php } ?>            
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
	
	var today_no_hour_no_min = getDate('<?php echo date($date_format, time()); ?>');

	jQuery("#vrcalendar:input").datepicker({
		minDate: today,
		dateFormat: today.format,
		beforeShowDay: setupCalendar
	});

	function setupCalendar(date) {
		
		var enabled = false;
		var clazz = "";
		var ignore_cd = 0;
		
		if( today_no_hour_no_min.valueOf() > date.valueOf() ) {
			return [false,""];
		}
	
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
	
	function vrUpdateWorkingShifts() {
		jQuery('#vrhour').prop('disabled', true);
		
		jQuery.noConflict();
		
		var jqxhr = jQuery.ajax({
			type: "POST",
			url: "index.php",
			data: {
				option: "com_cleverdine",
				task: "get_working_shifts",
				date: jQuery('#vrcalendar').val(),
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
	
});

</script>

