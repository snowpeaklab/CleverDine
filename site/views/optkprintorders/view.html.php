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
class cleverdineViewoptkprintorders extends JViewUI {
	
	/**
	 * Restaurants view display method
	 * @return void
	 */
	function display($tpl = null) {
		
		cleverdine::load_css_js();

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
		
		$cid = $input->get('cid', array(), 'uint');

		$orders = array();

		foreach( $cid as $id ) {

			if( ($order = cleverdine::fetchTakeAwayOrderDetails($id)) ) {
				$orders[] = $order;
			}

		}

		if( !count($orders) ) {
			exit('no order found');
		}

		$this->orders = &$orders;
		
		// Display the template
		parent::display($tpl);

	}

}
?>