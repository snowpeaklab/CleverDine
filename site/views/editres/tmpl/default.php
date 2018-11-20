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

$row = $this->row;
$shared_rows = $this->shared_rows;

$time_f = cleverdine::getTimeFormat();

if( count($row) > 0 ) {
	
	$tables = $this->tables;
	$cfields = $this->custom_fields;
	
	$shifts = $this->shifts;
	$continuos = $this->continuos;
	$min_intervals = cleverdine::getMinuteIntervals();
	$min_people = cleverdine::getMinimumPeople();
	$max_people = cleverdine::getMaximumPeople();
	
	// START SELECT HOURS
	
	$select_hours = '<select name="hourmin" class="vre-tinyselect" id="vrselecthour">';
	$_hm_e = explode( ':', $row["hourmin"] );
	$_hm = "";
	if( count( $_hm_e ) == 2 ) {
		$_hm = $_hm_e[0].':'.intval($_hm_e[1]);
	}
	
	$date_f = cleverdine::getDateFormat();
	
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
			
			$select_hours .= '<optgroup class="vrsearchoptgrouphour" label="'.$shifts[$k]["label"].'">';
			
			for( $_app = $shifts[$k]['from']; $_app <= $shifts[$k]['to']; $_app+=$min_intervals ) {
				$_hour = intval($_app/60);
				$_min = $_app%60;
				$select_hours .= '<option '.(($_hour.':'.$_min == $_hm) ? 'selected="selected"' : "").' value="'.$_hour.':'.$_min.'">'.date($time_f, mktime($_hour,$_min,0,1,1,2000)).'</option>';
			}
			
			$select_hours .= '</optgroup>';
		}
	}
	
	$select_hours .= '</select>';
	
	// END SELECT HOURS
	
	// START SELECT TABLES
	
	$select_tables = '<select name="id_table" id="vrselecttab" class="vre-tinyselect">';
	foreach( $tables as $_t ) {
		$select_tables .= '<option '.(($_t['id'] == $row['id_table']) ? 'selected="selected"' : '').'value="'.$_t['id'].'">'.$_t['name'].' ('.$_t['min_capacity'].'-'.$_t['max_capacity'].')</option>';
	}
	$select_tables .= '</select>';
	
	// END SELECT TABLES
	
	// START SELECT PEOPLE
	
	$select_people = '<select name="people" id="vrpeoplesel" onChange="peopleNumberChanged();" class="vre-tinyselect">';
	for( $i = cleverdine::getMinimumPeople(), $n = cleverdine::getMaximumPeople(); $i <= $n; $i++ ) {
		$select_people .= '<option value="'.$i.'" '.(($i == $row['people']) ? 'selected="selected"' : '').'>'.$i.'</option>';
	}
	$select_people .= '</select>';
	
	// END SELECT PEOPLE
	
	// START STATUS SELECT
	
	$statuses = array('CONFIRMED', 'PENDING', 'REMOVED', 'CANCELLED');
	$status_select = '<select name="status" class="vre-tinyselect">';
	foreach( $statuses as $s ) {
		$status_select .= '<option value="'.$s.'" '.($s == $row['status'] ? 'selected="selected"' : '').'>'.JText::_('VRRESERVATIONSTATUS'.$s).'</option>';
	}
	$status_select .= '</select>';
	
	// END STATUS SELECT
	
	// START RES CODES SELECT
	
	$res_codes_select = '<select name="rescode" class="vre-tinyselect">';
	$res_codes_select .= '<option value="">--</option>';
	foreach( $this->resCodes as $c ) {
		$res_codes_select .= '<option value="'.$c['id'].'" '.($c['id'] == $row['rescode'] ? 'selected="selected"' : '').'>'.$c['code'].'</option>';
	}
	$res_codes_select .= '</select>';
	
	// END RES CODES SELECT
	
	// jQuery datepicker
	$vik = new VikApplication();
	$vik->attachDatepickerRegional();

	$input = JFactory::getApplication()->input;
	
	$from 	= $input->get('from', '', 'string');
	$itemid = $input->get('Itemid', 0, 'uint');
	
	?>
	
	<div class="vrfront-manage-headerdiv">
		<div class="vrfront-manage-titlediv">
			<h2><?php echo JText::_('VREDITQUICKRESERVATION'); ?></h2>
		</div>
		
		<div class="vrfront-manage-actionsdiv">
			
			<div class="vrfront-manage-btn">
				<button type="button" onClick="vrSaveReservation(0);" id="vrfront-manage-btnsave" class="vrfront-manage-button"><?php echo JText::_('VRSAVE'); ?></button>
			</div>

			<div class="vrfront-manage-btn">
				<button type="button" onClick="vrSaveReservation(1);" id="vrfront-manage-btnsaveclose" class="vrfront-manage-button"><?php echo JText::_('VRSAVEANDCLOSE'); ?></button>
			</div>

			<div class="vrfront-manage-btn">
				<button type="button" onClick="vrEditBill();" id="vrfront-manage-btnbill" class="vrfront-manage-button"><?php echo JText::_('VREDITBILL'); ?></button>
			</div>
			
			<div class="vrfront-manage-btn">
				<button type="button" onClick="vrCloseQuickReservation();" id="vrfront-manage-btnclose" class="vrfront-manage-button"><?php echo JText::_('VRCLOSE'); ?></button>
			</div>
		</div>
	</div>  
	
	<form name="quickresform" action="index.php" method="post" enctype="multipart/form-data" id="vrquickresform">
		
		<table class="vrfront-manage-form">
			
			<tr>
				<td width="200">&bull; <b><?php echo JText::_('VRDATE');?></b> </td>
				<td><input type="text" name="date" id="vrdatefield" size="20" value="<?php echo $row['date']; ?>"/></td>
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
		
		<br/>
		
		<table class="vrfront-manage-form">
			<tr>
				<td width="200">&bull; <b><?php echo JText::_('VRORDERSTATUS');?>:</b> </td>
				<td><div class="vre-tinyselect-wrapper"><?php echo $status_select; ?></div></td>
			</tr>
			
			<tr>
				<td width="200">&bull; <b><?php echo JText::_('VRSTATUSRESCODE');?>:</b> </td>
				<td><div class="vre-tinyselect-wrapper"><?php echo $res_codes_select; ?></div></td>
			</tr>
			
			<tr>
				<td width="200">&bull; <b><?php echo JText::_('VREDITRESSENDMAIL');?>:</b></td>
				<td>
					<input type="radio" name="sendmail" value="1" id="vrsendmail1"/><label for="vrsendmail1">&nbsp;<?php echo JText::_('VRYES'); ?></label>
					<input type="radio" name="sendmail" value="0" id="vrsendmail0" checked/><label for="vrsendmail0">&nbsp;<?php echo JText::_('VRNO'); ?></label>
				</td>
			</tr>
		</table>
		
		<?php if( count($cfields) > 0 ) { ?>
			
			<br/>
			
			<table class="vrfront-manage-form">
			<?php
				
				$cf_data = json_decode($row['custom_f'], true);
				
				foreach( $cfields as $cf ) {
					
					if( !empty( $cf['poplink'] ) ) {
						$fname = "<a href=\"" . $cf['poplink'] . "\" id=\"vrcf" . $cf['id'] . "\" rel=\"{handler: 'iframe', size: {x: 750, y: 600}}\" target=\"_blank\" class=\"modal\">" . JText::_($cf['name']) . "</a>";
					} else {
						$fname = "<span id=\"vrcf" . $cf['id'] . "\">" . JText::_($cf['name']) . "</span>";
					}
					
					$_val = "";
					if( !empty($cf_data[$cf['name']]) ) {
						$_val = $cf_data[$cf['name']];
					}
					
					if( $cf['type'] == "text") {
						
					?>
						<tr>
							<td width="200">&bull; <b><?php echo $fname; ?></b></td>
							<td><input type="text" name="vrcf<?php echo $cf['id']; ?>" value="<?php echo $_val; ?>" size="40" class="vrcfinput"/></td>
						</tr>
					<?php } else if( $cf['type'] == "textarea" ) { ?>
						<tr>
							<td width="200" valign="top">&bull; <b><?php echo $fname; ?></b></td>
							<td><textarea name="vrcf<?php echo $cf['id']; ?>" rows="5" cols="30" class="vrtextarea vrcfinput"><?php echo $_val; ?></textarea></td>
						</tr>
					<?php } else if( $cf['type'] == "date" ) { ?>
						<tr>
							<td width="200">&bull; <b><?php echo $fname; ?></b></td>
							<td><input type="text" name="vrcf<?php echo $cf['id']; ?>" id="vrcf<?php echo $cf['id']; ?>date" class="vrcfinput" value="<?php echo $_val; ?>"/></td>
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
								$wcfsel .= "<option value=\"" . $aw . "\" ".($_val == $aw ? 'selected="selected"' : '').">" . $aw . "</option>\n";
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
					<?php } else if( empty($cf['poplink']) ) { ?>
						<tr>
							<td width="200">&bull; <b><?php echo $fname; ?></b></td>
							<td><input type="checkbox" name="vrcf<?php echo $cf['id']; ?>" value="1" <?php echo ($_val ? 'checked="checked"' : ''); ?> class="vrcfinput"/></td>
						</tr>
					<?php
				}
			} 
			?>
			
		</table>
		<?php } ?>
		
		<input type="hidden" name="id" value="<?php echo $row['id']; ?>"/>
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
				document.location.href = '<?php echo JRoute::_('index.php?option=com_cleverdine&task=opdashboard&datefilter='.$row['date']."&Itemid=$itemid"); ?>';
			<?php } else if( $from == "reservations" ) { ?>
				document.location.href = '<?php echo JRoute::_('index.php?option=com_cleverdine&task=opreservations&Itemid='.$itemid); ?>';
			<?php } else { ?>
				document.location.href = '<?php echo JRoute::_('index.php?option=com_cleverdine&view=oversight&datefilter='.$row['date']."&hourmin=".$row['hourmin']."&people=".$row['people'], false); ?>';
			<?php } ?>
		}
		
		function vrEditBill() {

			document.location.href = '<?php echo JRoute::_("index.php?option=com_cleverdine&task=opeditbill&id={$row['id']}&Itemid=$itemid"); ?>';

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
		
	</script>
	
<?php } else { 
	
	$icon_path = JUri::root().'components'.DIRECTORY_SEPARATOR.'com_cleverdine'.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR;
	
	?>
	
	<div class="vrfront-manage-headerdiv">
		<div class="vrfront-manage-titlediv">
			<h2><?php echo JText::sprintf('VREDITQUICKRESERVATIONSHARED', $shared_rows[0]['tname']); ?></h2>
		</div>
		
		<div class="vrfront-manage-actionsdiv">
			<div class="vrfront-manage-btn">
				<button type="button" onClick="vrCloseQuickReservation();" id="vrfront-manage-btnclose" class="vrfront-manage-button"><?php echo JText::_('VRCLOSE'); ?></button>
			</div>
		</div>
	</div> 
	
	<div class="vreditres-allrows">
		
		<?php foreach( $shared_rows as $r ) { ?>
			<div class="vreditres-row-block">
				<a href="<?php echo JRoute::_('index.php?option=com_cleverdine&Itemid='.$itemid.'&task=editres&cid[]='.$r['id'], false); ?>">
					<span class="vreditres-row-order"><?php echo $r['id'].'-'.$r['sid']; ?></span>
					<span class="vreditres-row-name"><?php echo $r['purchaser_nominative']; ?></span>
					<span class="vreditres-row-time"><?php echo date( $time_f, $r['checkin_ts']); ?></span>
					<span class="vreditres-row-people">x<?php echo $r['people']; ?></span>
					<?php if( !empty($r['code']) ) { ?>
						<span class="vreditres-row-code">
							<?php if( empty($r['code_icon']) ) {
								echo $r['code'];
							} else { ?>
								<img src="<?php echo $icon_path.$r['code_icon']; ?>" title="<?php echo $r['code']; ?>"/>
							<?php } ?>
						</span>
					<?php } ?>
				</a>
			</div>    
		<?php } ?>
		
	</div>
	
	<script>
		function vrCloseQuickReservation() {
			document.location.href = '<?php echo JRoute::_('index.php?option=com_cleverdine&view=oversight', false); ?>';
		}
	</script>
	
<?php } ?>
