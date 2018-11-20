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
class cleverdineViewopcoupons extends JViewUI {
	
	/**
	 * Restaurants view display method
	 * @return void
	 */
	function display($tpl = null) {

		cleverdine::load_font_awesome();

		$mainframe 	= JFactory::getApplication();
		$dbo 		= JFactory::getDbo();
		
		////// LOGIN //////
		
		$operator = cleverdine::getOperator();
		
		if( $operator === false || empty($operator['can_login']) || $operator['manage_coupon'] == 0 )  {
			$mainframe->enqueueMessage(JText::_('VRLOGINUSERNOTFOUND'), 'error');
			$mainframe->redirect(JRoute::_('index.php?option=com_cleverdine&view=oversight'));
			exit;
		}

		cleverdine::load_css_js();
		
		$coupons = array();

		$q = $dbo->getQuery(true);
		
		$q->select('*')->from($dbo->quoteName('#__cleverdine_coupons'));

		if( $operator['group'] != 0 ) {
			$q->where($dbo->quoteName('group') . ' = ' . ($operator['group'] - 1));
		}
		
		$dbo->setQuery($q);
		$dbo->execute();

		if( $dbo->getNumRows() > 0 ) {
			$coupons = $dbo->loadAssocList();
		}
		
		$this->operator = &$operator;
		$this->coupons 	= &$coupons;

		// Display the template
		parent::display($tpl);

	}

}
?>