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

$vik = new VikApplication(VersionListener::getID());

$curr_symb 	= cleverdine::getCurrencySymb(true);
$symb_pos 	= cleverdine::getCurrencySymbPosition(true);

$coupon_code = (strlen($this->order['coupon_str']) ? substr($this->order['coupon_str'], 0, strpos($this->order['coupon_str'], ';;')) : '');

?>

<form action="index.php?option=com_cleverdine" method="post" name="adminForm" id="adminForm">

	<div class="span6">
		<?php echo $vik->openEmptyFieldset(); ?>

			<!-- GRAND TOTAL - Label -->
			<?php echo $vik->openControl(JText::_('VRMANAGETKORDDISC1').':'); ?>
				<div class="control-html-value"><?php echo cleverdine::printPriceCurrencySymb($this->order['total_to_pay'], $curr_symb, $symb_pos, true); ?></div>
			<?php echo $vik->closeControl(); ?>

			<!-- TOTAL NET - Label -->
			<?php echo $vik->openControl(JText::_('VRMANAGETKORDDISC2').':'); ?>
				<div class="control-html-value"><?php echo cleverdine::printPriceCurrencySymb($this->order['total_net'], $curr_symb, $symb_pos, true); ?></div>
			<?php echo $vik->closeControl(); ?>

			<!-- COUPON CODE - Label -->
			<?php echo $vik->openControl(JText::_('VRMANAGETKORDDISC3').':'); ?>
				<div class="control-html-value">
					<?php echo (strlen($coupon_code) ? $coupon_code : '--'); ?>
				</div>
			<?php echo $vik->closeControl(); ?>

			<!-- METHOD - Dropdown -->
			<?php
			$elements = array(
				$vik->initOptionElement('', '', true)
			);

			if( empty($this->order['coupon_str']) ) {
				$elements[] = $vik->initOptionElement(1, JText::_('VRORDDISCMETHOD1'), false, false, (!count($this->coupons)));
			} else {
				$elements[] = $vik->initOptionElement(2, JText::_('VRORDDISCMETHOD2'), false, false, (!count($this->coupons)));
				$elements[] = $vik->initOptionElement(3, JText::_('VRORDDISCMETHOD3'));
			}

			if( $this->order['discount_val'] == 0 ) {
				$elements[] = $vik->initOptionElement(4, JText::_('VRORDDISCMETHOD4'));
			} else {
				$elements[] = $vik->initOptionElement(5, JText::_('VRORDDISCMETHOD5'));
				$elements[] = $vik->initOptionElement(6, JText::_('VRORDDISCMETHOD6'));
			}
			?>
			<?php echo $vik->openControl(JText::_('VRMANAGETKORDDISC4').'*:'); ?>
				<?php echo $vik->dropdown('method', $elements, 'vr-method-sel', 'required'); ?>
			<?php echo $vik->closeControl(); ?>

			<!-- COUPON CODE - Dropdown -->
			<?php
			$elements = array(
				$vik->initOptionElement('', '', true)
			);
			foreach( $this->coupons as $coupon ) {
				$coupon_label = $coupon['code']." : ".($coupon['percentot'] == 1 ? $coupon['value'].'%' : cleverdine::printPriceCurrencySymb($coupon['value'], $curr_symb, $symb_pos, true));
				$elements[] = $vik->initOptionElement($coupon['id'], $coupon_label, false, false, ($coupon['code'] == $coupon_code), 'data-value="'.$coupon['value'].'" data-percentot="'.$coupon['percentot'].'"');
			}
			?>
			<?php echo $vik->openControl(JText::_('VRMANAGETKORDDISC3').'*:', 'vr-coupon-field', 'style="display:none;"'); ?>
				<?php echo $vik->dropdown('id_coupon', $elements, 'vr-coupon-sel', ''); ?>
			<?php echo $vik->closeControl(); ?>

			<!-- AMOUNT - Number -->
			<?php
			$elements = array(
				$vik->initOptionElement(1, '%', false),
				$vik->initOptionElement(2, $curr_symb, true),
			);
			?>
			<?php echo $vik->openControl(JText::_('VRMANAGETKORDDISC5').'*:'); ?>
				<input type="number" name="amount" value="0" step="any" class="required" id="vr-amount-input"/>
				<?php echo $vik->dropdown('percentot', $elements, 'vr-percentot-sel'); ?>
			<?php echo $vik->closeControl(); ?>

		<?php echo $vik->closeEmptyFieldset(); ?>
	</div>

	<input type="hidden" name="id" value="<?php echo $this->id; ?>"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="option" value="com_cleverdine"/>
	
</form>

<script>

	jQuery(document).ready(function(){

		jQuery('#vr-method-sel').select2({
			minimumResultsForSearch: -1,
			placeholder: '<?php echo addslashes(JText::_('VRORDDISCMETHOD0')); ?>',
			allowClear: true,
			width: 300
		});

		jQuery('#vr-coupon-sel').select2({
			placeholder: '--',
			allowClear: true,
			width: 300
		});

		jQuery('#vr-percentot-sel').select2({
			minimumResultsForSearch: -1,
			allowClear: false,
			width: 130
		});

		jQuery('#vr-method-sel').on('change', function(){
			var val = jQuery(this).val();

			if( val == 1 || val == 2 ) {
				jQuery('.vr-coupon-field').show();
				jQuery('#vr-coupon-sel').addClass('required');
			} else {
				jQuery('.vr-coupon-field').hide();
				jQuery('#vr-coupon-sel').removeClass('required');
			}

			if( val == 3 || val == 6 ) {
				jQuery('#vr-amount-input').val(0).prop('disabled', true);
				jQuery('#vr-percentot-sel').select2('val', 2).prop('disabled', true);
			} else {
				jQuery('#vr-amount-input').prop('disabled', false);
				jQuery('#vr-percentot-sel').prop('disabled', false);
			}
		});

		jQuery('#vr-coupon-sel').on('change', function(){
			if( jQuery(this).val().length ) {
				
				var option = jQuery(this).find('option:selected');

				jQuery('#vr-amount-input').val(jQuery(option).data('value'));
				jQuery('#vr-percentot-sel').select2('val', jQuery(option).data('percentot'));
			}
		});

	});

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

</script>
