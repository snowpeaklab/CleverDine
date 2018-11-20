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
 * cleverdine platform factory class.
 * @final 	This class cannot be extended.
 *
 * @see 	UILoader 	Used to load external files.
 * @see 	UIConfig 	The configuration of the software.
 *
 * @since  	1.7
 */
final class UIFactory
{
	/**
	 * The configuration handler of cleverdine.
	 *
	 * @var UIConfig
	 */
	private static $config = null;

	/**
	 * The API Framework instance.
	 *
	 * @var FrameworkAPIs
	 */
	private static $apis = null;

	/**
	 * Class constructor.
	 */
	private function __construct()
	{
		// this class cannot be instantiated
	}

	/**
	 * Class cloner.
	 */
	private function __clone()
	{
		// cloning function not accessible
	}

	/**
	 * Instantiate a new configuration object.
	 *
	 * @param   int  $error_level 	The level of the error to evaluate failure attempts.
	 * @param   bool $cache 		True to cache the settings retrieved, false to read 
	 *								the settings always from the database.
	 *
	 * @return 	UIConfig 	The configuration object.
	 */
	public static function getConfig($level = 0, $cache = true)
	{
		if (self::$config === null) {

			UILoader::import('library.config.config');

			self::$config = new UIConfig($level, $cache);

		} else {

			// re-define always config params because they may 
			// be different depending on the section of the program
			self::$config->setErrorLevel($level)->setCache($cache);

		}

		return self::$config;
	}

	/**
	 * Instantiate a new Framework API object.
	 *
	 * @param   array  $config 	The associative key-val array containing all the settings.
	 *
	 * @return 	FrameworkAPIs 	The API framework object.
	 */
	public static function getApis($event_path = '')
	{
		if (self::$apis === null) {
			
			// include APIs lib and framework overrides
			cleverdine::loadFrameworkApis();

			// instantiate APIs Framework
			// leave constructor empty to select default plugins folder: 
			// components/com_cleverdine/helpers/library/apislib/apis/plugins/
			self::$apis = FrameworkAPIs::getInstance();

			// get config handler
			$config = self::getConfig(1, false);

			// set apis configuration
			self::$apis->set('max_failure_attempts', $config->getUint('apimaxfail', 10));

		}

		return self::$apis;
	}

}
