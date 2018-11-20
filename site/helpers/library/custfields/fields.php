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
 * cleverdine custom fields class handler.
 *
 * @see 	JFactory 	Joomla Factory to access the database resource.
 *
 * @since  	1.7
 */
abstract class VRCustomFields
{
	/**
	 * The default country code in case it is not specified.
	 * At the first position there is the country code for the restaurant,
	 * in the other the one for the take-away.
	 *
	 * @var array
	 */
	public static $default_country = array('US', 'US');

	/**
	 * Return the list of the custom fields for the specified section.
	 *
	 * @param 	integer 	$group 	The section of the program: 0 for Restaurant, 1 for Take-Away.
	 *
	 * @return 	array 	The list of custom fields.
	 */
	public static function getList($group = 0)
	{
		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true);

		$q->select('*')
			->from($dbo->quoteName('#__cleverdine_custfields'))
			->where($dbo->quoteName('group') . ' = ' . (int) $group)
			->order($dbo->quoteName('ordering').' ASC');

		$dbo->setQuery($q);
		$dbo->execute();

		if ($dbo->getNumRows() > 0) {
			return $dbo->loadAssocList();
		}

		return array();

	}

	/**
	 * Return the default country code assigned to the phone number custom field.
	 *
	 * @param 	integer 	$group 		The section of the program: 0 for Restaurant, 1 for Take-Away.
	 * @param 	string 		$langtag 	The langtag to retrieve the proper country depending on the current language.
	 *
	 * @param 	string 		The default country code.
	 */
	public static function getDefaultCountryCode($group = 0, $langtag = '')
	{
		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true);

		$q->select('`c`.`id`, `c`.`choose`, `l`.`choose` AS `lang_choose`, `l`.`tag`')
			->from($dbo->quoteName('#__cleverdine_custfields', 'c'))
			->join('LEFT', $dbo->quoteName('#__cleverdine_lang_customf', 'l') . ' ON `l`.`id_customf`=`c`.`id`')
			->where('`c`.`group` = ' . (int) $group)
			->where('`c`.`rule` = ' . self::PHONE_NUMBER);

		if (!empty($langtag)) {
			$q->where('`l`.`tag` = ' . $dbo->quote($langtag));
		}

		$dbo->setQuery($q, 0, 1);
		$dbo->execute();

		if( $dbo->getNumRows() == 0 ) {
			return self::$default_country[$group];
		}

		$row = $dbo->loadAssoc();

		if (!empty($langtag) && $row['tag'] == $langtag && strlen($row['lang_choose'])) {
			$row['choose'] = $row['lang_choose'];
		}

		return strlen($row['choose']) ? $row['choose'] : self::$default_country[$group];

	}

	/**
	 * Check if the custom field is a nominative.
	 *
	 * @param 	mixed 	$cf 	The array or the object of the custom field.
	 *
	 * @param 	boolean 	True if nominative, otherwise false.
	 *
	 * @uses 	getRule() 	Estabilish the rule from the variable.
	 */
	public static function isNominative($cf)
	{
		return (static::getRule($cf) == self::NOMINATIVE);
	}

	/**
	 * Check if the custom field is an e-mail.
	 *
	 * @param 	mixed 	$cf 	The array or the object of the custom field.
	 *
	 * @param 	boolean 	True if e-mail, otherwise false.
	 *
	 * @uses 	getRule() 	Estabilish the rule from the variable.
	 */
	public static function isEmail($cf)
	{
		return (static::getRule($cf) == self::EMAIL);
	}

	/**
	 * Check if the custom field is a phone number.
	 *
	 * @param 	mixed 	$cf 	The array or the object of the custom field.
	 *
	 * @param 	boolean 	True if phone number, otherwise false.
	 *
	 * @uses 	getRule() 	Estabilish the rule from the variable.
	 */
	public static function isPhoneNumber($cf)
	{
		return (static::getRule($cf) == self::PHONE_NUMBER);
	}

	/**
	 * Check if the custom field is an address.
	 *
	 * @param 	mixed 	$cf 	The array or the object of the custom field.
	 *
	 * @param 	boolean 	True if address, otherwise false.
	 *
	 * @uses 	getRule() 	Estabilish the rule from the variable.
	 */
	public static function isAddress($cf)
	{
		return (static::getRule($cf) == self::ADDRESS);
	}

	/**
	 * Check if the custom field is a delivery field.
	 *
	 * @param 	mixed 	$cf 	The array or the object of the custom field.
	 *
	 * @param 	boolean 	True if delivery field, otherwise false.
	 *
	 * @uses 	getRule() 	Estabilish the rule from the variable.
	 */
	public static function isDelivery($cf)
	{
		return (static::getRule($cf) == self::DELIVERY);
	}

	/**
	 * Check if the custom field is a ZIP field.
	 *
	 * @param 	mixed 	$cf 	The array or the object of the custom field.
	 *
	 * @param 	boolean 	True if ZIP field, otherwise false.
	 *
	 * @uses 	getRule() 	Estabilish the rule from the variable.
	 */
	public static function isZip($cf)
	{
		return (static::getRule($cf) == self::ZIP);
	}

	/**
	 * Get the rule property of the specified custom field object.
	 *
	 * @param 	mixed 	$cf 	The array or the object of the custom field.
	 *
	 * @return 	integer 	The rule of the custom field, 
	 * 						NONE if it is not possible to estabilish it.
	 */
	protected static function getRule($cf)
	{
		if (is_array($cf)) {
			
			if (array_key_exists('rule', $cf)) {
				return $cf['rule'];
			}

		} else if (is_object($cf)) {

			if (property_exists($cf, 'rule')) {
				return $cf->rule;
			}

		}

		return self::NONE;
	}

	/**
	 * NONE identifier rule.
	 *
	 * @var integer
	 */
	const NONE = 0;

	/**
	 * NOMINATIVE identifier rule.
	 *
	 * @var integer
	 */
	const NOMINATIVE = 1;

	/**
	 * EMAIL identifier rule.
	 *
	 * @var integer
	 */
	const EMAIL = 2;

	/**
	 * PHONE NUMBER identifier rule.
	 *
	 * @var integer
	 */
	const PHONE_NUMBER = 3;

	/**
	 * ADDRESS identifier rule.
	 *
	 * @var integer
	 */
	const ADDRESS = 4;

	/**
	 * DELIVERY identifier rule.
	 *
	 * @var integer
	 */
	const DELIVERY = 5;

	/**
	 * ZIP identifier rule.
	 *
	 * @var integer
	 */
	const ZIP = 6;
}
