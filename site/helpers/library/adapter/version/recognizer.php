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
 * Version recognizer class to identify which Joomla is running.
 *
 * @see 	JVersion 	Used to identify the installed Joomla version.
 *
 * @since  	1.7
 */

class VersionRecognizer
{	
	/**
	 * The identifier of the Joomla version.
	 *
	 * @var integer
	 */
	private static $id = null; 
	
	/**
	 * Class constructor.
	 */
	private function __construct()
	{
		// not accessible
	}

	/**
	 * Class cloner.
	 */
	private function __clone()
	{
		// not accessible
	}
	
	/**
	 * Recognize the Joomla version and return the respective indetifier.
	 *
	 * @return 	integer 	The identifier of the Joomla version.
	 *
	 * @uses 	JVersion 	Recognize the current Joomla version.
	 */
	public static function getID()
	{	
		if (self::$id === null) {

			if (class_exists('JVersion')) {

				$version = new JVersion();
				$v = $version->getShortVersion();
				
				if (version_compare($v, '2.5') >= 0 && version_compare($v, '3.0') < 0) {
					self::$id = self::J25;	// joomla 2.5
				}
				else if (version_compare($v, '3.0') >= 0 && version_compare($v, '3.5') < 0)
				{
					self::$id = self::J30; // joomla 3.0, 3.1, 3.2, 3.3, 3.4
				}
				else if (version_compare($v, '3.5') >= 0 && version_compare($v, '3.7') < 0)
				{
					self::$id = self::J35; // joomla 3.5, 3.6
				}
				else if (version_compare($v, '3.7') >= 0 && version_compare($v, '4.0') < 0)
				{
					self::$id = self::J37; // joomla 3.7, 3.8, 3.9
				}
				else if (version_compare($v, '4.0') >= 0)
				{
					self::$id = self::J40; // joomla 4.0
				}
				else
				{
					// version not supported
					self::$id = self::UNSUPPORTED;
				}

			} else {
				// if JVersion class does not exist, mark this version as Joomla 1.5
				self::$id = self::J15;
			}

		}

		return self::$id;
	}

	/**
	 * Check if the installed Joomla is 1.5 or 1.6.
	 *
	 * @return 	boolean 	True if Joomla is 1.5 or 1.6, otherwise false.
	 *
	 * @since 	1.7
	 */
	public static function isJoomla15()
	{
		return self::getID() == self::J15;
	}

	/**
	 * Check if the installed Joomla is 2.5.
	 *
	 * @return 	boolean 	True if Joomla is 2.5, otherwise false.
	 *
	 * @since 	1.7
	 */
	public static function isJoomla25()
	{
		return self::getID() == self::J25;
	}

	/**
	 * Check if the installed Joomla is between 3.0 and 3.4.
	 *
	 * @return 	boolean 	True if Joomla is between 3.0 and 3.4, otherwise false.
	 *
	 * @since 	1.7
	 */
	public static function isJoomla30()
	{
		return self::getID() == self::J30;
	}

	/**
	 * Check if the installed Joomla is between 3.5 and 4.0 (excluded).
	 *
	 * @return 	boolean 	True if Joomla is between 3.0 and 4.0 (excluded), otherwise false.
	 *
	 * @since 	1.7
	 */
	public static function isJoomla35()
	{
		return self::getID() == self::J35;
	}

	/**
	 * Check if the installed Joomla is 3.7 or higher.
	 *
	 * @return 	boolean 	True if Joomla is 3.7 or higher, otherwise false.
	 *
	 * @since 	1.7.1
	 */
	public static function isJoomla37()
	{
		return self::getID() == self::J37;
	}

	/**
	 * Check if the installed Joomla is 4.0 or higher.
	 *
	 * @return 	boolean 	True if Joomla is 4.0 or higher, otherwise false.
	 *
	 * @since 	1.7
	 */
	public static function isJoomla40()
	{
		return self::getID() == self::J40;
	}

	/**
	 * Check if the installed Joomla is supported.
	 * The Joomla version is not supported when the class is not able
	 * to recognize the installed version.
	 *
	 * @return 	boolean 	True if the Joomla version is supported, otherwise false.
	 *
	 * @since 	1.7
	 */
	public static function isSupported()
	{
		return self::getID() != self::UNSUPPORTED;
	}

	/**
	 * Check if the current Joomla version is higher than the provided one.
	 *
	 * @param 	integer 	The version to check.
	 *
	 * @return 	boolean 	True if the current version is higher, otherwsie false.
	 *
	 * @since 	1.7.1
	 */
	public static function isHigherThan($version)
	{
		return self::getID() > $version;
	}

	/**
	 * Check if the current Joomla version is lower than the provided one.
	 *
	 * @param 	integer 	The version to check.
	 *
	 * @return 	boolean 	True if the current version is lower, otherwsie false.
	 *
	 * @since 	1.7.1
	 */
	public static function isLowerThan($version)
	{
		return self::getID() < $version;
	}

	/**
	 * The UNSUPPORTED version identifier.
	 *
	 * @var 	integer
	 *
	 * @since 	1.7
	 */
	const UNSUPPORTED = -1;

	/**
	 * The Joomla 1.5 version identifier.
	 *
	 * @var 	integer
	 *
	 * @since 	1.7
	 */
	const J15 = 0;

	/**
	 * The Joomla 2.5 version identifier.
	 *
	 * @var 	integer
	 *
	 * @since 	1.7
	 */
	const J25 = 1;

	/**
	 * The Joomla 3.0 to 3.4 version identifier.
	 *
	 * @var 	integer
	 *
	 * @since 	1.7
	 */
	const J30 = 2;

	/**
	 * The Joomla 3.5 to 4.0 (excluded) version identifier.
	 *
	 * @var 	integer
	 *
	 * @since 	1.7
	 */
	const J35 = 3;

	/**
	 * The Joomla 3.7 version identifier.
	 *
	 * @var 	integer
	 *
	 * @since 	1.7.1
	 */
	const J37 = 4;

	/**
	 * The Joomla 4.0 version identifier.
	 *
	 * @var 	integer
	 *
	 * @since 	1.7 (@since 1.7.1 changed from 4 to 5)
	 */
	const J40 = 5;
}
