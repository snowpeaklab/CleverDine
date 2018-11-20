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

$cart = $this->cart;
$cfields = $this->customFields;
$payments = $this->payments;
$any_coupon = $this->anyCoupon;
$freq_time = $this->freq_time;

$dt_args = $this->dt_args;
$soon_time = $this->soon_time;
$delivery_val = $this->delivery_val;
$continuos = $this->continuos;
$shifts = $this->shifts;

$cal_special_days = $this->cal_special_days;

$user = $this->user;

$config = UIFactory::getConfig();

$date_ts = cleverdine::createTimestamp($dt_args['date'], 0, 0);

$time_f = cleverdine::getTimeFormat();
$min_intervals = cleverdine::getTakeAwayMinuteInterval();
$asap = cleverdine::getTakeAwayAsapAfter();
$max_items_per_interval = cleverdine::getTakeAwayMealsPerInterval();

$cart_q = $cart->getPreparationItemsQuantity();

$is_date_allowed = cleverdine::isTakeAwayDateAllowed();

// START SELECT HOURS

$select_hours = '<select name="hourmin" class="vre-tinyselect large" id="vrtktime" onChange="vrTimeChanged()">';

$check_soon = 0;
if( $date_ts == cleverdine::createTimestamp( date( cleverdine::getDateFormat(), time() ), 0, 0 ) ) {
	$check_soon = 1;
}

$at_least_one_time = 0;

$now_ts = time();
$now_ts = $now_ts - ($now_ts%($min_intervals*60));
$now_ts += (($min_intervals*60)*$asap);

if( count( $continuos ) == 2 ) { // CONTINUOS WORK TIME
	
	if( $continuos[0] <= $continuos[1] ) {
		for( $i = $continuos[0]; $i <= $continuos[1]; $i++ ) {
			for( $min = 0; $min < 60; $min+=$min_intervals ) {
				$_ts = cleverdine::createTimestamp( $dt_args['date'], $i, $min );
				if( $now_ts <= $_ts ) {
					if( empty( $freq_time[$_ts] ) || ( $freq_time[$_ts]+$cart_q <= $max_items_per_interval ) ) {
						$text = date($time_f, mktime($i,$min,0,1,1,2000));
						if( $check_soon == 1 ) {
							$text = JText::sprintf('VRTKTIMESELECTASAP', $text)."&nbsp;&nbsp;&nbsp;";
							$check_soon = 2;
						} 
						$select_hours .= '<option '.(($i.':'.$min == $dt_args["hourmin"]) ? 'selected="selected"' : "").' value="'.$i.':'.$min.'">'.$text.'</option>';
						$at_least_one_time = 1;
						
						if( $dt_args['hourmin'] == '-1:0' ){
							$dt_args['hourmin'] = $i.':'.$min;
						}
					}
				}
			}
		}
	} else {
		for( $i = 0; $i <= $continuos[1]; $i++ ) {	
			for( $min = 0; $min < 60; $min+=$min_intervals ) {
				$_ts = cleverdine::createTimestamp( $dt_args['date'], $i, $min );
				if( $now_ts <= $_ts ) {
					if( empty( $freq_time[$_ts] ) || ( $freq_time[$_ts]+$cart_q <= $max_items_per_interval ) ) {
						$text = date($time_f, mktime($i,$min,0,1,1,2000));
						if( $check_soon == 1 ) {
							$text = JText::sprintf('VRTKTIMESELECTASAP', $text)."&nbsp;&nbsp;&nbsp;";
							$check_soon = 2;
						} 
						$select_hours .= '<option '.(($i.':'.$min == $dt_args["hourmin"]) ? 'selected="selected"' : "").' value="'.$i.':'.$min.'">'.$text.'</option>';
						$at_least_one_time = 1;
						
						if( $dt_args['hourmin'] == '-1:0' ){
							$dt_args['hourmin'] = $i.':'.$min;
						}
					}
				}
			}
		}
		
		for( $i = $continuos[0]; $i <= 23; $i++ ) {
			for( $min = 0; $min < 60; $min+=$min_intervals ) {
				$_ts = cleverdine::createTimestamp( $dt_args['date'], $i, $min );
				if( $now_ts <= $_ts ) {
					if( empty( $freq_time[$_ts] ) || ( $freq_time[$_ts]+$cart_q <= $max_items_per_interval ) ) {
						$text = date($time_f, mktime($i,$min,0,1,1,2000));
						if( $check_soon == 1 ) {
							$text = JText::sprintf('VRTKTIMESELECTASAP', $text)."&nbsp;&nbsp;&nbsp;";
							$check_soon = 2;
						} 
						$select_hours .= '<option '.(($i.':'.$min == $dt_args["hourmin"]) ? 'selected="selected"' : "").' value="'.$i.':'.$min.'">'.$text.'</option>';
						$at_least_one_time = 1;
						
						if( $dt_args['hourmin'] == '-1:0' ){
							$dt_args['hourmin'] = $i.':'.$min;
						}
					}
				}
			}
		}
	}
} else { // SHIFTS WORK HOURS
	for( $k = 0, $n = count($shifts); $k < $n; $k++ ) {
			
		$optgroup = '<optgroup class="vrsearchoptgrouphour" label="'.$shifts[$k]["label"].'">';
		$almost_one = false;
		
		for( $_app = $shifts[$k]['from']; $_app < $shifts[$k]['to']; $_app+=$min_intervals ) {
			$_hour = intval($_app/60);
			$_min = $_app%60;
			
			$_ts = cleverdine::createTimestamp( $dt_args['date'], $_hour, $_min );
			if( $now_ts <= $_ts ) {
				if( empty( $freq_time[$_ts] ) || ( $freq_time[$_ts]+$cart_q <= $max_items_per_interval ) ) {
					$text = date($time_f, mktime($_hour,$_min,0,1,1,2000));
					if( $check_soon == 1 ) {
						$text = JText::sprintf('VRTKTIMESELECTASAP', $text)."&nbsp;&nbsp;&nbsp;";
						$check_soon = 2;
					} else if( !$almost_one && $shifts[$k]['showlabel']) {
						$select_hours .= $optgroup;
						$almost_one = true;
					}
					$select_hours .= '<option '.(($_hour.':'.$_min == $dt_args["hourmin"]) ? 'selected="selected"' : "").' value="'.$_hour.':'.$_min.'">'.$text.'</option>';
					$at_least_one_time = 1;
					
					if( $dt_args['hourmin'] == '-1:0' ){
						$dt_args['hourmin'] = $_hour.':'.$_min;
					}
				}
			}
		}	
		
		if( $almost_one ) {
			$select_hours .= '</optgroup>';
		}
	}
}

$select_hours .= '</select>';

// END SELECT HOURS

if( count($cfields) > 0 ) {
	foreach( $cfields as $cf ) {
		if( !empty( $cf['poplink'] ) ) {
			cleverdine::load_fancybox();
		}
	}
}

$step = 0;

$skip_payments = ( ( count( $payments ) == 0 ) ? 1 : 0 ); // 1 = skip, 0 = not skip

$session = JFactory::getSession();
$coupon = $session->get('vr_coupon_data', '');
if( !empty($coupon) ) {
	$cart->deals()->insert(
		new TakeAwayDiscount($coupon['code'], $coupon['value'], $coupon['percentot'], 1)
	);
}

$curr_symb = cleverdine::getCurrencySymb();
$symb_pos = cleverdine::getCurrencySymbPosition();
$_symb_arr = array( '', '' );
if( $symb_pos == 1 ) {
	$_symb_arr[1] = ' '.$curr_symb;
} else {
	$_symb_arr[0] = $curr_symb.' ';
}

$isdelivery = cleverdine::isTakeAwayDeliveryServiceEnabled();

//
if( $this->specialDays != -1 && count($this->specialDays) > 0 ) {
	// do delivery override
	if( $this->specialDays[0]['delivery_service'] > -1 ) {
		$isdelivery = $this->specialDays[0]['delivery_service'];
	}
}
//

$delivery_cost = cleverdine::getTakeAwayDeliveryServiceAddPrice();
$delivery_percentot = cleverdine::getTakeAwayDeliveryServicePercentOrTotal();

$pickup_cost = cleverdine::getTakeAwayPickupAddPrice();
$pickup_percentot = cleverdine::getTakeAwayPickupPercentOrTotal();

// jQuery datepicker
$vik = new VikApplication();
$vik->attachDatepickerRegional();

$closing_days = cleverdine::getClosingDays();

$date_format = cleverdine::getDateFormat();

for( $i = 0, $n = count( $cal_special_days ); $i < $n; $i++ ) {
	if( $cal_special_days[$i]['start_ts'] != -1 ) {
		$cal_special_days[$i]['start_ts'] = date( $date_format, $cal_special_days[$i]['start_ts'] );
		$cal_special_days[$i]['end_ts'] = date( $date_format, $cal_special_days[$i]['end_ts'] );
	}	
	
	if( !empty($cal_special_days[$i]['days_filter']) ) {
		$_days = explode( ', ', $cal_special_days[$i]['days_filter'] );
		
		$cal_special_days[$i]['days_filter'] = $_days;
	}
	
}

$show_taxes = cleverdine::isTakeAwayTaxesVisible();
$use_taxes = cleverdine::isTakeAwayTaxesUsable();

$login_req = cleverdine::getTakeAwayLoginRequirements();

// get login return URL
$return_url_to_encode = cleverdine::getLoginReturnURL();

$free_label = JText::_('VRFREE');

$total_cost = $cart->getTotalCost();
$total_discount = $cart->getTotalDiscount();

$grand_total = $cart->getRealTotalCost($use_taxes); // use taxes only if taxes are excluded

$free_delivery = 0;
if( $total_cost >= cleverdine::getTakeAwayFreeDeliveryService() ) {
	$delivery_cost = 0;
	$free_delivery = 1;
} else if( $delivery_percentot == 1 ) {
	// percentage delivery cost is applied to the original total cost (discounts are not considered) 
	$delivery_cost = $total_cost*$delivery_cost/100.0;
}

if( $pickup_percentot == 1 ) {
	$pickup_cost = $total_cost*$pickup_cost/100.0;
}

$delivery_address_object = $session->get('delivery_address', null, 'vre');

?>

<?php if( $login_req > 1 && !cleverdine::userIsLogged() ) { // if login requirements is not NEVER and customer is GUEST > display login/register form ?>
	
	<script type="text/javascript">
		
		function vrLoginValueChanged() {
			if(jQuery('input[name=loginradio]:checked').val() == 1) {
				jQuery('.vrregisterblock').hide();
				jQuery('.vrloginblock').show();
			}else {
				jQuery('.vrloginblock').hide()
				jQuery('.vrregisterblock').show();
			}
		}

		function vrValidateRegistrationFields() {
			var names = ["fname", "lname", "email", "username", "password", "confpassword"];
			var fields = {};

			var elem = null;
			var ok = true;

			for( var i = 0;  i < names.length; i++ ) {
				elem = jQuery('#vrregform input[name="'+names[i]+'"]');

				fields[names[i]] = elem.val();

				if( fields[names[i]].length > 0 ) {
					elem.removeClass('vrrequiredfield');
				} else {
					ok = false;
					elem.addClass('vrrequiredfield');
				}
			}

			if( ok ) {
				var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
				if( !re.test(fields.email) ) {
					ok = false;
					jQuery('#vrregform input[name="email"]').addClass('vrrequiredfield');
				}
			}

			if( ok ) {
				if( fields.password !== fields.confpassword ) {
					ok = false;
					jQuery('#vrregform input[name="password"], #vrregform input[name="confpassword"]').addClass('vrrequiredfield');
				}
			}

			return ok;
		}

		function vrValidateLoginFields() {
			var names = ["username", "password"];
			var fields = {};

			var elem = null;
			var ok = true;

			for( var i = 0;  i < names.length; i++ ) {
				elem = jQuery('#vrloginform input[name="'+names[i]+'"]');

				fields[names[i]] = elem.val();

				if( fields[names[i]].length > 0 ) {
					elem.removeClass('vrrequiredfield');
				} else {
					ok = false;
					elem.addClass('vrrequiredfield');
				}
			}

			return ok;
		}

	</script>
	
	<?php if( cleverdine::isTakeAwayRegistrationEnabled() ) { ?>
	
		<div class="vrloginradiobox" id="vrloginradiobox">
			<span class="vrloginradiosp">
				<label for="logradio1"><?php echo JText::_('VRLOGINRADIOCHOOSE1'); ?></label>
				<input type="radio" id="logradio1" name="loginradio" value="1" onChange="vrLoginValueChanged();" checked="checked"/>
			</span>
			<span class="vrloginradiosp">
				<label for="logradio2"><?php echo JText::_('VRLOGINRADIOCHOOSE2'); ?></label>
				<input type="radio" id="logradio2" name="loginradio" value="2" onChange="vrLoginValueChanged();" />
			</span>
		</div>
		
		<div class="vrregisterblock" style="display: none;">
			<form action="<?php echo JRoute::_('index.php?option=com_cleverdine'); ?>" method="post" name="vrregform" id="vrregform">
				<h3><?php echo JText::_('VRREGISTRATIONTITLE'); ?></h3>
				<div class="vrloginfieldsdiv">
					<div class="vrloginfield">
						<span class="vrloginsplabel" id="vrfname"><?php echo JText::_('VRREGNAME'); ?></span>
						<span class="vrloginspinput">
							<input type="text" name="fname" value="" size="20" class="vrinput"/>
						</span>
					</div>
					<div class="vrloginfield">
						<span class="vrloginsplabel" id="vrlname"><?php echo JText::_('VRREGLNAME'); ?></span>
						<span class="vrloginspinput">
							<input type="text" name="lname" value="" size="20" class="vrinput"/>
						</span>
					</div>
					<div class="vrloginfield">
						<span class="vrloginsplabel" id="vremail"><?php echo JText::_('VRREGEMAIL'); ?></span>
						<span class="vrloginspinput">
							<input type="text" name="email" value="" size="20" class="vrinput"/>
						</span>
					</div>
					<div class="vrloginfield">
						<span class="vrloginsplabel" id="vrusername"><?php echo JText::_('VRREGUNAME'); ?></span>
						<span class="vrloginspinput">
							<input type="text" name="username" value="" size="20" class="vrinput"/>
						</span>
					</div>
					<div class="vrloginfield">
						<span class="vrloginsplabel" id="vrpassword"><?php echo JText::_('VRREGPWD'); ?></span>
						<span class="vrloginspinput">
							<input type="password" name="password" value="" size="20" class="vrinput"/>
						</span>
					</div>
					<div class="vrloginfield">
						<span class="vrloginsplabel" id="vrconfpassword"><?php echo JText::_('VRREGCONFIRMPWD'); ?></span>
						<span class="vrloginspinput">
							<input type="password" name="confpassword" value="" size="20" class="vrinput"/>
						</span>
					</div>
					<div class="vrloginfield">
						<span class="vrloginsplabel" class="">&nbsp;</span>
						<span class="vrloginspinput">
							<button type="submit" class="vrbooknow" name="registerbutton" onClick="return vrValidateRegistrationFields();"><?php echo JText::_('VRREGSIGNUPBTN'); ?></button>
						</span>
					</div>
				</div>
		
				<input type="hidden" name="option" value="com_cleverdine" />
				<input type="hidden" name="task" value="tkregisteruser" />
				<input type="hidden" name="return" value="<?php echo base64_encode($return_url_to_encode); ?>" />
				<?php echo JHtml::_('form.token'); ?>
			</form>
		</div>
	
	<?php } ?>
	
	<div class="vrloginblock">
		<form action="<?php echo JRoute::_('index.php?option=com_users'); ?>" method="post" name="vrloginform" id="vrloginform">
			<h3><?php echo JText::_('VRLOGINTITLE'); ?></h3>
			<div class="vrloginfieldsdiv">
				<div class="vrloginfield">
					<span class="vrloginsplabel"><?php echo JText::_('VRLOGINUSERNAME'); ?></span>
					<span class="vrloginspinput">
						<input type="text" name="username" value="" size="20" class="vrlogininput"/>
					</span>
				</div>
				<div class="vrloginfield">
					<span class="vrloginsplabel"><?php echo JText::_('VRLOGINPASSWORD'); ?></span>
					<span class="vrloginspinput">
						<input type="password" name="password" value="" size="20" class="vrlogininput"/>
					</span>
				</div>
				<div class="vrloginfield">
					<span class="vrloginsplabel">&nbsp;</span>
					<span class="vrloginspinput">
						<button type="submit" class="vrloginbutton" name="Login" onClick="return vrValidateLoginFields();"><?php echo JText::_('VRLOGINSUBMIT'); ?></button>
					</span>
				</div>
			</div>
			<input type="hidden" name="remember" id="remember" value="yes" />
			<input type="hidden" name="return" value="<?php echo base64_encode($return_url_to_encode); ?>" />
			<input type="hidden" name="option" value="com_users" />
			<input type="hidden" name="task" value="user.login" />
			<?php echo JHtml::_('form.token'); ?>
		</form>

		<div class="vr-allorders-sublogin">
			<div class="vr-allorders-loginact">
				<a href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>"><?php echo JText::_('VRLOGINFORGOTPWD'); ?></a>
			</div>
			<div class="vr-allorders-loginact">
				<a href="<?php echo JRoute::_('index.php?option=com_users&view=remind'); ?>"><?php echo JText::_('VRLOGINFORGOTUSER'); ?></a>
			</div>
		</div>
	</div>
	
<?php } ?>

<?php if( $login_req != 3 || cleverdine::userIsLogged() ) { // if login requirements is not REQUIRED or user is LOGGED > display form ?>

<?php //////// CART /////// ?>

<div id="vrtkconfcartitemsdiv" class="vrtkconfcartitemsdiv">
	<div id="vrtkconfitemcontainer">
		<?php foreach( $cart->getItemsList() as $k => $item ) { ?>
			<div id="vrtk-conf-itemrow<?php echo $k; ?>" class="vrtkconfcartoneitemrow">

				<div class="vrtk-confcart-item-main">

					<div class="vrtkconfcartleftrow">
						<div class="vrtkconfcart-item-name">
							<span class="vrtkconfcartenamesp"><?php echo $item->getItemName(); ?></span>
							<?php if (strlen($item->getVariationName())) { ?>
								<span class="vrtkconfcartonamesp">-&nbsp;<?php echo $item->getVariationName(); ?></span>
							<?php } ?>
						</div>
					</div>
					
					<div class="vrtkconfcartrightrow">
						<span class="vrtkconfcartquantitysp"><?php echo JText::_('VRTKCARTQUANTITYSUFFIX') . $item->getQuantity(); ?></span>
						<?php $item_total_price = $item->getTotalCost();
						if( $item_total_price > 0 ) {
							$item_total_price = cleverdine::printPriceCurrencySymb($item->getTotalCost(), $curr_symb, $symb_pos);
						} else {
							$item_total_price = $free_label;
						} ?>
						<span class="vrtkconfcartpricesp"><?php echo $item_total_price; ?></span>
						<?php if( $item->getPrice() != $item->getOriginalPrice() ) { ?>
							<span class="vrtkconfcartpricesp-full"><s><?php echo cleverdine::printPriceCurrencySymb($item->getTotalCostNoDiscount(), $curr_symb, $symb_pos); ?></s></span>
						<?php } ?>
						<?php if( $item->canBeRemoved() ) { ?>
							<span class="vrtkconfcartremovesp">
								<a href="<?php echo JRoute::_("index.php?option=com_cleverdine&task=remove_from_cart&index=$k&do_ajax=0"); ?>" class="vrtkconfcartremovelink"></a>
							</span>
						<?php } ?>
					</div>

				</div>

				<div class="vrtk-confcart-item-details">
					<?php if (count($item->getToppingsGroupsList())) { ?>
						<div class="vrtk-confcart-item-toppings">
							<?php foreach( $item->getToppingsGroupsList() as $t_group ) { ?>
								<div class="vrtk-confcart-topping"><?php echo $t_group->getTitle(); ?>:&nbsp;
									<?php foreach( $t_group->getToppingsList() as $index => $topping ) { ?>
										<?php if( $index > 0 ) {
											echo ", ";
										} 
										echo $topping->getName();
										?>
									<?php } ?>
								</div>
							<?php } ?>
						</div>
					<?php } ?>

					<?php if( strlen($item->getAdditionalNotes()) ) { ?>
						<div class="vrtk-confcart-notes"><?php echo $item->getAdditionalNotes(); ?></div>
					<?php } ?>
				</div>

			</div>
		<?php } ?>
	</div>

	<!-- TOTAL NET -->
	<?php if( $total_discount > 0 || $show_taxes ) { ?>
		<div class="vrtk-confcart-fullcost-details net">
			<span class="fullcost-label">
				<?php echo JText::_('VRTKCARTTOTALNET'); ?>
			</span>
			<div class="fullcost-amount" id="vrtkconfcartfullcost">
				<!-- TOTAL NET BANNED, DISCOUNT IN USE -->
				<?php if( $total_discount > 0 ) { ?>
					<s><?php echo cleverdine::printPriceCurrencySymb( $total_cost, $curr_symb, $symb_pos); ?></s>
				<?php } ?>

				<?php echo cleverdine::printPriceCurrencySymb( $cart->getRealTotalNet($use_taxes), $curr_symb, $symb_pos); ?>
			</div>
		</div>
	<?php } ?>
	
	<!-- DELIVERY COST -->
	<div class="vrtk-confcart-fullcost-details service">
		<span class="fullcost-label">
			<?php echo JText::_('VRTKCARTTOTALSERVICE'); ?>
		</span>
		<div class="fullcost-amount" id="vrtkconfcartservice">
			
		</div>
	</div>

	<!-- DISCOUNT VALUE -->
	<?php if( $total_discount > 0 ) { ?>
		<div class="vrtk-confcart-fullcost-details discount">
			<span class="fullcost-label">
				<?php echo JText::_('VRTKCARTTOTALDISCOUNT'); ?>
			</span>
			<div class="fullcost-amount" id="vrtkconfcartdiscount">
				<?php echo cleverdine::printPriceCurrencySymb( $total_discount, $curr_symb, $symb_pos); ?>
			</div>
		</div>
	<?php } ?>
	
	<!-- TAXES -->
	<?php if( $show_taxes ) { ?>
		<div class="vrtk-confcart-fullcost-details taxes">
			<span class="fullcost-label">
				<?php echo JText::_('VRTKCARTTOTALTAXES'); ?>
			</span>
			<div class="fullcost-amount" id="vrtkconfcartdiscount">
				<?php echo cleverdine::printPriceCurrencySymb( $cart->getRealTotalTaxes($use_taxes), $curr_symb, $symb_pos); ?>
			</div>
		</div>
	<?php } ?>
	
	<!-- GRAND TOTAL -->
	<div class="vrtk-confcart-fullcost-details grand-total">
		<span class="fullcost-label">
			<?php echo JText::_('VRTKCARTTOTALPRICE'); ?>
		</span>
		<div class="fullcost-amount" id="vrtkconfcartprice">
			<?php echo cleverdine::printPriceCurrencySymb( $grand_total, $curr_symb, $symb_pos); ?>
		</div>
	</div>
	
</div>

<?php //////// ADD MORE ITEMS /////// ?>

<div class="vrtkaddmoreitemsdiv">
	<a href="<?php echo JRoute::_('index.php?option=com_cleverdine&view=takeaway'); ?>" class="vrtkaddmoreitemslink"><?php echo JText::_('VRTKADDMOREITEMS'); ?></a>
</div>

<?php //////// COUPONS /////// ?>

<?php if( $any_coupon == 1 ) { ?>
	<form action="<?php echo JRoute::_('index.php?option=com_cleverdine&task=takeawayconfirm'); ?>" name="vrcouponform" method="POST">
		<div class="vrcouponcodediv">
			<h3 class="vrheading3"><?php echo JText::_('VRENTERYOURCOUPON'); ?></h3>
			<input type="text" class="vrcouponcodetext" name="couponkey"/>
			<button type="submit" class="vrcouponcodesubmit"><?php echo JText::_('VRAPPLYCOUPON'); ?></button>
		</div>
		
		<input type="hidden" name="option" value="com_cleverdine"/>
		<input type="hidden" name="task" value="takeawayconfirm"/>
	</form>
<?php } ?>
	
<?php //////// DELIVERY OR PICKUP /////// ?>

<?php 
$ds_checked = array( '', 'checked="checked"' );
$delivery_val = $delivery_val && $isdelivery;
if( !$delivery_val ) {
	$ds_checked[1] = '';
	$ds_checked[0] = 'checked="checked"';
}

$delivery_cost_label = "(".(($delivery_cost > 0) ? '+'.cleverdine::printPriceCurrencySymb($delivery_cost, $curr_symb, $symb_pos) : JText::_('VRTKDELIVERYFREE')).")";

$pickup_cost_label = (($pickup_cost != 0) ? "(".($pickup_cost > 0 ? '+' : '').cleverdine::printPriceCurrencySymb($pickup_cost, $curr_symb, $symb_pos).")" : '');

?>

<form action="<?php echo JRoute::_('index.php?option=com_cleverdine&task=takeawayconfirm'); ?>" name="vrtkdatetimeform" id="vrtkdatetimeform" method="POST">

	<div class="vrtk-service-dt-wrapper">
		
		<?php //////// DATE AND TIME SELECTION /////// ?>

		<div class="vrtkdatetimediv">
			<div class="vrtkdeliverytitlediv">
				<?php if( $is_date_allowed ) {
					echo JText::_('VRTKDATETIMELEGEND');
				} else {
					echo JText::_('VRTKONLYTIMELEGEND');
				} ?>
			</div>
			
			<?php if( $is_date_allowed ) { ?>

				<div class="vrtkdatetimeinputdiv vrtk-date-box">
					<label class="vrtkdatetimeinputlabel" for="vrtkcalendar"><?php echo JText::_('VRDATE'); ?></label>
					<div class="vrtkdatetimeinput"><input class="vrtksearchdate" type="text" value="" id="vrtkcalendar" name="date" size="20"/></div>
				</div>

			<?php } ?>
			
			<?php if( $at_least_one_time == 1 ) { ?>
			
				<div class="vrtkdatetimeinputdiv vrtk-time-box">
					<label class="vrtkdatetimeinputlabel" for="vrtktime"><?php echo JText::_('VRTIME'); ?></label>
					<div class="vrtkdatetimeselect vre-tinyselect-wrapper"><?php echo $select_hours; ?></div>
				</div>
			
			<?php } else { ?>
				
				<div class="vrtkdatetimeerrmessdiv">
					<div class="vrtkdatetimenoselectdiv"><?php echo JText::_('VRTKNOTIMEAVERR'); ?></div>
				</div>
				
			<?php } ?>
		</div>

		<?php //////// DELIVERY or PICKUP SELECTION /////// ?>

		<div class="vrtkdeliveryservicediv">
			<div class="vrtkdeliverytitlediv">
				<?php echo JText::_('VRTKSERVICELABEL'); ?>
			</div>
			<div class="vrtkdeliveryradiodiv">
				<?php if( $isdelivery == 1 || $isdelivery == 2 ) { ?>
					<span class="vrtkdeliverysp">
						<input type="radio" name="delivery" value="1" id="vrtkdelivery1" <?php echo $ds_checked[1]; ?> onChange="vrServiceChanged(1);"> <label for="vrtkdelivery1"><?php echo JText::sprintf("VRTKDELIVERYLABEL", $delivery_cost_label ); ?></label>
					</span>
				<?php } ?>
				<?php if( $isdelivery == 0 || $isdelivery == 2 ) { ?>
					<span class="vrtkpickupsp">
						<input type="radio" name="delivery" value="0" id="vrtkdelivery0" <?php echo $ds_checked[0]; ?> onChange="vrServiceChanged(0);"> <label for="vrtkdelivery0"><?php echo JText::sprintf("VRTKPICKUPLABEL", $pickup_cost_label ); ?></label>
					</span>
				<?php } ?>
			</div>
		</div>

	</div>

	<input type="hidden" name="option" value="com_cleverdine">
	<input type="hidden" name="task" value="takeawayconfirm">

</form>

<form action="<?php echo JRoute::_('index.php?option=com_cleverdine&task=saveorder'); ?>" id="vrpayform" name="vrpayform" method="POST">

<?php //////// CUSTOM FIELDS /////// ?>

<div class="vrseparatordiv"></div>

	<div id="vrordererrordiv" class="vrordererrordiv" style="display: none;"></div>

	<?php if( count($cfields) > 0 ) { ?>
		<div class="vrcustomfields">
			<?php
			$currentUser = JFactory::getUser();
			$juseremail = !empty($currentUser->email) ? $currentUser->email : "";
			
			$user_fields = array();
			if( !empty($user['takeaway_fields']) ) {
				$user_fields = $user['takeaway_fields'];
			}
			
			$show_phones_prefix = $config->getBool('phoneprefix');

			$address_box_created = 0;
			
			foreach( $cfields as $cf ) {
				$cf['t_name'] 	= cleverdine::translate($cf['id'], $cf, $this->customfTranslations, 'name', 'name');
				$cf['choose'] 	= cleverdine::translate($cf['id'], $cf, $this->customfTranslations, 'choose', 'choose');
				$cf['poplink'] 	= cleverdine::translate($cf['id'], $cf, $this->customfTranslations, 'poplink', 'poplink');


				if( intval( $cf['required'] ) == 1 ) {
					$isreq = '<span class="vrrequired"><sup>*</sup></span>';
				} else {
					$isreq = "";
				}
				
				if( !empty( $cf['poplink'] ) ) {
					$fname = '<a href="javascript: void(0);" onclick="javascript: vreOpenPopup(\''.$cf['poplink'].'\');" id="vrcf'.$cf['id'].'">'.JText::_($cf['t_name']).'</a>';
				} else {
					$fname = '<span id="vrcf'.$cf['id'].'">'.JText::_($cf['t_name']).'</span>';
				}
				
				$textval = '';
				if( !empty( $user_fields[$cf['name']] ) ) {
					$textval = $user_fields[$cf['name']];
				} else if( $cf['rule'] == VRCustomFields::ADDRESS && $delivery_address_object !== null ) {
					$textval = !empty($delivery_address_object->address['fullAddress']) ? $delivery_address_object->address['fullAddress'] : '';
				}

				$delivery_field = false;
				if( $cf['rule'] == VRCustomFields::ADDRESS || $cf['rule'] == VRCustomFields::DELIVERY  || $cf['rule'] == VRCustomFields::ZIP ) {
					$delivery_field = true;
				}

				if( $cf['rule'] == VRCustomFields::ADDRESS && !empty($user['delivery']) && count($user['delivery']) > 1 ) { ?>
					<div class="<?php echo ($delivery_field ? 'vrtk-delivery-field' : ''); ?>" style="<?php echo (!$delivery_val && $delivery_field ? 'display:none;' : ''); ?>">
						<span class="cf-value">
							<select id="vrtk-user-address-sel">
								<option></option>
								<?php foreach( $user['delivery'] as $addr ) { 
									$val = cleverdine::deliveryAddressToStr($addr, array('country', 'address_2'));
									?>
									<option value="<?php echo $val; ?>"><?php echo $val; ?></option>
								<?php } ?>
							</select>

							<span class="cf-highlight"><!-- input highlight --></span>

							<span class="cf-bar"><!-- input bar --></span>

							<span class="cf-label">&nbsp;</span>
						</span>
					</div>
				<?php }
				
				if( $cf['type'] == "text") {
					if( empty($textval) && $cf['rule'] == VRCustomFields::EMAIL ) {
						$textval = $juseremail;
					}

					$onkeypress = "";

					$rule_class = '';
					if ($cf['rule'] == VRCustomFields::ADDRESS)
					{
						$rule_class = 'vrtk-address-field';
					}
					else if ($cf['rule'] == VRCustomFields::ZIP)
					{
						$rule_class = 'vrtk-zip-field';
					}
					
				?>
					<div class="<?php echo ($delivery_field ? 'vrtk-delivery-field' : ''); ?>" style="<?php echo (!$delivery_val && $delivery_field ? 'display:none;' : ''); ?>">
						
						<span class="cf-value <?php echo ($cf['rule'] == VRCustomFields::PHONE_NUMBER && $show_phones_prefix ? 'phone-field' : ''); ?>">
							<?php 
							if( $cf['rule'] == VRCustomFields::PHONE_NUMBER && $show_phones_prefix ) {
								if( empty($user['country_code']) ) {
									$user['country_code'] = $cf['choose'];
								}
								echo '<select name="vrcf'.$cf['id'].'_prfx" class="vr-phones-select">';
								foreach( $this->countries as $ctry ) {
									echo '<option value="'.$ctry['id']."_".$ctry['country_2_code'].'" title="'.trim($ctry['country_name']).'" '.($user['country_code'] == $ctry['country_2_code'] ? 'selected="selected"' : '').'>'.$ctry['phone_prefix'].'</option>';
								}
								echo '</select>';

								$onkeypress = 'onkeypress="return event.charCode >= 48 && event.charCode <= 57"';
							}    
							?>
							<input 
								type="text" 
								name="vrcf<?php echo $cf['id']; ?>" 
								id="vrcfinput<?php echo $cf['id']; ?>" 
								value="<?php echo $textval; ?>" 
								size="40" 
								class="vrinput <?php echo (empty($textval) ? '' : 'has-value'); ?> <?php echo $rule_class; ?>"
								<?php echo $onkeypress; ?>/>

							<span class="cf-highlight"><!-- input highlight --></span>

							<span class="cf-bar"><!-- input bar --></span>

							<span class="cf-label"><?php echo $isreq; ?><?php echo $fname; ?> </span>

							<?php
							if (!$address_box_created && ($cf['rule'] == VRCustomFields::ADDRESS || $cf['rule'] == VRCustomFields::ZIP))
							{
								$address_box_created = true;
								?>
								<div class="vrtk-address-response" style="display: none;"></div>
							<?php } ?>
						</span>

					</div>

				<?php } else if( $cf['type'] == "textarea" ) { ?>

					<div class="<?php echo ($delivery_field ? 'vrtk-delivery-field' : ''); ?>" style="<?php echo (!$delivery_val && $delivery_field ? 'display:none;' : ''); ?>">
						<span class="cf-value cf-textarea">
							<textarea name="vrcf<?php echo $cf['id']; ?>" class="vrtextarea"><?php echo $textval; ?></textarea>

							<span class="cf-highlight"><!-- input highlight --></span>

							<span class="cf-bar"><!-- input bar --></span>

							<span class="cf-label"><?php echo $isreq; ?><?php echo $fname; ?> </span>
						</span>
					</div>

				<?php } else if( $cf['type'] == "date" ) { ?>

					<div class="<?php echo ($delivery_field ? 'vrtk-delivery-field' : ''); ?>" style="<?php echo (!$delivery_val && $delivery_field ? 'display:none;' : ''); ?>">
						<span class="cf-value">
							<input type="text" name="vrcf<?php echo $cf['id']; ?>" id="vrcfinput<?php echo $cf['id']; ?>" value="<?php echo $textval; ?>" size="25" class="vrinput vrcalendar">

							<span class="cf-highlight"><!-- input highlight --></span>

							<span class="cf-bar"><!-- input bar --></span>

							<span class="cf-label"><?php echo $isreq; ?><?php echo $fname; ?> </span>
						</span>
					</div>

				<?php } else if( $cf['type'] == "select" ) {
					$answ = explode(";;__;;", $cf['choose']);
					$wcfsel = '<select name="vrcf'.$cf['id'].'" class="vr-cf-select vre-tinyselect">';
					foreach( $answ as $aw ) {
						if( !empty($aw) ) {
							$wcfsel .= '<option value="'.$aw.'" '.($aw == $textval ? 'selected="selected"' : '').'>'.$aw.'</option>';
						}
					}
					$wcfsel .= '</select>';
					?>

					<div class="<?php echo ($delivery_field ? 'vrtk-delivery-field' : ''); ?>" style="<?php echo (!$delivery_val && $delivery_field ? 'display:none;' : ''); ?>">
						<span class="cf-value cf-dropdown">
							<span class="cf-label"><?php echo $isreq; ?><?php echo $fname; ?> </span>

							<span class="vre-tinyselect-wrapper">
								<?php echo $wcfsel; ?>
							</span>
						</span>
					</div>

				<?php } else if( $cf['type'] == "separator" ) {
					$cfsepclass = "vrseparatorcf".(strlen(JText::_($cf['t_name'])) > 30 ? " long" : "");
					?>

					<div class="<?php echo ($delivery_field ? 'vrtk-delivery-field' : ''); ?>" style="<?php echo (!$delivery_val && $delivery_field ? 'display:none;' : ''); ?>">
						<span class="<?php echo $cfsepclass; ?>"><?php echo JText::_($cf['t_name']); ?></span>
					</div>

				<?php } else if( $cf['type'] == "checkbox" ) { ?>

					<div class="<?php echo ($delivery_field ? 'vrtk-delivery-field' : ''); ?>" style="<?php echo (!$delivery_val && $delivery_field ? 'display:none;' : ''); ?>">
						<span class="cf-label">&nbsp;</span>
						<span class="cf-value">
							<input type="checkbox" id="vrcf<?php echo $cf['id']; ?>cb" name="vrcf<?php echo $cf['id']; ?>" value="<?php echo JText::_('VRYES'); ?>" <?php echo ($textval == JText::_('VRYES') ? 'checked="checked"' : ''); ?>/>
							<label for="vrcf<?php echo $cf['id']; ?>cb"><?php echo $isreq; ?><?php echo $fname; ?></label>
						</span>
					</div>

				<?php } ?>

			<?php } ?>

		</div>

	<?php } else { $step = 1; } ?>
	
	<!-- PAYMENTS -->
	
	<?php if( ($payCount = count($payments)) > 0 ) { ?>

		<div class="vr-payments-list" id="vrpaymentsdiv" style="display: none;">

			<div class="vrtkdeliverytitlediv"><?php echo JText::_('VRMETHODOFPAYMENT'); ?></div>

			<div class="vr-payments-container">

				<?php foreach( $payments as $i => $p ) {

					$p['name'] = cleverdine::translate($p['id'], $p, $this->paymentsTranslations, 'name', 'name');
					$p['prenote'] = cleverdine::translate($p['id'], $p, $this->paymentsTranslations, 'prenote', 'prenote');
					
					$cost_str = '';

					if( $p['charge'] != 0 ) {
						$cost_str = floatval($p['charge']);

						if( $cost_str > 0 ) {
							$cost_str = '+' . $cost_str;
						} else if( $cost_str == 0 ) { 
							$cost_str = '';
						}

						if( $p['percentot'] == 1 ) {
							$cost_str .= '%';
						} else {
							$cost_str = cleverdine::printPriceCurrencySymb($cost_str);
						}
					}
					?>

					<div class="vr-payment-wrapper vr-payment-block">

						<div class="vr-payment-title">
							<?php if( $payCount > 1 ) { ?>
								<input type="radio" name="vrpaymentradio" value="<?php echo $p['id']; ?>" id="vrpayradio<?php echo $p['id']; ?>" onchange="vrPaymentRadioChanged(<?php echo $p['id']; ?>, <?php echo (strlen($p['prenote']) ? 0 : 1); ?>);"/>
							<?php } else { ?>
								<input type="hidden" name="vrpaymentradio" value="<?php echo $p['id']; ?>" />
							<?php } ?>
							<label for="vrpayradio<?php echo $p['id']; ?>" class="vr-payment-title-label">
								<?php if( $p['icontype'] == 1 ) { ?>
									<i class="fa fa-<?php echo $p['icon']; ?>"></i>&nbsp;
								<?php } else if( $p['icontype'] == 2 ) { ?>
									<img src="<?php echo JUri::root().'components/com_cleverdine/assets/media/'.$p['icon']; ?>" />&nbsp;
								<?php } ?>

								<span><?php echo $p['name'] . (( strlen($cost_str) > 0 ) ? ' ('.$cost_str.')' : ''); ?></span>
							</label>
						</div>

						<?php if( strlen($p['prenote']) ) { ?>
							<div class="vr-payment-description" id="vr-payment-description<?php echo $p['id']; ?>" style="<?php echo ($payCount > 1 ? 'display: none;' : ''); ?>">
								<?php echo $p['prenote']; ?>
							</div>
						<?php } ?>

					</div>
					
				<?php } ?>
				
			</div>

		</div>

	<?php } ?>
	
	<!-- CONTINUE BUTTON -->
	
	<?php if( $at_least_one_time == 1 ) { ?>
		<button type="button" id="vrconfcontinuebutton" onClick="vrContinueButton();"><?php echo JText::_((!$step && !$skip_payments) ? 'VRCONTINUE' : 'VRTKCONFIRMORDER'); ?></button>
	<?php } else {
		// Doesn't set the continue button title in javascript
		$step = -1;
	} ?>
	
	<input type="hidden" name="date" value="<?php echo $dt_args['date']; ?>" />
	<input type="hidden" name="hourmin" id="vrhiddenhourmin" value="<?php echo $dt_args['hourmin']; ?>" />
	<input type="hidden" name="delivery" id="vrhiddendelivery" name="delivery" value="<?php echo $delivery_val; ?>" />

	<input type="hidden" name="option" value="com_cleverdine">
	<input type="hidden" name="task" value="savetakeawayorder">
</form>

<script type="text/javascript">

	var step = <?php echo $step; ?>;
	var num_payments = <?php echo count( $payments ); ?>;	
	var skip_payments = <?php echo $skip_payments; ?>;

	// END VARS

	jQuery(document).ready(function(){

		// CUSTOM FIELDS effect

		jQuery('.vrcustomfields .cf-value input, .vrcustomfields .cf-value textarea').on('change blur', function(){

			// add/remove has-value class during change and blur events

			if (jQuery(this).val().length) {
				jQuery(this).addClass('has-value');
			} else {
				jQuery(this).removeClass('has-value');
			}
		});

		//

		if( num_payments > 0 ) {
			jQuery("#vrpayradio0").prop('checked',true);
		}
		
		jQuery('#vrtkcalendar').val('<?php echo $dt_args['date']; ?>');
		
		vrServiceChanged( <?php echo (($delivery_val) ? 1 : 0); ?> );

	});

	function vrContinueButton() {
		
		if( step == 0 ) {
			jQuery(".vrcustomfields").fadeIn("normal");
			
			if( validateCustomFields() ) {
				jQuery("#vrpaymentsdiv").fadeIn("normal");
				jQuery("#vrconfcontinuebutton").html('<?php echo addslashes(JText::_('VRTKCONFIRMORDER')); ?>');
				step++;
			} 
		} else {
			if( validateCustomFields() ) {
				jQuery('#vrordererrordiv').fadeOut();
				
				if( !skip_payments ) {
						
					if( jQuery('input[name="vrpaymentradio"]:checked').length > 0 || jQuery('input[name="vrpaymentradio"]').length == 1 ) {
						step++;
					} else {
						jQuery(".vr-payment-title-label").addClass('vrrequired');
					}

				} else {
					step++;
				}
				
			} else {
				jQuery('#vrordererrordiv').html('<?php echo addslashes(JText::_('VRCONFRESFILLERROR')); ?>');
				jQuery('#vrordererrordiv').fadeIn();
			}
			
		}

		if( step > 1 || (step == 1 && skip_payments) ) {
			jQuery("#vrpayform").submit();
		}
	}

	// CUSTOM FIELDS

	jQuery(function(){
		var sel_format = "<?php echo $date_format; ?>";
		var df_separator = sel_format[1];

		sel_format = sel_format.replace(new RegExp("\\"+df_separator, 'g'), "");

		if( sel_format == "Ymd") {
			Date.prototype.format = "yy"+df_separator+"mm"+df_separator+"dd";
		} else if( sel_format == "mdY" ) {
			Date.prototype.format = "mm"+df_separator+"dd"+df_separator+"yy";
		} else {
			Date.prototype.format = "dd"+df_separator+"mm"+df_separator+"yy";
		}

		var today = new Date();

		jQuery(".vrinput.vrcalendar").datepicker({
			dateFormat: new Date().format,
		});
	});
		
	function validateCustomFields() {
		var vrvar = document.vrpayform;

		var ok = true;
		var is_email = false;
		var is_phone = false;
		var is_address = false;
		var is_zip = false;
		var req_on_delivery = false;

		var elem_to_animate = null;
		
		<?php

		if( count( $cfields ) > 0 ) {
			foreach( $cfields as $cf ) {
				if( intval( $cf['required'] ) == 1 ) {
		?>
					req_on_delivery = <?php echo ($cf['required_delivery'] ? 1 : 0); ?>;
					is_email = <?php echo ($cf['rule'] == VRCustomFields::EMAIL ? 1 : 0); ?>;
					is_phone = <?php echo ($cf['rule'] == VRCustomFields::PHONE_NUMBER ? 1 : 0); ?>;
					is_address = <?php echo ($cf['rule'] == VRCustomFields::ADDRESS ? 1 : 0); ?>;
					is_zip = <?php echo ($cf['rule'] == VRCustomFields::ZIP ? 1 : 0); ?>;
		<?php
					if( $cf['type'] == "text" || $cf['type'] == "textarea" || $cf['type'] == "date" ) {
		?>
						if( !vrvar.vrcf<?php echo $cf['id']; ?>.value.match(/\S/) ) {
							if( !req_on_delivery || DELIVERY_SERVICE ) {
								document.getElementById('vrcf<?php echo $cf['id']; ?>').style.color='#ff0000';
								ok = false;

								elem_to_animate = (elem_to_animate) ? elem_to_animate : jQuery('#vrcf<?php echo $cf['id']; ?>');

							} else {
								document.getElementById('vrcf<?php echo $cf['id']; ?>').style.color='';
							}
						} else if( is_email ) {

							if( !validateMailField( vrvar.vrcf<?php echo $cf['id']; ?>.value ) ) {
								document.getElementById('vrcf<?php echo $cf['id']; ?>').style.color='#ff0000';
								ok = false;

								elem_to_animate = (elem_to_animate) ? elem_to_animate : jQuery('#vrcf<?php echo $cf['id']; ?>');

							} else {
								document.getElementById('vrcf<?php echo $cf['id']; ?>').style.color='';
							}
						
						} else if( is_phone ) {

							var phone = vrvar.vrcf<?php echo $cf['id']; ?>.value;

							if (phone.length == 0 || /\D/.test(phone)) {
								document.getElementById('vrcf<?php echo $cf['id']; ?>').style.color='#ff0000';
								ok = false;

								elem_to_animate = (elem_to_animate) ? elem_to_animate : jQuery('#vrcf<?php echo $cf['id']; ?>');
							} else {
								document.getElementById('vrcf<?php echo $cf['id']; ?>').style.color='';
							}

						} else if( is_address  || is_zip ) {

							if( !DELIVERY_ADDRESS_STATUS ) {
								document.getElementById('vrcf<?php echo $cf['id']; ?>').style.color='#ff0000';
								ok = false;

								elem_to_animate = (elem_to_animate) ? elem_to_animate : jQuery('#vrcf<?php echo $cf['id']; ?>');

							} else {
								document.getElementById('vrcf<?php echo $cf['id']; ?>').style.color='';
							}

						} else {
							document.getElementById('vrcf<?php echo $cf['id']; ?>').style.color='';
						}

		<?php
					} else if( $cf['type'] == "select" ) {
		?>
						if( !vrvar.vrcf<?php echo $cf['id']; ?>.value.match(/\S/) ) {
							if( !req_on_delivery || DELIVERY_SERVICE ) {
								document.getElementById('vrcf<?php echo $cf['id']; ?>').style.color='#ff0000';
								ok = false;

								elem_to_animate = (elem_to_animate) ? elem_to_animate : jQuery('#vrcf<?php echo $cf['id']; ?>');

							} else {
								document.getElementById('vrcf<?php echo $cf['id']; ?>').style.color='';
							}
						} else {
							document.getElementById('vrcf<?php echo $cf['id']; ?>').style.color='';
						}
		<?php
					} else if( $cf['type'] == "checkbox" ) {
		?>
						if( vrvar.vrcf<?php echo $cf['id']; ?>.checked ) {
							document.getElementById('vrcf<?php echo $cf['id']; ?>').style.color='';
						} else {
							if( !req_on_delivery || DELIVERY_SERVICE ) {
								document.getElementById('vrcf<?php echo $cf['id']; ?>').style.color='#ff0000';
								ok = false;

								elem_to_animate = (elem_to_animate) ? elem_to_animate : jQuery('#vrcf<?php echo $cf['id']; ?>');

							} else {
								document.getElementById('vrcf<?php echo $cf['id']; ?>').style.color='';
							}
						}
		<?php
					}
				}
			}
		}
		?>

		if (elem_to_animate) {
			jQuery('html,body').stop(true, true).animate({
				scrollTop: (elem_to_animate.offset().top-100)
			}, {
				duration:'medium'
			}).promise().done(function(){
				var name = elem_to_animate.attr('id');
				jQuery('input[name="'+name+'"], textarea[name="'+name+'"]').focus();
			});
		}

		return ok;
	}

	function validateMailField(email) {
		var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		return re.test(email);
	}

	jQuery(document).ready(function(){
		jQuery(".vr-phones-select").select2({
			allowClear: true,
			width: 100,
			minimumResultsForSearch: -1,
			formatResult: format,
			formatSelection: format,
			escapeMarkup: function(m) { return m; }
		});
	});

	function format(state) {
		if(!state.id) return state.text; // optgroup

		return '<img class="vr-opt-flag" src="<?php echo JUri::root(); ?>components/com_cleverdine/assets/css/flags/' + state.id.toLowerCase().split("_")[1] + '.png"/>' + state.text;
	}

	var PAY_DESC_VISIBLE = <?php echo (count($payments) > 1 ? 0 : 1); ?>;

	function vrPaymentRadioChanged(id, close_effect) {
		jQuery(".vr-payment-title-label").removeClass('vrrequired');

		if( close_effect ) {
			jQuery('.vr-payment-description').slideUp();
		} else {
			jQuery('.vr-payment-description').hide();
		}

		if( PAY_DESC_VISIBLE ) {
			jQuery('#vr-payment-description'+id).show();
		} else {
			jQuery('#vr-payment-description'+id).slideDown();
		}

		PAY_DESC_VISIBLE = !close_effect;

	}

	// TAKEAWAY FIELDS

	var TK_GRAND_TOTAL = <?php echo $grand_total; ?>;
	var TK_DELIVERY_COST = <?php echo $delivery_cost; ?>;
	var TK_FREE_DELIVERY = <?php echo $free_delivery; ?>;
	var TK_DELIVERY_SURCHARGE = 0;
	var TK_PICKUP_COST = <?php echo $pickup_cost; ?>;

	function vrServiceChanged(status) {
		if( status == 1 ) {
			jQuery('#vrtkconfcartprice').text( '<?php echo $_symb_arr[0]; ?>'+(TK_GRAND_TOTAL+TK_DELIVERY_COST+TK_DELIVERY_SURCHARGE).toFixed(2)+'<?php echo $_symb_arr[1]; ?>' );
			jQuery('#vrtkconfcartservice').text('<?php echo $_symb_arr[0]; ?>'+(TK_DELIVERY_COST+TK_DELIVERY_SURCHARGE).toFixed(2)+'<?php echo $_symb_arr[1]; ?>');
			jQuery('#vrhiddendelivery').val(1);

			jQuery('.vrtk-delivery-field').show();
			
			DELIVERY_SERVICE = 1;
			DELIVERY_ADDRESS_STATUS = 0; // need a validation
		} else {
			jQuery('#vrtkconfcartprice').text( '<?php echo $_symb_arr[0]; ?>'+(TK_GRAND_TOTAL+TK_PICKUP_COST).toFixed(2)+'<?php echo $_symb_arr[1]; ?>' );
			jQuery('#vrtkconfcartservice').text('<?php echo $_symb_arr[0]; ?>'+TK_PICKUP_COST.toFixed(2)+'<?php echo $_symb_arr[1]; ?>');
			jQuery('#vrhiddendelivery').val(0);

			jQuery('.vrtk-delivery-field').hide();
			
			DELIVERY_SERVICE = 0;
			DELIVERY_ADDRESS_STATUS = 1; // don't need a validation
		}
	}

	function vrDateChanged() {
		jQuery('#vrtkdatetimeform').submit();
	} 

	function vrTimeChanged() {
		jQuery('#vrhiddenhourmin').val(jQuery('#vrtktime').val());
	}

	// ADDRESS VALIDATION

	var DELIVERY_SERVICE = <?php echo ($delivery_val ? 0 : 1); ?>;
	var DELIVERY_ADDRESS_STATUS = (DELIVERY_SERVICE ? 0 : 1);

	var LAST_COORDS_FOUND = {lat: 0, lng: 0};
	var LAST_COMPONENTS_FOUND = {};

	jQuery(document).ready(function(){

		jQuery('.vrtk-address-field').on('change', function(){
			vrValidateAddress(vrGetAddressString());
		});

		jQuery('.vrtk-address-field').prop('autocomplete', 'off');

		if( jQuery('.vrtk-address-field').length && jQuery('.vrtk-address-field').val().length ) {
			jQuery('.vrtk-address-field').trigger('change');
		}

		jQuery('#vrtk-user-address-sel').select2({
			placeholder: '<?php echo addslashes(JText::_('VRTKDELIVERYADDRPLACEHOLDER')); ?>',
			allowClear: true,
			width: 'resolve',
		});

		jQuery('#vrtk-user-address-sel').on('change', function(){
			if( jQuery(this).val().length ) {
				jQuery('.vrtk-delivery-field').find('.vrinput').val('');
			}
			jQuery('.vrtk-address-field').val(jQuery(this).val()).trigger('change');
		});

		// zip

		jQuery('.vrtk-zip-field').on('change', function() {

			if (jQuery('.vrtk-address-field').length == 0) {
				
				vrIsAddressAccepted(LAST_COORDS_FOUND, jQuery(this).val(), LAST_COMPONENTS_FOUND);

			} else {

				vrValidateAddress(vrGetAddressString());

			}
		});

		if (jQuery('.vrtk-zip-field').length && jQuery('.vrtk-zip-field').val().length) {
			jQuery('.vrtk-zip-field').trigger('change');
		}

	});

	function vrGetAddressString() {

		var parts = [];

		jQuery('.vrtk-address-field, .vrtk-zip-field').each(function() {

			var val = jQuery(this).val();

			if (val.length) {
				parts.push(val);
			}

		});

		return parts.join(' ');
	}

	function vrIsDeliveryMap() {
		return (typeof VRTK_ADDR_MARKER !== "undefined");
	}

	function vrValidateAddress(address) {

		jQuery('.vrtk-address-response').hide();
		jQuery('.vrtk-address-response').removeClass('fail');

		DELIVERY_ADDRESS_STATUS = 0;
		TK_DELIVERY_SURCHARGE = 0;
		vrServiceChanged(parseInt(jQuery('#vrhiddendelivery').val()));
		
		// MAP MODULE HANDLER
		if( vrIsDeliveryMap() ) {
			if( VRTK_ADDR_MARKER !== null ) {
				VRTK_ADDR_MARKER.setMap(null);
			}
		}

		if( address.length == 0 ) {
			return false;
		}

		var geocoder = new google.maps.Geocoder();

		var coord = null;

		geocoder.geocode({'address': address}, function(results, status) {
			if( status == "OK" ) {
				coord = {
					"lat": results[0].geometry.location.lat(),
					"lng": results[0].geometry.location.lng(),
				};

				var components = { fullAddress: results[0].formatted_address };

				var zip = '';
				jQuery.each(results[0].address_components, function(){
					if( this.types[0] == "postal_code") {
						zip = this.short_name;
						
						components.zip = this.short_name;
					} else if( this.types[0] == "locality" ) {
						components.city = this.short_name;
					} else if( this.types[0] == "administrative_area_level_2" ) {
						components.state = this.short_name;
					} else if( this.types[0] == "country" ) {
						components.country_2_code = this.short_name;
						components.country = this.long_name;
					} else if( this.types[0] == "street_number" || this.types[0] == "premise" ) {
						components.street_number = this.short_name;
					} else if( this.types[0] == "route" ) {
						components.route = this.short_name;
					}
				});

				if( !components.hasOwnProperty('street_number') || !components.hasOwnProperty('route') ) {
					jQuery('.vrtk-address-response').html('<?php echo addslashes(JText::_('VRTKDELIVERYADDRNOTFULL')); ?>');
					jQuery('.vrtk-address-response').addClass('fail');
					jQuery('.vrtk-address-response').show();
					return false;
				}

				// MAP MODULE HANDLER
				if( vrIsDeliveryMap() ) {
					VRTK_ADDR_MARKER = new google.maps.Marker({
						position: coord,
					});
					VRTK_ADDR_MARKER.setAnimation(google.maps.Animation.DROP);
					VRTK_ADDR_MARKER.setMap(VRTK_MAP);

					VRTK_MAP.setCenter(VRTK_ADDR_MARKER.position);
				}

				// VALIDATION

				vrIsAddressAccepted(coord, zip, components);
			}
		});
	}

	function vrIsAddressAccepted(coord, zip, components) {

		DELIVERY_ADDRESS_STATUS = 0;

		LAST_COMPONENTS_FOUND = components;
		LAST_COORDS_FOUND = coord;

		jQuery.noConflict();

		jQuery('.vrtk-address-response').hide();
		jQuery('.vrtk-address-response').removeClass('fail');
			
		var jqxhr = jQuery.ajax({
			type: "POST",
			url: '<?php echo JRoute::_("index.php?option=com_cleverdine&task=get_location_delivery_info&tmpl=component"); ?>',
			data: { lat: coord.lat, lng: coord.lng, zip: zip, address: components }
		}).done(function(resp) {
			var obj = jQuery.parseJSON(resp);

			if( obj.status == 1 ) {

				if( obj.area.minCost > TK_GRAND_TOTAL ) {

					jQuery('.vrtk-address-response').html('<?php echo addslashes(JText::_('VRTKDELIVERYMINCOST')); ?>'.replace('%s', obj.area.minCostLabel));
					jQuery('.vrtk-address-response').addClass('fail');
					jQuery('.vrtk-address-response').show();

				} else {
					if( !TK_FREE_DELIVERY ) {
						TK_DELIVERY_SURCHARGE = obj.area.charge;

						if( TK_DELIVERY_SURCHARGE != 0 ) {
							jQuery('.vrtk-address-response').html('<?php echo addslashes(JText::_('VRTKDELIVERYSURCHARGE')); ?>'.replace('%s', obj.area.chargeLabel));
							jQuery('.vrtk-address-response').show();
						}

						vrServiceChanged(parseInt(jQuery('#vrhiddendelivery').val()));
					}

					DELIVERY_ADDRESS_STATUS = 1;

				}

			} else {

				jQuery('.vrtk-address-response').html(obj.error);
				jQuery('.vrtk-address-response').addClass('fail');
				jQuery('.vrtk-address-response').show();
				
			}

		}).fail(function(){
			alert('<?php echo addslashes(JText::_('VRTKADDITEMERR2')); ?>');
		});

	}

	// CALENDAR

	<?php if( $is_date_allowed ) { ?>

		var specialDays = <?php echo json_encode($cal_special_days); ?>;
		var closingDays = <?php echo json_encode($closing_days); ?>;

		var sel_format = "<?php echo $date_format; ?>";
		var df_separator = sel_format[1];

		sel_format = sel_format.replace(new RegExp("\\"+df_separator, 'g'), "");

		if( sel_format == "Ymd") {
			Date.prototype.format = "yy"+df_separator+"mm"+df_separator+"dd";
		} else if( sel_format == "mdY" ) {
			Date.prototype.format = "mm"+df_separator+"dd"+df_separator+"yy";
		} else {
			Date.prototype.format = "dd"+df_separator+"mm"+df_separator+"yy";
		}

		var today = new Date();

		var today_no_hour_no_min = getDate('<?php echo date($date_format, time()); ?>');

		jQuery("#vrtkcalendar:input").datepicker({
			minDate: today,
			dateFormat: today.format,
			beforeShowDay: setupCalendar,
			onSelect: vrDateChanged
		});

		function setupCalendar(date) {
				
			var enabled = false;
			var clazz = "";
			var ignore_cd = 0;
			
			if( today_no_hour_no_min.valueOf() > date.valueOf() ) {
				return [false,""];
			}

			for( var i = 0; i < specialDays.length && !enabled; i++ ) {
				if( specialDays[i]['start_ts'] == -1 ) {
					if( specialDays[i]['days_filter'].length == 0 ) {
						if( specialDays[i]['markoncal'] == 1 ) {
							clazz = "vrtdspecialday";
						}
						ignore_cd = specialDays[i]['ignoreclosingdays'];
					} else if( contains( specialDays[i]['days_filter'], date.getDay() ) ) {
						if( specialDays[i]['markoncal'] == 1 ) {
							clazz = "vrtdspecialday";
						}
						ignore_cd = specialDays[i]['ignoreclosingdays'];
					}
				}
				
				_ds = getDate(specialDays[i]['start_ts']);
				_de = getDate(specialDays[i]['end_ts']);
				
				if( _ds.valueOf() <= date.valueOf() && date.valueOf() <= _de.valueOf() ) {
					if( specialDays[i]['days_filter'].length == 0 ) {
						if( specialDays[i]['markoncal'] == 1 ) {
							clazz = "vrtdspecialday";
						}
						ignore_cd = specialDays[i]['ignoreclosingdays'];
					} else if( contains( specialDays[i]['days_filter'], date.getDay() ) ) {
						if( specialDays[i]['markoncal'] == 1 ) {
							clazz = "vrtdspecialday";
						}
						ignore_cd = specialDays[i]['ignoreclosingdays'];
					}
				}
			}
			
			enabled = true;
			if( ignore_cd == 0 ) {
				for( var i = 0; i < closingDays.length; i++ ) {
					var _d = getDate( closingDays[i]['date'] );
					
					if( closingDays[i]['freq'] == 0 ) {
						if( _d.valueOf() == date.valueOf() ) {
							return [false,""];
						}
					} else if( closingDays[i]['freq'] == 1 ) {
						if( _d.getDay() == date.getDay() ) {
							return [false,""];
						}
					} else if( closingDays[i]['freq'] == 2 ) {
						if( _d.getDate() == date.getDate() ) {
							return [false,""];
						} 
					} else if( closingDays[i]['freq'] == 3 ) {
						if( _d.getDate() == date.getDate() && _d.getMonth() == date.getMonth() ) {
							return [false,""];
						} 
					}
				}
			}
			
			return [enabled,clazz];
		}

		function getDate(day) {
			var formats = today.format.split(df_separator);
			var date_exp = day.split(df_separator);
			
			var _args = new Array();
			for( var i = 0; i < formats.length; i++ ) {
				_args[formats[i]] = parseInt( date_exp[i] );
			}
			
			return new Date( _args['yy'], _args['mm']-1, _args['dd'] );
		}

		function contains(arr,key) {
			for( var i = 0; i < arr.length; i++ ) {
				if( arr[i] == key ) {
					return true;
				}
			}
			
			return false;
		}

	<?php } ?>

</script>

<?php } ?>
