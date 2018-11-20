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

$row = $this->row;

$vik = new VikApplication(VersionListener::getID());

$curr_symb 	= cleverdine::getCurrencySymb(true);
$symb_pos 	= cleverdine::getCurrencySymbPosition(true);

$_symb_arr = array( '', '' );
if( $symb_pos == 1 ) {
	$_symb_arr[1] = ' '.$curr_symb;
} else {
	$_symb_arr[0] = $curr_symb.' ';
}

$items_optgroup = array(
	'published' => 'VRSYSPUBLISHED1',
	'unpublished' => 'VRSYSPUBLISHED0',
	'hidden' => 'VRSYSHIDDEN'
);

$all_items_select = '<select id="vrtk-order-cart-allitem-select">';
$all_items_select .= '<option></option>';

foreach( $this->products as $key => $list ) {

	if( count($list) ) {

		$all_items_select .= '<optgroup label="'.JText::_($items_optgroup[$key]).'" data-key="'.$key.'">';

		foreach( $list as $prod ) {
			$all_items_select .= '<option value="'.$prod['id'].'">'.$prod['name'].'</option>';
		}

		$all_items_select .= '</optgroup>';

	}
}

$all_items_select .= '</select>';

// active coupon

$coupon_code = (strlen($row['coupon_str']) ? substr($row['coupon_str'], 0, strpos($row['coupon_str'], ';;')) : '');

?>

<form name="adminForm" action="index.php" method="post" enctype="multipart/form-data" id="adminForm">
		
	<div class="span8">
		
		<!-- empty div to align span8 boxes vertically -->
		<div></div>
		
		<div class="span10">
			<?php echo $vik->openFieldset(JText::_('VRTKORDERCARTFIELDSET1'), 'form-horizontal'); ?>
				
				<!-- BILL VALUE - Number -->
				<?php echo $vik->openControl(JText::_('VRMANAGEBILL2').':'); ?>
					<input type="number" name="bill_value" value="<?php echo $row['bill_value']; ?>" id="vrtk-total-text" style="text-align: right;"/>
					&nbsp;<?php echo $curr_symb; ?>

					&nbsp;

					<button type="button" class="btn" onclick="toggleSearchToolsButton(this);">
						<?php echo JText::_('VRDISCOUNT'); ?>&nbsp;<i class="fa fa-caret-down" id="vr-tools-caret"></i>
					</button>
				<?php echo $vik->closeControl(); ?>

				<div id="vr-discount-wrapper" style="display: none;padding: 10px 0;border-top:1px dashed #ccc;border-bottom: 1px dashed #ccc;margin-bottom: 10px;">

					<!-- COUPON CODE - Label -->
					<?php echo $vik->openControl(JText::_('VRMANAGETKORDDISC3').':'); ?>
						<div class="control-html-value">
							<?php echo (strlen($coupon_code) ? $coupon_code : '--'); ?>&nbsp;<?php echo ($row['discount_val'] != 0 ? '( '.cleverdine::printPriceCurrencySymb($row['discount_val']*-1, $curr_symb, $symb_pos, true).' )' : ''); ?>
						</div>
					<?php echo $vik->closeControl(); ?>

					<!-- METHOD - Dropdown -->
					<?php
					$elements = array(
						$vik->initOptionElement('', '', true)
					);

					if( empty($row['coupon_str']) ) {
						$elements[] = $vik->initOptionElement(1, JText::_('VRORDDISCMETHOD1'), false, false, (!count($this->coupons)));
					} else {
						$elements[] = $vik->initOptionElement(2, JText::_('VRORDDISCMETHOD2'), false, false, (!count($this->coupons)));
						$elements[] = $vik->initOptionElement(3, JText::_('VRORDDISCMETHOD3'));
					}

					if( $row['discount_val'] == 0 ) {
						$elements[] = $vik->initOptionElement(4, JText::_('VRORDDISCMETHOD4'));
					} else {
						$elements[] = $vik->initOptionElement(5, JText::_('VRORDDISCMETHOD5'));
						$elements[] = $vik->initOptionElement(6, JText::_('VRORDDISCMETHOD6'));
					}
					?>
					<?php echo $vik->openControl(JText::_('VRMANAGETKORDDISC4').'*:'); ?>
						<?php echo $vik->dropdown('method', $elements, 'vr-method-sel', 'required'); ?>
					<?php echo $vik->closeControl(); ?>

					<!-- COUPON CODE - Dropdown -->
					<?php
					$elements = array(
						$vik->initOptionElement('', '', true)
					);
					foreach( $this->coupons as $coupon ) {
						$coupon_label = $coupon['code']." : ".($coupon['percentot'] == 1 ? $coupon['value'].'%' : cleverdine::printPriceCurrencySymb($coupon['value'], $curr_symb, $symb_pos, true));
						$elements[] = $vik->initOptionElement($coupon['id'], $coupon_label, false, false, ($coupon['code'] == $coupon_code), 'data-value="'.$coupon['value'].'" data-percentot="'.$coupon['percentot'].'"');
					}
					?>
					<?php echo $vik->openControl(JText::_('VRMANAGETKORDDISC3').'*:', 'vr-coupon-field', 'style="display:none;"'); ?>
						<?php echo $vik->dropdown('id_coupon', $elements, 'vr-coupon-sel', ''); ?>
					<?php echo $vik->closeControl(); ?>

					<!-- AMOUNT - Number -->
					<?php
					$elements = array(
						$vik->initOptionElement(1, '%', false),
						$vik->initOptionElement(2, $curr_symb, true),
					);
					?>
					<?php echo $vik->openControl(JText::_('VRMANAGETKORDDISC5').'*:'); ?>
						<input type="number" name="amount" value="0" step="any" class="required" id="vr-amount-input"/>
						<?php echo $vik->dropdown('percentot', $elements, 'vr-percentot-sel'); ?>
					<?php echo $vik->closeControl(); ?>

				</div>

				<!-- BILL CLOSED - Radio Button -->
				<?php
				$elem_yes = $vik->initRadioElement('', '', $row['bill_closed']);
				$elem_no = $vik->initRadioElement('', '', !$row['bill_closed']);
				?>
				<?php echo $vik->openControl(JText::_('VRMANAGEBILL3').':'); ?>
					<?php echo $vik->radioYesNo('bill_closed', $elem_yes, $elem_no, false); ?>
				<?php echo $vik->closeControl(); ?>
				
				<!-- ITEMS TO ADD - Dropdown -->
				<?php echo $vik->openControl(''); ?>
					<?php echo $all_items_select; ?>

					<button type="button" class="btn" id="vr-createnew-btn" onclick="createNewProduct(this);">
						<?php echo JText::_('VRCREATENEWPROD'); ?>
					</button>
				<?php echo $vik->closeControl(); ?>
				
			<?php echo $vik->closeFieldset(); ?>
		</div>
		
		<div class="span10">
			<?php echo $vik->openFieldset(JText::_('VRTKORDERCARTFIELDSET2'), 'form-horizontal'); ?>
				<div class="vrtk-order-cart-item-selected-form">
					
				</div>
			<?php echo $vik->closeFieldset(); ?>
		</div>
		
	</div>

	<div class="span4">
		<?php echo $vik->openFieldset(JText::_('VRTKORDERCARTFIELDSET3'), 'form-horizontal'); ?>
			<div class="control-group">
				<div class="vrtk-order-cart">
					<?php foreach( $row['products'] as $item ) { ?>
						<div id="vrtk-order-cart-item<?php echo $item['id_assoc']; ?>" class="vrtk-order-cart-item">
							<a href="javascript: void(0);" onClick="getFoodDetailsForm(<?php echo $item['id']; ?>, <?php echo $item['id_assoc']; ?>);">
								<div class="vrtk-order-cart-item-left">
									<span class="vrtk-order-cart-item-name"><?php echo $item['name']; ?></span>
								</div>
							</a>

							<div class="vrtk-order-cart-item-right">
								<span class="vrtk-order-cart-item-quantity">x<?php echo $item['quantity']; ?></span>
								<span class="vrtk-order-cart-item-price"><?php echo cleverdine::printPriceCurrencySymb($item['price'], $curr_symb, $symb_pos, true); ?></span>
								<span class="vrtk-order-cart-item-remove">
									<a href="javascript: void(0);" class="vrtkcartremovelink" onClick="vrRemoveCartItem(<?php echo $item['id_assoc']; ?>);"></a>
								</span>
							</div>
						</div>
					<?php } ?>
				</div>
			</div>
		<?php echo $vik->closeFieldset(); ?>
	</div>
	
	<input type="hidden" name="cid[]" value="<?php echo $row['id']; ?>"/>
	<input type="hidden" name="id" value="<?php echo $row['id']; ?>"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="option" value="com_cleverdine"/>
</form>

<script type="text/javascript">

	jQuery(document).ready(function(){

		jQuery('#vrtk-order-cart-allitem-select').select2({
			placeholder: '<?php echo addslashes(JText::_('VRTKCARTOPTION4')); ?>',
			allowClear: true,
			width: 300
		});
		
		jQuery('#vrtk-order-cart-allitem-select').on('change', function(){
			jQuery('#vr-createnew-btn').removeClass('active');

			getFoodDetailsForm( jQuery(this).val(), -1 );
		});

		// discount

		jQuery('#vr-method-sel').select2({
			minimumResultsForSearch: -1,
			placeholder: '<?php echo addslashes(JText::_('VRORDDISCMETHOD0')); ?>',
			allowClear: true,
			width: 300
		});

		jQuery('#vr-coupon-sel').select2({
			placeholder: '--',
			allowClear: true,
			width: 300
		});

		jQuery('#vr-percentot-sel').select2({
			minimumResultsForSearch: -1,
			allowClear: false,
			width: 130
		});

		jQuery('#vr-method-sel').on('change', function(){
			var val = jQuery(this).val();

			if( val == 1 || val == 2 ) {
				jQuery('.vr-coupon-field').show();
				jQuery('#vr-coupon-sel').addClass('required');
			} else {
				jQuery('.vr-coupon-field').hide();
				jQuery('#vr-coupon-sel').removeClass('required');
			}

			if( val == 3 || val == 6 ) {
				jQuery('#vr-amount-input').val(0).prop('disabled', true);
				jQuery('#vr-percentot-sel').select2('val', 2).prop('disabled', true);
			} else {
				jQuery('#vr-amount-input').prop('disabled', false);
				jQuery('#vr-percentot-sel').prop('disabled', false);
			}
		});

		jQuery('#vr-coupon-sel').on('change', function(){
			if( jQuery(this).val().length ) {
				
				var option = jQuery(this).find('option:selected');

				jQuery('#vr-amount-input').val(jQuery(option).data('value'));
				jQuery('#vr-percentot-sel').select2('val', jQuery(option).data('percentot'));
			}
		});

	});

	function getFoodDetailsForm(id_prod, id_assoc) {
		if( id_prod.length == 0 ) {
			jQuery('.vrtk-order-cart-item-selected-form').html('');
			return;
		}

		if( id_assoc > -1 || id_prod <= 0 ) {
			jQuery('#vrtk-order-cart-allitem-select').select2('val', '');

			if( id_prod > 0 ) {
				jQuery('#vr-createnew-btn').removeClass('active');
			}
		}
		
		jQuery.noConflict();
	
		var jqxhr = jQuery.ajax({
			type: "POST",
			url: "index.php?option=com_cleverdine&task=resadditem&tmpl=component",
			data: { 
				id_product: id_prod, 
				id_assoc: id_assoc
			}
		}).done(function(resp){
			var obj = jQuery.parseJSON(resp);

			if( obj[0] == 1 ) {
				jQuery('.vrtk-order-cart-item-selected-form').html(obj[1]);
				
				jQuery('.vrtk-variations-reqselect').select2({
					allowClear: false,
					width: 300
				});
			} else {
				alert(obj[1]);
			}
		}).fail(function(){
			alert('<?php echo addslashes(JText::_('VRSYSTEMCONNECTIONERR')); ?>');
		});
	}

	function createNewProduct(btn) {

		if( jQuery(btn).hasClass('active') ) {
			jQuery('.vrtk-order-cart-item-selected-form').html('');

			jQuery(btn).removeClass('active');
		} else {
			getFoodDetailsForm(0, -1);

			jQuery(btn).addClass('active');
		}

	}

	function vrPostItem() {
		if( jQuery('.vrtk-addtocart-button').hasClass('disabled') ) {
			return;
		}
		
		jQuery('.vrtk-addtocart-button').addClass('disabled');
		
		jQuery.noConflict();
		
		jQuery('input[name="task"]').val('add_item_to_res');
		
		var jqxhr = jQuery.ajax({
			type: "POST",
			url: "index.php?option=com_cleverdine&task=add_item_to_res&tmpl=component",
			data: jQuery('#adminForm').serialize()
		}).done(function(resp){
			var obj = jQuery.parseJSON(resp);

			if( obj[0] ) {
				vrCartPushItem(obj[1], obj[2], obj[3]);

				// flush form
				jQuery('.vrtk-order-cart-item-selected-form').html('<div class="vrtk-addtocart-item-success"><?php echo addslashes(JText::_('VRTKSTOCKITEMSUCCESS')); ?></div>');
				jQuery('#vrtk-order-cart-allitem-select').select2('val', '');

			} else {
				alert(obj[1]);
			}

			jQuery('.vrtk-addtocart-button').removeClass('disabled');

		}).fail(function(resp){
			alert('<?php echo addslashes(JText::_('VRSYSTEMCONNECTIONERR')); ?>');
			jQuery('.vrtk-addtocart-button').removeClass('disabled');
		});
	}

	function vrPostNewItem() {

		var ok = true;
		jQuery("#adminForm .vrtk-order-cart-item-selected-form .required:input").each(function(){
			var val = jQuery(this).val();
			if( val !== null && val.length > 0 ) {
				jQuery(this).removeClass("vrrequired");
			} else {
				jQuery(this).addClass("vrrequired");
				ok = false;
			}
		});
		
		if( !ok ) {
			return false;
		}

		if( jQuery('.vrtk-addtocart-button').hasClass('disabled') ) {
			return;
		}
		
		jQuery('.vrtk-addtocart-button').addClass('disabled');
		
		jQuery.noConflict();
		
		jQuery('input[name="task"]').val('add_hidden_item_to_res');
		
		var jqxhr = jQuery.ajax({
			type: "POST",
			url: "index.php?option=com_cleverdine&task=add_hidden_item_to_res&tmpl=component",
			data: jQuery('#adminForm').serialize()
		}).done(function(resp){
			var obj = jQuery.parseJSON(resp);

			if( obj[0] ) {
				vrCartPushItem(obj[1], obj[2], obj[3]);

				vrListPushNewItem(obj[2]);

				// flush form
				jQuery('.vrtk-order-cart-item-selected-form').html('<div class="vrtk-addtocart-item-success"><?php echo addslashes(JText::_('VRTKSTOCKITEMSUCCESS')); ?></div>');
				jQuery('#vrtk-order-cart-allitem-select').select2('val', '');

				jQuery('#vr-createnew-btn').removeClass('active');

			} else {
				alert(obj[1]);
			}

			jQuery('.vrtk-addtocart-button').removeClass('disabled');

		}).fail(function(resp){
			alert('<?php echo addslashes(JText::_('VRSYSTEMCONNECTIONERR')); ?>');
			jQuery('.vrtk-addtocart-button').removeClass('disabled');
		});

	}

	function vrCartPushItem(index, item, tcost) {
		
		var html = '<div id="vrtk-order-cart-item'+index+'" class="vrtk-order-cart-item">\n'+
				'<a href="javascript: void(0);" onClick="getFoodDetailsForm('+item.item_id+', '+index+');">\n'+
					'<div class="vrtk-order-cart-item-left">\n'+
						'<span class="vrtk-order-cart-item-name">'+item.item_name+'</span>\n'+
					'</div>\n'+
				'</a>\n'+
				'<div class="vrtk-order-cart-item-right">\n'+
					'<span class="vrtk-order-cart-item-quantity">x'+item.quantity+'</span>\n'+
					'<span class="vrtk-order-cart-item-price"><?php echo $_symb_arr[0]; ?>'+item.price.toFixed(2)+'<?php echo $_symb_arr[1]; ?></span>\n'+
					'<span class="vrtk-order-cart-item-remove">\n'+
						'<a href="javascript: void(0);" class="vrtkcartremovelink" onClick="vrRemoveCartItem('+index+');"></a>\n'+
					'</span>\n'+
				'</div>\n'+
			'</div>\n';
		
		if( jQuery('#vrtk-order-cart-item'+index).length == 0 ) {
			jQuery('.vrtk-order-cart').append(html);
		} else {
			jQuery('#vrtk-order-cart-item'+index).replaceWith(html);
		}
		
		vrCartUpdateTotalCost(tcost);
	}

	function vrListPushNewItem(item) {

		var html = '<option value="'+item.item_id+'">'+item.item_name+'</option>';

		var optgroup = jQuery('#vrtk-order-cart-allitem-select optgroup[data-key="hidden"]');

		if( optgroup.length == 0 ) {
			html = '<optgroup label="<?php echo addslashes(JText::_('VRSYSHIDDEN')); ?>" data-key="hidden">'+html+'</optgroup>';

			jQuery('#vrtk-order-cart-allitem-select').append(html);
		} else {
			optgroup.append(html);
		}

	}
	
	function vrCartUpdateTotalCost(tcost) {
		jQuery('#vrtk-total-text').val(tcost.toFixed(2));
	}

	function vrRemoveCartItem(id) {
		jQuery.noConflict();
		
		var jqxhr = jQuery.ajax({
			type: "POST",
			url: "index.php?option=com_cleverdine&task=remove_item_from_res&tmpl=component",
			data: {
				id_assoc: id, 
				id_res: <?php echo $row['id']; ?>
			}
		}).done(function(resp){
			var obj = jQuery.parseJSON(resp);

			if( obj[0] ) {
				jQuery('#vrtk-order-cart-item'+id).remove();
				vrCartUpdateTotalCost(obj[1]);
			} else {
				alert(obj[1]);
			}

		}).fail(function(resp){
			alert('<?php echo addslashes(JText::_('VRSYSTEMCONNECTIONERR')); ?>');
		});
	}

	// discount

	function toggleSearchToolsButton(btn) {

		if( jQuery(btn).hasClass('btn-primary') ) {
			jQuery('#vr-search-tools').slideUp();

			jQuery(btn).removeClass('btn-primary');
			
			jQuery('#vr-tools-caret').removeClass('fa-caret-up').addClass('fa-caret-down');

			jQuery('#vr-discount-wrapper').hide();

		} else {
			jQuery('#vr-search-tools').slideDown();

			jQuery(btn).addClass('btn-primary');

			jQuery('#vr-tools-caret').removeClass('fa-caret-down').addClass('fa-caret-up');

			jQuery('#vr-discount-wrapper').show();

		}

	}

</script>
