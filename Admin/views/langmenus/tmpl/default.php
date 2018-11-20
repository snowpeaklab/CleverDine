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

$vik = new VikApplication(VersionListener::getID());

?>

<form action="index.php?option=com_cleverdine" method="post" name="adminForm" id="adminForm">
<?php 
	if( count( $rows ) == 0 ) {
		?>
		<p><?php echo JText::_('VRNOLANGMENU');?></p>
		<?php
	} else {
?>

	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="<?php echo $vik->getAdminTableClass(); ?>">
		<?php echo $vik->openTableHead(); ?>
			<tr>
				<th width="20">
					<?php echo $vik->getAdminToggle(count($rows)); ?>
				</th>
				<th class="<?php echo $vik->getAdminThClass('left'); ?>" width="50" style="text-align: left;"><?php echo JText::_('VRMANAGELANG1');?></th>
				<th class="<?php echo $vik->getAdminThClass('left'); ?>" width="150" style="text-align: left;"><?php echo JText::_('VRMANAGELANG2');?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="400" style="text-align: center;"><?php echo JText::_('VRMANAGELANG3');?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="75" style="text-align: center;"><?php echo JText::_('VRMANAGELANG4');?></th>
			</tr>
		<?php echo $vik->closeTableHead(); ?>
		<?php
		$kk = 0;
		for( $i = 0; $i < count($rows); $i++ ) {
			$row = $rows[$i];
			
			$description = $row['description'];
			if( strlen(strip_tags($description)) > 1024 ) {
				$description = mb_substr(strip_tags($description), 0, 1020, 'UTF-8')." ...";
			}
			?>
			<tr class="row<?php echo $kk; ?>">
				<td><input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row['id']; ?>" onClick="<?php echo $vik->checkboxOnClick(); ?>"></td>
				<td><?php echo $row['id']; ?></td>
				<td><a href="index.php?option=com_cleverdine&task=editlangmenu&cid[]=<?php echo $row['id']; ?>"><?php echo $row['name']; ?></a></td>
				<td style="text-align: center;"><?php echo $description; ?></td>
				<td style="text-align: center;">
					<img src="<?php echo JUri::root().'components/com_cleverdine/assets/css/flags/'.strtolower(substr($row['tag'], 3, 2)).'.png'; ?>"/>    			        
				</td>
			</tr>
			
			<?php
			$kk = 1 - $kk;
		}		
		?>
	</table>
	<?php } ?>
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="task" value="langmenus"/>
	<input type="hidden" name="id_menu" value="<?php echo $this->idMenu; ?>"/>
	<?php echo JHTML::_( 'form.token' ); ?>
	<?php echo $navbut; ?>
</form>