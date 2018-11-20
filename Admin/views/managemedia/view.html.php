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
class cleverdineViewmanagemedia extends JViewUI {
	
	/**
	 * Restaurants view display method
	 * @return void
	 */
	function display($tpl = null) {
		
		RestaurantsHelper::load_css_js();
		cleverdine::load_complex_select();

		$mainframe 	= JFactory::getApplication();
		$input 		= $mainframe->input;
		
		// Set the toolbar
		$this->addToolBar();
		
		$filename = $input->get('cid', array(''), 'string');
		$filename = $filename[0];

		$path = JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_cleverdine'.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR;

		if( empty($filename) || !file_exists($path.'media'.DIRECTORY_SEPARATOR.$filename) ) {
			$mainframe->redirect('index.php?option=com_cleverdine&task=media');
			exit;
		}

		$media = RestaurantsHelper::getFileProperties($path.'media'.DIRECTORY_SEPARATOR.$filename);
		$thumb = RestaurantsHelper::getFileProperties($path.'media@small'.DIRECTORY_SEPARATOR.$filename);
		
		$this->media = &$media;
		$this->thumb = &$thumb;

		// Display the template
		parent::display($tpl);

	}

	/**
	 * Setting the toolbar
	 */
	private function addToolBar() {
		//Add menu title and some buttons to the page
		JToolbarHelper::title(JText::_('VRMAINTITLEEDITMEDIA'), 'restaurants');
		
		if (JFactory::getUser()->authorise('core.edit', 'com_cleverdine')) {
			JToolbarHelper::apply('saveMedia', JText::_('VRSAVE'));
			JToolbarHelper::save('saveAndCloseMedia', JText::_('VRSAVEANDCLOSE'));
			JToolbarHelper::custom('saveAndNewMedia', 'save-new', 'save-new', JText::_('VRSAVEANDNEW'), false, false);
			JToolbarHelper::divider();
		}
		
		JToolbarHelper::cancel('cancelMedia', JText::_('VRCANCEL'));
	}

}
?>