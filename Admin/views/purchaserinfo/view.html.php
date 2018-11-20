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

class cleverdineViewpurchaserinfo extends JViewUI {
	
	/**
	 * Restaurants view display method
	 * @return void
	 */
	function display($tpl = null) {
		
		RestaurantsHelper::load_css_js();
		RestaurantsHelper::load_font_awesome();

		$input = JFactory::getApplication()->input;
		
		$oid 		= $input->get('oid', 0, 'uint');
		$go_back 	= $input->get('goback');
		
		$order_details = cleverdine::fetchOrderDetails($oid);
		if( $order_details === false ) {
			exit(JText::_('VRTKCARTROWNOTFOUND'));
		}

		if( $order_details['created_by'] ) {

			$dbo = JFactory::getDbo();

			$q = "SELECT `name` FROM `#__users` WHERE `id`=".$order_details['created_by']." LIMIT 1;";
			$dbo->setQuery($q);
			$dbo->execute();

			if( $dbo->getNumRows() ) {
				$order_details['createdby_name'] = $dbo->loadResult();
			}

		}
		
		$this->order 	= &$order_details;
		$this->go_back 	= &$go_back;

		// Display the template
		parent::display($tpl);
		
	}
}
?>