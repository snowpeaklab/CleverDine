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
$lim0 = $this->lim0;
$navbut = $this->navbut;

$filters = $this->filters;

$ordering = $this->ordering;

$vik = new VikApplication(VersionListener::getID());

$COLUMNS_TO_ORDER = array('id', 'billing_name', 'rescount', 'ordcount');
foreach( $COLUMNS_TO_ORDER as $c ) {
	if( empty($ordering[$c]) ) {
		$ordering[$c] = 0;
	}
}

$links = array(
	OrderingManager::getLinkColumnOrder( 'customers', JText::_('VRMANAGECUSTOMER1'), 'id', $ordering['id'], 1, $filters, 'vrheadcolactive'.(($ordering['id'] == 2) ? 1 : 2) ),
	OrderingManager::getLinkColumnOrder( 'customers', JText::_('VRMANAGECUSTOMER2'), 'billing_name', $ordering['billing_name'], 1, $filters, 'vrheadcolactive'.(($ordering['billing_name'] == 2) ? 1 : 2) ),
	OrderingManager::getLinkColumnOrder( 'customers', JText::_('VRMANAGECUSTOMER18'), 'rescount', $ordering['rescount'], 1, $filters, 'vrheadcolactive'.(($ordering['rescount'] == 2) ? 1 : 2) ),
	OrderingManager::getLinkColumnOrder( 'customers', JText::_('VRMANAGECUSTOMER21'), 'ordcount', $ordering['ordcount'], 1, $filters, 'vrheadcolactive'.(($ordering['ordcount'] == 2) ? 1 : 2) ),
);

$is_restaurant_enabled 	= cleverdine::isRestaurantEnabled(true);
$is_takeaway_enabled 	= cleverdine::isTakeAwayEnabled(true);

?>

<form action="index.php?option=com_cleverdine" method="post" name="adminForm" id="adminForm">
	
	<div class="btn-toolbar vr-btn-toolbar" id="filter-bar">
		<div class="filter-search btn-group pull-left">
			<input type="text" name="keysearch" id="vrkeysearch" class="vrkeysearch" size="32" value="<?php echo $filters['keysearch']; ?>" placeholder="<?php echo JText::_('VRRESERVATIONKEYSEARCH'); ?>"/>
		</div>
		
		<div class="btn-group pull-left hidden-phone">
			<button type="submit" class="btn hasTooltip" title="" data-original-title="<?php echo JText::_('VRRESERVATIONBUTTONFILTER'); ?>">
				<i class="icon-search"></i>
			</button>
			<button type="button" class="btn hasTooltip" title="" data-original-title="<?php echo JText::_('VRCLEARFILTER'); ?>" onClick="clearFilter();">
				<i class="icon-remove"></i>
			</button>
		</div>

	</div>
	
<?php 
	if( count( $rows ) == 0 ) {
		?>
		<p><?php echo JText::_('VRNOCUSTOMER');?></p>
		<?php
	} else {
?>

	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="<?php echo $vik->getAdminTableClass(); ?>">
		<?php echo $vik->openTableHead(); ?>
			<tr>
				<th width="20">
					<?php echo $vik->getAdminToggle(count($rows)); ?>
				</th>
				<th class="<?php echo $vik->getAdminThClass('left'); ?>" width="50" style="text-align: left;"><?php echo $links[0]; ?></th>
				<th class="<?php echo $vik->getAdminThClass('left'); ?>" width="50" style="text-align: left;"><?php echo $links[1]; ?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="100" style="text-align: center;"><?php echo JText::_('VRMANAGECUSTOMER3');?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="100" style="text-align: center;"><?php echo JText::_('VRMANAGECUSTOMER4');?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="100" style="text-align: center;"><?php echo JText::_('VRMANAGECUSTOMER5');?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="100" style="text-align: center;"><?php echo JText::_('VRMANAGECUSTOMER7');?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="100" style="text-align: center;"><?php echo JText::_('VRMANAGECUSTOMER10');?></th>
				<?php if( $is_restaurant_enabled ) { ?>
					<th class="<?php echo $vik->getAdminThClass(); ?>" width="100" style="text-align: center;"><?php echo $links[2]; ?></th>
				<?php } ?>
				<?php if( $is_takeaway_enabled ) { ?>
					<th class="<?php echo $vik->getAdminThClass(); ?>" width="100" style="text-align: center;"><?php echo $links[3]; ?></th>
				<?php } ?>
				<?php if( $this->is_sms ) { ?>
					<th class="<?php echo $vik->getAdminThClass(); ?>" width="50" style="text-align: center;"><?php echo JText::_('VRSENDSMS');?></th>
				<?php } ?>
			</tr>
		<?php echo $vik->closeTableHead(); ?>
		<?php
		$kk = 0;
		for( $i = 0; $i < count($rows); $i++ ) {
			$row = $rows[$i];
			
			?>
			<tr class="row<?php echo $kk; ?>">
				<td><input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row['id']; ?>" onClick="<?php echo $vik->checkboxOnClick(); ?>"></td>
				<td><?php echo $row['id']; ?></td>
				<td style="text-align: left;"><a href="index.php?option=com_cleverdine&amp;task=editcustomer&amp;cid[]=<?php echo $row['id']; ?>"><?php echo $row['billing_name']; ?></a></td>
				<td style="text-align: center;"><?php echo $row['billing_mail']; ?></td>
				<td style="text-align: center;"><?php echo $row['billing_phone']; ?></td>
				<td style="text-align: center;">
					<?php if( !empty($row['country_code']) ) { ?>
						<img src="<?php echo JUri::root().'components/com_cleverdine/assets/css/flags/'.strtolower($row['country_code']).'.png'; ?>" />
					<?php } ?>
				</td>
				<td style="text-align: center;"><?php echo $row['billing_city']; ?></td>
				<td style="text-align: center;"><?php echo $row['company']; ?></td>
				<?php if( $is_restaurant_enabled ) { ?>
					<td style="text-align: center;"><?php echo ($row['jid'] != -1 ? $row['rescount'] : JText::_('VRMANAGECUSTOMER15')); ?></td>
				<?php } ?>
				<?php if( $is_takeaway_enabled ) { ?>
					<td style="text-align: center;"><?php echo ($row['jid'] != -1 ? $row['ordcount'] : JText::_('VRMANAGECUSTOMER15')); ?></td>
				<?php } ?>
				<?php if( $this->is_sms ) { ?>
					<td style="text-align: center;">
						<input type="hidden" id="vr-hidden-name-<?php echo $row['id']; ?>" value="<?php echo JText::sprintf('VRSMSDIALOGTITLE', $row['billing_name']); ?>" />
						<?php if( !empty($row['billing_phone']) ) { ?>
							<a href="javascript: void(0);" onClick="openSmsDialog(<?php echo $row['id']; ?>);">
								<i class="fa fa-comment big"></i>
							</a>
						<?php } else { ?>
							<a href="javascript: void(0);" class="disabled">
								<i class="fa fa-comment big"></i>
							</a>
						<?php } ?>
					</td>
				<?php } ?>
			</tr>
			<?php
			$kk = 1 - $kk;
		}       
		?>
	</table>
	<?php } ?>
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="task" value="customers"/>
	<?php echo JHTML::_( 'form.token' ); ?>
	<?php echo $navbut; ?>
</form>

<?php 

$sms_default_text = "";
if( $this->is_sms ) {
	$sms_default_text = cleverdine::getSmsDefaultCustomersText(true);
}

?>

<div id="dialog-confirm" title="" style="display: none;">
	<p>
		<span class="ui-icon ui-icon-comment" style="float: left; margin: 0 7px 20px 0;"></span>
		<span>
			<span><?php echo JText::_('VRSMSDIALOGMESSAGE'); ?></span>
			<div><textarea style="width: 90%;height: 120px;" id="dialog-confirm-input" maxlength="160"><?php echo $sms_default_text; ?></textarea></div>
			<div>
				<input type="checkbox" value="1" id="vr-keepmsg-asdef" />
				<label for="vr-keepmsg-asdef" style="display: inline-block;"><?php echo JText::_('VRKEEPSMSTEXTDEF'); ?></label>
			</div>
		</span>
	</p>
</div>

<script>
	function openSmsDialog(id) {
		var title = jQuery('#vr-hidden-name-'+id).val();
		jQuery('#dialog-confirm').dialog({title: title});
		
		jQuery("#dialog-confirm").dialog({
			//resizable: false,
			width: 450,
			height: 315,
			modal: true,
			buttons: {
				"<?php echo JText::_('VRSENDSMS'); ?>": function() {
					jQuery( this ).dialog( "close" );
					sendSms(id);
				},
				"<?php echo JText::_('VRCANCEL'); ?>": function() {
					jQuery( this ).dialog( "close" );
				}
			}
		});
	}    
	
	function sendSms(id) {
		
		var sms_msg = jQuery('#dialog-confirm-input').val();
		var keep_msg = (jQuery('#vr-keepmsg-asdef').is(':checked') ? 1 : 0);
		
		jQuery('#vrcustid_h').val(id);
		jQuery('#vrmsg_h').val(sms_msg);
		jQuery('#vrkeepdef_h').val(keep_msg);
		
		jQuery('#vrsmsform').submit();
	}
	
	var _LAST_SEARCH_ = '<?php echo addslashes($filters['keysearch']); ?>';
	
	function clearFilter() {
		jQuery('#vrkeysearch').val('');
		if( _LAST_SEARCH_.length > 0 ) {
			document.adminForm.submit();
		}
	}
	
</script>

<form action="index.php?option=com_cleverdine&task=sendcustsms" id="vrsmsform" method="POST">
	<input type="hidden" name="id_cust" value="" id="vrcustid_h" />
	<input type="hidden" name="msg" value="" id="vrmsg_h" />
	<input type="hidden" name="keepdef" value="" id="vrkeepdef_h" />
</form>
