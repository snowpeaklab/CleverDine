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
 * Used to handle the take-away item group topping into the cart.
 *
 * @since 	1.7
 */
class TakeAwayItemGroupTopping
{	
	/**
	 * The ID of the topping.
	 *
	 * @var integer
	 */
	private $id_topping;

	/**
	 * The Associative ID of the topping.
	 * This ID is needed to know the parent group.
	 *
	 * @var integer
	 */
	private $id_assoc;

	/**
	 * The name of the topping.
	 *
	 * @var string
	 */
	private $name;

	/**
	 * The cost of the topping.
	 *
	 * @var float
	 */
	private $rate;
	
	/**
	 * Class constructor.
	 *
	 * @param 	integer 	$id_topping 	The ID of the topping.
	 * @param 	integer 	$id_assoc 		The associative ID of the topping.
	 * @param 	string 		$name 			The name of the topping.
	 * @param 	float 		$rate 			The cost of the topping.
	 */
	public function __construct($id_topping, $id_assoc, $name, $rate)
	{
		$this->id_topping 	= $id_topping;
		$this->id_assoc 	= $id_assoc;
		$this->name 		= $name;
		$this->rate 		= $rate;
	}
	
	/**
	 * Get the ID of the topping.
	 *
	 * @return 	integer 	The topping ID.
	 */
	public function getToppingID()
	{
		return $this->id_topping;
	}

	/**
	 * Get the associative ID of the topping.
	 * This ID chain the topping to its parent group.
	 *
	 * @return 	integer 	The topping assoc ID.
	 */
	public function getAssocID()
	{
		return $this->id_assoc;
	}
	
	/**
	 * Get the name of the topping.
	 *
	 * @return 	string 	The topping name.
	 */
	public function getName()
	{
		return $this->name;
	}
	
	/**
	 * Get the cost of the topping.
	 *
	 * @return 	float 	The topping cost.
	 */
	public function getRate()
	{
		return $this->rate;
	}
	
	/**
	 * Check if this object is equal to the specified topping.
	 * Two toppings are equal if they have the same ID.
	 *
	 * @param 	TakeAwayItemGroupTopping 	$topping 	The topping to check.
	 *
	 * @return 	boolean 	True if the 2 objects are equal, otherwise false.
	 *
	 * @uses 	getToppingID() 	Return the ID of the topping.
	 */
	public function equalsTo(TakeAwayItemGroupTopping $topping)
	{
		return ($this->getToppingID() == $topping->getToppingID());
	} 
	
	/**
	 * Magic toString method to debug the topping contents.
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
	 * Method to debug the topping contents.
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
 * Deprecated class placeholder. You should use TakeAwayItemGroupTopping instead.
 *
 * @since 		1.6
 * @deprecated 	1.8
 */
class VRTakeAwayItemGroupTopping extends TakeAwayItemGroupTopping
{
	/**
	 * Class constructor.
	 *
	 * @param 	integer 	$id_topping 	The ID of the topping.
	 * @param 	integer 	$id_assoc 		The associative ID of the topping.
	 * @param 	string 		$name 			The name of the topping.
	 * @param 	float 		$rate 			The cost of the topping.
	 *
	 * @deprecated 	1.8
	 */
	public function __construct($id_topping, $id_assoc, $name, $rate)
	{
		JLog::add('VRTakeAwayItemGroupTopping is deprecated. Use TakeAwayItemGroupTopping instead.', JLog::WARNING, 'deprecated');
		parent::__construct($id_topping, $id_assoc, $name, $rate);
	}
}
