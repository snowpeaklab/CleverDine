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
 * Used to handle the take-away item group into the cart.
 * This class wraps a list of toppings.
 *
 * @since 	1.7
 */
class TakeAwayItemGroup
{	
	/**
	 * The ID of the group.
	 *
	 * @var integer
	 */
	private $id_group;
	
	/**
	 * The title of the group.
	 *
	 * @var string
	 */
	private $title;

	/**
	 * If has multiple choises or only one.
	 *
	 * @var boolean
	 */
	private $multiple;	
	
	/**
	 * The list of topping chosen.
	 *
	 * @var array
	 */
	private $toppings = array();
	
	/**
	 * Class constructor.
	 *
	 * @param 	integer 	$id_group 	The group ID.
	 * @param 	string 		$title 		The group title.
	 * @param 	boolean 	$multiple 	True if group is multiple, otherwise is single.
	 */
	public function __construct($id_group, $title, $multiple)
	{
		$this->id_group = $id_group;
		$this->title 	= $title;
		$this->multiple = $multiple;
	}
	
	/**
	 * Get the ID of the group.
	 *
	 * @return 	integer 	The group ID.
	 */
	public function getGroupID()
	{
		return $this->id_group;
	}
	
	/**
	 * Get the title of the group.
	 *
	 * @return 	string 	The group title.
	 */
	public function getTitle()
	{
		return $this->title;
	}
	
	/**
	 * Check if the group is multiple: allow the selection of multiple toppings.
	 *
	 * @return 	boolean 	True if multiple, otherwise false.
	 */
	public function isMultiple()
	{
		return $this->multiple;
	}

	/**
	 * Check if the group is single: allow the selection of only one topping.
	 *
	 * @return 	boolean 	True if single, otherwise false.
	 *
	 * @uses 	isMultiple() 	Check if the group is multiple.
	 *
	 * @since 	1.7 
	 */
	public function isSingle()
	{
		return !$this->isMultiple();
	}
	
	/**
	 * Get the total cost of the group by summing the cost of each topping in the list.
	 *
	 * @return 	float 	The group total cost.
	 */
	public function getTotalCost()
	{
		$tcost = 0;

		foreach ($this->toppings as $t) {
			$tcost += $t->getRate();
		}

		return $tcost;
	}

	/**
	 * Get the index of the specified topping.
	 *
	 * @param 	TakeAwayItemGroupTopping 	$topping 	The topping to search for.
	 *
	 * @return 	integer 	The index of the topping on success, otherwise -1.
	 */
	public function indexOf(TakeAwayItemGroupTopping $topping)
	{
		foreach ($this->toppings as $k => $t) {
			if ($t->equalsTo($topping)) {
				return $k;
			}
		}

		return -1;
	}

	/**
	 * Push the specified topping into the list.
	 * It is possible to push a topping only if it is not yet contained in the list.
	 *
	 * @param 	TakeAwayItemGroupTopping 	$topping 	The topping to insert.
	 *
	 * @return 	boolean 	True on success, otherwise false.
	 *
	 * @uses 	indexOf() 	Check if the topping already exists.
	 */
	public function addTopping(TakeAwayItemGroupTopping $topping)
	{
		if ($this->indexOf($topping) === -1) {
			array_push($this->toppings, $topping);

			return true;
		}

		return false;
	}

	/**
	 * Reset the list by removing all the toppings.
	 *
	 * @return 	TakeAwayItemGroup 	This object to support chaining.
	 */
	public function emptyToppings()
	{
		$this->toppings = array();

		return $this;
	}
	
	/**
	 * Get the list containing all the toppings.
	 *
	 * @return 	array 	The list of toppings.
	 */
	public function getToppingsList()
	{
		return $this->toppings;
	}
	
	/**
	 * Check if this object is equal to the specified group.
	 * Two groups are equal if they have the same ID and the 
	 * toppings contained in both the lists are the same.
	 *
	 * @param 	TakeAwayItemGroup 	$group 	The group to check.
	 *
	 * @return 	boolean 	True if the 2 objects are equal, otherwise false.
	 *
	 * @uses 	getGroupID() 		Return the ID of the group.
	 * @uses 	getToppingsList() 	Return the list of the toppings.
	 */
	public function equalsTo(TakeAwayItemGroup $group)
	{
		if ($this->getGroupID() != $group->getGroupID()) {
			return false;
		}

		$l1 = $this->getToppingsList();
		$l2 = $group->getToppingsList();

		if (count($l1) != count($l2)) {
			return false;
		}

		$ok = true;

		// repeat until the count is reached or there is a different topping.
		for ($i = 0; $i < count($l1) && $ok; $i++) {

			$inner_ok = false;
			// repeat until the topping is found.
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
	 * Magic toString method to debug the group contents.
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
	 * Method to debug the group contents.
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
 * Deprecated class placeholder. You should use TakeAwayItemGroup instead.
 *
 * @since 		1.6
 * @deprecated 	1.8
 */
class VRTakeAwayItemGroup extends TakeAwayItemGroup
{
	/**
	 * Class constructor.
	 *
	 * @param 	integer 	$id_group 	The group ID.
	 * @param 	string 		$title 		The group title.
	 * @param 	boolean 	$multiple 	True if group is multiple, otherwise is single.
	 *
	 * @deprecated 	1.8
	 */
	public function __construct($id_group, $title, $multiple)
	{
		JLog::add('VRTakeAwayItemGroup is deprecated. Use TakeAwayItemGroup instead.', JLog::WARNING, 'deprecated');
		parent::__construct($id_group, $title, $multiple);
	}
}
