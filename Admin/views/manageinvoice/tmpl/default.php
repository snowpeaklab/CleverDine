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

JHtml::_('behavior.calendar');

$row = $this->row;
$invoice = $this->invoice;

$vik = new VikApplication(VersionListener::getID());

$date = getdate();

if( !strlen($this->filters['month']) ) {
	$this->filters['month'] = $date['mon'];
}

if( !strlen($this->filters['year']) ) {
	$this->filters['year'] = $date['year'];
}

$row['inv_number'] = explode('/', $row['inv_number']);

$date_format = cleverdine::getdateFormat(true);

?>

<form name="adminForm" action="index.php" method="post" enctype="multipart/form-data" id="adminForm">

	<div class="span6">
		<?php echo $vik->openFieldset(JText::_('VRINVOICEFIELDSET1'), 'form-horizontal'); ?>

			<!-- OVERWRITE - Hidden -->
			<input type="hidden" name="ord_group" value="<?php echo $row['group']; ?>" />

			<!-- OVERWRITE - Hidden -->
			<input type="hidden" name="overwrite" value="1" />

			<!-- MANAGEMENT - Hidden -->
			<input type="hidden" name="management" value="1" />

			<!-- NOTIFY CUSTOMERS - Radio Button -->
			<?php
			$elem_yes 	= $vik->initRadioElement('', '', false);
			$elem_no 	= $vik->initRadioElement('', '', true);
			?>
			<?php echo $vik->openControl(JText::_('VRMANAGEINVOICE7').':'); ?>
				<?php echo $vik->radioYesNo('notifycust', $elem_yes, $elem_no, false); ?>
			<?php echo $vik->closeControl(); ?>

		<?php echo $vik->closeFieldset(); ?>
	</div>

	<div class="span6">

		<div></div>

		<div class="span12">
			<?php echo $vik->openFieldset(JText::_('VRINVOICEFIELDSET2'), 'form-horizontal'); ?>

				<!-- INVOICE NUMBER - Text -->
				<?php echo $vik->openControl(JText::_('VRMANAGEINVOICE4').'*:'); ?>
					<input type="number" name="inv_number[]" value="<?php echo $row['inv_number'][0]; ?>" min="1" max="99999999" value="" class="required" style="text-align:right;" step="1"/>&nbsp;/&nbsp;
					<input type="text" name="inv_number[]" value="<?php echo $row['inv_number'][1]; ?>" size="10" value="" />
				<?php echo $vik->closeControl(); ?>

				<!-- INVOICE DATE - Dropdown -->
				<?php echo $vik->openControl(JText::_('VRMANAGEINVOICE5').':'); ?>
					<?php echo $vik->calendar(date($date_format, $row['inv_date']), 'custom_date', 'vrcustdate', null, array('class' => 'required')); ?>
				<?php echo $vik->closeControl(); ?>

				<!-- LEGAL INFO - Textarea -->
				<?php echo $vik->openControl(JText::_('VRMANAGEINVOICE6').':'); ?>
					<textarea name="legal_info" style="width: 80%;height: 150px;resize: vertical;"><?php echo $invoice->params->legalInfo; ?></textarea>
				<?php echo $vik->closeControl(); ?>

			<?php echo $vik->closeFieldset(); ?>
		</div>

		<div class="span12">
			<?php echo $vik->openFieldset(JText::_('VRINVOICEFIELDSET3'), 'form-horizontal'); ?>

				<!-- PAGE ORIENTATION - Dropdown -->
				<?php
				$elements = array(
					$vik->initOptionElement(cleverdineConstraintsPDF::PAGE_ORIENTATION_PORTRAIT, JText::_('VRINVOICEPAGEORIOPT1'), $invoice->constraints->pageOrientation == cleverdineConstraintsPDF::PAGE_ORIENTATION_PORTRAIT),
					$vik->initOptionElement(cleverdineConstraintsPDF::PAGE_ORIENTATION_LANDSCAPE, JText::_('VRINVOICEPAGEORIOPT2'), $invoice->constraints->pageOrientation == cleverdineConstraintsPDF::PAGE_ORIENTATION_LANDSCAPE)
				);
				?>
				<?php echo $vik->openControl(JText::_('VRMANAGEINVOICE8').':'); ?>
					<?php echo $vik->dropdown('page_orientation', $elements, '', 'medium'); ?>
				<?php echo $vik->closeControl(); ?>

				<!-- PAGE FORMAT - Dropdown -->
				<?php
				$elements = array(
					$vik->initOptionElement(cleverdineConstraintsPDF::PAGE_FORMAT_A4, cleverdineConstraintsPDF::PAGE_FORMAT_A4, $invoice->constraints->pageFormat == cleverdineConstraintsPDF::PAGE_FORMAT_A4),
					$vik->initOptionElement(cleverdineConstraintsPDF::PAGE_FORMAT_A5, cleverdineConstraintsPDF::PAGE_FORMAT_A5, $invoice->constraints->pageFormat == cleverdineConstraintsPDF::PAGE_FORMAT_A5),
					$vik->initOptionElement(cleverdineConstraintsPDF::PAGE_FORMAT_A6, cleverdineConstraintsPDF::PAGE_FORMAT_A6, $invoice->constraints->pageFormat == cleverdineConstraintsPDF::PAGE_FORMAT_A6)
				);
				?>
				<?php echo $vik->openControl(JText::_('VRMANAGEINVOICE9').':'); ?>
					<?php echo $vik->dropdown('page_format', $elements, '', 'medium'); ?>
				<?php echo $vik->closeControl(); ?>

				<!-- UNIT - Dropdown -->
				<?php
				$elements = array(
					$vik->initOptionElement(cleverdineConstraintsPDF::UNIT_POINT, JText::_('VRINVOICEUNITOPT1'), $invoice->constraints->unit == cleverdineConstraintsPDF::UNIT_POINT),
					$vik->initOptionElement(cleverdineConstraintsPDF::UNIT_MILLIMETER, JText::_('VRINVOICEUNITOPT2'), $invoice->constraints->unit == cleverdineConstraintsPDF::UNIT_MILLIMETER),
					$vik->initOptionElement(cleverdineConstraintsPDF::UNIT_CENTIMETER, JText::_('VRINVOICEUNITOPT3'), $invoice->constraints->unit == cleverdineConstraintsPDF::UNIT_CENTIMETER),
					$vik->initOptionElement(cleverdineConstraintsPDF::UNIT_INCH, JText::_('VRINVOICEUNITOPT4'), $invoice->constraints->unit == cleverdineConstraintsPDF::UNIT_INCH)
				);
				?>
				<?php echo $vik->openControl(JText::_('VRMANAGEINVOICE10').':'); ?>
					<?php echo $vik->dropdown('unit', $elements, '', 'medium'); ?>
				<?php echo $vik->closeControl(); ?>

				<!-- SCALE RATIO - Number -->
				<?php echo $vik->openControl(JText::_('VRMANAGEINVOICE11').':'); ?>
					<input type="number" name="scale" value="<?php echo ($invoice->constraints->imageScaleRatio*100); ?>" min="0" step="1" />&nbsp;%
				<?php echo $vik->closeControl(); ?>

			<?php echo $vik->closeFieldset(); ?>
		</div>

	</div>
	
	<?php foreach( $this->filters as $k => $v ) { ?>
		<?php if( strlen($v) ) { ?>
			<input type="hidden" name="<?php echo $k; ?>" value="<?php echo $v; ?>" />
		<?php } ?>
	<?php } ?>
	
	<input type="hidden" name="cid[]" value="<?php echo $row['id_order']; ?>"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="option" value="com_cleverdine"/>
</form>

<script type="text/javascript">

	jQuery(document).ready(function(){

		jQuery('.vik-dropdown.medium').select2({
			minimumResultsForSearch: -1,
			allowClear: false,
			width: 200
		});

	});	

	// validation

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