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
class cleverdineVieworder extends JViewUI {

	/**
	 * Order view display method
	 * @return void
	 */
	function display($tpl = null) {
		
		cleverdine::load_css_js();

		$mainframe 	= JFactory::getApplication();
		$input 		= $mainframe->input;
		$dbo 		= JFactory::getDbo();
		
		$oid 	= $input->get('ordnum', 0, 'uint');
		$sid 	= $input->get('ordkey', '', 'alnum');
		$otype 	= $input->get('ordtype', 0, 'uint');

		$this->orderHasExpired($oid, $sid, $otype, $dbo);
		
		$order 			= array();
		$payment 		= null;
		$array_order 	= array();
		
		if( !empty( $oid ) && !empty( $sid ) ) {

			if( $otype == 0 ) {

				// RESTAURANT TYPE ORDER

				$q = "SELECT `id` FROM `#__cleverdine_reservation` WHERE `id`=$oid AND `sid`=".$dbo->quote($sid)." LIMIT 1;";
				$dbo->setQuery($q);
				$dbo->execute();
				if( $dbo->getNumRows() > 0 ) {

					$order = cleverdine::fetchOrderDetails($dbo->loadResult());
					
					$q = "SELECT * FROM `#__cleverdine_gpayments` WHERE `id`=".$order['id_payment']." LIMIT 1;";
					$dbo->setQuery($q);
					$dbo->execute();
					if( $dbo->getNumRows() > 0 ) {
						$payment = $dbo->loadAssoc();

						$payment['name'] = $order['payment_name'];
						$payment['note'] = $order['payment_note'];
						$payment['prenote'] = $order['payment_prenote'];
					}
					
					$menus = array();
					$q = "SELECT `m`.`name`, `a`.`quantity` FROM `#__cleverdine_menus` AS `m` LEFT JOIN `#__cleverdine_res_menus_assoc` AS `a` ON `m`.`id`=`a`.`id_menu` WHERE `a`.`id_reservation`=".$oid.";";
					$dbo->setQuery($q);
					$dbo->execute();
					if( $dbo->getNumRows() > 0 ) {
						$menus = $dbo->loadAssocList();
					}
					
					if( $order['status'] == "PENDING" && $order['id_payment'] > 0 && $payment !== null ) {
						
						$return_url = JUri::root() . "index.php?option=com_cleverdine&view=order&ordnum=" . $order['id'] . "&ordkey=" . $order['sid'] . "&ordtype=0";
						$error_url  = JUri::root() . "index.php?option=com_cleverdine&view=order&ordnum=" . $order['id'] . "&ordkey=" . $order['sid'] . "&ordtype=0";
						$notify_url = JUri::root() . "index.php?option=com_cleverdine&task=notifypayment&ordnum=" . $order['id'] . "&ordkey=" . $order['sid'];
						$transaction_name = JText::sprintf('VRTRANSACTIONNAME', cleverdine::getRestaurantName());
				
						$array_order['oid'] = $order['id'];
						$array_order['sid'] = $order['sid'];
						$array_order['tid'] = 0;
						$array_order['transaction_currency'] = cleverdine::getCurrencyName();
						$array_order['transaction_name'] = $transaction_name;
						$array_order['currency_symb'] = cleverdine::getCurrencySymb();
						$array_order['tax'] = 0;
						$array_order['return_url'] = $return_url;
						$array_order['error_url'] = $error_url;
						$array_order['notify_url'] = $notify_url;
						$array_order['total_to_pay'] = $order['deposit'];
						$array_order['total_net_price'] = $order['deposit'];
						$array_order['total_tax'] = 0;
						$array_order['leave_deposit'] = 0;
						$array_order['payment_info'] = $payment;
						$array_order['details'] = array(
							'purchaser_mail' => $order['purchaser_mail'],
							'purchaser_phone' => $order['purchaser_phone'],
							'purchaser_nominative' => $order['purchaser_nominative']
						);
							
					} 
					
				} else {
					$mainframe->enqueueMessage(JText::_('VRORDERRESERVATIONERROR'), 'error');
				}
				
				$this->menus = &$menus;

			} else {

				// TAKEAWAY TYPE ORDER
				
				$items = array();
				
				$q = "SELECT `id` FROM `#__cleverdine_takeaway_reservation` WHERE `id`=$oid AND `sid`=".$dbo->quote($sid)." LIMIT 1;";
				$dbo->setQuery($q);
				$dbo->execute();
				if( $dbo->getNumRows() > 0 ) {
					
					$order = cleverdine::fetchTakeawayOrderDetails($dbo->loadResult());
					
					$q = "SELECT * FROM `#__cleverdine_gpayments` WHERE `id`=".$order['id_payment']." LIMIT 1;";
					$dbo->setQuery($q);
					$dbo->execute();
					if( $dbo->getNumRows() > 0 ) {
						$payment = $dbo->loadAssoc();

						$payment['name'] = $order['payment_name'];
						$payment['note'] = $order['payment_note'];
						$payment['prenote'] = $order['payment_prenote'];
					}
					
					if( $order['status'] == "PENDING" && $order['id_payment'] > 0 && $payment !== null ) {
						
						$return_url = JUri::root() . "index.php?option=com_cleverdine&view=order&ordnum=" . $order['id'] . "&ordkey=" . $order['sid'] . "&ordtype=1";
						$error_url  = JUri::root() . "index.php?option=com_cleverdine&view=order&ordnum=" . $order['id'] . "&ordkey=" . $order['sid'] . "&ordtype=1";
						$notify_url = JUri::root() . "index.php?option=com_cleverdine&task=notifytkpayment&ordnum=" . $order['id'] . "&ordkey=" . $order['sid'];
						$transaction_name = JText::sprintf('VRTRANSACTIONNAME', cleverdine::getRestaurantName());
						
						$array_order['oid'] = $order['id'];
						$array_order['sid'] = $order['sid'];
						$array_order['tid'] = 1;
						$array_order['transaction_currency'] = cleverdine::getCurrencyName();
						$array_order['transaction_name'] = $transaction_name;
						$array_order['currency_symb'] = cleverdine::getCurrencySymb();
						$array_order['tax'] = 0;
						$array_order['return_url'] = $return_url;
						$array_order['error_url'] = $error_url;
						$array_order['notify_url'] = $notify_url;
						$array_order['total_to_pay'] = $order['total_to_pay'];
						$array_order['total_net_price'] = $order['total_to_pay'];
						$array_order['total_tax'] = 0;
						$array_order['leave_deposit'] = 0;
						$array_order['payment_info'] = $payment;
						$array_order['details'] = array(
							'purchaser_mail' => $order['purchaser_mail'],
							'purchaser_phone' => $order['purchaser_phone'],
							'purchaser_nominative' => $order['purchaser_nominative']
						);
							
					} 
					
					$items = $order['items'];
					
				} else {
					$mainframe->enqueueMessage(JText::_('VRORDERRESERVATIONERROR'), 'error');
				}
			
				$this->items = &$items;
			}
		
		}

		$this->order 		= &$order;
		$this->array_order 	= &$array_order;
		$this->payment 		= &$payment;
		$this->ordtype 		= &$otype;

		// prepare page content
		cleverdine::prepareContent($this);
		
		// Display the template
		parent::display($tpl);

	}

	private function orderHasExpired($oid, $sid, $tid, $dbo='') {
		if( empty($dbo) ) {
			$dbo = JFactory::getDbo();
		}
		
		$table = 'cleverdine_'.($tid == 0 ? '' : 'takeaway_').'reservation';

		$q = "SELECT `sid`, `status`, `locked_until` FROM `#__$table` WHERE `id`=$oid LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->execute();

		if( !$dbo->getNumRows() ) {
			return false;
		}

		$order = $dbo->loadAssoc();

		if( !strcmp($order['sid'], $sid) && $order['status'] == 'PENDING' && $order['locked_until'] < time() ) {
			$q = "UPDATE `#__$table` SET `status`='REMOVED' WHERE `id`=$oid LIMIT 1;";
			$dbo->setQuery($q);
			$dbo->execute();

			return true;
		}
		
		return false;
		
	}

}
?>