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
class cleverdineViewtkstocks extends JViewUI {
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

		// Set the toolbar
		$this->addToolBar();

		$filters = array();
		$filters['id_menu'] 	= $mainframe->getUserStateFromRequest('vrtkstocks.id_menu', 'id_menu', 0, 'uint');
		$filters['keysearch'] 	= $mainframe->getUserStateFromRequest('vrtkstocks.keysearch', 'keysearch', '', 'string');

		// ORDERING AND FILTERS
		$ordering = OrderingManager::getColumnToOrder('tkstocks', 'remaining', 1);

		// ALL TK MENUS
		$menus = array();
		$q = "SELECT `id`, `title` FROM `#__cleverdine_takeaway_menus` ORDER BY `ordering` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$menus = $dbo->loadAssocList();
		}

		// FETCH LIST
		$lim 	= $mainframe->getUserStateFromRequest('com_cleverdine.limit', 'limit', $mainframe->get('list_limit'), 'int');
		$lim0 	= $mainframe->getUserStateFromRequest('vrtkstocks.limitstart', 'limitstart', 0, 'uint');
		$navbut	= "";

		$rows = array();
		$q = $this->buildStockQuery($filters, $ordering, $dbo);
		$dbo->setQuery($q, $lim0, $lim);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$rows = $dbo->loadAssocList();

			$dbo->setQuery('SELECT FOUND_ROWS();');
			jimport('joomla.html.pagination');
			$pageNav = new JPagination( $dbo->loadResult(), $lim0, $lim );
			$navbut="<table align=\"center\"><tr><td>".$pageNav->getListFooter()."</td></tr></table>";
		}

		$new_type = OrderingManager::getSwitchColumnType( 'tkstocks', $ordering['column'], $ordering['type'], array( 1, 2 ) );
		$ordering = array( $ordering['column'] => $new_type );
		
		$this->rows 	= &$rows;
		$this->menus 	= &$menus;
		$this->navbut 	= &$navbut;
		$this->filters 	= &$filters;
		$this->ordering = &$ordering;
		
		// Display the template (default.php)
		parent::display($tpl);

	}

	/**
	 * Setting the toolbar
	 */
	private function addToolBar() {
		//Add menu title and some buttons to the page
		JToolbarHelper::title(JText::_('VRTKSTOCKSOVERVIEW'), 'restaurants');
		
		if (JFactory::getUser()->authorise('core.edit', 'com_cleverdine')) {
			JToolbarHelper::apply('saveTkMenuStocksOverrides', JText::_('VRSAVE'));
			JToolbarHelper::save('saveAndCloseTkMenuStocksOverrides', JText::_('VRSAVEANDCLOSE'));
			JToolbarHelper::divider();
		}

		JToolbarHelper::cancel('cancelTkreservation', JText::_('VRCANCEL'));

	}

	protected function buildStockQuery($filters, $ordering, $dbo='') {
		if( empty($dbo) ) {
			$dbo = JFactory::getDbo();
		}

		$where_claus = "";
		if( !empty($filters['id_menu']) ) {
			$where_claus = "WHERE `e`.`id_takeaway_menu`=".$filters['id_menu'];
		}

		if( !empty($filters['keysearch']) ) {
			if( strlen($where_claus) ) {
				$where_claus .= " AND ";
			} else {
				$where_claus = "WHERE ";
			}
			$where_claus .= "CONCAT_WS(' ', `e`.`name`, `o`.`name`) LIKE ".$dbo->quote("%".$filters['keysearch']."%");
		}

		$ordering_column = ($ordering['column'] != 'remaining' ? "`".$ordering['column']."`" : "(`products_in_stock`-`products_used`)");

		return "SELECT SQL_CALC_FOUND_ROWS `e`.`id` AS `eid`, `e`.`name` AS `ename`, `o`.`id` AS `oid`, `o`.`name` AS `oname`, CONCAT_WS(' ', `e`.`name`, `o`.`name`) AS `concat_name`, 
			IF(`o`.`id` IS NULL, `e`.`notify_below`, `o`.`notify_below` ) AS `product_notify_below`,
			IF(`o`.`id` IS NULL, `e`.`items_in_stock`, `o`.`items_in_stock` ) AS `product_original_stock`,
			IF(`o`.`id` IS NULL, 
				(
					IFNULL(
						(
							SELECT SUM(`so`.`items_available`) 
							FROM `#__cleverdine_takeaway_stock_override` AS `so` 
							WHERE `so`.`id_takeaway_entry`=`e`.`id` AND `so`.`id_takeaway_option` IS NULL
						), `e`.`items_in_stock`
					)
				), (
					IFNULL(
						(
							SELECT SUM(`so`.`items_available`) 
							FROM `#__cleverdine_takeaway_stock_override` AS `so` 
							WHERE `so`.`id_takeaway_entry`=`e`.`id` AND `so`.`id_takeaway_option`=`o`.`id`
						), `o`.`items_in_stock`
					)
				)
			) AS `products_in_stock`,
			IF(`o`.`id` IS NULL, 
				(
					IFNULL(
						(
							SELECT SUM(`i`.`quantity`)
							FROM `#__cleverdine_takeaway_reservation` AS `r` 
							LEFT JOIN `#__cleverdine_takeaway_res_prod_assoc` AS `i` ON `i`.`id_res`=`r`.`id`
							WHERE (`r`.`status`='CONFIRMED' OR `r`.`status`='PENDING') AND `i`.`id_product`=`e`.`id` AND `o`.`id` IS NULL
						), 0
					)
				), (
					IFNULL(
						(
							SELECT SUM(`i`.`quantity`)
							FROM `#__cleverdine_takeaway_reservation` AS `r` 
							LEFT JOIN `#__cleverdine_takeaway_res_prod_assoc` AS `i` ON `i`.`id_res`=`r`.`id`
							WHERE (`r`.`status`='CONFIRMED' OR `r`.`status`='PENDING') AND `i`.`id_product`=`e`.`id` AND `i`.`id_product_option`=`o`.`id`
						), 0
					)
				)
			) AS `products_used`
			FROM `#__cleverdine_takeaway_menus_entry` AS `e`
			LEFT JOIN `#__cleverdine_takeaway_menus_entry_option` AS `o` ON `e`.`id` = `o`.`id_takeaway_menu_entry`
			$where_claus
			ORDER BY $ordering_column ".(($ordering['type'] == 2 ) ? 'DESC' : 'ASC');
	}
}
?>