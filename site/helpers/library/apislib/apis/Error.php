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
 * The APIs error representation.
 *
 * @since  1.7
 */
class ErrorAPIs
{
	/**
	 * The identifier code of the error.
	 *
	 * @var integer
	 */
	public $errcode;

	/**
	 * The text description of the error.
	 *
	 * @var string
	 */
	public $error;

	/**
	 * Class constructor.
	 * 
	 * @param 	integer 	$errcode 	The code identifier.
	 * @param 	string 		$error 		The text description.
	 */
	public function __construct($errcode, $error)
	{
		$this->errcode 	= $errcode;
		$this->error 	= $error;
	}

	/**
	 * Return this object encoded in JSON.
	 *
	 * @return 	string 	This object in JSON.
	 */
	public function toJSON()
	{
		return json_encode($this);
	}

	/**
	 * Raise the specified error and stop the flow if needed.
	 *
	 * @param 	integer 	$errcode 	The code identifier.
	 * @param 	string 		$error 		The text description.
	 * @param 	boolean 	$exit 		True to stop the execution, otherwise false.
	 *
	 * @return 	mixed 		The error raised when exit is not needed, otherwise the error will be echoed in JSON.
	 */
	public static function raise($errcode, $error, $exit = true)
	{
		$err = new ErrorAPIs($errcode, $error);

		if ($exit) {
			echo $err->toJSON();
			exit;
		}

		return $err;
	}

}
