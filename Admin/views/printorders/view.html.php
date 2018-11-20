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

class cleverdineViewprintorders extends JViewUI {
	
	/**
	 * Restaurants view display method
	 * @return void
	 */
	function display($tpl = null) {
		
		RestaurantsHelper::load_css_js();

		$input 	= JFactory::getApplication()->input;

		$print_orders_attr = $input->get('printorders', cleverdine::getPrintOrdersText(true), 'array');

		if( !empty($print_orders_attr['update']) ) {
			$dbo = JFactory::getDbo();

			$q = "UPDATE `#__cleverdine_config` SET `setting`=".$dbo->quote(json_encode($print_orders_attr))." WHERE `param`='printorderstext' LIMIT 1;";
			$dbo->setQuery($q);
			$dbo->execute();
		}
		
		$type 	= $input->get('type', 0, 'uint');
		$ids 	= $input->get('cid', array(), 'uint');
		
		$rows = array();
		foreach( $ids as $id ) {

			$order = null;
			
			if( $type == 1 ) { 

				$order = cleverdine::fetchOrderDetails($id);
				if( $order !== null ) {
					$order['items'] = cleverdine::getFoodFromReservation($id);
				}

			} else {

				$order = cleverdine::fetchTakeawayOrderDetails($id);

			}

			if( $order !== null ) {
				array_push($rows, $order);
			}
				
		}
		
		$this->type = &$type;
		$this->rows = &$rows;
		$this->text = &$print_orders_attr;

		// Display the template
		parent::display($tpl);
		
	}
}
?>