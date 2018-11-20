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
class cleverdineViewmaps extends JViewUI {
	
	/**
	 * Restaurants view display method
	 * @return void
	 */
	function display($tpl = null) {

		RestaurantsHelper::load_css_js();
		RestaurantsHelper::load_complex_select();

		$mainframe 	= JFactory::getApplication();
		$input 		= $mainframe->input;
		$dbo 		= JFactory::getDbo();
		
		// Set the toolbar
		$this->addToolBar();

		$selectedRoomId = $mainframe->getUserStateFromRequest('vrmap.selectedroom', 'selectedroom', 0, 'uint');

		$_df = $mainframe->getUserStateFromRequest('vrmap.datefilter', 'datefilter', '', 'string');
		$_hm = $mainframe->getUserStateFromRequest('vrmap.hourmin', 'hourmin', '', 'string');
		$_pl = $mainframe->getUserStateFromRequest('vrmap.people', 'people', 1, 'uint');
		
		$_df_ts = cleverdine::createTimestamp($_df, 0, 0, true);
		if( strlen($_df) == 0 || $_df_ts == -1 ) {
			$_df_ts = time();
		}
		$_df = date(cleverdine::getDateFormat(true), $_df_ts);
		
		$_hm_exp = explode(':',$_hm);
		if( count( $_hm_exp ) != 2 || !cleverdine::isHourBetweenShifts($_hm_exp[0], $_hm_exp[1], 1, true ) ) {
			$_hm = cleverdine::getFirstAvailableHour();
			$_hm_exp = explode(':', $_hm);
		} 
		
		if( cleverdine::getMinimumPeople(true) > $_pl || cleverdine::getMaximumPeople(true) < $_pl ) {
			$_pl = max(array(2, cleverdine::getMinimumPeople(true))); // 2 or higher
		}
		
		$filters = array( 'date' => $_df, 'hourmin' => $_hm, 'people' => $_pl, 'hour' => $_hm_exp[0], 'min' => $_hm_exp[1] );
		
		$rooms = array();
		
		$res_ts = cleverdine::createTimestamp($filters['date'], $filters['hour'], $filters['min'], true);
		
		$q = "SELECT `rm`.*, (
		  SELECT COUNT(1) FROM `#__cleverdine_room_closure` AS `rc` WHERE `rc`.`id_room`=`rm`.`id` AND `rc`.`start_ts`<=$res_ts AND $res_ts<`rc`.`end_ts` LIMIT 1
		) AS `is_closed` 
		 FROM `#__cleverdine_room` AS `rm` ORDER BY `rm`.`ordering`;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$rooms = $dbo->loadAssocList();
		} else {
			$rooms = array();
		}
		
		$allRoomTables = array();
		$roomHeight = 500; // default
		
		if( $selectedRoomId != -1 ) {
			$q = "SELECT * FROM `#__cleverdine_table` WHERE `id_room`=$selectedRoomId;";
			$dbo->setQuery($q);
			$dbo->execute();
			if($dbo->getNumRows() > 0) {
				$allRoomTables = $dbo->loadAssocList();
			}
			
			$q = "SELECT `graphics_properties` FROM `#__cleverdine_room` WHERE `id`=$selectedRoomId LIMIT 1;";
			$dbo->setQuery($q);
			$dbo->execute();
			if($dbo->getNumRows() > 0) {
				$gp = $dbo->loadResult();
				$gp = json_decode($gp, true);
				if( !empty($gp['mapheight']) ) {
					$roomHeight = $gp['mapheight'];
				}
			}
		} 
		
		$shifts = array();
		$continuos = array();
		
		if( !cleverdine::isContinuosOpeningTime(true) ) {
			$shifts = cleverdine::getWorkingShifts(1, true);
			$special_day_for = cleverdine::getSpecialDaysOnDate($filters, 1, true);
			if( count($special_day_for) > 0 && $special_day_for !== -1 ) {
				$shifts = cleverdine::getWorkingShiftsFromSpecialDays($shifts, $special_day_for, 1, true);
			}
		} else {
			$continuos = array( cleverdine::getFromOpeningHour(true), cleverdine::getToOpeningHour(true) );
		}
		
		$_hm_exp = explode(':',$_hm);
		$args = array( 'date' => $_df, 'hourmin' => $_hm, 'hour' => $_hm_exp[0], 'min' => $_hm_exp[1], 'people' => $_pl );
		$rows = array();
		$rows_multi = array();
		
		$q = cleverdine::getQueryFindTable($args, true);
		$dbo->setQuery($q);
		$dbo->execute();
		// check at least one single table 
		$_app = array();
		if( $dbo->getNumRows() > 0 ) {
			$rows = $dbo->loadAssocList();
			$i = 0;
			foreach( $rows as $r ) {
				if( $r['multi_res'] == 0 ) {
					$_app[$i] = $r;
					$i++;
				}
			} 
		} 
		
		// get all shared table with at least 1 people
		$q = cleverdine::getQueryFindTableMultiRes($args, true);
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$rows_multi = $dbo->loadAssocList();
		}
			
		$rows = cleverdine::mergeArrays($rows, $rows_multi);
		
		$allSharedTablesOccurrency = array();
		
		$q = cleverdine::getQueryCountOccurrencyTableMultiRes($args, true);
		$dbo->setQuery($q);
		$dbo->execute();
		
		if( $dbo->getNumRows() > 0 ) {
			$allSharedTablesOccurrency = $dbo->loadAssocList();
		}
		
		$this->rooms 						= &$rooms;
		$this->tables 						= &$allRoomTables;
		$this->selectedRoomId 				= &$selectedRoomId;
		$this->roomHeight 					= &$roomHeight;
		$this->filters 						= &$filters;
		$this->shifts 						= &$shifts;
		$this->continuos 					= &$continuos;
		$this->reservationTableOnDate 		= &$rows;
		$this->allSharedTablesOccurrency 	= &$allSharedTablesOccurrency;

		// Display the template
		parent::display($tpl);

	}

	/**
	 * Setting the toolbar
	 */
	private function addToolBar() {
		//Add menu title and some buttons to the page
		JToolbarHelper::title(JText::_('VRMAINTITLEVIEWMAPS'), 'restaurants');
		
		if (JFactory::getUser()->authorise('core.edit', 'com_cleverdine')) {
			JToolbarHelper::custom('editmap', 'edit', 'edit', JText::_('VREDIT'), false);
		}
	}

}
?>