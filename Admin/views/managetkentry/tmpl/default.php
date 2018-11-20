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
if( !count( $this->entry ) ) {
	$sel = array(
		'ename' => '', 'eprice' => 0, 'attributes' => array(), 'eimg' => '', 'epublished' => true, 'eready' => false, 'menuid' => $this->idMenu, 'edesc' => '', 'variations' => array()
	);
} else {
	$sel = $this->entry;
	$id = $sel['eid'];
}

/////

$image_path = JUri::root().'components/com_cleverdine/assets/media/';

$vik = new VikApplication(VersionListener::getID());

$mediaManager = new MediaManagerHTML(RestaurantsHelper::getAllMedia(), $image_path, $vik, 'vre');

/////

$curr_symb = cleverdine::getCurrencySymb(true);

$last_var_id = 0;

$max_topping_assoc_id = $max_group_assoc_id = 0;

$elem_yes = $vik->initRadioElement('', JText::_('VRYES'), 1);
$elem_no = $vik->initRadioElement('', JText::_('VRNO'), 0);

?>

<div class="row-fluid"><div class="span12">

<form action="index.php" method="POST" name="adminForm" id="adminForm" enctype="multipart/form-data">
	
	<div class="span6">
		<?php echo $vik->openFieldset(JText::_('VRMANAGETKENTRYFIELDSET1'), 'form-horizontal'); ?>
		
			<!-- NAME - Text -->
			<?php echo $vik->openControl(JText::_('VRMANAGETKMENU4').'*:'); ?>
				<input type="text" name="name" class="required" value="<?php echo $sel['ename']; ?>" size="30"/>
			<?php echo $vik->closeControl(); ?>
			
			<!-- PRICE - Number -->
			<?php echo $vik->openControl(JText::_('VRMANAGETKMENU5').':'); ?>
				<input type="number" name="price" value="<?php echo $sel['eprice']; ?>" min="0" max="999999" step="any"/>
				&nbsp;<?php echo $curr_symb; ?>
			<?php echo $vik->closeControl(); ?>
			
			<!-- ATTRIBUTES - Dropdown -->
			<?php
			$elements = array(
				$vik->initOptionElement('', '', false)
			);
			foreach( $this->menusAttributes as $attr ) {
				array_push($elements, $vik->initOptionElement($attr['id'], $attr['name'], @in_array($attr['id'], $sel['attributes'])));
			}
			?>
			<?php echo $vik->openControl(JText::_('VRMANAGETKMENU18').':'); ?>
				<?php echo $vik->dropdown('attributes[]', $elements, 'vrtk-attributes-select', '', 'multiple style="width: 85%;"'); ?>
			<?php echo $vik->closeControl(); ?>
			
			<!-- IMAGE - File -->
			<?php echo $vik->openControl(JText::_('VRMANAGETKMENU16').':'); ?>
				<?php echo $mediaManager->buildMedia('image', 1, $sel['eimg']); ?>
			<?php echo $vik->closeControl(); ?>
			
			<!-- PUBLISHED - Radio Button -->
			<?php
			$elem_yes = $vik->initRadioElement('', $elem_yes->label, $sel['epublished']==1);
			$elem_no = $vik->initRadioElement('', $elem_no->label, $sel['epublished']==0);
			?>
			<?php echo $vik->openControl(JText::_('VRMANAGETKMENU12').':'); ?>
				<?php echo $vik->radioYesNo('published', $elem_yes, $elem_no, false); ?>
			<?php echo $vik->closeControl(); ?>

			<!-- NO PREPARATION - Radio Button -->
			<?php
			$elem_yes = $vik->initRadioElement('', $elem_yes->label, $sel['eready']==1);
			$elem_no = $vik->initRadioElement('', $elem_no->label, $sel['eready']==0);
			?>
			<?php echo $vik->openControl(JText::_('VRMANAGETKMENU9').':'); ?>
				<?php echo $vik->radioYesNo('ready', $elem_yes, $elem_no, false); ?>
				<?php echo $vik->createPopover(array(
					"title" 	=> JText::_('VRMANAGETKMENU9'),
					"content" 	=> JText::_('VRMANAGETKMENU9_HELP'),
				)); ?>
			<?php echo $vik->closeControl(); ?>
			
			<!-- MENU PARENT - Dropdown -->
			<?php
			$elements = array();
			foreach( $this->allMenus as $menu ) {
				array_push($elements, $vik->initOptionElement($menu['id'], $menu['title'], $menu['id']==$sel['menuid']));
			}
			?>
			<?php echo $vik->openControl(JText::_('VRMANAGETKMENU15').':'); ?>
				<?php echo $vik->dropdown('id_menu', $elements, 'vrtk-menus-select'); ?>
			<?php echo $vik->closeControl(); ?>
			
			<!-- DESCRIPTION - Textarea -->
			<?php echo $vik->openControl(JText::_('VRMANAGETKMENU6').':'); ?>
				<textarea name="description" style="width: 90%;height: 120px;"><?php echo $sel['edesc']; ?></textarea>
			<?php echo $vik->closeControl(); ?>
		
		<?php echo $vik->closeFieldset(); ?>
	</div>
	
	<div class="span5">
		<?php echo $vik->openFieldset(JText::_('VRMANAGETKENTRYFIELDSET2'), 'form-horizontal'); ?>
			<div class="control-group">
				
				<div class="vrtk-entry-variations">
					<?php foreach( $sel['variations'] as $var ) { ?>
						<div id="vrtkoptdiv<?php echo $var['oid']; ?>" class="vrtk-entry-var">
							<span class="vrtk-entry-varsp">
								<span class="vrtk-entryvar-sortbox"></span>
								<input type="hidden" name="option_id[]" id="vrtkoptionid<?php echo $var['oid']; ?>" value="<?php echo $var['oid']; ?>" />
								<input type="text" name="oname[]" value="<?php echo $var['oname']; ?>" class="required" size="32" placeholder="<?php echo JText::_('VRMANAGETKMENU4'); ?>" />
								<input type="number" name="oprice[]" value="<?php echo $var['oprice']; ?>" step="any"/> <?php echo $curr_symb; ?>
								<a href="javascript: void(0);" class="vrtkmenuremovebutton" onClick="removeVariation(<?php echo $var['oid']; ?>);"></a>
							</span>
						</div>
						<?php $last_var_id = max(array($var['oid'], $last_var_id)); ?>
					<?php } ?>
				</div>
				
				<div class="vrtk-entry-addvar">
					<button type="button" class="btn" onClick="addNewVariation();">
						<?php echo JText::_('VRMANAGETKMENUADDVAR'); ?>
					</button>
				</div>
				
			</div>            
		<?php echo $vik->closeFieldset(); ?>
	</div>
	
	<div class="span11">
		<?php echo $vik->openFieldset(JText::_('VRMANAGETKENTRYFIELDSET3'), 'form-horizontal vrtk-groups-wrapper'); ?>
		<!-- GROUPS GO HERE -->
		
		<?php foreach( $this->entryGroups as $group ) { ?>
		
			<div class="vrtk-group-box" id="vrtkgroupbox<?php echo $group['id']; ?>">
				<div class="vrtk-group-sortbox"></div>
				
				<div class="vrtk-group-removebox">
					<a href="javascript: void(0);" onClick="removeGroup(<?php echo $group['id']; ?>, 1);"></a>
				</div>
				
				<div class="vrtk-group-left">
					<div class="vrtk-group-field">
						<span>
							<label for="vrtkinput-title<?php echo $group['id']; ?>"><?php echo JText::_('VRTKMANAGEENTRYGROUP1'); ?>:</label>
							<input type="text" name="title[]" value="<?php echo $group['title']; ?>" class="required" id="vrtkinput-title<?php echo $group['id']; ?>"/>
						</span>
					</div>
					<div class="vrtk-group-field">
						<span>
							<label><?php echo JText::_('VRTKMANAGEENTRYGROUP5'); ?>:</label>
							<select name="group_var[]" class="vr-groupvar-sel" id="vr-groupvar-sel<?php echo $group['id']; ?>">
								<option value=""></option>
								<?php foreach( $sel['variations'] as $var ) { ?>
									<option value="<?php echo $var['oid']; ?>" <?php echo ($var['oid'] == $group['id_variation'] ? 'selected="selected"' : ''); ?>><?php echo $var['oname']; ?></option>
								<?php } ?>
							</select>
						</span>
					</div>
					<div class="vrtk-group-field">
						<span>
							<label><?php echo JText::_('VRTKMANAGEENTRYGROUP2'); ?>:</label>
							<select name="multi[]" class="vr-groupmulti-sel" id="vrtkinput-multi<?php echo $group['id']; ?>" onChange="changeStatusMinMax(<?php echo $group['id']; ?>);">
								<option value="1" <?php echo ($group['multiple'] ? 'selected="selected"' : ''); ?>><?php echo JText::_('VRYES'); ?></option>
								<option value="0" <?php echo (!$group['multiple'] ? 'selected="selected"' : ''); ?>><?php echo JText::_('VRNO'); ?></option>
							</select>
						</span>
					</div>
					<div class="vrtk-group-field">
						<span>
							<label for="vrtkinput-min<?php echo $group['id']; ?>"><?php echo JText::_('VRTKMANAGEENTRYGROUP3'); ?>:</label>
							<input type="number" name="min[]" value="<?php echo $group['min_toppings']; ?>" id="vrtkinput-min<?php echo $group['id']; ?>" min="0" max="9999" <?php echo ($group['multiple'] ? '' : 'readonly'); ?> step="any"/>
						</span>
					</div>
					<div class="vrtk-group-field">
						<span>
							<label for="vrtkinput-max<?php echo $group['id']; ?>"><?php echo JText::_('VRTKMANAGEENTRYGROUP4'); ?>:</label>
							<input type="number" name="max[]" value="<?php echo $group['max_toppings']; ?>" id="vrtkinput-max<?php echo $group['id']; ?>" min="0" max="9999" <?php echo ($group['multiple'] ? '' : 'readonly'); ?> step="any"/>
						</span>
					</div>
				</div>
				<div class="vrtk-group-right">
					<div class="vrtk-toppings-choice" id="vrtoppingschoicebox<?php echo $group['id']; ?>">
						<button type="button" onClick="moveToppingsDropdown(<?php echo $group['id']; ?>);" class="vrshowdropdownlink btn" id="vrshowdropdownlink<?php echo $group['id']; ?>">
							<?php echo JText::_('VRTKENTRYADDTOPPINGS'); ?>
						</button>
					</div>
					<div class="vrtk-toppings-container" id="vrtktoppingscont<?php echo $group['id']; ?>">
						<?php foreach( $group['toppings'] as $tp ) { ?>
							<div class="vrtk-topping-row" id="vrtktopping<?php echo $tp['assoc_id']; ?>" data-assoc-id="<?php echo $tp['assoc_id']; ?>">
								<span>
									<span class="vrtk-topping-sortbox"></span>
									<input type="text" value="<?php echo $tp['name']; ?>" readonly size="28"/>
									<input type="number" name="topping_price[<?php echo $group['id']; ?>][]" value="<?php echo $tp['rate']; ?>" size="6" min="0" max="999999" step="any"/>&nbsp;<?php echo $curr_symb; ?>
									<a href="javascript: void(0);" class="vrtkmenuremovebutton" onClick="popTopping(<?php echo $tp['assoc_id']; ?>, 1);"></a>
									<input type="hidden" name="id_topping[<?php echo $group['id']; ?>][]" value="<?php echo $tp['id']; ?>"/>
									<input type="hidden" name="id_assoc_gt[<?php echo $group['id']; ?>][]" value="<?php echo $tp['assoc_id']; ?>"/>
								</span>
							</div>
							<?php
							$max_topping_assoc_id = max(array($max_topping_assoc_id, $tp['assoc_id']));
							?>
						<?php } ?>
					</div>
				</div>
				
				<input type="hidden" name="id_group[]" value="<?php echo $group['id']; ?>"/>
				<input type="hidden" name="id_tmp[]" value="<?php echo $group['id']; ?>"/>
			
			</div>
			<?php
			$max_group_assoc_id = max(array($max_group_assoc_id, $group['id']));
			?>
		<?php } ?>
		
		<div class="control-group" id="vrtk-entrygroup-button">
			<button type="button" class="btn" onClick="addToppingsGroup();">
				<?php echo JText::_('VRTKENTRYADDGROUP'); ?>
			</button>
		</div>
		
		<?php echo $vik->closeFieldset(); ?>
	</div>
	
	<input type="hidden" name="id" value="<?php echo $id; ?>"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="option" value="com_cleverdine"/>
	
</form>

<!-- row fluid and span12 div closure -->
</div></div>

<?php $last_separator_id = -1; ?>
<div id="vrtk-toppings-select-wrapper" style="display: none;">
	<select id="vrtk-toppings-select" onChange="pushTopping('');">
		<option></option>
		<?php foreach( $this->allToppings as $topping ) { ?>
			<?php if( $last_separator_id != $topping['id_sep'] ) { ?>
				<?php if( $last_separator_id != -1 ) { ?>
					</optgroup>
				<?php } ?>
				<?php
				$top_separator = (!empty($topping['separator']) ? $topping['separator'] : JText::_('VRTKOTHERSSEPARATOR'));
				?>
				<optgroup label="<?php echo $top_separator; ?>">
				<?php $last_separator_id = $topping['id_sep']; ?>
				
				<option value="<?php echo ($topping['id_sep']*-1); ?>" data-separator="<?php echo $topping['id_sep']; ?>"><?php echo strtolower(JText::sprintf('VRTKTOPPINGOPTGROUP', $top_separator)); ?></option>

			<?php } ?>
			<option value="<?php echo $topping['id']; ?>" data-price="<?php echo $topping['price']; ?>" id="topping<?php echo $topping['id']; ?>" class="separator<?php echo $topping['id_sep']; ?>"><?php echo $topping['name']; ?></option>
		<?php } ?>
		</optgroup>
	</select>
</div>
<input type="hidden" id="vrselectedgroup" value=""/>

<?php echo $mediaManager->buildModal(JText::_('VRMEDIAFIELDSET4')); ?>

<?php echo $mediaManager->useScript(JText::_("VRMANAGEMEDIA10")); ?>

<script>
	var attr_icons = <?php echo json_encode($this->menusAttributes); ?>;

	jQuery(document).ready(function(){
		jQuery("#vrtk-attributes-select").select2({
			placeholder: '<?php echo addslashes(JText::_('VRTKNOATTR')); ?>',
			allowClear: true,
			width: 'resolve',
			formatResult: format,
			formatSelection: format,
			escapeMarkup: function(m) { return m; }
		});
		
		jQuery('#vrtk-menus-select').select2({
			allowClear: false,
			width: 300
		});

		makeSelect('');

		var tarea = jQuery('textarea[name="description"]');
		tarea.css('max-width', tarea.width());
	});
	
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

	function makeSelect(id) {

		var selector = ['.vr-groupvar-sel', '.vr-groupmulti-sel'];

		if( id.length != 0 ) {
			selector = ['#vr-groupvar-sel'+id, '#vrtkinput-multi'+id];
		}

		jQuery(selector[0]).select2({
			placeholder: '<?php echo addslashes(JText::_("VRTKGROUPVARPLACEHOLDER")); ?>',
			allowClear: true,
			width: 300
		});

		jQuery(selector[1]).select2({
			minimumResultsForSearch: -1,
			allowClear: false,
			width: 150
		});
	}
	
	// VARIATIONS
	
	curr_var_index = <?php echo ($last_var_id+1); ?>;
	
	function addNewVariation() {
		jQuery('.vrtk-entry-variations').append(
			'<div id="vrtkoptdiv'+curr_var_index+'" class="vrtk-entry-var">\n'+
				'<span class="vrtk-entry-varsp">\n'+
					'<span class="vrtk-entryvar-sortbox"></span>\n'+
					'<input type="hidden" name="option_id[]" id="vrtkoptionid'+curr_var_index+'" value="-1" />\n'+
					'<input type="text" name="oname[]" value="" class="required" size="32" placeholder="<?php echo JText::_('VRMANAGETKMENU4'); ?>" />\n'+
					'<input type="text" name="oprice[]" value="" size="6" placeholder="<?php echo JText::_('VRMANAGETKMENU5'); ?>"/> <?php echo $curr_symb; ?>\n'+
					'<a href="javascript: void(0);" class="vrtkmenuremovebutton" onClick="removeVariation('+curr_var_index+');"></a>\n'+
				'</span>\n'+
			'</div>\n');
			
		curr_var_index++;
		
		makeSortable();
	}
	
	function removeVariation(var_id) {
		var table_row = jQuery('#vrtkoptionid'+var_id).val();
		jQuery('#vrtkoptdiv'+var_id).remove();
		if( table_row != -1 ) {
			jQuery('#adminForm').append('<input type="hidden" name="remove_variation[]" value="'+table_row+'"/>');
		}
	}
	
	// TOPPINGS
	
	jQuery(document).ready(function(){
		jQuery('#vrtk-toppings-select').select2({
			placeholder: '<?php echo addslashes(JText::_('VRTKTOPPINGSPLACEHOLDER')); ?>',
			allowClear: true,
			width: 300
		});
	});
	
	function addToppingsGroup() {
		jQuery('#vrtk-entrygroup-button').before(
			'<div class="vrtk-group-box" id="vrtkgroupbox'+GROUPS_COUNT+'">\n'+
				'<div class="vrtk-group-sortbox"></div>\n'+
				'<div class="vrtk-group-removebox">\n'+
					'<a href="javascript: void(0);" onClick="removeGroup('+GROUPS_COUNT+', 0);"></a>\n'+
				'</div>\n'+
				'<div class="vrtk-group-left">\n'+
					'<div class="vrtk-group-field">\n'+
						'<span>\n'+
							'<label for="vrtkinput-title'+GROUPS_COUNT+'"><?php echo addslashes(JText::_('VRTKMANAGEENTRYGROUP1')); ?>:</label>\n'+
							'<input type="text" name="title[]" value="" class="required" id="vrtkinput-title'+GROUPS_COUNT+'"/>\n'+
						'</span>\n'+
					'</div>\n'+
					'<div class="vrtk-group-field">\n'+
						'<span>\n'+
							'<label><?php echo addslashes(JText::_('VRTKMANAGEENTRYGROUP5')); ?>:</label>\n'+
							'<select name="group_var[]" class="vr-groupvar-sel" id="vr-groupvar-sel'+GROUPS_COUNT+'">\n'+
								'<option value=""></option>\n'+
								<?php foreach( $sel['variations'] as $var ) { ?>
									'<option value="<?php echo $var['oid']; ?>"><?php echo addslashes($var['oname']); ?></option>\n'+
								<?php } ?>
							'</select>\n'+
						'</span>\n'+
					'</div>\n'+
					'<div class="vrtk-group-field">\n'+
						'<span>\n'+
							'<label><?php echo addslashes(JText::_('VRTKMANAGEENTRYGROUP2')); ?>:</label>\n'+
							'<select name="multi[]" class="vr-groupmulti-sel" id="vrtkinput-multi'+GROUPS_COUNT+'" onChange="changeStatusMinMax('+GROUPS_COUNT+');">\n'+
								'<option value="1"><?php echo addslashes(JText::_('VRYES')); ?></option>\n'+
								'<option value="0"><?php echo addslashes(JText::_('VRNO')); ?></option>\n'+
							'</select>\n'+
						'</span>\n'+
					'</div>\n'+
					'<div class="vrtk-group-field">\n'+
						'<span>\n'+
							'<label for="vrtkinput-min'+GROUPS_COUNT+'"><?php echo addslashes(JText::_('VRTKMANAGEENTRYGROUP3')); ?>:</label>\n'+
							'<input type="number" name="min[]" value="0" id="vrtkinput-min'+GROUPS_COUNT+'" min="0" max="9999" step="any"/>\n'+
						'</span>\n'+
					'</div>\n'+
					'<div class="vrtk-group-field">\n'+
						'<span>\n'+
							'<label for="vrtkinput-max'+GROUPS_COUNT+'"><?php echo addslashes(JText::_('VRTKMANAGEENTRYGROUP4')); ?>:</label>\n'+
							'<input type="number" name="max[]" value="5" id="vrtkinput-max'+GROUPS_COUNT+'" min="0" max="9999" step="any"/>\n'+
						'</span>\n'+
					'</div>\n'+
				'</div>\n'+
				'<div class="vrtk-group-right">\n'+
					'<div class="vrtk-toppings-choice" id="vrtoppingschoicebox'+GROUPS_COUNT+'">\n'+
						'<button type="button" onClick="moveToppingsDropdown('+GROUPS_COUNT+');" class="vrshowdropdownlink btn" id="vrshowdropdownlink'+GROUPS_COUNT+'">\n'+
							'<?php echo addslashes(JText::_('VRTKENTRYADDTOPPINGS')); ?>\n'+
						'</button>\n'+
					'</div>\n'+
					'<div class="vrtk-toppings-container" id="vrtktoppingscont'+GROUPS_COUNT+'"></div>\n'+
				'</div>\n'+
				'<input type="hidden" name="id_group[]" value="-1"/>\n'+
				'<input type="hidden" name="id_tmp[]" value="'+GROUPS_COUNT+'"/>\n'+
			'</div>\n'
		);
		
		makeSortable();

		makeSelect(GROUPS_COUNT);
		
		GROUPS_COUNT++;
	}
		
	var GROUPS_COUNT = <?php echo ($max_group_assoc_id+1); ?>;
	var TOPPINGS_COUNT = <?php echo ($max_topping_assoc_id+1); ?>;

	function changeStatusMinMax(id) {
		if( jQuery('#vrtkinput-multi'+id).val() == "1" ) {
			jQuery('#vrtkinput-min'+id+', #vrtkinput-max'+id).prop('readonly', false);
		} else {
			jQuery('#vrtkinput-min'+id+', #vrtkinput-max'+id).prop('readonly', true);
			jQuery('#vrtkinput-min'+id+', #vrtkinput-max'+id).val('1');
		}
	}
	
	function removeGroup(id, erase) {
		jQuery('#vrtkgroupbox'+id).remove();
		if( erase ) {
			jQuery('#adminForm').append('<input type="hidden" name="remove_group[]" value="'+id+'"/>');
		}
	}

	function pushTopping(id_top) {
		var option = null;

		// get topping
		if( !id_top.length ) {
			id_top = jQuery('#vrtk-toppings-select').val();
			// get selected option
			option = jQuery('#vrtk-toppings-select :selected');
		} else {
			// get topping depending on specified ID
			option = jQuery('#vrtk-toppings-select option#topping'+id_top);
		}

		// if empty togging > exit
		if( id_top.length == 0 ) return;

		id_top = parseInt(id_top);

		console.log("ID TOPPING = "+id_top);

		if( id_top > 0 ) {

			// get topping name
			var name = option.text();
			// get topping price
			var price = option.data('price');
			
			var id_group = jQuery('#vrselectedgroup').val();

			var found = false;
			jQuery('#vrtktoppingscont'+id_group+' .vrtk-topping-row input[name="id_topping['+id_group+'][]"]').each(function(){
				if( jQuery(this).val() == id_top ) {
					found = true;
					return false;
				}
			});

			console.log('TRY TO PUSH TOPPING = '+id_top+" FOUND = "+found);

			if( !found ) {
				
				jQuery('#vrtktoppingscont'+id_group).append(
					'<div class="vrtk-topping-row" id="vrtktopping'+TOPPINGS_COUNT+'">\n'+
						'<span>\n'+
							'<span class="vrtk-topping-sortbox"></span>\n'+
							'<input type="text" value="'+name+'" readonly size="28"/>\n'+
							'<input type="number" name="topping_price['+id_group+'][]" value="'+price+'" size="6" min="0" max="999999" step="any"/> <?php echo $curr_symb; ?>\n'+
							'<a href="javascript: void(0);" class="vrtkmenuremovebutton" onClick="popTopping('+TOPPINGS_COUNT+', 0);"></a>\n'+
							'<input type="hidden" name="id_topping['+id_group+'][]" value="'+id_top+'"/>\n'+
							'<input type="hidden" name="id_assoc_gt['+id_group+'][]" value="-1"/>\n'+
						'</span>\n'+
					'</div>'
				);
				
				makeSortable();

				TOPPINGS_COUNT++;
			}

		} else {

			var separator = option.data('separator');

			console.log("ID SEPARATOR = "+separator);

			jQuery('#vrtk-toppings-select option.separator'+separator).each(function(){
				console.log("PUSH TOPPING RECURSIVE = "+jQuery(this).val());
				pushTopping(jQuery(this).val());
			});

		}
	}

	function popTopping(id, erase) {
		jQuery('#vrtktopping'+id).remove();
		if( erase ) {
			jQuery('#adminForm').append('<input type="hidden" name="remove_topping[]" value="'+id+'"/>');
		}
	}
	
	function moveToppingsDropdown(id_group) {
		jQuery('.vrshowdropdownlink').show();
		jQuery('#vrshowdropdownlink'+id_group).hide();
		
		jQuery('#vrtk-toppings-select').select2('val', '');
		
		jQuery('#vrselectedgroup').val(id_group);
		jQuery('#vrtk-toppings-select-wrapper').appendTo('#vrtoppingschoicebox'+id_group);
		jQuery('#vrtk-toppings-select-wrapper').show();
	}
	
	// ORDERING
	
	jQuery(document).ready(function(){
		makeSortable();
	});
	
	function makeSortable() {
		jQuery( ".vrtk-toppings-container" ).sortable({
			revert: true
		});
		//jQuery( ".vrtk-toppings-container, .vrtk-topping-row" ).disableSelection();
		
		/////////////////
		
		jQuery( ".vrtk-groups-wrapper" ).sortable({
			revert: true
		});
		//jQuery( ".vrtk-groups-wrapper, .vrtk-group-box" ).disableSelection();
		
		/////////////////
		
		jQuery( ".vrtk-entry-variations" ).sortable({
			revert: true
		});
		//jQuery( ".vrtk-entry-variations, .vrtk-entry-var" ).disableSelection();
		
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
