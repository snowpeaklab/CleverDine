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

$curr_symb = cleverdine::getCurrencySymb(true);
$symb_pos = cleverdine::getCurrencySymbPosition(true);

$vik = new VikApplication(VersionListener::getID());

$core_edit = (JFactory::getUser()->authorise('core.edit', 'com_cleverdine'));

$multi_lang = cleverdine::isMultilanguage(true);

// ORDERING LINKS

$COLUMNS_TO_ORDER = array('id', 'name', 'price', 'ordering');
foreach( $COLUMNS_TO_ORDER as $c ) {
	if( empty($ordering[$c]) ) {
		$ordering[$c] = 0;
	}
}

$links = array(
	OrderingManager::getLinkColumnOrder( 'tkproducts', JText::_('VRMANAGEMENUSPRODUCT1'), 'id', $ordering['id'], 1, $filters, 'vrheadcolactive'.(($ordering['id'] == 2) ? 1 : 2) ),
	OrderingManager::getLinkColumnOrder( 'tkproducts', JText::_('VRMANAGETKPRODUCT1'), 'name', $ordering['name'], 1, $filters, 'vrheadcolactive'.(($ordering['name'] == 2) ? 1 : 2) ),
	OrderingManager::getLinkColumnOrder( 'tkproducts', JText::_('VRMANAGETKPRODUCT2'), 'price', $ordering['price'], 1, $filters, 'vrheadcolactive'.(($ordering['price'] == 2) ? 1 : 2) ),
	OrderingManager::getLinkColumnOrder( 'tkproducts', JText::_('VRMANAGETKTOPPING4'), 'ordering', $ordering['ordering'], 1, $filters, 'vrheadcolactive'.(($ordering['ordering'] == 2) ? 1 : 2) ),
);

?>

<form action="index.php?option=com_cleverdine" method="post" name="adminForm" id="adminForm">

	<div class="btn-toolbar vr-btn-toolbar" id="filter-bar">
		<div class="btn-group pull-left">
			<input type="text" name="key" id="vrkeysearch" class="vrkeysearch" size="32" value="<?php echo $filters['key']; ?>" placeholder="<?php echo JText::_('VRMENUPRODKEYSEARCH'); ?>"/>
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
			<button type="button" class="btn" onClick="document.location.href='index.php?option=com_cleverdine&task=tkmenus';">
				<?php echo JText::_('VRGOTOMENUS'); ?>
			</button>
		</div>

		<div class="btn-group pull-right">
			<div class="vr-toolbar-setfont">
				<?php
				$elements = array();
				foreach( $this->allMenus as $m ) {
					array_push($elements, $vik->initOptionElement($m['id'], $m['title'], $m['id'] == $filters['id_menu']));
				}
				?>
				<?php echo $vik->dropdown('id_menu', $elements, 'vr-menu-sel'); ?>
			</diV>
		</div>
	</div>
	
<?php if( count( $rows ) == 0 ) { ?>
	<p><?php echo JText::_('VRNOTKPRODUCT');?></p>
<?php } else { ?>
	
	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="<?php echo $vik->getAdminTableClass(); ?>">
		<?php echo $vik->openTableHead(); ?>
			<tr>
				<th width="20">
					<?php echo $vik->getAdminToggle(count($rows)); ?>
				</th>
				<th class="<?php echo $vik->getAdminThClass('left'); ?>" width="50" style="text-align: left;"><?php echo $links[0]; ?></th>
				<th class="<?php echo $vik->getAdminThClass('left'); ?>" width="300" style="text-align: left;"><?php echo $links[1]; ?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="150" style="text-align: center;"><?php echo $links[2]; ?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="150" style="text-align: center;"><?php echo JText::_('VRMANAGETKMENU12'); ?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="150" style="text-align: center;"><?php echo JText::_('VRMANAGETKMENU9'); ?></th>
				<?php if( $multi_lang ) { ?>
					<th class="<?php echo $vik->getAdminThClass(); ?>" width="100" style="text-align: center;"><?php echo JText::_('VRMANAGETKPRODUCT4');?></th>
				<?php } ?>
				<?php if( $core_edit ) { ?>
					<th class="<?php echo $vik->getAdminThClass(); ?>" width="150" style="text-align: center;">
						<?php echo $links[3]; ?>
						<a href="javascript: saveSort();" class="vrorderingsavelink">
							<i class="fa fa-floppy-o big"></i>
						</a>
					</th>
				<?php } ?>
			</tr>
		<?php echo $vik->closeTableHead(); ?>
		<?php
		$kk = 0;
		for( $i = 0; $i < count($rows); $i++ ) {
			$row = $rows[$i];
			?>
			<tr class="row<?php echo $kk; ?>">
				<td><input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row['id']; ?>" onClick="<?php echo $vik->checkboxOnClick(); ?>"></td>
				<td><?php echo $row['id']; ?></td>
				<td><a href="index.php?option=com_cleverdine&amp;task=edittkentry&amp;cid[]=<?php echo $row['id']; ?>&id_menu=<?php echo $filters['id_menu']; ?>"><?php echo $row['name']; ?></a></td>
				<td style="text-align: center;"><?php echo cleverdine::printPriceCurrencySymb($row['price'], $curr_symb, $symb_pos, true); ?></td>
				<td style="text-align: center;">
					<?php if( $core_edit ) { ?>
						<a href="index.php?option=com_cleverdine&task=changeStatusColumn&table_db=takeaway_menus_entry&column_db=published&val=<?php echo $row['published']; ?>&id=<?php echo $row['id']; ?>&return_task=tkproducts<?php echo $qs_filters; ?>">
							<?php echo intval($row['published']) == 1 ? "<img src=\"".JUri::root()."administrator/components/com_cleverdine/assets/images/ok.png\"/>" : "<img src=\"".JUri::root()."administrator/components/com_cleverdine/assets/images/no.png\"/>"; ?>
						</a>
					<?php } else { ?>
						<?php echo intval($row['published']) == 1 ? "<img src=\"".JUri::root()."administrator/components/com_cleverdine/assets/images/ok.png\"/>" : "<img src=\"".JUri::root()."administrator/components/com_cleverdine/assets/images/no.png\"/>"; ?>
					<?php } ?>
				</td>
				<td style="text-align: center;">
					<?php if( $core_edit ) { ?>
						<a href="index.php?option=com_cleverdine&task=changeStatusColumn&table_db=takeaway_menus_entry&column_db=ready&val=<?php echo $row['ready']; ?>&id=<?php echo $row['id']; ?>&return_task=tkproducts<?php echo $qs_filters; ?>">
							<?php echo intval($row['ready']) == 1 ? "<img src=\"".JUri::root()."administrator/components/com_cleverdine/assets/images/ok.png\"/>" : "<img src=\"".JUri::root()."administrator/components/com_cleverdine/assets/images/no.png\"/>"; ?>
						</a>
					<?php } else { ?>
						<?php echo intval($row['ready']) == 1 ? "<img src=\"".JUri::root()."administrator/components/com_cleverdine/assets/images/ok.png\"/>" : "<img src=\"".JUri::root()."administrator/components/com_cleverdine/assets/images/no.png\"/>"; ?>
					<?php } ?>
				</td>
				<?php if( $multi_lang ) { ?>
					<td style="text-align: center;">
						<a href="index.php?option=com_cleverdine&task=langtkproducts&id_menu=<?php echo $filters['id_menu']; ?>&id=<?php echo $row['id']; ?>">
							<?php foreach( $row['languages'] as $lang ) { ?>
								<img src="<?php echo JUri::root().'components/com_cleverdine/assets/css/flags/'.strtolower(substr($lang, 3, 2)).'.png'; ?>"/>
							<?php } ?>
						</a>
					</td>
				<?php } ?>
				<?php if( $core_edit ) { ?>
					<td style="text-align: center;">
						
						<?php if( $ordering['ordering'] > 0 ) { ?>
							<?php if( $row['ordering'] > $this->constraints['min'] ) { ?>
								<a href="index.php?option=com_cleverdine&amp;task=sortfield&cid[]=<?php echo $row['id']; ?>&mode=up&db_table=takeaway_menus_entry&return_task=tkproducts&params[id_takeaway_menu]=<?php echo $filters['id_menu']; ?>">
									<i class="fa fa-arrow-<?php echo ($ordering['ordering'] == 2 ? 'up' : 'down'); ?>"></i>
								</a>
							<?php } else { ?>
								<i class="empty"></i>
							<?php } ?>

							<?php if( $row['ordering'] < $this->constraints['max'] ) { ?>
								<a href="index.php?option=com_cleverdine&amp;task=sortfield&cid[]=<?php echo $row['id']; ?>&mode=down&db_table=takeaway_menus_entry&return_task=tkproducts&params[id_takeaway_menu]=<?php echo $filters['id_menu']; ?>">
									<i class="fa fa-arrow-<?php echo ($ordering['ordering'] == 2 ? 'down' : 'up'); ?>"></i>
								</a>
							<?php } else { ?>
								<i class="empty"></i>
							<?php } ?>
						<?php } ?>

						<input type="text" size="4" style="margin-bottom: 0;text-align: center;" value="<?php echo $row['ordering']; ?>" name="row_ord[<?php echo $row['id']; ?>][]"/>
					</td>
				<?php } ?>
			</tr>
			<?php
			$kk = 1 - $kk;
		}		
		?>
	</table>
	<?php } ?>

	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="task" value="tkproducts"/>
	<?php echo JHTML::_( 'form.token' ); ?>
	<?php echo $navbut; ?>
</form>

<script type="text/javascript">

	jQuery(document).ready(function(){
		jQuery('#vr-menu-sel').select2({
			allowClear: false,
			width: 300
		});

		jQuery('#vr-menu-sel').on('change', function(){
			document.adminForm.submit();
		});
	});
	
	function saveSort() {
		jQuery('input[name=task]').val('saveTkproductsSort');
		document.adminForm.submit();
	}
	
	var _LAST_SEARCH_ = '<?php echo addslashes($filters['key']); ?>';
	
	function clearFilter() {
		jQuery('#vrkeysearch').val('');
		if( _LAST_SEARCH_.length > 0 ) {
			document.adminForm.submit();
		}
	}
	
</script>
