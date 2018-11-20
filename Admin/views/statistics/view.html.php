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
class cleverdineViewstatistics extends JViewUI {
	/**
	 * Restaurants view display method
	 * @return void
	 */
	function display($tpl = null) {
		
		RestaurantsHelper::load_css_js();
		RestaurantsHelper::load_complex_select();
		RestaurantsHelper::load_charts();

		$mainframe 	= JFactory::getApplication();
		$input 		= $mainframe->input;
		$dbo 		= JFactory::getDbo();
		
		$type = $input->get('type', 0, 'uint');
		
		// Set the toolbar
		$this->addToolBar($type);
		
		$_ds = $input->get('datestart', '', 'string');
		$_de = $input->get('dateend', '', 'string');
		$_sh = $input->get('shift', '', 'string');

		$chart_filters = $input->get('chart_filters', array('global', '0', '1', '2', '3', '4', '5', '6'), 'string');
		
		$arr = getdate();
		
		if( empty($_ds) ) {
			$_ds = getdate( mktime( 0, 0, 0, $arr['mon'], 1, $arr['year'] ) );
			$_ds = date( cleverdine::getDateFormat(true), $_ds[0] );
		}
		
		$from_ts = cleverdine::createTimestamp($_ds, 0, 0);
		$arr = getdate( $from_ts );
		if( empty($_de) ) {
			$_de = getdate( mktime( 0, 0, 0, $arr['mon']+1, 1, $arr['year'] ) );
			$end_ts = $_de[0];
		} else {
			$end_ts = cleverdine::createTimestamp($_de, 0, 0);
		}
		
		if( $end_ts < $from_ts ) {
			$end_ts = $from_ts;
		}
		
		$_ds = date( cleverdine::getDateFormat(true), $from_ts );
		$_de = date( cleverdine::getDateFormat(true), $end_ts );
		
		$filters = array( 'datestart' => $_ds, 'dateend' => $_de, 'shift' => $_sh, 'chart' => $chart_filters );
		
		$q_shift_filter = "";
		if( strlen( $_sh ) > 0 ) {
			$_sh_e = explode('-', $_sh); 
			$q_shift_filter = $_sh_e[0] . " <= DATE_FORMAT(FROM_UNIXTIME(`checkin_ts`), '%H') AND DATE_FORMAT(FROM_UNIXTIME(`checkin_ts`), '%H') <= " . $_sh_e[1] . " AND ";
		}
		
		$rows = array();
		
		$table_name = '#__cleverdine_reservation';
		$cost_name = 'bill_value';
		$claus_type = "`bill_closed`=1";
		if( $type == 2 ) {
			$table_name = '#__cleverdine_takeaway_reservation';
			$cost_name = 'total_to_pay';
			$claus_type = "`status`='CONFIRMED'";
		}
		
		$q = "SELECT COUNT(`id`) AS `num_res`, SUM(`$cost_name`) AS `tot_earn`, 
		DATE_FORMAT(FROM_UNIXTIME(`checkin_ts`),'%w')*1 AS `weekday`, 
		DATE_FORMAT(FROM_UNIXTIME(`checkin_ts`),'%c')*1 AS `month`, 
		DATE_FORMAT(FROM_UNIXTIME(`checkin_ts`),'%Y')*1 AS `year` 
		FROM `$table_name` 
		WHERE $q_shift_filter $from_ts <= `checkin_ts` AND `checkin_ts` <= $end_ts AND $claus_type 
		GROUP BY `weekday`, `month`, `year` 
		ORDER BY `year` ASC, `month` ASC, `weekday` ASC;";
		
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$app = $dbo->loadAssocList();
			
			$last_month = -1;
			foreach( $app as $a ) {
				if( $last_month != $a['month'] ) {
					array_push($rows, array(
						"year" => $a['year'],
						"month" => $a['month'],
						"label" => JText::_('VRMONTH'.$a['month']),
						"tot_earn" => 0.0,
						"num_res" => 0,
						"weekdays" => array(),
					));
					$last_month = $a['month'];
				}
				
				$rows[count($rows)-1]['weekdays'][$a['weekday']] = array(
					"wday" => $a['weekday'],
					"label" => JText::_('VRDAY'.($a['weekday'] > 0 ? $a['weekday'] : 7)),
					"tot_earn" => $a['tot_earn'],
					"num_res" => $a['num_res'], 
				);
				
				$rows[count($rows)-1]['tot_earn'] += $a['tot_earn'];
				$rows[count($rows)-1]['num_res'] += $a['num_res'];
			}
			
		}
		
		$all = array();
		
		$q = "SELECT COUNT(`id`) AS `num_res`, SUM(`".$cost_name."`) AS `total_earn` 
		FROM `$table_name` WHERE $q_shift_filter $from_ts <= `checkin_ts` AND `checkin_ts` <= $end_ts AND $claus_type;";
		
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$all = $dbo->loadAssoc();
		}
		
		$shifts = cleverdine::getWorkingShifts($type, true);
		
		$this->all 		= &$all;
		$this->rows 	= &$rows;
		$this->shifts 	= &$shifts;
		$this->filters 	= &$filters;
		$this->type 	= &$type;
		
		// Display the template (default.php)
		parent::display($tpl);

	}

	/**
	 * Setting the toolbar
	 */
	private function addToolBar($type = 1) {
		//Add menu title and some buttons to the page
		$title_key = 'VRMAINTITLEVIEWSTATISTICS';
		$cancel_key = 'cancelReservation';
		if( $type == 2 ) {
			$title_key = 'VRMAINTITLEVIEWTKSTATISTICS';
			$cancel_key = 'cancelTkreservation';
		}
		JToolbarHelper::title(JText::_($title_key), 'restaurants');
		
		JToolbarHelper::cancel($cancel_key, JText::_('VRCANCEL'));	
		
	}
	
	protected function getTimestampNextMonths($n_months, $arr) {
		$next_mon = $arr['mon']+$n_months;
		$year = $arr['year'];
		if( $next_mon > 12 ) {
			$next_mon -= 12;
			$year++;
		}
		$arr = getdate( mktime( 0, 0, 0, $next_mon, 1, $year ) );
		return $arr[0]-1;
	}

}
?>