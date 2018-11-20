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
 * The PayPal payment gateway (hosted) prints the standard orange PayPal button to start the transaction.
 * The payment will come on PayPal website and, only after the transaction, the customers will be 
 * redirected to the order page on your website.
 *
 * @since 1.0
 */
class cleverdinePayment
{
	/**
	 * The PayPal e-mail account.
	 *
	 * @var string
	 */
	private $account = "";

	/**
	 * The sandbox environment status (ON enabled, OFF disabled).
	 *
	 * @var string
	 */
	private $sandbox = "OFF";
	
	/**
	 * The order information needed to complete the payment process.
	 *
	 * @var array
	 */
	private $order_info;
	
	/**
	 * Return the fields that should be filled in from the details of the payment.
	 * The configuration fields are listed below:
	 * @property 	logo 		The PayPal image logo.
	 * @property 	account 	The PayPal e-mail account.
	 * @property 	sandbox 	The PayPal environment to use.
	 *
	 * @return 	array 	The fields array.
	 */
	public static function getAdminParameters()
	{
		return array(
			'logo' => array(
				'type' 	=> 'custom', 
				'label' => '', 
				'html' 	=> '<img src="https://www.paypalobjects.com/webstatic/i/ex_ce2/logo/logo_paypal_106x29.png"/>'
			),
			'account' => array(
				'type' 	=> 'text', 
				'label' => 'PayPal Account:'
			),
			'sandbox' => array(
				'type' 		=> 'select', 
				'label' 	=> 'Test Mode://if ON, the PayPal Sandbox will be used', 
				'options' 	=> array(0 => 'OFF', 1 => 'ON')
			)
		);
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
		
		$this->account = (!empty($params['account'])) ? $params['account'] : $this->account;
		$this->sandbox = (!empty($params['sandbox'])) ? $params['sandbox'] : $this->sandbox;
	}
	
	/**
	 * This method is invoked every time a user visits the page of a reservation with PENDING Status.
	 * Display the PayPal paynow button to begin a transaction.
	 *
	 * @return 	void
	 */
	public function showPayment()
	{
		if ($this->sandbox == 1) {
			$paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
		} else {
			$paypal_url = "https://www.paypal.com/cgi-bin/webscr";
		}
		
		$form = "<form action=\"".$paypal_url."\" method=\"post\">\n";
		$form .= "<input type=\"hidden\" name=\"business\" value=\"".$this->account."\"/>\n";
		$form .= "<input type=\"hidden\" name=\"cmd\" value=\"_xclick\"/>\n";
		$form .= "<input type=\"hidden\" name=\"amount\" value=\"".number_format($this->order_info['total_net_price'], 2)."\"/>\n";
		$form .= "<input type=\"hidden\" name=\"item_name\" value=\"".$this->order_info['transaction_name']."\"/>\n";
		$form .= "<input type=\"hidden\" name=\"quantity\" value=\"1\"/>\n";
		$form .= "<input type=\"hidden\" name=\"tax\" value=\"".number_format($this->order_info['total_tax'], 2)."\"/>\n";
		$form .= "<input type=\"hidden\" name=\"shipping\" value=\"0.00\"/>\n";
		$form .= "<input type=\"hidden\" name=\"currency_code\" value=\"".$this->order_info['transaction_currency']."\"/>\n";
		$form .= "<input type=\"hidden\" name=\"no_shipping\" value=\"1\"/>\n";
		$form .= "<input type=\"hidden\" name=\"rm\" value=\"2\"/>\n";
		$form .= "<input type=\"hidden\" name=\"notify_url\" value=\"".$this->order_info['notify_url']."\"/>\n";
		$form .= "<input type=\"hidden\" name=\"return\" value=\"".$this->order_info['return_url']."\"/>\n";
		$form .= "<input type=\"image\" src=\"https://www.paypal.com/en_US/i/btn/btn_paynow_SM.gif\" name=\"submit\" alt=\"PayPal - The safer, easier way to pay online!\">\n";
		$form .= "</form>\n";
		
		// output form
		echo $form;
		
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
		$log = "";
		$array_result = array();
		$array_result['verified'] = 0;
		
		//cURL Method HTTP1.1 October 2015
		$raw_post_data = file_get_contents('php://input');
		$raw_post_array = explode('&', $raw_post_data);
		$myPost = array();

		foreach ($raw_post_array as $keyval) {
			$keyval = explode('=', $keyval);
			if (count($keyval) == 2)
				$myPost[$keyval[0]] = urldecode($keyval[1]);
		}

		// check if the form has been spoofed
		$against = array(
			'business' 	  => $this->account,
			'mc_gross' 	  => number_format($this->order_info['total_net_price'], 2),
			'mc_currency' => $this->order_info['transaction_currency'],
			'tax'		  => number_format($this->order_info['total_tax'], 2),
		);

		// inject the original values within the payment data
		foreach ($against as $k => $v)
		{
			if (isset($myPost[$k]))
			{
				$myPost[$k] = $v;
			}
		}
		//

		$req = 'cmd=_notify-validate';
		if (function_exists('get_magic_quotes_gpc')) {
			$get_magic_quotes_exists = true;
		}

		foreach ($myPost as $key => $value) {
			if ($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
				$value = urlencode(stripslashes($value));
			} else {
				$value = urlencode($value);
			}
			$req .= "&$key=$value";
			$log .= $key.": ".$value."\n";
		}
		
		if (!function_exists('curl_init')) {
			$log = "FATAL ERROR: cURL is not installed on the server\n\n".$log;
			$array_result['log'] = $log;
			return $array_result;
		}
		
		if ($this->sandbox == 1) {
			$paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
		} else {
			$paypal_url = "https://www.paypal.com/cgi-bin/webscr";
		}
		
		$ch = curl_init($paypal_url);
		if ($ch == FALSE) {
			$log = "Curl error: ".curl_error($ch)."\n\n".$log;
			$array_result['log'] = $log;
			return $array_result;
		}
		
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
		//curl_setopt($ch, CURLOPT_SSLVERSION, 6);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
		
		// CONFIG: Please download 'cacert.pem' from "http://curl.haxx.se/docs/caextract.html" and copy it in the same folder as this php file
		// This is mandatory for some environments.
		//$cert = dirname(__FILE__) . "/cacert.pem";
		//curl_setopt($ch, CURLOPT_CAINFO, $cert);
		
		$res = curl_exec($ch);
		if (curl_errno($ch) != 0) {
			$log .= date('[Y-m-d H:i e] '). "Can't connect to PayPal to validate IPN message: " . curl_error($ch) . PHP_EOL;
			curl_close($ch);
			$array_result['log']=$log;
			return $array_result;
		} else {
			$log .= date('[Y-m-d H:i e] '). "HTTP request of validation request:". curl_getinfo($ch, CURLINFO_HEADER_OUT) ." for IPN payload: $req" . PHP_EOL;
			$log .= date('[Y-m-d H:i e] '). "HTTP response of validation request: $res" . PHP_EOL;
			curl_close($ch);
		}

		$input = JFactory::getApplication()->input->post;
		
		if (!strcmp(trim($res), "VERIFIED")) {
			$array_result['tot_paid'] = $input->getFloat('mc_gross');
			$array_result['verified'] = 1;
		} else if (!strcmp($res, "INVALID")) {
			$log .= date('[Y-m-d H:i e] '). "Invalid IPN: $req"."\n$res" . PHP_EOL;
		}
		
		//END cURL Method HTTP1.1 October 2015
		
		//old IPN method before October 2015
//		$req = 'cmd=_notify-validate';
//		foreach ($input->getArray() as $k => $v) {
//			$req .= "&".$k."=".urlencode(stripslashes($v));
//			$log.=$k.": ".$v."\n";
//			if($k=='mc_gross') {
//				//cannot be in decimals
//				$array_result['tot_paid']=$v;
//			}
//		}
//		
//		$sheader .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
//		$sheader .= "Content-Type: application/x-www-form-urlencoded\r\n";
//		$sheader .= "Content-Length: " . strlen($req) . "\r\n\r\n";
//		
//		$fp = fsockopen ('ssl://www.paypal.com', 443, $errno, $errstr, 30);
//		$res = "";
//		if ($fp) {
//			fputs ($fp, $sheader.$req);
//			while (!feof($fp)) {
//				$res .= fgets ($fp, 1024);
//			}
//			fclose ($fp);
//			if (strcmp ($res, "VERIFIED") == 0 || substr($res, -8, 8) == "VERIFIED") {
//				$array_result['verified']=1;
//			}
//		}
		//END old IPN method before October 2015
		
		$log .= "\n\n".$res;
		$array_result['log'] = $log;

		return $array_result;
	}
	
}
