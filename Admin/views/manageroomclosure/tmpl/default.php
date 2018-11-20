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
		'id_room' => -1, 'start_ts' => '', 'end_ts' => '', 'start_date' => '', 'end_date' => '', 'start_hour' => 0, 'start_min' => 0, 'end_hour' => 0, 'end_min' => 0
	);
} else {
	$sel = $this->row;
	$id = $sel['id'];
}

$date_format = cleverdine::getDateFormat(true);

if( !empty($sel['start_ts']) ) {
	$sel['start_date'] = date($date_format, $sel['start_ts']);
	list($sel['start_hour'], $sel['start_min']) = explode(':', date('H:i', $sel['start_ts']));
}

if( !empty($sel['end_ts']) ) {
	$sel['end_date'] = date($date_format, $sel['end_ts']);
	list($sel['end_hour'], $sel['end_min']) = explode(':', date('H:i', $sel['end_ts']));
}

$df_joomla = $date_format;
$df_joomla = str_replace( "d", "%d", $df_joomla );
$df_joomla = str_replace( "m", "%m", $df_joomla );
$df_joomla = str_replace( "Y", "%Y", $df_joomla );

$vik = new VikApplication(VersionListener::getID());

?>

<form name="adminForm" action="index.php" method="post" enctype="multipart/form-data" id="adminForm">
	
	<div class="span10">
		<?php echo $vik->openEmptyFieldset(); ?>
			
			<!-- ROOM - Dropdown -->
			<?php
			$elements = array();
			foreach( $this->rooms as $room ) {
				array_push($elements, $vik->initOptionElement($room['id'], $room['name'], $room['id']==$sel['id_room']));
			}
			?>
			<?php echo $vik->openControl(JText::_('VRMANAGEROOMCLOSURE1').'*:'); ?>
				<?php echo $vik->dropdown('id_room', $elements, '', 'required'); ?>
			<?php echo $vik->closeControl(); ?>
			
			<!-- START CLOSURE - Form -->
			<?php echo $vik->openControl(JText::_('VRMANAGEROOMCLOSURE2').'*:'); ?>
				<?php
				$attr = array();
				$attr['onChange'] 	= 'vrStartDateChanged();';
				$attr['class']		= 'required';

				echo $vik->calendar($sel['start_date'], 'start_date', 'vrstartdate', null, $attr);
				?>
				<input type="number" name="start_hour" value="<?php echo intval($sel['start_hour']); ?>" min="0" max="23" class="required"/>
				<input type="number" name="start_min" value="<?php echo intval($sel['start_min']); ?>" min="0" max="59" class="required" />
			<?php echo $vik->closeControl(); ?>
			
			<!-- END CLOSURE - Form -->
			<?php echo $vik->openControl(JText::_('VRMANAGEROOMCLOSURE3').'*:'); ?>
				<?php echo $vik->calendar($sel['end_date'], 'end_date', 'vrenddate', null, array('class' => 'required')); ?>
				<input type="number" name="end_hour" value="<?php echo intval($sel['end_hour']); ?>" min="0" max="23" class="required" />
				<input type="number" name="end_min" value="<?php echo intval($sel['end_min']); ?>" min="0" max="59" class="required" />
			<?php echo $vik->closeControl(); ?>
		
		<?php echo $vik->closeEmptyFieldset(); ?>
	</div>
	
	<input type="hidden" name="id" value="<?php echo $id; ?>"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="option" value="com_cleverdine"/>
</form>

<script type="text/javascript">
	
	jQuery(document).ready(function(){
		jQuery('.vik-dropdown').select2({
			allowClear: false,
			width: 300,
		});

		jQuery("#adminForm .required").on("blur", function(){
			if( jQuery(this).val().length > 0 ) {
				jQuery(this).removeClass("vrrequired");
			} else {
				jQuery(this).addClass("vrrequired");
			}
		});
	});

	function vrStartDateChanged() {
		if (jQuery('#vrenddate').val().length == 0) {
			jQuery('#vrenddate').val(jQuery('#vrstartdate').val());
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
		return ok;
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
