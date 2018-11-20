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

$operator = $this->operator;
$user = JFactory::getUser();

$itemid = JFactory::getApplication()->input->get('Itemid', 0, 'uint');

$date_format = cleverdine::getDateFormat();
$curr_symb = cleverdine::getCurrencySymb();
$symb_pos = cleverdine::getCurrencySymbPosition();

$new_url = JRoute::_("index.php?option=com_cleverdine&task=opmanagecoupon&Itemid=$itemid");

?>

<div class="vrfront-manage-titlediv">
	<h2><?php echo JText::_('VROVERSIGHTMENUITEM4'); ?></h2>
	<?php echo cleverdine::getToolbarLiveMap($operator); ?>
</div>

<div class="vrfront-list-wrapper">

	<?php if( $operator['manage_coupon'] == 2 ) { ?>
		<div class="vrfront-manage-actionsdiv">
			<div class="vrfront-manage-btn">
				<button type="button" onClick="document.location.href='<?php echo $new_url; ?>';" id="vrfront-manage-btncreate" class="vrfront-manage-button"><?php echo JText::_('VRNEW'); ?></button>
			</div>
		</div>
	<?php } ?>

	<div class="vr-allorders-list">
		<?php 
		$kk = 1;
		foreach( $this->coupons as $row ) { 
			$date_valid = explode("-", $row['datevalid']);
			?>
			<div class="vr-allorders-singlerow vr-allorders-row<?php echo $kk; ?>">
				<span class="vr-allorders-column" style="width: 30%;text-align: center;">
					<?php if( $operator['manage_coupon'] == 2 ) { ?>
						<a href="<?php echo JRoute::_('index.php?option=com_cleverdine&task=opmanagecoupon&id='.$row['id']."&Itemid=$itemid"); ?>">
							<?php echo $row['code']; ?>
						</a>
					<?php } else { 
						echo $row['code'];
					} ?>

					<?php if( $operator['group'] == 0 ) { ?>
						<i class="fa fa-<?php echo ($row['group'] == 0 ? 'cutlery' : 'shopping-basket'); ?> vr-icon-idgroup"></i>
					<?php } ?>

				</span>
				<span class="vr-allorders-column" style="width: 20%;text-align: center;">
					<?php echo JText::_('VRCOUPONTYPEOPT'.$row['type']); ?>
				</span>
				<span class="vr-allorders-column" style="width: 19%;text-align: center;">
					<?php echo ($row['percentot'] == 1 ? $row['value']."%" : cleverdine::printPriceCurrencySymb($row['value'], $curr_symb, $symb_pos)); ?>
				</span>
				<span class="vr-allorders-column" style="width: 30%;text-align: center;">
					<?php if( count($date_valid) == 2 ) {
						echo date($date_format, $date_valid[0])." - ".date($date_format, $date_valid[1]);
					} else {
						echo "--";
					} ?>
				</span>
			</div>
		<?php } ?>
	</div>

</div>