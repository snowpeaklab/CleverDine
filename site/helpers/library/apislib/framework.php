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
 * cleverdine APIs base framework.
 * This class is used to run all the installed plugins in a given directory.
 * The classname of the plugins must follow the standard below:
 * e.g. File = plugin.php   		Class = Plugin
 * e.g. File = plugin_name.php   	Class = PluginName
 *
 * All the events are runnable only if the user is correctly authenticated.
 *
 * @see 	APIs 		This class extends the base framework handler.
 * @see 	JFactory 	Joomla Factory class to retrieve the database resource.
 * @see 	UIFactory 	Custom Factory class to retrieve the software configuration.
 * @see 	UserAPIs
 * @see 	ResponseAPIs
 * @see 	ErrorAPIs
 * @see 	EventAPIs
 *
 * @since  	1.7
 */
class FrameworkAPIs extends APIs
{
	/**
	 * Class constructor.
	 * @protected This class can be accessed only through the static getInstance() method.
	 *
	 * In case the framework is not accessible, it will be disabled.
	 *
	 * @param 	string 	$event_path 	The dir path containing all the plugins.
	 *
	 * @see APIs::getInstance()
	 */
	protected function __construct($event_path = '')
	{
		parent::__construct($event_path);

		// get config with maximum level and ignore cache
		$config = UIFactory::getConfig(1, false);

		$apis_enabled = $config->getBool('apifw');
		if (!$apis_enabled) {
			$this->disable();
		}
	}

	/**
	 * Authenticate the provided user and connect it on success.
	 * The credentials of the user are stored in the database.
	 * @usedby 	APIs::connect()
	 *
	 * This method can raise the following internal errors:
	 * - 103 = The username and password do not match
	 * - 104 = This account is blocked
	 * - 105 = The source IP is not authorised
	 *
	 * @param 	UserAPIs 	$user 	The object of the user.
	 *
	 * @return 	integer 	The ID of the user on success, otherwise false.
	 *
	 * @uses 	APIs::setError() 	Set the error raised.
	 */
	protected function doConnection(UserAPIs $user)
	{
		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true);

		// get login that matches with the credentials provided
		$q->select('*')
			->from($dbo->quoteName('#__cleverdine_api_login'))
			->where($dbo->quoteName('username') . ' = ' . $dbo->quote($user->getUsername()))
			->where($dbo->quoteName('password') . ' = ' . $dbo->quote($user->getPassword()));

		$dbo->setQuery($q, 0, 1);
		$dbo->execute();

		if ($dbo->getNumRows() == 0) {
			// set error : credentials not correct
			$this->setError(103, 'Authentication Error! The username and password do not match.');
			return false;
		}

		// load login
		$login = $dbo->loadAssoc();

		// check if login account is still active
		if (!$login['active']) {
			// set error : login blocked
			$this->setError(104, 'Authentication Error! This account is blocked.');
			return false;
		}

		// check if user IP address is in the list of the allowed IPs
		// if there are no IPs specified, all addresses are allowed
		if (strlen($login['ips'])) {

			$ip_list = json_decode($login['ips'], true);

			if (count($ip_list) && !in_array($user->getSourceIp(), $ip_list)) {
				// set error : ip address not allowed
				$this->setError(105, 'Authentication Error! The source IP is not authorised.');
				return false;
			}

		}

		return $login['id'];
	}

	/**
	 * Register the provided event and response.
	 * This log is registered in the database and it is visible only from the administrator.
	 * @usedby 	APIs::connect()
	 * @usedby 	APIs::trigger()
	 *
	 * @param 	EventAPIs 		$event 	 	The event requested.
	 * @param 	ResponseAPIs 	$response 	The response caught or raised.
	 *
	 * @return 	boolean 	True if the event has been registered, otherwise false.
	 *
	 * @uses 	APIs::isConnected() 	Check if the user is connected.
	 * @uses 	APIs::getUser() 		Get the current user.
	 */
	protected function registerEvent(EventAPIs $event = null, ResponseAPIs $response = null)
	{
		$log 		= '';
		$status 	= 2;
		$id_user 	= $this->isConnected() ? $this->getUser()->id() : -1;
		$ip 		= $this->isConnected() ? $this->getUser()->getSourceIp() : JFactory::getApplication()->input->server->get('REMOTE_ADDR');
		$ts 		= time();

		// if the event is not empty : register it
		if ($event !== null) {
			$log .= 'Event Requested: '.$event->getName()."\n";
		}

		// if the response is not empty : register it and evaluate the status
		if ($response !== null) {
			$log .= $response->getContent();

			$status = $response->isVerified() ? 1 : 0;
		}

		if (empty($log)) {
			// if the evaluated log is still empty

			if ($id_user > 0) {
				// try to register the details of the user
				$log = 'User ['.$this->getUser()->getUsername().'] login @ '.date('Y-m-d H:i:s', $ts);
			} else {
				// otherwise register a "unrecognised" response
				$log = 'Impossible to recognise the response';
			}

		}

		// get config with maximum level and ignore cache
		$config = UIFactory::getConfig(1, false);

		// get the Log Mode setting and save the log
		$mode = $config->getUint('apilogmode');

		// register log only in case it is set to 2 (always) or 1 and the status is failure (only errors)
		if ($mode == 2 || ($mode == 1 && !$status)) {

			$dbo = JFactory::getDbo();

			$q = $dbo->getQuery(true);

			$q->insert($dbo->quoteName('#__cleverdine_api_login_logs'))
				->columns(array(
					$dbo->quoteName('id_login'),
					$dbo->quoteName('status'),
					$dbo->quoteName('content'),
					$dbo->quoteName('ip'),
					$dbo->quoteName('createdon')
				))
				->values(
					$id_user.','.
					$status.','.
					$dbo->quote($log).','.
					$dbo->quote($ip).','.
					$ts
				);

			$dbo->setQuery($q);
			$dbo->execute();

			return $dbo->insertid() ? true : false;
		}

		return false;
	}

	/**
	 * Update the user manifest after a successful authentication.
	 * @usedby 	APIs::connect()
	 *
	 * @return 	boolean 	True on success, otherwise false.
	 *
	 * @uses 	APIs::getUser() Access the user object.
	 */
	protected function updateUserManifest()
	{
		if ($this->getUser() === null) {
			return false;
		}

		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true);

		$q->update($dbo->quoteName('#__cleverdine_api_login'))
			->set($dbo->quoteName('last_login') . ' = ' . time())
			->where($dbo->quoteName('id') . ' = ' . $this->getUser()->id());

		$dbo->setQuery($q);
		$dbo->execute();

		return ($dbo->getAffectedRows() ? true : false);
	}

	/**
	 * Check if the provided user has been banned.
	 * This action is executed only before the authentication.
	 * The ban is evaluated on the IP origin.
	 *
	 * A user is considered banned when its failures are equals or higher
	 * than the maximum number of failure attempts allowed.
	 *
	 * The failure attempts are always increased by the ban() function.
	 *
	 * @usedby 	APIs::connect()
	 *
	 * @param 	UserAPIs 	$user 	The object of the user.
	 *
	 * @return 	boolean 	True is the user is banned, otherwise false.
	 *
	 * @uses 	APIs::get() 	Get the maximum number of failure attempts from config.
	 *
	 * @see 	FrameworkAPIs::ban() to ban a user.
	 */
	protected function isBanned(UserAPIs $user)
	{
		// get the number of failures associated to the IP address of the user
		
		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true);

		$q->select($dbo->quoteName('fail_count'))
			->from($dbo->quoteName('#__cleverdine_api_ban'))
			->where($dbo->quoteName('ip') . ' = ' . $dbo->quote($user->getSourceIp()));

		$dbo->setQuery($q, 0, 1);
		$dbo->execute();

		// if the failures count is equals or higher than the maximum allowed, it means the user is banned

		if ($dbo->getNumRows()) {
			return ($dbo->loadResult() >= $this->get('max_failure_attempts', 10));
		}

		return false;
	}

	/**
	 * Considering this function is called after every failure, a ban is always needed.
	 * Every time this function is executed, the system will call the ban() function to apply the ban.
	 * @usedby 	APIs::connect()
	 *
	 * @param 	UserAPIs 	$user 	The object of the user.
	 *
	 * @return 	boolean 	Return true.
	 *
	 * @see 	FrameworkAPIs::ban() to ban a user.
	 */
	protected function needBan(UserAPIs $user)
	{
		// all failures need to be banned
		// ban() function provide to increase the number of failures
		return true;
	}

	/**
	 * Increase the failure attempts of the provided user.
	 * Once this function is terminated, the user is not effectively banned, unless its 
	 * total failures are equals or higher than the maximum number allowed.
	 * @usedby 	APIs::connect()
	 *
	 * @param 	UserAPIs 	$user 	The object of the user.
	 *
	 * @see 	FrameworkAPIs::isBanned() 	check if the user is banned.
	 */
	protected function ban(UserAPIs $user)
	{
		$dbo = JFactory::getDbo();

		$now = time();

		// get the ID of the user to ban

		$q = $dbo->getQuery(true);

		$q->select($dbo->quoteName('id'))
			->from($dbo->quoteName('#__cleverdine_api_ban'))
			->where($dbo->quoteName('ip') . ' = ' . $dbo->quote($user->getSourceIp()));

		$dbo->setQuery($q, 0, 1);
		$dbo->execute();

		$q = $dbo->getQuery(true);

		if ($dbo->getNumRows()) {
			
			// user exists : update row to increase by 1 the failures count
			$q->update($dbo->quoteName('#__cleverdine_api_ban'))
				->set($dbo->quoteName('fail_count') . ' = (' . $dbo->quoteName('fail_count') . '+1)')
				->set($dbo->quoteName('last_update') . ' = ' . $now)
				->where($dbo->quoteName('id') . ' = ' . (int) $dbo->loadResult());

			// set query with limit
			$dbo->setQuery($q);

		} else {

			// use not exists : insert row and set the failures count to 1
			$q->insert($dbo->quoteName('#__cleverdine_api_ban'))
				->columns(array(
					$dbo->quoteName('ip'),
					$dbo->quoteName('fail_count'),
					$dbo->quoteName('last_update')
				))
				->values(
					$dbo->quote($user->getSourceIp()).','.
					'1,'.
					$now
				);

			// set query without limit
			$dbo->setQuery($q);

		}

		$dbo->execute();
	}

	/**
	 * Reset the count of failure attempts for the provided user.
	 * @usedby 	APIs::connect()
	 *
	 * @param 	UserAPIs 	$user 	The object of the user.
	 *
	 * @return 	boolean 	True if the user is correctly logged, otherwise false.
	 */
	protected function resetBan(UserAPIs $user)
	{
		if (!$user->id()) {
			return false;
		}

		$dbo = JFactory::getDbo();

		$now = time();

		$q = $dbo->getQuery(true);

		$q->update($dbo->quoteName('#__cleverdine_api_ban'))
			->set($dbo->quoteName('fail_count') . ' = 0')
			->set($dbo->quoteName('last_update') . ' = ' . $now)
			->where($dbo->quoteName('ip') . ' = ' . $dbo->quote($user->getSourceIp()));

		$dbo->setQuery($q);
		$dbo->execute();

		return true;
	}

}
