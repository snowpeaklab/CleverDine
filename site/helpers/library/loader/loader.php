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
 * cleverdine loader class
 *
 * @since  1.7
 */
abstract class UILoader
{
	/**
	 * The list containing all the resources loaded.
	 *
	 * @var array
	 */
	private static $includes = array();

	/**
	 * The list containing all the filename aliases.
	 *
	 * @var array
	 */
	private static $aliases = array();

	/**
	 * Loads the specified file.
	 *
	 * @param   string  $key   The class name to look for (dot notation).
	 * @param   string  $base  Search this directory for the class.
	 *
	 * @return  boolean  True on success, otherwise false.
	 */
	public static function import($key, $base = null)
	{
		if (!isset(static::$includes[$key])) {

			$success = false;

			if (empty($base)) {
				$base = JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_cleverdine'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR;
			}

			$parts = explode('.', $key);
			$class = array_pop($parts);

			if (isset(static::$aliases[$class])) {
				$class = static::$aliases[$class];
			}

			$path = implode(DIRECTORY_SEPARATOR, $parts) . (count($parts) ? DIRECTORY_SEPARATOR : '') . $class;

			if (is_file($base . $path . '.php')) {
				$success = (bool) include $base . $path . '.php';
			}

			static::$includes[$key] = $success;
		}

		return static::$includes[$key];
	}

	/**
	 * Register an alias of a given class filename.
	 * This is useful for those files that contain a dot in their name.
	 *
	 * @param 	string 	$name 	The filename to register.
	 * @param 	string 	$alias 	The alias to use.
	 */
	public static function registerAlias($name, $alias)
	{	
		if (!isset(static::$aliases[$alias])) {
			static::$aliases[$alias] = $name;
		}
	}

}
