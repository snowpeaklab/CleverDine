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

$all_tk_tmpl_files = glob('../components/com_cleverdine/helpers/tk_mail_tmpls/*.php');

$editor = JEditor::getInstance(JFactory::getApplication()->get('editor'));

$elem_yes = $vik->initRadioElement();
$elem_no = $vik->initRadioElement();

?>

<!-- LEFT SIDE -->

<div class="config-left-side">

	<!-- RESERVATION Fieldset -->

	<div class="config-fieldset">
		<div class="config-fieldset-legend"><?php echo JText::_('VRCONFIGFIELDSETRESERVATION'); ?></div>
		<table class="admintable table" cellspacing="1">

			<!-- TK DEFAULT STATUS - Dropdown -->
			<?php
			$elements = array(
				$vik->initOptionElement('PENDING', JText::_('VRRESERVATIONSTATUSPENDING'), $params['tkdefstatus'] == "PENDING"),
				$vik->initOptionElement('CONFIRMED', JText::_('VRRESERVATIONSTATUSCONFIRMED'), $params['tkdefstatus'] == "CONFIRMED"),
			);
			?>
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIGTK12"); ?></b> </td>
				<td><?php echo $vik->dropdown('tkdefstatus', $elements, '', 'medium'); ?></td>
			</tr>
			
			<!-- TK ENABLE CANCELLATION - Radio Button -->
			<?php
			$elem_yes = $vik->initRadioElement('', $elem_yes->label, $params['tkenablecanc'] == "1", 'onClick="jQuery(\'.vrtkcancelchild\').show();"');
			$elem_no = $vik->initRadioElement('', $elem_no->label, $params['tkenablecanc'] == "0", 'onClick="jQuery(\'.vrtkcancelchild\').hide();"');
			?>
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIGTK14"); ?></b></td>
				<td><?php echo $vik->radioYesNo('tkenablecanc', $elem_yes, $elem_no); ?></td>
			</tr>

			<!-- TK CANCELLATION REASON - Dropdown -->
			<?php
			$elements = array(
				$vik->initOptionElement(0, JText::_('VRCONFIGCANCREASONOPT0'), $params['tkcancreason'] == "0"),
				$vik->initOptionElement(1, JText::_('VRCONFIGCANCREASONOPT1'), $params['tkcancreason'] == "1"),
				$vik->initOptionElement(2, JText::_('VRCONFIGCANCREASONOPT2'), $params['tkcancreason'] == "2")
			);
			?>
			<tr class="vrtkcancelchild" style="<?php echo ($params['tkenablecanc'] ? '' : 'display: none;'); ?>">
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIG68"); ?></b> </td>
				<td><?php echo $vik->dropdown('tkcancreason', $elements, '', 'medium-large'); ?></td>
			</tr>
			
			<!-- TK ACCEPT CANCELLATION BEFORE - Number -->
			<tr class="vrtkcancelchild" style="<?php echo ($params['tkenablecanc'] ? '' : 'display: none;'); ?>">
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIGTK15"); ?></b> </td>
				<td><input type="number" name="tkcanctime" value="<?php echo $params['tkcanctime']?>" min="0" size="10">
					<span class="right-label">&nbsp;<?php echo JText::_('VRDAYS'); ?></span></td>
			</tr>
			
			<!-- TK MINUTES INTERVAL -->
			<?php
			$elements = array();
			foreach( $min_itvl as $min ) {
				array_push($elements, $vik->initOptionElement($min, $min, $min==$params['tkminint']));
			}
			?>
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIGTK1"); ?></b> </td>
				<td><?php echo $vik->dropdown('tkminint', $elements, 'vrtkminselect', 'small-medium', 'onChange="asapChanged();"'); ?></td>
			</tr>
			
			<!-- TK SOONEST DELIVERY AFTER - Dropdown -->
			<?php
			$elements = array();
			for( $i = 1; $i <= 6; $i++ ) {
				array_push($elements, $vik->initOptionElement($i, $i*$params['tkminint'], $params['asapafter'] == $i));
			}
			?>
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIGTK9"); ?></b></td>
				<td><?php echo $vik->dropdown('asapafter', $elements, 'vrtkasapselect', 'small-medium'); ?>
					<span class="right-label">&nbsp;<?php echo JText::_('VRSHORTCUTMINUTE'); ?></span></td>
			</tr>

			<!-- TK KEEP ORDER LOCKED - Number -->
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIGTK8"); ?></b> </td>
				<td><input type="number" id="tklocktime" name="tklocktime" value="<?php echo $params['tklocktime']?>" min="5" step="5" size="10">
					<span class="right-label">&nbsp;<?php echo JText::_('VRSHORTCUTMINUTE'); ?></span></td>
			</tr>
			
			<!-- TK LOGIN REQUIREMENTS -->
			<?php
			$elements = array(
				$vik->initOptionElement(1, JText::_('VRCONFIGLOGINREQ1'), $params['tkloginreq'] == "1"),
				$vik->initOptionElement(2, JText::_('VRCONFIGLOGINREQ2'), $params['tkloginreq'] == "2"),
				$vik->initOptionElement(3, JText::_('VRCONFIGLOGINREQ3'), $params['tkloginreq'] == "3"),
			);
			?>
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIG33"); ?></b> </td>
				<td><?php echo $vik->dropdown('tkloginreq', $elements, 'vrtkloginreqsel', 'medium', 'onChange="tkLoginRequirementsChanged();"'); ?></td>
			</tr>
			
			<!-- TK ENABLE REGISTRATION - Radio Button -->
			<?php
			$elem_yes = $vik->initRadioElement('', $elem_yes->label, $params['tkenablereg'] == "1");
			$elem_no = $vik->initRadioElement('', $elem_no->label, $params['tkenablereg'] == "0");
			?>
			<tr id="vrtkenableregtr" style="<?php echo ($params['tkloginreq'] > 1 ? '' : 'display: none;'); ?>">
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIG34"); ?></b></td>
				<td><?php echo $vik->radioYesNo('tkenablereg', $elem_yes, $elem_no); ?></td>
			</tr>

		</table>
	</div>

	<!-- STOCKS Fieldset -->

	<div class="config-fieldset">
		<div class="config-fieldset-legend"><?php echo JText::_('VRMANAGECONFIGTKSECTION2'); ?></div>
		<table class="admintable table" cellspacing="1">

			<!-- ENABLE STOCK SYSTEM - Radio Button -->
			<?php
			$elem_yes = $vik->initRadioElement('', $elem_yes->label, $params['tkenablestock']==1, 'onClick="jQuery(\'.vre-stock-child\').show();"');
			$elem_no = $vik->initRadioElement('', $elem_no->label, $params['tkenablestock']==0, 'onClick="jQuery(\'.vre-stock-child\').hide();"');
			?>
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIGTK16"); ?></b> </td>
				<td><?php echo $vik->radioYesNo('tkenablestock', $elem_yes, $elem_no); ?></td>
			</tr>
			
			<!-- EMAIL TEMPLATE -->
			<?php
			$elements = array();
			foreach( $all_tk_tmpl_files as $file ) {
				$file_name = str_replace("../components/com_cleverdine/helpers/tk_mail_tmpls/", "", $file);
				array_push($elements, $vik->initOptionElement($file, $file_name, $params['tkstockmailtmpl'] == $file_name));
			}
			?>
			<tr class="vre-stock-child" style="<?php echo ($params['tkenablestock'] == 1 ? '' : 'display: none;'); ?>">
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIGTK17"); ?></b> </td>
				<td>
					<?php echo $vik->dropdown('tkstockmailtmpl', $elements, 'vr-tkstockemailtmpl-sel', 'large'); ?>
						
					<button type="button" id="tkstockemailtmpl" class="btn" onclick="vrOpenJModal(this.id, null, true); return false;" style="margin-left: 2px;">
						<i class="icon-pencil"></i>
					</button>
				</td>
			</tr>

		</table>
	</div>

	<!-- TAKE-AWAY Fieldset -->

	<div class="config-fieldset">
		<div class="config-fieldset-legend"><?php echo JText::_('VRCONFIGFIELDSETTAKEAWAY'); ?></div>
		<table class="admintable table" cellspacing="1">

			<!-- TK SHOW IMAGES - Radio Button -->
			<?php
			$elem_yes = $vik->initRadioElement('', $elem_yes->label, $params['tkshowimages']==1);
			$elem_no = $vik->initRadioElement('', $elem_no->label, $params['tkshowimages']==0);
			?>
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIGTK30"); ?></b> </td>
				<td><?php echo $vik->radioYesNo('tkshowimages', $elem_yes, $elem_no); ?></td>
			</tr>

			<!-- TK CONFIRM PAGE ITEM ID - Dropdown -->
			<?php
			$elements = array();
			$elements[] = $vik->initOptionElement('', '', empty($params['tkconfitemid']));
			$elements[] = $vik->initOptionElement(-1, JText::_('VRTKCONFIGITEMOPT1'), !empty($params['tkconfitemid']));

			$found = false;
			foreach( RestaurantsHelper::getMenusItemsList() as $menu ) {
				$elements[] = $vik->initOptionElement($menu['id'], $menu['type'], false, true);
				foreach( $menu['items'] as $item ) {
					$found = $found || ($params['tkconfitemid'] == $item['id']);
					$elements[] = $vik->initOptionElement($item['id'], $item['title'], $params['tkconfitemid'] == $item['id']);
				}
			}
			?>
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_('VRMANAGECONFIGTK23');?></b> </td>
				<td>
					<?php echo $vik->dropdown('tkconfitemid', $elements, 'vrtk-confitem-sel'); ?>
					<input type="number" name="tkconfitem_custom" value="<?php echo $params['tkconfitemid']; ?>" style="<?php echo (empty($params['tkconfitemid']) || $found ? 'display: none;' : ''); ?>" step="any" min="0"/>
				</td>
			</tr>

			<!-- TK PROD DESC LENGTH - Number -->
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIGTK29"); ?></b></td>
				<td><input type="number" name="tkproddesclength" value="<?php echo $params['tkproddesclength']; ?>" min="0" />
					<span class="right-label">&nbsp;<?php echo JText::_('VRCHARS'); ?></span></td>
			</tr>
			
			<!-- TK NOTES - Textarea -->
			<tr>
				<td width="200" style="vertical-align: top;" class="adminparamcol"> <b><?php echo JText::_('VRMANAGECONFIGTK6');?></b> </td>
				<td>
					<?php /*echo $editor->display("tknote", $params['tknote'], 400, 200, 70, 20);*/ ?>
					<textarea name="tknote" style="width: 90%;height: 100px;resize: vertical;"><?php echo $params['tknote']; ?></textarea>
				</td>
			</tr>

		</table>
	</div>

	<!-- ORDERS LIST COLUMNS Fieldset -->

	<div class="config-fieldset">
		<div class="config-fieldset-legend"><?php echo JText::_('VRMANAGECONFIGTK13'); ?></div>
		<table class="admintable table" cellspacing="1">

			<?php
			$all_tklist_fields = array(
				'1' 	=> 'id', 
				'2' 	=> 'sid',
				'27' 	=> 'payment',
				'3' 	=> 'checkin_ts',
				'4' 	=> 'delivery',
				'24' 	=> 'customer',
				'5' 	=> 'mail',
				'23' 	=> 'phone', 
				'6' 	=> 'info',
				'7' 	=> 'coupon',
				'8' 	=> 'totpay',
				'21' 	=> 'taxes',
				'26' 	=> 'rescode',
				'9' 	=> 'status',
			);

			$tklistable_fields = array();
			if (!empty($params['tklistablecols'])) {
				$tklistable_fields = explode(",", $params['tklistablecols']);
			}
			?>

			<?php foreach ($all_tklist_fields as $k => $f) { 
				$selected = in_array($f, $tklistable_fields); 
				
				$elem_yes = $vik->initRadioElement('', $elem_yes->label, $selected, 'onClick="toggleTkListField(\''.$f.'\', 1);"');
				$elem_no = $vik->initRadioElement('', $elem_no->label, !$selected, 'onClick="toggleTkListField(\''.$f.'\', 0);"');
				?>
				<tr>
					<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGETKRES".$k); ?></b> </td>
					<td>
						<?php echo $vik->radioYesNo($f."tklistcol", $elem_yes, $elem_no); ?>
						<input type="hidden" name="tklistablecols[]" value="<?php echo $f.':'.$selected; ?>" id="vrtkhidden<?php echo $f; ?>"/>
					</td>
				</tr>
			<?php } ?>

		</table>
	</div>

</div>

<!-- RIGHT SIDE -->

<div class="config-right-side">

	<!-- ORDER Fieldset -->

	<div class="config-fieldset">
		<div class="config-fieldset-legend"><?php echo JText::_('VRCONFIGFIELDSETORDER'); ?></div>
		<table class="admintable table" cellspacing="1">

			<!-- TK MIN COST PER ORDER - Number -->
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIGTK5"); ?></b> </td> 
				<td><input type="number" name="mincostperorder" value="<?php echo $params['mincostperorder']; ?>" min="0" size="10" step="any"/>
					<span class="right-label">&nbsp;<?php echo $params['currencysymb']; ?></span></td>
			</tr>

			<!-- TK MEALS PER INTERVAL - Number -->
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIGTK2"); ?></b> </td> 
				<td>
					<input type="number" name="mealsperint" value="<?php echo $params['mealsperint']; ?>" min="1" size="10"/>
					<span class="right-label">&nbsp;<?php echo JText::_('VRPREPARATIONMEALS'); ?></span>
				</td>
			</tr>

			<!-- TK MAX ITEMS IN CART - Number -->
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIGTK25"); ?></b> </td> 
				<td>
					<input type="number" name="tkmaxitems" value="<?php echo $params['tkmaxitems']; ?>" min="1" size="10"/>
					<span class="right-label">&nbsp;<?php echo JText::_('VRPREPARATIONMEALS'); ?></span>
				</td>
			</tr>

			<!-- TK USE OVERLAY - Dropdown -->
			<?php
			$elements = array(
				$vik->initOptionElement(2, JText::_('VRTKCONFIGOVERLAYOPT2'), $params['tkuseoverlay'] == 2),
				$vik->initOptionElement(1, JText::_('VRTKCONFIGOVERLAYOPT1'), $params['tkuseoverlay'] == 1),
				$vik->initOptionElement(0, JText::_('VRTKCONFIGOVERLAYOPT0'), $params['tkuseoverlay'] == 0)
			);
			?>
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIGTK28"); ?></b></td>
				<td><?php echo $vik->dropdown('tkuseoverlay', $elements, '', 'medium-large'); ?></td>
			</tr>

			<!-- TK TODAY ORDERS - Radio Button -->
			<?php
			$elem_yes = $vik->initRadioElement('', '', $params['tkallowdate'] == "1", 'onClick="tkAllowDateValueChanged(1);"');
			$elem_no = $vik->initRadioElement('', '', $params['tkallowdate'] == "0", 'onClick="tkAllowDateValueChanged(0);"');
			?>
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIGTK26"); ?></b> </td> 
				<td>
					<?php echo $vik->radioYesNo('tkallowdate', $elem_yes, $elem_no); ?>
					<?php
					echo $vik->createPopover(array(
						'title' => JText::_("VRMANAGECONFIGTK26"),
						'content' => JText::_('VRMANAGECONFIGTK26_HELP'),
					));
					?>
				</td>
			</tr>

			<!-- TK LIVE ORDERS - Radio Button -->
			<?php
			$elem_yes = $vik->initRadioElement('', '', $params['tkwhenopen'] == "1");
			$elem_no = $vik->initRadioElement('', '', $params['tkwhenopen'] == "0");
			?>
			<tr class="tkallowdate-child" style="<?php echo ($params['tkallowdate'] == 1 ? 'display:none;' : ''); ?>">
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIGTK27"); ?></b> </td> 
				<td>
					<?php echo $vik->radioYesNo('tkwhenopen', $elem_yes, $elem_no); ?>
					<?php
					echo $vik->createPopover(array(
						'title' => JText::_("VRMANAGECONFIGTK27"),
						'content' => JText::_('VRMANAGECONFIGTK27_HELP'),
					));
					?>
				</td>
			</tr>

		</table>
	</div>

	<!-- DELIVERY Fieldset -->

	<div class="config-fieldset">
		<div class="config-fieldset-legend"><?php echo JText::_('VRCONFIGFIELDSETDELIVERY'); ?></div>
		<table class="admintable table" cellspacing="1">

			<!-- TK ENABLE DELIVERY SERVICE -->
			<?php
			$elements = array(
				$vik->initOptionElement(2, JText::_('VRDELIVERYSERVICEOPT3'), $params['deliveryservice'] == 2),
				$vik->initOptionElement(1, JText::_('VRDELIVERYSERVICEOPT2'), $params['deliveryservice'] == 1),
				$vik->initOptionElement(0, JText::_('VRDELIVERYSERVICEOPT1'), $params['deliveryservice'] == 0),
			);
			?>
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIGTK3"); ?></b> </td>
				<td><?php echo $vik->dropdown('deliveryservice', $elements, 'vr-deliveryservice-sel', 'medium'); ?></td>
			</tr>
			
			<!-- TK DELIVERY COST - Number -->
			<?php
			$elements = array(
				$vik->initOptionElement(1, '%', $params['dspercentot']==1),
				$vik->initOptionElement(2, $params['currencysymb'], $params['dspercentot']==2)
			);
			?>
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIGTK4"); ?></b> </td> 
				<td><input type="number" name="dsprice" class="delivery-param" value="<?php echo $params['dsprice']; ?>" min="0" max="99999" size="6" step="any" <?php echo ($params['deliveryservice'] == 0 ? 'readonly="readonly"' : ''); ?>/>
					<?php echo $vik->dropdown('dspercentot', $elements, '', 'delivery-param short', ($params['deliveryservice'] == 0 ? 'disabled="disabled"' : '')); ?></td>
			</tr>
			
			<!-- TK FREE DELIVERY WITH - Number -->
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIGTK7"); ?></b> </td> 
				<td><input type="number" name="freedelivery" class="delivery-param" value="<?php echo $params['freedelivery']; ?>" min="0" max="99999" size="6" step="any" <?php echo ($params['deliveryservice'] == 0 ? 'readonly="readonly"' : ''); ?>/>
					<span class="right-label">&nbsp;<?php echo $params['currencysymb']; ?></span></td>
			</tr>

			<!-- TK PICKUP COST - Number -->
			<?php
			$elements = array(
				$vik->initOptionElement(1, '%', $params['pickuppercentot']==1),
				$vik->initOptionElement(2, $params['currencysymb'], $params['pickuppercentot']==2)
			);
			?>
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIGTK18"); ?></b> </td> 
				<td><input type="number" name="pickupprice" class="pickup-param" value="<?php echo $params['pickupprice']; ?>" max="99999" size="6" step="any" <?php echo ($params['deliveryservice'] == 1 ? 'readonly="readonly"' : ''); ?>/>
					<?php echo $vik->dropdown('pickuppercentot', $elements, '', 'pickup-param short', ($params['deliveryservice'] == 1 ? 'disabled="disabled"' : '')); ?></td>
			</tr>

		</table>
	</div>

	<!-- TAXES Fieldset -->

	<div class="config-fieldset">
		<div class="config-fieldset-legend"><?php echo JText::_('VRCONFIGFIELDSETTAXES'); ?></div>
		<table class="admintable table" cellspacing="1">

			<!-- TK TAXES RATIO - Number -->
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIGTK10"); ?></b> </td>
				<td><input type="number" id="tktaxesratio" name="tktaxesratio" value="<?php echo $params['tktaxesratio']?>" min="0" max="99999" size="6" step="any"/>
					<span class="right-label">&nbsp;%</span></td>
			</tr>

			<!-- TK USE TAXES - Dropdown -->
			<?php
			$elements = array(
				$vik->initOptionElement(0, JText::_('VRTKCONFIGUSETAXOPT0'), $params['tkusetaxes'] == 0),
				$vik->initOptionElement(1, JText::_('VRTKCONFIGUSETAXOPT1'), $params['tkusetaxes'] == 1),
			);
			?>
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIGTK24"); ?></b> </td>
				<td><?php echo $vik->dropdown('tkusetaxes', $elements, '', 'medium'); ?></td>
			</tr>
			
			<!-- TK SHOW TAXES - Radio Button -->
			<?php
			$elem_yes = $vik->initRadioElement('', $elem_yes->label, $params['tkshowtaxes'] == "1");
			$elem_no = $vik->initRadioElement('', $elem_no->label, $params['tkshowtaxes'] == "0");
			?>
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIGTK11"); ?></b> </td>
				<td><?php echo $vik->radioYesNo('tkshowtaxes', $elem_yes, $elem_no); ?></td>
			</tr>

		</table>
	</div>

	<!-- ORIGIN ADDRESSES Fieldset -->

	<div class="config-fieldset">
		<div class="config-fieldset-legend"><?php echo JText::_('VRMANAGECONFIGTK19'); ?></div>
		<table class="admintable table" cellspacing="1">

			<tr>
				<!--<td width="200" class="adminparamcol" style="vertical-align:top;"> <b><?php echo JText::_('VRMANAGECONFIGTK20'); ?></b> </td>-->
				<td colspan="2">
					<div id="vroriginscont">
						<?php 
						$origins = json_decode($params['tkaddrorigins']); 
						foreach( $origins as $i => $origin ) { ?>
							<div id="vrorigin<?php echo $i; ?>" style="margin-bottom: 5px;">
								<input type="text" name="tkaddrorigins[]" value="<?php echo $origin; ?>" size="64" placeholder="<?php echo addslashes(JText::_('VRMANAGECONFIGTK22')); ?>"/>
								<a href="javascript: void(0);" onClick="removeOriginAddress(<?php echo $i; ?>);">
									<i class="fa fa-times big"></i>
								</a>
							</div>
						<?php } ?>
					</div>

					<button type="button" class="btn" onClick="addOriginAddress();"><?php echo JText::_('VRMANAGECONFIGTK21'); ?></button>
				</td>
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
				$vik->initOptionElement(1, JText::_('VRCONFIGSENDMAILWHEN1'), $params['tkmailcustwhen'] == 1),
				$vik->initOptionElement(2, JText::_('VRCONFIGSENDMAILWHEN2'), $params['tkmailcustwhen'] == 2),
				$vik->initOptionElement(0, JText::_('VRCONFIGSENDMAILWHEN0'), $params['tkmailcustwhen'] == 0),
			);
			?>
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIG44"); ?></b> </td>
				<td><?php echo $vik->dropdown('tkmailcustwhen', $elements, '', 'medium'); ?></td>
			</tr>
			
			<!-- SEND TO EMPLOYEE WHEN - Dropdown -->
			<?php
			$elements = array(
				$vik->initOptionElement(1, JText::_('VRCONFIGSENDMAILWHEN1'), $params['tkmailoperwhen'] == 1),
				$vik->initOptionElement(2, JText::_('VRCONFIGSENDMAILWHEN2'), $params['tkmailoperwhen'] == 2),
				$vik->initOptionElement(0, JText::_('VRCONFIGSENDMAILWHEN0'), $params['tkmailoperwhen'] == 0),
			);
			?>
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIG45"); ?></b> </td>
				<td><?php echo $vik->dropdown('tkmailoperwhen', $elements, '', 'medium'); ?></td>
			</tr>
			
			<!-- SEND TO ADMIN WHEN - Dropdown -->
			<?php
			$elements = array(
				$vik->initOptionElement(1, JText::_('VRCONFIGSENDMAILWHEN1'), $params['tkmailadminwhen'] == 1),
				$vik->initOptionElement(2, JText::_('VRCONFIGSENDMAILWHEN2'), $params['tkmailadminwhen'] == 2),
				$vik->initOptionElement(0, JText::_('VRCONFIGSENDMAILWHEN0'), $params['tkmailadminwhen'] == 0),
			);
			?>
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIG46"); ?></b> </td>
				<td><?php echo $vik->dropdown('tkmailadminwhen', $elements, '', 'medium'); ?></td>
			</tr>
			
			<!-- TAKE-AWAY CUSTOMER EMAIL TEMPLATE -->
			<?php
			$elements = array();
			foreach( $all_tk_tmpl_files as $file ) {
				$file_name = str_replace("../components/com_cleverdine/helpers/tk_mail_tmpls/", "", $file);
				array_push($elements, $vik->initOptionElement($file, $file_name, $params['tkmailtmpl'] == $file_name));
			}
			?>
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIG47"); ?></b> </td>
				<td>
					<?php echo $vik->dropdown('tkmailtmpl', $elements, 'vr-tkemailtmpl-sel', 'large'); ?>
			
					<button type="button" id="tkemailtmpl" class="btn" onclick="vrOpenJModal(this.id, null, true); return false;" style="margin-left: 2px;">
						<i class="icon-pencil"></i>
					</button>
				</td>
			</tr>

			<!-- TAKE-AWAY ADMIN EMAIL TEMPLATE -->
			<?php
			$elements = array();
			foreach( $all_tk_tmpl_files as $file ) {
				$file_name = str_replace("../components/com_cleverdine/helpers/tk_mail_tmpls/", "", $file);
				array_push($elements, $vik->initOptionElement($file, $file_name, $params['tkadminmailtmpl'] == $file_name));
			}
			?>
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIG56"); ?></b> </td>
				<td>
					<?php echo $vik->dropdown('tkadminmailtmpl', $elements, 'vr-tkadminemailtmpl-sel', 'large'); ?>
			
					<button type="button" id="tkadminemailtmpl" class="btn" onclick="vrOpenJModal(this.id, null, true); return false;" style="margin-left: 2px;">
						<i class="icon-pencil"></i>
					</button>
				</td>
			</tr>

			<!-- TAKE-AWAY CANCELLATION EMAIL TEMPLATE -->
			<?php
			$elements = array();
			foreach( $all_tk_tmpl_files as $file ) {
				$file_name = str_replace("../components/com_cleverdine/helpers/tk_mail_tmpls/", "", $file);
				array_push($elements, $vik->initOptionElement($file, $file_name, $params['tkcancmailtmpl'] == $file_name));
			}
			?>
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIG57"); ?></b> </td>
				<td>
					<?php echo $vik->dropdown('tkcancmailtmpl', $elements, 'vr-tkcancemailtmpl-sel', 'large'); ?>
			
					<button type="button" id="tkcancemailtmpl" class="btn" onclick="vrOpenJModal(this.id, null, true); return false;" style="margin-left: 2px;">
						<i class="icon-pencil"></i>
					</button>
				</td>
			</tr>

			<!-- TAKE-AWAY REVIEW EMAIL TEMPLATE -->
			<?php
			$elements = array();
			foreach( $all_tk_tmpl_files as $file ) {
				$file_name = str_replace("../components/com_cleverdine/helpers/tk_mail_tmpls/", "", $file);
				array_push($elements, $vik->initOptionElement($file, $file_name, $params['tkreviewmailtmpl'] == $file_name));
			}
			?>
			<tr class="vrreviewstr" <?php echo ($params['enablereviews'] == "0" || $params['revtakeaway'] == "0" ? 'style="display:none;"' : ''); ?>>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIG67"); ?></b> </td>
				<td>
					<?php echo $vik->dropdown('tkreviewmailtmpl', $elements, 'vr-tkreviewemailtmpl-sel', 'large'); ?>
			
					<button type="button" id="tkreviewemailtmpl" class="btn" onclick="vrOpenJModal(this.id, null, true); return false;" style="margin-left: 2px;">
						<i class="icon-pencil"></i>
					</button>
				</td>
			</tr>

		</table>
	</div>

</div>

<!-- JQUERY MODALS -->

<div class="modal hide fade" id="jmodal-tkemailtmpl" style="width:90%;height:80%;margin-left:-45%;">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h3><?php echo JText::_('VRJMODALEMAILTMPL'); ?></h3>
	</div>
	<div id="jmodal-box-tkemailtmpl"></div>
</div>

<div class="modal hide fade" id="jmodal-tkadminemailtmpl" style="width:90%;height:80%;margin-left:-45%;">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h3><?php echo JText::_('VRJMODALEMAILTMPL'); ?></h3>
	</div>
	<div id="jmodal-box-tkadminemailtmpl"></div>
</div>

<div class="modal hide fade" id="jmodal-tkcancemailtmpl" style="width:90%;height:80%;margin-left:-45%;">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h3><?php echo JText::_('VRJMODALEMAILTMPL'); ?></h3>
	</div>
	<div id="jmodal-box-tkcancemailtmpl"></div>
</div>

<div class="modal hide fade" id="jmodal-tkreviewemailtmpl" style="width:90%;height:80%;margin-left:-45%;">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h3><?php echo JText::_('VRJMODALEMAILTMPL'); ?></h3>
	</div>
	<div id="jmodal-box-tkreviewemailtmpl"></div>
</div>

<div class="modal hide fade" id="jmodal-tkstockemailtmpl" style="width:90%;height:80%;margin-left:-45%;">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h3><?php echo JText::_('VRJMODALEMAILTMPL'); ?></h3>
	</div>
	<div id="jmodal-box-tkstockemailtmpl"></div>
</div>

<script type="text/javascript">

	jQuery(document).ready(function(){

		jQuery('#vrtk-confitem-sel').select2({
			placeholder: '<?php echo addslashes(JText::_('VRTKCONFIGITEMOPT0')); ?>',
			allowClear: true,
			width: 250
		});

		jQuery('#vrtk-confitem-sel').on('change', function(){
			if( jQuery(this).val() == "-1" ) {
				jQuery('input[name="tkconfitem_custom"]').show();
			} else {
				jQuery('input[name="tkconfitem_custom"]').hide();
			}
		});

		jQuery('#vr-deliveryservice-sel').on('change', function(){
			var val = jQuery(this).val();

			jQuery('input.delivery-param').prop('readonly', (val == 0 ? true : false));
			jQuery('select.delivery-param').prop('disabled', (val == 0 ? true : false));
			
			jQuery('input.pickup-param').prop('readonly', (val == 1 ? true : false));
			jQuery('select.pickup-param').prop('disabled', (val == 1 ? true : false));
		});

		// register email templates
		
		jQuery('#jmodal-tkemailtmpl').on('show', function() {
			tkEmailTmplOnShow();
		});

		jQuery('#jmodal-tkadminemailtmpl').on('show', function() {
			tkAdminEmailTmplOnShow();
		});

		jQuery('#jmodal-tkcancemailtmpl').on('show', function() {
			tkCancEmailTmplOnShow();
		});		

		jQuery('#jmodal-tkstockemailtmpl').on('show', function() {
			tkStockEmailTmplOnShow();
		});

		jQuery('#jmodal-tkreviewemailtmpl').on('show', function() {
			tkReviewEmailTmplOnShow();
		});

	});

	// handle first possible time

	function asapChanged() {
		var mins = parseInt(jQuery('#vrtkminselect').val());

		jQuery('#vrtkasapselect option').each(function(){
			jQuery(this).text((jQuery(this).val()*mins));
		});

		jQuery('#vrtkasapselect').select2('val', jQuery('#vrtkasapselect').val());
	}

	// handle login requirements

	function tkLoginRequirementsChanged() {
		var index = jQuery('#vrtkloginreqsel').val();

		if( index > 1 ) {
			jQuery('#vrtkenableregtr').show();
		} else {
			jQuery('#vrtkenableregtr').hide();
		}
	}

	// handle date allowed setting

	function tkAllowDateValueChanged(is) {
		if( is ) {
			jQuery('.tkallowdate-child').hide();
		} else {
			jQuery('.tkallowdate-child').show();
		}
	}

	// ORIGIN ADDRESSES

	var ORIGINS_CONT = <?php echo count($origins); ?>;

	// create a new origin

	function addOriginAddress() {
		jQuery('#adminForm #vroriginscont').append('<div id="vrorigin'+ORIGINS_CONT+'" style="margin-bottom: 5px;">\n'+
			'<input type="text" name="tkaddrorigins[]" value="" size="64" placeholder="<?php echo addslashes(JText::_('VRMANAGECONFIGTK22')); ?>"/>\n'+
			'<a href="javascript: void(0);" onClick="removeOriginAddress('+ORIGINS_CONT+');">\n'+
				'<i class="fa fa-times big"></i>\n'+
			'</a>\n'+
		'</div>\n');

		ORIGINS_CONT++;
	}

	// remove an existing origin

	function removeOriginAddress(index) {
		jQuery('#adminForm #vrorigin'+index).remove();
	}

	// toggle order columns

	function toggleTkListField(id, value) {
		jQuery('#vrtkhidden'+id).val(id+':'+value);
	}

	// email template on show

	function tkEmailTmplOnShow() {
		var href = 'index.php?option=com_cleverdine&task=managefile&file='+jQuery('#vr-tkemailtmpl-sel').val();
		var size = {
			width: jQuery('#jmodal-tkemailtmpl').width(), //940,
			height: jQuery('#jmodal-tkemailtmpl').height(), //590
		}
		appendModalContent('jmodal-box-tkemailtmpl', href, size);
	}

	// admin email template on show

	function tkAdminEmailTmplOnShow() {
		var href = 'index.php?option=com_cleverdine&task=managefile&file='+jQuery('#vr-tkadminemailtmpl-sel').val();
		var size = {
			width: jQuery('#jmodal-tkadminemailtmpl').width(), //940,
			height: jQuery('#jmodal-tkadminemailtmpl').height(), //590
		}
		appendModalContent('jmodal-box-tkadminemailtmpl', href, size);
	}

	// cancellation email template on show

	function tkCancEmailTmplOnShow() {
		var href = 'index.php?option=com_cleverdine&task=managefile&file='+jQuery('#vr-tkcancemailtmpl-sel').val();
		var size = {
			width: jQuery('#jmodal-tkcancemailtmpl').width(), //940,
			height: jQuery('#jmodal-tkcancemailtmpl').height(), //590
		}
		appendModalContent('jmodal-box-tkcancemailtmpl', href, size);
	}

	// stock email template on show

	function tkStockEmailTmplOnShow() {
		var href = 'index.php?option=com_cleverdine&task=managefile&file='+jQuery('#vr-tkstockemailtmpl-sel').val();
		var size = {
			width: jQuery('#jmodal-tkstockemailtmpl').width(), //940,
			height: jQuery('#jmodal-tkstockemailtmpl').height(), //590
		}
		appendModalContent('jmodal-box-tkstockemailtmpl', href, size);
	}

	// review email template on show

	function tkReviewEmailTmplOnShow() {
		var href = 'index.php?option=com_cleverdine&task=managefile&file='+jQuery('#vr-tkreviewemailtmpl-sel').val();
		var size = {
			width: jQuery('#jmodal-tkreviewemailtmpl').width(), //940,
			height: jQuery('#jmodal-tkreviewemailtmpl').height(), //590
		}
		appendModalContent('jmodal-box-tkreviewemailtmpl', href, size);
	}

</script>
