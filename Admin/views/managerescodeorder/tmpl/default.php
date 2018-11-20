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
if( !count( $this->selectedStatus ) ) {
	$sel = array(
		'id_rescode' => 0, 'notes' => ''
	);
} else {
	$sel = $this->selectedStatus;
	$id = $sel['id'];
}

$vik = new VikApplication(VersionListener::getID());

?>

<form name="adminForm" action="index.php" method="post" enctype="multipart/form-data" id="adminForm">
	
	<div class="span10">
		<?php echo $vik->openEmptyFieldset(); ?>
		
			<!-- CODE - Dropdown -->
			<?php
			$elements = array(
				$vik->initOptionElement('', '', $sel['id_rescode'] == 0)
			);
			foreach( $this->resCodes as $code ) {
				$elements[] = $vik->initOptionElement($code['id'], $code['code'], $sel['id_rescode'] == $code['id']);
			}
			?>
			<?php echo $vik->openControl(JText::_('VRMANAGERESCODE2')."*:"); ?>
				<?php echo $vik->dropdown('id_rescode', $elements, 'vr-code-sel', 'required'); ?>
			<?php echo $vik->closeControl(); ?>
			
			<!-- NOTES - Editor -->
			<?php echo $vik->openControl(JText::_('VRMANAGERESCODE5').":"); ?>
				<textarea name="notes" style="width: 80%;height: 200px;min-width: 80%;min-height: 200px;max-width: 100%;"><?php echo $sel['notes']; ?></textarea>
			<?php echo $vik->closeControl(); ?>
		
		<?php echo $vik->closeEmptyFieldset(); ?>
	</div>
	
	<input type="hidden" name="id" value="<?php echo $id; ?>"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="option" value="com_cleverdine"/>

	<?php foreach( $this->filters as $k => $v ) {
		?><input type="hidden" name="filters[<?php echo $k; ?>]" value="<?php echo $v; ?>" /><?php
	} ?>

</form>

<script type="text/javascript">
	
	// VALIDATION

	jQuery(document).ready(function(){

		jQuery('#vr-code-sel').select2({
			placeholder: '--',
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
