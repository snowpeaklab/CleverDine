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
class cleverdineViewmanagetkmenu extends JViewUI {
	
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
		$menu_rows = array();
		
		if( $type == "edit" ) {
			$ids = $input->get('cid', array(0), 'uint');
			$id = $ids[0];
			
			$q = "SELECT * FROM `#__cleverdine_takeaway_menus` WHERE `id`=$id LIMIT 1;";
			$dbo->setQuery($q);
			$dbo->execute();
			if( $dbo->getNumRows() > 0 ) {
				$selectedMenu = $dbo->loadAssoc();
				
				$q = "SELECT `e`.`id` AS `eid`, `e`.`name` AS `ename`, `e`.`description` AS `edesc`, `e`.`price` AS `eprice`, `e`.`ready` AS `eready`, `e`.`img_path` AS `eimg`,
				 `o`.`id` AS `oid`, `o`.`name` AS `oname`, `o`.`inc_price` AS `oprice`
				FROM `#__cleverdine_takeaway_menus_entry` AS  `e`  
				LEFT JOIN `#__cleverdine_takeaway_menus_entry_option` AS `o` ON  `e`.`id` = `o`.`id_takeaway_menu_entry` 
				WHERE `e`.`id_takeaway_menu`=".intval($id)." 
				ORDER BY `e`.`ordering`, `o`.`ordering`;";
				$dbo->setQuery($q);
				$dbo->execute();
				if($dbo->getNumRows() > 0) {
					$menu_rows = $dbo->loadAssocList();
					
					$last_entry_id = -1;
					foreach( $menu_rows as $i => $entry ) {
						if( $entry['eid'] != $last_entry_id ) {
							$menu_rows[$i]['attributes'] = array();
							
							$q = "SELECT `id_attribute` FROM `#__cleverdine_takeaway_menus_attr_assoc` WHERE `id_menuentry`=".$entry['eid'].";";
							$dbo->setQuery($q);
							$dbo->execute();
							if( $dbo->getNumRows() > 0 ) {
								foreach( $dbo->loadAssocList() as $attr ) {
									array_push($menu_rows[$i]['attributes'], $attr['id_attribute']);
								}
							}
							
							$last_entry_id = $entry['eid']; 
						}
					}
					
				}
			}
		}
		
		$menus_attributes = array();
		$q = "SELECT * FROM `#__cleverdine_takeaway_menus_attribute` ORDER BY `ordering`;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$menus_attributes = $dbo->loadAssocList();
		}
		
		$this->selectedMenu 	= &$selectedMenu;
		$this->menu_rows 		= &$menu_rows;
		$this->menusAttributes 	= &$menus_attributes;

		// Display the template
		parent::display($tpl);

	}

	/**
	 * Setting the toolbar
	 */
	private function addToolBar($type) {
		//Add menu title and some buttons to the page
		if( $type == "edit" ) {
			JToolbarHelper::title(JText::_('VRMAINTITLEEDITTKMENU'), 'restaurants');
		} else {
			JToolbarHelper::title(JText::_('VRMAINTITLENEWTKMENU'), 'restaurants');
		}
		
		if (JFactory::getUser()->authorise('core.edit', 'com_cleverdine')) {
			JToolbarHelper::apply('saveTkmenu', JText::_('VRSAVE'));
			JToolbarHelper::save('saveAndCloseTkmenu', JText::_('VRSAVEANDCLOSE'));
			JToolbarHelper::custom('saveAndNewTkmenu', 'save-new', 'save-new', JText::_('VRSAVEANDNEW'), false, false);
			JToolbarHelper::divider();
		}
		
		JToolbarHelper::cancel('cancelTkmenu', JText::_('VRCANCEL'));
	}

}
?>