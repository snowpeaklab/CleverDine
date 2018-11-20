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

$media = $this->media;
$thumb = $this->thumb;

$vik = new VikApplication(VersionListener::getID());

$settings = cleverdine::getMediaProperties();

?>

<form name="adminForm" action="index.php" method="post" enctype="multipart/form-data" id="adminForm">
	
	<div class="span12" style="margin-bottom: 30px;">
		<?php echo $vik->openFieldset(JText::_('VRMEDIAFIELDSET1'), 'form-horizontal'); ?>
		
			<!-- NAME - Text -->
			<?php echo $vik->openControl(JText::_('VRMANAGEMEDIA1').'*:'); ?>
				<input type="text" name="name" value="<?php echo $media["name_no_ext"]; ?>" class="required" size="64"/>&nbsp;<?php echo $media['file_ext']; ?>
			<?php echo $vik->closeControl(); ?>
			
			<!-- ACTION - Dropdown -->
			<?php
			$elements = array(
				$vik->initOptionElement('', '', true),
				$vik->initOptionElement(1, JText::_('VRMEDIAACTION1'), false),
				$vik->initOptionElement(2, JText::_('VRMEDIAACTION2'), false),
				$vik->initOptionElement(3, JText::_('VRMEDIAACTION3'), false),
			);
			?>
			<?php echo $vik->openControl(JText::_('VRMANAGEMEDIA5').':'); ?>
				<?php echo $vik->dropdown('action', $elements, 'vr-media-action'); ?>
			<?php echo $vik->closeControl(); ?>

			<!-- MEDIA - File -->
			<?php echo $vik->openControl(JText::_('VRMANAGEMEDIA4').'*:', 'vr-action-child', 'style="display:none;"'); ?>
				<input type="file" name="image" class="vr-action-child-field" size="32"/>
			<?php echo $vik->closeControl(); ?>

			<!-- Resize - Radio Button -->
			<?php
			$elem_yes = $vik->initRadioElement(1, JText::_('VRYES'), $settings['resize'], 'onClick="resizeValueChanged(1);"');
			$elem_no = $vik->initRadioElement(0, JText::_('VRNO'), !$settings['resize'], 'onClick="resizeValueChanged(0);"');
			?>
			<?php echo $vik->openControl(JText::_('VRMANAGEMEDIA6').':', 'vr-replace-child', 'style="display:none;"'); ?>
				<?php echo $vik->radioYesNo('resize', $elem_yes, $elem_no, false); ?>
			<?php echo $vik->closeControl(); ?>

			<!-- Resize Width - Number -->
			<?php echo $vik->openControl(JText::_('VRMANAGEMEDIA7').':', 'vr-replace-child', 'style="display:none;"'); ?>
				<input type="number" name="resize_value" value="<?php echo $settings['resize_value']; ?>" min="16" step="1" id="vr-resize-field" <?php echo ($settings['resize'] ? '' : 'readonly="readonly"'); ?>/>&nbsp;px
			<?php echo $vik->closeControl(); ?>

			<!-- Thumb Width - Number -->
			<?php echo $vik->openControl(JText::_('VRMANAGEMEDIA8').':', 'vr-replace-child', 'style="display:none;"'); ?>
				<input type="number" name="thumb_value" value="<?php echo $settings['thumb_value']; ?>" min="16" step="1" />&nbsp;px
			<?php echo $vik->closeControl(); ?>
		
		<?php echo $vik->closeFieldset(); ?>
	</div>

	<div class="span6">
		<?php echo $vik->openFieldset(JText::_('VRMEDIAFIELDSET2'), 'form-horizontal'); ?>

			<?php echo $vik->openControl(JText::_('VRMANAGEMEDIA2').':'); ?>
				<?php echo $media['size']; ?>
				<span style="margin: 0 10px;">|</span>
				<?php echo $media['width'].' x '.$media['height'].' px'; ?>
			<?php echo $vik->closeControl(); ?>

			<?php echo $vik->openControl(JText::_('VRMANAGEMEDIA3').':'); ?>
				<?php echo $media['creation']; ?>
			<?php echo $vik->closeControl(); ?>

			<div class="control">
				<img src="<?php echo JUri::root().'components/com_cleverdine/assets/media/'.$media['name'].'?t='.time(); ?>"/>
			</div>

		<?php echo $vik->closeFieldset(); ?>
	</div>

	<div class="span6">
		<?php echo $vik->openFieldset(JText::_('VRMEDIAFIELDSET3'), 'form-horizontal'); ?>

			<?php echo $vik->openControl(JText::_('VRMANAGEMEDIA2').':'); ?>
				<?php echo $thumb['size']; ?>
				<span style="margin: 0 10px;">|</span>
				<?php echo $thumb['width'].' x '.$thumb['height'].' px'; ?>
			<?php echo $vik->closeControl(); ?>

			<?php echo $vik->openControl(JText::_('VRMANAGEMEDIA3').':'); ?>
				<?php echo $thumb['creation']; ?>
			<?php echo $vik->closeControl(); ?>

			<div class="control">
				<img src="<?php echo JUri::root().'components/com_cleverdine/assets/media@small/'.$thumb['name'].'?t='.time(); ?>" />
			</div>

		<?php echo $vik->closeFieldset(); ?>
	</div>
	
	<input type="hidden" name="media" value="<?php echo $media['name']; ?>"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="option" value="com_cleverdine"/>
</form>

<script type="text/javascript">

	jQuery(document).ready(function(){

		jQuery('#vr-media-action').select2({
			placeholder: '<?php echo addslashes(JText::_("VRMEDIAACTION0")); ?>',
			allowClear: true,
			width: 300
		});

		jQuery('#vr-media-action').on('change', function(){
			var val = '';

			if( (val = jQuery(this).val()).length ) {
				jQuery('.vr-action-child').show();
				jQuery('.vr-action-child-field').addClass('required');

				if( val == "3" ) {
					jQuery('.vr-replace-child').show();
				} else {
					jQuery('.vr-replace-child').hide();
				}
			} else {
				jQuery('.vr-action-child, .vr-replace-child').hide();
				jQuery('.vr-action-child-field').removeClass('required');
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

	function resizeValueChanged(s) {
		jQuery('#vr-resize-field').prop('readonly', (s ? false : true));
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