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

class cleverdineViewccdetails extends JViewUI {
	
	/**
	 * Restaurants view display method
	 * @return void
	 */
	function display($tpl = null) {
		
		RestaurantsHelper::load_css_js();

		$input 	= JFactory::getApplication()->input;
		$dbo 	= JFactory::getDbo();

		// request
		
		$oid 	= $input->get('oid', 0, 'uint');
		$tid 	= $input->get('tid', 0, 'uint');
		
		$back 	= $input->get('back');

		$rm_hash = $input->get('rmhash', '', 'string');

		$real_hash = $this->checkForRemove($oid, $tid, $rm_hash, $dbo);

		//
		
		$credit_card = null;

		$table = 'cleverdine_'.($tid == 0 ? '' : 'takeaway_').'reservation';

		$q = "SELECT `checkin_ts`, `cc_details` FROM `#__$table` WHERE `id`=$oid LIMIT 1";
		$dbo->setQuery($q);
		$dbo->execute();

		if( !$dbo->getNumRows() ) {
			// order does not exist
			exit(JText::_('VRNOMATCHES'));
		}

		$order = $dbo->loadAssoc();

		if( !strlen($credit_card = $order['cc_details']) ) {
			// credit card details empty
			exit(JText::_('VRNOMATCHES'));
		}

		cleverdine::loadCryptLibrary();

		$cipher = SecureCipher::getInstance();

		$credit_card = json_decode($cipher->safeEncodingDecryption($credit_card));

		//
		
		$this->creditCard 	= &$credit_card;
		$this->order 		= &$order;

		$this->oid 		= &$oid;
		$this->tid 		= &$tid;
		$this->rmHash 	= &$real_hash;

		$this->back = &$back;

		// Display the template
		parent::display($tpl);
		
	}

	private function checkForRemove($oid, $tid, $rm_hash, $dbo = null) {
		if( $dbo === null ) {
			$dbo = JFactory::getDbo();
		}

		$real_hash = md5($oid.':'.$tid);

		if( !empty($rm_hash) && !strcmp($rm_hash, $real_hash) ) {
			
			$table = 'cleverdine_'.($tid == 0 ? '' : 'takeaway_').'reservation';

			$q = "UPDATE `#__$table` SET `cc_details`='' WHERE `id`=$oid LIMIT 1;";
			$dbo->setQuery($q);
			$dbo->execute();

			if( $dbo->getAffectedRows() ) {
				exit(JText::_('VRCREDITCARDREMOVED'));
			}

		}

		return $real_hash;

	}
}
?>