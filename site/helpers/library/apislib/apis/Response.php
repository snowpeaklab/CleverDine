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
 * The APIs response wrapper.
 *
 * @since  1.7
 */
class ResponseAPIs
{
	/**
	 * The status of the response. True for success, false on failure.
	 *
	 * @var boolean
	 */
	private $status = false;

	/**
	 * The text description of the response.
	 *
	 * @var string
	 */
	private $content = "";

	/**
	 * The initial timestamp in seconds of the creation of this object.
	 *
	 * @var integer
	 */
	private $startTime = 0;

	/**
	 * Class constructor.
	 * 
	 * @param 	boolean		$status 	True for success response, otherwise false.
	 * @param 	string 	 	$content 	The text description of the response.
	 *
	 * @uses 	setStatus() 	Set the status of the response.
	 * @uses 	setContent() 	Set the content of the response.
	 */
	public function __construct($status = false, $content = '')
	{
		$this->setStatus($status)->setContent($content);

		$this->startTime = microtime(true);
	}

	/**
	 * Set the status of the response.
	 * @usedby 	ResponseAPIs::__construct()
	 *
	 * @param 	boolean 	$status 	True for success response, otherwise false.
	 *
	 * @return 	ResponseAPIs 	This object to support chaining.
	 */
	public function setStatus($status)
	{
		$this->status = $status;

		return $this;
	}

	/**
	 * Return true if the status of the response is success, otherwise false.
	 *
	 * @return 	boolean 	True on success, otherwise false.
	 */
	public function isVerified()
	{
		return ($this->status == true);
	}

	/**
	 * Return true if the status of the response is failure, otherwise false.
	 *
	 * @return 	boolean 	True on failure, otherwise false.
	 */
	public function isError()
	{
		return ($this->status == false);
	}

	/**
	 * Set the text description of the response.
	 * @usedby 	ResponseAPIs::__construct()
	 * @usedby 	ResponseAPIs::clearContent()
	 *
	 * @param 	string 	$content 	The content of the response.
	 *
	 * @return 	ResponseAPIs 	This object to support chaining.
	 */
	public function setContent($content)
	{
		$this->content = (string) $content;

		return $this;
	}

	/**
	 * Append some text to the existing description of the response.
	 *
	 * @param 	string 	$content 	The content of the response.
	 *
	 * @return 	ResponseAPIs 	This object to support chaining.
	 */
	public function appendContent($content)
	{
		$this->content .= (string) $content;

		return $this;
	}

	/**
	 * Prepend some text to the existing description of the response.
	 *
	 * @param 	string 	$content 	The content of the response.
	 *
	 * @return 	ResponseAPIs 	This object to support chaining.
	 */
	public function prependContent($content)
	{
		$this->content = $content . (string) $this->content;

		return $this;
	}

	/**
	 * Clear the text description of the response.
	 *
	 * @return 	ResponseAPIs 	This object to support chaining.
	 *
	 * @uses 	setContent()
	 */
	public function clearContent()
	{
		return $this->setContent('');
	}

	/**
	 * Get the text description of the response.
	 *
	 * @return 	string 	The text description of the response.
	 */
	public function getContent()
	{
		return $this->content;
	}

	/**
	 * Get the initial timestamp of the response.
	 * The initial time is recorded during the creation of the response.
	 *
	 * @return 	integer 	The initial timestamp in seconds.
	 */
	public function createdOn()
	{
		return $this->startTime;
	}

	/**
	 * Get the elapsed time between the current time and the initial time.
	 *
	 * @return 	integer 	The elapsed time in seconds.
	 */
	public function getElapsedTime()
	{
		return microtime()-$this->startTime;
	}
	
}
