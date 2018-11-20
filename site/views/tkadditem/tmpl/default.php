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

$curr_symb = cleverdine::getCurrencySymb();
$symb_pos = cleverdine::getCurrencySymbPosition();
$_symb_arr = array( '', '' );
if( $symb_pos == 1 ) {
	$_symb_arr[1] = ' '.$curr_symb;
} else {
	$_symb_arr[0] = $curr_symb.' ';
}

$toppings_map_costs = array();

?>

<?php

// start catching the html code
ob_start();

?>

<form action="index.php?option=com_cleverdine&task=add_to_cart&tmpl=component" method="POST" id="vrtk-additem-form">

	<div class="vrtk-additem-container">
		
		<!-- ITEM QUANTITY -->
		<div class="vrtk-additem-quantity-box">
			<span class="quantity-label"><?php echo JText::_('VRTKADDQUANTITY'); ?>:</span>
			<span class="quantity-actions">
				<a href="javascript: void(0);" onClick="vrAddItemQuantity(-1);" class="vrtk-action-remove <?php echo ($this->item['quantity'] <= 1 ? 'disabled' : ''); ?>">
					<img src="<?php echo JUri::root().'components/com_cleverdine/assets/css/images/less_ico.png'; ?>"/>
				</a>
				<input type="text" name="quantity" value="<?php echo $this->item['quantity']; ?>" size="4" id="vrtk-quantity-input" onkeypress="return event.charCode >= 48 && event.charCode <= 57" onchange="vrUpdateQuantityActions();"/>
				<a href="javascript: void(0);" onClick="vrAddItemQuantity(1);" class="vrtk-action-add">
					<img src="<?php echo JUri::root().'components/com_cleverdine/assets/css/images/plus_ico.png'; ?>"/>
				</a>
			</span>
		</div>
		
		<div class="vrtk-additem-middle">
		
			<!-- ADDITIONAL NOTES -->
			<div class="vrtk-additem-notes-box">
				<div class="vrtk-additem-notes-title vr-disable-selection">
					<?php echo JText::_('VRTKADDREQUEST'); ?>
				</div>
				<div class="vrtk-additem-notes-field" style="display: none;">
					<div class="vrtk-additem-notes-info">
						<?php echo JText::_('VRTKADDREQUESTSUBT'); ?>
					</div>
					<textarea name="notes" maxlength="256"><?php echo $this->item['notes']; ?></textarea>
				</div>
			</div>
			
			<!-- TOTAL COST -->
			<div class="vrtk-additem-tcost-box">
				<?php echo cleverdine::printPriceCurrencySymb($this->item['price'], $curr_symb, $symb_pos); ?>
			</div>
		
		</div>
		
		<!-- TOPPINGS GROUPS CONTAINER -->
		
		<div class="vrtk-additem-groups-loading" style="display: none;text-align: center;">
			<img id="img-loading" src="<?php echo JUri::root(); ?>components/com_cleverdine/assets/css/images/hor-loader.gif"/>
		</div>
		
		<div class="vrtk-additem-groups-container" style="visibility: hidden;">
			
			<?php foreach( $this->groups as $group ) { 
				$group['title'] = cleverdine::translate($group['id'], $group, $this->groupsTranslations, 'title', 'name');
				?>
				
				<div class="vrtk-additem-group-box" id="vrtkgroup<?php echo $group['id']; ?>" data-multiple="<?php echo $group['multiple']; ?>" data-min-toppings="<?php echo $group['min_toppings']; ?>" data-max-toppings="<?php echo $group['max_toppings']; ?>">
					<div class="vrtk-additem-group-title">
						<?php echo $group['title']; ?>
					</div>
					
					<div class="vrtk-additem-group-fields">
						<?php foreach( $group['toppings'] as $topping ) { 
							$topping['name'] = cleverdine::translate($topping['id'], $topping, $this->toppingsTranslations, 'name', 'name');
							?>
							<div class="vrtk-additem-group-topping vrtk-group-<?php echo ($group['multiple'] ? 'multiple' : 'single'); ?>">
								
								<?php if( $group['multiple'] ) { ?>
									<span class="vrtk-additem-topping-field">
										<input type="checkbox" value="<?php echo $topping['assoc_id']; ?>" id="vrtk-cb<?php echo $topping['assoc_id']; ?>" name="topping[<?php echo $group['id']; ?>][]" 
										class="vre-topping-checkbox" data-price="<?php echo $topping['rate']; ?>" data-group="<?php echo $group['id']; ?>" <?php echo ($topping['checked'] ? 'checked="checked"' : ''); ?>/>
										<label for="vrtk-cb<?php echo $topping['assoc_id']; ?>"><?php echo $topping['name']; ?></label>
									</span>
									<?php if( $topping['rate'] != 0 ) { ?>
										<span class="vrtk-additem-topping-price">
											<?php echo cleverdine::printPriceCurrencySymb($topping['rate'], $curr_symb, $symb_pos); ?>
										</span>
									<?php } ?>
								<?php } else { ?>
									<span class="vrtk-additem-topping-field">
										<input type="radio" value="<?php echo $topping['assoc_id']; ?>" id="vrtk-rb<?php echo $topping['assoc_id']; ?>" name="topping[<?php echo $group['id']; ?>][]" 
										class="vre-topping-radio" data-price="<?php echo $topping['rate']; ?>" data-group="<?php echo $group['id']; ?>" <?php echo ($topping['checked'] ? 'checked="checked"' : ''); ?>/>
										<label for="vrtk-rb<?php echo $topping['assoc_id']; ?>"><?php echo $topping['name']; ?></label>
									</span>
									<?php if( $topping['rate'] != 0 ) { ?>
										<span class="vrtk-additem-topping-price">
											<?php echo cleverdine::printPriceCurrencySymb($topping['rate'], $curr_symb, $symb_pos); ?>
										</span>
									<?php } ?>
									<?php
									if( $topping['checked'] ) {
										$toppings_map_costs[$group['id']] = $topping['rate']; 
									}
									?>
								<?php } ?>
							</div>
						<?php } ?>
					</div>
				</div>
				
			<?php } ?>
			
		</div>
		
		<div class="vrtk-additem-bottom">
			
			<!-- ADD TO CART BUTTON -->
			<div class="vrtk-additem-success-button">
				<button type="button" id="vrtk-addtocart-button">
					<?php echo JText::_($this->itemCartIndex >= 0 ? "VRSAVE" : "VRTKADDOKBUTTON"); ?>
				</button>
			</div>
			
			<!-- CANCEL BUTTON -->
			<div class="vrtk-additem-cancel-button">
				<button type="button" id="vrtk-cartcancel-button">
					<?php echo JText::_("VRTKADDCANCELBUTTON"); ?>
				</button>
			</div>
			
		</div>
		
	</div>
	
	<input type="hidden" name="item_index" value="<?php echo $this->itemCartIndex; ?>"/>
	<input type="hidden" name="id_entry" value="<?php echo $this->item['id']; ?>" />
	<input type="hidden" name="id_option" value="<?php echo $this->item['oid']; ?>" />

</form>

<script>

	setTimeout(function(){
		if( jQuery('.vrtk-additem-groups-container').css('visibility') == "hidden" ) {
			jQuery('.vrtk-additem-groups-loading').show();
		}
	}, 750);
	
	// ITEM QUANTITY
	
	function vrAddItemQuantity(units) {
		var q = parseInt(jQuery('#vrtk-quantity-input').val());
		
		if( q+units > 0 ) {
			jQuery('#vrtk-quantity-input').val((q+units));
		}
		
		vrUpdateQuantityActions();
	}
	
	function vrUpdateQuantityActions() {
		var q = parseInt(jQuery('#vrtk-quantity-input').val());
		
		if( q > 1 ) {
			jQuery('.vrtk-action-remove').removeClass('disabled');
		} else {
			jQuery('.vrtk-action-remove').addClass('disabled');
		}
	}
	
	// ADDITIONAL NOTES
	
	jQuery(document).ready(function(){
		jQuery('.vrtk-additem-notes-title').on('click', function(){
			if( !jQuery('.vrtk-additem-notes-field').is(':visible') ) {
				jQuery('.vrtk-additem-notes-field').slideDown();
			} else {
				jQuery('.vrtk-additem-notes-field').slideUp();
			}
		});
	});
	
	// GROUPS 
	
	jQuery(document).ready(function(){
		var bound = jQuery('.vrtk-additem-groups-container').offset().left+jQuery('.vrtk-additem-groups-container').width()/2;
		var _float, _pos;
		jQuery('.vrtk-additem-group-box').each(function(){
			
			var _float = jQuery(this).css('float');
			var _pos = jQuery(this).offset().left+jQuery(this).width();
			if( _pos < bound && _float == 'right' ) {
				jQuery(this).css('float', 'left');
			} else if( _pos >= bound && _float == 'left' ) {
				jQuery(this).css('float', 'right');   
			}
			
		});
		
		jQuery('.vrtk-additem-groups-loading').remove();
		jQuery('.vrtk-additem-groups-container').css('visibility', 'visible');
	});
	
	// TOPPINGS
	
	var ENTRY_TOTAL_COST = <?php echo ($this->item['price']); ?>;
	var TOPPINGS_MAP_COSTS = <?php echo json_encode($toppings_map_costs); ?>;
	
	jQuery(document).ready(function(){
		jQuery('.vrtk-additem-group-box').each(function(){
			if( jQuery(this).attr('data-multiple') == "1" ) {
				if( jQuery(this).find('input:checked').length == parseInt(jQuery(this).attr('data-max-toppings')) ) {
					jQuery(this).find('input:not(:checked)').prop('disabled', true);
				} else {
					jQuery(this).find('input:not(:checked)').prop('disabled', false);
				}
			}
		});
	});
	
	jQuery('.vre-topping-checkbox').on('change', function(){
		var p = parseFloat(jQuery(this).attr('data-price'));
		if( jQuery(this).is(':checked') ) {
			vrIncreaseEntryPrice(p);
		} else {
			vrIncreaseEntryPrice(p*-1);
		}
		
		if( jQuery('#vrtkgroup'+jQuery(this).attr('data-group')).find('input:checked').length == parseInt(jQuery('#vrtkgroup'+jQuery(this).attr('data-group')).attr('data-max-toppings')) ) {
			jQuery('#vrtkgroup'+jQuery(this).attr('data-group')).find('input:not(:checked)').prop('disabled', true);
		} else {
			jQuery('#vrtkgroup'+jQuery(this).attr('data-group')).find('input:not(:checked)').prop('disabled', false);
		}
	});
	
	jQuery('.vre-topping-radio').on('change', function(e){
		var p = parseFloat(jQuery(this).attr('data-price'));
		var old_p = TOPPINGS_MAP_COSTS[jQuery(this).attr('data-group')];
		TOPPINGS_MAP_COSTS[jQuery(this).attr('data-group')] = p;
		
		if( old_p !== undefined ) {
			p -= parseFloat(old_p);
		}
		
		vrIncreaseEntryPrice(p);
	});
	
	function vrIncreaseEntryPrice(p) {
		ENTRY_TOTAL_COST += p;
		jQuery('.vrtk-additem-tcost-box').text('<?php echo $_symb_arr[0]; ?>'+ENTRY_TOTAL_COST.toFixed(2)+'<?php echo $_symb_arr[1]; ?>');
	}
	
	function vrAllGroupsChecked() {
		var min_toppings, sel_toppings;
		var ok = true;
		jQuery('.vrtk-additem-group-box').each(function(){
			min_toppings = parseInt(jQuery(this).attr('data-min-toppings'));
			sel_toppings = jQuery(this).find('input:checked').length;
			if( min_toppings > 0 && sel_toppings < min_toppings ) {
				ok = false;
				jQuery(this).addClass('vrrequiredfield');
			} else {
				jQuery(this).removeClass('vrrequiredfield');
			}
		});
		
		return ok;
	}
	
	// MODAL BUTTONS
	
	jQuery('#vrtk-addtocart-button').on('click', function(){
		if( !vrAllGroupsChecked() ) {
			return false;
		}
		
		vrPostTakeAwayItem();
	});
	
	jQuery('#vrtk-cartcancel-button').on('click', function(){
		vrCloseOverlay('vrnewitemoverlay');
	});
	
	function vrPostTakeAwayItem() {
		
		jQuery.noConflict();
		
		var jqxhr = jQuery.ajax({
			type: "POST",
			url: "<?php echo JRoute::_('index.php?option=com_cleverdine&task=add_to_cart&tmpl=component'); ?>",
			data: jQuery('#vrtk-additem-form').serialize()
		}).done(function(resp){
			var obj = jQuery.parseJSON(resp);
			if( obj[0] ) {
				if( vrIsCartPublished() ) {
					vrCartRefreshItems(obj[1], obj[2], obj[3], obj[4]);
				}
				
				vrCloseOverlay('vrnewitemoverlay');

				if( obj[5] ) {
					vrDispatchMessage(obj[5]);
				}

			} else {
				alert(obj[1]);
			}
		}).fail(function(resp){
			alert('<?php echo addslashes(JText::_('VRTKADDITEMERR2')); ?>');
		});
	}
	
</script>

<?php

// get html caught
$contents = ob_get_contents();
// stop catching
ob_end_clean();

echo json_encode(array($contents));

// the exit at the end of this file may cause an error on the html encoding
// if you are facing this kind of issue, try to comment the line below
exit;

?> 