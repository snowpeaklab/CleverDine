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
 * Used to handle the take-away deals into the cart.
 * This class wraps a list of TakeAwayDiscount objects
 *
 * @see 	TakeAwayDiscount 	The details of the deal.
 *
 * @since 	1.7
 */
class TakeAwayDeals
{	
	/**
	 * The list containing all the current discounts.
	 *
	 * @var array
	 */
	private $discounts = array();
	
	/**
	 * Class constructor.
	 *
	 * @param 	array 	$discounts 	The list of deals to push.
	 * 
	 * @uses 	setDiscounts() 	Set the list of discounts with the specified array.
	 *
	 * @since 	1.7
	 */
	public function __construct(array $discounts = array())
	{
		$this->setDiscounts($discounts);
	}
	
	/**
	 * Push a new discount in the list.
	 * If the discount already exists, increase its quantity.
	 *
	 * @param 	TakeAwayDiscount 	$discount 	The details of the deal to push.
	 *
	 * @return 	TakeAwayDeals 	This object to support chaining.
	 *
	 * @uses 	indexOf() 	Get the index of the specified discount.
	 */
	public function insert(TakeAwayDiscount $discount)
	{
		$index = $this->indexOf($discount);

		if ($index != -1) {
			$this->discounts[$index]->addQuantity($discount->getQuantity());
		} else {
			array_push($this->discounts, $discount);
		}

		return $this;
	}
	
	/**
	 * Set or replace the discount at the specified position.
	 *
	 * @param 	TakeAwayDiscount 	$discount 	The details of the deal to set.
	 * @param  	integer 			$index 		The index of the deal to replace.
	 *
	 * @return 	TakeAwayDeals 	This object to support chaining.
	 *
	 * @uses 	getSize() 	Get the count of discounts in the list.
	 * @uses 	insert() 	Push the item at the end in case the index is out of bounds.
	 */
	public function set(TakeAwayDiscount $discount, $index)
	{
		if (isset($this->discounts[$index])) {
			$this->discounts[$index] = $discount;
		} else {
			$this->insert($discount);
		}

		return $this;
	}

	/**
	 * Set the disocunts list with the specified one.
	 *
	 * @param 	array 	$discounts 	The discounts array.
	 * 								Each element must be an instance of TakeAwayDiscount,
	 * 								otherwise it will be ignored.
	 *
	 * @return 	TakeAwayDeals 	This object to support chaining.
	 *
	 * @uses 	emptyDiscounts() 	Flush the internal list.
	 * @uses 	insert() 			Push each item of the array into the internal list.
	 *
	 * @since 	1.7
	 */
	public function setDiscounts(array $discounts)
	{
		$this->emptyDiscounts();

		foreach ($discounts as $d) {
			if ($d instanceof TakeAwayDiscount) {
				$this->insert($d);
			}
		}

		return $this;
	}
	
	/**
	 * Get the discount at the specified index.
	 *
	 * @return 	TakeAwayDiscount 	The discount at the specified index if exists, otherwise null.
	 *
	 * @uses 	getSize() 	Get the count of discounts in the list.
	 */
	public function get($index)
	{
		if (isset($this->discounts[$index])) {
			return $this->discounts[$index];
		}

		return null;
	}

	/**
	 * Get the index of the specified discount.
	 *
	 * @param 	TakeAwayDiscount 	$discount 	The discount to search for.
	 *
	 * @return 	integer 	The index of the discount on success, otherwise -1.
	 */
	public function indexOf(TakeAwayDiscount $discount)
	{
		foreach ($this->discounts as $k => $d) {
			if ($discount->equalsTo($d)) {
				return $k;
			}
		}

		return -1;
	}

	/**
	 * Get the index of the first discount that as same type of the specified one.
	 *
	 * @param 	TakeAwayDiscount 	$discount 	The discount to search for.
	 *
	 * @return 	integer 	The index of the discount on success, otherwise -1.
	 */
	public function indexOfType(TakeAwayDiscount $discount)
	{
		foreach ($this->discounts as $k => $d) {
			if ($discount->sameType($d)) {
				return $k;
			}
		}

		return -1;
	}

	/**
	 * Remove the discount at the specified index.
	 *
	 * @param 	integer 	$index 	The index of the deal to remove.
	 *
	 * @return 	boolean 	True on success, otherwise false.
	 *
	 * @uses 	getSize() 	Get the count of discounts in the list.
	 */
	public function removeAt($index)
	{
		if (isset($this->discounts[$index])) {
			unset($this->discounts[$index]);

			return true;
		}

		return false;
	}

	/**
	 * Remove an existing discount from the list.
	 *
	 * @param 	TakeAwayDiscount 	$discount 	The deal to remove.
	 *
	 * @return 	boolean 	True on success, otherwise false.
	 *
	 * @uses 	indexOf() 	Get the index of the specified discount.
	 * @uses 	removeAt() 	Remove the discount at the index found.
	 */
	public function remove(TakeAwayDiscount $discount)
	{
		$index = $this->indexOf($discount);

		return $this->removeAt($index);
	}
	
	/**
	 * Get the list containing all the valid discounts.
	 *
	 * @return 	array 	The list of deals.
	 */
	public function getDiscountsList()
	{
		$list = array();

		foreach ($this->discounts as $d) {
			if ($d->getQuantity() > 0) {
				array_push($list, $d);
			}
		}
		
		return $list;
	}

	/**
	 * Get the total count of discounts in the list.
	 *
	 * @return 	integer 	The size of the list.
	 */

	public function getSize()
	{
		return count(array_keys($this->discounts));
	}
	
	/**
	 * Reset the list by removing all the discounts.
	 *
	 * @return 	TakeAwayDeals 	This object to support chaining.
	 */
	public function emptyDiscounts()
	{
		$this->discounts = array();

		return $this;
	}
	
	/**
	 * Magic toString method to debug the deals contents.
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
	 * Method to debug the deals contents.
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
 * Deprecated class placeholder. You should use TakeAwayDeals instead.
 *
 * @since 		1.6
 * @deprecated 	1.8
 */
class VRTakeAwayDeals extends TakeAwayDeals
{
	/**
	 * Class constructor.
	 *
	 * @param 	array 	$discounts 	The list of deals to push.
	 * 
	 * @uses 	setDiscounts() 	Set the list of discounts with the specified array.
	 *
	 * @deprecated 	1.8
	 */
	public function __construct(array $discounts = array())
	{
		JLog::add('VRTakeAwayDeals is deprecated. Use TakeAwayDeals instead.', JLog::WARNING, 'deprecated');
		parent::__construct($discounts);
	}
}
