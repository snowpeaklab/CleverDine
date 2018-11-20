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

$vik = $this->vikApplication;

$mediaManager = $this->mediaManager;

$elem_yes = $vik->initRadioElement();
$elem_no = $vik->initRadioElement();

$date_format = $params['dateformat'];

$df_joomla = $date_format;
$df_joomla = str_replace("d", "%d", $df_joomla);
$df_joomla = str_replace("m", "%m", $df_joomla);
$df_joomla = str_replace("Y", "%Y", $df_joomla);

// parse closing days

$closing_days = array();
if (strlen($params['closingdays'])) {
	foreach (explode(';;', $params['closingdays']) as $_app) {
		$_cd = explode(':', $_app);
		$_tx = JText::_('VRFREQUENCYTYPE'.$_cd[1]);
		if ($_cd[1] == 1) { // week frequency
			$_tx = JText::_('VRDAY'.strtoupper(date('D', $_cd[0])));
		}
		$closing_days[count($closing_days)] = array(date($date_format, $_cd[0]), $_cd[1], $_tx);
	}
}

//

?>

<!-- LEFT SIDE -->

<div class="config-left-side">

	<!-- SYSTEM Fieldset -->

	<div class="config-fieldset">
		<div class="config-fieldset-legend"><?php echo JText::_('VRMANAGECONFIGGLOBSECTION1'); ?></div>
		<table class="admintable table" cellspacing="1">
		
			<!-- RESTAURANT NAME - Text -->
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIG0"); ?></b> </td>
				<td><input type="text" name="restname" value="<?php echo $params['restname']?>" size="40"></td>
			</tr>
			
			<!-- COMPANY LOGO - File -->
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIG4"); ?></b> </td>
				<td>
					<?php echo $mediaManager->buildMedia('companylogo', 1, $params['companylogo']); ?>	
				</td>
			</tr>

			<!-- ENABLE RESTAURANT - Radio Button -->
			<?php
			$elem_yes = $vik->initRadioElement('', $elem_yes->label, $params['enablerestaurant'] == "1", 'onClick="enableTabValueChanged(2, 1);"');
			$elem_no = $vik->initRadioElement('', $elem_no->label, $params['enablerestaurant'] == "0", 'onClick="enableTabValueChanged(2, 0);"');
			?>
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIG54"); ?></b> </td>
				<td><?php echo $vik->radioYesNo('enablerestaurant', $elem_yes, $elem_no); ?></td>
			</tr>

			<!-- ENABLE TAKEAWAY - Radio Button -->
			<?php
			$elem_yes = $vik->initRadioElement('', $elem_yes->label, $params['enabletakeaway'] == "1", 'onClick="enableTabValueChanged(3, 1);"');
			$elem_no = $vik->initRadioElement('', $elem_no->label, $params['enabletakeaway'] == "0", 'onClick="enableTabValueChanged(3, 0);"');
			?>
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIGTK0"); ?></b> </td>
				<td><?php echo $vik->radioYesNo('enabletakeaway', $elem_yes, $elem_no); ?></td>
			</tr>
			
			<!-- DATE FORMAT - Dropdown -->
			<?php
			$elements = array(
				$vik->initOptionElement('Y/m/d', JText::_('VRCONFIGDATEFORMAT1'), $params["dateformat"]=="Y/m/d"),
				$vik->initOptionElement('m/d/Y', JText::_('VRCONFIGDATEFORMAT2'), $params["dateformat"]=="m/d/Y"),
				$vik->initOptionElement('d/m/Y', JText::_('VRCONFIGDATEFORMAT3'), $params["dateformat"]=="d/m/Y"),
				$vik->initOptionElement('Y-m-d', JText::_('VRCONFIGDATEFORMAT4'), $params["dateformat"]=="Y-m-d"),
				$vik->initOptionElement('m-d-Y', JText::_('VRCONFIGDATEFORMAT5'), $params["dateformat"]=="m-d-Y"),
				$vik->initOptionElement('d-m-Y', JText::_('VRCONFIGDATEFORMAT6'), $params["dateformat"]=="d-m-Y"),
				$vik->initOptionElement('Y.m.d', JText::_('VRCONFIGDATEFORMAT7'), $params["dateformat"]=="Y.m.d"),
				$vik->initOptionElement('m.d.Y', JText::_('VRCONFIGDATEFORMAT8'), $params["dateformat"]=="m.d.Y"),
				$vik->initOptionElement('d.m.Y', JText::_('VRCONFIGDATEFORMAT9'), $params["dateformat"]=="d.m.Y"),
			);
			?>
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIG5"); ?></b> </td>
				<td><?php echo $vik->dropdown('dateformat', $elements, '', 'medium'); ?></td>
			</tr>
			
			<!-- TIME FORMAT - Dropdown -->
			<?php
			$elements = array(
				$vik->initOptionElement('h:i A', JText::_('VRCONFIGTIMEFORMAT1'), $params["timeformat"]=="h:i A"),
				$vik->initOptionElement('H:i', JText::_('VRCONFIGTIMEFORMAT2'), $params["timeformat"]=="H:i"),
			);
			?>
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIG6"); ?></b> </td>
				<td><?php echo $vik->dropdown('timeformat', $elements, '', 'medium'); ?></td>
			</tr>
			
			<!-- WORKING TIME MODE -->
			<?php
			$elements = array(
				$vik->initOptionElement('0', JText::_('VRCONFIGOPENTIME1'), $params["opentimemode"]=="0"),
				$vik->initOptionElement('1', JText::_('VRCONFIGOPENTIME2'), $params["opentimemode"]=="1"),
			);
			?>
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIG10"); ?></b> </td>
				<td><?php echo $vik->dropdown('opentimemode', $elements, 'vropentimeselect', 'medium', 'onChange="handleOpenTimeFields();"'); ?></td>
			</tr>

			<!-- CONTINUOUS OPENING HOUR -->
			<tr class="opening-cont-field" style="<?php echo ($params['opentimemode'] == "0" ? '' : 'display:none;'); ?>">
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGESHIFT2"); ?></b> </td>
				<td><input type="number" name="hourfrom" value="<?php echo $params["hourfrom"]; ?>" min="0" max="23" /></td>
			</tr>

			<!-- CONTINUOUS CLOSING HOUR -->
			<tr class="opening-cont-field" style="<?php echo ($params['opentimemode'] == "0" ? '' : 'display:none;'); ?>">
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGESHIFT3"); ?></b> </td>
				<td><input type="number" name="hourto" value="<?php echo $params["hourto"]; ?>" min="0" max="23" /></td>
			</tr>
			
			<!-- ENABLE MULTILANGUAGE - Radio Button -->
			<?php
			$elem_yes = $vik->initRadioElement('', $elem_yes->label, $params['multilanguage'] == "1");
			$elem_no = $vik->initRadioElement('', $elem_no->label, $params['multilanguage'] == "0");
			?>
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIG50"); ?></b> </td>
				<td><?php echo $vik->radioYesNo('multilanguage', $elem_yes, $elem_no); ?></td>
			</tr>

			<!-- SHOW PHONE PREFIX - Radio Button -->
			<?php
			$elem_yes = $vik->initRadioElement('', $elem_yes->label, $params['phoneprefix'] == "1");
			$elem_no = $vik->initRadioElement('', $elem_no->label, $params['phoneprefix'] == "0");
			?>
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIG80"); ?></b> </td>
				<td><?php echo $vik->radioYesNo('phoneprefix', $elem_yes, $elem_no); ?></td>
			</tr>
			
			<!-- REFRESH DASHBOARD TIME - Number -->
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIG37"); ?></b> </td>
				<td><input type="number" name="refreshdash" value="<?php echo $params['refreshdash']?>" size="10" min="15"></td>
			</tr>

			<!-- CHECKBOX STYLE - Dropdown -->
			<?php
			$elements = array(
				$vik->initOptionElement('ios', JText::_('VRCONFIGUIRADIOOPT1'), $params['uiradio'] == 'ios'),
				$vik->initOptionElement('joomla', JText::_('VRCONFIGUIRADIOOPT2'), $params['uiradio'] == 'joomla'),
			);
			?>
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIG78"); ?></b> </td>
				<td><?php echo $vik->dropdown('uiradio', $elements, '', 'medium'); ?></td>
			</tr>
			
			<!-- LOAD JQUERY - Radio Button -->
			<?php
			$elem_yes = $vik->initRadioElement('', $elem_yes->label, $params['loadjquery'] == "1");
			$elem_no = $vik->initRadioElement('', $elem_no->label, $params['loadjquery'] == "0");
			?>
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIG15"); ?></b> </td>
				<td><?php echo $vik->radioYesNo('loadjquery', $elem_yes, $elem_no); ?></td>
			</tr>

			<!-- GOOGLE API KEY - Number -->
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIG55"); ?></b> </td>
				<td>
					<input type="text" name="googleapikey" value="<?php echo $params['googleapikey']?>" size="40" <?php echo (strlen($params['googleapikey']) ? 'readonly="readonly"' : ''); ?> />
					<?php if( strlen($params['googleapikey']) ) { ?>
						<a href="javascript: void(0);" onClick="lockUnlockInput(this);" id="abc">
							<i class="fa fa-lock big"></i>
						</a>
					<?php } ?>
				</td>
			</tr>
			
			<!-- SHOW FOOTER - Radio Button -->
			<?php
			$elem_yes = $vik->initRadioElement('', $elem_yes->label, $params['showfooter'] == "1");
			$elem_no = $vik->initRadioElement('', $elem_no->label, $params['showfooter'] == "0");
			?>
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIG23"); ?></b> </td>
				<td><?php echo $vik->radioYesNo('showfooter', $elem_yes, $elem_no); ?></td>
			</tr>

			<!-- CURRENT TIMEZONE - Label -->
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIG79"); ?></b> </td>
				<td><?php echo "<strong>".str_replace('_', ' ', date_default_timezone_get())."</strong> | ".date('Y-m-d H:i:s T'); ?></td>
			</tr>
		
		</table>
	</div>

	<!-- REVIEWS Fieldset -->

	<div class="config-fieldset">
		<div class="config-fieldset-legend"><?php echo JText::_('VRCONFIGFIELDSETREVIEWS'); ?></div>
		<table class="admintable table" cellspacing="1">
			
			<!-- ENABLE REVIEWS - Radio Button -->
			<?php
			$elem_yes = $vik->initRadioElement('', $elem_yes->label, $params['enablereviews'] == "1", 'onClick="reviewsValueChanged(1);"');
			$elem_no = $vik->initRadioElement('', $elem_no->label, $params['enablereviews'] == "0", 'onClick="reviewsValueChanged(0);"');
			?>
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIG58"); ?></b> </td>
				<td><?php echo $vik->radioYesNo('enablereviews', $elem_yes, $elem_no); ?></td>
			</tr>
			
			<!-- TAKEAWAY REVIEWS - Radio Button -->
			<?php
			$elem_yes = $vik->initRadioElement('', $elem_yes->label, $params['revtakeaway'] == "1");
			$elem_no = $vik->initRadioElement('', $elem_no->label, $params['revtakeaway'] == "0");
			?>
			<tr class="vrreviewstr" <?php echo ($params['enablereviews'] == "0" ? 'style="display:none;"' : ''); ?>>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIG59"); ?></b> </td>
				<td><?php echo $vik->radioYesNo('revtakeaway', $elem_yes, $elem_no); ?></td>
			</tr>
			
			<!-- REVIEWS LEAVE MODE - Dropdown -->
			<?php
			$elements = array(
				$vik->initOptionElement(0, JText::_('VRCONFIGREVLEAVEMODEOPT0'), $params['revleavemode'] == "0"),
				$vik->initOptionElement(1, JText::_('VRCONFIGREVLEAVEMODEOPT1'), $params['revleavemode'] == "1"),
				$vik->initOptionElement(2, JText::_('VRCONFIGREVLEAVEMODEOPT2'), $params['revleavemode'] == "2"),
			);
			?>
			<tr class="vrreviewstr" <?php echo ($params['enablereviews'] == "0" ? 'style="display:none;"' : ''); ?>>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIG60"); ?></b> </td>
				<td><?php echo $vik->dropdown('revleavemode', $elements, '', 'large'); ?></td>
			</tr>
			
			<!-- REVIEW COMMENT REQUIRED - Radio Button -->
			<?php
			$elem_yes = $vik->initRadioElement('', $elem_yes->label, $params['revcommentreq'] == "1");
			$elem_no = $vik->initRadioElement('', $elem_no->label, $params['revcommentreq'] == "0");
			?>
			<tr class="vrreviewstr" <?php echo ($params['enablereviews'] == "0" ? 'style="display:none;"' : ''); ?>>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIG61"); ?></b> </td>
				<td><?php echo $vik->radioYesNo('revcommentreq', $elem_yes, $elem_no); ?></td>
			</tr>
			
			<!-- MIN COMMENT LENGTH - Number -->
			<tr class="vrreviewstr" <?php echo ($params['enablereviews'] == "0" ? 'style="display:none;"' : ''); ?>>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIG62"); ?></b> </td>
				<td><input type="number" name="revminlength" value="<?php echo $params['revminlength']; ?>" min="0"/>
					<span class="right-label">&nbsp;<?php echo JText::_('VRCHARS'); ?></span></td>
			</tr>
			
			<!-- MAX COMMENT LENGTH - Number -->
			<tr class="vrreviewstr" <?php echo ($params['enablereviews'] == "0" ? 'style="display:none;"' : ''); ?>>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIG63"); ?></b> </td>
				<td><input type="number" name="revmaxlength" value="<?php echo $params['revmaxlength']; ?>" min="32"/>
					<span class="right-label">&nbsp;<?php echo JText::_('VRCHARS'); ?></span></td>
			</tr>
			
			<!-- REVIEWS LIST LIMIT - Number -->
			<tr class="vrreviewstr" <?php echo ($params['enablereviews'] == "0" ? 'style="display:none;"' : ''); ?>>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIG64"); ?></b> </td>
				<td><input type="number" name="revlimlist" value="<?php echo $params['revlimlist']; ?>" min="1"/></td>
			</tr>
			
			<!-- AUTO PUBLISHED - Radio Button -->
			<?php
			$elem_yes = $vik->initRadioElement('', $elem_yes->label, $params['revautopublished'] == "1");
			$elem_no = $vik->initRadioElement('', $elem_no->label, $params['revautopublished'] == "0");
			?>
			<tr class="vrreviewstr" <?php echo ($params['enablereviews'] == "0" ? 'style="display:none;"' : ''); ?>>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIG65"); ?></b> </td>
				<td><?php echo $vik->radioYesNo('revautopublished', $elem_yes, $elem_no); ?></td>
			</tr>
			
			<!-- FILTER BY LANGUAGE - Radio Button -->
			<?php
			$elem_yes = $vik->initRadioElement('', $elem_yes->label, $params['revlangfilter'] == "1");
			$elem_no = $vik->initRadioElement('', $elem_no->label, $params['revlangfilter'] == "0");
			?>
			<tr class="vrreviewstr" <?php echo ($params['enablereviews'] == "0" ? 'style="display:none;"' : ''); ?>>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIG66"); ?></b> </td>
				<td><?php echo $vik->radioYesNo('revlangfilter', $elem_yes, $elem_no); ?></td>
			</tr>
			
		</table>
	</div>

</div>

<!-- RIGHT SIDE -->

<div class="config-right-side">

	<!-- EMAIL Fieldset -->

	<div class="config-fieldset">
		<div class="config-fieldset-legend"><?php echo JText::_('VRMANAGECONFIGGLOBSECTION2'); ?></div>
		<table class="admintable table" cellspacing="1">
			
			<!-- ADMIN EMAIL - Text -->
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIG1"); ?></b> </td>
				<td><input type="text" name="adminemail" value="<?php echo $params['adminemail']?>" size="40"></td>
			</tr>
			
			<!-- SENDER EMAIL - Text -->
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIG43"); ?></b> </td>
				<td><input type="text" name="senderemail" value="<?php echo $params['senderemail']?>" size="40"></td>
			</tr>
			
		</table>
	</div>

	<!-- CURRENCY Fieldset -->

	<div class="config-fieldset">
		<div class="config-fieldset-legend"><?php echo JText::_('VRMANAGECONFIGGLOBSECTION3'); ?></div>
		<table class="admintable table" cellspacing="1">
			
			<!-- CURRENCY SYMB - Text -->
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIG7"); ?></b> </td>
				<td><input type="text" name="currencysymb" value="<?php echo $params['currencysymb']?>" size="10"></td>
			</tr>
			
			<!-- CURRENCY NAME - Text --> 
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIG8"); ?></b> </td>
				<td><input type="text" name="currencyname" value="<?php echo $params['currencyname']?>" size="10"></td>
			</tr>
			
			<!-- CURRENCY SYMB POSITION - Dropdown -->
			<?php
			$elements = array(
				$vik->initOptionElement('1', JText::_('VRCONFIGSYMBPOSITION1'), $params["symbpos"]=="1"),
				$vik->initOptionElement('2', JText::_('VRCONFIGSYMBPOSITION2'), $params["symbpos"]=="2"),
			);
			?>
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIG25"); ?></b> </td>
				<td><?php echo $vik->dropdown('symbpos', $elements, '', 'small-medium'); ?></td>
			</tr>

			<!-- CURRENCY DECIMAL SEPARATOR - Text -->
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIG51"); ?></b> </td>
				<td><input type="text" name="currdecimalsep" value="<?php echo $params['currdecimalsep']?>" size="10"></td>
			</tr>

			<!-- CURRENCY THOUSANDS SEPARATOR - Text -->
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIG52"); ?></b> </td>
				<td><input type="text" name="currthousandssep" value="<?php echo $params['currthousandssep']?>" size="10"></td>
			</tr>

			<!-- CURRENCY NUMBER OF DECIMALS - Number -->
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIG53"); ?></b> </td>
				<td><input type="number" name="currdecimaldig" value="<?php echo $params['currdecimaldig']; ?>" min="0" max="9999"/></td>
			</tr>
			
		</table>
	</div>

	<!-- CLOSING DAYS Fieldset -->
	
	<div class="config-fieldset">
		<div class="config-fieldset-legend"><?php echo JText::_('VRMANAGECONFIG21'); ?></div>
		<table class="admintable table" cellspacing="1">
			
			<!-- CLOSING DAYS - Form -->
			<tr>
				<!--<td style="vertical-align: top;" width="200" class="adminparamcol"> <b><?php echo JText::_('VRMANAGECONFIG21');?></b> </td>-->
				<td colspan="2">
					<div class="btn-toolbar">
						<div class="btn-group pull-left vr-toolbar-setfont">
							<?php echo $vik->calendar('', 'vrday', 'vrday'); ?>
						</div>
						<div class="btn-group pull-left">
							<div class="vr-toolbar-setfont">
								<?php
								$elements = array();
								for( $i = 0; $i <= 3; $i++ ) {
									array_push($elements, $vik->initOptionElement(JText::_('VRFREQUENCYTYPE'.$i), JText::_('VRFREQUENCYTYPE'.$i), false));
								}
								?>
								<?php echo $vik->dropdown('', $elements, 'vrfrequency', 'medium'); ?>
							</div>
						</div>
						<div class="btn-group pull-left">
							<button type="button" class="btn" onClick="addClosingDay();"><?php echo JText::_('VRMANAGECONFIG22'); ?></button>
						</div>
					</div>
					<br clear="all">
					<div id="vrclosingdayscont">
						<?php foreach( $closing_days as $i => $cd ) { ?>
							<div id="vrcdrow<?php echo $i; ?>" style="margin-bottom: 5px;">
								<span>
									<input type="text" style="vertical-align: middle;" value="<?php echo $cd[0]; ?> (<?php echo $cd[2]; ?>)" readonly/>
									<a href="javascript: void(0);" onClick="removeClosingDay(<?php echo $i; ?>)">
										<i class="fa fa-times big"></i>
									</a>
								</span>
							</div>
							<input id="vrcdhidden<?php echo $i; ?>" name="closing_days[]" type="hidden" value="<?php echo $cd[0].':'.$cd[1]; ?>"/>
						<?php } ?>
					</div>
				</td>
			</tr>
			
		</table>
	</div>

</div>

<script type="text/javascript">

	// enable restaurant / takeaway sections

	function enableTabValueChanged(tab, is) {
		if( is ) {
			jQuery('#vretabli'+tab).show();
		} else {
			jQuery('#vretabli'+tab).hide();
		}
	}

	// handle opening time mode

	function handleOpenTimeFields() {
		if( jQuery('#vropentimeselect').val() == "0" ) {
			jQuery(".opening-cont-field").show();
		} else {
			jQuery(".opening-cont-field").hide();
		}
	}

	// enable reviews settings

	function reviewsValueChanged(is) {
		if( is ) {
			jQuery('.vrreviewstr').show();
		} else {
			jQuery('.vrreviewstr').hide();
		}
	}

	// closing days

	var _DAYS = new Array();
	<?php $_D = array( 'SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT' ); ?>
	<?php for( $i = 0; $i < 7; $i++ ) { ?>
		_DAYS[<?php echo $i; ?>] = '<?php echo JText::_('VRDAY'.$_D[$i]); ?>';
	<?php } ?>

	var daysCont = <?php echo count($closing_days); ?>;

	// add a closing day
	
	function addClosingDay() {
		var day = jQuery('#vrday').val();
		if( day.length > 0 ) {
			var f_id = getFrequencyIndex();
			var f_tx = getFrequency();
			
			if( f_id == 1 ) { // WEEK
				f_tx = _DAYS[getDate(day).getDay()];	
			}
			
			putClosingDay( day, f_id, f_tx );
			
			jQuery('#vrday').val(day);
			
			daysCont++;
		}
	}

	// build the closing day input

	function putClosingDay(day,f_id,f_val) {
		jQuery('#vrclosingdayscont').append('<div id="vrcdrow'+daysCont+'" style="margin-bottom: 5px;">\n'+
			'<span>\n'+
				'<input type="text" style="vertical-align: middle;" value="'+day+' ('+f_val+')" readonly/>\n'+
				'<a href="javascript: void(0);" onClick="removeClosingDay('+daysCont+')">\n'+
					'<i class="fa fa-times big"></i>\n'+
				'</a>\n'+
			'</span>\n'+
		'</div>\n');
		
		jQuery('#adminForm').append('<input id="vrcdhidden'+daysCont+'" name="closing_days[]" type="hidden" value="'+day+':'+f_id+'"/>');
	}
	
	// get selected frequency

	function getFrequency() {
		return jQuery('#vrfrequency').val();
	}

	// get selected frequency index
	
	function getFrequencyIndex() {
		var x = document.getElementById('vrfrequency').selectedIndex;
		var y = document.getElementById('vrfrequency').options;
		return y[x].index;
	}

	// remove existing closing day
	
	function removeClosingDay(index) {
		jQuery('#vrcdrow'+index).remove();
		jQuery('#vrcdhidden'+index).remove();
	}

	// get date from timestamp
	
	function getDate(day) {
		var df_separator = '<?php echo $params['dateformat'][1]; ?>';
		var formats = '<?php echo $params['dateformat']; ?>'.split(df_separator);
		var date_exp = day.split(df_separator);
		
		var _args = new Array();
		for( var i = 0; i < formats.length; i++ ) {
			_args[formats[i]] = parseInt( date_exp[i] );
		}
		
		return new Date( _args['Y'], _args['m']-1, _args['d'] );
	}

</script>
