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
		'name' => '', 'price' => 0.0, 'published' => 1, 'id_separator' => $this->lastSeparatorUsed
	);
} else {
	$sel = $this->row;
	$id = $sel['id'];
}

$vik = new VikApplication(VersionListener::getID());

?>

<form name="adminForm" action="index.php" method="post" enctype="multipart/form-data" id="adminForm">
	
	<div class="span10">
		<?php echo $vik->openEmptyFieldset(); ?>
			
			<!-- NAME - Text -->
			<?php echo $vik->openControl(JText::_('VRMANAGETKTOPPING1').'*:'); ?>
				<input type="text" name="name" class="required" value="<?php echo $sel['name']; ?>" size="40"/>
			<?php echo $vik->closeControl(); ?>
			
			<!-- PRICE - Number -->
			<?php echo $vik->openControl(JText::_('VRMANAGETKTOPPING2').':'); ?>
				<input  type="number" name="price" value="<?php echo $sel['price']; ?>" size="4" min="0" max="999999" step="any"/>
				&nbsp;<?php echo cleverdine::getCurrencySymb(true); ?>
			<?php echo $vik->closeControl(); ?>

			<?php if( $id > 0 ) { ?>

				<!-- PRICE QUICK UPDATE - Dropdown -->
				<?php
				$elements = array(
					$vik->initOptionElement(0, JText::_('VRTKTOPPINGQUICKOPT0'), true),
					$vik->initOptionElement(1, JText::_('VRTKTOPPINGQUICKOPT1'), false),
					$vik->initOptionElement(2, JText::_('VRTKTOPPINGQUICKOPT2'), false)
				);
				?>
				<?php echo $vik->openControl(JText::_('VRMANAGETKTOPPING6').':', 'vr-quick-update', 'style="display:none;"'); ?>
					<?php echo $vik->dropdown('update_price', $elements, 'vr-quick-update-sel'); ?>
				<?php echo $vik->closeControl(); ?>

				<input type="hidden" name="old_price" value="<?php echo $sel['price']; ?>" />

			<?php } ?>
			
			<!-- PUBLISHED - Number -->
			<?php
			$elem_yes = $vik->initRadioElement('', JText::_('VRYES'), $sel['published']==1);
			$elem_no = $vik->initRadioElement('', JText::_('VRNO'), $sel['published']==0);
			?>
			<?php echo $vik->openControl(JText::_('VRMANAGETKTOPPING3').':'); ?>
				<?php echo $vik->radioYesNo('published', $elem_yes, $elem_no, false); ?>
			<?php echo $vik->closeControl(); ?>
			
			<!-- SEPARATOR - Dropdown -->
			<?php
			$elements = array(
				$vik->initOptionElement('', '', $sel['id_separator'] <= 0),
				$vik->initOptionElement(-1, JText::_('VRCREATENEWOPT'), false)
			);
			foreach( $this->separators as $sep ) {
				array_push($elements, $vik->initOptionElement($sep['id'], $sep['title'], $sel['id_separator']==$sep['id']));
			}
			?>
			<?php echo $vik->openControl(JText::_('VRMANAGETKTOPPING5').':'); ?>
				<?php echo $vik->dropdown('id_separator', $elements, 'vr-separator-sel'); ?>
				<input type="text" name="separator_name" value="" size="32" id="vr-separator-name" style="display: none;" placeholder="<?php echo JText::_('VRMANAGETKTOPPINGSEP1'); ?>"/>
			<?php echo $vik->closeControl(); ?>
		
		<?php echo $vik->closeEmptyFieldset(); ?>
	</div>
	
	<input type="hidden" name="id" value="<?php echo $id; ?>"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="option" value="com_cleverdine"/>
</form>

<script type="text/javascript">

	jQuery(document).ready(function(){
		jQuery('#vr-separator-sel').select2({
			placeholder: '<?php echo addslashes(JText::_('VRNONEOPT')); ?>',
			allowClear: true,
			width: 300
		});

		jQuery('#vr-separator-sel').on('change', function(){
			var val = 0;

			if( (val = jQuery(this).val()) == "-1" ) {
				jQuery('#vr-separator-name').addClass('required');
				jQuery('#vr-separator-name').show();
			} else {
				jQuery('#vr-separator-name').removeClass('required');
				jQuery('#vr-separator-name').hide();
			}

		});

		<?php if( $id > 0 ) { ?>

			var PRICE_START_VAL = <?php echo $sel['price']; ?>;

			jQuery('input[name="price"]').on('change', function(){
				if( parseFloat(jQuery(this).val()) != PRICE_START_VAL ) {
					jQuery('.vr-quick-update').show();
				} else {
					jQuery('.vr-quick-update').hide();
					jQuery('#vr-quick-update-sel').select2('val', 0);
				}
			});

			jQuery('#vr-quick-update-sel').select2({
				minimumResultsForSearch: -1,
				allowClear: false,
				width: 300
			});

		<?php } ?>

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