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

class VikSmsApi
{	
	private $order_info;
	private $params;
	private $log = '';

	public static function getAdminParameters()
	{
		return array(
			'userid' => array(
				'label' => 'User ID',
				'type' => 'text'
			),
				
			'password' => array(
				'label' => 'Password',
				'type' => 'text'
			)
		);
	}
	
	public function __construct($order, $params = array())
	{
		$this->order_info = $order;
		$this->params = !empty($params) ? $params : $this->params;
	}
	
	public function sendMessage($phone_number, $msg_text)
	{
		$phone_number = $this->sanitizePhoneNumber($phone_number);
	
		$request = array(
			"to" => "sms:".$phone_number,
			"text" => $msg_text
		);
	
		$json = json_encode($request);

		$curl_opts = array(
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => $json,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HTTPHEADER => array(
				'Content-Type: application/json',
				'Authorization: Basic '.base64_encode($this->params['userid'].':'.$this->params['password'])
			)
        );
 
		$ch = curl_init('https://tellus-talk.appspot.com/send/v1');
		curl_setopt_array($ch, $curl_opts);
		$response = curl_exec($ch);
		
		$result = new stdClass;
		$result->responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		return $result;
	}
	
	protected function sanitizePhoneNumber($phone_number)
	{
		$str = '';
		for ($i = 0; $i < strlen($phone_number); $i++) {
			if (($phone_number[$i] >= '0' && $phone_number[$i] <= '9') || $phone_number[$i] == '+') {
				$str .= $phone_number[$i]; // copy only numbers and plus character
			}
		}

	
		$default_prefix = "+1"; // US, Canada phone prefix
	
		if ($phone_number[0] != '+') { 
			// $phone_number doesn't contain the phone prefix 
			$str = $default_prefix.$str;
		}
	
		return $str;
	}
	
	public function validateResponse($response_obj)
	{
		switch ($response_obj->responseCode) {
			case 200:
				return true;
			case 400:
				$this->log.="Bad Request\n";
				break;
			case 401:
				$this->log.="Unauthorized\n";
				break;
			case 404:
				$this->log.="Not Found\n";
				break;
			case 405:
				$this->log.="Method not Allowed\n";
				break;
			case 500:
				$this->log.="Internal Server Error\n";
				break;
		}

		return false;
	}
	
	public function getLog()
	{
		return $this->log;
	}

}
