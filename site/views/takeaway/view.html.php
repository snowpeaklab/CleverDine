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
class cleverdineViewtakeaway extends JViewUI {

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
		
		cleverdine::loadCartLibrary();
		
		$cart = TakeAwayCart::getInstance();
		
		$selected_menu = $input->get('takeaway_menu', -1, 'int');
		$selected_date = $input->get('takeaway_date', '', 'string');

		// only if date is set and date can be changed
		if( !empty($selected_date) && cleverdine::isTakeAwayDateAllowed() ) {
			$cart->setCheckinTimestamp(cleverdine::createTimestamp($selected_date, 0, 0));

			// check for deals
			cleverdine::resetDealsInCart($cart);
			cleverdine::checkForDeals($cart);
			
			$cart->store();
		}

		$dt_args = array(
			"date" => date(cleverdine::getDateFormat(), $cart->getCheckinTimestamp()),
			"hour" => -1,
			"min" => 0,
			"hourmin" => "-1:0",
		);

		$available_tk_menus = cleverdine::getAllTakeawayMenusOn($dt_args);
		
		$items = array();
		$menus = array();
		$all_attributes = array();
		
		$q = "SELECT `e`.`id` AS `eid`, `e`.`name` AS `ename`, `e`.`description` AS `edesc`, `e`.`price` AS `eprice`, `e`.`ready` AS `eready`, `e`.`img_path` AS `eimg`, 
		`o`.`id` AS `oid`, `o`.`name` AS `oname`, `o`.`inc_price` AS `oprice`, `m`.`id` AS `mid`, `m`.`title` AS `mtitle`, `m`.`description` AS `mdesc` 
		FROM `#__cleverdine_takeaway_menus` AS `m` 
		LEFT JOIN `#__cleverdine_takeaway_menus_entry` AS `e` ON `m`.`id`=`e`.`id_takeaway_menu` 
		LEFT JOIN `#__cleverdine_takeaway_menus_entry_option` AS `o` ON `e`.`id`=`o`.`id_takeaway_menu_entry` 
		WHERE `m`.`published`=1 AND `e`.`published`=1 ".($selected_menu > 0 ? "AND `m`.`id`=$selected_menu " : "")."
		ORDER BY `m`.`ordering` ASC, `e`.`ordering` ASC, `o`.`ordering` ASC;";
		
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$items = $this->parseTakeawayMenus($dbo->loadAssocList(), $dbo);
		}
		
		$q = "SELECT * FROM `#__cleverdine_takeaway_menus` WHERE `published`=1 ORDER BY `ordering`;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$menus = $dbo->loadAssocList();
		}
		
		$q = "SELECT * FROM `#__cleverdine_takeaway_menus_attribute` WHERE `published`=1 ORDER BY `ordering`;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$rows = $dbo->loadAssocList();
			foreach( $rows as $r ) {
				$all_attributes[$r['id']] = $r;
			}
		}

		$special_days = array();
		
		$q = "SELECT * FROM `#__cleverdine_specialdays` WHERE `group`=2;";
		$dbo->setQuery($q);
		$dbo->execute();
		if ($dbo->getNumRows() > 0) {
			$special_days = $dbo->loadAssocList();
		}
		
		cleverdine::loadDealsLibrary();
		$discount_deals = DealsHandler::getAvailableFullDeals($cart->getCheckinTimestamp(), 2);
		
		// translations
		$attributes_translations = cleverdine::getTranslatedTakeawayAttributes(array_keys($all_attributes));
		
		$menus_ids = array();
		foreach( $menus as $m ) {
			array_push($menus_ids, $m['id']);
		}
		$menus_translations = cleverdine::getTranslatedTakeawayMenus($menus_ids);
		
		$entries_ids = array();
		$options_ids = array();
		foreach( $items as $m ) {
			foreach( $m['entries'] as $entry ) {
				array_push($entries_ids, $entry['id']);
				foreach( $entry['options'] as $option ) {
					array_push($options_ids, $option['id']);
				}
			}
		}
		$entries_translations = cleverdine::getTranslatedTakeawayProducts($entries_ids);
		$options_translations = cleverdine::getTranslatedTakeawayOptions($options_ids);

		$this->items 					= &$items;
		$this->menus 					= &$menus;
		$this->availableTakeawayMenus 	= &$available_tk_menus;
		$this->allAttributes 			= &$all_attributes;
		$this->selectedMenu 			= &$selected_menu;
		$this->discountDeals 			= &$discount_deals;
		$this->cart 					= &$cart;
		$this->specialDays 				= &$special_days;

		$this->attributesTranslations 	= &$attributes_translations;
		$this->menusTranslations 		= &$menus_translations;
		$this->entriesTranslations 		= &$entries_translations;
		$this->optionsTranslations 		= &$options_translations;



		// prepare page content
		cleverdine::prepareContent($this);
		
		// Display the template
		parent::display($tpl);

	}
	
	private function parseTakeawayMenus($menus, $dbo) {
		$rows = array();
		$last_menu_id = $last_entry_id = -1;
		
		foreach( $menus as $r ) {
			if( $last_menu_id != $r['mid'] ) {
				array_push($rows, array(
					"id" => $r['mid'],
					"title" => $r['mtitle'],
					"description" => $r['mdesc'],
					"entries" => array()
				));
				
				$last_menu_id = $r['mid'];
			}
			
			if( $last_entry_id != $r['eid'] && !empty($r['eid']) ) {
				$entry = array(
					"id" => $r['eid'],
					"name" => $r['ename'],
					"description" => $r['edesc'],
					"price" => $r['eprice'],
					"ready" => $r['eready'],
					"image" => $r['eimg'],
					"options" => array(),
					"attributes" => array()
				);
				
				$q = "SELECT `id_attribute` FROM `#__cleverdine_takeaway_menus_attr_assoc` WHERE `id_menuentry`=".$r['eid'].";";
				$dbo->setQuery($q);
				$dbo->execute();
				if( $dbo->getNumRows() > 0 ) {
					foreach( $dbo->loadAssocList() as $attr ) {
						array_push($entry['attributes'], $attr['id_attribute']);
					}
				}
				
				array_push($rows[count($rows)-1]['entries'], $entry);
				
				$last_entry_id = $r['eid'];
			}
			
			if( !empty($r['oid']) ) {
				array_push($rows[count($rows)-1]['entries'][count($rows[count($rows)-1]['entries'])-1]['options'], array(
					"id" => $r['oid'],
					"name" => $r['oname'],
					"price" => $r['oprice'],
				));
			}
		}
		
		return $rows;
	}

}
?>