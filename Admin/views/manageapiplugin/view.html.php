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
class cleverdineViewmanageapiplugin extends JViewUI {
	
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
		
		cleverdine::loadFrameworkApis();

		$type = $input->get('type');
		
		// Set the toolbar
		$this->addToolBar($type);
		
		$plugin = null;
		
		if( $type == "edit" ) {
			
			$plg_name = $input->getString('plugin', '');

			$apis = FrameworkAPIs::getInstance();

			$plugins = $apis->getPluginsList($plg_name);

			if( count($plugins) ) {
				$plugin = $plugins[0];
			} else {
				$mainframe->redirect('index.php?option=com_cleverdine&view=apiplugins');
				exit;
			}

		}
		
		$this->row 		= &$row;
		$this->plugin 	= &$plugin;

		// Display the template
		parent::display($tpl);

	}

	/**
	 * Setting the toolbar
	 */
	private function addToolBar($type) {
		//Add menu title and some buttons to the page
		if( $type == "edit" ) {
			JToolbarHelper::title(JText::_('VRMAINTITLEEDITAPIPLUGIN'), 'restaurants');
		} else {
			JToolbarHelper::title(JText::_('VRMAINTITLENEWAPIPLUGIN'), 'restaurants');
		}
		
		JToolbarHelper::cancel('cancelApiplugin', JText::_('VRCANCEL'));
	}

}
?>