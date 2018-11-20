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
class cleverdineViewmanagemenusproduct extends JViewUI {
	
	/**
	 * Restaurants view display method
	 * @return void
	 */
	function display($tpl = null) {
		
		RestaurantsHelper::load_css_js();
		RestaurantsHelper::load_complex_select();
		RestaurantsHelper::load_font_awesome();
		cleverdine::load_fancybox();

		$mainframe 	= JFactory::getApplication();
		$input 		= $mainframe->input;
		$dbo 		= JFactory::getDbo();
		
		$type = $input->get('type');
		
		// Set the toolbar
		$this->addToolBar($type);
		
		// if type is edit -> assign the selected item
		$selectedProduct = array();
		$variations = array();

		$status = 0;
		
		if( $type == "edit" ) {
			$ids = $input->get('cid', array(0), 'uint');
			$id = $ids[0];

			$q = "SELECT * FROM `#__cleverdine_section_product` WHERE `id`=$id LIMIT 1;";
			$dbo->setQuery($q);
			$dbo->execute();
			if($dbo->getNumRows() > 0) {
				$selectedProduct = $dbo->loadAssoc();
				
				$q = "SELECT * FROM `#__cleverdine_section_product_option` WHERE `id_product`=$id ORDER BY `ordering`;";
				$dbo->setQuery($q);
				$dbo->execute();
				if( $dbo->getNumRows() > 0 ) {
					$variations = $dbo->loadAssocList();
				}
			}
		} else {
			$status = $mainframe->getUserStateFromRequest('vrprod.status', 'never_retrieve_status', 0, 'uint');
		}
		
		$this->selectedProduct 	= &$selectedProduct;
		$this->variations 		= &$variations;
		$this->status 			= &$status;

		// Display the template
		parent::display($tpl);

	}

	/**
	 * Setting the toolbar
	 */
	private function addToolBar($type) {
		//Add menu title and some buttons to the page
		if( $type == "edit" ) {
			JToolbarHelper::title(JText::_('VRMAINTITLEEDITMENUSPRODUCT'), 'restaurants');
		} else {
			JToolbarHelper::title(JText::_('VRMAINTITLENEWMENUSPRODUCT'), 'restaurants');
		}
		
		if (JFactory::getUser()->authorise('core.edit', 'com_cleverdine')) {
			JToolbarHelper::apply('saveMenusProduct', JText::_('VRSAVE'));
			JToolbarHelper::save('saveAndCloseMenusProduct', JText::_('VRSAVEANDCLOSE'));
			JToolbarHelper::custom('saveAndNewMenusProduct', 'save-new', 'save-new', JText::_('VRSAVEANDNEW'), false, false);
			JToolbarHelper::divider();
		}
		
		JToolbarHelper::cancel('cancelMenusProduct', JText::_('VRCANCEL'));
	}

}
?>