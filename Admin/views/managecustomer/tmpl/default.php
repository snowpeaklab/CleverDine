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

$sel = null;
$id = -1;
if( !count( $this->selectedCustomer ) ) {
	$sel = array(
		'jid' => '', 'billing_name' => '', 'billing_mail' => '', 'billing_phone' => '', 'country_code' => '', 'billing_state' => '', 'billing_city' => '', 
		'billing_address' => '', 'billing_address_2' => '', 'billing_zip' => '', 'company' => '', 'vatnum' => '', 'ssn' => '', 'fields' => '', 'tkfields' => '', 'notes' => '',
		'user_pwd1' => '', 'user_pwd2' => '', 'image' => ''
	);
} else {
	$sel = $this->selectedCustomer;
	$id = $sel['id'];
}

$sel['fields'] 		= (strlen($sel['fields']) ? json_decode($sel['fields'], true) : array());
$sel['tkfields'] 	= (strlen($sel['tkfields']) ? json_decode($sel['tkfields'], true) : array());

if( empty($sel['country_code']) ) {
	for( $i = 0; $i < count($this->customFields); $i++ ) {
		foreach( $this->customFields[$i] as $cf ) {
			if( $cf['type'] == 'text' && $cf['rule'] == VRCustomFields::PHONE_NUMBER ) {
				$sel['country_code'] = $cf['choose'];
				break;
			}
		}
	}
}

if( empty($sel['jid']) || $sel['jid'] == -1 ) {
	$sel['jid'] = '';
}

$vik = new VikApplication(VersionListener::getID());

$config = UIFactory::getConfig();

$active_tab = 'customer_billing';
if( $id > 0 ) {
	$session = JFactory::getSession();
	$active_tab = $session->get('customer_active_tab', $active_tab, 'vre');
}

$nowdf = $config->get('dateformat');
$nowdf = str_replace('d', '%d', $nowdf);
$nowdf = str_replace('m', '%m', $nowdf);
$nowdf = str_replace('Y', '%Y', $nowdf);

?>

<form name="adminForm" action="index.php" method="post" enctype="multipart/form-data" id="adminForm">

	<?php if( $this->isTmpl == 'component' ) { ?>

		<div class="btn-toolbar vr-btn-toolbar" id="filter-bar" style="margin-top: 0;">
			<div class="btn-group pull-left">
				<button type="button" class="btn btn-success" onclick="vrValidateFieldsAndDisableButton(this);">
					<i class="icon-apply"></i>&nbsp;<?php echo JText::_('VRSAVE'); ?>
				</button>
			</div>
		</div>

	<?php } ?>

	<?php echo $vik->bootStartTabSet('customer', array('active' => $active_tab)); ?>

		<!-- BILLING -->
			
		<?php echo $vik->bootAddTab('customer', 'customer_billing', JText::_('VRCUSTOMERTABTITLE1')); ?>

			<div></div>
	
			<div class="span11">
				<?php echo $vik->openFieldset(JText::_('VRMANAGECUSTOMERTITLE1'), 'form-horizontal'); ?>
					
					<!-- JOOMLA USER - Dropdown -->
					<?php echo $vik->openControl(JText::_('VRMANAGECUSTOMER12').":"); ?>
						<input type="hidden" name="jid" class="vr-users-select" value="<?php echo $sel['jid']; ?>"/>
						<button type="button" class="btn" onClick="userSelectValueChanged(this);"><?php echo JText::_('VRMANAGECUSTOMER16'); ?></button>
						<input type="hidden" name="create_new_user" value="0" />

						<?php if( empty($sel['image']) ) { ?>
							<img src="<?php echo JUri::root().'components/com_cleverdine/assets/css/images/default-profile.png'; ?>" class="vr-customer-image"/>
						<?php } else { ?>
							<a href="<?php echo JUri::root().'components/com_cleverdine/assets/customers/'.$sel['image']; ?>" class="modal">
								<img src="<?php echo JUri::root().'components/com_cleverdine/assets/customers/'.$sel['image']; ?>" class="vr-customer-image"/>
							</a>
						<?php } ?>
					<?php echo $vik->closeControl(); ?>
					
					<!-- JOOMLA USER NAME - Text -->
					<?php echo $vik->openControl(JText::_('VRMANAGECUSTOMER2')."*:", 'vr-account-row', 'style="display: none;"'); ?>
						<input class="" type="text" name="username" value="<?php echo $sel["billing_name"]; ?>" size="40"/>
					<?php echo $vik->closeControl(); ?>
					
					<!-- JOOMLA USER MAIL - Text -->
					<?php echo $vik->openControl(JText::_('VRMANAGECUSTOMER3')."*:", 'vr-account-row', 'style="display: none;"'); ?>
						<input class="mail-field" type="text" name="usermail" value="<?php echo $sel["billing_mail"]; ?>" size="40"/>
					<?php echo $vik->closeControl(); ?>
					
					<!-- JOOMLA USER GENERATE PWD - Button -->
					<?php echo $vik->openControl('', 'vr-account-row', 'style="display: none;"'); ?>
						<!--<input type="button" value="<?php echo JText::_('VRMANAGECUSTOMER17'); ?>" id="vr-genpwd-button"/>-->
						<button type="button" id="vr-genpwd-button" class="btn"><?php echo JText::_('VRMANAGECUSTOMER17'); ?></button>
					<?php echo $vik->closeControl(); ?>
					
					<!-- JOOMLA USER PWD - Password -->
					<?php echo $vik->openControl(JText::_('VRMANAGECUSTOMER13')."*:", 'vr-account-row', 'style="display: none;"'); ?>
						<input class="vr-genpwd-input" type="password" name="user_pwd1" size="40"/>
					<?php echo $vik->closeControl(); ?>
					
					<!-- JOOMLA USER CONFIRM PWD - Password -->
					<?php echo $vik->openControl(JText::_('VRMANAGECUSTOMER14')."*:", 'vr-account-row', 'style="display: none;"'); ?>
						<input class="vr-genpwd-input" type="password" name="user_pwd2" size="40"/>
					<?php echo $vik->closeControl(); ?>
					
				<?php echo $vik->closeFieldset(); ?>
			</div>
			
			<div class="span6">
				<?php echo $vik->openFieldset(JText::_('VRMANAGECUSTOMERTITLE2'), 'form-horizontal'); ?>
				
					<!-- BILLING NAME - Text -->
					<?php echo $vik->openControl(JText::_('VRMANAGECUSTOMER2')."*:"); ?>
						<input class="required" type="text" name="billing_name" value="<?php echo $sel["billing_name"]; ?>" size="40"/>
					<?php echo $vik->closeControl(); ?>
					
					<!-- BILLING MAIL - Text -->
					<?php echo $vik->openControl(JText::_('VRMANAGECUSTOMER3')."*:"); ?>
						<input class="required mail-field" type="text" name="billing_mail" value="<?php echo $sel["billing_mail"]; ?>" size="40"/>
					<?php echo $vik->closeControl(); ?>
					
					<!-- BILLING PHONE - Text -->
					<?php echo $vik->openControl(JText::_('VRMANAGECUSTOMER4').":"); ?>
						<input class="" type="text" name="billing_phone" value="<?php echo $sel["billing_phone"]; ?>" size="40"/>
					<?php echo $vik->closeControl(); ?>
					
					<!-- BILLING COUNTRY - Select -->
					<?php
					$elements = array(
						$vik->initOptionElement('', '', 0)
					);
					foreach( $this->countries as $country ) {
						array_push( $elements, $vik->initOptionElement($country['country_2_code'], $country['country_name'], $country['country_2_code'] == $sel['country_code']) );
					}
					?>
					<?php echo $vik->openControl(JText::_('VRMANAGECUSTOMER5').":"); ?>
						<?php echo $vik->dropdown('country_code', $elements, 'vr-countries-sel', 'vr-countries-sel', 'onChange="countriesSelectValueChanged();"'); ?>
					<?php echo $vik->closeControl(); ?>
					
					<!-- BILLING STATE - Text -->
					<?php echo $vik->openControl(JText::_('VRMANAGECUSTOMER6').":"); ?>
						<input type="text" name="billing_state" value="<?php echo $sel["billing_state"]; ?>" size="40"/>
					<?php echo $vik->closeControl(); ?>
					
					<!-- BILLING CITY - Text -->
					<?php echo $vik->openControl(JText::_('VRMANAGECUSTOMER7').":"); ?>
						<input type="text" name="billing_city" value="<?php echo $sel["billing_city"]; ?>" size="40"/>
					<?php echo $vik->closeControl(); ?>
					
					<!-- BILLING ADDRESS - Text -->
					<?php echo $vik->openControl(JText::_('VRMANAGECUSTOMER8').":"); ?>
						<input type="text" name="billing_address" value="<?php echo $sel["billing_address"]; ?>" size="40"/>
					<?php echo $vik->closeControl(); ?>
					
					<!-- BILLING ADDRESS 2 - Text -->
					<?php echo $vik->openControl(JText::_('VRMANAGECUSTOMER19').":"); ?>
						<input type="text" name="billing_address_2" value="<?php echo $sel["billing_address_2"]; ?>" size="40"/>
					<?php echo $vik->closeControl(); ?>
					
					<!-- BILLING ZIP CODE - Text -->
					<?php echo $vik->openControl(JText::_('VRMANAGECUSTOMER9').":"); ?>
						<input type="text" name="billing_zip" value="<?php echo $sel["billing_zip"]; ?>" size="40"/>
					<?php echo $vik->closeControl(); ?>
					
					<!-- BILLING COMPANY - Text -->
					<?php echo $vik->openControl(JText::_('VRMANAGECUSTOMER10').":"); ?>
						<input type="text" name="company" value="<?php echo $sel["company"]; ?>" size="40"/>
					<?php echo $vik->closeControl(); ?>
					
					<!-- BILLING VAT NUMBER - Text -->
					<?php echo $vik->openControl(JText::_('VRMANAGECUSTOMER11').":"); ?>
						<input type="text" name="vatnum" value="<?php echo $sel["vatnum"]; ?>" size="40"/>
					<?php echo $vik->closeControl(); ?>
					
					<!-- BILLING SSN - Text -->
					<?php echo $vik->openControl(JText::_('VRMANAGECUSTOMER20').":"); ?>
						<input type="text" name="ssn" value="<?php echo $sel["ssn"]; ?>" size="40"/>
					<?php echo $vik->closeControl(); ?>
					
				<?php echo $vik->closeFieldset(); ?>
			</div>

			<div class="span5">
				<?php echo $vik->openFieldset(JText::_('VRMANAGECUSTOMERTITLE4'), 'form-horizontal'); ?>
					<div class="control-group"><textarea style="width: 90%;height: 400px;resize:vertical;" name="notes"><?php echo $sel['notes']; ?></textarea></div>
				<?php echo $vik->closeFieldset(); ?>
			</div>

		<?php echo $vik->bootEndTab(); ?>

		<!-- DELIVERY -->

		<?php echo $vik->bootAddTab('customer', 'customer_delivery', JText::_('VRCUSTOMERTABTITLE2')); ?>

			<?php if( count($this->deliveryLocations) > 1 ) { ?>
				<div class="vr-delivery-locations-head">
					<p><?php echo JText::_('VRCUSTOMERDELIVERYHEAD'); ?></p>
				</div>
			<?php } ?>

			<div class="vr-delivery-locations-container">

				<div></div>

				<?php for( $i = 0; $i <= count($this->deliveryLocations); $i++ ) { ?>

					<div class="span5 delivery-fieldset" id="delivery-fieldset-<?php echo $i; ?>" data-index="<?php echo $i; ?>">
						<?php echo $vik->openFieldset(JText::_('VRMANAGECUSTOMERTITLE5'), 'form-horizontal'); ?>

							<?php
							if( $i < count($this->deliveryLocations) ) {
								$loc = $this->deliveryLocations[$i];
							} else {
								$loc = array( 'country' => $sel['country_code'], 'state' => '', 'city' => '', 'address' => '', 'address_2' => '', 'zip' => '', 'id' => -1 );
							}
							?>

							<!-- DELIVERY COUNTRY - Select -->
							<?php
							$elements = array(
								$vik->initOptionElement('', '', 0)
							);
							foreach( $this->countries as $country ) {
								array_push( $elements, $vik->initOptionElement($country['country_2_code'], $country['country_name'], $country['country_2_code'] == $loc['country']) );
							}
							?>
							<?php echo $vik->openControl(JText::_('VRMANAGECUSTOMER5').":"); ?>
								<?php echo $vik->dropdown('delivery_country[]', $elements, '', 'vr-countries-sel'); ?>
							<?php echo $vik->closeControl(); ?>
							
							<!-- DELIVERY STATE - Text -->
							<?php echo $vik->openControl(JText::_('VRMANAGECUSTOMER6').":"); ?>
								<input type="text" name="delivery_state[]" value="<?php echo $loc['state']; ?>" class="field" size="40" data-index="<?php echo $i; ?>"/>
							<?php echo $vik->closeControl(); ?>
							
							<!-- DELIVERY CITY - Text -->
							<?php echo $vik->openControl(JText::_('VRMANAGECUSTOMER7').":"); ?>
								<input type="text" name="delivery_city[]" value="<?php echo $loc['city']; ?>" class="field" size="40" data-index="<?php echo $i; ?>"/>
							<?php echo $vik->closeControl(); ?>
							
							<!-- DELIVERY ADDRESS - Text -->
							<?php echo $vik->openControl(JText::_('VRMANAGECUSTOMER8')."*:"); ?>
								<input type="text" name="delivery_address[]" value="<?php echo $loc['address']; ?>" class="field required<?php echo ($loc['id'] == -1 ? '-case' : ''); ?>" data-index="<?php echo $i; ?>" size="40" />
							<?php echo $vik->closeControl(); ?>
							
							<!-- DELIVERY ADDRESS 2 - Text -->
							<?php echo $vik->openControl(JText::_('VRMANAGECUSTOMER19').":"); ?>
								<input type="text" name="delivery_address_2[]" value="<?php echo $loc['address_2']; ?>" class="field" size="40" data-index="<?php echo $i; ?>"/>
							<?php echo $vik->closeControl(); ?>
							
							<!-- DELIVERY ZIP CODE - Text -->
							<?php echo $vik->openControl(JText::_('VRMANAGECUSTOMER9')."*:"); ?>
								<input type="text" name="delivery_zip[]" value="<?php echo $loc['zip']; ?>" class="field required<?php echo ($loc['id'] == -1 ? '-case' : ''); ?>" data-index="<?php echo $i; ?>" size="40" />
							<?php echo $vik->closeControl(); ?>

							<?php if( $i < count($this->deliveryLocations) ) { ?>
								<?php echo $vik->openControl(''); ?>
									<button type="button" class="btn" onClick="removeDeliveryLocation(<?php echo $i; ?>);"><i class="icon-remove"></i><?php echo JText::_('VRDELETE'); ?></button>
								<?php echo $vik->closeControl(); ?>
							<?php } ?>

							<input type="hidden" name="delivery_id[]" value="<?php echo $loc['id']; ?>" id="deliveryid<?php echo $i; ?>"/>

						<?php echo $vik->closeFieldset(); ?>
					</div>

				<?php } ?>

			</div>

		<?php echo $vik->bootEndTab(); ?>

		<!-- CUSTOM FIELDS -->

		<?php echo $vik->bootAddTab('customer', 'customer_fields', JText::_('VRMANAGECUSTOMERTITLE3')); ?>

			<div></div>

			<?php for( $j = 0; $j < count($this->customFields); $j++ ) { ?>

				<div class="span5">
					<?php echo $vik->openFieldset(JText::_('VRCUSTOMFGROUPOPTION'.($j+1)), 'form-horizontal'); ?>
						
						<?php
						foreach( $this->customFields[$j] as $cf ) {
								
							if( !empty( $cf['poplink'] ) ) {
								$fname = "<a href=\"" . $cf['poplink'] . "\" id=\"vrcf" . $cf['id'] . "\" rel=\"{handler: 'iframe', size: {x: 750, y: 600}}\" target=\"_blank\" class=\"modal\">" . JText::_($cf['name']) . "</a>";
							} else {
								$fname = "<span id=\"vrcf" . $cf['id'] . "\">" . JText::_($cf['name']) . "</span>";
							}
							
							if( $cf['type'] != 'separator' ) {
								echo $vik->openControl($fname);
							}
								
							$_val = "";
							if( !empty($sel['fields'][$cf['name']]) ) {                      
								$_val = $sel['fields'][$cf['name']];
							} else if( !empty($sel['tkfields'][$cf['name']]) ) {
								$_val = $sel['tkfields'][$cf['name']];
							}

							$onkeypress = '';
							
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
										echo '<option value="'.$ctry['id']."_".$ctry['country_2_code'].'" title="'.trim($ctry['country_name']).'" '.($sel['country_code'] == $ctry['country_2_code'] ? 'selected="selected"' : '').'>'.$ctry['phone_prefix'].$suffix.'</option>';
									}
									echo '</select>';

									$onkeypress = 'onkeypress="return event.charCode >= 48 && event.charCode <= 57"';
								}    
								?>
								<input type="text" name="vrcf<?php echo $cf['id']; ?>" value="<?php echo $_val; ?>" size="40" style="width: <?php echo $text_width; ?>px !important;" 
									class="<?php echo ($cf['rule'] == VRCustomFields::EMAIL ? 'mail-field' : ''); ?>" <?php echo $onkeypress; ?>/>
							
							<?php } else if( $cf['type'] == "textarea" ) { ?>
								<textarea name="vrcf<?php echo $cf['id']; ?>" rows="5" cols="30" class="vrtextarea"><?php echo $_val; ?></textarea>
							<?php } else if( $cf['type'] == "date" ) { ?>
								<?php echo $vik->calendar($_val, 'vrcf'.$cf['id'], 'vrcf'.$cf['id'].'date'); ?>
							<?php } else if( $cf['type'] == "select" ) {
								$answ = explode(";;__;;", $cf['choose']);
								$wcfsel = "<select name=\"vrcf" . $cf['id'] . "\" class=\"vik-dropdown-cf\">\n";
								foreach ($answ as $aw) {
									if (!empty ($aw)) {
										$wcfsel .= "<option value=\"" . $aw . "\" ".($aw == $_val ? 'selected="selected"' : '').">" . $aw . "</option>\n";
									}
								}
								$wcfsel .= "</select>\n";
								?>
								<?php echo $wcfsel; ?>
							<?php } else if( $cf['type'] == "separator" ) { ?>
								<div class="control-group"><strong><?php echo $fname; ?></strong></div>
							<?php } else { ?>
								<input type="checkbox" name="vrcf<?php echo $cf['id']; ?>" value="<?php echo JText::_('VRYES'); ?>" <?php echo ($_val == JText::_('VRYES') ? 'checked="checked"' : ''); ?>/>
							
							<?php } ?>
							
							<?php if( $cf['type'] != 'separator' ) {
								echo $vik->closeControl(); 
							} ?>
							
						<?php } ?>
						
					<?php echo $vik->closeFieldset(); ?>
				</div> 

			<?php } ?>

		<?php echo $vik->bootEndTab(); ?>

	<?php echo $vik->bootEndTabSet(); ?>

	<input type="hidden" name="active_tab" value="<?php echo $active_tab; ?>"/>
	
	<input type="hidden" name="id" value="<?php echo $id; ?>"/>
	<?php if($this->isTmpl == 'component' ) { ?>
		<input type="hidden" name="task" value="<?php echo ($this->isTmpl == 'component' ? 'saveCustomer' : ''); ?>"/>
		<input type="hidden" name="tmpl" value="component"/>
	<?php } else { ?>
		<input type="hidden" name="task" value=""/>
	<?php } ?>
	<input type="hidden" name="option" value="com_cleverdine"/>
</form>

<script>

	jQuery(document).ready(function(){

		jQuery('input[name="username"]').on('change', function(){
			if( jQuery('input[name="billing_name"]').val().length == 0 ) {
				jQuery('input[name="billing_name"]').val(jQuery(this).val());
			}
		});

		jQuery('input[name="usermail"]').on('change', function(){
			if( jQuery('input[name="billing_mail"]').val().length == 0 ) {
				jQuery('input[name="billing_mail"]').val(jQuery(this).val());
			}
		});

		jQuery('#vr-genpwd-button').on('click', function(){
			var pwd = generatePassword(8);

			jQuery('.vr-genpwd-input').attr('type', 'text');
			jQuery('.vr-genpwd-input').val(pwd);
		});
		
		jQuery('.vr-countries-sel').select2({
			placeholder: '--',
			allowClear: true,
			width: 300
		});

		jQuery('.vr-phones-select').select2({
			minimumResultsForSearch: -1,
			allowClear: false,
			width: 90
		});

		jQuery(".vr-users-select").select2({
			placeholder: '<?php echo addslashes(JText::_('VRMANAGECUSTOMER15')); ?>',
			allowClear: true,
			width: 300,
			minimumInputLength: 2,
			ajax: {
				url: 'index.php?option=com_cleverdine&task=search_jusers&tmpl=component&id=<?php echo $sel['jid']; ?>',
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
							return {
								text: item.name,
								id: item.id,
								disabled: (item.disabled == 1 ? true : false)
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
					callback({name: '<?php echo (empty($this->juser['name']) ? '' : addslashes($this->juser['name'])); ?>'});
				}
			},
			formatSelection: function(data) {
				if( jQuery.isEmptyObject(data.name) ) {
					// display data retured from ajax parsing
					return data.text;
				}
				// display pre-selected value
				return data.name;
			},
			dropdownCssClass: "bigdrop",
		});

		jQuery('.vik-dropdown-cf').select2({
			minimumResultsForSearch: -1,
			allowClear: false,
			width: 300
		});

		registerRequiredEvent();

	});
	
	function countriesSelectValueChanged() {
		var index = jQuery('#vr-countries-sel option:selected').index();
		jQuery('.vr-phones-select').prop('selectedIndex', index-1);
	}
	
	function userSelectValueChanged(btn) {

		if( jQuery(btn).hasClass('active') ) {
			jQuery(btn).removeClass('active');

			jQuery('.vr-users-select').prop('disabled', false);

			jQuery('.vr-account-row').hide();
			jQuery('.vr-account-row input').removeClass('required');

			jQuery('input[name="create_new_user"]').val(0);

		} else {
			jQuery(btn).addClass('active');

			jQuery('.vr-users-select').prop('disabled', true);

			jQuery('.vr-account-row input').addClass('required');
			jQuery('.vr-account-row').show();

			jQuery('input[name="create_new_user"]').val(1);

		}

		registerRequiredEvent();
	}
	
	function generatePassword(length) {
		var charset = "abcdefghijklnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789[]{}()#!.-_",
			pwd = "";
		for( var i = 0; i < length; i++ ) {
			pwd += charset.charAt(Math.floor(Math.random() * charset.length));
		}
		return pwd;
	}

	// delivery handler

	var NEW_DELIVERY_FIELDSET = null;

	jQuery(document).ready(function(){

		NEW_DELIVERY_FIELDSET = jQuery('.delivery-fieldset:last-child');

		NEW_DELIVERY_FIELDSET.find('.field').on('focus', function(){
			if( !isDeliveryFieldFilled(jQuery(this).data('index')) ) {
				NEW_DELIVERY_FIELDSET.find('.required-case').removeClass('vrrequired');
			}
		});

		NEW_DELIVERY_FIELDSET.find('.required-case').on('blur', function(){
			if( isDeliveryFieldFilled(jQuery(this).data('index')) ) {
				if( jQuery(this).val().length > 0 ) {
					jQuery(this).removeClass('vrrequired');
				} else {
					jQuery(this).addClass('vrrequired');
				}
			} else {
				NEW_DELIVERY_FIELDSET.find('.required-case').removeClass('vrrequired');
			}
		})

		jQuery('.vr-delivery-locations-container').sortable({
			revert: true
		});

	});

	function isDeliveryFieldFilled(id) {
		var ok = false;

		jQuery('#delivery-fieldset-'+id).each(function(){
			jQuery(this).find('.field').each(function(){
				if( jQuery(this).val().length > 0 ) {
					ok = true;
					return false;
				}
			});
		});

		return ok;
	}

	function validateNewDeliveryFields() {

		var ok = true;
			
		if( isDeliveryFieldFilled(NEW_DELIVERY_FIELDSET.data('index')) ) {
			NEW_DELIVERY_FIELDSET.find('.required-case').each(function(){
				if( jQuery(this).val().length > 0 ) {
					jQuery(this).removeClass('vrrequired');
				} else {
					jQuery(this).addClass('vrrequired');
					ok = false;
				}
			});
		} else {
			NEW_DELIVERY_FIELDSET.find('.required-case').removeClass('vrrequired');
		}

		return ok;
	}

	function removeDeliveryLocation(index) {
		var r = confirm('<?php echo addslashes(JText::_('VRSYSTEMCONFIRMATIONMSG')); ?>');

		if( r ) {

			var id = parseInt(jQuery('#deliveryid'+index).val());

			if( id > -1 ) {
				jQuery('#adminForm').append('<input type="hidden" name="delete_delivery[]" value="'+id+'" />');
			}
			jQuery('#delivery-fieldset-'+index).remove();
		}
	}

	// tab handler

	jQuery(document).ready(function(){

		jQuery('a[href="#customer_billing"],a[href="#customer_delivery"],a[href="#customer_fields"]').on('click', function(){
			var tab = jQuery(this).attr('href').replace(/#/g, '');
			jQuery('input[name="active_tab"]').val(tab);
		});

	});

	// validate

	function validateMailField(email) {
		var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		return re.test(email);
	}

	function registerRequiredEvent() {
		jQuery("#adminForm .required").off("blur");

		jQuery("#adminForm .required").on("blur", function(){
			if( jQuery(this).val().length > 0 ) {
				jQuery(this).removeClass("vrrequired");
			} else {
				jQuery(this).addClass("vrrequired");
			}
		});
	}

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

		// validate e-mail fields
		jQuery('.mail-field').each(function(){
			var mail = jQuery(this).val();
			if( mail.length && !validateMailField(mail) ) {
				jQuery(this).addClass("vrrequired");
				ok = false;
			} else if( !jQuery(this).hasClass('vrrequired') ) {
				jQuery(this).removeClass("vrrequired");
			}
		});

		// passwords must match
		var pwd = null;

		jQuery('.vr-genpwd-input.required').each(function(){
			if( pwd === null ) {
				pwd = jQuery(this).val();
			} else if ( pwd != jQuery(this).val() || !pwd.length ){
				jQuery(this).addClass('vrrequired');
				ok = false;
			} else if( !jQuery(this).hasClass('vrrequired') ) {
				jQuery(this).removeClass('vrrequired');
			}
		});

		return validateNewDeliveryFields() && ok;
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

	<?php if( $this->isTmpl ) { ?>

		function vrValidateFieldsAndDisableButton(button) {

			if( vrValidateFields() ) {
				jQuery(button).attr('disabled', true);

				document.adminForm.submit();
			}

		}

	<?php } ?>
	
</script>
