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

if( !$this->ACCESS ) {
	exit;
}

$operator = $this->operator;

$itemid = JFactory::getApplication()->input->get('Itemid', 0, 'uint');

$config = UIFactory::getConfig();

$dt_format = $config->get('dateformat').' '.$config->get('timeformat');

$curr_symb 	= $config->getString('currencysymb');
$symb_pos	= $config->getUint('symbpos');

?>

<div class="vroversighthead">
	<h2><?php echo JText::sprintf('VRLOGINOPERATORHI', $operator['firstname']); ?></h2>
	<?php echo cleverdine::getToolbarLiveMap($operator); ?>
</div>

<form name="oversightform" action="<?php echo JRoute::_('index.php?option=com_cleverdine'); ?>" method="post" enctype="multipart/form-data" id="vroversightform">

	<div class="vr-dash-section">
		
		<?php
		$takeaway_active_tab = JFactory::getApplication()->getUserStateFromRequest('tkdash.tab', 'tab', 1, 'uint');
		?>
		
		<div class="vr-dashboard-box">

			<div class="vrdash-title"><i class="fa fa-shopping-basket"></i>&nbsp;<?php echo JText::_('VRMENUTAKEAWAYRESERVATIONS'); ?></div>

			<div class="vrdash-container">
				<div class="vrdash-tab-head">
					<div class="vrdash-tab-button takeaway-tab">
						<a href="javascript: void(0);" onClick="switchTakeawayDashboardTab(1, this);" class="<?php echo ($takeaway_active_tab == 1 ? 'active' : ''); ?>">
							<?php echo JText::_('VRDASHLATESTTKORDERS'); ?>
						</a>
					</div>
					<div class="vrdash-tab-button takeaway-tab">
						<a href="javascript: void(0);" onClick="switchTakeawayDashboardTab(2, this);" class="<?php echo ($takeaway_active_tab == 2 ? 'active' : ''); ?>">
							<?php echo JText::_('VRDASHINCOMINGTKORDERS'); ?>
						</a>
					</div>
					<div class="vrdash-tab-button takeaway-tab">
						<a href="javascript: void(0);" onClick="switchTakeawayDashboardTab(3, this);" class="<?php echo ($takeaway_active_tab == 3 ? 'active' : ''); ?>">
							<?php echo JText::_('VRDASHCURRENTTKORDERS'); ?>
						</a>
					</div>
				</div>

				<div class="vrdash-scrollable">
				
					<table id="vrdash-takeaway-list1" class="vr-incoming-table takeaway-list listener" style="<?php echo ($takeaway_active_tab != 1 ? 'display:none;' : ''); ?>">
						<th class="vrdashtabtitle" width="10%" style="text-align: left;"><?php echo JText::_('VRORDERNUMBER'); ?></th>
						<th class="vrdashtabtitle" width="15%" style="text-align: center;"><?php echo JText::_('VRORDERBOOKED'); ?></th>
						<th class="vrdashtabtitle" width="15%" style="text-align: center;"><?php echo JText::_('VRORDERCHECKIN'); ?></th>
						<th class="vrdashtabtitle" width="10%" style="text-align: center;"><?php echo JText::_('VRTKORDERDELIVERYSERVICE'); ?></th>
						<th class="vrdashtabtitle" width="10%" style="text-align: center;"><?php echo JText::_('VRTKORDERTOTALTOPAY'); ?></th>
						<th class="vrdashtabtitle" width="20%" style="text-align: center;"><?php echo JText::_('VRORDERCUSTOMER'); ?></th>
						<th class="vrdashtabtitle" width="10%" style="text-align: center;"><?php echo JText::_('VRORDERSTATUS'); ?></th>
						
						<?php foreach( $this->latestTkOrders as $r ) { ?>
							<tr class="<?php echo ( ( $this->isTmpl && $this->ajaxParams['from'] < $r['id'] ) ? 'vrdashrowhighlight' : ''); ?>">
								<td><?php echo $r['id']; ?> - <a href="index.php?option=com_cleverdine&task=optkprintorders&tmpl=component&cid[]=<?php echo $r['id']; ?>" target="_blank"><i class="fa fa-print"></i></a></td>
								<td style="text-align: center;"><?php echo ($r['created_on'] != -1 ? cleverdine::formatTimestamp($dt_format, $r['created_on']) : ''); ?></td>
								<td style="text-align: center;"><a href="index.php?option=com_cleverdine&task=edittkreservation&cid[]=<?php echo $r['id']; ?>" target="_blank"><?php echo date( $dt_format, $r['checkin_ts'] ); ?></a></td>
								<td style="text-align: center;"><?php echo JText::_($r['delivery_service'] ? 'VRTKORDERDELIVERYOPTION' : 'VRTKORDERPICKUPOPTION'); ?></td>
								<td style="text-align: center;"><?php echo cleverdine::printPriceCurrencySymb($r['total_to_pay'], $curr_symb, $symb_pos); ?></td>
								<td style="text-align: center;"><?php echo $r['purchaser_nominative']; ?></td>
								<td style="text-align: center;" class="<?php echo 'vrreservationstatus'.strtolower($r['status']); ?>"><?php echo JText::_('VRRESERVATIONSTATUS'.$r['status']); ?></td>
							</tr>
						<?php } ?>
					</table>
					
					<table id="vrdash-takeaway-list2" class="vr-incoming-table takeaway-list listener" style="<?php echo ($takeaway_active_tab != 2 ? 'display:none;' : ''); ?>">
						<th class="vrdashtabtitle" width="10%" style="text-align: left;"><?php echo JText::_('VRORDERNUMBER'); ?></th>
						<th class="vrdashtabtitle" width="15%" style="text-align: center;"><?php echo JText::_('VRORDERBOOKED'); ?></th>
						<th class="vrdashtabtitle" width="15%" style="text-align: center;"><?php echo JText::_('VRORDERCHECKIN'); ?></th>
						<th class="vrdashtabtitle" width="10%" style="text-align: center;"><?php echo JText::_('VRTKORDERDELIVERYSERVICE'); ?></th>
						<th class="vrdashtabtitle" width="10%" style="text-align: center;"><?php echo JText::_('VRTKORDERTOTALTOPAY'); ?></th>
						<th class="vrdashtabtitle" width="15%" style="text-align: center;"><?php echo JText::_('VRORDERCUSTOMER'); ?></th>
						<th class="vrdashtabtitle" width="10%" style="text-align: center;"><?php echo JText::_('VRORDERCODE'); ?></th>
						<th class="vrdashtabtitle" width="10%" style="text-align: center;"><?php echo JText::_('VRORDERSTATUS'); ?></th>
						
						<?php foreach( $this->incomingTkOrders as $r ) { 

							$route_obj = json_decode($r['route']);

							$route_details = '';
							$keys = array('distancetext' => 'road', 'durationtext' => 'clock-o');

							foreach( $keys as $k => $icon ) {
								if( !empty($route_obj->$k) ) {

									$route_details .= '<i class="fa fa-'.$icon.'" style="margin-right:5px;margin-left: 15px;"></i>'.$route_obj->$k;
								}
							}

							?>
							<tr class="<?php echo ( ( $this->isTmpl && $this->ajaxParams['from'] < $r['id'] ) ? 'vrdashrowhighlight' : ''); ?>">
								<td>
									<?php echo $r['id']; ?>&nbsp;-&nbsp;<a href="index.php?option=com_cleverdine&task=optkprintorders&tmpl=component&cid[]=<?php echo $r['id']; ?>" target="_blank"><i class="fa fa-print"></i></a>&nbsp;&nbsp;<a href="javascript: void(0);" onClick="vrToggleOrderDetails(this, true);"><i class="fa fa-bars"></i></a>
								</td>
								<td style="text-align: center;"><?php echo ($r['created_on'] != -1 ? cleverdine::formatTimestamp($dt_format, $r['created_on']) : ''); ?></td>
								<td style="text-align: center;"><a href="index.php?option=com_cleverdine&task=edittkreservation&cid[]=<?php echo $r['id']; ?>" target="_blank">
									<?php if( ($r['checkin_ts']-time()) < 3600 ) {
										echo cleverdine::formatTimestamp($dt_format, $r['checkin_ts']);
									} else {
										echo date($dt_format, $r['checkin_ts']);
									} ?>
								</a></td>
								<td style="text-align: center;"><?php echo JText::_($r['delivery_service'] ? 'VRTKORDERDELIVERYOPTION' : 'VRTKORDERPICKUPOPTION'); ?></td>
								<td style="text-align: center;"><?php echo cleverdine::printPriceCurrencySymb($r['total_to_pay'], $curr_symb, $symb_pos); ?></td>
								<td style="text-align: center;">
									<?php echo $r['purchaser_nominative']; ?>
								</td>
								<td style="text-align: center;">
									<?php if( empty($r['code_icon']) ) {
										echo (!empty($r['code']) ? $r['code'] : '--');
									} else { ?>
										<img src="<?php echo JUri::root().'components/com_cleverdine/assets/media/'.$r['code_icon']; ?>" title="<?php echo $r['code']; ?>" />
									<?php } ?>
								</td>
								<td style="text-align: center;" class="<?php echo 'vrreservationstatus'.strtolower($r['status']); ?>"><?php echo JText::_('VRRESERVATIONSTATUS'.$r['status']); ?></td>
							</tr>

							<tr class="vrdash-details-row" data-id="<?php echo $r['id']; ?>" style="<?php echo (in_array($r['id'], $this->ajaxParams['details_list']) ? '' : 'display: none;'); ?>">
								<td style="text-align: left;" colspan="7">
									<?php if( isset($route_obj->origin) && strlen($route_obj->origin) ) { ?>
										<span style="margin-right:5px;">
											<i class="fa fa-map-pin" style="margin-right:5px;"></i><?php echo $route_obj->origin; ?><i class="fa fa-long-arrow-right" style="margin-left:5px;"></i>
										</span>
									<?php } ?>
									<?php if( strlen($r['purchaser_address']) ) { ?>
										<span style="margin-right:5px;"><?php echo $r['purchaser_address']; ?></span>
									<?php } ?>
									<?php if( strlen($route_details) ) { ?>
										<span style="margin-right:5px;"><?php echo $route_details; ?></span>
									<?php } ?>
									<span style="margin-right: 5px;">
										<i class="fa fa-fire" style="margin-right:5px;margin-left:15px;"></i><?php echo JText::sprintf('VRTKRESITEMSINCART', $r['items_preparation_count'], $r['items_count']); ?>
									</span>
								</td>

								<td style="text-align: center;">
									<a href="javascript: void(0);" onClick="vrToggleOrderDetails(this, false);">
										<i class="fa fa-close"></i>
									</a>
								</td>
							</tr>

						<?php } ?>
					</table>

					<table id="vrdash-takeaway-list3" class="vr-incoming-table takeaway-list listener" style="<?php echo ($takeaway_active_tab != 3 ? 'display:none;' : ''); ?>">
						<th class="vrdashtabtitle" width="10%" style="text-align: left;"><?php echo JText::_('VRORDERNUMBER'); ?></th>
						<th class="vrdashtabtitle" width="15%" style="text-align: center;"><?php echo JText::_('VRORDERBOOKED'); ?></th>
						<th class="vrdashtabtitle" width="15%" style="text-align: center;"><?php echo JText::_('VRORDERCHECKIN'); ?></th>
						<th class="vrdashtabtitle" width="10%" style="text-align: center;"><?php echo JText::_('VRTKORDERDELIVERYSERVICE'); ?></th>
						<th class="vrdashtabtitle" width="10%" style="text-align: center;"><?php echo JText::_('VRTKORDERTOTALTOPAY'); ?></th>
						<th class="vrdashtabtitle" width="20%" style="text-align: center;"><?php echo JText::_('VRORDERCUSTOMER'); ?></th>
						<th class="vrdashtabtitle" width="10%" style="text-align: center;"><?php echo JText::_('VRORDERCODE'); ?></th>
						
						<?php foreach( $this->currentTkOrders as $r ) { 

							$route_obj = json_decode($r['route']);

							$route_details = '';
							$keys = array('distancetext' => 'road', 'durationtext' => 'clock-o');

							foreach( $keys as $k => $icon ) {
								if( !empty($route_obj->$k) ) {

									$route_details .= '<i class="fa fa-'.$icon.'" style="margin-right:5px;margin-left: 15px;"></i>'.$route_obj->$k;
								}
							}

							?>
							<tr class="<?php echo ( ( $this->isTmpl && $this->ajaxParams['from'] < $r['id'] ) ? 'vrdashrowhighlight' : ''); ?>">
								<td>
									<?php echo $r['id']; ?>&nbsp;-&nbsp;<a href="index.php?option=com_cleverdine&task=optkprintorders&tmpl=component&cid[]=<?php echo $r['id']; ?>" target="_blank"><i class="fa fa-print"></i></a>&nbsp;&nbsp;<a href="javascript: void(0);" onClick="vrToggleOrderDetails(this, true);"><i class="fa fa-bars"></i></a>
								</td>
								<td style="text-align: center;"><?php echo ($r['created_on'] != -1 ? cleverdine::formatTimestamp($dt_format, $r['created_on']) : ''); ?></td>
								<td style="text-align: center;"><a href="index.php?option=com_cleverdine&task=edittkreservation&cid[]=<?php echo $r['id']; ?>" target="_blank">
									<?php if( ($r['checkin_ts']-time()) < 3600 ) {
										echo cleverdine::formatTimestamp($dt_format, $r['checkin_ts']);
									} else {
										echo date($dt_format, $r['checkin_ts']);
									} ?>
								</a></td>
								<td style="text-align: center;"><?php echo JText::_($r['delivery_service'] ? 'VRTKORDERDELIVERYOPTION' : 'VRTKORDERPICKUPOPTION'); ?></td>
								<td style="text-align: center;"><?php echo cleverdine::printPriceCurrencySymb($r['total_to_pay'], $curr_symb, $symb_pos); ?></td>
								<td style="text-align: center;">
									<?php echo $r['purchaser_nominative']; ?>
								</td>
								<td style="text-align: center;">
									<?php if( empty($r['code_icon']) ) {
										echo (!empty($r['code']) ? $r['code'] : '--');
									} else { ?>
										<img src="<?php echo JUri::root().'components/com_cleverdine/assets/media/'.$r['code_icon']; ?>" title="<?php echo $r['code']; ?>" />
									<?php } ?>
								</td>
							</tr>

							<tr class="vrdash-details-row" data-id="<?php echo $r['id']; ?>" style="<?php echo (in_array($r['id'], $this->ajaxParams['details_list']) ? '' : 'display: none;'); ?>">
								<td style="text-align: left;" colspan="6">
									<?php if( isset($route_obj->origin) && strlen($route_obj->origin) ) { ?>
										<span style="margin-right:5px;">
											<i class="fa fa-map-pin" style="margin-right:5px;"></i><?php echo $route_obj->origin; ?><i class="fa fa-long-arrow-right" style="margin-left:5px;"></i>
										</span>
									<?php } ?>
									<?php if( strlen($r['purchaser_address']) ) { ?>
										<span style="margin-right:5px;"><?php echo $r['purchaser_address']; ?></span>
									<?php } ?>
									<?php if( strlen($route_details) ) { ?>
										<span style="margin-right:5px;"><?php echo $route_details; ?></span>
									<?php } ?>
									<span style="margin-right: 5px;">
										<i class="fa fa-fire" style="margin-right:5px;margin-left:15px;"></i><?php echo JText::sprintf('VRTKRESITEMSINCART', $r['items_preparation_count'], $r['items_count']); ?>
									</span>
								</td>

								<td style="text-align: center;">
									<a href="javascript: void(0);" onClick="vrToggleOrderDetails(this, false);">
										<i class="fa fa-close"></i>
									</a>
								</td>
							</tr>

						<?php } ?>
					</table>

				</div>

			</div>
		</div>

	</div>

	<input type="hidden" name="view" value="oversight"/>
	<input type="hidden" name="option" value="com_cleverdine"/>

</form>

<script type="text/javascript">

	function switchTakeawayDashboardTab(page, elem) {
		jQuery('.vrdash-tab-button.takeaway-tab a').removeClass('active');
		jQuery(elem).addClass('active');
		
		jQuery('.vr-incoming-table.takeaway-list').hide();
		jQuery('#vrdash-takeaway-list'+page).show();
		
		jQuery.noConflict();

		var jqxhr = jQuery.ajax({
			url: '<?php echo JRoute::_("index.php?option=com_cleverdine&Itemid=$itemid&tmpl=component&task=store_tkdash_prop"); ?>',
			method: 'POST',
			data: {
				tab: page
			}
		}).done(function(resp){

		}).fail(function(resp){

		});
	}

	function vrToggleOrderDetails(link, next) {
		var row = jQuery(link).closest('tr')

		if( next ) {
			row = row.next();
		}

		row.toggle();
	}

</script>