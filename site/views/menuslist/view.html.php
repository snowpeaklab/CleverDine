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
class cleverdineViewmenuslist extends JViewUI {

	/**
	 * Restaurants view display method
	 * @return void
	 */
	function display($tpl = null) {
		
		cleverdine::load_css_js();
		
		$mainframe 	= JFactory::getApplication();
		$input 		= $mainframe->input;
		$dbo 		= JFactory::getDbo();
		
		$last_date 	= $input->get('date', '', 'string');
		$last_shift = $input->get('shift', '', 'string');
		$req_hour 	= $input->get('hour', -1, 'int');
		$_tmpl 		= $input->get('tmpl');
		
		if( strlen($last_date) == 0 ) {
			$last_date = date(cleverdine::getDateFormat(), time());
		}
		
		$shifts = array();
		$continuos = array();
		if( !cleverdine::isContinuosOpeningTime() ) {
			$shifts = cleverdine::getWorkingShifts(1);
			$special_day_for = cleverdine::getSpecialDaysOnDate(array(
				"date" => $last_date,
				"hour" => 0,
				"min" => 0,
				"people" => "",
				"hourmin" => "0:0"
			));
			if( count($special_day_for) > 0 && $special_day_for !== -1 ) {
				$shifts = cleverdine::getWorkingShiftsFromSpecialDays($shifts, $special_day_for);
			}
		} else {
			$continuos = array( cleverdine::getFromOpeningHour(), cleverdine::getToOpeningHour() );
		}
		
		$special_days = array();
		
		$q = "SELECT * FROM `#__cleverdine_specialdays` WHERE `group`=1;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$special_days = $dbo->loadAssocList();
		}
		
		$menus_list = array();
		
		$last_values = array( 'date' => $last_date, 'shift' => $last_shift );
		
		if( strlen( $last_date ) > 0 ) {
		
			$_hour = -1;
			if ($req_hour != -1) {
				$_hour = $req_hour;
			} else if (strlen($last_shift) > 0) {
				$_app = explode( '-', $last_shift );
				if( count( $_app ) == 2 ) {
					$_hour = intval( ( intval($_app[1]) + intval($_app[0]) ) / 2 );
				}
			}
			
			$args = array( 'date' => $last_date, 'hour' => $_hour, 'min' => 0 );
			
			$menus_list = cleverdine::getAllAvailableMenusOn($args);
			
		}
		
		for( $j = 0; $j < count($menus_list); $j++ ) {
			$m =& $menus_list[$j]; // don't remove & > the $m variable is assigned as reference
			
			if( !empty($m['id']) ) {
				$ws_exp = explode( ', ', $m['working_shifts'] );
				$q = "SELECT * FROM `#__cleverdine_shifts` WHERE `group`=1";
				if( strlen($m['working_shifts']) > 0 ) {
					$_app = explode( '-', $ws_exp[0] );
					$q .= ' AND ((`from`='.$_app[0].' AND `to`='.$_app[1].')';
					for( $i = 1; $i < count( $ws_exp ); $i++ ) {
						$_app = explode( '-', $ws_exp[$i] );
						$q .= ' OR (`from`='.$_app[0].' AND `to`='.$_app[1].')';
					}
					$q .= ');';
				}
				
				$dbo->setQuery($q);
				$dbo->execute();
				if( $dbo->getNumRows() > 0 ) {
					$m['working_shifts'] = $dbo->loadAssocList();
				} else {
					$m['working_shifts'] = array();
				}
			}
			
		}
		
		$show_search_form = ( $_tmpl == 'component' || $input->getBool('show_search_bar') === false ) ? false : true;
		
		$menus_ids = array();
		foreach( $menus_list as $app ) {
			array_push($menus_ids, $app['id']);
		}
		$translated_menus = cleverdine::getTranslatedMenus($menus_ids);

		$this->specialDays 		= &$special_days;
		$this->shifts 			= &$shift;
		$this->continuos 		= &$continuos;
		$this->menus 			= &$menus_list;
		$this->lastValues 		= &$last_values;
		$this->showSearchForm 	= &$show_search_form;
		$this->translatedMenus 	= &$translated_menus;

		// prepare page content
		cleverdine::prepareContent($this);
		
		// Display the template
		parent::display($tpl);

	}

}
?>