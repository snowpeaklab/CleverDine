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
if( !count( $this->row ) ) {
	$sel = array(
		'application' => '', 'username' => '', 'password' => '', 'active' => 0, 'ips' => '', 'denied' => ''
	);
} else {
	$sel = $this->row;
	$id = $sel['id'];
}

$vik = new VikApplication(VersionListener::getID());

$ips = ( strlen($sel['ips']) ? json_decode($sel['ips'], true) : array() );

$denied = ( strlen($sel['denied']) ? json_decode($sel['denied'], true) : array() );

?>

<form name="adminForm" action="index.php" method="post" enctype="multipart/form-data" id="adminForm">

	<div class="span12"></div>
	
	<div class="span6">
		<?php echo $vik->openFieldset(JText::_('VRMANAGEAPIUSER8'), 'form-horizontal'); ?>
			
			<!-- APPLICATION NAME - Text -->
			<?php echo $vik->openControl(JText::_('VRMANAGEAPIUSER2').':'); ?>
				<input type="text" name="application" class="" value="<?php echo $sel['application']; ?>" size="40"/>
			<?php echo $vik->closeControl(); ?>

			<!-- USERNAME - Text -->
			<?php echo $vik->openControl(JText::_('VRMANAGEAPIUSER3').'*:'); ?>
				<input type="text" name="username" class="required" value="<?php echo $sel['username']; ?>" size="40"/>
			<?php echo $vik->closeControl(); ?>

			<!-- USERNAME REGEX - Label -->
			<?php echo $vik->openControl('', 'vr-user-regex', 'id="user-regex" style="display:none;"'); ?>
				<span style="color: #900;font-size:95%;"><?php echo JText::_('VRAPIUSERUSERNAMEREGEX'); ?></span>
			<?php echo $vik->closeControl(); ?>

			<!-- PASSWORD - Password -->
			<?php echo $vik->openControl(JText::_('VRMANAGEAPIUSER4').'*:'); ?>
				<input type="password" name="password" class="required" value="<?php echo $sel['password']; ?>" size="40"/>
				<a href="javascript: void(0);" onclick="revealPassword(this);" id="pwd-reveal-link">
					<i class="fa fa-lock big" style="margin-left: 10px;"></i>
				</a>
			<?php echo $vik->closeControl(); ?>

			<!-- PASSWORD REGEX - Label -->
			<?php echo $vik->openControl('', 'vr-pwd-regex', 'id="pwd-regex" style="display:none;"'); ?>
				<span style="color: #900;font-size:95%;"><?php echo JText::_('VRAPIUSERPASSWORDREGEX'); ?></span>
			<?php echo $vik->closeControl(); ?>

			<!-- GENERATE PASSWORD - Button -->
			<?php echo $vik->openControl(''); ?>
				<button type="button" class="btn" onclick="generatePassword();"><?php echo JText::_('VRMANAGECUSTOMER17'); ?></button>
			<?php echo $vik->closeControl(); ?>
			
			<!-- ACTIVE - Number -->
			<?php
			$elem_yes = $vik->initRadioElement('', JText::_('VRYES'), $sel['active']==1);
			$elem_no = $vik->initRadioElement('', JText::_('VRNO'), $sel['active']==0);
			?>
			<?php echo $vik->openControl(JText::_('VRMANAGEAPIUSER6').':'); ?>
				<?php echo $vik->radioYesNo('active', $elem_yes, $elem_no, false); ?>
			<?php echo $vik->closeControl(); ?>
		
		<?php echo $vik->closeFieldset(); ?>
	</div>

	<div class="span6">
		<?php echo $vik->openFieldset(JText::_('VRMANAGEAPIUSER5'), 'form-horizontal'); ?>

			<div class="vr-ips-container" id="ips-container">

				<?php foreach( $ips as $k => $ip ) { ?>
					<div class="control ip-address" id="ipaddr<?php echo $k; ?>">
						<?php
						$parts = explode(".", $ip);

						for( $i = 0; $i < 4; $i++ ) {
							if( $i > 0 ) {
								?><span class="ip-dot">.</span><?php
							}
							?><input type="text" name="ip[<?php echo $k; ?>][]" value="<?php echo intval($parts[$i]); ?>" size="3" maxlength="3" onkeypress="return event.charCode >= 48 && event.charCode <= 57" onchange="checkBoxIP(this);"/><?php
						} ?>

						<a href="javascript: void(0);" onclick="removeIP(<?php echo $k; ?>);">
							<i class="fa fa-times big" style="margin-left: 10px;"></i>
						</a>

					</div>
				<?php } ?>

			</div>

			<div class="control" id="ips-container">
				<button type="button" class="btn" onclick="addIP();"><?php echo JText::_('VRMANAGEAPIUSER9'); ?></button>
			</div>

		<?php echo $vik->closeFieldset(); ?>
	</div>
	
	<div class="span12">
		<?php echo $vik->openFieldset(JText::_('VRMANAGEAPIUSER21'), 'form-horizontal'); ?>

			<?php echo $vik->openControl(''); ?>

				<button type="button" class="btn" onclick="allowAllRules(1);"><?php echo JText::_('VRINVSELECTALL'); ?></button>
				<button type="button" class="btn" onclick="allowAllRules(0);"><?php echo JText::_('VRINVSELECTNONE'); ?></button>

			<?php echo $vik->closeControl(); ?>

			<?php foreach( $this->plugins as $plugin ) { ?>

				<!-- PLUGIN - Dropdown -->

				<?php
				$is_allowed = $plugin->alwaysAllowed() || !in_array($plugin->getName(), $denied);

				$elements = array(
					$vik->initOptionElement(1, JText::_('VRALLOWED'), $is_allowed),
					$vik->initOptionElement(0, JText::_('VRDENIED'), !$is_allowed),
				);
				?>
				<?php echo $vik->openControl($plugin->getTitle().':'); ?>
					<?php echo $vik->dropdown('plugin['.$plugin->getName().']', $elements, '', 'vr-plugin-rules', ($plugin->alwaysAllowed() ? 'disabled="disabled"' : '')); ?>
					&nbsp;<i class="fa fa-<?php echo ($is_allowed ? 'check' : 'ban'); ?> big" style="color:#<?php echo ($is_allowed ? '090' : '900'); ?>;"></i>
				<?php echo $vik->closeControl(); ?>

			<?php } ?>

		<?php echo $vik->closeFieldset(); ?>
	</div>

	<input type="hidden" name="id" value="<?php echo $id; ?>"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="option" value="com_cleverdine"/>
</form>

<script type="text/javascript">

	jQuery(document).ready(function(){

		jQuery('.vr-plugin-rules').select2({
			minimumResultsForSearch: -1,
			allowClear: false,
			width: 200
		});

		jQuery("#adminForm .required").on("blur", function(){
			if( jQuery(this).val().length > 0 ) {
				jQuery(this).removeClass("vrrequired");
			} else {
				jQuery(this).addClass("vrrequired");
			}
		});

	});

	var IP_COUNT = <?php echo count($ips); ?>;

	function addIP() {

		var html = '';
		for( var i = 0; i < 4; i++ ) {
			if( i > 0 ) {
				html += '<span class="ip-dot">.</span>';
			}

			html += '<input type="text" name="ip['+IP_COUNT+'][]" value="" size="3" maxlength="3" onkeypress="return event.charCode >= 48 && event.charCode <= 57" onchange="checkBoxIP(this);"/>';
		}

		jQuery('#ips-container').append('<div class="control ip-address" id="ipaddr'+IP_COUNT+'">\n'+html+'\n'+
			'<a href="javascript: void(0);" onclick="removeIP('+IP_COUNT+');">\n'+
				'<i class="fa fa-times big" style="margin-left: 10px;"></i>\n'+
			'</a>\n'+
		'</div>\n');

		IP_COUNT++;

	}

	function removeIP(id) {
		jQuery('#ipaddr'+id).remove();
	}

	function checkBoxIP(input) {
		var val = parseInt(jQuery(input).val());
		if( val < 0 ) {
			jQuery(input).val(0);
		} else if( val > 255 ) {
			jQuery(input).val(255);
		}
	}

	function revealPassword(link) {
		var icon = jQuery(link).find('i');

		if( icon.hasClass('fa-lock') ) {
			jQuery('input[name="password"]').attr('type', 'text');

			icon.removeClass('fa-lock').addClass('fa-unlock');
		} else {
			jQuery('input[name="password"]').attr('type', 'password');

			icon.removeClass('fa-unlock').addClass('fa-lock');
		}
	}

	function generatePassword() {
		var password = buildPassword(8, 128, 1, 1, '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz');

		jQuery('input[name="password"]').val(password);

		if( jQuery('input[name="password"]').attr('type') == 'password' ) {
			revealPassword(jQuery('#pwd-reveal-link'));
		}

		jQuery('#pwd-regex').hide();
	}

	function buildPassword(min_length, max_length, min_digits, min_uppercase, chars_str) {

		var pwd = '';

		var len = Math.min(24, ( min_length + max_length ) / 2); 

		var i;

		for( i = 0; i < min_digits; i++ ) {
			pwd += ''+Math.floor(Math.random()*10);
		}

		for( i = 0; i < min_uppercase; i++ ) {
			pwd += String.fromCharCode(65+Math.floor(Math.random()*26));
		}

		for( i = pwd.length; i < len; i++ ) {
			pwd += chars_str.charAt(Math.floor(Math.random()*chars_str.length));
		}

		return pwd.shuffle();

	}

	String.prototype.shuffle = function () {
		var a = this.split("");

		for( var i = a.length - 1, j = 0, tmp = 0; i >= 0; i-- ) {
			j = Math.floor(Math.random() * (i + 1));
			
			tmp = a[i];
			a[i] = a[j];
			a[j] = tmp;
		}

		return a.join("");
	}

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

		ok = matchUsername() && ok;
		ok = matchPassword() && ok;

		return ok;
	}

	function matchPassword() {
		var pwd = jQuery('input[name="password"]');

		if( pwd.val().test(/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!?@#$%_.\-{\[()\]}]{8,128}$/) ) {
			pwd.removeClass('vrrequired');
			jQuery('#pwd-regex').hide();
			return true;
		}

		pwd.addClass('vrrequired');
		jQuery('#pwd-regex').show();
		return false;
	}

	function matchUsername() {
		var user = jQuery('input[name="username"]');

		if( user.val().test(/^[0-9A-Za-z._]{3,128}$/) ) {
			user.removeClass('vrrequired');
			jQuery('#user-regex').hide();
			return true;
		}

		user.addClass('vrrequired');
		jQuery('#user-regex').show();
		return false;
	}

	function allowAllRules(is) {
		jQuery('select.vr-plugin-rules:not(:disabled)').val(is).trigger('change');
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