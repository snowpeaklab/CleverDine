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

$cfields = $this->custom_fields;

$sel = $this->selectedReservation;
$id = $sel['id'];

$cf_data = $sel['custom_f'];

$shifts = $this->shifts;
$continuos = $this->continuos;
$min_intervals = cleverdine::getTakeAwayMinuteInterval(true);

$curr_symb = cleverdine::getCurrencySymb(true);
$symb_pos = cleverdine::getCurrencySymbPosition(true);

// START SELECT HOURS

$select_hours = '<select name="hourmin" class="vrsearchhour vik-dropdown required" id="vrhour" class="required">';
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

// START SELECT STATUS

$all_status = array( 'REMOVED', 'CANCELLED', 'PENDING', 'CONFIRMED');

$select_status = '<select name="status" class="vik-dropdown required">';
foreach( $all_status as $_s ) {
	$select_status .= '<option value="'.$_s.'" '.(($_s == $sel['status']) ? 'selected="selected"' : '').'>'.JText::_('VRRESERVATIONSTATUS'.$_s).'</option>';
}
$select_status .= '</select>';

// END SELECT STATUS

$date_format = cleverdine::getDateFormat(true);

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

$origin_addresses = cleverdine::getTakeAwayOriginAddresses(true);

$route_obj = null;
if( !empty($sel['route']) ) {
	$route_obj = json_decode($sel['route']);
}

$editor = JEditor::getInstance(JFactory::getApplication()->get('editor'));

$vik = new VikApplication(VersionListener::getID());

?>

<form name="adminForm" action="index.php" method="post" enctype="multipart/form-data" id="adminForm">
	
	<!-- empty div to align span6 boxes vertically -->
	<div></div>
	
	<div class="span6">
		<?php echo $vik->openFieldset(JText::_('VRMANAGETKRESTITLE1'), 'form-horizontal'); ?>
		
			<!-- DATE - Calendar -->
			<?php echo $vik->openControl(JText::_('VRMANAGETKRES10')."*:"); ?>
				<?php 
				$attributes = array();
				$attributes['onChange'] = 'vrUpdateWorkingShifts();';
				echo $vik->calendar($sel['date'], 'date', 'vrdatefilter', null, $attributes);
				?>

			<?php echo $vik->closeControl(); ?>
			
			<!-- HOUR MIN - Dropdown -->
			<?php echo $vik->openControl(JText::_('VRMANAGETKRES11')."*:"); ?>
				<?php echo $select_hours; ?>
				<a href="javascript: void(0);" id="busytime" onclick="vrOpenJModal(this.id, null, true); return false;" style="margin-left: 5px;">
					<i class="fa fa-calendar big"></i>
				</a>
			<?php echo $vik->closeControl(); ?>
			
			<!-- SERVICE - Dropdown -->
			<?php
			$elements = array(
				$vik->initOptionElement(1, JText::_("VRMANAGETKRES14"), $sel['delivery_service']==1),
				$vik->initOptionElement(0, JText::_("VRMANAGETKRES15"), $sel['delivery_service']==0),
			);
			?>
			<?php echo $vik->openControl(JText::_('VRMANAGETKRES13')."*:"); ?>
				<?php echo $vik->dropdown("delivery_service", $elements, 'vr-service-select'); ?>   
			<?php echo $vik->closeControl(); ?>

			<?php if( count($origin_addresses) ) { ?>

				<!-- ORIGIN ADDRESS - Dropdown -->
				<?php
				$elements = array(
					$vik->initOptionElement('', '', false),
				);

				foreach( $origin_addresses as $origin ) {
					array_push($elements, $vik->initOptionElement($origin, $origin, count($origin_addresses) == 1 || ($route_obj !== null && $origin == $route_obj->origin)));
				}
				?>
				<?php echo $vik->openControl(JText::_('VRMANAGETKRES32').":"); ?>
					<?php echo $vik->dropdown("route[origin]", $elements, 'vr-origin-select'); ?>   
				<?php echo $vik->closeControl(); ?>

				<!-- ROUTE DETAILS - Info -->
				<?php
				$route_details = '';
				if( $route_obj !== null ) {
					$keys = array('distancetext' => 'road', 'durationtext' => 'clock-o');

					foreach( $keys as $k => $icon ) {
						if( !empty($route_obj->$k) ) {
							$marginleft = 0;
							if( strlen($route_details) ) {
								$marginleft = 15;
							}

							$route_details .= '<i class="fa fa-'.$icon.'" style="margin-right:5px;margin-left:'.$marginleft.'px;"></i>'.$route_obj->$k;
						}
					}
				}
				?>
				<?php echo $vik->openControl(JText::_('VRMANAGETKRES33').":", 'vrroutedetailswrap', 'style="'.(empty($route_details) ? 'display:none;' : '').'"'); ?>
					<div id="vrroutedetails" class="control-html-value"><?php echo $route_details; ?></div>
				<?php echo $vik->closeControl(); ?>

				<input type="hidden" name="route[distance]" value="<?php echo ($route_obj !== null ? $route_obj->distance : ''); ?>" id="vrorigindistance"/>
				<input type="hidden" name="route[duration]" value="<?php echo ($route_obj !== null ? $route_obj->duration : ''); ?>" id="vroriginduration"/>

				<input type="hidden" name="route[distancetext]" value="<?php echo ($route_obj !== null ? $route_obj->distancetext : ''); ?>" id="vrorigindistancetext"/>
				<input type="hidden" name="route[durationtext]" value="<?php echo ($route_obj !== null ? $route_obj->durationtext : ''); ?>" id="vrorigindurationtext"/>

			<?php } ?>

			<div style="border-top: 1px dashed #ccc;width: 70%;">&nbsp;</div>
			
			<!-- USER - Dropdown -->
			<?php echo $vik->openControl(JText::_('VRMANAGERESERVATION22').':'); ?>
				<input type="hidden" name="id_user" class="vr-users-select" value="<?php echo $sel['id_user']; ?>"/>
				<a href="javascript: void(0);" id="addcustomer" onclick="vrOpenJModal(this.id, null, true); return false;" style="margin-left: 5px;<?php echo ($sel['id_user'] > 0 ? 'display:none;' : ''); ?>">
					<i class="fa fa-user-plus big"></i>
				</a>
			<?php echo $vik->closeControl(); ?>

			<!-- USER ADDRESS - Dropdown -->
			<?php
			$elements = array(
				$vik->initOptionElement('', '', false)
			);
			$max_percent = 0;
			if( $this->customer !== null ) {
				foreach( $this->customer['delivery'] as $addr ) {
					$addr_str = cleverdine::deliveryAddressToStr($addr, array('country', 'address_2'));

					$percent = 0;
					similar_text($addr_str, $sel['purchaser_address'], $percent);

					array_push($elements, $vik->initOptionElement($addr_str, $addr_str, ($percent >= 75 && $percent > $max_percent)));

					$max_percent = max(array($max_percent, $percent));
				}
			}
			?>
			<?php echo $vik->openControl(JText::_('VRMANAGETKRES29').':'); ?>
				<?php echo $vik->dropdown('id_useraddr', $elements, 'vr-user-address'); ?>
			<?php echo $vik->closeControl(); ?>
			
			<!-- PURCHASER NOMINATIVE - Text -->
			<?php echo $vik->openControl(JText::_('VRMANAGETKRES25').":"); ?>
				<input class="vr-nominative-field" type="text" name="purchaser_nominative" value="<?php echo $sel['purchaser_nominative']; ?>" size="40" /> 
			<?php echo $vik->closeControl(); ?>
			
			<!-- PURCHASER EMAIL - Text -->
			<?php echo $vik->openControl(JText::_('VRMANAGETKRES5').":"); ?>
				<input class="vr-email-field" type="text" name="purchaser_mail" value="<?php echo $sel['purchaser_mail']; ?>" size="40" id="vremail" onBlur="composeMailFields();"/>
			<?php echo $vik->closeControl(); ?>
			
			<!-- PURCHASER PHONE - Text -->
			<?php echo $vik->openControl(JText::_('VRMANAGETKRES23').":"); ?>
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
				<input class="vr-phone-field" type="text" name="purchaser_phone" value="<?php echo $sel['purchaser_phone']; ?>" size="40" id="vrphone" onBlur="composePhoneFields();" style="width: 178px !important;"/>
			<?php echo $vik->closeControl(); ?>

			<div style="border-top: 1px dashed #ccc;width: 70%;">&nbsp;</div>
			
			<!-- TOTAL TO PAY - Number -->
			<?php echo $vik->openControl(JText::_('VRMANAGETKRES8').":"); ?>
				<input type="number" name="total_to_pay" value="<?php echo $sel['total_to_pay']; ?>" id="vr-total-cost" size="8" min="0" max="999999" step="any"/>&nbsp;<?php echo $curr_symb; ?>
				<a href="javascript:void(0);" onClick="toggleTotalCostDetails(this);" style="margin-left: 5px;">
					<i class="fa fa-chevron-down"></i>
				</a>
			<?php echo $vik->closeControl(); ?>

			<div style="border-top: 1px dashed #ccc;width: 70%;display: none;" class="vr-cost-detailed">&nbsp;</div>

			<!-- TAXES - Number -->
			<?php echo $vik->openControl(JText::_('VRMANAGETKRES21').":", 'vr-cost-detailed', 'style="display:none;"'); ?>
				<input type="number" name="taxes" value="<?php echo $sel['taxes']; ?>" id="vr-taxes-charge" size="8" min="-999999" max="999999" step="any"/>&nbsp;<?php echo $curr_symb; ?> 
			<?php echo $vik->closeControl(); ?>

			<!-- DELIVERY CHARGE - Number -->
			<?php echo $vik->openControl(JText::_('VRMANAGETKRES31').":", 'vr-cost-detailed', 'style="display:none;"'); ?>
				<input type="number" name="delivery_charge" value="<?php echo $sel['delivery_charge']; ?>" id="vr-delivery-charge" size="8" min="-999999" max="999999" step="any"/>&nbsp;<?php echo $curr_symb; ?> 
			<?php echo $vik->closeControl(); ?>

			<!-- PAYMENT CHARGE - Number -->
			<?php echo $vik->openControl(JText::_('VRMANAGETKRES30').":", 'vr-cost-detailed', 'style="display:none;"'); ?>
				<input type="number" name="pay_charge" value="<?php echo $sel['pay_charge']; ?>" id="vr-pay-charge" size="8" min="-999999" max="999999" step="any"/>&nbsp;<?php echo $curr_symb; ?> 
			<?php echo $vik->closeControl(); ?>

			<div style="border-top: 1px dashed #ccc;width: 70%;display: none;" class="vr-cost-detailed">&nbsp;</div>
			
			<!-- STATUS - Dropdown -->
			<?php echo $vik->openControl(JText::_('VRMANAGETKRES9')."*:"); ?>
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
			<?php echo $vik->openControl(JText::_('VRMANAGETKRES27').":"); ?>
				<?php echo $vik->dropdown('id_payment', $elements, 'vr-payment-sel'); ?>
			<?php echo $vik->closeControl(); ?>
			
			<!-- NOTIFY CUSTOMER - Radio Button -->
			<?php
			$elem_yes = $vik->initRadioElement(1, '', false);
			$elem_no = $vik->initRadioElement(0, '', true);
			?>
			<?php echo $vik->openControl(JText::_('VRMANAGETKRES12').":"); ?>
				<?php echo $vik->radioYesNo('notify_customer', $elem_yes, $elem_no, false); ?>    
			<?php echo $vik->closeControl(); ?>
		
		<?php echo $vik->closeFieldset(); ?>
	</div>
	
	<?php if( count($cfields) > 0 ) { ?>
		<div class="span5">
			<?php echo $vik->openFieldset(JText::_('VRMANAGETKRESTITLE3'), 'form-horizontal'); ?>
				
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

							<td>
								<?php
								$attributes = array();
								$attributes['class'] = 'vr-custom-field ' . $field_class;
								$attributes['data-cfname'] = $cf['name'];

								echo $vik->calendar($_val, 'vrcf'.$cf['id'], 'vrcf'.$cf['id'].'date', null, $attributes);
								?>
							</td>
							
							<script>
								document.getElementById("<?php echo 'vrcf'.$cf['id']; ?>date").value = "<?php echo $_val; ?>";
							</script>

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
	
	<div class="span10">
		<?php echo $vik->openFieldset(JText::_('VRMANAGETKRESTITLE4'), 'form-horizontal'); ?>
		   <div class="control-group"><?php echo $editor->display( "notes", $sel['notes'], 400, 200, 70, 20 ); ?></div>
		<?php echo $vik->closeFieldset(); ?>
	</div>
	
	<input type="hidden" name="id" value="<?php echo $id; ?>"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="option" value="com_cleverdine"/>
</form>

<!-- JQUERY MODALS -->

<div class="modal hide fade" id="jmodal-busytime" style="width:90%;height:80%;margin-left:-45%;">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h3><?php echo JText::_('VRTKRESBUSYMODALTITLE'); ?></h3>
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

	jQuery(document).ready(function(){
		jQuery('#vrdatefilter').val('<?php echo $sel['date']; ?>');
		
		jQuery(".vik-dropdown").select2({
			minimumResultsForSearch: -1,
			allowClear: false,
			width: 200
		});

		jQuery('.vr-phones-select').select2({
			minimumResultsForSearch: -1,
			allowClear: false,
			width: 90
		});

		jQuery('#vr-payment-sel').select2({
			allowClear: true,
			placeholder: '<?php echo addslashes(JText::_('VRMANAGECONFIG32')); ?>',
			width: 200
		});

		jQuery('#vr-origin-select').select2({
			placeholder: '--',
			allowClear: true,
			width: 300
		});

		jQuery('#vr-service-select').on('change', function(){
			if( jQuery(this).val() == 1 ) {
				jQuery('#vr-user-address').prop('disabled', false);
				jQuery('.vr-delivery-field').prop('readonly', false);

				// re calculate the delivery charge
				jQuery('#vr-user-address').trigger('change');

			} else {
				jQuery('#vr-user-address').prop('disabled', true);
				jQuery('.vr-delivery-field').prop('readonly', true);

				/*
				var net = getTotalNetPrice();
				jQuery('#vr-delivery-charge').val(0);
				updateTotalCost(net);
				*/
				var base_charge = <?php echo cleverdine::getTakeAwayPickupAddPrice(true); ?>;
				var percentot = <?php echo cleverdine::getTakeAwayPickupPercentOrTotal(true); ?>;

				var net = getTotalNetPrice();

				if (percentot == 1) {
					base_charge = net*base_charge/100;
				}

				jQuery('#vr-delivery-charge').val(base_charge);
				updateTotalCost(net);

				// reset route
				calculateRoute('', '');
			}
		});

		// enable/disable delivery fields
		jQuery('#vr-service-select').trigger('change');
		
		jQuery('.vr-phones-select').on('change', function(){
			jQuery('.vr-phones-select').select2('val', jQuery(this).val());
		});

		jQuery('#vr-user-address').select2({
			placeholder: '<?php echo addslashes(JText::_('VRMANAGECONFIG32')); ?>',
			allowClear: true,
			width: 300
		});

		jQuery('#vr-user-address').on('change', function(){
			var val = jQuery(this).val();
			if( val.length ) {
				jQuery('.vr-delivery-field').val('');
			}
			jQuery('.vr-address-field').val(val);
		});

		jQuery('.vr-users-select').on('change', function(){
			var _html = '<option value=""></option>';

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

				// try always to recalculate delivery price
				evaluateCoordinatesFromAddress(getAddressString());
			}

			// push addresses
			if( !jQuery.isEmptyObject(DELIVERY_ADDRESS_LIST[val]) ) {
				for( var i = 0; i < DELIVERY_ADDRESS_LIST[val].length; i++ ) {
					_html += '<option value="'+DELIVERY_ADDRESS_LIST[val][i]+'">'+DELIVERY_ADDRESS_LIST[val][i]+'</option>';
				}
			}

			jQuery('#vr-user-address').html(_html);
			jQuery('#vr-user-address').select2('val', '');
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
							
							if( jQuery.isEmptyObject(DELIVERY_ADDRESS_LIST[item.id]) ) {
								DELIVERY_ADDRESS_LIST[item.id] = item.delivery;
							}

							if( jQuery.isEmptyObject(BILLING_USER_LIST[item.id]) ) {
								BILLING_USER_LIST[item.id] = {
									name: item.billing_name,
									mail: item.billing_mail,
									phone: item.billing_phone,
									country: item.country_code,
									fields: item.tkfields
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
			dropdownCssClass: "bigdrop",
		});
	});
	
	function vrUpdateWorkingShifts() {
		jQuery('#vrhour').prop('disabled', true);
		
		jQuery.noConflict();
		
		var jqxhr = jQuery.ajax({
			type: "POST",
			url: "index.php",
			data: { 
				option: "com_cleverdine",
				task: "get_takeaway_working_shifts",
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
			
		}).fail(function(resp){
			jQuery('#vrhour').prop('disabled', false);
		});
	}
	
	function composeMailFields() {
		var email = jQuery('#vremail').val();
		if( email.length ) {
			jQuery('.vr-email-field').val(email);
		}
	}
	
	function composePhoneFields() {
		var phone = jQuery('#vrphone').val();
		if( phone.length > 0 ) {
			jQuery('.vr-phone-field').val(phone);
		}
	}

	var DELIVERY_ADDRESS_LIST = [];
	var BILLING_USER_LIST = [];

	// total cost handler

	var TOTAL_NET_COST = null;

	jQuery(document).ready(function(){

		getTotalNetPrice();

		// payment
		jQuery('#vr-payment-sel').on('change', function(){

			var tcost = getTotalNetPrice();

			var charge = parseFloat(jQuery(this).find(':selected').data('charge'));
			var percentot = parseInt(jQuery(this).find(':selected').data('percentot'));

			if( isNaN(charge) ) {
				charge = 0;
				percentot = 2;
			}

			var curr_pay_charge = charge;

			if( percentot == 1 ) {
				curr_pay_charge = tcost*charge/100.0;
			}

			// update pay charge
			jQuery('#vr-pay-charge').val(curr_pay_charge);

			updateTotalCost(tcost);

		});

		jQuery('#vr-user-address, .vr-address-field, .vr-zip-field').on('change', function(){
			evaluateCoordinatesFromAddress(getAddressString());
		});

		jQuery('#vr-pay-charge, #vr-taxes-charge, #vr-delivery-charge').on('change', function(){
			updateTotalCost(TOTAL_NET_COST);
		});

	});

	function getAddressString() {

		var parts = [];

		jQuery('.vr-address-field, .vr-zip-field').each(function() {

			var val = jQuery(this).val();

			if (val.length) {
				parts.push(val);
			}

		});

		return parts.join(' ');
	}

	function updateDeliveryCharge(ch) {
		var base_charge = <?php echo cleverdine::getTakeAwayDeliveryServiceAddPrice(true); ?>;
		var percentot = <?php echo cleverdine::getTakeAwayDeliveryServicePercentOrTotal(true); ?>;

		var net = getTotalNetPrice();

		if( percentot == 1 ) {
			base_charge = net*base_charge/100;
		}

		jQuery('#vr-delivery-charge').val((base_charge+ch));

		updateTotalCost(net);

	}

	function getTotalNetPrice() {
		TOTAL_NET_COST = parseFloat(jQuery('#vr-total-cost').val()) - parseFloat(jQuery('#vr-pay-charge').val()) - parseFloat(jQuery('#vr-taxes-charge').val()) - parseFloat(jQuery('#vr-delivery-charge').val());
		return TOTAL_NET_COST;
	}

	function updateTotalCost(net) {
		var grand_total = net+parseFloat(jQuery('#vr-pay-charge').val())+parseFloat(jQuery('#vr-taxes-charge').val())+parseFloat(jQuery('#vr-delivery-charge').val());
		jQuery('#vr-total-cost').val((Math.round(grand_total*100)/100).toFixed(2));
	}

	function toggleTotalCostDetails(link) {
		var i = jQuery(link).find('i');
		if( jQuery('#vr-taxes-charge').is(':visible') ) {
			jQuery('.vr-cost-detailed').hide();

			i.removeClass('fa-chevron-up');
			i.addClass('fa-chevron-down');

		} else {
			jQuery('.vr-cost-detailed').show();

			i.removeClass('fa-chevron-down');
			i.addClass('fa-chevron-up');
		}
	}

	// geocoder

	function evaluateCoordinatesFromAddress(address) {

		if (address.length == 0 || jQuery('#vr-service-select').val() == 0) {
			updateDeliveryCharge(0);
			return;
		}

		jQuery('#vr-user-address, .vr-address-field, .vr-zip-field').prop('disabled', true);

		var geocoder = new google.maps.Geocoder();

		var coord = null;

		geocoder.geocode({'address': address}, function(results, status) {
			if( status == "OK" ) {
				coord = {
					"lat": results[0].geometry.location.lat(),
					"lng": results[0].geometry.location.lng(),
				};

				var zip = '';
				jQuery.each(results[0].address_components, function(){
					if( this.types[0] == "postal_code") {
						zip = this.short_name;
					}
				});

				// calculate route on address change
				jQuery('#vr-origin-select').trigger('change');

				getLocationDeliveryInfo(coord, zip);
			} else {
				jQuery('#vr-user-address, .vr-address-field, .vr-zip-field').prop('disabled', false);
			}
		});
	}

	function getLocationDeliveryInfo(coord, zip, elem) {

		jQuery.noConflict();
	
		var jqxhr = jQuery.ajax({
			type: "POST",
			url: "index.php?option=com_cleverdine&task=get_location_delivery_info&tmpl=component",
			data: { lat: coord.lat, lng: coord.lng, zip: zip, json: 1 }
		}).done(function(resp){
			var obj = jQuery.parseJSON(resp);

			if( obj.status == 1 ) {	
				updateDeliveryCharge(obj.area.charge);
			} else {
				var r = confirm('<?php echo addslashes(JText::_('VRTKRESADDRESSNOTVALID')); ?>');

				if( !r ) {
					jQuery('.vr-address-field').val('');
					jQuery('#vr-user-address').select2('val', '');
					updateDeliveryCharge(0);
				} else {
					updateDeliveryCharge(<?php echo $this->maxDeliveryCharge; ?>);
				}
			}

			jQuery('#vr-user-address, .vr-address-field, .vr-zip-field').prop('disabled', false);

		}).fail(function(){

			updateDeliveryCharge(0);

			jQuery('.vr-address-field').val('');
			jQuery('#vr-user-address').select2('val', '');

			alert('<?php echo addslashes(JText::_('VRSYSTEMCONNECTIONERR')); ?>');
		});

	}

	// route

	var routeDirectionService = null;

	jQuery(document).ready(function(){

		routeDirectionService = new google.maps.DirectionsService;
		
		jQuery('#vr-origin-select').on('change', function(){

			var origin 		= jQuery(this).val();
			var destination = getRouteDestination();

			calculateRoute(origin, destination);

		});

	});

	function getRouteDestination() {
		var destination = jQuery('#vr-user-address').val();

		if( destination.length == 0 ) {
			jQuery('.vr-address-field').each(function(){
				if( jQuery(this).val().length ) {
					destination += jQuery(this).val().trim()+" ";
				}
			});
		}

		return destination.trim();
	}

	function calculateRoute(origin, destination) {

		if( origin.length == 0 || destination.length == 0 || jQuery('#vr-service-select').val() == "0" ) {
			fillRouteResponse(null);
			displayRouteResponse('', '');
			return;
		}

		var registered = getRegisteredRoute(origin, destination);
		if( registered !== null ) {
			// get from pool
			fillRouteResponse(registered);
			displayRouteResponse(registered.distance.text, registered.duration.text);
			return;
		}

		var route_prop = {
			origin: origin,
			destination: destination,
			travelMode: google.maps.TravelMode.DRIVING,
			drivingOptions: {
				departureTime: getDepartureTime(),
				trafficModel: google.maps.TrafficModel.BEST_GUESS
				//trafficModel: google.maps.TrafficModel.PESSIMISTIC
			},
			avoidHighways: true,
			avoidTolls: true
		};

		routeDirectionService.route(route_prop, function(response, status) {

			if( status === google.maps.DirectionsStatus.OK ) {
				registerRoute(origin, destination, response.routes[0].legs[0]);

				fillRouteResponse(response.routes[0].legs[0]);

				displayRouteResponse(response.routes[0].legs[0].distance.text, response.routes[0].legs[0].duration.text);
			} else {
				fillRouteResponse(null);
				displayRouteResponse('', '');

				window.alert('<?php echo addslashes(JText::_('VRTKROUTEDELIVERYERR')); ?>'.replace('%s', status));
			}

		});

	}

	function fillRouteResponse(leg) {

		jQuery('#vrorigindistance').val( (leg !== null ? leg.distance.value : '') );
		jQuery('#vroriginduration').val( (leg !== null ? leg.duration.value : '') );

		jQuery('#vrorigindistancetext').val( (leg !== null ? leg.distance.text : '') );
		jQuery('#vrorigindurationtext').val( (leg !== null ? leg.duration.text : '') );

	}

	function displayRouteResponse(distance, duration) {
		if( distance.length && duration.length ) {
			jQuery('#vrroutedetails').html('<i class="fa fa-road" style="margin-right:5px;"></i>'+distance+'<i class="fa fa-clock-o" style="margin-right:5px;margin-left:15px;"></i>'+duration);
			jQuery('.vrroutedetailswrap').show();
		} else {
			jQuery('#vrroutedetails').html('');
			jQuery('.vrroutedetailswrap').hide();
		}
	}

	var MAP_DB_ROUTE = {};

	function registerRoute(origin, destination, leg) {
		MAP_DB_ROUTE[(origin+destination).hashCode()] = leg;
	}

	function getRegisteredRoute(origin, destination) {
		var hash = (origin+destination).hashCode();
		if( MAP_DB_ROUTE.hasOwnProperty(hash) ) {
			return MAP_DB_ROUTE[hash];
		}

		return null;
	}

	function getDepartureTime() {
		
		var day = jQuery('#vrdatefilter').val();
		if( day.length == 0 ) {
			return new Date();
		}

		var df_separator = '<?php echo $date_format[1]; ?>';
		var formats = '<?php echo $date_format; ?>'.split(df_separator);
		var date_exp = day.split(df_separator);
		
		var _args = new Array();
		for( var i = 0; i < formats.length; i++ ) {
			_args[formats[i]] = parseInt( date_exp[i] );
		}

		var time = jQuery('#vrhour').val().split(':');
		
		return new Date( _args['Y'], _args['m']-1, _args['d'], time[0], time[1], 0 );
	
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
		return ok;
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
			var href = 'index.php?option=com_cleverdine&task=tkbusyres&tmpl=component&date='+jQuery('#vrdatefilter').val()+'&time='+jQuery('#vrhour').val();
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