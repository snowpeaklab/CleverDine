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

$dt_format = cleverdine::getDateFormat(true).' '.cleverdine::getTimeFormat(true);

$now = time();

// ORDERING LINKS

$COLUMNS_TO_ORDER = array('id', 'status');
foreach( $COLUMNS_TO_ORDER as $c ) {
	if( empty($ordering[$c]) ) {
		$ordering[$c] = 0;
	}
}

$links = array(
	OrderingManager::getLinkColumnOrder( 'apilogs', JText::_('VRMANAGEAPIUSER1'), 'id', $ordering['id'], 1, $filters, 'vrheadcolactive'.(($ordering['id'] == 2) ? 1 : 2) ),
	OrderingManager::getLinkColumnOrder( 'apilogs', JText::_('VRMANAGEAPIUSER13'), 'status', $ordering['status'], 1, $filters, 'vrheadcolactive'.(($ordering['status'] == 2) ? 1 : 2) ),
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
	</div>
	
<?php if( count( $rows ) == 0 ) { ?>
	<p><?php echo JText::_('VRNOAPILOG');?></p>
<?php } else { ?>
	
	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="<?php echo $vik->getAdminTableClass(); ?>">
		<?php echo $vik->openTableHead(); ?>
			<tr>
				<th width="20">
					<?php echo $vik->getAdminToggle(count($rows)); ?>
				</th>
				<th class="<?php echo $vik->getAdminThClass('left'); ?>" width="50" style="text-align: left;"><?php echo $links[0]; ?></th>
				<th class="<?php echo $vik->getAdminThClass('left'); ?>" width="150" style="text-align: left;"><?php echo JText::_('VRMANAGEAPIUSER15'); ?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="300" style="text-align: center;"><?php echo JText::_('VRMANAGEAPIUSER14'); ?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="150" style="text-align: center;"><?php echo $links[1]; ?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="100" style="text-align: center;"><?php echo JText::_('VRMANAGEAPIUSER2'); ?></th>
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
				<td>
					<span title="<?php echo date($dt_format, $row['createdon']); ?>">
						<?php echo ($now - $row['createdon'] < 86400 ? cleverdine::formatTimestamp($dt_format, $row['createdon']) : date($dt_format, $row['createdon'])); ?>
					</span>
				</td>
				<td style="text-align: left;"><?php echo nl2br($row['content']); ?></td>
				<td style="text-align: center;" class="vrreservationstatus<?php echo ($row['status'] ? 'confirmed' : 'removed'); ?>"><?php echo JText::_($row['status'] ? 'VROK' : 'VRERROR'); ?></td>
				<td style="text-align: center;">
					<?php echo (strlen($row['application']) ? $row['application'] : (strlen($row['username']) ? $row['username'] : $row['ip']) ); ?>
				</td>
			</tr>
			<?php
			$kk = 1 - $kk;
		}		
		?>
	</table>
	<?php } ?>
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="task" value="apilogs"/>
	<input type="hidden" name="id_login" value="<?php echo $filters['id_login']; ?>"/>
	<?php echo JHTML::_( 'form.token' ); ?>
	<?php echo $navbut; ?>
</form>

<script type="text/javascript">
	
	var _LAST_SEARCH_ = '<?php echo addslashes($filters['key']); ?>';
	
	function clearFilter() {
		jQuery('#vrkeysearch').val('');
		if( _LAST_SEARCH_.length > 0 ) {
			document.adminForm.submit();
		}
	}

	Joomla.submitbutton = function(task) {
		if( task == 'truncateApilogs' ) {
			
			if( confirm('<?php echo JText::_('VRSYSTEMCONFIRMATIONMSG'); ?>') ) {
				Joomla.submitform(task, document.adminForm);	
			}
		} else {
			Joomla.submitform(task, document.adminForm);
		}
	}
	
</script>
