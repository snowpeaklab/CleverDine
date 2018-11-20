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
if( !count( $this->selectedOperator ) ) {
	$sel = array(
		'code' => '', 'firstname' => '', 'lastname' => '', 'phone_number' => '', 'email' => '', 'jid' => '', 'group' => 0,
		'can_login' => 0, 'keep_track' => 1, 'mail_notifications' => 0, 'manage_coupon' => 0
	);
} else {
	$sel = $this->selectedOperator;
	$id = $sel['id'];
}

$sel['username'] = '';
$sel['password'] = '';
$sel['confpassword'] = '';

$users_select = '<select name="jid" id="vr-users-sel">';
$users_select .= '<option value=""></option>';
$old_group_id = -1;
foreach( $this->users as $u ) {
	if( $u['group_id'] != $old_group_id ) {
		if( $old_group_id != -1 ) {
			$users_select .= '</optgroup>';
		}
		$users_select .= '<optgroup label="'.$u['title'].'">';
	}
	
	$users_select .= '<option value="'.$u['id'].'" '.($u['id'] == $sel['jid'] ? 'selected="selected"': '').'>'.$u['name'].'</option>';
	
	$old_group_id = $u['group_id'];
}
$users_select .= '</optgroup>';
$users_select .= '</select>';

$user_groups_select = '<select name="usertype[]" id="vr-usertypes-sel" multiple>';
foreach( $this->userGroups as $g ) {
	$user_groups_select .= '<option value="'.$g['id'].'">'.$g['title'].'</option>';
}
$user_groups_select .= '</select>';

$vik = new VikApplication(VersionListener::getID());

?>

<form name="adminForm" action="index.php" method="post" enctype="multipart/form-data" id="adminForm">

	<?php echo $vik->bootStartTabSet('operator', array('active' => $this->activeTab)); ?>

		<!-- DETAILS -->

		<?php echo $vik->bootAddTab('operator', 'operator_details', JText::_('VRMAPDETAILSBUTTON')); ?>
	
			<div class="span6">
				<?php echo $vik->openFieldset(JText::_('VROPERATORFIELDSET1'), 'form-horizontal'); ?>
					
					<!-- CODE - Text -->
					<?php echo $vik->openControl(JText::_('VRMANAGEOPERATOR1').':'); ?>
						<input type="text" name="code" value="<?php echo $sel['code']; ?>" size="40"/>
					<?php echo $vik->closeControl(); ?>
			
					<!-- FIRST NAME - Text -->
					<?php echo $vik->openControl(JText::_('VRMANAGEOPERATOR2').'*:'); ?>
						<input class="required" type="text" name="firstname" value="<?php echo $sel['firstname']; ?>" size="40"/>
					<?php echo $vik->closeControl(); ?>
					
					<!-- LAST NAME - Text -->
					<?php echo $vik->openControl(JText::_('VRMANAGEOPERATOR3').'*:'); ?>
						<input class="required" type="text" name="lastname" value="<?php echo $sel['lastname']; ?>" size="40"/>
					<?php echo $vik->closeControl(); ?>
					
					<!-- EMAIL - Text -->
					<?php echo $vik->openControl(JText::_('VRMANAGEOPERATOR5').'*:'); ?>
						<input class="required" type="text" name="email" value="<?php echo $sel['email']; ?>" size="40"/>
					<?php echo $vik->closeControl(); ?>

					<!-- PHONE NUMBER - Text -->
					<?php echo $vik->openControl(JText::_('VRMANAGEOPERATOR4').':'); ?>
						<input type="text" name="phone_number" value="<?php echo $sel['phone_number']; ?>" size="40"/>
					<?php echo $vik->closeControl(); ?>

					<!-- GROUP - Radio Button -->
					<?php echo $vik->openControl(JText::_('VRMANAGECUSTOMF7').':'); ?>
						<?php echo RestaurantsHelper::buildGroupDropdown('group', $sel['group'], 'vr-group-sel', array(1, 2), '', true); ?>
					<?php echo $vik->closeControl(); ?>
					
				<?php echo $vik->closeFieldset(); ?>
			</div>
		
			<div class="span6">
				<?php echo $vik->openFieldset(JText::_('VROPERATORFIELDSET2'), 'form-horizontal'); ?>
					
					<!-- JOOMLA USER - Dropdown -->
					<?php echo $vik->openControl(JText::_('VRMANAGEOPERATOR7').':'); ?>
						<?php echo $users_select; ?>
					<?php echo $vik->closeControl(); ?>
			
					<!-- USER GROUP - Dropdown -->
					<?php echo $vik->openControl(JText::_('VRMANAGEOPERATOR10').':', 'vruserfield', (!empty($sel['jid']) ? 'style="display: none;"' : '')); ?>
						<?php echo $user_groups_select; ?>
					<?php echo $vik->closeControl(); ?>
					
					<!-- USERNAME - Text -->
					<?php echo $vik->openControl(JText::_('VRMANAGEOPERATOR11').'*:', 'vruserfield', (!empty($sel['jid']) ? 'style="display: none;"' : '')); ?>
						<input class="maybe-required <?php echo (empty($sel['jid']) ? 'required' : ''); ?>" type="text" name="username" value="" size="40"/>
					<?php echo $vik->closeControl(); ?>
					
					<!-- PASSWORD - Password -->
					<?php echo $vik->openControl(JText::_('VRMANAGEOPERATOR12').'*:', 'vruserfield', (!empty($sel['jid']) ? 'style="display: none;"' : '')); ?>
						<input class="maybe-required <?php echo (empty($sel['jid']) ? 'required' : ''); ?>" type="password" name="password" value="" size="40"/>
					<?php echo $vik->closeControl(); ?>
					
					<!-- CONFIRM PASSWORD - Password -->
					<?php echo $vik->openControl(JText::_('VRMANAGEOPERATOR13').'*:', 'vruserfield', (!empty($sel['jid']) ? 'style="display: none;"' : '')); ?>
						<input class="maybe-required <?php echo (empty($sel['jid']) ? 'required' : ''); ?>" type="password" name="confpassword" value="" size="40"/>
					<?php echo $vik->closeControl(); ?>
					
				<?php echo $vik->closeFieldset(); ?>
			</div>

		<?php echo $vik->bootEndTab(); ?>

		<!-- ACTIONS -->

		<?php echo $vik->bootAddTab('operator', 'operator_actions', JText::_('VRMAPACTIONSBUTTON')); ?>

			<div class="span6">
				<?php echo $vik->openFieldset(JText::_('VROPERATORFIELDSET3'), 'form-horizontal'); ?>

					<!-- CAN LOGIN - Radio Button -->
					<?php
					$elem_yes = $vik->initRadioElement('', JText::_('VRYES'), $sel['can_login'] == 1, 'onclick="canLoginValueChanged(1);"');
					$elem_no = $vik->initRadioElement('', JText::_('VRNO'), $sel['can_login'] == 0, 'onclick="canLoginValueChanged(0);"');
					?>
					<?php echo $vik->openControl(JText::_('VRMANAGEOPERATOR6').':'); ?>
						<?php echo $vik->radioYesNo('can_login', $elem_yes, $elem_no, false); ?>
					<?php echo $vik->closeControl(); ?>
					
					<!-- KEEP TRACK - Radio Button -->
					<?php
					$elem_yes = $vik->initRadioElement('', JText::_('VRYES'), $sel['keep_track'] == 1);
					$elem_no = $vik->initRadioElement('', JText::_('VRNO'), $sel['keep_track'] == 0);
					?>
					<?php echo $vik->openControl(JText::_('VRMANAGEOPERATOR16').':'); ?>
						<?php echo $vik->radioYesNo('keep_track', $elem_yes, $elem_no, false); ?>
					<?php echo $vik->closeControl(); ?>
					
					<!-- MAIL NOTIFICATIONS - Radio Button -->
					<?php
					$elem_yes = $vik->initRadioElement('', JText::_('VRYES'), $sel['mail_notifications'] == 1);
					$elem_no = $vik->initRadioElement('', JText::_('VRNO'), $sel['mail_notifications'] == 0);
					?>
					<?php echo $vik->openControl(JText::_('VRMANAGEOPERATOR15').':'); ?>
						<?php echo $vik->radioYesNo('mail_notifications', $elem_yes, $elem_no, false); ?>
					<?php echo $vik->closeControl(); ?>

				<?php echo $vik->closeFieldset(); ?>
			</div>

			<div class="span6">
				<?php echo $vik->openFieldset(JText::_('VROPERATORFIELDSET4'), 'form-horizontal'); ?>

					<!-- MANAGE COUPON - Radio Button -->
					<?php
					$elements = array(
						$vik->initOptionElement(0, JText::_('VROPCOUPONOPT0'), $sel['manage_coupon'] == 0),
						$vik->initOptionElement(1, JText::_('VROPCOUPONOPT1'), $sel['manage_coupon'] == 1),
						$vik->initOptionElement(2, JText::_('VROPCOUPONOPT2'), $sel['manage_coupon'] == 2),
					);
					?>
					<?php echo $vik->openControl(JText::_('VRMANAGEOPERATOR17').':'); ?>
						<?php echo $vik->dropdown('manage_coupon', $elements, 'vr-managecoupon-sel', 'login-child', ($sel['can_login'] ? '' : 'disabled="disabled"')); ?>
					<?php echo $vik->closeControl(); ?>

				<?php echo $vik->closeFieldset(); ?>
			</div>

		<?php echo $vik->bootEndTab(); ?>

	<?php echo $vik->bootEndTabSet(); ?>

	<input type="hidden" name="active_tab" value="<?php echo $this->activeTab; ?>" />
	
	<input type="hidden" name="id" value="<?php echo $id; ?>"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="option" value="com_cleverdine"/>
</form>

<script type="text/javascript">

	jQuery(document).ready(function(){

		jQuery('#vr-users-sel').select2({
			placeholder: '<?php echo addslashes(JText::_('VRMANAGEOPERATOR9')); ?>',
			allowClear: true,
			width: 300
		});

		jQuery('#vr-usertypes-sel, #vr-managecoupon-sel').select2({
			minimumResultsForSearch: -1,
			allowClear: false,
			width: 300
		});

		jQuery('#vr-users-sel').on('change', function(){
			if( jQuery('#vr-users-sel').val().length == 0 ) {
			
				jQuery('.maybe-required').each(function(){
					if( !jQuery(this).hasClass('required') ) {
						jQuery(this).addClass('required');
					}
				});

				jQuery('.vruserfield').show();
			} else {

				jQuery('.maybe-required').each(function(){
					if( jQuery(this).hasClass('required') ) {
						jQuery(this).removeClass('required');
					}
				});

				jQuery('.vruserfield').hide();
			}
		});

		jQuery("#adminForm .required").on("blur", function(){
			if( jQuery(this).val().length > 0 ) {
				jQuery(this).removeClass("vrrequired");
			} else {
				jQuery(this).addClass("vrrequired");
			}
		});

		// tab handler

		jQuery('a[href="#operator_details"],a[href="#operator_actions"]').on('click', function(){
			var tab = jQuery(this).attr('href').replace(/#/g, '');
			jQuery('input[name="active_tab"]').val(tab);
		});

	});

	function canLoginValueChanged(is) {
		jQuery('.login-child').prop('disabled', is ? false : true);
	}

	// VALIDATION

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
		return ok && vrValidatePassword();
	}

	function vrValidatePassword() {

		var pass = [];
		pass[0] = jQuery('input[name="password"]');
		pass[1] = jQuery('input[name="confpassword"]');

		if( pass[0].hasClass('required') && pass[0].val() != pass[1].val() ) {
			pass[0].addClass('vrrequired');
			pass[1].addClass('vrrequired');

			return false;
		}

		pass[0].removeClass('vrrequired');
		pass[1].removeClass('vrrequired');

		return true;

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

