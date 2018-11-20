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
class cleverdineViewmanagelangmenu extends JViewUI {
	
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
		
		$where = "";
		
		if( $type == "edit" ) {
			$ids = $input->get('cid', array(0), 'uint');
			$id = $ids[0];	

			$q = "SELECT `m`.`id` AS `id_menu`, `m`.`name` AS `menu_name`, `m`.`description` AS `menu_description`,
			`ml`.`id` AS `id_lang_menu`, `ml`.`name` AS `menu_lang_name`, `ml`.`description` AS `menu_lang_description`, `ml`.`tag`,
			
			`s`.`id` AS `id_section`, `s`.`name` AS `section_name`, `s`.`description` AS `section_description`,
			`sl`.`id` AS `id_lang_section`, `sl`.`name` AS `section_lang_name`, `sl`.`description` AS `section_lang_description`,
			
			`p`.`id` AS `id_product`, `p`.`name` AS `product_name`, `p`.`description` AS `product_description`,
			`pl`.`id` AS `id_lang_product`, `pl`.`name` AS `product_lang_name`, `pl`.`description` AS `product_lang_description`,
			
			`o`.`id` AS `id_option`, `o`.`name` AS `option_name`,
			`ol`.`id` AS `id_lang_option`, `ol`.`name` AS `option_lang_name`
			
			FROM `#__cleverdine_menus` AS `m` 
			LEFT JOIN `#__cleverdine_lang_menus` AS `ml` ON `m`.`id`=`ml`.`id_menu` 
			
			LEFT JOIN `#__cleverdine_menus_section` AS `s` ON `m`.`id`=`s`.`id_menu`
			LEFT JOIN `#__cleverdine_lang_menus_section` AS `sl` ON `s`.`id`=`sl`.`id_section`
			
			LEFT JOIN `#__cleverdine_section_product_assoc` AS `spa` ON `s`.`id`=`spa`.`id_section`
			LEFT JOIN `#__cleverdine_section_product` AS `p` ON `p`.`id`=`spa`.`id_product`
			LEFT JOIN `#__cleverdine_lang_section_product` AS `pl` ON `p`.`id`=`pl`.`id_product` 
			
			LEFT JOIN `#__cleverdine_section_product_option` AS `o` ON `p`.`id`=`o`.`id_product`
			LEFT JOIN `#__cleverdine_lang_section_product_option` AS `ol` ON `o`.`id`=`ol`.`id_option`
			
			WHERE `ml`.`id`=$id
			ORDER BY `m`.`ordering` ASC, `s`.`ordering` ASC, `spa`.`ordering` ASC, `o`.`ordering` ASC;";

		} else {
			$id_menu = $input->get('id_menu', 0, 'uint');
		
			$q = "SELECT `m`.`id` AS `id_menu`, `m`.`name` AS `menu_name`, `m`.`description` AS `menu_description`,
			
			`s`.`id` AS `id_section`, `s`.`name` AS `section_name`, `s`.`description` AS `section_description`,
			
			`p`.`id` AS `id_product`, `p`.`name` AS `product_name`, `p`.`description` AS `product_description`,
			
			`o`.`id` AS `id_option`, `o`.`name` AS `option_name`
			
			FROM `#__cleverdine_menus` AS `m`
			
			LEFT JOIN `#__cleverdine_menus_section` AS `s` ON `s`.`id_menu`=`m`.`id`
			
			LEFT JOIN `#__cleverdine_section_product_assoc` AS `spa` ON `s`.`id`=`spa`.`id_section`
			LEFT JOIN `#__cleverdine_section_product` AS `p` ON `p`.`id`=`spa`.`id_product`
			
			LEFT JOIN `#__cleverdine_section_product_option` AS `o` ON `o`.`id_product`=`p`.`id`
			
			WHERE `m`.`id`=$id_menu 
			ORDER BY `m`.`ordering` ASC, `s`.`ordering` ASC, `spa`.`ordering` ASC, `o`.`ordering` ASC;";

		}
		
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() == 0 ) {
			$mainframe->redirect('index.php?option=com_cleverdine&task=menus');
			exit;
		}
		
		$struct = $this->fetchMenuStructure( $dbo->loadAssocList() ); 
		
		$this->struct 	= &$struct;
		$this->type 	= &$type;

		// Display the template
		parent::display($tpl);
	}

	/**
	 * Setting the toolbar
	 */
	private function addToolBar($type) {
		//Add menu title and some buttons to the page
		if( $type == "edit" ) {
			JToolbarHelper::title(JText::_('VRMAINTITLEEDITLANGMENU'), 'restaurants');
		} else {
			JToolbarHelper::title(JText::_('VRMAINTITLENEWLANGMENU'), 'restaurants');
		}
		
		if (JFactory::getUser()->authorise('core.edit', 'com_cleverdine')) {
		   JToolbarHelper::apply('saveLangMenu', JText::_('VRSAVE'));
			JToolbarHelper::save('saveAndCloseLangMenu', JText::_('VRSAVEANDCLOSE'));
			JToolbarHelper::custom('saveAndNewLangMenu', 'save-new', 'save-new', JText::_('VRSAVEANDNEW'), false, false);
			JToolbarHelper::divider();
		}

		JToolbarHelper::cancel('cancelLangMenu', JText::_('VRCANCEL'));
	}
	
	private function fetchMenuStructure($arr) {

		$struct = array(
			"id" => $arr[0]['id_menu'],
			"id_lang" => empty($arr[0]['id_lang_menu']) ? '' : $arr[0]['id_lang_menu'],
			"name" => $arr[0]['menu_name'],
			"description" => $arr[0]['menu_description'],
			"lang_name" => empty($arr[0]['menu_lang_name']) ? '' : $arr[0]['menu_lang_name'],
			"lang_description" => empty($arr[0]['menu_lang_description']) ? '' : $arr[0]['menu_lang_description'],
			"tag" => empty($arr[0]['tag']) ? '' : $arr[0]['tag'],
			"sections" => array()
		);
		
		$old_section = $old_product = $old_option = -1;
		
		foreach( $arr as $row ) {
			if( $old_section != $row['id_section'] && !empty($row['id_section']) ) {
				
				array_push($struct['sections'], array(
					"id" => $row['id_section'],
					"id_lang" => empty($row['id_lang_section']) ? '' : $row['id_lang_section'],
					"name" => $row['section_name'],
					"description" => $row['section_description'],
					"lang_name" => empty($row['section_lang_name']) ? '' : $row['section_lang_name'],
					"lang_description" => empty($row['section_lang_description']) ? '' : $row['section_lang_description'],
					"products" => array(),
				));
				
				$old_section = $row['id_section'];
			}
			
			if( $old_product != $row['id_product'] && !empty($row['id_product']) ) {
				
				array_push($struct['sections'][count($struct['sections'])-1]['products'], array(
					"id" => $row['id_product'],
					"id_lang" => empty($row['id_lang_product']) ? '' : $row['id_lang_product'],
					"name" => $row['product_name'],
					"description" => $row['product_description'],
					"lang_name" => empty($row['product_lang_name']) ? '' : $row['product_lang_name'],
					"lang_description" => empty($row['product_lang_description']) ? '' : $row['product_lang_description'],
					"options" => array(),
				));
				
				$old_product = $row['id_product'];
			}

			if( $old_option != $row['id_option'] && !empty($row['id_option']) ) {
				
				array_push($struct['sections'][count($struct['sections'])-1]['products'][count($struct['sections'][count($struct['sections'])-1]['products'])-1]['options'], array(
					"id" => $row['id_option'],
					"id_lang" => empty($row['id_lang_option']) ? '' : $row['id_lang_option'],
					"name" => $row['option_name'],
					"lang_name" => empty($row['option_lang_name']) ? '' : $row['option_lang_name'],
				));
				
				$old_option = $row['id_option'];
			}
		}

		return $struct;
		
	}

}
?>