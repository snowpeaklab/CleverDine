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

defined('cleverdine_SOFTWARE_VERSION') or define('cleverdine_SOFTWARE_VERSION', '1.0');
defined('CLEVERAPP') or define('CLEVERAPP', 'com_cleverdine');

// require helper files
include JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_cleverdine'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'helper.php';
include JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_cleverdine'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'library'.DIRECTORY_SEPARATOR.'loader'.DIRECTORY_SEPARATOR.'autoload.php';

if (!VersionListener::isSupported()) {
	die('This Joomla version is not supported!');
}

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_cleverdine')) {
	if (VersionListener::getID() >= VersionListener::J35) {
		// the exception will be handle by the Joomla core
		throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'), 404);
	} else {
		// return the error to the control page of Joomla
		return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
	}
}

// handle list ordering manager 
new OrderingManager('com_cleverdine', 'vrordcolumn', 'vrordtype');

// Add CSS file and JS for all pages
RestaurantsHelper::load_css_js();

// remove expired credit cards
// check every 15 minutes only
cleverdine::removeExpiredCreditCards();

// check updater fields : add them in case are missing
RestaurantsHelper::registerUpdaterFields();

// import joomla controller library
jimport('joomla.application.component.controller');
// Get an instance of the controller prefixed by Restaurants
$controller = JControllerUI::getInstance('cleverdine');
// Perform the Request task
$controller->execute(JFactory::getApplication()->input->get('task'));
// Redirect if set by the controller
$controller->redirect();
