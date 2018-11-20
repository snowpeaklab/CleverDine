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

$shifts = $this->shifts;

$sel = null;
$id = -1;
if( !count( $this->selectedMenu ) ) {
	$sel = array(
		'name' => '', 'published' => 0, 'choosable' => 0, 'special_day' => 0, 'working_shifts' => '', 'days_filter' => '', 'description' => '', 'image' => ''
	);
} else {
	$sel = $this->selectedMenu;
	$id = $sel["id"];
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

// ALL PRODUCTS SELECT 
$products_select = '<select id="vrprodselect" multiple size="8" onChange>';
$products_select .= '</select>';
// END SELECT

$editor = JEditor::getInstance(JFactory::getApplication()->get('editor'));

$image_path = JUri::root().'components/com_cleverdine/assets/media/';

$curr_symb = cleverdine::getCurrencySymb(true);

$vik = new VikApplication(VersionListener::getID());

$mediaManager = new MediaManagerHTML(RestaurantsHelper::getAllMedia(), $image_path, $vik, 'vre');

?>

<form name="adminForm" id="adminForm" action="index.php" method="post" enctype="multipart/form-data">
	
	<div class="span6">
		<?php echo $vik->openFieldset(JText::_('VRMENUDETAILSFIELDSET'), 'form-horizontal'); ?>
			
			<!-- NAME - Text -->
			<?php echo $vik->openControl(JText::_('VRMANAGEMENU1').'*:'); ?>
				<input type="text" name="name" id="vrnametitle" class="required" value="<?php echo $sel["name"]; ?>" size="40"/>
			<?php echo $vik->closeControl(); ?>
			
			<!-- PUBLISHED - Radio Button -->
			<?php
			$elem_yes = $vik->initRadioElement('', JText::_('VRYES'), $sel['published']==1);
			$elem_no = $vik->initRadioElement('', JText::_('VRNO'), $sel['published']==0);
			?>
			<?php echo $vik->openControl(JText::_('VRMANAGEMENU26').':'); ?>
				<?php echo $vik->radioYesNo('published', $elem_yes, $elem_no, false); ?>
			<?php echo $vik->closeControl(); ?>
			
			<!-- CHOOSABLE - Radio Button -->
			<?php
			$elem_yes = $vik->initRadioElement('', $elem_yes->label, $sel['choosable']==1);
			$elem_no = $vik->initRadioElement('', $elem_no->label, $sel['choosable']==0);
			?>
			<?php echo $vik->openControl(JText::_('VRMANAGEMENU31').':'); ?>
				<?php echo $vik->radioYesNo('choosable', $elem_yes, $elem_no, false); ?>
			<?php echo $vik->closeControl(); ?>
			
			<!-- SPECIAL DAY - Radio Button -->
			<?php
			$elem_yes = $vik->initRadioElement('', $elem_yes->label, $sel['special_day']==1, 'onClick="specialDayChanged(1);"');
			$elem_no = $vik->initRadioElement('', $elem_no->label, $sel['special_day']==0, 'onClick="specialDayChanged(0);"');
			?>
			<?php echo $vik->openControl(JText::_('VRMANAGEMENU2').':'); ?>
				<?php echo $vik->radioYesNo('special_day', $elem_yes, $elem_no, false); ?>
			<?php echo $vik->closeControl(); ?>
			
			<!-- WORKING SHIFTS - Dropdown -->
			<?php if( strlen( $working_shifts_select ) > 0 ) { ?>
				<?php echo $vik->openControl(JText::_('VRMANAGEMENU3').':', 'vrspdaychild', ($sel['special_day'] ? 'style="display: none;"' : '')); ?>
					<?php echo $working_shifts_select; ?>
				<?php echo $vik->closeControl(); ?>
			<?php } ?>
			
			<!-- DAYS FILTER - Dropdown -->
			<?php echo $vik->openControl(JText::_('VRMANAGEMENU4').':', 'vrspdaychild', ($sel['special_day'] ? 'style="display: none;"' : '')); ?>
				<?php echo $days_filter_select; ?>
			<?php echo $vik->closeControl(); ?>
			
			<!-- IMAGE - File -->
			<?php echo $vik->openControl(JText::_('VRMANAGEMENU18').':'); ?>
				<?php echo $mediaManager->buildMedia('image', 0, $sel['image']); ?>
			<?php echo $vik->closeControl(); ?>
			
		<?php echo $vik->closeFieldset(); ?>
	</div>
	
	<div class="span5">
		<?php echo $vik->openFieldset(JText::_('VRMANAGEMENU17'), 'form-horizontal'); ?>
			<div class="control-group"><?php echo $editor->display( "description", $sel['description'], 400, 200, 70, 20 ); ?></div>
		<?php echo $vik->closeFieldset(); ?>
	</div>
	
	<div class="span10">
		<?php echo $vik->openFieldset(JText::_('VRMANAGEMENU20'), 'form-horizontal'); ?>
			
			<?php 
			$old_section_id = -1;

			$max_section_id = 0;
			$max_product_aid = 0;
			?>
			
			<div class="vrmenu-section-head">
				<a href="javascript: void(0);" onClick="jQuery('.vrmenusubsection').slideDown();" class="vrmenuheadbutton"><?php echo JText::_('VRMANAGEMENU29'); ?></a>
				<a href="javascript: void(0);" onClick="jQuery('.vrmenusubsection').slideUp();" class="vrmenuheadbutton"><?php echo JText::_('VRMANAGEMENU30'); ?></a>
			</div>
			
			<div id="vrmenusectioncont">
				<?php foreach( $this->sections as $s ) {
					if( $old_section_id != $s['id'] ) { ?>
						<?php if($old_section_id != -1 ) { ?>
							<!-- close products container tags -->
										</div>
										<div class="vrmenuprodaddlink">
											<a href="javascript: void(0);" onClick="showProductsDialog(<?php echo $old_section_id; ?>);" class="vraddprodmodlink"><?php echo JText::_('VRMANAGEMENU23'); ?></a>
										</div>
									</div>
								</div>
							</div>
							<!-- end close -->
						<?php } ?>
						<div class="vrmenusection" id="vrsection<?php echo $s['id']; ?>">
							<h3 class="vrmenutitle" id="vrtitle<?php echo $s['id']; ?>" onClick="changeSectionStatus(<?php echo $s['id']; ?>);"><?php echo $s['name']; ?></h3>
							<a href="javascript: void(0);" class="vrmenusecremovelink" onClick="removeSection(<?php echo $s['id']; ?>, 1);"></a>
							<div class="vrmenusubsection" id="vrsubsection<?php echo $s['id']; ?>" style="display: none;">
								<div class="control-group">
									<input type="text" name="sec_name[]" size="32" placeholder="<?php echo JText::_('VRMANAGEMENU27'); ?>" class="vrmenusectext" id="vrmenusectext<?php echo $s['id']; ?>" value="<?php echo $s['name']; ?>" onBlur="addTitle(<?php echo $s['id']; ?>);"/>
								</div>
								<div class="control-group">
									<textarea class="vrmenusecarea" name="sec_desc[]" placeholder="<?php echo JText::_('VRMANAGEMENU17'); ?>"><?php echo $s['description']; ?></textarea>
								</div>
								<div class="control-group">
									<input type="checkbox" value="1" id="vrmenusecbox<?php echo $s['id']; ?>" <?php echo ($s['published'] ? 'checked="checked"' : ''); ?> onChange="pubValueChanged(<?php echo $s['id']; ?>);"/>
									<label for="vrmenusecbox<?php echo $s['id']; ?>" style="display: inline-block"><?php echo JText::_('VRMANAGEMENU26'); ?></label>
									<input type="hidden" name="sec_publ[]" value="<?php echo ($s['published'] ? 1 : 0); ?>" id="vrmenusecpubhidden<?php echo $s['id']; ?>"/>
								</div>
								<div class="control-group">
									<input type="checkbox" value="1" id="vrmenusechighlight<?php echo $s['id']; ?>" <?php echo ($s['highlight'] ? 'checked="checked"' : ''); ?> onChange="highlightValueChanged(<?php echo $s['id']; ?>);"/>
									<label for="vrmenusechighlight<?php echo $s['id']; ?>" style="display: inline-block"><?php echo JText::_('VRMANAGEMENU32'); ?></label>
									<input type="hidden" name="sec_highlight[]" value="<?php echo ($s['highlight'] ? 1 : 0); ?>" id="vrmenusechighlighthidden<?php echo $s['id']; ?>"/>
								</div>
								<div class="control-group">
									<?php echo $mediaManager->buildMedia('sec_image[]', $s['id'], $s['image']); ?>
								</div>
								<input type="hidden" name="sec_id[]" id="vrtkentryid<?php echo $s['id']; ?>" value="<?php echo $s['id']; ?>" />
								<input type="hidden" name="sec_app_id[]" value="<?php echo $s['id']; ?>" />
								
								<div class="vrmenuprods">
									<div id="vrsectionplistdest<?php echo $s['id']; ?>"></div>
									<div class="vrmenuprodscont" id="vrmenuprodscont<?php echo $s['id']; ?>">
								
					<?php } 

					if( !empty($s['pid']) ) { ?>
						<div class="vrmenuproduct" id="vrmenuproduct<?php echo $s['aid']; ?>">
							<input type="text" readonly value="<?php echo $s['pname']; ?>" size="28"/>
							<input type="text" name="charge[<?php echo $s['id']; ?>][]" value="<?php echo ($s['acharge'] > 0 ? '+' : '').$s['acharge']; ?>" size="6" style="text-align: right;"/>
							<span class="vrmenuprodcurrsp"><?php echo $curr_symb; ?></span>
							<input type="hidden" name="prod_id[<?php echo $s['id']; ?>][]" value="<?php echo $s['pid']; ?>"/>
							<input type="hidden" name="real_prod_id[<?php echo $s['id']; ?>][]" value="<?php echo $s['aid']; ?>" />
							<a href="javascript: void(0);" class="vrmenuprodremovelink" onClick="removeProduct(<?php echo $s['aid']; ?>, 1);"></a>
						</div>
					<?php } ?>
				<?php 
					$old_section_id = $s['id'];

					$max_section_id = max(array($s['id'], $max_section_id));
					$max_product_aid = max(array($s['aid'], $max_product_aid));
				} 
				
				?>
				<!-- close products container tags -->
				<?php if( count($this->sections) > 0 ) { ?>
							   </div>
							   <div class="vrmenuprodaddlink">
									<a href="javascript: void(0);" onClick="showProductsDialog(<?php echo $old_section_id; ?>);" class="vraddprodmodlink"><?php echo JText::_('VRMANAGEMENU23'); ?></a>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>
				<!-- end close -->
			</div>
			
			<div class="vrmenu-section-bottom">
				<a href="javascript: void(0);" class="vrmenuaddsectionlink" onClick="addSection();"><?php echo JText::_('VRMANAGEMENU22'); ?></a></td>
			</div>
		
		<?php echo $vik->closeFieldset(); ?>
	</div>
	
	<input type="hidden" name="id" value="<?php echo $id; ?>"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="option" value="com_cleverdine"/>
</form>

<div class="vrprodsdialog" style="display: none;">
	<div class="vrprodslistdiv">
		<select id="vrsearch-list" onChange="addProductToSection();">
			<option></option>
			<?php foreach( $this->products as $p ) { ?>
				<option value="<?php echo $p['id']; ?>"><?php echo $p['name']; ?></option>
			<?php } ?>
		</select>
	</div>
	
	<input type="hidden" id="vrselectedsection" value="-1" />
</div>

<?php echo $mediaManager->buildModal(JText::_('VRMEDIAFIELDSET4')); ?>

<?php echo $mediaManager->useScript(JText::_("VRMANAGEMEDIA10")); ?>

<script>

	var sections_cont = <?php echo $max_section_id; ?>+1;
	var products_cont = <?php echo $max_product_aid; ?>+1;
	
	jQuery(document).ready(function() { 
		jQuery("#vrsearch-list").select2({
			placeholder: '<?php echo addslashes(JText::_('VRMANAGEMENU28')); ?>',
			allowClear: true,
			width: 300
		});
		
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
		
	});

	function specialDayChanged(is) {
		if( is ) {
			jQuery('.vrspdaychild').hide();
		} else {
			jQuery('.vrspdaychild').show();
		}
	}
	
	// 
	
	function addSection() {
		
		var _html =	'<div class="vrmenusection" id="vrsection'+sections_cont+'">\n'+
			   '<h3 class="vrmenutitle" id="vrtitle'+sections_cont+'" onClick="changeSectionStatus('+sections_cont+');"><?php echo addslashes(JText::_('VRMANAGEMENU27')); ?></h3>\n'+
			   '<a href="javascript: void(0);" class="vrmenusecremovelink" onClick="removeSection('+sections_cont+', 0);"></a>\n'+
			   '<div class="vrmenusubsection" id="vrsubsection'+sections_cont+'">\n'+
				   '<div class="control-group"\n>'+
					  '<input type="text" name="sec_name[]" size="32" placeholder="<?php echo addslashes(JText::_('VRMANAGEMENU27')); ?>" class="vrmenusectext" id="vrmenusectext'+sections_cont+'" onBlur="addTitle('+sections_cont+');"/>\n'+
					'</div>\n'+
					'<div class="control-group">\n'+
						'<textarea class="vrmenusecarea" name="sec_desc[]" placeholder="<?php echo addslashes(JText::_('VRMANAGEMENU17')); ?>"></textarea>\n'+
					'</div>\n'+
					'<div class="control-group">\n'+
						'<input type="checkbox" value="1" id="vrmenusecbox'+sections_cont+'" onChange="pubValueChanged('+sections_cont+')"/>\n'+
						'<label for="vrmenusecbox'+sections_cont+'" style="display: inline-block;"><?php echo addslashes(JText::_('VRMANAGEMENU26')); ?></label>\n'+
						'<input type="hidden" name="sec_publ[]" value="0" id="vrmenusecpubhidden'+sections_cont+'"/>\n'+
					'</div>\n'+
					'<div class="control-group">\n'+
						'<input type="checkbox" value="1" id="vrmenusechighlight'+sections_cont+'" checked="checked" onChange="highlightValueChanged('+sections_cont+');"/>\n'+
						'<label for="vrmenusechighlight'+sections_cont+'" style="display: inline-block"><?php echo addslashes(JText::_('VRMANAGEMENU32')); ?></label>\n'+
						'<input type="hidden" name="sec_highlight[]" value="1" id="vrmenusechighlighthidden'+sections_cont+'"/>\n'+
					'</div>\n'+
					'<div class="control-group vrmenusecimage">\n'+
						'<?php echo $mediaManager->buildMedia('sec_image[]', '{next_id}', '', true); ?>\n'+
					'</div>\n'+
					'<input type="hidden" name="sec_id[]" id="vrmenusecid'+sections_cont+'" value="-1" />\n'+
					'<input type="hidden" name="sec_app_id[]" id="vrmenuesecappid'+sections_cont+'" value="'+sections_cont+'" />\n'+
					'<div class="vrmenuprods">\n'+
						'<div id="vrsectionplistdest'+sections_cont+'"></div>\n'+
						'<div class="vrmenuprodscont" id="vrmenuprodscont'+sections_cont+'"></div>\n'+
						'<div class="vrmenuprodaddlink">\n'+
							'<a href="javascript: void(0);" onClick="showProductsDialog('+sections_cont+');" class="vraddprodmodlink"><?php echo addslashes(JText::_('VRMANAGEMENU23')); ?></a>\n'+
						'</div>\n'+
					'</div>\n'+
				'</div>\n'+
			'</div>\n'
		jQuery('#vrmenusectioncont').append(_html.replace(/\{next_id\}/g, sections_cont));

		vreRenderMediaSelect(sections_cont);
		
		sections_cont++;
	}
	
	function pubValueChanged(id) {
		jQuery('#vrmenusecpubhidden'+id).val(jQuery('#vrmenusecbox'+id).is(':checked') ? 1 : 0);
	}
	
	function highlightValueChanged(id) {
		jQuery('#vrmenusechighlighthidden'+id).val(jQuery('#vrmenusechighlight'+id).is(':checked') ? 1 : 0);
	}
	
	function removeSectionImageValueChanged(id) {
		jQuery('#vrmenusecrmimagehidden'+id).val(jQuery('#vrmenusecrmimage'+id).is(':checked') ? 1 : 0);
	}
	
	function showProductsDialog(id_section) {
		jQuery('#vrselectedsection').val(id_section);
		
		jQuery('.vrprodsdialog').appendTo('#vrsectionplistdest'+id_section);
		
		jQuery('.vrprodsdialog').show();
	}
	
	function addProductToSection() {
		var id_prod = jQuery('#vrsearch-list').val();
		var text_prod = jQuery('#vrsearch-list :selected').text();
		var id_section = jQuery('#vrselectedsection').val();
		
		jQuery('.vrmenuproduct').removeClass('vrmenuprodjustadded');
		
		var default_charge = 0;
		
		if( id_prod.length > 0 ) {
			jQuery('#vrmenuprodscont'+id_section).append(
				'<div class="vrmenuproduct vrmenuprodjustadded" id="vrmenuproduct'+products_cont+'">\n'+
				'<input type="text" readonly value="'+text_prod+'" size="28"/>\n'+
				'<input type="text" name="charge['+id_section+'][]" value="'+default_charge.toFixed(2)+'" size="6" style="text-align: right;"/>\n'+
				'<span class="vrmenuprodcurrsp"><?php echo $curr_symb; ?></span>\n'+
				'<input type="hidden" name="prod_id['+id_section+'][]" value="'+id_prod+'"/>\n'+
				'<input type="hidden" name="real_prod_id['+id_section+'][]" value="-1" />\n'+
				'<a href="javascript: void(0);" class="vrmenuprodremovelink" onClick="removeProduct('+products_cont+', 1);"></a>\n'+
				'</div>\n'
			);
		   
		   products_cont++;
		}
	}
	
	function removeSection(id, from_db) {
		jQuery('#vrsection'+id).remove();
		if( from_db ) {
		   jQuery('#adminForm').append('<input type="hidden" name="remove_section[]" value="'+id+'" />');
		}
	}
	
	function removeProduct(id, from_db) {
		jQuery('#vrmenuproduct'+id).remove();
		if( from_db ) {
			jQuery('#adminForm').append('<input type="hidden" name="remove_product[]" value="'+id+'" />');
		}
	}
	
	function addTitle(id) {
		jQuery('#vrtitle'+id).html(jQuery('#vrmenusectext'+id).val());
	}
	
	function changeSectionStatus(id) {
		if( jQuery('#vrsubsection'+id).is(':visible') ) {
			jQuery('#vrsubsection'+id).slideUp();
		} else {
			jQuery('#vrsubsection'+id).slideDown();
		}
	}

	// validation

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