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

$plugin = $this->plugin;

$vik = new VikApplication(VersionListener::getID());

?>

<form name="adminForm" action="index.php" method="post" enctype="multipart/form-data" id="adminForm">
	
	<div class="span12">

		<?php if( $plugin === null ) { ?>

			<!-- create plugin : do nothing -->

		<?php } else { ?>

			<?php echo $vik->openFieldset($plugin->getTitle().' : '.$plugin->getName().'.php', 'form-horizontal'); ?>

				<div class="control"><?php echo $plugin->getDescription(); ?></div>

			<?php echo $vik->closeFieldset(); ?>

		<?php } ?>

	</div>

	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="option" value="com_cleverdine"/>
</form>