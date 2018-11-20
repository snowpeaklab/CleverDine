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
class cleverdineViewmanagetktopping extends JViewUI {
	
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
		
		// if type is edit -> assign the selected item
		$row = array();
		
		if( $type == "edit" ) {
			$ids = $input->get('cid', array(0), 'uint');
			$id = $ids[0];

			$q = "SELECT * FROM `#__cleverdine_takeaway_topping` WHERE `id`=$id LIMIT 1;";
			$dbo->setQuery($q);
			$dbo->execute();
			if( $dbo->getNumRows() > 0 ) {
				$row = $dbo->loadAssoc();
			} else {
				$mainframe->redirect('index.php?option=com_cleverdine&task=tktoppings');
				exit;
			}
		}
		
		$separators = array();
		$q = "SELECT `id`, `title` FROM `#__cleverdine_takeaway_topping_separator` ORDER BY `ordering`;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$separators = $dbo->loadAssocList();
		}

		$last_separator = JFactory::getSession()->get('tklastseparator', 0, 'vre');
		
		$this->row 					= &$row;
		$this->separators 			= &$separators;
		$this->lastSeparatorUsed 	= &$last_separator;

		// Display the template
		parent::display($tpl);

	}

	/**
	 * Setting the toolbar
	 */
	private function addToolBar($type) {
		//Add menu title and some buttons to the page
		if( $type == "edit" ) {
			JToolbarHelper::title(JText::_('VRMAINTITLEEDITTKTOPPING'), 'restaurants');
		} else {
			JToolbarHelper::title(JText::_('VRMAINTITLENEWTKTOPPING'), 'restaurants');
		}
		
		if (JFactory::getUser()->authorise('core.edit', 'com_cleverdine')) {
			JToolbarHelper::apply('saveTktopping', JText::_('VRSAVE'));
			JToolbarHelper::save('saveAndCloseTktopping', JText::_('VRSAVEANDCLOSE'));
			JToolbarHelper::custom('saveAndNewTktopping', 'save-new', 'save-new', JText::_('VRSAVEANDNEW'), false, false);
			JToolbarHelper::divider();
		}
		
		JToolbarHelper::cancel('cancelTktopping', JText::_('VRCANCEL'));
	}

}
?>