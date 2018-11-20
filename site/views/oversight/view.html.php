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
class cleverdineViewoversight extends JViewUI {
	
	/**
	 * Restaurants view display method
	 * @return void
	 */
	function display($tpl = null) {

		$mainframe 	= JFactory::getApplication();
		$input 		= $mainframe->input;
		$dbo 		= JFactory::getDbo();
		
		////// LOGIN //////
		
		$operator = cleverdine::getOperator();
		
		$access = $operator !== false && !empty($operator['can_login']);
		
		$this->operator = &$operator;
		$this->ACCESS 	= &$access;
		
		////// MANAGEMENT //////
		
		if( $access ) {
			
			$group = $operator['group'];

			if( $group == 0 ) {
				$group = $input->getUint('group', 1);
			}

			if( $group == 1 ) {

				$this->loadRestaurantContents($input, $dbo);
				$tpl = 'restaurant';

			} else {

				$this->loadTakeawayContents($input, $dbo);
				$tpl = 'takeaway';

			}
		
		} else {

			// load css and js for login form
			cleverdine::load_css_js();

		}

		// prepare page content
		cleverdine::prepareContent($this);

		// Display the template
		parent::display($tpl);

	}

	private function loadRestaurantContents($input, $dbo) {

		cleverdine::load_css_js();
		
		$selectedRoomId = $input->get('selectedroom', 0, 'uint');
		
		// SESSION 
		$session = JFactory::getSession();
		if( strlen( $selectedRoomId ) == 0 ) {
			$selectedRoomId = $session->get('vr_last_selected_room','-1');
		} 
		// END SESSION
		
		$_df = $input->get('datefilter', '', 'string');
		$_hm = $input->get('hourmin', '', 'string');
		$_pl = $input->get('people', '', 'string');
		
		$_df_ts = cleverdine::createTimestamp($_df,0,0);
		if( strlen( $_df ) == 0 || $_df_ts == -1 ) {
			$_df = date( cleverdine::getDateFormat(), time() );
		} else {
			$_df = date( cleverdine::getDateFormat(), $_df_ts);
		}
		
		$_hm_exp = explode(':',$_hm);
		$time_ok = true;
		if( count( $_hm_exp ) != 2 || !cleverdine::isHourBetweenShifts($_hm_exp[0], $_hm_exp[1], 1) ) {
			$_hm = explode(':', date('H:i'));
			$min_int = cleverdine::getMinuteIntervals();
			if( $_hm[1] % $min_int != 0 ) {
				$_hm[1] -= ($_hm[1]%$min_int);
				if( $_hm[1] == 60 ) {
					$_hm[1] = 0;
					$_hm[0]++;
					if( $_hm[0] == 24 ) {
						$_hm[0] = 0;
					}
				}
			}
			
			$time_ok = cleverdine::isHourBetweenShifts($_hm[0], $_hm[1], 1);
			$_hm = intval($_hm[0]).':'.intval($_hm[1]);
		}
		
		if( cleverdine::getMinimumPeople() > $_pl || cleverdine::getMaximumPeople() < $_pl ) {
			$_pl = cleverdine::getMinimumPeople();
			if( $_pl == 1 ) {
				$_pl = 2;
			}
		}
		
		$_hm_exp = explode(":", $_hm);
		$filters = array( 'date' => $_df, 'hourmin' => $_hm, 'people' => $_pl, "hour" => $_hm_exp[0], "min" => $_hm_exp[1] );
		
		$rooms = array();
		
		$ts = cleverdine::createTimestamp($_df, 0, 0);
		
		$q="SELECT `rm`.* FROM `#__cleverdine_room` AS `rm` WHERE (
				SELECT COUNT(1) FROM `#__cleverdine_room_closure` AS `rc` 
				WHERE `rc`.`id_room`=`rm`.`id` AND `rc`.`start_ts`<=$ts AND $ts<`rc`.`end_ts` LIMIT 1
			)=0 ORDER BY `rm`.`id`";
		$dbo->setQuery($q);
		$dbo->execute();
		if($dbo->getNumRows() > 0) {
			$rooms = $dbo->loadAssocList();
		}
		
		$selectedRoomId = intval( $selectedRoomId );
		if( (empty($selectedRoomId) || $selectedRoomId == -1) && count($rooms) > 0 ) {
			$selectedRoomId = $rooms[0]['id'];
		}
		
		$allRoomTables = array();
		$roomWidth = 600; // default
		$roomHeight = 500; // default
		
		if( $selectedRoomId != -1 ) {
			$q = "SELECT * FROM `#__cleverdine_table` WHERE `id_room` = " . $selectedRoomId . ";";
			$dbo->setQuery($q);
			$dbo->execute();
			if($dbo->getNumRows() > 0) {
				$allRoomTables = $dbo->loadAssocList();
			}
			
			$found = false;
			for( $i = 0; $i < count($rooms) && !$found; $i++ ) {
				if( $rooms[$i]['id'] == $selectedRoomId ) {
					$gp = json_decode($rooms[$i]['graphics_properties'], true);
					if( !empty($gp['mapwidth']) ) {
						$roomWidth = $gp['mapwidth'];
					}
					if( !empty($gp['mapheight']) ) {
						$roomHeight = $gp['mapheight'];
					}
					$found = true;
				}
			}
		}
		
		$roomSize = array("width" => $roomWidth, "height" => $roomHeight);
		
		$shifts = array();
		$continuos = array();
		
		if( !cleverdine::isContinuosOpeningTime() ) {
			$shifts = cleverdine::getWorkingShifts(1);
			$special_day_for = cleverdine::getSpecialDaysOnDate($filters);
			if( count($special_day_for) > 0 && $special_day_for !== -1 ) {
				$shifts = cleverdine::getWorkingShiftsFromSpecialDays($shifts, $special_day_for);
			}
		} else {
			$continuos = array( cleverdine::getFromOpeningHour(), cleverdine::getToOpeningHour() );
		}
		
		$session->set('vr_last_selected_room', $selectedRoomId);
		//$session->set('vr_last_selected_time', array( $_df, $_hm, $_pl ) );
		
		$_hm_exp = explode(':',$_hm);
		$args = array( 'date' => $_df, 'hourmin' => $_hm, 'hour' => $_hm_exp[0], 'min' => $_hm_exp[1], 'people' => $_pl );
		$rows = array();
		$rows_multi = array();
		
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
		
		$allSharedTablesOccurrency = array();
		
		$q = cleverdine::getQueryCountOccurrencyTableMultiRes($args);
		$dbo->setQuery($q);
		$dbo->execute();
		
		if( $dbo->getNumRows() > 0 ) {
			$allSharedTablesOccurrency = $dbo->loadAssocList();
		}
		
		$now = cleverdine::createTimestamp($args['date'], $args['hour'], $args['min']);
		$avg = cleverdine::getAverageTimeStay()*60;
		
		$current_res = array();
		$q = "SELECT `r`.`id`, `r`.`rescode`, `c`.`code`, `c`.`icon` AS `code_icon`, `r`.`id_table`, `r`.`checkin_ts`, `r`.`stay_time`,
		`r`.`purchaser_nominative` AS `custname`, `r`.`purchaser_mail` AS `custmail`, `r`.`purchaser_phone` AS `custphone` 
		FROM `#__cleverdine_reservation` AS `r` LEFT JOIN `#__cleverdine_res_code` AS `c` ON `r`.`rescode`=`c`.`id`
		WHERE `r`.`status`<>'REMOVED' AND `r`.`status`<>'CANCELLED' AND (
			( `r`.`checkin_ts` < $now AND $now < `r`.`checkin_ts`+IF(`r`.`stay_time`>0, `r`.`stay_time`*60, $avg) ) OR 
			( `r`.`checkin_ts` < $now+$avg AND $now+$avg < `r`.`checkin_ts`+IF(`r`.`stay_time`>0, `r`.`stay_time`*60, $avg) ) OR 
			( `r`.`checkin_ts` < $now AND $now+$avg < `r`.`checkin_ts`+IF(`r`.`stay_time`>0, `r`.`stay_time`*60, $avg) ) OR 
			( `r`.`checkin_ts` > $now AND $now+$avg > `r`.`checkin_ts`+IF(`r`.`stay_time`>0, `r`.`stay_time`*60, $avg) ) OR 
			( `r`.`checkin_ts` = $now AND $now+$avg = `r`.`checkin_ts`+IF(`r`.`stay_time`>0, `r`.`stay_time`*60, $avg) ) 
		);";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$current_res = $dbo->loadAssocList();
		}
		
		$all_res_codes = array();
		$q = "SELECT `id`, `code`, `icon` FROM `#__cleverdine_res_code` WHERE `type`=1 ORDER BY `code` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$all_res_codes = $dbo->loadAssocList();
		}
		
		$filters['hour'] = $args['hour'];
		$filters['min'] = $args['min'];
		
		$timestamp = time();
		
		$tab_sel = $session->get('vrlistrestab', 1);
		
		$closest_res = array();
		$q = "SELECT `r`.`id`, `r`.`checkin_ts`, `r`.`people`, `r`.`purchaser_nominative`, `r`.`rescode`, `r`.`stay_time`,
		`t`.`name` AS `tname`, `c`.`code` AS `codename`, `c`.`icon` AS `codeicon` 
		FROM `#__cleverdine_reservation` AS `r` LEFT JOIN `#__cleverdine_table` AS `t` ON `r`.`id_table`=`t`.`id` LEFT JOIN `#__cleverdine_res_code` AS `c` ON `r`.`rescode`=`c`.`id` 
		WHERE `r`.`status`='CONFIRMED' AND `r`.`checkin_ts`>=".($timestamp-$avg)." AND `checkin_ts`<=".($timestamp+$avg)." ORDER BY `checkin_ts`;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$closest_res = $dbo->loadAssocList();
		}
		
		$now_res = array();
		$upcoming_res = array();
		
		foreach( $closest_res as $r ) {
			if( $r['checkin_ts'] < $timestamp ) {
				array_push($now_res, $r);
			} else {
				array_push($upcoming_res, $r);
			}
		}
		
		$this->rooms 						= &$rooms;
		$this->tables 						= &$allRoomTables;
		$this->selectedRoomId 				= &$selectedRoomId;
		$this->roomSize 					= &$roomSize;
		$this->filters 						= &$filters;
		$this->shifts 						= &$shifts;
		$this->continuos 					= &$continuos;
		$this->reservationTableOnDate 		= &$rows;
		$this->allSharedTablesOccurrency 	= &$allSharedTablesOccurrency;
		$this->currentReservations 			= &$current_res;
		$this->allResCodes 					= &$all_res_codes;
		$this->timeOk 						= &$time_ok;
		$this->nowReservations 				= &$now_res;
		$this->upcomingReservations 		= &$upcoming_res;
		$this->selectedResTab 				= &$tab_sel;

	}

	private function loadTakeawayContents($input, $dbo) {

		$config = UIFactory::getConfig();

		$is_tmpl = !strcmp($input->get('tmpl'), 'component');

		if( !$is_tmpl ) {
			cleverdine::load_css_js();
			cleverdine::load_font_awesome();
		}

		$ajax_params = array(
			'from' => $input->get('from_tk_id', 0, 'uint'),
			'last' => $input->get('last_tk_id', 0, 'uint'),
			'details_list' => $input->get('tk_details_list', array(), 'uint')
		);

		$now = time();

		$tk_avg = $config->getUint('tkminint');
		
		$latest_tk_orders = array();
		$q = "SELECT `r`.* 
		FROM `#__cleverdine_takeaway_reservation` AS `r` 
		WHERE `r`.`status`<>'REMOVED'
		ORDER BY `r`.`id` DESC LIMIT 10;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$latest_tk_orders = $dbo->loadAssocList();
		}
		
		$incoming_tk_orders = array();
		$q = "SELECT `r`.*, `c`.`code`, `c`.`icon` AS `code_icon`, (
			SELECT SUM(`i`.`quantity`) 
			FROM `#__cleverdine_takeaway_res_prod_assoc` AS `i`
			WHERE `i`.`id_res`=`r`.`id`
		) AS `items_count`, (
			SELECT SUM(`i`.`quantity`) 
			FROM `#__cleverdine_takeaway_res_prod_assoc` AS `i`
			LEFT JOIN `#__cleverdine_takeaway_menus_entry` AS `e` ON `i`.`id_product`=`e`.`id`
			WHERE `i`.`id_res`=`r`.`id` AND `e`.`ready`=0
		) AS `items_preparation_count`
		FROM `#__cleverdine_takeaway_reservation` AS `r` 
		LEFT JOIN `#__cleverdine_res_code` AS `c` ON `r`.`rescode`=`c`.`id` 
		WHERE `r`.`checkin_ts`>$now AND (`r`.`status`='CONFIRMED' OR `r`.`status`='PENDING')
		ORDER BY `r`.`checkin_ts` ASC LIMIT 10;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$incoming_tk_orders = $dbo->loadAssocList();
		}

		$current_tk_orders = array();
		$q = "SELECT `r`.*, `c`.`code`, `c`.`icon` AS `code_icon`, (
			SELECT SUM(`i`.`quantity`) 
			FROM `#__cleverdine_takeaway_res_prod_assoc` AS `i`
			WHERE `i`.`id_res`=`r`.`id`
		) AS `items_count`, (
			SELECT SUM(`i`.`quantity`) 
			FROM `#__cleverdine_takeaway_res_prod_assoc` AS `i`
			LEFT JOIN `#__cleverdine_takeaway_menus_entry` AS `e` ON `i`.`id_product`=`e`.`id`
			WHERE `i`.`id_res`=`r`.`id` AND `e`.`ready`=0
		) AS `items_preparation_count`
		FROM `#__cleverdine_takeaway_reservation` AS `r` 
		LEFT JOIN `#__cleverdine_res_code` AS `c` ON `r`.`rescode`=`c`.`id` 
		WHERE `status`='CONFIRMED' AND $now BETWEEN (`r`.`checkin_ts`-$tk_avg*60) AND (`r`.`checkin_ts`+$tk_avg*60) AND (`r`.`status`='CONFIRMED' OR `r`.`status`='PENDING')
		ORDER BY `r`.`checkin_ts` ASC LIMIT 10;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$current_tk_orders = $dbo->loadAssocList();
		}

		$this->latestTkOrders 		= &$latest_tk_orders;
		$this->incomingTkOrders 	= &$incoming_tk_orders;
		$this->currentTkOrders 		= &$current_tk_orders;

		$this->ajaxParams 			= &$ajax_params;
		$this->isTmpl 				= &$is_tmpl;

	}

}
?>