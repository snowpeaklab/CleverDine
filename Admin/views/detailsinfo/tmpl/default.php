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

$rows = $this->rows;
$args = $this->args;

?>

<table class="adminform">
	<?php foreach( $rows as $row ) {
		?><tr>
			<td>Reservation ID:</td>
			<td style="text-align: center;"><a href="index.php?option=com_cleverdine&task=purchaserinfo&oid=<?php echo $row['id']; ?>&goback=<?php echo urlencode($go_back); ?>&tmpl=component">#<?php echo $row['id']; ?></a></td>
		</tr>
	<?php } ?>
</table>
	