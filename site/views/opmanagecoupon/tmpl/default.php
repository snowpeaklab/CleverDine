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
$curr_symb = cleverdine::getCurrencySymb();

// jQuery datepicker
$vik = new VikApplication();
$vik->attachDatepickerRegional();

$itemid = JFactory::getApplication()->input->get('Itemid', 0, 'uint');

$this->coupon['datevalid'] = explode("-", $this->coupon['datevalid']);

$config = UIFactory::getConfig();

?> 

<form name="managecouponform" action="index.php" method="post" enctype="multipart/form-data" id="vrmanageform">
	
	<div class="vrfront-manage-headerdiv">
		<div class="vrfront-manage-titlediv">
			<h2><?php echo JText::_($this->coupon['id'] == -1 ? 'VROPCREATECOUPON' : 'VROPUPDATECOUPON'); ?></h2>
		</div>
		
		<div class="vrfront-manage-actionsdiv">
			
			<div class="vrfront-manage-btn">
				<button type="button" onClick="vrSaveCoupon(0);" id="vrfront-manage-btnsave" class="vrfront-manage-button"><?php echo JText::_('VRSAVE'); ?></button>
			</div>
			<div class="vrfront-manage-btn">
				<button type="button" onClick="vrSaveCoupon(1);" id="vrfront-manage-btnsaveclose" class="vrfront-manage-button"><?php echo JText::_('VRSAVEANDCLOSE'); ?></button>
			</div>
			
			<div class="vrfront-manage-btn">
				<button type="button" onClick="vrCloseCoupon();" id="vrfront-manage-btnclose" class="vrfront-manage-button"><?php echo JText::_('VRCLOSE'); ?></button>
			</div>
		</div>
	</div> 
	
	<table class="vrfront-manage-form">
		
		<tr>
			<td width="200">&bull; <b><?php echo JText::_('VRMANAGECOUPON1');?></b> </td>
			<td><input type="text" name="code" size="20" value="<?php echo $this->coupon['code']; ?>"/></td>
		</tr>
		
		<tr>
			<td width="200">&bull; <b><?php echo JText::_('VRMANAGECOUPON2');?></b> </td>
			<td>
				<div class="vre-tinyselect-wrapper">
					<select name="type" class="vre-tinyselect">
						<option value="1" <?php echo ($this->coupon['type'] == 1 ? 'selected="selected"' : ''); ?>><?php echo JText::_('VRCOUPONTYPEOPT1'); ?></option>
						<option value="2" <?php echo ($this->coupon['type'] == 2 ? 'selected="selected"' : ''); ?>><?php echo JText::_('VRCOUPONTYPEOPT2'); ?></option>
					</select>
				</div>
			</td>
		</tr>
		
		<tr>
			<td width="200">&bull; <b><?php echo JText::_('VRMANAGECOUPON3');?></b> </td>
			<td>
				<div class="vre-tinyselect-wrapper">
					<select name="percentot" class="vre-tinyselect">
						<option value="1" <?php echo ($this->coupon['percentot'] == 1 ? 'selected="selected"' : ''); ?>>%</option>
						<option value="2" <?php echo ($this->coupon['percentot'] == 2 ? 'selected="selected"' : ''); ?>><?php echo $curr_symb; ?></option>
					</select>
				</div>
			</td>
		</tr>
		
		<tr>
			<td width="200">&bull; <b><?php echo JText::_('VRMANAGECOUPON4');?></b> </td>
			<td><input type="number" name="value" size="20" value="<?php echo $this->coupon['value']; ?>" min="0"/></td>
		</tr>
		
		<tr>
			<td width="200">&bull; <b><?php echo JText::_('VRMANAGECOUPON5');?></b> </td>
			<td><input type="text" name="datestart" id="vrdatestart" size="20" value="<?php echo (count($this->coupon['datevalid']) == 2 ? date($date_format, $this->coupon['datevalid'][0]) : ''); ?>"/></td>
		</tr>
		
		<tr>
			<td width="200">&bull; <b><?php echo JText::_('VRMANAGECOUPON6');?></b> </td>
			<td><input type="text" name="datestop" id="vrdatestop" size="20" value="<?php echo (count($this->coupon['datevalid']) == 2 ? date($date_format, $this->coupon['datevalid'][1]) : ''); ?>"/></td>
		</tr>
		
		<tr>
			<td width="200">&bull; <b><?php echo JText::_('VRMANAGECOUPON7');?></b> </td>
			<td><input type="number" name="minvalue" size="20" value="<?php echo $this->coupon['minvalue']; ?>" min="0"/></td>
		</tr>

		<?php if( $this->operator['group'] == 0 ) { ?>

			<tr>
				<td width="200">&bull; <b><?php echo JText::_('VRMANAGECOUPON8');?></b> </td>
				<td>
					<div class="vre-tinyselect-wrapper">
						<select name="group" class="vre-tinyselect">
							<?php if( $config->getBool('enablerestaurant') ) { ?>
								<option value="0" <?php echo ($this->coupon['group'] == 0 ? 'selected="selected"' : ''); ?>><?php echo JText::_('VRORDERRESTAURANT'); ?></option>
							<?php } ?>

							<?php if( $config->getBool('enabletakeaway') ) { ?>
								<option value="1" <?php echo ($this->coupon['group'] == 1 ? 'selected="selected"' : ''); ?>><?php echo JText::_('VRORDERTAKEAWAY'); ?></option>
							<?php } ?>
						</select>
					</div>
				</td>
			</tr>

		<?php } else { ?>

			<input type="hidden" name="group" value="<?php echo ($this->operator['group'] - 1); ?>" />

		<?php } ?>
		
	</table>
	
	<input type="hidden" name="id" value="<?php echo $this->coupon['id']; ?>"/>
	<input type="hidden" name="return" value="0" id="vrhiddenreturn" /> 
	<input type="hidden" name="task" value="saveCoupon"/>
	<input type="hidden" name="option" value="com_cleverdine"/>
	<input type="hidden" name="Itemid" value="<?php echo $itemid; ?>"/>
</form>

<script>

	function vrCloseCoupon() {
		document.location.href = '<?php echo JRoute::_("index.php?option=com_cleverdine&task=opcoupons&Itemid=$itemid"); ?>';
	}
	
	function vrSaveCoupon(close) {
		
		var validate = true;
		
		if( validate ) {
			if(close) {
				jQuery('#vrhiddenreturn').val('1');
			}
			
			document.managecouponform.submit();
		} 
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
	
		jQuery("#vrdatestart:input, #vrdatestop:input").datepicker({
			dateFormat: new Date().format,
		});
		
	});
	
</script>