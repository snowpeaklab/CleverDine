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
			
			<!-- MENU NAME - Text -->
			<?php echo $vik->openControl(JText::_('VRMANAGELANG2').":"); ?>
				<input type="text" name="menu_name" value="<?php echo (empty($this->struct['lang_name']) ? $this->struct['name'] : $this->struct['lang_name']); ?>" size="48"/>
			<?php echo $vik->closeControl(); ?>
			
			<!-- MENU DESCRIPTION - Textarea -->
			<?php echo $vik->openControl(JText::_('VRMANAGELANG3').":"); ?>
				<textarea name="menu_description" style="width:450px;height:150px;"><?php echo (empty($this->struct['lang_description']) ? $this->struct['description'] : $this->struct['lang_description']); ?></textarea>
			<?php echo $vik->closeControl(); ?>
			
			<?php if( $this->type == "edit" ) { ?>
				<input type="hidden" name="id_lang_menu" value="<?php echo $this->struct['id_lang']; ?>"/>
			<?php } ?>
			
		<?php echo $vik->closeEmptyFieldset(); ?>
	</div>
	
	<?php foreach( $this->struct['sections'] as $section ) { ?>
		
		<div class="span11">
			<?php echo $vik->openFieldset(' ', 'form-horizontal'); ?>
			
				<!-- SECTION NAME - Text -->
				<?php echo $vik->openControl(JText::_('VRMANAGELANG2').":"); ?>
					<input type="text" name="section_name[]" value="<?php echo (empty($section['lang_name']) ? $section['name'] : $section['lang_name']); ?>" size="48"/>
				<?php echo $vik->closeControl(); ?>
				
				<!-- SECTION DESCRIPTION - Textarea -->
				<?php echo $vik->openControl(JText::_('VRMANAGELANG3').":"); ?>
					<textarea name="section_description[]" style="width:450px;height:150px;"><?php echo (empty($section['lang_description']) ? $section['description'] : $section['lang_description']); ?></textarea>
				<?php echo $vik->closeControl(); ?>
				
				<?php if( $this->type == "edit" ) { ?>
					<input type="hidden" name="id_lang_section[]" value="<?php echo $section['id_lang']; ?>"/>
				<?php } ?>
				<input type="hidden" name="id_section[]" value="<?php echo $section['id']; ?>"/>
				
				<div style="margin-left: 50px;">
					<?php foreach( $section['products'] as $product ) { ?>
						
						<!-- PRODUCT NAME - Text -->
						<?php echo $vik->openControl(JText::_('VRMANAGELANG2').":"); ?>
							<input type="text" name="product_name[]" value="<?php echo (empty($product['lang_name']) ? $product['name'] : $product['lang_name']); ?>" size="48"/>
						<?php echo $vik->closeControl(); ?>
						
						<!-- PRODUCT DESCRIPTION - Textarea -->
						<?php echo $vik->openControl(JText::_('VRMANAGELANG3').":"); ?>
							<textarea name="product_description[]" style="width:450px;height:150px;"><?php echo (empty($product['lang_description']) ? $product['description'] : $product['lang_description']); ?></textarea>
						<?php echo $vik->closeControl(); ?>
						
						<?php if( $this->type == "edit" ) { ?>
							<input type="hidden" name="id_lang_product[]" value="<?php echo $product['id_lang']; ?>"/>
						<?php } ?>
						<input type="hidden" name="id_product[]" value="<?php echo $product['id']; ?>"/>
						<input type="hidden" name="id_product_parent[]" value="<?php echo $section['id']; ?>"/>
						
						<div style="margin-left: 50px;">
							<?php foreach( $product['options'] as $option ) { ?>
								
								<!-- PRODUCT NAME - Text -->
								<?php echo $vik->openControl(JText::_('VRMANAGELANG2').":"); ?>
									<input type="text" name="option_name[]" value="<?php echo (empty($option['lang_name']) ? $option['name'] : $option['lang_name']); ?>" size="48"/>
								<?php echo $vik->closeControl(); ?>
								
								<?php if( $this->type == "edit" ) { ?>
									<input type="hidden" name="id_lang_option[]" value="<?php echo $option['id_lang']; ?>"/>
								<?php } ?>
								<input type="hidden" name="id_option[]" value="<?php echo $option['id']; ?>"/>
								<input type="hidden" name="id_option_parent[]" value="<?php echo $product['id']; ?>"/>
								
							<?php } ?>
						</div>
						
					<?php } ?>
				</div>
			
			<?php echo $vik->closeFieldset(); ?>
		</div>
		
	<?php } ?>
	
	<input type="hidden" name="id_menu" value="<?php echo $this->struct['id']; ?>"/>	
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