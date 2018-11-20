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
class cleverdineViewmanagerescodeorder extends JViewUI {
	
	/**
	 * Restaurants view display method
	 * @return void
	 */
	function display($tpl = null) {
		
		RestaurantsHelper::load_css_js();
		RestaurantsHelper::load_complex_select();
		RestaurantsHelper::load_font_awesome();

		$mainframe 	= JFactory::getApplication();
		$input 		= $mainframe->input;
		$dbo 		= JFactory::getDbo();
		
		$type = $input->get('type');
		
		// Set the toolbar
		$this->addToolBar($type);

		$filters = $input->get('filters', array(), 'array');

		$group = (intval($filters['group']) == 1 ? 1 : 2);
		
		// if type is edit -> assign the selected item
		$selectedStatus = array();
		
		if( $type == "edit" ) {
			$ids = $input->get('cid', array(0), 'uint');
			$id = $ids[0];
			$q = "SELECT * FROM `#__cleverdine_order_status` WHERE `id`=$id LIMIT 1;";
			$dbo->setQuery($q);
			$dbo->execute();
			if( $dbo->getNumRows() > 0 ) {
				$selectedStatus = $dbo->loadAssoc();
			}
		}

		$res_codes = array();

		$q = "SELECT * FROM `#__cleverdine_res_code` WHERE `type`=$group ORDER BY `ordering` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() ) {
			$res_codes = $dbo->loadAssocList();
		}
		
		$this->selectedStatus 	= &$selectedStatus;
		$this->filters 			= &$filters;
		$this->resCodes 		= &$res_codes;

		// Display the template
		parent::display($tpl);

	}

	/**
	 * Setting the toolbar
	 */
	private function addToolBar($type) {
		//Add menu title and some buttons to the page
		if( $type == "edit" ) {
			JToolbarHelper::title(JText::_('VRMAINTITLEEDITRESCODEORDER'), 'restaurants');
		} else {
			JToolbarHelper::title(JText::_('VRMAINTITLENEWRESCODEORDER'), 'restaurants');
		}
		
		if (JFactory::getUser()->authorise('core.edit', 'com_cleverdine')) {
			JToolbarHelper::apply('saveResCodeOrder', JText::_('VRSAVE'));
			JToolbarHelper::save('saveAndCloseResCodeOrder', JText::_('VRSAVEANDCLOSE'));
			JToolbarHelper::custom('saveAndNewResCodeOrder', 'save-new', 'save-new', JText::_('VRSAVEANDNEW'), false, false);
			JToolbarHelper::divider();
		}
		
		JToolbarHelper::cancel('cancelResCodeOrder', JText::_('VRCANCEL'));
	}

}
?>