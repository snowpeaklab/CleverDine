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

$args = $this->args;
$cfields = $this->customFields;
$payments = $this->payments;
$any_coupon = $this->anyCoupon;
$user = $this->user;

$config = UIFactory::getConfig();

if( count($cfields) > 0 ) {
	foreach( $cfields as $cf ) {
		if( !empty( $cf['poplink'] ) ) {
			cleverdine::load_fancybox();
		}
	}
}

$_h_m = explode(':', $args['hourmin']);
$args['hour'] = -1;
$args['min'] = 0;
if( count( $_h_m ) == 2 ) {
	$args['hour'] = $_h_m[0];
	$args['min'] = $_h_m[1];
}

$date_format = cleverdine::getDateFormat();

$nowdf = $date_format;
$nowdf = str_replace( 'd', '%d', $nowdf );
$nowdf = str_replace( 'm', '%m', $nowdf );
$nowdf = str_replace( 'Y', '%Y', $nowdf );

$step = 0;

$resrequirements = (int)cleverdine::getReservationRequirements();

$skip_payments = ( ( count( $payments ) == 0 ) ? 1 : 0 ); // 1 = skip, 0 = not skip

$_total_deposit = cleverdine::getDepositPerReservation();
$_perperson_deposit = cleverdine::getDepositPerPerson();

$sp_day = cleverdine::getSpecialDaysForDeposit($args, 1);

if( $sp_day != -1 && count( $sp_day ) > 0 ) {
	$_td = 0;
	$_pd = 0;
	for( $i = 0, $n = count($sp_day); $i < $n; $i++ ) {
		if( $_td < $sp_day[$i]['depositcost'] ) {
			$_td = $sp_day[$i]['depositcost'];
			$_pd = $sp_day[$i]['perpersoncost'];
		}
	}

	$_total_deposit = $_td;
	$_perperson_deposit = $_pd;
}

if( $_perperson_deposit == 1 ) {
	$_total_deposit *= $args['people'];
}

$session = JFactory::getSession();
$coupon = $session->get('vr_coupon_data', '');
if( !empty($coupon) ) {
	
	if( $coupon['percentot'] == 2 && ( $_total_deposit - $coupon['value'] <= 0 ) ) {
		$skip_payments = 1;
	} else if ( cleverdine::getApplyCouponType() == 2 && $coupon['percentot'] == 1 && $coupon['value'] >= 100 ) {
		$skip_payments = 1;
	}
}

if( $_total_deposit == 0 ) {
	$skip_payments = 1;
}

$login_req = cleverdine::getLoginRequirements();

// get login return URL
$return_url_to_encode = cleverdine::getLoginReturnURL('index.php?option=com_cleverdine&task=confirmres&'.http_build_query($args));

?>

<div class="vrstepbardiv">

	<div class="vrstepactive">
		<div class="vrstep-inner">
			<a href="<?php echo JRoute::_('index.php?option=com_cleverdine&view=restaurants'); ?>">
				<span class="vrsteptitle"><?php echo JText::_('VRSTEPONETITLE'); ?></span>
				<span class="vrstepsubtitle"><?php echo JText::_('VRSTEPONESUBTITLE'); ?></span>
			</a>
		</div>
	</div>

	<div class="vrstepactive">
		<div class="vrstep-inner">
			<a href="<?php echo JRoute::_('index.php?option=com_cleverdine&task=search&date='.$args['date'].'&hourmin='.$args['hourmin'].'&people='.$args['people']); ?>">
				<span class="vrsteptitle"><?php echo JText::_('VRSTEPTWOTITLE'); ?></span>
				<span class="vrstepsubtitle">
					<?php echo ($resrequirements == 0 ? JText::_('VRSTEPTWOSUBTITLEZERO') : ($resrequirements == 1 ? JText::_('VRSTEPTWOSUBTITLEONE') : JText::_('VRSTEPTWOSUBTITLETWO'))); ?>
				</span>
			</a>
		</div>
	</div>
	
	<div class="vrstepactive step-current">
		<div class="vrstep-inner">
			<span class="vrsteptitle"><?php echo JText::_('VRSTEPTHREETITLE'); ?></span>
			<span class="vrstepsubtitle"><?php echo JText::_('VRSTEPTHREESUBTITLE'); ?></span>
		</div>
	</div>
	
</div>

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
	
	<?php if( cleverdine::isRegistrationEnabled() ) { ?>
	
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
						<span class="vrloginsplabel">&nbsp;</span>
						<span class="vrloginspinput">
							<button type="submit" class="vrbooknow" name="registerbutton" onClick="return vrValidateRegistrationFields();"><?php echo JText::_('VRREGSIGNUPBTN'); ?></button>
						</span>
					</div>
				</div>
		
				<input type="hidden" name="option" value="com_cleverdine" />
				<input type="hidden" name="task" value="registeruser" />
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

	<?php if( $any_coupon == 1 ) { ?>
		<form action="<?php echo JRoute::_('index.php?option=com_cleverdine&task=confirmres'); ?>" name="vrcouponform" method="POST">
			<div class="vrcouponcodediv">
				<h3 class="vrheading3"><?php echo JText::_('VRENTERYOURCOUPON'); ?></h3>
				<input type="text" class="vrcouponcodetext"  name="couponkey"/>
				<button type="submit" class="vrcouponcodesubmit"><?php echo JText::_('VRAPPLYCOUPON'); ?></button>
			</div>
			
			<input type="hidden" name="date" value="<?php echo $args['date']; ?>"/>
			<input type="hidden" name="hourmin" value="<?php echo $args['hourmin']; ?>"/>
			<input type="hidden" name="people" value="<?php echo $args['people']; ?>"/>
			<input type="hidden" name="table" value="<?php echo $args['table']; ?>"/>
			
			<input type="hidden" name="option" value="com_cleverdine"/>
			<input type="hidden" name="task" value="confirmres"/>
		</form>
	<?php } ?>
	
	<?php 
	list($hour, $min) = explode(":", $args['hourmin']);
	$ts = cleverdine::createTimestamp($args['date'], $hour, $min); 
	?>
	<div class="vrresultsummarydiv confirmation">
		<div class="vrresultsuminnerdiv" id="vrresultsumdivdate">
			<span class="vrresultsumlabelsp" id="vrresultsumspanlbldate"><?php echo JText::_('VRDATE'); ?></span>
			<span class="vrresultsumvaluesp" id="vrresultsumspanvaldate"><?php echo date($date_format, $ts); ?></span>
		</div>
		
		<div class="vrresultsuminnerdiv" id="vrresultsumdivhour">
			<span class="vrresultsumlabelsp" id="vrresultsumspanlblhour"><?php echo JText::_('VRTIME'); ?></span>
			<span class="vrresultsumvaluesp" id="vrresultsumspanvalhour"><?php echo date(cleverdine::getTimeFormat(), $ts); ?></span>
		</div>
		
		<div class="vrresultsuminnerdiv" id="vrresultsumdivpeople">
			<span class="vrresultsumlabelsp" id="vrresultsumspanlblpeople"><?php echo JText::_('VRPEOPLE'); ?></span>
			<span class="vrresultsumvaluesp" id="vrresultsumspanvalpeople"><?php echo $args["people"]; ?></span>
		</div>
		
		<?php if( $_total_deposit > 0 ) { ?>
			<div class="vrresultsuminnerdiv" id="vrresultsumdivdeposit">
				<span class="vrresultsumlabelsp" id="vrresultsumspanlbldeposit"><?php echo JText::_('VRORDERRESERVATIONCOST'); ?>:</span>
				<span class="vrresultsumvaluesp" id="vrresultsumspanvaldeposit"><?php echo cleverdine::printPriceCurrencySymb($_total_deposit); ?></span>
			</div>
		<?php } ?>
	</div>
	
	<div class="vrseparatordiv"></div>
	
	<div id="vrordererrordiv" class="vrordererrordiv" style="display: none;"></div>
	
	<form action="<?php echo JRoute::_('index.php?option=com_cleverdine&task=saveorder'); ?>" id="vrpayform" name="vrpayform" method="GET">
	
		<?php if( count($cfields) > 0 ) { ?>
			<div class="vrcustomfields">
				<?php
				$currentUser = JFactory::getUser();
				$juseremail = !empty($currentUser->email) ? $currentUser->email : "";
				
				$user_fields = array();
				if( !empty($user['fields']) ) {
					$user_fields = $user['fields'];
				}
				
				$show_phones_prefix = $config->getBool('phoneprefix');
				
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
					}
					
					if( $cf['type'] == "text") {
						if( empty($textval) && $cf['rule'] == VRCustomFields::EMAIL ) {
							$textval = $juseremail;
						}

						$onkeypress = "";
						
					?>
						<div>

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
								<input type="text" name="vrcf<?php echo $cf['id']; ?>" id="vrcfinput<?php echo $cf['id']; ?>" value="<?php echo $textval; ?>" size="40" class="vrinput <?php echo (empty($textval) ? '' : 'has-value'); ?> <?php echo($cf['rule'] == VRCustomFields::ADDRESS ? 'vrtk-address-field' : ''); ?>" <?php echo $onkeypress; ?>/>

								<span class="cf-highlight"><!-- input highlight --></span>

								<span class="cf-bar"><!-- input bar --></span>

								<span class="cf-label"><?php echo $isreq; ?><?php echo $fname; ?> </span>
							</span>

						</div>

					<?php } else if( $cf['type'] == "textarea" ) { ?>

						<div>
							<span class="cf-value cf-textarea">
								<textarea name="vrcf<?php echo $cf['id']; ?>" class="vrtextarea"><?php echo $textval; ?></textarea>

								<span class="cf-highlight"><!-- input highlight --></span>

								<span class="cf-bar"><!-- input bar --></span>

								<span class="cf-label"><?php echo $isreq; ?><?php echo $fname; ?> </span>
							</span>
						</div>

					<?php } else if( $cf['type'] == "date" ) { ?>

						<div>
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

						<div>
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

						<div>
							<span class="<?php echo $cfsepclass; ?>"><?php echo JText::_($cf['t_name']); ?></span>
						</div>

					<?php } else if( $cf['type'] == "checkbox" ) { ?>

						<div>
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
	
		<?php if( ($payCount = count($payments)) > 0 && !$skip_payments ) { ?>

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
		
		<input type="hidden" name="date" value="<?php echo $args['date']; ?>"/>
		<input type="hidden" name="hourmin" value="<?php echo $args['hourmin']; ?>"/>
		<input type="hidden" name="people" value="<?php echo $args['people']; ?>"/>
		<input type="hidden" name="table" value="<?php echo $args['table']; ?>"/>
		
		<button type="button" id="vrconfcontinuebutton" onClick="vrContinueButton();">
			<?php echo JText::_((!$step && !$skip_payments) ? 'VRCONTINUE' : 'VRCONFIRMRESERVATION'); ?>
		</button>
	
		<input type="hidden" name="option" value="com_cleverdine">
		<input type="hidden" name="task" value="saveorder">
	</form>

	<script>
	
	var step = <?php echo $step; ?>;
	var skip_payments = <?php echo $skip_payments; ?>;
	var num_payments = <?php echo count( $payments ); ?>;		
	
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
			jQuery("#vrpayradio0").prop('checked',true)
		}

	});
		
	function continueButton() {
		
		if( step == 0 ) {
			if( validateCustomFields() ) {
				jQuery('#vrordererrordiv').fadeOut();
				if( skip_payments == 0 ) {
					jQuery("#vrpaymentsdiv").fadeIn("normal");
					jQuery("#vrconfcontinuebutton").html('<?php echo addslashes(JText::_('VRCONFIRMRESERVATION')); ?>');
				}
				step++;
			} else {
				jQuery('#vrordererrordiv').html('<?php echo addslashes(JText::_('VRCONFRESFILLERROR')); ?>');
				jQuery('#vrordererrordiv').fadeIn();
			}
		} else {
			ok = false;
			for( var i = 0; i < num_payments && !ok; i++ ) {
				ok = jQuery('#vrpayradio'+i).is(':checked');
			}	
			if( ok ) {
				if( validateCustomFields() ) {
					jQuery('#vrordererrordiv').fadeOut();
					step++;
				} else {
					jQuery('#vrordererrordiv').html('<?php echo addslashes(JText::_('VRCONFRESFILLERROR')); ?>');
					jQuery('#vrordererrordiv').fadeIn();
				}
			} else {
				jQuery("#vrpaymentsdiv").css('color','#ff0000');
			}
			
		}
	
		if( step > 1 || skip_payments == 1 && validateCustomFields() ) {
			jQuery("#vrpayform").submit();
		}
	}

	function vrContinueButton() {
		
		if( step == 0 ) {
			jQuery(".vrcustomfields").fadeIn("normal");
			
			if( validateCustomFields() ) {
				jQuery("#vrpaymentsdiv").fadeIn("normal");
				jQuery("#vrconfcontinuebutton").html('<?php echo addslashes(JText::_('VRCONFIRMRESERVATION')); ?>');
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

		var elem_to_animate = null;
		
		<?php

		if( count( $cfields ) > 0 ) {
			foreach( $cfields as $cf ) {
				if( intval( $cf['required'] ) == 1 ) {
		?>
					is_email = <?php echo ($cf['rule'] == VRCustomFields::EMAIL ? 1 : 0); ?>;
					is_phone = <?php echo ($cf['rule'] == VRCustomFields::PHONE_NUMBER ? 1 : 0); ?>;
		<?php
					if( $cf['type'] == "text" || $cf['type'] == "textarea" || $cf['type'] == "date" ) {
		?>
						if( !vrvar.vrcf<?php echo $cf['id']; ?>.value.match(/\S/) ) {
							document.getElementById('vrcf<?php echo $cf['id']; ?>').style.color='#ff0000';
							ok = false;

							elem_to_animate = (elem_to_animate) ? elem_to_animate : jQuery('#vrcf<?php echo $cf['id']; ?>');

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

						} else {
							document.getElementById('vrcf<?php echo $cf['id']; ?>').style.color='';
						}

		<?php
					} else if( $cf['type'] == "select" ) {
		?>
						if( !vrvar.vrcf<?php echo $cf['id']; ?>.value.match(/\S/) ) {
							document.getElementById('vrcf<?php echo $cf['id']; ?>').style.color='#ff0000';
							ok = false;

							elem_to_animate = (elem_to_animate) ? elem_to_animate : jQuery('#vrcf<?php echo $cf['id']; ?>');

						} else {
							document.getElementById('vrcf<?php echo $cf['id']; ?>').style.color='';
						}
		<?php
					} else if( $cf['type'] == "checkbox" ) {
		?>
						if( vrvar.vrcf<?php echo $cf['id']; ?>.checked ) {
							document.getElementById('vrcf<?php echo $cf['id']; ?>').style.color='';
						} else {
							document.getElementById('vrcf<?php echo $cf['id']; ?>').style.color='#ff0000';
							ok = false;

							elem_to_animate = (elem_to_animate) ? elem_to_animate : jQuery('#vrcf<?php echo $cf['id']; ?>');

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
	
	</script>
	
<?php } ?>
