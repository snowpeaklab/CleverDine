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
 * Used to retrieve the cart instance from the session.
 * This class cannot be instantiated manually as we can have only one instance per session.
 *
 * @see 	TakeAwayCart 	TakeAway cart handler.
 *
 * @since  		1.6
 * @deprecated 	1.8 	Use TakeAwayCart instead.
 */
class VRTakeAwayCartCore
{	
	/**
	 * Get the instance of the TakeAwayCart object stored in the PHP session.
	 * 
	 * @param 	array 		$properties 		The settings array.
	 * @param 	boolean 	$create_instance 	@deprecated never used.
	 *
	 * @return 	TakeAwayCart 	The instance of the TakeAwayCart.
	 *
	 * @deprecated 	1.8 	Use TakeAwayCart::getInstance() instead.
	 */
	public function getCartObject($properties = array(), $create_instance = true)
	{
		return TakeAwayCart::getInstance(array(), $properties);
	}
	
	/**
	 * Store the cart instance into the PHP session.
	 *
	 * @param 	TakeAwayCart 	The cart object to store.
	 *
	 * @deprecated 	1.8 	Use TakeAwayCart::store() instead.
	 */
	public function storeCart($cart)
	{
		$cart->store();
	}
	
}
