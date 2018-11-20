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
class cleverdineViewmanagecustomer extends JViewUI {
	/**
	 * Restaurants view display method
	 * @return void
	 */
	function display($tpl = null) {
		// Set the toolbar
		RestaurantsHelper::load_css_js();
		RestaurantsHelper::load_complex_select();
		RestaurantsHelper::load_font_awesome();

		$input 	= JFactory::getApplication()->input;
		$dbo 	= JFactory::getDbo();
		
		$type = $input->get('type');
		
		// Set the toolbar
		$this->addToolBar($type);
		
		// if type is edit -> assign the selected item
		$selectedCust = array();
		
		if( $type == "edit" ) {
			$ids = $input->get('cid', array(0), 'uint');
			$id = $ids[0];
			$q = "SELECT * FROM `#__cleverdine_users` WHERE `id`=$id LIMIT 1;";
			$dbo->setQuery($q);
			$dbo->execute();
			if( $dbo->getNumRows() > 0 ) {
				$selectedCust = $dbo->loadAssoc();
			}
		}
		
		$custom_fields = array( array(), array() );
		$q = "SELECT * FROM `#__cleverdine_custfields` WHERE `type`<>'checkbox' OR `required`=0 ORDER BY `ordering`;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			foreach( $dbo->loadAssocList() as $cf ) {
				array_push($custom_fields[$cf['group']], $cf);
			}
		}
		
		$countries = array();
		$q = "SELECT * FROM `#__cleverdine_countries` ORDER BY `country_name` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$countries = $dbo->loadAssocList();
		}
		
		$jid = -1;
		if( !empty($selectedCust['jid']) ) {
			$jid = $selectedCust['jid'];
		}
		
		$juser = array();
		$q = "SELECT `id`, `name` FROM `#__users` WHERE `id`=$jid LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$juser = $dbo->loadAssoc();
		}

		$delivery_locations = array();
		if( !empty($selectedCust['id']) ) {
			$q = "SELECT * FROM `#__cleverdine_user_delivery` WHERE `id_user`=".$selectedCust['id']." ORDER BY `ordering` ASC;";
			$dbo->setQuery($q);
			$dbo->execute();
			if( $dbo->getNumRows() > 0 ) {
				$delivery_locations = $dbo->loadAssocList();
			}
		}
		
		$this->countries 			= &$countries;
		$this->juser 				= &$juser;
		$this->customFields 		= &$custom_fields;
		$this->selectedCustomer 	= &$selectedCust;
		$this->deliveryLocations 	= &$delivery_locations;

		$is_tmpl = $input->get('tmpl');

		$this->isTmpl = $is_tmpl;

		// Display the template
		parent::display($tpl);

	}

	/**
	 * Setting the toolbar
	 */
	private function addToolBar($type) {
		//Add menu title and some buttons to the page
		if( $type == "edit" ) {
			JToolbarHelper::title(JText::_('VRMAINTITLEEDITCUSTOMER'), 'restaurants');
		} else {
			JToolbarHelper::title(JText::_('VRMAINTITLENEWCUSTOMER'), 'restaurants');
		}
		
		if( JFactory::getUser()->authorise('core.edit', 'com_cleverdine') ) {
			JToolbarHelper::apply('saveCustomer', JText::_('VRSAVE'));
			JToolbarHelper::save('saveAndCloseCustomer', JText::_('VRSAVEANDCLOSE'));
			JToolbarHelper::custom('saveAndNewCustomer', 'save-new', 'save-new', JText::_('VRSAVEANDNEW'), false, false);
			JToolbarHelper::divider();
		}

		JToolbarHelper::cancel('cancelCustomer', JText::_('VRCANCEL'));
	}
}
?>