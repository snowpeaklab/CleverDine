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
class cleverdineViewquickres extends JViewUI {
	
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
		
		$args = array();
		$args['date'] 		= $input->get('date', date(cleverdine::getDateFormat()), 'string');
		$args['hourmin'] 	= $input->get('hourmin', '0:0', 'string');
		$args['people'] 	= $input->get('people', 1, 'uint');
		$args['id_table'] 	= $input->get('idt', 0, 'uint');
		
		$_hm_exp = explode(":", $args['hourmin']);
		$args['hour'] = $_hm_exp[0];
		$args['min'] = $_hm_exp[1];
		
		$tables = array();
		
		$q = "SELECT * FROM `#__cleverdine_table` ORDER BY `name`;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$tables = $dbo->loadAssocList();
		}
		
		$q = "SELECT * FROM `#__cleverdine_custfields` WHERE `group`=0 ORDER BY `ordering` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$cfields = $dbo->loadAssocList();
		}
		
		$shifts = array();
		$continuos = array();
		
		if( !cleverdine::isContinuosOpeningTime() ) {
			$shifts = cleverdine::getWorkingShifts(1);
			$special_day_for = cleverdine::getSpecialDaysOnDate($args);
			if( count($special_day_for) > 0 && $special_day_for !== -1 ) {
				$shifts = cleverdine::getWorkingShiftsFromSpecialDays($shifts, $special_day_for);
			}
		} else {
			$continuos = array( cleverdine::getFromOpeningHour(), cleverdine::getToOpeningHour() );
		}
		
		$this->operator 		= &$operator;
		$this->args 			= &$args;
		$this->tables 			= &$tables;
		$this->custom_fields 	= &$cfields;
		$this->shifts 			= &$shifts;
		$this->continuos 		= &$continuos;

		// Display the template
		parent::display($tpl);

	}

}
?>