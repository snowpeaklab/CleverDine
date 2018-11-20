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

// load calendar behavior
JHTML::_('behavior.calendar');

$sel = null;
$id = -1;
if( !count( $this->selectedCoupon ) ) {
	$sel = array(
		'code' => cleverdine::generateSerialCode(12), 'type' => 1, 'percentot' => 1, 'value' => 0.0, 'datevalid' => '', 'minvalue' => 1, 'group' => 0
	);
} else {
	$sel = $this->selectedCoupon;
	$id = $sel['id'];

	if( $sel['group'] == 0 ) {
		$sel['minvalue'] = round($sel['minvalue']);
	}
}

$dates_exp = array("", "");
if (strlen($sel["datevalid"]) > 0) {
	$dates_exp = explode("-", $sel["datevalid"]);
}

$date_format = cleverdine::getDateFormat(true);

for ($i = 0; $i < count($dates_exp); $i++) {
	if (strlen($dates_exp[$i]) > 0) {
		$dates_exp[$i] = date($date_format, $dates_exp[$i]);
	} else {
		$dates_exp[$i] = '';
	}
}

$curr_symb = cleverdine::getCurrencySymb(true);

$vik = new VikApplication(VersionListener::getID());

?>

<form name="adminForm" action="index.php" method="post" enctype="multipart/form-data" id="adminForm">
	
	<div class="span6">
		<?php echo $vik->openEmptyFieldset(); ?>

			<!-- GROUP - Number -->
			<?php echo $vik->openControl(JText::_('VRMANAGECOUPON10').":"); ?>
				<?php echo RestaurantsHelper::buildGroupDropdown('group', $sel['group'], 'vr-group-sel'); ?>
			<?php echo $vik->closeControl(); ?>
		
			<!-- CODE - Text -->
			<?php echo $vik->openControl(JText::_('VRMANAGECOUPON1')."*:"); ?>
				<input type="text" name="code" class="required" value="<?php echo $sel['code']; ?>" size="40"/>
			<?php echo $vik->closeControl(); ?>
			
			<!-- TYPE - Dropdown -->
			<?php
			$elements = array(
				$vik->initOptionElement(1, JText::_('VRCOUPONTYPEOPTION1'), $sel['type']==1),
				$vik->initOptionElement(2, JText::_('VRCOUPONTYPEOPTION2'), $sel['type']==2)
			);
			?>
			<?php echo $vik->openControl(JText::_('VRMANAGECOUPON2').":"); ?>
				<?php echo $vik->dropdown('type', $elements, '', 'medium'); ?>
			<?php echo $vik->closeControl(); ?>
			
			<!-- PERCENT OR TOTAL - Dropdown -->
			<?php
			$elements = array(
				$vik->initOptionElement(1, JText::_('VRCOUPONPERCENTOTOPTION1'), $sel['percentot']==1),
				$vik->initOptionElement(2, $curr_symb, $sel['percentot']==2)
			);
			?>
			<?php echo $vik->openControl(JText::_('VRMANAGECOUPON3').":"); ?>
				<?php echo $vik->dropdown('percentot', $elements, '', 'medium'); ?>
			<?php echo $vik->closeControl(); ?>
			
			<!-- VALUE - Number -->
			<?php echo $vik->openControl(JText::_('VRMANAGECOUPON4')."*:"); ?>
				<input type="number" name="value" class="required" value="<?php echo $sel["value"]; ?>" size="40" min="0" step="any"/>
			<?php echo $vik->closeControl(); ?>
			
			<!-- DATE START - Calendar -->
			<?php echo $vik->openControl(JText::_('VRMANAGECOUPON5').":"); ?>
				<?php echo $vik->calendar($dates_exp[0], 'dstart', 'dstart'); ?>
			<?php echo $vik->closeControl(); ?>
			
			<!-- DATE END - Calendar -->
			<?php echo $vik->openControl(JText::_('VRMANAGECOUPON6').":"); ?>
				<?php echo $vik->calendar($dates_exp[1], 'dend', 'dend'); ?>
			<?php echo $vik->closeControl(); ?>
			
			<!-- MIN PEOPLE/MIN ORDER - Number -->
			<?php echo $vik->openControl(JText::_('VRMANAGECOUPON'.($sel['group'] == 0 ? '8' : '9')).":", 'vr-minvalue-row'); ?>
				<input type="number" name="minvalue" value="<?php echo $sel["minvalue"]; ?>" size="40" min="<?php echo ($sel['group'] == 0 ? '1' : '0'); ?>" step="<?php echo ($sel['group'] == 0 ? '1' : 'any'); ?>"/>
			<?php echo $vik->closeControl(); ?>
			
		<?php echo $vik->closeEmptyFieldset(); ?>
	</div>
	
	<input type="hidden" name="id" value="<?php echo $id; ?>"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="option" value="com_cleverdine"/>
</form>

<script type="text/javascript">

	jQuery(document).ready(function(){
		
		jQuery('.vik-dropdown.medium').select2({
			minimumResultsForSearch: -1,
			allowClear: false,
			width: 150
		});

		jQuery('#vr-group-sel').on('change', function(){

			var input = jQuery('input[name="minvalue"]');

			if( jQuery(this).val() == "0" ) {
				input.prop('step', '1');
				input.prop('min', '1');

				input.val( Math.max(1, parseInt(input.val())) );

				jQuery('.vr-minvalue-row b').text('<?php echo addslashes(JText::_('VRMANAGECOUPON8')); ?>');
			} else {
				input.prop('step', 'any');
				input.prop('min', '0');

				jQuery('.vr-minvalue-row b').text('<?php echo addslashes(JText::_('VRMANAGECOUPON9')); ?>');
			}

		});

		jQuery("#adminForm .required").on("blur", function(){
			if( jQuery(this).val().length > 0 ) {
				jQuery(this).removeClass("vrrequired");
			} else {
				jQuery(this).addClass("vrrequired");
			}
		});

	});

	// validation

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