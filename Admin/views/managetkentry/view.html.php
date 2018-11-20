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
class cleverdineViewmanagetkentry extends JViewUI {
	
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

		$id_menu = $input->get('id_menu', 0, 'int');
		
		// if type is edit -> assign the selected item
		$entry = array();
		$entry_groups = array();

		if( $type == "edit" ) {
			$ids = $input->get('cid', array(0), 'uint');
			$id = $ids[0];

			$q = "SELECT `e`.`id` AS `eid`, `e`.`name` AS `ename`, `e`.`description` AS `edesc`, `e`.`price` AS `eprice`, `e`.`ready` AS `eready`, `e`.`img_path` AS `eimg`, `e`.`published` AS `epublished`,
			`o`.`id` AS `oid`, `o`.`name` AS `oname`, `o`.`inc_price` AS `oprice`, `m`.`title` AS `menutitle`, `m`.`id` AS `menuid`
			FROM `#__cleverdine_takeaway_menus` AS  `m`   
			LEFT JOIN `#__cleverdine_takeaway_menus_entry` AS `e` ON `e`.`id_takeaway_menu`=`m`.`id`
			LEFT JOIN `#__cleverdine_takeaway_menus_entry_option` AS `o` ON  `e`.`id` = `o`.`id_takeaway_menu_entry` 
			WHERE `e`.`id`=$id 
			ORDER BY `e`.`ordering`, `o`.`ordering`;";

			$dbo->setQuery($q);
			$dbo->execute();
			if( $dbo->getNumRows() > 0 ) {

				// GET ENTRY
				$sel = $dbo->loadAssocList();
				
				$entry = $sel[0];
				$entry['attributes'] = array();
				$entry['variations'] = array();
				
				foreach( $sel as $var ) {
					if( !empty($var['oid']) ) {
						array_push($entry['variations'], array(
							"oid" => $var['oid'],
							"oname" => $var['oname'],
							"oprice" => $var['oprice'],
						));
					}
				}
				
				$q = "SELECT `id_attribute` FROM `#__cleverdine_takeaway_menus_attr_assoc` WHERE `id_menuentry`=".$entry['eid'].";";
				$dbo->setQuery($q);
				$dbo->execute();
				if( $dbo->getNumRows() > 0 ) {
					foreach( $dbo->loadAssocList() as $attr ) {
						array_push($entry['attributes'], $attr['id_attribute']);
					}
				}

				// GET GROUPS
				$entry_groups = array();
				$q = "SELECT `g`.*, `t`.`id` AS `topping_group_assoc_id`, `t`.`id_topping`, `t`.`rate` AS `topping_rate`, `t`.`ordering` AS `topping_ordering`, `t2`.`name` AS `topping_name` 
				FROM `#__cleverdine_takeaway_entry_group_assoc` AS `g` 
				LEFT JOIN `#__cleverdine_takeaway_group_topping_assoc` AS `t` ON `g`.`id`=`t`.`id_group`
				LEFT JOIN `#__cleverdine_takeaway_topping` AS `t2` ON `t`.`id_topping`=`t2`.`id` 
				WHERE `g`.`id_entry`=".$entry['eid']."  
				ORDER BY `g`.`ordering` ASC, `t`.`ordering` ASC;";
				$dbo->setQuery($q);
				$dbo->execute();
				if( $dbo->getNumRows() > 0 ) {
					$app = $dbo->loadAssocList();
					
					$last_group_id = -1;
					foreach( $app as $group ) {
						$group['toppings'] = array();
						if( $group['id'] != $last_group_id ) {
							array_push($entry_groups, $group);
							$last_group_id = $group['id'];
						}
						
						if( !empty($group['topping_group_assoc_id']) ) {
							array_push($entry_groups[count($entry_groups)-1]['toppings'], array(
								"assoc_id" => $group['topping_group_assoc_id'],
								"id" => $group['id_topping'],
								"name" => $group['topping_name'],
								"rate" => $group['topping_rate'],
								"ordering" => $group['topping_ordering']
							));
						}
					}
				}
				
			}
		}
		
		$all_menus = array();
		$q = "SELECT `id`, `title` FROM `#__cleverdine_takeaway_menus` ORDER BY `ordering`;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$all_menus = $dbo->loadAssocList();
		}
		
		$menus_attributes = array();
		$q = "SELECT * FROM `#__cleverdine_takeaway_menus_attribute` ORDER BY `ordering`;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$menus_attributes = $dbo->loadAssocList();
		}
		
		$all_toppings = array();
		$q = "SELECT `t`.*, `s`.`id` AS `id_sep`, `s`.`title` AS `separator` 
		FROM `#__cleverdine_takeaway_topping` AS `t` 
		LEFT JOIN `#__cleverdine_takeaway_topping_separator` AS `s` ON `t`.`id_separator`=`s`.`id` 
		ORDER BY `s`.`ordering` ASC, `t`.`ordering` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$all_toppings = $dbo->loadAssocList();
		}
				
		$this->entry 			= &$entry;
		$this->allMenus 		= &$all_menus;
		$this->menusAttributes 	= &$menus_attributes;
		$this->entryGroups 		= &$entry_groups;
		$this->allToppings 		= &$all_toppings;
		$this->idMenu 			= &$id_menu;

		// Display the template
		parent::display($tpl);
		
	}

	/**
	 * Setting the toolbar
	 */
	private function addToolBar($type) {
		//Add menu title and some buttons to the page
		if( $type == "edit" ) {
			JToolbarHelper::title(JText::_('VRMAINTITLEEDITTKENTRY'), 'restaurants');
		} else {
			JToolbarHelper::title(JText::_('VRMAINTITLENEWTKENTRY'), 'restaurants');
		}
		
		if (JFactory::getUser()->authorise('core.edit', 'com_cleverdine')) {
			JToolbarHelper::apply('saveTkentry', JText::_('VRSAVE'));
			JToolbarHelper::save('saveAndCloseTkentry', JText::_('VRSAVEANDCLOSE'));
			JToolbarHelper::custom('saveAndNewTkentry', 'save-new', 'save-new', JText::_('VRSAVEANDNEW'), false, false);
			JToolbarHelper::custom('saveAsCopyTkentry', 'save-copy', 'save-copy', JText::_('VRSAVEASCOPY'), false, false);
			JToolbarHelper::divider();
		}
		
		JToolbarHelper::cancel('cancelTkproduct', JText::_('VRCANCEL'));
	}
}
?>