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
class cleverdineViewtkstatstocks extends JViewUI {
	/**
	 * Restaurants view display method
	 * @return void
	 */
	function display($tpl = null) {
		// Set the toolbar
		$this->addToolBar();

		RestaurantsHelper::load_css_js();
		RestaurantsHelper::load_complex_select();
		RestaurantsHelper::load_charts();

		$mainframe 	= JFactory::getApplication();
		$input 		= $mainframe->input;
		$dbo 		= JFactory::getDbo();

		$start_day 		= cleverdine::createTimestamp($input->get('start_day', '', 'string'), 0, 0, true);
		$end_day 		= cleverdine::createTimestamp($input->get('end_day', '', 'string'), 23, 59, true);
		$id_menu 		= $input->get('id_menu', 0, 'uint');
		$keys_filter 	= $input->get('keysearch', '', 'string');

		// fetch dates range

		if( empty($start_day) || $start_day == -1 ) {
			$start_day = time();
		}
		if( empty($end_day) || $end_day == -1 ) {
			$end_day = time();
		}
		if( $start_day >= $end_day ) {
			$arr = getdate();
			$start_day = mktime(0, 0, 0, $arr['mon'], 1, $arr['year']);
			$end_day = mktime(0, 0, 0, $arr['mon']+1, 1, $arr['year'])-1;
		}

		// ORDERING AND FILTERS
		$ordering = OrderingManager::getColumnToOrder('tkstatstocks', 'products_used', 2);

		$filters = array(
			"start_day" => $start_day,
			"end_day" => $end_day,
			"id_menu" => $id_menu,
			"keysearch" => $keys_filter
		);

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
		$lim0 	= $input->get('limitstart', 0, 'uint');
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

		$new_type = OrderingManager::getSwitchColumnType( 'tkstatstocks', $ordering['column'], $ordering['type'], array( 1, 2 ) );
		$ordering = array( $ordering['column'] => $new_type );

		$filters['start_day'] = date(cleverdine::getDateFormat(true), $filters['start_day']);
		$filters['end_day'] = date(cleverdine::getDateFormat(true), $filters['end_day']);
		
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
		JToolbarHelper::title(JText::_('VRTKSTATSTOCKS'), 'restaurants');

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

		$start_day = $filters['start_day'];
		$end_day = $filters['end_day'];

		return "SELECT SQL_CALC_FOUND_ROWS `e`.`id` AS `eid`, `e`.`name` AS `ename`, `o`.`id` AS `oid`, `o`.`name` AS `oname`, CONCAT_WS(' ', `e`.`name`, `o`.`name`) AS `concat_name`, 
			IF(`o`.`id` IS NULL, 
				(
					IFNULL(
						(
							SELECT SUM(`i`.`quantity`)
							FROM `#__cleverdine_takeaway_reservation` AS `r` 
							LEFT JOIN `#__cleverdine_takeaway_res_prod_assoc` AS `i` ON `i`.`id_res`=`r`.`id`
							WHERE (`r`.`status`='CONFIRMED' OR `r`.`status`='PENDING') AND `r`.`checkin_ts` BETWEEN $start_day AND $end_day AND `i`.`id_product`=`e`.`id` AND `o`.`id` IS NULL
						), 0
					)
				), (
					IFNULL(
						(
							SELECT SUM(`i`.`quantity`)
							FROM `#__cleverdine_takeaway_reservation` AS `r` 
							LEFT JOIN `#__cleverdine_takeaway_res_prod_assoc` AS `i` ON `i`.`id_res`=`r`.`id`
							WHERE (`r`.`status`='CONFIRMED' OR `r`.`status`='PENDING') AND `r`.`checkin_ts` BETWEEN $start_day AND $end_day AND `i`.`id_product`=`e`.`id` AND `i`.`id_product_option`=`o`.`id`
						), 0
					)
				)
			) AS `products_used`
			FROM `#__cleverdine_takeaway_menus_entry` AS `e`
			LEFT JOIN `#__cleverdine_takeaway_menus_entry_option` AS `o` ON `e`.`id` = `o`.`id_takeaway_menu_entry`
			$where_claus
			HAVING `products_used` > 0
			ORDER BY ".$ordering['column']." ".(($ordering['type'] == 2 ) ? 'DESC' : 'ASC');
	}
}
?>