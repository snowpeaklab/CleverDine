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

// this should be already loaded from autoload.php
UILoader::import('library.adapter.version.listener');

// include UIs
UILoader::import('library.ui.radio.ios');
UILoader::import('library.ui.radio.joomla');

/**
 * Helper class to adapt the application to the requirements of the installed Joomla.
 *
 * @see 	VersionListener 	Used to evaluate the current Joomla version.
 *
 * @since  	1.2
 */
	
class VikApplication
{	
	/**
	 * The identifier of the Joomla version.
	 *
	 * @var integer
	 *
	 * @deprecated use VersionListener::getID() instead.
	 */
	private $id = -1;
	
	/**
	 * Class constructor.
	 *
	 * @param 	integer 	$id 	The identifier of the Joomla version.
	 */
	public function __construct($id = null)
	{
		/**
		 * @deprecated unused property.
		 */
		$this->id = ($id !== null ? (int)$id : VersionListener::getID());
	}
	
	/**
	 * Backward compatibility for Joomla admin list <table> class.
	 *
	 * @return 	string 	The class selector to use.
	 */
	public function getAdminTableClass()
	{
		if (VersionListener::isJoomla25()) {
			// 2.5
			return "adminlist";
		} else {
			// 3.x
			return "table table-striped";
		}
	}
	
	/**
	 * Backward compatibility for Joomla admin list <table> head opening.
	 *
	 * @return 	string 	The <thead> tag to use.
	 */
	public function openTableHead()
	{
		if (VersionListener::isJoomla25()) {
			// 2.5
			return "";
		} else {
			// 3.x
			return "<thead>";
		}
	}
	
	/**
	 * Backward compatibility for Joomla admin list <table> head closing.
	 *
	 * @return 	string 	The </thead> tag to use.
	 */
	public function closeTableHead()
	{
		if (VersionListener::isJoomla25()) {
			// 2.5
			return "";
		} else {
			// 3.x
			return "</thead>";
		}
	}
	
	/**
	 * Backward compatibility for Joomla admin list <th> class.
	 *
	 * @param 	string 	$h_align 	The additional class to use for horizontal alignment.
	 *								Accepted rules should be: left, center or right.
	 *
	 * @return 	string 	The class selector to use.
	 */
	public function getAdminThClass($h_align = 'center')
	{
		if (VersionListener::isJoomla25()) {
			// 2.5
			return 'title';
		} else {
			// 3.x
			return 'title ' . $h_align;
		}
	}
	
	/**
	 * Backward compatibility for Joomla admin list checkAll JS event.
	 *
	 * @param 	integer 	The total count of rows in the table.	
	 *
	 * @return 	string 	The check all checkbox input to use.
	 */
	public function getAdminToggle($count)
	{
		if (VersionListener::isJoomla25()) {
			// 2.5
			return '<input type="checkbox" name="toggle" value="" onclick="checkAll('.$count.');" />';
		} else {
			// 3.x
			return '<input type="checkbox" onclick="Joomla.checkAll(this)" value="" name="checkall-toggle" />';
		}
	}
	
	/**
	 * Backward compatibility for Joomla admin list isChecked JS event.
	 *
	 * @return 	string 	The JS function to use.
	 */
	public function checkboxOnClick()
	{
		if (VersionListener::isJoomla25()) {
			// 2.5
			return 'isChecked(this.checked);';
		} else {
			// 3.x
			return 'Joomla.isChecked(this.checked);';
		}
	}

	/**
	 * Helper method to send e-mails.
	 *
	 * @param 	string 		$from_address	The e-mail address of the sender.
	 * @param 	string 		$from_name 		The name of the sender.
	 * @param 	string 		$to 			The e-mail address of the receiver.
	 * @param 	string 		$reply_address 	The reply to e-mail address.
	 * @param 	string 		$subject 		The subject of the e-mail.
	 * @param 	string 		$hmess 			The body of the e-mail (HTML is supported).
	 * @param 	array 		$attachments 	The list of the attachments to include.
	 * @param 	boolean 	$is_html 		True to support HTML body, otherwise false for plain text.
	 * @param 	string 		$encoding 		The encoding to use.
	 *
	 * @return 	boolean 	True if the e-mail was sent successfully, otherwise false.
	 */
	public function sendMail($from_address, $from_name, $to, $reply_address, $subject, $hmess, $attachments = null, $is_html = true, $encoding = 'base64')
	{
		$subject = '=?UTF-8?B?' . base64_encode($subject) . '?=';
		
		if ($is_html) {
			$hmess = '<html>'."\n".'<head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"></head>'."\n".'<body>'.$hmess.'</body>'."\n".'</html>';
		}
		
		$mailer = JFactory::getMailer();
		$sender = array($from_address, $from_name);
		$mailer->setSender($sender);
		$mailer->addRecipient($to);
		$mailer->addReplyTo($reply_address);
		$mailer->setSubject($subject);
		$mailer->setBody($hmess);
		$mailer->isHTML($is_html);
		$mailer->Encoding = $encoding;

		if ($attachments !== null && is_array($attachments)) {
			foreach ($attachments as $attach) {
				if (!empty($attach) && file_exists($attach)) {
					$mailer->addAttachment($attach);
				}
			}
		}

		return $mailer->Send();
	}
	
	/**
	 * Backward compatibility for Joomla add script.
	 *
	 * @param 	string 	$path 	The path of the script to add.
	 */
	public function addScript($path = '')
	{
		if (empty($path)) {
			return;
		}
		
		if (VersionListener::isJoomla25()) {
			$doc = JFactory::getDocument();
			$doc->addScript($path);
		} else {
			JHtml::_('script', $path);
		}
	}
	
	/**
	 * Backward compatibility for Joomla framework loading.
	 *
	 * @param 	string 	$fw 	The framework to load. 
	 */
	public function loadFramework($fw = '')
	{
		if (empty($fw)) {
			return;
		}
		
		if (VersionListener::isJoomla25()) {
			
		} else {
			JHtml::_($fw, true, true);
		}
	}
	
	/**
	 * Backward compatibility for punycode conversion.
	 *
	 * @param 	string 	$mail 	The e-mail to convert in punycode.
	 *
	 * @return 	string 	The punycode conversion of the e-mail.
	 *
	 * @since 	1.4
	 */
	public function emailToPunycode($email = '')
	{
		if (VersionListener::isJoomla25()) {
			// 2.5
			return $email;
		} else {
			// 3.x
			return JStringPunycode::emailToPunycode($email);
		}
	}
	
	/**
	 * Helper method to build a input radio object.
	 *
	 * @param 	string 		$id 		The id of the input.
	 * @param 	string 		$label 		The text of the label.
	 * @param 	boolean 	$checked 	True if the input is checked.
	 * @param 	string 		$htmlAttr 	The additional html attributes to include.
	 *
	 * @return 	object 	The object to represent radio inputs. 
	 *
	 * @since 	1.6
	 */
	public function initRadioElement($id = '', $label = '', $checked = false, $htmlAttr = '')
	{
		$elem = new stdClass();
		$elem->id 		= $id;
		$elem->label 	= $label;
		$elem->checked 	= $checked;
		$elem->htmlAttr = $htmlAttr;

		return $elem;
	}
	
	/**
	 * Helper method to build a select <option> object.
	 *
	 * @param 	string 		$value 		The value of the option.
	 * @param 	string 		$label 		The text of the option.
	 * @param 	boolean 	$selected 	True if the option is selected.
	 * @param 	boolean 	$isoptgrp 	True if the option is a <optgroup> tag.
	 * @param 	boolean 	$disabled 	True if the option is disabled.
	 * @param 	string 		$htmlAttr 	The additional html attributes to include.
	 *
	 * @return 	object 	The object to represent select options. 
	 *
	 * @since 	1.6
	 */
	public function initOptionElement($value, $label, $selected = false, $isoptgrp = false, $disabled = false, $htmlAttr = '')
	{
		$elem = new stdClass();
		$elem->value 	= $value;
		$elem->label 	= $label;
		$elem->selected = $selected;
		$elem->isoptgrp = $isoptgrp;
		$elem->disabled = $disabled;
		$elem->htmlAttr = $htmlAttr;

		return $elem;
	}
	
	/**
	 * Helper method to build a select <optgroup> object.
	 *
	 * @param 	string 	$label 	The text of the optgroup.
	 *
	 * @return 	object 	The object to represent select optgroups.
	 *
	 * @uses 	initOptionElement() 	Create an optgroup starting from an option.
	 *
	 * @since 	1.6
	 */
	public function getDropdownGroup($label)
	{
		return $this->initOptionElement("", $label, 0, true);
	}
	
	/**
	 * Helper method to build a tiny YES/NO radio button.
	 *
	 * @param 	string 		$name 		The name of the input.
	 * @param 	object 		$elem_1 	The first input object.
	 * @param 	object 		$elem_2 	The second input object.
	 * @param 	boolean 	wrapped 	True if the input is wrapped in a control class, otherwise false..
	 *
	 * @return 	string 	The html to display.
	 *
	 * @since 	1.6
	 */
	public function radioYesNo($name, $elem_1, $elem_2, $wrapped = true, $layout = null)
	{
		$elements 	= array($elem_1, $elem_2);
		$options 	= array('wrapped' => $wrapped);

		//

		if (!$layout) {
			// if not specified, get default layout from config
			$layout = UIFactory::getConfig()->get('uiradio', 'ios');
		}

		$radio_class = null;

		switch ($layout) {
			case 'ios':
				$radio_class = 'UIRadioIOS';
				break;

			default:
				$radio_class = 'UIRadioJoomla';
				
		}

		//

		$radio = new $radio_class($name, $elements, $options);

		return $radio->display();	
	}

	/**
	 * Helper method to build a normal HTML select.
	 *
	 * @param 	string 	$name 		The name of the select.
	 * @param 	array 	$elems 		The list containing all the option objects.
	 * @param 	string 	$id 		The ID attribute of the select.
	 * @param 	string 	$class 		The class attribute of the select.
	 * @param 	string 	htmlAttr 	The additional html attributes to include.
	 *
	 * @return 	string 	The html to display.
	 *
	 * @since 	1.6
	 */
	public function dropdown($name, $elems, $id = '', $class = '', $htmlAttr = '')
	{
		$first = true;
		$select = '<select name="'.$name.'" id="'.$id.'" class="vik-dropdown '.$class.'" '.$htmlAttr.'>';

		foreach ($elems as $elem) {
			if (!$elem->isoptgrp) {
				$select .= '<option value="'.$elem->value.'" '.($elem->selected ? 'selected="selected"' : '').($elem->disabled ? 'disabled' : '').' '.$elem->htmlAttr.'>'.$elem->label.'</option>';
			} else {
				if (!$first) {
					$select .= '</optgroup>';
				}
				$select .= '<optgroup label="'.$elem->label.'">';
				$first = false;
			}
		}
		if (!$first) {
			$select .= '</optgroup>';
		}
		$select .= '</select>';

		return $select;
	}
	
	/**
	 * Backward compatibility for Joomla fieldset opening.
	 *
	 * @param 	string 	$legend 	The title of the fieldset.
	 * @param 	string 	$class 		The class attribute for the fieldset.
	 *
	 * @return 	string 	The html to display.
	 *
	 * @since 	1.6
	 */
	public function openFieldset($legend, $class)
	{
		if (VersionListener::isJoomla25()) {
			// 2.5
			return (!empty($legend) ? '<h2>'.$legend.'</h2>' : '').'<table class="adminform">';
		} else {
			// 3.x
			return '<fieldset class="'.$class.'">
				<legend>'.$legend.'</legend>';
		}
	}
	
	/**
	 * Backward compatibility for Joomla fieldset closing.
	 *
	 * @return 	string 	The html to display.
	 *
	 * @since 	1.6
	 */
	public function closeFieldset()
	{
		if (VersionListener::isJoomla25()) {
			// 2.5
			return '</table>';
		} else {
			// 3.x
			return '</fieldset>';
		}
	}

	/**
	 * Backward compatibility for Joomla empty fieldset opening.
	 *
	 * @return 	string 	The html to display.
	 *
	 * @since 	1.6
	 */
	public function openEmptyFieldset()
	{
		if (VersionListener::isJoomla25()) {
			// 2.5
			return $this->openFieldset('', '');
		} else {
			// 3.x
			return '<div class="form-horizontal">';
		}
	}
	
	/**
	 * Backward compatibility for Joomla empty fieldset opening.
	 *
	 * @return 	string 	The html to display.
	 *
	 * @since 	1.6
	 */
	public function closeEmptyFieldset()
	{
		if (VersionListener::isJoomla25()) {
			// 2.5
			return $this->closeFieldset();
		} else {
			// 3.x
			return '</div>';
		}
	}
	
	/**
	 * Backward compatibility for Joomla control opening.
	 *
	 * @param 	string 	$label 	The label of the control field.
	 * @param 	string 	$class 	The class of the control field.
	 * @param 	string 	$attr 	The additional attributes to add.
	 *
	 * @return 	string 	The html to display.
	 *
	 * @since 	1.6
	 */
	public function openControl($label, $class = '', $attr = '')
	{
		if (VersionListener::isJoomla25()) {
			// 2.5
			return '<tr class="'.$class.'" '.$attr.'>
				<td width="200"><b>'.$label.'</b></td>
				<td>';
		} else {
			$class = 'control-group '.$class;
			// 3.x
			return '<div class="'.$class.'" '.$attr.'>
				<div class="control-label">
					<b>'.$label.'</b>
				</div>
				<div class="controls">';
		}
	}
	
	/**
	 * Backward compatibility for Joomla control closing.
	 *
	 * @return 	string 	The html to display.
	 *
	 * @since 	1.6
	 */
	public function closeControl()
	{
		if (VersionListener::isJoomla25()) {
			// 2.5
			return '</td></tr>';
		} else {
			// 3.x
			return '</div></div>';
		}
	}
	
	/**
	 * Returns the codemirror editor in Joomla 3.x, otherwise a simple textarea.
	 *
	 * @param 	string 	$name 	The name of the textarea.
	 * @param 	string 	$value 	The value of the textarea.
	 *
	 * @return 	string 	The html to display.
	 *
	 * @since 	1.6
	 */
	public function getCodeMirror($name, $value)
	{
		if (VersionListener::isJoomla25()) {
			// 2.5
			return '<textarea name="'.$name.'" style="width: 100%;height: 520px;">'.$value.'</textarea>';
		} else {
			// 3.x
			return JEditor::getInstance('codemirror')->display( $name, $value, '600', '600', 30, 30, false );
		}
	}
	
	/**
	 * Backward compatibility for Joomla Bootstrap tabset opening.
	 *
	 * @param 	string 	$group 	The group of the tabset.
	 * @param 	string 	$attr 	The attributes to use.
	 *
	 * @return 	string 	The html to display.
	 *
	 * @since 	1.6
	 */
	public function bootStartTabSet($group, $attr = array())
	{
		if (VersionListener::isJoomla25()) {
			// 2.5
			return '';
		} else {
			// 3.x
			return JHtml::_('bootstrap.startTabSet', $group, $attr);
		}
	}
	
	/**
	 * Backward compatibility for Joomla Bootstrap tabset closing.
	 *
	 * @return 	string 	The html to display.
	 *
	 * @since 	1.6
	 */
	public function bootEndTabSet()
	{
		if (VersionListener::isJoomla25()) {
			// 2.5
			return '';
		} else {
			// 3.x
			return JHtml::_('bootstrap.endTabSet');
		}
	}
	
	/**
	 * Backward compatibility for Joomla Bootstrap add tab.
	 *
	 * @param 	string 	$group 	The tabset parent group.
	 * @param 	string 	$id 	The id of the tab.
	 * @param 	string 	$label 	The title of the tab.
	 *
	 * @return 	string 	The html to display.
	 *
	 * @since 	1.6
	 */
	public function bootAddTab($group, $id, $label)
	{
		if (VersionListener::isJoomla25()) {
			// 2.5
			return '<h3>'.$label.'</h3>';
		} else {
			// 3.x
			return JHtml::_('bootstrap.addTab', $group, $id, $label);
		}
	}
	
	/**
	 * Backward compatibility for Joomla Bootstrap end tab.
	 *
	 * @return 	string 	The html to display.
	 *
	 * @since 	1.6
	 */
	public function bootEndTab()
	{
		if (VersionListener::isJoomla25()) {
			// 2.5
			return '';
		} else {
			// 3.x
			return JHtml::_('bootstrap.endTab');
		}
	}
	
	/**
	 * Backward compatibility for Joomla Bootstrap open modal JS event.
	 *
	 * @param 	string 	$onclose 	The javascript function to call on close event.
	 *
	 * @return 	string 	The javascript function.
	 *
	 * @since 	1.6
	 */
	public function bootOpenModalJS($onclose = '')
	{
		if (VersionListener::isJoomla25()) {
			// 2.5
			return "jQuery('#jmodal-' + id).css('marginLeft', '0px');
				jQuery('.modal-header .close').hide();
				jQuery('#jmodal-' + id).dialog({
					resizable: true,
					height: 600,
					width: 750,
					".(!empty($close) ? "close:$onclose," : "")."
					modal: true
				});
				jQuery('#jmodal-' + id).trigger('show');
				return false;";
		} else {
			// 3.x
			return "jQuery('#jmodal-' + id).modal('show');
				if(url) {
					jQuery('#jmodal-box-'+id).find('iframe').attr('src', url);
				}
				".(!empty($onclose) ? "jQuery('#jmodal-'+id).on('hidden', ".$onclose.");" : "")."
				return false;";
		}
	}
	
	/**
	 * Backward compatibility for Joomla Bootstrap dismiss modal JS event.
	 *
	 * @param 	string 	$selector 	The selector to identify the modal box.
	 *
	 * @return 	string 	The javascript function.
	 *
	 * @since 	1.6
	 */
	public function bootDismissModalJS($selector)
	{
		if (VersionListener::isJoomla25()) {
			// 2.5
			return "jQuery('$selector').dialog('close');";
		} else {
			// 3.x
			return "jQuery('$selector').modal('toggle');";
		}
	}

	/**
	 * Backward compatibility to fit the layout of the left main menu.
	 *
	 * @param 	JDocument 	$document 	The base Joomla document.
	 *
	 * @since 	1.7
	 */
	public function fixContentPadding($document)
	{
		if (VersionListener::isJoomla25()) {
			// 2.5
			$document->addStyleDeclaration('/* main menu adapter */.vre-leftboard-menu .title a {color: #fff !important;}.vre-leftboard-menu .custom a {color: #fff !important;}');
		} else {
			// 3.x
			$document->addStyleDeclaration('/* main menu adapter */.subhead-collapse{margin-bottom: 0 !important;}.container-fluid.container-main{margin: 0 !important;padding: 0 !important;}#system-message-container{padding: 0px 5px 0 5px;}#system-message-container .alert{margin-top: 10px;}');
		}
	}

	/**
	 * Add javascript support for Bootstrap popovers.
	 *
	 * @param 	string 	$selector   Selector for the popover.
	 * @param 	array 	$options     An array of options for the popover.
	 * 					Options for the popover can be:
	 * 						animation  boolean          apply a css fade transition to the popover
	 *                      html       boolean          Insert HTML into the popover. If false, jQuery's text method will be used to insert
	 *                                                  content into the dom.
	 *                      placement  string|function  how to position the popover - top | bottom | left | right
	 *                      selector   string           If a selector is provided, popover objects will be delegated to the specified targets.
	 *                      trigger    string           how popover is triggered - hover | focus | manual
	 *                      title      string|function  default title value if `title` tag isn't present
	 *                      content    string|function  default content value if `data-content` attribute isn't present
	 *                      delay      number|object    delay showing and hiding the popover (ms) - does not apply to manual trigger type
	 *                                                  If a number is supplied, delay is applied to both hide/show
	 *                                                  Object structure is: delay: { show: 500, hide: 100 }
	 *                      container  string|boolean   Appends the popover to a specific element: { container: 'body' }
	 *
	 * @since 	1.7
	 */
	public function attachPopover($selector = '.vrPopover', array $options = array())
	{
		if (VersionListener::isJoomla25()) {
			// 2.5
			JFactory::getDocument()->addStyleDeclaration('jQuery(document).ready(function(){
				jQuery('.$selector.').tooltip();
			}');
		} else {
			// 3.x
			JHtml::_('bootstrap.popover', $selector, $options);
		}
	}

	/**
	 * Create a standard tag and attach a popover event.
	 * NOTE. FontAwesome framework MUST be loaded in order to work.
	 *
	 * @param 	string 	$selector   Selector for the popover.
	 * @param 	array 	$options     An array of options for the popover.
	 *
	 * @see 	VikApplication::attachPopover() for further details about options keys.
	 *
	 * @since 	1.7
	 */
	public function createPopover(array $options = array())
	{
		$options['content'] = isset($options['content']) ? $options['content'] : '';

		// attach an empty array option so that the data will be recovered 
		// directly from the tag during the runtime
		$this->attachPopover(".vr-quest-popover", array());

		if (VersionListener::isJoomla25()) {
			// 2.5
			return '<i class="fa fa-question-circle vr-quest-popover" title="'.$options['content'].'"></i>';
		} else {
			// 3.x
			$attr = '';
			foreach ($options as $k => $v) {
				$attr .= 'data-'.$k.'="'.str_replace('"', '&quot;', $v).'" ';
			}

			return '<i class="fa fa-question-circle vr-quest-popover" '.$attr.'></i>';
		}
	}

	/**
	 * Return the Joomla date format specs.
	 *
	 * @param 	string 	$format 	The format to use.
	 *
	 * @return 	string 	The adapted date format.
	 *
	 * @since 	1.7.1
	 */
	public function jdateFormat($format = null)
	{
		if ($format === null) {
			$format = UIFactory::getConfig()->getString('dateformat');
		}

		$format = str_replace('Y', '%Y', $format);
		$format = str_replace('m', '%m', $format);
		$format = str_replace('d', '%d', $format);

		return $format;
	}

	/**
	 * Provides support to handle the Joomla calendar across different frameworks.
	 *
	 * @param 	string 	$value 		 The date to fill.
	 * @param 	string 	$name 		 The input name.
	 * @param 	string 	$id 		 The input id attribute.
	 * @param 	string 	$format 	 The date format.
	 * @param 	array 	$attributes  Some attributes to use.
	 * 
	 * @return 	string 	The calendar field.
	 *
	 * @since 	1.7.1
	 */
	public function calendar($value, $name, $id = null, $format = null, array $attributes = array())
	{
		$html = '';

		if ($id === null) {
			$id = $name;
		}

		if ($format === null) {
			$format = $this->jdateFormat();
		}

		if (VersionListener::isJoomla37() || VersionListener::isHigherThan(VersionListener::J37)) {

			// make sure to display the clear | today | close buttons
			$attributes['todayBtn'] = isset($attributes['todayBtn']) ? $attributes['todayBtn'] : 'true';

			$html = JHtml::_('calendar', $value, $name, $id, $format, $attributes);

			// if the value if set, make sure it has been filled in
			if ($value) {

				// Considering that the Joomla validation may not recognize the 
				// specified format, we need to fill manually the value via Javascript 
				// if the datepicker field is empty.
				JFactory::getDocument()->addScriptDeclaration("jQuery(document).ready(function(){
					if (jQuery('#$id').val().length == 0) {
						jQuery('#$id').val('$value').attr('data-alt-value', '$value');
					}
				});");

			}

		} else {

			$html = JHtml::_('calendar', '', $name, $id, $format, $attributes);

			if (isset($attributes['onChange'])) {

				JFactory::getDocument()->addScriptDeclaration("jQuery('#{$id}_img').on('change', function(){
					jQuery('.day').on('change', function(){
						{$attributes['onChange']}
					});
				});");

				// remove to avoid duplicated events
				unset($attributes['onChange']);
			}

			if (!empty($value)) {
				JFactory::getDocument()->addScriptDeclaration("jQuery(document).on('ready', function(){
					jQuery('#{$id}').val('$value');
				});");
			}

		}

		return $html;
	}

	/**
	 * Method used to attache in the document <head>
	 * the jQuery datepicker regional properties.
	 *
	 * @return 	void
	 *
	 * @since 	1.0
	 */
	public function attachDatepickerRegional()
	{
		static $loaded = 0;

		if ($loaded)
		{
			return;
		}

		$loaded = 1;

		// Labels
		$done 	= JText::_('VRJQCALDONE');
		$prev 	= JText::_('VRJQCALPREV');
		$next 	= JText::_('VRJQCALNEXT');
		$today 	= JText::_('VRJQCALTODAY');
		$wk 	= JText::_('VRJQCALWKHEADER');

		// Months
		$months = array(
			JText::_('JANUARY'),
			JText::_('FEBRUARY'),
			JText::_('MARCH'),
			JText::_('APRIL'),
			JText::_('MAY'),
			JText::_('JUNE'),
			JText::_('JULY'),
			JText::_('AUGUST'),
			JText::_('SEPTEMBER'),
			JText::_('OCTOBER'),
			JText::_('NOVEMBER'),
			JText::_('DECEMBER'),
		);

		$months_short = array(
			JText::_('JANUARY_SHORT'),
			JText::_('FEBRUARY_SHORT'),
			JText::_('MARCH_SHORT'),
			JText::_('APRIL_SHORT'),
			JText::_('MAY_SHORT'),
			JText::_('JUNE_SHORT'),
			JText::_('JULY_SHORT'),
			JText::_('AUGUST_SHORT'),
			JText::_('SEPTEMBER_SHORT'),
			JText::_('OCTOBER_SHORT'),
			JText::_('NOVEMBER_SHORT'),
			JText::_('DECEMBER_SHORT'),
		);

		$months 		= json_encode($months);
		$months_short 	= json_encode($months_short);

		// Days
		$days = array(
			JText::_('SUNDAY'),
			JText::_('MONDAY'),
			JText::_('TUESDAY'),
			JText::_('WEDNESDAY'),
			JText::_('THURSDAY'),
			JText::_('FRIDAY'),
			JText::_('SATURDAY'),
		);

		$days_short_3 = array(
			JText::_('SUN'),
			JText::_('MON'),
			JText::_('TUE'),
			JText::_('WED'),
			JText::_('THU'),
			JText::_('FRI'),
			JText::_('SAT'),
		);

		$days_short_2 = array();
		foreach ($days_short_3 as $d)
		{
			$days_short_2[] = mb_substr($d, 0, 2, 'UTF-8');
		}

		// snippet used to make sure the substring of
		// the week days doesn't return the same value (see Hebrew)
		// for all the elements
		$days_short_2 = array_unique($days_short_2);
		if (count($days_short_2) != 7)
		{
			$days_short_2 = $days_short_3;
		}

		$days 			= json_encode($days);
		$days_short_3 	= json_encode($days_short_3);
		$days_short_2 	= json_encode($days_short_2);

		$lang = JFactory::getLanguage();

		// should return a value between 0-6 (1: Monday, 0: Sunday)
		$start_of_week  = $lang->getFirstDay();
		$is_rtl 		= $lang->isRtl() ? 'true' : 'false';

		JFactory::getDocument()->addScriptDeclaration(
<<<JS
jQuery(function($){
	$.datepicker.regional["cleverdine"] = {
		closeText: "$done",
		prevText: "$prev",
		nextText: "$next",
		currentText: "$today",
		monthNames: $months,
		monthNamesShort: $months_short,
		dayNames: $days,
		dayNamesShort: $days_short_3,
		dayNamesMin: $days_short_2,
		weekHeader: "$wk",
		firstDay: $start_of_week,
		isRTL: $is_rtl,
		showMonthAfterYear: false,
		yearSuffix: ""
	};

	$.datepicker.setDefaults($.datepicker.regional["cleverdine"]);
});
JS
		);
	}
	
	/*
	### Backward compatibility usage ###
	Follow the example below to understand how to declare a method.

	public function _name(_params,...)
	{
		if (VersionListener::isJoomla25()) {
			// 2.5
			
		} else {
			// 3.x
		
		}
	}
	*/
	
}
