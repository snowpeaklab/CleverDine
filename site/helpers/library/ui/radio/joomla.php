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
 * This class provides the construction of a radio button (toggle) using the Joomla style.
 *
 * @since 	1.7
 */
class UIRadioJoomla extends UIRadio
{
	/**
	 * Call this method to build and return the HTML of the input.
	 * @override
	 *
	 * @return 	string 	The input HTML.
	 */
	public function display()
	{
		$name 		= parent::getName();
		$elements 	= parent::getElements();
		$options 	= parent::getoptions();

		if (count($elements) != 2) {
			return '';
		}

		$elem_1 = $elements[0];
		$elem_2 = $elements[1];

		if (!$elem_1->checked && !$elem_2->checked) {
			$elem_1->checked = true;
		}
		
		if (empty($elem_1->htmlAttr)) {
			$elem_1->htmlAttr = "";
		}
		if (empty($elem_2->htmlAttr)) {
			$elem_2->htmlAttr = "";
		}
		
		if (empty($elem_1->label)) {
			$elem_1->label = JText::_('VRYES');
		}
		if (empty($elem_2->label)) {
			$elem_2->label = JText::_('VRNO');
		}
		
		if (empty($elem_1->id)) {
			$elem_1->id = $name."1";
		}
		if (empty($elem_2->id)) {
			$elem_2->id = $name."0";
		}

		$wrapped = false;
		if (array_key_exists('wrapped', $options)) {
			$wrapped = $options['wrapped'];
		}

		$html = '';

		if (VersionListener::isJoomla25()) {
			// 2.5
			$html = '<input type="radio" name="'.$name.'" value="1" id="'.$elem_1->id.'" '.($elem_1->checked ? "checked=\"checked\"" : "" ).' '.$elem_1->htmlAttr.'/>
				<label for="'.$elem_1->id.'">'.$elem_1->label.'</label>
				<input type="radio" name="'.$name.'" value="0" id="'.$elem_2->id.'" '.($elem_2->checked ? "checked=\"checked\"" : "" ).' '.$elem_2->htmlAttr.'/>
				<label for="'.$elem_2->id.'">'.$elem_2->label.'</label>';
		} else {
			// 3.x
			$html = '<fieldset class="radio btn-group btn-group-yesno">
						<input class="btn-group" type="radio" name="'.$name.'" value="1" id="'.$elem_1->id.'" '.($elem_1->checked ? "checked=\"checked\"" : "" ).'/>
						<label for="'.$elem_1->id.'" '.$elem_1->htmlAttr.'>'.$elem_1->label.'</label>
						<input class="btn-group" type="radio" name="'.$name.'" value="0" id="'.$elem_2->id.'" '.($elem_2->checked ? "checked=\"checked\"" : "" ).'/>
						<label for="'.$elem_2->id.'" '.$elem_2->htmlAttr.'>'.$elem_2->label.'</label>
					</fieldset>';

			if ($wrapped) {
				$html = '<div class="controls" style="display: inline-block;">'.$html.'</div>';
			}
		}

		return $html;
	}
}
