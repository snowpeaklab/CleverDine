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
class cleverdineViewmanagetkreservation extends JViewUI {
	
	/**
	 * Restaurants view display method
	 * @return void
	 */
	function display($tpl = null) {
		
		RestaurantsHelper::load_css_js();
		RestaurantsHelper::load_complex_select();
		RestaurantsHelper::load_font_awesome();
		cleverdine::load_googlemaps();

		$mainframe 	= JFactory::getApplication();
		$input 		= $mainframe->input;
		$dbo 		= JFactory::getDbo();
		
		$type = $input->get('type');
		
		// Set the toolbar
		$this->addToolBar($type);
		
		$_date 		= $input->get('date', '', 'string');
		$_hourmin 	= $input->get('hourmin', '', 'string');
		
		// if type is edit -> assign the selected item
		$selectedReservation = array();
		$cfields = array();
		
		if( $type == "edit" ) {
			$ids = $input->get('cid', array(0), 'uint');
			$id = intval( $ids[0] );
			$q = "SELECT * FROM `#__cleverdine_takeaway_reservation` WHERE `id`=$id LIMIT 1;";
			$dbo->setQuery($q);
			$dbo->execute();
			if( $dbo->getNumRows() > 0 ) {
				$selectedReservation = $dbo->loadAssoc();
				
				$selectedReservation['date'] = date( cleverdine::getDateFormat(true), $selectedReservation['checkin_ts'] );
				$selectedReservation['hourmin'] = date( 'H:i', $selectedReservation['checkin_ts'] );
				$selectedReservation['custom_f'] = json_decode($selectedReservation['custom_f'], true);
			}
			
		} else {
			$selectedReservation['id'] = -1;
			$selectedReservation['date'] = date( cleverdine::getDateFormat(true) );
			$selectedReservation['hourmin'] = (intval(date('H'))+1).":0";
			$selectedReservation['status'] = 'PENDING';
			$selectedReservation['total_to_pay'] = 0.0;
			$selectedReservation['delivery_service'] = 1;
			$selectedReservation['id_user'] = -1;
			$selectedReservation['id_payment'] = -1;
			
			$selectedReservation['purchaser_nominative'] = '';
			$selectedReservation['purchaser_mail'] = '';
			$selectedReservation['purchaser_phone'] = '';
			$selectedReservation['purchaser_prefix'] = '';
			$selectedReservation['purchaser_country'] = '';
			$selectedReservation['purchaser_address'] = '';
			$selectedReservation['notes'] = '';

			$selectedReservation['taxes'] = 0.0;
			$selectedReservation['delivery_charge'] = 0.0;
			$selectedReservation['pay_charge'] = 0.0;

			$selectedReservation['custom_f'] = array();
		}
		
		$q = "SELECT * FROM `#__cleverdine_custfields` 
		WHERE `group`=1 AND (`type`<>'checkbox' OR `required`=0) 
		ORDER BY `ordering` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$cfields = $dbo->loadAssocList();
		}
		
		if( !cleverdine::isContinuosOpeningTime(true) ) {
			list($selectedReservation['hour'], $selectedReservation['min']) = explode(':', $selectedReservation['hourmin']);
			
			$shifts = cleverdine::getWorkingShifts(2, true);
			$special_day_for = cleverdine::getSpecialDaysOnDate($selectedReservation, 2, true);
			if( count($special_day_for) > 0 && $special_day_for !== -1 ) {
				$shifts = cleverdine::getWorkingShiftsFromSpecialDays($shifts, $special_day_for, 2, true);
			}
		} else {
			$continuos = array( cleverdine::getFromOpeningHour(true), cleverdine::getToOpeningHour(true) );
		}
		
		$countries = array();
		$q = "SELECT * FROM `#__cleverdine_countries` ORDER BY `phone_prefix` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$countries = $dbo->loadAssocList();
		}

		$customer = null;
		if( $selectedReservation['id_user'] > 0 ) {
			$customer = cleverdine::getCustomer($selectedReservation['id_user']);
		}

		$payments = array();
		$q = "SELECT `id`, `name`, `charge`, `percentot`, `published` FROM `#__cleverdine_gpayments` WHERE `group`<>1 ORDER BY `published` DESC;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$payments = $dbo->loadAssocList();
		}

		$q = "SELECT MAX(`charge`) FROM `#__cleverdine_takeaway_delivery_area` WHERE `published`=1;";
		$dbo->setQuery($q);
		$dbo->execute();
		$max_delivery_charge = $dbo->loadResult();

		$this->selectedReservation 	= &$selectedReservation;
		$this->custom_fields 		= &$cfields;
		$this->shifts 				= &$shifts;
		$this->continuos 			= &$continuos;
		$this->customer 			= &$customer;
		$this->countries 			= &$countries;
		$this->payments 			= &$payments;
		$this->maxDeliveryCharge 	= &$max_delivery_charge;

		// remove all expired orders
		cleverdine::removeAllTakeAwayOrdersOutOfTime($dbo);

		// Display the template
		parent::display($tpl);

	}

	/**
	 * Setting the toolbar
	 */
	private function addToolBar($type) {
		//Add menu title and some buttons to the page
		if( $type == "edit" ) {
			JToolbarHelper::title(JText::_('VRMAINTITLEEDITTKRES'), 'restaurants');
		} else {
			JToolbarHelper::title(JText::_('VRMAINTITLENEWTKRES'), 'restaurants');
		}
		
		if (JFactory::getUser()->authorise('core.edit', 'com_cleverdine')) {
			if( $type == "edit" ) {
				//JToolbarHelper::apply('saveTkreservation', JText::_('VRSAVE'));
				JToolbarHelper::apply('saveAndNextTkreservation', JText::_('VRSAVEANDCART'));
				JToolbarHelper::spacer();
				JToolbarHelper::save('saveAndCloseTkreservation', JText::_('VRSAVEANDCLOSE'));
				JToolbarHelper::spacer();
				JToolbarHelper::custom('saveAndNewTkreservation', 'save-new', 'save-new', JText::_('VRSAVEANDNEW'), false, false);
			} else {
				JToolbarHelper::apply('saveAndNextTkreservation', JText::_('VRSAVEANDCART'));
			}
			JToolbarHelper::divider();
		}
		
		JToolbarHelper::cancel('cancelTkreservation', JText::_('VRCANCEL'));
	}

}
?>