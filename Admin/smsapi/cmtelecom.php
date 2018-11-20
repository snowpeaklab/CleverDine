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

class VikSmsApi {
	
	private $order_info;
	private $params;
	private $log = '';
	private $BASE_URI = 'https://gw.cmtelecom.com/v1.0/message';
	private $devMachine = false;
	
	public static function getAdminParameters()
	{
		return array(
			'producttoken' => array(
				'label' => 'Producttoken',
				'type' => 'text'
			),
			'sender' => array(
				'label' => 'Sender Name//Maximum 11 alpha or 16 numeric characters',
				'type' => 'text'
			),
			'prefix' => array(
				'label' => 'Default prefix',
				'type' => 'text'
			),
			'minimumNumberOfMessageParts' => array(
				'label' => 'Minimum number of message parts//Used when sending multipart or concatenated SMS messages',
				'type' => 'text',
				'default' => 1
			),
			'maximumNumberOfMessageParts' => array(
				'label' => 'Maximum number of message parts//Used when sending multipart or concatenated SMS messages',
				'type' => 'text',
				'default' => 4
			)
		);
	}
	
	public function __construct($order, $params = array())
	{
		$this->order_info = $order;
		$this->params = !empty($params) ? $params : $this->params;
	}

	public function sendMessage($phone_number, $msg_text, $when = NULL)
	{
		if (empty($phone_number) || empty($msg_text)) return;
		return $this->_send($this->parsePhoneNumber($phone_number), $msg_text);
	}
	
	public function getLog()
	{
		return $this->log;
	}
	
	///// CMTELECOM /////
	private function parsePhoneNumber($phone_number)
	{
		if (!isset($this->params['prefix'])) {
			$this->params['prefix'] = '';
		}

		$phone_number = str_replace(" ", "", $phone_number);

		if (substr($phone_number, 0, 2) != '00') {
			if (substr($phone_number, 0, 1) == '+') {
				$phone_number = '00'.substr($phone_number, 1);
			} else if (substr($phone_number, 0, 1) == '0') { // HAVE TO CHECK IF THIS IS ONLY IN THE NETHERLANDS DON't KNOW FOR SURE!
				$phone_number = $this->params['prefix'].substr($phone_number, 1);
			} else {
				$phone_number = $this->params['prefix'].$phone_number;
			}
		}

		return $phone_number;
	}
	
	private function _send($destination, $message)
	{
		$this->log = '';
		
		$jsonArray = array(
			'messages' => array(
				'authentication' => array(
					'producttoken' => $this->params['producttoken']
				),
				'msg' => array(
					array(
						'from' => $this->params['sender'],
						'to' => array(
							array(
								'number' => $destination
							)
						),
						'minimumNumberOfMessageParts' => (isset($this->params['minimumNumberOfMessageParts']) && !empty($this->params['minimumNumberOfMessageParts']) ? $this->params['minimumNumberOfMessageParts'] : 1),
						'maximumNumberOfMessageParts' => (isset($this->params['maximumNumberOfMessageParts']) && !empty($this->params['maximumNumberOfMessageParts']) ? $this->params['maximumNumberOfMessageParts'] : 1),
						'customGrouping3' => 'E4J',
						'body' => array(
							'type' => 'AUTO',
							'content' => $message
						)
					)
				)
			)
		);
				
		$jsonString = json_encode($jsonArray);
		$result = $this->_doPost($jsonString);
		
		return $result;
	}
	
	public function validateResponse($responsObj)
	{
		if (!$responsObj) {
			return false;
		}

		$responsObj->errorCode = $responsObj->code;
		if ($responsObj->code == 200) {
			$responsObj->errorCode = 0;
			// success
			return true;
		} else if (isset($responsObj->data->details)) {
			// failure
			$this->log = $responsObj->data->details;
		}

		return false;
	}
	
	private function _doPost($jsonString)
	{
		$ch = curl_init();
		
		if ($this->devMachine) {
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		}
		
		curl_setopt($ch, CURLOPT_URL, $this->BASE_URI);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonString);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Content-Length: ' . strlen($jsonString))
		);
		$result = curl_exec($ch);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		
		if ($curl_errno = curl_errno($ch)) {
			$this->log = "Something went wrong with the request: (".$curl_errno.") ".curl_error($ch);
			return false;
		}

		$response_obj = new stdClass;
		$response_obj->data = json_decode($result); 
		$response_obj->code = $httpcode;

		return $response_obj;
	}
}
