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
class cleverdineViewrestaurant extends JViewUI {
	/**
	 * Restaurants view display method
	 * @return void
	 */
	function display($tpl = null) {

		$mainframe 	= JFactory::getApplication();
		$input 		= $mainframe->input;
		$dbo 		= JFactory::getDbo();

		$is_tmpl = !strcmp($input->get('tmpl'), 'component');

		if( !$is_tmpl ) {
			RestaurantsHelper::load_css_js();
			RestaurantsHelper::load_complex_select();
		}

		// Set the toolbar
		$this->addToolBar();
		
		$tk_l_rows = array();
		$tk_r_rows = array();
		
		$_df 		= $mainframe->getUserStateFromRequest('vrdash.datefilter', 'datefilter', '', 'string');
		$_min_int 	= $mainframe->getUserStateFromRequest('vrdash.minint', 'minint', 0, 'uint');
		
		$ajax_params = array(
			"from" => array(
				$input->get('from_id', 0, 'uint'),
				$input->get('from_tk_id', 0, 'uint'),
			),
			"last" => array(
				$input->get('last_id', 0, 'uint'),
				$input->get('last_tk_id', 0, 'uint'),
			),
			"details_list" => array(
				array(), // not used
				$input->get('tk_details_list', array(), 'uint')
			)
		);

		$now = time();
		$avg = cleverdine::getAverageTimeStay(true);

		$tk_avg = cleverdine::getTakeAwayMinuteInterval(true);
		
		if( strlen( $_df ) == 0 || cleverdine::createTimestamp($_df, 0, 0, true) == -1 ) {
			$_df = date(cleverdine::getDateFormat(true), $now);
		}

		if( empty($_min_int) || $_min_int % 5 != 0 ) {
			$_min_int = cleverdine::getMinuteIntervals(true);
		}
		
		$filters = array($_df, $_min_int);
		
		// restaurant //
		
		$selected_ts = cleverdine::createTimestamp($_df, 0, 0, true);
		
		$rooms = array();
		$q = "SELECT `r`.`id` AS `id_room`, `r`.`name` AS `room_name`, `t`.`id` AS `id_table`, `t`.`name` AS `table_name`, `t`.`min_capacity` AS `min`, `t`.`max_capacity` AS `max`, (
		  SELECT COUNT(1) FROM `#__cleverdine_room_closure` AS `rc` WHERE `rc`.`id_room`=`r`.`id` AND `rc`.`start_ts`<=$selected_ts AND $selected_ts<`rc`.`end_ts` LIMIT 1
		) AS `is_closed`
		FROM `#__cleverdine_room` AS `r`
		LEFT JOIN `#__cleverdine_table` AS `t` ON `t`.`id_room`=`r`.`id`
		WHERE `r`.`published` 
		ORDER BY `r`.`ordering`, `t`.`name`;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$app = $dbo->loadAssocList();
		   
			$last_room = -1;
			foreach( $app as $a ) {
				if( $a['id_room'] != $last_room ) {
					array_push($rooms, array(
						'id' => $a['id_room'],
						'name' => $a['room_name'],
						'closed' => $a['is_closed'],
						'tables' => array()
					));
					
					$last_room = $a['id_room'];
				}
				
				array_push($rooms[count($rooms)-1]['tables'], array(
					'id' => $a['id_table'],
					'name' => $a['table_name'],
					'min' => $a['min'],
					'max' => $a['max']
				));
			}
		}
		
		$today_bookings = array();
		$q = "SELECT `r`.`id`, `r`.`checkin_ts`, `r`.`id_table`, `r`.`purchaser_nominative`, `r`.`status`, `r`.`rescode`, `r`.`stay_time`,
		`c`.`code`, `c`.`icon` AS `code_icon` 
		FROM `#__cleverdine_reservation` AS `r` 
		LEFT JOIN `#__cleverdine_res_code` AS `c` ON `r`.`rescode`=`c`.`id` 
		WHERE (`r`.`status`='CONFIRMED' OR `r`.`status`='PENDING') AND `r`.`checkin_ts` BETWEEN ".cleverdine::createTimestamp($_df, 0, 0, true)." AND ".cleverdine::createTimestamp($_df, 23, 59, true)."
		ORDER BY `r`.`id_table` ASC, `r`.`checkin_ts` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$app = $dbo->loadAssocList();
			$last_res = -1;
			foreach( $app as $a ) {
				if( $last_res != $a['id_table'] ) {
					$today_bookings[$a['id_table']] = array();
					$last_res = $a['id_table'];
				}
				
				array_push($today_bookings[$a['id_table']], $a);
			}
		}
		
		$shifts = array();
		$continuos = array();
		
		if( !cleverdine::isContinuosOpeningTime(true) ) {
			$shifts = cleverdine::getWorkingShifts(1, true);
			$special_day_for = cleverdine::getSpecialDaysOnDate(array(
				"date" => $_df,
				"hourmin" => "0:0",
				"hour" => 0,
				"min" => "",
				"people" => ""
			), 1, true);
			if( count($special_day_for) > 0 && $special_day_for !== -1 ) {
				$shifts = cleverdine::getWorkingShiftsFromSpecialDays($shifts, $special_day_for, 1, true);
			}
		} else {
			$continuos = array( cleverdine::getFromOpeningHour(true), cleverdine::getToOpeningHour(true) );
		}
		
		$latest_reservations = array();
		$q = "SELECT `r`.*, `t`.`name` AS `tname` 
		FROM `#__cleverdine_reservation` AS `r`, `#__cleverdine_table` AS `t` 
		WHERE `r`.`id_table`=`t`.`id`
		ORDER BY `r`.`id` DESC LIMIT 10;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$latest_reservations = $dbo->loadAssocList();
		}
		
		$incoming_reservations = array();
		$q = "SELECT `r`.*, `t`.`name` AS `tname` 
		FROM `#__cleverdine_reservation` AS `r`, `#__cleverdine_table` AS `t` 
		WHERE `r`.`id_table`=`t`.`id` AND `r`.`checkin_ts`>$now 
		ORDER BY `r`.`checkin_ts` ASC LIMIT 10;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$incoming_reservations = $dbo->loadAssocList();
		}

		$current_reservations = array();
		$q = "SELECT `r`.*, `t`.`name` AS `tname`, `c`.`code`, `c`.`icon` AS `code_icon`
		FROM `#__cleverdine_reservation` AS `r`
		LEFT JOIN `#__cleverdine_table` AS `t` ON `t`.`id`=`r`.`id_table` 
		LEFT JOIN `#__cleverdine_res_code` AS `c` ON `c`.`id`=`r`.`rescode`
		WHERE `status`='CONFIRMED' AND $now BETWEEN `r`.`checkin_ts` AND (`r`.`checkin_ts`+$avg*60) 
		ORDER BY `r`.`checkin_ts` ASC LIMIT 10;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$current_reservations = $dbo->loadAssocList();
		}
		
		// takeaway //
		
		$latest_tk_orders = array();
		$q = "SELECT `r`.* 
		FROM `#__cleverdine_takeaway_reservation` AS `r` 
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
		WHERE `r`.`checkin_ts`>$now 
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
		WHERE `status`='CONFIRMED' AND $now BETWEEN (`r`.`checkin_ts`-$tk_avg*60) AND (`r`.`checkin_ts`+$tk_avg*60) 
		ORDER BY `r`.`checkin_ts` ASC LIMIT 10;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$current_tk_orders = $dbo->loadAssocList();
		}
		
		///////////////
		
		$this->rooms 				= &$rooms;
		$this->shifts 				= &$shifts;
		$this->continuos 			= &$continuos;
		$this->bookings 			= &$today_bookings;

		$this->latestReservations 	= &$latest_reservations;
		$this->incomingReservations = &$incoming_reservations;
		$this->currentReservations 	= &$current_reservations;

		$this->latestTkOrders 		= &$latest_tk_orders;
		$this->incomingTkOrders 	= &$incoming_tk_orders;
		$this->currentTkOrders 		= &$current_tk_orders;

		$this->filters 				= &$filters;
		$this->ajaxParams 			= &$ajax_params;
		$this->isTmpl 				= &$is_tmpl;
		
		// Display the template (default.php)
		parent::display($tpl);
		
	}

	/**
	 * Setting the toolbar
	 */
	private function addToolBar() {
		//Add menu title and some buttons to the page
		JToolbarHelper::title(JText::_('VRMAINTITLEVIEWDASHBOARD'), 'restaurants');
		if (JFactory::getUser()->authorise('core.admin', 'com_cleverdine')) {
			JToolbarHelper::preferences('com_cleverdine');
		}
		
	}

}
?>