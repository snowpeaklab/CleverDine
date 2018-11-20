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

// load calendar behavior
JHTML::_('behavior.calendar');

$sel = null;
$id = -1;
if( !count( $this->selectedReview ) ) {
	$sel = array(
		'title' => '', 'name' => '', 'email' => '', 'jid' => -1, 'comment' => '', 'published' => 0, 'verified' => 0,
		'timestamp' => time(), 'rating' => 5, 'langtag' => 'en-GB', 'id_takeaway_product' => -1
	);
} else {
	$sel = $this->selectedReview;
	$id = $sel['id'];
}

$date_format = cleverdine::getDateFormat(true);

$vik = new VikApplication(VersionListener::getID());

$languages = cleverdine::getKnownLanguages();

if( $sel['jid'] <= 0 ) {
	$sel['jid'] = '';
}

?>

<form name="adminForm" action="index.php" method="post" enctype="multipart/form-data" id="adminForm">
	
	<div class="span8">
		<?php echo $vik->openEmptyFieldset(); ?>
			
			<!-- TITLE - Text -->
			<?php echo $vik->openControl(JText::_('VRMANAGEREVIEW2').'*:'); ?>
				<input type="text" name="title" class="required" value="<?php echo $sel['title']; ?>" size="40"/>
			<?php echo $vik->closeControl(); ?>
			
			<!-- USER - Dropdown -->
			<?php echo $vik->openControl(JText::_('VRMANAGEREVIEW10').':'); ?>
				<input type="hidden" name="jid" class="vr-users-select" value="<?php echo $sel['jid']; ?>"/>
			<?php echo $vik->closeControl(); ?>
			
			<!-- USER NAME - Text -->
			<?php echo $vik->openControl(JText::_('VRMANAGEREVIEW3').'*:'); ?>
				<input class="required" id="user-name" type="text" name="name" value="<?php echo $sel['name']; ?>" size="40"/>
			<?php echo $vik->closeControl(); ?>
			
			<!-- USER EMAIL - Text -->
			<?php echo $vik->openControl(JText::_('VRMANAGEREVIEW11').'*:'); ?>
				<input class="required" id="user-mail" type="text" name="email" value="<?php echo $sel['email']; ?>" size="40"/>
			<?php echo $vik->closeControl(); ?>
			
			<!-- DATE - Date -->
			<?php echo $vik->openControl(JText::_('VRMANAGEREVIEW4').'*:'); ?>
				<?php echo $vik->calendar(date($date_format, $sel['timestamp']), 'timestamp', 'timestamp', null, array('class' => 'required')); ?>
				<input type="hidden" name="hour" value="<?php echo date('H', $sel['timestamp']); ?>"/>
				<input type="hidden" name="min" value="<?php echo date('i', $sel['timestamp']); ?>"/>
			<?php echo $vik->closeControl(); ?>
			
			<!-- RATING - Dropdown -->
			<?php 
			$elements = array();
			for( $i = 5; $i > 0; $i-- ) {
				array_push($elements, $vik->initOptionElement($i, $i.' '.strtolower(JText::_($i > 1 ? 'VRSTARS' : 'VRSTAR')), $sel['rating'] == $i));
			}
			?>
			<?php echo $vik->openControl(JText::_('VRMANAGEREVIEW5').'*:'); ?>
				<?php echo $vik->dropdown('rating', $elements, '', 'medium'); ?>
			<?php echo $vik->closeControl(); ?>
			
			<!-- PUBLISHED - Radio Button -->
			<?php
			$elem_yes = $vik->initRadioElement('', JText::_('VRYES'), $sel['published'] == 1);
			$elem_no = $vik->initRadioElement('', JText::_('VRNO'), $sel['published'] == 0);
			?>
			<?php echo $vik->openControl(JText::_('VRMANAGEREVIEW7').':'); ?>
				<?php echo $vik->radioYesNo('published', $elem_yes, $elem_no, false); ?>
			<?php echo $vik->closeControl(); ?>

			<!-- VERIFIED - Radio Button -->
			<?php
			$elem_yes = $vik->initRadioElement('', $elem_yes->label, $sel['verified'] == 1);
			$elem_no = $vik->initRadioElement('', $elem_no->label, $sel['verified'] == 0);
			?>
			<?php echo $vik->openControl(JText::_('VRMANAGEREVIEW12').':'); ?>
				<?php echo $vik->radioYesNo('verified', $elem_yes, $elem_no, false); ?>
			<?php echo $vik->closeControl(); ?>
			
			<!-- PRODUCT - Dropdown -->
			<?php
			$elements = array(
				$vik->initOptionElement('', '', false)
			);

			foreach( $this->products as $menu ) {
				$elements[] = $vik->initOptionElement('', $menu['title'], false, true);
				foreach( $menu['items'] as $item ) {
					$elements[] = $vik->initOptionElement($item['id'], $item['name'], $item['id'] == $sel['id_takeaway_product']);
				}
			}
			?>
			<?php echo $vik->openControl(JText::_('VRMANAGEREVIEW6').'*:'); ?>
				<?php echo $vik->dropdown('id_takeaway_product', $elements, 'vr-product-sel', 'required'); ?>
			<?php echo $vik->closeControl(); ?>
			
			<!-- LANGUAGE - Dropdown -->
			<?php
			$elements = array();
			foreach( $languages as $lang ) {
				array_push($elements, $vik->initOptionElement($lang, $lang, $sel['langtag'] == $lang));
			}
			?>
			<?php echo $vik->openControl(JText::_('VRMANAGEREVIEW8').':'); ?>
				<?php echo $vik->dropdown('langtag', $elements, '', 'medium'); ?>
			<?php echo $vik->closeControl(); ?>
			
			<!-- COMMENT - Textarea -->
			<?php echo $vik->openControl(JText::_('VRMANAGEREVIEW9').':'); ?>
				<textarea name="comment" style="width:100%;height:180px;"><?php echo $sel['comment']; ?></textarea>
			<?php echo $vik->closeControl(); ?>
			
		<?php echo $vik->closeEmptyFieldset(); ?>
	</div>
	
	<?php foreach( $this->filters as $k => $v ) {
		if( !empty($v) ) { ?>
			<input type="hidden" name="<?php echo $k; ?>" value="<?php echo $v; ?>"/>
		<?php }
	} ?>

	<input type="hidden" name="id" value="<?php echo $id; ?>"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="option" value="com_cleverdine"/>
</form>

<script type="text/javascript">

	var USERS_POOL = [];

	jQuery(document).ready(function(){

		jQuery('.vik-dropdown.medium').select2({
			minimumResultsForSearch: -1,
			allowClear: false,
			width: 150
		});

		jQuery('#vr-product-sel').select2({
			placeholder: '--',
			allowClear: false,
			width: 300
		});

		jQuery(".vr-users-select").select2({
			placeholder: '<?php echo addslashes(JText::_('VRMANAGECUSTOMER15')); ?>',
			allowClear: true,
			width: 300,
			minimumInputLength: 2,
			ajax: {
				url: 'index.php?option=com_cleverdine&task=search_jusers&tmpl=component&id=<?php echo $sel['jid']; ?>',
				dataType: 'json',
				type: "POST",
				quietMillis: 50,
				data: function(term) {
					return {
						term: term
					};
				},
				results: function(data) {
					return {
						results: jQuery.map(data, function (item) {
							if( jQuery.isEmptyObject(USERS_POOL[item.id]) ) {
								USERS_POOL[item.id] = item;
							}

							return {
								text: item.name,
								id: item.id
							}
						})
					};
				},
			},
			initSelection: function(element, callback) {
				// the input tag has a value attribute preloaded that points to a preselected repository's id
				// this function resolves that id attribute to an object that select2 can render
				// using its formatResult renderer - that way the repository name is shown preselected
				
				if( jQuery(element).val().length ) {
					callback({name: '<?php echo (empty($this->juser['name']) ? '' : addslashes($this->juser['name'])); ?>'});
				}
			},
			formatSelection: function(data) {
				if( jQuery.isEmptyObject(data.name) ) {
					// display data retured from ajax parsing
					return data.text;
				}
				// display pre-selected value
				return data.name;
			},
			dropdownCssClass: "bigdrop",
		});

		jQuery(".vr-users-select").on('change', function(){
			var id = jQuery(this).val();

			if( !jQuery.isEmptyObject(USERS_POOL[id]) ) {
				jQuery('#user-name').val(USERS_POOL[id].name);
				jQuery('#user-mail').val(USERS_POOL[id].email);
			}
		});

		jQuery("#adminForm .required").on("blur", function(){
			if( jQuery(this).val().length > 0 ) {
				jQuery(this).removeClass("vrrequired");
			} else {
				jQuery(this).addClass("vrrequired");
			}
		});

		jQuery("#user-mail").on("blur", function(){
			validateMailField();
		});

	});

	// validation

	function validateMailField() {
		var email = jQuery('#user-mail').val();

		var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		if( re.test(email) ) {
			jQuery('#user-mail').removeClass('vrrequired');
			return true;
		}

		jQuery('#user-mail').addClass('vrrequired');
		return false;
	}

	function vrValidateFields() {
		var ok = true;
		jQuery("#adminForm .required:input").each(function(){
			var val = jQuery(this).val();
			if( val !== null && val.length > 0 ) {
				jQuery(this).removeClass("vrrequired");
			} else {
				jQuery(this).addClass("vrrequired");
				ok = false;
			}
		});

		return validateMailField() && ok;
	}

	Joomla.submitbutton = function(task) {
		if( task.indexOf('save') !== -1 ) {
			if( vrValidateFields() ) {
				Joomla.submitform(task, document.adminForm);	
			}
		} else {
			Joomla.submitform(task, document.adminForm);
		}
	}

</script>