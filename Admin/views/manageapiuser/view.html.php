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
class cleverdineViewmanageapiuser extends JViewUI {
	
	/**
	 * Restaurants view display method
	 * @return void
	 */
	function display($tpl = null) {
		
		RestaurantsHelper::load_css_js();
		RestaurantsHelper::load_font_awesome();
		RestaurantsHelper::load_complex_select();

		$mainframe 	= JFactory::getApplication();
		$input 		= $mainframe->input;
		$dbo 		= JFactory::getDbo();
		
		$type = $input->get('type');
		
		// Set the toolbar
		$this->addToolBar($type);
		
		// if type is edit -> assign the selected item
		$row = array();
		
		if( $type == "edit" ) {
			$ids = $input->get('cid', array(0), 'uint');
			$id = $ids[0];

			$q = "SELECT * FROM `#__cleverdine_api_login` WHERE `id`=$id LIMIT 1;";
			$dbo->setQuery($q);
			$dbo->execute();
			if( $dbo->getNumRows() > 0 ) {
				$row = $dbo->loadAssoc();
			} else {
				$mainframe->redirect('index.php?option=com_cleverdine&task=apiusers');
				exit;
			}
		}

		cleverdine::loadFrameworkApis();

		$apis = FrameworkAPIs::getInstance();
		$plugins = $apis->getPluginsList();
		
		$this->row 		= &$row;
		$this->plugins 	= &$plugins;

		// Display the template
		parent::display($tpl);

	}

	/**
	 * Setting the toolbar
	 */
	private function addToolBar($type) {
		//Add menu title and some buttons to the page
		if( $type == "edit" ) {
			JToolbarHelper::title(JText::_('VRMAINTITLEEDITAPIUSER'), 'restaurants');
		} else {
			JToolbarHelper::title(JText::_('VRMAINTITLENEWAPIUSER'), 'restaurants');
		}
		
		if (JFactory::getUser()->authorise('core.edit', 'com_cleverdine')) {
			JToolbarHelper::apply('saveApiuser', JText::_('VRSAVE'));
			JToolbarHelper::save('saveAndCloseApiuser', JText::_('VRSAVEANDCLOSE'));
			JToolbarHelper::custom('saveAndNewApiuser', 'save-new', 'save-new', JText::_('VRSAVEANDNEW'), false, false);
			JToolbarHelper::divider();
		}
		
		JToolbarHelper::cancel('cancelApiuser', JText::_('VRCANCEL'));
	}

}
?>