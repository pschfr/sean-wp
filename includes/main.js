// Checks to see if fonts are in sessionStorage, loads faster if cached
(function() {
	if (sessionStorage.fonts) {
		document.documentElement.classList.add('wf-active');
		console.log("fonts loaded from cache");
	} else { console.log("no fonts in cache"); }
})();

// Loads webfonts from Google if not cached
WebFont.load({
	google: { families: [ 'Source+Sans+Pro:400,400italic,700,700italic', 'Playfair+Display+SC:400,700,900' ] },
	active: function (){
		sessionStorage.fonts = true; // caches the fonts in sessionStorage
		console.log('webfonts activated');
	}
});

// Magic that disables pointer events on scroll, really improves performance
var body = document.body, timer;
window.addEventListener('scroll', function() {
	clearTimeout(timer);
	if(!body.classList.contains('disable-hover')) {
		body.classList.add('disable-hover');
	}
	timer = setTimeout(function() {
		body.classList.remove('disable-hover');
	}, 100);
}, false);

$(function() {
	// Pulls rendering information from the HTML, logs it in console
	$('body').contents().filter(function(){
		return this.nodeType == 8;
	}).each(function(i, e) {
		console.log('%c%s', 'font-weight:bold;', e.nodeValue);
	});

	if($('section.instagram_shots').length) {
		console.log('instagram shots loaded successfully');
		// Initialize commonly used elements for easier DOM manipulation
		var section  = $('section.instagram_shots');
		var lightbox = $(section).find('div.lightbox');
		// Builds lightbox on click, locks page scrolling
		$(this).find('div a.lightbox').on('click', function(e) {
			e.preventDefault();
			$('body').toggleClass('no-scrolling');
			$(lightbox).find('img').attr('src', $(this).attr('href'));
			$(lightbox).find('div.caption').html($(this).find('img').attr('title'));
			$(lightbox).stop().animate({ 'opacity': 'toggle' }, 250);
		});
		// Destroys lightbox when clicked on border
		$(lightbox).on('click', function() {
			$('body').toggleClass('no-scrolling');
			// Prevents the lag when switching the image quickly
			$(this).find('img').attr('src',  '');
			$(this).find('div.caption').html('');
			$(this).stop().animate({ 'opacity': 'toggle' }, 250);
			// Prevents lightbox from closing if you click on the image or caption
			$(this).find('div.content').on('click', function() { return false; });
		});
		/*
			TODO: Build keyboard nav, add caption, close button, GIF loader?
			Have it request more (~100) but only show 20, so we can request more with a button and AJAX
			Filter by Instagram username and specific hashtag?
		*/
	}
});
