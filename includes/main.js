// Checks to see if fonts are in sessionStorage, loads faster if cached
(function() {
	if (sessionStorage.fonts) {
		document.documentElement.classList.add('wf-active');
		console.log("fonts loaded from cache");
	} else { console.log("no fonts in cache"); }
})();

// Loads webfonts from Google if not cached
WebFont.load({
	google: { families: [ 'Source+Sans+Pro:400,400italic,700,700italic', 'Tangerine:400,700' ] },
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
