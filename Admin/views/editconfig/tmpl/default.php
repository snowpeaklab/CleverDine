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

JHTML::_('behavior.calendar');
JHtml::_('behavior.modal');

$params = $this->params;

// vik application

$vik = $this->vikApplication;

// navigation

$_selected_tab_view = JFactory::getSession()->get('vretabactive', 1, 'vreconfig');
if( ($_selected_tab_view == 2 && !$params['enablerestaurant']) || ($_selected_tab_view == 3 && !$params['enabletakeaway']) ) {
	$_selected_tab_view = 1;
}

// media manager

$mediaManager = $this->mediaManager;

?>

<div class="vr-config-head-wrapper">

	<!-- NAVIGATION -->

	<div id="navigation">
		<ul>
			<li id="vretabli1" class="vretabli<?php echo (($_selected_tab_view == 1) ? ' vreconfigtabactive' : ''); ?>" style="">
				<a href="javascript: changeTabView(1);">
					<i class="fa fa-cogs"></i>
					<?php echo JText::_('VRECONFIGTABNAME1'); ?>
				</a>
			</li>
			<li id="vretabli2" class="vretabli<?php echo (($_selected_tab_view == 2) ? ' vreconfigtabactive' : ''); ?>" style="<?php echo ($params['enablerestaurant'] ? '' : 'display:none;'); ?>">
				<a href="javascript: changeTabView(2);">
					<?php echo JText::_('VRECONFIGTABNAME2'); ?>
				</a>
			</li>
			<li id="vretabli3" class="vretabli<?php echo (($_selected_tab_view == 3) ? ' vreconfigtabactive' : ''); ?>" style="<?php echo ($params['enabletakeaway'] ? '' : 'display:none;'); ?>">
				<a href="javascript: changeTabView(3);">
					<?php echo JText::_('VRECONFIGTABNAME3'); ?>
				</a>
			</li>
			<li id="vretabli4" class="vretabli<?php echo (($_selected_tab_view == 4) ? ' vreconfigtabactive' : ''); ?>" style="">
				<a href="javascript: changeTabView(4);">
					<?php echo JText::_('VRECONFIGTABNAME4'); ?>
				</a>
			</li>
			<li id="vretabli5" class="vretabli<?php echo (($_selected_tab_view == 5) ? ' vreconfigtabactive' : ''); ?>" style="">
				<a href="javascript: changeTabView(5);">
					<?php echo JText::_('VRECONFIGTABNAME5'); ?>
				</a>
			</li>
		</ul>
	</div>

	<!-- SEARCH TOOLBAR -->

	<div class="vre-config-toolbar">

		<!--<div class="btn-toolbar vr-btn-toolbar" id="filter-bar" style="margin-bottom: 10px;">-->

			<div class="btn-group pull-right input-append" style="margin-left: 5px;">
				<input type="text" id="vre-search-param" value="" placeholder="Settings Research" size="24"/>

				<button type="button" class="btn" onClick="hideSearchBar();">
					<i class="icon-remove"></i>
				</button>
			</div>	

		<!--</div>-->

	</div>

</div>

<!-- SEARCH FLOATING BAR -->

<div class="vre-config-searchbar" style="display: none">
	<div class="vre-config-searchbar-results">
		<span class="vre-config-searchbar-stat badge"></span>
		<span class="vre-config-searchbar-control">
			<a href="javascript: void(0);" class="" onClick="goToPrevMatch();">
				<i class="fa fa-chevron-left big"></i>
			</a>
		</span>
		<span class="vre-config-searchbar-control">
			<a href="javascript: void(0);" class="" onClick="goToNextMatch();">
				<i class="fa fa-chevron-right big"></i>
			</a>
		</span>
	</div>
	<div class="vre-config-searchbar-gotop">
		<a href="javascript: void(0);" onClick="animateToPageTop();"><?php echo JText::_('VRGOTOP'); ?></a>
	</div>
</div>

<!-- SETTINGS FORM -->

<form name="adminForm" id="adminForm" action="index.php" method="post" enctype="multipart/form-data">
	
	<!-- GLOBAL -->

	<div id="vretabview1" class="vretabview" style="<?php echo (($_selected_tab_view != 1) ? 'display: none;' : ''); ?>">
		
		<?php echo $this->loadTemplate('global'); ?>
		
	</div>

	<!-- RESTAURANT -->
	
	<div id="vretabview2" class="vretabview" style="<?php echo (($_selected_tab_view != 2) ? 'display: none;' : ''); ?>">

		<?php echo $this->loadTemplate('restaurant'); ?>
		
	</div>

	<!-- TAKEAWAY -->
	
	<div id="vretabview3" class="vretabview" style="<?php echo (($_selected_tab_view != 3) ? 'display: none;' : ''); ?>">
		
		<?php echo $this->loadTemplate('takeaway'); ?>

	</div>

	<!-- SMS APIS -->
	
	<div id="vretabview4" class="vretabview" style="<?php echo (($_selected_tab_view != 4) ? 'display: none;' : ''); ?>">
		
		<?php echo $this->loadTemplate('sms'); ?>
		
	</div>

	<!-- FRAMEWORK APIS -->
	
	<div id="vretabview5" class="vretabview" style="<?php echo (($_selected_tab_view != 5) ? 'display: none;' : ''); ?>">
		
		<?php echo $this->loadTemplate('apps'); ?>
		
	</div>
	
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="option" value="com_cleverdine"/>
</form>

<!-- MEDIA MANAGER -->
<?php echo $mediaManager->buildModal(JText::_('VRMEDIAFIELDSET4')); ?>

<?php echo $mediaManager->useScript(JText::_("VRMANAGEMEDIA10")); ?>
<!-- END MEDIA MANAGER -->

<!-- JQUERY DIALOGS -->

<div id="dialog-confirm" title="<?php echo JText::_('VRRENEWSESSION');?>" style="display: none;">
	<p>
		<span class="ui-icon ui-icon-locked" style="float: left; margin: 0 7px 20px 0;"></span>
		<span><?php echo JText::_('VRRENEWSESSIONCONFMSG'); ?></span>
	</p>
</div>

<div id="dialog-firstconfig" title="<?php echo JText::_('VRFIRSTCONFIGTITLE');?>" style="display: none;">
	<p>
		<span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>
		<span><?php echo JText::_('VRFIRSTCONFIGMESSAGE'); ?></span>
	</p>
</div>

<script>
	
	jQuery(document).ready(function(){

		jQuery('.vik-dropdown.short').select2({
			minimumResultsForSearch: -1,
			allowClear: false,
			width: 100
		});
		jQuery('.vik-dropdown.small-medium').select2({
			minimumResultsForSearch: -1,
			allowClear: false,
			width: 150
		});
		jQuery('.vik-dropdown.medium').select2({
			minimumResultsForSearch: -1,
			allowClear: false,
			width: 200
		});
		jQuery('.vik-dropdown.medium-large').select2({
			minimumResultsForSearch: -1,
			allowClear: false,
			width: 250
		});
		jQuery('.vik-dropdown.large').select2({
			minimumResultsForSearch: -1,
			allowClear: false,
			// do not use 300px because the input may exceed in width (13" screen)
			//width: 300
			width: 250
		});
		
	});

	// lock / unlock an input starting from the specified link

	function lockUnlockInput(link) {

		var input = jQuery(link).prev();

		if( input.prop('readonly') ) {
			input.prop('readonly', false);

			jQuery(link).find('i').removeClass('fa-lock');
			jQuery(link).find('i').addClass('fa-unlock-alt');
		} else {
			input.prop('readonly', true);

			jQuery(link).find('i').removeClass('fa-unlock-alt');
			jQuery(link).find('i').addClass('fa-lock');
		}

	}

	// open renew session dialog
	
	function openRenewSessionDialog() {
	
		jQuery( "#dialog-confirm" ).dialog({
			resizable: false,
			height:140,
			modal: true,
			buttons: {
				"<?php echo JText::_('VRRENEWSESSIONCONFOK'); ?>": function() {
					jQuery( this ).dialog( "close" );
					Joomla.submitform('truncateSession', document.adminForm);
				},
				"<?php echo JText::_('VRRENEWSESSIONCONFCANC'); ?>": function() {
					jQuery( this ).dialog( "close" );
				}
			}
		});
		
	}

	// open first configuration dialog

	function openFirstStoreDialog() {
		jQuery( "#dialog-firstconfig" ).dialog({
			resizable: false,
			width: 380,
			height: 220,
			modal: true,
			buttons: {
				"<?php echo JText::_('VRGOTIT'); ?>": function() {
					jQuery( this ).dialog( "close" );
					Joomla.submitform('saveConfiguration', document.adminForm);
				}
			}
		});
	}

	var FIRST_CONFIG = <?php echo ($params['firstconfig'] ? 1 : 0); ?>;

	// override Joomla submit button

	Joomla.submitbutton = function(task) {
		if( task == 'truncateSession' ) {
			openRenewSessionDialog();
		} else if( task == 'saveConfiguration' && FIRST_CONFIG ) {
			openFirstStoreDialog();
		} else {
			Joomla.submitform(task, document.adminForm);
		}
	}
	
	// TAB LISTENERS
	
	var last_tab_view = <?php echo $_selected_tab_view; ?>;
	
	// switch configuration tab

	function changeTabView(tab_pressed) {
		if( tab_pressed != last_tab_view ) {
			jQuery('.vretabli').removeClass('vreconfigtabactive');
			jQuery('#vretabli'+tab_pressed).addClass('vreconfigtabactive');
			
			jQuery('.vretabview').hide();
			jQuery('#vretabview'+tab_pressed).show();
			
			storeTabSelected(tab_pressed);
			
			last_tab_view = tab_pressed;
		}
	}
	
	// store active tab in PHP session

	function storeTabSelected(tab) {
		jQuery.noConflict();
			
		var jqxhr = jQuery.ajax({
			type: "POST",
			url: "index.php",
			data: { option: "com_cleverdine", task: "store_tab_selected", tab: tab, tmpl: "component" }
		}).done(function(resp){
			
		}).fail(function(resp){
			
		});
	}
	
	// open jmodal handler
	
	function vrOpenJModal(id, url, jqmodal) {
		<?php echo $vik->bootOpenModalJS(); ?>
	}
	
	// append contents to specified modal
	
	function appendModalContent(id, href, size) {
		jQuery('#'+id).html('<div class="modal-body" style="max-height:'+(size.height-20)+'px;">'+
		'<iframe class="iframe" src="'+href+'" width="'+size.width+'" height="'+size.height+'" style="max-height:'+(size.height-100)+'px;"></iframe>'+
		'</div>');
	}

	/**********************
	 ***** SEARCH BAR *****
	 **********************/

	var searchBar = new SearchBar(false);

	// init search bar events

	jQuery(document).ready(function(){

		jQuery('#vre-search-param').on('keyup', function(event){
			
			var value = jQuery('#vre-search-param').val().toLowerCase();
			searchBar.setMatches(getParamsFromSearch(value));

			if (searchBar.isNull()) {
				hideSearchBar();
			} else {
				displaySearchBar();
				if (searchBar.size() > 0 && event.keyCode == 13) {
					goToCurrentMatch();
					jQuery('#vre-search-param').blur();
				}
			}
		});
		
		jQuery(window).on('scroll', function(){
			windowScrollControl(true);
		});
		
		jQuery(document).bind('keydown', function (event){
			if (searchBar.isNull() || jQuery('#vre-search-param').is(':focus')) {
				return;
			}
			
			switch (event.keyCode) {
				case 37: goToPrevMatch(); break; // left arrow > prev match
				case 39: goToNextMatch(); break; // right arrow > next match
				case 13: goToNextMatch(); break; // enter > next match
				case 27: hideSearchBar(); break; // esc > hide search bar
			}
		});

	});

	// get matches from value
	
	function getParamsFromSearch(value) {
		if (value.length == 0) {
			return false;
		}
		
		var matches = new Array();
		
		jQuery('.adminparamcol').each(function(){
			
			if(jQuery(this).text().toLowerCase().indexOf(value) != -1) {
				var style = jQuery(this).parent().attr('style');
				
				if (typeof style === 'undefined' || style.length === 0) {
					matches.push(jQuery(this));
				}
			}

		});
		
		return matches;
	}

	// display search bar on submit
	
	function displaySearchBar() {
		if (searchBar.size() == 0) {
			jQuery('.vre-config-searchbar-stat').text('<?php echo addslashes(JText::_('VRNOMATCHES')); ?>');
			jQuery('.vre-config-searchbar-control').hide();
		} else {
			jQuery('.vre-config-searchbar-stat').text('1/'+searchBar.size());
			jQuery('.vre-config-searchbar-control').show();
		}
		
		windowScrollControl(false);
		
		jQuery('.vre-config-searchbar').show();
	}

	// hide search bar
	
	function hideSearchBar() {
		jQuery('#vre-search-param').val('');
		jQuery('.vre-config-searchbar').hide();
		jQuery('.adminparamcol b').removeClass('badge vre-orange-badge');
		searchBar.clear();
	}

	// handle window scroll to display/hide GO TOP button
	
	function windowScrollControl(effect) {
		if (jQuery(window).scrollTop() <= 0) {
			
			if(effect) {
				jQuery('.vre-config-searchbar-gotop').fadeOut();
			} else {
				jQuery('.vre-config-searchbar-gotop').hide();
			}

		} else {

			if (effect) {
				jQuery('.vre-config-searchbar-gotop').fadeIn();
			} else {
				jQuery('.vre-config-searchbar-gotop').show();
			}

		}
	}

	// go to first match
	
	function goToCurrentMatch() {
		if (searchBar.size() == 0) {
			return;
		}
		
		var elem = searchBar.getElement();
		highlightMatch(elem);
		checkMatchParent(elem);
		animateToScrollTop( elem.offset().top-200 );
	}

	// go to previous match (to last match if cannot go back)
	
	function goToPrevMatch() {
		if (searchBar.size() == 0) {
			return;
		}
		
		var elem = searchBar.previous();
		highlightMatch(elem);
		checkMatchParent(elem);
		animateToScrollTop( elem.offset().top-200 );
		jQuery('.vre-config-searchbar-stat').text((searchBar.getCurrentIndex()+1)+'/'+searchBar.size());
	}
	
	// go to next match (to first match if cannot go forward)

	function goToNextMatch() {
		if (searchBar.size() == 0) {
			return;
		}
		
		var elem = searchBar.next();
		highlightMatch(elem);
		checkMatchParent(elem);
		animateToScrollTop( elem.offset().top-200 );
		jQuery('.vre-config-searchbar-stat').text((searchBar.getCurrentIndex()+1)+'/'+searchBar.size());
	}

	// animate scroll to find match
	
	function animateToScrollTop(px) {
		jQuery("html, body").stop(true, true).animate({ scrollTop: px });
	}

	// animate scroll to go back to the search bar
	
	function animateToPageTop() {
		jQuery("html, body").stop(true, true).animate({ scrollTop: 0 }).promise().done(function(){
			jQuery('#vre-search-param').focus();
		});
	}

	// highlight current match
	
	function highlightMatch(match) {
		jQuery('.adminparamcol b').removeClass('badge vre-orange-badge');
		match.children().first().addClass('badge vre-orange-badge');
	}

	// if the current match is not visible, find the section parent and show it
	
	function checkMatchParent(match) {
		var parent = match.parent();
		while (parent.length > 0 && !parent.hasClass('vretabview')) {
			parent = parent.parent();
		}
		
		if (parent.length > 0 && !parent.is(':visible')) {
			changeTabView(parent.attr('id').split('vretabview')[1]);
		}
	}
	
</script>
