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
if( !count( $this->selectedCustomf ) ) {
	$sel = array( 
			'name' => '', 'type' => 'text', 'required' => 0, 'required_delivery' => 0, 'rule' => 0, 'poplink' => '', 'group' => 0, 'choose' => ''
	);
} else {
	$sel = $this->selectedCustomf;
	$id = $sel['id'];
}

$options_list = array();
if( $sel['type'] == "select" ) {

	if( strlen($sel['choose']) ) {
		$options_list = explode(";;__;;", $sel['choose']);
	}

}

$vik = new VikApplication(VersionListener::getID());

?>

<input type="hidden" value="0" id="theValue" />
		
<form name="adminForm" action="index.php" method="post" id="adminForm">
	
	<div class="span10">
		<?php echo $vik->openEmptyFieldset(); ?>

			<!-- GROUP - Dropdown -->
			<?php
			$elements = array(
				$vik->initOptionElement(0, JText::_('VRCUSTOMFGROUPOPTION1'), $sel['group']==0),
				$vik->initOptionElement(1, JText::_('VRCUSTOMFGROUPOPTION2'), $sel['group']==1)
			);
			?>
			<?php echo $vik->openControl(JText::_('VRMANAGECUSTOMF7').":"); ?>
				<?php echo RestaurantsHelper::buildGroupDropdown('group', $sel['group'], 'vr-group-sel'); ?>
			<?php echo $vik->closeControl(); ?>
			
			<!-- NAME - Text -->
			<?php echo $vik->openControl(JText::_('VRMANAGECUSTOMF1').'*:'); ?>
				<input type="text" name="name" class="required" value="<?php echo $sel['name']; ?>" size="30"/>
			<?php echo $vik->closeControl(); ?>
			
			<!-- TYPE - Dropdown -->
			<?php 
			$elements = array(
				$vik->initOptionElement('text', JText::_('VRCUSTOMFTYPEOPTION1'), $sel['type']=='text'),
				$vik->initOptionElement('textarea', JText::_('VRCUSTOMFTYPEOPTION2'), $sel['type']=='textarea'),
				$vik->initOptionElement('date', JText::_('VRCUSTOMFTYPEOPTION3'), $sel['type']=='date'),
				$vik->initOptionElement('select', JText::_('VRCUSTOMFTYPEOPTION4'), $sel['type']=='select'),
				$vik->initOptionElement('checkbox', JText::_('VRCUSTOMFTYPEOPTION5'), $sel['type']=='checkbox'),
				$vik->initOptionElement('separator', JText::_('VRCUSTOMFTYPEOPTION6'), $sel['type']=='separator'),
			);
			?>
			<?php echo $vik->openControl(JText::_('VRMANAGECUSTOMF2').':'); ?>
				<?php echo $vik->dropdown('type', $elements, 'vr-type-sel'); ?>
				
				<div id="vr-customf-select-box" style="display: <?php echo ($sel['type'] == 'select' ? 'block' : 'none'); ?>;">
					<div id="vr-customf-select-choose">

						<?php foreach( $options_list as $i => $v ) {
							if( !empty($v) ) { ?>
								<div id="vrchoose<?php echo $i; ?>" class="vr-customf-select-choose">
									<span class="vrtk-entryvar-sortbox"></span>
									<input type="text" name="choose[]" value="<?php echo $v; ?>" size="40"/>
									<a href="javascript: void(0)" onclick="removeElement(<?php echo $i; ?>);">
										<i class="fa fa-times big"></i>
									</a>
								</div>
							<?php }
						} ?>

					</div>

					<div style="margin-top: 10px;">
						<button type="button" class="btn" onclick="addElement();">
							<?php echo JText::_('VRCUSTOMFSELECTADDANSWER'); ?>
						</button>
					</div>
				</div>
			<?php echo $vik->closeControl(); ?>
			
			<!-- REQUIRED - Radio Button -->
			<?php
			$elem_yes = $vik->initRadioElement('', JText::_('VRYES'), $sel['required']==1, 'onClick="requiredStatusChanged(1);"');
			$elem_no = $vik->initRadioElement('', JText::_('VRNO'), $sel['required']==0, 'onClick="requiredStatusChanged(0);"');
			?>
			<?php echo $vik->openControl(JText::_('VRMANAGECUSTOMF3').':'); ?>
				<?php echo $vik->radioYesNo('required', $elem_yes, $elem_no, false); ?>
			<?php echo $vik->closeControl(); ?>

			<!-- REQUIRED DELIVERY - Dropdown -->
			<?php
			$elements = array(
				$vik->initOptionElement(0, JText::_('VRCUSTFIELDREQOPT1'), $sel['required_delivery'] == 0),
				$vik->initOptionElement(1, JText::_('VRCUSTFIELDREQOPT2'), $sel['required_delivery'] == 1),
			);

			?>
			<?php echo $vik->openControl('', '', 'id="vr-reqdel-field" style="'.($sel['required'] && $sel['group'] == 1 ? '' : 'display:none;').'"'); ?>
				<?php echo $vik->dropdown('required_delivery', $elements, 'vr-reqdel-sel'); ?>
			<?php echo $vik->closeControl(); ?>
			
			<!-- RULE - Dropdown -->
			<?php
			$elements = array(
				$vik->initOptionElement('', '', $sel['rule'] == 0),
				$vik->initOptionElement(1, JText::_('VRCUSTFIELDRULE1'), $sel['rule'] == 1),
				$vik->initOptionElement(2, JText::_('VRCUSTFIELDRULE2'), $sel['rule'] == 2),
				$vik->initOptionElement(3, JText::_('VRCUSTFIELDRULE3'), $sel['rule'] == 3),
				$vik->initOptionElement(4, JText::_('VRCUSTFIELDRULE4'), $sel['rule'] == 4, false, $sel['group'] == 0, 'class="takeaway-rule"'),
				$vik->initOptionElement(5, JText::_('VRCUSTFIELDRULE5'), $sel['rule'] == 5, false, $sel['group'] == 0, 'class="takeaway-rule"'),
				$vik->initOptionElement(6, JText::_('VRCUSTFIELDRULE6'), $sel['rule'] == 6, false, $sel['group'] == 0, 'class="takeaway-rule"'),
			);
			?>
			<?php echo $vik->openControl(JText::_('VRMANAGECUSTOMF11').':'); ?>
				<?php echo $vik->dropdown('rule', $elements, 'vr-rule-sel'); ?>
			<?php echo $vik->closeControl(); ?>
			
			<!-- DEFAULT PREFIX - Text -->
			<?php echo $vik->openControl(JText::_('VRMANAGECUSTOMF10').'*:', '', 'id="vr-phone-dp" style="'.($sel['rule'] == VRCustomFields::PHONE_NUMBER ? '' : 'display: none;').'"'); ?>
				<input type="text" name="def_prfx" value="<?php echo $sel['choose']; ?>" class="<?php echo ($sel['rule'] == VRCustomFields::PHONE_NUMBER ? 'required' : ''); ?>" id="vr-phone-pfx"/>
			<?php echo $vik->closeControl(); ?>
			
			<!-- POPUP LINK - Text -->
			<?php echo $vik->openControl(JText::_('VRMANAGECUSTOMF5').':', '', 'id="vr-popup-field" style="'.($sel['type'] == 'checkbox' ? '' : 'display: none;').'"'); ?>
				<input type="text" name="poplink" value="<?php echo $sel['poplink']; ?>" size="64"/>
				&nbsp;<small>Ex. <i>index.php?option=com_content&view=article&id=#JoomlaArticleID#&tmpl=component</i></small>
			<?php echo $vik->closeControl(); ?>
					
		<?php echo $vik->closeEmptyFieldset(); ?>
	</div>
		
	<input type="hidden" name="id" value="<?php echo $id; ?>"/>
	<input type="hidden" name="task" value="">
	<input type="hidden" name="option" value="com_cleverdine">

</form>

<script>
	
	jQuery(document).ready(function(){

		jQuery('#vr-type-sel').select2({
			allowClear: false,
			width: 300
		});

		jQuery('#vr-rule-sel').select2({
			placeholder: '<?php echo addslashes(JText::_('VRCUSTFIELDRULE0')); ?>',
			allowClear: true,
			width: 300
		});

		jQuery('#vr-reqdel-sel').select2({
			minimumResultsForSearch: -1,
			allowClear: false,
			width: 300
		});

		jQuery('#vr-type-sel').on('change', function(){
			var val = jQuery(this).val();

			isSelectValueChanged(val == 'select');
			isCheckboxValueChanged(val == 'checkbox');
		});

		jQuery('#vr-rule-sel').on('change', function(){
			var val = jQuery(this).val();

			isPhoneValueChanged(val == 3);
		});

		jQuery('#vr-group-sel').on('change', function(){
			var val = parseInt(jQuery(this).val());

			if( val == 1 ) {
				// takeaway
				requiredStatusChanged(parseInt(jQuery('input[name="required"]:checked').val()));

				jQuery('#vr-rule-sel .takeaway-rule').prop('disabled', false);
			} else {
				// restaurant
				requiredStatusChanged(0);

				jQuery('#vr-rule-sel .takeaway-rule').prop('disabled', true);
				if( jQuery('#vr-rule-sel').find(':selected').prop('disabled') ) {
					jQuery('#vr-rule-sel').select2('val', '');
				}
			}
		});

		jQuery("#adminForm .required").on("blur", function(){
			if( jQuery(this).val().length > 0 ) {
				jQuery(this).removeClass("vrrequired");
			} else {
				jQuery(this).addClass("vrrequired");
			}
		});

		makeSortable();

	});

	function isPhoneValueChanged(is) {
		if( is ) {
			jQuery('#vr-phone-pfx').addClass('required');
			jQuery('#vr-phone-dp').show();
		} else {
			jQuery('#vr-phone-pfx').removeClass('required');
			jQuery('#vr-phone-dp').hide();
		}
	}

	function isSelectValueChanged(is) {
		if( is ) {
			jQuery('#vr-customf-select-box').show();
		} else {
			jQuery('#vr-customf-select-box').hide();
		}
	}

	function isCheckboxValueChanged(is) {
		if( is ) {
			jQuery('#vr-popup-field').show();
		} else {
			jQuery('#vr-popup-field').hide();
		}
	}

	function requiredStatusChanged(is) {
		if( is && parseInt(jQuery('#vr-group-sel').val()) == 1 ) {
			jQuery('#vr-reqdel-field').show();
		} else {
			jQuery('#vr-reqdel-field').hide();
		}
	}

	// select handler

	var CHOOSE_COUNT = <?php echo count($options_list); ?>;

	function addElement() {
		jQuery('#vr-customf-select-choose').append('<div id="vrchoose'+CHOOSE_COUNT+'" class="vr-customf-choose">\n'+
			'<span class="vrtk-entryvar-sortbox"></span>\n'+
			'<input type="text" name="choose[]" value="" size="40"/>\n'+
			'<a href="javascript: void(0)" onclick="removeElement('+CHOOSE_COUNT+');">\n'+
				'<i class="fa fa-times big"></i>\n'+
			'</a>\n'+
		'</div>\n');

		CHOOSE_COUNT++;

		makeSortable();
	}

	function removeElement(id) {
		jQuery('#vrchoose'+id).remove();
	}

	function makeSortable() {
		jQuery( "#vr-customf-select-choose" ).sortable({
			revert: true
		});
	}

	// validate

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