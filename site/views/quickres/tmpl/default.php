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
$tables = $this->tables;
$cfields = $this->custom_fields;

$shifts = $this->shifts;
$continuos = $this->continuos;
$min_intervals = cleverdine::getMinuteIntervals();
$min_people = cleverdine::getMinimumPeople();
$max_people = cleverdine::getMaximumPeople();

// START SELECT HOURS

$select_hours = '<select name="hourmin" class="vre-tinyselect" id="vrselecthour">';
$_hm_e = explode( ':', $args["hourmin"] );
$_hm = "";
if( count( $_hm_e ) == 2 ) {
	$_hm = $_hm_e[0].':'.intval($_hm_e[1]);
}

$date_f = cleverdine::getDateFormat();
$time_f = cleverdine::getTimeFormat();

if( count( $continuos ) == 2 ) { // CONTINUOS WORK TIME
	
	if( $continuos[0] <= $continuos[1] ) {
		for( $i = $continuos[0]; $i <= $continuos[1]; $i++ ) {
			
			for( $min = 0; $min < 60; $min+=$min_intervals ) {
				$select_hours .= '<option '.(($i.':'.$min == $_hm) ? 'selected="selected"' : "").' value="'.$i.':'.$min.'">'.date($time_f, mktime($i,$min,0,1,1,2000)).'</option>';
			}
		}
	} else {
		for( $i = 0; $i <= $continuos[1]; $i++ ) {	
			for( $min = 0; $min < 60; $min+=$min_intervals ) {
				$select_hours .= '<option '.(($i.':'.$min == $_hm) ? 'selected="selected"' : "").' value="'.$i.':'.$min.'">'.date($time_f, mktime($i,$min,0,1,1,2000)).'</option>';
			}
		}
		
		for( $i = $continuos[0]; $i <= 23; $i++ ) {
			for( $min = 0; $min < 60; $min+=$min_intervals ) {
				$select_hours .= '<option '.(($i.':'.$min == $_hm) ? 'selected="selected"' : "").' value="'.$i.':'.$min.'">'.date($time_f, mktime($i,$min,0,1,1,2000)).'</option>';
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
			$select_hours .= '<option '.(($_hour.':'.$_min == $_hm) ? 'selected="selected"' : "").' value="'.$_hour.':'.$_min.'">'.date($time_f, mktime($_hour,$_min,0,1,1,2000)).'</option>';
		}
		
		if( $shifts[$k]['showlabel'] ) {
			$select_hours .= '</optgroup>';
		}
	}
}

$select_hours .= '</select>';

// END SELECT HOURS

// START SELECT TABLES

$select_tables = '<select name="id_table" id="vrselecttab" class="vre-tinyselect">';
foreach( $tables as $_t ) {
	$select_tables .= '<option '.(($_t['id'] == $args['id_table']) ? 'selected="selected"' : '').'value="'.$_t['id'].'">'.$_t['name'].' ('.$_t['min_capacity'].'-'.$_t['max_capacity'].')</option>';
}
$select_tables .= '</select>';

// END SELECT TABLES

// START SELECT PEOPLE

$select_people = '<select name="people" id="vrpeoplesel" onChange="peopleNumberChanged();" class="vre-tinyselect">';
for( $i = cleverdine::getMinimumPeople(), $n = cleverdine::getMaximumPeople(); $i <= $n; $i++ ) {
	$select_people .= '<option value="'.$i.'" '.(($i == $args['people']) ? 'selected="selected"' : '').'>'.$i.'</option>';
}
$select_people .= '</select>';

// END SELECT PEOPLE

// jQuery datepicker
$vik = new VikApplication();
$vik->attachDatepickerRegional();

$input = JFactory::getApplication()->input;

$from 	= $input->get('from', '', 'string');
$itemid = $input->get('Itemid', 0, 'uint');

?> 

<form name="quickresform" action="index.php?option=com_cleverdine" method="post" enctype="multipart/form-data" id="vrquickresform">
	
	<div class="vrfront-manage-headerdiv">
		<div class="vrfront-manage-titlediv">
			<h2><?php echo JText::_('VRNEWQUICKRESERVATION'); ?></h2>
		</div>
		
		<div class="vrfront-manage-actionsdiv">
			
			<div class="vrfront-manage-btn">
				<button type="button" onClick="vrSaveReservation(0);" id="vrfront-manage-btnsave" class="vrfront-manage-button"><?php echo JText::_('VRSAVE'); ?></button>
			</div>
			<div class="vrfront-manage-btn">
				<button type="button" onClick="vrSaveReservation(1);" id="vrfront-manage-btnsaveclose" class="vrfront-manage-button"><?php echo JText::_('VRSAVEANDCLOSE'); ?></button>
			</div>
			
			<div class="vrfront-manage-btn">
				<button type="button" onClick="vrCloseQuickReservation();" id="vrfront-manage-btnclose" class="vrfront-manage-button"><?php echo JText::_('VRCLOSE'); ?></button>
			</div>
		</div>
	</div> 
	
	<table class="vrfront-manage-form">
		
		<tr>
			<td width="200">&bull; <b><?php echo JText::_('VRDATE');?></b> </td>
			<td><input type="text" name="date" id="vrdatefield" size="20" value="<?php echo $args['date']; ?>"/></td>
		</tr>
		
		<tr>
			<td width="200">&bull; <b><?php echo JText::_('VRTIME');?></b> </td>
			<td><div class="vre-tinyselect-wrapper"><?php echo $select_hours; ?></div></td>	
		</tr>
		
		<tr>
			<td width="200">&bull; <b><?php echo JText::_('VRTABLE');?>:</b> </td>
			<td><div class="vre-tinyselect-wrapper"><?php echo $select_tables; ?></div></td>	
		</tr>
		
		<tr>
			<td width="200">&bull; <b><?php echo JText::_('VRPEOPLE');?></b> </td>
			<td><div class="vre-tinyselect-wrapper"><?php echo $select_people; ?></div></td>
		</tr>
		
	</table>
	
	<?php if( count($cfields) > 0 ) { ?>
		
		<br/>
		
		<table class="vrfront-manage-form">
		<?php
			  
			foreach( $cfields as $cf ) {
				
				if( !empty( $cf['poplink'] ) ) {
					$fname = "<a href=\"" . $cf['poplink'] . "\" id=\"vrcf" . $cf['id'] . "\" rel=\"{handler: 'iframe', size: {x: 750, y: 600}}\" target=\"_blank\" class=\"modal\">" . JText::_($cf['name']) . "</a>";
				} else {
					$fname = "<span id=\"vrcf" . $cf['id'] . "\">" . JText::_($cf['name']) . "</span>";
				}
				
				if( $cf['type'] == "text") {
					
				?>
					<tr>
						<td width="200">&bull; <b><?php echo $fname; ?></b></td>
						<td><input type="text" name="vrcf<?php echo $cf['id']; ?>" size="40" class="vrcfinput"/></td>
					</tr>
				<?php } else if( $cf['type'] == "textarea" ) { ?>
					<tr>
						<td width="200">&bull; <b><?php echo $fname; ?></b></td>
						<td><textarea name="vrcf<?php echo $cf['id']; ?>" rows="5" cols="30" class="vrtextarea vrcfinput"></textarea></td>
					</tr>
				<?php } else if( $cf['type'] == "date" ) { ?>
					<tr>
						<td width="200">&bull; <b><?php echo $fname; ?></b></td>
						<td><input type="text" name="vrcf<?php echo $cf['id']; ?>" id="vrcf<?php echo $cf['id']; ?>date" class="vrcfinput"/></td>
					</tr>
					<script>
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
					
						jQuery("#vrcf<?php echo $cf['id']; ?>date:input").datepicker({
							dateFormat: today.format
						});
					</script>
				<?php } else if( $cf['type'] == "select" ) {
					$answ = explode(";;__;;", $cf['choose']);
					$wcfsel = "<select name=\"vrcf" . $cf['id'] ."\" class=\"vrcfinput\">\n";
					foreach ($answ as $aw) {
						if (!empty ($aw)) {
							$wcfsel .= "<option value=\"" . $aw . "\">" . $aw . "</option>\n";
						}
					}
					$wcfsel .= "</select>\n";
					?>
					<tr>
						<td width="200">&bull; <b><?php echo $fname; ?></b></td>
						<td><?php echo $wcfsel; ?></td>
					</tr>
				<?php } else if( $cf['type'] == "separator" ) {
					$cfsepclass = strlen(JText::_($cf['name'])) > 30 ? "vrseparatorcflong" : "vrseparatorcf";
				?>
					<tr><td colspan="2" class="<?php echo $cfsepclass; ?>"><?php echo JText::_($cf['name']); ?></td></tr>
				<?php } else { ?>
					<tr>
						<td width="200">&bull; <b><?php echo $fname; ?></b></td>
						<td><input type="checkbox" name="vrcf<?php echo $cf['id']; ?>" value="1" class="vrcfinput"/></td>
					</tr>
				<?php
			}
				
			$get_focus = false;
		} 
		?>
		
	</table>
	<?php } ?>
	
	<input type="hidden" name="return" value="0" id="vrhiddenreturn"/> 
	<input type="hidden" name="from" value="<?php echo $from; ?>"/> 
	<input type="hidden" name="task" value="saveQuickReservation"/>
	<input type="hidden" name="option" value="com_cleverdine"/>
	<input type="hidden" name="Itemid" value="<?php echo $itemid; ?>"/>
</form>

<script>

	jQuery(document).ready(function(){
		jQuery("input[name=vrcf<?php echo $cfields[0]['id']; ?>]").focus();
		
		jQuery('.vrcfinput').keypress(function(e){
			if( e.keyCode == 13 ) {
				vrSaveReservation(1);
			}
		});
		
		jQuery('#vrdatefield:input').on('change', function(){
			vrUpdateWorkingShifts();
		});
	});

	function vrCloseQuickReservation() {
		<?php if( $from == "dash" ) { ?>
			document.location.href = '<?php echo JRoute::_('index.php?option=com_cleverdine&task=opdashboard&datefilter='.$args['date']."&Itemid=$itemid"); ?>';
		<?php } else if( $from == "reservations" ) { ?>
			document.location.href = '<?php echo JRoute::_('index.php?option=com_cleverdine&task=opreservations&datefilter='.$args['date']."&Itemid=$itemid"); ?>';
		<?php } else { ?>
			document.location.href = '<?php echo JRoute::_('index.php?option=com_cleverdine&view=oversight&datefilter='.$args['date']."&hourmin=".$args['hourmin']."&people=".$args['people'], false); ?>';
		<?php } ?>
	}
	
	function vrSaveReservation(close) {
		
		var validate = vrValidateFieldsBeforeSubmit();
		
		if( validate ) {
			if(close) {
				jQuery('#vrhiddenreturn').val('1');
			}
			
			document.quickresform.submit();
		} 
	}
	
	function vrValidateFieldsBeforeSubmit() {
		var arr = new Array('vrdatefield', 'vrselecthour', 'vrselecttab', 'vrpeoplesel');
		
		var ok = true;
		for( var i = 0; i < arr.length; i++ ) {
			if( jQuery('#'+arr[i]).val().length == 0 ) {
				ok = false;
				jQuery('#'+arr[i]).addClass('vrrequiredfield');
			} else {
				jQuery('#'+arr[i]).removeClass('vrrequiredfield');
			}
		}
		
		var cont_filled = 0;
		jQuery('.vrcfinput').each(function(){
			if( this.value.length > 0 ) {
				cont_filled++;
			}
		});
		
		if( cont_filled > 0 ) {
			jQuery('.vrcfinput').first().removeClass('vrrequiredfield');
		} else {
			jQuery('.vrcfinput').first().addClass('vrrequiredfield');
		}
		
		ok = ok && (cont_filled > 0);
		
		return ok;
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
		
		var today_no_hour_no_min = getDate('<?php echo date($date_f, time()); ?>');
	
		jQuery("#vrdatefield:input").datepicker({
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
	
</script>