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

// include parent class in order to extend the configuration without errors
UILoader::import('library.config.wrapper');

/**
 * Utility class working with a physical configuration stored into the Joomla database.
 *
 * @see 	UIConfigWrapper 	This class extends the configuration wrapper.
 * @see 	JFactory 			Joomla Factory class to access database, application and session resources.
 *
 * @since  	1.7
 */
class UIConfig extends UIConfigWrapper
{
	/**
	 * The error level for failure attempts: 1 for Development, 0 for Simple.
	 *
	 * @var integer
	 */
	protected $error_level;

	/**
	 * Specify 1 if you want to cache into the session the settings used.
	 *
	 * @var boolean
	 */
	protected $cache;

	/**
	 * The Joomla global application object.
	 *
	 * @var JApplicationCms 	
	 */
	private static $app = null;

	/**
	 * The Joomla global database driver object.
	 *
	 * @var JDatabaseDriver
	 */
	private static $dbo = null;

	/**
	 * The Joomla global session handler object.
	 *
	 * @var JSession
	 */
	private static $session = null;

	/**
	 * Class constructor.
	 *
	 * @param   int  $error_level 	The level of the error to evaluate failure attempts.
	 * @param   bool $cache 		True to cache the settings retrieved, false to read 
	 *								the settings always from the database.
	 *
	 * @uses 	setErrorLevel		Error level option setter.
	 * @uses 	setCache			Cache option setter.
	 */
	public function __construct($error_level = 0, $cache = true)
	{
		$this->setErrorLevel($error_level)
			->setCache($cache);
	}

	/**
	 * Set the error level to evaluate failure attempts.
	 *
	 * @return  UIConfig  This object to support chaining.
	 *
	 * @usedby UIConfig::__construct()
	 */
	public function setErrorLevel($error_level)
	{
		if ($error_level < self::SIMPLE || $error_level > self::DEVELOPMENT) {
			$error_level = self::SIMPLE;
		}

		$this->error_level = $error_level;

		return $this;
	}

	/**
	 * Set the cache option to maintain the settings used.
	 *
	 * @return  UIConfig  This object to support chaining.
	 *
	 * @usedby UIConfig::__construct()
	 * @usedby UIConfig::startCaching()
	 * @usedby UIConfig::stopCaching()
	 */
	public function setCache($cache)
	{
		$this->cache = $cache;

		return $this;
	}

	/**
	 * Cache all the future settings into the session.
	 * @uses 	setCache 	Cache option setter.
	 *
	 * @return  UIConfig  This object to support chaining.
	 */
	public function startCaching()
	{
		return $this->setCache(true);
	}

	/**
	 * Ignore caching for all the future settings.
	 * Recover the settings always from the database.
	 * @uses 	setCache 	Cache option setter.
	 *
	 * @return  UIConfig  This object to support chaining.
	 */
	public function stopCaching()
	{
		return $this->setCache(false);
	}

	/**
	 * @override
	 * Retrieve the value of the setting stored in the Joomla database.
	 * When cache is enable and the user is in the front-end store 
	 * the setting value into the session to speed up future usages.
	 * @uses 	getResources 	Load the resources needed.
	 *
	 * @param   string   $key 	The name of the setting.
	 *
	 * @return  mixed 	The value of the setting if exists, otherwise false.
	 *
	 * @throws 	Exception if the error reporting is set to DEVELOPMENT and the setting does not exist.
	 */
	protected function retrieve($key)
	{
		// load the resources
		list($app, $dbo, $session) = self::getResources();

		$value = '';
		
		// read the setting from DB only if you are in the admin section 
		// or the setting is not stored in the session 
		// or the cache is disabled
		if ($app->isAdmin() || !$session->has($key, 'vrconfig') || $this->cache === false) {

			// read value from database
			$value = $this->getFromDatabase($key);

			// register the setting if it exists
			// and you are in the site section
			// and it is possible to cache
			if ($value !== false && $app->isSite() && $this->cache) {
				// push setting in the session
				$session->set($key, $value, 'vrconfig');
			}

		} else {
			// access this statement only if you are in the site section
			// and the setting is stored in the session
			// and cache is enabled

			// otherwise get the setting from the session
			$value = $session->get($key, false, 'vrconfig');
		}

		// if the setting does not exist and the error level is set to DEVELOPMENT
		if ($value === false && $this->error_level === self::DEVELOPMENT) {
			// throw an exception and stop the flow
			throw new Exception("cleverdine - Configuration key not found [$key]");
		}

		return $value;

	}

	/**
	 * @override
	 * Register the value of the setting into the Joomla database.
	 * All the array and objects will be stringified in JSON.
	 * @uses 	getResources 	Load the resources needed.
	 *
	 * @param   string  $key 	The name of the setting.
	 * @param   mixed   $val 	The value of the setting.
	 *
	 * @return  bool 	True in case of success, otherwise false.
	 */
	protected function register($key, $val)
	{
		if (is_array($val) || is_object($val)) {
			$val = json_encode($val);
		}

		// load the resources
		list($app, $dbo, $session) = self::getResources();

		$query = $dbo->getQuery(true);

		$query->update($dbo->quoteName('#__cleverdine_config'))
			->set($dbo->quoteName('setting') . ' = ' . $dbo->quote($val))
			->where($dbo->quoteName('param') . ' = ' . $dbo->quote($key));

		$dbo->setQuery($query);
		$dbo->execute();

		return ($dbo->getAffectedRows() ? true : false);

	}

	/**
	 * Read the value of the specified setting from the database.
	 * @uses 	getResources 	Load the resources needed.
	 *
	 * @param   string   $key 	The name of the setting.
	 *
	 * @return  mixed 	The value of the setting.
	 *
	 * @throws 	Exception is the error reporting is set to DEVELOPMENT and the setting does not exist.
	 */
	private function getFromDatabase($key)
	{
		list($app, $dbo, $session) = self::getResources();

		$query = $dbo->getQuery(true);

		$query->select($dbo->quoteName('setting'))
			->from($dbo->quoteName('#__cleverdine_config'))
			->where($dbo->quoteName('param') . ' = ' . $dbo->quote($key));

		$dbo->setQuery($query, 0, 1);
		$dbo->execute();

		if ($dbo->getNumRows()) {
			return $dbo->loadResult();
		}

		return false;
	}

	/**
	 * Load the resources needed to retrieve the settings.
	 *
	 * @return array 	An array containing JApplicationCms, JDatabaseDriver and JSession objects
	 *
	 * @usedby UIConfig::retrieve()
	 * @usedby UIConfig::getFromDatabase()
	 * @usedby UIConfig::register()
	 */
	protected static function getResources()
	{
		if (self::$app === null) {
			self::$app = JFactory::getApplication();
		}

		if (self::$dbo === null) {
			self::$dbo = JFactory::getDbo();
		}

		if (self::$session === null) {
			self::$session = JFactory::getSession();
		}

		return array(self::$app, self::$dbo, self::$session);

	}

	/**
	 * The SIMPLE error level identifier.
	 *
	 * @var integer
	 */
	const SIMPLE = 0;

	/**
	 * The DEVELOPMENT error level identifier.
	 *
	 * @var integer
	 */
	const DEVELOPMENT = 1;

}
