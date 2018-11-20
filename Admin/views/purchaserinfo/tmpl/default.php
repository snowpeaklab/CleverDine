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

$go_back = $this->go_back;

$date_format = cleverdine::getDateFormat(true);
$time_format = cleverdine::getTimeFormat(true);

$_customf = json_decode($this->order['custom_f']);

$curr_symb = cleverdine::getCurrencySymb(true);
$symb_pos = cleverdine::getCurrencySymbPosition(true);

$pay_name = "";
$charge_str = "";
if( strlen( $this->order['payment_name'] ) > 0 ) {
	$pay_name = $this->order['payment_name']." : ".cleverdine::printPriceCurrencySymb($this->order['deposit'], $curr_symb, $symb_pos);
}

$oid_tooltip = '';
if( $this->order['created_on'] != -1 ) {
	$created_by = '';
	if( $this->order['created_by'] != -1 ) {
		$created_by = $this->order['createdby_name'];
	} else {
		$created_by = JText::_('VRMANAGERESERVATION23');
	}
	$oid_tooltip = JText::sprintf('VAPRESLISTCREATEDTIP', date($date_format.' '.$time_format, $this->order['created_on']), $created_by);
}

$has_cc_details = strlen($this->order['cc_details']);

$vik = new VikApplication(VersionListener::getID());

?>

<div class="span7">
	<?php echo $vik->openEmptyFieldset(); ?>

		<?php if( !empty($oid_tooltip) ) { ?>
			<div class="vrtk-orderbasket-badge large"><?php echo $oid_tooltip; ?></div>
		<?php } ?>
	
		<div class="vrtk-orderbasket-badge"><?php echo date($date_format, $this->order['checkin_ts'])." @ ".date($time_format, $this->order['checkin_ts']); ?></div>
		<div class="vrtk-orderbasket-badge"><?php echo $this->order['people']." ".JText::_('VRORDERPEOPLE'); ?></div>
		
		<?php if( strlen( $pay_name ) > 0 ) { ?>
			<div class="vrtk-orderbasket-badge <?php echo ($has_cc_details ? 'large' : ''); ?>">
				<?php echo $pay_name; ?>

				<?php if( $has_cc_details ) { ?>
					<br />

					<small>
						<a href="index.php?option=com_cleverdine&task=ccdetails&tmpl=component&tid=0&oid=<?php echo $this->order['id']; ?>&back=1">
							<i class="fa fa-credit-card-alt"></i>&nbsp;&nbsp;<?php echo JText::_('VRSEECCDETAILS'); ?>
						</a>
					</small>
				<?php } ?>

			</div>
		<?php } ?>
		
		<div class="vrtk-orderbasket-badge <?php echo (strlen($pay_name) == 0 || $has_cc_details ? 'large' : ''); ?>">
			<?php echo JText::_('VRMANAGERESERVATION10')." : ".cleverdine::printPriceCurrencySymb($this->order['bill_value'], $curr_symb, $symb_pos); ?>
		</div>
		
		<div class="vrtk-orderbasket-badge large <?php echo strtolower($this->order['status']); ?>">
			<?php echo JText::_('VRRESERVATIONSTATUS'.$this->order['status']); ?>
		</div>
		
		<?php if( strlen( $this->order['coupon_str'] ) > 0 ) {
			$coupon = explode( ';;', $this->order['coupon_str'] ); ?>
			<div class="vrtk-orderbasket-badge large">
				<?php echo $coupon[0] . ' : '.($coupon[2] == 1 ? $coupon[1].'%' : cleverdine::printPriceCurrencySymb($coupon[1], $curr_symb, $symb_pos) ); ?>
			</div>
		<?php } ?>
		
	<?php echo $vik->closeEmptyFieldset(); ?>
</div>

<div class="span5">
	<?php echo $vik->openEmptyFieldset(); ?>
	
		<?php foreach( $_customf as $key => $val ) { ?>
			<?php if( !empty( $val ) ) { ?>
				<?php echo $vik->openControl(JText::_($key).":"); ?>
					<input type="text" value="<?php echo $val; ?>" readonly size="32"/>
				<?php echo $vik->closeControl(); ?>
			<?php } ?>
		<?php } ?>
	
	<?php echo $vik->closeEmptyFieldset(); ?>
</div>

<?php if( count($this->order['menus_list']) ) { ?>
	<div class="span10">
		<?php echo $vik->openEmptyFieldset(); ?>
			<?php foreach( $this->order['menus_list'] as $menu ) { ?>
				<div class="vr-order-menusinfo">
					<div><?php echo $menu['name']; ?></div>
					<div>x<?php echo $menu['quantity']; ?></div>
				</div>
			<?php } ?>
		<?php echo $vik->closeEmptyFieldset(); ?>
	</div>
<?php } ?>

<?php if( strlen( $this->order['notes'] ) > 0 ) { ?>
	<div class="span10">
		<?php echo $vik->openEmptyFieldset(); ?>
			<div class="control-group">
				<div class="vrtk-orderbasket-genericnotes"><?php echo $this->order['notes']; ?></div>
			</div>
		<?php echo $vik->closeEmptyFieldset(); ?>
	</div>      
<?php } ?> 

<?php if( strlen( $go_back ) > 0 ) {
	?><a href="<?php echo urldecode($go_back); ?>">Back</a><?php
} ?>
	