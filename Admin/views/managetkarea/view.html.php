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
class cleverdineViewmanagetkarea extends JViewUI {
	
	/**
	 * Restaurants view display method
	 * @return void
	 */
	function display($tpl = null) {
		
		RestaurantsHelper::load_css_js();
		RestaurantsHelper::load_complex_select();
		RestaurantsHelper::load_font_awesome();
		cleverdine::load_googlemaps();

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
			$q = "SELECT * FROM `#__cleverdine_takeaway_delivery_area` WHERE `id`=$id LIMIT 1;";
			$dbo->setQuery($q);
			$dbo->execute();
			if($dbo->getNumRows() > 0) {
				$row = $dbo->loadAssoc();
			} else {
				$mainframe->redirect('index.php?option=com_cleverdine&task=tkareas');
				exit;
			}
		}

		cleverdine::loadGraphics2D();
		$shapes = cleverdine::getAllDeliveryAreas(true);
		
		$this->row 		= &$row;
		$this->shapes 	= &$shapes;

		// Display the template
		parent::display($tpl);

	}

	/**
	 * Setting the toolbar
	 */
	private function addToolBar($type) {
		//Add menu title and some buttons to the page
		if( $type == "edit" ) {
			JToolbarHelper::title(JText::_('VRMAINTITLEEDITTKAREA'), 'restaurants');
		} else {
			JToolbarHelper::title(JText::_('VRMAINTITLENEWTKAREA'), 'restaurants');
		}
		
		if (JFactory::getUser()->authorise('core.edit', 'com_cleverdine')) {
			JToolbarHelper::apply('saveTkarea', JText::_('VRSAVE'));
			JToolbarHelper::save('saveAndCloseTkarea', JText::_('VRSAVEANDCLOSE'));
			JToolbarHelper::custom('saveAndNewTkarea', 'save-new', 'save-new', JText::_('VRSAVEANDNEW'), false, false);
			JToolbarHelper::custom('saveAsCopyTkarea', 'save-copy', 'save-copy', JText::_('VRSAVEASCOPY'), false, false);
			JToolbarHelper::divider();
		}
		
		JToolbarHelper::cancel('cancelTkarea', JText::_('VRCANCEL'));
	}

}
?>