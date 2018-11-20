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

// load parent class
UILoader::import('library.ui.radio.radio');

/**
 * This class provides the construction of a radio button (toggle) using the Apple iOS style.
 * The radio button built from this class MUST have only 2 states: 1 and 0.
 *
 * @since 	1.7
 */
class UIRadioIOS extends UIRadio
{
	/**
	 * Insert a radio button element.
	 * @override
	 *
	 * @param 	object 	 $element 	The radio button element.
	 *
	 * @return 	UIRadio  This object to support chaining.
	 */
	public function addElement($element)
	{
		if (count(parent::getElements()) < 2) {
			parent::addElement($element);
		}

		return $this;
	}

	/**
	 * Adapt the given element.
	 *
	 * @param 	object 	$element 	The radio button element.
	 *
	 * @return 	object 	The adapted element.
	 */
	protected function bind($element)
	{
		if (isset($element->htmlAttr) && stripos($element->htmlAttr, 'onclick') !== false) {
			// strip onclick and trim ending double quotes
			$function = rtrim($element->htmlAttr, '"');
			$function = str_replace('onClick="', '', $function);
			$function = str_replace('onclick="', '', $function);

			// the javascript function to call
			$element->htmlAttr = $function;
		}

		if (empty($element->id)) {
			$element->id = parent::getName() . '1';
		}

		if (empty($element->value)) {
			$element->value = 1;
		}

		return $element;
	}

	/**
	 * Call this method to build and return the HTML of the input.
	 *
	 * @return 	string 	The input HTML.
	 */
	public function display()
	{
		$elements = parent::getElements();

		if (count($elements) != 2) {
			return '';
		}

		list($yes, $no) = $elements;

		$checked = '';
		if (!empty($yes->checked)) {
			$checked = 'checked="checked"';
		}

		$onchange = '';
		if (!empty($yes->htmlAttr) && !empty($no->htmlAttr)) {
			$onchange = 'onchange="jQuery(this).is(\':checked\') ? function(){'.$yes->htmlAttr.'}() : function(){'.$no->htmlAttr.'}()"';
		}

		$html = '<div class="switch-ios">
			<input type="checkbox" name="'.parent::getName().'" value="'.$yes->value.'" id="'.$yes->id.'" class="ios-toggle ios-toggle-round" '.$checked.' '.$onchange.'/>
			<label for="'.$yes->id.'"></label>
		</div>';

		return $html;
	}
}
