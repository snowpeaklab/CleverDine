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
defined('_JEXEC') OR die('Restricted Area');

defined('cleverdine_SOFTWARE_VERSION') or define('cleverdine_SOFTWARE_VERSION', '1.0');
defined('CLEVERAPP') or define('CLEVERAPP', 'com_cleverdine');

require_once JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_cleverdine'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'library'.DIRECTORY_SEPARATOR.'loader'.DIRECTORY_SEPARATOR.'autoload.php';

if (!VersionListener::isSupported()) {
	die('This Joomla version is not supported!');
}

JFactory::getDocument()->addStyleSheet(JUri::root().'components/com_cleverdine/assets/css/cleverdine.css');

// import joomla controller library
jimport('joomla.application.component.controller');
// Get an instance of the controller prefixed by cleverdine
$controller = JControllerUI::getInstance('cleverdine');
// Perform the request task
$controller->execute(JFactory::getApplication()->input->get('task'));
// Redirect if set by the controller
$controller->redirect();
