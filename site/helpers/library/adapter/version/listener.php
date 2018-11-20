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

UILoader::import('library.adapter.version.recognizer');

/**
 * Version listener class to identify which Joomla is supported.
 *
 * @see 	VersionRecognizer  This class extends the version recognizer.
 *
 * @since  	1.2 	The native methods of this class have been 
 *					moved to the parent VersionRecognizer class.
 */
final class VersionListener extends VersionRecognizer
{
	/**
	 * Check if the installed Joomla is supported.
	 * The Joomla version is not supported when the installed Joomla is lower than 2.5.
	 *
	 * @return 	boolean 	True if Joomla version is supported, otherwise false.
	 *
	 * @since 	1.7
	 */
	public static function isSupported()
	{
		// this condition covers also the UNSOPPORTED cases
		return self::getID() > self::J15;
	}
	
}
