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
<div class="control">
	<div class="vr-media-droptarget"><?php echo JText::_('VRMEDIADRAGDROP'); ?></div>
</div>

<div class="control" id="vr-uploads-cont" style="margin-top: 20px;"></div>

<script type="text/javascript">

	// drag&drop actions on target div
	jQuery('.vr-media-droptarget').on('dragenter', function(e) {
		e.stopPropagation();
		e.preventDefault();
		
		jQuery(this).addClass('drag-enter');
	});
	jQuery('.vr-media-droptarget').on('dragleave', function(e) {
		e.stopPropagation();
		e.preventDefault();
		
		jQuery(this).removeClass('drag-enter');
	});
	jQuery('.vr-media-droptarget').on('dragover', function(e) {
		e.stopPropagation();
		e.preventDefault();
	});
	jQuery('.vr-media-droptarget').on('drop', function(e) {
		e.preventDefault();
		
		jQuery(this).removeClass('drag-enter');
		
		var files = e.originalEvent.dataTransfer.files;
		
		execUploads(files);
		
	});
	
	// avoid drag&drop on browser page
	jQuery(document).on('dragenter', function(e) {
		e.stopPropagation();
		e.preventDefault();
	});
	jQuery(document).on('dragover', function(e) { 
	  e.stopPropagation();
	  e.preventDefault();
	});
	jQuery(document).on('drop', function (e) {
		e.stopPropagation();
		e.preventDefault();
	});
	
	// upload

	var POOL_SIZE = 0;
	var POOL_COUNT = 0;
	var TIMEOUT_HANDLER = null;
	
	function execUploads(files) {
		jQuery('#vr-uploads').show();
		var up_cont = jQuery('#vr-uploads-cont');
		
		for( var i = 0; i < files.length; i++ ) {
			if( isAnImage(files[i].name) ) {
				var status = new createStatusBar();
				status.setFileNameSize(files[i].name, files[i].size);
				status.setProgress(0);
				up_cont.append(status.getHtml());
				
				fileUploadThread(status, files[i]);

				POOL_SIZE++;

				stopTimer();
			}
		}
	}
	
	var fileCount = 0;
	function createStatusBar() {
		fileCount++;
		this.statusbar = jQuery("<div class='vr-progressbar-status'></div>");
		this.filename = jQuery("<div class='vr-progressbar-filename'></div>").appendTo(this.statusbar);
		this.size = jQuery("<div class='vr-progressbar-filesize'></div>").appendTo(this.statusbar);
		this.progressBar = jQuery("<div class='vr-progressbar'><div></div></div>").appendTo(this.statusbar);
		this.abort = jQuery("<div class='vr-progressbar-abort'>Abort</div>").appendTo(this.statusbar);
		this.statusinfo = jQuery("<div class='vr-progressbar-info' style='display:none;'><?php echo addslashes(JText::_('VRMANAGEMEDIA9')); ?></div>").appendTo(this.statusbar);
		this.completed = false;
	 
		this.setFileNameSize = function(name, size) {
			var sizeStr = "";
			if(size > 1024*1024) {
				var sizeMB = size/(1024*1024);
				sizeStr = sizeMB.toFixed(2)+" MB";
			} else if(size > 1024) {
				var sizeKB = size/1024;
				sizeStr = sizeKB.toFixed(2)+" kB";
			} else {
				sizeStr = size.toFixed(2)+" B";
			}
	 
			this.filename.html(name);
			this.size.html(sizeStr);
		}
		
		this.setProgress = function(progress) {       
			var progressBarWidth = progress*this.progressBar.width()/100;  
			this.progressBar.find('div').css('width', progressBarWidth+'px').html(progress + "% ");
			if(parseInt(progress) >= 100) {
				if( !this.completed ) {
					this.abort.hide();
					this.statusinfo.show();
				}
			}
		}
		
		this.complete = function() {
			this.completed = true;
			this.abort.hide();
			this.statusinfo.hide();
			this.setProgress(100);
			this.progressBar.find('div').addClass('completed');
		}
		
		this.setAbort = function(jqxhr) {
			var bar = this.progressBar;
			this.abort.click(function() {
				jqxhr.abort();
				this.hide();
				bar.find('div').addClass('aborted');
			});
		}
		
		this.getHtml = function() {
			return this.statusbar;
		}
	}
	
	function fileUploadThread(status, file) {
		jQuery.noConflict();
		
		var formData = new FormData();
		formData.append('image', file);
		
		var jqxhr = jQuery.ajax({
			xhr: function() {
				var xhrobj = jQuery.ajaxSettings.xhr();
				if( xhrobj.upload ) {
					xhrobj.upload.addEventListener('progress', function(event) {
						var percent = 0;
						var position = event.loaded || event.position;
						var total = event.total;
						if( event.lengthComputable ) {
							percent = Math.ceil(position / total * 100);
						}
						//Set progress
						status.setProgress(percent);
					}, false);
				}
				return xhrobj;
			},
			url: 'index.php?option=com_cleverdine&task=uploadimageajax&tmpl=component',
			type: "POST",
			contentType:false,
			processData: false,
			cache: false,
			data: formData,
			success: function(resp){
				var obj = jQuery.parseJSON(resp);
				
				status.complete();
				
				if( obj[0] ) {
					status.filename.html(obj[1]);

					if( typeof window.parent.UPLOADED_MEDIAS !== 'undefined' ) {
						window.parent.UPLOADED_MEDIAS.push(obj[1]);
					}

					POOL_COUNT++;

					if( POOL_COUNT >= POOL_SIZE ) {
						startTimer();
					}
				} else {
					status.progressBar.find('div').addClass('aborted');
				}
			}
		}); 
	 
		status.setAbort(jqxhr);
	}
	
	function isAnImage(name) {
		return name.toLowerCase().match(/\.(jpg|jpeg|png|gif)$/);
	}

	function startTimer() {
		if( TIMEOUT_HANDLER !== null ) {
			stopTimer();
		}

	 	TIMEOUT_HANDLER = setTimeout(function(){
			window.parent.vreCloseJModal();
		}, 500);
	}

	function stopTimer() {
		if( TIMEOUT_HANDLER !== null ) {
			clearTimeout(TIMEOUT_HANDLER);
			TIMEOUT_HANDLER = null;
		}
	}

</script>