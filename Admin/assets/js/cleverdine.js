/*
 * SEARCH BAR - editconfig
 */

function SearchBar(matches) {
	this.setMatches(matches);
}

SearchBar.prototype.setMatches = function(matches) {
	this.matches = matches;
	this.currIndex = 0;
};

SearchBar.prototype.clear = function() {
	this.setMatches(false);
};

SearchBar.prototype.isNull = function() {
	return this.matches === false;
};

SearchBar.prototype.isEmpty = function() {
	return !this.isNull() && this.matches.length == 0;
};

SearchBar.prototype.getElement = function() {
	if( this.matches === false ) {
		return null;
	}
	return this.matches[this.currIndex];
};

SearchBar.prototype.getCurrentIndex = function() {
	return this.currIndex;
};

SearchBar.prototype.size = function() {
	if( this.matches === false ) {
		return 0;
	}
	return this.matches.length;
};

SearchBar.prototype.next = function() {
	if( this.matches === false ) {
		return null;
	}
	this.currIndex++;
	if( this.currIndex >= this.matches.length ) {
		this.currIndex = 0;
	}
	return this.matches[this.currIndex];
};

SearchBar.prototype.previous = function() {
	if( this.matches === false ) {
		return null;
	}
	this.currIndex--;
	if( this.currIndex < 0 ) {
		this.currIndex = this.matches.length-1;
	}
	return this.matches[this.currIndex];
};

/*
 * LEFTBOARD MENU
 */

jQuery(document).ready(function(){

	if( isLeftBoardMenuCompressed() ) {
		jQuery('.vre-leftboard-menu.compressed .parent .title.selected').removeClass('collapsed');
		jQuery('.vre-leftboard-menu.compressed .parent .wrapper.collapsed').removeClass('collapsed');
	}

	jQuery('.vre-leftboard-menu .parent .title').disableSelection();

	jQuery('.vre-leftboard-menu .parent .title').on('click', function(){
		leftBoardMenuItemClicked(this);
	});

	jQuery('.vre-leftboard-menu .parent .title').hover(function(){
		if( isLeftBoardMenuCompressed() && !jQuery(this).hasClass('collapsed') ) {
			leftBoardMenuItemClicked(this);

			jQuery('.vre-leftboard-menu.compressed .parent .title').removeClass('collapsed');
			jQuery(this).addClass('collapsed');
		}
	}, function(){
		
	});
	
	jQuery('.vre-leftboard-menu').hover(function(){
		
	}, function(){
		jQuery('.vre-leftboard-menu.compressed .parent .title').removeClass('collapsed');
		jQuery('.vre-leftboard-menu.compressed .parent .wrapper').removeClass('collapsed');
	});

	jQuery('.vre-leftboard-menu .custom').hover(function(){
		jQuery('.vre-leftboard-menu.compressed .parent .title').removeClass('collapsed');
		jQuery('.vre-leftboard-menu.compressed .parent .wrapper').removeClass('collapsed');
	}, function(){

	});

});

function leftBoardMenuItemClicked(elem) {
	var wrapper = jQuery(elem).next();
	var has = !wrapper.hasClass('collapsed') 

	jQuery('.vre-leftboard-menu .parent .wrapper').removeClass('collapsed');

	jQuery('.vre-angle-dir').removeClass('fa-angle-up');
	jQuery('.vre-angle-dir').addClass('fa-angle-down');
	
	if( has ) {
		wrapper.addClass('collapsed');
		var angle = jQuery(elem).find('.vre-angle-dir');
		angle.addClass('fa-angle-up');
		angle.removeClass('fa-angle-down');
	}
}

function leftBoardMenuToggle() {

	// restore arrows
	jQuery('.vre-angle-dir').removeClass('fa-angle-up');
	jQuery('.vre-angle-dir').addClass('fa-angle-down');

	var status;

	if( isLeftBoardMenuCompressed() ) {
		jQuery('.vre-leftboard-menu').removeClass('compressed');
		jQuery('.vre-task-wrapper').removeClass('extended');
		status = 1;
	} else {
		jQuery('.vre-leftboard-menu').addClass('compressed');
		jQuery('.vre-task-wrapper').addClass('extended');

		jQuery('.vre-leftboard-menu.compressed .parent .title.selected').removeClass('collapsed');
		jQuery('.vre-leftboard-menu.compressed .parent .wrapper.collapsed').removeClass('collapsed');

		status = 2;
	}

	leftBoardMenuRegisterStatus(status);
	jQuery(window).trigger('resize');

}

function leftBoardMenuRegisterStatus(status) {

	jQuery.noConflict();
		
	var jqxhr = jQuery.ajax({
		type: "POST",
		url: "index.php?option=com_cleverdine&task=store_mainmenu_status&tmpl=component",
		data: {status: status}
	}).done(function(resp){
		
	}).fail(function(resp){
		
	});

}

function isLeftBoardMenuCompressed() {
	return jQuery('.vre-leftboard-menu').hasClass('compressed');
}

/*
 * DOCUMENT CONTENT RESIZE
 */

jQuery(document).ready(function(){

	// statement for quick disable
	if( true ) {

		var task = jQuery('.vre-task-wrapper');
		var lfb_menu = jQuery('.vre-leftboard-menu');
		var _margin = 20;

		jQuery(window).resize(function(){
				var p = (lfb_menu.width()+_margin) * 100 / jQuery(document).width();
				task.css('width', (100-Math.ceil(p))+"%");
			}
		);

	}

	jQuery(window).trigger('resize');

 });

/*
 * OVERLAYS
 */

function openLoadingOverlay(lock, message) {

	var _html = '';

	if( message !== undefined ) {
		_html += '<div class="vr-loading-box-message">'+message+'</div>';
	}

	jQuery('#content').append('<div class="vr-loading-overlay'+(lock ? ' lock' : '')+'">'+_html+'<div class="vr-loading-box"></div></div>');
}

function closeLoadingOverlay() {
	jQuery('.vr-loading-overlay').remove();
}

/*
 * SYSTEM
 */

jQuery._parseJSON = jQuery.parseJSON;

jQuery.parseJSON = function( data ) {

	try {

		return jQuery._parseJSON(data);
		
	} catch (err) {
		console.log(err);
		console.log(data);
	}

	return null;

}

String.prototype.hashCode = function(){
	var hash = 0;
	if (this.length == 0) return hash;
	for (i = 0; i < this.length; i++) {
		char = this.charCodeAt(i);
		hash = ((hash<<5)-hash)+char;
		hash = hash & hash; // Convert to 32bit integer
	}
	return hash;
}


function vreOpenPopup(link) {
	jQuery.fancybox({
		beforeLoad: function() {
			this.href = link;
		},
		"helpers": {
			"overlay": {
				"locked": false
			}
		},
		"width": "50%",
		"height": "50%",
		"autoScale": false,
		"transitionIn": "none",
		"transitionOut": "none",
		"padding": 0,
		"type": "iframe" 
	});
}

function vreOpenModalImage(link) {
	jQuery.fancybox({
		"helpers": {
			"overlay": {
				"locked": false
			}
		},
		"href": link,
		"padding": 0
	});
}