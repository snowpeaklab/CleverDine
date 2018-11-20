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
class cleverdineViewmanagetable extends JViewUI {
	
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
		
		$type = $input->get('type');
		
		// Set the toolbar
		$this->addToolBar($type);

		$rooms = array();
		
		$q = "SELECT `id`, `name` FROM `#__cleverdine_room` ORDER BY `ordering` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$rooms = $dbo->loadAssocList();
		} else {
			$mainframe->enqueueMessage(JText::_('VRROOMMISSINGERROR'), 'warning');
			$mainframe->redirect("index.php?option=com_cleverdine&task=newroom");
			exit;
		}
		
		// if type is edit -> assign the selected item
		$selectedTable = array();
		
		if( $type == "edit" ) {
			$ids = $input->get('cid', array(0), 'uint');
			$id = $ids[0];

			$q = "SELECT * FROM `#__cleverdine_table` WHERE `id`=$id LIMIT 1;";
			$dbo->setQuery($q);
			$dbo->execute();
			if($dbo->getNumRows() > 0) {
				$selectedTable = $dbo->loadAssoc();
			}
		}
		
		$this->rooms 			= &$rooms;
		$this->selectedTable 	= &$selectedTable;

		// Display the template
		parent::display($tpl);

	}

	/**
	 * Setting the toolbar
	 */
	private function addToolBar($type) {
		//Add menu title and some buttons to the page
		if( $type == "edit" ) {
			JToolbarHelper::title(JText::_('VRMAINTITLEEDITTABLE'), 'restaurants');
		} else {
			JToolbarHelper::title(JText::_('VRMAINTITLENEWTABLE'), 'restaurants');
		}
		
		if (JFactory::getUser()->authorise('core.edit', 'com_cleverdine')) {
			JToolbarHelper::apply('saveTable', JText::_('VRSAVE'));
			JToolbarHelper::save('saveAndCloseTable', JText::_('VRSAVEANDCLOSE'));
			JToolbarHelper::custom('saveAndNewTable', 'save-new', 'save-new', JText::_('VRSAVEANDNEW'), false, false);
			JToolbarHelper::divider();
		}
		
		JToolbarHelper::cancel('cancelTable', JText::_('VRCANCEL'));
	}

}
?>