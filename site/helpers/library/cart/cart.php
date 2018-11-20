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
 * Used to handle the take-away cart of the program.
 * This class cannot be instantiated manually as we can have only one instance per session.
 *
 * Usage:
 * $cart = TakeAwayCart::getInstance();
 *
 * or:
 * $items 	= array();
 * $config 	= array('max_size' => 20);
 *
 * $cart = TakeAwayCart::getInstance($items, $config);
 *
 * @see 	JFactory 		Joomla Factory to handle the session object.
 * @see 	TakeAwayItem 	To handle take-away item objects.
 * @see 	TakeAwayDeals 	To handle a list of deals.
 *
 * @since 	1.7
 */
class TakeAwayCart
{	
	/**
	 * The list containing the TakeAwayItem objects.
	 *
	 * @var array
	 */
	private $cart = array();

	/**
	 * The object to handle all the deals found.
	 *
	 * @var TakeAwayDeals
	 */
	private $deals;
	
	/**
	 * The checkin timestamp of the order.
	 *
	 * @var integer
	 */
	private $checkin_ts = -1;
	
	/**
	 * The array containing all the settings of the cart.
	 * Supported keys: 
	 * @param maxsize 	integer 	The max number of items, or -1 for unlimited size.
	 *
	 * @var array
	 */
	private $params = array(
		self::MAX_SIZE => self::UNLIMITED,
	);

	/**
	 * The instance of the Cart.
	 * There should be only one cart instance for the whole session.
	 *
	 * @var TakeAwayCart
	 *
	 * @since 1.7
	 */
	private static $instance = null;
	
	/**
	 * Class constructor.
	 *
	 * @param 	array 	$cart 	The array containing all the items to push.
	 * @param 	array 	$params The settings array.
	 *
	 * @uses 	setCart 		Items array setter.
	 * @uses 	setParams 		Settings array setter.
	 *
	 * @since 	1.7 	This method is no more accessible. 
	 */
	protected function __construct(array $cart = array(), array $params = array())
	{
		// this method can be accessed only internally.
		
		// init this method before setCart() to avoid errors.
		$this->deals = new TakeAwayDeals();

		$this->setCart($cart)
			->setParams($params);
	}

	/**
	 * Class cloner.
	 *
	 * @since 	1.7 	This method is no more accessible. 
	 */
	protected function __clone()
	{
		// this method is not accessible
	}

	/**
	 * Get the instance of the TakeAwayCart object.
	 * If the instance is not yet available, create a new one.
	 * 
	 * @param 	array 	$cart 	The array containing all the items to push.
	 * @param 	array 	$params The settings array.
	 *
	 * @return 	TakeAwayCart 	The instance of the TakeAwayCart.
	 *
	 * @since 	1.7
	 */
	public static function getInstance(array $cart = array(), array $params = array())
	{
		if (static::$instance === null) {

			// get cart from session
			$session_cart = JFactory::getSession()->get(self::CART_SESSION_KEY, '', 'tk');

			if (empty($session_cart)) {
				$cart = new static($cart, $params);
			} else {
				$cart = unserialize($session_cart);
				// params should have been stored too
				//$cart->setParams($params);
			}

			static::$instance = $cart;
		}

		return static::$instance;
	}

	/**
	 * Store this instance into the PHP session.
	 *
	 * @return 	TakeAwayCart 	This object to support chaining.
	 *
	 * @since 	1.7
	 */
	public function store()
	{
		JFactory::getSession()->set(self::CART_SESSION_KEY, serialize($this), 'tk');

		return $this;
	}
	
	/**
	 * Set the configuration params of the object.
	 *
	 * @param 	array 	$params The settings array.
	 * 					This array accepts only [maxsize] key.
	 *
	 * @return 	TakeAwayCart 	This object to support chaining.
	 */
	public function setParams(array $params)
	{
		foreach ($params as $k => $v) {
			if (array_key_exists($k, $this->params)) {
				$this->params[$k] = $v;
			}
		}

		return $this;
	}

	/**
	 * Set the items into the array.
	 *
	 * @param 	array 	$items The items array.
	 * 					Each element must be an instance of TakeAwayItem,
	 * 					otherwise it will be ignored.
	 *
	 * @return 	TakeAwayCart 	This object to support chaining.
	 *
	 * @since 	1.7
	 */
	public function setCart(array $items)
	{
		$this->emptyCart();

		foreach ($items as $item) {
			if ($item instanceof TakeAwayItem) {
				$this->addItem($item);
			}
		}

		return $this;
	}
	
	/**
	 * Set the max size of the cart.
	 * If you don't need a maximum size, just specify -1.
	 *
	 * @param 	integer 	$max_size 	The maximum number of items, 
	 *									otherwise -1 for unlimited size.
	 *
	 * @return 	TakeAwayCart 	This object to support chaining.
	 */
	public function setMaxSize($max_size)
	{
		$this->params[self::MAX_SIZE] = $max_size;

		return $this;
	}

	/**
	 * Get the maximum size of the cart.
	 *
	 * @return 	integer 	The maximum number of items, otherwise -1 for unlimited size.
	 *
	 * @since 	1.7
	 */
	public function getMaxSize()
	{
		return $this->params[self::MAX_SIZE];
	}
	
	/**
	 * Set the checkin timestamp.
	 *
	 * @param 	integer 	$ts 	The checkin timestamp.
	 *
	 * @return 	TakeAwayCart 	This object to support chaining.
	 */
	public function setCheckinTimestamp($ts)
	{
		$this->checkin_ts = $ts;

		return $this;
	}

	/**
	 * Get the checkin timestamp.
	 * In case the checkin timestamp is not set, return this day at midnight.
	 *
	 * @return 	integer 	The checkin timestamp.
	 */
	public function getCheckinTimestamp()
	{
		if ($this->checkin_ts === -1) {
			$date = getdate();

			return mktime(0, 0, 0, $date['mon'], $date['mday'], $date['year']);
		}

		return $this->checkin_ts;
	}
	
	/**
	 * Empty the items and the deals stored in the cart.
	 *
	 * @return 	TakeAwayCart 	This object to support chaining.
	 *
	 * @uses 	emptyDiscount() Flush all the discounts stored.
	 */
	public function emptyCart()
	{
		$this->cart = array();

		$this->deals->emptyDiscounts();

		return $this;
	}
	
	/**
	 * Check if the cart doesn't contain elements.
	 *
	 * @return 	boolean 	True if has no element, otherwise false.
	 */
	public function isEmpty()
	{
		if (count($this->cart) == 0) {
			return true;
		}
		
		foreach ($this->cart as $i) {
			if ($i->getQuantity() > 0) {
				return false;
			}
		}
		
		return true;
	}
	
	/**
	 * Balance the cart by removing all the items without quantity.
	 *
	 * @return 	TakeAwayCart 	This object to support chaining.
	 *
	 * @uses 	emptyCart() 	Flush the items to start balance.
	 */
	public function balance()
	{
		$app = $this->cart;
		$this->emptyCart();
		
		foreach ($app as $item) {
			if ($item->getQuantity() > 0) {
				array_push($this->cart, $item);
			}
		}

		return $this;
	}
	
	/**
	 * Attempt to add a new item into the cart.
	 * An item is not pushed when the size of the cart is full.
	 * @see 	getMaxSize()
	 * 
	 * @param 	TakeAwayItem 	$item 	The item to push.
	 *
	 * @return 	mixed 	The index of the array on success, otherwise false.
	 *
	 * @uses 	getPreparationItemsQuantity() 	The number of items that needs a preparation.
	 */
	public function addItem(TakeAwayItem $item)
	{	
		if( $this->params[self::MAX_SIZE] == -1
			|| $this->getPreparationItemsQuantity() + ($item->isReady() ? 0 : $item->getQuantity()) <= $this->params[self::MAX_SIZE] 
		) {
			array_push($this->cart, $item);
			return count($this->cart)-1;
		}
		
		return false;
	}
	
	/**
	 * Get the item at the specified position.
	 *
	 * @param 	integer 	$index 	The index of the item.
	 *
	 * @return 	TakeAwayItem 	The item found on success, otherwise null.
	 *
	 * @uses 	getCartLength() Get the size of the cart.
	 */
	public function getItemAt($index)
	{
		if ($index >= 0 && $index < $this->getCartLength() && $this->cart[$index]->getQuantity() > 0) {
			return $this->cart[$index];
		}
		
		return null;
	}

	/**
	 * Remove the item at the specified index.
	 *
	 * @param 	integer 	$index	The index of the item to remove.
	 * @param 	integer 	$units 	The units of the item to remove.
	 *
	 * @return 	boolean 	True on success, otherwise false.
	 *
	 * @uses 	getItemAt() 	Get the item at the specified position.
	 * @uses 	isEmpty() 		Check if the cart is empty.
	 * @uses 	emptyCart() 	Flush the cart to balance it (in case is empty).
	 *
	 * @since 	1.7 	Renamed from removeItem
	 */
	public function removeItemAt($index, $units = 1)
	{
		if (($item = &$this->getItemAt($index)) !== null && $units > 0) {

			$item->remove($units);

			if ($this->isEmpty()) {
				// in case the cart is empty, flush all to balance
				$this->emptyCart();
			}

			return true;
		}
		
		return false;
	}
	
	/**
	 * Get the index of the specified item.
	 *
	 * @param 	TakeAwayItem 	$item 	The item to find.
	 *
	 * @return 	integer 	The index found on success, otherwise -1.
	 */
	public function indexOf(TakeAwayItem $item)
	{
		foreach ($this->cart as $k => $i) {
			if ($i->getQuantity() > 0 && $i->equalsTo($item)) {
				return $k;
			}
		}

		return -1;
	}

	/**
	 * Remove the specified item found.
	 *
	 * @param 	TakeAwayItem 	$item	The item to remove
	 * @param 	integer 		$units 	The units of the item to remove.
	 *
	 * @return 	boolean 	True on success, otherwise false.
	 *
	 * @uses 	indexOf() 		The index of the item.
	 * @uses 	removeItemAt() 	Remove the item at the index found.
	 *
	 * @since 	1.7 	Existing function with different arguments.
	 */
	public function removeItem(TakeAwayItem $item, $units = 1)
	{
		if (($index = $this->indexOf($item)) != -1) {
			return $this->removeItemAt($index, $units);
		}

		return false;
	}
	
	/**
	 * Get the current size of the cart, including the item without quantity.
	 * @protected This method should be used only for internal purposes.
	 *
	 * @return 	integer 	The size of the cart.
	 */
	protected function getCartLength()
	{
		return count($this->cart);
	}
	
	/**
	 * Get the real size of the cart.
	 * Consider only the items with quantity equals or higher than 1.
	 *
	 * @return 	integer 	The real size of the cart.
	 */
	public function getCartRealLength()
	{
		$cont = 0;
		foreach ($this->cart as $i) {
			if ($i->getQuantity() > 0) {
				$cont++;
			}
		}
		return $cont;
	}

	/**
	 * Get the number of items that need a preparation.
	 *
	 * @return 	integer 	The preparation items count.
	 *
	 * @since 	1.7
	 */
	public function getPreparationItemsQuantity()
	{
		$count = 0;
		foreach ($this->cart as $k => $i) {
			if ($i->getQuantity() > 0 && !$i->isReady()) {
				$count += $i->getQuantity();
			}
		}

		return $count;
	}
	
	/**
	 * Get the list of all the valid items in cart.
	 *
	 * @return 	array 	The list of the items.
	 */
	public function getItemsList()
	{
		$list = array();
		foreach ($this->cart as $k => $i) {
			if ($i->getQuantity() > 0) {
				$list[$k] = $i;
			}
		}
		
		return $list;
	}

	/**
	 * Get the total quantity of the specified item and variation in cart.
	 *
	 * @param 	integer 	$id_item 	The ID of the item.
	 * @param 	integer 	$id_var 	The ID of the variation.
	 *
	 * @return 	integer 	The total quantity.
	 *
	 * @since 	1.7
	 */
	public function getQuantityItems($id_item, $id_var)
	{
		$q = 0;
		foreach ($this->cart as $k => $i) {
			if ($i->getItemID() == $id_item && (empty($id_var) || $i->getVariationID() == $id_var)) {
				$q += $i->getQuantity();
			}
		}
		
		return $q;
	}
	
	/**
	 * Get the list of current deals.
	 *
	 * @return 	array 	The deals list.
	 */
	public function deals()
	{
		if ($this->deals === null) {
			$this->deals = new TakeAwayDeals();
		}

		return $this->deals;
	}

	/**
	 * Get the base total cost of the cart by summing the base cost of each item.
	 *
	 * @return 	float 	The base total cost.
	 */
	public function getTotalCost()
	{
		$tcost = 0;
		foreach ($this->cart as $i) {
			if ($i->getQuantity() > 0) {
				$tcost += $i->getTotalCost();
			}
		}
		
		return $tcost;
	}
	
	/**
	 * Get the base total discount related to the items in cart.
	 *
	 * @return 	float 	The base total discount.
	 *
	 * @uses 	getTotalCost() 	Recover the base total cost.
	 */
	public function getTotalDiscount()
	{
		$cost = $grand_total = $this->getTotalCost();
		$discount_val = 0.0;
		foreach ($this->deals->getDiscountsList() as $discount) {
			for ($q = 1; $q <= $discount->getQuantity(); $q++) {
				if ($discount->getPercentOrTotal() == 1) {
					$discount_val += $cost*$discount->getAmount()/100.0;
					$cost -= $cost*$discount->getAmount()/100.0;
				} else {
					$discount_val += $discount->getAmount();
					$cost -= $discount->getAmount();
				}
			}
		}
		
		return ($cost < 0 ? $grand_total : $discount_val);
	}

	/**
	 * Get the total taxes of the cart.
	 * 
	 * @param 	boolean 	$use_taxes 	True if taxes are escluded, otherwise false.
	 *
	 * @return 	float 	The total taxes.
	 *
	 * @since 	1.7
	 */
	public function getTaxes($use_taxes = false)
	{
		$taxes = 0;
		foreach ($this->cart as $i) {
			if ($i->getQuantity() > 0) {
				$taxes += $i->getTaxes($use_taxes);
			}
		}

		return $taxes;
	}

	/**
	 * Get the real total net of the cart, by substracting the taxes from the grand total.
	 *
	 * @param 	boolean 	$use_taxes 	True if taxes are escluded, otherwise false.
	 *
	 * @return 	float 	The real total net.
	 *
	 * @uses 	getTotalCost() 		Get the base total cost.
	 * @uses 	getTotalDiscount()  Get the total discount.
	 * @uses 	getRealTotalTaxes() Get the real total taxes amount.
	 *
	 * @since 	1.7
	 */
	public function getRealTotalNet($use_taxes = false)
	{
		$net = $this->getTotalCost() - $this->getTotalDiscount();

		if (!$use_taxes) {
			// get net and subtract included taxes
			return  $net - $this->getRealTotalTaxes($use_taxes);			
		}

		return $net;
		
	}
	
	/**
	 * Get the real grand total of the cart, by summing the real total net and the real total taxes.
	 * In case there is a discount, taxes need to be recalculated proportionally.
	 *
	 * @param 	boolean 	$use_taxes 	True if taxes are escluded, otherwise false.
	 *
	 * @return 	float 	The real grand total.
	 *
	 * @uses 	getTotalCost() 		Get the base total cost.
	 * @uses 	getTotalDiscount()  Get the total discount.
	 * @uses 	getTaxes()  		Get the base taxes amount.
	 * @uses 	getRealTotalNet() 	Get the real total net.
	 * @uses 	getRealTotalTaxes() Get the real total taxes amount.
	 *
	 * @since 	1.7
	 */
	public function getRealTotalCost($use_taxes = false)
	{
		if (!$use_taxes) {
			// get real total net
			return $this->getRealTotalNet($use_taxes) + $this->getRealTotalTaxes($use_taxes);
		}

		// apply additional taxes

		$net 		= $this->getTotalCost();
		$discount 	= $this->getTotalDiscount();
		$taxes 		= $this->getTaxes($use_taxes);

		if ($discount > 0) {
			// if there is a discount to apply > calculates the resulting total taxes
			// TAX_NO_DISC : NET_NO_DISC = TAX_DISC : NET_DISC
			// TAX_DISC = TAX_NO_DISC * NET_DISC / NET_NO_DISC

			$taxes = $taxes * ($net - $discount) / $net;
		}

		return $net - $discount + $taxes;
	}

	/**
	 * Get the real total taxes of the cart.
	 * 
	 * @param 	boolean 	$use_taxes 	True if taxes are escluded, otherwise false.
	 *
	 * @return 	float 	The total taxes.
	 *
	 * @uses 	getTotalCost() 		Get the base total cost.
	 * @uses 	getTotalDiscount()  Get the total discount.
	 * @uses 	getTaxes()  		Get the base taxes amount.
	 * @uses 	getRealTotalNet() 	Get the real total net.
	 * @uses 	getRealTotalCost() 	Get the real grand total.
	 *
	 * @since 	1.7
	 */
	public function getRealTotalTaxes($use_taxes = false)
	{
		if (!$use_taxes) {
			// get included taxes amount
			$net 		= $this->getTotalCost();
			$discount 	= $this->getTotalDiscount();
			$taxes 		= $this->getTaxes($use_taxes);

			if ($discount > 0) {
				// if there is a discount to apply > calculates the resulting total taxes
				// TAX_NO_DISC : NET_NO_DISC = TAX_DISC : NET_DISC
				// TAX_DISC = TAX_NO_DISC * NET_DISC / NET_NO_DISC

				$taxes = $taxes * ($net - $discount) / $net;
			}

			return $taxes;
		}

		// get additional taxes
		return $this->getRealTotalCost($use_taxes) - $this->getRealTotalNet($use_taxes);
	}
	
	/**
	 * Magic toString method to debug the cart contents.
	 *
	 * @return  string  The debug string of the cart.
	 *
	 * @since   1.7
	 */
	public function __toString()
	{
		return '<pre>' . print_r($this, true) . '</pre><br />Total Cost = ' . $this->getTotalCost() . '<br />';
	}

	/**
	 * Method to debug the cart contents.
	 *
	 * @return  string  The debug string of the cart.
	 *
	 * @deprecated  1.8  Use __toString() magic method instead.
	 */
	public function toString()
	{
		return $this->__toString();
	}

	/**
	 * MAX_SIZE setting identifier.
	 *
	 * @var string
	 */
	const MAX_SIZE = 'maxsize';
	
	/**
	 * UNLIMITED cart size identifier.
	 *
	 * @var integer
	 */
	const UNLIMITED = -1;

	/**
	 * CART_SESSION_KEY identifier for session key.
	 *
	 * @var string
	 *
	 * @since 1.7
	 */
	const CART_SESSION_KEY = 'vrecartdev';
	
}

/**
 * Deprecated class placeholder. You should use TakeAwayCart instead.
 *
 * @since 		1.6
 * @deprecated 	1.8
 */
class VRTakeAwayCart extends TakeAwayCart
{
	/**
	 * Class constructor.
	 *
	 * @param 	array 	$cart 	The array containing all the items to push.
	 * @param 	array 	$params The settings array.
	 *
	 * @deprecated 	1.8
	 */
	public function __construct(array $cart = array(), array $params = array())
	{
		JLog::add('VRTakeAwayCart is deprecated. Use TakeAwayCart instead.', JLog::WARNING, 'deprecated');
		parent::__construct($cart, $params);
	}
}
