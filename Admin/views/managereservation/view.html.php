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
class cleverdineViewmanagereservation extends JViewUI {
	
	/**
	 * Restaurants view display method
	 * @return void
	 */
	function display($tpl = null) {
		
		RestaurantsHelper::load_css_js();
		RestaurantsHelper::load_complex_select();
		RestaurantsHelper::load_font_awesome();

		$mainframe 	= JFactory::getApplication();
		$input 		= $mainframe->input;
		$dbo 		= JFactory::getDbo();
		
		$type = $input->get('type');
		
		$new_res_arg = array();
		
		$_idt 		= $input->get('idt', 0, 'uint');
		$_date 		= $input->get('date', '', 'string');
		$_hourmin 	= $input->get('hourmin', '', 'string');
		$_people 	= $input->get('people', 1, 'uint');
		$_from 		= $input->get('from', '', 'string');
		
		if( strlen($_idt) > 0 ) {
			$new_res_arg['date'] 		= $_date;
			$new_res_arg['hourmin'] 	= $_hourmin;
			$new_res_arg['people'] 		= $_people;
			$new_res_arg['id_table'] 	= $_idt;
		}
		
		// Set the toolbar
		$this->addToolBar($type);
		
		// if type is edit -> assign the selected item
		$selectedReservation = array();
		$blankKeys = array();
		$table_capacity = array();
		$cfields = array();
		
		if( $type == "edit" ) {
			$ids = $input->get('cid', array(0), 'uint');
			$id = intval( $ids[0] );
			$q = "SELECT * FROM `#__cleverdine_reservation` WHERE `id`=$id LIMIT 1;";
			$dbo->setQuery($q);
			$dbo->execute();
			if($dbo->getNumRows() > 0) {
				$selectedReservation = $dbo->loadAssoc();
				
				$selectedReservation['custom_f'] = json_decode($selectedReservation['custom_f'], true);
				
				$selectedReservation['date'] = date( cleverdine::getDateFormat(true), $selectedReservation['checkin_ts'] );
				$selectedReservation['hourmin'] = date( 'H:i', $selectedReservation['checkin_ts'] );
				
				$filters = $selectedReservation;
			} else {
				$mainframe->redirect('index.php?option=com_cleverdine&task=reservations');
				exit;
			}	
		} else {
			$filters = $new_res_arg;
		}
		
		$rooms = array();
		
		$q = "SELECT `t`.*, `r`.`name` AS `room_name` 
		FROM `#__cleverdine_room` AS `r`, `#__cleverdine_table` AS `t`
		WHERE `r`.`id`=`t`.`id_room`
		ORDER BY `r`.`ordering` ASC, `t`.`name` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$last_room_id = -1;
			foreach( $dbo->loadAssocList() as $t ) {
				if( $last_room_id != $t['id_room'] ) {
					array_push($rooms, array(
						'id' => $t['id_room'],
						'name' => $t['room_name'],
						'tables' => array()
					));
					$last_room_id = $t['id_room'];
				}
				array_push($rooms[count($rooms)-1]['tables'], $t);
			}
		}
		
		$q = "SELECT * FROM `#__cleverdine_custfields` 
		WHERE `group`=0 AND (`type`<>'checkbox' OR `required`=0) 
		ORDER BY `ordering` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$cfields = $dbo->loadAssocList();
		}
		
		$shifts = array();
		$continuos = array();
		
		if( !cleverdine::isContinuosOpeningTime(true) ) {
			if( empty($filters['date']) ) {
				$filters['date'] = date( cleverdine::getDateFormat(true), time() );
			}
			if( empty($filters['hourmin']) ) {
				$filters['hourmin'] = '0:0';
			}
			
			$_hm_exp = explode(':', $filters['hourmin']);
			$filters['hour'] = $_hm_exp[0];
			$filters['min'] = $_hm_exp[1];
			
			$shifts = cleverdine::getWorkingShifts(1, true);
			$special_day_for = cleverdine::getSpecialDaysOnDate($filters, 1, true);
			if( count($special_day_for) > 0 && $special_day_for !== -1 ) {
				$shifts = cleverdine::getWorkingShiftsFromSpecialDays($shifts, $special_day_for, 1, true);
			}
		} else {
			$continuos = array( cleverdine::getFromOpeningHour(true), cleverdine::getToOpeningHour(true) );
		}
		
		$all_menus = array();
		$q = "SELECT `id`, `name` FROM `#__cleverdine_menus` WHERE `choosable`=1 ORDER BY `ordering` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$all_menus = $dbo->loadAssocList();
		}
		
		$sel_menus = array();
		if( !empty($selectedReservation['id']) ) {
			$q = "SELECT `id`, `id_menu`, `quantity` FROM `#__cleverdine_res_menus_assoc` WHERE `id_reservation`=".$selectedReservation['id'].";";
			$dbo->setQuery($q);
			$dbo->execute();
			if( $dbo->getNumRows() > 0 ) {
				$app = $dbo->loadAssocList();
				foreach( $app as $a ) {
					$sel_menus[$a['id_menu']] = array( "assoc" => $a['id'], "quantity" => $a['quantity'] );
				}
			}
		}
		
		$countries = array();
		$q = "SELECT * FROM `#__cleverdine_countries` ORDER BY `phone_prefix` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$countries = $dbo->loadAssocList();
		}

		$payments = array();
		$q = "SELECT `id`, `name`, `charge`, `percentot`, `published` FROM `#__cleverdine_gpayments` WHERE `group`<>2 ORDER BY `published` DESC;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$payments = $dbo->loadAssocList();
		}

		$customer = null;
		if( !empty($selectedReservation['id_user']) && $selectedReservation['id_user'] > 0 ) {
			$customer = cleverdine::getCustomer($selectedReservation['id_user']);
		}
		
		$this->selectedReservation 	= &$selectedReservation;
		$this->rooms 				= &$rooms;
		$this->custom_fields 		= &$cfields;
		$this->shifts 				= &$shifts;
		$this->continuos 			= &$continuos;
		$this->blankKeys 			= &$blankKeys;
		$this->new_res_arg 			= &$new_res_arg;
		$this->allMenus 			= &$all_menus;
		$this->selectedMenus 		= &$sel_menus;
		$this->countries 			= &$countries;
		$this->customer 			= &$customer;
		$this->payments 			= &$payments;
		$this->returnTask 			= &$_from;

		// Display the template
		parent::display($tpl);

	}

	/**
	 * Setting the toolbar
	 */
	private function addToolBar($type) {
		//Add menu title and some buttons to the page
		if( $type == "edit" ) {
			JToolbarHelper::title(JText::_('VRMAINTITLEEDITRESERVATION'), 'restaurants');
		} else {
			JToolbarHelper::title(JText::_('VRMAINTITLENEWRESERVATION'), 'restaurants');
		}
		
		if (JFactory::getUser()->authorise('core.edit', 'com_cleverdine')) {
			JToolbarHelper::apply('saveReservation', JText::_('VRSAVE'));
			JToolbarHelper::save('saveAndCloseReservation', JText::_('VRSAVEANDCLOSE'));
			JToolbarHelper::custom('saveAndNewReservation', 'save-new', 'save-new', JText::_('VRSAVEANDNEW'), false, false);
			JToolbarHelper::divider();
		}
		
		JToolbarHelper::cancel('cancelReservation', JText::_('VRCANCEL'));
	}

}
?>