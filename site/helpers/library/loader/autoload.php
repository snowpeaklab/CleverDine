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

if (!class_exists('UILoader')) {
	include JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_cleverdine'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'library'.DIRECTORY_SEPARATOR.'loader'.DIRECTORY_SEPARATOR.'loader.php';
}

// fix filenames with dots
UILoader::registerAlias('lib.cleverdine', 'lib_cleverdine');
UILoader::registerAlias('pdf.constraints', 'constraints'); // this will be loaded specifically

// load adapters
UILoader::import('library.adapter.version.listener');
UILoader::import('library.adapter.application');
UILoader::import('library.adapter.joomla');

// load factory
UILoader::import('library.factory.factory');

// load custom fields
UILoader::import('library.custfields.fields');

// load component helper
UILoader::import('lib_cleverdine');
