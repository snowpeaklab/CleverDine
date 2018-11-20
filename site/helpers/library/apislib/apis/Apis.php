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
 * @see 	UserAPIs
 * @see 	ResponseAPIs
 * @see 	ErrorAPIs
 * @see 	EventAPIs
 *
 * @since  	1.7
 */
abstract class APIs
{
	/**
	 * The path of the folder containing all the available plugins.
	 *
	 * @var string
	 */
	private $event_path = "";

	/**
	 * True if the API framework is enabled and accessible.
	 *
	 * @var boolean
	 */
	private $enabled = true;	

	/**
	 * The instance of the user which is using the API framework.
	 *
	 * @var UserAPIs
	 */
	private $user = null;

	/**
	 * The last error caught.
	 *
	 * @var ErrorAPIs
	 */
	private $error = null;

	/**
	 * The array that contains the configuration keys.
	 *
	 * @var array
	 */
	private $config = array();

	/**
	 * The instance of the API framework.
	 *
	 * @var APIs
	 */
	protected static $instance = null;

	/**
	 * Class constructor.
	 * @protected This class can be accessed only through the static getInstance() method.
	 *
	 * @param 	string 	$event_path 	The dir path containing all the plugins.
	 *
	 * @see APIs::getInstance()
	 */
	protected function __construct($event_path = '')
	{
		if (empty($event_path) || !is_string($event_path)) {
			$event_path = dirname(__FILE__).DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR;
		} else if ($event_path[strlen($event_path)-1] != DIRECTORY_SEPARATOR) {
			$event_path .= DIRECTORY_SEPARATOR;
		}

		$this->event_path = $event_path;
	}

	/**
	 * Class cloner.
	 */
	private function __clone()
	{
		// cloning function not accessible
	}

	/**
	 * Get the instance of the APIs object.
	 * 
	 * @param 	string 	$event_path 	The dir path containing all the plugins.
	 *
	 * @return 	APIs 	The instance of the API framework.
	 */
	public static function getInstance($event_path = '')
	{
		if (static::$instance === null) {
			static::$instance = new static($event_path);
		}

		return static::$instance;
	}

	/**
	 * Return true if the APIs framework is enabled and accessible.
	 * @usedby 	APIs::connect()
	 * @usedby 	APIs::trigger()
	 *
	 * @return boolean	True if enabled, otherwise false.
	 */
	public function isEnabled()
	{
		return $this->enabled;
	}

	/**
	 * Enable the APIs framework.
	 *
	 * @return APIs		This object to support chaining.
	 */
	protected function enable()
	{
		$this->enabled = true;

		return $this;
	}

	/**
	 * Disable the APIs framework.
	 *
	 * @return APIs		This object to support chaining.
	 */
	protected function disable()
	{
		$this->enabled = false;

		return $this;
	}

	/**
	 * Return true if the user is correctly logged.
	 * @usedby 	APIs::trigger()
	 *
	 * @return boolean	True if logged, otherwise false.
	 */
	public function isConnected()
	{
		return ($this->user !== null && $this->user->id());
	}

	/**
	 * Return the object of the logged user.
	 *
	 * @return UserAPIs		The object of the user connected, otherwise NULL.
	 */
	public function getUser()
	{
		return $this->user;
	}

	/**
	 * Disconnect the user.
	 *
	 * @return APIs		This object to support chaining.
	 */
	public function disconnect()
	{
		$this->user = null;

		return $this;
	}

	/**
	 * Get the path of the folder containing the plugins.
	 *
	 * @return string	The plugins folder.
	 *
	 */
	public function getEventPath()
	{
		return $this->event_path;
	}

	/**
	 * Connect the specified user to the APIs framework.
	 *
	 * In case the login fails, here is evaluated a permanent BAN.
	 * Otherwise the MANIFEST of the user is updated and the BAN is reset.
	 *
	 * This method can raise the following internal errors:
	 * - 100 = Authentication Error (Generic)
	 * - 101 = The username is empty or invalid
	 * - 102 = The password is empty or invalid
	 * - 104 = The account is blocked
	 *
	 * @param 	UserAPIs 	$user	The object to represent the user login.
	 *
	 * @return 	boolean		True if the user is accepted, otherwise false.
	 */
	public function connect(UserAPIs $user)
	{
		// check if APIs framework is enabled
		if (!$this->isEnabled()) {
			// do not log anything and stop flow
			return false;
		}

		// check if the user is banned
		// and the user is connectable
		// and the login connection returns a valid user ID
		if (
			!($banned = $this->isBanned($user)) 
			&& $user->isConnectable() 
			&& ($id_user = $this->doConnection($user)) !== false
		) {
			// setup the user and fill the ID
			$this->user = $user;
			$this->user->assign($id_user);

			// update user manifest
			$this->updateUserManifest();

			$this->resetBan($this->user);

			return true;
		}

		// login failed : if user is not yet banned, evaluate a ban
		if (!$banned && $this->needBan($user)) {
			// ban the user
			$this->ban($user);
		}

		// only if the user is not banned
		// register the failure of the login (no event is reported)
		if (!$banned) {
			$credentials = $user->getCredentials();
			$this->registerEvent(null, new ResponseAPIs(0, 'Authentication Error! Impossible to login for user {'.$credentials->username.' : '.$credentials->password.'} from '.$user->getSourceIp().'.'));
		}

		if ($banned) {
			// set error : user banned
			$this->setError(104, 'Authentication Error! This account is blocked.');
		} else if (!strlen($user->getUsername())) {
			// set error : username empty
			$this->setError(101, 'Authentication Error! The username is empty or invalid.');
		} else if (!strlen($user->getPassword())) {
			// set error : password empty
			$this->setError(102, 'Authentication Error! The password is empty or invalid.');
		} else if (!$this->hasError()) {
			// no err specified yet : set a generic authentication error
			$this->setError(100, 'Authentication Error!');
		}

		return false;
	}

	/**
	 * Trigger the specified event.
	 * Accessible only in case the user is correctly connected.
	 *
	 * This method can raise the following internal errors:
	 * - 100 = Authentication Error (Generic)
	 * - 201 = The event requested does not exists
	 * - 202 = The event requested is not valid
	 * - 203 = The event requested is not runnable
	 * - 204 = The event requested is not authorized
	 * - 500 = Internal error of the plugin executed
	 *
	 * The response of the plugin is always echoed.
	 *
	 * @param 	string		$event 		The filename of the plugin to run.
	 * @param 	array 		$args 		The arguments to pass within the plugin.
	 * @param 	boolean 	$register 	True to register the response, otherwise false to skip it.
	 *
	 * @return 	boolean		True if the plugin is executed without errors.
	 */
	public function trigger($event, array $args = array(), $register = true)
	{
		// check if APIs framework is still enabled
		if (!$this->isEnabled() || !$this->isConnected()) {
			// this condition can be verified only when triggered manually
			$this->setError(100, 'Authentication Error');
			return false;
		}

		$obj = null;

		$response = new ResponseAPIs();

		// the event requested does not exist (?) : define response and error
		$response->setStatus(0)->setContent('File Not Found! The event requested does not exists.');
		$this->setError(201, $response->getContent());

		if (file_exists($this->event_path.$event.".php")) {
			// COMMIT : the event exists

			// the event is not valid (?) : define response and error
			$response->setContent('Event Not Found! The event requested is not valid.');
			$this->setError(202, $response->getContent());

			$event_clazz = str_replace('_', ' ', $event);
			$event_clazz = ucwords($event_clazz);
			$event_clazz = str_replace(' ', '', $event_clazz);

			include $this->event_path . $event . '.php';

			if (class_exists($event_clazz)) {
				// COMMIT : the event is valid

				// the event does not own a runnable method (?) : define response and error 
				$response->setContent('Run Method Not Accessible! The event requested is not runnable.');
				$this->setError(203, $response->getContent());

				$obj = new $event_clazz($event);

				if ($obj instanceof EventAPIs) {
					// COMMIT : the event is runnable

					// the user is not authorized to run the event (?) : define response and error 
					$response->setContent('Event Authorization Error! The event requested is not authorized.');
					$this->setError(204, $response->getContent());

					if ($this->user->authorise($obj)) {
						// COMMIT : the user is authorized

						// clear the response error
						$response->clearContent();

						// run the event
						// the event is able to modify the response
						$err = $obj->run($args, $response);

						if ($response->isVerified()) {
							// call get error function to clean all
							$this->getError();
						} else {

							if ($err && $err instanceof ErrorAPIs) {
								// set error retrieved from plugin
								$this->setError($err->errcode, $err->error);
							} else {
								// generic event error (500) : get details from response
								$this->setError(500, $response->getContent());
							}
							
						}

					}
				}
			}
		}

		// register event and response
		if ($register) {
			$this->registerEvent($obj, $response);
		}

		return $response->isVerified();
	}

	/**
	 * Dispatch the specified event to catch the response echoed from the plugin.
	 * Accessible only in case the user is correctly connected.
	 *
	 * This method can raise the following internal errors:
	 * - 100 = Authentication Error (Generic)
	 * - 201 = The event requested does not exists
	 * - 202 = The event requested is not valid
	 * - 203 = The event requested is not runnable
	 * - 204 = The event requested is not authorized
	 * - 500 = Internal error of the plugin executed
	 * @uses 	trigger() 	Trigger the event to catch the response.
	 *
	 * @param 	string		$event 		The filename of the plugin to run.
	 * @param 	array 		$args 		The arguments to pass within the plugin.
	 * @param 	boolean 	$register 	True to register the response, otherwise false to skip it.
	 *
	 * @return 	string		The response echoed from the plugin on success.
	 *
	 * @throws 	Exception 	In case of failure, an exception is thrown.
	 */
	public function dispatch($event, array $args = array(), $register = false)
	{
		// start catching the response echoed
		ob_start();
		// trigger the plugin and get the verified status
		$verified = $this->trigger($event, $args, $register);
		// get the response echoed
		$contents = ob_get_contents();
		// stop catching
		ob_end_clean();

		if ($verified) {
			return $contents;
		}

		//return $this->getError()->toJSON();
		$err = $this->getError();
		throw new Exception($err->error, $err->errcode);
	}

	/**
	 * Set the last error caught.
	 * @usedby 	APIs::connect()
	 * @usedby 	APIs::trigger()
	 *
	 * @param 	string 	$code 	The code identifier of the error.
	 * @param 	string 	$str 	A text description of the error.
	 *
	 * @return 	APIs 	This object to support chaining.
	 */
	protected function setError($code, $str)
	{
		$this->error = new ErrorAPIs($code, $str);

		return $this;
	}

	/**
	 * Get the last error caught and clean it.
	 * @usedby 	APIs::trigger()
	 * @usedby 	APIs::dispatch()
	 *
	 * @return 	ErrorAPIs 	The error object if exists, otherwise NULL.
	 */
	public function getError()
	{
		$err = $this->error;
		$this->error = null;
		return $err;
	}

	/**
	 * Return true if an error has been raised.
	 * @usedby 	APIs::connect()
	 *
	 * @return 	boolean 	True in case of error, otherwise false.
	 */
	public function hasError()
	{
		return ($this->error !== null);
	}

	/**
	 * Check if the specified key is set in the configuration.
	 *
	 * @param 	string 	$key 	The configuration key to check.
	 *
	 * @return 	boolean 	True if exists, otherwise false.
	 */
	public function has($key)
	{
		return array_key_exists($key, $this->config);
	}

	/**
	 * Get the configuration value of the specified setting.
	 *
	 * @param 	string 	$key 	The key of the configuration value to get.
	 * @param 	mixed 	$def 	The default value if not exists.
	 *
	 * @return 	mixed 	The configuration value if exists, otherwise the default value.
	 *
	 * @uses 	has() 	Check if the setting exists.
	 */
	public function get($key, $def = null)
	{
		if ($this->has($key)) {
			return $this->config[$key];
		}

		return $def;
	}

	/**
	 * Set the configuration value for the specified setting.
	 *
	 * @param 	string 	$key 	The key of the configuration value to set.
	 * @param 	string 	$val 	The configuration value to set.
	 *
	 * @return 	APIs 	This object to support chaining.
	 */
	public function set($key, $val)
	{
		$this->config[$key] = $val;

		return $this;
	}

	/**
	 * Get the object of the given plugin name, otherwise return all the installed plugins if not specified.
	 *
	 * @param 	string 	$plg_name 	The name of the plugin to get.
	 * 								If not specified it will be replaced by "*" (all plugins).
	 *
	 * @return 	array 	A list of the plugins found.
	 */
	public function getPluginsList($plg_name = '')
	{
		// if the plugin name is empty or NULL
		if ($plg_name === null || empty($plg_name)) {
			// get all the installed plugins
			$plg_name = '*';
		}

		// retrieve all the plugin that match the query
		$paths = glob($this->event_path."$plg_name.php");

		$plugins = array();

		foreach ($paths as $p) {
			// require the plugin file
			require_once($p);

			// get the filename from full path
			$event = substr($p, ($n = strrpos($p, DIRECTORY_SEPARATOR)+1), strrpos($p, '.')-$n);

			// convert the filename in classname
			$event_clazz = str_replace('_', ' ', $event);
			$event_clazz = ucwords($event_clazz);
			$event_clazz = str_replace(' ', '', $event_clazz);

			if (class_exists($event_clazz)) {

				$obj = new $event_clazz($event);
				if ($obj instanceof EventAPIs) {
					$plugins[] = $obj;
				}

			}
		}

		return $plugins;
	}

	/**
	 * Authenticate the provided user and connect it on success.
	 * @usedby 	APIs::connect()
	 *
	 * @param 	UserAPIs 	$user 	The object of the user.
	 *
	 * @return 	integer 	The ID of the user on success, otherwise false.
	 */
	protected abstract function doConnection(UserAPIs $user);

	/**
	 * Check if the provided user has been banned.
	 * This action is executed only before the authentication.
	 * The ban could be evaluated on the name of the user and on the IP origin.
	 * @usedby 	APIs::connect()
	 *
	 * @param 	UserAPIs 	$user 	The object of the user.
	 *
	 * @return 	boolean 	True is the user is banned, otherwise false.
	 */
	protected abstract function isBanned(UserAPIs $user);

	/**
	 * Evaluates if the provided user needs to be banned.
	 * This action is executed only after a failed authentication.
	 * @usedby 	APIs::connect()
	 *
	 * @param 	UserAPIs 	$user 	The object of the user.
	 *
	 * @return 	boolean 	Return true if the user should be banned, otherwise false.
	 */
	protected abstract function needBan(UserAPIs $user);

	/**
	 * Register a new ban for the provided user.
	 * @usedby 	APIs::connect()
	 *
	 * @param 	UserAPIs 	$user 	The object of the user.
	 */
	protected abstract function ban(UserAPIs $user);

	/**
	 * Reset or remove the ban of the provided user.
	 * @usedby 	APIs::connect()
	 *
	 * @param 	UserAPIs 	$user 	The object of the user.
	 */
	protected abstract function resetBan(UserAPIs $user);

	/**
	 * Register the provided event and response.
	 * This log should be visible only from the administrator.
	 * @usedby 	APIs::connect()
	 * @usedby 	APIs::trigger()
	 *
	 * @param 	EventAPIs 		$event 	 	The event requested.
	 * @param 	ResponseAPIs 	$response 	The response caught or raised.
	 *
	 * @return 	boolean 	True if the event has been registered, otherwise false.
	 */
	protected abstract function registerEvent(EventAPIs $event, ResponseAPIs $response);

	/**
	 * Update the user manifest after a successful authentication.
	 * @usedby 	APIs::connect()
	 *
	 * @return 	boolean 	True on success, otherwise false.
	 *
	 * @see 	APIs::getUser() to access the user object.
	 */
	protected abstract function updateUserManifest();

}
