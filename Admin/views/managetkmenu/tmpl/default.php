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

JHtml::_('behavior.modal');

$menu_rows = $this->menu_rows;

$sel = null;
$id = -1;
if( !count( $this->selectedMenu ) ) {
	$sel = array(
		'title' => '', 'description' => '', 'published' => 0, 'taxes_type' => 0, 'taxes_amount' => 0.0
	);
} else {
	$sel = $this->selectedMenu;
	$id = $sel["id"];
}

$editor = JEditor::getInstance(JFactory::getApplication()->get('editor'));

$curr_symb = cleverdine::getCurrencySymb(true);

$image_path = JUri::root().'components/com_cleverdine/assets/media/';

$vik = new VikApplication(VersionListener::getID());

$mediaManager = new MediaManagerHTML(RestaurantsHelper::getAllMedia(), $image_path, $vik, 'vre');

$curr_var_index = 0;

?>

<form name="adminForm" action="index.php" method="post" enctype="multipart/form-data" id="adminForm">
	
	<div class="span6">
		<?php echo $vik->openFieldset(JText::_('VRTKMENUFIELDSET1'), 'form-horizontal'); ?>
		
			<!-- TITLE - Text -->
			<?php echo $vik->openControl(JText::_('VRMANAGETKMENU1').'*:'); ?>
				<input type="text" name="title" class="required" value="<?php echo $sel['title']; ?>" size="40"/>
			<?php echo $vik->closeControl(); ?>
			
			<!-- PUBLISHED - Radio Button -->
			<?php
			$elem_yes = $vik->initRadioElement('', JText::_('VRYES'), $sel['published']==1);
			$elem_no = $vik->initRadioElement('', JText::_('VRNO'), $sel['published']==0);
			?>
			<?php echo $vik->openControl(JText::_('VRMANAGETKMENU12').':'); ?>
				<?php echo $vik->radioYesNo('published', $elem_yes, $elem_no, false); ?>
			<?php echo $vik->closeControl(); ?>

			<!-- TAXES TYPE - Dropdown -->
			<?php
			$elements = array(
				$vik->initOptionElement(0, JText::_('VRTKMENUTAXESOPT1'), $sel['taxes_type'] == 0),
				$vik->initOptionElement(1, JText::_('VRTKMENUTAXESOPT2'), $sel['taxes_type'] == 1)
			);
			?>
			<?php echo $vik->openControl(JText::_('VRMANAGETKMENU22').':'); ?>
				<?php echo $vik->dropdown('taxes_type', $elements, 'vrtk-taxestype-sel'); ?>
				<span id="vrtk-taxes-amount" style="<?php echo ($sel['taxes_type'] ? '' : 'display: none;'); ?>">
					<input type="number" name="taxes_amount" value="<?php echo $sel['taxes_amount']; ?>" min="0" max="100" step="any"/>
					&nbsp;%
				</span>
			<?php echo $vik->closeControl(); ?>
		
		<?php echo $vik->closeFieldset(); ?>
	</div>
	
	<div class="span5">
		<?php echo $vik->openFieldset(JText::_('VRMANAGETKMENU2'), 'form-horizontal'); ?>
			<div class="control-group">
				<button type="button" class="btn" onClick="jQuery(this).hide();jQuery('.vrtkmenu-desc').slideDown();"><?php echo JText::_('VRMANAGETKMENU19'); ?></button>
				<div class="vrtkmenu-desc" style="display:none;"><?php echo $editor->display( "description", $sel['description'], 400, 200, 20, 20 ); ?></div>
			</div>
		<?php echo $vik->closeFieldset(); ?>
	</div>
	
	<div class="span10">
		<?php echo $vik->openFieldset(JText::_('VRTKMENUFIELDSET2'), 'form-horizontal'); ?>
			<div class="vradmintable">
				
				<?php 
				$prev_eid = -1; 
				$last_eid = -1;
				
				if( !empty( $menu_rows[0]['eid'] ) ) {
					for( $i = 0, $n = count($menu_rows); $i < $n; $i++ ) {
						
						if( $menu_rows[$i]['eid'] != $prev_eid ) { 
							if( $prev_eid != -1 ) { ?>
								</div>
										<input type="button" class="vrtkmenuaddvarbutton" value="<?php echo JText::_('VRMANAGETKMENUADDVAR'); ?>" onClick="addNewVariation(<?php echo $prev_eid; ?>);" />
									</div>
									
									<div class="vrtkmenu-sort-bottombox"></div>
									
								</div>
									
							</div>
							<?php } ?>
							
							<div id="vrtknode<?php echo $menu_rows[$i]['eid']; ?>" class="vrtkmenutr">
								<input type="hidden" name="entry_id[]" id="vrtkentryid<?php echo $menu_rows[$i]['eid']; ?>" value="<?php echo $menu_rows[$i]['eid']; ?>" />
								<input type="hidden" name="entry_app_id[]" id="vrtkentryappid<?php echo $menu_rows[$i]['eid']; ?>" value="<?php echo $menu_rows[$i]['eid']; ?>" />
								<input type="hidden" name="eready[]" id="vrtkentryready<?php echo $menu_rows[$i]['eid']; ?>" value="<?php echo $menu_rows[$i]['eready']; ?>" />
								
								<div class="vrtkmenuentrydiv">
									
									<div class="vrtkmenuentry">
										<a href="javascript: void(0);" class="vrtkmenuremovebutton" onClick="removeEntry(<?php echo $menu_rows[$i]['eid'];?>);"></a>
										
										<div class="vrtkmenuentryrow">
											<span class="vrtkmenuentryrowsp">
												<input type="text" name="ename[]" value="<?php echo $menu_rows[$i]['ename']; ?>" class="required" size="20" placeholder="<?php echo JText::_('VRMANAGETKMENU4'); ?>" />
												<input type="text" name="eprice[]" value="<?php echo $menu_rows[$i]['eprice']; ?>" size="6" placeholder="<?php echo JText::_('VRMANAGETKMENU5'); ?>" /> <?php echo $curr_symb; ?>
											</span>
										</div>
										
										<div class="vrtkmenuentryrow">
											<span class="vrtkmenuentryrowsp">
												<select name="eattribute[<?php echo $menu_rows[$i]['eid']; ?>][]" class="vr-tkentry-attr-select" multiple style="width:100%;">
													<option></option>
													<?php foreach( $this->menusAttributes as $attr ) { 
														$selected = (@in_array($attr['id'], $menu_rows[$i]['attributes']) ? 'selected="selected"' : '');
														?>
														<option value="<?php echo $attr['id']; ?>" <?php echo $selected; ?>><?php echo $attr['name']; ?></option>
													<?php } ?>
												</select>
											</span>
										</div>
										
										<div class="vrtkmenuentryrow">
											<?php echo $mediaManager->buildMedia('efile[]', $menu_rows[$i]['eid'], $menu_rows[$i]['eimg']); ?>
										</div>
										<div class="vrtkmenuentryrow">
											<span class="vrtkmenuentryrowsp">
												<textarea name="edesc[]" placeholder="<?php echo JText::_('VRMANAGETKMENU6'); ?>" ><?php echo $menu_rows[$i]['edesc']; ?></textarea>
											</span>
										</div>
										
										<div class="vrtkmenuentryrow">
											<span class="vrtkmenuentryrowchecksp">
												<input type="checkbox" value="1" id="vrcheckready<?php echo $menu_rows[$i]['eid']; ?>" <?php echo (($menu_rows[$i]['eready']) ? 'checked="checked"' : ''); ?> onChange="readyChangeStatus(<?php echo $menu_rows[$i]['eid']; ?>);"/>
												<label for="vrcheckready<?php echo $menu_rows[$i]['eid']; ?>"><?php echo JText::_('VRMANAGETKMENU9'); ?></label>
											</span>
										</div>
										
									</div>
									
									<div class="vrtkmenuentryoptionsdiv">
										<div id="vrtkentryopts<?php echo $menu_rows[$i]['eid']; ?>" class="vrtkmenuentryinneroptions">
							
						<?php } 
						
						if( !empty( $menu_rows[$i]['oname'] ) ) { ?> 
							<div id="vrtkoptdiv<?php echo $menu_rows[$i]['oid']; ?>" class="vrtksingleoptdiv">
								<span class="vrtkoptionsp">
									<input type="hidden" name="option_id[<?php echo $menu_rows[$i]['eid']; ?>][]" id="vrtkoptionid<?php echo $menu_rows[$i]['oid']; ?>" value="<?php echo $menu_rows[$i]['oid']; ?>" />
									<input type="text" name="oname[<?php echo $menu_rows[$i]['eid']; ?>][]" value="<?php echo $menu_rows[$i]['oname']; ?>" class="required" size="16" placeholder="<?php echo JText::_('VRMANAGETKMENU7'); ?>" />
									<input type="text" name="oprice[<?php echo $menu_rows[$i]['eid']; ?>][]" value="<?php echo $menu_rows[$i]['oprice']; ?>" size="6" placeholder="<?php echo JText::_('VRMANAGETKMENU8'); ?>" /> <?php echo $curr_symb; ?>
									<a href="javascript: void(0);" class="vrtkmenuremovebutton" onClick="removeOption(<?php echo $menu_rows[$i]['oid']; ?>);"></a>
								</span>
							</div>
							
						<?php 
							$curr_var_index++;
						}
						
						$prev_eid = $menu_rows[$i]['eid'];
						$last_eid = max(array($menu_rows[$i]['eid'], $last_eid));
					} 
				
				}?>
				
				<?php if( count($menu_rows) > 0 ) { ?>
								</div>
								<input type="button" class="vrtkmenuaddvarbutton" value="<?php echo JText::_('VRMANAGETKMENUADDVAR'); ?>" onClick="addNewVariation(<?php echo $prev_eid; ?>);" />
							</div>
							
							<div class="vrtkmenu-sort-bottombox"></div>
							
						</div>
							
					</div>
				<?php } ?>
				
				<div class="vrtkmenu-addentry-box">
					<?php echo JText::_('VRMANAGETKMENUADDENTRY'); ?>
				</div>
				
			</div>
		<?php echo $vik->closeFieldset(); ?>
	</div>
	
	<input type="hidden" name="id" value="<?php echo $id; ?>"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="option" value="com_cleverdine"/>
</form>

<!-- JQUERY MODALS -->
<div class="modal hide fade" id="jmodal-managetkentry" style="width:90%;height:90%;margin-left:-45%;">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h3><?php echo JText::_('VRMANAGETKENTRYTITLE'); ?></h3>
	</div>
	<div id="jmodal-box-managetkentry"></div>
</div>

<!-- JQUERY DIALOGS -->
<div id="dialog-confirm" title="<?php echo JText::_('VRTKMENUREFRESHTITLE');?>" style="display: none;">
	<p>
		<span class="ui-icon ui-icon-locked" style="float: left; margin: 0 7px 20px 0;"></span>
		<span><?php echo JText::_('VRTKMENUREFRESHMESSAGE'); ?></span>
	</p>
</div>

<?php echo $mediaManager->buildModal(JText::_('VRMEDIAFIELDSET4')); ?>

<?php echo $mediaManager->useScript(JText::_("VRMANAGEMEDIA10")); ?>

<script type="text/javascript">

	var attr_icons = <?php echo json_encode($this->menusAttributes); ?>;

	jQuery(document).ready(function(){
		jQuery('.vrtkmenu-addentry-box').on('click', function(){
			addNewEntry();
		});
		
		renderSelectAttributes('.vr-tkentry-attr-select');
		
		jQuery('.vr-tkentry-attr-select').on('change', function(){
			calibrateHeight();    
		});

		jQuery('#vrtk-taxestype-sel').select2({
			minimumResultsForSearch: -1,
			allowClear: false,
			width: 150
		});

		jQuery('#vrtk-taxestype-sel').on('change', function(){
			if( jQuery(this).val() == "1" ) {
				jQuery('#vrtk-taxes-amount').show();
			} else {
				jQuery('#vrtk-taxes-amount').hide();
			}
		});
		
		calibrateHeight();
	});
	
	function renderSelectAttributes(selector) {
		jQuery(selector).select2({
			placeholder: '<?php echo addslashes(JText::_('VRTKNOATTR')); ?>',
			allowClear: true,
			width: 'resolve',
			formatResult: format,
			formatSelection: format,
			escapeMarkup: function(m) { return m; }
		});
	}
	
	function format(attr) {
		if(!attr.id) return attr.text; // optgroup
		var icon = getIconFromID(attr.id);
		if(icon.length == 0) return attr.text; // no icon

		return "<img class='vr-opt-tkattr' src='<?php echo JUri::root(); ?>components/com_cleverdine/assets/media/"+icon+"'/> " + attr.text;
	}
	
	function getIconFromID(id) {
		for( var i = 0; i < attr_icons.length; i++ ) {
			if( attr_icons[i].id == id ) {
				return attr_icons[i].icon;
			}
		}
		return '';
	}

	var curr_index = <?php echo $last_eid+1; ?>;
	var curr_var_index = <?php echo $curr_var_index; ?>;
	if( curr_index == 0 ) {
		curr_var_index = 0;
	}
	
	function addNewEntry() {
		var _html = '<div id="vrtknode'+curr_index+'" class="vrtkmenutr">\n'+
			'<input type="hidden" name="entry_id[]" id="vrtkentryid'+curr_index+'" value="-1" />'+
			'<input type="hidden" name="entry_app_id[]" id="vrtkentryappid'+curr_index+'" value="'+curr_index+'" />'+
			'<input type="hidden" name="eready[]" id="vrtkentryready'+curr_index+'" value="0" />'+
			'<div class="vrtkmenuentrydiv">\n'+
				
				'<div class="vrtkmenuentry">\n'+
					'<a href="javascript: void(0);" class="vrtkmenuremovebutton" onClick="removeEntry('+curr_index+');"></a>\n'+
					
					'<div class="vrtkmenuentryrow">\n'+
						'<span class="vrtkmenuentryrowsp">\n'+
							'<input type="text" name="ename[]" value="" class="required" size="20" placeholder="<?php echo JText::_('VRMANAGETKMENU4'); ?>" />\n'+
							'<input type="text" name="eprice[]" value="" size="6" placeholder="<?php echo JText::_('VRMANAGETKMENU5'); ?>" /> <?php echo $curr_symb; ?>\n'+
						'</span>\n'+
					'</div>\n'+
					
					'<div class="vrtkmenuentryrow">\n'+
						'<span class="vrtkmenuentryrowsp">\n'+
							'<select name="eattribute['+curr_index+'][]" class="vr-tkentry-attr-select" multiple style="width:100%;" id="vrtkattrsel'+curr_index+'">\n'+
								'<option></option>\n'+
								<?php foreach( $this->menusAttributes as $attr ) { ?>
									'<option value="<?php echo $attr['id']; ?>"><?php echo $attr['name']; ?></option>\n'+
								<?php } ?>
							'</select>\n'+  
						'</span>\n'+
					'</div>'+
					
					'<div class="vrtkmenuentryrow">\n'+
						'<?php echo $mediaManager->buildMedia('efile[]', '{next_id}', '', true); ?>\n'+
					'</div>\n'+
					
					'<div class="vrtkmenuentryrow">\n'+
						'<span class="vrtkmenuentryrowsp">\n'+
							'<textarea name="edesc[]" placeholder="<?php echo JText::_('VRMANAGETKMENU6'); ?>" ></textarea>\n'+
						'</span>\n'+
					'</div>\n'+
					
					'<div class="vrtkmenuentryrow">\n'+
						'<span class="vrtkmenuentryrowchecksp">\n'+
							'<input type="checkbox" value="1" id="vrcheckready'+curr_index+'" onChange="readyChangeStatus('+curr_index+');"/>\n'+
							'<label for="vrcheckready'+curr_index+'"><?php echo JText::_('VRMANAGETKMENU9'); ?></label>\n'+
						'</span>\n'+
					'</div>\n'+
				'</div>\n'+
				
				'<div class="vrtkmenuentryoptionsdiv">\n'+
					'<div id="vrtkentryopts'+curr_index+'" class="vrtkmenuentryinneroptions">'+'</div>\n'+
					'<input type="button" class="vrtkmenuaddvarbutton" value="<?php echo JText::_('VRMANAGETKMENUADDVAR'); ?>" onClick="addNewVariation('+curr_index+');" />\n'+
				'</div>\n'+
				
				'<div class="vrtkmenu-sort-bottombox"></div>\n'+
			'</div>\n'+
		'</div>\n';
		jQuery('.vrtkmenu-addentry-box').before(_html.replace(/\{next_id\}/g, curr_index));

		vreRenderMediaSelect(curr_index);
		
		renderSelectAttributes('#vrtkattrsel'+curr_index);
		
		curr_index++;
		
		calibrateHeight();
		makeSortable();
	}
	
	function addNewVariation(id) {
		jQuery('#vrtkentryopts'+id).append(
			'<div id="vrtkoptdiv'+curr_var_index+'" class="vrtksingleoptdiv">\n'+
				'<span class="vrtkoptionsp">\n'+
					'<input type="hidden" name="option_id['+id+'][]" id="vrtkoptionid'+curr_var_index+'" value="-1" />\n'+
					'<input type="text" name="oname['+id+'][]" value="" class="required" size="16" placeholder="<?php echo JText::_('VRMANAGETKMENU7'); ?>" />\n'+
					'<input type="text" name="oprice['+id+'][]" value="" size="6" placeholder="<?php echo JText::_('VRMANAGETKMENU8'); ?>"/> <?php echo $curr_symb; ?>\n'+
					'<a href="javascript: void(0);" class="vrtkmenuremovebutton" onClick="removeOption('+curr_var_index+');"></a>\n'+
				'</span>\n'+
			'</div>\n');
			
		curr_var_index++;
		
		calibrateHeight();
	}
	
	function removeEntry(id) {
		var table_row = jQuery('#vrtkentryid'+id).val();
		jQuery('#vrtknode'+id).remove();
		if( table_row != -1 ) {
			jQuery('#adminForm').append('<input type="hidden" name="remove_entry[]" value="'+table_row+'"/>');
		}
	}
	
	function removeOption(opt_id) {
		var table_row = jQuery('#vrtkoptionid'+opt_id).val();
		jQuery('#vrtkoptdiv'+opt_id).remove();
		if( table_row != -1 ) {
			jQuery('#adminForm').append('<input type="hidden" name="remove_option[]" value="'+table_row+'"/>');
		}
	}
	
	function readyChangeStatus(id) {
		if( jQuery('#vrtkentryready'+id).val() == 1 ) {
			jQuery('#vrtkentryready'+id).val( '0' ); 
		} else {
			jQuery('#vrtkentryready'+id).val( '1' ); 
		}
	}
	
	function resizeChangeStatus(is) {
		jQuery('#vrtextresize').prop( 'readonly', is ? false : true );
	}
	
	function calibrateHeight() {
		var i = 0;
		var max_height = 0;
		var h;
		var stack = null;
		jQuery('.vrtkmenuentrydiv').each(function(){
			if( (i % 3) == 0 ) {
				if( stack !== null ) {
					jQuery.each(stack, function(k, v){
						jQuery(v).css('min-height', max_height+'px');
					});
				}
				
				stack = new Array();
				max_height = 0;
				
			}
			
			h = jQuery(this).height();
			if( h > max_height ) {
				max_height = h;
			}
			
			stack.push(this);
			
			i = (i+1)%3;
		});
		
		if( stack !== null ) {
			jQuery.each(stack, function(k, v){
				jQuery(v).css('min-height', (max_height+1)+'px');
			});
		}
	}
	
	// ORDERING
	
	jQuery(document).ready(function(){
		makeSortable();
	});
	
	function makeSortable() {
		jQuery( ".vradmintable" ).sortable({
			revert: true,
			items: ".vrtkmenutr"
		});
		//jQuery( ".vradmintable, .vrtkmenutr" ).disableSelection();
	}

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