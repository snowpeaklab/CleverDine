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

if( !count( $this->selectedShift ) ) {
	$sel = array(
		'name' => '', 'from' => 12, 'to' => 23, 'minfrom' => 0, 'minto' => 0, 'group' => 1, 'showlabel' => 1, 'label' => ''
	);
} else {
	$sel = $this->selectedShift;
	$id = $sel['id'];
	
	$_app = $sel['from'];
	$sel['from'] = intval($_app/60);
	$sel['minfrom'] = $_app%60;
	
	$_app = $sel['to'];
	$sel['to'] = intval($_app/60);
	$sel['minto'] = $_app%60;
}

$min_intervals = array( cleverdine::getMinuteIntervals(true), cleverdine::getTakeAwayMinuteInterval(true) );

$vik = new VikApplication(VersionListener::getID());

?>

<form name="adminForm" action="index.php" method="post" enctype="multipart/form-data" id="adminForm">
	
	<div class="span6">
		<?php echo $vik->openFieldset(JText::_('VRWORKSHIFTFIELDSET1'), 'form-horizontal'); ?>
			
			<!-- NAME - Text -->
			<?php echo $vik->openControl(JText::_('VRMANAGESHIFT1').'*:'); ?>
				<input class="required" type="text" name="name" value="<?php echo $sel['name']; ?>" size="40"/>
			<?php echo $vik->closeControl(); ?>
			
			<!-- DISPLAY LABEL - Radio Button -->
			<?php
			$elem_yes = $vik->initRadioElement('', JText::_('VRYES'), $sel['showlabel']==1, 'onClick="changeLabelStatus(1);"');
			$elem_no = $vik->initRadioElement('', JText::_('VRNO'), $sel['showlabel']==0, 'onClick="changeLabelStatus(0);"');
			?>
			<?php echo $vik->openControl(JText::_('VRMANAGESHIFT5').':'); ?>
				<?php echo $vik->radioYesNo('showlabel', $elem_yes, $elem_no, false); ?>
			<?php echo $vik->closeControl(); ?>
			
			<!-- LABEL - Text -->
			<?php echo $vik->openControl(JText::_('VRMANAGESHIFT6').':', 'vrlabelrow', ($sel['showlabel'] ? '' : 'style="display:none;"')); ?>
				<input type="text" name="label" value="<?php echo $sel['label']; ?>" size="40"/>
			<?php echo $vik->closeControl(); ?>
			
			<!-- FROM HOUR MIN - Form -->
			<?php
			$from_hours = array();
			for( $i = 0; $i < 24; $i++ ) {
				$from_hours[] = $vik->initOptionElement($i, ($i < 10 ? '0' : '').$i, $sel['from'] == $i);
			}

			$from_minutes = array();
			for( $i = 0; $i < 60; $i += $min_intervals[$sel['group']-1] ) {
				$from_minutes[] = $vik->initOptionElement($i, ($i < 10 ? '0' : '').$i, $sel['minfrom'] == $i);
			}
			?>
			<?php echo $vik->openControl(JText::_('VRMANAGESHIFT2').'*:'); ?>
				<?php echo $vik->dropdown('from', $from_hours, 'vr-hourfrom-sel', 'short-select required'); ?>
				<?php echo $vik->dropdown('minfrom', $from_minutes, 'vr-minfrom-sel', 'short-select required'); ?>
			<?php echo $vik->closeControl(); ?>
			
			<!-- TO HOUR MIN - Form -->
			<?php
			$to_hours = array();
			for( $i = 0; $i < 24; $i++ ) {
				$to_hours[] = $vik->initOptionElement($i, ($i < 10 ? '0' : '').$i, $sel['to'] == $i);
			}

			$to_minutes = array();
			for( $i = 0; $i < 60; $i += $min_intervals[$sel['group']-1] ) {
				$to_minutes[] = $vik->initOptionElement($i, ($i < 10 ? '0' : '').$i, $sel['minto'] == $i);
			}
			?>
			<?php echo $vik->openControl(JText::_('VRMANAGESHIFT3').'*:'); ?>
				<?php echo $vik->dropdown('to', $to_hours, 'vr-hourto-sel', 'short-select required'); ?>
				<?php echo $vik->dropdown('minto', $to_minutes, 'vr-minto-sel', 'short-select required'); ?>
			<?php echo $vik->closeControl(); ?>
			
			<!-- GROUP - Dropdown -->
			<?php echo $vik->openControl(JText::_('VRMANAGESHIFT4').':'); ?>
				<?php echo RestaurantsHelper::buildGroupDropdown('group', $sel['group'], 'vr-group-sel', array(1, 2)); ?>
			<?php echo $vik->closeControl(); ?>
		
		<?php echo $vik->closeFieldset(); ?>
	</div>
	
	<input type="hidden" name="id" value="<?php echo $id; ?>"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="option" value="com_cleverdine"/>
</form>

<script>

	var MINUTES_LIST = <?php echo json_encode($min_intervals); ?>;

	jQuery(document).ready(function(){

		jQuery('select.short-select').select2({
			minimumResultsForSearch: -1,
			allowClear: false,
			width: 70
		});

		jQuery('#vr-group-sel').on('change', function(){
			var group = jQuery('#vr-group-sel').val();
		
			var minfrom = jQuery('#vr-minfrom-sel').val();
			var minto = jQuery('#vr-minto-sel').val();
			
			var _html = new Array( '', '' );
			for( var min = 0; min < 60; min+=MINUTES_LIST[group-1] ) {
				_html[0] += '<option value="'+min+'" '+((minfrom == min)?'selected="selected"':'')+'>'+((min < 10)?'0':'')+min+'</option>';
				_html[1] += '<option value="'+min+'" '+((minto == min)?'selected="selected"':'')+'>'+((min < 10)?'0':'')+min+'</option>';
			}
			
			jQuery('#vr-minfrom-sel').html(_html[0]);
			jQuery('#vr-minto-sel').html(_html[1]);

			jQuery('#vr-minfrom-sel').select2('val', jQuery('#vr-minfrom-sel').val());
			jQuery('#vr-minto-sel').select2('val', jQuery('#vr-minto-sel').val());

		});

		jQuery("#adminForm .required").on("blur", function(){
			if( jQuery(this).val().length > 0 ) {
				jQuery(this).removeClass("vrrequired");
			} else {
				jQuery(this).addClass("vrrequired");
			}
		});

	});
	
	function changeLabelStatus(is) {
		if( is ) {
			jQuery('.vrlabelrow').show();
		} else {
			jQuery('.vrlabelrow').hide();
		}
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

		return ok && vrValidateBounds();
	}

	function vrValidateBounds() {

		var fromHour 	= jQuery('#vr-hourfrom-sel');
		var fromMin 	= jQuery('#vr-minfrom-sel');

		var toHour 	= jQuery('#vr-hourto-sel');
		var toMin 	= jQuery('#vr-minto-sel');

		if( parseInt(fromHour.val()) * 60 + parseInt(fromMin.val()) > parseInt(toHour.val()) * 60 + parseInt(toMin.val()) ) {

			if( fromHour.val() != toHour.val() ) {
				fromHour.addClass('vrrequired');
				toHour.addClass('vrrequired');
			} else {
				fromHour.removeClass('vrrequired');
				toHour.removeClass('vrrequired');
			}

			fromMin.addClass('vrrequired');
			toMin.addClass('vrrequired');

			return false;
		}

		fromHour.removeClass('vrrequired');
		toHour.removeClass('vrrequired');

		fromMin.removeClass('vrrequired');
		toMin.removeClass('vrrequired');

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