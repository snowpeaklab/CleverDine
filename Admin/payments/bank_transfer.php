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
defined('_JEXEC') OR die('Restricted Area');

/**
 * The Bank Transfer (or Pay upon Arrival) payment gateway is used to skip the payment method process 
 * when you have other gateways published. This gateway means you will receive the payment by cash 
 * when the customers will come to your company.
 *
 * @since 1.0
 */
class cleverdinePayment
{
	/**
	 * The order information needed to complete the payment process.
	 *
	 * @var array
	 */
	private $order_info;
	
	/**
	 * Return the fields that should be filled in from the details of the payment.
	 * No fields to return for this payment gateway.
	 *
	 * @return 	array 	The fields array.
	 */
	public static function getAdminParameters()
	{
		return array();
	}
	
	/**
	 * Class constructor.
	 *
	 * @param 	array 	$order 	 The order info array.
	 * @param 	array 	$params  The payment configuration. These fields are the 
	 * 							 same of the getAdminParameters() function.
	 */
	public function __construct($order, $params = array())
	{
		$this->order_info = $order;
	}
	
	/**
	 * This method is invoked every time a user visits the page of a reservation with PENDING Status.
	 *
	 * @return 	void
	 */
	public function showPayment()
	{
		// do nothing
		
		/**
		 * @since 1.7 payment notes are shown with the PRE PURCHASE NOTES parameter.
		 */

		return true;
	}
	
	/**
	 * Validate the transaction details sent from the bank. 
	 * This method is invoked by the system every time the Notify URL 
	 * is visited (the one used in the showPayment() method). 
	 *
	 * @return 	array 	The array result, which MUST contain the "verified" key (1 or 0).
	 */
	public function validatePayment()
	{
		$array_result = array();
		$array_result['verified'] = 1;

		// this method will be never called.
		
		return $array_result;
	}
	
}
