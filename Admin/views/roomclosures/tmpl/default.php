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

$dt_format = cleverdine::getDateFormat(true)." ".cleverdine::getTimeFormat(true);

$rooms_list = array(
	$vik->initOptionElement('', '', true)
);
foreach( $this->allRooms as $room ) {
	array_push($rooms_list, $vik->initOptionElement($room['id'], $room['name'], $room['id'] == $filters['id_room']));
}

// ORDERING LINKS

$COLUMNS_TO_ORDER = array('id', 'id_room', 'start_ts');
foreach( $COLUMNS_TO_ORDER as $c ) {
	if( empty($ordering[$c]) ) {
		$ordering[$c] = 0;
	}
}

$links = array(
	OrderingManager::getLinkColumnOrder( 'roomclosures', JText::_('VRMANAGEMENUSPRODUCT1'), 'id', $ordering['id'], 1, $filters, 'vrheadcolactive'.(($ordering['id'] == 2) ? 1 : 2) ),
	OrderingManager::getLinkColumnOrder( 'roomclosures', JText::_('VRMANAGEROOMCLOSURE1'), 'id_room', $ordering['id_room'], 1, $filters, 'vrheadcolactive'.(($ordering['id_room'] == 2) ? 1 : 2) ),
	OrderingManager::getLinkColumnOrder( 'roomclosures', JText::_('VRMANAGEROOMCLOSURE2'), 'start_ts', $ordering['start_ts'], 1, $filters, 'vrheadcolactive'.(($ordering['start_ts'] == 2) ? 1 : 2) ),
);

?>

<form action="index.php?option=com_cleverdine" method="post" name="adminForm" id="adminForm">
	
	<div class="btn-toolbar vr-btn-toolbar">
		<div class="btn-group pull-right">
			<div class="vr-toolbar-setfont">
				<?php echo $vik->dropdown("room", $rooms_list, 'vr-room-filter'); ?>
			</div>
		</div>
	</div>
	
<?php if( count( $rows ) == 0 ) { ?>
	<p><?php echo JText::_('VRNOROOMCLOSURES');?></p>
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
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="100" style="text-align: center;"><?php echo JText::_('VRMANAGEROOMCLOSURE3'); ?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="150" style="text-align: center;"><?php echo JText::_('VRMANAGEROOMCLOSURE4'); ?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="100" style="text-align: center;"><?php echo JText::_('VRMANAGEROOMCLOSURE5'); ?></th>
			</tr>
		<?php echo $vik->closeTableHead(); ?>
		<?php
		$kk = 0;
		for( $i = 0; $i < count($rows); $i++ ) {
			$row = $rows[$i];
			
			$now = time();
			$status = "pending";
			if( $row['start_ts'] <= $now && $now < $row['end_ts'] ) {
				$status = "confirmed";
			} else if( $row['end_ts'] <= $now ) {
				$status = "removed";
			}
			?>
			<tr class="row<?php echo $kk; ?>">
				<td><input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row['id']; ?>" onClick="<?php echo $vik->checkboxOnClick(); ?>"></td>
				<td><?php echo $row['id']; ?></td>
				<td><a href="index.php?option=com_cleverdine&amp;task=editroomclosure&amp;cid[]=<?php echo $row['id']; ?>"><?php echo $row['name']; ?></a></td>
				<td style="text-align: center;"><?php echo date($dt_format, $row['start_ts']); ?></td>
				<td style="text-align: center;"><?php echo date($dt_format, $row['end_ts']); ?></td>
				<td style="text-align: center;"><?php echo cleverdine::minutesToStr(($row['end_ts']-$row['start_ts'])/60); ?></td>
				<td style="text-align: center;" class="vrreservationstatus<?php echo $status; ?>"><?php echo JText::_('VRROOMCLOSURESTATUS'.strtoupper($status)); ?></td>
			</tr>
			<?php
			$kk = 1 - $kk;
		}		
		?>
	</table>
	<?php } ?>
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="task" value="roomclosures"/>
	<?php echo JHTML::_( 'form.token' ); ?>
	<?php echo $navbut; ?>
</form>

<script>
	
	jQuery(document).ready(function(){

		jQuery('#vr-room-filter').select2({
			placeholder: '--',
			allowClear: true,
			width: 300
		});

		jQuery('#vr-room-filter').on('change', function(){
			document.adminForm.submit();
		});
	});
	
</script>