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

class cleverdineViewexportres extends JViewUI {
	
	/**
	 * Restaurants view display method
	 * @return void
	 */
	function display($tpl = null) {
		
		RestaurantsHelper::load_css_js();
		RestaurantsHelper::load_complex_select();

		$input 	= JFactory::getApplication()->input;
		$dbo 	= JFactory::getDbo();
		
		$type = $input->get('type', 0, 'uint');
		$table_px = 'cleverdine_reservation';
		if ($type == 1) {
			$table_px = 'cleverdine_takeaway_reservation';
		}
		
		$this->addToolBar($type);
		
		$ids = $input->get('cid', array(), 'uint');
		$dates = array();	
		
		if (count($ids) == 0) {
			$q = "SELECT MIN(`checkin_ts`) AS `min_date`, MAX(`checkin_ts`) AS `max_date` FROM `#__$table_px`;";
			
			$dbo->setQuery($q);
			$dbo->execute();
			
			if ($dbo->getNumRows() > 0) {
				$row = $dbo->loadAssoc(); 
				$dates = array($row['min_date'], $row['max_date']);
			}

			if (empty($dates[0])) {
				$dates[0] = time();
			}

			if (empty($dates[1])) {
				$dates[1] = time();
			}
		}
		
		$this->ids 		= &$ids;
		$this->dates 	= &$dates;
		$this->type 	= &$type;

		// Display the template
		parent::display($tpl);
	}

	/**
	 * Setting the toolbar
	 */
	private function addToolBar($type) {
	
		$title = '';
		$cancel = '';
		if( $type == 1 ) {
			$title = JText::_('VRMAINTITLETKEXPORTRES');
			$cancel = "cancelTkreservation";
		} else {
			$title = JText::_('VRMAINTITLEEXPORTRES');
			$cancel = "cancelReservation";
		}
		
		JToolbarHelper::title($title, 'restaurants');
		
		if (JFactory::getUser()->authorise('core.edit', 'com_cleverdine')) {
			JToolbarHelper::custom('exportReservations', 'download', 'download', JText::_('VRDOWNLOAD'), false);
			JToolbarHelper::divider();
		}
		
		JToolbarHelper::cancel($cancel, JText::_('VRCANCEL'));
	}
}
?>