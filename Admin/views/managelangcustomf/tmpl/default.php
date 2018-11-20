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

$choose = (empty($this->struct['customf_lang_choose']) ? JText::_($this->struct['customf_choose']) : $this->struct['customf_lang_choose']);

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
			
			<!-- CUSTOM FIELD NAME - Text -->
			<?php echo $vik->openControl(JText::_('VRMANAGELANG2').":"); ?>
				<input type="text" name="customf_name" value="<?php echo (empty($this->struct['customf_lang_name']) ? JText::_($this->struct['customf_name']) : $this->struct['customf_lang_name']); ?>" size="48"/>
			<?php echo $vik->closeControl(); ?>

			<!-- CUSTOM FIELD CHOOSE - FORM -->
			<?php if( $this->struct['customf_type'] == 'text' ) { ?>
				
				<?php if( $this->struct['customf_rule'] == VRCustomFields::PHONE_NUMBER ) { ?>

					<?php echo $vik->openControl(JText::_('VRMANAGECUSTOMF10').":"); ?>
						<input type="text" name="customf_defprefix" value="<?php echo $choose; ?>" size="16"/>
					<?php echo $vik->closeControl(); ?>

				<?php } ?>

			<?php } else if( $this->struct['customf_type'] == 'select' && strlen($choose) ) { ?>
			
				<?php
				$options_list = explode(';;__;;', $choose);
				foreach( $options_list as $i => $v ) { 
					if( !empty($v) ) { ?>
						<?php echo $vik->openControl( ($i == 0 ? JText::_('VRMANAGECUSTOMF12').":" : '') ); ?>
							<input type="text" name="customf_choose[]" value="<?php echo $v; ?>" size="48" />
						<?php echo $vik->closeControl(); ?>
					<?php }
				} ?>

			<?php } ?>

			<!-- CUSTOM FIELD POPUP LINK - Text -->
			<?php if( $this->struct['customf_type'] == 'checkbox' ) { ?>
				<?php echo $vik->openControl(JText::_('VRMANAGECUSTOMF5').":"); ?>
						<input type="text" name="customf_poplink" value="<?php echo (empty($this->struct['customf_lang_poplink']) ? JText::_($this->struct['customf_poplink']) : $this->struct['customf_lang_poplink']); ?>" size="128"/>
					<?php echo $vik->closeControl(); ?>
			<?php } ?>
			
			<?php if( $this->type == "edit" ) { ?>
				<input type="hidden" name="id_lang_customf" value="<?php echo $this->struct['id_lang_customf']; ?>"/>
			<?php } ?>
			
		<?php echo $vik->closeEmptyFieldset(); ?>
	</div>
	
	<input type="hidden" name="id_customf" value="<?php echo $this->struct['id_customf']; ?>"/>	
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