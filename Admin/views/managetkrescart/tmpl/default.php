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

$curr_symb = cleverdine::getCurrencySymb(true);
$symb_pos = cleverdine::getCurrencySymbPosition(true);
$_symb_arr = array( '', '' );
if( $symb_pos == 1 ) {
	$_symb_arr[1] = ' '.$curr_symb;
} else {
	$_symb_arr[0] = $curr_symb.' ';
}

$all_items_select = '<select id="vrtk-order-cart-allitem-select">';
$all_items_select .= '<option></option>';
foreach( $this->allItems as $menu ) {
	$all_items_select .= '<optgroup label="'.$menu['menu_title'].'">';
	foreach( $menu['items'] as $item ) {
		$all_items_select .= '<option value="'.$item['id'].'">'.$item['name'].'</option>';
	}
	$all_items_select .= '</optgroup>';
}
$all_items_select .= '</select>';

$vik = new VikApplication(VersionListener::getID());

$amounts = array();
$amounts['total_cost'] = $this->order['total_to_pay'];
$amounts['taxes'] = $this->order['taxes'];
$amounts['total_net'] = $amounts['total_cost']-$amounts['taxes']-$this->order['delivery_charge']-$this->order['pay_charge'];

?>

<form name="adminForm" action="index.php" method="post" enctype="multipart/form-data" id="adminForm">
	
	<div class="span8">
		
		<!-- empty div to align span8 boxes vertically -->
		<div></div>
		
		<div class="span10">
			<?php echo $vik->openFieldset(JText::_('VRTKORDERCARTFIELDSET1'), 'form-horizontal'); ?>
				
				<!-- TOTAL NET PRICE - Text -->
				<?php echo $vik->openControl(JText::_('VRTKCARTOPTION1').':'); ?>
					<input type="text" readonly value="<?php echo number_format($amounts['total_net'], 2); ?>" id="vrtk-net-text" style="text-align: right;"/>
					&nbsp;<?php echo $curr_symb; ?>
				<?php echo $vik->closeControl(); ?>
				
				<!-- TAXES - Text -->
				<?php echo $vik->openControl(JText::_('VRTKCARTOPTION2').':'); ?>
					<input type="text" readonly value="<?php echo number_format($amounts['taxes'], 2); ?>" id="vrtk-taxes-text" style="text-align: right;"/>
					&nbsp;<?php echo $curr_symb; ?>
				<?php echo $vik->closeControl(); ?>

				<?php if( $this->order['delivery_charge'] != 0 ) { ?>
					<!-- DELIVERY CHARGE - Text -->
					<?php echo $vik->openControl(JText::_('VRMANAGETKRES31').':'); ?>
						<input type="text" readonly value="<?php echo number_format($this->order['delivery_charge'], 2); ?>" style="text-align: right;"/>
						&nbsp;<?php echo $curr_symb; ?>
					<?php echo $vik->closeControl(); ?>
				<?php } ?>

				<?php if( $this->order['pay_charge'] != 0 ) { ?>
					<!-- PAYMENT CHARGE - Text -->
					<?php echo $vik->openControl(JText::_('VRMANAGETKRES30').':'); ?>
						<input type="text" readonly value="<?php echo number_format($this->order['pay_charge'], 2); ?>" style="text-align: right;"/>
						&nbsp;<?php echo $curr_symb; ?>
					<?php echo $vik->closeControl(); ?>
				<?php } ?>
				
				<!-- TOTAL COST - Text -->
				<?php echo $vik->openControl(JText::_('VRTKCARTOPTION3').':'); ?>
					<input type="text" readonly value="<?php echo number_format($amounts['total_cost'], 2); ?>" id="vrtk-total-text" style="text-align: right;"/>
					&nbsp;<?php echo $curr_symb; ?>
				<?php echo $vik->closeControl(); ?>
				
				<!-- ITEMS TO ADD - Dropdown -->
				<?php echo $vik->openControl(''); ?>
					<?php echo $all_items_select; ?>
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
					<?php foreach( $this->order['items'] as $item ) { ?>
						<div id="vrtk-order-cart-item<?php echo $item['id_assoc']; ?>" class="vrtk-order-cart-item">
							<a href="javascript: void(0);" onClick="getFoodDetailsForm(<?php echo $item['id']; ?>, <?php echo $item['id_assoc']; ?>);">
								<div class="vrtk-order-cart-item-left">
									<span class="vrtk-order-cart-item-name"><?php echo $item['name']; ?></span>
									<span class="vrtk-order-cart-item-varname"><?php echo $item['option_name']; ?></span>
								</div>
							</a>

							<div class="vrtk-order-cart-item-right">
								<span class="vrtk-order-cart-item-quantity">x<?php echo $item['quantity']; ?></span>
								<span class="vrtk-order-cart-item-price"><?php echo cleverdine::printPriceCurrencySymb($item['price'], $curr_symb, $symb_pos, true); ?></span>
								<span class="vrtk-order-cart-item-remove">
									<a href="javascript: void(0);" class="vrtkcartremovelink" onClick="vrRemoveCartItem(<?php echo $item["id_assoc"]; ?>);"></a>
								</span>
							</div>
						</div>
					<?php } ?>
				</div>
			</div>
		<?php echo $vik->closeFieldset(); ?>
	</div>
	
	<input type="hidden" name="id" value="<?php echo $this->idOrder; ?>"/>
	<input type="hidden" name="cid[]" value="<?php echo $this->idOrder; ?>"/>
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
			getFoodDetailsForm( jQuery(this).val(), -1 );
		});
	});
	
	function getFoodDetailsForm(id_prod, id_assoc) {
		if( id_prod.length == 0 ) {
			jQuery('.vrtk-order-cart-item-selected-form').html('');
			return;
		}

		if( id_assoc > -1 ) {
			jQuery('#vrtk-order-cart-allitem-select').select2('val', '');
		}
		
		jQuery.noConflict();
	
		var jqxhr = jQuery.ajax({
			type: "POST",
			url: "index.php",
			data: { option: "com_cleverdine", task: "tkadditem", id_product: id_prod, id_assoc: id_assoc, tmpl: "component" }
		}).done(function(resp){
			var obj = jQuery.parseJSON(resp);
			if( obj[0] == 1 ) {
				jQuery('.vrtk-order-cart-item-selected-form').html(obj[1]);
				
				jQuery('.vrtk-toppings-select').select2({
					placeholder: '--',
					allowClear: true,
					width: 300
				});
				
				jQuery('.vrtk-toppings-reqselect, .vrtk-variations-reqselect').select2({
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
	
	function vrPostTakeAwayItem() {
		if( jQuery('.vrtk-addtocart-button').hasClass('disabled') ) {
			return;
		}
		
		jQuery('.vrtk-addtocart-button').addClass('disabled');
		
		jQuery.noConflict();
		
		jQuery('input[name="task"]').val('add_item_to_cart');
		
		var jqxhr = jQuery.ajax({
			type: "POST",
			url: "index.php?option=com_cleverdine&task=add_item_to_cart&tmpl=component",
			data: jQuery('#adminForm').serialize()
		}).done(function(resp){
			var obj = jQuery.parseJSON(resp);
			if( obj[0] ) {
				vrCartPushItem(obj[1], obj[2], obj[3], obj[4], obj[5]);

				// flush form
				jQuery('.vrtk-order-cart-item-selected-form').html('<div class="vrtk-addtocart-item-success"><?php echo addslashes(JText::_('VRTKSTOCKITEMSUCCESS')); ?></div>');
				jQuery('#vrtk-order-cart-allitem-select').select2('val', '');

				if( obj[6] !== null && obj[6].status == 2 ) {
					alert(obj[6].text);
				}
			} else {
				alert(obj[1]);
			}
			jQuery('.vrtk-addtocart-button').removeClass('disabled');
		}).fail(function(resp){
			alert('<?php echo addslashes(JText::_('VRSYSTEMCONNECTIONERR')); ?>');
			jQuery('.vrtk-addtocart-button').removeClass('disabled');
		});
	}
	
	function vrCartPushItem(index, item, tcost, tnet, taxes) {
		
		var html = '<div id="vrtk-order-cart-item'+index+'" class="vrtk-order-cart-item">\n'+
				'<a href="javascript: void(0);" onClick="getFoodDetailsForm('+item.item_id+', '+index+');">\n'+
					'<div class="vrtk-order-cart-item-left">\n'+
						'<span class="vrtk-order-cart-item-name">'+item.item_name+'</span>\n'+
						'<span class="vrtk-order-cart-item-varname">'+item.var_name+'</span>\n'+
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
		
		vrCartUpdateTotalCost(tcost, tnet, taxes);
	}
	
	function vrCartUpdateTotalCost(tcost, tnet, taxes) {
		jQuery('#vrtk-net-text').val(tnet.toFixed(2));
		jQuery('#vrtk-taxes-text').val(taxes.toFixed(2));
		jQuery('#vrtk-total-text').val(tcost.toFixed(2));
	}
	
	function vrRemoveCartItem(id) {
		jQuery.noConflict();
		
		var jqxhr = jQuery.ajax({
			type: "POST",
			url: "index.php?option=com_cleverdine&task=remove_item_from_cart&tmpl=component",
			data: {id_assoc: id, id_res: <?php echo $this->idOrder; ?>}
		}).done(function(resp){
			var obj = jQuery.parseJSON(resp);
			if( obj[0] ) {
				jQuery('#vrtk-order-cart-item'+id).remove();
				vrCartUpdateTotalCost(obj[1], obj[2], obj[3]);
			} else {
				alert(obj[1]);
			}
		}).fail(function(resp){
			alert('<?php echo addslashes(JText::_('VRSYSTEMCONNECTIONERR')); ?>');
		});
	}
	
</script>