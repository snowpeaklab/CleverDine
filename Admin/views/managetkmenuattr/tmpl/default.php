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
if( !count( $this->row ) ) {
	$sel = array(
		'name' => '', 'description' => '', 'published' => 1, 'icon' => ''
	);
} else {
	$sel = $this->row;
	$id = $sel["id"];
}

$image_path = JUri::root().'components/com_cleverdine/assets/media/';

$vik = new VikApplication(VersionListener::getID());

$mediaManager = new MediaManagerHTML(RestaurantsHelper::getAllMedia(), $image_path, $vik, 'vre');

?>

<form name="adminForm" action="index.php" method="post" enctype="multipart/form-data" id="adminForm">
	
	<div class="span8">
		<?php echo $vik->openEmptyFieldset(); ?>
			
			<!-- NAME - Text -->
			<?php echo $vik->openControl(JText::_('VRMANAGETKMENUATTR1').'*:'); ?>
				<input type="text" name="name" class="required" value="<?php echo $sel["name"]; ?>" size="40"/>
			<?php echo $vik->closeControl(); ?>
			
			<!-- PUBLISHED - Number -->
			<?php
			$elem_yes = $vik->initRadioElement('', JText::_('VRYES'), $sel['published']==1);
			$elem_no = $vik->initRadioElement('', JText::_('VRNO'), $sel['published']==0);
			?>
			<?php echo $vik->openControl(JText::_('VRMANAGETKMENUATTR3').':'); ?>
				<?php echo $vik->radioYesNo('published', $elem_yes, $elem_no, false); ?>
			<?php echo $vik->closeControl(); ?>
			
			<!-- ICON - File -->
			<?php echo $vik->openControl(JText::_('VRMANAGETKMENUATTR4').'*:'); ?>
				<?php echo $mediaManager->buildMedia('icon', 1, $sel['icon']); ?>
			<?php echo $vik->closeControl(); ?>
			
			<!-- DESCRIPTION - Textarea -->
			<?php echo $vik->openControl(JText::_('VRMANAGETKMENUATTR2').':'); ?>
				<textarea name="description" style="width: 99%;height: 120px;" maxlength="512"><?php echo $sel['description']; ?></textarea>
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

	jQuery(document).ready(function(){

		jQuery('select[name="icon"]').addClass('required');

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