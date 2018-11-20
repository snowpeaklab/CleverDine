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

$itemid = JFactory::getApplication()->input->getUint('Itemid');

$curr_symb 	= cleverdine::getCurrencySymb();
$symb_pos 	= cleverdine::getCurrencySymbPosition();

$_symb_arr = array( '', '' );
if( $symb_pos == 1 ) {
	$_symb_arr[1] = ' '.$curr_symb;
} else {
	$_symb_arr[0] = $curr_symb.' ';
}

// push menu for hidden products
$this->menus[] = array(
	'id' => 0,
	'name' => JText::_('VROTHER'),
	'image' => ''
);

?>

<div class="vrfront-manage-headerdiv">

	<div class="vrfront-manage-titlediv">
		<h2><?php echo JText::_('VREDITBILL'); ?></h2>
	</div>
	
	<div class="vrfront-manage-actionsdiv">
		
		<div class="vrfront-manage-btn">
			<button type="button" onClick="vrCloseBill();" id="vrfront-manage-btnclose" class="vrfront-manage-button"><?php echo JText::_('VRCLOSE'); ?></button>
		</div>

	</div>

</div>

<div class="vrfront-search-toolbar">
	<input type="hidden" id="vr-users-select" value="" style="width:100%;"/>
</div>

<div class="vrfront-editbill-menus">

	<form action="index.php" method="POST" id="editbillform">

		<div id="vrfront-menus-container">

			<?php foreach( $this->menus as $menu ) { 
				if( empty($menu['image']) ) {
					$menu['image'] = 'menu_default_icon.jpg';
				}
				?>

				<div class="vrfront-menu-block">

					<div class="menu-image">
						<a href="javascript: void(0);" onclick="openMenuSections(<?php echo $menu['id']; ?>)">
							<img src="<?php echo JUri::root().'/components/com_cleverdine/assets/media/'.$menu['image']; ?>"/>
						</a>
					</div>

					<div class="menu-title"><?php echo $menu['name']; ?></div>

				</div>

			<?php } ?>

			<a href="javascript: void(0);" onclick="openProductDetails(0);">
				<div class="vrfront-menu-block ghost">
					<i class="fa fa-plus"></i>
				</div>
			</a>

		</div>

		<div id="vr-sections-container">

		</div>

		<div id="vr-products-container">

		</div>

		<div id="vr-product-details">

		</div>

		<input type="hidden" name="id" value="<?php echo $this->order['id']; ?>" />

	</form>

</div>

<div class="vrfront-food-summary">

	<div class="vrfront-food-list" id="vr-food-container">

		<?php foreach( $this->order['food'] as $food ) { ?>

			<div class="food-details" id="food<?php echo $food['id']; ?>">

				<div class="food-details-left">
					<a href="javascript: void(0);" onclick="openProductDetails(<?php echo $food['id_product']; ?>, <?php echo $food['id']; ?>)">
						<?php echo $food['name']; ?>
					</a>
				</div>

				<div class="food-details-right">
					<span class="food-quantity">x<?php echo $food['quantity']; ?></span>
					<span class="food-price"><?php echo cleverdine::printPriceCurrencySymb($food['price']); ?></span>
					<span class="food-remove">
						<a href="javascript: void(0);" onclick="removeProduct(<?php echo $food['id']; ?>)">
							<i class="fa fa-times"></i>
						</a>
					</span>
				</div>

			</div>

		<?php } ?>

	</div>

	<div class="food-cost-total">
		<span class="food-total-label"><?php echo JText::_('VRTOTAL'); ?>:</span>
		<span class="food-total-value" id="vr-food-tcost"><?php echo cleverdine::printPriceCurrencySymb($this->order['bill_value']); ?></span>
	</div>

</div>

<script type="text/javascript">

	jQuery(document).ready(function(){

		jQuery('#vr-users-select').select2({
			placeholder: '<?php echo addslashes(JText::_('VRSEARCHPRODPLACEHOLDER')); ?>',
			allowClear: true,
			width: 'resolve',
			minimumInputLength: 2,
			ajax: {
				url: 'index.php?option=com_cleverdine&task=search_section_product&tmpl=component',
				dataType: 'json',
				type: "POST",
				quietMillis: 50,
				data: function(term) {
					return {
						term: term
					};
				},
				results: function(data) {
					return {
						results: jQuery.map(data, function (item) {
							return {
								text: item.name,
								id: item.id
							}
						})
					};
				},
			},
			formatSelection: function(data) {
				if( jQuery.isEmptyObject(data.name) ) {
					// display data retured from ajax parsing
					return data.text;
				}
				// display pre-selected value
				return data.name;
			}
			
		});

		jQuery('#vr-users-select').on('change', function(){
			var val = jQuery(this).val();

			if( val.length ) {
				openProductDetails(val);
			} else {
				closeProductDetails();
			}
		});

	});

	function vrCloseBill() {

		document.location.href = '<?php echo JRoute::_("index.php?option=com_cleverdine&task=editres&cid[]={$this->order['id']}&Itemid=$itemid"); ?>';

	}

	// SECTIONS

	function openMenuSections(id) {

		closeSections();

		if( id == 0 ) {
			// dispatch load products
			openSectionProducts(0);
			return;
		}

		if( jQuery('#vrsections'+id).length ) {

			jQuery('#vrsections'+id).show();

		} else {

			loadMenuSections(id, function(){
				openMenuSections(id);
			});

		}

	}

	function loadMenuSections(id, callback) {

		openLoadingOverlay(true);

		jQuery.noConflict();

		var jqxhr = jQuery.ajax({
			type: 'POST',
			url: '<?php echo JRoute::_('index.php?option=com_cleverdine&task=get_menu_sections&tmpl=component'); ?>',
			data: {
				id_menu: id
			}
		}).done(function(resp){
			var obj = jQuery.parseJSON(resp);

			var html = '<div id="vrsections'+id+'" class="vrfront-menu-sections">\n';

			jQuery.each(obj, function(i, section){

				if( !section.image.length ) {
					section.image = 'menu_default_icon.jpg';
				}

				html += '<div class="vrfront-section-block">\n';

				html += '<div class="section-image">\n';
				html += '<a href="javascript: void(0);" onclick="openSectionProducts('+section.id+');">';
				html += '<img src="<?php echo JUri::root(); ?>/components/com_cleverdine/assets/media/'+section.image+'"/>\n';
				html += '</a>\n';
				html += '</div>\n';

				html += '<div class="section-title">'+section.name+'</div>\n';

				html += '</div>\n';
			});

			html += '</div>\n';

			closeLoadingOverlay();

			jQuery('#vr-sections-container').append(html);
			callback();

		}).fail(function(resp){

			console.log(resp);
			closeLoadingOverlay();

		});

	}

	function closeSections() {
		// close sections
		jQuery('.vrfront-menu-sections').hide();

		// close products
		closeProducts();
	}

	// PRODUCTS

	function openSectionProducts(id) {

		closeProducts();

		if( jQuery('#vrproducts'+id).length ) {

			jQuery('#vrproducts'+id).show();

		} else {

			loadSectionProducts(id, function(){
				openSectionProducts(id);
			});

		}

	}

	function loadSectionProducts(id, callback) {

		openLoadingOverlay(true);

		jQuery.noConflict();

		var jqxhr = jQuery.ajax({
			type: 'POST',
			url: '<?php echo JRoute::_('index.php?option=com_cleverdine&task=get_section_products&tmpl=component'); ?>',
			data: {
				id_section: id
			}
		}).done(function(resp){
			var obj = jQuery.parseJSON(resp);

			var html = '<div id="vrproducts'+id+'" class="vrfront-section-products">\n';

			jQuery.each(obj, function(i, prod){

				if( !prod.image.length ) {
					prod.image = 'menu_default_icon.jpg';
				}

				html += '<div class="vrfront-product-block">\n';

				html += '<div class="product-image">\n';
				html += '<a href="javascript: void(0);" onclick="openProductDetails('+prod.id+');">';
				html += '<img src="<?php echo JUri::root(); ?>/components/com_cleverdine/assets/media/'+prod.image+'"/>\n';
				html += '</a>\n';
				html += '</div>\n';

				html += '<div class="product-title">'+prod.name+'</div>\n';

				html += '</div>\n';
			});

			html += '</div>\n';

			closeLoadingOverlay();

			jQuery('#vr-products-container').append(html);
			callback();

		}).fail(function(resp){

			console.log(resp);
			closeLoadingOverlay();

		});

	}

	function closeProducts() {
		// close products
		jQuery('.vrfront-section-products').hide();

		// close product details
		closeProductDetails();
	}

	// PROD DETAILS

	var PRODUCT_DETAILS = {};

	function openProductDetails(id, assoc) {
		
		if( id == 0 ) {
			// close sections because no menu is selected
			closeSections();
		}

		if( assoc === undefined ) {
			assoc = 0;
		}

		var hash = id+"."+assoc;

		if( PRODUCT_DETAILS.hasOwnProperty(hash) ) {
			// get from local pool
			jQuery('#vr-product-details').html(PRODUCT_DETAILS[hash]);
		} else {
			// get from controller via AJAX

			openLoadingOverlay(true);

			jQuery.noConflict();

			var jqxhr = jQuery.ajax({
				type: 'POST',
				url: '<?php echo JRoute::_('index.php?option=com_cleverdine&task=get_product_html&tmpl=component'); ?>',
				data: {
					id_product: id,
					id_assoc: assoc
				}
			}).done(function(resp){
				var obj = jQuery.parseJSON(resp);

				if( obj.status ) {
					jQuery('#vr-product-details').html(obj.html);

					PRODUCT_DETAILS[hash] = obj.html;
				}

				closeLoadingOverlay();

			}).fail(function(resp){

				console.log(resp);
				closeLoadingOverlay();

			});
		}
	}

	function closeProductDetails() {
		// close product details
		jQuery('#vr-product-details').html('');
	}

	function vrPostItem(exists) {
		openLoadingOverlay(true);
		
		jQuery.noConflict();
		
		var jqxhr = jQuery.ajax({
			type: "POST",
			url: "index.php?option=com_cleverdine&task=add_item_to_res&tmpl=component",
			data: jQuery('#editbillform').serialize()
		}).done(function(resp){
			var obj = jQuery.parseJSON(resp);

			if( obj.status ) {
				
				var html = '<div class="food-details" id="food'+obj.id+'">\n'+
					'<div class="food-details-left">\n'+
						'<a href="javascript: void(0);" onclick="openProductDetails('+obj.object.item_id+', '+obj.id+')">'+obj.object.item_name+'</a>\n'+
					'</div>\n'+
					'<div class="food-details-right">\n'+
						'<span class="food-quantity">x'+obj.object.quantity+'</span>\n'+
						'<span class="food-price"><?php echo $_symb_arr[0]; ?>'+obj.object.price.toFixed(2)+'<?php echo $_symb_arr[1]; ?></span>\n'+
						'<span class="food-remove">\n'+
							'<a href="javascript: void(0);" onclick="removeProduct('+obj.id+')">\n'+
								'<i class="fa fa-times"></i>\n'+
							'</a>\n'+
						'</span>\n'+
					'</div>\n'+
				'</div>\n';

				if( jQuery('#food'+obj.id).length ) {
					// replace existing item
					jQuery('#food'+obj.id).replaceWith(html);

					// clean storage to be always updated
					var hash = obj.object.item_id+'.'+obj.id;
					delete PRODUCT_DETAILS[hash];

				} else {
					// otherwise append it
					jQuery('#vr-food-container').append(html);
				}

				// update total cost
				updateTotalCost(obj.grand_total);

				if( !exists ) {
					// remove "other" section to re-load items correctly
					jQuery('#vrproducts0').remove();
				}

			} else {
				alert(obj.error);
			}

			closeLoadingOverlay();

		}).fail(function(resp){
			
			console.log(resp)
			closeLoadingOverlay();

		});
	}

	function removeProduct(id) {
		
		jQuery.noConflict();
		
		var jqxhr = jQuery.ajax({
			type: "POST",
			url: "index.php?option=com_cleverdine&task=remove_item_from_res&tmpl=component",
			data: {
				id: id
			}
		}).done(function(resp){
			var obj = jQuery.parseJSON(resp);
			
			jQuery('#food'+id).remove();

			updateTotalCost(obj.grand_total);

		}).fail(function(resp){

			console.log(resp)

		});

	}

	function updateTotalCost(grand_total) {
		jQuery('#vr-food-tcost').html('<?php echo $_symb_arr[0]; ?>'+grand_total.toFixed(2)+'<?php echo $_symb_arr[1]; ?>');
	}

</script>