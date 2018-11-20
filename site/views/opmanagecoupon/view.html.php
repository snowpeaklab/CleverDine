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
class cleverdineViewopmanagecoupon extends JViewUI {
	
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
		
		if( $operator === false || empty($operator['can_login']) || $operator['manage_coupon'] != 2 )  {
			$mainframe->enqueueMessage(JText::_('VRLOGINUSERNOTFOUND'), 'error');
			$mainframe->redirect(JRoute::_('index.php?option=com_cleverdine&view=oversight'));
			exit;
		}
		
		////// MANAGEMENT //////
		
		cleverdine::load_css_js();
		
		$id = $input->get('id', 0, 'uint');

		$coupon = null;
		
		if( !empty($id) ) {

			$q = $dbo->getQuery(true);

			$q->select('*')
				->from($dbo->quoteName('#__cleverdine_coupons'))
				->where($dbo->quoteName('id') . ' = ' . $id);

			$dbo->setQuery($q, 0, 1);
			$dbo->execute();

			if( $dbo->getNumRows() > 0 ) {
				$coupon = $dbo->loadAssoc();

				if( $operator['group'] != 0 && $operator['group']-1 != $coupon['group'] ) {
					$coupon = null;
				}
			}

		}

		if( $coupon === null ) {
			$coupon = $this->initNewCoupon();
		}
		
		$this->operator = &$operator;
		$this->coupon 	= &$coupon;

		// Display the template
		parent::display($tpl);

	}

	private function initNewCoupon() {
		return array( "id" => -1, "code" => cleverdine::generateSerialCode(12), "type" => 1, "value" => 0, "datevalid" => "", "percentot" => 1, "minvalue" => 1, 'group' => 0);
	} 

}
?>