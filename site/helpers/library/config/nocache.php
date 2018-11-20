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
UILoader::import('library.config.config');

/**
 * Utility class working with a physical configuration stored into the Joomla database.
 *
 * @see 	UIConfig 			This class extends the configuration wrapper to avoid always cache.
 *
 * @since  	1.7
 */
class UIConfigNoCache extends UIConfig
{
	/**
	 * Class constructor.
	 *
	 * @param   int  $error_level 	The level of the error to evaluate failure attempts.
	 *
	 * @uses 	UIConfig::__construct() 	Set error level and disable cache.
	 */
	public function __construct($error_level = 0)
	{
		parent::__construct($error_level, false);
	}

	/**
	 * @override parent method
	 * Disable always the cache to force recovery from the database.
	 *
	 * @return  UIConfigNoCache  This object to support chaining.
	 */
	public function setCache($cache = false)
	{
		$this->cache = false;

		return $this;
	}

}
