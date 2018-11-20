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

$vik = new VikApplication(VersionListener::getID());

$core_edit = (JFactory::getUser()->authorise('core.edit', 'com_cleverdine'));

$time_format = cleverdine::getTimeFormat(true);

// ORDERING LINKS

$COLUMNS_TO_ORDER = array('id', 'name', 'from', 'group');
foreach( $COLUMNS_TO_ORDER as $c ) {
	if( empty($ordering[$c]) ) {
		$ordering[$c] = 0;
	}
}

$links = array(
	OrderingManager::getLinkColumnOrder( 'shifts', JText::_('VRMANAGERESCODE1'), 'id', $ordering['id'], 1, $filters, 'vrheadcolactive'.(($ordering['id'] == 2) ? 1 : 2) ),
	OrderingManager::getLinkColumnOrder( 'shifts', JText::_('VRMANAGESHIFT1'), 'name', $ordering['name'], 1, $filters, 'vrheadcolactive'.(($ordering['name'] == 2) ? 1 : 2) ),
	OrderingManager::getLinkColumnOrder( 'shifts', JText::_('VRMANAGESHIFT2'), 'from', $ordering['from'], 1, $filters, 'vrheadcolactive'.(($ordering['from'] == 2) ? 1 : 2) ),
	OrderingManager::getLinkColumnOrder( 'shifts', JText::_('VRMANAGESHIFT4'), 'group', $ordering['group'], 1, $filters, 'vrheadcolactive'.(($ordering['group'] == 2) ? 1 : 2) ),
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
		
		<div class="btn-group pull-right">
			<div class="vr-toolbar-setfont">
				<?php echo RestaurantsHelper::buildGroupDropdown('group', $filters['group'], 'vr-group-sel', array(1, 2), '', true); ?> 
			</div>
		</div>
	</div>

<?php if( count( $rows ) == 0 ) { ?>
	<p><?php echo JText::_('VRNOSHIFT');?></p>
<?php } else { ?>
	
	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="<?php echo $vik->getAdminTableClass(); ?>">
		<?php echo $vik->openTableHead(); ?>
			<tr>
				<th width="20">
					<?php echo $vik->getAdminToggle(count($rows)); ?>
				</th>
				<th class="<?php echo $vik->getAdminThClass('left'); ?>" width="50" style="text-align: left;"><?php echo $links[0]; ?></th>
				<th class="<?php echo $vik->getAdminThClass('left'); ?>" width="150" style="text-align: left;"><?php echo $links[1]; ?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="100" style="text-align: center;"><?php echo $links[2]; ?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="100" style="text-align: center;"><?php echo JText::_('VRMANAGESHIFT3'); ?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="100" style="text-align: center;"><?php echo JText::_('VRMANAGESHIFT5'); ?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="150" style="text-align: center;"><?php echo $links[3]; ?></th>
			</tr>
		<?php echo $vik->closeTableHead(); ?>
		<?php
		$kk = 0;
		for( $i = 0; $i < count($rows); $i++ ) {
			$row = $rows[$i];
			
			$from_hour = intval($row['from']/60);
			$from_min = $row['from']%60;
			
			$to_hour = intval($row['to']/60);
			$to_min = intval($row['to'])%60;
			
			?>
			<tr class="row<?php echo $kk; ?>">
				<td><input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row['id']; ?>" onClick="<?php echo $vik->checkboxOnClick(); ?>"></td>
				<td><?php echo $row['id']; ?></td>
				<td>
					<a href="index.php?option=com_cleverdine&amp;task=editshift&amp;cid[]=<?php echo $row['id']; ?>"><?php echo $row['name']; ?></a>
					<?php echo (strlen($row['label']) && $row['name'] != $row['label'] ? '<br /><small>'.$row['label'].'</small>' : ''); ?>
				</td>
				<td style="text-align: center;"><?php echo date($time_format, mktime( $from_hour, $from_min, 0, 1, 1, 2000 )); ?></td>
				<td style="text-align: center;"><?php echo date($time_format, mktime( $to_hour, $to_min, 0, 1, 1, 2000 )); ?></td>
				<td style="text-align: center;">
					<?php if( $core_edit ) { ?>
						<a href="index.php?option=com_cleverdine&task=changeStatusColumn&table_db=shifts&column_db=showlabel&val=<?php echo $row['showlabel']; ?>&id=<?php echo $row['id']; ?>&return_task=shifts">
							<?php echo intval($row['showlabel']) == 1 ? "<img src=\"".JUri::root()."administrator/components/com_cleverdine/assets/images/ok.png\"/>" : "<img src=\"".JUri::root()."administrator/components/com_cleverdine/assets/images/no.png\"/>"; ?>
						</a>
					<?php } else { ?>
						<?php echo intval($row['showlabel']) == 1 ? "<img src=\"".JUri::root()."administrator/components/com_cleverdine/assets/images/ok.png\"/>" : "<img src=\"".JUri::root()."administrator/components/com_cleverdine/assets/images/no.png\"/>"; ?>
					<?php } ?>
				</td>
				<td style="text-align: center;"><?php echo JText::_(($row['group'] == 1) ? 'VRSHIFTGROUPOPT1' : 'VRSHIFTGROUPOPT2'); ?></td>
			</tr>
			<?php
			$kk = 1 - $kk;
		}		
		?>
	</table>
	<?php } ?>
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="task" value="shifts"/>
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