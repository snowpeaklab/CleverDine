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

$vik = new VikApplication(VersionListener::getID());

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
	<p><?php echo JText::_('VRNOAPIPLUGIN');?></p>
<?php } else { ?>
	
	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="<?php echo $vik->getAdminTableClass(); ?>">
		<?php echo $vik->openTableHead(); ?>
			<tr>
				<th width="20">
					<?php echo $vik->getAdminToggle(count($rows)); ?>
				</th>
				<th class="<?php echo $vik->getAdminThClass('left'); ?>" width="150" style="text-align: left;"><?php echo JText::_('VRMANAGEAPIUSER22'); ?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="150" style="text-align: center;"><?php echo JText::_('VRMANAGEAPIUSER23'); ?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="150" style="text-align: center;"><?php echo JText::_('VRMANAGEAPIUSER24'); ?></th>
			</tr>
		<?php echo $vik->closeTableHead(); ?>
		<?php
		$kk = 0;
		for( $i = 0; $i < count($rows); $i++ ) {
			$row = $rows[$i];

			?>
			<tr class="row<?php echo $kk; ?>">
				<td><input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row->getName(); ?>" onClick="<?php echo $vik->checkboxOnClick(); ?>"></td>
				<td><?php echo $row->getTitle(); ?></td>
				<td style="text-align: center;"><?php echo $row->getName().'.php'; ?></td>
				<td style="text-align: center;">
					<?php if( strlen($row->getDescription()) ) { ?>
						<a href="index.php?option=com_cleverdine&task=editapiplugin&plugin=<?php echo $row->getName(); ?>" >
							<i class="fa fa-sticky-note big"></i>
						</a>
					<?php } else { ?>
						<a href="javascript: void(0);" class="disabled" disabled="disabled">
							<i class="fa fa-sticky-note big"></i>
						</a>
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
	<input type="hidden" name="task" value="apiplugins"/>
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
