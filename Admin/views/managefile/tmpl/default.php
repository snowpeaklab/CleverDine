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

$code_mirror = $vik->getCodeMirror('code', $this->content);

?>

<form action="index.php" method="POST" name="adminForm" id="adminForm">
	
	<div class="maincont">

		<div class="btn-toolbar vr-file-toolbar" style="height: 48px;">
			<div class="btn-group pull-left">
				<h3><?php echo $this->fileName; ?></h3>
			</div>
			<div class="btn-group pull-right">
				<button type="submit" class="btn"><?php echo JText::_('VRSAVE'); ?></button>
			</div>
			<div class="btn-group pull-right">
				<button type="button" class="btn" onClick="saveAsCopyButtonPressed();"><?php echo JText::_('VRSAVEASCOPY'); ?></button>
			</div>
		</div>
		
		<div class="vr-file-box">
			<?php echo $code_mirror; ?>
		</div>
	
	</div>
	
	<input type="hidden" name="file" value="<?php echo $this->filePath; ?>"/>
	<input type="hidden" name="task" value="storefile"/>
	<input type="hidden" name="option" value="com_cleverdine"/>
	
</form>

<div id="dialog-confirm" title="<?php echo JText::_('VREXPORTRES1');?>" style="display: none;">
		<p>
			<span class="ui-icon ui-icon-pencil" style="float: left; margin: 0 7px 20px 0;"></span>
			<span><input type="text" id="dialog-confirm-input" value="file.php"/></span>
		</p>
	</div>

<script>
	
	function saveAsCopyButtonPressed() {
		jQuery('#adminForm').append('<input type="hidden" name="ascopy" value="1"/>');
		openRenameModalBox();
	}
	
	function openRenameModalBox() {
		jQuery("#dialog-confirm").dialog({
			resizable: false,
			height: 180,
			modal: true,
			buttons: {
				"<?php echo JText::_('VRSAVE'); ?>": function() {
					jQuery( this ).dialog( "close" );
					
					var newname = jQuery('#dialog-confirm-input').val();
					
					jQuery('#adminForm').append('<input type="hidden" name="newname" value="'+newname+'"/>');
					jQuery('#adminForm').submit();
				},
				"<?php echo JText::_('VRCANCEL'); ?>": function() {
					jQuery( this ).dialog( "close" );
				}
			}
		});
	}
	
</script>
