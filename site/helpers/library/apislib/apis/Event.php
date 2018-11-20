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
 * The APIs event (plugin) representation.
 * The classname of a plugin must follow the standard below:
 * e.g. File = plugin.php   		Class = Plugin
 * e.g. File = plugin_name.php   	Class = PluginName
 *
 * @see 	ResponseAPIs
 *
 * @since  	1.7
 */
abstract class EventAPIs
{
	/**
	 * The name of the event. Usually equal to the filename.
	 *
	 * @var string
	 */
	private $name;

	/**
	 * Class constructor.
	 *
	 * @param 	string 	$name 	The name of the event.
	 */
	public function __construct($name = '')
	{
		$this->name = strlen($name) ? $name : uniqid();
	}

	/**
	 * Get the name of the event.
	 *
	 * @return 	string 	The name of the event.
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Get the title of the event, a more readable representation of the plugin name.
	 *
	 * @return 	string 	The title of the event.
	 */
	public function getTitle()
	{
		return ucwords(str_replace("_", " ", $this->name));
	}

	/**
	 * Get the description of the plugin.
	 *
	 * @return 	string 	An empty string. To display a description,
	 *					override this method from the child class.
	 */
	public function getDescription()
	{
		return "";
	}

	/**
	 * Returns true if the plugin is always authorised, otherwise false.
	 * When this value is false, the system will need to authorise the plugin 
	 * through the ACL of the user.
	 *
	 * @return 	boolean 	Always false. To allow always this plugin,
	 *						override this method from the child class.
	 */
	public function alwaysAllowed()
	{
		return false;
	}

	/**
	 * Perform the action of the event.
	 *
	 * @param 	array 			$args 		The provided arguments for the event.
	 * @param 	ResponseAPIs 	$response 	The response object for admin.
	 *
	 * @return 	ErrorAPIs 	The error occurred, if any.
	 *
	 * @uses 	doAction() 	execute the action code of the event.
	 */
	public function run(array $args, ResponseAPIs &$response)
	{
		return $this->doAction($args, $response);
	}

	/**
	 * The custom action that the event have to perform.
	 * This method should not contain any exit or die function, 
	 * otherwise the event won't be stopped properly.
	 *
	 * All the information to return, should be echoed instead.
	 *
	 * @usedby 	EventAPIs::run()
	 *
	 * @param 	array 			$args 		The provided arguments for the event.
	 * @param 	ResponseAPIs 	$response 	The response object for admin.
	 *
	 * @return 	ErrorAPIs 	The error occurred, if any.
	 */
	protected abstract function doAction(array $args, ResponseAPIs &$response);

}
