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
class cleverdineVieweditres extends JViewUI {
	
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
		
		if( $operator === false || empty($operator['can_login']) )  {
			$mainframe->enqueueMessage(JText::_('VRLOGINUSERNOTFOUND'), 'error');
			$mainframe->redirect(JRoute::_('index.php?option=com_cleverdine&view=oversight'));
			exit;
		}
		
		////// MANAGEMENT //////
		
		cleverdine::load_css_js();
		
		$row = array();
		$shared_rows = array();
		
		$cid = $input->get('cid', array(), 'uint');
		
		if( count($cid) == 1 ) {
			$q = "SELECT * FROM `#__cleverdine_reservation` WHERE `id`=".$cid[0]." LIMIT 1;";
			$dbo->setQuery($q);
			$dbo->execute();
			if( $dbo->getNumRows() > 0 ) {
				$row = $dbo->loadAssoc();
				$row['date'] = date(cleverdine::getDateFormat(), $row['checkin_ts']);
				$row['hourmin'] = date('H:i', $row['checkin_ts']);
				list( $row['hour'], $row['min'] ) = explode(":", $row['hourmin']);
			} 
		} else if( count($cid) > 0 ) {
			$q = "SELECT `r`.`id`, `r`.`sid`, `r`.`purchaser_nominative`, `r`.`checkin_ts`, `r`.`people`, `c`.`code`, `c`.`icon` AS `code_icon`, `t`.`name` AS `tname` 
			FROM `#__cleverdine_reservation` AS `r` LEFT JOIN `#__cleverdine_res_code` AS `c` ON `r`.`rescode`=`c`.`id` 
			LEFT JOIN `#__cleverdine_table` AS `t` ON `r`.`id_table`=`t`.`id` 
			WHERE `r`.`id` IN (".implode(',', $cid).");";
			$dbo->setQuery($q);
			$dbo->execute();
			if( $dbo->getNumRows() > 0 ) {
				$shared_rows = $dbo->loadAssocList();
			} 
		}
		
		if( count($row) == 0 && count($shared_rows) == 0 ) {
			$mainframe->enqueueMessage(JText::_('VRORDERRESERVATIONERROR'), 'error');
			$mainframe->redirect(JRoute::_('index.php?option=com_cleverdine&view=oversight&Itemid='.$mainframe->input->get('Itemid', 0, 'uint')));
			exit;
		}
		
		$tables = array();
		
		$q = "SELECT * FROM `#__cleverdine_table` ORDER BY `name`;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$tables = $dbo->loadAssocList();
		}
		
		$cfields = array();
		
		$q = "SELECT * FROM `#__cleverdine_custfields` WHERE `group`=0 ORDER BY `ordering` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$cfields = $dbo->loadAssocList();
		}
		
		$rescodes = array();
		
		$q = "SELECT `id`, `code` FROM `#__cleverdine_res_code` WHERE `type`=1 ORDER BY `code` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$rescodes = $dbo->loadAssocList();
		}
		
		$shifts = array();
		$continuos = array();
		
		if( count($shared_rows) == 0 ) {
			if( !cleverdine::isContinuosOpeningTime() ) {
				$shifts = cleverdine::getWorkingShifts(1);
				$special_day_for = cleverdine::getSpecialDaysOnDate($row);
				if( count($special_day_for) > 0 && $special_day_for !== -1 ) {
					$shifts = cleverdine::getWorkingShiftsFromSpecialDays($shifts, $special_day_for);
				}
			} else {
				$continuos = array( cleverdine::getFromOpeningHour(), cleverdine::getToOpeningHour() );
			}
		}
		
		$this->operator 		= &$operator;
		$this->row 				= &$row;
		$this->shared_rows 		= &$shared_rows;
		$this->tables 			= &$tables;
		$this->custom_fields 	= &$cfields;
		$this->resCodes 		= &$rescodes;
		$this->shifts 			= &$shifts;
		$this->continuos 		= &$continuos;

		// Display the template
		parent::display($tpl);

	}

}
?>