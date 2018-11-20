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

// ORDERING LINKS

$COLUMNS_TO_ORDER = array('code', 'lastname');
foreach( $COLUMNS_TO_ORDER as $c ) {
	if( empty($ordering[$c]) ) {
		$ordering[$c] = 0;
	}
}

$links = array(
	OrderingManager::getLinkColumnOrder( 'operators', JText::_('VRMANAGEOPERATOR1'), 'code', $ordering['code'], 1, $filters, 'vrheadcolactive'.(($ordering['code'] == 2) ? 1 : 2) ),
	OrderingManager::getLinkColumnOrder( 'operators', JText::_('VRMANAGEOPERATOR8'), 'lastname', $ordering['lastname'], 1, $filters, 'vrheadcolactive'.(($ordering['lastname'] == 2) ? 1 : 2) )
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
	<p><?php echo JText::_('VRNOOPERATOR');?></p>
<?php } else { ?>

	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="<?php echo $vik->getAdminTableClass(); ?>">
		<?php echo $vik->openTableHead(); ?>
			<tr>
				<th width="20">
					<?php echo $vik->getAdminToggle(count($rows)); ?>
				</th>
				<th class="<?php echo $vik->getAdminThClass('left'); ?>" width="100" style="text-align: left;"><?php echo $links[0]; ?></th>
				<th class="<?php echo $vik->getAdminThClass('left'); ?>" width="150" style="text-align: left;"><?php echo $links[1]; ?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="150" style="text-align: center;"><?php echo JText::_('VRMANAGEOPERATOR5'); ?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="150" style="text-align: center;"><?php echo JText::_('VRMANAGEOPERATOR4'); ?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="100" style="text-align: center;"><?php echo JText::_('VRMANAGEOPERATOR6'); ?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="100" style="text-align: center;"><?php echo JText::_('VRMANAGEOPERATOR14'); ?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="100" style="text-align: center;"><?php echo JText::_('VRMANAGEOPERATOR7'); ?></th>
			</tr>
		<?php echo $vik->closeTableHead(); ?>
		<?php
		$kk = 0;
		for( $i = 0; $i < count($rows); $i++ ) {
			$row = $rows[$i];
			 
			?>
			<tr class="row<?php echo $kk; ?>">
				<td><input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row['id']; ?>" onClick="<?php echo $vik->checkboxOnClick(); ?>"></td>
				<td><?php echo $row['code']; ?></td>
				<td><a href="index.php?option=com_cleverdine&amp;task=editoperator&amp;cid[]=<?php echo $row['id']; ?>"><?php echo $row['lastname']." ".$row['firstname']; ?></a></td>
				<td style="text-align: center;"><?php echo $row['email']; ?></td>
				<td style="text-align: center;"><?php echo $row['phone_number']; ?></td>
				<td style="text-align: center;">
					<?php if( $core_edit ) { ?>
					<a href="index.php?option=com_cleverdine&task=changeStatusColumn&table_db=operator&column_db=can_login&val=<?php echo $row['can_login']; ?>&id=<?php echo $row['id']; ?>&return_task=operators">
						<?php echo intval($row['can_login']) == 1 ? "<img src=\"".JUri::root()."administrator/components/com_cleverdine/assets/images/ok.png\"/>" : "<img src=\"".JUri::root()."administrator/components/com_cleverdine/assets/images/no.png\"/>"; ?>
					</a>
					<?php } else {
						echo intval($row['can_login']) == 1 ? "<img src=\"".JUri::root()."administrator/components/com_cleverdine/assets/images/ok.png\"/>" : "<img src=\"".JUri::root()."administrator/components/com_cleverdine/assets/images/no.png\"/>";
					} ?>
				</td>
				<td style="text-align: center;">
					<?php if( $row['keep_track'] ) { ?>
					<a href="index.php?option=com_cleverdine&task=operatorlogs&id=<?php echo $row['id']; ?>">
						<i class="fa fa-file-text big"></i>
					</a>
					<?php } else { ?>
						<a href="javascript: void(0);" class="disabled">
							<i class="fa fa-file-text big"></i>
						</a>
					<?php } ?>
				</td>
				<td style="text-align: center;color: #009900;font-weight: bold;"><?php echo "#".$row['jid']; ?></td>
			</tr>
			<?php
			$kk = 1 - $kk;
		}		
		?>
	</table>

	<?php } ?>
	
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="task" value="operators"/>
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