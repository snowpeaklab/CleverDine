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
if( !count( $this->selectedTable ) ) {
	$sel = array(
		'name' => '', 'min_capacity' => 2, 'max_capacity' => 4, 'multi_res' => 0, 'published' => 1, 'id_room' => 0
	);
} else {
	$sel = $this->selectedTable;
	$id = $sel['id'];
}

$vik = new VikApplication(VersionListener::getID());

?>

<form name="adminForm" action="index.php" method="post" enctype="multipart/form-data" id="adminForm">
	
	<div class="span6">
		<?php echo $vik->openEmptyFieldset(); ?>
			
			<!-- NAME - Text -->
			<?php echo $vik->openControl(JText::_('VRMANAGETABLE1').'*:'); ?>
				<input class="required" type="text" name="name" value="<?php echo $sel['name']; ?>" size="40"/>
			<?php echo $vik->closeControl(); ?>
			
			<!-- MIN CAPACITY - Number -->
			<?php echo $vik->openControl(JText::_('VRMANAGETABLE2').'*:'); ?>
				<input class="required"type="number" name="min_capacity" value="<?php echo $sel['min_capacity']; ?>" size="4" min="1" max="9999" step="1"/>
			<?php echo $vik->closeControl(); ?>
		
			<!-- MAX CAPACITY - Number -->
			<?php echo $vik->openControl(JText::_('VRMANAGETABLE3').'*:'); ?>
				<input class="required"type="number" name="max_capacity" value="<?php echo $sel['max_capacity']; ?>" size="4" min="1" max="9999" step="1"/>
			<?php echo $vik->closeControl(); ?>
			
			<!-- CAN BE SHARED - Radio Button -->
			<?php
			$elem_yes = $vik->initRadioElement('', JText::_('VRYES'), $sel['multi_res'] == 1);
			$elem_no = $vik->initRadioElement('', JText::_('VRNO'), $sel['multi_res'] == 0);
			?>
			<?php echo $vik->openControl(JText::_('VRMANAGETABLE12').':'); ?>
				<?php echo $vik->radioYesNo('multi_res', $elem_yes, $elem_no, false); ?>
			<?php echo $vik->closeControl(); ?>

			<!-- PUBLISHED - Radio Button -->
			<?php
			$elem_yes = $vik->initRadioElement('', JText::_('VRYES'), $sel['published'] == 1);
			$elem_no = $vik->initRadioElement('', JText::_('VRNO'), $sel['published'] == 0);
			?>
			<?php echo $vik->openControl(JText::_('VRMANAGEROOM3').':'); ?>
				<?php echo $vik->radioYesNo('published', $elem_yes, $elem_no, false); ?>
			<?php echo $vik->closeControl(); ?>
			
			<!-- ROOM - Dropdown -->
			<?php
			$elements = array();
			foreach( $this->rooms as $room ) {
				array_push($elements, $vik->initOptionElement($room['id'], $room['name'], $room['id']==$sel['id_room']));
			}
			?>
			<?php echo $vik->openControl(JText::_('VRMANAGETABLE4').'*:'); ?>
				<?php echo $vik->dropdown('id_room', $elements, 'vr-room-sel', 'required'); ?>
			<?php echo $vik->closeControl(); ?>
		
		<?php echo $vik->closeEmptyFieldset(); ?>
	</div>
	
	<input type="hidden" name="id" value="<?php echo $id; ?>"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="option" value="com_cleverdine"/>
</form>

<script type="text/javascript">
	
	// VALIDATION

	jQuery(document).ready(function(){

		jQuery('#vr-room-sel').select2({
			allowClear: false,
			width: 300
		});

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

		return ok && vrValidateCapacity();
	}

	function vrValidateCapacity() {

		var min = jQuery('input[name="min_capacity"]');
		var max = jQuery('input[name="max_capacity"]');

		if( parseInt(min.val()) > parseInt(max.val()) ) {
			min.addClass('vrrequired');
			max.addClass('vrrequired');

			return false;
		}

		min.removeClass('vrrequired');
		max.removeClass('vrrequired');

		return true;

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