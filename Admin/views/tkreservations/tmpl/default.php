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

JHtml::_('behavior.calendar');

$rows = $this->rows;
$lim0 = $this->lim0;
$navbut = $this->navbut;

$shifts = $this->shifts;
$all_res_codes = $this->allResCodes;
$filters = $this->filters;

$ordering = $this->ordering;

$curr_symb = cleverdine::getCurrencySymb(true);

$created_by_default = JText::_('VRMANAGERESERVATION23');

$shifts_select = "";
if( count($shifts) > 1 ) {
	$shifts_select = '<select name="shift" id="vrselectshift">';
	$shifts_select .= '<option></option>';
	foreach( $shifts as $_s ) {
		$shifts_select .= '<option '.(($filters['shift'] == intval($_s['from']/60).'-'.intval($_s['to']/60)) ? 'selected="selected"' : "").' value="'.intval($_s['from']/60).'-'.intval($_s['to']/60).'">'.($_s['showlabel'] && strlen($_s['label']) ? $_s['label'] : $_s['name']).'</option>';
	}
	$shifts_select .= '</select>'; 
}

$date_format = cleverdine::getDateFormat(true);
$time_format = cleverdine::getTimeFormat(true);

// ORDERING LINKS

$COLUMNS_TO_ORDER = array('id', 'checkin_ts', 'purchaser_mail', 'total_to_pay', 'status');
foreach( $COLUMNS_TO_ORDER as $c ) {
	if( empty($ordering[$c]) ) {
		$ordering[$c] = 0;
	}
}

$links = array(
	OrderingManager::getLinkColumnOrder( 'tkreservations', JText::_('VRMANAGETKRES1'), 'id', $ordering['id'], 1, $filters, 'vrheadcolactive'.(($ordering['id'] == 2) ? 1 : 2) ),
	OrderingManager::getLinkColumnOrder( 'tkreservations', JText::_('VRMANAGETKRES3'), 'checkin_ts', $ordering['checkin_ts'], 1, $filters, 'vrheadcolactive'.(($ordering['checkin_ts'] == 2) ? 1 : 2) ),
	OrderingManager::getLinkColumnOrder( 'tkreservations', JText::_('VRMANAGETKRES5'), 'purchaser_mail', $ordering['purchaser_mail'], 1, $filters, 'vrheadcolactive'.(($ordering['purchaser_mail'] == 2) ? 1 : 2) ),
	OrderingManager::getLinkColumnOrder( 'tkreservations', JText::_('VRMANAGETKRES8'), 'total_to_pay', $ordering['total_to_pay'], 1, $filters, 'vrheadcolactive'.(($ordering['total_to_pay'] == 2) ? 1 : 2) ),
	OrderingManager::getLinkColumnOrder( 'tkreservations', JText::_('VRMANAGETKRES9'), 'status', $ordering['status'], 1, $filters, 'vrheadcolactive'.(($ordering['status'] == 2) ? 1 : 2) ),
);

$vik = new VikApplication(VersionListener::getID());

$listable_fields = cleverdine::getTakeAwayListableFields(true);

$is_searching = false;
foreach( $filters as $k => $v ) {
	$is_searching = $is_searching || ($k != 'tools' && strlen($v));
}

?>

<form action="index.php?option=com_cleverdine" method="post" name="adminForm" id="adminForm">
	
	<div class="btn-toolbar vr-btn-toolbar" id="filter-bar">
		
		<div class="btn-group pull-left input-append">
			<input type="text" name="keysearch" id="vrkeysearch" class="vrkeysearch" size="32" value="<?php echo $filters['keysearch']; ?>" placeholder="<?php echo JText::_('VRRESERVATIONKEYSEARCH'); ?>"/>
			
			<button type="submit" class="btn hasTooltip" title="" data-original-title="<?php echo JText::_('VRRESERVATIONBUTTONFILTER'); ?>">
				<i class="icon-search"></i>
			</button>
		</div>

		<div class="btn-group pull-left">
			<button type="button" class="btn <?php echo ($filters['tools'] ? 'btn-primary' : ''); ?>" onclick="toggleSearchToolsButton(this);">
				<?php echo JText::_('VRSEARCHTOOLS'); ?>&nbsp;<i class="fa fa-caret-<?php echo ($filters['tools'] ? 'up' : 'down'); ?>" id="vr-tools-caret"></i>
			</button>
		</div>

		<div class="btn-group pull-left">
			<button type="button" class="btn" onClick="clearFilters();">
				<?php echo JText::_('VRCLEARFILTER'); ?>
			</button>
		</div>

		<?php if( cleverdine::isTakeAwayStockEnabled(true) ) { ?>
			<div class="btn-group pull-right">
				<button type="button" class="btn" onClick="document.location.href='index.php?option=com_cleverdine&task=tkstocks';">
					<i class="icon-search"></i> <?php echo JText::_('VRTKSTOCKSOVERVIEWBTN'); ?>
				</button>
				<button type="button" class="btn" onClick="document.location.href='index.php?option=com_cleverdine&task=tkstatstocks';">
					<i class="icon-bars"></i> <?php echo JText::_('VRTKSTATSTOCKSBTN'); ?>
				</button>
			</div>
		<?php } ?>

		<?php if( count($rows) == 1 && strlen($rows[0]['cc_details']) ) { ?>
			<div class="btn-group pull-right">
				<button type="button" class="btn btn-primary" onclick="SELECTED_ORDER=<?php echo $rows[0]['id']; ?>;vrOpenJModal('ccdetails', null, true); return false;">
					<i class="fa fa-credit-card-alt"></i>&nbsp;&nbsp;<?php echo JText::_('VRSEECCDETAILS'); ?>
				</button>
			</div>
		<?php } ?>
		
	</div>

	<div class="btn-toolbar vr-btn-toolbar" id="vr-search-tools" style="<?php echo ($filters['tools'] ? '' : 'display: none;'); ?>">

		<div class="btn-group pull-left input-append">
			<input type="text" name="ordnum" id="vrordnumfilter" class="vrkeysearch" size="24" value="<?php echo $filters['ordnum']; ?>" placeholder="<?php echo JText::_('VRRESERVATIONORDNUMFILTER'); ?>"/>
			<button type="submit" class="btn hasTooltip" title="" data-original-title="<?php echo JText::_('VRRESERVATIONBUTTONFILTER'); ?>">
				<i class="icon-search"></i>
			</button>
		</div>

		<div class="btn-group pull-left input-append">
			<input type="text" name="couponsearch" id="vrcouponsearch" class="vrkeysearch" size="24" value="<?php echo $filters['couponsearch']; ?>" placeholder="<?php echo JText::_('VRRESERVATIONCPNSEARCH'); ?>"/>
			<button type="submit" class="btn hasTooltip" title="" data-original-title="<?php echo JText::_('VRRESERVATIONBUTTONFILTER'); ?>">
				<i class="icon-search"></i>
			</button>
		</div>

		<?php
		$elements = array($vik->initOptionElement('', '', false));

		$status_arr = array('CONFIRMED', 'PENDING', 'REMOVED', 'CANCELLED');

		foreach( $status_arr as $s ) {
			$elements[] = $vik->initOptionElement($s, JText::_('VRRESERVATIONSTATUS'.$s), $filters['ordstatus'] == $s);
		}
		?>
		<div class="btn-group pull-left">
			<div class="vr-toolbar-setfont">
				<?php echo $vik->dropdown('ordstatus', $elements, 'vrordstatsel'); ?>
			</div>
		</div>

		<div class="btn-group pull-left vr-toolbar-setfont">
			<?php
			$attributes = array();
			$attributes['onChange'] = 'document.adminForm.submit();';
			echo $vik->calendar($filters['datefilter'], 'datefilter', 'vrdatefilter', null, $attributes);
			?>
		</div>

		<?php if( strlen( $shifts_select ) > 0 ) { ?>
			<div class="btn-group pull-left">
				<div class="vr-toolbar-setfont">
					<?php echo $shifts_select; ?>
				</div>
			</div>
		<?php } ?>

	</div>
	
<?php if( count( $rows ) == 0 ) { ?>
	<p><?php echo JText::_('VRNOTKRESERVATION');?></p>
<?php } else { ?>

	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="<?php echo $vik->getAdminTableClass(); ?>">
		<?php echo $vik->openTableHead(); ?>
			<tr>
				<th width="20">
					<?php echo $vik->getAdminToggle(count($rows)); ?>
				</th>
				<?php 
				if( in_array('id', $listable_fields) ) {
					?><th class="<?php echo $vik->getAdminThClass(); ?>" width="130" style="text-align: center;"><?php echo $links[0]; ?></th><?php
				}
				if( in_array('sid', $listable_fields) ) {
					?><th class="<?php echo $vik->getAdminThClass(); ?>" width="150" style="text-align: center;"><?php echo JText::_('VRMANAGETKRES2');?></th><?php
				}	
				if( in_array('payment', $listable_fields) ) {
					?><th class="<?php echo $vik->getAdminThClass(); ?>" width="150" style="text-align: center;"><?php echo JText::_('VRMANAGETKRES27');?></th><?php
				}   
				if( in_array('checkin_ts', $listable_fields) ) {
					?><th class="<?php echo $vik->getAdminThClass(); ?>" width="150" style="text-align: center;"><?php echo $links[1]; ?></th><?php
				}
				if( in_array('delivery', $listable_fields) ) {
					?><th class="<?php echo $vik->getAdminThClass(); ?>" width="100" style="text-align: center;"><?php echo JText::_('VRMANAGETKRES4');?></th><?php
				}
				if( in_array('customer', $listable_fields) ) {
					?><th class="<?php echo $vik->getAdminThClass(); ?>" width="150" style="text-align: center;"><?php echo JText::_('VRMANAGETKRES24');?></th><?php
				}
				if( in_array('mail', $listable_fields) ) {
					?><th class="<?php echo $vik->getAdminThClass(); ?>" width="150" style="text-align: center;"><?php echo $links[2]; ?></th><?php
				}
				if( in_array('phone', $listable_fields) ) {
					?><th class="<?php echo $vik->getAdminThClass(); ?>" width="120" style="text-align: center;"><?php echo JText::_('VRMANAGETKRES23');?></th><?php
				}
				if( in_array('info', $listable_fields) ) {
					?><th class="<?php echo $vik->getAdminThClass(); ?>" width="100" style="text-align: center;"><?php echo JText::_('VRMANAGETKRES6');?></th><?php
				}
				if( in_array('coupon', $listable_fields) ) {
					?><th class="<?php echo $vik->getAdminThClass(); ?>" width="75" style="text-align: center;"><?php echo JText::_('VRMANAGETKRES7');?></th><?php
				}
				if( in_array('totpay', $listable_fields) ) {
					?><th class="<?php echo $vik->getAdminThClass(); ?>" width="150" style="text-align: center;"><?php echo $links[3]; ?></th><?php
				}
				if( in_array('taxes', $listable_fields) ) {
					?><th class="<?php echo $vik->getAdminThClass(); ?>" width="100" style="text-align: center;"><?php echo JText::_('VRMANAGETKRES21'); ?></th><?php
				}
				if( in_array('rescode', $listable_fields) ) {
					?><th class="<?php echo $vik->getAdminThClass(); ?>" width="80" style="text-align: center;"><?php echo JText::_('VRMANAGETKRES26'); ?></th><?php
				}
				if( in_array('status', $listable_fields) ) {
					?><th class="<?php echo $vik->getAdminThClass(); ?>" width="120" style="text-align: center;"><?php echo $links[4]; ?></th><?php
				}
				?>
			</tr>
		<?php echo $vik->closeTableHead(); ?>
		<?php
		$kk = 0;
		for( $i = 0; $i < count($rows); $i++ ) {
			$row = $rows[$i];
			
			$_coupon = explode(';;',$row['coupon_str']);
			$coupon_str = "";
			if( count( $_coupon ) == 3 ) {
				$coupon_str = $_coupon[1] . ' ' . (($_coupon[2] == 1) ? '%' : $curr_symb ); 
			}
			
			$deposit_tot_paid_str = '';
			if( $row['tot_paid'] > 0 ) {
				$deposit_tot_paid_str .= ' ('.$row['tot_paid'].' '.$curr_symb.')';
			}

			$oid_tooltip = '';
			if( $row['created_on'] != -1 ) {
				$created_by = '';
				if( $row['created_by'] != -1 ) {
					$created_by = $row['createdby_name'];
				} else {
					$created_by = $created_by_default;
				}
				$oid_tooltip = JText::sprintf('VAPRESLISTCREATEDTIP', date($date_format.' '.$time_format, $row['created_on']), $created_by);
			}
			 
			?>
			<tr class="row<?php echo $kk; ?>">
				<td><input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row['id']; ?>" onClick="<?php echo $vik->checkboxOnClick(); ?>"></td>
				<?php
				if( in_array('id', $listable_fields) ) {
					?><td>
						<span class="vr-res-id" title="<?php echo $oid_tooltip; ?>"><?php echo $row['id']; ?></span>
						<a href="index.php?option=com_cleverdine&task=rescodesorder&id_order=<?php echo $row['id']; ?>&group=2" class="vr-order-status-link">
							<i class="fa fa-folder<?php echo ($row['order_status_count'] == 0 ? '-o' : ''); ?> big" id="vrordfoldicon<?php echo $row['id']; ?>"></i>
						</a>
					</td><?php
				}
				if( in_array('sid', $listable_fields) ) {
					?><td style="text-align: center;"><a href="index.php?option=com_cleverdine&amp;task=edittkreservation&amp;cid[]=<?php echo $row['id']; ?>"><?php echo $row['sid']; ?></a></td><?php
				}
				if( in_array('payment', $listable_fields) ) {
					?><td style="text-align: center;"><?php echo (!empty($row['payment_name']) ? $row['payment_name'] : '/'); ?></td><?php
				}
				if( in_array('checkin_ts', $listable_fields) ) {
					?><td style="text-align: center;"><?php echo date($date_format.' '.$time_format, $row['checkin_ts'] ); ?></td><?php
				}
				if( in_array('delivery', $listable_fields) ) {
					?><td style="text-align: center;"><?php echo intval($row['delivery_service']) == 1 ? "<img src=\"".JUri::root()."administrator/components/com_cleverdine/assets/images/ok.png\"/>" : "<img src=\"".JUri::root()."administrator/components/com_cleverdine/assets/images/no.png\"/>"; ?></td><?php
				}
				if( in_array('customer', $listable_fields) ) {
					?><td style="text-align: center;"><?php echo $row['purchaser_nominative']; ?></td><?php
				}
				if( in_array('mail', $listable_fields) ) {
					?><td style="text-align: center;"><?php echo $row['purchaser_mail']; ?></td><?php
				}
				if( in_array('phone', $listable_fields) ) {
					?><td style="text-align: center;"><?php echo $row['purchaser_phone']; ?></td><?php
				}
				if( in_array('info', $listable_fields) ) {
					?><td style="text-align: center;">
						<a href="javascript: void(0);" onclick="SELECTED_ORDER=<?php echo $row['id']; ?>;vrOpenJModal('tkbasket', null, true); return false;">
							<img src="<?php echo JUri::root()."administrator/components/com_cleverdine/assets/images/basket.png"; ?>"/>
						</a>
					</td><?php
				}
				if( in_array('coupon', $listable_fields) ) {
					?><td style="text-align: center;"><?php echo $coupon_str; ?></td><?php
				}
				if( in_array('totpay', $listable_fields) ) {
					?><td style="text-align: center;"><?php echo $row['total_to_pay'] . ' ' . $curr_symb.$deposit_tot_paid_str; ?></td><?php
				}
				if( in_array('taxes', $listable_fields) ) {
					?><td style="text-align: center;"><?php echo $row['taxes'] . ' ' . $curr_symb; ?></td><?php
				}
				if( in_array('rescode', $listable_fields) ) {
					?><td style="text-align: center;" id="vrcodetd<?php echo $row['id']; ?>">
						<a href="javascript: void(0);" onClick="openResCodeDialog(<?php echo $row['id']; ?>, <?php echo $row['rescode']; ?>);" class="vrrescodelink" id="vrrescodelink<?php echo $row['id']; ?>">
							<?php if( empty($row['code_icon']) ) {
								echo (!empty($row['code']) ? $row['code'] : '--');
							} else { ?>
								<img src="<?php echo JUri::root().'components'.DIRECTORY_SEPARATOR.'com_cleverdine'.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.$row['code_icon']; ?>" title="<?php echo $row['code']; ?>" />
							<?php } ?>
						</a>
					</td><?php
				}
				if( in_array('status', $listable_fields) ) {
					?><td style="text-align: center;" class="<?php echo 'vrreservationstatus'.strtolower($row['status']); ?>"><?php echo JText::_('VRRESERVATIONSTATUS'.$row['status']); ?></td><?php
				}
				?>
			</tr>
			<?php
			$kk = 1 - $kk;
		}		
		?>
	</table>
	<?php } ?>

	<input type="hidden" name="notifycust" value="0" />

	<input type="hidden" name="tools" value="<?php echo $filters['tools']; ?>" />

	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="task" value="tkreservations"/>
	<?php echo JHTML::_( 'form.token' ); ?>
	<?php echo $navbut; ?>
</form>

<div class="vrrescodedialog" style="display: none;">
	<div class="vrrescodeblock" onClick="changeReservationCode(0);" id="vrrescodeblock0">
		<div class="vrrescodeblockimage"></div>
		<div class="vrrescodeblockname">--</div>
	</div>
	<?php foreach( $all_res_codes as $c ) { ?>
		<div class="vrrescodeblock" onClick="changeReservationCode(<?php echo $c['id']; ?>);" id="vrrescodeblock<?php echo $c['id']; ?>">
			<div class="vrrescodeblockimage">
				<?php if( !empty($c['icon']) ) { ?>
					<img src="<?php echo JUri::root().'components'.DIRECTORY_SEPARATOR.'com_cleverdine'.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.$c['icon']; ?>" />
				<?php } ?>
			</div>
			<div class="vrrescodeblockname"><?php echo $c['code']; ?></div>
		</div>
	<?php } ?>
</div>

<div class="modal hide fade" id="jmodal-tkbasket" style="width:90%;height:80%;margin-left:-45%;">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h3><?php echo JText::_('VRJMODALTKBASKET'); ?></h3>
	</div>
	<div id="jmodal-box-tkbasket"></div>
</div>

<div class="modal hide fade" id="jmodal-ccdetails" style="width:90%;height:80%;margin-left:-45%;">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h3><?php echo JText::_('VRSEECCDETAILS'); ?></h3>
	</div>
	<div id="jmodal-box-ccdetails"></div>
</div>

<div id="dialog-invoice" title="<?php echo JText::_('VRINVOICEDIALOG');?>" style="display: none;">
	<div>
		<?php
		$elem_yes = $vik->initRadioElement('', '', false, 'onClick="notifyCustValueChanged(1);"');
		$elem_no = $vik->initRadioElement('', '', true, 'onClick="notifyCustValueChanged(0);"');
		?>
		<?php echo $vik->openControl(JText::_('VRMANAGEINVOICE7').':'); ?>
			<?php echo $vik->radioYesNo('notifycust_radio', $elem_yes, $elem_no, false); ?>
		<?php echo $vik->closeControl(); ?>
	</div>
</div>

<div id="dialog-printorders" title="<?php echo JText::_('VRPRINT');?>" style="display: none;">
	<?php $printorders_text = cleverdine::getPrintOrdersText(true); ?>
	<div>
		<?php echo $vik->openControl(JText::_('VRPRINTORDERS1').':'); ?>
			<textarea name="printorders_header" style="width: 95%;height: 50px;"><?php echo $printorders_text['header']; ?></textarea>
		<?php echo $vik->closeControl(); ?>

		<?php echo $vik->openControl(JText::_('VRPRINTORDERS2').':'); ?>
			<textarea name="printorders_footer" style="width: 95%;height: 50px;"><?php echo $printorders_text['footer']; ?></textarea>
		<?php echo $vik->closeControl(); ?>

		<?php
		$elem_yes = $vik->initRadioElement('', '', false);
		$elem_no = $vik->initRadioElement('', '', true);
		?>
		<?php echo $vik->openControl(JText::_('VRPRINTORDERS3').':'); ?>
			<?php echo $vik->radioYesNo('printorders_update', $elem_yes, $elem_no, false); ?>
		<?php echo $vik->closeControl(); ?>
	</div>
</div>

<script type="text/javascript">

	var last_id_res = -1;
	var last_res_code = -1;

	jQuery(document).ready(function(){

		jQuery('#vrselectshift').select2({
			minimumResultsForSearch: -1,
			placeholder: '<?php echo addslashes(JText::_('VRRESERVATIONSHIFTSEARCH')); ?>',
			allowClear: true,
			width: 200
		});

		jQuery('#vrordstatsel').select2({
			minimumResultsForSearch: -1,
			placeholder: '<?php echo addslashes(JText::_('VRRESERVATIONSTATUSSEARCH')); ?>',
			allowClear: true,
			width: 200
		});

		jQuery('#vrselectshift, #vrordstatsel').on('change', function(){
			document.adminForm.submit();
		});

		jQuery('.vr-res-id').tooltip();

	});
	
	jQuery(document).mouseup(function (e) {
		var container = jQuery('.vrrescodedialog');
		var links = jQuery('.vrrescodelink');
	
		if( !container.is(e.target) && container.has(e.target).length === 0 && !links.is(e.target) && links.has(e.target).length === 0 ) {
			disposeResCodeDialog();
		}
	});
	
	function openResCodeDialog(id_res, id_code) {
		if( id_res == last_id_res ) {
			disposeResCodeDialog();
		} else {
			jQuery('.vrrescodelink').removeClass('vrcodelinkselected');
			
			jQuery('.vrrescodedialog').css('left', jQuery('#vrcodetd'+id_res).offset().left-jQuery('.vrrescodedialog').width()-15);
			
			var window_height = jQuery(window).height();
			var dialog_height = jQuery('.vrrescodedialog').height();
			var top = jQuery('#vrcodetd'+id_res).offset().top-dialog_height/2;
			if( top-jQuery(window).scrollTop() + jQuery('.vrrescodedialog').outerHeight() + 15 > window_height ) {
				top = window_height+jQuery(window).scrollTop()-jQuery('.vrrescodedialog').outerHeight() - 15;
			} 
			
			jQuery('.vrrescodedialog').css('top', top);
			
			jQuery('.vrrescodeblock').removeClass('vrcodeblockselected');
			jQuery('#vrrescodeblock'+id_code).addClass('vrcodeblockselected');
			
			jQuery('#vrrescodelink'+id_res).addClass('vrcodelinkselected');
			
			jQuery('.vrrescodedialog').show();
			
			last_id_res = id_res; 
			last_res_code = id_code;
		}
		
	} 
	
	function disposeResCodeDialog() {
		jQuery('.vrrescodedialog').hide();
		last_id_res = -1;
		
		jQuery('.vrrescodelink').removeClass('vrcodelinkselected');
	}
	
	function changeReservationCode(new_code) {
		if( last_res_code == new_code ) {
			return;
		}
		
		var res_id = last_id_res;
		
		disposeResCodeDialog();
		
		var last_html = jQuery('#vrcodetd'+res_id).html();
		jQuery('#vrcodetd'+res_id).html('...');
		
		jQuery.noConflict();
		
		var jqxhr = jQuery.ajax({
			type: "POST",
			url: "index.php",
			data: { option: "com_cleverdine", task: "change_reservation_code", id: res_id, new_code: new_code, type: 2, tmpl: "component" }
		}).done(function(resp){
			var obj = jQuery.parseJSON(resp); 
			if( obj[0] == 1 ) {
				jQuery('#vrcodetd'+res_id).html(obj[1]);

				// switch folder icon
				if( new_code > 0 ) {
					var fold_icon = jQuery('#vrordfoldicon'+res_id);
					if( fold_icon.length && fold_icon.hasClass('fa-folder-o') ) {
						fold_icon.removeClass('fa-folder-o').addClass('fa-folder');
					}
				}

			} else {
				jQuery('#vrcodetd'+res_id).html(last_html);
				alert(obj[1]);
			}
		}).fail(function(resp){
			jQuery('#vrcodetd'+res_id).html(last_html);
			alert('<?php echo addslashes(JText::_('VRSYSTEMCONNECTIONERR')); ?>');
		});
	}

	function notifyCustValueChanged(is) {
		jQuery('#adminForm input[name="notifycust"]').val(is);
	}
	
	var _LAST_SEARCH_ = <?php echo ($is_searching ? 1 : 0); ?>;
	
	function clearFilters() {
		jQuery('#vrkeysearch').val('');
		jQuery('#vrordnumfilter').val('');
		jQuery('#vrcouponsearch').val('');
		jQuery('#vrdatefilter').val('');

		jQuery('#vrordstatsel').select2('val', '');

		if( jQuery('#vrselectshift').length ) {
			jQuery('#vrselectshift').select2('val', '');
		}

		jQuery('input[name="tools"]').val(0);

		if( _LAST_SEARCH_ ) {
			document.adminForm.submit();
		}
	}

	function toggleSearchToolsButton(btn) {

		var tools = 0;

		if( jQuery(btn).hasClass('btn-primary') ) {
			jQuery('#vr-search-tools').slideUp();

			jQuery(btn).removeClass('btn-primary');
			
			jQuery('#vr-tools-caret').removeClass('fa-caret-up').addClass('fa-caret-down');
		} else {
			jQuery('#vr-search-tools').slideDown();

			jQuery(btn).addClass('btn-primary');

			jQuery('#vr-tools-caret').removeClass('fa-caret-down').addClass('fa-caret-up');

			tools = 1;
		}

		jQuery('input[name="tools"]').val(tools);

	}
	
	// JQUERY MODAL
	
	SELECTED_ORDER = -1;
	
	jQuery(document).ready(function(){
		// order summary
		jQuery('#jmodal-tkbasket').on('show', function() {
			tkBasketOnShow();
		});

		// credit card details
		jQuery('#jmodal-ccdetails').on('show', function() {
			ccDetailsOnShow();
		});
	});
	
	function vrOpenJModal(id, url, jqmodal) {
		<?php echo $vik->bootOpenModalJS(); ?>
	}
	
	function tkBasketOnShow() {
		var href = 'index.php?option=com_cleverdine&task=tkpurchaserinfo&tmpl=component&oid='+SELECTED_ORDER;
		var size = {
			width: jQuery('#jmodal-tkbasket').width(), //940,
			height: jQuery('#jmodal-tkbasket').height(), //590
		}
		appendModalContent('jmodal-box-tkbasket', href, size);
	}

	function ccDetailsOnShow() {
		var href = 'index.php?option=com_cleverdine&task=ccdetails&tmpl=component&tid=1&oid='+SELECTED_ORDER;
		var size = {
			width: jQuery('#jmodal-ccdetails').width(), //940,
			height: jQuery('#jmodal-ccdetails').height(), //590
		}
		appendModalContent('jmodal-box-ccdetails', href, size);
	}
	
	function appendModalContent(id, href, size) {
		jQuery('#'+id).html('<div class="modal-body" style="max-height:'+(size.height-20)+'px;">'+
		'<iframe class="iframe" src="'+href+'" width="'+size.width+'" height="'+size.height+'" style="max-height:'+(size.height-100)+'px;"></iframe>'+
		'</div>');
	}

	// SUBMIT

	function openInvoiceDialog(task) {
	
		jQuery("#dialog-invoice").dialog({
			resizable: true,
			height: 180,
			modal: true,
			buttons: {
				"<?php echo JText::_('VROK'); ?>": function() {
					jQuery( this ).dialog( "close" );
					Joomla.submitform(task, document.adminForm);
				},
				"<?php echo JText::_('VRCANCEL'); ?>": function() {
					jQuery( this ).dialog( "close" );
				}
			}
		});
		
	}

	function openPrintOrdersDialog(task) {

		jQuery("#dialog-printorders").dialog({
			resizable: true,
			width: 480,
			height: 380,
			modal: true,
			buttons: {
				"<?php echo JText::_('VROK'); ?>": function() {
					jQuery( this ).dialog( "close" );
					
					jQuery('#adminForm').append('<input type="hidden" name="printorders[header]" value="'+jQuery('textarea[name="printorders_header"]').val()+'"/>');
					jQuery('#adminForm').append('<input type="hidden" name="printorders[footer]" value="'+jQuery('textarea[name="printorders_footer"]').val()+'"/>');
					jQuery('#adminForm').append('<input type="hidden" name="printorders[update]" value="'+jQuery('input[name="printorders_update"]:checked').val()+'"/>');

					jQuery('#adminForm').attr('target', '_blank');
					
					Joomla.submitform(task, document.adminForm);

					jQuery('#adminForm').attr('target', '');


				},
				"<?php echo JText::_('VRCANCEL'); ?>": function() {
					jQuery( this ).dialog( "close" );
				}
			}
		});

	}

	Joomla.submitbutton = function(task) {
		if( task.indexOf('saveInvoice') != -1 ) {
			openInvoiceDialog(task);
		} else if( task == 'tkprintorders' ) {
			openPrintOrdersDialog(task);
		} else {
			Joomla.submitform(task, document.adminForm);
		}
	}

</script>
