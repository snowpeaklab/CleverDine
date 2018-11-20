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
if( !count( $this->selectedPayment ) ) {
	$sel = array( 
			'name' => '', 'file' => '', 'published' => 0, 'note' => '', 'prenote' => '', 'charge' => 0.0, 'percentot' => 2, 'setconfirmed' => 0, 'group' => 0, 'icontype' => 0, 'icon' => '', 'enablecost' => 0, 'position' => ''
	);
} else {
	$sel = $this->selectedPayment;
	$id = $sel['id'];
}

$editor = JEditor::getInstance(JFactory::getApplication()->get('editor'));

$allf = glob('./components/com_cleverdine/payments/*.php');
$payment_select = "";
if( @count($allf) > 0 ) {
	$classfiles = array();
	foreach( $allf as $af ) {
		$classfiles[] = str_replace('./components/com_cleverdine/payments/', '', $af);
	}
	sort($classfiles);
	
	$payment_select = '<select name="file" class="required" id="vrgpsel">';
	$payment_select .= '<option value=""></option>';
	foreach( $classfiles as $cf ) {
		$payment_select .= '<option value="'.$cf.'" '.($cf==$sel['file'] ? 'selected="selected"' : '').'>'.$cf.'</option>';
	}
	$payment_select .= '</select>';
}

$currencysymb = cleverdine::getCurrencySymb(true);

$image_path = JUri::root().'components/com_cleverdine/assets/media/';

$vik = new VikApplication(VersionListener::getID());

$mediaManager = new MediaManagerHTML(RestaurantsHelper::getAllMedia(), $image_path, $vik, 'vre');

?>

<form name="adminForm" id="adminForm" action="index.php" method="post">
		
	<div class="span12"></div>

	<div class="span6">
		<?php echo $vik->openFieldset(JText::_('VRMANAGERESERVATION20'), 'form-horizontal'); ?>

			<!-- NAME - Text -->
			<?php echo $vik->openControl(JText::_('VRMANAGEPAYMENT1').'*:'); ?>
				<input type="text" name="name" class="required" value="<?php echo $sel['name']; ?>" size="30"/>
			<?php echo $vik->closeControl(); ?>

			<!-- CLASS - Dropdown -->
			<?php echo $vik->openControl(JText::_('VRMANAGEPAYMENT2').'*:'); ?>
				<?php echo $payment_select; ?>
			<?php echo $vik->closeControl(); ?>

			<!-- PUBLISHED - Radio Button -->
			<?php
			$elem_yes = $vik->initRadioElement('', JText::_('VRYES'), $sel['published']==1);
			$elem_no = $vik->initRadioElement('', JText::_('VRNO'), $sel['published']==0);
			?>
			<?php echo $vik->openControl(JText::_('VRMANAGEPAYMENT3').':'); ?>
				<?php echo $vik->radioYesNo('published', $elem_yes, $elem_no, false); ?>
			<?php echo $vik->closeControl(); ?>

			<!-- CHARGE - Number -->
			<?php
			$elements = array(
				$vik->initOptionElement(1, '%', $sel['percentot'] == 1),
				$vik->initOptionElement(2, $currencysymb, $sel['percentot'] == 2),
			);
			?>
			<?php echo $vik->openControl(JText::_('VRMANAGEPAYMENT4').':'); ?>
				<input type="number" name="charge" value="<?php echo $sel['charge']; ?>" step="any"/>
				<?php echo $vik->dropdown('percentot', $elements, 'vr-percentot-sel'); ?>
			<?php echo $vik->closeControl(); ?>

			<!-- SET CONFIRMED - Radio Button -->
			<?php
			$elem_yes = $vik->initRadioElement('', $elem_yes->label, $sel['setconfirmed']==1);
			$elem_no = $vik->initRadioElement('', $elem_no->label, $sel['setconfirmed']==0);
			?>
			<?php echo $vik->openControl(JText::_('VRMANAGEPAYMENT5').':'); ?>
				<?php echo $vik->radioYesNo('setconfirmed', $elem_yes, $elem_no, false); ?>
			<?php echo $vik->closeControl(); ?>

			<!-- ICON - Fieldset -->
			<?php
			$elements = array(
				$vik->initOptionElement('', '', $sel['icontype'] == 0),
				$vik->initOptionElement(1, JText::_('VRPAYMENTICONOPT1'), $sel['icontype'] == 1),
				$vik->initOptionElement(2, JText::_('VRPAYMENTICONOPT2'), $sel['icontype'] == 2)
			);

			$font_icons = array(
				$vik->initOptionElement('', '', false),
				$vik->initOptionElement('paypal', 'PayPal', $sel['icon'] == 'paypal'),
				$vik->initOptionElement('credit-card', 'Credit Card', $sel['icon'] == 'credit-card'),
				$vik->initOptionElement('credit-card-alt', 'Credit Card Alt', $sel['icon'] == 'credit-card-alt'),
				$vik->initOptionElement('money', 'Money', $sel['icon'] == 'money'),

				$vik->initOptionElement('cc-visa', 'Visa', $sel['icon'] == 'cc-visa'),
				$vik->initOptionElement('cc-mastercard', 'Mastercard', $sel['icon'] == 'cc-mastercard'),
				$vik->initOptionElement('cc-amex', 'American Express', $sel['icon'] == 'cc-amex'),
				$vik->initOptionElement('cc-discover', 'Discovery', $sel['icon'] == 'cc-discover'),
				$vik->initOptionElement('cc-jcb', 'JCB', $sel['icon'] == 'cc-jcb'),
				$vik->initOptionElement('cc-diners-club', 'Diners Club', $sel['icon'] == 'cc-diners-club'),
				$vik->initOptionElement('cc-stripe', 'Stripe', $sel['icon'] == 'cc-stripe'),

				$vik->initOptionElement('eur', 'Euro', $sel['icon'] == 'eur'),
				$vik->initOptionElement('usd', 'Dollar', $sel['icon'] == 'usd'),
				$vik->initOptionElement('gbp', 'Pound', $sel['icon'] == 'gbp'),
			);
			?>
			<?php echo $vik->openControl(JText::_('VRMANAGEPAYMENT12')); ?>
				<?php echo $vik->dropdown('icontype', $elements, 'vr-icontype-sel'); ?>
				
				<span id="vr-fonticon-wrapper" style="<?php echo ($sel['icontype'] == 1 ? '' : 'display: none;'); ?>">
					<?php echo $vik->dropdown('font_icon', $font_icons, 'vr-fonticon-sel'); ?>
					<span id="vr-fonticon-preview"><i class="fa fa-<?php echo $sel['icon']; ?> big" style="margin-left: 10px;"></i></span>
				</span>

				<span id="vr-iconupload-wrapper" style="<?php echo ($sel['icontype'] == 2 ? '' : 'display: none;'); ?>">
					<?php echo $mediaManager->buildMedia('upload_icon', 1, $sel['icon']); ?>
				</span>

			<?php echo $vik->closeControl(); ?>

			<!-- POSITION - Select -->
			<?php
			$elements = array(
				$vik->initOptionElement('', '', false),
				$vik->initOptionElement('vr-payment-position-top', JText::_('VRPAYMENTPOSOPT2'), $sel['position']=='vr-payment-position-top'),
				$vik->initOptionElement('vr-payment-position-bottom', JText::_('VRPAYMENTPOSOPT3'), $sel['position']=='vr-payment-position-bottom'),
			);
			?> 
			<?php echo $vik->openControl(JText::_('VRMANAGEPAYMENT13').':'); ?>
				<?php echo $vik->dropdown('position', $elements, 'vr-position-sel'); ?>
			<?php echo $vik->closeControl(); ?>

			<!-- RESTRICTIONS - Number -->
			<?php
			$elements = array(
				$vik->initOptionElement(0, JText::_('VRPAYRESTROPT1'), $sel['enablecost'] == 0),
				$vik->initOptionElement(1, JText::_('VRPAYRESTROPT2'), $sel['enablecost'] > 0),
				$vik->initOptionElement(-1, JText::_('VRPAYRESTROPT3'), $sel['enablecost'] < 0),
			);
			?>
			<?php echo $vik->openControl(JText::_('VRMANAGEPAYMENT10').':'); ?>
				<?php echo $vik->dropdown('enablecost_factor', $elements, 'vr-enablecost-sel'); ?>
				<span class="vrenablecost-amount" style="<?php echo ($sel['enablecost'] == 0 ? 'display: none;' : ''); ?>">
					<input type="number" name="enablecost_amount" value="<?php echo abs($sel['enablecost']); ?>" min="0" max="999999" step="any"/>
					&nbsp;<?php echo cleverdine::getCurrencySymb(true); ?>
				</span>
			<?php echo $vik->closeControl(); ?>

			<!-- GROUP - Radio Button -->
			<?php echo $vik->openControl(JText::_('VRMANAGECUSTOMF7').':'); ?>
				<?php echo RestaurantsHelper::buildGroupDropdown('group', $sel['group'], 'vr-group-sel', array(1, 2), '', true); ?>
			<?php echo $vik->closeControl(); ?>
			
		<?php echo $vik->closeFieldset(); ?>
	</div>

	<div class="span6">
		<?php echo $vik->openFieldset(JText::_('VRMANAGEPAYMENT8'), 'form-horizontal'); ?>
			<div class="vikpayparamdiv">
				<div class="vrpaymentparam"><?php echo JText::_('VRMANAGEPAYMENT9'); ?></div>
			</div>
		<?php echo $vik->closeEmptyFieldset(); ?>
	</div>

	<div class="span10">
		<?php echo $vik->openFieldset(JText::_('VRMANAGEPAYMENT11'), 'form-horizontal'); ?>
			<div class="control-group"><?php echo $editor->display( "prenote", $sel['prenote'], 400, 200, 70, 20 ); ?></div>
		<?php echo $vik->closeFieldset(); ?>
	</div>
	
	<div class="span10">
		<?php echo $vik->openFieldset(JText::_('VRMANAGEPAYMENT7'), 'form-horizontal'); ?>
			<div class="control-group"><?php echo $editor->display( "note", $sel['note'], 400, 200, 70, 20 ); ?></div>
		<?php echo $vik->closeFieldset(); ?>
	</div>
		
	<input type="hidden" name="id" value="<?php echo $id; ?>"/>
	<input type="hidden" name="task" value="">
	<input type="hidden" name="option" value="com_cleverdine">

</form>

<?php echo $mediaManager->buildModal(JText::_('VRMEDIAFIELDSET4')); ?>

<?php echo $mediaManager->useScript(JText::_("VRMANAGEMEDIA10")); ?>

<script>

	jQuery(document).ready(function() {

		jQuery('#vrgpsel').select2({
			placeholder: '--',
			allowClear: false,
			width: 300
		});

		jQuery('#vr-enablecost-sel').select2({
			minimumResultsForSearch: -1,
			allowClear: false,
			width: 300
		});

		jQuery('#vr-percentot-sel').select2({
			minimumResultsForSearch: -1,
			allowClear: false,
			width: 100
		});

		jQuery('#vr-icontype-sel').select2({
			minimumResultsForSearch: -1,
			allowClear: true,
			placeholder: '<?php echo addslashes(JText::_('VRPAYMENTICONOPT0')); ?>',
			width: 200
		});

		jQuery('#vr-position-sel').select2({
			minimumResultsForSearch: -1,
			allowClear: true,
			placeholder: '<?php echo addslashes(JText::_('VRPAYMENTPOSOPT1')); ?>',
			width: 200
		});

		jQuery('#vr-fonticon-sel').select2({
			placeholder: '--',
			allowClear: false,
			width: 200
		});

		jQuery('#vr-icontype-sel').on('change', function(){

			var val = jQuery(this).val();

			if( val == 1 ) {
				jQuery('#vr-fonticon-wrapper').show();
				jQuery('#vr-iconupload-wrapper').hide();
			} else if( val == 2 ) {
				jQuery('#vr-fonticon-wrapper').hide();
				jQuery('#vr-iconupload-wrapper').show();
			} else {
				jQuery('#vr-fonticon-wrapper').hide();
				jQuery('#vr-iconupload-wrapper').hide();
			}

		});

		jQuery('#vr-fonticon-sel').on('change', function(){
			jQuery('#vr-fonticon-preview i').attr('class', 'fa fa-'+jQuery(this).val()+' big');
		});

		jQuery('#vrgpsel').on('change', function(){
			vrPaymentGatewayChanged();
		});

		<?php if( $id != -1 ) { ?>
			vrPaymentGatewayChanged();
		<?php } ?>

		jQuery('#vr-enablecost-sel').on('change', function(){
			if( jQuery(this).val() == "0" ) {
				jQuery('.vrenablecost-amount').hide();
			} else {
				jQuery('.vrenablecost-amount').show();
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
	
	function vrPaymentGatewayChanged() {
		var gp = jQuery('#vrgpsel').val();
		
		jQuery.noConflict();
		
		jQuery('.vikpayparamdiv').html('');
		
		var jqxhr = jQuery.ajax({
			type: "POST",
			url: "index.php",
			data: { option: "com_cleverdine", task: "get_payment_fields", gpn: gp, id_gp: <?php echo $id; ?>, tmpl: "component" }
		}).done(function(resp){
			var obj = jQuery.parseJSON(resp); 
			
			jQuery('.vikpayparamdiv').html(obj[0]);

			jQuery('.vikpayparamdiv select').select2({
				allowClear: false,
				width: 280,
			});

			jQuery("#adminForm .required").on("blur", function(){
				if( jQuery(this).val().length > 0 ) {
					jQuery(this).removeClass("vrrequired");
				} else {
					jQuery(this).addClass("vrrequired");
				}
			});

		});
	}

	// VALIDATION

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