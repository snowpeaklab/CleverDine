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
class cleverdineViewsearch extends JViewUI {

	/**
	 * Restaurants view display method
	 * @return void
	 */
	function display($tpl = null) {
		
		cleverdine::load_css_js();
		
		$mainframe 	= JFactory::getApplication();
		$input 		= $mainframe->input;
		$dbo 		= JFactory::getDbo();
		
		$args = null;
		$rows = null;
		$attempt = null;
		$bef_h = null;
		$aft_h = null;
		$selectedRoom = -1;
		
		// SESSION
		$session = JFactory::getSession();
		if( strlen( $session->get( 'vr_room_changed', '' ) ) != 0 ) {

			$vr_session_data = $session->get('vr_session_data','');
			$args = $vr_session_data['ARGS'];
			$rows = $vr_session_data['ROWS'];
			$attempt = $vr_session_data['ATTEMPT'];
			$bef_h = $vr_session_data['BEFORE_HINTS'];
			$aft_h = $vr_session_data['AFTER_HINTS'];
			$selectedRoom = $input->get('room', 0, 'uint');
			$session->set('vr_room_changed', '');
			
		} else {
			
			$args = $input->get('args', array(), 'array');
			$rows = array();
			$rows_multi = array();
			$attempt = 1;
			
			$hints = array();
			
			$q = cleverdine::getQueryFindTable($args);
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
			$q = cleverdine::getQueryFindTableMultiRes($args);
			$dbo->setQuery($q);
			$dbo->execute();
			if( $dbo->getNumRows() > 0 ) {
				$rows_multi = $dbo->loadAssocList();
			}
			
			$rows = cleverdine::mergeArrays($rows, $rows_multi);
			
			if( count( $_app ) == 0 ) {
				$attempt++;
				
				for( $i = 0, $n = count($rows); $i < $n; $i++ ) {
					$_app[$i] = $rows[$i];
				}
				
				if( count( $_app ) == 0 ) {
					$attempt++;
				}
				
				// ELABORATE HINTS
				$q = cleverdine::getQueryAllReservationsOnDate($args);
				$dbo->setQuery($q);
				$dbo->execute();
				
				if( $dbo->getNumRows() > 0 ) {
					$_h = $dbo->loadAssocList();
					
					// Delimiters of shift _d[0] = start shift, _d[1] = end shift
					$_d      = cleverdine::getOpeningTimeDelimiters($args);
					// Average time of stay
					$_avg    = cleverdine::getAverageTimeStay();
					// Counter of table with same ID: if 0 -> compare with _d[0]
					// _cont = 0 when current id table != next id table -> compare with _d[1]
					$_cont   = 0;
					
					for( $i = 0, $n = count($_h); $i < $n; $i++ ) {

						// evaluate stay time for current reservation
						if (empty($_h[$i]['stay_time'])) {
							$_h[$i]['stay_time'] = $_avg;
						}
						$_h[$i]['stay_time'] *= 60;
						
						$_argv = array();
						if( $_cont == 0 ) {
							$_argv = cleverdine::getAvailableHoursFromInterval( $_d[0], $_h[$i]['checkin_ts'] );
						} else if( $_h[$i]['idt'] == $_h[$i-1]['idt'] ) {
							$_argv = cleverdine::getAvailableHoursFromInterval( $_h[$i-1]['checkin_ts']+$_h[$i-1]['stay_time'], $_h[$i]['checkin_ts'] );
						}
						
						foreach( $_argv as $val ) {
							$hints[count($hints)] = $val;
						}
						
						if( $i == $n-1 || $_h[$i]['idt'] != $_h[$i+1]['idt'] ) {
							$_cont = 0;
							$_argv = cleverdine::getAvailableHoursFromInterval( $_h[$i]['checkin_ts']+$_h[$i]['stay_time'], $_d[1] );
							
							foreach( $_argv as $val ) {
								$hints[count($hints)] = $val;
							}
						} else {
							$_cont++;
						}
					}
				}
			}
			
			sort( $hints );
			
			$bef_h = array( -1, -1 );
			$aft_h = array( -1, -1 );
			$ts = cleverdine::createTimestamp($args['date'], $args['hour'], $args['min']);
				
			// FIND NEAREST BEFORE HOURS
			$i = 0;
			$n = count( $hints );
			while( $i < $n && $hints[$i] < $ts ) {
				if( $bef_h[1] != $hints[$i] ) {
					$bef_h[0] = $bef_h[1];
					$bef_h[1] = $hints[$i];
				}
				$i++;
			}
			
			// FIND NEAREST AFTER HOURS
			$i = count( $hints )-1;
			while( $i >= 0 && $hints[$i] > $ts ) {
				if( $aft_h[1] != $hints[$i] ) {
					$aft_h[0] = $aft_h[1];
					$aft_h[1] = $hints[$i];
				}
				$i--;
			}
			
		}
		
		$allRoomTables = array();
		$allSharedTablesOccurrency = array();
		if( cleverdine::getReservationRequirements() == 0 ) {
		
			$_sr = intval($selectedRoom);
			if( $_sr == -1 && count($rows) > 0 ) {
				$_sr = $rows[0]['rid'];
			}
			
			$q = "SELECT * FROM `#__cleverdine_table` WHERE `id_room`=$_sr AND `published`=1;";
			$dbo->setQuery($q);
			$dbo->execute();
			if( $dbo->getNumRows() ) {
				$allRoomTables = $dbo->loadAssocList();
			}
			
			$q = cleverdine::getQueryCountOccurrencyTableMultiRes($args);
			$dbo->setQuery($q);
			$dbo->execute();
			
			if( $dbo->getNumRows() ) {
				$allSharedTablesOccurrency = $dbo->loadAssocList();
			}
		}
		
		$allRooms = array();
		$q = "SELECT `id`, `description`, `image` FROM `#__cleverdine_room` WHERE `published`=1 ORDER BY `ordering`;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$allRooms = $dbo->loadAssocList();
		} 
		
		$menus_list = array();
		if( cleverdine::isMenusChoosable($args) ) {
			$menus_list = cleverdine::getAllAvailableMenusOn($args, 1);
		}

		$menus_ids = array();
		foreach( $menus_list as $app ) {
			array_push($menus_ids, $app['id']);
		}
		$translated_menus = cleverdine::getTranslatedMenus($menus_ids);

		$this->rows 						= &$rows;
		$this->args 						= &$args;
		$this->attempt 						= &$attempt;
		$this->befHints 					= &$bef_h;
		$this->aftHints 					= &$aft_h;
		$this->lastSelectedRoom 			= &$selectedRoom;
		$this->allRoomTables 				= &$allRoomTables;
		$this->allSharedTablesOccurrency 	= &$allSharedTablesOccurrency;
		$this->menusList 					= &$menus_list;
		$this->allRooms 					= &$allRooms;
		$this->translatedMenus 				= &$translated_menus ;

		// prepare page content
		cleverdine::prepareContent($this);
		
		// Display the template
		parent::display($tpl);

	}

}
?>