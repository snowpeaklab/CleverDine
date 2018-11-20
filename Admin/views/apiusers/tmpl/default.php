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

// ORDERING LINKS

$COLUMNS_TO_ORDER = array('id', 'application', 'username', 'last_login');
foreach( $COLUMNS_TO_ORDER as $c ) {
	if( empty($ordering[$c]) ) {
		$ordering[$c] = 0;
	}
}

$links = array(
	OrderingManager::getLinkColumnOrder( 'apiusers', JText::_('VRMANAGEAPIUSER1'), 'id', $ordering['id'], 1, $filters, 'vrheadcolactive'.(($ordering['id'] == 2) ? 1 : 2) ),
	OrderingManager::getLinkColumnOrder( 'apiusers', JText::_('VRMANAGEAPIUSER2'), 'application', $ordering['application'], 1, $filters, 'vrheadcolactive'.(($ordering['application'] == 2) ? 1 : 2) ),
	OrderingManager::getLinkColumnOrder( 'apiusers', JText::_('VRMANAGEAPIUSER3'), 'username', $ordering['username'], 1, $filters, 'vrheadcolactive'.(($ordering['username'] == 2) ? 1 : 2) ),
	OrderingManager::getLinkColumnOrder( 'apiusers', JText::_('VRMANAGEAPIUSER7'), 'last_login', $ordering['last_login'], 1, $filters, 'vrheadcolactive'.(($ordering['last_login'] == 2) ? 1 : 2) ),
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
			<a href="index.php?option=com_cleverdine&task=apibans" class="btn">
				<?php echo JText::_('VRMANAGEAPIUSER16'); ?>
			</a>
			
			<a href="index.php?option=com_cleverdine&task=apilogs" class="btn">
				<?php echo JText::_('VRMANAGEAPIUSER12'); ?>
			</a>
		</div>
	</div>
	
<?php if( count( $rows ) == 0 ) { ?>
	<p><?php echo JText::_('VRNOAPIUSER');?></p>
<?php } else { ?>
	
	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="<?php echo $vik->getAdminTableClass(); ?>">
		<?php echo $vik->openTableHead(); ?>
			<tr>
				<th width="20">
					<?php echo $vik->getAdminToggle(count($rows)); ?>
				</th>
				<th class="<?php echo $vik->getAdminThClass('left'); ?>" width="50" style="text-align: left;"><?php echo $links[0]; ?></th>
				<th class="<?php echo $vik->getAdminThClass('left'); ?>" width="150" style="text-align: left;"><?php echo $links[1]; ?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="150" style="text-align: center;"><?php echo $links[2]; ?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="150" style="text-align: center;"><?php echo JText::_('VRMANAGEAPIUSER5'); ?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="100" style="text-align: center;"><?php echo JText::_('VRMANAGEAPIUSER6'); ?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="100" style="text-align: center;"><?php echo JText::_('VRMANAGEAPIUSER11'); ?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="150" style="text-align: center;"><?php echo $links[3]; ?></th>
			</tr>
		<?php echo $vik->closeTableHead(); ?>
		<?php
		$kk = 0;
		for( $i = 0; $i < count($rows); $i++ ) {
			$row = $rows[$i];

			$ips_str = (strlen($row['ips']) ? implode(', ', json_decode($row['ips'], true)) : '');

			?>
			<tr class="row<?php echo $kk; ?>">
				<td><input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row['id']; ?>" onClick="<?php echo $vik->checkboxOnClick(); ?>"></td>
				<td><?php echo $row['id']; ?></td>
				<td><a href="index.php?option=com_cleverdine&amp;task=editapiuser&amp;cid[]=<?php echo $row['id']; ?>"><?php echo (strlen($row['application']) ? $row['application'] : $row['username']); ?></a></td>
				<td style="text-align: center;"><?php echo $row['username']; ?></td>
				<td style="text-align: center;"><?php echo $ips_str; ?></td>
				<td style="text-align: center;">
					<?php if( $core_edit ) { ?>
						<a href="index.php?option=com_cleverdine&task=changeStatusColumn&table_db=api_login&column_db=active&val=<?php echo $row['active']; ?>&id=<?php echo $row['id']; ?>&return_task=apiusers">
							<?php echo intval($row['active']) == 1 ? "<img src=\"".JUri::root()."administrator/components/com_cleverdine/assets/images/ok.png\"/>" : "<img src=\"".JUri::root()."administrator/components/com_cleverdine/assets/images/no.png\"/>"; ?>
						</a>
					<?php } else { ?>
						<?php echo intval($row['active']) == 1 ? "<img src=\"".JUri::root()."administrator/components/com_cleverdine/assets/images/ok.png\"/>" : "<img src=\"".JUri::root()."administrator/components/com_cleverdine/assets/images/no.png\"/>"; ?>
					<?php } ?>
				</td>
				<td style="text-align: center;">
					<?php if( $row['log'] !== null ) { ?>
						<a href="index.php?option=com_cleverdine&task=apilogs&id_login=<?php echo $row['id']; ?>">
							<i class="fa fa-file-text big"></i>
						</a>
					<?php } else { ?>
						<a href="javascript: void(0);" class="disabled" disabled="disabled">
							<i class="fa fa-file-text big"></i>
						</a>
					<?php } ?>
				</td>
				<td style="text-align: center;">
					<span style="float: left;margin-left: 10px;">
						<?php echo ($row['last_login'] > 0 ? cleverdine::formatTimestamp($dt_format, $row['last_login']) : JText::_('VRMANAGEAPIUSER10')); ?>
					</span>

					<span style="float: right;margin-right: 10px;">
						<?php
						if( $row['log'] === null && $row['last_login'] <= 0 ) {
							$color = '999';
						} else if( $row['log'] === null || $row['log']['status'] ) {
							$color = '090';
						} else {
							$color = '900';
						} ?> 

						<i class="fa fa-circle big" style="color: #<?php echo $color; ?>;"></i>
					</span>
				</td>
			</tr>
			<?php
			$kk = 1 - $kk;
		}		
		?>
	</table>
	<?php } ?>
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="task" value="apiusers"/>
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
	
</script>
