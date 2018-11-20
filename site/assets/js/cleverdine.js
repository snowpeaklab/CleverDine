function vreOpenPopup(link) {

	var _w = 50;
	if (jQuery(window).width() <= 800) {
		_w = 90;
	}

	jQuery.fancybox({
		beforeLoad: function() {
			this.href = link;
		},
		"helpers": {
			"overlay": {
				"locked": false
			}
		},
		"width": _w+"%",
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

/*
 * DEBOUNCE
 */

function __debounce(func, wait, immediate) {
	var timeout;
	return function() {
		var context = this, args = arguments;
		var later = function() {
			timeout = null;
			if (!immediate) func.apply(context, args);
		};
		var callNow = immediate && !timeout;
		clearTimeout(timeout);
		timeout = setTimeout(later, wait);
		if (callNow) func.apply(context, args);
	};
};

/*
 * SYSTEM
 */

jQuery._parseJSON = jQuery.parseJSON;

jQuery.parseJSON = function( data ) {

	try {

		return jQuery._parseJSON(data);
		
	} catch( err ) {
		console.log(err);
		console.log(data);
	}

	return null;

}

/*
 * OVERLAYS
 */

function openLoadingOverlay(lock, message) {

	var _html = '';

	if( message !== undefined ) {
		_html += '<div class="vr-loading-box-message">'+message+'</div>';
	}

	jQuery('body').append('<div class="vr-loading-overlay'+(lock ? ' lock' : '')+'">'+_html+'<div class="vr-loading-box"></div></div>');
}

function closeLoadingOverlay() {
	jQuery('.vr-loading-overlay').remove();
}