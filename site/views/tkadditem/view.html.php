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
class cleverdineViewtkadditem extends JViewUI {
	
	/**
	 * Restaurants view display method
	 * @return void
	 */
	function display($tpl = null) {
		
		cleverdine::load_css_js();

		$mainframe 	= JFactory::getApplication();
		$input 		= $mainframe->input;
		$dbo 		= JFactory::getDbo();
		
		$id_entry 	= $input->get('eid', 0, 'uint');
		$id_option 	= $input->get('oid', 0, 'uint');
		$index 		= $input->get('index', -1, 'int');

		// get cart instance
		cleverdine::loadCartLibrary();
		
		$cart = TakeAwayCart::getInstance();

		// get deals
		cleverdine::loadDealsLibrary();
		$discountDeals = DealsHandler::getAvailableFullDeals($cart->getCheckinTimestamp(), 2);
		
		$item = array();
		
		if( $index < 0 ) {
			$q = "SELECT `e`.`id`, `e`.`name`, `e`.`price`, `o`.id AS `oid`, `o`.`name` AS `oname`, `o`.`inc_price` AS `oprice` 
			FROM `#__cleverdine_takeaway_menus_entry` AS `e` 
			LEFT JOIN `#__cleverdine_takeaway_menus_entry_option` AS `o` ON `e`.`id`=`o`.`id_takeaway_menu_entry` 
			WHERE `e`.`id`=$id_entry AND `e`.`published`=1 ".($id_option > 0 ? "AND `o`.`id`=$id_option " : "")."LIMIT 1;";
			
			$dbo->setQuery($q);
			$dbo->execute();
			if( $dbo->getNumRows() > 0 ) {
				$item = $dbo->loadAssoc();
				$item['price'] += $item['oprice'];
				$item['quantity'] = 1;
				$item['notes'] = "";
				$item['selected_toppings'] = array();

				// check discount
				$is_discounted = DealsHandler::isProductInDeals(array(
					"id_product" => $item['id'],
					"id_option" => $item['oid'],
					"quantity" => 1
				), $discountDeals);

				if( $is_discounted !== false ) {
					if( $discountDeals[$is_discounted]['percentot'] == 1 ) {
						$item['price'] -= $item['price']*$discountDeals[$is_discounted]['amount']/100.0;
					} else {
						$item['price'] -= $discountDeals[$is_discounted]['amount'];
					}
				}
				//
			} else {
				$this->raiseError(JText::_('VRTKCARTROWNOTFOUND'));
				// process will exit
			}
		} else {
			
			$item = $cart->getItemAt($index);
			if( $item === null ) {
				$this->raiseError(JText::_('VRTKCARTROWNOTFOUND'));
				// process will exit
			}
			
			$sel_groups = $item->getToppingsGroupsList();
			
			$item = array(
				"id" => $item->getItemID(),
				"name" => $item->getItemName(),
				//"price" => ($item->getTotalCostNoDiscount()/$item->getQuantity()),
				"price" => ($item->getTotalCost()/$item->getQuantity()),
				"oid" => $item->getVariationID(),
				"oname" => $item->getVariationName(),
				"quantity" => $item->getQuantity(),
				"notes" => $item->getAdditionalNotes(),
				"selected_toppings" => array()
			);
			
			foreach( $sel_groups as $g ) {
				foreach( $g->getToppingsList() as $t ) {
					array_push($item['selected_toppings'], $t->getAssocID());
				}
			}
			
			$id_entry = $item['id'];
			$id_option = $item['oid'];
			
		}

		if( empty($id_option) ) {
			$id_option = 0;
		}
		
		$entry_groups = array();
		$q = "SELECT `g`.*, `t`.`id` AS `topping_group_assoc_id`, `t`.`id_topping`, `t`.`rate` AS `topping_rate`, `t`.`ordering` AS `topping_ordering`, `t2`.`name` AS `topping_name` 
		FROM `#__cleverdine_takeaway_entry_group_assoc` AS `g` 
		LEFT JOIN `#__cleverdine_takeaway_group_topping_assoc` AS `t` ON `g`.`id`=`t`.`id_group`
		LEFT JOIN `#__cleverdine_takeaway_topping` AS `t2` ON `t`.`id_topping`=`t2`.`id` 
		WHERE `g`.`id_entry`=$id_entry AND (`g`.`id_variation`=-1 OR `g`.`id_variation`=$id_option) 
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
						"ordering" => $group['topping_ordering'],
						"checked" => (in_array($group['topping_group_assoc_id'], $item['selected_toppings']) ? 1 : 0),
					));
				}
			}
		}
		
		// translations
		$groups_ids = array();
		$toppings_ids = array();
		foreach( $entry_groups as $group ) {
			array_push($groups_ids, $group['id']);
			foreach( $group['toppings'] as $topping ) {
				array_push($toppings_ids, $topping['id']);
			}
		}
		$groups_translations = cleverdine::getTranslatedTakeawayGroups($groups_ids);
		$toppings_translations = cleverdine::getTranslatedTakeawayToppings($toppings_ids);
		
		$this->item 				= &$item;
		$this->groups 				= &$entry_groups;
		$this->itemCartIndex 		= &$index;

		$this->groupsTranslations 	= &$groups_translations;
		$this->toppingsTranslations = &$toppings_translations;
		
		// Display the template
		parent::display($tpl);

	}

	protected function raiseError($err) {
		echo json_encode(array($err));
		exit;
	}

}
?>