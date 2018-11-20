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

/**
 * Base interface to display a custom input.
 *
 * @since 	1.7
 */
interface UIInput
{
	/**
	 * Call this method to build and return the HTML of the input.
	 *
	 * @return 	string 	The input HTML.
	 */
	public function display();
}
