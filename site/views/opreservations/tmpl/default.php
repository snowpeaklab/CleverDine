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
$time_format = cleverdine::getTimeFormat();
$curr_symb = cleverdine::getCurrencySymb();
$symb_pos = cleverdine::getCurrencySymbPosition();

// jQuery datepicker
$vik = new VikApplication();
$vik->attachDatepickerRegional();

?>

<div class="vrfront-manage-titlediv">
	<h2><?php echo JText::_('VROVERSIGHTMENUITEM3'); ?></h2>
	<?php echo cleverdine::getToolbarLiveMap($operator); ?>
</div>

<div class="vrfront-list-wrapper">

	<form action="<?php echo JRoute::_('index.php?option=com_cleverdine&task=opreservations&Itemid=$itemid'); ?>" method="POST" name="opreservationsform">

		<div class="vrfront-manage-headerdiv">
			
			<div class="vrfront-manage-actionsdiv">
				
				<div class="vrfront-manage-btn">
					<input type="text" name="keysearch" value="<?php echo $this->keySearch; ?>" size="32" placeholder="<?php echo JText::_('VROPRESKEYFILTER'); ?>"/>
				</div>
				<div class="vrfront-manage-btn">
					<input type="text" name="datefilter" id="vrdatefilter" class="vr-calendar-icon" size="20" value="<?php echo $this->dateFilter; ?>" placeholder="<?php echo JText::_('VROPRESDATEFILTER'); ?>"/>
				</div>
				
				<div class="vrfront-manage-btn move-right">
					<button type="submit" onclick="return clearLimitStart();" id="vrfront-manage-btnfilter" class="vrfront-manage-button"><?php echo JText::_('VRMAPSSUBMITSEARCH'); ?></button>
				</div>
				
			</div>
			
		</div> 

		<div class="vr-allorders-list">
			<?php 
			$kk = 1;
			foreach( $this->reservations as $row ) { ?>
				<div class="vr-allorders-singlerow vr-allorders-row<?php echo $kk; ?>">
					<span class="vr-allorders-column" style="width: 20%;text-align: center;">
						<a href="<?php echo JRoute::_('index.php?option=com_cleverdine&from=reservations&task=editres&cid[]='.$row['id']."&Itemid=$itemid"); ?>">
							<?php echo $row['id']."-".$row['sid']; ?>
						</a>
					</span>
					<span class="vr-allorders-column" style="width: 15%;text-align: center;">
						<?php echo date("$date_format $time_format", $row['checkin_ts']); ?>
					</span>
					<span class="vr-allorders-column" style="width: 7%;text-align: center;">
						<?php echo $row['people']." ".JText::_('VRORDERPEOPLE'); ?>
					</span>
					<span class="vr-allorders-column" style="width: 7%;text-align: center;">
						<?php echo $row['tname']; ?>
					</span>
					<span class="vr-allorders-column" style="width: 15%;text-align: center;">
						<?php echo $row['purchaser_nominative']; ?>
					</span>
					<span class="vr-allorders-column" style="width: 20%;text-align: center;">
						<?php echo (!empty($row['purchaser_phone']) ? $row['purchaser_phone'] : $row['purchaser_mail']); ?>
					</span>
					<span class="vr-allorders-column vrreservationstatus<?php echo strtolower($row['status']); ?>" style="width: 15%;">
						<?php echo strtoupper(JText::_('VRRESERVATIONSTATUS'.($row['status']))); ?>
					</span>
				</div>
			<?php } ?>
		</div>

		<?php echo JHTML::_( 'form.token' ); ?>
		<div class="vr-list-pagination"><?php echo $this->navbut; ?></div>
		<input type="hidden" name="option" value="com_cleverdine"/>
		<input type="hidden" name="view" value="opreservations"/>
	</form>

</div>

<script>

function clearLimitStart() {
	// reset the limit start to avoid that the pagination calculates wront limits
	jQuery('form[name="opreservationsform"]').append('<input type="hidden" name="limitstart" value="0"/>');

	return true;
}

jQuery(function(){
		
	var sel_format = "<?php echo $date_format; ?>";
	var df_separator = sel_format[1];

	sel_format = sel_format.replace(new RegExp("\\"+df_separator, 'g'), "");

	if( sel_format == "Ymd") {
		Date.prototype.format = "yy"+df_separator+"mm"+df_separator+"dd";
	} else if( sel_format == "mdY" ) {
		Date.prototype.format = "mm"+df_separator+"dd"+df_separator+"yy";
	} else {
		Date.prototype.format = "dd"+df_separator+"mm"+df_separator+"yy";
	}

	var today = new Date();

	jQuery("#vrdatefilter:input").datepicker({
		dateFormat: new Date().format,
	});
	
});
	
</script>