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

// load calendar behavior
JHtml::_('behavior.calendar');

$sel = null;
$id = -1;
if( !count( $this->selectedDeal ) ) {
	$sel = array(
		'name' => '', 'description' => '', 'start_ts' => -1, 'end_ts' => -1, 'max_quantity' => 1, 'published' => 0, 'type' => 0, 'days_filter' => array(),
		'amount' => 0.0, 'percentot' => 2, 'auto_insert' => 0, 'min_quantity' => 1, 'cart_tcost' => 0.0
	);
} else {
	$sel = $this->selectedDeal;
	$id = $sel['id'];
}

// ALL PRODUCTS SELECT
$all_prod_select = '<select id="vrtk-allprod-select">';
$all_prod_select .= '<option></option>';
foreach( $this->allProductsMenus as $menu ) {
	$all_prod_select .= '<optgroup label="'.$menu['title'].'">';
	foreach( $menu['products'] as $prod ) {
		$all_prod_select .= '<option value="'.$prod['id'].':-1">'.$prod['name'].'</option>';
		foreach( $prod['options'] as $opt ) {
			$all_prod_select .= '<option value="'.$prod['id'].':'.$opt['id'].'">'.$prod['name']." - ".$opt['name'].'</option>';
		}
	}
	$all_prod_select .= '</optgroup>';
}
$all_prod_select .= '</select>';

// ALL GIFT SELECT
$all_gift_select = '<select id="vrtk-allgift-select">';
$all_gift_select .= '<option></option>';
foreach( $this->allProductsMenus as $menu ) {
	$all_gift_select .= '<optgroup label="'.$menu['title'].'">';
	foreach( $menu['products'] as $prod ) {
		$all_gift_select .= '<option value="'.$prod['id'].':-1">'.$prod['name'].'</option>';
		foreach( $prod['options'] as $opt ) {
			$all_gift_select .= '<option value="'.$prod['id'].':'.$opt['id'].'">'.$prod['name']." - ".$opt['name'].'</option>';
		}
	}
	$all_gift_select .= '</optgroup>';
}
$all_gift_select .= '</select>';


$curr_symb = cleverdine::getCurrencySymb(true);

$date_format = cleverdine::getDateFormat(true);

if ($sel['start_ts'] != -1 && $sel['end_ts'] != -1) {
	$sel['start_ts'] 	= date($date_format, $sel['start_ts']);
	$sel['end_ts'] 		= date($date_format, $sel['end_ts']);
} else {
	$sel['start_ts'] 	= '';
	$sel['end_ts'] 		= '';
}

$editor = JEditor::getInstance(JFactory::getApplication()->get('editor'));

$vik = new VikApplication(VersionListener::getID());

$deal_food_count = $free_food_count = 0;

?>

<form name="adminForm" action="index.php" method="post" enctype="multipart/form-data" id="adminForm">
	
	<div></div>
	
	<div class="span6">
		<?php echo $vik->openFieldset(JText::_('VRTKDEALFIELDSET1'), 'form-horizontal'); ?>
	
			<!-- NAME - Text -->
			<?php echo $vik->openControl(JText::_('VRMANAGETKDEAL2').'*:'); ?>
				<input class="required" type="text" name="name" value="<?php echo $sel['name']; ?>" size="40"/>
			<?php echo $vik->closeControl(); ?>
			
			<!-- START DATE - Calendar -->
			<?php echo $vik->openControl(JText::_('VRMANAGETKDEAL4').':'); ?>
				<?php echo $vik->calendar($sel['start_ts'], 'start_ts', 'start_ts'); ?>
			<?php echo $vik->closeControl(); ?>
			
			<!-- END DATE - Calendar -->
			<?php echo $vik->openControl(JText::_('VRMANAGETKDEAL5').':'); ?>
				<?php echo $vik->calendar($sel['end_ts'], 'end_ts', 'end_ts'); ?>
			<?php echo $vik->closeControl(); ?>
			
			<!-- MAX QUANTITY - Number -->
			<?php
			$elements = array(
				$vik->initOptionElement(1, JText::_('VRTKDEALQUANTITYOPT1'), $sel['max_quantity'] <= 0),
				$vik->initOptionElement(2, JText::_('VRTKDEALQUANTITYOPT2'), $sel['max_quantity'] > 0),
			);
			?>
			<?php echo $vik->openControl(JText::_('VRMANAGETKDEAL6').':'); ?>
				<?php echo $vik->dropdown('tkquant_type', $elements, 'vrtk-quantity-select'); ?>
				<input type="number" name="max_quantity" value="<?php echo $sel['max_quantity']; ?>" min="0" max="9999" style="<?php echo ($sel['max_quantity'] <= 0 ? 'display: none;' : ''); ?>"/>
			<?php echo $vik->closeControl(); ?>
			
			<!-- PUBLISHED - Radio Button -->
			<?php
			$elem_yes = $vik->initRadioElement('', '', $sel['published'] == 1);
			$elem_no = $vik->initRadioElement('', '', $sel['published'] == 0);
			?>
			<?php echo $vik->openControl(JText::_('VRMANAGETKDEAL7').':'); ?>
				<?php echo $vik->radioYesNo('published', $elem_yes, $elem_no, false); ?>
			<?php echo $vik->closeControl(); ?>
			
			<!-- DAYS FILTER - Dropdown -->
			<?php
			$elements = array();
			for( $i = 1; $i <= 7; $i++ ) {
				array_push($elements, $vik->initOptionElement(($i == 7 ? 0 : $i), JText::_('VRDAY'.$i), @in_array(($i == 7 ? 0 : $i), $sel['days_filter'])));
			}
			?>
			<?php echo $vik->openControl(JText::_('VRMANAGETKDEAL13').':'); ?>
				<?php echo $vik->dropdown('days_filter[]', $elements, 'vrtk-daysfilter-select', '', 'multiple'); ?>
			<?php echo $vik->closeControl(); ?>
			
			<!-- TYPE - Dropdown -->
			<?php
			$elements = array(
				$vik->initOptionElement('', '', false)
			);
			for( $i = 1; $i <= 6; $i++ ) {
				array_push($elements, $vik->initOptionElement($i, JText::_('VRTKDEALTYPE'.$i), $i==$sel['type']));
			}
			?>
			<?php echo $vik->openControl(JText::_('VRMANAGETKDEAL8').'*:'); ?>
				<?php echo $vik->dropdown('deal_type', $elements, 'vrtk-type-select', 'required'); ?>

				<?php 
				if ($sel['type'] > 0) {
					echo $vik->createPopover(array(
						'title' => JText::_('VRTKDEALTYPE'.$sel['type']),
						'content' => JText::_('VRTKDEALTYPEDESC'.$sel['type']),
					));
				}
				?>

			<?php echo $vik->closeControl(); ?>
	
		<?php $vik->closeFieldset(); ?>
	</div>
	
	<div class="span5">
		<?php echo $vik->openFieldset(JText::_('VRMANAGETKDEAL3'), 'form-horizontal'); ?>
			<div class="control-group"><?php echo $editor->display('description', $sel['description'], 400, 200, 20, 20); ?></div> 
		<?php $vik->closeFieldset(); ?>
	</div>
	
	<?php if( ($sel['type'] > 0 && $sel['type'] <= 4) || $sel['type'] == 6 ) { ?>
		<div class="span11" id="vrtk-dealparams" style="margin-bottom: 100px;">
			<?php echo $vik->openFieldset(JText::_('VRTKDEALFIELDSET2'), 'form-horizontal'); ?>
			
				<?php if( $sel['type'] == 1 ) { ?>
					
					<!-- AMOUNT / PERCENT OR TOTAL - Number -->
					<?php
					$elements = array(
						$vik->initOptionElement(1, '%', $sel['percentot'] == 1),
						$vik->initOptionElement(2, $curr_symb, $sel['percentot'] == 2),
					);
					?>
					<?php echo $vik->openControl(JText::_('VRMANAGETKDEAL10').':'); ?>
						<input type="number" name="amount" value="<?php echo $sel['amount']; ?>" min="0" step="any"/>
						<?php echo $vik->dropdown('percentot', $elements, '', 'short'); ?>
					<?php echo $vik->closeControl(); ?>
					
					<!-- MIN QUANTITY - Number -->
					<?php echo $vik->openControl(JText::_('VRMANAGETKDEAL17').':'); ?>
						<input type="number" name="min_quantity" value="<?php echo $sel['min_quantity']; ?>" min="1"/>
					<?php echo $vik->closeControl(); ?>
					
					<!-- TARGET FOOD - Form -->
					<?php echo $vik->openControl(JText::_('VRMANAGETKDEAL14').':'); ?>
						<?php echo $all_prod_select; ?>
						<div id="vrtk-reqfood-container" class="vrtk-food-container">
							<?php foreach( $this->dealProducts as $deal_prod ) { ?>
								<div class="vrtk-dealfood-row" id="vrtkdealfood<?php echo $deal_prod['id']; ?>">
									<input type="text" readonly value="<?php echo $deal_prod['product_name'].(!empty($deal_prod['option_name']) ? " - ".$deal_prod['option_name'] : ""); ?>" size="32"/>
									<select name="deal_food[required][]" class="vik-dropdown medium">
										<option value="1" <?php echo ($deal_prod['required'] == 1 ? 'selected="selected"' : ''); ?>><?php echo JText::_('VRTKDEALTARGETOPT1'); ?></option>
										<option value="0" <?php echo ($deal_prod['required'] == 0 ? 'selected="selected"' : ''); ?>><?php echo JText::_('VRTKDEALTARGETOPT2'); ?></option>
									</select>
									<span>x</span><input type="number" name="deal_food[quantity][]" value="<?php echo $deal_prod['quantity']; ?>" min="1" max="9999" class="vrtkdealfoodquantity"/>
									<input type="hidden" name="deal_food[id_prod_option][]" value="<?php echo $deal_prod['id_product'].":".$deal_prod['id_option']; ?>" class="vrtkdealfoodid"/>
									<input type="hidden" name="deal_food[id][]" value="<?php echo $deal_prod['id']; ?>" class="vrtkrealid"/>
									<a href="javascript: void(0);" class="vrtk-dealfood-trash" onClick="removeSelectedDealFood(<?php echo $deal_prod['id']; ?>);"></a>
								</div>
							<?php 
								$deal_food_count = max(array($deal_food_count, $deal_prod['id']));
							} ?>
						</div>
					<?php echo $vik->closeControl(); ?>
					
				<?php } else if( $sel['type'] == 2 ) { ?>
					
					<!-- AMOUNT / PERCENT OR TOTAL - Number -->
					<?php
					$elements = array(
						$vik->initOptionElement(1, '%', $sel['percentot'] == 1),
						$vik->initOptionElement(2, $curr_symb, $sel['percentot'] == 2),
					);
					?>
					<?php echo $vik->openControl(JText::_('VRMANAGETKDEAL10').':'); ?>
						<input type="number" name="amount" value="<?php echo $sel['amount']; ?>" min="0" step="any"/>
						<?php echo $vik->dropdown('percentot', $elements, '', 'short'); ?>
					<?php echo $vik->closeControl(); ?>
					
					<!-- TARGET FOOD - Form -->
					<?php echo $vik->openControl(JText::_('VRMANAGETKDEAL14').':'); ?>
						<?php echo $all_prod_select; ?>
						<div id="vrtk-reqfood-container" class="vrtk-food-container">
							<?php foreach( $this->dealProducts as $deal_prod ) { ?>
								<div class="vrtk-dealfood-row" id="vrtkdealfood<?php echo $deal_prod['id']; ?>">
									<input type="text" readonly value="<?php echo $deal_prod['product_name'].(!empty($deal_prod['option_name']) ? " - ".$deal_prod['option_name'] : ""); ?>" size="32"/>
									<input type="hidden" name="deal_food[required][]" value="0"/>
									<span>x</span><input type="number" name="deal_food[quantity][]" value="<?php echo $deal_prod['quantity']; ?>" min="1" max="9999" class="vrtkdealfoodquantity"/>
									<input type="hidden" name="deal_food[id_prod_option][]" value="<?php echo $deal_prod['id_product'].":".$deal_prod['id_option']; ?>" class="vrtkdealfoodid"/>
									<input type="hidden" name="deal_food[id][]" value="<?php echo $deal_prod['id']; ?>" class="vrtkrealid"/>
									<a href="javascript: void(0);" class="vrtk-dealfood-trash" onClick="removeSelectedDealFood(<?php echo $deal_prod['id']; ?>);"></a>
								</div>
							<?php 
								$deal_food_count = max(array($deal_food_count, $deal_prod['id']));
							} ?>
						</div>
					<?php echo $vik->closeControl(); ?>
					
				<?php } else if( $sel['type'] == 3 ) { ?>
					
					<!-- AUTO INSERT - Radio Button -->
					<?php
					$elem_yes = $vik->initRadioElement('', $elem_yes->label, $sel['auto_insert']);
					$elem_no = $vik->initRadioElement('', $elem_no->label, !$sel['auto_insert']);
					?>
					<?php echo $vik->openControl(JText::_('VRMANAGETKDEAL12').':'); ?>
						<?php echo $vik->radioYesNo('auto_insert', $elem_yes, $elem_no, false); ?>
					<?php echo $vik->closeControl(); ?>
					
					<!-- MIN QUANTITY - Number -->
					<?php echo $vik->openControl(JText::_('VRMANAGETKDEAL17').':'); ?>
						<input type="number" name="min_quantity" value="<?php echo $sel['min_quantity']; ?>" min="0"/>
					<?php echo $vik->closeControl(); ?>
					
					<!-- TARGET FOOD - Form -->
					<?php echo $vik->openControl(JText::_('VRMANAGETKDEAL14').':'); ?>
						<?php echo $all_prod_select; ?>
						<div id="vrtk-reqfood-container" class="vrtk-food-container">
							<?php foreach( $this->dealProducts as $deal_prod ) { ?>
								<div class="vrtk-dealfood-row" id="vrtkdealfood<?php echo $deal_prod['id']; ?>">
									<input type="text" readonly value="<?php echo $deal_prod['product_name'].(!empty($deal_prod['option_name']) ? " - ".$deal_prod['option_name'] : ""); ?>" size="32"/>
									<select name="deal_food[required][]" class="vik-dropdown medium">
										<option value="1" <?php echo ($deal_prod['required'] == 1 ? 'selected="selected"' : ''); ?>><?php echo JText::_('VRTKDEALTARGETOPT1'); ?></option>
										<option value="0" <?php echo ($deal_prod['required'] == 0 ? 'selected="selected"' : ''); ?>><?php echo JText::_('VRTKDEALTARGETOPT2'); ?></option>
									</select>
									<span>x</span><input type="number" name="deal_food[quantity][]" value="<?php echo $deal_prod['quantity']; ?>" min="1" max="9999" class="vrtkdealfoodquantity"/>
									<input type="hidden" name="deal_food[id_prod_option][]" value="<?php echo $deal_prod['id_product'].":".$deal_prod['id_option']; ?>" class="vrtkdealfoodid"/>
									<input type="hidden" name="deal_food[id][]" value="<?php echo $deal_prod['id']; ?>" class="vrtkrealid"/>
									<a href="javascript: void(0);" class="vrtk-dealfood-trash" onClick="removeSelectedDealFood(<?php echo $deal_prod['id']; ?>);"></a>
								</div>
							<?php 
								$deal_food_count = max(array($deal_food_count, $deal_prod['id']));
							} ?>
						</div>
					<?php echo $vik->closeControl(); ?>
					
					<!-- GIFT FOOD - Form -->
					<?php echo $vik->openControl(JText::_('VRMANAGETKDEAL15').':'); ?>
						<?php echo $all_gift_select; ?>
						<div id="vrtk-giftfood-container" class="vrtk-food-container">
							<?php foreach( $this->freeProducts as $free_prod ) { ?>
								<div class="vrtk-dealfood-row" id="vrtkfreefood<?php echo $free_prod['id']; ?>">
									<input type="text" readonly value="<?php echo $free_prod['product_name'].(!empty($free_prod['option_name']) ? " - ".$free_prod['option_name'] : ""); ?>" size="32"/>
									<span>x</span><input type="number" name="free_food[quantity][]" value="<?php echo $free_prod['quantity']; ?>" min="1" max="9999" class="vrtkdealfoodquantity"/>
									<input type="hidden" name="free_food[id_prod_option][]" value="<?php echo $free_prod['id_product'].":".$free_prod['id_option']; ?>" class="vrtkdealfoodid"/>
									<input type="hidden" name="free_food[id][]" value="<?php echo $free_prod['id']; ?>" class="vrtkrealid"/>
									<a href="javascript: void(0);" class="vrtk-dealfood-trash" onClick="removeSelectedFreeFood(<?php echo $free_prod['id']; ?>);"></a>
								</div>
							<?php 
								$free_food_count = max(array($free_food_count, $free_prod['id']));
							} ?>
						</div>
					<?php echo $vik->closeControl(); ?>
					
				<?php } else if( $sel['type'] == 4 ) { ?>
					
					<!-- TOTAL COST - Number -->
					<?php echo $vik->openControl(JText::_('VRMANAGETKDEAL16').':'); ?>
						<input type="number" name="amount" value="<?php echo $sel['amount']; ?>" min="0" step="any"/>
						&nbsp;<?php echo $curr_symb; ?>
					<?php echo $vik->closeControl(); ?>
					
					<!-- AUTO INSERT - Radio Button -->
					<?php
					$elem_yes = $vik->initRadioElement('', $elem_yes->label, $sel['auto_insert']);
					$elem_no = $vik->initRadioElement('', $elem_no->label, !$sel['auto_insert']);
					?>
					<?php echo $vik->openControl(JText::_('VRMANAGETKDEAL12').':'); ?>
						<?php echo $vik->radioYesNo('auto_insert', $elem_yes, $elem_no, false); ?>
					<?php echo $vik->closeControl(); ?>
					
					<!-- GIFT FOOD - Form -->
					<?php echo $vik->openControl(JText::_('VRMANAGETKDEAL15').':'); ?>
						<?php echo $all_gift_select; ?>
						<div id="vrtk-giftfood-container" class="vrtk-food-container">
							<?php foreach( $this->freeProducts as $free_prod ) { ?>
								<div class="vrtk-dealfood-row" id="vrtkfreefood<?php echo $free_prod['id']; ?>">
									<input type="text" readonly value="<?php echo $free_prod['product_name'].(!empty($free_prod['option_name']) ? " - ".$free_prod['option_name'] : ""); ?>" size="32"/>
									<span>x</span><input type="number" name="free_food[quantity][]" value="<?php echo $free_prod['quantity']; ?>" min="1" max="9999" class="vrtkdealfoodquantity"/>
									<input type="hidden" name="free_food[id_prod_option][]" value="<?php echo $free_prod['id_product'].":".$free_prod['id_option']; ?>" class="vrtkdealfoodid"/>
									<input type="hidden" name="free_food[id][]" value="<?php echo $free_prod['id']; ?>" class="vrtkrealid"/>
									<a href="javascript: void(0);" class="vrtk-dealfood-trash" onClick="removeSelectedFreeFood(<?php echo $free_prod['id']; ?>);"></a>
								</div>
							<?php 
								$free_food_count = max(array($free_food_count, $free_prod['id']));
							} ?>
						</div>
					<?php echo $vik->closeControl(); ?>
					
				<?php } else if( $sel['type'] == 6 ) { ?>

					<!-- AMOUNT / PERCENT OR TOTAL - Number -->
					<?php
					$elements = array(
						$vik->initOptionElement(1, '%', $sel['percentot'] == 1),
						$vik->initOptionElement(2, $curr_symb, $sel['percentot'] == 2),
					);
					?>
					<?php echo $vik->openControl(JText::_('VRMANAGETKDEAL10').':'); ?>
						<input type="number" name="amount" value="<?php echo $sel['amount']; ?>" min="0" step="any"/>
						<?php echo $vik->dropdown('percentot', $elements, '', 'short'); ?>
					<?php echo $vik->closeControl(); ?>

					<!-- TOTAL COST - Number -->
					<?php echo $vik->openControl(JText::_('VRMANAGETKDEAL16').':'); ?>
						<input type="number" name="cart_tcost" value="<?php echo $sel['cart_tcost']; ?>" min="0" step="any"/>
						&nbsp;<?php echo $curr_symb; ?>
					<?php echo $vik->closeControl(); ?>

				<?php } ?>
			
			<?php echo $vik->closeFieldset(); ?>
		</div>
	<?php } ?>
	
	<input type="hidden" name="id" value="<?php echo $id; ?>"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="option" value="com_cleverdine"/>
	
	<input type="hidden" name="cid[]" value="<?php echo $id; ?>"/>
	<input type="hidden" name="submitted" value="1"/>
</form>

<script>

	jQuery(document).ready(function(){

		jQuery('.vik-dropdown.short').select2({
			minimumResultsForSearch: -1,
			allowClear: false,
			width: 100
		});

		jQuery('#vrtk-quantity-select, .vik-dropdown.medium').select2({
			minimumResultsForSearch: -1,
			allowClear: false,
			width: 150
		});

		jQuery('#vrtk-type-select').select2({
			minimumResultsForSearch: -1,
			allowClear: false,
			placeholder: '<?php echo addslashes(JText::_('VRTKDEALTYPE0')); ?>',
			width: 300
		});

		jQuery('#vrtk-quantity-select').on('change', function(){
			if( jQuery(this).val() == 1 ) {
				jQuery('input[name="max_quantity"]').hide();
				jQuery('input[name="max_quantity"]').val('-1');
			} else {
				jQuery('input[name="max_quantity"]').val('1');
				jQuery('input[name="max_quantity"]').show();
			}
		});
		
		jQuery('#vrtk-type-select').on('change', function(){
			jQuery('input[name="task"]').val('<?php echo ($id > 0 ? 'edittkdeal' : 'newtkdeal'); ?>');
			jQuery('#adminForm').submit();
		});
		
		jQuery('#vrtk-daysfilter-select').select2({
			placeholder: '<?php echo addslashes(JText::_('VRTKDEALWEEKDAYSALL')); ?>',
			allowClear: true,
			width: 350,
		});
		
		jQuery('#vrtk-allprod-select, #vrtk-allgift-select').select2({
			placeholder: '<?php echo addslashes(JText::_('VRTKCARTOPTION4')); ?>',
			allowClear: true,
			width: 350,
		});
		
		<?php if( $sel['type'] == 1 || $sel['type'] == 2 || $sel['type'] == 3 ) { ?>
			jQuery('#vrtk-allprod-select').on('change', function(){
				pushSelectedDealFood();
			});
		<?php } ?>
		
		<?php if( $sel['type'] == 3 || $sel['type'] == 4 ) { ?>
			jQuery('#vrtk-allgift-select').on('change', function(){
				pushSelectedFreeFood();
			});
		<?php } ?>
		
		<?php if( $sel['type'] > 0 && $sel['type'] <= 4 && !empty($sel['animate']) ) { ?>
			jQuery('html,body').animate( {scrollTop: (jQuery('#vrtk-dealparams').offset().top-5)}, {duration:'slow'} );
		<?php } ?>
	});
	
	<?php if( $sel['type'] == 1 || $sel['type'] == 2 || $sel['type'] == 3 ) { ?>
		
		var DEAL_FOOD_COUNT = <?php echo ($deal_food_count+1); ?>;
	
		function pushSelectedDealFood() {
			var id = jQuery('#vrtk-allprod-select').val();
			if( id.length == 0 ) {
				return;
			}
			
			var text = jQuery('#vrtk-allprod-select :selected').text();
			
			var elem_found = null;
			jQuery('#vrtk-reqfood-container .vrtkdealfoodid').each(function(){
				if( jQuery(this).val() == id ) {
					elem_found = this;
					return false;
				}
			});
			
			if( elem_found === null ) {
				jQuery('#vrtk-reqfood-container').append(
					'<div class="vrtk-dealfood-row" id="vrtkdealfood'+DEAL_FOOD_COUNT+'">\n'+
						'<input type="text" readonly value="'+text+'" size="32"/>\n'+
						<?php if( $sel['type'] == 1 || $sel['type'] == 3 ) { ?>
							'<select name="deal_food[required][]" class="vik-dropdown medium">\n'+
								'<option value="1"><?php echo addslashes(JText::_('VRTKDEALTARGETOPT1')); ?></option>\n'+
								'<option value="2"><?php echo addslashes(JText::_('VRTKDEALTARGETOPT2')); ?></option>\n'+
							'</select>\n'+
						<?php } else if( $sel['type'] == 2 ) { ?>
							'<input type="hidden" name="deal_food[required][]" value="0"/>\n'+
						<?php } ?>
						'<span>x</span><input type="number" name="deal_food[quantity][]" value="1" min="1" max="9999" class="vrtkdealfoodquantity"/>\n'+
						'<input type="hidden" name="deal_food[id_prod_option][]" value="'+id+'" class="vrtkdealfoodid"/>\n'+
						'<input type="hidden" name="deal_food[id][]" value="-1" class="vrtkrealid"/>\n'+
						'<a href="javascript: void(0);" class="vrtk-dealfood-trash" onClick="removeSelectedDealFood('+DEAL_FOOD_COUNT+');"></a>\n'+
					'</div>\n'
				);

				jQuery('#vrtkdealfood'+DEAL_FOOD_COUNT+' .vik-dropdown.medium').select2({
					minimumResultsForSearch: -1,
					allowClear: false,
					width: 150
				});
				
				DEAL_FOOD_COUNT++;
			} else {
				var q_elem = jQuery(elem_found).parent().find('.vrtkdealfoodquantity');
				q_elem.val(parseInt(q_elem.val())+1);
			}
			
		}
	
		function removeSelectedDealFood(id) {
			var real_id = jQuery('#vrtkdealfood'+id+" .vrtkrealid").first().val();
			if( real_id != -1 ) {
				jQuery('#adminForm').append('<input type="hidden" name="remove_deal_food[]" value="'+real_id+'"/>');
			}
			jQuery('#vrtkdealfood'+id).remove();
		}
	
	<?php } ?>
	
	<?php if( $sel['type'] == 3 || $sel['type'] == 4 ) { ?>
		
		var FREE_FOOD_COUNT = <?php echo ($free_food_count+1); ?>;
	
		function pushSelectedFreeFood() {
			var id = jQuery('#vrtk-allgift-select').val();
			if( id.length == 0 ) {
				return;
			}
			
			var text = jQuery('#vrtk-allgift-select :selected').text();
			
			var elem_found = null;
			jQuery('#vrtk-giftfood-container .vrtkdealfoodid').each(function(){
				if( jQuery(this).val() == id ) {
					elem_found = this;
					return false;
				}
			});
			
			if( elem_found === null ) {
				jQuery('#vrtk-giftfood-container').append(
					'<div class="vrtk-dealfood-row" id="vrtkfreefood'+FREE_FOOD_COUNT+'">\n'+
						'<input type="text" readonly value="'+text+'" size="32"/>\n'+
						'<span>x</span><input type="number" name="free_food[quantity][]" value="1" min="1" max="9999" class="vrtkdealfoodquantity"/>\n'+
						'<input type="hidden" name="free_food[id_prod_option][]" value="'+id+'" class="vrtkdealfoodid"/>\n'+
						'<input type="hidden" name="free_food[id][]" value="-1" class="vrtkrealid"/>\n'+
						'<a href="javascript: void(0);" class="vrtk-dealfood-trash" onClick="removeSelectedFreeFood('+FREE_FOOD_COUNT+');"></a>\n'+
					'</div>\n'
				);
				
				FREE_FOOD_COUNT++;
			} else {
				var q_elem = jQuery(elem_found).parent().find('.vrtkdealfoodquantity');
				q_elem.val(parseInt(q_elem.val())+1);
			}
			
		}
	
		function removeSelectedFreeFood(id) {
			var real_id = jQuery('#vrtkfreefood'+id+" .vrtkrealid").first().val();
			if( real_id != -1 ) {
				jQuery('#adminForm').append('<input type="hidden" name="remove_free_food[]" value="'+real_id+'"/>');
			}
			jQuery('#vrtkfreefood'+id).remove();
		}
	
	<?php } ?>

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