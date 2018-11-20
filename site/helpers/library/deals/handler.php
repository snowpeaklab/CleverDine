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

/**
 * Used to handle the deals stored in the database.s
 *
 * @see 	JFactory 	Joomla base factory to access database resource.
 *
 * @since  	1.6
 * @since 	1.7 	Renamed from VRDealsHandler
 */
class DealsHandler
{
	/**
	 * Get all the available deals between the selected date. Target products and Gift products are not included.
	 * @usedby 	DealsHandler::getAvailableFullDeals()
	 *
	 * @param 	integer 	$ts 	The timestamp of the selected date. 
	 * 								Use -1 to skip date filtering.
	 * @param 	integer 	$type 	The value of the type to filter deals (1 to 6 only). 
	 * 								Use -1 to skip type filtering.
	 *
	 * @return 	array 		The list containing all the deals found.
	 */
	public static function getAvailableDeals($ts, $type = -1)
	{	
		$date = getdate($ts);
		
		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true);

		$q->select('`deal`.*, `day`.`id_weekday`')
			->from($dbo->quoteName('#__cleverdine_takeaway_deal', 'deal'))
			->join('LEFT', $dbo->quoteName('#__cleverdine_takeaway_deal_day_assoc', 'day') . ' ON `deal`.`id`=`day`.`id_deal`')
			->where('`deal`.`published` = 1');

		if ($ts != -1) {
			$q->where("(`deal`.`start_ts`=-1 OR $ts BETWEEN `deal`.`start_ts` AND `deal`.`end_ts`+86400)");
		}

		if ($type != -1) {
			$q->where("`deal`.`type`=$type");
		}

		$q->order('`deal`.`ordering` ASC, `day`.`id_weekday` ASC');
		
		$dbo->setQuery($q);
		$dbo->execute();

		if ($dbo->getNumRows() > 0) {

			$arr = array();
			$last_deal_id = -1;

			foreach ($dbo->loadAssocList() as $deal) {
				if ($last_deal_id != $deal['id']) {
					$deal['days_filter'] = array();
					array_push($arr, $deal);
					$last_deal_id = $deal['id'];
				}
				
				array_push($arr[count($arr)-1]['days_filter'], $deal['id_weekday']);
				
				if ($deal['id_weekday'] == $date['wday']) {
					$arr[count($arr)-1]['active'] = 1;
				}
				
			}
			
			$deals = array();
			foreach ($arr as $a) {
				if ($ts == -1 || !empty($a['active'])) {
					array_push($deals, $a);
				}
			}
			
			return $deals;
		}
		
		return array();	
	}
	
	/**
	 * Get all the available deals between the selected date. Target products and Gift products are included.
	 *
	 * @param 	integer 	$ts 	The timestamp of the selected date. 
	 * 								Use -1 to skip date filtering.
	 * @param 	integer 	$type 	The value of the type to filter deals (1 to 6 only). 
	 * 								Use -1 to skip type filtering.
	 *
	 * @return 	array 		The list containing all the deals found.
	 *
	 * @uses 	getAvailableDeals() 	Retreive the available deals to fill targets and gifts.
	 */
	public static function getAvailableFullDeals($ts, $type = -1)
	{
		$deals = self::getAvailableDeals($ts, $type);
		
		if (!count($deals)) {
			return array();
		}
		
		$dbo = JFactory::getDbo();
		
		foreach ($deals as $k => $deal) {

			$q = $dbo->getQuery(true);

			$q->select('*')
				->from($dbo->quoteName('#__cleverdine_takeaway_deal_product_assoc'))
				->where($dbo->quoteName('id_deal') . ' = ' . $deal['id']);

			$dbo->setQuery($q);
			$dbo->execute();

			if ($dbo->getNumRows() > 0) {
				$deals[$k]['products'] = $dbo->loadAssocList();
			} else {
				$deals[$k]['products'] = array();
			}

			//

			$q = $dbo->getQuery(true);

			$q->select('`g`.*, `p`.`name` AS `product_name`, `p`.`price` AS `product_price`, `p`.`ready`, `p`.`id_takeaway_menu`, 
			`o`.`name` AS `option_name`, `o`.`inc_price` AS `option_price`')
				->from($dbo->quoteName('#__cleverdine_takeaway_deal_free_assoc', 'g'))
				->join('LEFT', $dbo->quoteName('#__cleverdine_takeaway_menus_entry', 'p') . ' ON `g`.`id_product`=`p`.`id`')
				->join('LEFT', $dbo->quoteName('#__cleverdine_takeaway_menus_entry_option', 'o') . ' ON `g`.`id_option`=`o`.`id`')
				->where('`g`.`id_deal` = ' . $deal['id']);

			$dbo->setQuery($q);
			$dbo->execute();

			if ($dbo->getNumRows() > 0) {
				$deals[$k]['gifts'] = $dbo->loadAssocList();
			} else {
				$deals[$k]['gifts'] = array();
			}
		}
		
		return $deals;
	}

	/**
	 * This method sort the deals by pushing all the unactive items at the bottom.
	 *
	 * @param 	array 	$deals 	The list containing the deals to sort.
	 *
	 * @return 	array 	The deals sorted.
	 */
	public static function reOrderActiveDeals(array $deals)
	{
		if (count($deals) <= 1) {
			return $deals;
		}
		
		$active_d = array();
		$not_active_d = array();

		foreach ($deals as $deal) {
			if (!empty($deal['active'])) {
				array_push($active_d, $deal);
			} else {
				array_push($not_active_d, $deal);
			}
		}
		
		return array_merge($active_d, $not_active_d);
	}
	
	/**
	 * Return the index of the deal which matches the specified parameters. 
	 * 
	 * @param 	array 	$matches  	The associative array containing all the keys to match.
	 * @param 	array 	$deals 		The array containing all the available deals. 
	 * 								The deals should be retrieved with the method getAvailableFullDeals.
	 *
	 * @return 	integer 	The index of the deal found, otherwise false.
	 * 
	 * @see 	getAvailableFullDeals() to retrieve deals properly.
	 */
	public static function isProductInDeals(array $matches, array $deals)
	{
		$keys_matches = array_keys($matches);

		if (!count($keys_matches)) {
			return false;
		}
		
		foreach ($deals as $index => $deal) {

			foreach ($deal['products'] as $prod) {
				$found = true;
				
				for ($i = 0; $i < count($keys_matches) && $found; $i++) {
					$found = $found && (
						$matches[$keys_matches[$i]] == $prod[$keys_matches[$i]]
						// ignore id_option match when deal product doesn't specify it
						// all the options of the entry will be taken
						|| ($keys_matches[$i] == 'id_option' && $prod['id_option'] == -1)  
					);
				}

				if ($found) {
					return $index;
				}
			}

		}
		
		return false;
	}
	
}
