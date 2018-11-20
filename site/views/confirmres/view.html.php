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
class cleverdineViewconfirmres extends JViewUI {

	/**
	 * Restaurants view display method
	 * @return void
	 */
	function display($tpl = null) {
		
		cleverdine::load_css_js();
		cleverdine::load_complex_select();

		$mainframe 	= JFactory::getApplication();
		$input 		= $mainframe->input;
		$dbo 		= JFactory::getDbo();
		
		$args 		= $input->get('args', array(), 'array');
		$coupon_key = $input->get('couponkey', '', 'string');

		$cust_f 	= array();
		$payments 	= array();
		$any_coupon = 0;
		
		$q = "SELECT * FROM `#__cleverdine_gpayments` WHERE `published`=1 AND `group`<>2 ORDER BY `ordering` ASC;"; // group 2 : only take-away
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$payments = $dbo->loadAssocList();
		}
		
		$q = "SELECT * FROM `#__cleverdine_custfields` WHERE `group`=0 ORDER BY `ordering` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() ) {
			$cust_f = $dbo->loadAssocList();
		}
		
		if( strlen( $coupon_key ) > 0 ) {
			$q = "SELECT * FROM `#__cleverdine_coupons` WHERE `code`=".$dbo->quote($coupon_key)." LIMIT 1;";
			$dbo->setQuery($q);
			$dbo->execute();
			if( $dbo->getNumRows() ) {
				$coupon = $dbo->loadAssoc();
				
				if( cleverdine::validateCoupon( $coupon, $args['people'] ) ) {
					
					$session = JFactory::getSession();
					$session->set('vr_coupon_data',$coupon);
					
					$mainframe->enqueueMessage(JText::_('VRCOUPONFOUND'));
				
				} else {
					$mainframe->enqueueMessage(JText::_('VRCOUPONNOTVALID'), 'error');
				}
				
			} else {
				$mainframe->enqueueMessage(JText::_('VRCOUPONNOTVALID'), 'error');
			}
		}
		
		$q = "SELECT COUNT(`id`) AS `count` FROM `#__cleverdine_coupons`;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$_app = $dbo->loadAssocList();
			$any_coupon = (( $_app[0]['count'] > 0 ) ? 1 : 0 );
		}
		
		$user = array();
		if( cleverdine::userIsLogged() ) {
			$curr_user = JFactory::getUser();
			$q = "SELECT * FROM `#__cleverdine_users` WHERE `jid`=".$curr_user->id." LIMIT 1;";
			$dbo->setQuery($q);
			$dbo->execute();
			if( $dbo->getNumRows() > 0 ) {
				$user = $dbo->loadAssoc();
			}
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

		$this->paymentsTranslations = &$payments_translations;
		$this->customfTranslations 	= &$customf_translations;
		
		$this->args 		= &$args;
		$this->customFields = &$cust_f;
		$this->payments 	= &$payments;
		$this->anyCoupon 	= &$any_coupon;
		$this->user 		= &$user;
		$this->countries 	= &$countries;
		
		// Display the template
		parent::display($tpl);

	}
	
}
?>