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

<form name="adminForm" action="index.php" method="post" enctype="multipart/form-data" id="adminForm">
	
	<div class="span12">
		<?php echo $vik->openFieldset($this->version->shortTitle, 'form-horizontal'); ?>

			<div class="control"><strong><?php echo $this->version->title; ?></strong></div>

			<div class="control" style="margin-top: 10px;">
				<button type="button" class="btn btn-primary" onclick="downloadSoftware(this);">
					<?php echo JText::_($this->version->compare == 1 ? 'VRDOWNLOADUPDATEBTN1' : 'VRDOWNLOADUPDATEBTN0'); ?>
				</button>
			</div>

			<div class="control vr-box-error" id="update-error" style="display: none;margin-top: 10px;"></div>

			<?php if( isset($this->version->changelog) && count($this->version->changelog) ) { ?>

				<div class="control vr-update-changelog" style="margin-top: 10px;">

					<?php echo $this->digChangelog($this->version->changelog); ?>

				</div>

			<?php } ?>

		<?php echo $vik->closeFieldset(); ?>
	</div>

	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="option" value="com_cleverdine"/>
</form>

<script type="text/javascript">

	var isRunning = false;

	function downloadSoftware(btn) {

		if( isRunning ) {
			return;
		}

		switchRunStatus(btn);
		setError(null);

		var jqxhr = jQuery.ajax({
			url: "index.php?option=com_cleverdine&task=launch_update&tmpl=component",
			type: "POST",
			data: {}
		}).done(function(resp){

			var obj = jQuery.parseJSON(resp);
			
			if( obj === null ) {

				// connection failed. Something gone wrong while decoding JSON
				alert('<?php echo addslashes(JText::_('VRSYSTEMCONNECTIONERR')); ?>');

			} else if( obj.status ) {

				document.location.href = 'index.php?option=com_cleverdine';
				return;

			} else {

				console.log("### ERROR ###");
				console.log(obj);

				if( obj.hasOwnProperty('error') ) {
					setError(obj.error);
				} else {
					setError('Your website does not own a valid support license!<br />Please visit <a href="https://woodboxmedia.co.uk" target="_blank">woodboxmedia.co.uk</a> to purchase a license or to receive assistance.');
				}

			}

			switchRunStatus(btn);

		}).fail(function(resp){
			console.log('### FAILURE ###');
			console.log(resp);
			alert('<?php echo addslashes(JText::_('VRSYSTEMCONNECTIONERR')); ?>');

			switchRunStatus(btn);
		}); 
	}

	function switchRunStatus(btn) {
		isRunning = !isRunning;

		jQuery(btn).prop('disabled', isRunning);

		if( isRunning ) {
			// start loading
			openLoadingOverlay(true, 'It may take a few minutes to completion.<br />Please wait without leaving the page or closing the browser.');
		} else {
			// stop loading
			closeLoadingOverlay();
		}
	}

	function setError(err) {

		if( err !== null && err !== undefined && err.length ) {
			jQuery('#update-error').show();
		} else {
			jQuery('#update-error').hide();
		}

		jQuery('#update-error').html(err);

	}

</script>