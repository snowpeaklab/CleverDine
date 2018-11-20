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
if( !count( $this->selectedProduct ) ) {
	$sel = array(
		'name' => '', 'description' => '', 'price' => 0.0, 'published' => ($this->status == 1 ? 1 : 0), 'image' => '', 'hidden' => ($this->status == 3 ? 1 : 0)
	);
} else {
	$sel = $this->selectedProduct;
	$id = $sel['id'];
}

$curr_symb = cleverdine::getCurrencySymb(true);

$editor = JEditor::getInstance(JFactory::getApplication()->get('editor'));

$image_path = JUri::root().'components/com_cleverdine/assets/media/';

$vik = new VikApplication(VersionListener::getID());

$mediaManager = new MediaManagerHTML(RestaurantsHelper::getAllMedia(), $image_path, $vik, 'vre');

?>

<form name="adminForm" action="index.php" method="post" enctype="multipart/form-data" id="adminForm">
	
	<div class="span6">
		
		<div></div>
		
		<div class="span11">
			<?php echo $vik->openFieldset(JText::_('VRMENUPRODFIELDSET1'), 'form-horizontal'); ?>
				
				<!-- NAME - Text -->
				<?php echo $vik->openControl(JText::_('VRMANAGEMENUSPRODUCT2').'*:'); ?>
					<input type="text" name="name" class="required" value="<?php echo $sel['name']; ?>" size="40"/>
				<?php echo $vik->closeControl(); ?>
		
				<!-- PRICE - Number -->
				<?php echo $vik->openControl(JText::_('VRMANAGEMENUSPRODUCT4').':'); ?>
					<input type="number" name="price" value="<?php echo $sel['price']; ?>" min="0" max="999999" step="any"/>&nbsp;<?php echo $curr_symb; ?>
				<?php echo $vik->closeControl(); ?>
				
				<?php if( $sel['hidden'] == 0 ) { ?>

					<!-- PRODUCT IS NOT HIDDEN -->

					<!-- PUBLISHED - Radio Button -->
					<?php
					$elem_yes = $vik->initRadioElement('', JText::_('VRYES'), $sel['published']==1);
					$elem_no = $vik->initRadioElement('', JText::_('VRNO'), $sel['published']==0);
					?>
					<?php echo $vik->openControl(JText::_('VRMANAGEMENUSPRODUCT6').':'); ?>
						<?php echo $vik->radioYesNo('published', $elem_yes, $elem_no, false); ?>
					<?php echo $vik->closeControl(); ?>
					
					<!-- IMAGE - File -->
					<?php echo $vik->openControl(JText::_('VRMANAGEMENUSPRODUCT5').':'); ?>
						<?php echo $mediaManager->buildMedia('image', 1, $sel['image']); ?>
					<?php echo $vik->closeControl(); ?>

					<!-- END -->

				<?php } ?>
				
			<?php echo $vik->closeFieldset(); ?>
		</div>
		
		<div class="span11">
			<?php echo $vik->openFieldset(JText::_('VRMENUPRODFIELDSET2'), 'form-horizontal'); ?>
				
				<div class="control-group">
				
					<div class="vrtk-entry-variations">
						<?php 
						$last_var_id = 0;
						foreach( $this->variations as $var ) { ?>
							<div id="vrtkoptdiv<?php echo $var['id']; ?>" class="vrtk-entry-var">
								<span class="vrtk-entry-varsp">
									<span class="vrtk-entryvar-sortbox"></span>
									<input type="hidden" name="option_id[]" id="vrtkoptionid<?php echo $var['id']; ?>" value="<?php echo $var['id']; ?>" />
									<input type="text" name="oname[]" value="<?php echo $var['name']; ?>" size="32" placeholder="<?php echo JText::_('VRMANAGETKMENU4'); ?>" />
									<input type="text" name="oprice[]" value="<?php echo $var['inc_price']; ?>" size="6" placeholder="<?php echo JText::_('VRMANAGETKMENU5'); ?>"/> <?php echo $curr_symb; ?>
									<a href="javascript: void(0);" class="vrtkmenuremovebutton" onClick="removeVariation(<?php echo $var['id']; ?>);"></a>
								</span>
							</div>
							<?php $last_var_id = max(array($var['id'], $last_var_id)); ?>
						<?php } ?>
					</div>
					
					<div class="vrtk-entry-addvar">
						<button type="button" class="btn" onClick="addNewVariation();">
							<?php echo JText::_('VRMANAGETKMENUADDVAR'); ?>
						</button>
					</div>
					
				</div>  
				
			<?php echo $vik->closeFieldset(); ?>
		</div>
		
	</div>
	
	<div class="span5">
		<?php echo $vik->openFieldset(JText::_('VRMANAGEMENUSPRODUCT3'), 'form-horizontal'); ?>
			<div class="control-group"><?php echo $editor->display( "description", $sel['description'], 400, 200, 70, 20 ); ?></div>
		<?php echo $vik->closeFieldset(); ?>
	</div>

	<?php if( $sel['hidden'] == 1 ) { ?>
		<input type="hidden" name="hidden" value="1" />
	<?php } ?>
	
	<input type="hidden" name="id" value="<?php echo $id; ?>"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="option" value="com_cleverdine"/>
</form>

<?php echo $mediaManager->buildModal(JText::_('VRMEDIAFIELDSET4')); ?>

<?php echo $mediaManager->useScript(JText::_("VRMANAGEMEDIA10")); ?>

<script>
	jQuery(document).ready(function(){
		makeSortable();

		jQuery("#adminForm .required").on("blur", function(){
			if( jQuery(this).val().length > 0 ) {
				jQuery(this).removeClass("vrrequired");
			} else {
				jQuery(this).addClass("vrrequired");
			}
		});

	});

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

	// VARIATIONS
	
	curr_var_index = <?php echo ($last_var_id+1); ?>;
	
	function addNewVariation() {
		jQuery('.vrtk-entry-variations').append(
			'<div id="vrtkoptdiv'+curr_var_index+'" class="vrtk-entry-var">\n'+
				'<span class="vrtk-entry-varsp">\n'+
					'<span class="vrtk-entryvar-sortbox"></span>\n'+
					'<input type="hidden" name="option_id[]" id="vrtkoptionid'+curr_var_index+'" value="-1" />\n'+
					'<input type="text" name="oname[]" value="" size="32" placeholder="<?php echo JText::_('VRMANAGETKMENU4'); ?>" />\n'+
					'<input type="text" name="oprice[]" value="" size="6" placeholder="<?php echo JText::_('VRMANAGETKMENU5'); ?>"/> <?php echo $curr_symb; ?>\n'+
					'<a href="javascript: void(0);" class="vrtkmenuremovebutton" onClick="removeVariation('+curr_var_index+');"></a>\n'+
				'</span>\n'+
			'</div>\n');
			
		curr_var_index++;
		
		makeSortable();
	}
	
	function removeVariation(var_id) {
		var table_row = jQuery('#vrtkoptionid'+var_id).val();
		jQuery('#vrtkoptdiv'+var_id).remove();
		if( table_row != -1 ) {
			jQuery('#adminForm').append('<input type="hidden" name="remove_option[]" value="'+table_row+'"/>');
		}
	}
	
	function makeSortable() {
		jQuery( ".vrtk-entry-variations" ).sortable({
			revert: true
		});
		//jQuery( ".vrtk-entry-variations, .vrtk-entry-var" ).disableSelection();
	}

</script>
