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
class cleverdineViewopdashboard extends JViewUI {
	
	/**
	 * Restaurants view display method
	 * @return void
	 */
	function display($tpl = null) {

		$mainframe 	= JFactory::getApplication();
		$input 		= $mainframe->input;
		$dbo 		= JFactory::getDbo();

		$is_tmpl = !strcmp($input->get('tmpl'), 'component');
		
		////// LOGIN //////
		
		$operator = cleverdine::getOperator();
		
		if( $operator === false || empty($operator['can_login']) )  {

			if( !$is_tmpl ) {
				$mainframe->enqueueMessage(JText::_('VRLOGINUSERNOTFOUND'), 'error');
				$mainframe->redirect(JRoute::_('index.php?option=com_cleverdine&view=oversight'));
			} else {
				echo json_encode(array(JText::_('VRLOGINUSERNOTFOUND')));
			}

			exit;
		}
		
		cleverdine::load_css_js();
		cleverdine::load_font_awesome();

		$_df = $mainframe->getUserStateFromRequest('dash.datefilter', 'datefilter', '', 'string');
		
		if( strlen( $_df ) == 0 || cleverdine::createTimestamp($_df,0,0) == -1 ) {
			$_df = date( cleverdine::getDateFormat(), time() );
		}
		
		// restaurant //
		
		$selected_ts = cleverdine::createTimestamp($_df,0,0);
		
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
		WHERE (`r`.`status`='CONFIRMED' OR `r`.`status`='PENDING') AND `r`.`checkin_ts` BETWEEN ".cleverdine::createTimestamp($_df, 0, 0)." AND ".cleverdine::createTimestamp($_df, 23, 59)."
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
		
		if( !cleverdine::isContinuosOpeningTime() ) {
			$shifts = cleverdine::getWorkingShifts(1);
			$special_day_for = cleverdine::getSpecialDaysOnDate(array(
				"date" => $_df,
				"hourmin" => "0:0",
				"hour" => 0,
				"min" => "",
				"people" => ""
			), 1);
			if( count($special_day_for) > 0 && $special_day_for !== -1 ) {
				$shifts = cleverdine::getWorkingShiftsFromSpecialDays($shifts, $special_day_for, 1);
			}
		} else {
			$continuos = array( cleverdine::getFromOpeningHour(), cleverdine::getToOpeningHour() );
		}
		
		///////////////
		
		$this->operator 	= &$operator;
		$this->rooms 		= &$rooms;
		$this->shifts 		= &$shifts;
		$this->continuos 	= &$continuos;
		$this->bookings 	= &$today_bookings;
		
		$this->dateFilter 	= &$_df;
		$this->isTmpl 		= &$is_tmpl;

		// Display the template
		parent::display($tpl);

	}
	
}
?>