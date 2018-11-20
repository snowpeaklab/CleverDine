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

$rows = $this->rows;
$lim0 = $this->lim0;
$navbut = $this->navbut;

$filters = $this->filters;

$ordering = $this->ordering;

$date_format 	= cleverdine::getDateFormat(true);
$curr_symb 		= cleverdine::getCurrencySymb(true);
$symb_pos 		= cleverdine::getCurrencySymbPosition(true);

$vik = new VikApplication(VersionListener::getID());

// ORDERING LINKS

$COLUMNS_TO_ORDER = array('id', 'code', 'group');
foreach( $COLUMNS_TO_ORDER as $c ) {
	if( empty($ordering[$c]) ) {
		$ordering[$c] = 0;
	}
}

$links = array(
	OrderingManager::getLinkColumnOrder( 'coupons', JText::_('VRMANAGEMENUSPRODUCT1'), 'id', $ordering['id'], 1, $filters, 'vrheadcolactive'.(($ordering['id'] == 2) ? 1 : 2) ),
	OrderingManager::getLinkColumnOrder( 'coupons', JText::_('VRMANAGECOUPON1'), 'code', $ordering['code'], 1, $filters, 'vrheadcolactive'.(($ordering['code'] == 2) ? 1 : 2) ),
	OrderingManager::getLinkColumnOrder( 'coupons', JText::_('VRMANAGECOUPON10'), 'group', $ordering['group'], 1, $filters, 'vrheadcolactive'.(($ordering['group'] == 2) ? 1 : 2) )
);

$min_value_head = strlen($filters['group']) ? ($filters['group'] == 0 ? '8' : '9') : '7';

?>

<form action="index.php?option=com_cleverdine" method="post" name="adminForm" id="adminForm">

	<div class="btn-toolbar vr-btn-toolbar" id="filter-bar">
		<div class="btn-group pull-left">
			<input type="text" name="keysearch" id="vrkeysearch" class="vrkeysearch" size="32" value="<?php echo $filters['keysearch']; ?>" placeholder="<?php echo JText::_('VRMENUPRODKEYSEARCH'); ?>"/>
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
				<?php echo RestaurantsHelper::buildGroupDropdown('group', $filters['group'], 'vr-group-sel', null, '', true); ?>
			</diV>
		</div>
	</div>

<?php if( count( $rows ) == 0 ) { ?>
	<p><?php echo JText::_('VRNOCOUPON'); ?></p>
<?php } else { ?>

	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="<?php echo $vik->getAdminTableClass(); ?>">
		<?php echo $vik->openTableHead(); ?>
			<tr>
				<th width="20">
					<?php echo $vik->getAdminToggle(count($rows)); ?>
				</th>
				<th class="<?php echo $vik->getAdminThClass('left'); ?>" width="50" style="text-align: left;"><?php echo $links[0]; ?></th>
				<th class="<?php echo $vik->getAdminThClass('left'); ?>" width="150" style="text-align: left;"><?php echo $links[1]; ?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="100" style="text-align: center;"><?php echo JText::_('VRMANAGECOUPON2'); ?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="100" style="text-align: center;"><?php echo JText::_('VRMANAGECOUPON4'); ?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="200" style="text-align: center;"><?php echo JText::_('VRMANAGECOUPON11'); ?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="100" style="text-align: center;"><?php echo JText::_('VRMANAGECOUPON'.$min_value_head); ?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="100" style="text-align: center;"><?php echo $links[2]; ?></th>
			</tr>
		<?php echo $vik->closeTableHead(); ?>
		<?php
		$kk = 0;
		for( $i = 0; $i < count($rows); $i++ ) {
			$row = $rows[$i];
			
			$ds_de = false;
			if( !empty($row['datevalid']) ) {
				$ds_de = explode("-", $row['datevalid']);
			}
			
			?>
			<tr class="row<?php echo $kk; ?>">
				<td><input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row['id']; ?>" onClick="<?php echo $vik->checkboxOnClick(); ?>"></td>
				<td><?php echo $row['id']; ?></td>

				<td><a href="index.php?option=com_cleverdine&amp;task=editcoupon&amp;cid[]=<?php echo $row['id']; ?>"><?php echo $row['code']; ?></a></td>

				<td style="text-align: center;">
					<?php echo JText::_('VRCOUPONTYPEOPTION'.$row['type']); ?>
				</td>

				<td style="text-align: center;">
					<?php echo ($row['percentot'] == 1 ? $row['value'].' '.JText::_("VRCOUPONPERCENTOTOPTION1") : cleverdine::printPriceCurrencySymb($row['value'], $curr_symb, $symb_pos, true) ); ?>
				</td>

				<td style="text-align: center;">
					<?php echo ($ds_de !== false ? date($date_format, $ds_de[0]).' - '.date($date_format, $ds_de[1]) : ''); ?>
				</td>

				<td style="text-align: center;">
					<?php echo ($row['group'] == 0 ? intval($row['minvalue']).(strlen($filters['group']) ? '' : ' '.strtolower(JText::_('VRORDERPEOPLE'))) : cleverdine::printPriceCurrencySymb($row['minvalue'], $curr_symb, $symb_pos, true)); ?>
				</td>

				<td style="text-align: center;">
					<?php echo JText::_($row['group'] == 0 ? 'VRMANAGECONFIGTITLE1' : 'VRMANAGECONFIGTITLE2'); ?>
				</td>
			</tr>
			<?php
			$kk = 1 - $kk;
		}		
		?>
	</table>
	<?php } ?>
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="task" value="coupons"/>
	<?php echo JHTML::_( 'form.token' ); ?>
	<?php echo $navbut; ?>
</form>

<script type="text/javascript">

	jQuery(document).ready(function(){
		jQuery('#vr-group-sel').on('change', function(){
			document.adminForm.submit();
		});
	});

	var _LAST_SEARCH_ = '<?php echo addslashes($filters['keysearch']); ?>';
	
	function clearFilter() {
		jQuery('#vrkeysearch').val('');
		if( _LAST_SEARCH_.length > 0 ) {
			document.adminForm.submit();
		}
	}

</script>
