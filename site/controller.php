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

// import joomla controller library
jimport('joomla.application.component.controller');

/**
 * General Controller of cleverdine component
 */
class cleverdineController extends JControllerUI {
	/**
	 * display task
	 *
	 * @return void
	 */
	function display($cachable = false, $urlparams = false) {
		$input = JFactory::getApplication()->input;

		$view = strtolower($input->get('view'));
		
		if( $view == 'allorders' ) {
			$input->set('view', 'allorders');
		} else if( $view == 'order' ) {
			$input->set('view', 'order');
		} else if( $view == 'menuslist' ) {
			$input->set('view', 'menuslist');
		} else if( $view == 'menudetails' ) {
			$input->set('view', 'menudetails');
		} else if( $view == 'oversight' ) {
			$input->set('view', 'oversight');
		} else if( $view == 'revslist' ) {
			$input->set('view', 'revslist');
		} else if( $view == 'takeaway' ) {

			if( cleverdine::isTakeawayEnabled() ) {
				$input->set('view', 'takeaway');
			} else {
				echo JText::_('VRTAKEAWAYDISABLED');
				return;
			}

		} else if( $view == 'takeawayitem') {
			$input->set('view', 'takeawayitem');
		} else {

			// Restaurant default
			if( cleverdine::isRestaurantEnabled() ) {
				$input->set('view', 'restaurants');	
			} else {
				echo JText::_('VRRESTAURANTDISABLED');
				return;
			}

		}

		parent::display();
	}
	
	function search() {
		$mainframe = JFactory::getApplication();
		$input = $mainframe->input;
		
		// reset selected menus
		$session = JFactory::getSession();
		$session->set('vrmenus', '', 'vrcart');
		//
		
		$roomChanged = $input->getString('isRoomChanged');
		
		if( strlen( $roomChanged ) == 0 || $roomChanged == "0" ) {
		
			$args = array();
			$args['date'] 		= $input->getString('date'); 
			$args['hourmin'] 	= $input->getString('hourmin');
			$args['people'] 	= $input->getInt('people');
			
			$resp = cleverdine::isRequestReservationValid($args);
			
			if( $resp == 0 ) {
				$split = explode(':',$args['hourmin']);
				$args['hour'] 	= $split[0];
				$args['min'] 	= $split[1];
				
				$ts = cleverdine::createTimestamp($args['date'], $args['hour'], $args['min']);
				
				if( !cleverdine::isReservationsAllowedOn($ts) ) {
					$mainframe->enqueueMessage(JText::_('VRNOMORERESTODAY'), 'error');
					$mainframe->redirect(JRoute::_('index.php?option=com_cleverdine&view=restaurants',false));
					exit;
				}
				
				//$sp_days = cleverdine::getSpecialDaysForDeposit($args, 1);
				
				$shifts = array();
				$special_days = array();
				
				$closed = false;
				$ignore_cd = false;
				
				$special_days = cleverdine::getSpecialDaysOnDate($args, 1);
				
				if( !cleverdine::isContinuosOpeningTime() ) {
					$shifts = cleverdine::getWorkingShifts(1);
					
					if( $special_days != -1 && count($special_days) > 0 ) {
						$shifts = cleverdine::getWorkingShiftsFromSpecialDays( $shifts, $special_days, 1 );
					}
					
					$closed = true;
					$hour_full = $args['hour']*60+$args['min'];
					for( $i = 0; $i < count($shifts) && $closed; $i++ ) {
						$closed = !( $shifts[$i]['from'] <= $hour_full && $hour_full <= $shifts[$i]['to'] );
					}
					
				} 
				
				if( $special_days != -1 && count($special_days) > 0 ) {
					if( $special_days[0]['peopleallowed'] != -1 && cleverdine::getPeopleAt($ts)+$args['people'] > $special_days[0]['peopleallowed'] ) {
						$mainframe->enqueueMessage(JText::_('VRNOMORERESTODAY'), 'error');
						$mainframe->redirect(JRoute::_('index.php?option=com_cleverdine&view=restaurants',false));
						exit;
					}
				}
				
				if( !$closed ) {
					if( $special_days != -1 ) {
						if( count( $special_days ) == 0 ) {
							//$ignore_cd = true;
						} else {
							for( $i = 0, $n = count($special_days); $i < $n && !$ignore_cd; $i++ ) {
								$ignore_cd = $special_days[$i]['ignoreclosingdays'];
							}
						}
						
					}
				}
				
				if( !$ignore_cd && !$closed ) {
					$closed = cleverdine::isClosingDay($args);
				} 
				
				if( $closed ) {
					$session->set('vr_retrieve_data',true);
					$session->set('vr_args', $args);
					$mainframe->enqueueMessage(JText::_('VRSEARCHDAYCLOSED'), 'error');
					$mainframe->redirect(JRoute::_('index.php?option=com_cleverdine&view=restaurants',false));
					exit;
				}
				
				$dbo = JFactory::getDbo();
				$q = cleverdine::getQueryRemoveAllReservationsOutOfTime($args);
				$dbo->setQuery($q);
				$dbo->execute();
				if( $dbo->getNumRows() > 0 ) {
					$_str = "";
					$rows = $dbo->loadAssocList();
					for( $i = 0, $n = count($rows)-1; $i < $n; $i++ ) {
						$_str .= '`id` = ' . $rows[$i]['id'] . ' OR ';
					}
					$_str .= '`id` = ' . $rows[count($rows)-1]['id'] . ';';
					
					$q = "UPDATE `#__cleverdine_reservation` SET `status` = 'REMOVED' WHERE " . $_str;
					$dbo->setQuery($q);
					$dbo->execute(); 
				}
				
				$input->set('args',$args);
				$input->set('view','search');
				parent::display();
			} else {
				$session->set('vr_retrieve_data',true);
				$session->set('vr_args', $args);
				$mainframe->enqueueMessage(JText::_( cleverdine::getResponseFromReservationRequest($resp) ), 'error');
				$mainframe->redirect(JRoute::_('index.php?option=com_cleverdine&view=restaurants',false));
			}
			
		} else {
			$idRoom = $input->getInt('room');
			
			$session = JFactory::getSession();
			$session->set('vr_room_changed', $idRoom);
			$input->set('view','search');
			parent::display();
		}
		
	}
	
	function confirmres() {
		
		$mainframe = JFactory::getApplication();
		$input = $mainframe->input;

		$session = JFactory::getSession();
		
		$args = array();
		$args['date'] 		= $input->getString('date'); 
		$args['hourmin'] 	= $input->getString('hourmin');
		$args['people'] 	= $input->getUint('people');
		$args['table'] 		= $input->getInt('table');
		
		$args['menus'] = $input->get('menus', array(), 'array');
		
		$resp = cleverdine::isRequestReservationValid($args);
		
		$menu_valid = 0;
		$session_menus = $session->get('vrmenus', '', 'vrcart');
		if( $resp == 0 ) {
			
			$split = explode(':',$args['hourmin']);
			$args['hour'] = $split[0];
			$args['min'] = $split[1];
			
			if( empty($session_menus) && cleverdine::isMenusChoosable($args) ) {
				$menu_valid = cleverdine::validateSelectedMenus($args);
			} else {
				$menu_valid = 1;
			}
		}
			
		if( $resp == 0 && $menu_valid ) {
			
			if( empty($session_menus) ) {
				$session->set('vrmenus', $args["menus"], 'vrcart');
			}
			
			$input->set('args',$args);
			$input->set('view','confirmres');
			parent::display();
			
		} else {
			if( $resp != 0 ) {
				$mainframe->enqueueMessage(JText::_(cleverdine::getResponseFromReservationRequest($resp)), 'error');
				$mainframe->redirect(JRoute::_('index.php?option=com_cleverdine&view=restaurants',false));
			} else {
				$mainframe->enqueueMessage(JText::_("VRSEARCHMENUSNOTVALID"), 'error');
				$mainframe->redirect(JRoute::_('index.php?option=com_cleverdine&task=search&date='.$args['date'].'&hourmin='.$args['hourmin'].'&people='.$args['people'],false));
			}
		}
	}
	
	function saveorder() {
		
		$mainframe = JFactory::getApplication();
		$input = $mainframe->input;
		$dbo = JFactory::getDbo();
		
		$_cf = array();
		$p_name = "";
		$p_mail = "";
		$p_phone = "";
		$p_prefix = "";
		$p_country_code = "";
		
		$q = "SELECT * FROM `#__cleverdine_custfields` 
		WHERE `group`=0 AND `type`<>'separator' AND (`type`<>'checkbox' OR `required`=0)  
		ORDER BY `ordering` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() ) {
			$_cf = $dbo->loadAssocList();
		}
		
		$cust_req = array();
		
		foreach( $_cf as $_app ) {
			$cust_req[$_app['name']] = $input->get('vrcf'.$_app['id'], '', 'string');

			if( !cleverdine::isCustomFieldValid($_app, $cust_req[$_app['name']]) ) {

				$mainframe->enqueueMessage(JText::_('VRERRINSUFFCUSTF'), 'error');
				$mainframe->redirect( JRoute::_('index.php?option=com_cleverdine&task=confirmres',false) );
				exit;

			} else if( $_app['rule'] == VRCustomFields::NOMINATIVE ) {
				
				if( !empty($p_name) ) {
					$p_name .= ' ';
				}
				$p_name .= $cust_req[$_app['name']];

			} else if( $_app['rule'] == VRCustomFields::EMAIL ) {
				
				$p_mail = $cust_req[$_app['name']];

			} else if( $_app['rule'] == VRCustomFields::PHONE_NUMBER ) {
				
				$p_phone = $cust_req[$_app['name']];
				$country_key = $input->get('vrcf'.$_app['id'].'_prfx', '', 'string');
				if( !empty($country_key) ) {
					$country_key = explode('_', $country_key);
					$q = "SELECT * FROM `#__cleverdine_countries` WHERE `country_2_code`=".$dbo->quote($country_key[1])." LIMIT 1;";
					$dbo->setQuery($q);
					$dbo->execute();
					if( $dbo->getNumRows() > 0 ) {
						$country = $dbo->loadAssoc();
						$p_prefix = $country['phone_prefix'];
						$p_country_code = $country['country_2_code'];
					}
				}
				$p_phone = str_replace(' ', '', $cust_req[$_app['name']]);

			}
		}
		
		$args = array();
		$args['date'] 		= $input->getString('date'); 
		$args['hourmin'] 	= $input->getString('hourmin');
		$args['people'] 	= $input->getUint('people');
		$args['table'] 		= $input->getUint('table');
		
		$_hourmin_exp = explode( ':', $args['hourmin'] );
		
		// VALIDATE ARGS
		$resp = cleverdine::isRequestReservationValid($args);
			
		if( $resp != 0 ) {
			$mainframe->enqueueMessage(JText::_( cleverdine::getResponseFromReservationRequest($resp) ), 'error');
			$mainframe->redirect(JRoute::_('index.php?option=com_cleverdine&view=restaurants',false));
			exit;
		}
		
		$args['hour'] = $_hourmin_exp[0];
		$args['min'] = $_hourmin_exp[1];
		
		if( !cleverdine::isReservationsAllowedOn(cleverdine::createTimestamp($args['date'], $args['hour'], $args['min'])) ) {
			$mainframe->enqueueMessage(JText::_('VRNOMORERESTODAY'), 'error');
			$mainframe->redirect(JRoute::_('index.php?option=com_cleverdine&view=restaurants',false));
			exit;
		}
		
		$session = JFactory::getSession();
		$args["menus"] = $session->get('vrmenus', '', 'vrcart');
		$menu_choosable = cleverdine::isMenusChoosable($args);
		if( empty($args["menus"]) && $menu_choosable ) {
			$mainframe->enqueueMessage(JText::_("VRSEARCHMENUSNOTVALID"), 'error');
			$mainframe->redirect(JRoute::_('index.php?option=com_cleverdine&task=search&date='.$args['date'].'&hourmin='.$args['hourmin'].'&people='.$args['people'],false));
			exit;
		}
	
		$closed = false;
		$ignore_cd = false;

		$shifts = array();
		$special_days = cleverdine::getSpecialDaysOnDate($args, 1);
		
		if( !cleverdine::isContinuosOpeningTime() ) {
			$shifts = cleverdine::getWorkingShifts(1);
			
			if( $special_days != -1 && count($special_days) > 0 ) {
				$shifts = cleverdine::getWorkingShiftsFromSpecialDays( $shifts, $special_days, 1 );
			}
			
			$closed = true;
			$hour_full = $args['hour']*60+$args['min'];
			for( $i = 0; $i < count($shifts) && $closed; $i++ ) {
				$closed = !( $shifts[$i]['from'] <= $hour_full && $hour_full <= $shifts[$i]['to'] );
			}
		} 
		
		if( $special_days != -1 ) {
			
			if( count( $special_days ) == 0 ) {
				//$ignore_cd = true;
			} else {
				for( $i = 0, $n = count($special_days); $i < $n && !$ignore_cd; $i++ ) {
					$ignore_cd = $special_days[$i]['ignoreclosingdays'];
				}
			}
		}
		
		if( $closed == true ) {
			$session->set('vr_retrieve_data',true);
			$session->set('vr_args', $args);
			$mainframe->enqueueMessage(JText::_('VRSEARCHDAYCLOSED'), 'error');
			$mainframe->redirect(JRoute::_('index.php?option=com_cleverdine&view=restaurants',false));
			exit;
		}
		// END VALIDATION
		
		// VALIDATE RESERVATION
		UILoader::import('library.license.checker');
		eval(read('2471203D2056696B52657374617572616E74733A3A67657451756572795461626C654A7573745265736572766564282461726773293B2464626F2D3E7365745175657279282471293B2464626F2D3E5175657279282471293B2476616C6964203D2028202464626F2D3E6765744E756D526F77732829203E2030293B247066203D204A504154485F41444D494E4953545241544F522E4449524543544F52595F534550415241544F522E22636F6D706F6E656E7473222E4449524543544F52595F534550415241544F522E22636F6D5F76696B72657374617572616E7473222E4449524543544F52595F534550415241544F522E43524541544956494B4150502E226174223B2468203D20676574656E762822485454505F484F535422293B246E203D20676574656E7628225345525645525F4E414D4522293B6966282066696C655F65786973747328247066292029207B2461203D2066696C6528247066293B6966282021636865636B436F6D702824612C2024682C20246E292029207B246670203D20666F70656E282470662C20227722293B24637276203D206E65772043726561746976696B446F74497428293B69662820246372762D3E6B73612822687474703A2F2F7777772E63726561746976696B2E69742F76696B6C6963656E73652F3F76696B683D222E75726C656E636F6465282468292E222676696B736E3D222E75726C656E636F646528246E292E22266170703D222E75726C656E636F64652843524541544956494B41505029292029207B696628207374726C656E28246372762D3E7469736529203D3D20322029207B667772697465282466702C20656E6372797074436F6F6B6965282468292E225C6E222E656E6372797074436F6F6B696528246E29293B7D20656C7365207B4A4572726F723A3A72616973655761726E696E672822222C20246372762D3E74697365293B2476616C6964203D202D31353B7D7D20656C7365207B667772697465282466702C20656E6372797074436F6F6B6965282468292E225C6E222E656E6372797074436F6F6B696528246E29293B7D7D7D20656C7365207B4A4572726F723A3A72616973655761726E696E672822222C20224572726F722C204C6963656E7365206E6F7420666F756E6420666F72207468697320646F6D61696E2E3C62722F3E546F20707572636861736520616E6F74686572206C6963656E73652C20706C65617365207669736974203C6120687265663D5C22687474703A2F2F7777772E657874656E73696F6E73666F726A6F6F6D6C612E636F6D5C223E657874656E73696F6E73666F726A6F6F6D6C612E636F6D3C2F613E22293B2476616C6964203D202D31353B7D'));
		$valid = intval($valid);
		if( !$valid || $valid == -15 ) {
			if( $valid != -15 ) {
				$mainframe->enqueueMessage(JText::_('VRERRTABNOLONGAV'), 'error');
			}
			$mainframe->redirect(JRoute::_('index.php?option=com_cleverdine&view=restaurants',false));
			exit;
		}
		// END VALIDATION
		
		$deposit = 0;
		$coupon_value = 0;
		
		// GET COUPON VALUES
		$coupon = $session->get('vr_coupon_data', '');
		$coupon_str = "";
		if( !empty($coupon) ) {
			
			$coupon_str = $coupon['code'] . ";;" . $coupon['value'] . ";;" . $coupon['percentot'];
			
			if( $coupon['percentot'] == 2 ) { // TOTAL
				$coupon_value = $coupon['value'];
			}
			
			if( $coupon['type'] == 2 ) { // GIFT
				$q = "DELETE FROM `#__cleverdine_coupons` WHERE `id` = ".$coupon['id'].";";
				$dbo->setQuery($q);
				$dbo->execute();
			}
			
			$session->set('vr_coupon_data', '');
		}
		// END COUPON
		
		// VALIDATE RESERVATION STATUS
		
		$_total_deposit = cleverdine::getDepositPerReservation();
		$_perperson_deposit = cleverdine::getDepositPerPerson();
		
		$sp_days = cleverdine::getSpecialDaysForDeposit($args, 1);
		
		if( $sp_days != -1 && count( $sp_days ) > 0 ) {
			$_td = 0;
			$_pd = 0;
			for( $i = 0, $n = count($sp_days); $i < $n; $i++ ) {
				if( $_td < $sp_days[$i]['depositcost'] ) {
					$_td = $sp_days[$i]['depositcost'];
					$_pd = $sp_days[$i]['perpersoncost'];
				}
			}

			$_total_deposit = $_td;
			$_perperson_deposit = $_pd;
		}
		
		if( $_perperson_deposit == 1 ) {
			$_total_deposit *= $args['people'];
		}
		
		if( !empty($coupon) ) {
			if( $coupon['percentot'] == 2 ) {
				$_total_deposit = max(array($_total_deposit - $coupon_value, 0));
			} else if( cleverdine::getApplyCouponType() == 2 ) {
				$_total_deposit -= $_total_deposit*$coupon['value']/100.0;
			}
		}

		// VALIDATE PAYMENT

		$payment_id = $input->getInt('vrpaymentradio', -1);
		$no_payments_available = false;
		$payments_found_row = -1;
		
		$payments = array();
		$q = "SELECT * FROM `#__cleverdine_gpayments` WHERE `published`=1 AND `group`<>2;"; // group 2 : only take-away
		$dbo->setQuery($q);
		$dbo->execute();
	
		if ($dbo->getNumRows() > 0 && $_total_deposit > 0) {
			$payments = $dbo->loadAssocList();
			for( $i = 0, $n = count($payments); $i < $n && $payments_found_row == -1; $i++ ) {
				if( $payments[$i]['id'] == $payment_id ) {
					$payments_found_row = $i;
				}
			}
			
			if( $payments_found_row == -1 ) {
				$mainframe->enqueueMessage(JText::_('VRERRINVPAYMENT'), 'error');
				$mainframe->redirect(JRoute::_('index.php?option=com_cleverdine&task=confirmres&date='.$args["date"].'&hourmin='.$args["hourmin"].'&people='.$args["people"].'&table='.$args["table"],false));
				exit;
			}
		} else {
			$no_payments_available = true;
		}
		// END VALIDATION
		
		if ($_total_deposit > 0 && $payments_found_row != -1) {
			$_total_deposit += $payments[$payments_found_row]['charge'];
		} else {
			$payment_id = -1;
		}
		
		$tot_paid = 0;
		$status = "PENDING";
		if( $no_payments_available || $_total_deposit == 0 || $payments[$payments_found_row]['setconfirmed'] == 1 ) {
			//$status = "CONFIRMED";
			$status = cleverdine::getDefaultStatus();
		}
		
		//$deposit = $_total_deposit;
		
		// END VALIDATION
		
		$locked_until = time() + cleverdine::getTablesLockedTime()*60;
		
		$created_by = -1;
		$curr_user = JFactory::getUser();
		if( !$curr_user->guest ) {
			$created_by = $curr_user->id;
		}
		
		$sid = cleverdine::generateSerialCode(16);
		$conf_key = cleverdine::generateSerialCode(12);
		
		// INSERT RESERVATION
		$q = "INSERT INTO `#__cleverdine_reservation` ".
		"(`sid`,`conf_key`,`id_table`,`id_payment`,`coupon_str`,`checkin_ts`,`people`,`purchaser_nominative`,`purchaser_mail`,`purchaser_phone`,`purchaser_prefix`,`purchaser_country`,`langtag`,`custom_f`,`deposit`,`tot_paid`,`status`,`locked_until`,`created_on`,`created_by`,`id_user`) ".
		"VALUES( ".
		$dbo->quote($sid).",".
		$dbo->quote($conf_key).",".
		$args['table'].",".
		$payment_id.",". 
		$dbo->quote( $coupon_str ).",".
		cleverdine::createTimestamp($args['date'], $_hourmin_exp[0], $_hourmin_exp[1] ).",".
		$args['people'].",".
		$dbo->quote( $p_name ).",".
		$dbo->quote( $p_mail ).",".
		$dbo->quote( $p_phone ).",".
		$dbo->quote( $p_prefix ).",".
		$dbo->quote( $p_country_code ).",".
		$dbo->quote( JFactory::getLanguage()->getTag() ).",".
		$dbo->quote( json_encode($cust_req) ).",".
		$_total_deposit.",".
		$tot_paid.",".
		$dbo->quote($status).",".
		$locked_until.",".
		time().",".
		$created_by.",".
		$created_by.
		");";
		
		$dbo->setQuery($q);
		$dbo->execute();
		$lid = $dbo->insertid();
		
		if( $lid <= 0 ) {
			$mainframe->enqueueMessage(JText::_('VRINSERTRESERVATIONERROR'), 'error');
			$mainframe->redirect(JRoute::_('index.php?option=com_cleverdine&task=restaurants',false));
			exit;
		}
		
		if( $menu_choosable ) {
			foreach( $args['menus'] as $id => $quantity ) {
				$q = "INSERT INTO `#__cleverdine_res_menus_assoc` (`id_reservation`,`id_menu`,`quantity`) VALUES (".$lid.",".$id.",".$quantity.");";
				$dbo->setQuery($q);
				$dbo->execute();
			}
		}
		
		$session->set('vrmenus', '', 'vrcart');
		
		// SAVE USER DATA
		if( cleverdine::userIsLogged() ) {
			$id_customer = -1;

			// prepare customer plugin

			$customer_arr = array(
				'billing_name' 			=> $p_name,
				'billing_mail' 			=> $p_mail,
				'billing_phone' 		=> $p_phone,
				'billing_phone_prefix' 	=> $p_prefix,
				'country_code' 			=> $p_country_code,
				'jid' 					=> $curr_user->id
			);

			$options = array(
				'alias' 	=> 'com_cleverdine',
				'version' 	=> cleverdine_SOFTWARE_VERSION,
				'admin' 	=> $mainframe->isAdmin(),
				'call' 		=> __FUNCTION__
			);

			JPluginHelper::importPlugin('e4j');
			$dispatcher = JEventDispatcher::getInstance();
			
			//
			
			$curr_user = JFactory::getUser();

			$q = "SELECT `id` FROM `#__cleverdine_users` WHERE `jid`=".$curr_user->id." LIMIT 1;";

			$dbo->setQuery($q);
			$dbo->execute();

			if( $dbo->getNumRows() > 0 ) {

				$id_customer = $dbo->loadResult();
				
				$q = "UPDATE `#__cleverdine_users` SET 
				`fields`=".$dbo->quote(json_encode($cust_req)).",
				`billing_name`=".$dbo->quote($customer_arr['billing_name']).",
				`billing_mail`=".$dbo->quote($customer_arr['billing_mail']).",
				`billing_phone`=".$dbo->quote($customer_arr['billing_phone']).",
				`country_code`=".$dbo->quote($customer_arr['country_code'])." 
				WHERE `jid`=".intval($customer_arr['jid'])." LIMIT 1;";

				$dbo->setQuery($q);
				$dbo->execute();

				if( $dbo->getAffectedRows() ) {
					// trigger plugin -> customer update
					$dispatcher->trigger('onCustomerUpdate', array(&$customer_arr, &$options));
				}

			} else {

				$q = "INSERT INTO `#__cleverdine_users` (`jid`,`fields`,`billing_name`,`billing_mail`,`billing_phone`,`country_code`) VALUES (".
				intval($customer_arr['jid']).",".
				$dbo->quote(json_encode($cust_req)).",".
				$dbo->quote($customer_arr['billing_name']).",".
				$dbo->quote($customer_arr['billing_mail']).",".
				$dbo->quote($customer_arr['billing_phone']).",".
				$dbo->quote($customer_arr['country_code']).
				");";

				$dbo->setQuery($q);
				$dbo->execute();
				
				if( ($id_customer = $dbo->insertid()) ) {
					// trigger plugin -> customer creation
					$dispatcher->trigger('onCustomerInsert', array(&$customer_arr, &$options));
				}

			}

			if( $id_customer > 0 ) {
				$q = "UPDATE `#__cleverdine_reservation` SET `id_user`=$id_customer WHERE `id`=$lid LIMIT 1;";
				$dbo->setQuery($q);
				$dbo->execute();
			}
		}
		// END USER
		
		$send_when = cleverdine::getSendMailWhen();
		
		// SEND EMAILS
		$order_details = cleverdine::fetchOrderDetails($lid);
		if( $send_when['admin'] == 2 || $send_when['operator'] == 2 || $order_details['status'] == 'CONFIRMED' ) {
			cleverdine::sendAdminEmail($order_details);
		}
		if( $send_when['customer'] != 0 && ( $send_when['customer'] == 2 || $order_details['status'] == 'CONFIRMED' ) ) {
			cleverdine::sendCustomerEmail($order_details);
		}
		// END SEND EMAILS
		
		// SEND SMS NOTIFICATIONS
		// phone_number, order_details, action_restaurant (0)
		if( $status == 'CONFIRMED' ) {
			cleverdine::sendSmsAction( $p_prefix.$p_phone, $order_details, 0 );
		}
		// END SMS
		
		$mainframe->redirect( JRoute::_('index.php?option=com_cleverdine&view=order&ordnum='.$lid.'&ordkey='.$sid.'&ordtype=0',false) );
	}
	
	function notifypayment() {

		$input = JFactory::getApplication()->input;
			
		$oid = $input->getUint('ordnum');
		$sid = $input->getAlnum('ordkey');
		
		$dbo = JFactory::getDbo();
		
		$q = "SELECT * FROM `#__cleverdine_reservation` WHERE `id`=".intval($oid)." AND `sid`=".$dbo->quote($sid)." LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$order = $dbo->loadAssoc();
			
			if( $order['status'] == "PENDING" ) {
				
				$q = "SELECT * FROM `#__cleverdine_gpayments` WHERE `id`=".$order['id_payment']." LIMIT 1;";
				$dbo->setQuery($q);
				$dbo->execute();
				if( $dbo->getNumRows() > 0 ) {
					$payment = $dbo->loadAssoc();
				} 
					
				$return_url = JUri::root() . "index.php?option=com_cleverdine&view=order&ordnum=" . $order['id'] . "&ordkey=" . $order['sid'] . '&ordtype=0';
				$error_url  = JUri::root() . "index.php?option=com_cleverdine&view=order&ordnum=" . $order['id'] . "&ordkey=" . $order['sid'] . '&ordtype=0';
				$notify_url = JUri::root() . "index.php?option=com_cleverdine&task=notifypayment&ordnum=" . $order['id'] . "&ordkey=" . $order['sid'];
				$transaction_name = JText::sprintf('VRRESTRANSACTIONNAME', cleverdine::getRestaurantName());
		
				$array_order['oid'] = $oid;
				$array_order['sid'] = $sid;
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
				
				$admail = cleverdine::getAdminMail();
				
				require_once(JPATH_ADMINISTRATOR .DIRECTORY_SEPARATOR."components".DIRECTORY_SEPARATOR."com_cleverdine".DIRECTORY_SEPARATOR. "payments" .DIRECTORY_SEPARATOR. $payment['file']);
			
				$params = array();
				if( !empty($payment['params']) ) {
					$params = json_decode($payment['params'], true);
				}
			
				$obj = new cleverdinePayment($array_order, $params);
			
				$res_args = $obj->validatePayment();
				
				if( $res_args['verified'] == 1 ) {

					if( empty($res_args['tot_paid']) ) {
						$res_args['tot_paid'] = 0.0;
					}
					
					$q = "UPDATE `#__cleverdine_reservation` SET `tot_paid`=(`tot_paid`+".$res_args['tot_paid']."), `status`='CONFIRMED' WHERE id=".$oid." LIMIT 1;";
					$dbo->setQuery($q);
					$dbo->execute();
					
					// SEND EMAILS
					$order_details = cleverdine::fetchOrderDetails($order['id']);
					cleverdine::sendAdminEmail($order_details);
					cleverdine::sendCustomerEmail($order_details);
					// END SEND EMAILS
					
					// SEND SMS NOTIFICATIONS
					// phone_number, order_details, action_restaurant (0)
					cleverdine::sendSmsAction( $order_details['purchaser_prefix'].$order_details['purchaser_phone'], $order_details, 0 );
					// END SMS
					
				} else {

					if( strlen($res_args['log']) ) {
						// send email to admin with $res_args['log']
						@mail($admail, 'Invalid Payment Received', "Invalid Payment:\n\n".$res_args['log']);
					}

				}

				if( method_exists($obj, 'afterValidation') ) {
					$obj->afterValidation($res_args['verified'] ? 1 : 0);
				}
			
			}
			
		} else {
			// ORDER NOT EXISTS
		}
	}
	
	function cancel_order() {
		
		$mainframe = JFactory::getApplication();
		$input = $mainframe->input;
		$dbo = JFactory::getDbo();
		
		$oid 	= $input->getUint('oid');
		$sid 	= $input->getAlnum('sid');
		$type 	= $input->getUint('type');

		$reason = $input->getString('reason');

		$canc_reason = ( $type == 0 ? cleverdine::getCancellationReason() : cleverdine::getTakeAwayCancellationReason() );

		if( (strlen($reason) > 0 && strlen($reason) < 32 ) || ( strlen($reason) == 0 && $canc_reason == 2 ) ) {
			$mainframe->redirect("index.php?option=com_cleverdine&view=order&ordnum=$oid&ordkey=$sid&ordtype=$type#cancel");
			exit;
		}
		
		if( $type == 0 ) {
			if( !cleverdine::isCancellationEnabled() ) {
				$mainframe->enqueueMessage(JText::_('VRORDERCANCDISABLEDERROR'), 'error');
				$mainframe->redirect(JRoute::_('index.php?option=com_cleverdine&view=order&ordnum='.$oid.'&ordkey='.$sid.'&ordtype='.$type, false));
				exit;
			}
			
			$q = "SELECT `checkin_ts` FROM `#__cleverdine_reservation` WHERE `id`=".$oid." AND `sid`=".$dbo->quote($sid)." AND `status`='CONFIRMED' LIMIT 1;";
			$dbo->setQuery($q);
			$dbo->execute();
			if( $dbo->getNumRows() == 0 ) {
				$mainframe->enqueueMessage(JText::_('VRORDERCANCDISABLEDERROR'), 'error');
				$mainframe->redirect(JRoute::_('index.php?option=com_cleverdine&view=order&ordnum='.$oid.'&ordkey='.$sid.'&ordtype='.$type, false));
				exit;
			}
		
			$row = $dbo->loadAssoc();
			
			if( !cleverdine::canUserCancelOrder($row['checkin_ts'], 0) ) {
				$mainframe->enqueueMessage(JText::sprintf('VRORDERCANCEXPIREDERROR', cleverdine::getCancelBeforeTime()), 'error');
				$mainframe->redirect(JRoute::_('index.php?option=com_cleverdine&view=order&ordnum='.$oid.'&ordkey='.$sid.'&ordtype='.$type, false));
				exit;
			}
			
			$q = "UPDATE `#__cleverdine_reservation` set `status`='CANCELLED' WHERE `id`=".$oid." LIMIT 1;";
			$dbo->setQuery($q);
			$dbo->execute();
		} else {
			if( !cleverdine::isTakeAwayCancellationEnabled() ) {
				$mainframe->enqueueMessage(JText::_('VRORDERCANCDISABLEDERROR'), 'error');
				$mainframe->redirect(JRoute::_('index.php?option=com_cleverdine&view=order&ordnum='.$oid.'&ordkey='.$sid.'&ordtype='.$type, false));
				exit;
			}
			
			$q = "SELECT `checkin_ts` FROM `#__cleverdine_takeaway_reservation` WHERE `id`=".$oid." AND `sid`=".$dbo->quote($sid)." AND `status`='CONFIRMED' LIMIT 1;";
			$dbo->setQuery($q);
			$dbo->execute();
			if( $dbo->getNumRows() == 0 ) {
				$mainframe->enqueueMessage(JText::_('VRORDERCANCDISABLEDERROR'), 'error');
				$mainframe->redirect(JRoute::_('index.php?option=com_cleverdine&view=order&ordnum='.$oid.'&ordkey='.$sid.'&ordtype='.$type, false));
				exit;
			}
		
			$row = $dbo->loadAssoc();
			
			if( !cleverdine::canUserCancelOrder($row['checkin_ts'], 1) ) {
				$mainframe->enqueueMessage(JText::sprintf('VRORDERCANCEXPIREDERROR', cleverdine::getTakeAwayCancelBeforeTime()), 'error');
				$mainframe->redirect(JRoute::_('index.php?option=com_cleverdine&view=order&ordnum='.$oid.'&ordkey='.$sid.'&ordtype='.$type, false));
				exit;
			}
			
			$q = "UPDATE `#__cleverdine_takeaway_reservation` set `status`='CANCELLED' WHERE `id`=".$oid." LIMIT 1;";
			$dbo->setQuery($q);
			$dbo->execute();
		}
		
		if( $type == 0 ) {
			$order_details = cleverdine::fetchOrderDetails($oid);
			$order_details['cancellation_reason'] = $reason;

			cleverdine::sendCustomerEmail($order_details);
			
			cleverdine::sendCancellationEmail($order_details);
			
		} else {
			$order_details = cleverdine::fetchTakeAwayOrderDetails($oid);
			cleverdine::sendCustomerEmailTakeAway($order_details);

			$order_details_original = cleverdine::fetchTakeAwayOrderDetails($oid, cleverdine::getDefaultLanguage('site'));
			$order_details_original['cancellation_reason'] = $reason;
			cleverdine::sendCancellationEmailTakeAway($order_details_original);
		}
		
		$mainframe->redirect(JRoute::_('index.php?option=com_cleverdine&view=order&ordnum='.$oid.'&ordkey='.$sid.'&ordtype='.$type, false));
		
	}
	
	function takeawayconfirm() {
		$mainframe = JFactory::getApplication();
		$input = $mainframe->input;

		$input->set('view','takeawayconfirm');

		$custom_item_id = cleverdine::getTakeAwayConfirmItemID(true);

		if( !empty($custom_item_id) ) {
			$input->set('Itemid', $custom_item_id);
		}

		parent::display();
	}
	
	function savetakeawayorder() {
		$mainframe = JFactory::getApplication();
		$input = $mainframe->input;
		$dbo = JFactory::getDbo();
		$session = JFactory::getSession();

		$args = array();
		
		// get cart instance
		cleverdine::loadCartLibrary();
		
		$cart = TakeAwayCart::getInstance();

		if( !cleverdine::isTakeAwayReservationsAllowedOn( $cart->getCheckinTimestamp() ) ) {
			// if orders are stopped
			$mainframe->enqueueMessage(JText::_('VRTKMENUNOTAVAILABLE3'), 'error');
			$mainframe->redirect(JRoute::_('index.php?option=com_cleverdine&view=takeaway'));
			exit;
		}

		// CHECK STOCK AVAILABILITY
		if( !cleverdine::checkCartStockAvailability($cart) ) {
			$cart->store();

			$mainframe->redirect( JRoute::_('index.php?option=com_cleverdine&task=takeawayconfirm',false) );
			exit;
		}

		// CHECK DELIVERY AVAILABILITY
		$args['delivery'] = $input->getInt('delivery');

		$is_delivery = cleverdine::isTakeAwayDeliveryServiceEnabled();
		if( $args['delivery'] == 0 && $is_delivery == 1 ) {
			// pickup not allowed
			$mainframe->enqueueMessage(JText::_('VRTKSERVICENOTALLOWEDERR'), 'error');
			$mainframe->redirect( JRoute::_('index.php?option=com_cleverdine&task=takeawayconfirm',false) );
			exit;
		} else if( $args['delivery'] == 1 && $is_delivery == 0 ) {
			// delivery not allowed
			$mainframe->enqueueMessage(JText::_('VRTKSERVICENOTALLOWEDERR'), 'error');
			$mainframe->redirect( JRoute::_('index.php?option=com_cleverdine&task=takeawayconfirm',false) );
			exit;
		}

		if ($args['delivery'] && cleverdine::hasDeliveryAreas()) {
			
			$delivery_info = $session->get('delivery_address', null, 'vre');

			if ($delivery_info === null || !$delivery_info->status) {
				$mainframe->enqueueMessage(JText::_('VRTKDELIVERYLOCNOTFOUND'), 'error');
				$mainframe->redirect( JRoute::_('index.php?option=com_cleverdine&task=takeawayconfirm',false) );
				exit;
			}

		} else {
			// fill an empty delivery
			$delivery_info = new stdClass;
			
			$delivery_info->area = new stdClass;
			$delivery_info->area->charge 	= 0;
			$delivery_info->area->minCost 	= 0;

			$delivery_info->address = array();
			$delivery_info->address['fullAddress'] 		= '';
			$delivery_info->address['country_2_code'] 	= '';
			$delivery_info->address['country'] 			= '';
			$delivery_info->address['state'] 			= '';
			$delivery_info->address['city'] 			= '';
			$delivery_info->address['zip'] 				= '';
			$delivery_info->address['route'] 			= '';
			$delivery_info->address['street_number'] 	= '';
		}
		
		// VALIDATE CART MIN COST
		$mincost = max(array(cleverdine::getTakeAwayMinimumCostPerOrder(), $delivery_info->area->minCost));
		
		if( $cart->getTotalCost() < $mincost ) {
			$mainframe->enqueueMessage(JText::sprintf('VRTAKEAWAYMINIMUMCOST', cleverdine::printPriceCurrencySymb($mincost)), 'error');
			$mainframe->redirect( JRoute::_('index.php?option=com_cleverdine&view=takeaway',false) );
			exit;
		}

		// VALIDATE CUSTOM FIELDS

		$p_name 		= "";
		$p_mail 		= "";
		$p_phone 		= "";
		$p_prefix 		= "";
		$p_country_code = "";
		$p_address 		= "";
		
		$_cf = array();
		$q = "SELECT * FROM `#__cleverdine_custfields` 
		WHERE `group`=1 AND `type`<>'separator' AND (`type`<>'checkbox' OR `required`=0)  
		ORDER BY `ordering` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() ) {
			$_cf = $dbo->loadAssocList();
		}
		
		$cust_req = array();
		
		foreach( $_cf as $_app ) {
			$cust_req[$_app['name']] = $input->get('vrcf'.$_app['id'], '', 'string');
			if( !cleverdine::isCustomFieldValid($_app, $cust_req[$_app['name']], $args['delivery']) ) {

				$mainframe->enqueueMessage(JText::_('VRERRINSUFFCUSTF'), 'error');
				$mainframe->redirect( JRoute::_('index.php?option=com_cleverdine&task=takeawayconfirm',false) );
				exit;

			} else if( $_app['rule'] == VRCustomFields::NOMINATIVE ) {

				if( !empty($p_name) ) {
					$p_name .= ' ';
				}
				$p_name .= $cust_req[$_app['name']];

			} else if( $_app['rule'] == VRCustomFields::EMAIL ) {

				$p_mail = $cust_req[$_app['name']];

			} else if( $_app['rule'] == VRCustomFields::PHONE_NUMBER ) {

				$p_phone = $cust_req[$_app['name']];
				$country_key = $input->get('vrcf'.$_app['id'].'_prfx', '', 'string');
				if( !empty($country_key) ) {
					$country_key = explode('_', $country_key);
					$q = "SELECT * FROM `#__cleverdine_countries` WHERE `country_2_code`=".$dbo->quote($country_key[1])." LIMIT 1;";
					$dbo->setQuery($q);
					$dbo->execute();
					if( $dbo->getNumRows() > 0 ) {
						$country = $dbo->loadAssoc();
						$p_prefix = $country['phone_prefix'];
						$p_country_code = $country['country_2_code'];
					}
				}
				$p_phone = str_replace(" ", "", $cust_req[$_app['name']]);

			} else if( $_app['rule'] == VRCustomFields::ADDRESS ) { 

				$p_address = $delivery_info->address['fullAddress'];

			}
		}

		if( empty($p_country_code) ) {
			$p_country_code = $delivery_info->address['country_2_code'];
		}
		
		// VALIDATE ARGS
		$args['date'] = date(cleverdine::getDateFormat(), $cart->getCheckinTimestamp());
		$args['hourmin'] = $input->getString('hourmin');
		
		$_hourmin_exp = explode( ':', $args['hourmin'] );

		$resp = cleverdine::isRequestTakeAwayOrderValid($args);
			
		if( $resp != 0 ) {
			$mainframe->enqueueMessage(JText::_( cleverdine::getResponseFromTakeAwayOrderRequest($resp) ), 'error');
			$mainframe->redirect(JRoute::_('index.php?option=com_cleverdine&task=takeawayconfirm',false));
			exit;
		}
		
		$args['hour'] = $_hourmin_exp[0];
		$args['min'] = $_hourmin_exp[1];
		
		if( cleverdine::isClosingDay($args) ) {
			$special_days = cleverdine::getSpecialDaysOnDate($args, 2);

			$ignore_cd = false;

			if( $special_days != -1 && count($special_days) > 0 ) {
				for( $i = 0; $i < count($special_days) && !$ignore_cd; $i++ ) {
					$ignore_cd = $special_days[$i]['ignoreclosingdays'];
				}
			}

			if( !$ignore_cd ) {
				$mainframe->enqueueMessage(JText::_('VRSEARCHDAYCLOSED'), 'error');
				$mainframe->redirect(JRoute::_('index.php?option=com_cleverdine&task=takeawayconfirm',false));
				exit;
			}
		}
		// END VALIDATION
		
		// VALIDATE RESERVATION
		$ok_datetime = false;
		
		$cart_q = $cart->getPreparationItemsQuantity();

		$max_items_per_interval = cleverdine::getTakeAwayMealsPerInterval();
		
		$_ts = cleverdine::createTimestamp( $args['date'], $args['hour'], $args['min'] );
		$q = "SELECT `r`.`checkin_ts`, SUM(`a`.`quantity`) as `sum_quantity` 
		FROM `#__cleverdine_takeaway_reservation` AS `r`, `#__cleverdine_takeaway_res_prod_assoc` AS `a`, `#__cleverdine_takeaway_menus_entry` AS `e` 
		WHERE `r`.`id`=`a`.`id_res` AND `a`.`id_product`=`e`.`id` AND `e`.`ready`=0 AND ".$_ts." <= `r`.`checkin_ts` AND `r`.`checkin_ts` < ".$_ts."+".(cleverdine::getTakeAwayMinuteInterval()*60)."
		GROUP BY `r`.`checkin_ts` ORDER BY `r`.`checkin_ts`;";	
		
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$row = $dbo->loadAssocList();
			if( $row[0]['sum_quantity']+$cart_q <= $max_items_per_interval ) {
				$ok_datetime = true;
			}
		} else {
			$ok_datetime = true;
		}
		
		if( !$ok_datetime ) {
			$mainframe->enqueueMessage(JText::_('VRTKNOTIMEAVERR'), 'error');
			$mainframe->redirect(JRoute::_('index.php?option=com_cleverdine&task=takeawayconfirm',false));
			exit;
		}
		// END VALIDATION
		
		$payment_id = $input->getInt('vrpaymentradio', -1);
		$no_payments_available = false;
		$payments_found_row = -1;
		
		// VALIDATE PAYMENT
		$payments = array();
		$q = "SELECT * FROM `#__cleverdine_gpayments` WHERE `published`=1 AND `group`<>1;"; // group 2 : only restaurant
		$dbo->setQuery($q);
		$dbo->execute();
	
		if( $dbo->getNumRows() > 0 ) {
			$payments = $dbo->loadAssocList();
			for( $i = 0, $n = count($payments); $i < $n && $payments_found_row == -1; $i++ ) {
				if( $payments[$i]['id'] == $payment_id ) {

					$cost = $cart->getRealTotalCost();

					if( $payments[$i]['enablecost'] == 0 || ( $payments[$i]['enablecost'] > 0 && $payments[$i]['enablecost'] <= $cost ) || ( $payments[$i]['enablecost'] < 0 && abs($payments[$i]['enablecost']) >= $cost ) ) {
						$payments_found_row = $i;
					}
				}
			}
			
			if( $payments_found_row == -1 ) {
				$mainframe->enqueueMessage(JText::_('VRERRINVPAYMENT'), 'error');
				$mainframe->redirect(JRoute::_('index.php?option=com_cleverdine&task=takeawayconfirm',false));
			}
		} else {
			$no_payments_available = true;
			$payment_id = -1;
		}
		// END VALIDATION
		
		// GET COUPON VALUES
		$session = JFactory::getSession();
		$coupon = $session->get('vr_coupon_data', '');
		$coupon_str = "";
		if( !empty($coupon) ) {
			
			$coupon_str = $coupon['code'] . ";;" . $coupon['value'] . ";;" . $coupon['percentot'];
			
			$cart->deals()->insert(
				new TakeAwayDiscount($coupon['code'], $coupon['value'], $coupon['percentot'], 1)
			);
			
			if( $coupon['type'] == 2 ) { // GIFT
				$q = "DELETE FROM `#__cleverdine_coupons` WHERE `id`=".$coupon['id']." LIMIT 1;";
				$dbo->setQuery($q);
				$dbo->execute();
			}
			
			$session->set('vr_coupon_data', '');
		}
		// END COUPON
		
		// ADD TAXES TO TOTALCOST
		$use_taxes = cleverdine::isTakeAwayTaxesUsable();

		$total_to_pay 	= $cart->getTotalCost();
		$grand_total 	= $cart->getRealTotalCost($use_taxes);
		$taxes 			= $cart->getRealTotalTaxes($use_taxes);
		$discount_val 	= $cart->getTotalDiscount();
		// END TAXES
		
		// ignore the coupon, get original total cost
		$deliverycost = 0;
		if ($args['delivery'] == 1) {

			if ($total_to_pay < cleverdine::getTakeAwayFreeDeliveryService()) {
				$deliverycost = cleverdine::getTakeAwayDeliveryServiceAddPrice();
				if (cleverdine::getTakeAwayDeliveryServicePercentOrTotal() == 1) {
					// apply percentage delivery charge to the total net
					$deliverycost = $total_to_pay * $deliverycost / 100.0;
				}
				$deliverycost += $delivery_info->area->charge;
				$grand_total += $deliverycost;
			}

		} else {
			$deliverycost = cleverdine::getTakeAwayPickupAddPrice();
			if (cleverdine::getTakeAwayPickupPercentOrTotal() == 1) {
				// apply percentage delivery charge to the total net
				$deliverycost = $total_to_pay * $deliverycost / 100.0;
			}
			$grand_total += $deliverycost;
		}
		
		// VALIDATE RESERVATION STATUS

		$pay_charge = 0;
		
		$status = "PENDING";
		if( $no_payments_available || ( $grand_total == 0 )  || $payments[$payments_found_row]['setconfirmed'] == 1 ) {
			//$status = "CONFIRMED";
			$status = cleverdine::getTakeAwayDefaultStatus();
		} else {
			if( $payments[$payments_found_row]['percentot'] == 1 ) {
				// apply percentage payment charge to the total net
				$pay_charge = $total_to_pay*$payments[$payments_found_row]['charge']/100;
			} else {
				$pay_charge = $payments[$payments_found_row]['charge'];
			}

			$grand_total += $pay_charge;
		}
		// END VALIDATION
		
		$locked_until = time() + cleverdine::getTakeawayOrdersLockedTime()*60;
		
		$sid = cleverdine::generateSerialCode(16);
		$conf_key = cleverdine::generateSerialCode(12);
		
		$created_by = -1;
		$curr_user = JFactory::getUser();
		if( !$curr_user->guest ) {
			$created_by = $curr_user->id;
		}
		
		// INSERT RESERVATION
		$q = "INSERT INTO `#__cleverdine_takeaway_reservation` ".
		"(`sid`,`conf_key`,`id_payment`,`delivery_service`,`coupon_str`,`checkin_ts`,
		`purchaser_nominative`,`purchaser_mail`,`purchaser_phone`,`purchaser_prefix`,`purchaser_country`,`purchaser_address`,
		`langtag`,`custom_f`,`total_to_pay`,`taxes`,`pay_charge`,`delivery_charge`,`discount_val`,`status`,`locked_until`,`created_on`,`created_by`) ".
		"VALUES( ".
		$dbo->quote($sid).",".
		$dbo->quote($conf_key).",".
		$payment_id.",".
		$args['delivery'].",". 
		$dbo->quote( $coupon_str ).",".
		cleverdine::createTimestamp($args['date'], $_hourmin_exp[0], $_hourmin_exp[1] ).",".
		$dbo->quote( $p_name ).",".
		$dbo->quote( $p_mail ).",".
		$dbo->quote( $p_phone ).",".
		$dbo->quote( $p_prefix ).",".
		$dbo->quote( $p_country_code ).",".
		$dbo->quote( $p_address ).",".
		$dbo->quote( JFactory::getLanguage()->getTag() ).",".
		$dbo->quote( json_encode($cust_req) ).",".
		$grand_total.",".
		$taxes.",".
		$pay_charge.",".
		$deliverycost.",".
		$discount_val.",".
		$dbo->quote($status).",".
		$locked_until.",".
		time().",".
		$created_by.
		");";
		
		$dbo->setQuery($q);
		$dbo->execute();
		$lid = $dbo->insertid();
		
		if( $lid <= 0 ) {
			$mainframe->enqueueMessage(JText::_('VRINSERTRESERVATIONERROR'), 'error');
			$mainframe->redirect(JRoute::_('index.php?option=com_cleverdine&task=takeawayconfirm',false));
			exit;
		}
		
		foreach( $cart->getItemsList() as $item ) {
			$var_id = $item->getVariationID();
			
			$q = "INSERT INTO `#__cleverdine_takeaway_res_prod_assoc`(`id_product`,`id_res`,`id_product_option`,`quantity`,`price`,`taxes`,`notes`) VALUES(
			".$item->getItemID().",
			".$lid.",
			".(!empty($var_id) ? $var_id : -1).",
			".$item->getQuantity().",
			".$item->getTotalCost().",
			".$item->getTaxes($use_taxes).",
			".$dbo->quote($item->getAdditionalNotes()).
			");";
			
			$dbo->setQuery($q);
			$dbo->execute();
			$assoc_lid = $dbo->insertid();
			if( $assoc_lid <= 0 ) {
				$mainframe->enqueueMessage('Impossible to add item!', 'error');
			}
			
			foreach( $item->getToppingsGroupsList() as $group ) {
				foreach( $group->getToppingsList() as $topping ) {
					$q = "INSERT INTO `#__cleverdine_takeaway_res_prod_topping_assoc`(`id_assoc`,`id_group`,`id_topping`) VALUES(
					".$assoc_lid.",
					".$group->getGroupID().",
					".$topping->getToppingID()."
					);";
					
					$dbo->setQuery($q);
					$dbo->execute();
					if( $dbo->insertid() <= 0 ) {
						$mainframe->enqueueMessage('Impossible to add topping!', 'error');
					}
				}
			}
		}
		
		$cart->emptyCart()->store();

		$session->clear('delivery_address', 'vre');
		
		// SAVE USER DATA
		if( cleverdine::userIsLogged() ) {
			$id_customer = -1;

			// prepare customer plugin

			$customer_arr = array(
				'billing_name' 			=> $p_name,
				'billing_mail' 			=> $p_mail,
				'billing_phone' 		=> $p_phone,
				'billing_phone_prefix' 	=> $p_prefix,
				'country_code' 			=> $p_country_code,
				'billing_state' 		=> $delivery_info->address['state'],
				'billing_city' 			=> $delivery_info->address['city'],
				'billing_address' 		=> $delivery_info->address['route'].' '.$delivery_info->address['street_number'],
				'billing_zip' 			=> $delivery_info->address['zip'],
				'jid' 					=> $curr_user->id
			);

			$options = array(
				'alias' 	=> 'com_cleverdine',
				'version' 	=> cleverdine_SOFTWARE_VERSION,
				'admin' 	=> $mainframe->isAdmin(),
				'call' 		=> __FUNCTION__
			);

			JPluginHelper::importPlugin('e4j');
			$dispatcher = JEventDispatcher::getInstance();
			
			//
			
			$curr_user = JFactory::getUser();

			$q = "SELECT * FROM `#__cleverdine_users` WHERE `jid`=".intval($curr_user->id)." LIMIT 1;";

			$dbo->setQuery($q);
			$dbo->execute();

			if( $dbo->getNumRows() > 0 ) {

				$customer = $dbo->loadAssoc();

				$id_customer = $customer['id'];
				
				$q = "UPDATE `#__cleverdine_users` SET 
				".(empty($customer['billing_name']) ? "`billing_name`=".$dbo->quote($customer_arr['billing_name'])."," : '')."
				".(empty($customer['billing_mail']) ? "`billing_mail`=".$dbo->quote($customer_arr['billing_mail'])."," : '')."
				".(empty($customer['billing_phone']) ? "`billing_phone`=".$dbo->quote($customer_arr['billing_phone'])."," : '')."
				".(empty($customer['country_code']) ? "`country_code`=".$dbo->quote($customer_arr['country_code'])."," : '')."
				".(empty($customer['billing_state']) ? "`billing_state`=".$dbo->quote($customer_arr['billing_state'])."," : '')."
				".(empty($customer['billing_city']) ? "`billing_city`=".$dbo->quote($customer_arr['billing_city'])."," : '')."
				".(empty($customer['billing_address']) ? "`billing_address`=".$dbo->quote($customer_arr['billing_address'])."," : '')."
				".(empty($customer['billing_zip']) ? "`billing_zip`=".$dbo->quote($customer_arr['billing_zip'])."," : '')."
				`tkfields`=".$dbo->quote(json_encode($cust_req))." 
				WHERE `jid`=".intval($customer_arr['jid'])." LIMIT 1;";

				$dbo->setQuery($q);
				$dbo->execute();

				if( $dbo->getAffectedRows() ) {
					// trigger plugin -> customer update
					$dispatcher->trigger('onCustomerUpdate', array(&$customer_arr, &$options));
				}

			} else {

				$q = "INSERT INTO `#__cleverdine_users` (`jid`,`tkfields`,`billing_name`,`billing_mail`,`billing_phone`,`country_code`,`billing_state`,`billing_city`,`billing_address`,`billing_zip`) VALUES (".
				intval($customer_arr['jid']).",".
				$dbo->quote(json_encode($cust_req)).",".
				$dbo->quote($customer_arr['billing_name']).",".
				$dbo->quote($customer_arr['billing_mail']).",".
				$dbo->quote($customer_arr['billing_phone']).",".
				$dbo->quote($customer_arr['country_code']).",".
				$dbo->quote($customer_arr['billing_state']).",".
				$dbo->quote($customer_arr['billing_city']).",".
				$dbo->quote($customer_arr['billing_address']).",".
				$dbo->quote($customer_arr['billing_zip']).
				");";

				$dbo->setQuery($q);
				$dbo->execute();
				
				if( ($id_customer = $dbo->insertid()) ) {
					// trigger plugin -> customer creation
					$dispatcher->trigger('onCustomerInsert', array(&$customer_arr, &$options));
				}

			}
			
			if( $id_customer > 0 ) {
				$q = "UPDATE `#__cleverdine_takeaway_reservation` SET `id_user`=$id_customer WHERE `id`=$lid LIMIT 1;";
				$dbo->setQuery($q);
				$dbo->execute();

				if( strlen($delivery_info->address['fullAddress']) ) {
					$user_data = cleverdine::getCustomer($id_customer);
					$match = 0;

					foreach( $user_data['delivery'] as $addr ) {

						$addr_str = cleverdine::deliveryAddressToStr($addr);

						$percent = 0;
						similar_text($addr_str, $delivery_info->address['fullAddress'], $percent);

						$match = max(array($match, $percent));
					}

					if( $match < 75 ) {

						$q = "INSERT INTO `#__cleverdine_user_delivery` (`id_user`,`country`,`state`,`city`,`address`,`zip`,`ordering`) VALUES(".
						$id_customer.",".
						$dbo->quote($p_country_code).",".
						$dbo->quote($delivery_info->address['state']).",".
						$dbo->quote($delivery_info->address['city']).",".
						$dbo->quote($delivery_info->address['route']." ".$delivery_info->address['street_number']).",".
						$dbo->quote($delivery_info->address['zip']).",".
						(count($user_data['delivery'])+1).
						");";

						$dbo->setQuery($q);
						$dbo->execute();
					} 

				}

			}
		}
		// END USER
		
		$send_when = cleverdine::getTakeawaySendMailWhen();
		
		// SEND EMAILS
		$order_details = cleverdine::fetchTakeAwayOrderDetails($lid);
		if( $send_when['admin'] == 2 || $send_when['operator'] == 2 || $order_details['status'] == 'CONFIRMED' ) {
			$order_details_original = cleverdine::fetchTakeAwayOrderDetails($lid, cleverdine::getDefaultLanguage('site'));
			cleverdine::sendAdminEmailTakeAway($order_details_original);
		}
		if( $send_when['customer'] != 0 && ( $send_when['customer'] == 2 || $order_details['status'] == 'CONFIRMED' ) ) {
			cleverdine::sendCustomerEmailTakeAway($order_details);
		}
		// END SEND EMAILS
		
		// SEND SMS NOTIFICATIONS
		// phone_number, order_details, action_takeaway (1)
		if( $status == 'CONFIRMED' ) {
			cleverdine::sendSmsAction( $p_prefix.$p_phone, $order_details, 1 );
		}
		// END SMS

		// NOTIFY ADMIN FOR LOW STOCKS
		if( $status == 'CONFIRMED' ) {
			cleverdine::notifyAdminLowStocks();
		}
		// END NOTIFY
		
		$mainframe->redirect( JRoute::_('index.php?option=com_cleverdine&view=order&ordnum='.$lid.'&ordkey='.$sid.'&ordtype=1',false) );
		 
	}

	function notifytkpayment() {

		$input = JFactory::getApplication()->input;
		
		$oid = $input->getUint('ordnum');
		$sid = $input->getAlnum('ordkey');
		
		$dbo = JFactory::getDbo();
		
		$q = "SELECT * FROM `#__cleverdine_takeaway_reservation` WHERE `id`=".intval($oid)." AND `sid`=".$dbo->quote($sid)." LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$order = $dbo->loadAssoc();
			
			if( $order['status'] == "PENDING" ) {
				
				$q = "SELECT * FROM `#__cleverdine_gpayments` WHERE `id`=".$order['id_payment']." LIMIT 1;";
				$dbo->setQuery($q);
				$dbo->execute();
				if( $dbo->getNumRows() > 0 ) {
					$payment = $dbo->loadAssoc();
				} 
					
				$return_url = JUri::root() . "index.php?option=com_cleverdine&view=order&ordnum=" . $order['id'] . "&ordkey=" . $order['sid'] . '&ordtype=1';
				$error_url  = JUri::root() . "index.php?option=com_cleverdine&view=order&ordnum=" . $order['id'] . "&ordkey=" . $order['sid'] . '&ordtype=1';
				$notify_url = JUri::root() . "index.php?option=com_cleverdine&task=notifytkpayment&ordnum=" . $order['id'] . "&ordkey=" . $order['sid'];
				$transaction_name = JText::sprintf('VRTRANSACTIONNAME', cleverdine::getRestaurantName());
		
				$array_order['oid'] = $oid;
				$array_order['sid'] = $sid;
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
				
				$admail = cleverdine::getAdminMail();
				
				require_once(JPATH_ADMINISTRATOR .DIRECTORY_SEPARATOR."components".DIRECTORY_SEPARATOR."com_cleverdine".DIRECTORY_SEPARATOR. "payments" .DIRECTORY_SEPARATOR. $payment['file']);
			
				$params = array();
				if( !empty($payment['params']) ) {
					$params = json_decode($payment['params'], true);
				}
			
				$obj = new cleverdinePayment($array_order, $params);
			
				$res_args = $obj->validatePayment();
				
				if( $res_args['verified'] == 1 ) {

					if( empty($res_args['tot_paid']) ) {
						$res_args['tot_paid'] = 0.0;
					}

					$q = "UPDATE `#__cleverdine_takeaway_reservation` SET `tot_paid`=(`tot_paid`+".$res_args['tot_paid']."), `status`='CONFIRMED' WHERE id=".$oid.";";
					$dbo->setQuery($q);
					$dbo->execute();
					
					// SEND EMAILS
					$order_details = cleverdine::fetchTakeAwayOrderDetails($order['id']);
					cleverdine::sendAdminEmailTakeAway($order_details);
					cleverdine::sendCustomerEmailTakeAway($order_details);
					// END SEND EMAILS
					
					// SEND SMS NOTIFICATIONS
					// phone_number, order_details, action_takeaway (1)
					cleverdine::sendSmsAction( $order_details['purchaser_prefix'].$order_details['purchaser_phone'], $order_details, 1 );
					// END SMS

					// NOTIFY ADMIN FOR LOW STOCKS
					cleverdine::notifyAdminLowStocks();
					// END NOTIFY
					
				} else {

					if( strlen($res_args['log']) ) {
						// send email to admin with $res_args['log']
						@mail($admail, 'Invalid Payment Received', "Invalid Payment:\n\n".$res_args['log']);
					}

				}

				if( method_exists($obj, 'afterValidation') ) {
					$obj->afterValidation($res_args['verified'] ? 1 : 0);
				}
			
			}
			
		} else {
			// ORDER NOT EXISTS
		}
	}

	function trackorder() {
		$mainframe = JFactory::getApplication();
		$input = $mainframe->input;

		$input->set('view','trackorder');
		parent::display();
	}

	function opeditbill() {
		$mainframe = JFactory::getApplication();
		$input = $mainframe->input;

		$input->set('view','opeditbill');
		parent::display();
	}

	function optkprintorders() {
		$mainframe = JFactory::getApplication();
		$input = $mainframe->input;

		$input->set('view','optkprintorders');
		parent::display();
	}

	function registeruser() {
		$this->create_new_user(cleverdine::isRegistrationEnabled());
	}

	function tkregisteruser() {
		$this->create_new_user(cleverdine::isTakeAwayRegistrationEnabled());
	}
	
	private function create_new_user($enabled) {
			
		$mainframe = JFactory::getApplication();
		$input = $mainframe->input;
		
		$return_url = base64_decode($input->getBase64('return'));

		if (empty($return_url)) {
			$return_url = 'index.php';
		}
		
		if (!$enabled) {
			$mainframe->enqueueMessage(JText::_('VRREGISTRATIONFAILED1'), 'error');
			$mainframe->redirect($return_url);
			exit;
		}

		if (!JSession::checkToken()) {
			$mainframe->enqueueMessage('Invalid Token!', 'error');
			$mainframe->redirect($return_url);
			exit;
		}
		
		$args = array();
		$args['firstname'] 		= $input->getString('fname');
		$args['lastname'] 		= $input->getString('lname');
		$args['email'] 			= $input->getString('email');
		$args['username'] 		= $input->getString('username');
		$args['password'] 		= $input->getString('password');
		$args['confpassword'] 	= $input->getString('confpassword');
		
		if (!cleverdine::checkUserArguments($args)) {
			$mainframe->enqueueMessage(JText::_('VRREGISTRATIONFAILED2'), 'error');
			$mainframe->redirect($return_url);
			exit;
		}
		
		$userid = cleverdine::createNewJoomlaUser( $args );
		if (!$userid || $userid == 'useractivate' || $userid == 'adminactivate') {
			//$mainframe->enqueueMessage(JText::_('VRREGISTRATIONFAILED3'), 'error');
			// use native com_users messages
			$mainframe->redirect($return_url);
			exit;
		}
		
		// AUTO LOG IN
		$credentials = array('username' => $args['username'], 'password' => $args['password'] );
		
		$mainframe->login($credentials);
		$currentUser = JFactory::getUser();
		$currentUser->setLastVisit(time());
		$currentUser->set('guest', 0);
		// END LOG IN
		
		$mainframe->redirect($return_url);
	} 
	
	// AJAX
	
	function add_to_cart() {

		$input = JFactory::getApplication()->input;
		
		$id_entry 			= $input->getUint('id_entry');
		$id_option 			= $input->getInt('id_option', 0);
		$item_cart_index 	= $input->getInt('item_index', -1);
		
		$quantity 	= $input->getUint('quantity');
		$notes 		= $input->getString('notes');
		$toppings 	= $input->get('topping', array(), 'array');
		
		if( $quantity <= 0 ) {
			$quantity = 1;
		}
		
		$dbo = JFactory::getDbo();
		
		$entry = array();
		
		$q = "SELECT `e`.*, `o`.id AS `oid`, `o`.`name` AS `oname`, `o`.`inc_price` AS `oprice`, `e`.`id_takeaway_menu`, `m`.`taxes_type`, `m`.`taxes_amount` 
		FROM `#__cleverdine_takeaway_menus_entry` AS `e` 
		LEFT JOIN `#__cleverdine_takeaway_menus_entry_option` AS `o` ON `e`.`id`=`o`.`id_takeaway_menu_entry` 
		LEFT JOIN `#__cleverdine_takeaway_menus` AS `m` ON `e`.`id_takeaway_menu`=`m`.`id`
		WHERE `e`.`id`=$id_entry ".($id_option > 0 ? "AND `o`.`id`=$id_option " : "")."LIMIT 1;";
		
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$entry = $dbo->loadAssoc();
		} else {
			echo json_encode(array(0, JText::_('VRTKCARTROWNOTFOUND')));
			exit;
		}
		
		$entries_translations = cleverdine::getTranslatedTakeawayProducts(array($id_entry));
		$options_translations = cleverdine::getTranslatedTakeawayOptions(array($id_option));
		
		$entry['name'] = cleverdine::translate($entry['id'], $entry, $entries_translations, 'name', 'name');
		$entry['oname'] = cleverdine::translate($entry['oid'], $entry, $options_translations, 'oname', 'name');

		// taxes
		if( $entry['taxes_type'] == 0 ) {
			$entry['taxes_amount'] = cleverdine::getTakeAwayTaxesRatio();
		}
		
		$entry_groups = array();
		$q = "SELECT `g`.*, `t`.`id` AS `topping_group_assoc_id`, `t`.`id_topping`, `t`.`rate` AS `topping_rate`, `t`.`ordering` AS `topping_ordering`, `t2`.`name` AS `topping_name` 
		FROM `#__cleverdine_takeaway_entry_group_assoc` AS `g` 
		LEFT JOIN `#__cleverdine_takeaway_group_topping_assoc` AS `t` ON `g`.`id`=`t`.`id_group`
		LEFT JOIN `#__cleverdine_takeaway_topping` AS `t2` ON `t`.`id_topping`=`t2`.`id` 
		WHERE `g`.`id_entry`=$id_entry AND (`g`.`id_variation`=-1 OR `g`.`id_variation`=$id_option) 
		ORDER BY `g`.`ordering` ASC, `t`.`ordering` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$app = $dbo->loadAssocList();
			
			$last_group_id = -1;
			foreach( $app as $group ) {
				$group['toppings'] = array();
				if( $group['id'] != $last_group_id ) {
					array_push($entry_groups, $group);
					$last_group_id = $group['id'];
				}
				
				if( !empty($group['topping_group_assoc_id']) ) {
					array_push($entry_groups[count($entry_groups)-1]['toppings'], array(
						"assoc_id" => $group['topping_group_assoc_id'],
						"id" => $group['id_topping'],
						"name" => $group['topping_name'],
						"rate" => $group['topping_rate'],
						"ordering" => $group['topping_ordering']
					));
				}
			}
		}
		
		// get cart instance
		cleverdine::loadCartLibrary();
		
		$cart = TakeAwayCart::getInstance();

		$cart->setMaxSize(cleverdine::getTakeAwayMaxMeals());
		
		// create take-away cart item
		if( $item_cart_index < 0 ) {
			$item = new TakeAwayItem(
				$entry['id_takeaway_menu'], 
				$entry['id'], 
				$entry['oid'], 
				$entry['name'], 
				$entry['oname'], 
				$entry['price']+$entry['oprice'], 
				$quantity, 
				$entry['ready'],
				$entry['taxes_amount'],
				$notes
			);            
		} else {
			$item = $cart->getItemAt($item_cart_index);
			if( $item === null ) {
				echo json_encode(array(0, JText::_('VRTKCARTROWNOTFOUND')));
				exit;
			}
			
			$item->setQuantity($quantity);
			$item->setAdditionalNotes($notes);
		}
		
		$item->emptyGroups();
		
		// validate toppings against groups
		foreach( $entry_groups as $group ) {
			
			// create take-away cart item group
			$item_group = new TakeAwayItemGroup($group['id'], $group['title'], $group['multiple']);
			
			if( empty($toppings[$group['id']]) ) {
				$toppings[$group['id']] = array();
			}
			
			$to_remove = array();
			// validate selected toppings
			for( $i = 0; $i < count($toppings[$group['id']]); $i++ ) {
				$found = false;
				for( $j = 0; $j < count($group['toppings']) && !$found; $j++ ) {
					if( $toppings[$group['id']][$i] == $group['toppings'][$j]['assoc_id'] ) {
						$found = true;
					}
				}
				if( !$found ) {
					array_push($to_remove, $i);
				}
			}
			
			// check repeated toppings
			for( $i = 0; $i < count($toppings[$group['id']])-1; $i++ ) {
				for( $j = $i+1; $j < count($toppings[$group['id']]); $j++ ) {
					if( $toppings[$group['id']][$i] == $toppings[$group['id']][$j] ) {
						array_push($to_remove, $j);
					}
				}
			}
			
			// remove wrong toppings
			foreach( $to_remove as $rm ) {
				if( !empty($toppings[$group['id']][$rm]) ) {
					unset($toppings[$group['id']][$rm]);
				}
			}
			
			// check selected quantity toppings
			if( $group['min_toppings'] > count($toppings[$group['id']]) || count($toppings[$group['id']]) > $group['max_toppings'] ) {
				echo json_encode(array(0, JText::_('VRTKADDITEMERR1')));
				exit;
			}
			
			// get toppings objects
			for( $i = 0; $i < count($toppings[$group['id']]); $i++ ) {
				$found = false;
				for( $j = 0; $j < count($group['toppings']) && !$found; $j++ ) {
					if( $toppings[$group['id']][$i] == $group['toppings'][$j]['assoc_id'] ) {
							
						// create take-away cart item group
						$item_group_topping = new TakeAwayItemGroupTopping($group['toppings'][$j]['id'], $group['toppings'][$j]['assoc_id'], $group['toppings'][$j]['name'], $group['toppings'][$j]['rate']);
						$item_group->addTopping($item_group_topping);
						
						$found = true;
					}
				}
			}

			$item->addToppingsGroup($item_group);
			
		}
		
		if( $item_cart_index < 0 ) {
			// create new item
			$index = $cart->indexOf($item);
			if( $index !== -1 ) {
				// a similar item already exists > update it
				$item = $cart->getItemAt($index);
				
				$item->setQuantity($item->getQuantity()+$quantity);
				$item->setAdditionalNotes($notes);
			} else {
				// add the new item
				if( ($index = $cart->addItem($item)) === false ) {
					echo json_encode(array(0, JText::sprintf('VRTKMAXSIZECARTERR', $cart->getMaxSize())));
					exit;
				}
			}
		} else {
			// update item > recover index from cart
			$index = $item_cart_index;
		}

		// check max quantity for update or merge functions
		if( $cart->getPreparationItemsQuantity() > $cart->getMaxSize() ) {
			echo json_encode(array(0, JText::sprintf('VRTKMAXSIZECARTERR', $cart->getMaxSize())));
			exit;
		}

		$msg = null;

		// CHECK IN STOCK
		$stock_item = $cart->getItemAt($index);
		$in_stock = cleverdine::getTakeawayItemRemainingInStock($stock_item->getItemID(), $stock_item->getVariationID(), -1, $dbo);
		if( $in_stock != -1 ) { // if -1, stocks are disabled
			$stock_item_quantity = $cart->getQuantityItems($stock_item->getItemID(), $stock_item->getVariationID());
			
			if( $in_stock-$stock_item_quantity < 0 ) {
				$removed_items = $stock_item_quantity-$in_stock;
				$stock_item->remove($removed_items);

				$msg = new stdClass;
				if( $quantity == $removed_items ) {
					$msg->text = JText::sprintf('VRTKSTOCKNOITEMS', $item->getFullName());
					$msg->status = 0;
				} else {
					$msg->text = JText::sprintf('VRTKSTOCKREMOVEDITEMS', $item->getFullName(), $removed_items);
					$msg->status = 2;
				}
			}
		}
		//
		
		// @since 1.7 : reset cart to handle correctly deal_quantities
		cleverdine::resetDealsInCart($cart);
		//
		cleverdine::checkForDeals($cart);
		
		$cart->store();
		
		$cart_array = array();
		foreach( $cart->getItemsList() as $item_index => $item ) {
			$std = new stdClass;
			$std->item_name = $item->getItemName();
			$std->var_name = $item->getVariationName();
			$std->price = $item->getTotalCost();
			$std->original_price = $item->getTotalCostNoDiscount();
			$std->quantity = $item->getQuantity();
			$std->index = $item_index;
			$std->removable = $item->canBeRemoved();
			array_push($cart_array, $std);
		}
		
		// return : array( success, std items array, total cost, discount, real total, message )
		echo json_encode(array(1, $cart_array, $cart->getTotalCost(), $cart->getTotalDiscount(), $cart->getRealTotalCost(), $msg));
		exit;
	}
	
	function remove_from_cart() {

		$input = JFactory::getApplication()->input;
		
		$index 		= $input->getInt('index', -1);
		$do_ajax 	= $input->getBool('do_ajax');
		
		$response = array(0, JText::_('VRTKCARTROWNOTFOUND')); // ERROR
		
		// get cart instance
		cleverdine::loadCartLibrary();
		
		$cart = TakeAwayCart::getInstance();
		
		// get selected item
		$item = $cart->getItemAt($index);
		if ($item !== null) {
			$item->remove($item->getQuantity());
			
			cleverdine::checkForDeals($cart);
			$cart->store();
			
			$cart_array = array();
			foreach ($cart->getItemsList() as $item_index => $item) {
				$std = new stdClass;
				$std->item_name = $item->getItemName();
				$std->var_name = $item->getVariationName();
				$std->price = $item->getTotalCost();
				$std->original_price = $item->getTotalCostNoDiscount();
				$std->quantity = $item->getQuantity();
				$std->index = $item_index;
				$std->removable = $item->canBeRemoved();
				array_push($cart_array, $std);
			}
			
			$response = array(1, $cart_array, $cart->getTotalCost(), $cart->getTotalDiscount(), $cart->getRealTotalCost());
			
		}        
		
		if ($do_ajax) {
			echo json_encode($response);
			exit;
		}
		
		$mainframe = JFactory::getApplication();
		$mainframe->redirect(JRoute::_('index.php?option=com_cleverdine&task=takeawayconfirm', false));
	}
	
	function flush_cart() {
		cleverdine::loadCartLibrary();
		
		TakeAwayCart::getInstance()
			->emptyCart()
			->store();
		
		exit;
	}
	
	function get_working_shifts() {

		$input = JFactory::getApplication()->input;
		
		$date 		= $input->getString('date');
		$sel_hm 	= $input->getString('hourmin');
		$only_names = $input->getBool('onlynames');
		
		$shifts = array();
		
		if( !cleverdine::isContinuosOpeningTime() ) {
			$shifts = cleverdine::getWorkingShifts(1);
			$special_day_for = cleverdine::getSpecialDaysOnDate(array('date' => $date, 'hour' => 0, 'min' => 0, "hourmin" => "0:0"));
			if( count($special_day_for) > 0 && $special_day_for !== -1 ) {
				$shifts = cleverdine::getWorkingShiftsFromSpecialDays($shifts, $special_day_for);
			}
		} else {
			// do not change
			echo json_encode(array(1, ''));
			exit;
		}
		
		$min_intervals = cleverdine::getMinuteIntervals();
		$time_f = cleverdine::getTimeFormat();
		
		$html = '';
		if( !$only_names ) {
			for( $k = 0, $n = count($shifts); $k < $n; $k++ ) {
			
				if( $shifts[$k]['showlabel'] ) {
				  $html .= '<optgroup class="vrsearchoptgrouphour" label="'.$shifts[$k]["label"].'">';
				}
				
				for( $_app = $shifts[$k]['from']; $_app <= $shifts[$k]['to']; $_app+=$min_intervals ) {
					$_hour = intval($_app/60);
					$_min = $_app%60;
					
					$selected = ($sel_hm==$_hour.":".$_min ? 'selected="selected"' : '');
					
					$html .= '<option value="'.$_hour.':'.$_min.'" '.$selected.'>'.date($time_f, mktime($_hour,$_min,0,1,1,2000)).'</option>';
				}
				
				if( $shifts[$k]['showlabel'] ) {
				  $html .= '</optgroup>';
				}
			}
		} else {
			foreach( $shifts as $_s ) {
				$name = $_s['label'];
				if( !$_s['showlabel'] || empty($name) ) {
					$name = date($time_format, mktime($_s['from_hour'], $_s['from_min'], 0, 1, 1, 2000))." - ".
					date($time_format, mktime($_s['to_hour'], $_s['to_min'], 0, 1, 1, 2000));
				}
				$html .= '<option value="'.intval($_s['from']/60).'-'.intval($_s['to']/60).'">'.$name.'</option>';
			}
		}
		
		echo json_encode(array(1, $html));
		exit;
		
	}

	//

	function get_menu_sections() {
		$operator = cleverdine::getOperator();
		if( $operator === false ) {
			echo json_encode(array());
			exit;
		}

		$input = JFactory::getApplication()->input;

		$id_menu = $input->getUint('id_menu', 0);

		$dbo = JFactory::getDbo();

		$sections = array();

		$q = "SELECT * FROM `#__cleverdine_menus_section` WHERE `id_menu`=$id_menu ORDER BY `ordering`;";

		$dbo->setQuery($q);
		$dbo->execute();

		if( $dbo->getNumRows() ) {
			$sections = $dbo->loadAssocList();
		}

		echo json_encode($sections);
		exit;
	}

	function get_section_products() {
		$operator = cleverdine::getOperator();
		if( $operator === false ) {
			echo json_encode(array());
			exit;
		}

		$input = JFactory::getApplication()->input;

		$id_section = $input->getUint('id_section', 0);

		$dbo = JFactory::getDbo();

		$products = array();

		if( $id_section > 0 ) {

			$q = "SELECT `p`.* 
			FROM `#__cleverdine_section_product_assoc` AS `a` 
			LEFT JOIN `#__cleverdine_section_product` AS `p` ON `a`.`id_product`=`p`.`id`
			WHERE `a`.`id_section`=$id_section 
			ORDER BY `p`.`ordering`;";

		} else {

			$q = "SELECT * FROM `#__cleverdine_section_product` WHERE `hidden`=1 ORDER BY `ordering` ASC;";

		}

		$dbo->setQuery($q);
		$dbo->execute();

		if( $dbo->getNumRows() ) {
			$products = $dbo->loadAssocList();
		}

		echo json_encode($products);
		exit;
	}

	function get_product_html() {
		$operator = cleverdine::getOperator();
		if( $operator === false ) {
			echo json_encode(array('status' => 0));
			exit;
		}

		$input = JFactory::getApplication()->input;

		$id_product = $input->getUint('id_product', 0);
		$id_assoc	= $input->getUint('id_assoc', 0);

		$item = null;

		// get item

		if( $id_product > 0 ) {

			$dbo = JFactory::getDbo();

			$q = "SELECT `p`.`id`, `p`.`name`, `p`.`price`, `o`.`id` AS `oid`, `o`.`name` AS `oname`, `o`.`inc_price` AS `oprice` 
			FROM `#__cleverdine_section_product` AS `p` 
			LEFT JOIN `#__cleverdine_section_product_option` AS `o` ON `o`.`id_product`=`p`.`id` 
			WHERE `p`.`id`=$id_product ORDER BY `o`.`ordering`;";

			$dbo->setQuery($q);
			$dbo->execute();

			if( $dbo->getNumRows() > 0 ) {

				// build defaul item
				
				$rows = $dbo->loadAssocList();

				$item = array();

				$item['id'] 		= $rows[0]['id'];
				$item['name'] 		= $rows[0]['name'];
				$item['price'] 		= $rows[0]['price'];
				$item['quantity'] 	= 1;
				$item['id_var'] 	= 0;
				$item['notes']		= '';
				$item['variations'] = array();
				foreach( $rows as $r ) {
					if( !empty($r['oid']) ) {
						array_push($item['variations'], array(
							'id' => $r['oid'],
							'name' => $r['oname'],
							'price' => $r['oprice'],
						));
					}
				}

				// build assoc item

				if( $id_assoc > 0 ) {

					$q = "SELECT `i`.`quantity`, `i`.`price`, `i`.`id_product_option` AS `id_var`, `i`.`notes`
					FROM `#__cleverdine_res_prod_assoc` AS `i`
					WHERE `i`.`id`=$id_assoc LIMIT 1;";
					$dbo->setQuery($q);
					$dbo->execute();
					if( $dbo->getNumRows() > 0 ) {
						
						foreach( $dbo->loadAssoc() as $k => $v ) {
							$item[$k] = $v;
						}

					}

				}

			}

		}

		//

		// HTML
		
		$curr_symb = cleverdine::getCurrencySymb();
		$symb_pos = cleverdine::getCurrencySymbPosition();

		$html = '<div class="control-group"><h4>'.($item !== null ? $item['name'] : JText::_('VRCREATENEWPROD')).'</h4></div>';

		if( $item === null ) {
			$html .= '<div class="vrfront-field">
				<span class="field-label">'.JText::_('VRNAME').':</span>
				<span class="field-value">
					<input type="text" name="name" value="" size="32"/>
				</span>
			</div>';

			$html .= '<div class="vrfront-field">
				<span class="field-label">'.JText::_('VRPRICE').':</span>
				<span class="field-value">
					<input type="number" name="price" value="0.00" size="4" min="0" step="any"/>
				</span>
			</div>';
		}

		$html .= '<div class="vrfront-field">
			<span class="field-label">'.JText::_('VRTKADDQUANTITY').':</span>
			<span class="field-value">
				<input type="number" name="quantity" value="'.($item !== null ? $item['quantity'] : 1).'" size="4" min="1"/>
			</span>
		</div>';
			
		if( $item !== null && count($item['variations']) > 0 ) {
			$html .= '<div class="vrfront-field">
				<span class="field-label">'.JText::_('VRVARIATION').':</span>
				<span class="field-value vre-tinyselect-wrapper">
					<select name="id_option" class="vrtk-variations-reqselect vre-tinyselect">';
						foreach( $item['variations'] as $var ) {
							$html .= '<option value="'.$var['id'].'" '.($item['id_var'] == $var['id'] ? 'selected="selected"' : '').'>'.$var['name'].'</option>';
						}
					$html .= '</select>
				</span>
			</div>';
		}
			
		$html .= '<div class="vrfront-field">
			<span class="field-label">'.JText::_('VRNOTES').':</span>
			<span class="field-value">
				<textarea name="notes" maxlength="128" style="width: 80%;height:100px;">'.($item !== null ? $item['notes'] : '').'</textarea>
			</span>
		</div>';

		$html .= '<div class="vrfront-field">
			<span class="field-label"></span>
			<span class="field-value">
				<button type="button" class="vrtk-addtocart-button" onClick="vrPostItem('.($item !== null ? 1 : 0).');">
					'.strtoupper(JText::_($id_assoc >= 0 ? "VRSAVE" : "VRTKADDOKBUTTON")).'
				</button>
			</span>
		</div>
		<input type="hidden" name="item_index" value="'.$id_assoc.'"/>
		<input type="hidden" name="id_entry" value="'.$id_product.'" />';
		
		echo json_encode(array("status" => 1, "html" => $html));
		exit;
	}

	function search_section_product() {
		$operator = cleverdine::getOperator();
		if( $operator === false ) {
			echo "[]";
			exit;
		}

		$input = JFactory::getApplication()->input;
		$dbo = JFactory::getDbo();
		
		$search = $input->getString('term');
		
		$q = "SELECT `id`, `name` FROM `#__cleverdine_section_product` WHERE `name` LIKE ".$dbo->quote("%$search%").";";
		$dbo->setQuery($q);
		$dbo->execute();

		if( $dbo->getNumRows() > 0 ) {
			echo json_encode($dbo->loadAssocList());
		} else {
			echo "[]";
		}
		
		exit;

	}

	function add_item_to_res() {
		$operator = cleverdine::getOperator();
		if( $operator === false ) {
			echo json_encode(array("status" => 0, "error" => JText::_('VRACTIONDENIED')));
			exit;
		}

		$input = JFactory::getApplication()->input;
		
		$id_entry 			= $input->get('id_entry', 0, 'int');
		$id_option 			= $input->get('id_option', 0, 'int');
		$id_res 			= $input->get('id', 0, 'int');
		$item_cart_index 	= $input->get('item_index', 0, 'int');
		
		$quantity 	= $input->get('quantity', 1, 'uint');
		$notes 		= $input->get('notes', '', 'string');
		
		if( $quantity <= 0 ) {
			$quantity = 1;
		}
		
		$dbo = JFactory::getDbo();

		// create a new entry if does not exist
		if( $id_entry == 0 ) {

			$name 	= $input->get('name', '', 'string');
			$price 	= abs($input->get('price', 0, 'float'));

			if( empty($name) ) {
				$name = 'prod #'.rand(1000, 9999);
			}

			$q = "INSERT INTO `#__cleverdine_section_product`(`name`,`price`,`published`,`hidden`,`ordering`) VALUES(".$dbo->quote($name).", $price, 0, 1, 0);";

			$dbo->setQuery($q);
			$dbo->execute();

			$id_entry = $dbo->insertid();
		}

		
		$entry = array();

		$q = "SELECT `p`.`id`, `p`.`name`, `p`.`price`, `o`.`id` AS `oid`, `o`.`name` AS `oname`, `o`.`inc_price` AS `oprice` 
		FROM `#__cleverdine_section_product` AS `p` 
		LEFT JOIN `#__cleverdine_section_product_option` AS `o` ON `o`.`id_product`=`p`.`id` 
		WHERE `p`.`id`=$id_entry ".($id_option > 0 ? "AND `o`.`id`=$id_option " : "")."LIMIT 1;";
		
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$entry = $dbo->loadAssoc();
		} else {
			echo json_encode(array("status" => 0, "error" => JText::_('VRTKCARTROWNOTFOUND')));
			exit;
		}
		
		$insert_id = -1;

		$entry_total_cost = ($entry['price'] + $entry['oprice']) * $quantity;

		$entry_full_name = $entry['name'].(!empty($entry['oname']) ? ' - '.$entry['oname'] : '');

		// get total bill

		$total_bill_value = 0;

		$q = "SELECT `bill_value` FROM `#__cleverdine_reservation` WHERE `id`=$id_res LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->execute();

		if( $dbo->getNumRows() ) {
			$total_bill_value = $dbo->loadResult();
		}
		
		// create take-away cart item
		if( $item_cart_index <= 0 ) {

			$q = "INSERT INTO `#__cleverdine_res_prod_assoc`(`id_reservation`,`id_product`,`id_product_option`,`name`,`price`,`quantity`,`notes`) VALUES(".
			$id_res.",".
			$id_entry.",".
			(!empty($id_option) ? $id_option : -1).",".
			$dbo->quote($entry_full_name).",".
			$entry_total_cost.",".
			$quantity.",".
			$dbo->quote($notes).
			");";
			
			$dbo->setQuery($q);
			$dbo->execute();
			$insert_id = $dbo->insertid();
			if( $insert_id <= 0 ) {
				echo json_encode(array("status" => 0, "error" => JText::_('VRTKCARTROWNOTFOUND')));
				exit;
			}

			$total_bill_value += $entry_total_cost;

		} else {

			$q = "SELECT `price` FROM `#__cleverdine_res_prod_assoc` WHERE `id`=$item_cart_index LIMIT 1;";
			$dbo->setQuery($q);
			$dbo->execute();

			if( $dbo->getNumRows() ) {
				$total_bill_value -= (float)$dbo->loadResult();
			}

			$q = "UPDATE `#__cleverdine_res_prod_assoc` SET 
			`id_product_option`=".(!empty($id_option) ? $id_option : -1).", 
			`name`=".$dbo->quote($entry_full_name).",
			`price`=$entry_total_cost,
			`quantity`=$quantity,
			`notes`=".$dbo->quote($notes)." 
			WHERE `id`=$item_cart_index LIMIT 1;";
			
			$dbo->setQuery($q);
			$dbo->execute();

			if ($dbo->getAffectedRows()) {
				$insert_id = $item_cart_index;

				$total_bill_value += $entry_total_cost;
			}

		}

		// update bill value

		$total_bill_value = max(array(0, $total_bill_value));

		$q = "UPDATE `#__cleverdine_reservation` SET `bill_value`=$total_bill_value WHERE `id`=$id_res LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->execute();
		
		$std = new stdClass;
		$std->item_id = $id_entry;
		$std->item_name = $entry_full_name;
		$std->price = $entry_total_cost;
		$std->quantity = $quantity;
		
		// return : array( success, index, std item, total bill value )
		echo json_encode(array("status" => 1, "id" => $insert_id, "object" => $std, "grand_total" => $total_bill_value));
		exit;
		
	}

	function remove_item_from_res() {
		$operator = cleverdine::getOperator();
		if( $operator === false ) {
			exit;
		}

		$input = JFactory::getApplication()->input;

		$id = $input->getUint('id', 0);

		$dbo = JFactory::getDbo();

		// get food details

		$q = "SELECT * FROM `#__cleverdine_res_prod_assoc` WHERE `id`=$id LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->execute();

		if( !$dbo->getNumRows() ) {
			exit;
		}

		$food = $dbo->loadAssoc();

		// get total bill

		$q = "SELECT `bill_value` FROM `#__cleverdine_reservation` WHERE `id`=".$food['id_reservation']." LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->execute();

		if( !$dbo->getNumRows() ) {
			exit;
		}

		$total_bill_value = (float) ($dbo->loadResult() - $food['price']);

		// delete product

		$q = "DELETE FROM `#__cleverdine_res_prod_assoc` WHERE `id`=$id LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->execute();

		if( !$dbo->getAffectedRows() ) {
			exit;
		}

		$q = "UPDATE `#__cleverdine_reservation` SET `bill_value`=$total_bill_value WHERE `id`=".$food['id_reservation']." LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->execute();

		echo json_encode(array('grand_total' => $total_bill_value));
		exit;
	}

	//

	function tkadditem() {
		$input = JFactory::getApplication()->input;

		$input->set('view','tkadditem');
		$input->set('tmpl','component');
		parent::display();
	}
	
	function confirmord() {
		$input = JFactory::getApplication()->input;

		$oid 		= $input->getUint('oid');
		$conf_key 	= $input->getAlnum('conf_key');
		$tid 		= $input->getUint('tid');
		
		if( empty($conf_key) ) {
			echo '<div class="vr-confirmpage order-error">'.JText::_('VRCONFORDNOROWS').'</div>';
			return;
		}
		
		$dbo = JFactory::getDbo();
		$q = "SELECT `sid`, `status` FROM `#__cleverdine".($tid == 1 ? '_takeaway' : '')."_reservation` WHERE `id`=$oid AND `conf_key`=".$dbo->quote($conf_key)." LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() == 0 ) {
			echo '<div class="vr-confirmpage order-error">'.JText::_('VRCONFORDNOROWS').'</div>';
			return;
		}
		
		$assoc = $dbo->loadAssoc();
		if( $assoc['status'] != 'PENDING' ) {
			if( $assoc['status'] == 'CONFIRMED' ) {
				echo '<div class="vr-confirmpage order-notice">'.JText::_('VRCONFORDISCONFIRMED').'</div>';
			} else {
				echo '<div class="vr-confirmpage order-error">'.JText::_('VRCONFORDISREMOVED').'</div>';
			}
			return;
		}
		
		$q = "UPDATE `#__cleverdine".($tid == 1 ? '_takeaway' : '')."_reservation` SET `status`='CONFIRMED' WHERE `id`=$oid LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->execute();
		
		echo '<div class="vr-confirmpage order-good">'.JText::_('VRCONFORDCOMPLETED').'</div>';
		
		if( $tid == 0 ) {
			$order_details = cleverdine::fetchOrderDetails($oid);
			cleverdine::sendAdminEmail($order_details);
			cleverdine::sendCustomerEmail($order_details);
		} else {
			$order_details = cleverdine::fetchTakeAwayOrderDetails($oid);
			cleverdine::sendAdminEmailTakeaway($order_details);
			cleverdine::sendCustomerEmailTakeaway($order_details);
		}
		
		// STORE OPERATOR LOG
		$operator = cleverdine::getOperator();
		if( !empty($operator['id']) && $operator['keep_track'] ) {
			$log = cleverdine::generateOperatorLog(
				$operator, $oid, 
				($tid == 0 ? cleverdine::OPERATOR_RESTAURANT_LOG : cleverdine::OPERATOR_TAKEAWAY_LOG ), 
				($tid == 0 ? cleverdine::OPERATOR_RESTAURANT_CONFIRMED : cleverdine::OPERATOR_TAKEAWAY_CONFIRMED )
			);
			cleverdine::storeOperatorLog($operator['id'], $oid, $log, ($tid == 0 ? cleverdine::OPERATOR_RESTAURANT_LOG : cleverdine::OPERATOR_TAKEAWAY_LOG ));
		}
		
	}

	function approve_review() {
		$input = JFactory::getApplication()->input;

		$id 		= $input->getUint('id');
		$conf_key 	= $input->getAlnum('conf_key');
		
		if( empty($conf_key) ) {
			echo '<div class="vr-confirmpage order-error">'.JText::_('VRCONFREVIEWNOROWS').'</div>';
			return;
		}

		$dbo = JFactory::getDbo();
		
		$review = cleverdine::fetchReview($id, $conf_key, $dbo);
		if( $review === null ) {
			echo '<div class="vr-confirmpage order-error">'.JText::_('VRCONFREVIEWNOROWS').'</div>';
			return;
		}
		
		if( $review['published'] ) {
			echo '<div class="vr-confirmpage order-notice">'.JText::_('VRCONFREVIEWISCONFIRMED').'</div>';
			return;
		}
		
		$q = "UPDATE `#__cleverdine_reviews` SET `published`=1 WHERE `id`=$id LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->execute();
		
		echo '<div class="vr-confirmpage order-good">'.JText::_('VRCONFREVIEWCOMPLETED').'</div>';
		
	}

	function userlogout() {
		$mainframe = JFactory::getApplication();
		$mainframe->logout(JFactory::getUser()->id);
		$mainframe->redirect(JRoute::_('index.php?option=com_cleverdine&view=allorders'));
	}

	function submit_review() {

		$mainframe = JFactory::getApplication();
		$input = $mainframe->input;
		$dbo = JFactory::getDbo();
			
		$args = array();
		$args['title'] 			= $input->getString('review_title');
		$args['comment'] 		= $input->getString('review_comment');
		$args['rating'] 		= $input->getUint('review_rating', 1);
		$args['id_tk_prod'] 	= $input->getInt('id_tk_prod');
		$args['user'] 			= JFactory::getUser();
		$args['published'] 		= cleverdine::isReviewsAutoPublished();
		$args['timestamp'] 		= time();
		$args['langtag'] 		= JFactory::getLanguage()->getTag();
		
		$qs = '';
		foreach( $input->get('request', array(), 'array') as $k => $v ) {
			if( !empty($k) ) {
				$qs .= "&$k=$v";
			}
		}

		// user cannot leave a review for this element
		if( !cleverdine::canLeaveTakeAwayReview($args['id_tk_prod'], $dbo) ) {
			$mainframe->enqueueMessage(JText::_('VRPOSTREVIEWAUTHERR'), 'error');
			$mainframe->redirect(JRoute::_('index.php?option=com_cleverdine&view=revslist'.$qs));
			exit;
		}
		
		// title or rating or service are empties
		if( empty($args['title']) || empty($args['rating']) || empty($args['id_tk_prod']) ) {
			$mainframe->enqueueMessage(JText::_('VRPOSTREVIEWFILLERR'), 'error');
			$mainframe->redirect(JRoute::_('index.php?option=com_cleverdine&view=revslist'.$qs));
			exit;
		}
		
		// comment required and empty
		if( cleverdine::isReviewsCommentRequired() && empty($args['comment']) ) {
			$mainframe->enqueueMessage(JText::_('VRPOSTREVIEWFILLERR'), 'error');
			$mainframe->redirect(JRoute::_('index.php?option=com_cleverdine&view=revslist'.$qs));
			exit;
		}

		// comment length higher than 0 but lower than min length
		if( strlen($args['comment']) > 0 && strlen($args['comment']) < cleverdine::getReviewsCommentMinLength() ) {
			$mainframe->enqueueMessage(JText::_('VRPOSTREVIEWFILLERR'), 'error');
			$mainframe->redirect(JRoute::_('index.php?option=com_cleverdine&view=revslist'.$qs));
			exit;
		}

		$args['comment'] = mb_substr($args['comment'], 0, cleverdine::getReviewsCommentMaxLength(), 'UTF-8');

		$args['verified'] = intval(cleverdine::isVerifiedTakeAwayReview($args['id_tk_prod'], $args['user'], $dbo));

		if( $args['user']->guest ) {
			$args['user']->name = $input->getString('review_user_name');
			$args['user']->email = $input->getString('review_user_mail');

			if( empty($args['user']->name) || !cleverdine::validateUserEmail($args['user']->email) ) {
				$mainframe->enqueueMessage(JText::_('VRPOSTREVIEWFILLERR'), 'error');
				$mainframe->redirect(JRoute::_('index.php?option=com_cleverdine&view=revslist'.$qs));
				exit;
			} 
		}
		
		$q = "INSERT INTO `#__cleverdine_reviews` (`jid`,`ipaddr`,`timestamp`,`name`,`email`,`title`,`comment`,`rating`,`published`,`verified`,`langtag`,`id_takeaway_product`,`conf_key`) VALUES(
		".$args['user']->id.",
		".$dbo->quote($input->server->get('REMOTE_ADDR')).",
		".$args['timestamp'].",
		".$dbo->quote($args['user']->name).",
		".$dbo->quote($args['user']->email).",
		".$dbo->quote($args['title']).",
		".$dbo->quote($args['comment']).",
		".$args['rating'].",
		".$args['published'].",
		".$args['verified'].",
		".$dbo->quote($args['langtag']).",
		".$args['id_tk_prod'].",
		".$dbo->quote(cleverdine::generateSerialCode(12))."
		);";
		
		$dbo->setQuery($q);
		$dbo->execute();
		$id_review = $dbo->insertid();
		if( $id_review > 0 ) {
			$mainframe->enqueueMessage(JText::_($args['published'] ? 'VRPOSTREVIEWCREATEDCONF' : 'VRPOSTREVIEWCREATEDPEND'));
		} else {
			$mainframe->enqueueMessage(JText::_('VRPOSTREVIEWINSERTERR'), 'error');
		}

		$review = cleverdine::fetchReview($id_review);
		if( $review !== null ) {
			cleverdine::sendReviewEmailTakeAway($review, '', $dbo);
		}
		
		$mainframe->redirect(JRoute::_('index.php?option=com_cleverdine&view=revslist'.$qs));
		exit;
		

	}

	function register_allorders_tab() {
		$id = JFactory::getApplication()->input->getUint('id');
		if( $id < 1 || $id > 2 ) {
			$id = 1;
		}
		JFactory::getSession()->set('allorderstab', $id, 'vre');
		exit;
	}
	
	// MANAGEMENT
	
	function oplogout() {
		$mainframe = JFactory::getApplication();
		$mainframe->logout(JFactory::getUser()->id);
		$mainframe->redirect(JRoute::_('index.php?option=com_cleverdine&view=oversight'));
	}
	
	function quickres() {
		JFactory::getApplication()->input->set('view', 'quickres');
		parent::display();
	}

	function editres() {
		JFactory::getApplication()->input->set('view', 'editres');
		parent::display();
	}
	
	function opdashboard() {
		JFactory::getApplication()->input->set('view', 'opdashboard');
		parent::display();
	}
	
	function opreservations() {
		JFactory::getApplication()->input->set('view', 'opreservations');
		parent::display();
	}
	
	function opcoupons() {
		JFactory::getApplication()->input->set('view', 'opcoupons');
		parent::display();
	}
	
	function opmanagecoupon() {
		JFactory::getApplication()->input->set('view', 'opmanagecoupon');
		parent::display();
	}

	function store_active_room() {
		$room = JFactory::getApplication()->input->getUint('room');
		
		$session = JFactory::getSession();
		$session->set('active-room', $room, 'vre');
		exit;
	}
	
	function changetable() {
		
		$mainframe = JFactory::getApplication();
		$input = $mainframe->input;
		$dbo = JFactory::getDbo();
		
		$operator = cleverdine::getOperator();
		
		$args = array();
		$args['date'] 		= $input->getString('date');
		$args['hourmin'] 	= $input->getString('hourmin');
		$args['people'] 	= $input->getUint('people');
		$args['id_table'] 	= $input->getUint('oldid');
		$args['table'] 		= $args['id_table'];
		
		if( $operator === false || empty($operator['can_login']) ) {
			$mainframe->enqueueMessage(JText::_('VRACTIONDENIED'), 'error');
			$mainframe->redirect(JRoute::_('index.php?option=com_cleverdine&view=oversight&datefilter='.$args['date']."&hourmin=".$args['hourmin']."&people=".$args['people'].'&Itemid='.$input->getUint('Itemid'), false));
			exit;
		}
		
		$_app_exp = explode( ':', $args['hourmin'] );
		$args['hour'] = -1;
		$args['min'] = 0;
		if( count( $_app_exp ) == 2 ) {
			$args['hour'] = $_app_exp[0];
			$args['min'] = $_app_exp[1];
		}
		
		$new_tab_id = $input->getUint('newid');
		
		$q = cleverdine::getQueryAllReservationsRelativeToWithoutPayments($args,true);
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$rows = $dbo->loadAssocList();
			$oid = $rows[0]['id'];
			
			$args['table'] = $args['id_table'] = $new_tab_id;
			
			$multi_res = 0;
			$q = "SELECT `t`.`multi_res` FROM `#__cleverdine_table` AS `t`, `#__cleverdine_reservation` AS `r` WHERE `t`.`id`=`r`.`id_table` AND `t`.`id`=".$new_tab_id.";";
			$dbo->setQuery($q);
			$dbo->execute();
			if( $dbo->getNumRows() > 0 ) {
				$multi_res = $dbo->loadResult();
			}
			
			$q = "";
			if( $multi_res == 0 ) {
				$q = cleverdine::getQueryTableJustReserved($args, true);
			} else {
				$q = cleverdine::getQueryFindTableMultiResWithID($args, true);
			}
			$dbo->setQuery($q);
			$dbo->execute();
			if( $dbo->getNumRows() > 0 || $multi_res ) {
				
				$result = $dbo->loadAssoc();
				
				if( !$multi_res || count($result) == 0 || $result['curr_capacity']+$args['people'] <= $result['max_capacity'] ) {

					$q = "UPDATE `#__cleverdine_reservation` SET `id_table`=".$new_tab_id." WHERE `id`=".$oid.";";
					$dbo->setQuery($q); 
					$dbo->execute();

					if( $dbo->getAffectedRows() ) {
						$mainframe->enqueueMessage(JText::_('VRMAPTABLECHANGEDSUCCESS'));
						
						// STORE OPERATOR LOG
						$operator = cleverdine::getOperator();
						if( !empty($operator['id']) && $operator['keep_track'] ) {
							$log = cleverdine::generateOperatorLog($operator, $oid, cleverdine::OPERATOR_RESTAURANT_LOG, cleverdine::OPERATOR_RESTAURANT_TABLE_CHANGED);
							cleverdine::storeOperatorLog($operator['id'], $oid, $log, cleverdine::OPERATOR_RESTAURANT_LOG);
						}
					}
					
				} else {
					$mainframe->enqueueMessage(JText::_('VRMAPTABLENOTCHANGED'), 'error');
				}
				
			} else {
				$mainframe->enqueueMessage(JText::_('VRMAPTABLENOTCHANGED'), 'error');
			}
			
		} else {
			$mainframe->enqueueMessage(JText::_('VRMAPTABLENOTCHANGED'), 'error');
		}
		
		$mainframe->redirect(JRoute::_('index.php?option=com_cleverdine&view=oversight&datefilter='.$args['date']."&hourmin=".$args['hourmin']."&people=".$args['people'].'&Itemid='.$input->getUint('Itemid'), false));

	}

	function saveQuickReservation() {
		
		$mainframe = JFactory::getApplication();
		$input = $mainframe->input;
		$dbo = JFactory::getDbo();
		
		$operator = cleverdine::getOperator();
		
		if( $operator === false || empty($operator['can_login']) )  {
			$mainframe->enqueueMessage(JText::_('VRLOGINUSERNOTFOUND'), 'error');
			$mainframe->redirect(JRoute::_('index.php?option=com_cleverdine&view=oversight&Itemid='.$input->getUint('Itemid')));
			exit;
		}
		
		$args = array();
		$args['date'] 		= $input->getString('date');
		$args['hourmin'] 	= $input->getString('hourmin');
		$args['id_table'] 	= $input->getUint('id_table');
		$args['people'] 	= $input->getUint('people');
		$args['id'] 		= $input->getInt('id', -1);
		
		$_hour_min = explode(':', $args['hourmin']);
		$args['hour'] = $_hour_min[0];
		$args['min'] = $_hour_min[1];
		
		$_cf = array();
		$p_name = "";
		$p_mail = "";
		$p_phone = "";
		
		$q = "SELECT * FROM `#__cleverdine_custfields` 
		WHERE `group`=0 AND `type`<>'separator' AND (`type`<>'checkbox' OR `required`=0)  
		ORDER BY `ordering` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() ) {
			$_cf = $dbo->loadAssocList();
		}
		
		$cust_req = array();
		
		$blank_keys = array();
		$_i = 0;
		foreach( $_cf as $_app ) {
			$cust_req[$_app['name']] = $input->get('vrcf'.$_app['id'], '', 'string');
			if( !cleverdine::isCustomFieldValid($_app, $cust_req[$_app['name']]) ) {
				// IF YOU WANT TO REQUIRE CUSTOM FIELDS DECOMMENTS THESE LINES
				//$blank_keys[$_i] = 'vrcf'.$_app['id'];
				//$_i++;
			} else if( $_app['rule'] == VRCustomFields::NOMINATIVE ) {

				if( !empty($p_name) ) {
					$p_name .= ' ';
				}
				$p_name .= $cust_req[$_app['name']];

			} else if( $_app['rule'] == VRCustomFields::EMAIL ) {

				$p_mail = $cust_req[$_app['name']];

			} else if( $_app['rule'] == VRCustomFields::PHONE_NUMBER ) {

				$p_phone = $cust_req[$_app['name']];

			}
		}
		
		
		$args['purchaser_nominative'] = $p_name;
		$args['purchaser_mail'] = $p_mail;
		$args['purchaser_phone'] = $p_phone;
		
		$args['custom_f'] = $cust_req;
		
		$args['table'] = $args['id_table'];
		
		$args['status'] = 'CONFIRMED';
		
		$tb_available = true;
		if( count($blank_keys) == 0 ) {

			if( $args['id'] == -1 ) {
				$q = cleverdine::getQueryTableJustReserved($args,true);
				$dbo->setQuery($q);
				$dbo->execute();
				if( $dbo->getNumRows() == 0 ) {
					$tb_available = false;
				}
			} else {
				$q = cleverdine::getQueryTableJustReservedExcludingResId($args,$args['id'],true);
				$dbo->setQuery($q);
				$dbo->execute();
				if( $dbo->getNumRows() == 0 ) {
					$tb_available = false;
				}
			}

		}
		
		$args['sendmail'] = $input->getBool('sendmail');
		if( $args['id'] == -1 ) {
			$args['sendmail'] = 1;
		}
		
		if( $tb_available ) {
			if( $args['id'] == -1 ) {
				$args['id'] = $this->saveNewReservation($args, $dbo, $mainframe);
			} else {
				$this->editSelectedReservation($args, $dbo, $mainframe);
			}
		} else {
			$q_str = "&date=".$args['date']."&hourmin=".$args['hourmin']."&people=".$args['people']."&idt=".$args['id_table'];
			$mainframe->enqueueMessage(JText::_('VRERRTABNOLONGAV'), 'error');
			$mainframe->redirect(JRoute::_("index.php?option=com_cleverdine&Itemid=".$input->getUint('Itemid')."&task=" . ( ( $args["id"] <= 0 ) ? "quickres".$q_str : "editres&cid[]=".$args['id'] ), false));
		}
		
		if( strlen($p_mail) > 0 && $args['sendmail'] ) {
			$order_details = cleverdine::fetchOrderDetails($args['id']);
			cleverdine::sendCustomerEmail($order_details);
			$mainframe->enqueueMessage(JText::_('VRNEWRESMAILSENT'));
		}
		
		$close_page = $input->get('return', 0, 'int');
		if( $close_page ) {
			$from = $input->get('from', '', 'string');
			if( $from == "dash" ) {
				$redirect_url = JRoute::_('index.php?option=com_cleverdine&task=opdashboard&datefilter='.$args['date'].'&Itemid='.$input->get('Itemid', 0, 'int'));
			} else if( $from == "reservations" ) {
				$redirect_url = JRoute::_('index.php?option=com_cleverdine&task=opreservations&datefilter='.$args['date'].'&Itemid='.$input->get('Itemid', 0, 'int'));
			} else {
				$redirect_url = JRoute::_('index.php?option=com_cleverdine&view=oversight&datefilter='.$args['date']."&hourmin=".$args['hourmin']."&people=".$args['people'].'&Itemid='.$input->get('Itemid', 0, 'int'));
			}
		} else {
			$redirect_url = JRoute::_('index.php?option=com_cleverdine&task=editres&cid[]='.$args['id']."&Itemid=".$input->getUint('Itemid'));
		}
		
		$mainframe->redirect($redirect_url);
		
	}

	private function saveNewReservation($args, $dbo, $mainframe) {
		
		$q = "INSERT INTO `#__cleverdine_reservation` 
		(`checkin_ts`,`id_table`,`id_payment`,`people`,`custom_f`,`purchaser_nominative`,`purchaser_mail`,`purchaser_phone`,`status`,`sid`,`created_on`,`created_by`) VALUES(".
		cleverdine::createTimestamp($args['date'],$args['hour'],$args['min']).",".
		$args['id_table'].",".
		"-1,".
		$args['people'].",".
		$dbo->quote(json_encode($args['custom_f'])).",".
		$dbo->quote($args['purchaser_nominative']).",".
		$dbo->quote($args['purchaser_mail']).",".
		$dbo->quote($args['purchaser_phone']).",".
		$dbo->quote($args['status']).",".
		$dbo->quote(cleverdine::generateSerialCode(16)).",".
		time().",".
		JFactory::getUser()->id.
		");";

		$dbo->setQuery($q);
		$dbo->execute();
		$lid = $dbo->insertid();
		if( $lid > 0 ) {
			$mainframe->enqueueMessage(JText::_('VRNEWQUICKRESCREATED'));
			
			// STORE OPERATOR LOG
			$operator = cleverdine::getOperator();
			if( !empty($operator['id']) && $operator['keep_track'] ) {
				$log = cleverdine::generateOperatorLog($operator, $lid, cleverdine::OPERATOR_RESTAURANT_LOG, cleverdine::OPERATOR_RESTAURANT_INSERT);
				cleverdine::storeOperatorLog($operator['id'], $lid, $log, cleverdine::OPERATOR_RESTAURANT_LOG);
			}
		} else {
			$mainframe->enqueueMessage(JText::_('VRNEWQUICKRESNOTCREATED'), 'error');
		}
		
		return $lid;
	}
	
	private function editSelectedReservation($args, $dbo, $mainframe) {

		$input = $mainframe->input;
		
		$args['status'] 	= $input->getString('status');
		$args['rescode'] 	= $input->getInt('rescode');
		
		$q = "UPDATE `#__cleverdine_reservation` SET 
		`checkin_ts`=".cleverdine::createTimestamp($args['date'],$args['hour'],$args['min'],true).", 
		`id_table`=".$args['id_table'].",
		`people`=".$args['people'].", 
		`custom_f`=".$dbo->quote(json_encode($args['custom_f'])).",
		`purchaser_nominative`=".$dbo->quote($args['purchaser_nominative']).",
		`purchaser_mail`=".$dbo->quote($args['purchaser_mail']).",
		`purchaser_phone`=".$dbo->quote($args['purchaser_phone']).", 
		`status`=".$dbo->quote($args['status']).",
		`rescode`=".intval($args['rescode'])." 
		WHERE `id`=".$args['id'].";";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getAffectedRows() ) {
			// STORE OPERATOR LOG
			$operator = cleverdine::getOperator();
			if( !empty($operator['id']) && $operator['keep_track'] ) {
				$log = cleverdine::generateOperatorLog($operator, $args['id'], cleverdine::OPERATOR_RESTAURANT_LOG, cleverdine::OPERATOR_RESTAURANT_UPDATE);
				cleverdine::storeOperatorLog($operator['id'], $args['id'], $log, cleverdine::OPERATOR_RESTAURANT_LOG);
			}
			
			$mainframe->enqueueMessage(JText::_('VRQUICKRESUPDATED'));
		}
		
	}
	
	function saveCoupon() {
		
		$mainframe = JFactory::getApplication();
		$input = $mainframe->input;
		$dbo = JFactory::getDbo();
		
		$operator = cleverdine::getOperator();
		
		$itemid = $input->getUint('Itemid');
		
		if( $operator === false || empty($operator['can_login']) )  {
			$mainframe->enqueueMessage(JText::_('VRLOGINUSERNOTFOUND'), 'error');
			$mainframe->redirect(JRoute::_("index.php?option=com_cleverdine&view=oversight&Itemid=$itemid"));
			exit;
		}
		
		$args = array();
		$args['code'] 		= $input->getString('code');
		$args['type'] 		= $input->getUint('type', 1);
		$args['percentot'] 	= $input->getUint('percentot', 1);
		$args['value'] 		= $input->getFloat('value');
		$args['datestart'] 	= $input->getString('datestart');
		$args['datestop'] 	= $input->getString('datestop');
		$args['minvalue'] 	= $input->getFloat('minvalue');
		$args['group'] 		= $input->getUint('group');
		$args['id'] 		= $input->getInt('id', -1);
		
		if( empty($args['code']) ) {
			$args['code'] = cleverdine::generateSerialCode(12);
		}
		
		if( empty($args['datestart']) || empty($args['datestop']) ) {
			$args['datevalid'] = "";
		} else {
			$args['datevalid'] = cleverdine::createTimestamp($args['datestart'], 0, 0)."-".cleverdine::createTimestamp($args['datestop'], 0, 0);
		}
		
		if( $args["id"] == -1 ) {
			$q = "INSERT INTO `#__cleverdine_coupons` (`code`,`type`,`percentot`,`value`,`datevalid`,`minvalue`,`group`) VALUES (".
			$dbo->quote($args['code']).",".
			$args['type'].",".
			$args['percentot'].",".
			$args['value'].",".
			$dbo->quote($args['datevalid']).",".
			$args['minvalue'].",".
			$args['group']."
			);";
			$dbo->setQuery($q);
			$dbo->execute();
			$args['id'] = $dbo->insertid();
			if( $args['id'] > 0 ) {
				$mainframe->enqueueMessage(JText::_('VROPCOUPONCREATED'));
			}
		} else {
			$q = "UPDATE `#__cleverdine_coupons` SET 
			`code`=".$dbo->quote($args['code']).",
			`type`=".$args['type'].",
			`percentot`=".$args['percentot'].",
			`value`=".$args['value'].",
			`datevalid`=".$dbo->quote($args['datevalid']).",
			`minvalue`=".$args['minvalue'].",
			`group`=".$args['group']." 
			WHERE `id`=".$args['id']." LIMIT 1;";
			$dbo->setQuery($q);
			$dbo->execute();
			if( $dbo->getAffectedRows() > 0 ) {
				$mainframe->enqueueMessage(JText::_('VROPCOUPONUPDATED'));
			}
		}
		
		$close_page = $input->getBool('return');
		if( $close_page ) {
			$redirect_url = JRoute::_("index.php?option=com_cleverdine&task=opcoupons&Itemid=$itemid");
		} else {
			$redirect_url = JRoute::_('index.php?option=com_cleverdine&task=opmanagecoupon&id='.$args['id']."&Itemid=$itemid");
		}
		
		$mainframe->redirect($redirect_url);
		
	}

	function store_tkdash_prop() {
		JFactory::getApplication()->getUserStateFromRequest('tkdash.tab', 'tab', 1, 'uint');
		exit;
	}
	
	function change_reservation_code() {
		
		$input = JFactory::getApplication()->input;
		$dbo = JFactory::getDbo();
		
		$id 		= $input->getInt('id');
		$id_code 	= $input->getUint('new_code');
		$type 		= $input->getUint('type');
		
		$rescode = array( 'id' => 0, 'code' => '--', 'icon' => '' );
		
		$q = "SELECT `id`, `code`, `icon` FROM `#__cleverdine_res_code` WHERE `id`=$id_code AND `type`=$type LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$rescode = $dbo->loadAssoc();   
		}
		
		$q = "UPDATE `#__cleverdine_".($type == 1 ? '' : 'takeaway_')."reservation` SET `rescode`=".$rescode['id']." WHERE `id`=$id LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->execute();
		
		$html = '<div class="vrtrescode" id="vrtrescode'.$id.'" onClick="vrReservationStatusPressed('.$rescode['id'].','.$id.');">';
		$basic_html = '<span class="vroversight-resrow-status" id="vrlinestatus'.$id.'" onclick="vrReservationStatusPressed('.$rescode['id'].','.$id.');">';
		if( empty($rescode['icon']) ) {
			$html .= '<span class="vrtrescodelabel">'.$rescode['code'].'</span>';
			$basic_html .= $rescode['code'];
		} else {
			$html .= '<img src="'.JUri::root().'components'.DIRECTORY_SEPARATOR.'com_cleverdine'.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.$rescode['icon'].'" title="'.$rescode['code'].'"/>';
			$basic_html .= '<img src="'.JUri::root().'components'.DIRECTORY_SEPARATOR.'com_cleverdine'.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.$rescode['icon'].'" title="'.$rescode['code'].'"/>';
		}
		$html .= '</div>';
		$basic_html .= '</span>';
		
		echo json_encode(array(1, $html, $basic_html));
		die;     
	}
	
	function refresh_live_map() {

		$input = JFactory::getApplication()->input;
			
		$selectedRoomId = $input->getUint('selectedroom');
		if( empty($selectedRoomId) || $selectedRoomId == -1 ) {
			echo json_encode(array(0, ''));
			die;
		}    
		
		$dbo = JFactory::getDbo();
		
		$filters = array();
		$filters['date'] 	= $input->getString('date');
		$filters['hourmin'] = $input->getString('hourmin');
		$filters['people'] 	= $input->getUint('people');
		
		$rooms = array();
		
		$tables = array();
		$q = "SELECT `id`, `name`, `min_capacity`, `max_capacity`, `multi_res` FROM `#__cleverdine_table` WHERE `id_room`=".$selectedRoomId.";";
		$dbo->setQuery($q);
		$dbo->execute();
		if($dbo->getNumRows() > 0) {
			$tables = $dbo->loadAssocList();
		}
		
		$shifts = array();
		$continuos = array();
		
		if( !cleverdine::isContinuosOpeningTime() ) {
			$shifts = cleverdine::getWorkingShifts(1);
		} else {
			$continuos = array( cleverdine::getFromOpeningHour(), cleverdine::getToOpeningHour() );
		}
		
		$_hm_exp = explode(':', $filters['hourmin']);
		$min_int = cleverdine::getMinuteIntervals();
		
		$start_ts = cleverdine::createTimestamp($filters['date'], $_hm_exp[0], $_hm_exp[1]);
		
		if( $start_ts <= time() && time() <= $start_ts+($min_int+5)*60 ) {
			$_hm = explode(':', date('H:i'));
			if( $_hm[1] % $min_int != 0 ) {
				$_hm[1] -= ($_hm[1]%$min_int);
				if( $_hm[1] == 60 ) {
					$_hm[1] = 0;
					$_hm[0]++;
					if( $_hm[0] == 24 ) {
						$_hm[0] = 0;
					}
				}
			}
			
			$_hm_exp[0] = intval($_hm[0]);
			$_hm_exp[1] = intval($_hm[1]);
			if( cleverdine::isHourBetweenShifts($_hm_exp[0], $_hm_exp[1], 1) ) {
				$filters['hourmin'] = $_hm_exp[0].':'.$_hm_exp[1];
			} else {
				$_hm_exp = explode(":", $filters['hourmin']);  
			}
		}
		
		$filters['hour'] = $_hm_exp[0];
		$filters['min'] = $_hm_exp[1];
		
		$rows = array();
		$rows_multi = array();
		
		$q = cleverdine::getQueryFindTable($filters);
		$dbo->setQuery($q);
		$dbo->execute();
		// check at least one single table 
		$_app = array();
		if( $dbo->getNumRows() > 0 ) {
			$rows = $dbo->loadAssocList();
			$i = 0;
			foreach( $rows as $r ) {
				if( $r['multi_res'] == 0 ) {
					$_app[$i] = $r;
					$i++;
				}
			} 
		} 
		
		// get all shared table with at least 1 people
		$q = cleverdine::getQueryFindTableMultiRes($filters);
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$rows_multi = $dbo->loadAssocList();
		}
			
		$rows = cleverdine::mergeArrays($rows, $rows_multi);
		
		$shared_occurrency = array();
		$q = cleverdine::getQueryCountOccurrencyTableMultiRes($filters);
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$shared_occurrency = $dbo->loadAssocList();
		}
		
		$now = cleverdine::createTimestamp($filters['date'], $filters['hour'], $filters['min']);
		$avg = cleverdine::getAverageTimeStay()*60;
		
		$currentReservations = array();
		/*
		$q = "SELECT `r`.`id`, `r`.`rescode`, `c`.`code`, `c`.`icon` AS `code_icon`, `r`.`id_table`, `r`.`checkin_ts`,
		`r`.`purchaser_nominative` AS `custname`, `r`.`purchaser_mail` AS `custmail`, `r`.`purchaser_phone` AS `custphone` 
		FROM `#__cleverdine_reservation` AS `r` LEFT JOIN `#__cleverdine_res_code` AS `c` ON `r`.`rescode`=`c`.`id`
		WHERE `r`.`status`<>'REMOVED' AND `r`.`status`<>'CANCELLED' AND (".
		"( `r`.`checkin_ts` < " . $now . " AND " . $now . " < `r`.`checkin_ts`+" . $avg . " ) OR ".
			"( `r`.`checkin_ts` < " . ($now+$avg) . " AND " . ($now+$avg) . " < `r`.`checkin_ts`+" . $avg . " ) OR ".
			"( `r`.`checkin_ts` < " . $now . " AND " . ($now+$avg) . " < `r`.`checkin_ts`+" . $avg . " ) OR ".
			"( `r`.`checkin_ts` > " . $now . " AND " . ($now+$avg) . " > `r`.`checkin_ts`+" . $avg . " ) OR ".
			"( `r`.`checkin_ts` = " . $now . " AND " . ($now+$avg) . " = `r`.`checkin_ts`+" . $avg . " ) ".
		");";
		*/
		
		$q = "SELECT `r`.`id`, `r`.`rescode`, `c`.`code`, `c`.`icon` AS `code_icon`, `r`.`id_table`, `r`.`checkin_ts`,
		`r`.`purchaser_nominative` AS `custname`, `r`.`purchaser_mail` AS `custmail`, `r`.`purchaser_phone` AS `custphone` 
		FROM `#__cleverdine_reservation` AS `r` LEFT JOIN `#__cleverdine_res_code` AS `c` ON `r`.`rescode`=`c`.`id`
		WHERE `r`.`status`<>'REMOVED' AND `r`.`status`<>'CANCELLED' AND (
			( `r`.`checkin_ts` < $now AND $now < `r`.`checkin_ts`+IF(`r`.`stay_time`>0, `r`.`stay_time`*60, $avg) ) OR 
			( `r`.`checkin_ts` < $now+$avg AND $now+$avg < `r`.`checkin_ts`+IF(`r`.`stay_time`>0, `r`.`stay_time`*60, $avg) ) OR 
			( `r`.`checkin_ts` < $now AND $now+$avg < `r`.`checkin_ts`+IF(`r`.`stay_time`>0, `r`.`stay_time`*60, $avg) ) OR 
			( `r`.`checkin_ts` > $now AND $now+$avg > `r`.`checkin_ts`+IF(`r`.`stay_time`>0, `r`.`stay_time`*60, $avg) ) OR 
			( `r`.`checkin_ts` = $now AND $now+$avg = `r`.`checkin_ts`+IF(`r`.`stay_time`>0, `r`.`stay_time`*60, $avg) ) 
		);";

		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$currentReservations = $dbo->loadAssocList();
		}
		
		/////////////
		
		$code_icon_path = JUri::root().'components'.DIRECTORY_SEPARATOR.'com_cleverdine'.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR;
		
		$time_f = cleverdine::getTimeFormat();
		
		$now = time();
		
		// SETTING AVAILABLE TABLES
		for( $i = 0, $n = count($tables); $i < $n; $i++ ) {
			$found = 0;
			for( $j = 0, $m = count($rows); $j < $m && $found == 0; $j++ ) {
				$found = ( ( $rows[$j]['tid'] == $tables[$i]['id'] ) ? 1 : 0 );
			}
			
			$tables[$i]['available'] = $found;
		}
		// END SET
		
		// CALCULATING OCCURRENCY IN SHARED TABLE
		for( $i = 0, $n = count($tables); $i < $n; $i++ ) {
			$occurrency = 0;
			for( $j = 0, $m = count($shared_occurrency); $j < $m; $j++ ) {
				if( $shared_occurrency[$j]['id'] == $tables[$i]['id'] ) {
					$occurrency = $shared_occurrency[$j]['curr_capacity'];
					break;
				}
			}
			$tables[$i]['occurrency'] = $occurrency;
			
			$tables[$i]['id_reservation'] = array();
			$tables[$i]['rescode'] = 0;
			$tables[$i]['code'] = '';
			$tables[$i]['code_icon'] = '';
			$tables[$i]['customer'] = array('name' => '', 'mail' => '', 'phone' => ''); 
			
			$found = false;
			for( $j = 0; $j < count($currentReservations) && !$found; $j++ ) {
				if( $tables[$i]['id'] == $currentReservations[$j]['id_table'] ) {
					array_push( $tables[$i]['id_reservation'], $currentReservations[$j]['id'] );
					if( !$tables[$i]['multi_res'] ) {
						if( !empty($currentReservations[$j]['code']) ) {
							$tables[$i]['rescode'] = $currentReservations[$j]['rescode'];
							$tables[$i]['code'] = $currentReservations[$j]['code'];
							$tables[$i]['code_icon'] = $currentReservations[$j]['code_icon'];
						} else {
							$tables[$i]['code'] = '--';
						}
						
						$time_left = '';
						if( $currentReservations[$j]['checkin_ts'] <= $now && $now < $currentReservations[$j]['checkin_ts']+$avg ) {
							$time_left = $currentReservations[$j]['checkin_ts']+$avg-$now;
							if( $time_left < 60 ) {
								$time_left = JText::sprintf('VRRESTIMELEFTSEC', $time_left);
							} else {
								$time_left = JText::sprintf('VRRESTIMELEFTMIN', ceil($time_left/60));
							}
						}
						
						$tables[$i]['customer'] = array(
							'name' => $currentReservations[$j]['custname'],
							'mail' => $currentReservations[$j]['custmail'],
							'phone' => $currentReservations[$j]['custphone']
						);
						
						$tables[$i]['time'] = date($time_f, $currentReservations[$j]['checkin_ts']);
						$tables[$i]['timeleft'] = $time_left;
						
						$found = true;
					}
				}
			}
			
			$tables[$i]['code_html'] = "";
			if( !empty($tables[$i]['code']) ) {
				$tables[$i]['code_html'] = '<div class="vrtrescode" id="vrtrescode'.$tables[$i]['id_reservation'][0].'" onClick="vrReservationStatusPressed('.$tables[$i]['rescode'].','.$tables[$i]['id_reservation'][0].');">';
				if( empty($tables[$i]['code_icon']) ) {
					$tables[$i]['code_html'] .= '<span class="vrtrescodelabel">'.$tables[$i]['code'].'</span>';
				} else {
					$tables[$i]['code_html'] .= '<img src="'.$code_icon_path.$tables[$i]['code_icon'].'" title="'.$tables[$i]['code'].'"/>';
				}
				$tables[$i]['code_html'] .= '</div>';
			}          

			$tables[$i]['res_assoc'] = "";
			if( count($tables[$i]['id_reservation']) > 0 ) {
				$tables[$i]['res_assoc'] = implode(',', $tables[$i]['id_reservation']);
			}

			// ACTION COMMANDS
			
			$id = $tables[$i]['id'];
			
			$actionCommand = "";
			if( $tables[$i]['available'] == 1 ) {
				$newres_url = JRoute::_('index.php?option=com_cleverdine&task=quickres&date='.$filters['date'].'&hourmin='.$filters['hourmin'].'&people='.$filters['people'].'&idt='.$id, false);
				$actionCommand = '<a class="vrnewreslink" href="'.$newres_url.'" style="display: block;">'.JText::_('VRMAPNEWRESBUTTON').'</a>';
				if( $tables[$i]['occurrency'] != 0 ) {
					$details_url = 'index.php?option=com_cleverdine&task=editres';
					foreach( $tables[$i]['id_reservation'] as $idr ) {
						$details_url .= '&cid[]='.$idr;
					}
					$details_url = JRoute::_($details_url, true);
					// shared table > shows only the last reservation found
					$actionCommand .= '<a href="'.$details_url.'" class="vrtdetailslink" style="display: block;">'.JText::_('VRMAPDETAILSBUTTON').'</a>';
				}
			} else if( count($tables[$i]['id_reservation']) > 0 ) {
				if( $tables[$i]['occurrency'] == 0 && $tables[$i]['min_capacity'] <= $filters['people'] && $filters['people'] <= $tables[$i]['max_capacity'] ) {
					$actionCommand = '<a class="vrchangetablelink" href="javascript: void(0);" onClick="changeTableActionPressed('.$id.');" style="display: block;">'.JText::_('VRMAPCHANGETABLEBUTTON').'</a>';
				}
				$details_url = JRoute::_('index.php?option=com_cleverdine&task=editres&cid[]='.$tables[$i]['id_reservation'][0]);
				$actionCommand .= '<a href="'.$details_url.'" class="vrtdetailslink" style="display: block;">'.JText::_('VRMAPDETAILSBUTTON').'</a>';
			}
			
			$tables[$i]['action_command'] = $actionCommand;
			
			// TABLE CLASS
			
			if( $tables[$i]['available'] == 1 ) {
				if( $tables[$i]['occurrency'] == 0 ) {
					// FULL AVAILABLE
					$tables[$i]['class'] = "vrtgreen";
				} else {
					// NOT FULL AVAILABLE
					$tables[$i]['class'] = "vrtorange";
				}
			} else {
				// NOT AVAILABLE
				$tables[$i]['class'] = "vrtred";
			}
			
		}
		// END CALCULATE
		
		$now = cleverdine::createTimestamp($filters['date'], $filters['hour'], $filters['min']);
		$dt = explode('-', date('D-n-d-Y-'.$time_f, $now)); // week day - month num - day - year - time
		$num_text_arr = array("ONE", "TWO", "THREE", "FOUR", "FIVE", "SIX", "SEVEN", "EIGHT", "NINE", "TEN");
		$time_sel = mb_substr(JText::_('VRJQCAL'.strtoupper($dt[0])), 0, 3, 'UTF-8')." ".$dt[2].", ".JText::_('VRMONTH'.$num_text_arr[$dt[1]-1])." ".$dt[3]."|".$dt[4]."|x ".$filters['people'];
		
		// RESERVATIONS LIST
		
		$timestamp = time();
		
		$closest_res = array();
		$q = "SELECT `r`.`id`, `r`.`checkin_ts`, `r`.`people`, `r`.`purchaser_nominative`, `r`.`rescode`, `t`.`name` AS `tname`, `c`.`code` AS `codename`, `c`.`icon` AS `codeicon` 
		FROM `#__cleverdine_reservation` AS `r` LEFT JOIN `#__cleverdine_table` AS `t` ON `r`.`id_table`=`t`.`id` LEFT JOIN `#__cleverdine_res_code` AS `c` ON `r`.`rescode`=`c`.`id` 
		WHERE `r`.`status`='CONFIRMED' AND `r`.`checkin_ts`>=".($timestamp-$avg)." AND `checkin_ts`<=".($timestamp+$avg)." ORDER BY `checkin_ts`;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$closest_res = $dbo->loadAssocList();
		}
		
		$html_new_res = '';
		$html_upcoming_res = '';
		
		foreach( $closest_res as $r ) {
			if( $r['checkin_ts'] < $timestamp ) {
				$checkout = $r['checkin_ts']+$avg-$timestamp;
				if( $checkout < 60 ) {
					$checkout .= " ".JText::_('VRSECSHORT');
				} else {
					$checkout = ceil($checkout/60)." ".JText::_('VRMINSHORT'); 
				}
				
				$html_new_res .= '<div class="vroversight-reservation-row">
					<span class="vroversight-resrow-time">'.date($time_f, $r['checkin_ts']).'</span>
					<span class="vroversight-resrow-table">'.$r['tname'].'</span>
					<span class="vroversight-resrow-people">'.$r['people'].'</span>
					<span class="vroversight-resrow-people">'.$r['purchaser_nominative'].'</span>
					<span class="vroversight-resrow-checkout">'.$checkout.'</span>
					<span class="vroversight-resrow-status" id="vrlinestatus'.$r['id'].'" onClick="vrReservationStatusPressed('.$r['rescode'].','.$r['id'].');">'; 
						if( $r['rescode'] > 0 ) {
							if( !empty($r['codeicon']) ) {
								$html_new_res .= '<img src="'.$code_icon_path.$r['codeicon'].'" title="'.$r['codename'].'"/>';
							} else {
								$html_new_res .= $r['codename'];
							}
						} else {
							$html_new_res .= '--';
						}
				$html_new_res .= '</span>
				</div>';
			} else {
				$checkout = $r['checkin_ts']-$timestamp;
				if( $checkout < 60 ) {
					$checkout .= " ".JText::_('VRSECSHORT');
				} else {
					$checkout = ceil($checkout/60)." ".JText::_('VRMINSHORT'); 
				}
				
				$html_upcoming_res .= '<div class="vroversight-reservation-row">
					<span class="vroversight-resrow-time">'.date($time_f, $r['checkin_ts']).'</span>
					<span class="vroversight-resrow-table">'.$r['tname'].'</span>
					<span class="vroversight-resrow-people">'.$r['people'].'</span>
					<span class="vroversight-resrow-people">'.$r['purchaser_nominative'].'</span>
					<span class="vroversight-resrow-checkout">'.$checkout.'</span>
					<span class="vroversight-resrow-status" id="vrlinestatus'.$r['id'].'" onClick="vrReservationStatusPressed('.$r['rescode'].','.$r['id'].');">'; 
						if( $r['rescode'] > 0 ) {
							if( !empty($r['codeicon']) ) {
								$html_upcoming_res .= '<img src="'.$code_icon_path.$r['codeicon'].'" title="'.$r['codename'].'"/>';
							} else {
								$html_upcoming_res .= $r['codename'];
							}
						} else {
							$html_upcoming_res .= '--';
						}
				$html_upcoming_res .= '</span>
				</div>';
			}
		}
		
		echo json_encode(array(1, $tables, $filters['hourmin'], $time_sel, array($html_new_res, $html_upcoming_res)));
		exit;
		
	}

	function change_tab_res_list() {
		$tab = JFactory::getApplication()->input->getUint('tab');
		
		$session = JFactory::getSession();
		$session->set('vrlistrestab', $tab);
		
		die;
	}
	
	// QUICK RESERVATION MODULE
	
	function quickres_find_table() {
		
		$input = JFactory::getApplication()->input;
		$session = JFactory::getSession();
		
		$session_lifetime = 15*60;
		
		jimport('joomla.application.module.helper');
		$module = JModuleHelper::getModule('mod_cleverdine_quickres');
		$params = json_decode($module->params);
		if( !empty($params) ) {
			$session_lifetime = $params->session_lifetime*60;
		}
		
		$user_session = intval($session->get('vr-quickres-session', '', 'quik-res-mod'));
		if( !empty($user_session) && time()-$session_lifetime < $user_session ) {
			echo json_encode(array(0, JText::sprintf('VRQRMOD_SPAMATTEMPT', ceil(($session_lifetime-(time()-$user_session))/60) )));
			exit;
		}
		
		$dbo = JFactory::getDbo();
		
		$args = array();
		$args['date'] 		= $input->getString('date');
		$args['hourmin'] 	= $input->getString('hourmin');
		$args['people'] 	= $input->getUint('people');

		list( $args['hour'], $args['min'] ) = explode(":", $args['hourmin']);
		$args['ts'] = cleverdine::createTimestamp($args['date'], $args['hour'], $args['min']);
		
		// VALIDATE ARGS
		$resp = cleverdine::isRequestReservationValid($args);
			
		if( $resp != 0 ) {
			echo json_encode(array(0, JText::_(cleverdine::getResponseFromReservationRequest($resp)) ));
			exit;
		}
		
		if( !cleverdine::isReservationsAllowedOn($args['ts']) ) {
			echo json_encode(array(0, JText::_('VRNOMORERESTODAY')));
			exit;
		}
		
		$closed = false;
		$ignore_cd = false;
		
		$shifts = array();
		$special_days = cleverdine::getSpecialDaysOnDate($args, 1);

		if( !cleverdine::isContinuosOpeningTime() ) {
			$shifts = cleverdine::getWorkingShifts(1);
			
			if( $special_days != -1 && count($special_days) > 0 ) {
				$shifts = cleverdine::getWorkingShiftsFromSpecialDays( $shifts, $special_days, 1 );
			}
			
			$closed = true;
			$hour_full = $args['hour']*60+$args['min'];
			for( $i = 0; $i < count($shifts) && $closed; $i++ ) {
				$closed = !( $shifts[$i]['from'] <= $hour_full && $hour_full <= $shifts[$i]['to'] );
			}
		} 
		
		if( $special_days != -1 ) {
			if( count( $special_days ) == 0 ) {
				//$ignore_cd = true;
			} else {
				for( $i = 0, $n = count($special_days); $i < $n && !$ignore_cd; $i++ ) {
					$ignore_cd = $special_days[$i]['ignoreclosingdays'];
				}

				if( $special_days[0]['peopleallowed'] != -1 && cleverdine::getPeopleAt($args['ts'])+$args['people'] > $special_days[0]['peopleallowed'] ) {
					echo json_encode(array(0, JText::_('VRRESNOSINGTABLEFOUND')));
					exit;
				}
			}
		}

		if( !$ignore_cd && !$closed ) {
			$closed = cleverdine::isClosingDay($args);
		}
		
		if( $closed == true ) {
			echo json_encode(array(0, JText::_('VRSEARCHDAYCLOSED')));
			exit;
		}
		// END VALIDATION
		
		// SEARCH TABLE
		$rows = array();
		$rows_multi = array();
		$attempt = 1;
		
		$hints = array();
		
		$q = cleverdine::getQueryFindTable($args);
		$dbo->setQuery($q);
		$dbo->execute();
		
		// check at least one single table 
		$_app = array();
		if( $dbo->getNumRows() > 0 ) {
			$rows = $dbo->loadAssocList();
			$i = 0;
			foreach( $rows as $r ) {
				if( $r['multi_res'] == 0 ) {
					$_app[$i] = $r;
					$i++;
				}
			} 
		} 
		
		// get all shared table with at least 1 people
		$q = cleverdine::getQueryFindTableMultiRes($args);
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$rows_multi = $dbo->loadAssocList();
		}
		$rows = cleverdine::mergeArrays($rows, $rows_multi);
		
		if( count( $_app ) == 0 ) {
			$attempt++;
			
			for( $i = 0, $n = count($rows); $i < $n; $i++ ) {
				$_app[$i] = $rows[$i];
			}
			
			if( count( $_app ) == 0 ) {
				$attempt++;
			}
			
			// ELABORATE HINTS
			$q = cleverdine::getQueryAllReservationsOnDate($args);
			$dbo->setQuery($q);
			$dbo->execute();
			
			if( $dbo->getNumRows() > 0 ) {
				$_h = $dbo->loadAssocList();
				
				// Delimiters of shift _d[0] = start shift, _d[1] = end shift
				$_d      = cleverdine::getOpeningTimeDelimiters($args);
				// Average time of stay
				$_avg    = cleverdine::getAverageTimeStay();
				// Counter of table with same ID: if 0 -> compare with _d[0]
				// _cont = 0 when current id table != next id table -> compare with _d[1]
				$_cont   = 0;
				
				for( $i = 0, $n = count($_h); $i < $n; $i++ ) {

					// evaluate stay time for current reservation
					if (empty($_h[$i]['stay_time'])) {
						$_h[$i]['stay_time'] = $_avg;
					}
					$_h[$i]['stay_time'] *= 60;

					$_argv = array();
					if( $_cont == 0 ) {
						$_argv = cleverdine::getAvailableHoursFromInterval( $_d[0], $_h[$i]['checkin_ts'] );
					} else if( $_h[$i]['idt'] == $_h[$i-1]['idt'] ) {
						$_argv = cleverdine::getAvailableHoursFromInterval( $_h[$i-1]['checkin_ts']+$_h[$i-1]['stay_time'], $_h[$i]['checkin_ts'] );
					}
					
					foreach( $_argv as $val ) {
						$hints[count($hints)] = $val;
					}
					
					if( $i == $n-1 || $_h[$i]['idt'] != $_h[$i+1]['idt'] ) {
						$_cont = 0;
						$_argv = cleverdine::getAvailableHoursFromInterval( $_h[$i]['checkin_ts']+$_h[$i]['stay_time'], $_d[1] );
						
						foreach( $_argv as $val ) {
							$hints[count($hints)] = $val;
						}
					} else {
						$_cont++;
					}
				}
			}
		}
		
		sort( $hints );
		
		$bef_h = array( -1, -1 );
		$aft_h = array( -1, -1 );
			
		// FIND NEAREST BEFORE HOURS
		$i = 0;
		$n = count( $hints );
		while( $i < $n && $hints[$i] < $args['ts'] ) {
			if( $bef_h[1] != $hints[$i] ) {
				$bef_h[0] = $bef_h[1];
				$bef_h[1] = $hints[$i];
			}
			$i++;
		}
		
		// FIND NEAREST AFTER HOURS
		$i = count( $hints )-1;
		while( $i >= 0 && $hints[$i] > $args['ts'] ) {
			if( $aft_h[1] != $hints[$i] ) {
				$aft_h[0] = $aft_h[1];
				$aft_h[1] = $hints[$i];
			}
			$i--;
		}
		
		$free_rooms_id = array();
		foreach( $rows as $r ) {
			if( !in_array($r['rid'], $free_rooms_id) ) {
				array_push($free_rooms_id, $r['rid']);
			}
		}
		
		$rooms_array = array();
		if( count($free_rooms_id) > 0 ) {
			$q = "SELECT `id`, `name` FROM `#__cleverdine_room` WHERE `id` IN (".implode(",", $free_rooms_id).") ORDER BY `ordering`;";
			$dbo->setQuery($q);
			$dbo->execute();
			if( $dbo->getNumRows() > 0 ) {
				$rooms_array = $dbo->loadAssocList();
				
				for( $i = 0; $i < count($rooms_array); $i++ ) {
					$found = false;
					for( $j = 0; $j < count($rows) && !$found; $j++ ) {
						if( $rooms_array[$i]['id'] == $rows[$j]['rid'] ) {
							$found = true;
							$rooms_array[$i]['tid'] = $rows[$j]['tid'];
						}
					}
				}
				
			}
		} else {
			$time_format = cleverdine::getTimeFormat();
			
			$app = array_merge($bef_h, $aft_h);
			sort($app);
			
			$hints_formatted = array();
			foreach( $app as $t ) {
				if( $t != -1 ) {
					array_push( $hints_formatted, date($time_format, $t) );
				}
			}

			if( count($hints_formatted) > 0 ) {
				echo json_encode(array(-1, $hints_formatted));
			} else {
				echo json_encode(array(0, JText::_('VRRESNOSINGTABLEFOUND')));
			}
			exit;
		}
		///////////////
		
		$date_str = JText::sprintf('VRQRMOD_DATETIMESTR', date(cleverdine::getDateFormat(), $args['ts']), date(cleverdine::getTimeFormat(), $args['ts']), $args['people']);
		
		$table = array();
		if( count($rooms_array) == 1 ) {
			$rooms_array[0]['str'] = JText::sprintf('VRQRMOD_ROOMSELSTR', $rooms_array[0]['name']); 
		}
		$table = array( "rid" => $rooms_array[0]['id'], "tid" => $rooms_array[0]['tid'] );
		
		$session->set('vrqr-reservation-details', array(
			"args" => $args,
			"rooms" => $rooms_array,
			"table" => $table,
		));
		
		echo json_encode(array(1, $date_str, $rooms_array));
		exit;
		
	}
	
	function quickres_select_room() {
		$id_room = JFactory::getApplication()->input->getInt('id_room');
		
		$session = JFactory::getSession();
		$search = $session->get('vrqr-reservation-details', '');
		
		if( empty($search) ) {
			echo json_encode(array(0));
			exit;
		}
		
		for( $i = 0; $i < count($search['rooms']); $i++ ) {
			if( $search['rooms'][$i]['id'] == $id_room ) {
				$search['table']['rid'] = $search['rooms'][$i]['id'];
				$search['table']['tid'] = $search['rooms'][$i]['tid'];
				$session->set('vrqr-reservation-details', $search);
				echo json_encode(array( 1, JText::sprintf('VRQRMOD_ROOMSELSTR', $search['rooms'][$i]['name']) ));
				exit;
			}
		}
		
		echo json_encode(array(0));
		exit;
		
	}
	
	function quickres_register_reservation() {

		$app = JFactory::getApplication();
		$input = $app->input;
		$dbo = JFactory::getDbo();
		
		$_cf = array();
		$p_name = $p_mail = $p_phone = $p_prefix = $p_country_code = "";
		
		$q = "SELECT * FROM `#__cleverdine_custfields` WHERE `group`=0 AND `type`<>'separator' ORDER BY `ordering` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() ) {
			$_cf = $dbo->loadAssocList();
		}
		
		$cust_req = array();
		
		foreach( $_cf as $_app ) {
			$cust_req[$_app['name']] = $input->get('vrcf'.$_app['id'], '', 'string');

			if( !cleverdine::isCustomFieldValid($_app, $cust_req[$_app['name']]) ) {
				
				echo json_encode(array(0, JText::_('VRERRINSUFFCUSTF')));
				exit;

			} else if( $_app['rule'] == VRCustomFields::NOMINATIVE ) {
				
				if( !empty($p_name) ) {
					$p_name .= ' ';
				}
				$p_name .= $cust_req[$_app['name']];

			} else if( $_app['rule'] == VRCustomFields::EMAIL ) {

				$p_mail = $cust_req[$_app['name']];

			} else if( $_app['rule'] == VRCustomFields::PHONE_NUMBER ) {

				$p_phone = $cust_req[$_app['name']];
				$country_key = $input->get('vrcf'.$_app['id'].'_prfx', '', 'string');
				if( !empty($country_key) ) {
					$country_key = explode('_', $country_key);
					$q = "SELECT * FROM `#__cleverdine_countries` WHERE `country_2_code`=".$dbo->quote($country_key[1])." LIMIT 1;";
					$dbo->setQuery($q);
					$dbo->execute();
					if( $dbo->getNumRows() > 0 ) {
						$country = $dbo->loadAssoc();
						$p_prefix = $country['phone_prefix'];
						$p_country_code = $country['country_2_code'];
					}
				}
				$p_phone = str_replace(" ", "", $cust_req[$_app['name']]);

			}
		}
		
		$session = JFactory::getSession();
		$search = $session->get('vrqr-reservation-details', '');
		
		if( empty($search) ) {
			echo json_encode(array(0, JText::_('VRRESERVATIONREQUESTMSG1') ));
			exit;
		}
		
		$args = $search['args'];
		$args['table'] = (!empty($search['table']['tid']) ? $search['table']['tid'] : -1);
		
		// VALIDATE ARGS
		$resp = cleverdine::isRequestReservationValid($args);
			
		if( $resp != 0 ) {
			echo json_encode(array(0, JText::_(cleverdine::getResponseFromReservationRequest($resp)) ));
			exit;
		}
		
		if( !cleverdine::isReservationsAllowedOn(cleverdine::createTimestamp($args['date'], $args['hour'], $args['min'])) ) {
			echo json_encode(array(0, JText::_('VRNOMORERESTODAY')));
			exit;
		}
	
		$closed = false;
		$ignore_cd = false;

		$shifts = array();
		$special_days = cleverdine::getSpecialDaysOnDate($args, 1);
		
		if( !cleverdine::isContinuosOpeningTime() ) {
			$shifts = cleverdine::getWorkingShifts(1);
			
			if( $special_days != -1 && count($special_days) > 0 ) {
				$shifts = cleverdine::getWorkingShiftsFromSpecialDays( $shifts, $special_days, 1 );
			}
			
			$closed = true;
			$hour_full = $args['hour']*60+$args['min'];
			for( $i = 0; $i < count($shifts) && $closed; $i++ ) {
				$closed = !( $shifts[$i]['from'] <= $hour_full && $hour_full <= $shifts[$i]['to'] );
			}
		} 
		
		if( $special_days != -1 ) {
			
			if( count( $special_days ) == 0 ) {
				//$ignore_cd = true;
			} else {
				for( $i = 0, $n = count($special_days); $i < $n && !$ignore_cd; $i++ ) {
					$ignore_cd = $special_days[$i]['ignoreclosingdays'];
				}
			}
		}

		if( !$ignore_cd && !$closed ) {
			$closed = cleverdine::isClosingDay($args);
		}
		
		if( $closed == true ) {
			echo json_encode(array(0, JText::_('VRSEARCHDAYCLOSED')));
			exit;
		}
		// END VALIDATION
		
		// VALIDATE RESERVATION
		$q = cleverdine::getQueryTableJustReserved($args);
		$dbo->setQuery($q);
		$dbo->execute();
		$valid = ($dbo->getNumRows() > 0);
		
		if( !$valid ) {
			echo json_encode(array(0, JText::_('VRERRTABNOLONGAV')));
			exit;
		}
		// END VALIDATION
		
		$status = cleverdine::getDefaultStatus();
		
		$locked_until = time() + cleverdine::getTablesLockedTime()*60;
		
		$created_by = -1;
		$curr_user = JFactory::getUser();
		if( !$curr_user->guest ) {
			$created_by = $curr_user->id;
		}
		
		$sid = cleverdine::generateSerialCode(16);
		$conf_key = cleverdine::generateSerialCode(12);
		
		// INSERT RESERVATION
		$q = "INSERT INTO `#__cleverdine_reservation` ".
		"(`sid`,`conf_key`,`id_table`,`checkin_ts`,`people`,`purchaser_nominative`,`purchaser_mail`,`purchaser_phone`,`purchaser_prefix`,`purchaser_country`,`custom_f`,`status`,`locked_until`,`created_on`,`created_by`,`id_user`) ".
		"VALUES( ".
		$dbo->quote($sid).",".
		$dbo->quote($conf_key).",".
		$args['table'].",".
		cleverdine::createTimestamp($args['date'], $args['hour'], $args['min'] ).",".
		$args['people'].",".
		$dbo->quote( $p_name ).",".
		$dbo->quote( $p_mail ).",".
		$dbo->quote( $p_phone ).",".
		$dbo->quote( $p_prefix ).",".
		$dbo->quote( $p_country_code ).",".
		$dbo->quote( json_encode($cust_req) ).",".
		$dbo->quote($status).",".
		$locked_until.",".
		time().",".
		$created_by.",".
		$created_by.
		");";
		
		$dbo->setQuery($q);
		$dbo->execute();
		$lid = $dbo->insertid();
		
		if( $lid <= 0 ) {
			echo json_encode(array(0, JText::_('VRINSERTRESERVATIONERROR')));
			exit;
		}
		
		// SAVE USER DATA
		if( cleverdine::userIsLogged() ) {
			$id_customer = -1;

			// prepare customer plugin

			$customer_arr = array(
				'billing_name' 				=> $p_name,
				'billing_mail' 				=> $p_mail,
				'billing_phone' 			=> $p_phone,
				'billing_phone_prefix' 		=> $p_prefix,
				'country_code' 	=> $p_country_code,
				'jid' 			=> $curr_user->id
			);

			$options = array(
				'alias' 	=> 'com_cleverdine',
				'version' 	=> cleverdine_SOFTWARE_VERSION,
				'admin' 	=> $app->isAdmin(),
				'call' 		=> __FUNCTION__
			);

			JPluginHelper::importPlugin('e4j');
			$dispatcher = JEventDispatcher::getInstance();
			
			//
			
			$curr_user = JFactory::getUser();

			$q = "SELECT `id` FROM `#__cleverdine_users` WHERE `jid`=".$curr_user->id." LIMIT 1;";

			$dbo->setQuery($q);
			$dbo->execute();

			if( $dbo->getNumRows() > 0 ) {

				$id_customer = $dbo->loadResult();
				
				$q = "UPDATE `#__cleverdine_users` SET 
				`fields`=".$dbo->quote(json_encode($cust_req)).",
				`billing_name`=".$dbo->quote($customer_arr['billing_name']).",
				`billing_mail`=".$dbo->quote($customer_arr['billing_mail']).",
				`billing_phone`=".$dbo->quote($customer_arr['billing_phone']).",
				`country_code`=".$dbo->quote($customer_arr['country_code'])." 
				WHERE `jid`=".intval($customer_arr['jid'])." LIMIT 1;";

				$dbo->setQuery($q);
				$dbo->execute();

				if( $dbo->getAffectedRows() ) {
					// trigger plugin -> customer update
					$dispatcher->trigger('onCustomerUpdate', array(&$customer_arr, &$options));
				}

			} else {

				$q = "INSERT INTO `#__cleverdine_users` (`jid`,`fields`,`billing_name`,`billing_mail`,`billing_phone`,`country_code`) VALUES (".
				intval($customer_arr['jid']).",".
				$dbo->quote(json_encode($cust_req)).",".
				$dbo->quote($customer_arr['billing_name']).",".
				$dbo->quote($customer_arr['billing_mail']).",".
				$dbo->quote($customer_arr['billing_phone']).",".
				$dbo->quote($customer_arr['country_code']).
				");";

				$dbo->setQuery($q);
				$dbo->execute();
				
				if( ($id_customer = $dbo->insertid()) ) {
					// trigger plugin -> customer creation
					$dispatcher->trigger('onCustomerInsert', array(&$customer_arr, &$options));
				}

			}

			if( $id_customer > 0 ) {
				$q = "UPDATE `#__cleverdine_reservation` SET `id_user`=".$id_customer." WHERE `id`=$lid LIMIT 1;";
				$dbo->setQuery($q);
				$dbo->execute();
			}
		}
		// END USER
		
		$send_when = cleverdine::getSendMailWhen();
		
		// SEND EMAILS
		$order_details = cleverdine::fetchOrderDetails($lid);
		if( $send_when['admin'] == 2 || $send_when['operator'] == 2 || $order_details['status'] == 'CONFIRMED' ) {
			cleverdine::sendAdminEmail($order_details);
		}
		if( $send_when['customer'] != 0 && ( $send_when['customer'] == 2 || $order_details['status'] == 'CONFIRMED' ) ) {
			cleverdine::sendCustomerEmail($order_details);
		}
		// END SEND EMAILS
		
		// SEND SMS NOTIFICATIONS
		// phone_number, order_details, action_restaurant (0)
		if( $status == 'CONFIRMED' ) {
			cleverdine::sendSmsAction( $p_prefix.$p_phone, $order_details, 0 );
		}
		// END SMS
		
		$custom_fields_summary = (!empty($p_name) ? $p_name." " : "").(!empty($p_mail) ? $p_mail." " : "").( !empty($p_phone) ? $p_phone : "");
		$url = JRoute::_('index.php?option=com_cleverdine&view=order&ordnum='.$lid.'&ordkey='.$sid.'&ordtype=0', false);
		
		$session->set('vrqr-reservation-details', '');
		$session->set('vr-quickres-session', time(), 'quik-res-mod');
		
		echo json_encode(array(1, $custom_fields_summary, $url));
		exit;
		
	}

	// DELIVERY MAP MODULE

	function get_location_delivery_info() {

		$input = JFactory::getApplication()->input;

		$lat 	= $input->getFloat('lat');
		$lng 	= $input->getFloat('lng');
		$zip 	= $input->getString('zip');
		$addr 	= $input->get('address', array(), 'array');

		$area = null;

		if( ($has = cleverdine::hasDeliveryAreas()) ) {
			$area = cleverdine::getDeliveryAreaFromCoordinates($lat, $lng, $zip);
		}

		$session = JFactory::getSession();

		$response = new stdClass;
		$response->status = 0;

		if( $area === null && $has ) {

			$response->error = JText::_('VRTKDELIVERYLOCNOTFOUND');

			$session->clear('delivery_address', 'vre');
			
		} else {

			$curr_symb 	= cleverdine::getCurrencySymb(true);
			$symb_pos 	= cleverdine::getCurrencySymbPosition(true);

			$response->status = 1;

			$response->latitude	 	= $lat;
			$response->longitude 	= $lng;
			$response->zip 			= $zip;
			$response->address 		= $addr;

			$response->area = new stdClass;

			if( $has ) {

				$response->area->name 			= $area['name'];
				$response->area->charge 		= (float)$area['charge'];
				$response->area->chargeLabel 	= ($area['charge'] > 0 ? '+ ' : '').cleverdine::printPriceCurrencySymb($area['charge'], $curr_symb, $symb_pos);
				$response->area->minCost 		= (float)$area['min_cost'];
				$response->area->minCostLabel 	= cleverdine::printPriceCurrencySymb($area['min_cost'], $curr_symb, $symb_pos);

			} else {

				$response->area->name 			= '';
				$response->area->charge 		= 0.0;
				$response->area->chargeLabel 	= cleverdine::printPriceCurrencySymb(0, $curr_symb, $symb_pos);
				$response->area->minCost 		= 0.0;
				$response->area->minCostLabel 	= cleverdine::printPriceCurrencySymb(0, $curr_symb, $symb_pos);

			}

			// FILL FULL CHARGE LABEL
			$base_charge = cleverdine::getTakeAwayDeliveryServiceAddPrice();
			$percent_tot = cleverdine::getTakeAwayDeliveryServicePercentOrTotal();

			if( $percent_tot == 1 ) {
				$response->area->fullChargeLabel = $base_charge."%".($response->area->charge != 0 ? " ".$response->area->chargeLabel : "");
			} else {
				$base_charge += $response->area->charge;
				$response->area->fullChargeLabel = ($base_charge > 0 ? '+ ' : '').cleverdine::printPriceCurrencySymb($base_charge, $curr_symb, $symb_pos);
			}

			$session->set('delivery_address', $response, 'vre');

		}

		echo json_encode($response);
		exit;

	}

	/**
	 * ##########################
	 * #     APIs End-Point     #
	 * ##########################
	 * 
	 * This function is the end-point to dispatch events requested from external connections.
	 * It is required to specify all the following values:
	 *
	 * @param 	string 	username 		The username for login.
	 * @param 	string 	password 		The password for login.
	 * @param 	string 	event 			The name of the event to dispatch.
	 * 
	 * It is also possible to pre-send certain arguments to dispatch within the event:
	 *
	 * @param 	array 	args 			The arguments of the event (optional).
	 *									All the specified values are cleansed with string filtering.
	 *
	 * @return 	string 					In case of error it is returned a JSON string with the code (errcode) 
	 * 									and the message of the error (error).
	 *
	 *									In case of success the result may vary on the event dispatched.
	 */

	function apis() {

		$input = JFactory::getApplication()->input;

		// instantiate APIs Framework
		// leave constructor empty to select default plugins folder: 
		// components/com_cleverdine/helpers/library/apislib/apis/plugins/
		$apis = UIFactory::getApis();

		// check if APIs are allowed, otherwise disable all
		if (!$apis->isEnabled()) {
			// raise error in echo and exit automatically
			ErrorAPIs::raise(403, 'This resource is forbidden!');
			// the code above is equivalent to:
			// $err = ErrorAPIs::raise(403, 'This resource is forbidden!', false);
			// echo $err->toJSON();
			// exit;
		}

		// flush stored APIs logs
		cleverdine::flushApiLogs();

		// get credentials
		$username 	= $input->getString('username');
		$password 	= $input->getString('password');
		// get event to dispatch
		$event		= $input->get('event');
		// get event arguments
		$args 		= $input->get('args', array(), 'string');

		// create a Login for this user
		$login = new LoginAPIs($username, $password, $input->server->get('REMOTE_ADDR'));

		// do login
		if (!$apis->connect($login)) {
			// user is not authorized to login
			// display the reason and stop the process
			echo $apis->getError()->toJSON();
			exit;
		}

		// user correctly logged in

		// dispatch the event
		if (!$apis->trigger($event, $args)) {
			// event error thrown
			// display the reason and stop the flow
			echo $apis->getError()->toJSON();
			exit;
		}

		// disconnect the user on success
		$apis->disconnect();

		exit;
	}

}
