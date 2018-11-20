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

class cleverdineViewdetailsinfo extends JViewUI {
	
	/**
	 * Restaurants view display method
	 * @return void
	 */
	function display($tpl = null) {
		
		RestaurantsHelper::load_css_js();

		$input 	= JFactory::getApplication()->input;
		$dbo 	= JFactory::getDbo();
		
		$args = array();

		$args['date'] 		= $input->get('date', '', 'string');
		$args['hourmin'] 	= $input->get('hourmin', '', 'string');
		$args['people'] 	= $input->get('people', 1, 'uint');
		$args['table'] 		= $input->get('table', 0, 'uint');
		
		$rows = $input->get('rows', array(), 'array');
		
		$this->rows 	= &$rows;
		$this->args 	= &$args;

		// Display the template
		parent::display($tpl);
		
	}
}
?>