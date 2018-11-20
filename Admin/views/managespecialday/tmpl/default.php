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

JHtml::_('behavior.calendar');
JHtml::_('behavior.modal');

$shifts = $this->shifts;
$menus = $this->menus;
$tkmenus = $this->tkmenus;
$sel_menus = $this->sel_menus;

$sel = null;
$id = -1;
if( !count( $this->selectedSpecialDay ) ) {
	$sel = array(
		'group' => 1, 'name' => '', 'start_ts' => '', 'end_ts' => '', 'working_shifts' => '', 'days_filter' => '', 'markoncal' => 0, 'ignoreclosingdays' => 1, 'choosemenu' => 0, 'peopleallowed' => -1, 
		'depositcost' => cleverdine::getDepositPerReservation(true), 'perpersoncost' => cleverdine::getDepositPerPerson(true), 'sel_menus' => array(), 'images' => '', 'priority' => 1, 'delivery_service' => -1
	);
} else {
	$sel = $this->selectedSpecialDay;
	$id = $sel['id'];
	$sel['sel_menus'] = array();
	
	if( count( $sel_menus ) > 0 ) {
		for( $i = 0; $i < count($sel_menus); $i++ ) {
			array_push($sel['sel_menus'], $sel_menus[$i]['mid']); 
		}
	}
}

if( !empty($this->post_group) ) {
	$sel['group'] = $this->post_group;
}

// WORKING SHIFTS SELECT
$working_shifts_select = "";

if( count( $shifts ) > 0 ) {
	$sel_shifts = explode(",", str_replace(" ", "", $sel['working_shifts'])); 
	
	$working_shifts_select = '<select name="working_shifts[]" id="vrwsselect" multiple>';
	foreach( $shifts as $_s ) {
		$from_min = $_s['from_min'];
		if( $from_min < 10 ) {
			$from_min = '0'.$from_min;
		}
		$to_min = $_s['to_min'];
		if( $to_min < 10 ) {
			$to_min = '0'.$to_min;
		}
		
		$selected = (in_array($_s['from']."-".$_s['to'], $sel_shifts) ? 'selected="selected"' : '');

		$working_shifts_select .= '<option value="'.$_s['from_hour'].':'.$from_min.'-'.$_s['to_hour'].':'.$to_min.'" '.$selected.'>'.$_s['name'].'</option>';
	}
	$working_shifts_select .= '</select>';
}
// END SELECT

// DAYS FILTER SELECT
$days_filter_select = '<select name="days_filter[]" id="vrdfselect" multiple>';

$sel_days = explode(",", str_replace(" ", "", $sel['days_filter']));
for( $i = 1; $i <= 7; $i++ ) {
	$day = JText::_('VRDAY'.$i);
	
	$selected = (in_array( ($i != 7 ? $i : "0"), $sel_days ) ? 'selected="selected"' : '');
	
	$days_filter_select .= '<option value="'.mb_substr($day, 0, 3, 'UTF-8').'" '.$selected.'>'.$day.'</option>';
}
$days_filter_select .= '</select>';
// END SELECT

$menus_select;
if( $sel['group'] == 1 ) {
	// MENUS SELECT
	$menus_select = '<select name="id_menu[]" id="vrmiselect" multiple>';
	$_last_menu_is_special_day = -1;
	foreach( $menus as $_m ) {
		if( $_last_menu_is_special_day != $_m['special_day'] ) {
			if( $_last_menu_is_special_day != -1 ) {
				$menus_select .= '</optgroup>';
			}
			$menus_select .= '<optgroup label="'.JText::_( (($_m['special_day'] == 1) ? 'VRMANAGESPDAY14' : 'VRMANAGESPDAY15' ) ).'">';
		}
		$_last_menu_is_special_day = $_m['special_day'];
		
		$selected = (in_array( $_m['id'], $sel['sel_menus'] ) ? 'selected="selected"' : '');
		
		$menus_select .= '<option value="'.$_m['id'].'" '.$selected.'>'.$_m['name'].'</option>';
	}
	$menus_select .= '</optgroup>';
	$menus_select .= '</select>';
	// END SELECT
} else {
	// TKMENUS SELECT
	$menus_select = '<select name="id_menu[]" id="vrmiselect" multiple>';
	
	foreach( $tkmenus as $_m ) {
		$selected = (in_array( $_m['id'], $sel['sel_menus'] ) ? 'selected="selected"' : '');
		
		$menus_select .= '<option value="'.$_m['id'].'" '.$selected.'>'.$_m['title'].'</option>';
	}
	$menus_select .= '</select>';
	// END SELECT
}

$date_format = cleverdine::getDateFormat(true);

if (strlen($sel['start_ts']) > 0 && $sel['start_ts'] != -1 ) {
	$sel['start_ts'] 	= date($date_format, $sel['start_ts']);
	$sel['end_ts'] 		= date($date_format, $sel['end_ts']);
} else {
	$sel['start_ts']	= '';
	$sel['end_ts']		= '';
}

$sp_images = array();
if( !empty($sel['images']) ) {
	$sp_images = explode(";;", $sel['images']);
}

$vik = new VikApplication(VersionListener::getID());

$elem_yes = $vik->initRadioElement('', '', 1);
$elem_no = $vik->initRadioElement('', '', 0);

$image_path = JUri::root().'components/com_cleverdine/assets/media/';

$mediaManager = new MediaManagerHTML(RestaurantsHelper::getAllMedia(), $image_path, $vik, 'vre');

?>

<form name="adminForm" id="adminForm" action="index.php" method="post" enctype="multipart/form-data">
	
	<div class="span6">
		<?php echo $vik->openFieldset(JText::_('VRMANAGEMENU2'), 'form-horizontal'); ?>
		
			<!-- GROUP - Dropdown -->
			<?php echo $vik->openControl(JText::_('VRMANAGESPDAY16').'*:'); ?>
				<?php echo RestaurantsHelper::buildGroupDropdown('group', $sel['group'], 'vr-group-sel', array(1, 2)); ?>
			<?php echo $vik->closeControl(); ?>
			
			<!-- NAME - Text -->
			<?php echo $vik->openControl(JText::_('VRMANAGESPDAY1').'*:'); ?>
				<input type="text" class="required" id="vrnametitle" name="name" value="<?php echo $sel["name"]; ?>" size="40"/>
			<?php echo $vik->closeControl(); ?>
			
			<!-- START - Calendar -->
			<?php echo $vik->openControl(JText::_('VRMANAGESPDAY2').':'); ?>
				<?php echo $vik->calendar($sel['start_ts'], 'start_date', 'start_date'); ?>
			<?php echo $vik->closeControl(); ?>
			
			<!-- END - Calendar -->
			<?php echo $vik->openControl(JText::_('VRMANAGESPDAY3').':'); ?>
				<?php echo $vik->calendar($sel['end_ts'], 'end_date', 'end_date'); ?>
			<?php echo $vik->closeControl(); ?>
			
			<!-- WORKING SHIFTS - Dropdown -->
			<?php if( strlen( $working_shifts_select ) > 0 ) { ?>
				<?php echo $vik->openControl(JText::_('VRMANAGESPDAY4').':'); ?>
					<?php echo $working_shifts_select; ?>
				<?php echo $vik->closeControl(); ?>
			<?php } ?>
			
			<!-- DAYS FILTER - Dropdown -->
			<?php echo $vik->openControl(JText::_('VRMANAGESPDAY5').':'); ?>
				<?php echo $days_filter_select; ?>
			<?php echo $vik->closeControl(); ?>
			
			<?php if( $sel['group'] == 1 ) { ?>
				
				<!-- DEPOSIT COST - Number -->
				<?php echo $vik->openControl(JText::_('VRMANAGESPDAY6').':'); ?>
					<input type="number" id="depositcost" name="depositcost" value="<?php echo $sel['depositcost']?>" min="0" step="any" size="10">&nbsp;<?php echo cleverdine::getCurrencySymb(true); ?>
				<?php echo $vik->closeControl(); ?>
				
				<!-- COST PER PERSON - Radio Button -->
				<?php 
				$elem_yes = $vik->initRadioElement('', $elem_yes->label, $sel['perpersoncost']==1);
				$elem_no = $vik->initRadioElement('', $elem_no->label, $sel['perpersoncost']==0);
				?>
				<?php echo $vik->openControl(JText::_('VRMANAGESPDAY7').':'); ?>
					<?php echo $vik->radioYesNo('perpersoncost', $elem_yes, $elem_no, false); ?>
				<?php echo $vik->closeControl(); ?>
				
				<!-- PEOPLE ALLOWED - Radio Button -->
				<?php 
				$elements = array(
					$vik->initOptionElement(1, JText::_('VRPEOPLEALLOPT1'), $sel['peopleallowed'] == -1),
					$vik->initOptionElement(2, JText::_('VRPEOPLEALLOPT2'), $sel['peopleallowed'] != -1),
				);
				?>
				<?php echo $vik->openControl(JText::_('VRMANAGESPDAY21').':'); ?>
					<?php echo $vik->dropdown('peopallradio', $elements, 'vr-peopleall-select'); ?>
					<input type="number" name="peopleallowed" value="<?php echo $sel['peopleallowed']; ?>" min="0" max="9999" id="vr-people-allowed-text" 
					style="<?php echo ($sel['peopleallowed']==-1 ? 'display:none;' : ''); ?>"/>
				<?php echo $vik->closeControl(); ?>
				<?php
				$elem_yes->label = JText::_('VRYES');
				$elem_no->label = JText::_('VRNO');
				?>
				
			<?php } ?>

			<?php if( $sel['group'] == 2 ) { ?>

				<!-- DELIVERY SERVICE - Select -->
				<?php 
				$elements = array(
					$vik->initOptionElement(-1, JText::_('VRSPDAYSERVICEOPT1'), $sel['delivery_service'] == -1),
					$vik->initOptionElement(2, JText::_('VRSPDAYSERVICEOPT2'), $sel['delivery_service'] == 2),
					$vik->initOptionElement(1, JText::_('VRSPDAYSERVICEOPT3'), $sel['delivery_service'] == 1),
					$vik->initOptionElement(0, JText::_('VRSPDAYSERVICEOPT4'), $sel['delivery_service'] == 0)
				);
				?>
				<?php echo $vik->openControl(JText::_('VRMANAGESPDAY22').':'); ?>
					<?php echo $vik->dropdown('delivery_service', $elements, 'vr-service-sel'); ?>
				<?php echo $vik->closeControl(); ?>

			<?php } ?>
			
			<!-- MARK ON CALENDAR - Radio Button -->
			<?php 
			$elem_yes = $vik->initRadioElement('', $elem_yes->label, $sel['markoncal']==1);
			$elem_no = $vik->initRadioElement('', $elem_no->label, $sel['markoncal']==0);
			?>
			<?php echo $vik->openControl(JText::_('VRMANAGESPDAY12').':'); ?>
				<?php echo $vik->radioYesNo('markoncal', $elem_yes, $elem_no, false); ?>
			<?php echo $vik->closeControl(); ?>
			
			<!-- IGNORE CLOSING DAYS - Radio Button -->
			<?php 
			$elem_yes = $vik->initRadioElement('', $elem_yes->label, $sel['ignoreclosingdays']==1);
			$elem_no = $vik->initRadioElement('', $elem_no->label, $sel['ignoreclosingdays']==0);
			?>
			<?php echo $vik->openControl(JText::_('VRMANAGESPDAY13').':'); ?>
				<?php echo $vik->radioYesNo('ignoreclosingdays', $elem_yes, $elem_no, false); ?>
			<?php echo $vik->closeControl(); ?>
			
			<!-- PRIORITY - Dropdown -->
			<?php 
			$elements = array();
			for( $i = 1; $i <= 3; $i++ ) {
				array_push($elements, $vik->initOptionElement($i, JText::_('VRPRIORITY'.$i), $sel['priority']==$i));
			}
			?>
			<?php echo $vik->openControl(JText::_('VRMANAGESPDAY20').':'); ?>
				<?php echo $vik->dropdown('priority', $elements, 'vr-priority-sel'); ?>
			<?php echo $vik->closeControl(); ?>
			
			<?php if( $sel['group'] == 1 ) { ?>
				
				<!-- CHOOSABLE MENUS - Radio Button -->
				<?php 
				$elem_yes = $vik->initRadioElement('', $elem_yes->label, $sel['choosemenu']==1);
				$elem_no = $vik->initRadioElement('', $elem_no->label, $sel['choosemenu']==0);
				?>
				<?php echo $vik->openControl(JText::_('VRMANAGESPDAY19').':'); ?>
					<?php echo $vik->radioYesNo('choosemenu', $elem_yes, $elem_no, false); ?>
				<?php echo $vik->closeControl(); ?>
				
			<?php } ?>
				
			<!-- MENUS - Dropdown -->
			<?php echo $vik->openControl(JText::_('VRMANAGESPDAY9').':'); ?>
				<?php echo $menus_select; ?>
			<?php echo $vik->closeControl(); ?>
		
		<?php echo $vik->closeFieldset(); ?>
	</div>
	
	<?php if( $sel['group'] == 1 ) { ?>
		<div class="span5">
			<?php echo $vik->openFieldset(JText::_('VRMANAGESPDAY17'), 'form-horizontal'); ?>
			
				<div id="vrspimgcont" class="control-group">
					<?php 
					$cont = 1;
					foreach( $sp_images as $img ) { ?>
						<div class="vr-sp-image" id="vrimagefield<?php echo $cont; ?>">
							<?php echo $mediaManager->buildMedia('image[]', $cont, $img); ?>
						</div>
					<?php 
						$cont++;
					} ?>
				</div>
				
				<div class="vr-sp-image-bottom">
					<button type="button" onClick="vrAddImageField();" class="btn"><?php echo JText::_('VRMANAGESPDAY18');?></button>
				</div>
				
			<?php echo $vik->closeFieldset(); ?>
		</div>
	<?php } ?>
	
	<input type="hidden" name="id" value="<?php echo $id; ?>"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="option" value="com_cleverdine"/>
</form>

<?php echo $mediaManager->buildModal(JText::_('VRMEDIAFIELDSET4')); ?>

<?php echo $mediaManager->useScript(JText::_("VRMANAGEMEDIA10")); ?>

<script>

	jQuery(document).ready(function(){
		
		jQuery("#vrwsselect").select2({
			placeholder: '<?php echo addslashes(JText::_('VRMANAGEMENU24')); ?>',
			allowClear: true,
			width: 400
		});
		
		jQuery("#vrdfselect").select2({
			placeholder: '<?php echo addslashes(JText::_('VRMANAGEMENU25')); ?>',
			allowClear: true,
			width: 400
		});
		
		jQuery("#vrmiselect").select2({
			placeholder: '<?php echo addslashes(JText::_('--')); ?>',
			allowClear: true,
			width: 400
		});

		jQuery('#vr-priority-sel, #vr-service-sel, #vr-peopleall-select').select2({
			minimumResultsForSearch: -1,
			allowClear: false,
			width: 200
		});

		jQuery('#vr-peopleall-select').on('change', function(){
			if (jQuery(this).val() == "1") {
				jQuery('#vr-people-allowed-text').hide();
				jQuery('#vr-people-allowed-text').val(-1);
			} else {
				jQuery('#vr-people-allowed-text').show();
				jQuery('#vr-people-allowed-text').val(100);
			}
		});

		jQuery('#vr-group-sel').on('change', function(){
			var group = jQuery(this).val();
			<?php if( $id == -1 ) { ?>
				document.location.href = 'index.php?option=com_cleverdine&task=newspecialday&post_group='+group;
			<?php } else { ?>
				document.location.href = 'index.php?option=com_cleverdine&task=editspecialday&post_group='+group+'&cid[]=<?php echo $id; ?>';
			<?php } ?>
		});
		
	});
	
	// IMAGE

	<?php if( $sel['group'] == 1 ) { ?>

		var IMAGE_COUNT = <?php echo $cont; ?>
		
		function vrAddImageField() {
			var _html = '<div class="vr-sp-image" id="vrimagefield'+IMAGE_COUNT+'">\n'+
				'<?php echo $mediaManager->buildMedia('image[]', '{next_id}', '', true); ?>\n'+
			'</div>';

			jQuery('#vrspimgcont').append(_html.replace(/\{next_id\}/g, IMAGE_COUNT));

			vreRenderMediaSelect(IMAGE_COUNT);

			IMAGE_COUNT++;
		}

	<?php } ?>

	// VALIDATION

	jQuery(document).ready(function(){

		jQuery("#adminForm .required").on("blur", function(){
			if( jQuery(this).val().length > 0 ) {
				jQuery(this).removeClass("vrrequired");
			} else {
				jQuery(this).addClass("vrrequired");
			}
		});

	});

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