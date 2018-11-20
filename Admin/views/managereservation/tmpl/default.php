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

// load modal behavior
JHtml::_('behavior.modal');

$rooms = $this->rooms;
$cfields = $this->custom_fields;
$new_res_arg = $this->new_res_arg;

$sel = null;
$id = -1;
if( !count( $this->selectedReservation ) ) {
	$sel = array(
		'date' => date( cleverdine::getDateFormat(true), time() ), 'hourmin' => 0, 'id_table' => 0, 'id_payment' => -1, 'people' => 2, 'bill_closed' => 0, 'bill_value' => 0.0, 'deposit' => 0, 
		'purchaser_nominative' => '', 'purchaser_mail' => '', 'purchaser_phone' => '', 'purchaser_prefix' => '', 'purchaser_country' => '', 'status' => 'CONFIRMED', 'id' => -1, 
		'notes' => '', 'id_user' => -1, 'custom_f' => array(), 'stay_time' => 0
	);
	
	if( count($new_res_arg) > 0 ) {
		foreach( $new_res_arg as $k => $v ) {
			if( !empty($v) ) {
				$sel[$k] = $v;
			}
		}
	}
} else {
	$sel = $this->selectedReservation;
	$id = $sel['id'];
}

$cf_data = $sel['custom_f'];

$shifts = $this->shifts;
$continuos = $this->continuos;

$min_intervals = cleverdine::getMinuteIntervals(true);

$min_people = cleverdine::getMinimumPeople(true);
$max_people = cleverdine::getMaximumPeople(true);

$curr_symb 	= cleverdine::getCurrencySymb(true);
$symb_pos 	= cleverdine::getCurrencySymbPosition(true);

// START SELECT HOURS

$select_hours = '<select name="hourmin" class="vrsearchhour vik-dropdown" id="vrhour" class="required">';
$_hm_e = explode( ':', $sel["hourmin"] );
$_hm = "";
if( count( $_hm_e ) == 2 ) {
	$_hm = $_hm_e[0].':'.intval($_hm_e[1]);
}

$time_f = cleverdine::getTimeFormat(true);

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

$select_tables = '<select name="id_table" id="vrtablesel" class="required">';
$select_tables .= '<option class="placeholder"></option>';
foreach( $rooms as $_r ) {
	$select_tables .= '<optgroup label="'.$_r['name'].'">';
	foreach( $_r['tables'] as $_t ) {
		$ori_name = $_t['name'].' ('.$_t['min_capacity'].'-'.$_t['max_capacity'].')';
		$select_tables .= '<option '.(($_t['id'] == $sel['id_table']) ? 'selected="selected"' : '').'value="'.$_t['id'].'" data-capacity="'.$_t['min_capacity'].'-'.$_t['max_capacity'].'" data-name="'.$ori_name.'" data-shared="'.$_t['multi_res'].'">'.$ori_name.'</option>';
	}
	$select_tables .= '</optgroup>';
}
$select_tables .= '</select>';

// END SELECT TABLES

// START SELECT PEOPLE

$select_people = '<select name="people" id="vrpeoplesel" class="vik-dropdown required" onChange="peopleNumberChanged();">';
for( $i = cleverdine::getMinimumPeople(true), $n = cleverdine::getMaximumPeople(true); $i <= $n; $i++ ) {
	$select_people .= '<option value="'.$i.'" '.(($i == $sel['people']) ? 'selected="selected"' : '').'>'.$i.'</option>';
}
$select_people .= '</select>';

// END SELECT PEOPLE

// START SELECT STATUS

$all_status = array( 'REMOVED', 'CANCELLED', 'PENDING', 'CONFIRMED');

$select_status = '<select name="status" class="vik-dropdown required">';
foreach( $all_status as $_s ) {
	$select_status .= '<option value="'.$_s.'" '.(($_s == $sel['status']) ? 'selected="selected"' : '').'>'.JText::_('VRRESERVATIONSTATUS'.$_s).'</option>';
}
$select_status .= '</select>';

// END SELECT STATUS

if( empty($sel['purchaser_country']) ) {
	foreach( $cfields as $cf ) {
		if( $cf['type'] == 'text' && $cf['rule'] == VRCustomFields::PHONE_NUMBER ) {
			$sel['purchaser_country'] = $cf['choose'];
			break;
		}
	}
}

if( empty($sel['id_user']) || $sel['id_user'] == -1 ) {
	$sel['id_user'] = '';
}

$editor = JEditor::getInstance(JFactory::getApplication()->get('editor'));

$vik = new VikApplication(VersionListener::getID());

?>

<form name="adminForm" action="index.php" method="post" enctype="multipart/form-data" id="adminForm">

	<!-- empty div to align span6 boxes vertically -->
	<div></div>
	
	<div class="span6">
		<?php echo $vik->openFieldset(JText::_('VRMANAGERESERVATIONTITLE1'), 'form-horizontal'); ?>
			
			<!-- DATE - Calendar -->
			<?php echo $vik->openControl(JText::_('VRMANAGERESERVATION13').'*:'); ?>
				<?php
				$attributes = array();
				$attributes['class'] 	= 'required';
				$attributes['onChange'] = "vrUpdateWorkingShifts();vrUpdateAvailableTables();";

				echo $vik->calendar($sel['date'], 'date', 'vrdatefilter', null, $attributes);
				?>
			<?php echo $vik->closeControl(); ?>
			
			<!-- TIME - Dropdown -->
			<?php echo $vik->openControl(JText::_('VRMANAGERESERVATION14').'*:'); ?>
				<?php echo $select_hours; ?>
				<a href="javascript: void(0);" id="busytime" onclick="vrOpenJModal(this.id, null, true); return false;" style="margin-left: 5px;">
					<i class="fa fa-calendar big"></i>
				</a>
				<a href="javascript: void(0);" id="staytime" onclick="vrOpenStayTime(this)" style="margin-left: 15px;">
					<i class="fa fa-chevron-down"></i>
				</a>
			<?php echo $vik->closeControl(); ?>

			<!-- STAY TIME - Number -->
			<div style="border-top-width: 1px; border-top-style: dashed; border-top-color: rgb(204, 204, 204); width: 70%; display: none;" class="staytime-field">&nbsp;</div>

			<?php echo $vik->openControl(JText::_('VRMANAGERESERVATION25').':', 'staytime-field', 'style="display:none;"'); ?>
				<input type="number" name="stay_time" value="<?php echo $sel['stay_time']; ?>" min="15" max="9999" step="5" />
				&nbsp;<?php echo JText::_('VRSHORTCUTMINUTE'); ?>
			<?php echo $vik->closeControl(); ?>

			<div style="border-top-width: 1px; border-top-style: dashed; border-top-color: rgb(204, 204, 204); width: 70%; display: none;" class="staytime-field">&nbsp;</div>
			
			<!-- PEOPLE - Dropdown -->
			<?php echo $vik->openControl(JText::_('VRMANAGERESERVATION4').'*:'); ?>
				<?php echo $select_people; ?>
			<?php echo $vik->closeControl(); ?>

			<!-- TABLE - Dropdown -->
			<?php echo $vik->openControl(JText::_('VRMANAGERESERVATION5').'*:'); ?>
				<?php echo $select_tables; ?>
				<a href="javascript: void(0);" onclick="unlockDisabledTables(this);" id="unlock-tables-link" style="margin-left: 5px;">
					<i class="fa fa-lock big"></i>
				</a>
			<?php echo $vik->closeControl(); ?>
			
			<!-- USER - Dropdown -->
			<?php echo $vik->openControl(JText::_('VRMANAGERESERVATION22').':'); ?>
				<input type="hidden" name="id_user" class="vr-users-select" value="<?php echo $sel['id_user']; ?>"/>
				<a href="javascript: void(0);" id="addcustomer" onclick="vrOpenJModal(this.id, null, true); return false;" style="margin-left: 5px;<?php echo ($sel['id_user'] > 0 ? 'display:none;' : ''); ?>">
					<i class="fa fa-user-plus big"></i>
				</a>
			<?php echo $vik->closeControl(); ?>
			
			<!-- NOMINATIVE - Text -->
			<?php echo $vik->openControl(JText::_('VRMANAGERESERVATION18').':'); ?>
				<input class="vr-nominative-field" type="text" name="purchaser_nominative" value="<?php echo $sel['purchaser_nominative']; ?>" size="40" />
			<?php echo $vik->closeControl(); ?>
			
			<!-- EMAIL - Text -->
			<?php echo $vik->openControl(JText::_('VRMANAGERESERVATION6').':'); ?>
				<input class="vr-email-field" type="text" name="purchaser_mail" value="<?php echo $sel['purchaser_mail']; ?>" size="40" id="vremail" onBlur="composeMailFields();"/>
			<?php echo $vik->closeControl(); ?>
			
			<!-- PHONE NUMBER - Text -->
			<?php echo $vik->openControl(JText::_('VRMANAGERESERVATION16').':'); ?>
				<?php 
				echo '<select name="phone_prefix" class="vr-phones-select">';
				foreach( $this->countries as $i => $ctry ) {
					$suffix = "";
					if( ($i != 0 && $this->countries[$i-1]['phone_prefix'] == $ctry['phone_prefix']) || ($i != count($this->countries)-1 && $this->countries[$i+1]['phone_prefix']==$ctry['phone_prefix']) ) {
						$suffix = ' : '.$ctry['country_2_code'];
					}
					echo '<option value="'.$ctry['id']."_".$ctry['country_2_code'].'" title="'.trim($ctry['country_name']).'" '.($sel['purchaser_country'] == $ctry['country_2_code'] ? 'selected="selected"' : '').'>'.$ctry['phone_prefix'].$suffix.'</option>';
				}
				echo '</select>';
				?>
				<input class="vr-phone-field" type="text" name="purchaser_phone" value="<?php echo $sel['purchaser_phone']; ?>" size="40" id="vrphone" onBlur="composePhoneFields();" style="width: 181px !important;"/>
			<?php echo $vik->closeControl(); ?>
			
			<!-- DEPOSIT - Number -->
			<?php echo $vik->openControl(JText::_('VRMANAGERESERVATION9').':'); ?>
				<input type="number" name="deposit" value="<?php echo $sel['deposit']; ?>" size="8" min="0" max="999999" step="any"/>
				&nbsp;<?php echo $curr_symb; ?>
			<?php echo $vik->closeControl(); ?>
			
			<!-- BILL VALUE - Number -->
			<?php echo $vik->openControl(JText::_('VRMANAGERESERVATION10').':'); ?>
				<input type="number" name="bill_value" value="<?php echo $sel['bill_value']; ?>" size="8" min="0" max="999999" step="any"/>
				&nbsp;<?php echo $curr_symb; ?>
			<?php echo $vik->closeControl(); ?>
			
			<!-- BILL CLOSED - Radio Button -->
			<?php
			$elem_yes = $vik->initRadioElement('', JText::_('VRYES'), $sel['bill_closed'] == 1);
			$elem_no = $vik->initRadioElement('', JText::_('VRNO'), $sel['bill_closed'] == 0);
			?>
			<?php echo $vik->openControl(JText::_('VRMANAGERESERVATION11').':'); ?>
				<?php echo $vik->radioYesNo('bill_closed', $elem_yes, $elem_no, false); ?>
			<?php echo $vik->closeControl(); ?>
			
			<!-- STATUS - Dropdown -->
			<?php echo $vik->openControl(JText::_('VRMANAGERESERVATION12').':'); ?>
				<?php echo $select_status; ?>
			<?php echo $vik->closeControl(); ?>

			<!-- PAYMENT - Dropdown -->
			<?php
			$elements = array(
				$vik->initOptionElement('', '', $sel['id_payment'] <= 0)
			);

			$published = -1;

			foreach( $this->payments as $pay ) {
				if( $published != $pay['published'] ) {
					$published = $pay['published'];
					
					array_push($elements, $vik->initOptionElement('', JText::_('VRSYSPUBLISHED'.$published), false, true));
				}

				$p_charge = '';
				if( $pay['charge'] != 0 ) {
					$p_charge = ' '.($pay['charge'] > 0 ? '+' : '');
					if( $pay['percentot'] == 1 ) {
						$p_charge .= (float)$pay['charge'].'%';
					} else {
						$p_charge .= cleverdine::printPriceCurrencySymb($pay['charge'], $curr_symb, $symb_pos, true);
					}
				}

				$html_attr = 'data-charge="'.$pay['charge'].'" data-percentot="'.$pay['percentot'].'"';

				array_push($elements, $vik->initOptionElement($pay['id'], $pay['name'].$p_charge, $sel['id_payment'] == $pay['id'], false, false, $html_attr));
			}

			?>
			<?php echo $vik->openControl(JText::_('VRMANAGERESERVATION20').":"); ?>
				<?php echo $vik->dropdown('id_payment', $elements, 'vr-payment-sel'); ?>
			<?php echo $vik->closeControl(); ?>
			
			<!-- NOTIFY CUSTOMER - Radio Button -->
			<?php
			$elem_yes = $vik->initRadioElement('', JText::_('VRYES'), 0);
			$elem_no = $vik->initRadioElement('', JText::_('VRNO'), 1);
			?>
			<?php echo $vik->openControl(JText::_('VRMANAGERESERVATION15').':'); ?>
				<?php echo $vik->radioYesNo('notify_customer', $elem_yes, $elem_no, false); ?>
			<?php echo $vik->closeControl(); ?>
			
		<?php echo $vik->closeFieldset(); ?>
	</div>
	
	<?php if( count($cfields) > 0 ) { ?>
		<div class="span5">
			<?php echo $vik->openFieldset(JText::_('VRMANAGERESERVATIONTITLE2'), 'form-horizontal'); ?>
					
				<?php foreach( $cfields as $cf ) {
					
					if( !empty( $cf['poplink'] ) ) {
						$fname = "<a href=\"" . $cf['poplink'] . "\" id=\"vrcf" . $cf['id'] . "\" rel=\"{handler: 'iframe', size: {x: 750, y: 600}}\" target=\"_blank\" class=\"modal\">" . JText::_($cf['name']) . "</a>";
					} else {
						$fname = "<span id=\"vrcf" . $cf['id'] . "\">" . JText::_($cf['name']) . "</span>";
					}
					
					$_val = "";
					if( !empty($cf_data[$cf['name']]) ) {
						$_val = $cf_data[$cf['name']];
					}

					$field_class = '';

					if( $cf['rule'] == VRCustomFields::NOMINATIVE ) {
						$field_class = 'vr-nominative-field';
					} else if( $cf['rule'] == VRCustomFields::EMAIL ) {
						$field_class = 'vr-email-field';
					} else if( $cf['rule'] == VRCustomFields::PHONE_NUMBER ) {
						$field_class = 'vr-phone-field';
					} else if( $cf['rule'] == VRCustomFields::ADDRESS ) {
						$field_class = 'vr-address-field vr-delivery-field';
					} else if( $cf['rule'] == VRCustomFields::DELIVERY ) {
						$field_class = 'vr-delivery-field';
					} else if( $cf['rule'] == VRCustomFields::ZIP ) {
						$field_class = 'vr-zip-field vr-delivery-field';
					}
					
					if( $cf['type'] != "separator" ) {
						echo $vik->openControl($fname.':');
						
						if( $cf['type'] == "text") {
						
							$text_width = 272;
							?>
							<?php 
							if( $cf['rule'] == VRCustomFields::PHONE_NUMBER ) {
								$text_width = 178;
								echo '<select name="vrcf'.$cf['id'].'_prfx" class="vr-phones-select">';
								foreach( $this->countries as $i => $ctry ) {
									$suffix = "";
									if( ($i != 0 && $this->countries[$i-1]['phone_prefix'] == $ctry['phone_prefix']) || ($i != count($this->countries)-1 && $this->countries[$i+1]['phone_prefix']==$ctry['phone_prefix']) ) {
										$suffix = ' : '.$ctry['country_2_code'];
									}
									echo '<option value="'.$ctry['id']."_".$ctry['country_2_code'].'" title="'.trim($ctry['country_name']).'" '.($sel['purchaser_country'] == $ctry['country_2_code'] ? 'selected="selected"' : '').'>'.$ctry['phone_prefix'].$suffix.'</option>';
								}
								echo '</select>';
							}
							?>
							<input type="text" name="vrcf<?php echo $cf['id']; ?>" value="<?php echo $_val; ?>" class="vr-custom-field <?php echo $field_class; ?>" size="40" style="width: <?php echo $text_width; ?>px !important;" data-cfname="<?php echo $cf['name']; ?>"/>
						
						<?php } else if( $cf['type'] == "textarea" ) { ?>
							
							<textarea name="vrcf<?php echo $cf['id']; ?>" rows="5" cols="30" class="vrtextarea vr-custom-field <?php echo $field_class; ?>" data-cfname="<?php echo $cf['name']; ?>"><?php echo $_val; ?></textarea>
						
						<?php } else if( $cf['type'] == "date" ) { ?>

							<td><?php echo $vik->calendar($_val, 'vrcf'.$cf['id'], 'vrcf'.$cf['id'].'date', null, array('class' => 'vr-custom-field '.$field_class, 'data-cfname' => $cf['name'])); ?></td>

						<?php } else if( $cf['type'] == "select" ) {

							$answ = explode(";;__;;", $cf['choose']);
							$wcfsel = '<select name="vrcf'.$cf['id'].'" class="vr-custom-field '.$field_class.' vik-dropdown" data-cfname="'.$cf['name'].'">';
							foreach( $answ as $aw ) {
								if ( !empty($aw) ) {
									$wcfsel .= '<option value="'.$aw.'" '.($_val == $aw ? 'selected="selected"' : '').'>'.$aw.'</option>';
								}
							}
							$wcfsel .= '</select>';
							?>
							<?php echo $wcfsel; ?>

						<?php } else if( $cf['type'] == 'checkbox' ) { ?>

							<input type="checkbox" name="vrcf<?php echo $cf['id']; ?>" value="<?php echo JText::_('VRYES'); ?>" <?php echo ($_val == JText::_('VRYES') ? 'checked="checked"' : ''); ?> class="vr-custom-field <?php echo $field_class; ?>" data-cfname="<?php echo $cf['name']; ?>"/>

						<?php }
						
						echo $vik->closeControl();
					}
				} 
				?>
			<?php echo $vik->closeFieldset(); ?>
		</div>
	<?php } ?>
	
	<?php if( count($this->allMenus) > 0 ) { ?>
		<div class="span5">
			<?php echo $vik->openFieldset(JText::_('VRMENUMENUS'), 'form-horizontal'); ?>
				<?php foreach( $this->allMenus as $m ) { 
					$q = (!empty($this->selectedMenus[$m['id']]['quantity']) ? $this->selectedMenus[$m['id']]['quantity'] : 0);
					$assoc = (!empty($this->selectedMenus[$m['id']]['assoc']) ? $this->selectedMenus[$m['id']]['assoc'] : 0);
					?>
					<?php echo $vik->openControl($m['name'].':'); ?>
						<input type="number" name="quantity[<?php echo $m['id']; ?>]" value="<?php echo $q; ?>" class="vrmenuquant"
							min="0" max="<?php echo $sel['people']; ?>" style="text-align: right;" />
						<input type="hidden" name="menu_assoc[]" value="<?php echo $assoc; ?>"/>
					<?php echo $vik->closeControl(); ?>
				<?php } ?>
			<?php echo $vik->closeFieldset(); ?>
		</div>
	<?php } ?>
	
	<div class="span10">
		<?php echo $vik->openFieldset(JText::_('VRMANAGERESERVATIONTITLE3'), 'form-horizontal'); ?>
		   <div class="control-group"><?php echo $editor->display('notes', $sel['notes'], 400, 200, 70, 20); ?></div>
		<?php echo $vik->closeFieldset(); ?>
	</div>
	
	<input type="hidden" name="from" value="<?php echo $this->returnTask; ?>"/>
	<input type="hidden" name="id" value="<?php echo $id; ?>"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="option" value="com_cleverdine"/>
</form>

<!-- JQUERY MODALS -->

<div class="modal hide fade" id="jmodal-busytime" style="width:90%;height:80%;margin-left:-45%;">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h3><?php echo JText::_('VRRESBUSYMODALTITLE'); ?></h3>
	</div>
	<div id="jmodal-box-busytime"></div>
</div>

<div class="modal hide fade" id="jmodal-addcustomer" style="width:90%;height:80%;margin-left:-45%;">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h3><?php echo JText::_('VRMAINTITLENEWCUSTOMER'); ?></h3>
	</div>
	<div id="jmodal-box-addcustomer"></div>
</div>

<script>
	
	var selected_people = <?php echo $sel['people']; ?>;
	
	var BILLING_USER_LIST = [];

	var IS_AJAX_CALLING = false;
	
	jQuery(document).ready(function(){

		jQuery('.vik-dropdown').select2({
			minimumResultsForSearch: -1,
			allowClear: false,
			width: 200,
		});

		jQuery('#vrtablesel').select2({
			placeholder: '- select a table -',
			allowClear: false,
			width: 200,
			formatResult: formatTablesSelect,
			formatSelection: formatTablesSelect,
			escapeMarkup: function(m) { return m; }
		});

		jQuery('#vr-payment-sel').select2({
			allowClear: true,
			placeholder: '<?php echo addslashes(JText::_('VRMANAGECONFIG32')); ?>',
			width: 200
		});

		jQuery('.vr-phones-select').select2({
			minimumResultsForSearch: -1,
			allowClear: false,
			width: 90
		});
		
		jQuery('.vr-phones-select').on('change', function(){
			jQuery('.vr-phones-select').select2('val', jQuery(this).val());
		});

		jQuery('.vr-users-select').on('change', function(){

			var val = jQuery(this).val();

			if( val.length == 0 ) {
				jQuery('#addcustomer').show();
			} else {
				jQuery('#addcustomer').hide();
			}

			// fill billing
			if( !jQuery.isEmptyObject(BILLING_USER_LIST[val]) ) {
				// nominative
				if( jQuery('.vr-nominative-field').length <= 2 ) {
					// all the fields found are FULL NAME
					jQuery('.vr-nominative-field').each(function(){
						jQuery(this).val(BILLING_USER_LIST[val].name);
					});
				} else {
					// otherwise only the first is FULL NAME
					jQuery('.vr-nominative-field').first().val(BILLING_USER_LIST[val].name);
				}

				// mail
				jQuery('.vr-email-field').each(function(){
					jQuery(this).val(BILLING_USER_LIST[val].mail);
				});

				// phone number
				jQuery('.vr-phone-field').each(function(){
					jQuery(this).val(BILLING_USER_LIST[val].phone);
				});

				// country code
				var cc = null;
				jQuery('select.vr-phones-select').first().find('option').each(function(){
					var code = jQuery(this).val();
					if( code.indexOf(BILLING_USER_LIST[val].country) >= 0 ) {
						cc = code;
						return false;
					}
				});
				if( cc !== null ) {
					jQuery('.vr-phones-select').select2('val', cc);
				}

				// fill all remaining custom fields
				jQuery.each(BILLING_USER_LIST[val].fields, function(cf_name, cf_val){
					
					var input = jQuery('.vr-custom-field[data-cfname="'+cf_name+'"]');

					if( input.length ) {

						if( input.is('select') ) {
							// refresh always select value
							input.val(cf_val);
						} else if( !input.is('checkbox') ) {
							// otherwise refresh only when field is not a checkbox
							input.val(cf_val);
						}

					}

				});

			}

		});
		
		jQuery('.vr-users-select').select2({
			placeholder: '<?php echo addslashes(JText::_('VRMANAGERESERVATION23')); ?>',
			allowClear: true,
			width: 300,
			minimumInputLength: 2,
			ajax: {
				url: 'index.php?option=com_cleverdine&task=search_users&tmpl=component',
				dataType: 'json',
				type: "POST",
				quietMillis: 50,
				data: function(term) {
					return {
						term: term
					};
				},
				results: function(data) {
					return {
						results: jQuery.map(data, function (item) {

							if( jQuery.isEmptyObject(BILLING_USER_LIST[item.id]) ) {
								BILLING_USER_LIST[item.id] = {
									name: item.billing_name,
									mail: item.billing_mail,
									phone: item.billing_phone,
									country: item.country_code,
									fields: item.fields
								};
							}

							return {
								text: item.billing_name,
								id: item.id
							}
						})
					};
				},
			},
			initSelection: function(element, callback) {
				// the input tag has a value attribute preloaded that points to a preselected repository's id
				// this function resolves that id attribute to an object that select2 can render
				// using its formatResult renderer - that way the repository name is shown preselected
				if( jQuery(element).val().length ) {
					callback({billing_name: '<?php echo ($this->customer === null ? '' : addslashes($this->customer['billing_name'])); ?>'});
				}
			},
			formatSelection: function(data) {
				if( jQuery.isEmptyObject(data.billing_name) ) {
					// display data retured from ajax parsing
					return data.text;
				}
				// display pre-selected value
				return data.billing_name;
			},
			dropdownCssClass: "bigdrop"
		});

		// update working shifts

		// @deprecated (the event is triggered directly from the calendar)
		jQuery('#vrdatefilter').on('change', function(){
			vrUpdateWorkingShifts();
		});

		// update available tables

		jQuery('#vrdatefilter, #vrhour, #vrpeoplesel').on('change', function(){
			vrUpdateAvailableTables();
		});

		// refresh available tables
		vrUpdateAvailableTables();

	});
	
	function peopleNumberChanged() {
		var people = jQuery('#vrpeoplesel').val();
		jQuery('.vrmenuquant').each(function(){
			var max = parseInt(jQuery(this).prop('max'))+(people-selected_people);
			jQuery(this).prop('max', max);
			if( jQuery(this).val() > max ) {
				jQuery(this).val(max);
			}
		});
		
		selected_people = people;
	}
	
	function vrUpdateWorkingShifts() {
		jQuery('#vrhour').prop('disabled', true);
		
		jQuery.noConflict();

		IS_AJAX_CALLING = true;
		
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
			
			jQuery("#vrhour").select2({
				allowClear: false,
				width: 200
			});
			
			jQuery('#vrhour').prop('disabled', false);

			IS_AJAX_CALLING = false;
			
		}).fail(function(resp){
			jQuery('#vrhour').prop('disabled', false);

			IS_AJAX_CALLING = false;
		});
	}
	
	function composeMailFields() {
		var email = jQuery('#vremail').val();
		jQuery('.vremailfield').val(email);
	}
	
	function composePhoneFields() {
		var phone = jQuery('#vrphone').val();
		jQuery('.vrphonefield').val(phone);
	}

	function vrUpdateAvailableTables() {

		jQuery('#vrtablesel').prop('disabled', true);

		jQuery.noConflict();

		IS_AJAX_CALLING = true;
		
		var jqxhr = jQuery.ajax({
			type: "POST",
			url: "index.php?option=com_cleverdine&task=get_available_tables&tmpl=component",
			data: { 
				date: 		jQuery('#vrdatefilter').val(),
				hourmin: 	jQuery('#vrhour').val(),
				people: 	jQuery('#vrpeoplesel').val(),
			}
		}).done(function(resp){
			var obj = jQuery.parseJSON(resp); 

			LAST_TABLES = obj;
			
			enableAvailableTables(obj, UNLOCK_TABLES);

			jQuery('#vrtablesel').prop('disabled', false);

			IS_AJAX_CALLING = false;
			
		}).fail(function(resp){
			enableAvailableTables([], UNLOCK_TABLES);

			LAST_TABLES = [];

			jQuery('#vrtablesel').prop('disabled', false);

			IS_AJAX_CALLING = false;
		});

	}

	function enableAvailableTables(arr, force) {
		var people = jQuery('#vrpeoplesel').val();

		jQuery('#vrtablesel option:not(option.placeholder)').each(function(){
			var _in = ( force !== undefined || CURR_TABLE == jQuery(this).val() || jQuery.inArray(jQuery(this).val(), arr) !== -1 );
			jQuery(this).prop('disabled', !_in);

			var txt = jQuery(this).data('name');

			if( !_in ) {
				var capacity = jQuery(this).data('capacity').split('-');

				if( parseInt(capacity[0]) <= people && people <= parseInt(capacity[1]) ) {
					txt += ' : <?php echo addslashes(JText::_('VRTABNOTAV')); ?>';
				} else {
					txt += ' : <?php echo addslashes(JText::_('VRTABNOTFIT')); ?>';
				}
			}

			jQuery(this).text(txt);

		});

		jQuery('#vrtablesel').select2('val', jQuery('#vrtablesel').val());
	}

	var UNLOCK_TABLES = undefined;
	var LAST_TABLES = [];
	var CURR_TABLE = <?php echo $sel['id_table']; ?>;

	function unlockDisabledTables(link) {

		if( UNLOCK_TABLES ) {
			UNLOCK_TABLES = undefined;

			jQuery(link).find('i.fa').removeClass('fa-unlock').addClass('fa-lock');
		} else {
			UNLOCK_TABLES = true;

			jQuery(link).find('i.fa').removeClass('fa-lock').addClass('fa-unlock');
		}

		enableAvailableTables(LAST_TABLES, UNLOCK_TABLES);

	}

	function formatTablesSelect(opt) {
		if(!opt.id) return opt.text; // optgroup

		var html = opt.text;

		if( jQuery(opt.element).data('shared') == "1" ) {
			html = '<i class="fa fa-users" style=""></i> '+html;
		}

		return html;
	}

	var INITIAL_STAY_TIME = <?php echo $sel['stay_time']; ?>;

	function vrOpenStayTime(icon) {
		icon = jQuery(icon).find('i');

		if( jQuery(icon).hasClass('fa-chevron-down') ) {
			jQuery(icon).removeClass('fa-chevron-down').addClass('fa-chevron-up');

			jQuery('.staytime-field').show();

			var val = parseInt(jQuery('input[name="stay_time"]').val());
			if( val == 0 ) {
				jQuery('input[name="stay_time"]').val(<?php echo cleverdine::getAverageTimeStay(true); ?>);
			}
		} else {
			jQuery(icon).removeClass('fa-chevron-up').addClass('fa-chevron-down');

			jQuery('.staytime-field').hide();

			jQuery('input[name="stay_time"]').val(INITIAL_STAY_TIME);	
		}
	}

	// validate

	jQuery(document).ready(function(){
		jQuery("#adminForm .required").on("blur", function(){
			if( jQuery(this).val().length > 0 ) {
				jQuery(this).removeClass("vrrequired");
			} else {
				jQuery(this).addClass("vrrequired");
			}
		});
	});

	function vrValidateFields() {
		var ok = true;
		jQuery("#adminForm .required:input").each(function(){
			var val = jQuery(this).val();
			if( val !== null && val.length > 0 ) {
				jQuery(this).removeClass("vrrequired");
			} else {
				jQuery(this).addClass("vrrequired");
				ok = false;
			}
		});
		return ok && !IS_AJAX_CALLING;
	}

	Joomla.submitbutton = function(task) {
		if( task.indexOf('save') !== -1 ) {
			if( vrValidateFields() ) {
				Joomla.submitform(task, document.adminForm);	
			}
		} else {
			Joomla.submitform(task, document.adminForm);
		}
	}

	// MODAL BOXES

	function vrOpenJModal(id, url, jqmodal) {
		<?php echo $vik->bootOpenModalJS(); ?>
	}

	jQuery(document).ready(function(){

		jQuery('#jmodal-busytime').on('show', function() {
			var href = 'index.php?option=com_cleverdine&task=restbusyres&tmpl=component&date='+jQuery('#vrdatefilter').val()+'&time='+jQuery('#vrhour').val();
			var size = {
				width: jQuery('#jmodal-busytime').width(), //940,
				height: jQuery('#jmodal-busytime').height(), //590
			}
			appendModalContent('jmodal-box-busytime', href, size);
		});

		jQuery('#jmodal-addcustomer').on('show', function() {
			var href = 'index.php?option=com_cleverdine&task=newcustomer&tmpl=component';
			var size = {
				width: jQuery('#jmodal-addcustomer').width(), //940,
				height: jQuery('#jmodal-addcustomer').height(), //590
			}
			appendModalContent('jmodal-box-addcustomer', href, size);
		});

	});
	
	function appendModalContent(id, href, size) {
		jQuery('#'+id).html('<div class="modal-body" style="max-height:'+(size.height-20)+'px;">'+
		'<iframe class="iframe" src="'+href+'" width="'+size.width+'" height="'+size.height+'" style="max-height:'+(size.height-100)+'px;"></iframe>'+
		'</div>');
	}
	
</script>