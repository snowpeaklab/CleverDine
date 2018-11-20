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

class cleverdineViewrestbusyres extends JViewUI {
	
	/**
	 * Restaurants view display method
	 * @return void
	 */
	function display($tpl = null) {
		
		RestaurantsHelper::load_css_js();
		RestaurantsHelper::load_font_awesome();
		RestaurantsHelper::load_complex_select();

		$mainframe 	= JFactory::getApplication();
		$input 		= $mainframe->input;
		$dbo 		= JFactory::getDbo();
		
		$date 		= $input->get('date', '', 'string');
		$time 		= explode(':', $input->get('time', '', 'string'));
		$interval 	= $mainframe->getUserStateFromRequest('busyres.interval', 'interval', 60, 'uint');

		if( count($time) < 2 ) {
			$time = array(0, 0);
		}

		$arr = getdate(cleverdine::createTimestamp($date, $time[0], $time[1], true));

		$ts1 = mktime($arr['hours'], $arr['minutes']-$interval, 0, $arr['mon'], $arr['mday'], $arr['year']);
		$ts2 = mktime($arr['hours'], $arr['minutes']+$interval, 0, $arr['mon'], $arr['mday'], $arr['year']);
		
		$rows = array();
		
		$q = "SELECT `r`.`id`,`r`.`sid`,`r`.`checkin_ts`,`r`.`people`,`r`.`status`,`r`.`locked_until`,`r`.`bill_value`,
		`r`.`purchaser_nominative`,`r`.`purchaser_mail`,`r`.`purchaser_prefix`,`r`.`purchaser_phone`,
		`t`.`name` AS `table_name`,`rm`.`name` AS `room_name`,
		`c`.`icon` AS `code_icon`,`c`.`code`
		FROM `#__cleverdine_reservation` AS `r` 
		LEFT JOIN `#__cleverdine_res_code` AS `c` ON `r`.`rescode`=`c`.`id` 
		LEFT JOIN `#__cleverdine_table` AS `t` ON `r`.`id_table`=`t`.`id`
		LEFT JOIN `#__cleverdine_room` AS `rm` ON `t`.`id_room`=`rm`.`id`
		WHERE (`r`.`status`='CONFIRMED' OR `r`.`status`='PENDING') AND `r`.`checkin_ts` BETWEEN $ts1 AND $ts2 
		ORDER BY `r`.`checkin_ts` ASC;";

		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$rows = $dbo->loadAssocList();
		}

		$filters = array(
			'date' => $date,
			'time' => $time[0].':'.$time[1],
			'interval' => $interval
		);
		
		$this->rows 	= &$rows;
		$this->filters 	= &$filters;

		// Display the template
		parent::display($tpl);
		
	}
}
?>