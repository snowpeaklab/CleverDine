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
class cleverdineViewmanagemenu extends JViewUI {
	
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
		$selectedMenu = array();
		
		$sections = array();
		
		$id_menu = 0;
		
		if( $type == "edit" ) {
			$ids = $input->get('cid', array(0), 'uint');
			$id_menu = $ids[0];

			$q = "SELECT * FROM `#__cleverdine_menus` WHERE `id`=$id_menu LIMIT 1;";
			$dbo->setQuery($q);
			$dbo->execute();

			if($dbo->getNumRows() > 0) {
				$selectedMenu = $dbo->loadAssoc();
				
				$sections = array();
				$q = "SELECT `s`.*, `p`.`id` AS `pid`, `p`.`name` AS `pname`, `a`.`id` AS `aid`, `a`.`charge` AS `acharge` FROM `#__cleverdine_menus_section` AS `s` 
				LEFT JOIN `#__cleverdine_section_product_assoc` AS `a` ON `s`.`id`=`a`.`id_section` 
				LEFT JOIN `#__cleverdine_section_product` AS `p` ON `p`.`id`=`a`.`id_product` 
				WHERE `s`.`id_menu`=$id_menu ORDER BY `s`.`ordering` ASC, `a`.`ordering` ASC;";
				$dbo->setQuery($q);
				$dbo->execute();
				if( $dbo->getNumRows() > 0 ) {
					$sections = $dbo->loadAssocList();
				}
				
			}
		}
		
		$products = array();
		$q = "SELECT `id`, `name` FROM `#__cleverdine_section_product` WHERE `hidden`=0 ORDER BY `name` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$products = $dbo->loadAssocList();
		}
		
		$shifts = cleverdine::getWorkingShifts(1);
		
		$this->selectedMenu = &$selectedMenu;
		$this->sections 	= &$sections;
		$this->products 	= &$products;
		$this->shifts 		= &$shifts;

		// Display the template
		parent::display($tpl);

	}

	/**
	 * Setting the toolbar
	 */
	private function addToolBar($type) {
		//Add menu title and some buttons to the page
		if( $type == "edit" ) {
			JToolbarHelper::title(JText::_('VRMAINTITLEEDITMENU'), 'restaurants');
		} else {
			JToolbarHelper::title(JText::_('VRMAINTITLENEWMENU'), 'restaurants');
		}
		
		if (JFactory::getUser()->authorise('core.edit', 'com_cleverdine')) {
			JToolbarHelper::apply('saveMenu', JText::_('VRSAVE'));
			JToolbarHelper::save('saveAndCloseMenu', JText::_('VRSAVEANDCLOSE'));
			JToolbarHelper::custom('saveAndNewMenu', 'save-new', 'save-new', JText::_('VRSAVEANDNEW'), false, false);
			JToolbarHelper::divider();
		}

		JToolbarHelper::cancel('cancelMenu', JText::_('VRCANCEL'));
	}

}
?>