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

$order = $this->order;
$array_order = $this->array_order;
$otype = $this->ordtype;
if( count( $order ) > 0 ) {
	if( $otype == 0 ) {
		$menus = $this->menus;
		$res_requirements = cleverdine::getReservationRequirements();
	} else {
		$items = $this->items;
	}
	
	$payment = $this->payment;
	$curr_symb = cleverdine::getCurrencySymb();
	$symb_pos = cleverdine::getCurrencySymbPosition();
	
	$_custf = json_decode($order['custom_f']);
	$_coupon = explode(';;',$order['coupon_str']);
} 

$can_cancel_order = false;

?>

<div id="vr-payment-position-top"></div>

<form action="<?php echo JRoute::_('index.php?option=com_cleverdine&view=order'); ?>" name="orderform" method="GET">
	
	<?php if( count( $order ) == 0 ) { ?>
		<div class="vrorderpagediv">
			<div class="vrordertitlediv"><?php echo JText::_('VRORDERTITLE1'); ?></div>
			<div class="vrordercomponentsdiv">
				<div class="vrorderinputdiv">
					<label class="vrorderlabel" for="vrordnum" style="float: left;"><?php echo JText::_('VRORDERNUMBER'); ?>:</label> <input class="vrorderinput" type="text" id="vrordnum" name="ordnum" size="16"/>
				</div>
				
				<div class="vrorderinputdiv">
					<label class="vrorderlabel" for="vrordkey" style="float: left;"><?php echo JText::_('VRORDERKEY'); ?>:</label> <input class="vrorderinput" type="text" id="vrordkey" name="ordkey" size="16"/>
				</div>

				<?php
				$is_restaurant_enabled = cleverdine::isRestaurantEnabled();
				$is_takeaway_enabled = cleverdine::isTakeAwayEnabled();
				?>
				
				<?php if( $is_restaurant_enabled && $is_takeaway_enabled ) { ?>
					<div class="vrorderinputdiv">
						<label class="vrorderlabel" for="vrordertypediv" style="float: left;"><?php echo JText::_('VRORDERTYPE'); ?>:</label> 
						<div class="vrordertypediv" id="vrordertypediv">
							<span class="vrorderradiosp">
								<input type="radio" name="ordtype" value="0" id="vrordtype0" checked="checked"> <label for="vrordtype0"><?php echo JText::_("VRORDERRESTAURANT"); ?></label>
							</span>
							<span class="vrorderradiosp">
								<input type="radio" name="ordtype" value="1" id="vrordtype1"> <label for="vrordtype1"><?php echo JText::_("VRORDERTAKEAWAY"); ?></label>
							</span>
						</div>
					</div>
				<?php } else if( $is_restaurant_enabled ) { ?>
					<!-- ONLY RESTAURANT -->
					<input type="hidden" name="ordtype" value="0" />
				<?php } else { ?>
					<!-- ONLY TAKEAWAY -->
					<input type="hidden" name="ordtype" value="1" />
				<?php } ?>
				
				<div class="vrorderinputdiv">
					<button type="submit" class="vrordersubmit"><?php echo JText::_('VRSUBMIT'); ?></button>
				</div>
			</div>
		</div>
	<?php } else {
			 
		$can_cancel_order = ( cleverdine::canUserCancelOrder($order['checkin_ts'], $otype) && $order['status'] == 'CONFIRMED' );

		$canc_reason = ( $otype == 0 ? cleverdine::getCancellationReason() : cleverdine::getTakeAwayCancellationReason() );

		?>
		
		<?php if( $otype == 0 ) { ?>
		
			<div class="vrorderpagediv">
				
				<div class="vrorderboxcontent">
					<h3 class="vrorderheader"><?php echo JText::_('VRORDERTITLE1'); ?></h3>
					<div class="vrordercontentinfo">
						<div class="vrorderinfo">
							<span class="orderinfo-label"><?php echo JText::_('VRORDERNUMBER'); ?>:</span>
							<span class="orderinfo-value"><?php echo $order['id'];?></span>
						</div>
						<div class="vrorderinfo">
							<span class="orderinfo-label"><?php echo JText::_('VRORDERKEY'); ?>:</span>
							<span class="orderinfo-value"><?php echo $order['sid'];?></span>
						</div>
						<div class="vrorderinfo">
							<span class="orderinfo-label"><?php echo JText::_('VRORDERSTATUS'); ?>:</span>
							<span class="orderinfo-value vrreservationstatus<?php echo strtolower($order['status']); ?>"><?php echo JText::_('VRRESERVATIONSTATUS'.$order['status']); ?></span>
						</div>
						
						<?php if( $payment !== null ) { ?>
							<br clear="all"/><br/>
							<div class="vrorderinfo">
								<span class="orderinfo-label"><?php echo JText::_('VRORDERPAYMENT'); ?>:</span>
								<span class="orderinfo-value"><?php echo $payment['name'] . ( ( $payment['charge'] != 0 ) ? ' (' . ( ($payment['charge'] > 0) ? '+' : '' ) . cleverdine::printPriceCurrencySymb($payment['charge']) . ')' : '' ); ?></span>
							</div>
							<div class="vrorderinfo">
								<span class="orderinfo-label"><?php echo JText::_('VRORDERRESERVATIONCOST'); ?>:</span>
								<span class="orderinfo-value"><?php echo cleverdine::printPriceCurrencySymb($order['deposit']); ?></span>
							</div>
							<?php if( $order['tot_paid'] > 0 ) { ?>
								<div class="vrorderinfo">
									<span class="orderinfo-label"><?php echo JText::_('VRORDERDEPOSIT'); ?>:</span>
									<span class="orderinfo-value"><?php echo cleverdine::printPriceCurrencySymb($order['tot_paid']); ?></span>
								</div>
							<?php } ?>
							<?php if( count( $_coupon ) == 3 ) { ?>
								<div class="vrorderinfo">
									<span class="orderinfo-label"><?php echo JText::_('VRORDERCOUPON'); ?>:</span>
									<span class="orderinfo-value"><?php 
										if( $_coupon[2] == 1 ) {
											echo $_coupon[1].'%';
										} else {
											echo cleverdine::printPriceCurrencySymb($_coupon[1], $curr_symb, $symb_pos);
										}
									?></span>
								</div>
							<?php } ?>
						<?php } ?>
					</div>
				</div>
				
				<div class="vrorderboxcontent">
					<h3 class="vrorderheader"><?php echo JText::_('VRORDERTITLE2'); ?></h3>
					<div class="vrordercontentinfo">
						<div class="vrorderinfo">
							<span class="orderinfo-label"><?php echo JText::_('VRORDERDATETIME'); ?>:</span>
							<span class="orderinfo-value"><?php echo date(cleverdine::getDateFormat().' @ '.cleverdine::getTimeFormat(), $order['checkin_ts']); ?></span>
						</div>
						<div class="vrorderinfo">
							<span class="orderinfo-label"><?php echo JText::_('VRORDERPEOPLE'); ?>:</span>
							<span class="orderinfo-value"><?php echo $order['people']; ?></span>
						</div>
						<?php if( $res_requirements == 1 || $res_requirements == 0 ) { ?>
							<div class="vrorderinfo">
								<span class="orderinfo-label"><?php echo JText::_('VRROOM'); ?>:</span>
								<span class="orderinfo-value"><?php echo $order['room_name']; ?></span>
							</div>
						<?php } ?>
						<?php if( $res_requirements == 0 ) { ?>
							<div class="vrorderinfo">
								<span class="orderinfo-label"><?php echo JText::_('VRTABLE'); ?>:</span>
								<span class="orderinfo-value"><?php echo $order['table_name']; ?></span>
							</div>
						<?php } ?>
						
						<br clear="all"/>
						
						<?php foreach( $_custf as $key => $val ) {
							if( !empty($val) ) { ?>
								<div class="vrorderinfo">
									<span class="orderinfo-label"><?php echo JText::_($key); ?>:</span>
									<span class="orderinfo-value"><?php echo $val; ?></span>
								</div>
							<?php }
						} ?>
					</div>
				</div>
				
				<?php if( count($menus) > 0 ) { ?>
					<div class="vrorderboxcontent">
						<h3 class="vrorderheader"><?php echo JText::_('VRORDERTITLE3'); ?></h3>
						<div class="vrordercontentinfo">
							<?php foreach( $menus as $m ) { ?>
								<div class="vrtk-order-food">
									<div class="vrtk-order-food-details">
										<div class="vrtk-order-food-details-left">
											<span class="vrtk-order-food-details-name"><?php echo $m['name']; ?></span>
										</div>
										<div class="vrtk-order-food-details-right">
											<span class="vrtk-order-food-details-quantity">x<?php echo $m['quantity']; ?></span>
										</div>
									</div>
								</div>
							<?php } ?>
						</div>
					</div>
				<?php } ?>
				
			</div>
		<?php } else { ?>
			
			<div class="vrorderpagediv">
				
				<div class="vrorderboxcontent">
					<h3 class="vrorderheader"><?php echo JText::_('VRORDERTITLE1'); ?></h3>
					<div class="vrordercontentinfo">
						<div class="vrorderinfo">
							<span class="orderinfo-label"><?php echo JText::_('VRORDERNUMBER'); ?>:</span>
							<span class="orderinfo-value"><?php echo $order['id'];?></span>
						</div>
						<div class="vrorderinfo">
							<span class="orderinfo-label"><?php echo JText::_('VRORDERKEY'); ?>:</span>
							<span class="orderinfo-value"><?php echo $order['sid'];?></span>
						</div>
						<div class="vrorderinfo">
							<span class="orderinfo-label"><?php echo JText::_('VRORDERSTATUS'); ?>:</span>
							<span class="orderinfo-value vrreservationstatus<?php echo strtolower($order['status']); ?>"><?php echo JText::_('VRRESERVATIONSTATUS'.$order['status']); ?></span>
						</div>
						
						<?php if( $payment !== null ) { ?>
							<br clear="all"/><br/>
							<div class="vrorderinfo">
								<span class="orderinfo-label"><?php echo JText::_('VRORDERPAYMENT'); ?>:</span>
								<span class="orderinfo-value"><?php echo $payment['name'] . ( ( $order['pay_charge'] != 0 ) ? ' (' . ( ($order['pay_charge'] > 0) ? '+' : '' ) . 
									cleverdine::printPriceCurrencySymb($order['pay_charge'], $curr_symb, $symb_pos) . ')' : '' ); ?></span>
							</div>
							<br clear="all"/>
						<?php } ?>
							
						<?php if( $order['total_to_pay'] > 0 ) { ?>
							<div class="vrorderinfo">
								<span class="orderinfo-label"><?php echo JText::_('VRTKORDERTOTALTOPAY'); ?>:</span>
								<span class="orderinfo-value"><?php echo cleverdine::printPriceCurrencySymb($order['total_to_pay']); ?></span>
							</div>
						<?php } ?>

						<?php if( $order['taxes'] > 0 ) { ?>
							<div class="vrorderinfo">
								<span class="orderinfo-label"><?php echo JText::_('VRTKORDERTOTALNETPRICE'); ?>:</span>
								<span class="orderinfo-value"><?php echo cleverdine::printPriceCurrencySymb($order['total_to_pay']-$order['pay_charge']-$order['taxes']); ?></span>
							</div>
							<div class="vrorderinfo">
								<span class="orderinfo-label"><?php echo JText::_('VRTKORDERTAXES'); ?>:</span>
								<span class="orderinfo-value"><?php echo cleverdine::printPriceCurrencySymb($order['taxes']); ?></span>
							</div>
						<?php } ?>
						
						<?php if( $order['tot_paid'] > 0 ) { ?>
							<br clear="all"/>
							<div class="vrorderinfo">
								<span class="orderinfo-label"><?php echo JText::_('VRORDERDEPOSIT'); ?>:</span>
								<span class="orderinfo-value"><?php echo cleverdine::printPriceCurrencySymb($order['tot_paid']); ?></span>
							</div>
						<?php } ?>
						
						<?php if( count( $_coupon ) == 3 ) { ?>
							<br clear="all"/>
							<div class="vrorderinfo">
								<span class="orderinfo-label"><?php echo JText::_('VRORDERCOUPON'); ?></span>
								<span class="orderinfo-value"><?php
									if( $_coupon[2] == 1 ) {
										echo $_coupon[1].'%';
									} else {
										echo cleverdine::printPriceCurrencySymb($_coupon[1], $curr_symb, $symb_pos);
									}
								?></span>
							</div>
						<?php } ?>
						
					</div>
				</div>
				
				<div class="vrorderboxcontent">
					<h3 class="vrorderheader"><?php echo JText::_('VRORDERTITLE2'); ?></h3>
					<div class="vrordercontentinfo">
						<div class="vrorderinfo">
							<span class="orderinfo-label"><?php echo JText::_('VRORDERDATETIME'); ?>:</span>
							<span class="orderinfo-value"><?php echo date(cleverdine::getDateFormat().' @ '.cleverdine::getTimeFormat(), $order['checkin_ts']); ?></span>
						</div>
						<div class="vrorderinfo">
							<span class="orderinfo-label"><?php echo JText::_('VRTKORDERDELIVERYSERVICE'); ?>:</span>
							<span class="orderinfo-value">
								<?php echo JText::_(($order['delivery_service']) ? 'VRTKORDERDELIVERYOPTION': 'VRTKORDERPICKUPOPTION'); ?>
								<?php if ($order['delivery_charge'] != 0) {
									echo ' (' . ($order['delivery_charge'] > 0 ? '+' : '') . cleverdine::printPriceCurrencySymb($order['delivery_charge']) . ')';
								} ?>
							</span>
						</div>
						
						<br clear="all"/>
						
						<?php foreach( $_custf as $key => $val ) {
							if( !empty($val) ) { ?>
								<div class="vrorderinfo">
									<span class="orderinfo-label"><?php echo JText::_($key);?>:</span>
									<span class="orderinfo-value"><?php echo $val;?></span>
								</div>
						<?php }
						} ?>
					</div>
				</div>
				
				<div class="vrorderboxcontent">
					<h3 class="vrorderheader"><?php echo JText::_('VRTKORDERTITLE3'); ?></h3>
					<div class="vrordercontentinfo vrtk-order-foodlist">
						<?php foreach( $items as $item ) { ?>
							<div class="vrtk-order-food">
								<div class="vrtk-order-food-details">
									<div class="vrtk-order-food-details-left">
										<span class="vrtk-order-food-details-name"><?php echo $item['name'].(!empty($item['option_name']) ? " - ".$item['option_name'] : ''); ?></span>
									</div>
									<div class="vrtk-order-food-details-right">
										<span class="vrtk-order-food-details-quantity">x<?php echo $item['quantity']; ?></span>
										<span class="vrtk-order-food-details-price"><?php echo cleverdine::printPriceCurrencySymb($item['price'], $curr_symb, $symb_pos); ?></span>
									</div>
								</div>
								
								<?php if( count($item['toppings_groups']) > 0 ) { ?>
									<div class="vrtk-order-food-middle">
										<?php foreach( $item['toppings_groups'] as $group ) { ?>
											<div class="vrtk-order-food-group">
												<span class="vrtk-order-food-group-title"><?php echo $group['title']; ?>:</span>
												<span class="vrtk-order-food-group-toppings">
													<?php foreach( $group['toppings'] as $k => $topping ) {
														echo ($k > 0 ? ", " : "").$topping['name'];
													} ?>
												</span>
											</div>
										<?php } ?>
									</div>
								<?php } ?>
								
								<?php if( !empty($item['notes']) ) { ?>
									<div class="vrtk-order-food-notes">
										<?php echo $item['notes']; ?>
									</div>
								<?php } ?>
							</div>
						<?php } ?>
					</div>

					<div class="vrorder-grand-total">

						<?php if( $order['total_to_pay'] > 0 ) {

							// net
							$net = $order['total_to_pay']-$order['taxes']-$order['pay_charge']-$order['delivery_charge'];

							?>
							<div class="grand-total-row">
								<span class="label"><?php echo JText::_('VRTKCARTTOTALNET'); ?></span>
								<span class="amount"><?php echo cleverdine::printPriceCurrencySymb($net + $order['discount_val']); ?></span>
							</div>
							<?php

							// delivery charge
							if( $order['delivery_charge'] != 0 ) { ?>
								<div class="grand-total-row">
									<span class="label"><?php echo JText::_('VRTKCARTTOTALSERVICE'); ?></span>
									<span class="amount"><?php echo cleverdine::printPriceCurrencySymb($order['delivery_charge']); ?></span>
								</div>
							<?php }

							// payment charge
							if( $order['pay_charge'] != 0 ) { ?>
								<div class="grand-total-row">
									<span class="label"><?php echo JText::_('VRTKCARTTOTALPAYCHARGE'); ?></span>
									<span class="amount"><?php echo cleverdine::printPriceCurrencySymb($order['pay_charge']); ?></span>
								</div>
							<?php }

							// taxes
							if( $order['taxes'] > 0 ) { ?>
								<div class="grand-total-row red">
									<span class="label"><?php echo JText::_('VRTKCARTTOTALTAXES'); ?></span>
									<span class="amount"><?php echo cleverdine::printPriceCurrencySymb($order['taxes']); ?></span>
								</div>
							<?php }

							// discount
							if( $order['discount_val'] > 0 ) { ?>
								<div class="grand-total-row red">
									<span class="label"><?php echo JText::_('VRTKCARTTOTALDISCOUNT'); ?></span>
									<span class="amount"><?php echo cleverdine::printPriceCurrencySymb($order['discount_val'] * -1); ?></span>
								</div>
							<?php }

							// grand total
							if( $order['taxes'] > 0 ) { ?>
								<div class="grand-total-row grand-total">
									<span class="label"><?php echo JText::_('VRTKCARTTOTALPRICE'); ?></span>
									<span class="amount"><?php echo cleverdine::printPriceCurrencySymb($order['total_to_pay']); ?></span>
								</div>
							<?php }

						} ?>

					</div>

				</div>
				
			</div>
			
		<?php } ?>
		
		<?php if( $can_cancel_order ) { ?>
			<div class="vrordercancdiv vrcancallbox">
				<button type="button" class="vrordercancbutton" onClick="vrCancelButtonPressed(<?php echo $order['id']; ?>, '<?php echo $order['sid']; ?>');"><?php echo JText::_('VRCANCELORDERTITLE'); ?></button>
			</div>
		<?php } ?>
		
	<?php } ?>
	
	<input type="hidden" name="option" value="com_cleverdine" />
	<input type="hidden" name="view" value="order" />
</form>

<div id="vr-payment-position-bottom"></div>

<div id="dialog-confirm" title="<?php echo JText::_('VRCANCELORDERTITLE');?>" style="display: none;">
	<p>
		<span class="ui-icon ui-icon-cancel" style="float: left; margin: 0 7px 20px 0;"></span>
		<span><?php echo JText::_('VRCANCELORDERMESSAGE'); ?></span>
	</p>
	<?php if( $canc_reason > 0 ) { ?>
		<div>
			<div class="vr-cancreason-err" style="display: none;"><?php echo JText::_('VRCANCREASONERR'); ?></div>
			<textarea name="cancreason" id="vrcancreason" style="width: 99%;resize: vertical;height: 120px;max-height:140px;" placeholder="<?php echo JText::_('VRCANCREASONPLACEHOLDER'.$canc_reason); ?>"></textarea>
		</div>
	<?php } ?>
</div>

<?php
if( count($order) > 0 ) {

	$payment_class = strtolower(substr($payment['file'], 0, strrpos($payment['file'], '.')));

	if( count($array_order) > 0 && $payment !== null ) {
		require_once(JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_cleverdine'.DIRECTORY_SEPARATOR.'payments'.DIRECTORY_SEPARATOR.$payment['file']);
		
		$params = array();
		if( !empty($payment['params']) ) {
			$params = json_decode($payment['params'], true);
		}

		echo '<div id="vr-pay-box" class="'.$payment_class.'">';

		if( strlen($payment['prenote']) ) { ?>
			<div class="vrpaymentouternotes">
				<div class="vrpaymentnotes"><?php echo $payment['prenote'];?></div>
			</div><?php
		}
		
		$obj = new cleverdinePayment($array_order, $params);
		
		$obj->showPayment();

		echo '</div>';

		if( strlen($payment['position']) ) { ?>

			<script type="text/javascript">
				jQuery(document).ready(function(){
					jQuery('#vr-pay-box').appendTo('#<?php echo $payment['position']; ?>');
				});
			</script>

		<?php }

	} else if( strlen($payment['note']) > 0 && $order['status'] == 'CONFIRMED' ){
		?>
		<div id="vr-pay-box" class="<?php echo $payment_class; ?>">
			<div class="vrpaymentouternotes">
				<div class="vrpaymentnotes"><?php echo $payment['note'];?></div>
			</div>
		</div>

		<script type="text/javascript">
			jQuery(document).ready(function(){
				jQuery('#vr-pay-box').appendTo('#<?php echo $payment['position']; ?>');
			});
		</script>
		<?php
	}
	
	// CANCELLATION SCRIPT
	
	if( $can_cancel_order ) { ?>
		<script type="text/javascript">

			const CANC_REASON = <?php echo intval($canc_reason); ?>;
			
			jQuery(document).ready(function() {
			   if( window.location.hash === '#cancel' ) {
				   vrCancelButtonPressed(<?php echo $order['id']; ?>,'<?php echo $order['sid']; ?>');
			   } 
			});
		
			function vrCancelOrder(oid, sid, reason) {
				document.location.href = 'index.php?option=com_cleverdine&task=cancel_order&oid='+oid+'&sid='+sid+'&type=<?php echo $otype; ?>&reason='+reason;
			}
			
			function vrCancelButtonPressed(oid, sid) {
				jQuery( "#dialog-confirm" ).dialog({
					resizable: false,
					width: 480,
					height: ( CANC_REASON > 0 ? 320 : 180 ),
					modal: true,
					buttons: {
						"<?php echo JText::_('VRCANCELORDEROK'); ?>": function() {
							
							var reason = '';

							if( CANC_REASON > 0 ) {

								reason = jQuery('#vrcancreason').val();

								if( ( reason.length > 0 && reason.length < 32 ) || ( reason.length == 0 && CANC_REASON == 2 ) ) {
									jQuery('#vrcancreason').addClass('vrrequiredfield');
									jQuery('.vr-cancreason-err').show();
									return false;
								}

								jQuery('.vr-cancreason-err').hide();
								jQuery('#vrcancreason').removeClass('vrrequiredfield');

							}

							jQuery( this ).dialog( "close" );
							vrCancelOrder(oid, sid, reason);
						},
						"<?php echo JText::_('VRCANCELORDERCANC'); ?>": function() {
							jQuery( this ).dialog( "close" );
						}
					}
				});
			}
		</script>
	<?php }
}
		
?>
