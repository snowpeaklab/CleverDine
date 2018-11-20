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
class cleverdineViewmanagetkdeal extends JViewUI {
	
	/**
	 * Restaurants view display method
	 * @return void
	 */
	function display($tpl = null) {
		
		RestaurantsHelper::load_css_js();
		RestaurantsHelper::load_complex_select();
		RestaurantsHelper::load_font_awesome();

		$mainframe 	= JFactory::getApplication();
		$input 		= $mainframe->input;
		$dbo 		= JFactory::getDbo();
		
		$type = $input->get('type');
		
		// Set the toolbar
		$this->addToolBar($type);
		
		// if type is edit -> assign the selected item
		$sel = array();
		
		if( $type == "edit" ) {
			$ids = $input->get('cid', array(0), 'uint');
			$id = intval($ids[0]);
			
			$q = "SELECT * FROM `#__cleverdine_takeaway_deal` WHERE `id`=$id LIMIT 1;";
			$dbo->setQuery($q);
			$dbo->execute();
			if($dbo->getNumRows() > 0) {
				$sel = $dbo->loadAssoc();
			}
			
			$sel['days_filter'] = array();
			$q = "SELECT `id_weekday` FROM `#__cleverdine_takeaway_deal_day_assoc` WHERE `id_deal`=$id ORDER BY `id_weekday` ASC;";
			$dbo->setQuery($q);
			$dbo->execute();
			if( $dbo->getNumRows() > 0 ) {
				foreach( $dbo->loadAssocList() as $week_day ) {
					array_push($sel['days_filter'], $week_day['id_weekday']);
				}
			}
		}

		if( $input->get('submitted', 0, 'uint') == 1 ) {
			$sel['name'] 			= $input->get('name', '', 'string');
			$sel['description'] 	= $input->get('description', '', 'raw');
			$sel['start_ts'] 		= cleverdine::createTimestamp($input->get('start_ts', '', 'string'), 0, 0);
			$sel['end_ts'] 			= cleverdine::createTimestamp($input->get('end_ts', '', 'string'), 0, 0);
			$sel['max_quantity'] 	= $input->get('max_quantity', 0, 'int');
			$sel['published'] 		= $input->get('published', 0, 'uint');
			$sel['days_filter'] 	= $input->get('days_filter', array(), 'uint');
			$sel['type'] 			= $input->get('deal_type', 0, 'uint');
			$sel['amount'] 			= $input->get('amount', 0.0, 'float');
			$sel['percentot'] 		= $input->get('percentot', 0, 'uint');
			$sel['auto_insert'] 	= $input->get('auto_insert', 0, 'uint');
			$sel['min_quantity'] 	= $input->get('min_quantity', 0, 'uint');
			$sel['cart_tcost'] 		= $input->get('cart_tcost', 0.0, 'float');
			if( empty($sel['id']) ) {
				$sel['id'] = -1;
			}
			
			$sel['animate'] = 1;
		}

		$all_prod_menus = array();
		$q = "SELECT `m`.`id` AS `id_menu`, `m`.`title` AS `menu_title`, `e`.`id` AS `id_product`, `e`.`name` AS `product_name`, `o`.`id` AS `id_option`, `o`.`name` AS `option_name` 
		FROM `#__cleverdine_takeaway_menus` AS `m` 
		LEFT JOIN `#__cleverdine_takeaway_menus_entry` AS `e` ON `m`.`id`=`e`.`id_takeaway_menu` 
		LEFT JOIN `#__cleverdine_takeaway_menus_entry_option` AS `o` ON `e`.`id`=`o`.`id_takeaway_menu_entry` 
		ORDER BY `m`.`ordering` ASC, `e`.`ordering` ASC, `o`.`ordering` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$rows = $dbo->loadAssocList();
			
			$last_menu_id = $last_prod_id = -1;
			foreach( $rows as $r ) {
				if( $last_menu_id != $r['id_menu'] ) {
					array_push($all_prod_menus, array(
						"id" => $r["id_menu"],
						"title" => $r["menu_title"],
						"products" => array()
					));
					$last_menu_id = $r['id_menu'];
				}
				
				if( $last_prod_id != $r['id_product'] ) {
					array_push($all_prod_menus[count($all_prod_menus)-1]['products'], array(
						"id" => $r["id_product"],
						"name" => $r["product_name"],
						"options" => array()
					));
					$last_prod_id = $r['id_product'];
				}
				
				if( !empty($r['id_option']) ) {
					array_push($all_prod_menus[count($all_prod_menus)-1]['products'][count($all_prod_menus[count($all_prod_menus)-1]['products'])-1]['options'], array(
						"id" => $r["id_option"],
						"name" => $r["option_name"],
					));
				}
			}
			
		}

		$deal_prod_assoc = array();
		if( !empty($sel['id']) && $sel['id'] > -1 ) {
			$q = "SELECT `d`.*, `e`.`name` AS `product_name`, `o`.`name` AS `option_name` 
			FROM `#__cleverdine_takeaway_deal_product_assoc` AS `d` 
			LEFT JOIN `#__cleverdine_takeaway_menus_entry` AS `e` ON `d`.`id_product`=`e`.`id`
			LEFT JOIN `#__cleverdine_takeaway_menus_entry_option` AS `o` ON `d`.`id_option`=`o`.`id`
			WHERE `id_deal`=".$sel['id'].";";
			$dbo->setQuery($q);
			$dbo->execute();
			if( $dbo->getNumRows() > 0 ) {
				$deal_prod_assoc = $dbo->loadAssocList();
			}
		}
		
		$free_prod_assoc = array();
		if( !empty($sel['id']) && $sel['id'] > -1 ) {
			$q = "SELECT `d`.*, `e`.`name` AS `product_name`, `o`.`name` AS `option_name` 
			FROM `#__cleverdine_takeaway_deal_free_assoc` AS `d` 
			LEFT JOIN `#__cleverdine_takeaway_menus_entry` AS `e` ON `d`.`id_product`=`e`.`id`
			LEFT JOIN `#__cleverdine_takeaway_menus_entry_option` AS `o` ON `d`.`id_option`=`o`.`id`
			WHERE `id_deal`=".$sel['id'].";";
			$dbo->setQuery($q);
			$dbo->execute();
			if( $dbo->getNumRows() > 0 ) {
				$free_prod_assoc = $dbo->loadAssocList();
			}
		}
		
		$this->selectedDeal 	= &$sel;
		$this->allProductsMenus = &$all_prod_menus;
		$this->dealProducts 	= &$deal_prod_assoc;
		$this->freeProducts 	= &$free_prod_assoc;

		// Display the template
		parent::display($tpl);

	}

	/**
	 * Setting the toolbar
	 */
	private function addToolBar($type) {
		//Add menu title and some buttons to the page
		if( $type == "edit" ) {
			JToolbarHelper::title(JText::_('VRMAINTITLEEDITTKDEAL'), 'restaurants');
		} else {
			JToolbarHelper::title(JText::_('VRMAINTITLENEWTKDEAL'), 'restaurants');
		}
		
		if (JFactory::getUser()->authorise('core.edit', 'com_cleverdine')) {
			JToolbarHelper::apply('saveTkdeal', JText::_('VRSAVE'));
			JToolbarHelper::save('saveAndCloseTkdeal', JText::_('VRSAVEANDCLOSE'));
			JToolbarHelper::custom('saveAndNewTkdeal', 'save-new', 'save-new', JText::_('VRSAVEANDNEW'), false, false);
			JToolbarHelper::divider();
		}
		
		JToolbarHelper::cancel('cancelTkdeal', JText::_('VRCANCEL'));
	}

}
?>