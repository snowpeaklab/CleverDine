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

$date_format = cleverdine::getDateFormat();
$time_format = cleverdine::getTimeFormat();

$now = time();

?>

<?php if( $this->orderDetails === null || $this->orderDetails['status'] != 'CONFIRMED' ) { ?>

	<!-- order not found or not confirmed yet -->

	<div class="vr-confirmpage order-error"><?php echo JText::_('VRCONFORDNOROWS'); ?></div>

<?php } else { ?>

	<!-- order found and confirmed -->

	<?php if( $this->statusList === null ) { ?>

		<!-- no order status -->

		<div class="vr-confirmpage"><?php echo JText::_('VRTRACKORDERNOSTATUS'); ?></div>

	<?php } else { ?>

		<!-- display list of statuses -->

		<div class="vr-trackorder-wrapper">

			<?php foreach( $this->statusList as $day => $list ) { ?>

				<div class="vr-trackorder-day">

					<div class="vr-trackorder-day-head"><?php echo date($date_format, $day); ?></div>

					<div class="vr-trackorder-day-list">

						<?php foreach( $list as $status ) { ?>

							<div class="vr-trackorder-status">

								<span class="vr-trackorder-status-time"><?php echo date($time_format, $status['createdon']); ?></span>

								<span class="vr-trackorder-status-details">
									<?php echo (strlen($status['notes']) ? $status['notes'] : $status['code_notes']); ?>
								</span>

								<?php if( $now - $status['createdon'] < 7200 ) { ?>
									<span class="vr-trackorder-status-ago">(<?php echo cleverdine::formatTimestamp($date_format.' '.$time_format, $status['createdon']); ?>)</span>
								<?php } ?> 

							</div>

						<?php } ?>

					</div>

				</div>

			<?php } ?>

		</div>

	<?php } ?>

<?php } ?>