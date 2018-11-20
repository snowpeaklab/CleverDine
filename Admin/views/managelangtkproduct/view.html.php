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
class cleverdineViewmanagelangtkproduct extends JViewUI {
	
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
		
		$type 		= $input->get('type');
		$id_menu 	= $input->get('id_menu', 0, 'uint');
		
		// Set the toolbar
		$this->addToolBar($type);
		
		$where = "";
		
		if( $type == "edit" ) {
			$ids = $input->get('cid', array(0), 'uint');
			$id = $ids[0];	
			$where = "`pl`.`id`=$id AND `ol`.`id_parent`=$id";

			$q = "SELECT `p`.`id` AS `id_product`, `p`.`name` AS `product_name`, `p`.`description` AS `product_description`,
			`pl`.`id` AS `id_lang_product`, `pl`.`name` AS `product_lang_name`, `pl`.`description` AS `product_lang_description`, `pl`.`tag`,
			
			`o`.`id` AS `id_option`, `o`.`name` AS `option_name`,
			`ol`.`id` AS `id_lang_option`, `ol`.`name` AS `option_lang_name`,
			
			`g`.`id` AS `id_group`, `g`.`title` AS `group_name`, 
			`gl`.`id` AS `id_lang_group`, `gl`.`name` AS `group_lang_name`
			
			FROM `#__cleverdine_lang_takeaway_menus_entry` AS `pl`
			LEFT JOIN `#__cleverdine_takeaway_menus_entry` AS `p` ON `p`.`id`=`pl`.`id_entry`
			
			LEFT JOIN `#__cleverdine_lang_takeaway_menus_entry_option` AS `ol` ON `pl`.`id`=`ol`.`id_parent`
			LEFT JOIN `#__cleverdine_takeaway_menus_entry_option` AS `o` ON `o`.`id`=`ol`.`id_option`
			
			LEFT JOIN `#__cleverdine_lang_takeaway_menus_entry_topping_group` AS `gl` ON `ol`.`id`=`gl`.`id_parent`
			LEFT JOIN `#__cleverdine_takeaway_entry_group_assoc` AS `g` ON `g`.`id`=`gl`.`id_group`
			
			WHERE $where ORDER BY `o`.`ordering` ASC, `g`.`ordering` ASC;";
		} else {
			$id_product = $input->get('id_product', 0, 'uint');

			$q = "SELECT `p`.`id` AS `id_product`, `p`.`name` AS `product_name`, `p`.`description` AS `product_description`,
			
			`o`.`id` AS `id_option`, `o`.`name` AS `option_name`,
			
			`g`.`id` AS `id_group`, `g`.`title` AS `group_name`
			
			FROM `#__cleverdine_takeaway_menus_entry` AS `p`
			
			LEFT JOIN `#__cleverdine_takeaway_menus_entry_option` AS `o` ON `o`.`id_takeaway_menu_entry`=`p`.`id`
			
			LEFT JOIN `#__cleverdine_takeaway_entry_group_assoc` AS `g` ON `g`.`id_entry`=`p`.`id`
			
			WHERE `p`.`id`=$id_product ORDER BY `o`.`ordering` ASC, `g`.`ordering` ASC;";
		}
		
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() == 0 ) {
			$mainframe->redirect('index.php?option=com_cleverdine&task=tkproducts&id_menu='.$id_menu);
			exit;
		}
		
		$struct = $this->fetchMenuStructure( $dbo->loadAssocList() ); 
		
		$this->struct 	= &$struct;
		$this->type 	= &$type;
		$this->idMenu 	= &$id_menu;

		// Display the template
		parent::display($tpl);
	}

	/**
	 * Setting the toolbar
	 */
	private function addToolBar($type) {
		//Add menu title and some buttons to the page
		if( $type == "edit" ) {
			JToolbarHelper::title(JText::_('VRMAINTITLEEDITLANGTKPRODUCT'), 'restaurants');
		} else {
			JToolbarHelper::title(JText::_('VRMAINTITLENEWLANGTKPRODUCT'), 'restaurants');
		}
		
		if (JFactory::getUser()->authorise('core.edit', 'com_cleverdine')) {
		   JToolbarHelper::apply('saveLangTkproduct', JText::_('VRSAVE'));
			JToolbarHelper::save('saveAndCloseLangTkproduct', JText::_('VRSAVEANDCLOSE'));
			JToolbarHelper::custom('saveAndNewLangTkproduct', 'save-new', 'save-new', JText::_('VRSAVEANDNEW'), false, false);
			JToolbarHelper::divider();
		}

		JToolbarHelper::cancel('cancelLangTkproduct', JText::_('VRCANCEL'));
	}
	
	private function fetchMenuStructure($arr) {
		$struct = array(
			"id" => $arr[0]['id_product'],
			"id_lang" => empty($arr[0]['id_lang_product']) ? '' : $arr[0]['id_lang_product'],
			"name" => $arr[0]['product_name'],
			"description" => $arr[0]['product_description'],
			"lang_name" => empty($arr[0]['product_lang_name']) ? '' : $arr[0]['product_lang_name'],
			"lang_description" => empty($arr[0]['product_lang_description']) ? '' : $arr[0]['product_lang_description'],
			"tag" => empty($arr[0]['tag']) ? '' : $arr[0]['tag'],
			"options" => array(),
			"groups" => array()
		);
		
		$old_option = -1;
		$groups_ids = array();
		
		foreach( $arr as $row ) {

			if( $old_option != $row['id_option'] && !empty($row['id_option']) ) {
				
				array_push($struct['options'], array(
					"id" => $row['id_option'],
					"id_lang" => empty($row['id_lang_option']) ? '' : $row['id_lang_option'],
					"name" => $row['option_name'],
					"lang_name" => empty($row['option_lang_name']) ? '' : $row['option_lang_name']
				));
				
				$old_option = $row['id_option'];
			}
			
			if( !in_array($row['id_group'], $groups_ids) && !empty($row['id_group']) ) {
				
				array_push($struct['groups'], array(
					"id" => $row['id_group'],
					"id_lang" => empty($row['id_lang_group']) ? '' : $row['id_lang_group'],
					"name" => $row['group_name'],
					"lang_name" => empty($row['group_lang_name']) ? '' : $row['group_lang_name']
				));
				
				array_push($groups_ids, $row['id_group']);
			}
		}
		
		return $struct;
		
	}

}
?>