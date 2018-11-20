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
 * Used to handle the take-away item into the cart.
 *
 * @see 	TakeAwayItemGroup 	The details of the topping group.
 *
 * @since 	1.7
 */
class TakeAwayItem
{	
	/**
	 * The ID of the menu to which the item belongs.
	 *
	 * @var integer
	 */
	private $id_takeaway_menu;
	
	/**
	 * The ID of the item.
	 *
	 * @var integer
	 */
	private $id_item;
	
	/**
	 * The ID of the variation.
	 * Specify 0 or -1 if the item has no variations.
	 *
	 * @var integer.
	 */
	private $id_var;

	/**
	 * The name of the item.
	 *
	 * @var string
	 */
	private $name_item;
	
	/**
	 * The name of the variation.
	 * Empty if the item has no variations.
	 *
	 * @var string
	 */
	private $name_var;
	
	/**
	 * The sum of the item and variation prices without discounts.
	 *
	 * @var float
	 */
	private $original_price;
	
	/**
	 * The original price with eventual discounts.
	 *
	 * @var float
	 */
	private $price;
	
	/**
	 * The quantity of the item.
	 *
	 * @var integer
	 */
	private $quantity;
	
	/**
	 * If the item requires a preparation or not.
	 *
	 * @var boolean
	 */
	private $ready;
	
	/**
	 * The taxes ratio of the item. Defined from the settings of the menu.
	 *
	 * @var float
	 */
	private $taxes;

	/**
	 * The additional notes and requirements for this item.
	 *
	 * @var string
	 */
	private $additional_notes;	
	
	/**
	 * The list containing the toppings for this item.
	 *
	 * @var array
	 */
	private $toppings_groups = array();
	
	/**
	 * The number of times the deal has been redeemed.
	 *
	 * @var integer
	 */
	private $deal_quantity = 0;

	/**
	 * The ID of the deal redeemed.
	 *
	 * @var integer
	 */
	private $id_deal = -1;

	/**
	 * If the item can be removed from the cart or not.
	 * For example, an item cannot be removed in case it has been added with a deal.
	 *
	 * @var boolean
	 */
	private $can_be_removed = true;
	
	/**
	 * Class constructor.
	 *
	 * @param 	integer 	$id_menu 	The item menu ID.
	 * @param 	integer 	$id_item 	The item ID
	 * @param 	integer 	$id_var 	The item variation ID.
	 * @param 	string 		$name_item 	The item name.
	 * @param 	string 		$name_var 	The item variation name.
	 * @param 	float 		$price 		The item price.
	 * @param 	integer 	$quantity 	The item quantity.
	 * @param 	boolean 	$ready 		True if doesn't require a preparation, otherwise false.
	 * @param 	float 		$taxes 		The item taxes ratio.
	 * @param 	$string 	$notes 		The item additional notes.
	 */
	public function __construct($id_menu, $id_item, $id_var, $name_item, $name_var, $price, $quantity, $ready, $taxes, $notes)
	{
		$this->id_takeaway_menu 	= $id_menu;
		$this->id_item 				= $id_item;
		$this->id_var 				= $id_var;
		$this->name_item 			= $name_item;
		$this->name_var 			= $name_var;
		$this->price 				= abs($price);
		$this->original_price 		= $this->price;
		$this->quantity 			= max(array(1, abs($quantity)));
		$this->ready 				= $ready;
		$this->taxes 				= abs($taxes);
		$this->additional_notes 	= $notes;
	}
	
	/**
	 * Get the ID of the item menu.
	 *
	 * @return 	integer 	The item menu ID.
	 */
	public function getMenuID()
	{
		return $this->id_takeaway_menu;
	}
	
	/**
	 * Get the ID of the item.
	 *
	 * @return 	integer 	The item ID.
	 */
	public function getItemID()
	{
		return $this->id_item;
	}
	
	/**
	 * Get the ID of the item variation.
	 *
	 * @return 	integer 	The item variation ID.
	 */
	public function getVariationID()
	{
		return $this->id_var;
	}
	
	/**
	 * Get the name of the item.
	 *
	 * @return 	string 	The item name.
	 */
	public function getItemName()
	{
		return $this->name_item;
	}
	
	/**
	 * Get the name of the item variation.
	 *
	 * @return 	string 	The item variation name.
	 */
	public function getVariationName()
	{
		return (!empty($this->name_var) ? $this->name_var : "");
	}

	/**
	 * Get the full name of the item.
	 * Concatenate the item name and the variation name, separated by the given string.
	 *
	 * @param 	string 	$separator 	The separator string between the names.
	 *
	 * @return 	string 	The item full name.
	 */
	public function getFullName($separator = '')
	{
		if (empty($separator)) {
			$separator = ' = ';
		}

		return $this->name_item.(!empty($this->name_var) ? $separator.$this->name_var : '');
	}
	
	/**
	 * Get the real price of the item.
	 *
	 * @return 	float 	The item real price.
	 */
	public function getPrice()
	{
		return floatval($this->price);
	}
	
	/**
	 * Get the original price of the item.
	 *
	 * @return 	float 	The item original price.
	 */
	public function getOriginalPrice()
	{
		return floatval($this->original_price);
	}
	
	/**
	 * Set the price of the item.
	 *
	 * @param 	float 	$price 	The item price.
	 *
	 * @return 	TakeAwayItem 	This object to support chaining.
	 */
	public function setPrice($price)
	{
		$this->price = max(array(0, $price));

		return $this;
	}
	
	/**
	 * Set the deal quantity used.
	 *
	 * @param 	integer 	$deal_q 	The deal quantity.
	 *
	 * @return 	TakeAwayItem 	This object to support chaining.
	 */
	public function setDealQuantity($deal_q)
	{
		$this->deal_quantity = $deal_q;

		return $this;
	}
	
	/**
	 * Get the deal quantity used.
	 *
	 * @return 	integer 	The deal quantity.
	 */
	public function getDealQuantity()
	{
		return $this->deal_quantity;
	}
	
	/**
	 * Get the quantity of the item.
	 *
	 * @return 	integer 	The item quantity.
	 */
	public function getQuantity()
	{
		return $this->quantity;
	}
	
	/**
	 * Set the quantity of the item.
	 *
	 * @param 	integer 	The item quantity.
	 *
	 * @return 	TakeAwayItem 	This object to support chaining.
	 */
	public function setQuantity($units)
	{
		$this->quantity = max(array(0, abs($units)));

		return $this;
	}
	
	/**
	 * Increase the quantity of the item by the specified units.
	 *
	 * @param 	integer 	$units 	The units to add.
	 *
	 * @return 	TakeAwayItem 	This object to support chaining.
	 */
	public function push($units = 1)
	{
		$this->quantity += $units;

		return $this;
	}
	
	/**
	 * Decrease the quantity of the item by the specified units.
	 *
	 * @param 	integer 	$units 	The units to remove.
	 *
	 * @return 	TakeAwayItem 	This object to support chaining.
	 *
	 * @uses 	emptyItem() 	Reset quantity in case the units to remove are more than the quantity available.
	 */
	public function remove($units = 1)
	{
		$this->quantity -= $units;

		if ($this->quantity < 0) {
			$this->emptyItem();
		}

		return $this;
	}
	
	/**
	 * Reset the quantity of the item.
	 *
	 * @return 	TakeAwayItem 	This object to support chaining.
	 */
	public function emptyItem()
	{
		$this->quantity = 0;

		return $this;
	}
	
	/**
	 * Check if the item doesn't need a preparation.
	 *
	 * @return 	boolean 	True if the item is ready, otherwise false.
	 */
	public function isReady()
	{
		return $this->ready;
	}

	/**
	 * Check if the item need a preparation.
	 *
	 * @return 	boolean 	True if the item needs a preparation, otherwise false.
	 *
	 * @uses 	isReady() 	Check if the item is ready.
	 *
	 * @since 	1.7	
	 */
	public function needPreparation()
	{
		return !$this->isReady();
	}

	/**
	 * Get the taxes ratio of the item.
	 *
	 * @return 	float 	The item taxes ratio.
	 */
	public function getTaxesRatio()
	{
		return $this->taxes;
	}
	
	/**
	 * Get the additional notes of the item.
	 *
	 * @return 	string 	The item additional notes.
	 */
	public function getAdditionalNotes()
	{
		return $this->additional_notes;
	}
	
	/**
	 * Set the additional notes of the item.
	 *
	 * @param 	string 	The item additional notes.
	 *
	 * @return 	TakeAwayItem 	This object to support chaining.
	 */
	public function setAdditionalNotes($notes)
	{
		$this->additional_notes = $notes;

		return $this;
	}
	
	/**
	 * Set the deal ID.
	 *
	 * @param 	integer 	The deal ID.
	 *
	 * @return 	TakeAwayItem 	This object to support chaining.
	 */
	public function setDealID($id_deal)
	{
		$this->id_deal = $id_deal;

		return $this;
	}
	
	/**
	 * Get the deal ID.
	 *
	 * @return 	integer 	The deal ID.
	 */
	public function getDealID()
	{
		return $this->id_deal;
	}

	/**
	 * Check if the item can be removed.
	 *
	 * @return 	boolean 	True if the item can be removed, otherwise false.
	 */
	public function canBeRemoved()
	{
		return $this->can_be_removed;
	}
	
	/**
	 * Set if the item can be removed.
	 *
	 * @param 	boolean 	True if the item can be removed, otherwise false.
	 *
	 * @return 	TakeAwayItem 	This object to support chaining.
	 */
	public function setRemovable($s)
	{
		$this->can_be_removed = $s;

		return $this;
	}
	
	/**
	 * Get the total cost of the item without discounts.
	 * Calculated by summing the original price with the sum of the toppings total cost.
	 *
	 * @return 	float 	The item total cost with no discount.
	 *
	 * @uses 	getOriginalPrice() 	Get the price with no discount.
	 * @uses 	getQuantity() 		Count the number of items to multiply the price.
	 */
	public function getTotalCostNoDiscount()
	{
		$tprice = $this->getOriginalPrice();

		foreach ($this->toppings_groups as $group) {
			$tprice += $group->getTotalCost();
		}

		return $tprice*$this->getQuantity();
	}
	
	/**
	 * Get the total cost of the item considering discounts.
	 * Calculated by summing the original price with the sum of the toppings total cost.
	 * Then subtract the discounts in case there is at least a deal.
	 *
	 * @return 	float 	The real item total cost.
	 *
	 * @uses 	getOriginalPrice() 	Get the price with no discount.
	 * @uses 	getPrice() 			Get the price with discounts.
	 * @uses 	getQuantity() 		Count the number of items to multiply the price.
	 */
	public function getTotalCost()
	{
		$tprice = 0;

		if ($this->deal_quantity == 0) {
			$tprice = $this->getPrice()*$this->getQuantity();
		} else {
			$diff = max(array(0, $this->getQuantity()-$this->deal_quantity));
			$tprice = $this->getPrice()*$this->deal_quantity+$this->getOriginalPrice()*$diff;
		}

		foreach ($this->toppings_groups as $group) {
			$tprice += $group->getTotalCost()*$this->getQuantity();
		}

		return $tprice;
	}

	/**
	 * Get the taxes amount of the item.
	 *
	 * @param 	boolean 	$use_taxes 	True if taxes are escluded, otherwise false.
	 * 
	 * @return 	float 	The item taxes amount.
	 *
	 * @uses 	getTotalCost() 		Get the total cost to calculate the taxes.
	 * @uses 	getTaxesRatio() 	Get the taxes ratio.
	 */
	public function getTaxes($use_taxes = false)
	{
		$total_cost = $this->getTotalCost();

		if ($use_taxes) {
			return $total_cost * $this->getTaxesRatio() / 100.0;
		}

		// get taxes from total price
		return $total_cost - $total_cost * 100 / (100 + $this->getTaxesRatio());
	}
	
	/**
	 * Empty the topping groups of the item.
	 * 
	 * @return 	TakeAwayItem 	This object to support chaining.
	 */
	public function emptyGroups()
	{
		$this->toppings_groups = array();

		return $this;
	}

	/**
	 * Get the index of the specified topping group.
	 *
	 * @param 	TakeAwayItemGroup 	$group 	The group to search for.
	 *
	 * @return 	integer 	The index of the group on success, otherwise -1. 
	 */
	public function indexOf(TakeAwayItemGroup $group)
	{
		foreach ($this->toppings_groups as $k => $g) {
			if ($g->equalsTo($group)) {
				return $k;
			}
		}

		return -1;
	}

	/**
	 * Push the specified topping group into the list.
	 * It is possible to push a topping group only if it is not yet contained in the list.
	 *
	 * @param 	TakeAwayItemGroup 	$group 	The group to insert.
	 *
	 * @return 	boolean 	True on success, otherwise false.
	 *
	 * @uses 	indexOf() 	Check if the group already exists.
	 */
	public function addToppingsGroup(TakeAwayItemGroup $group)
	{
		if ($this->indexOf($group) == -1) {
			array_push($this->toppings_groups, $group);

			return true;
		}

		return false;
	}
	
	/**
	 * Get the list containing all the topping groups.
	 *
	 * @return 	array 	The list of topping groups.
	 */
	public function getToppingsGroupsList()
	{
		return $this->toppings_groups;
	}
	
	/**
	 * Check if this object is equal to the specified item.
	 * Two items are equal if they have the same ID,
	 * the same variation ID,
	 * the same additional notes and the 
	 * groups contained in both the lists are the same.
	 *
	 * @param 	TakeAwayItem 	$item 	The item to check.
	 *
	 * @return 	boolean 	True if the 2 objects are equal, otherwise false.
	 *
	 * @uses 	getItemID() 			Return the ID of the item.
	 * @uses 	getVariationID() 		Return the ID of the item variation.
	 * @uses 	getAdditionalNotes() 	Return the additional notes of the item.
	 * @uses 	getToppingsGroupsList() Return the list of the groups.
	 */
	public function equalsTo(TakeAwayItem $item)
	{
		if ($this->getItemID() != $item->getItemID() 
			|| $this->getVariationID() != $item->getVariationID() 
			|| $this->getAdditionalNotes() != $item->getAdditionalNotes() 
		) {
			return false;
		}
			
		$l1 = $this->getToppingsGroupsList();
		$l2 = $item->getToppingsGroupsList();

		if (count($l1) != count($l2)) {
			return false;
		}

		$ok = true;

		// repeat until the count is reached or there is a different group.
		for ($i = 0; $i < count($l1) && $ok; $i++) {

			// repeat until the group is found.
			$inner_ok = false;
			for ($j = 0; $j < count($l2) && !$inner_ok; $j++) {
				// if true, break the statement
				$inner_ok = $l1[$i]->equalsTo($l2[$j]);
			}

			// update the main flag with the last result.
			// if true, continue with the search, otherwise break the for.
			$ok = $inner_ok;

		}

		return $ok;
	}
	
	/**
	 * Magic toString method to debug the item contents.
	 *
	 * @return  string  The debug string of this object.
	 *
	 * @since   1.7
	 */
	public function __toString()
	{
		return '<pre>'.print_r($this, true).'</pre>';
	}

	/**
	 * Method to debug the item contents.
	 *
	 * @return  string  The debug string of this object.
	 *
	 * @deprecated  1.8  Use __toString() magic method instead.
	 */
	public function toString()
	{
		return $this->__toString();
	}
	
}

/**
 * Deprecated class placeholder. You should use TakeAwayItem instead.
 *
 * @since 		1.6
 * @deprecated 	1.8
 */
class VRTakeAwayItem extends TakeAwayItem
{
	/**
	 * Class constructor.
	 *
	 * @param 	integer 	$id_menu 	The item menu ID.
	 * @param 	integer 	$id_item 	The item ID
	 * @param 	integer 	$id_var 	The item variation ID.
	 * @param 	string 		$name_item 	The item name.
	 * @param 	string 		$name_var 	The item variation name.
	 * @param 	float 		$price 		The item price.
	 * @param 	integer 	$quantity 	The item quantity.
	 * @param 	boolean 	$ready 		True if doesn't require a preparation, otherwise false.
	 * @param 	float 		$taxes 		The item taxes ratio.
	 * @param 	$string 	$notes 		The item additional notes.
	 *
	 * @deprecated 	1.8
	 */
	public function __construct($id_menu, $id_item, $id_var, $name_item, $name_var, $price, $quantity, $ready, $taxes, $notes)
	{
		JLog::add('VRTakeAwayItem is deprecated. Use TakeAwayItem instead.', JLog::WARNING, 'deprecated');
		parent::__construct($id_menu, $id_item, $id_var, $name_item, $name_var, $price, $quantity, $ready, $taxes, $notes);
	}
}
