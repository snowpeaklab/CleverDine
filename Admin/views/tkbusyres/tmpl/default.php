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
	<p><?php echo JText::_('VRNOTKRESERVATION');?></p>
<?php } else { ?>
	
	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="<?php echo $vik->getAdminTableClass(); ?>">
		<?php echo $vik->openTableHead(); ?>
			<tr>
				<th class="<?php echo $vik->getAdminThClass('left'); ?>" width="10%" style="text-align: left;"><?php echo JText::_('VRMANAGETKRES1'); ?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="15%" style="text-align: center;"><?php echo JText::_('VRMANAGETKRES3'); ?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="15%" style="text-align: center;"><?php echo JText::_('VRMANAGETKRES13'); ?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="15%" style="text-align: center;"><?php echo JText::_('VRMANAGETKRES24'); ?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="5%" style="text-align: center;"><?php echo JText::_('VRMANAGETKRES22'); ?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="5%" style="text-align: center;"><?php echo JText::_('VRMANAGETKRES8'); ?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="10%" style="text-align: center;"><?php echo JText::_('VRMANAGETKRES9'); ?></th>
			</tr>
		<?php echo $vik->closeTableHead(); ?>
		<?php
		$kk = 0;
		for( $i = 0, $n = count($rows); $i < $n; $i++ ) {
			$row = $rows[$i];

			$route_obj = json_decode($row['route']);

			$route_details = '';
			$keys = array('distancetext' => 'road', 'durationtext' => 'clock-o');

			foreach( $keys as $k => $icon ) {
				if( !empty($route_obj->$k) ) {
					$marginleft = 0;
					if( strlen($route_details) ) {
						$marginleft = 15;
					}

					$route_details .= '<i class="fa fa-'.$icon.'" style="margin-right:5px;margin-left:'.$marginleft.'px;"></i>'.$route_obj->$k;
				}
			}

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
					<?php if( $row['checkin_ts'] > time() && $row['delivery_service'] && isset($route_obj->duration) && $route_obj->duration > 0 ) {
						echo JText::sprintf('VRMANAGETKRES34', date($time_format, $row['checkin_ts']-$route_obj->duration));
					} ?>
				</td>

				<td style="text-align: center;">
					<i class="fa fa-<?php echo ($row['delivery_service'] ? 'motorcycle' : 'hand-rock-o'); ?>" style="margin-right: 2px;"></i>
					<strong><?php echo JText::_('VRMANAGETKRES'.($row['delivery_service'] ? '14' : '15')); ?></strong>

					<br /><?php echo $route_details; ?>

					<br /><?php echo (isset($route_obj->origin) ? $route_obj->origin : ''); ?>

				</td>

				<td style="text-align: center;">
					<strong><?php echo $row['purchaser_nominative']; ?></strong>
					<br /><?php echo $row['purchaser_mail']; ?>
					<br /><?php echo $row['purchaser_address']; ?>
				</td>

				<td style="text-align: center;">
					<div class="hasTooltip" title="<?php echo JText::sprintf('VRTKRESITEMSINCART', $row['items_preparation_count'], $row['items_count']); ?>">
						<?php if( $row['items_preparation_count'] > 0 ) { ?>
							<span style="display: inline-block;text-align: right;width: 48%;"><i class="fa fa-fire"></i></span>
							<span style="display: inline-block;text-align: left;width: 48%;"><?php echo $row['items_preparation_count']; ?></span>
							<br />
						<?php } ?>
						
						<?php if( $row['items_count']-$row['items_preparation_count'] > 0 ) { ?>
							<span style="display: inline-block;text-align: right;width: 48%;"><i class="fa fa-beer"></i></span>
							<span style="display: inline-block;text-align: left;width: 48%;"><?php echo ($row['items_count']-$row['items_preparation_count']); ?></span>
						<?php } ?>
					</div>
				</td>

				<td style="text-align: center;">
					<?php echo cleverdine::printPriceCurrencySymb($row['total_to_pay'], $curr_symb, $symb_pos, true); ?>
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
	
	<input type="hidden" name="task" value="tkbusyres"/>
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

	