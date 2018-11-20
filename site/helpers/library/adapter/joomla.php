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

// import Joomla controller library
jimport('joomla.application.component.controller');
// import Joomla view library
jimport('joomla.application.component.view');

// this should be already loaded from autoload.php
UILoader::import('library.adapter.version.listener');

if (VersionListener::isJoomla25() === false) {

	/* Joomla 3.x adapters */

	class JViewUI extends JViewLegacy
	{
		/* adapter for JViewLegacy */
	}

	class JControllerUI extends JControllerLegacy
	{
		/* adapter for JControllerLegacy */
	}

} else {

	/* Joomla 2.5 adapters */

	class JViewUI extends JView
	{
		/* adapter for JView */
	}

	class JControllerUI extends JController
	{
		/* adapter for JController */
	}

}
