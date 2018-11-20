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

$vik = new VikApplication(VersionListener::getID());

?>

<?php if( count($this->rows) == 0 ) { ?>

	<p><?php echo JText::_('VRNOMENU');?></p>

<?php } else { ?>

	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="<?php echo $vik->getAdminTableClass(); ?>">
		<?php foreach( $this->rows as $r ) { ?>
			<tr><td style="text-align: center;">
				<span><?php echo $r['menu_name']; ?></span>
			</td></tr>
		<?php } ?>
	</table>

<?php } ?>