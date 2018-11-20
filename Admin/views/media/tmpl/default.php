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
		<div class="filter-search btn-group pull-left">
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

	</div>
	
<?php if( count( $rows ) == 0 ) { ?>
	<p><?php echo JText::_('VRNOMEDIA');?></p>
<?php } else { ?>

	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="<?php echo $vik->getAdminTableClass(); ?>">
		<?php echo $vik->openTableHead(); ?>
			<tr>
				<th width="20">
					<?php echo $vik->getAdminToggle(count($rows)); ?>
				</th>
				<th class="<?php echo $vik->getAdminThClass('left'); ?>" width="350" style="text-align: left;"><?php echo JText::_('VRMANAGEMEDIA1'); ?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="250" style="text-align: center;"><?php echo JText::_('VRMANAGEMEDIA2'); ?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="150" style="text-align: center;"><?php echo JText::_('VRMANAGEMEDIA3'); ?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="150" style="text-align: center;"><?php echo JText::_('VRMANAGEMEDIA4'); ?></th>
			</tr>
		<?php echo $vik->closeTableHead(); ?>
		<?php
		$kk = 0;
		for( $i = 0; $i < count($rows); $i++ ) {
			$row = $rows[$i];
			 
			?>
			<tr class="row<?php echo $kk; ?>">
				<td><input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row['name']; ?>" onClick="<?php echo $vik->checkboxOnClick(); ?>"></td>
				<td><a href="index.php?option=com_cleverdine&amp;task=editmedia&amp;cid[]=<?php echo $row['name']; ?>"><?php echo $row['name']; ?></a></td>
				<td style="text-align: center;">
					<div>
						<span style="width: 40%;display: inline-block;text-align: right;"><?php echo $row['size']; ?></span>
						<span style="width: 5%;display: inline-block;text-align: center;">|</span>
						<span style="width: 40%;display: inline-block;text-align: left;"><?php echo $row['width'].' x '.$row['height'].' px'; ?></span>
					</div>
				</td>
				<td style="text-align: center;"><?php echo $row['creation']; ?></td>
				<td style="text-align: center;">
					<a href="javascript: void(0);" class="vremodal" onClick="vreOpenModalImage('<?php echo JUri::root().'components/com_cleverdine/assets/media/'.$row['name']; ?>');">
						<img src="<?php echo JUri::root().'components/com_cleverdine/assets/media@small/'.$row['name']; ?>" style="max-width: 64px;height: auto;"/>
					</a>
				</td>
			</tr>
			<?php
			$kk = 1 - $kk;
		}		
		?>
	</table>
	<?php } ?>
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="task" value="media"/>
	<?php echo JHTML::_( 'form.token' ); ?>
	<?php echo $navbut; ?>
</form>

<script type="text/javascript">

	var _LAST_SEARCH_ = '<?php echo addslashes($filters['keysearch']); ?>';
	
	function clearFilter() {
		jQuery('#vrkeysearch').val('');
		if( _LAST_SEARCH_.length > 0 ) {
			document.adminForm.submit();
		}
	}

</script>