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
class cleverdineViewtakeawayconfirm extends JViewUI {
	
	/**
	 * Restaurants view display method
	 * @return void
	 */
	function display($tpl = null) {

		cleverdine::load_css_js();
		cleverdine::load_complex_select();
		cleverdine::load_googlemaps();
		cleverdine::load_font_awesome();
		
		$mainframe 	= JFactory::getApplication();
		$input 		= $mainframe->input;
		$dbo 		= JFactory::getDbo();

		$session = JFactory::getSession();
		
		$mincost = cleverdine::getTakeAwayMinimumCostPerOrder();
		
		// get cart instance
		cleverdine::loadCartLibrary();
		cleverdine::loadDealsLibrary();
		
		$cart = TakeAwayCart::getInstance();
		
		$last_date 		= $input->get('date', '', 'string');
		$last_time 		= $input->get('hourmin', '', 'string');
		$delivery_val 	= $input->get('delivery', 1, 'uint');
		
		if( empty($last_date) ) {
			$last_date = date( cleverdine::getDateFormat(), $cart->getCheckinTimestamp() );
		} else if( cleverdine::isTakeAwayDateAllowed() ) {
			// only if date is set and date can be changed
			$cart->setCheckinTimestamp(cleverdine::createTimestamp($last_date, 0, 0));

			// check stock availability
			cleverdine::checkCartStockAvailability($cart);

			// check for deals
			cleverdine::resetDealsInCart($cart);
			cleverdine::checkForDeals($cart);
			
			$cart->store();
		}

		if( !cleverdine::isTakeAwayReservationsAllowedOn( $cart->getCheckinTimestamp() ) ) {
			// if orders are stopped
			$mainframe->enqueueMessage(JText::_('VRTKMENUNOTAVAILABLE3'), 'error');
			$mainframe->redirect(JRoute::_('index.php?option=com_cleverdine&view=takeaway'));
			exit;
		}
		
		if( $cart->getRealTotalCost() < $mincost ) {
			$mainframe->enqueueMessage(JText::sprintf('VRTAKEAWAYMINIMUMCOST', cleverdine::printPriceCurrencySymb($mincost)), 'error');
			$mainframe->redirect(JRoute::_('index.php?option=com_cleverdine&view=takeaway', false));
			exit;
		}
		
		if( empty($last_time) ) {
			$last_time = '-1:0';
		}
		
		$_hour_min = explode( ':', $last_time );
		
		$dt_args = array( 'date' => $last_date, 'hourmin' => $last_time, 'hour' => intval($_hour_min[0]), 'min' => intval($_hour_min[1]) );
		
		$shifts = array();
		$continuos = array();
		
		$special_days = cleverdine::getSpecialDaysOnDate($dt_args, 2);
		if( !cleverdine::isContinuosOpeningTime() ) {
			$shifts = cleverdine::getWorkingShifts(2);
			
			if( $special_days != -1 && count($special_days) > 0 ) {
				$shifts = cleverdine::getWorkingShiftsFromSpecialDays( $shifts, $special_days, 2 );
			}
		} else {
			$continuos = array( cleverdine::getFromOpeningHour(), cleverdine::getToOpeningHour() );
		}
		
		$coupon_key = $input->get('couponkey', '', 'string');
		$cust_f = array();
		$payments = array();
		$any_coupon = 0;
		
		$q = "SELECT * FROM `#__cleverdine_gpayments` WHERE `published`=1 AND `group`<>1 ORDER BY `ordering` ASC;"; // group 1 : only restaurant
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {

			$cost = $cart->getRealTotalCost();

			foreach( $dbo->loadAssocList() as $p ) {
				if( $p['enablecost'] == 0 || ( $p['enablecost'] > 0 && $p['enablecost'] <= $cost ) || ( $p['enablecost'] < 0 && abs($p['enablecost']) >= $cost ) ) {
					array_push($payments, $p);
				}
			}

		}
		
		$q = "SELECT * FROM `#__cleverdine_custfields` WHERE `group`=1 ORDER BY `ordering` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() ) {
			$cust_f = $dbo->loadAssocList();
		}
		
		$cp_err = true;
		
		if( strlen( $coupon_key ) > 0 ) {
			$q = "SELECT * FROM `#__cleverdine_coupons` WHERE `code`=".$dbo->quote($coupon_key)." LIMIT 1;";
			$dbo->setQuery($q);
			$dbo->execute();
			if( $dbo->getNumRows() ) {
				$coupon = $dbo->loadAssoc();
				
				if( cleverdine::validateTakeawayCoupon( $coupon, $cart ) ) {
					$session->set('vr_coupon_data',$coupon);
					$mainframe->enqueueMessage(JText::_('VRCOUPONFOUND'));
					
					$cp_err = false;
				} else {
					$mainframe->enqueueMessage(JText::_('VRCOUPONNOTVALID'), 'error');
				}
				
			} else {
				$mainframe->enqueueMessage(JText::_('VRCOUPONNOTVALID'), 'error');
			}
		}
		
		if( $cp_err ) {
			$last_cp = $session->get('vr_coupon_data', '');
			if( !empty($last_cp) ) {
				if( !cleverdine::validateTakeawayCoupon( $last_cp, $cart ) ) {
					$session->set('vr_coupon_data', '');
				}
			}
		}
		
		$q = "SELECT COUNT(`id`) AS `count` FROM `#__cleverdine_coupons`;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$_app = $dbo->loadAssocList();
			$any_coupon = (( $_app[0]['count'] > 0 ) ? 1 : 0 );
		}
		
		cleverdine::removeAllTakeAwayOrdersOutOfTime($dbo);
		
		$freq_time = $this->filterQuantityItemsTime( $this->getQuantityItemsTimeOnDay($dbo, $dt_args['date']), $shifts, $continuos );
		
		$soon_time = array( 'hour' => -1, 'min' => 0 );
		
		if( cleverdine::isClosingDay($dt_args, true) ) {
			if( $special_days != -1 && count($special_days) > 0 ) {
				$ignore_cd = false;
				for( $i = 0; $i < count($special_days) && !$ignore_cd; $i++ ) {
					$ignore_cd = $special_days[$i]['ignoreclosingdays'];
				}
				if( !$ignore_cd ) {
					$shifts = array();
					$continuos = array();
				}
			}
		}
		
		$cal_special_days = array();
		$q = "SELECT * FROM `#__cleverdine_specialdays` WHERE `group`=2;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$cal_special_days = $dbo->loadAssocList();
		}
		
		$user = cleverdine::getCustomer();
		if( $user === null ) {
			$user = array();
		}
		
		$countries = array();
		$q = "SELECT * FROM `#__cleverdine_countries` WHERE `published`=1 ORDER BY `phone_prefix` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$countries = $dbo->loadAssocList();
		}

		$payments_ids = array();
		foreach( $payments as $p ) {
			array_push($payments_ids, $p['id']);
		}
		$payments_translations = cleverdine::getTranslatedPayments($payments_ids);

		$customf_ids = array();
		foreach( $cust_f as $cf ) {
			array_push($customf_ids, $cf['id']);
		}
		$customf_translations = cleverdine::getTranslatedCustomFields($customf_ids);
		
		$this->cart 			= &$cart;
		$this->customFields 	= &$cust_f;
		$this->payments 		= &$payments;
		$this->anyCoupon 		= &$any_coupon;
		$this->dt_args 			= &$dt_args;
		$this->soon_time 		= &$soon_time;
		$this->freq_time 		= &$freq_time;
		$this->delivery_val 	= &$delivery_val;
		$this->continuos 		= &$continuos;
		$this->shifts 			= &$shifts;
		$this->specialDays 		= $special_days;
		$this->cal_special_days = &$cal_special_days;
		$this->user 			= &$user;
		$this->countries 		= &$countries;

		$this->paymentsTranslations = &$payments_translations;
		$this->customfTranslations 	= &$customf_translations;
		
		// Display the template
		parent::display($tpl);

	}

	protected function getAllItemsOnDay($dbo,$day) {
		$_ts = cleverdine::createTimestamp( $day, 0, 0 );
		$q = "SELECT `r`.`checkin_ts`, SUM(`a`.`quantity`) as `sum_quantity` 
				FROM `#__cleverdine_takeaway_reservation` AS `r`, `#__cleverdine_takeaway_res_prod_assoc` AS `a`, `#__cleverdine_takeaway_menus_entry` AS `e` 
				WHERE `r`.`id`=`a`.`id_res` AND `a`.`id_product`=`e`.`id` AND `e`.`ready`=0 
				AND (`r`.`status`='CONFIRMED' OR `r`.`status`='PENDING')
				AND ".$_ts." <= `r`.`checkin_ts` AND `r`.`checkin_ts` <= ".$_ts."+86400
				GROUP BY `r`.`checkin_ts` ORDER BY `r`.`checkin_ts`;";	
		
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			return $dbo->loadAssocList();
		}
		return array();
	}

	protected function getQuantityItemsTimeOnDay($dbo,$day) {
		$rows = $this->getAllItemsOnDay($dbo, $day);
		$min_interval = cleverdine::getTakeAwayMinuteInterval();
		$asap = cleverdine::getTakeAwayAsapAfter();
		
		$freq_rows = array();
		
		$now_ts = time();
		$now_ts = $now_ts - ($now_ts%($min_interval*60));
		$now_ts += (($min_interval*60)*$asap);
		
		for( $i = 0, $n = count($rows); $i < $n; $i++ ) {
			if( $now_ts <= $rows[$i]['checkin_ts'] ) {
				if( empty( $freq_rows[$rows[$i]['checkin_ts']] ) ) {
					$freq_rows[$rows[$i]['checkin_ts']] = $rows[$i]['sum_quantity'];
				} 
			}
		}
		
		return $freq_rows;
	}
	
	protected function filterQuantityItemsTime($rows,$shifts,$continuos) {
		$result_arr = array(); 
		
		if( count( $continuos ) == 2  ) {
			$from = $continuos[0];
			$to   = $continuos[1];
			
			if( $from <= $to ) {
				foreach( $rows as $k => $v  ) {
					$hour = date( 'H', $k );
					if( $hour >= $from && $hour <= $to ) {
						$result_arr[$k] = $v;
					} 
				}
			} else {
				foreach( $rows as $k => $v  ) {
					$hour = date( 'H', $v );
					if( ( $hour >= 0 && $hour <= $to ) || ( $hour >= $from && $hour <= 23 ) ) {
						$result_arr[$k] = $v;
					} 
				}
			}
		} else {
			foreach( $rows as $k => $v ) {
				$_hm = explode(':', date( 'H:i', $k ) );
				
				$_hour_minute_full = $_hm[0]*60+$_hm[1];
				
				$isValid = 0;
				for( $i = 0, $n = count( $shifts ); $i < $n && !$isValid; $i++ ) {
					if( $shifts[$i]['from'] <= $_hour_minute_full && $_hour_minute_full <= $shifts[$i]['to'] ) {
						$isValid = 1;
						$result_arr[$k] = $v;
					}
				}
			}
		}
		
		return $result_arr;
	}
	
}
?>