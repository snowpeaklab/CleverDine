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

// get login return URL
$return_url_to_encode = cleverdine::getLoginReturnURL();

$active_tab = JFactory::getSession()->get('allorderstab', 1, 'vre');

?>

<?php if( $this->user->guest ) { ?>
	
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
	
	<div class="vr-allorders-mainlogin">

		<?php if (($tk_reg = cleverdine::isTakeAwayRegistrationEnabled()) || cleverdine::isRegistrationEnabled()) { ?>
	
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
					<input type="hidden" name="task" value="<?php echo ($tk_reg ? 'tkregisteruser' : 'registeruser'); ?>" />
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
		
	</div>
	
<?php } else { ?>
	
	<div class="vr-allorders-userhead">
		<div class="vr-allorders-userleft">
			<h2><?php echo JText::sprintf('VRALLORDERSTITLE', $this->user->name); ?></h2>
		</div>
		<div class="vr-allorders-userright">
			<button type="button" class="vr-allorders-logout" onClick="document.location.href='<?php echo JRoute::_('index.php?option=com_cleverdine&task=userlogout'); ?>';">
				<?php echo JText::_('VRLOGOUT'); ?>
			</button>
		</div>
	</div>

	<?php

	$is_restaurant	= cleverdine::isRestaurantEnabled();
	$is_takeaway	= cleverdine::isTakeAwayEnabled();

	?>

	<script type="text/javascript">

		function switchOrderTab(link, tab) {

			jQuery.noConflict();
		
			var jqxhr = jQuery.ajax({
				type: "POST",
				url: "<?php echo JRoute::_('index.php?option=com_cleverdine&task=register_allorders_tab&tmpl=component'); ?>",
				data: { id: tab }
			}).done(function(resp){

			}).fail(function(){

			});

			jQuery('.vr-allorders-switch-tabs .switch-box').removeClass('active');
			jQuery(link).parent().addClass('active');

			jQuery('.vr-allorders-wrapper').hide();
			jQuery('#vrboxwrapper'+tab).show();

		}

	</script>

	<div class="vr-allorders-switch-tabs">
		<?php if( $is_restaurant ) { ?>
			<div class="switch-box <?php echo (!$is_takeaway || $active_tab == 1 ? 'active' : ''); ?>">
				<a href="javascript: void(0);" onClick="switchOrderTab(this, 1);">
					<?php echo JText::_('VRALLORDERSRESTAURANTHEAD'); ?>
				</a>
			</div>
		<?php } ?>

		<?php if( $is_takeaway ) { ?>
			<div class="switch-box <?php echo (!$is_restaurant || $active_tab == 2 ? 'active' : ''); ?>">
				<a href="javascript: void(0);" onClick="switchOrderTab(this, 2);">
					<?php echo JText::_('VRALLORDERSTAKEAWAYHEAD'); ?>
				</a>
			</div>
		<?php } ?>
	</div>
	
	<?php
	
	$date_format 	= cleverdine::getDateFormat();
	$time_format 	= cleverdine::getTimeFormat();
	$curr_symb 		= cleverdine::getCurrencySymb();
	$symb_pos 		= cleverdine::getCurrencySymbPosition();
		
	?>

	<?php if( $is_restaurant ) { ?>

		<div class="vr-allorders-wrapper" id="vrboxwrapper1" style="<?php echo (!$is_takeaway || $active_tab == 1  ? '' : 'display:none;'); ?>">
			
			<?php if( count($this->orders) == 0 ) { ?>
				<div class="vr-allorders-void"><?php echo JText::_('VRALLORDERSVOID'); ?></div>
			<?php } else { ?>
				
				<div class="vr-allorders-box">

					<div class="vr-allorders-tinylist">
						<?php foreach( $this->orders as $ord ) { 
							$cost = $ord['deposit'];
							if( $ord['bill_closed'] ) {
								$cost = $ord['bill_value']; 
							}
							?>
							<div class="list-order-bar">

								<div class="order-oid">
									<?php echo substr($ord['sid'], 0, 2).'#'.substr($ord['sid'], -2, 2); ?>
								</div>

								<div class="order-summary">
									<div class="summary-status <?php echo strtolower($ord['status']); ?>">
										<?php echo strtoupper(JText::_('VRRESERVATIONSTATUS'.($ord['status']))); ?>
									</div>
									<div class="summary-service">
										<?php echo $ord['people']." ".JText::_('VRORDERPEOPLE'); ?>
									</div>
								</div>

								<div class="order-purchase">
									<div class="purchase-date">
										<?php
										$ord['created_on'] = ($ord['created_on'] > 0 ? $ord['created_on'] : $ord['checkin_ts']);

										$date_str = cleverdine::formatTimestamp('', $ord['created_on']);
										if( empty($date_str) ) {
											$arr = getdate($ord['created_on']);
											$months = array(
												'VRMONTHONE', 'VRMONTHTWO', 'VRMONTHTHREE',
												'VRMONTHFOUR', 'VRMONTHFIVE', 'VRMONTHSIX',
												'VRMONTHSEVEN', 'VRMONTHEIGHT', 'VRMONTHNINE',
												'VRMONTHTEN', 'VRMONTHELEVEN', 'VRMONTHTWELVE',
											);
											$date_str = JText::_($months[$arr['mon']-1])." ".$arr['mday'].", ".$arr['year'];
										}
										echo $date_str;
										?>
									</div>
									<div class="purchase-price">
										<?php if( $cost > 0 ) {
											echo cleverdine::printPriceCurrencySymb($cost, $curr_symb, $symb_pos);
										} ?>
									</div>
								</div>

								<div class="order-view-button">
									<a href="<?php echo JRoute::_('index.php?option=com_cleverdine&view=order&ordnum='.$ord['id'].'&ordkey='.$ord['sid'].'&ordtype=0'); ?>">
										<?php echo JText::_('VRVIEWORDER'); ?>					
									</a>
								</div>

							</div>

						<?php } ?>
					</div>
					
					<form action="<?php echo JRoute::_('index.php?option=com_cleverdine&view=allorders'); ?>" method="POST">
						<?php echo JHTML::_( 'form.token' ); ?>
						<div class="vr-list-pagination"><?php echo $this->ordersNavigation; ?></div>
						<input type="hidden" name="option" value="com_cleverdine"/>
						<input type="hidden" name="view" value="allorders"/>
					</form>
					
				</div>
				
			<?php } ?>

		</div>

	<?php } ?>
	
	<?php if( $is_takeaway ) { ?>

		<div class="vr-allorders-wrapper" id="vrboxwrapper2" style="<?php echo (!$is_restaurant || $active_tab == 2 ? '' : 'display:none;'); ?>">
		
			<?php if( count($this->tkorders) == 0 ) { ?>
				<div class="vr-allorders-void"><?php echo JText::_('VRALLTKORDERSVOID'); ?></div>
			<?php } else { ?>
				
				<div class="vr-allorders-box">
					
					<div class="vr-allorders-tinylist">
						<?php foreach( $this->tkorders as $ord ) { ?>

							<div class="list-order-bar">

								<div class="order-oid">
									<?php echo substr($ord['sid'], 0, 2).'#'.substr($ord['sid'], -2, 2); ?>
								</div>

								<div class="order-summary">
									<div class="summary-status <?php echo strtolower($ord['status']); ?>">
										<?php echo strtoupper(JText::_('VRRESERVATIONSTATUS'.($ord['status']))); ?>
									</div>
									<div class="summary-service">
										<?php echo JText::_($ord['delivery_service'] ? 'VRTKORDERDELIVERYOPTION' : 'VRTKORDERPICKUPOPTION'); ?>
									</div>
								</div>

								<div class="order-purchase">
									<div class="purchase-date">
										<?php
										$ord['created_on'] = ($ord['created_on'] > 0 ? $ord['created_on'] : $ord['checkin_ts']);

										$date_str = cleverdine::formatTimestamp('', $ord['created_on']);
										if( empty($date_str) ) {
											$arr = getdate($ord['created_on']);
											$months = array(
												'VRMONTHONE', 'VRMONTHTWO', 'VRMONTHTHREE',
												'VRMONTHFOUR', 'VRMONTHFIVE', 'VRMONTHSIX',
												'VRMONTHSEVEN', 'VRMONTHEIGHT', 'VRMONTHNINE',
												'VRMONTHTEN', 'VRMONTHELEVEN', 'VRMONTHTWELVE',
											);
											$date_str = JText::_($months[$arr['mon']])." ".$arr['mday'].", ".$arr['year'];
										}
										echo $date_str;
										?>
									</div>
									<div class="purchase-price">
										<?php if( $ord['total_to_pay'] > 0 ) {
											echo cleverdine::printPriceCurrencySymb($ord['total_to_pay'], $curr_symb, $symb_pos);
										} ?>
									</div>
								</div>

								<div class="order-view-button">
									<a href="<?php echo JRoute::_('index.php?option=com_cleverdine&view=order&ordnum='.$ord['id'].'&ordkey='.$ord['sid'].'&ordtype=1'); ?>">
										<?php echo JText::_('VRVIEWORDER'); ?>					
									</a>
								</div>

							</div>

						<?php } ?>
					</div>
					
					<form action="<?php echo JRoute::_('index.php?option=com_cleverdine&view=allorders'); ?>" method="POST">
						<?php echo JHTML::_( 'form.token' ); ?>
						<div class="vr-list-pagination"><?php echo $this->tkordersNavigation; ?></div>
						<input type="hidden" name="option" value="com_cleverdine"/>
						<input type="hidden" name="view" value="allorders"/>
					</form>
				
				</div>
				
			<?php } ?>

		</div>
	
	<?php } ?>
		
<?php } ?>
