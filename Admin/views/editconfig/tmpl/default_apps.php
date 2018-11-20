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

$elem_yes = $vik->initRadioElement();
$elem_no = $vik->initRadioElement();

?>

<!-- LEFT SIDE -->

<div class="config-left-side">

	<!-- APIs -->

	<div class="config-fieldset">
		<div class="config-fieldset-legend"><?php echo JText::_('VRCONFIGFIELDSETAPIFR'); ?></div>
		<table class="admintable table" cellspacing="1">
			
			<!-- API FRAMEWORK -->

			<?php
			$elem_yes = $vik->initRadioElement('', $elem_yes->label, $params['apifw'] == '1', 'onClick="toggleApiFrameworkFields(1);"');
			$elem_no = $vik->initRadioElement('', $elem_no->label, $params['apifw'] == '0', 'onClick="toggleApiFrameworkFields(0);"');
			?>
			<tr>
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIG69"); ?></b> </td>
				<td>
					<?php echo $vik->radioYesNo("apifw", $elem_yes, $elem_no); ?>
				</td>
			</tr>

			<!-- API MAX FAILURE ATTEMPTS -->

			<tr class="vr-apifw-field" style="<?php echo ($params['apifw'] == "1" ? '' : 'display:none;'); ?>">
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIG74"); ?></b> </td>
				<td>
					<input type="number" name="apimaxfail" value="<?php echo $params['apimaxfail']; ?>" min="1" step="1" />
					<?php
					echo $vik->createPopover(array(
						'title' => JText::_("VRMANAGECONFIG74"),
						'content' => JText::_("VRMANAGECONFIG75")
					));
					?>
				</td>
			</tr>

			<!-- API LOG MODE -->

			<?php
			$elements = array();
			for( $i = 0; $i < 3; $i++ ) {
				$elements[] = $vik->initOptionElement($i, JText::_('VRCONFIGAPIREGLOGOPT'.$i), $params['apilogmode'] == $i);
			}
			?>

			<tr class="vr-apifw-field" style="<?php echo ($params['apifw'] == "1" ? '' : 'display:none;'); ?>">
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIG72"); ?></b> </td>
				<td>
					<?php echo $vik->dropdown("apilogmode", $elements, '', 'medium'); ?>
				</td>
			</tr>

			<!-- API LOG MODE -->

			<?php
			$elements = array();
			$elements[] = $vik->initOptionElement( 1, JText::_('VRCONFIGAPIFLUSHLOGOPT1'), $params['apilogflush'] == 1 );
			$elements[] = $vik->initOptionElement( 7, JText::_('VRCONFIGAPIFLUSHLOGOPT2'), $params['apilogflush'] == 7 );
			$elements[] = $vik->initOptionElement(30, JText::_('VRCONFIGAPIFLUSHLOGOPT3'), $params['apilogflush'] == 30);
			$elements[] = $vik->initOptionElement( 0, JText::_('VRCONFIGAPIFLUSHLOGOPT0'), $params['apilogflush'] == 0 );
			?>

			<tr class="vr-apifw-field" style="<?php echo ($params['apifw'] == "1" ? '' : 'display:none;'); ?>">
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIG73"); ?></b> </td>
				<td>
					<?php echo $vik->dropdown("apilogflush", $elements, '', 'medium'); ?>
				</td>
			</tr>

			<!-- SEE USERS LIST -->

			<tr class="vr-apifw-field" style="<?php echo ($params['apifw'] == "1" ? '' : 'display:none;'); ?>">
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIG70"); ?></b> </td>
				<td>
					<a href="index.php?option=com_cleverdine&task=apiusers" class="btn"><?php echo JText::_("VRMANAGECONFIG71"); ?></a>
				</td>
			</tr>

			<!-- SEE USERS LIST -->

			<tr class="vr-apifw-field" style="<?php echo ($params['apifw'] == "1" ? '' : 'display:none;'); ?>">
				<td width="200" class="adminparamcol"> <b><?php echo JText::_("VRMANAGECONFIG76"); ?></b> </td>
				<td>
					<a href="index.php?option=com_cleverdine&task=apiplugins" class="btn"><?php echo JText::_("VRMANAGECONFIG77"); ?></a>
				</td>
			</tr>
		
		</table>
	</div>

</div>

<!-- RIGHT SIDE -->

<div class="config-right-side">

</div>

<script type="text/javascript">

	// toggle api fields

	function toggleApiFrameworkFields(is) {

		if( is ) {
			jQuery('.vr-apifw-field').show();
		} else {
			jQuery('.vr-apifw-field').hide();
		}

	}

</script>
