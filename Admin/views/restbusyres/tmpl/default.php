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

$date_format = cleverdine::getDateFormat(true);
$time_format = cleverdine::getTimeFormat(true);

$curr_symb = cleverdine::getCurrencySymb(true);
$symb_pos = cleverdine::getCurrencySymbPosition(true);

$vik = new VikApplication(VersionListener::getID());

?>

<form action="index.php?option=com_cleverdine" method="post" name="adminForm" id="adminForm">
	
	<div class="btn-toolbar vr-btn-toolbar" id="filter-bar">
		
		<div class="btn-group pull-right">
			<div class="vr-toolbar-setfont">
				<select name="interval" id="vr-interval-select">
					<option value="15" <?php echo ($this->filters['interval'] == 15 ? 'selected="selected"' : ''); ?>><?php echo cleverdine::minutesToStr(15); ?></option>
					<option value="30" <?php echo ($this->filters['interval'] == 30 ? 'selected="selected"' : ''); ?>><?php echo cleverdine::minutesToStr(30); ?></option>
					<option value="60" <?php echo ($this->filters['interval'] == 60 ? 'selected="selected"' : ''); ?>><?php echo cleverdine::minutesToStr(60); ?></option>
					<option value="90" <?php echo ($this->filters['interval'] == 90 ? 'selected="selected"' : ''); ?>><?php echo cleverdine::minutesToStr(90); ?></option>
					<option value="120" <?php echo ($this->filters['interval'] == 120 ? 'selected="selected"' : ''); ?>><?php echo cleverdine::minutesToStr(120); ?></option>
				</select>   
			</div>
		</div>
		
	</div>
	
<?php if( count( $rows ) == 0 ) { ?>
	<p><?php echo JText::_('VRNORESERVATION');?></p>
<?php } else { ?>
	
	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="<?php echo $vik->getAdminTableClass(); ?>">
		<?php echo $vik->openTableHead(); ?>
			<tr>
				<th class="<?php echo $vik->getAdminThClass('left'); ?>" width="10%" style="text-align: left;"><?php echo JText::_('VRMANAGERESERVATION1'); ?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="15%" style="text-align: center;"><?php echo JText::_('VRMANAGERESERVATION3'); ?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="15%" style="text-align: center;"><?php echo JText::_('VRMANAGERESERVATION5'); ?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="15%" style="text-align: center;"><?php echo JText::_('VRMANAGERESERVATION17'); ?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="5%" style="text-align: center;"><?php echo JText::_('VRMANAGERESERVATION10'); ?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="10%" style="text-align: center;"><?php echo JText::_('VRMANAGERESERVATION12'); ?></th>
			</tr>
		<?php echo $vik->closeTableHead(); ?>
		<?php
		$kk = 0;
		for( $i = 0, $n = count($rows); $i < $n; $i++ ) {
			$row = $rows[$i];

			?>
			<tr class="row<?php echo $kk; ?>">
				<td>
					<strong><?php echo $row['id']."-".$row['sid']; ?></strong>
					
					<br />
					<?php if( empty($row['code_icon']) ) {
						echo (!empty($row['code']) ? $row['code'] : '');
					} else { ?>
						<img src="<?php echo JUri::root().'components'.DIRECTORY_SEPARATOR.'com_cleverdine'.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.$row['code_icon']; ?>" title="<?php echo $row['code']; ?>" style="max-width:32px;margin:4px 0 0 4px;"/>
					<?php } ?>
				</td>

				<td style="text-align: center;">
					<strong><?php echo date($date_format.' '.$time_format, $row['checkin_ts']); ?></strong>
					<br />
					<?php echo $row['people'].' '.JText::_(($row['people'] > 1 ? 'VRORDERPEOPLE' : 'VRPERSON')); ?>
				</td>

				<td style="text-align: center;">
					<?php echo $row['room_name'].' - '.$row['table_name']; ?>
				</td>

				<td style="text-align: center;">
					<strong><?php echo $row['purchaser_nominative']; ?></strong>
					<br /><?php echo $row['purchaser_mail']; ?>
					<br /><?php echo $row['purchaser_prefix'].' '.$row['purchaser_phone']; ?>
				</td>

				<td style="text-align: center;">
					<?php echo cleverdine::printPriceCurrencySymb($row['bill_value'], $curr_symb, $symb_pos, true); ?>
				</td>

				<td style="text-align: center;">
					<span class="<?php echo 'vrreservationstatus'.strtolower($row['status']); ?>"><?php echo JText::_('VRRESERVATIONSTATUS'.$row['status']); ?></span>
					<br />
					<?php if( $row['status'] == 'PENDING' ) {
						echo JText::sprintf('VRTKRESEXPIRESIN', cleverdine::formatTimestamp($date_format." ".$time_format, $row['locked_until']));
					} ?>
				</td>

			</tr>
			<?php
			$kk = 1 - $kk;
		}		
		?>
	</table>
	<?php } ?>
	
	<input type="hidden" name="task" value="restbusyres"/>
	<input type="hidden" name="option" value="com_cleverdine"/>
	<input type="hidden" name="date" value="<?php echo $this->filters['date']; ?>"/>
	<input type="hidden" name="time" value="<?php echo $this->filters['time']; ?>"/>
	
</form>

<script type="text/javascript">
	
	jQuery(document).ready(function(){

		jQuery('#vr-interval-select').select2({
			minimumResultsForSearch: -1,
			allowClear: false,
			width: 200
		});

		jQuery('#vr-interval-select').on('change', function(){
			document.adminForm.submit();
		});

		jQuery('#adminForm .hasTooltip').tooltip();

	});

</script>

	