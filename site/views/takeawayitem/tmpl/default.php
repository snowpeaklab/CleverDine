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

$item = $this->item;

$curr_symb = cleverdine::getCurrencySymb();
$symb_pos = cleverdine::getCurrencySymbPosition();
$_symb_arr = array( '', '' );
if( $symb_pos == 1 ) {
	$_symb_arr[1] = ' '.$curr_symb;
} else {
	$_symb_arr[0] = $curr_symb.' ';
}

$date_format = cleverdine::getDateFormat();

// is menu active
$is_date_allowed 	= cleverdine::isTakeAwayDateAllowed();
$is_live_orders 	= ($is_date_allowed ? false : cleverdine::isTakeAwayLiveOrders());
$is_currently_avail = (!$is_live_orders ? true : cleverdine::isTakeAwayCurrentlyAvailable());

$menu_active = $is_currently_avail && $this->availableTakeawayMenus !== false && ( count($this->availableTakeawayMenus) == 0 || in_array($item['id_menu'], $this->availableTakeawayMenus) );
//

$item_total_cost = $item['price'];

// check global discount
$is_discounted = DealsHandler::isProductInDeals(array(
	"id_product" => $item['id'],
	"id_option" => -1,
	"quantity" => 1
), $this->discountDeals);

if( $is_discounted !== false ) {
	if( $this->discountDeals[$is_discounted]['percentot'] == 1 ) {
		$item_total_cost -= $item_total_cost*$this->discountDeals[$is_discounted]['amount']/100.0;
	} else {
		$item_total_cost -= $this->discountDeals[$is_discounted]['amount'];
	}
}
//

$variations_cost_map = array(
	0 => $item_total_cost
);
$variations_curr_id = 0;

$toppings_cost_map = array();
$toppings_curr_id = array();

$toppings_constraints = array();

$itemid = JFactory::getApplication()->input->get('Itemid', 0, 'uint');

?>

<div class="vrtk-itemdet-category">

	<a href="<?php echo JRoute::_('index.php?option=com_cleverdine&view=takeaway'); ?>">
		<?php echo JText::_('VRTAKEAWAYALLMENUS'); ?>
	</a>
	<span class="arrow-separator">></span>
	<a href="<?php echo JRoute::_('index.php?option=com_cleverdine&view=takeaway&takeaway_menu='.$item['id_menu']); ?>">
		<?php echo $item['menu_title']; ?>
	</a>

</div>

<form action="<?php echo JRoute::_('index.php?option=com_cleverdine&view=takeawayitem&takeaway_entry='.$this->request->idEntry.'&Itemid='.$itemid); ?>" method="POST" name="vrtkitemform" id="vrtkitemform">

	<div class="vrtk-itemdet-page">
			
		<!-- Product Wrapper -->
		<div class="vrtk-itemdet-product">
			
			<!-- Product Head -->
			<div class="vrtk-itemdet-prod-head">

				<!-- Title -->
				<div class="tk-title">
					<h2><?php echo $item['name']; ?></h2>
					<?php if( !$menu_active ) { ?>
						<div class="tk-subtitle-notactive">
							<?php if( $is_currently_avail ) {
								// menu is not available
								echo JText::_('VRTKMENUNOTAVAILABLE'); 
							} else {
								// restaurant is closed
								echo JText::_('VRTKMENUNOTAVAILABLE2'); 
							} ?>
						</div>
					<?php } ?> 
				</div>

				<!-- Attributes -->
				<?php if( count($item['attributes']) > 0 ) { ?>
					<div class="tk-attributes">
						<?php foreach( $item['attributes'] as $attr ) { 
							if( !empty($this->allAttributes[$attr]['icon']) ) {
								// attribute published
								?><img src="<?php echo JUri::root().'components/com_cleverdine/assets/media/'.$this->allAttributes[$attr]['icon']; ?>"/><?php 
							}
						} ?>
					</div>
				<?php } ?>

			</div>

			<!-- Product Body -->
			<div class="vrtk-itemdet-prod-body">

				<!-- Left Side -->
				<div class="tk-left">

					<!-- Image -->
					<?php if( strlen($item['image']) > 0 && file_exists(JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_cleverdine'.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.$item['image']) ) { ?>
						<div class="tk-image">
							<a href="javascript: void(0);" class="vremodal" onClick="vreOpenModalImage('<?php echo JUri::root().'components/com_cleverdine/assets/media/'.$item['image']; ?>');">
								<img src="<?php echo JUri::root().'components/com_cleverdine/assets/media/'.$item['image']; ?>"/>
							</a>
						</div>
					<?php } ?>

					<!-- Variations -->
					<?php if( count($item['options']) ) { ?>
						<div class="tk-variations">

							<div class="tk-label" id="vrtkvarlabel"><?php echo JText::_('VRTKCHOOSEVAR'); ?>*:</div>
							
							<div class="vre-tinyselect-wrapper">
								<select name="id_option" class="vre-tinyselect" id="vrtk-vars-select">
									<option value="0" data-price="<?php echo $variations_cost_map[0]; ?>"><?php echo JText::_('VRTKPLEASECHOOSEOPT'); ?></option>
									<?php foreach( $item['options'] as $var ) { 

										// check variation discount
										$is_discounted = DealsHandler::isProductInDeals(array(
											"id_product" => $item['id'],
											"id_option" => $var['id'],
											"quantity" => 1
										), $this->discountDeals);

										$price = $item['price']+$var['price'];

										if( $is_discounted !== false ) {
											if( $this->discountDeals[$is_discounted]['percentot'] == 1 ) {
												$price -= $price*$this->discountDeals[$is_discounted]['amount']/100.0;
											} else {
												$price -= $this->discountDeals[$is_discounted]['amount'];
											}
										}
										//

										$selected = '';
										if( $var['id'] == $this->request->idOption ) {
											$item_total_cost += $var['price'];
											$selected = 'selected="selected"';
											$variations_curr_id = $var['id'];
										}

										$variations_cost_map[$var['id']] = $price;
										?>
										<option value="<?php echo $var['id']; ?>" <?php echo $selected; ?> data-price="<?php echo $price; ?>">
											<?php echo $var['name'].' '.cleverdine::printPriceCurrencySymb($price, $curr_symb, $symb_pos); ?>
										</option>
									<?php } ?>
								</select>
							</div>

						</div>
					<?php } ?>

					<!-- Toppings Groups -->
					<div class="tk-toppings-groups">

						<?php foreach( $item['toppings_groups'] as $group ) { ?>

							<div class="tk-topping-wrapper">

								<div class="tk-label vrtklabel<?php echo $group['id']; ?>"><?php echo $group['title'].($group['min_toppings'] > 0 ? '*' : ''); ?>:</div>
								
								<?php if( $group['multiple'] ) { ?>

									<div class="tk-topping-fields-cont">

										<?php foreach( $group['toppings'] as $topping ) { 
											$checked = '';
											if( !empty($this->request->toppings[$group['id']]) && in_array($topping['assoc_id'], $this->request->toppings[$group['id']]) ) {
												$checked = 'checked="checked"';
												$item_total_cost += $topping['rate'];
											}

											if( empty($toppings_constraints[$group['id']]) ) {
													$toppings_constraints[$group['id']] = array(
														'min' => $group['min_toppings'],
														'max' => $group['max_toppings']
													);
												}
											?>

											<div class="tk-topping-field">

												<span class="tk-topping-checkbox">
													<input type="checkbox" value="<?php echo $topping['assoc_id']; ?>" id="vrtkitem-cb<?php echo $topping['assoc_id']; ?>" name="topping[<?php echo $group['id']; ?>][]" <?php echo $checked; ?> 
														data-price="<?php echo $topping['rate']; ?>" data-group="<?php echo $group['id']; ?>" class="vrtk-topping-checkbox<?php echo $group['id']; ?>"/>
													<label for="vrtkitem-cb<?php echo $topping['assoc_id']; ?>"><?php echo $topping['name']; ?></label>
												</span>
												<?php if( $topping['rate'] != 0 ) { ?>
													<span class="tk-topping-rate">
														<?php echo cleverdine::printPriceCurrencySymb($topping['rate'], $curr_symb, $symb_pos); ?>
													</span>
												<?php } ?>

											</div>

										<?php } ?>

									</div>

								<?php } else { ?>

									<div class="vre-tinyselect-wrapper">
										<select name="topping[<?php echo $group['id']; ?>][]" class="vre-tinyselect" data-group="<?php echo $group['id']; ?>">
											<option value=""><?php echo JText::_('VRTKPLEASECHOOSEOPT'); ?></option>
											<?php foreach( $group['toppings'] as $topping ) { 
												$selected = '';

												if( empty($toppings_cost_map[$group['id']]) ) {
													$toppings_cost_map[$group['id']] = array();
													$toppings_curr_id[$group['id']] = -1;
												}

												$toppings_cost_map[$group['id']][$topping['assoc_id']] = $topping['rate'];

												if( !empty($this->request->toppings[$group['id']]) && $topping['assoc_id'] == $this->request->toppings[$group['id']][0] ) {
													$selected = 'selected="selected"';

													$toppings_curr_id[$group['id']] = $topping['assoc_id'];
													$item_total_cost += $topping['rate'];
												}
												?>
													
												<option value="<?php echo $topping['assoc_id']; ?>" <?php echo $selected; ?> data-price="<?php echo $topping['rate']; ?>">
													<?php echo $topping['name'].($topping['rate'] != 0 ? ' '.cleverdine::printPriceCurrencySymb($topping['rate'], $curr_symb, $symb_pos) : ''); ?>
												</option>
												
											<?php } ?>
										</select>
									</div>

								<?php } ?>

							</div>
							
						<?php } ?>
					</div>

				</div>

				<!-- Right Side -->
				<div class="tk-right">

					<!-- Cart Info : price and quantity -->
					<div class="tk-cart-summary">

						<div class="tk-cart-summary-inner">
						
							<!-- Price -->
							<div class="tk-price" id="vrtk-price-box">
								<?php echo cleverdine::printPriceCurrencySymb($item['price'], $curr_symb, $symb_pos); ?>
							</div>

							<!-- Quantity and Add button -->
							<div class="tk-add-cart">
								<input type="text" name="quantity" value="<?php echo $this->request->quantity; ?>" size="3" onkeypress="return event.charCode >= 48 && event.charCode <= 57" maxlength="3"/>
								<button type="button" onClick="vrInsertTakeAwayItem();" id="vrtk-item-addbutton" <?php echo ($menu_active ? '' : 'disabled="disabled"'); ?>>
									<?php echo JText::_('VRTKADDOKBUTTON'); ?>
								</button>
							</div>

						</div>

						<div class="tk-cart-message" id="vrtk-cart-msg" style="display: none;"></div>

						<!-- Order Now -->
						<div class="tk-ordernow" id="vrtk-ordernow-box" style="<?php echo ($this->cart->getCartRealLength() ? '' : 'display:none;'); ?>">
							<a href="<?php echo JRoute::_('index.php?option=com_cleverdine&task=takeawayconfirm'); ?>">
								<?php echo JText::_('VRTAKEAWAYORDERBUTTON'); ?>
							</a>
						</div>

					</div>

					<!-- Description -->
					<?php if( strlen($item['description']) ) { ?>
						<div class="tk-description">
							<?php echo $item['description']; ?>
						</div>
					<?php } ?>

					<!-- Special Notes -->
					<div class="tk-special-notes">
						
						<div class="tk-notes-title vr-disable-selection">
							<?php echo JText::_('VRTKADDREQUEST'); ?>
						</div>
						<div class="tk-notes-field">
							<div class="tk-notes-info">
								<?php echo JText::_('VRTKADDREQUESTSUBT'); ?>
							</div>
							<textarea name="notes" maxlength="256"><?php echo $this->request->notes; ?></textarea>
						</div>

					</div>

				</div>

			</div>

		</div>
		
	</div>

	<input type="hidden" name="option" value="com_cleverdine"/>
	<input type="hidden" name="view" value="takeawayitem"/>
	<input type="hidden" name="id_entry" value="<?php echo $this->request->idEntry; ?>"/>
	<input type="hidden" name="item_index" value="-1"/>

</form>

<!-- delimiter for take-away cart -->
<div class="vrtkgotopaydiv">&nbsp;</div>

<!-- REVIEWS -->
<?php if( $this->reviews !== false ) { ?>
	<div class="vr-reviews-quickwrapper">

		<h3><?php echo JText::_('VRREVIEWSTITLE'); ?></h3>

		<?php if( $this->reviewsStats !== null ) { ?>
			<div class="rv-reviews-quickstats">
				<div class="rv-top">
					<div class="rv-average-stars">
						<?php for( $i = 1; $i <= $this->reviewsStats->halfRating; $i++ ) {
							?><img src="<?php echo JUri::root().'components/com_cleverdine/assets/css/images/rating-star.png'; ?>"/><?php
						}
						if( round($this->reviewsStats->halfRating) != $this->reviewsStats->halfRating ) {
							?><img src="<?php echo JUri::root().'components/com_cleverdine/assets/css/images/rating-star-middle.png'; ?>"/><?php
						}
						for( $i = round($this->reviewsStats->halfRating)+1; $i <= 5; $i++ ) {
							?><img src="<?php echo JUri::root().'components/com_cleverdine/assets/css/images/rating-star-no.png'; ?>"/><?php
						} ?>
					</div>
					<div class="rv-count-reviews">
						<?php echo JText::sprintf('VRREVIEWSCOUNT', $this->reviewsStats->count); ?>
					</div>
					<?php if( cleverdine::canLeaveTakeAwayReview($item['id']) || !cleverdine::isAlreadyTakeAwayReviewed($item['id']) ) { ?>
						<div class="rv-submit-review">
							<a href="<?php echo JRoute::_('index.php?option=com_cleverdine&view=revslist&id_tk_prod='.$item['id'].'&submit_rev=1'); ?>" class="vr-review-btn">
								<?php echo JText::_('VRREVIEWLEAVEBUTTON'); ?>
							</a>
						</div>
					<?php } ?>
				</div>

				<div class="rv-average-ratings">
					<?php echo JText::sprintf(
						'VRREVIEWSAVG', 
						floatval(number_format($this->reviewsStats->rating, 2))+0
					); ?>
				</div>

				<?php if( $this->reviewsStats->count > 0 ) { ?>
					<div class="rv-see-all">
						<a href="<?php echo JRoute::_('index.php?option=com_cleverdine&view=revslist&id_tk_prod='.$item['id']); ?>" class="vr-review-btn">
							<?php echo JText::sprintf('VRREVIEWSEEALLBUTTON', $this->reviewsStats->count); ?>
						</a>
					</div>
				<?php } ?>
			</div>
		<?php } ?>

		<div class="vr-reviews-quicklist">

			<?php if( !count($this->reviews) ) { ?>
				<div class="no-review"><?php echo JText::_('VRREVIEWSNOLEFT'); ?></div>
			<?php } else { 

				foreach( $this->reviews as $review ) { ?>

					<div class="review-block">

						<div class="rv-top">

							<div class="rv-head-up">
								<div class="rv-rating">
									<?php for( $i = 1; $i <= 5; $i++ ) { ?>
										<img src="<?php echo JUri::root().'components/com_cleverdine/assets/css/images/'.($i <= $review['rating'] ? 'rating-star.png' : 'rating-star-no.png'); ?>"/>
									<?php } ?>
								</div>
								<div class="rv-title"><?php echo $review['title']; ?></div>
							</div>

							<div class="rv-head-down">
								<?php echo JText::sprintf(
									'VRREVIEWSUBHEAD', 
									'<strong>'.$review['name'].'</strong>', 
									cleverdine::formatTimestamp(JText::sprintf('VRDFWHEN', $date_format), $review['timestamp'])
								); ?>

								<?php if( $review['verified'] ) { ?>
									<div class="rv-verified"><?php echo JText::_('VRREVIEWVERIFIED'); ?></div>
								<?php } ?>
							</div>

						</div>
						<div class="rv-middle">
							<?php echo $review['comment']; ?>
						</div>

					</div>

				<?php }

			} ?>

		</div>

	</div>
<?php } ?>

<?php if( count($this->allAttributes) > 0 ) { ?>
	<div class="vrtk-attributes-legend">
		<?php foreach( $this->allAttributes as $k => $attr ) { ?>
			<div class="vrtk-attribute-box">
				<img src="<?php echo JUri::root().'components/com_cleverdine/assets/media/'.$attr['icon']; ?>"/>
				<span><?php echo $attr['name']; ?></span>
			</div>
		<?php } ?>
	</div>
<?php } ?>

<div class="vr-overlay" id="vrnewitemoverlay" style="display: none;">
	<div class="vr-modal-box" style="width: 90%;max-width: 960px;height: 90%;margin-top:10px;">
		<div class="vr-modal-head">
			<div class="vr-modal-head-title">
				<h3></h3>
			</div>
			<div class="vr-modal-head-dismiss">
				<a href="javascript: void(0);" onClick="vrCloseOverlay('vrnewitemoverlay');">×</a>
			</div>
		</div>
		<div class="vr-modal-body" style="height:90%;overflow:scroll;">
			
		</div>
	</div>
</div>

<script>

	var ITEM_TOTAL_COST = <?php echo (float)$item_total_cost; ?>;

	var VARIATIONS_COST_MAP = <?php echo json_encode($variations_cost_map); ?>;
	var VARIATIONS_CURR_ID = <?php echo $variations_curr_id; ?>;

	var TOPPINGS_COST_MAP = <?php echo json_encode($toppings_cost_map); ?>;
	var TOPPINGS_CURR_ID = <?php echo json_encode($toppings_curr_id); ?>;

	var TOPPINGS_CONSTRAINTS = <?php echo json_encode($toppings_constraints); ?>;

	jQuery(document).ready(function(){
		// variations
		jQuery('#vrtk-vars-select').on('change', function(){
			<?php if( $this->isToSubmit ) { ?>
				jQuery('#vrtkitemform').submit();
			<?php } else { ?>
				var cost = parseFloat(jQuery(this).find('option:selected').data('price'));


				if( VARIATIONS_CURR_ID != -1 && VARIATIONS_COST_MAP.hasOwnProperty(VARIATIONS_CURR_ID) ) {
					ITEM_TOTAL_COST -= VARIATIONS_COST_MAP[VARIATIONS_CURR_ID];
				}

				ITEM_TOTAL_COST += (isNaN(cost) ? 0 : cost);
				vrUpdateItemCost();

				VARIATIONS_CURR_ID = parseInt(jQuery(this).val());
			<?php } ?>
		});

		// checkbox toppings
		jQuery('.vrtk-itemdet-page .tk-topping-wrapper input[type="checkbox"]').on('change', function(){
			// update cost
			var cost = parseFloat(jQuery(this).data('price'));
			if( isNaN(cost) ) {
				cost = 0;
			}

			if( jQuery(this).is(':checked') ) {
				ITEM_TOTAL_COST += cost;
			} else {
				ITEM_TOTAL_COST -= cost;
			}

			vrUpdateItemCost();

			// handle constraints
			var id_group = jQuery(this).data('group');

			if( jQuery('.vrtk-topping-checkbox'+id_group+':checked').length >= TOPPINGS_CONSTRAINTS[id_group]['max'] ) {
				jQuery('.vrtk-topping-checkbox'+id_group+':not(:checked)').prop('disabled', true);
			} else {
				jQuery('.vrtk-topping-checkbox'+id_group+':not(:checked)').prop('disabled', false);
			}
		});

		// dropdown toppings
		jQuery('.vrtk-itemdet-page .tk-topping-wrapper select').on('change', function(){
			var id_group = jQuery(this).data('group');

			var cost = parseFloat(jQuery(this).find('option:selected').data('price'));

			if( TOPPINGS_CURR_ID[id_group] != -1 && TOPPINGS_COST_MAP[id_group].hasOwnProperty(TOPPINGS_CURR_ID[id_group]) ) {
				ITEM_TOTAL_COST -= TOPPINGS_COST_MAP[id_group][TOPPINGS_CURR_ID[id_group]];
			}

			ITEM_TOTAL_COST += (isNaN(cost) ? 0 : cost);
			vrUpdateItemCost();
			TOPPINGS_CURR_ID[id_group] = parseInt(jQuery(this).val());
		});

		// quantity
		jQuery('input[name="quantity"]').on('change', function(){
			var quantity = parseInt(jQuery(this).val());
			if(  isNaN(quantity) || quantity <= 0 ) {
				jQuery(this).val(1);
			} else if( quantity > 999 ) {
				jQuery(this).val(999);
			}

			vrUpdateItemCost();
		});

		// set initial total cost
		vrUpdateItemCost();
	});

	function vrIsCartPublished() {
		return ( typeof cleverdine_CART_INSTANCE !== "undefined" );
	}

	var MESSAGE_HANDLER = null;

	function vrDispatchMessage(msg) {

		if( MESSAGE_HANDLER !== null ) {
			clearTimeout(MESSAGE_HANDLER);
		}
		
		var content = jQuery('#vrtk-cart-msg');

		content.html(msg.text);
		content.removeClass('error');
		content.removeClass('success');
		content.removeClass('warning');

		switch(msg.status) {
			case 0: content.addClass('error'); break;
			case 1: content.addClass('success'); break;
			case 2: content.addClass('warning'); break;
		}

		content.fadeIn();

		MESSAGE_HANDLER = setTimeout(function(){
			content.fadeOut();
			MESSAGE_HANDLER = null;
		}, 3000);
	}

	function vrGetQuantity() {
		var quantity = parseInt(jQuery('input[name="quantity"]').val());
		if(  isNaN(quantity) || quantity <= 0 ) {
			quantity = 1;
		} else if( quantity > 999 ) {
			quantity = 999;
		}

		return quantity;
	}

	function vrUpdateItemCost() {
		var q = vrGetQuantity();
		jQuery('#vrtk-price-box').html('<?php echo $_symb_arr[0]; ?>'+(ITEM_TOTAL_COST*q).toFixed(2)+'<?php echo $_symb_arr[1]; ?>');
	}

	function vrValidateBeforeSubmit() {

		var ok = true;

		// check quantity
		var quantity = parseInt(jQuery('input[name="quantity"]').val());
		if(  isNaN(quantity) || quantity <= 0 ) {
			ok = false;
			jQuery('input[name="quantity"]').addClass('vrrequiredfield');
		} else {
			jQuery('input[name="quantity"]').removeClass('vrrequiredfield');
		}

		// check variation
		if( jQuery('#vrtk-vars-select').length ) {
			var id_var = jQuery('#vrtk-vars-select').val();
			if( isNaN(id_var) || id_var <= 0 ) {
				ok = false;
				jQuery('#vrtkvarlabel').addClass('vrrequired');
			} else {
				jQuery('#vrtkvarlabel').removeClass('vrrequired');
			}
		}

		// check single toppings
		jQuery('.vrtk-itemdet-page .tk-topping-wrapper select').each(function(){
			var id_group = jQuery(this).data('group');
			if( !jQuery(this).val().length ) {
				ok = false;
				jQuery('.vrtklabel'+id_group).addClass('vrrequired');
			} else {
				jQuery('.vrtklabel'+id_group).removeClass('vrrequired');
			}
		});

		// check multiple toppings
		jQuery.each(TOPPINGS_CONSTRAINTS, function(id_group, bounds){
			var checkedCount = jQuery('.vrtk-topping-checkbox'+id_group+':checked').length;
			if(  checkedCount < bounds['min'] || checkedCount > bounds['max'] ) {
				ok = false;
				jQuery('.vrtklabel'+id_group).addClass('vrrequired');
			} else {
				jQuery('.vrtklabel'+id_group).removeClass('vrrequired');
			}
		});

		// check notes
		if( jQuery('textarea[name="notes"]').val().length > 256 ) {
			ok = false;
			jQuery('textarea[name="notes"]').addClass('vrrequiredfield');
		} else {
			jQuery('textarea[name="notes"]').removeClass('vrrequiredfield');
		}

		return ok;

	}

	function vrInsertTakeAwayItem() {

		<?php if( $menu_active ) { ?>

			var msg = {
				status: 0,
				text: ''
			};

			if( !vrValidateBeforeSubmit() ) {
				msg.text = '<?php echo addslashes(JText::_('VRTKADDITEMERR1')); ?>';
				vrDispatchMessage(msg);
				return;
			}

			jQuery('#vrtk-item-addbutton').prop('disabled', true);
			
			jQuery.noConflict();
			
			var jqxhr = jQuery.ajax({
				type: "POST",
				url: "<?php echo JRoute::_('index.php?option=com_cleverdine&task=add_to_cart&tmpl=component'); ?>",
				data: jQuery('#vrtkitemform').serialize()
			}).done(function(resp){
				var obj = jQuery.parseJSON(resp);
				
				if( obj[0] ) {
					if( vrIsCartPublished() ) {
						vrCartRefreshItems(obj[1], obj[2], obj[3], obj[4]);
					}

					if( obj[5] ) {
						msg.status = obj[5].status;
						msg.text = obj[5].text;
					} else {
						msg.status = 1;
						msg.text = '<?php echo addslashes(JText::_('VRTKADDITEMSUCC')); ?>';
					}

					jQuery('#vrtk-ordernow-box').show();

				} else {
					msg.text = obj[1];
				}

				jQuery('#vrtk-item-addbutton').prop('disabled', false);

				vrDispatchMessage(msg);
			}).fail(function(resp){
				alert('<?php echo addslashes(JText::_('VRTKADDITEMERR2')); ?>');

				jQuery('#vrtk-item-addbutton').prop('disabled', false);
			});

		<?php } ?>
	}

	// OVERLAY //

	function vrOpenOverlay(ref, title, id_entry, id_option, index) {
		
		jQuery('.vr-modal-head-title h3').text(title);
		
		jQuery('.vr-modal-body').html('<div class="vr-modal-overlay-loading"><img id="img-loading" src="<?php echo JUri::root(); ?>components/com_cleverdine/assets/css/images/hor-loader.gif"/></div>');
		jQuery('#'+ref).show();
		
		jQuery.noConflict();
		
		var jqxhr = jQuery.ajax({
			type: "POST",
			url: "index.php",
			data: { option: "com_cleverdine", task: "tkadditem", eid: id_entry, oid: id_option, index: index, tmpl: "component" }
		}).done(function(resp){
			resp = jQuery.parseJSON(resp)[0];

			jQuery('.vr-modal-body').html(resp);
		}).fail(function(){
			alert('<?php echo addslashes(JText::_('VRTKADDITEMERR2')); ?>');
		});
		
	}


	function vrCloseOverlay(ref) {
		jQuery('#'+ref).hide();
		jQuery('.vr-modal-body').html('');
	}

	jQuery('.vr-modal-box').on('click', function(e){
		e.stopPropagation();
		// IGNORE OUTSIDE CLICK
	});
	jQuery('.vr-overlay').on('click', function(){
		vrCloseOverlay(jQuery(this).attr('id'));
	});

</script>