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

$operator = $this->operator;
$user = JFactory::getUser();

$itemid = JFactory::getApplication()->input->get('Itemid', 0, 'uint');

// get login return URL
$return_url_to_encode = cleverdine::getLoginReturnURL();

?>

<?php

if( !$this->ACCESS ) { // start login

	if( $operator === false ) { ?>

		<script type="text/javascript">

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

		</div>

	<?php } else { ?>

		<div class="vrloginwarning">
			<div class="vrloginwarningtext"><?php echo JText::sprintf('VRLOGINOPERATORNOTFOUND', $user->name); ?></div>
			<div class="vrloginwarningexit">
				<a href="<?php echo JRoute::_('index.php?option=com_cleverdine&task=oplogout'); ?>" class="vrloginwarningexitlink"><?php echo JText::_('VRLOGOUT'); ?></a>
			</div>
		</div>

	<?php }

} else { 

	// you should not enter in this block of code as the view.html.php file should dispatch properly the template to use.

} ?>