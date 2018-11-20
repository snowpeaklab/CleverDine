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

$dt_format = cleverdine::getDateFormat(true)." ".cleverdine::getTimeFormat(true);

$vik = new VikApplication(VersionListener::getID());

$core_edit = (JFactory::getUser()->authorise('core.edit', 'com_cleverdine'));

?>

<form action="index.php?option=com_cleverdine" method="post" name="adminForm" id="adminForm">
	
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
			<div class="vr-toolbar-setfont">
				<?php
				$arr = getdate();
				$date_filters = array(
					'',
					mktime(0, 0, 0, $arr['mon'], $arr['mday'], $arr['year']),
					mktime(0, 0, 0, $arr['mon'], $arr['mday']-7, $arr['year']),
					mktime(0, 0, 0, $arr['mon']-1, $arr['mday'], $arr['year']),
					mktime(0, 0, 0, $arr['mon']-3, $arr['mday'], $arr['year'])
				);
				$elements = array();
				foreach( $date_filters as $i => $ts ) {
					array_push($elements, $vik->initOptionElement($ts, !empty($ts) ? JText::_('VROPLOGDATEFILTER'.$i) : '', $filters['date'] == $ts));
				}
				echo $vik->dropdown('date', $elements, 'vr-op-datefilter');
				?>
			</div>
		</div>
	</div>
	
<?php if( count( $rows ) == 0 ) { ?>
	<p><?php echo JText::_('VRNOOPERATORLOG');?></p>
<?php } else { ?>

	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="<?php echo $vik->getAdminTableClass(); ?>">
		<?php echo $vik->openTableHead(); ?>
			<tr>
				<th width="20">
					<?php echo $vik->getAdminToggle(count($rows)); ?>
				</th>
				<th class="<?php echo $vik->getAdminThClass('left'); ?>" width="400" style="text-align: left;"><?php echo JText::_('VRMANAGEOPLOG1'); ?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="100" style="text-align: center;"><?php echo JText::_('VRMANAGEOPLOG2'); ?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="150" style="text-align: center;"><?php echo JText::_('VRMANAGEOPLOG3'); ?></th>
			</tr>
		<?php echo $vik->closeTableHead(); ?>
		<?php
		$kk = 0;
		for( $i = 0; $i < count($rows); $i++ ) {
			$row = $rows[$i];
			?>
			<tr class="row<?php echo $kk; ?>">
				<td><input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row['id']; ?>" onClick="<?php echo $vik->checkboxOnClick(); ?>"></td>
				<td><?php echo $row['log']; ?></td>
				<td style="text-align: center;"><?php echo cleverdine::formatTimestamp($dt_format, $row['createdon']); ?></td>
				<td style="text-align: center;"><?php echo JText::_("VROPLOGTYPE".$row['group']); ?></td>
			</tr>
			<?php
			$kk = 1 - $kk;
		}		
		?>
	</table>
	<?php } ?>
	<input type="hidden" name="id" value="<?php echo $filters['id_operator']; ?>" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="task" value="operatorlogs"/>
	<?php echo JHTML::_( 'form.token' ); ?>
	<?php echo $navbut; ?>
</form>

<script>

jQuery(document).ready(function(){

	jQuery('#vr-op-datefilter').select2({
		placeholder: '<?php echo addslashes(JText::_('VROPLOGDATEFILTER5')); ?>',
		allowClear: true,
		width: 300
	});

	jQuery('#vr-op-datefilter').on('change', function(){
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