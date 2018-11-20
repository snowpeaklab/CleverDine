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

$params = $this->params;

$min_itvl = array(10, 15, 30, 60);

$vik = $this->vikApplication;

$all_tmpl_files = glob('../components/com_cleverdine/helpers/mail_tmpls/*.php');

$elem_yes = $vik->initRadioElement();
$elem_no = $vik->initRadioElement();

?>

<!-- LEFT SIDE -->

<div class="config-left-side">

	<!-- RESTAURANT Fieldset -->

	<div class="config-fieldset">
		<div class="config-fieldset-legend"><?php echo JText::_('VRMANAGECONFIGTITLE1'); ?></div>
		<table class="admintable table" cellspacing="1">

			<!-- DISPLAY ON DASHBOARD - Radio Button -->
			<?php
			$elem_yes = $vik->initRadioElement('', $elem_yes->label, $params['ondashboard'] == "1");
			$elem_no = $vik->initRadioElement('', $elem_no->label, $params['ondashboard'] == "0");
			?>
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIG36"); ?></b></td>
				<td><?php echo $vik->radioYesNo('ondashboard', $elem_yes, $elem_no); ?></td>
			</tr>
			
			<!-- MINUTES INTERVALS - Dropdown -->
			<?php
			$elements = array();
			foreach( $min_itvl as $min ) {
				array_push($elements, $vik->initOptionElement($min, $min, $min==$params['minuteintervals']));
			}
			?>
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIG11"); ?></b> </td>
				<td><?php echo $vik->dropdown('minuteintervals', $elements, '', 'small-medium'); ?></td>
			</tr>
			
			<!-- AVERAGE TIME STAY - Number -->
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIG12"); ?></b> </td>
				<td><input type="number" name="averagetimestay" value="<?php echo $params['averagetimestay']?>" size="10" min="5" step="5"></td>
			</tr>
			
			<!-- BOOKING MINUTES RETRICTIONS - Number -->
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIG24"); ?></b> </td>
				<td><input type="number" name="bookrestr" value="<?php echo $params['bookrestr']?>" size="10" min="0" step="5"></td>
			</tr>
			
			<!-- RESERVATION DEPOSIT - Number -->
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIG18"); ?></b> </td>
				<td><input type="number" id="resdeposit" name="resdeposit" value="<?php echo $params['resdeposit']?>" min="0" size="10"step="any"/>
					<span class="right-label">&nbsp;<?php echo $params['currencysymb']; ?></span></td>
			</tr>
			
			<!-- COST PER PERSON - Radio Button -->
			<?php
			$elem_yes = $vik->initRadioElement('', $elem_yes->label, $params['costperperson'] == "1");
			$elem_no = $vik->initRadioElement('', $elem_no->label, $params['costperperson'] == "0");
			?>
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIG19"); ?></b></td>
				<td><?php echo $vik->radioYesNo('costperperson', $elem_yes, $elem_no); ?></td>
			</tr>
			
			<!-- CHOOSABLE MENUS - Radio Button -->
			<?php
			$elem_yes = $vik->initRadioElement('', $elem_yes->label, $params['choosemenu'] == "1");
			$elem_no = $vik->initRadioElement('', $elem_no->label, $params['choosemenu'] == "0");
			?>
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIG39"); ?></b></td>
				<td><?php echo $vik->radioYesNo('choosemenu', $elem_yes, $elem_no); ?></td>
			</tr>
			
			<!-- DEFAULT STATUS - Dropdown -->
			<?php
			$elements = array(
				$vik->initOptionElement('PENDING', JText::_('VRRESERVATIONSTATUSPENDING'), $params['defstatus'] == "PENDING"),
				$vik->initOptionElement('CONFIRMED', JText::_('VRRESERVATIONSTATUSCONFIRMED'), $params['defstatus'] == "CONFIRMED"),
			);
			?>
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIG35"); ?></b> </td>
				<td><?php echo $vik->dropdown('defstatus', $elements, '', 'medium'); ?></td>
			</tr>
			
			<!-- TABLES LOCKED FOR - Number -->
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIG20"); ?></b> </td>
				<td><input type="number" id="tablocktime" name="tablocktime" value="<?php echo $params['tablocktime']?>" min="5" step="5" size="10">
					<span class="right-label">&nbsp;<?php echo JText::_('VRSHORTCUTMINUTE'); ?></span></td>
			</tr>
			
			<!-- ENABLE CANCELLATION - Radio Button -->
			<?php
			$elem_yes = $vik->initRadioElement('', $elem_yes->label, $params['enablecanc'] == "1", 'onClick="jQuery(\'.vrcancelchild\').show();"');
			$elem_no = $vik->initRadioElement('', $elem_no->label, $params['enablecanc'] == "0", 'onClick="jQuery(\'.vrcancelchild\').hide();"');
			?>
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIG40"); ?></b></td>
				<td><?php echo $vik->radioYesNo('enablecanc', $elem_yes, $elem_no); ?></td>
			</tr>

			<!-- CANCELLATION REASON - Dropdown -->
			<?php
			$elements = array(
				$vik->initOptionElement(0, JText::_('VRCONFIGCANCREASONOPT0'), $params['cancreason'] == "0"),
				$vik->initOptionElement(1, JText::_('VRCONFIGCANCREASONOPT1'), $params['cancreason'] == "1"),
				$vik->initOptionElement(2, JText::_('VRCONFIGCANCREASONOPT2'), $params['cancreason'] == "2")
			);
			?>
			<tr class="vrcancelchild" style="<?php echo ($params['enablecanc'] ? '' : 'display: none;'); ?>">
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIG68"); ?></b> </td>
				<td><?php echo $vik->dropdown('cancreason', $elements, '', 'medium-large'); ?></td>
			</tr>
			
			<!-- ACCEPT CANCELLATION BEFORE - Number -->
			<tr class="vrcancelchild" style="<?php echo ($params['enablecanc'] ? '' : 'display: none;'); ?>">
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIG41"); ?></b> </td>
				<td><input type="number" name="canctime" value="<?php echo $params['canctime']?>" min="0" max="999999" size="10">
					<span class="right-label">&nbsp;<?php echo JText::_('VRDAYS'); ?></span></td>
			</tr>
			
			<!-- LOGIN REQUIREMENTS - Dropdown -->
			<?php
			$elements = array(
				$vik->initOptionElement(1, JText::_('VRCONFIGLOGINREQ1'), $params['loginreq'] == "1"),
				$vik->initOptionElement(2, JText::_('VRCONFIGLOGINREQ2'), $params['loginreq'] == "2"),
				$vik->initOptionElement(3, JText::_('VRCONFIGLOGINREQ3'), $params['loginreq'] == "3"),
			);
			?>
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIG33"); ?></b> </td>
				<td><?php echo $vik->dropdown('loginreq', $elements, 'vrloginreqsel', 'medium', 'onChange="loginRequirementsChanged();"'); ?></td>
			</tr>
			
			<!-- ENABLE USER REGISTRATION - Radio Button -->
			<?php
			$elem_yes = $vik->initRadioElement('', $elem_yes->label, $params['enablereg'] == "1");
			$elem_no = $vik->initRadioElement('', $elem_no->label, $params['enablereg'] == "0");
			?>
			<tr id="vrenableregtr" style="<?php echo ($params['loginreq'] > 1 ? '' : 'display: none;'); ?>">
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIG34"); ?></b></td>
				<td><?php echo $vik->radioYesNo('enablereg', $elem_yes, $elem_no); ?></td>
			</tr>
			
		</table>
	</div>

	<!-- RESERVATION COLUMNS LIST Fieldset -->

	<?php
	$all_list_fields = array(
		'1' 	=> 'id',
		'2' 	=> 'sid',
		'20' 	=> 'payment',
		'3' 	=> 'checkin_ts',
		'4' 	=> 'people',
		'5' 	=> 'tname',
		'18' 	=> 'customer',
		'6' 	=> 'mail', 
		'16' 	=> 'phone',
		'7' 	=> 'info',
		'8' 	=> 'coupon',
		'9' 	=> 'deposit',
		'10' 	=> 'billval',
		'11' 	=> 'billclosed',
		'19' 	=> 'rescode',
		'12' 	=> 'status',
	);

	$listable_fields = array();
	if (!empty($params['listablecols'])) {
		$listable_fields = explode(",", $params['listablecols']);
	}
	?>
	
	<div class="config-fieldset">
		<div class="config-fieldset-legend"><?php echo JText::_('VRMANAGECONFIG38'); ?></div>
		<table class="admintable table" cellspacing="1">
			<?php foreach ($all_list_fields as $k => $f) { 
				$selected = in_array($f, $listable_fields); 
				
				$elem_yes = $vik->initRadioElement('', $elem_yes->label, $selected, 'onClick="toggleListField(\''.$f.'\', 1);"');
				$elem_no = $vik->initRadioElement('', $elem_no->label, !$selected, 'onClick="toggleListField(\''.$f.'\', 0);"');
				?>
				<tr>
					<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGERESERVATION".$k); ?></b> </td>
					<td>
						<?php echo $vik->radioYesNo($f."listcol", $elem_yes, $elem_no); ?>
						<input type="hidden" name="listablecols[]" value="<?php echo $f.':'.$selected; ?>" id="vrhidden<?php echo $f; ?>"/>
					</td>
				</tr>
			<?php } ?>
		</table>
	</div>

</div>

<!-- RIGHT SIDE -->

<div class="config-right-side">

	<!-- SEARCH Fieldset -->

	<div class="config-fieldset">
		<div class="config-fieldset-legend"><?php echo JText::_('VRCONFIGFIELDSETSEARCH'); ?></div>
		<table class="admintable table" cellspacing="1">

			<!-- RESERVATION REQUIREMENTS - Dropdown -->
			<?php
			$elements = array(
				$vik->initOptionElement('0', JText::_('VRCONFIGRESREQ0'), $params["reservationreq"]=="0"),
				$vik->initOptionElement('1', JText::_('VRCONFIGRESREQ1'), $params["reservationreq"]=="1"),
				$vik->initOptionElement('2', JText::_('VRCONFIGRESREQ2'), $params["reservationreq"]=="2"),
			);
			?> 
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIG16"); ?><br>&nbsp; <small>(<?php echo JText::_("VRMANAGECONFIG17"); ?>)</small>:</b> </td>
				<td><?php echo $vik->dropdown('reservationreq', $elements, '', 'large'); ?></td>
			</tr>

			<!-- MINIMUM PEOPLE - Number -->
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIG13"); ?></b> </td>
				<td><input type="number" name="minpeople" value="<?php echo $params['minimumpeople']?>" size="10" min="1" step="1"></td>
			</tr>
			
			<!-- MAXIMUM PEOPLE - Number -->
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIG14"); ?></b> </td>
				<td><input type="number" name="maxpeople" value="<?php echo $params['maximumpeople']?>" size="10" min="1" step="1"></td>
			</tr>
			
			<!-- LARGE PARTY LABEL - Radio Button -->
			<?php
			$elem_yes = $vik->initRadioElement('', $elem_yes->label, $params['largepartylbl'] == "1", 'onClick="jQuery(\'.vrlargepartyrow\').show();"');
			$elem_no = $vik->initRadioElement('', $elem_no->label, $params['largepartylbl'] == "0", 'onClick="jQuery(\'.vrlargepartyrow\').hide();"');
			?>
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIG48"); ?></b></td>
				<td><?php echo $vik->radioYesNo('largepartylbl', $elem_yes, $elem_no); ?></td>
			</tr>
			
			<!-- LARGE PARTY URL - Text -->
			<tr class="vrlargepartyrow" style="<?php echo ($params['largepartylbl'] == "1" ? '' : 'display:none;'); ?>">
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIG49"); ?></b> </td>
				<td><input type="text" name="largepartyurl" value="<?php echo $params['largepartyurl']?>" size="10" style="width: 400px !important;"></td>
			</tr>

			<!-- APPLY PERCENTAGE COUPONS - Radio Button -->
			<?php
			$elements = array(
				$vik->initOptionElement(1, JText::_('VRCONFIGAPPLYCOUPONTYPE1'), $params['applycoupon'] == "1"),
				$vik->initOptionElement(2, JText::_('VRCONFIGAPPLYCOUPONTYPE2'), $params['applycoupon'] == "2"),
			);
			?>
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIG42"); ?></b></td>
				<td><?php echo $vik->dropdown('applycoupon', $elements, '', 'medium'); ?></td>
			</tr>

		</table>
	</div>

	<!-- TAXES FIELDSET -->

	<div class="config-fieldset">
		<div class="config-fieldset-legend"><?php echo JText::_('VRCONFIGFIELDSETTAXES'); ?></div>
		<table class="admintable table" cellspacing="1">

			<!-- TAXES RATIO - Number -->
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIGTK10"); ?></b> </td>
				<td><input type="number" id="taxesratio" name="taxesratio" value="<?php echo $params['taxesratio']?>" min="0" max="99999" size="6" step="any"/>
					<span class="right-label">&nbsp;%</span></td>
			</tr>

			<!-- USE TAXES - Dropdown -->
			<?php
			$elements = array(
				$vik->initOptionElement(0, JText::_('VRTKCONFIGUSETAXOPT0'), $params['usetaxes'] == 0),
				$vik->initOptionElement(1, JText::_('VRTKCONFIGUSETAXOPT1'), $params['usetaxes'] == 1),
			);
			?>
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIGTK24"); ?></b> </td>
				<td><?php echo $vik->dropdown('usetaxes', $elements, '', 'medium'); ?></td>
			</tr>

		</table>
	</div>

	<!-- EMAIL Fieldset -->

	<div class="config-fieldset">
		<div class="config-fieldset-legend"><?php echo JText::_('VRMANAGECONFIGGLOBSECTION2'); ?></div>
		<table class="admintable table" cellspacing="1">
			
			<!-- SEND TO CUSTOMER WHEN - Dropdown -->
			<?php
			$elements = array(
				$vik->initOptionElement(1, JText::_('VRCONFIGSENDMAILWHEN1'), $params['mailcustwhen'] == 1),
				$vik->initOptionElement(2, JText::_('VRCONFIGSENDMAILWHEN2'), $params['mailcustwhen'] == 2),
				$vik->initOptionElement(0, JText::_('VRCONFIGSENDMAILWHEN0'), $params['mailcustwhen'] == 0),
			);
			?>
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIG44"); ?></b> </td>
				<td><?php echo $vik->dropdown('mailcustwhen', $elements, '', 'medium'); ?></td>
			</tr>
			
			<!-- SEND TO EMPLOYEE WHEN - Dropdown -->
			<?php
			$elements = array(
				$vik->initOptionElement(1, JText::_('VRCONFIGSENDMAILWHEN1'), $params['mailoperwhen'] == 1),
				$vik->initOptionElement(2, JText::_('VRCONFIGSENDMAILWHEN2'), $params['mailoperwhen'] == 2),
				$vik->initOptionElement(0, JText::_('VRCONFIGSENDMAILWHEN0'), $params['mailoperwhen'] == 0),
			);
			?>
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIG45"); ?></b> </td>
				<td><?php echo $vik->dropdown('mailoperwhen', $elements, '', 'medium'); ?></td>
			</tr>
			
			<!-- SEND TO ADMIN WHEN - Dropdown -->
			<?php
			$elements = array(
				$vik->initOptionElement(1, JText::_('VRCONFIGSENDMAILWHEN1'), $params['mailadminwhen'] == 1),
				$vik->initOptionElement(2, JText::_('VRCONFIGSENDMAILWHEN2'), $params['mailadminwhen'] == 2),
				$vik->initOptionElement(0, JText::_('VRCONFIGSENDMAILWHEN0'), $params['mailadminwhen'] == 0),
			);
			?>
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIG46"); ?></b> </td>
				<td><?php echo $vik->dropdown('mailadminwhen', $elements, '', 'medium'); ?></td>
			</tr>
			
			<!-- EMAIL TEMPLATE -->
			<?php
			$elements = array();
			foreach( $all_tmpl_files as $file ) {
				$file_name = str_replace("../components/com_cleverdine/helpers/mail_tmpls/", "", $file);
				array_push($elements, $vik->initOptionElement($file, $file_name, $params['mailtmpl'] == $file_name));
			}
			?>
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIG47"); ?></b> </td>
				<td>
					<?php echo $vik->dropdown('mailtmpl', $elements, 'vr-emailtmpl-sel', 'large'); ?>
					<button type="button" id="emailtmpl" class="btn" onclick="vrOpenJModal(this.id, null, true); return false;" style="margin-left: 2px;">
						<i class="icon-pencil"></i>
					</button>
				</td>
			</tr>

			<!-- ADMIN EMAIL TEMPLATE -->
			<?php
			$elements = array();
			foreach( $all_tmpl_files as $file ) {
				$file_name = str_replace("../components/com_cleverdine/helpers/mail_tmpls/", "", $file);
				array_push($elements, $vik->initOptionElement($file, $file_name, $params['adminmailtmpl'] == $file_name));
			}
			?>
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIG56"); ?></b> </td>
				<td>
					<?php echo $vik->dropdown('adminmailtmpl', $elements, 'vr-adminemailtmpl-sel', 'large'); ?>
			
					<button type="button" id="adminemailtmpl" class="btn" onclick="vrOpenJModal(this.id, null, true); return false;" style="margin-left: 2px;">
						<i class="icon-pencil"></i>
					</button>
				</td>
			</tr>

			<!-- CANCELLATION EMAIL TEMPLATE -->
			<?php
			$elements = array();
			foreach( $all_tmpl_files as $file ) {
				$file_name = str_replace("../components/com_cleverdine/helpers/mail_tmpls/", "", $file);
				array_push($elements, $vik->initOptionElement($file, $file_name, $params['cancmailtmpl'] == $file_name));
			}
			?>
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIG57"); ?></b> </td>
				<td>
					<?php echo $vik->dropdown('cancmailtmpl', $elements, 'vr-cancemailtmpl-sel', 'large'); ?>
			
					<button type="button" id="cancemailtmpl" class="btn" onclick="vrOpenJModal(this.id, null, true); return false;" style="margin-left: 2px;">
						<i class="icon-pencil"></i>
					</button>
				</td>
			</tr>
			
		</table>
	</div>

</div>

<!-- JQUERY MODALS -->

<div class="modal hide fade" id="jmodal-emailtmpl" style="width:90%;height:80%;margin-left:-45%;">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h3><?php echo JText::_('VRJMODALEMAILTMPL'); ?></h3>
	</div>
	<div id="jmodal-box-emailtmpl"></div>
</div>

<div class="modal hide fade" id="jmodal-adminemailtmpl" style="width:90%;height:80%;margin-left:-45%;">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h3><?php echo JText::_('VRJMODALEMAILTMPL'); ?></h3>
	</div>
	<div id="jmodal-box-adminemailtmpl"></div>
</div>

<div class="modal hide fade" id="jmodal-cancemailtmpl" style="width:90%;height:80%;margin-left:-45%;">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h3><?php echo JText::_('VRJMODALEMAILTMPL'); ?></h3>
	</div>
	<div id="jmodal-box-cancemailtmpl"></div>
</div>

<script type="text/javascript">

	jQuery(document).ready(function(){

		// register email templates

		jQuery('#jmodal-emailtmpl').on('show', function() {
			emailTmplOnShow();
		});

		jQuery('#jmodal-adminemailtmpl').on('show', function() {
			adminEmailTmplOnShow();
		});

		jQuery('#jmodal-cancemailtmpl').on('show', function() {
			cancEmailTmplOnShow();
		});

	});

	// handle login requirements

	function loginRequirementsChanged() {
		var index = jQuery('#vrloginreqsel').val();

		if( index > 1 ) {
			jQuery('#vrenableregtr').show();
		} else {
			jQuery('#vrenableregtr').hide();
		}
	}

	// toggle reservation columns 

	function toggleListField(id, value) {
		jQuery('#vrhidden'+id).val(id+':'+value);
	}

	// email template on show

	function emailTmplOnShow() {
		var href = 'index.php?option=com_cleverdine&task=managefile&file='+jQuery('#vr-emailtmpl-sel').val();
		var size = {
			width: jQuery('#jmodal-emailtmpl').width(), //940,
			height: jQuery('#jmodal-emailtmpl').height(), //590
		}
		appendModalContent('jmodal-box-emailtmpl', href, size);
	}

	// admin email template on show

	function adminEmailTmplOnShow() {
		var href = 'index.php?option=com_cleverdine&task=managefile&file='+jQuery('#vr-adminemailtmpl-sel').val();
		var size = {
			width: jQuery('#jmodal-adminemailtmpl').width(), //940,
			height: jQuery('#jmodal-adminemailtmpl').height(), //590
		}
		appendModalContent('jmodal-box-adminemailtmpl', href, size);
	}

	// cancellation email template on show

	function cancEmailTmplOnShow() {
		var href = 'index.php?option=com_cleverdine&task=managefile&file='+jQuery('#vr-cancemailtmpl-sel').val();
		var size = {
			width: jQuery('#jmodal-cancemailtmpl').width(), //940,
			height: jQuery('#jmodal-cancemailtmpl').height(), //590
		}
		appendModalContent('jmodal-box-cancemailtmpl', href, size);
	}

</script>
