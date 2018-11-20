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
 * This interface declares the basic mathod that all the 2D geometric shapes should implements.
 *
 * @since  	1.7
 */
interface Shape
{
	/**
	 * Returns the perimeter of the shape.
	 *
	 * @return 	float 	The shape perimeter.
	 */
	public function perimeter();

	/**
	 * Returns the area of the shape.
	 *
	 * @return 	float 	The shape area.
	 */
	public function area();

	/**
	 * Returns the center of the shape.
	 *
	 * @return 	mixed 	The X and Y center of the shape.
	 */
	public function centroid();

}
