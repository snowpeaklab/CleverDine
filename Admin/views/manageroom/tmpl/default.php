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
if( !count( $this->selectedRoom ) ) {
	$sel = array(
		'name' => '', "description" => '', 'published' => 1, 'image' => ''
	);
} else {
	$sel = $this->selectedRoom;
	$id = $sel['id'];
}

$editor = JEditor::getInstance(JFactory::getApplication()->get('editor'));

$date_format = cleverdine::getDateFormat(true);

$df_joomla = $date_format;
$df_joomla = str_replace( "d", "%d", $df_joomla );
$df_joomla = str_replace( "m", "%m", $df_joomla );
$df_joomla = str_replace( "Y", "%Y", $df_joomla );

$image_path = JUri::root().'components/com_cleverdine/assets/media/';

$vik = new VikApplication(VersionListener::getID());

$mediaManager = new MediaManagerHTML(RestaurantsHelper::getAllMedia(), $image_path, $vik, 'vre');

?>

<form name="adminForm" action="index.php" method="post" enctype="multipart/form-data" id="adminForm">
	
	<div class="span6">
		<?php echo $vik->openFieldset(JText::_('VRMANAGETABLE4'), 'form-horizontal'); ?>
			
			<!-- NAME - Text -->
			<?php echo $vik->openControl(JText::_('VRMANAGEROOM1').'*:'); ?>
				<input class="required" type="text" name="name" value="<?php echo $sel['name']; ?>" size="40"/>
			<?php echo $vik->closeControl(); ?>
			
			<!-- PUBLISHED - Radio Button -->
			<?php
			$elem_yes = $vik->initRadioElement('', JText::_('VRYES'), $sel['published'] == 1);
			$elem_no = $vik->initRadioElement('', JText::_('VRNO'), $sel['published'] == 0);
			?>
			<?php echo $vik->openControl(JText::_('VRMANAGEROOM3').':'); ?>
				<?php echo $vik->radioYesNo('published', $elem_yes, $elem_no, false); ?>
			<?php echo $vik->closeControl(); ?>
			
			<!-- IMAGE - File -->
			<?php echo $vik->openControl(JText::_('VRMANAGEROOM4').':'); ?>
				<?php echo $mediaManager->buildMedia('image', 1, $sel['image']); ?>
			<?php echo $vik->closeControl(); ?>
		
		<?php echo $vik->closeFieldset(); ?>
	</div>
		
	<div class="span6">
		<?php echo $vik->openFieldset(JText::_('VRMANAGEROOM2'), 'form-horizontal'); ?>
			<div class="control-group"><?php echo $editor->display( 'description', $sel['description'], 400, 200, 70, 20 ); ?></div>
		<?php echo $vik->closeFieldset(); ?>
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
