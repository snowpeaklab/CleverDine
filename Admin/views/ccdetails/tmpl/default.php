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

$credit_card = $this->creditCard;

$order = $this->order;

$vik = new VikApplication(VersionListener::getID());

$dt_format = cleverdine::getDateFormat(true).' @ '.cleverdine::getTimeFormat(true);

?>

<div class="btn-toolbar vr-btn-toolbar">

	<div class="btn-group pull-left vr-toolbar-setfont">
		<strong><?php echo JText::sprintf('VRCREDITCARDAUTODELMSG', date($dt_format, $order['checkin_ts']+86400)); ?></strong>
	</div>

	<div class="btn-group pull-right">
		<button type="button" class="btn btn-danger" onclick="confirmCreditCardDelete();">
			<?php echo JText::_('VRDELETE'); ?>
		</button>
	</div>

	<?php if( !empty($this->back) ) { ?>

		<div class="btn-group pull-right">
			<a href="index.php?option=com_cleverdine&tmpl=component&task=<?php echo ($this->tid ? 'tk' : ''); ?>purchaserinfo&oid=<?php echo $this->oid; ?>" class="btn">
				<?php echo JText::_('VRCANCEL'); ?>
			</a>
		</div>

	<?php } ?>

</div>

<div class="span6">
	<?php echo $vik->openEmptyFieldset(); ?>

		<?php foreach( $credit_card as $k => $v ) { ?>

			<?php echo $vik->openControl($v->label.':'); ?>
				<input type="text" value="<?php echo $v->value; ?>" readonly size="32"/>
				<?php if( $k == 'cardNumber' ) { ?>
					<img src="<?php echo JUri::root().'administrator/components/com_cleverdine/payments/off-cc/resources/icons/'.$credit_card->brand->alias.'.png'; ?>" />
				<?php } ?>
			<?php echo $vik->closeControl(); ?>

		<?php } ?>

	<?php echo $vik->closeEmptyFieldset(); ?>
</div>

<script type="text/javascript">

	function confirmCreditCardDelete() {

		if( confirm('<?php echo addslashes(JText::_('VRSYSTEMCONFIRMATIONMSG')); ?>') ) {
			document.location.href = 'index.php?option=com_cleverdine&task=ccdetails&tmpl=component&oid=<?php echo $this->oid; ?>&tid=<?php echo $this->tid; ?>&rmhash=<?php echo $this->rmHash; ?>';
		}

	}

</script>
	