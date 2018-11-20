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

$dt_format = cleverdine::getDateFormat(true).' '.cleverdine::getTimeFormat(true);

$now = time();

$max_fail = cleverdine::getApiFrameworkMaxFailureAttempts();

// ORDERING LINKS

$COLUMNS_TO_ORDER = array('id', 'last_update', 'fail_count');
foreach( $COLUMNS_TO_ORDER as $c ) {
	if( empty($ordering[$c]) ) {
		$ordering[$c] = 0;
	}
}

$links = array(
	OrderingManager::getLinkColumnOrder( 'apibans', JText::_('VRMANAGEAPIUSER1'), 'id', $ordering['id'], 1, $filters, 'vrheadcolactive'.(($ordering['id'] == 2) ? 1 : 2) ),
	OrderingManager::getLinkColumnOrder( 'apibans', JText::_('VRMANAGEAPIUSER18'), 'last_update', $ordering['last_update'], 1, $filters, 'vrheadcolactive'.(($ordering['last_update'] == 2) ? 1 : 2) ),
	OrderingManager::getLinkColumnOrder( 'apibans', JText::_('VRMANAGEAPIUSER19'), 'fail_count', $ordering['fail_count'], 1, $filters, 'vrheadcolactive'.(($ordering['fail_count'] == 2) ? 1 : 2) ),
);

?>

<form action="index.php?option=com_cleverdine" method="post" name="adminForm" id="adminForm">
	
	<div class="btn-toolbar vr-btn-toolbar" id="filter-bar">
		<div class="btn-group pull-left">
			<input type="text" name="keysearch" id="vrkeysearch" class="vrkeysearch" size="32" value="<?php echo $filters['key']; ?>" placeholder="<?php echo JText::_('VRMENUPRODKEYSEARCH'); ?>"/>
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
				<select name="type" id="vr-type-sel">
					<option value="1" <?php echo ($filters['type'] == 1 ? 'selected="selected"' : ''); ?>><?php echo JText::_('VRAPIBANOPT1'); ?></option>
					<option value="2" <?php echo ($filters['type'] == 2 ? 'selected="selected"' : ''); ?>><?php echo JText::_('VRAPIBANOPT2'); ?></option>
				</select>
			</div>
		</div>
	</div>
	
<?php if( count( $rows ) == 0 ) { ?>
	<p><?php echo JText::_('VRNOAPIBAN');?></p>
<?php } else { ?>
	
	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="<?php echo $vik->getAdminTableClass(); ?>">
		<?php echo $vik->openTableHead(); ?>
			<tr>
				<th width="20">
					<?php echo $vik->getAdminToggle(count($rows)); ?>
				</th>
				<th class="<?php echo $vik->getAdminThClass('left'); ?>" width="50" style="text-align: left;"><?php echo $links[0]; ?></th>
				<th class="<?php echo $vik->getAdminThClass('left'); ?>" width="150" style="text-align: left;"><?php echo JText::_('VRMANAGEAPIUSER17'); ?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="200" style="text-align: center;"><?php echo $links[1]; ?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="100" style="text-align: center;"><?php echo $links[2]; ?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="150" style="text-align: center;"></th>
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
				<td><?php echo $row['ip']; ?></td>
				<td style="text-align: center;">
					<span title="<?php echo date($dt_format, $row['last_update']); ?>">
						<?php echo ($now - $row['last_update'] < 86400 ? cleverdine::formatTimestamp($dt_format, $row['last_update']) : date($dt_format, $row['last_update'])); ?>
					</span>
				</td>
				<td style="text-align: center;<?php echo ($row['fail_count'] >= $max_fail ? 'color:#900;' : ''); ?>"><?php echo $row['fail_count'].' / '.$max_fail; ?></td>
				<td style="text-align: center;">
					<?php if( $row['fail_count'] >= $max_fail ) { ?>
						<i class="fa fa-ban"></i>&nbsp;<?php echo JText::_('VRMANAGEAPIUSER20'); ?>
					<?php } ?>
				</td>
			</tr>
			<?php
			$kk = 1 - $kk;
		}		
		?>
	</table>
	<?php } ?>
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="task" value="apibans"/>
	<?php echo JHTML::_( 'form.token' ); ?>
	<?php echo $navbut; ?>
</form>

<script type="text/javascript">

	jQuery(document).ready(function(){
		
		jQuery('#vr-type-sel').select2({
			minimumResultsForSearch: -1,
			allowClear: false,
			width: 300
		});

		jQuery('#vr-type-sel').on('change', function(){
			document.adminForm.submit();
		});

	});
	
	var _LAST_SEARCH_ = '<?php echo addslashes($filters['key']); ?>';
	
	function clearFilter() {
		jQuery('#vrkeysearch').val('');
		if( _LAST_SEARCH_.length > 0 ) {
			document.adminForm.submit();
		}
	}
	
</script>
