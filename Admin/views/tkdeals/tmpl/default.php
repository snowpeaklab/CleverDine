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

$date_format = cleverdine::getDateFormat(true);

// ORDERING LINKS

$COLUMNS_TO_ORDER = array('id', 'name', 'type', 'published', 'ordering');
foreach( $COLUMNS_TO_ORDER as $c ) {
	if( empty($ordering[$c]) ) {
		$ordering[$c] = 0;
	}
}

$links = array(
	OrderingManager::getLinkColumnOrder( 'tkdeals', JText::_('VRMANAGETKDEAL1'), 'id', $ordering['id'], 1, $filters, 'vrheadcolactive'.(($ordering['id'] == 2) ? 1 : 2) ),
	OrderingManager::getLinkColumnOrder( 'tkdeals', JText::_('VRMANAGETKDEAL2'), 'name', $ordering['name'], 1, $filters, 'vrheadcolactive'.(($ordering['name'] == 2) ? 1 : 2) ),
	OrderingManager::getLinkColumnOrder( 'tkdeals', JText::_('VRMANAGETKDEAL8'), 'type', $ordering['type'], 1, $filters, 'vrheadcolactive'.(($ordering['type'] == 2) ? 1 : 2) ),
	OrderingManager::getLinkColumnOrder( 'tkdeals', JText::_('VRMANAGETKDEAL7'), 'published', $ordering['published'], 1, $filters, 'vrheadcolactive'.(($ordering['published'] == 2) ? 1 : 2) ),
	OrderingManager::getLinkColumnOrder( 'tkdeals', JText::_('VRMANAGETKDEAL9'), 'ordering', $ordering['ordering'], 1, $filters, 'vrheadcolactive'.(($ordering['ordering'] == 2) ? 1 : 2) ),
);

$vik = new VikApplication(VersionListener::getID());

$core_edit = (JFactory::getUser()->authorise('core.edit', 'com_cleverdine'));

$multi_lang = cleverdine::isMultilanguage(true);

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
				<?php
				$elements = array(
					$vik->initOptionElement('', '', false),
				);
				for( $i = 1; $i <= 6; $i++ ) {
					$elements[] = $vik->initOptionElement($i, JText::_('VRTKDEALTYPE'.$i), $i == $filters['type']);
				}
				echo $vik->dropdown('type', $elements, 'vr-type-sel');
				?>
			</div>
		</div>
		
	</div>

<?php if( count( $rows ) == 0 ) { ?>
	<p><?php echo JText::_('VRNOTKDEAL');?></p>
<?php } else { ?>
	
	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="<?php echo $vik->getAdminTableClass(); ?>">
		<?php echo $vik->openTableHead(); ?>
			<tr>
				<th width="20">
					<?php echo $vik->getAdminToggle(count($rows)); ?>
				</th>
				<th class="<?php echo $vik->getAdminThClass('left'); ?>" width="50" style="text-align: left;"><?php echo $links[0]; ?></th>
				<th class="<?php echo $vik->getAdminThClass('left'); ?>" width="150" style="text-align: left;"><?php echo $links[1]; ?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="100" style="text-align: center;"><?php echo JText::_('VRMANAGETKDEAL4'); ?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="100" style="text-align: center;"><?php echo JText::_('VRMANAGETKDEAL5'); ?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="200" style="text-align: center;"><?php echo $links[2]; ?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="75" style="text-align: center;"><?php echo JText::_('VRMANAGETKDEAL6'); ?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="75" style="text-align: center;"><?php echo $links[3]; ?></th>
				<?php if( $multi_lang ) { ?>
					<th class="<?php echo $vik->getAdminThClass(); ?>" width="100" style="text-align: center;"><?php echo JText::_('VRMANAGEMENU33');?></th>
				<?php } ?>
				<?php if( $core_edit ) { ?>
					<th class="<?php echo $vik->getAdminThClass(); ?>" width="150" style="text-align: center;">
						<?php echo $links[4]; ?>
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
				<td><a href="index.php?option=com_cleverdine&amp;task=edittkdeal&amp;cid[]=<?php echo $row['id']; ?>"><?php echo $row['name']; ?></a></td>
				<td style="text-align: center;"><?php echo ($row['start_ts'] > 0 ? date($date_format, $row['start_ts']) : '/'); ?></td>
				<td style="text-align: center;"><?php echo ($row['end_ts'] > 0 ? date($date_format, $row['end_ts']) : '/'); ?></td>
				<td style="text-align: center;"><?php echo JText::_('VRTKDEALTYPE'.$row['type']); ?></td>
				<td style="text-align: center;"><?php echo ($row['max_quantity'] > 0 ? $row['max_quantity'] : '/'); ?></td>
				<td style="text-align: center;">
					<?php if( $core_edit ) { ?>
						<a href="index.php?option=com_cleverdine&task=changeStatusColumn&table_db=takeaway_deal&column_db=published&val=<?php echo $row['published']; ?>&id=<?php echo $row['id']; ?>&return_task=tkdeals">
							<?php echo intval($row['published']) == 1 ? "<img src=\"".JUri::root()."administrator/components/com_cleverdine/assets/images/ok.png\"/>" : "<img src=\"".JUri::root()."administrator/components/com_cleverdine/assets/images/no.png\"/>"; ?>
						</a>
					<?php } else { ?>
						<?php echo intval($row['published']) == 1 ? "<img src=\"".JUri::root()."administrator/components/com_cleverdine/assets/images/ok.png\"/>" : "<img src=\"".JUri::root()."administrator/components/com_cleverdine/assets/images/no.png\"/>"; ?>
					<?php } ?>
				</td>
				<?php if( $multi_lang ) { ?>
					<td style="text-align: center;">
						<a href="index.php?option=com_cleverdine&task=langtkdeals&id=<?php echo $row['id']; ?>">
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
								<a href="index.php?option=com_cleverdine&amp;task=sortfield&amp;cid[]=<?php echo $row['id']; ?>&amp;mode=up&amp;db_table=takeaway_deal&amp;return_task=tkdeals">
									<i class="fa fa-arrow-<?php echo ($ordering['ordering'] == 2 ? 'up' : 'down'); ?>"></i>
								</a>
							<?php } else { ?>
								<i class="empty"></i>
							<?php } ?>

							<?php if( $row['ordering'] < $this->constraints['max'] ) { ?>
								<a href="index.php?option=com_cleverdine&amp;task=sortfield&amp;cid[]=<?php echo $row['id']; ?>&amp;mode=down&amp;db_table=takeaway_deal&amp;return_task=tkdeals">
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
	<input type="hidden" name="task" value="tkdeals"/>
	<?php echo JHTML::_( 'form.token' ); ?>
	<?php echo $navbut; ?>
</form>

<script>

	jQuery(document).ready(function(){

		jQuery('#vr-type-sel').select2({
			placeholder: '--',
			allowClear: true,
			width: 300
		});

		jQuery('#vr-type-sel').on('change', function(){
			document.adminForm.submit();
		});

	});

	function saveSort() {
		jQuery('input[name=task]').val('saveTkdealSort');
		document.adminForm.submit();
	}

	var _LAST_SEARCH_ = '<?php echo addslashes($filters['keysearch']); ?>';
	
	function clearFilter() {
		jQuery('#vrkeysearch').val('');
		if( _LAST_SEARCH_.length > 0 ) {
			document.adminForm.submit();
		}
	}
</script>
