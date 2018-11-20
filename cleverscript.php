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

defined('CLEVERAPP') or define('CLEVERAPP', 'com_cleverdine');

jimport('joomla.installer.installer');
jimport('joomla.installer.helper');

/**
 * Script file of cleverdine component.
 *
 * @since 1.0
 */
class com_cleverdineInstallerScript
{
	/**
	 * Method to install the component.
	 *
	 * @param 	object 	  $parent 	The parent class which is calling this method.
	 *
	 * @return 	boolean   True on success, otherwise false to stop the flow.
	 */
	function install($parent)
	{
		// load component dependencies
		//require_once JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_cleverdine'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'library'.DIRECTORY_SEPARATOR.'loader'.DIRECTORY_SEPARATOR.'autoload.php';


		?>
		<div style="text-align: center;">
			<p><strong>Cleverdine v1.0 - WoodBox Media</strong></p>
			<img src="<?php echo JUri::root(); ?>administrator/components/com_cleverdine/assets/images/cleverlogo.png"/>
		</div>
		<?php

		return true;
	}

	/**
	 * Method to uninstall the component.
	 *
	 * @param 	object 	  $parent 	The parent class which is calling this method.
	 *
	 * @return 	boolean   True on success, otherwise false to stop the flow.
	 */
	function uninstall($parent)
	{
		echo 'Cleverdine was uninstalled. Wood Box Media - <a href="https://woodboxmedia.co.uk">Woodboxmedia.co.uk</a>';

		return true;
	}

	/**
	 * Method to update the component.
	 *
	 * @param 	object 	  $parent 	The parent class which is calling this method.
	 *
	 * @return 	boolean   True on success, otherwise false to stop the flow.
	 */
	function update($parent)
	{
		// load component dependencies
		require_once JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_cleverdine'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'library'.DIRECTORY_SEPARATOR.'loader'.DIRECTORY_SEPARATOR.'autoload.php';

		// return update callbacks esit
		return $this->runUpdateCallbacks($this->version, 'update');
	}

	/**
	 * Method to run before an install/update/uninstall method.
	 *
	 * @param 	string    $type 	The method type [install, update, uninstall].
	 * @param 	object 	  $parent 	The parent class which is calling this method.
	 *
	 * @return 	boolean   True on success, otherwise false to stop the flow.
	 */
	function preflight($type, $parent)
	{
		// no need to continue if the type is not an updater
		if ($type !== 'update') {
			return true;
		}

		// NOTE. no access to new files of the updater downloaded/installed
		// you MUST use new libraries in update and postflight methods.

		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true);

		$q->select('setting')->from('#__cleverdine_config')->where('param = ' . $dbo->quote('version'));

		$dbo->setQuery($q, 0, 1);
		$dbo->execute();

		if (!$dbo->getNumRows()) {
			// impossible to recognize the version of the component
			return false;
		}

		// keep current version in the properties of this class
		$this->version = $dbo->loadResult();

		/**
		 * Get custom fields.
		 * @since 1.7
		 */
		if (version_compare($this->version, '1.7', '<')) {

			$q = $dbo->getQuery(true);

			$q->select('*')
				->from($dbo->qn('#__cleverdine_custfields'));

			$dbo->setQuery($q);
			$dbo->execute();

			if ($dbo->getNumRows()) {
				$this->cfields = $dbo->loadObjectList();
			}

		}

		return true;
	}

	/**
	 * Method to run after an install/update/uninstall method.
	 *
	 * @param 	string    $type 	The method type [install, update, uninstall].
	 * @param 	object 	  $parent 	The parent class which is calling this method.
	 *
	 * @return 	boolean   True on success, otherwise false to stop the flow.
	 */
	function postflight($type, $parent)
	{
		// no need to continue if the type is not an updater
		if ($type !== 'update') {
			return true;
		}

		// return finalise callbacks esit
		return $this->runUpdateCallbacks($this->version, 'finalise');
	}

	/**
	 * Loop through each supported version to discover update adapters.
	 *
	 * ------------------------------------------------------------------------------------
	 *
	 * Update adapters CLASS name must have the following structure:
	 * 
	 * COMPONENT_NAME (no com_) + "UpdateAdapter" + VERSION (replace dots with underscores)
	 * eg. ExampleUpdateAdapter1_2_5 (com_example 1.2.5)
	 *
	 * ------------------------------------------------------------------------------------
	 *
	 * Update adapters FILE name must have the following structure:
	 * 
	 * "upd" + VERSION (replace dots with underscores) + ".php"
	 * eg. upd1_2_5.php (com_example 1.2.5)
	 *
	 * @param 	string 	$version 	The current version of the software. 	
	 * @param 	string 	$callback 	The callback function to perform.
	 *
	 * @return 	boolean   True on success, otherwise false to stop the flow.
	 *
	 * @since 	1.7
	 */
	private function runUpdateCallbacks($version, $callback)
	{
		// iterate each supported version
		foreach ($this->versionsPool as $v) {
			// get version suffix by replacing all dots with underscores.
			$safe_suffix = str_replace('.', '_', $v);

			// get filename to include updater adapter for current loop version
			$filename = 'upd' . $safe_suffix;

			// get class name of update adapter for current loop version
			$classname = 'cleverdineUpdateAdapter' . $safe_suffix;

			// in case the software version is lower than loop version
			if (version_compare($version, $v, '<')) {

				// load updater adapter file
				$loaded = UILoader::import('library.update.adapters.' . $filename);

				// in case the file has been loaded
				// and the adapter class owns the specified callback
				if ($loaded && method_exists($classname, $callback)) {
					// then run update callback function
					$success = call_user_func(array($classname, $callback), $this);

					if ($success === false) {
						// stop adapters in case something gone wrong
						return false;
					}
				}

				// NOTE. it is not needed to check if the class exists because the 
				// method_exists function would return always false
			}

		}

		// no error found
		return true;
	}

	/**
	 * List containing all the versions next to the very first (supported) one.
	 *
	 * @var 	array
	 * @since 	1.7
	 */
	private $versionsPool = array('1.7', '1.7.1', '1.7.2', '1.0');

}
