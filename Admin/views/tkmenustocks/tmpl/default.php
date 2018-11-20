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

?>

<form action="index.php?option=com_cleverdine" method="post" name="adminForm" id="adminForm">

	<?php if( count($this->menus) > 0 ) { ?>

		<div class="btn-toolbar vr-btn-toolbar" id="filter-bar">
			<div class="btn-group pull-left">
				<input type="text" name="keysearch" id="vrkeysearch" class="vrkeysearch" size="32" value="<?php echo $this->filters['keysearch']; ?>" placeholder="<?php echo JText::_('VRRESERVATIONKEYSEARCH'); ?>"/>
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
				<div class="vr-toolbar-setfont">
					<select name="id_menu" onChange="document.adminForm.submit();" id="vrtk-menu-sel">
						<?php foreach( $this->menus as $m ) { ?>
							<option value="<?php echo $m['id']; ?>" <?php echo ($m['id'] == $this->filters['id_menu'] ? 'selected="selected"' : ''); ?>><?php echo $m['title']; ?></option>
						<?php } ?>
					</select>
				</div>
			</div>
		</div>

	<?php } ?>

<?php 
	if( count( $this->productsList ) == 0 ) {
		?>
		<p><?php echo JText::_('VRNOTKPRODUCT');?></p>
		<?php
	} else {
?>
	
	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="<?php echo $vik->getAdminTableClass(); ?>">
		<?php echo $vik->openTableHead(); ?>
			<tr>
				<th class="<?php echo $vik->getAdminThClass('left'); ?>" width="200" style="text-align: left;"><?php echo JText::_('VRMANAGETKSTOCK1'); ?></th>
				<th class="<?php echo $vik->getAdminThClass('left'); ?>" width="150" style="text-align: left;"><?php echo JText::_('VRMANAGETKSTOCK2'); ?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="100" style="text-align: center;"><?php echo JText::_('VRMANAGETKSTOCK3'); ?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="100" style="text-align: center;"><?php echo JText::_('VRMANAGETKSTOCK4');?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="100" style="text-align: center;"><?php echo JText::_('VRMANAGETKSTOCK5');?></th>
			</tr>
		<?php echo $vik->closeTableHead(); ?>
		<?php
		$kk = 0;
		$i = 0;
		foreach( $this->productsList as $product ) { ?>

			<tr class="row<?php echo $kk; ?>">
				<td><?php echo $product['name']; ?></td>
				<td>&nbsp;</td>
				<td style="text-align: center;">
					<input type="hidden" name="prod_ids[]" value="<?php echo $product['id']; ?>"/>
					<input type="number" name="prod_items_in_stock[]" value="<?php echo $product['items_in_stock']; ?>" id="vreproditstock<?php echo $product['id']; ?>" min="0" max="999999" step="1"/>
				</td>
				<td style="text-align: center;">
					<input type="number" name="prod_notify_below[]" value="<?php echo $product['notify_below']; ?>" id="vreprodnotify<?php echo $product['id']; ?>" min="0" max="999999" step="1"/>
				</td>
				<td style="text-align: center;">
					<?php if( count($product['options']) ) { ?>
						<button type="button" class="btn" onClick="updateProductChildren(<?php echo $product['id']; ?>);"><?php echo JText::_('VRMANAGETKSTOCK6'); ?></button>
					<?php } else { ?>
						&nbsp;
					<?php } ?>
				</td>
			</tr>

			<?php $kk = 1 - $kk; ?>

			<?php foreach( $product['options'] as $option ) { ?>
				<tr class="row<?php echo $kk; ?> vre-prod-child<?php echo $product['id']; ?>">
					<td>&nbsp;</td>
					<td><?php echo $option['name']; ?></td>
					<td style="text-align: center;">
						<input type="hidden" name="option_ids[]" value="<?php echo $option['id']; ?>"/>
						<input type="number" name="option_items_in_stock[]" value="<?php echo $option['items_in_stock']; ?>" style="margin-left: 80px;"
							class="vre-item-stock" min="0" max="999999" step="1"/>
					</td>
					<td style="text-align: center;">
						<input type="number" name="option_notify_below[]" value="<?php echo $option['notify_below']; ?>"  style="margin-left: 80px;"
							class="vre-item-notify" min="0" max="999999" step="1"/>
					</td>
					<td style="text-align: center;">
						&nbsp;
					</td>
				</tr>

				<?php $kk = 1 - $kk; ?>

			<?php } ?>
			
		<?php }	?>
	</table>
	<?php } ?>
	<input type="hidden" name="task" value="tkmenustocks"/>
</form>

<script>

	jQuery(document).ready(function(){
		jQuery('#vrtk-menu-sel').select2({
			allowClear: false,
			width: 300
		});
	});

	function updateProductChildren(id_prod) {
		jQuery('.vre-prod-child'+id_prod).each(function(){
			jQuery(this).find('.vre-item-stock').val(parseInt(jQuery('#vreproditstock'+id_prod).val()));
			jQuery(this).find('.vre-item-notify').val(parseInt(jQuery('#vreprodnotify'+id_prod).val()));
		});
	}

	var _LAST_SEARCH_ = '<?php echo addslashes($this->filters['keysearch']); ?>';
	
	function clearFilter() {
		jQuery('#vrkeysearch').val('');
		if( _LAST_SEARCH_.length > 0 ) {
			document.adminForm.submit();
		}
	}

</script>
