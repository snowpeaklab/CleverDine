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

$type = $this->type;
$rows = $this->rows;

$date_format = cleverdine::getDateFormat(true);
$time_format = cleverdine::getTimeFormat(true);

$curr_symb = cleverdine::getCurrencySymb(true);
$symb_pos = cleverdine::getCurrencySymbPosition(true);

// load site language to include all tags about payment and prices (taxes, delivery cost, etc...)
cleverdine::loadLanguage(cleverdine::getDefaultLanguage('site'));

?>

<div class="vr-printer-layout">

	<?php if( strlen($this->text['header']) ) { ?>
		<div class="vr-printer-header"><?php echo $this->text['header']; ?></div>
	<?php } ?>

	<?php foreach( $rows as $i => $r ) {
		
		$fields = json_decode($r['custom_f'], true);

		$has_custom_fields = false;
		foreach( $fields as $k => $v ) {
			$has_custom_fields = $has_custom_fields || strlen($v);
		}
		 
		if( $type == 1 ) {
			
			if( $i > 0 ) {
				?><div class="vr-dashed-vr-dashed-separator"></div><?php
			} ?>

			<!-- HEAD -->
			<div class="tk-print-box">
				<div class="tk-field">
					<span class="tk-label"><?php echo JText::_('VRORDERNUMBER').':'; ?></span>
					<span class="tk-value"><?php echo $r['id']." - ".$r['sid']; ?></span>
				</div>
				<div class="tk-field">
					<span class="tk-label"><?php echo JText::_('VRORDERSTATUS').':'; ?></span>
					<span class="tk-value order-<?php echo strtolower($r['status']); ?>"><?php echo strtoupper(JText::_('VRRESERVATIONSTATUS'.$r['status'])); ?></span>
				</div>
				<div class="tk-field">
					<span class="tk-label"><?php echo JText::_('VRORDERDATETIME').':'; ?></span>
					<span class="tk-value"><?php echo date($date_format." ".$time_format, $r['checkin_ts']); ?></span>
				</div>
				<div class="tk-field">
					<span class="tk-label"><?php echo JText::_('VRORDERPEOPLE').':'; ?></span>
					<span class="tk-value"><?php echo $r['people']; ?></span>
				</div>
				<div class="tk-field">
					<span class="tk-label"><?php echo JText::_('VRORDERTABLE').':'; ?></span>
					<span class="tk-value"><?php echo $r['room_name'].' - '.$r['table_name']; ?></span>
				</div>
				<?php if( !empty($r['payment_name']) ) { ?>
					<div class="tk-field">
						<span class="tk-label"><?php echo JText::_('VRORDERPAYMENT').':'; ?></span>
						<span class="tk-value"><?php echo $r['payment_name']; ?></span>
					</div>
				<?php } ?>
				<?php if( $r['deposit'] > 0 ) { ?>
					<div class="tk-field">
						<span class="tk-label"><?php echo JText::_('VRMANAGERESERVATION9').':'; ?></span>
						<span class="tk-value">
							<?php echo cleverdine::printPriceCurrencySymb($r['deposit'], $curr_symb, $symb_pos, true); ?>
						</span>
					</div>
				<?php } ?>
				<?php if( !empty($r['coupon_str']) ) { 
					list($code, $pt, $value) = explode(';;', $r['coupon_str']);
					?>
					<div class="tk-field">
						<span class="tk-label"><?php echo JText::_('VRORDERCOUPON').':'; ?></span>
						<span class="tk-value"><?php echo $code." : ".($pt == 1 ? $value.'%' : cleverdine::printPriceCurrencySymb($value, $curr_symb, $symb_pos, true)); ?></span>
					</div>
				<?php } ?>
			</div>

			<!-- CUSTOMER DETAILS -->
			<?php if( $has_custom_fields ) { ?>
				<div class="tk-print-box">
					<?php foreach( $fields as $k => $v ) { 
						if( strlen($v) ) { ?>
							<div class="tk-field">
								<span class="tk-label"><?php echo JText::_($k).':'; ?></span>
								<span class="tk-value"><?php echo $v ?></span>
							</div>
						<?php } ?>
					<?php } ?>
				</div>
			<?php } ?>

			<!-- ITEMS -->
			<?php if( count($r['items']) ) { ?>
				<div class="tk-print-box">
					<?php foreach( $r['items'] as $item ) { ?>
						<div class="tk-item">
							<div class="tk-details">
								<span class="name"><?php echo $item['name']; ?></span>
								<span class="quantity">x<?php echo $item['quantity']; ?></span>
								<span class="price"><?php echo cleverdine::printPriceCurrencySymb($item['price'], $curr_symb, $symb_pos, true); ?></span>
							</div>

							<?php if( strlen($item['notes']) ) { ?>
								<div class="tk-notes"><?php echo $item['notes']; ?></div>
							<?php } ?>
						</div>
					<?php } ?>
				</div>
			<?php } ?>

			<!-- ORDER TOTAL -->
			<?php if( $r['bill_value'] > 0 ) { ?>
				<div class="tk-print-box">
					<div class="tk-total-row">
						<span class="tk-label"><?php echo JText::_('VRTKCARTTOTALPRICE'); ?></span>
						<span class="tk-amount"><?php echo cleverdine::printPriceCurrencySymb($r['bill_value'], $curr_symb, $symb_pos, true); ?></span>
					</div>
				</div>
			<?php } ?>
			
		<?php } else { ?>
			
			<?php if( $i > 0 ) {
				?><div class="vr-dashed-separator"></div><?php
			} ?>
			
			<!-- HEAD -->
			<div class="tk-print-box">
				<div class="tk-field">
					<span class="tk-label"><?php echo JText::_('VRORDERNUMBER').':'; ?></span>
					<span class="tk-value"><?php echo $r['id']." - ".$r['sid']; ?></span>
				</div>
				<div class="tk-field">
					<span class="tk-label"><?php echo JText::_('VRORDERSTATUS').':'; ?></span>
					<span class="tk-value order-<?php echo strtolower($r['status']); ?>"><?php echo strtoupper(JText::_('VRRESERVATIONSTATUS'.$r['status'])); ?></span>
				</div>
				<div class="tk-field">
					<span class="tk-label"><?php echo JText::_('VRORDERDATETIME').':'; ?></span>
					<span class="tk-value"><?php echo date($date_format." ".$time_format, $r['checkin_ts']); ?></span>
				</div>
				<div class="tk-field">
					<span class="tk-label"><?php echo JText::_('VRTKORDERDELIVERYSERVICE').':'; ?></span>
					<span class="tk-value"><?php echo JText::_($r['delivery_service'] ? 'VRTKORDERDELIVERYOPTION' : 'VRTKORDERPICKUPOPTION'); ?></span>
				</div>
				<?php if( !empty($r['payment_name']) ) { ?>
					<div class="tk-field">
						<span class="tk-label"><?php echo JText::_('VRORDERPAYMENT').':'; ?></span>
						<span class="tk-value"><?php echo $r['payment_name']; ?></span>
					</div>
				<?php } ?>
				<?php if( $r['total_to_pay'] > 0 ) { ?>
					<div class="tk-field">
						<span class="tk-label"><?php echo JText::_('VRTKORDERTOTALTOPAY').':'; ?></span>
						<span class="tk-value"><?php echo cleverdine::printPriceCurrencySymb($r['total_to_pay'], $curr_symb, $symb_pos, true); ?></span>
					</div>
				<?php } ?>
				<?php if( !empty($r['coupon_str']) ) { 
					list($code, $pt, $value) = explode(';;', $r['coupon_str']);
					?>
					<div class="tk-field">
						<span class="tk-label"><?php echo JText::_('VRORDERCOUPON').':'; ?></span>
						<span class="tk-value"><?php echo $code." : ".($pt == 1 ? $value.'%' : cleverdine::printPriceCurrencySymb($value, $curr_symb, $symb_pos, true)); ?></span>
					</div>
				<?php } ?>
			</div>

			<!-- CUSTOMER DETAILS -->
			<?php if( $has_custom_fields ) { ?>
				<div class="tk-print-box">
					<?php foreach( $fields as $k => $v ) { 
						if( strlen($v) ) { ?>
							<div class="tk-field">
								<span class="tk-label"><?php echo JText::_($k).':'; ?></span>
								<span class="tk-value"><?php echo $v ?></span>
							</div>
						<?php } ?>
					<?php } ?>
				</div>
			<?php } ?>

			<!-- CART -->
			<div class="tk-print-box">
				<?php foreach( $r['items'] as $item ) { ?>
					<div class="tk-item">
						<div class="tk-details">
							<span class="name"><?php echo $item['name'].(!empty($item['option_name']) ? ' - '.$item['option_name'] : ''); ?></span>
							<span class="quantity">x<?php echo $item['quantity']; ?></span>
							<span class="price"><?php echo cleverdine::printPriceCurrencySymb($item['price'], $curr_symb, $symb_pos, true); ?></span>
						</div>

						<?php if( count($item['toppings_groups']) ) { ?>
							<div class="tk-toppings-cont">
								<?php foreach( $item['toppings_groups'] as $group ) { ?>
									<div class="tk-toppings-group">
										<span class="title"><?php echo $group['title']; ?>:&nbsp;</span>
										<span class="toppings">
											<?php foreach( $group['toppings'] as $k => $topping ) {
												echo ($k > 0 ? ', ' : '').$topping['name'];
											} ?>
										</span>
									</div>
								<?php } ?>
							</div>
						<?php } ?>

						<?php if( strlen($item['notes']) ) { ?>
							<div class="tk-notes"><?php echo $item['notes']; ?></div>
						<?php } ?>
					</div>
				<?php } ?>
			</div>

			<!-- ORDER TOTAL -->
			<?php
			$net = $r['total_to_pay']-$r['taxes']-$r['pay_charge']-$r['delivery_charge'];
			?>
			<div class="tk-print-box">
				<div class="tk-total-row">
					<span class="tk-label"><?php echo JText::_('VRTKCARTTOTALNET'); ?></span>
					<span class="tk-amount"><?php echo cleverdine::printPriceCurrencySymb($net, $curr_symb, $symb_pos, true); ?></span>
				</div>
			
				<?php if( $r['delivery_charge'] != 0 ) { ?>
					<div class="tk-total-row">
						<span class="tk-label"><?php echo JText::_('VRTKCARTTOTALSERVICE'); ?></span>
						<span class="tk-amount"><?php echo cleverdine::printPriceCurrencySymb($r['delivery_charge'], $curr_symb, $symb_pos, true); ?></span>
					</div>
				<?php } ?>
			
				<?php if( $r['pay_charge'] != 0 ) { ?>
					<div class="tk-total-row">
						<span class="tk-label"><?php echo JText::_('VRTKCARTTOTALPAYCHARGE'); ?></span>
						<span class="tk-amount"><?php echo cleverdine::printPriceCurrencySymb($r['pay_charge'], $curr_symb, $symb_pos, true); ?></span>
					</div>
				<?php } ?>
			
				<?php if( $r['taxes'] != 0 ) { ?>
					<div class="tk-total-row">
						<span class="tk-label"><?php echo JText::_('VRTKCARTTOTALTAXES'); ?></span>
						<span class="tk-amount"><?php echo cleverdine::printPriceCurrencySymb($r['taxes'], $curr_symb, $symb_pos, true); ?></span>
					</div>
				<?php } ?>
			
				<?php if( $r['discount_val'] != 0 ) { ?>
					<div class="tk-total-row">
						<span class="tk-label red"><?php echo JText::_('VRTKCARTTOTALDISCOUNT'); ?></span>
						<span class="tk-amount"><?php echo cleverdine::printPriceCurrencySymb($r['discount_val'], $curr_symb, $symb_pos, true); ?></span>
					</div>
				<?php } ?>
			
				<div class="tk-total-row">
					<span class="tk-label"><?php echo JText::_('VRTKCARTTOTALPRICE'); ?></span>
					<span class="tk-amount"><?php echo cleverdine::printPriceCurrencySymb($r['total_to_pay'], $curr_symb, $symb_pos, true); ?></span>
				</div>
			</div>
			
		<?php } 
		
	} ?>

	<?php if( strlen($this->text['footer']) ) { ?>
		<div class="vr-printer-footer"><?php echo $this->text['footer']; ?></div>
	<?php } ?>

</div>

<script>
	
	jQuery(document).ready(function(){
		window.print();
	});
	
</script>	