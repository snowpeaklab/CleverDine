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
class cleverdineViewtakeawayitem extends JViewUI {
	
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
		
		$id_item 	= $input->get('takeaway_item', 0, 'uint');
		$id_option 	= $input->get('id_option', 0, 'uint');

		// compose request
		$request = new stdClass;
		$request->idEntry 	= $id_item;
		$request->idOption 	= $id_option;
		$request->quantity 	= $input->get('quantity', 1, 'uint');
		$request->notes 	= $input->get('notes', '', 'string');
		$request->toppings 	= $input->get('topping', array(), 'array');

		$available_tk_menus = cleverdine::getAllTakeawayMenusOn(array(
			"date" => date(cleverdine::getDateFormat(), $cart->getCheckinTimestamp()),
			"hour" => -1,
			"min" => 0,
			"hourmin" => "-1:0"
		));
		
		$item = array();
		$all_attributes = array();
		
		$q = "SELECT `e`.`id` AS `eid`, `e`.`name` AS `ename`, `e`.`description` AS `edesc`, `e`.`price` AS `eprice`, `e`.`ready` AS `eready`, `e`.`img_path` AS `eimg`, 
		`o`.`id` AS `oid`, `o`.`name` AS `oname`, `o`.`inc_price` AS `oprice`, `m`.`id` AS `mid`, `m`.`title` AS `mtitle`, `m`.`description` AS `mdesc` 
		FROM `#__cleverdine_takeaway_menus` AS `m` 
		LEFT JOIN `#__cleverdine_takeaway_menus_entry` AS `e` ON `m`.`id`=`e`.`id_takeaway_menu` 
		LEFT JOIN `#__cleverdine_takeaway_menus_entry_option` AS `o` ON `e`.`id`=`o`.`id_takeaway_menu_entry` 
		WHERE `m`.`published`=1 AND `e`.`published`=1 AND `e`.`id`=$id_item 
		ORDER BY `m`.`ordering` ASC, `e`.`ordering` ASC, `o`.`ordering` ASC;";
		
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$item = $this->parseTakeawayMenus($dbo->loadAssocList(), $id_option, $dbo);
		} else {
			$mainframe->redirect(JRoute::_('index.php?option=com_cleverdine&view=takeaway'));
			exit;
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

		// translate attributes
		$attributes_translations = cleverdine::getTranslatedTakeawayAttributes(array_keys($all_attributes));
		foreach( $all_attributes as $k => $v ) {
			$all_attributes[$k]['name'] = cleverdine::translate($v['id'], $v, $attributes_translations, 'name', 'name');
		}
		
		cleverdine::loadDealsLibrary();
		$discount_deals = DealsHandler::getAvailableFullDeals($cart->getCheckinTimestamp(), 2);

		// should be submitted on variation change ?
		$q = "SELECT 1
		FROM `#__cleverdine_takeaway_entry_group_assoc`		
		WHERE `id_entry`=$id_item AND `id_variation`<>-1 
		LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->execute();
		$to_submit = $dbo->getNumRows();

		// get reviews
		UILoader::import('library.reviews.handler');

		$reviewsHandler = new ReviewsHandler();

		$reviews = $reviewsHandler->takeaway()
			->setOrdering('rating', 2)
			->addOrdering('timestamp', 2)
			->getReviews($item['id'], $dbo);

		$reviews_stats = $reviewsHandler->takeaway()->getAverageRatio($item['id'], $dbo);
		
		$this->item 					= &$item;
		$this->cart 					= &$cart;
		$this->availableTakeawayMenus 	= &$available_tk_menus;
		$this->allAttributes 			= &$all_attributes;
		$this->discountDeals 			= &$discount_deals;
		$this->reviews 					= &$reviews;
		$this->reviewsStats 			= &$reviews_stats;
		$this->request 					= &$request;
		$this->isToSubmit 				= &$to_submit;

		// prepare page content
		cleverdine::prepareContent($this);
		
		// Display the template
		parent::display($tpl);

	}
	
	protected function parseTakeawayMenus($item, $id_option, $dbo) {

		$prod = array(
			"id" => $item[0]['eid'],
			"name" => $item[0]['ename'],
			"description" => $item[0]['edesc'],
			"price" => $item[0]['eprice'],
			"ready" => $item[0]['eready'],
			"image" => $item[0]['eimg'],
			"id_menu" => $item[0]['mid'],
			"menu_title" => $item[0]['mtitle'],
			"menu_desc" => $item[0]['mdesc'],
			"options" => array(),
			"attributes" => array(),
			"toppings_groups" => array()
		);

		// translate menu
		$menus_translations = cleverdine::getTranslatedTakeawayMenus(array($prod['id_menu']));
		$prod['menu_title'] = cleverdine::translate($prod['id'], $prod, $menus_translations, 'menu_title', 'title');
		$prod['menu_desc'] = cleverdine::translate($prod['id'], $prod, $menus_translations, 'menu_desc', 'description');

		// translate entry
		$entries_translations = cleverdine::getTranslatedTakeawayProducts(array($prod['id']));
		$prod['name'] = cleverdine::translate($prod['id'], $prod, $entries_translations, 'name', 'name');
		$prod['description'] = cleverdine::translate($prod['id'], $prod, $entries_translations, 'description', 'description');
		
		$q = "SELECT `id_attribute` FROM `#__cleverdine_takeaway_menus_attr_assoc` WHERE `id_menuentry`=".$item[0]['eid'].";";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			foreach( $dbo->loadAssocList() as $attr ) {
				array_push($prod['attributes'], $attr['id_attribute']);
			}
		}

		// get options
		$options_ids = array();
		
		foreach( $item as $r ) {
			
			if( !empty($r['oid']) ) {
				array_push($prod['options'], array(
					"id" => $r['oid'],
					"name" => $r['oname'],
					"price" => $r['oprice'],
				));

				$options_ids[] = $r['oid'];
			}

		}

		// translate options
		$options_translations = cleverdine::getTranslatedTakeawayOptions($options_ids);
		foreach( $prod['options'] as $i => $opt ) {
			$prod['options'][$i]['name'] = cleverdine::translate($opt['id'], $opt, $options_translations, 'name', 'name');
		}

		// get toppings
		$groups_ids = array();
		$toppings_ids = array();

		$q = "SELECT `g`.*, `t`.`id` AS `topping_group_assoc_id`, `t`.`id_topping`, `t`.`rate` AS `topping_rate`, `t`.`ordering` AS `topping_ordering`, `t2`.`name` AS `topping_name` 
		FROM `#__cleverdine_takeaway_entry_group_assoc` AS `g` 
		LEFT JOIN `#__cleverdine_takeaway_group_topping_assoc` AS `t` ON `g`.`id`=`t`.`id_group`
		LEFT JOIN `#__cleverdine_takeaway_topping` AS `t2` ON `t`.`id_topping`=`t2`.`id` 
		WHERE `g`.`id_entry`=".$prod['id']." AND (`g`.`id_variation`=-1 OR `g`.`id_variation`=$id_option)  
		ORDER BY `g`.`ordering` ASC, `t`.`ordering` ASC;";

		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			
			$last_group_id = -1;
			foreach( $dbo->loadAssocList() as $group ) {
				$group['toppings'] = array();
				if( $group['id'] != $last_group_id ) {
					array_push($prod['toppings_groups'], $group);
					$last_group_id = $group['id'];

					array_push($groups_ids, $group['id']);
				}
				
				if( !empty($group['topping_group_assoc_id']) ) {
					array_push($prod['toppings_groups'][count($prod['toppings_groups'])-1]['toppings'], array(
						"assoc_id" => $group['topping_group_assoc_id'],
						"id" => $group['id_topping'],
						"name" => $group['topping_name'],
						"rate" => $group['topping_rate'],
						"ordering" => $group['topping_ordering']
					));

					array_push($toppings_ids, $group['id_topping']);
				}
			}
		}
		
		// translate groups and toppings
		$groups_translations = cleverdine::getTranslatedTakeawayGroups($groups_ids);		
		$toppings_translations = cleverdine::getTranslatedTakeawayToppings($toppings_ids);
		foreach( $prod['toppings_groups'] as $i => $group ) {
			$prod['toppings_groups'][$i]['title'] = cleverdine::translate($group['id'], $group, $groups_translations, 'title', 'title');
			foreach( $group['toppings'] as $j => $topping ) {
				$prod['toppings_groups'][$i]['toppings'][$j]['name'] = cleverdine::translate($topping['id'], $topping, $toppings_translations, 'name', 'name');
			}
		}
		
		return $prod;
	}

}
?>