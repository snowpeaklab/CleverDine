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
if( !count( $this->selectedCode ) ) {
	$sel = array(
		'code' => '', 'icon' => '', 'type' => 1, 'notes' => ''
	);
} else {
	$sel = $this->selectedCode;
	$id = $sel['id'];
}

$image_path = JUri::root().'components/com_cleverdine/assets/media/';

$vik = new VikApplication(VersionListener::getID());

$mediaManager = new MediaManagerHTML(RestaurantsHelper::getAllMedia(), $image_path, $vik, 'vre');

?>

<form name="adminForm" action="index.php" method="post" enctype="multipart/form-data" id="adminForm">
	
	<div class="span10">
		<?php echo $vik->openEmptyFieldset(); ?>
		
			<!-- CODE - Text -->
			<?php echo $vik->openControl(JText::_('VRMANAGERESCODE2')."*:"); ?>
				<input type="text" name="code" value="<?php echo $sel['code']; ?>" class="required" size="40"/>
			<?php echo $vik->closeControl(); ?>
			
			<!-- ICON - File -->
			<?php echo $vik->openControl(JText::_('VRMANAGERESCODE3').":"); ?>
				<?php echo $mediaManager->buildMedia('icon', 1, $sel['icon']); ?>
			<?php echo $vik->closeControl(); ?>
			
			<!-- TYPE - Dropdown -->
			<?php echo $vik->openControl(JText::_('VRMANAGERESCODE4').":"); ?>
				<?php echo RestaurantsHelper::buildGroupDropdown('type', $sel['type'], 'vr-type-sel', array(1, 2)); ?>
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
</form>

<?php echo $mediaManager->buildModal(JText::_('VRMEDIAFIELDSET4')); ?>

<?php echo $mediaManager->useScript(JText::_("VRMANAGEMEDIA10")); ?>

<script type="text/javascript">
	
	// VALIDATION

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
