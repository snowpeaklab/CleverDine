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

$vik = new VikApplication(VersionListener::getID());

$date_format = cleverdine::getDateFormat(true);

$filters = $this->filters;

$ordering = $this->ordering;

$COLUMNS_TO_ORDER = array('concat_name', 'remaining');
foreach( $COLUMNS_TO_ORDER as $c ) {
	if( empty($ordering[$c]) ) {
		$ordering[$c] = 0;
	}
}

// ORDERING LINKS

$links = array(
	OrderingManager::getLinkColumnOrder( 'tkstocks', JText::_('VRMANAGETKSTOCK1'), 'concat_name', $ordering['concat_name'], 1, $filters, 'vrheadcolactive'.(($ordering['concat_name'] == 2) ? 1 : 2) ),
	OrderingManager::getLinkColumnOrder( 'tkstocks', JText::_('VRMANAGETKSTOCK7'), 'remaining', $ordering['remaining'], 1, $filters, 'vrheadcolactive'.(($ordering['remaining'] == 2) ? 1 : 2) ),
);

?>

<form action="index.php?option=com_cleverdine" method="post" name="adminForm" id="adminForm">

	<?php if( count($this->menus) > 0 ) { ?>

		<div class="btn-toolbar vr-btn-toolbar" id="filter-bar">

			<div class="btn-group pull-left">
				<input type="text" name="keysearch" id="vrkeysearch" class="vrkeysearch" size="32" value="<?php echo $filters['keysearch']; ?>" placeholder="<?php echo JText::_('VRRESERVATIONKEYSEARCH'); ?>"/>
			</div>
			
			<div class="btn-group pull-left hidden-phone">
				<button type="submit" class="btn hasTooltip" title="" data-original-title="<?php echo JText::_('VRRESERVATIONBUTTONFILTER'); ?>">
					<i class="icon-search"></i>
				</button>
				<button type="button" class="btn hasTooltip" title="" data-original-title="<?php echo JText::_('VRCLEARFILTER'); ?>" onClick="clearFilter();">
					<i class="icon-remove"></i>
				</button>
			</div>

			<div class="btn-group pull-right">
				<button type="button" class="btn" onclick="refillAll();"><?php echo JText::_('VRREFILLALL'); ?></button>
			</div>

			<div class="btn-group pull-right">
				<div class="vr-toolbar-setfont">
					<select name="id_menu" onChange="document.adminForm.submit();" id="vr-menu-select">
						<option value=""></option>
						<?php foreach( $this->menus as $m ) { ?>
							<option value="<?php echo $m['id']; ?>" <?php echo ($m['id'] == $filters['id_menu'] ? 'selected="selected"' : ''); ?>><?php echo $m['title']; ?></option>
						<?php } ?>
					</select>
				</div>
			</div>

		</div>

	<?php } ?>

<?php 
	if( count( $this->rows ) == 0 ) {
		?>
		<p><?php echo JText::_('VRNOTKPRODUCT');?></p>
		<?php
	} else {
?>
	
	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="<?php echo $vik->getAdminTableClass(); ?>">
		<?php echo $vik->openTableHead(); ?>
			<tr>
				<th class="<?php echo $vik->getAdminThClass('left'); ?>" width="200" style="text-align: left;"><?php echo $links[0]; ?></th>
				<th class="<?php echo $vik->getAdminThClass('left'); ?>" width="150" style="text-align: left;">&nbsp;</th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="150" style="text-align: center;"><?php echo $links[1]; ?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="150" style="text-align: center;"><?php echo JText::_('VRMANAGETKSTOCK10'); ?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="75" style="text-align: center;">&nbsp;</th>
			</tr>
		<?php echo $vik->closeTableHead(); ?>
		<?php
		$kk = 0;
		$i = 0;
		foreach( $this->rows as $r ) { 

			$color = "";
			$title = "";
			if( $r['products_used'] > 0 ) {
				$color = "color: #009900;";
				if( $r['products_in_stock']-$r['products_used'] <= $r['product_notify_below'] ) {
					$color = "color: #990000;";
				}

				$title = JText::sprintf('VRSTOCKITEMUSED', $r['products_used'], $r['products_in_stock']);
			} else {
				$title = JText::_('VRSTOCKITEMNOUSED');
			}

			$available_stock = $r['products_in_stock']-$r['products_used'];

			$identifier = intval($r['eid']).'-'.intval($r['oid']);

			?>

			<tr class="row<?php echo $kk; ?>">
				<td><?php echo $r['ename']; ?></td>
				<td><?php echo (strlen($r['oname']) ? $r['oname'] : ' '); ?></td>
				<td style="text-align: center;<?php echo $color; ?>">
					<span title="" class="hasTooltip" data-original-title="<?php echo $title; ?>">
						<?php echo $available_stock; ?>
					</span>
				</td>
				<td style="text-align: center;">
					<input type="hidden" name="original_stock[]" value="<?php echo $r['product_original_stock']; ?>" />
					<input type="hidden" name="id_product[]" value="<?php echo $r['eid']; ?>" />
					<input type="hidden" name="id_option[]" value="<?php echo $r['oid']; ?>" />
					
					<select name="stock_factor[]" class="vr-stockfactor-sel" id="vr-stockfactor-sel<?php echo $identifier; ?>">
						<option value="1" selected="selected">+</option>
						<option value="-1">-</option>
					</select>
					<input type="number" name="stock_override[]" value="0" min="0" max="999999" step="1" id="vr-stock-override<?php echo $identifier; ?>"/>
				</td>
				<td style="text-align: center;">
					<?php if( $available_stock < $r['product_original_stock'] ) { ?>
						<button type="button" class="btn vr-refill-btn" onclick="refillStock('<?php echo $identifier; ?>', <?php echo $available_stock; ?>, <?php echo $r['product_original_stock']; ?>);">
							<?php echo JText::_('VRREFILL'); ?>
						</button>
					<?php } ?>
				</td>
			</tr>
			
			<?php $kk = ($kk+1)%2; ?>

		<?php } ?>
	</table>
	<?php } ?>

	<input type="hidden" name="task" value="tkstocks"/>
	<?php echo JHTML::_( 'form.token' ); ?>
	<?php echo $this->navbut; ?>
</form>

<script>

	jQuery(document).ready(function(){

		jQuery('#vr-menu-select').select2({
			placeholder: '<?php echo addslashes(JText::_('VRALLMENUSOPTION')); ?>',
			allowClear: true,
			width: 300
		});

		jQuery('.vr-stockfactor-sel').select2({
			minimumResultsForSearch: -1,
			allowClear: false,
			width: 50
		});

		jQuery('input[type="number"]').keypress(function(e){
			if( (e.keyCode || e.which) == 13 ) {
				jQuery('input[name="task"]').val('saveTkMenuStocksOverrides');
			}
		});

	});

	function refillStock(id, available_stock, default_stock) {
		var refill = parseInt(default_stock) - parseInt(available_stock);

		jQuery('#vr-stockfactor-sel'+id).select2('val', 1);
		jQuery('#vr-stock-override'+id).val(refill);

	}

	function refillAll() {
		jQuery('.vr-refill-btn').each(function(){
			jQuery(this).trigger('click');
		});
	}

	var _LAST_SEARCH_ = '<?php echo addslashes($filters['keysearch']); ?>';
	
	function clearFilter() {
		jQuery('#vrkeysearch').val('');
		if( _LAST_SEARCH_.length > 0 ) {
			document.adminForm.submit();
		}
	}

</script>
