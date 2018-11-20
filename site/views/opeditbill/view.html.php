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
class cleverdineViewopeditbill extends JViewUI {
	
	/**
	 * Restaurants view display method
	 * @return void
	 */
	function display($tpl = null) {
		
		cleverdine::load_css_js();
		cleverdine::load_font_awesome();
		cleverdine::load_complex_select();

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
		
		$id = $input->getUint('id');

		$order = cleverdine::fetchOrderDetails($id);	

		if( $order === false ) {
			$mainframe->enqueueMessage(JText::_('VRORDERRESERVATIONERROR'), 'error');
			$mainframe->redirect(JRoute::_('index.php?option=com_cleverdine&view=oversight'));
			exit;
		}

		$order['food'] = cleverdine::getFoodFromReservation($id, $dbo);

		//

		$menus = array();

		$q = "SELECT * FROM `#__cleverdine_menus` ORDER BY `ordering` ASC";

		$dbo->setQuery($q);
		$dbo->execute();

		if( $dbo->getNumRows() ) {
			$menus = $dbo->loadAssocList();
		}

		//

		$this->order = &$order;

		$this->menus = &$menus;
		
		// Display the template
		parent::display($tpl);

	}

}
?>