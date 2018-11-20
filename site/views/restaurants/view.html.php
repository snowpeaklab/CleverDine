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
class cleverdineViewrestaurants extends JViewUI {

	/**
	 * Restaurants view display method
	 * @return void
	 */
	function display($tpl = null) {
		
		cleverdine::load_css_js();
		
		$dbo = JFactory::getDbo();
		
		$shifts = array();
		$continuos = array();
		
		$session = JFactory::getSession();
		if( $session->get('vr_retrieve_data','') ) {
			$lastValues = $session->get('vr_args', array());
			if( empty($lastValues['hourmin']) ) {
				$lastValues['hourmin'] = "0:0";
			}
			
			$hour_min = explode(":", $lastValues['hourmin']);
			$lastValues['hour'] = $hour_min[0];
			$lastValues['min'] = $hour_min[1];
			$session->set('vr_retrieve_data',false);
		} else {
			$lastValues = array(
				"date" => date(cleverdine::getDateFormat()),
				"hour" => intval(date("H"))+1,
				"min" => '',
				"people" => ''
			);
			$lastValues['hourmin'] = $lastValues['hour'].":".$lastValues['min'];
		}
		
		$special_days = array();
		
		$q = "SELECT * FROM `#__cleverdine_specialdays` WHERE `group`=1;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$special_days = $dbo->loadAssocList();
		}
		
		if( !cleverdine::isContinuosOpeningTime() ) {
			$shifts = cleverdine::getWorkingShifts(1);
			$special_day_for = cleverdine::getSpecialDaysOnDate($lastValues);
			if( count($special_day_for) > 0 && $special_day_for !== -1 ) {
				$shifts = cleverdine::getWorkingShiftsFromSpecialDays($shifts, $special_day_for);
			}
		} else {
			$continuos = array( cleverdine::getFromOpeningHour(), cleverdine::getToOpeningHour() );
		}
		
		$this->lastValues 	= &$lastValues;
		$this->specialDays 	= &$special_days;
		$this->shifts 		= &$shifts;
		$this->continuos 	= &$continuos;

		// prepare page content
		cleverdine::prepareContent($this);
		
		// Display the template
		parent::display($tpl);

	}

}
?>