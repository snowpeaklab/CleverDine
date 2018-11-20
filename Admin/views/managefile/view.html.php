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

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * restaurants View
 */
class cleverdineViewmanagefile extends JViewUI {
	
	/**
	 * Restaurants view display method
	 * @return void
	 */
	function display($tpl = null) {
		
		RestaurantsHelper::load_css_js();

		$input 	= JFactory::getApplication()->input;
		
		$file 		= $input->get('file', '', 'string');
		$file_name 	= substr($file, strrpos($file, DIRECTORY_SEPARATOR)+1);
		
		$content = '';

		$handle = fopen($file, 'rb');
		while( !feof($handle) ) {
			$content .= fread($handle, 8192);
		}
		fclose($handle);
		
		$this->filePath = &$file;
		$this->fileName = &$file_name;
		$this->content 	= &$content;

		// Display the template
		parent::display($tpl);
		
	}
}
?>