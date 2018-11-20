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
class cleverdineViewmenudetails extends JViewUI {

	/**
	 * Restaurants view display method
	 * @return void
	 */
	function display($tpl = null) {
		
		cleverdine::load_css_js();
		cleverdine::load_fancybox();

		$mainframe 	= JFactory::getApplication();
		$input 		= $mainframe->input;
		$dbo 		= JFactory::getDbo();
		
		$last_values = array(
			"date" => $input->get('date', '', 'string'),
			"shift" => $input->get('shift', '', 'string')
		);
		
		$sections_ids = array();
		$products_ids = array();
		$options_ids = array();
		
		$id_menu = $input->get('id', 0, 'uint');
		
		$menu = array();
		$q = "SELECT `s`.*, `p`.`id` AS `pid`, `p`.`name` AS `pname`, `p`.`image` AS `pimage`, `p`.`description` AS `pdesc`, `a`.`id` AS `aid`, (`p`.`price`+`a`.`charge`) AS `pcharge`, 
		`m`.`name` AS `menu_name`, `m`.`description` AS `menu_description`, `m`.`image` AS `menu_image`, `o`.`id` AS `oid`, `o`.`name` AS `oname`, `o`.`inc_price` AS `oprice`
		FROM `#__cleverdine_menus_section` AS `s` 
		LEFT JOIN `#__cleverdine_section_product_assoc` AS `a` ON `s`.`id`=`a`.`id_section` 
		LEFT JOIN `#__cleverdine_section_product` AS `p` ON `p`.`id`=`a`.`id_product`
		LEFT JOIN `#__cleverdine_section_product_option` AS `o` ON `o`.`id_product`=`p`.`id`  
		LEFT JOIN `#__cleverdine_menus` AS `m` ON `m`.`id`=`s`.`id_menu` 
		WHERE `m`.`id`=".$id_menu." AND `m`.`published`=1 AND `s`.`published`=1 AND `p`.`published`=1 ORDER BY `s`.`ordering` ASC, `a`.`ordering` ASC, `o`.`ordering` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$rows = $dbo->loadAssocList();
			
			$last_menu_id = -1;
			$last_section_id = -1;
			$last_product_id = -1;
			foreach( $rows as $r ) {
					
				if( $last_menu_id != $r['id_menu'] ) {
					$menu = array(
						"id" => $r['id_menu'],
						"name" => $r['menu_name'],
						"description" => $r['menu_description'],
						"image" => $r['menu_image'],
						"sections" => array()
					);
					
					$last_menu_id = $r['id_menu'];
				}
				
				if( $last_section_id != $r['id'] ) {
					array_push($menu['sections'], array(
						"id" => $r['id'],
						"name" => $r['name'],
						"description" => $r['description'],
						"image" => $r['image'],
						"highlight" => $r['highlight'],
						"products" => array()
					));
					
					$last_section_id = $r['id'];
					
					array_push($sections_ids, $r['id']);
				}
				
				if( $last_product_id != $r['pid'] ) {
					array_push($menu['sections'][count($menu['sections'])-1]['products'], array(
						"id" => $r['pid'],
						"name" => $r['pname'],
						"description" => $r['pdesc'],
						"image" => $r['pimage'],
						"price" => $r['pcharge'],
						"options" => array()
					));
					
					$last_product_id = $r['pid'];
					
					array_push($products_ids, $r['pid']);
				}
				
				if( !empty($r['oid']) ) {
					array_push($menu['sections'][count($menu['sections'])-1]['products'][count($menu['sections'][count($menu['sections'])-1]['products'])-1]['options'], array(
						"id" => $r['oid'],
						"name" => $r['oname'],
						"price" => $r['oprice'],
					));
					
					array_push($options_ids, $r['oid']);
				}
				
			}
		}

		$translated_menus 		= cleverdine::getTranslatedMenus(array($menu['id']));
		$translated_sections 	= cleverdine::getTranslatedSections($sections_ids);
		$translated_products 	= cleverdine::getTranslatedProducts($products_ids);
		$translated_options 	= cleverdine::getTranslatedProductOptions($options_ids);

		$this->translate($menu, $translated_menus, $translated_sections, $translated_products, $translated_options);
		
		$this->menu 		= &$menu;
		$this->lastValues 	= &$last_values;

		// prepare page content
		cleverdine::prepareContent($this);
		
		// Display the template
		parent::display($tpl);

	}
	
	private function translate(&$menu, $tmenus, $tsections, $tproducts, $toptions) {
		
		$menu['name'] = cleverdine::translate($menu['id'], $menu, $tmenus, 'name', 'name');
		$menu['description'] = cleverdine::translate($menu['id'], $menu, $tmenus, 'description', 'description');
		
		for( $i = 0; $i < count($menu['sections']); $i++ ) {
			$section =& $menu['sections'][$i];
			$section['name'] = cleverdine::translate($section['id'], $section, $tsections, 'name', 'name');
			$section['description'] = cleverdine::translate($section['id'], $section, $tsections, 'description', 'description');
			
			for( $j = 0; $j < count($section['products']); $j++ ) {
				$prod =& $section['products'][$j];
				$prod['name'] = cleverdine::translate($prod['id'], $prod, $tproducts, 'name', 'name');
				$prod['description'] = cleverdine::translate($prod['id'], $prod, $tproducts, 'description', 'description');
				
				for( $k = 0; $k < count($prod['options']); $k++ ) {
					$option =& $prod['options'][$k];
					$option['name'] = cleverdine::translate($option['id'], $option, $toptions, 'name', 'name');
				}
			}
		}
		
	}
}
?>