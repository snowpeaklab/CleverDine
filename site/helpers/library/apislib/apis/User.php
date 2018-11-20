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
 * cleverdine APIs base user.
 * This class is used from the framework to connect the users.
 *
 * @see 	EventAPIs
 *
 * @since  	1.7
 */
abstract class UserAPIs
{
	/**
	 * The username of the user, required to login.
	 * 
	 * @var string
	 */
	private $username;

	/**
	 * The password of the user, required to login.
	 * 
	 * @var string
	 */
	private $password;

	/**
	 * The ID of the user, assigned after a successful login.
	 * 
	 * @var integer
	 */
	private $id = null;

	/**
	 * The origin IP address from which the user is trying to connect.
	 *
	 * @var string
	 */
	private $sourceIp;

	/**
	 * A temporary array to maintain the provided credentials in case they don't match the requirements.
	 * This variable is useful to return always the details provided from the user, 
	 * because in case of failure the credentials may be unset.
	 *
	 * @var array
	 */
	private $failure = array();

	/**
	 * Class constructor.
	 *
	 * @param 	string 	$username 	The username of the user for login.
	 * @param 	string 	$password 	The password of the user for login.
	 * @param 	string 	$ip 		The IP address from which the user is trying to login.
	 *
	 * @uses 	isUsernameAccepted() 	Validate if the provided username mets the structure requirements.
	 * @uses 	isPasswordAccepted() 	Validate if the provided password mets the structure requirements.
	 * @uses 	hashMask() 				Mask the password with the chosen hash algorithm.
	 */
	public function __construct($username, $password, $ip = null)
	{
		// create a temporary credentials array
		$this->failure = array('', '');

		// check if the username can be accepted
		if ($this->isUsernameAccepted($username)) {
			// assign it to this class
			$this->username = $username;
		} else {
			// otherwise push it into the temporary array
			$this->failure[0] = $username;
		}

		// check if the password can be accepted
		if ($this->isPasswordAccepted($password)) {
			// mask the password and assign it to this class
			$this->password = $this->hashMask($password);
		} else {
			// otherwise push it into the temporary array
			$this->failure[1] = $password;
		}

		$this->sourceIp = $ip;
	}

	/**
	 * Get the username of the user.
	 * The username is not empty only if it is verified from the constructor.
	 * 
	 * @return 	string 	The username of the user.
	 *
	 * @see UserAPIs::getCredentials() 	Get always the provided credentials, also in failure cases.
	 */
	public function getUsername()
	{
		return $this->username;
	}

	/**
	 * Get the password of the user.
	 * The password is not empty only if it is verified from the constructor.
	 * 
	 * @return 	string 	The password of the user.
	 *
	 * @see UserAPIs::getCredentials() 	Get always the provided credentials, also in failure cases.
	 */
	public function getPassword()
	{
		return $this->password;
	}

	/**
	 * Get the credentials of the user, also in failure cases.
	 * 
	 * @return 	object 	An object containing the credentials of the user.
	 */
	public function getCredentials()
	{
		$credentials = new stdClass;
		
		// if username is not empty (accepted) return it, otherwise return failure[0]
		$credentials->username = (!empty($this->username) ? $this->username : $this->failure[0]);
		// if password is not empty (accepted) return it, otherwise return failure[1]
		$credentials->password = (!empty($this->password) ? $this->password : $this->failure[1]);
		
		return $credentials;
	}

	/**
	 * Set the ID of the user after a successful login.
	 * By setting an ID through this method, the framework assumes that the user is currently connected.
	 *
	 * @return 	UserAPIs 	This object to support chaining.
	 */
	public function assign($id)
	{
		$this->id = $id;

		return $this;
	}

	/**
	 * Get the ID of the user. Return NULL in case the user is not yet connected.
	 *
	 * @return 	integer 	The ID of the user or NULL.
	 */
	public function id()
	{
		return $this->id;
	}

	/**
	 * Return true if the credentials provided match the strcture requirements.
	 * When true, it is possible to proceed with the login check.
	 *
	 * @return 	boolean 	True if the username and password are not empty (accepted).
	 */
	public function isConnectable()
	{
		return (strlen($this->username) && strlen($this->password));
	}

	/**
	 * Get the origin IP address from which the user is trying to connect.
	 *
	 * @return 	string 	The IP address if provided, otherwise NULL.
	 */
	public function getSourceIp()
	{
		return $this->sourceIp;
	}

	/**
	 * Check if the user is able to perform the event provided.
	 *
	 * @param 	EventAPIs 	$event 	The event to authorise.
	 *
	 * @return 	boolean 	True if the event can be performed, otherwise false.
	 */
	public abstract function authorise(EventAPIs $event);

	/**
	 * Return true if the given username owns a valid structure.
	 * In this function it is possible to check minimum length, minimum digits and so on.
	 *
	 * @param 	string 	$username 	The username to check.
	 *
	 * @return 	boolean 	True in case the username is valid.
	 */
	protected abstract function isUsernameAccepted($username);
	
	/**
	 * Return true if the given password owns a valid structure.
	 * In this function it is possible to check minimum length, minimum digits and so on.
	 *
	 * @param 	string 	$password 	The password to check.
	 *
	 * @return 	boolean 	True in case the password is valid.
	 */
	protected abstract function isPasswordAccepted($password);

	/**
	 * Return the hash of the specified password to mask it.
	 *
	 * @param 	string 	$password 	The password to mask.
	 *
	 * @return 	string 	The hash of the password masked.
	 */
	protected abstract function hashMask($password);

}
