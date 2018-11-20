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
class cleverdineViewmanagemap extends JViewUI {
	
	/**
	 * Restaurants view display method
	 * @return void
	 */
	function display($tpl = null) {
		
		RestaurantsHelper::load_css_js();
		RestaurantsHelper::load_font_awesome();

		$mainframe 	= JFactory::getApplication();
		$input 		= $mainframe->input;
		$dbo 		= JFactory::getDbo();
		
		$type = $input->get('type');
		
		// Set the toolbar
		$this->addToolBar($type);
		
		$id = $input->get('selectedroom', 0, 'uint');
		
		$selectedRoom = array();
		$allRoomTables = array();

		$q = "SELECT * FROM `#__cleverdine_room` WHERE `id`=$id LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->execute();
		if($dbo->getNumRows() > 0) {
			$selectedRoom = $dbo->loadAssoc();
		}
		
		$q = "SELECT * FROM `#__cleverdine_table` WHERE `id_room`=$id;";
		$dbo->setQuery($q);
		$dbo->execute();
		if($dbo->getNumRows() > 0) {
			$allRoomTables = $dbo->loadAssocList();
		}
		
		$this->room 		= &$selectedRoom;
		$this->tables 		= &$allRoomTables;
		$this->blankKeys 	= &$blankKeys;

		// Display the template
		parent::display($tpl);

	}

	/**
	 * Setting the toolbar
	 */
	private function addToolBar($type) {
		//Add menu title and some buttons to the page
		if( $type == "edit" ) {
			JToolbarHelper::title(JText::_('VRMAINTITLEEDITMAP'), 'restaurants');
		}
		
		if (JFactory::getUser()->authorise('core.edit', 'com_cleverdine')) {
			JToolbarHelper::apply('saveMap', JText::_('VRSAVE'));
			JToolbarHelper::spacer();
			
			JToolbarHelper::save('saveandcloseMap', JText::_('VRSAVECLOSE'));
			JToolbarHelper::divider();
		}
		
		JToolbarHelper::custom('reloadMap', 'refresh', 'refresh', JText::_('VRRELOAD'), false, false);
		JToolbarHelper::divider();
		
		JToolbarHelper::cancel('cancelMap', JText::_('VRCANCEL'));
	}

}
?>