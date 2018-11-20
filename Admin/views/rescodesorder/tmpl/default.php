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

// ORDERING LINKS

$COLUMNS_TO_ORDER = array('id', 'createdon');
foreach( $COLUMNS_TO_ORDER as $c ) {
	if( empty($ordering[$c]) ) {
		$ordering[$c] = 0;
	}
}

$links = array(
	OrderingManager::getLinkColumnOrder( 'rescodesorder', JText::_('VRCREATEDON'), 'createdon', $ordering['createdon'], 1, $filters, 'vrheadcolactive'.(($ordering['createdon'] == 2) ? 1 : 2) ),
);

$dt_format = cleverdine::getDateFormat(true).' '.cleverdine::getTimeFormat(true);

$filters_query = '';
foreach( $filters as $k => $v ) {
	$filters_query .= "&filters[$k]=$v";
}

?>

<form action="index.php?option=com_cleverdine" method="post" name="adminForm" id="adminForm">
	
<?php if( count( $rows ) == 0 ) { ?>
	<p><?php echo JText::_('VRNORESCODEORDER');?></p>
<?php } else { ?>

	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="<?php echo $vik->getAdminTableClass(); ?>">
		<?php echo $vik->openTableHead(); ?>
			<tr>
				<th width="20">
					<?php echo $vik->getAdminToggle(count($rows)); ?>
				</th>
				<th class="<?php echo $vik->getAdminThClass('left'); ?>" width="150" style="text-align: left;"><?php echo JText::_('VRMANAGERESCODE2'); ?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="150" style="text-align: center;"><?php echo JText::_('VRMANAGERESCODE3'); ?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="250" style="text-align: center;"><?php echo JText::_('VRMANAGERESCODE5'); ?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="350" style="text-align: center;"><?php echo $links[0]; ?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="150" style="text-align: center;"><?php echo JText::_('VRCREATEDBY'); ?></th>
			</tr>
		<?php echo $vik->closeTableHead(); ?>
		<?php
		$kk = 0;
		for( $i = 0; $i < count($rows); $i++ ) {
			$row = $rows[$i];
			 
			?>
			<tr class="row<?php echo $kk; ?>">
				<td><input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row['id']; ?>" onClick="<?php echo $vik->checkboxOnClick(); ?>"></td>
				<td><a href="index.php?option=com_cleverdine&amp;task=editrescodeorder&amp;cid[]=<?php echo $row['id']; ?><?php echo $filters_query; ?>"><?php echo $row['code']; ?></a></td>
				<td style="text-align: center;" class="vrrescodelink">
					<?php if( !empty($row['icon']) ) { ?>
						<img src="<?php echo JUri::root().'components/com_cleverdine/assets/media/'.$row['icon']; ?>" style="width: 20px;"/>
					<?php } ?>
				</td>
				<td style="text-align: center;">
					<?php if( strlen($row['notes']) ) {
						echo $row['notes']; 
					} else {
						?><small><i><?php echo $row['code_notes']; ?></i></small><?php
					} ?>
				</td>
				<td style="text-align: center;">
					<?php
					if( time() - $row['createdon'] < 86400 ) { 
						echo cleverdine::formatTimestamp($dt_format, $row['createdon']); 
					} else {
						echo date($dt_format, $row['createdon']);
					}
					?>
				</td>
				<td style="text-align: center;"><?php echo $row['user_name']; ?></td>
			</tr>
			<?php
			$kk = 1 - $kk;
		}		
		?>
	</table>
	<?php } ?>
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="task" value="rescodesorder"/>
	<?php echo JHTML::_( 'form.token' ); ?>
	<?php echo $navbut; ?>

	<?php foreach( $filters as $key => $val ) {
		?><input type="hidden" name="filters[<?php echo $key; ?>]" value="<?php echo $val; ?>" /><?php
	} ?>

</form>