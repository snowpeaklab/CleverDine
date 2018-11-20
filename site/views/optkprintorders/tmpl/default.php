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

$html = '';

foreach( $this->orders as $order ) {

	if( !empty($html) ) {
		$html .= '<div class="separator"></div>';
	}

	$tmpl = cleverdine::loadTakeAwayAdminEmailTemplate();

	$html .= cleverdine::parseTakeAwayAdminEmailTemplate($tmpl, $order);

}

?>

<div class="vr-operator-takeaway-print-orders">

	<?php echo $html; ?>

</div>