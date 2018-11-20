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

class cleverdineConstraintsPDF
{    
    public $pageOrientation = self::PAGE_ORIENTATION_PORTRAIT;
    public $unit = self::UNIT_MILLIMETER;
    public $pageFormat = self::PAGE_FORMAT_A4;
    public $margins = array(
        "top" => 10, "bottom" => 10, "right" => 10, "left" => 10, "header" => 5, "footer" => 5
    );
    //ratio used to adjust the conversion of pixels to user units
    public $imageScaleRatio = 1.25;
    
    public $fontSizes = array(
       "header" => 10, "body" => 10, "footer" => 10
    );
    
    // PAGE ORIENTATION
    const PAGE_ORIENTATION_LANDSCAPE    = 'L';
    const PAGE_ORIENTATION_PORTRAIT     = 'P';
    
    // UNIT
    const UNIT_POINT        = 'pt';
    const UNIT_MILLIMETER   = 'mm';
    const UNIT_CENTIMETER   = 'cm';
    const UNIT_INCH         = 'in';
    
    // PAGE FORMAT
    const PAGE_FORMAT_A4    = 'A4';
    const PAGE_FORMAT_A5    = 'A5';
    const PAGE_FORMAT_A6    = 'A6';
}
