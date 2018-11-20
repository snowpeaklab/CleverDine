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

$rows = $this->rows;
$lim0 = $this->lim0;
$navbut = $this->navbut;

$filters = $this->filters;

$ordering = $this->ordering;

$vik = new VikApplication(VersionListener::getID());

$core_edit = (JFactory::getUser()->authorise('core.edit', 'com_cleverdine'));

// ORDERING LINKS

$COLUMNS_TO_ORDER = array('id', 'name', 'published', 'ordering');
foreach( $COLUMNS_TO_ORDER as $c ) {
	if( empty($ordering[$c]) ) {
		$ordering[$c] = 0;
	}
}

$links = array(
	OrderingManager::getLinkColumnOrder( 'rooms', JText::_('VRMANAGEMENUSPRODUCT1'), 'id', $ordering['id'], 1, $filters, 'vrheadcolactive'.(($ordering['id'] == 2) ? 1 : 2) ),
	OrderingManager::getLinkColumnOrder( 'rooms', JText::_('VRMANAGEROOM1'), 'name', $ordering['name'], 1, $filters, 'vrheadcolactive'.(($ordering['name'] == 2) ? 1 : 2) ),
	OrderingManager::getLinkColumnOrder( 'rooms', JText::_('VRMANAGEROOM3'), 'published', $ordering['published'], 1, $filters, 'vrheadcolactive'.(($ordering['published'] == 2) ? 1 : 2) ),
	OrderingManager::getLinkColumnOrder( 'rooms', JText::_('VRMANAGEMENU19'), 'ordering', $ordering['ordering'], 1, $filters, 'vrheadcolactive'.(($ordering['ordering'] == 2) ? 1 : 2) ),
);

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
	</div>

<?php if( count( $rows ) == 0 ) { ?>
	<p><?php echo JText::_('VRNOROOM');?></p>
<?php } else { ?>

	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="<?php echo $vik->getAdminTableClass(); ?>">
		<?php echo $vik->openTableHead(); ?>
			<tr>
				<th width="20">
					<?php echo $vik->getAdminToggle(count($rows)); ?>
				</th>
				<th class="<?php echo $vik->getAdminThClass('left'); ?>" width="50" style="text-align: left;"><?php echo $links[0]; ?></th>
				<th class="<?php echo $vik->getAdminThClass('left'); ?>" width="150" style="text-align: left;"><?php echo $links[1]; ?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="300" style="text-align: center;"><?php echo JText::_('VRMANAGEROOM2'); ?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="50" style="text-align: center;"><?php echo $links[2]; ?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="50" style="text-align: center;"><?php echo JText::_('VRMANAGEROOM10'); ?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="50" style="text-align: center;"><?php echo JText::_('VRMANAGEROOM4'); ?></th>
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

			$room_desc = strip_tags($row['description']);
			if( strlen($room_desc) > 280 ) {
				$room_desc = mb_substr($room_desc, 0, 256, 'UTF-8')."...";
			}
			
			$ts = time();
			$status_class = "vrreservationstatusconfirmed"; 
			$status_name = "VRROOMSTATUSACTIVE";
			if( $row['is_closed'] ) {
				$status_class = "vrreservationstatusremoved"; 
				$status_name = "VRROOMSTATUSCLOSED";
			}
			
			$icon_type = 1;
			if( empty($row['image']) ) {
				$icon_type = 2; // ICON NOT UPLOADED
			}
			
			$img_url = 'components'.DIRECTORY_SEPARATOR.'com_cleverdine'.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.$row['image'];
			if( !file_exists(JPATH_SITE.DIRECTORY_SEPARATOR.$img_url) ) {
				$icon_type = 0;
			}
			
			$img_title = JText::_('VRIMAGESTATUS'.$icon_type);
			?>
			<tr class="row<?php echo $kk; ?>">
				<td><input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row['id']; ?>" onClick="<?php echo $vik->checkboxOnClick(); ?>"></td>
				<td><?php echo $row['id']; ?>
				<td><a href="index.php?option=com_cleverdine&amp;task=editroom&amp;cid[]=<?php echo $row['id']; ?>"><?php echo $row['name']; ?></a></td>
				<td><?php echo $room_desc; ?></td>
				<td style="text-align: center;">
					<?php if( $core_edit ) { ?>
						<a href="index.php?option=com_cleverdine&task=changeStatusColumn&table_db=room&column_db=published&val=<?php echo $row['published']; ?>&id=<?php echo $row['id']; ?>&return_task=rooms">
							<?php echo intval($row['published']) == 1 ? "<img src=\"".JUri::root()."administrator/components/com_cleverdine/assets/images/ok.png\"/>" : "<img src=\"".JUri::root()."administrator/components/com_cleverdine/assets/images/no.png\"/>"; ?>
						</a>
					<?php } else { ?>
						<?php echo intval($row['published']) == 1 ? "<img src=\"".JUri::root()."administrator/components/com_cleverdine/assets/images/ok.png\"/>" : "<img src=\"".JUri::root()."administrator/components/com_cleverdine/assets/images/no.png\"/>"; ?>
					<?php } ?>
				</td>
				<td style="text-align: center;" class="<?php echo $status_class; ?>"><?php echo JText::_($status_name); ?></td>
				<td style="text-align: center;">
					<?php if( $icon_type == 1) { ?>
						<a href="<?php echo JUri::root().$img_url; ?>" class="modal" target="_blank">
							<img src="<?php echo JUri::root()."administrator/components/com_cleverdine/assets/images/imagepreview.png"; ?>" title="<?php echo $img_title ?>"/>
						</a>
					<?php } else if( $icon_type == 0 ) { ?>
						<img src="<?php echo JUri::root()."administrator/components/com_cleverdine/assets/images/imagenotfound.png"; ?>" title="<?php echo $img_title ?>"/>
					<?php } else { ?>
						<img src="<?php echo JUri::root()."administrator/components/com_cleverdine/assets/images/imageno.png"; ?>" title="<?php echo $img_title ?>"/>
					<?php } ?>
				</td>
				<?php if( $core_edit ) { ?>
					<td style="text-align: center;">
						
						<?php if( $ordering['ordering'] > 0 ) { ?>
							<?php if( $row['ordering'] > $this->constraints['min'] ) { ?>
								<a href="index.php?option=com_cleverdine&amp;task=sortfield&cid[]=<?php echo $row['id']; ?>&mode=up&db_table=room&return_task=rooms">
									<i class="fa fa-arrow-<?php echo ($ordering['ordering'] == 2 ? 'up' : 'down'); ?>"></i>
								</a>
							<?php } else { ?>
								<i class="empty"></i>
							<?php } ?>

							<?php if( $row['ordering'] < $this->constraints['max'] ) { ?>
								<a href="index.php?option=com_cleverdine&amp;task=sortfield&cid[]=<?php echo $row['id']; ?>&mode=down&db_table=room&return_task=rooms">
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
	<input type="hidden" name="task" value="rooms"/>
	<?php echo JHTML::_( 'form.token' ); ?>
	<?php echo $navbut; ?>
</form>

<script type="text/javascript">

	function saveSort() {
		jQuery('input[name=task]').val('saveRoomsSort');
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