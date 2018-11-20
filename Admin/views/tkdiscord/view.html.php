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
class cleverdineViewtkdiscord extends JViewUI {
	/**
	 * Restaurants view display method
	 * @return void
	 */
	function display($tpl = null) {

		RestaurantsHelper::load_css_js();
		RestaurantsHelper::load_complex_select();

		$mainframe 	= JFactory::getApplication();
		$input 		= $mainframe->input;
		$dbo 		= JFactory::getDbo();

		// Set the toolbar
		$this->addToolBar();

		$cid = $input->get('cid', array(0), 'uint');
		$id = $cid[0];

		$q = "SELECT `total_to_pay`, (`total_to_pay`-`taxes`-`pay_charge`-`delivery_charge`) AS `total_net`, `discount_val`, `coupon_str`
		FROM `#__cleverdine_takeaway_reservation` WHERE `id`=$id LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() == 0 ) {
			$mainframe->redirect('index.php?option=com_cleverdine&task=tkreservations');
			exit;
		}

		$order = $dbo->loadAssoc();

		$coupons = array();

		$q = "SELECT * FROM `#__cleverdine_coupons` WHERE `group`=1;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$coupons = $dbo->loadAssocList();
		}

		$this->order 	= &$order;
		$this->coupons 	= &$coupons;
		$this->id 		= &$id;
		
		// Display the template (default.php)
		parent::display($tpl);

	}

	/**
	 * Setting the toolbar
	 */
	private function addToolBar() {
		//Add menu title and some buttons to the page
		JToolbarHelper::title(JText::_('VRMAINTITLEVIEWTKDISCORD'), 'restaurants');
		
		if (JFactory::getUser()->authorise('core.edit', 'com_cleverdine')) {
			JToolbarHelper::save('saveTkDiscountOrder', JText::_('VRSAVE'));
			JToolbarHelper::divider();
		}

		JToolbarHelper::cancel('cancelTkreservation', JText::_('VRCANCEL'));

	}

}
?>