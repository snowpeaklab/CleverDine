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

$vik = new VikApplication(VersionListener::getID());

if( empty($this->struct['tag']) ) {
	$this->struct['tag'] = '';
}

?>

<form name="adminForm" action="index.php" method="post" enctype="multipart/form-data" id="adminForm">
	
	<div></div>
	
	<div class="span11">
		<?php echo $vik->openEmptyFieldset(); ?>
		
			<!-- LANGUAGE - Dropdown -->
			<?php
			$elements = array();
			foreach( cleverdine::getKnownLanguages() as $lang ) {
				array_push($elements, $vik->initOptionElement($lang, $lang, $lang==$this->struct['tag']));
			}
			?>
			<?php echo $vik->openControl(JText::_('VRMANAGELANG4')."*:"); ?>
				<?php echo $vik->dropdown('tag', $elements); ?>
			<?php echo $vik->closeControl(); ?>
			
			<!-- PAYMENT NAME - Text -->
			<?php echo $vik->openControl(JText::_('VRMANAGELANG2').":"); ?>
				<input type="text" name="payment_name" value="<?php echo (empty($this->struct['payment_lang_name']) ? $this->struct['payment_name'] : $this->struct['payment_lang_name']); ?>" size="48"/>
			<?php echo $vik->closeControl(); ?>

			<!-- PAYMENT PRENOTE - Textarea -->
			<?php echo $vik->openControl(JText::_('VRMANAGEPAYMENT11').":"); ?>
				<textarea name="payment_prenote" style="width:450px;height:150px;"><?php echo (empty($this->struct['payment_lang_prenote']) ? $this->struct['payment_prenote'] : $this->struct['payment_lang_prenote']); ?></textarea>
			<?php echo $vik->closeControl(); ?>

			<!-- PAYMENT NOTE - Textarea -->
			<?php echo $vik->openControl(JText::_('VRMANAGEPAYMENT7').":"); ?>
				<textarea name="payment_note" style="width:450px;height:150px;"><?php echo (empty($this->struct['payment_lang_note']) ? $this->struct['payment_note'] : $this->struct['payment_lang_note']); ?></textarea>
			<?php echo $vik->closeControl(); ?>
			
			<?php if( $this->type == "edit" ) { ?>
				<input type="hidden" name="id_lang_payment" value="<?php echo $this->struct['id_lang_payment']; ?>"/>
			<?php } ?>
			
		<?php echo $vik->closeEmptyFieldset(); ?>
	</div>
	
	<input type="hidden" name="id_payment" value="<?php echo $this->struct['id_payment']; ?>"/>	
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="option" value="com_cleverdine"/>
</form>

<script>
	
	jQuery(document).ready(function(){
		jQuery('.vik-dropdown').select2({
			allowClear: false,
			width: 200,
			minimumResultsForSearch: -1,
			formatResult: formatFlags,
			formatSelection: formatFlags,
			escapeMarkup: function(m) { return m; }
		});
	});
	
	function formatFlags(state) {
		if(!state.id) return state.text; // optgroup

		return '<img class="vr-opt-flag" src="<?php echo JUri::root(); ?>components/com_cleverdine/assets/css/flags/' + state.id.toLowerCase().split("-")[1] + '.png"/>' + state.text;
	}
	
</script>