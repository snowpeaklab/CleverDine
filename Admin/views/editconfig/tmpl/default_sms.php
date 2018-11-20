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

$languages = cleverdine::getKnownLanguages();

$elem_yes = $vik->initRadioElement();
$elem_no = $vik->initRadioElement();

?>

<style>
	.vr-uc-text-green {
		color: green;
		font-weight: bold;
	}
	
	.vr-uc-text-red {
		color: red;
		font-weight: bold;
	}	
</style>

<!-- LEFT SIDE -->

<div class="config-left-side">

	<!-- SMS APIs Fieldset -->

	<div class="config-fieldset">
		<div class="config-fieldset-legend"><?php echo JText::_('VRMANAGECONFIGTITLE3'); ?></div>
		<table class="admintable table" cellspacing="1">
			
			<!-- SMS API CLASS - Dropdown -->
			<?php
			$sms_apis = glob('./components/com_cleverdine/smsapi/*.php');
			
			$elements = array(
				$vik->initOptionElement('', '', false)
			);

			foreach( $sms_apis as $api ) {
				$api = str_replace('./components/com_cleverdine/smsapi/', '', $api);
				array_push($elements, $vik->initOptionElement($api, $api, $api == $params['smsapi']));
			}
			?>
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIGSMS1"); ?></b> </td>
				<td><?php echo $vik->dropdown('smsapi', $elements, 'smsapiselect', '', 'onChange="refreshSmsApiParameters();"'); ?></td>
			</tr>
			
			<!-- SMS API WHEN - Dropdown -->
			<?php
			$elements = array();
			
			if( $params['enablerestaurant'] ) { 
				$elements[] = $vik->initOptionElement(0, JText::_('VRCONFIGSMSAPIWHEN0'), $params['smsapiwhen'] == 0);
			}
			if( $params['enabletakeaway'] ) {
				$elements[] = $vik->initOptionElement(1, JText::_('VRCONFIGSMSAPIWHEN1'), $params['smsapiwhen'] == 1);
			}
			if( $params['enablerestaurant'] && $params['enabletakeaway'] ) {
				$elements[] = $vik->initOptionElement(2, JText::_('VRCONFIGSMSAPIWHEN2'), $params['smsapiwhen'] == 2);
			}

			$elements[] = $vik->initOptionElement(3, JText::_('VRCONFIGSMSAPIWHEN3'), $params['smsapiwhen'] == 3);
			
			?>
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIGSMS2"); ?></b> </td>
				<td><?php echo $vik->dropdown('smsapiwhen', $elements, '', 'medium-large'); ?></td>
			</tr>
			
			<!-- SMS API TO - Dropdown -->
			<?php
			$elements = array(
				$vik->initOptionElement(0, JText::_('VRCONFIGSMSAPITO0'), $params['smsapito'] == 0),
				$vik->initOptionElement(1, JText::_('VRCONFIGSMSAPITO1'), $params['smsapito'] == 1),
				$vik->initOptionElement(2, JText::_('VRCONFIGSMSAPITO2'), $params['smsapito'] == 2),
			);
			?>
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIGSMS3"); ?></b> </td>
				<td><?php echo $vik->dropdown('smsapito', $elements, '', 'medium-large'); ?></td>
			</tr>
			
			<!-- SMS API ADMIN PHONE - Text -->
			<?php
			$elements = array();
			$check_default = true;
			foreach( $this->countries as $country ) {

				$selected = false;

				if( ($pos = strpos($params['smsapiadminphone'], $country['phone_prefix'])) !== false ) {
					$params['smsapiadminphone'] = substr($params['smsapiadminphone'], $pos+strlen($country['phone_prefix']));
					$selected = true;
					$check_default = false;
				}

				if( $check_default && strlen($this->defaultCountry) && $this->defaultCountry == $country['country_2_code'] ) {
					$selected = true;
					$check_default = false;
				}

				array_push($elements, $vik->initOptionElement($country['phone_prefix'], $country['phone_prefix'], $selected, false, false, 'data-2code="'.$country['country_2_code'].'"'));
			}
			?>
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIGSMS4"); ?></b> </td>
				<td>
					<?php echo $vik->dropdown('smsapiadminphone_pfx', $elements, 'vr-smsphone-prefix'); ?>
					<input type="text" name="smsapiadminphone" value="<?php echo $params['smsapiadminphone']; ?>" size="16" onkeypress="return event.charCode >= 48 && event.charCode <= 57;"/>
				</td>
			</tr>
			
			<!-- SMS API PARAMS - Form -->
			<!--
			<tr>
				<td width="200" style="vertical-align: top;" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIGSMS5"); ?></b> </td>
				<td><div class="vikpayparamdiv">
						
					</div></td>
			</tr>
			-->
			
			<?php
			$can_estimate = false;
			$sms_api_path = JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_cleverdine'.DIRECTORY_SEPARATOR.'smsapi'.DIRECTORY_SEPARATOR.$params['smsapi'];
			if( file_exists( $sms_api_path ) && strlen($params['smsapi']) > 0 ) {
				require_once( $sms_api_path );
				if( method_exists('VikSmsApi', 'estimate') ) { 
					$can_estimate = true;
					?>
					<tr>
						<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIGSMS7"); ?></b> </td>
						<td>
							<span id="usercreditsp" style="margin-right: 50px;">/</span>
							<button type="button" class="btn" onClick="estimateSmsApiUserCredit();"><?php echo JText::_("VRMANAGECONFIGSMS8"); ?></button>
						</td>
					</tr>
				<?php } ?>
			<?php } ?>
		
		</table>
	</div>

	<!-- SMS APIs Fieldset -->

	<div class="config-fieldset">
		<div class="config-fieldset-legend"><?php echo JText::_('VRMANAGECONFIGSMS5'); ?></div>
		<table class="admintable table" cellspacing="1" id="vr-smsapi-params-table">

			<?php if (empty($params['smsapi'])) { ?>
				<tr><td colspan="2"><?php echo JText::_("VRMANAGECONFIGSMS6"); ?></td></tr>
			<?php } ?>

		</table>
	</div>

</div>

<!-- RIGHT SIDE -->

<div class="config-right-side">

	<!-- CUSTOMER TEMPLATE Fieldset -->

	<div class="config-fieldset">
		<div class="config-fieldset-legend"><?php echo JText::_('VRCONFIGSMSTITLE2'); ?></div>
		<div class="admintable table" cellspacing="1">

			<div style="margin-left: 10px;">

				<div class="btn-toolbar vr-btn-toolbar">
					<div class="btn-group pull-left">
						<button type="button" class="btn" onClick="putSmsTagOnActiveContent(1, '{total_cost}');">{total_cost}</button>
						<button type="button" class="btn" onClick="putSmsTagOnActiveContent(1, '{checkin}');">{checkin}</button>
						<button type="button" class="btn" onClick="putSmsTagOnActiveContent(1, '{people}');">{people}</button>
						<button type="button" class="btn" onClick="putSmsTagOnActiveContent(1, '{company}');">{company}</button>
						<button type="button" class="btn" onClick="putSmsTagOnActiveContent(1, '{created_on}');">{created_on}</button>
					</div>
				</div>
				<div class="control">
					<?php 
					$sms_tmpl_cust = array( json_decode($params['smstmplcust'], true), json_decode($params['smstmpltkcust'], true) );
					foreach( $languages as $k => $lang ) { 
						$lang_name = explode('-', $lang);
						$lang_name = strtolower($lang_name[1]);
						for( $i = 0; $i < 2; $i++ ) { 
							$content = "";
							if( !empty($sms_tmpl_cust[$i][$lang]) ) {
								$content = $sms_tmpl_cust[$i][$lang];
							}
							?>
							<textarea class="vr-smscont-1" id="vrsmscont<?php echo $lang_name; ?>-<?php echo ($i+1); ?>" 
							style="width: 95%;height: 200px;resize: vertical;<?php echo ($k != 0 || $i == 1 ? 'display:none;' : ''); ?>" name="smstmplcust[<?php echo $i; ?>][]"><?php echo $content; ?></textarea>
						<?php }
					} ?>
				</div>  
				<!-- LANGUAGES -->
				<div class="btn-toolbar vr-btn-toolbar" style="width: 95%;">
					<div class="btn-group pull-left">
						<button type="button" class="btn" id="vr-switch-button-1" onClick="switchSmsContent(1);"><?php echo JText::_('VRMANAGECONFIGSMS10'); ?></button>
					</div>
					<div class="btn-group pull-right">
						<?php foreach( $languages as $k => $lang ) { 
							$lang_name = explode('-', $lang);
							$lang_name = strtolower($lang_name[1]);
							?>
							<button type="button" class="vr-sms-langtag btn <?php echo ($k == 0 ? 'active' : ''); ?>" id="vrsmstag<?php echo $lang_name; ?>" onClick="changeLanguageSMS('<?php echo $lang_name; ?>');">
								<i class="icon">
									<img src="<?php echo JUri::root().'components/com_cleverdine/assets/css/flags/'.$lang_name.'.png';?>"/>
								</i>
								&nbsp;<?php echo strtoupper($lang_name); ?>
							</button>
						<?php } ?>
					</div>
				</div>

			</div>

		</div>
	</div>

	<!-- ADMIN TEMPLATE Fieldset -->

	<div class="config-fieldset">
		<div class="config-fieldset-legend"><?php echo JText::_('VRCONFIGSMSTITLE3'); ?></div>
		<div class="admintable table" cellspacing="1">

			<div style="margin-left: 10px;">

				<div class="btn-toolbar vr-btn-toolbar">
					<div class="btn-group pull-left">
						<button type="button" class="btn" onClick="putSmsTagOnActiveContent(2, '{total_cost}');">{total_cost}</button>
						<button type="button" class="btn" onClick="putSmsTagOnActiveContent(2, '{checkin}');">{checkin}</button>
						<button type="button" class="btn" onClick="putSmsTagOnActiveContent(2, '{people}');">{people}</button>
						<button type="button" class="btn" onClick="putSmsTagOnActiveContent(2, '{company}');">{company}</button>
						<button type="button" class="btn" onClick="putSmsTagOnActiveContent(2, '{customer}');">{customer}</button>
						<button type="button" class="btn" onClick="putSmsTagOnActiveContent(2, '{created_on}');">{created_on}</button>
					</div>
				</div>
				<div class="control">
					<?php 
					$sms_tmpl_admin = array($params['smstmpladmin'], $params['smstmpltkadmin']);
					for( $i = 0; $i < 2; $i++ ) { ?>
						<textarea class="vr-smscont-2" id="vrsmscontadmin-<?php echo ($i+1); ?>" style="width: 95%;height: 200px;resize: vertical;<?php echo ($i != 0 ? 'display:none;' : ''); ?>"
							name="smstmpladmin[]"><?php echo $sms_tmpl_admin[$i]; ?></textarea>
					<?php } ?>
				</div>
				
				<div class="btn-toolbar vr-btn-toolbar" style="width: 95%;">
					<div class="btn-group pull-left">
						<button type="button" class="btn" id="vr-switch-button-2" onClick="switchSmsContent(2);"><?php echo JText::_('VRMANAGECONFIGSMS10'); ?></button>
					</div>
				</div>

			</div>

		</div>
	</div>

</div>

<script type="text/javascript">

	jQuery(document).ready(function(){

		<?php if( !empty($params['smsapi']) ) { ?>
			refreshSmsApiParameters();
		<?php } ?>

		jQuery('#smsapiselect').select2({
			placeholder: '--',
			allowClear: true,
			width: 250
		});

		jQuery("#vr-smsphone-prefix").select2({
			allowClear: false,
			width: 120,
			minimumResultsForSearch: -1,
			formatResult: format,
			formatSelection: format,
			escapeMarkup: function(m) { return m; }
		});

		function format(state) {
			if(!state.id) return state.text; // optgroup

			return '<img class="vr-opt-flag" src="<?php echo JUri::root(); ?>components/com_cleverdine/assets/css/flags/' + jQuery(state.element).data('2code').toLowerCase() + '.png"/>' + state.text;
		}

	});

	// refresh SMS API params
	
	function refreshSmsApiParameters() {
		var sms_api = jQuery('#smsapiselect').val();
		
		jQuery.noConflict();

		jQuery('#vr-smsapi-params-table select').select2('destroy');
		
		jQuery('#vr-smsapi-params-table').html('<tr><td colspan="2"></td></tr>');

		if( sms_api.length == 0 ) {
			// trigger empty result
			parseSmsApiResult([[]]);
			return;
		}
		
		var jqxhr = jQuery.ajax({
			type: "POST",
			url: "index.php",
			data: { option: "com_cleverdine", task: "get_sms_api_fields", sms_api: sms_api, tmpl: "component" }
		}).done(function(resp){
			var obj = jQuery.parseJSON(resp); 
			
			parseSmsApiResult(obj);
		}).fail(function(resp){
			jQuery('#vr-smsapi-params-table').html('<tr><td colspan="2"><?php echo addslashes(JText::_("VRSYSTEMCONNECTIONERR")); ?></td></tr>');
		});
	}

	// parse params retrieved

	function parseSmsApiResult(obj) {

		jQuery('#vr-smsapi-params-table').html('');

		var length = 0;
		
		jQuery.each(obj[0], function(key, elem){
			var def_val = '';
			if( obj[1][key] ) {
				def_val = obj[1][key];
			}
			
			var _label = elem['label'].split('//');
			_helplabel = _label[1];
			_label = _label[0]; 
			
			var _row = '<tr>\n';
			if( elem['label'].length > 0 ) {
				_row += '<td width="200" class="adminparamcol"><b>'+_label+'</b></td>\n';
			}
			
			var _input = '';
			if( elem['type'] == 'text' ) {
				_input = '<input type="text" value="'+def_val+'" name="sms'+key+'" size="40"/>\n';	
			} else if( elem['type'] == 'select' ) {
				_input = '<select name="sms'+key+'">\n';
				for( var i = 0; i < elem['options'].length; i++ ) {
					var selected = '';
					if( elem['options'][i] == def_val ) {
						selected = 'selected="selected"';
					}
					_input += '<option value="'+elem['options'][i]+'" '+selected+'>'+elem['options'][i]+'</option>\n';
				}
				_input += '</select>\n';
			} else {
				_input = elem['html'];
			}
			
			if( _helplabel ) {
				_input += '<span class="right-label"> '+_helplabel+'</span>\n';
			}
			
			_row += '<td>'+_input+'</td>\n';
			_row += '</tr>';
			
			jQuery('#vr-smsapi-params-table').append(_row);
			
			length++;
			
		});
		
		if (length == 0) {
			jQuery('#vr-smsapi-params-table').append('<tr><td colspan="2"><?php echo addslashes(JText::_("VRMANAGECONFIGSMS6")); ?></td></tr>');
		} else {
			jQuery('#vr-smsapi-params-table select').select2({
				minimumResultsForSearch: -1,
				allowClear: false,
				width: 200,
			});
		}

	}
	
	<?php if( $can_estimate ) { ?>

		// estimate the remaining credit

		function estimateSmsApiUserCredit() {
			var sms_api = '<?php echo $params['smsapi']; ?>';
			var sms_api_phone = '<?php echo $params['smsapiadminphone']; ?>';
			
			jQuery.noConflict();
			
			jQuery('#usercreditsp').html('/');
			
			var jqxhr = jQuery.ajax({
				type: "POST",
				url: "index.php",
				data: { option: "com_cleverdine", task: "get_sms_api_credit", sms_api: sms_api, sms_api_phone: sms_api_phone, tmpl: "component" }
			}).done(function(resp){
				var obj = jQuery.parseJSON(resp); 
				
				if( obj[0] ) {
					if( obj[1] > 0 ) {
						jQuery('#usercreditsp').addClass('vr-uc-text-green');
						jQuery('#usercreditsp').removeClass('vr-uc-text-red');
					} else {
						jQuery('#usercreditsp').addClass('vr-uc-text-red');
						jQuery('#usercreditsp').removeClass('vr-uc-text-green');
					}
					jQuery('#usercreditsp').html(obj[2]);
				} else {
					alert(obj[1]);
				}
			});
		}

	<?php } ?>

	// insert selected placeholder on active textarea
	
	function putSmsTagOnActiveContent(id, cont) {
		
		var area = null;
		jQuery('.vr-smscont-'+id).each(function(){
			if( jQuery(this).css('display') != 'none' ) {
				area = jQuery(this);
			}
		});
		
		if( area == null ) {
			return;
		}
		
		var start = area.get(0).selectionStart;
		var end = area.get(0).selectionEnd;
		area.val(area.val().substring(0, start) + cont + area.val().substring(end));
		area.get(0).selectionStart = area.get(0).selectionEnd = start + cont.length;
		area.focus();
	}

	// switch language
	
	function changeLanguageSMS(tag) {
		jQuery('.vr-sms-langtag').removeClass('active');
		jQuery('#vrsmstag'+tag).addClass('active');
		
		var area = null;
		jQuery('.vr-smscont-1').each(function(){
			if( jQuery(this).css('display') != 'none' ) {
				area = jQuery(this);
			}
		});
		
		if( area == null ) {
			return;
		}
		
		jQuery('.vr-smscont-1').hide();
		jQuery('#vrsmscont'+tag+'-'+area.attr('id').split("-")[1]).show();
	}

	// switch type of contents (restaurant or takeaway)
	
	function switchSmsContent(section) {
		var area = null;
		jQuery('.vr-smscont-'+section).each(function(){
			if( jQuery(this).css('display') != 'none' ) {
				area = jQuery(this);
			}
		});
		
		if( area == null ) {
			return;
		}
		
		var id = area.attr('id').split('-');
		area.hide();
		jQuery('#'+id[0]+'-'+(id[1] == '1' ? '2' : '1')).show();
		
		if( id[1] == "1" ) {
			jQuery('#vr-switch-button-'+section).html('<?php echo addslashes(JText::_('VRMANAGECONFIGSMS9')); ?>');
		} else {
			jQuery('#vr-switch-button-'+section).html('<?php echo addslashes(JText::_('VRMANAGECONFIGSMS10')); ?>');
		}
	}

</script>
