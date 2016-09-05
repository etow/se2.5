/* To avoid CSS expressions while still supporting IE 7 and IE 6, use this script */
/* The script tag referencing this file must be placed before the ending body tag. */

/* Use conditional comments in order to target IE 7 and older:
	<!--[if lt IE 8]><!-->
	<script src="ie7/ie7.js"></script>
	<!--<![endif]-->
*/

(function() {
	function addIcon(el, entity) {
		var html = el.innerHTML;
		el.innerHTML = '<span style="font-family: \'icomoon\'">' + entity + '</span>' + html;
	}
	var icons = {
		'se-icon-move': '&#xe623;',
		'se-icon-newspaper': '&#xe601;',
		'se-icon-pencil': '&#xe600;',
		'se-icon-pencil2': '&#xe602;',
		'se-icon-paint-format': '&#xe603;',
		'se-icon-images': '&#xe604;',
		'se-icon-play': '&#xe605;',
		'se-icon-spades': '&#xe606;',
		'se-icon-bullhorn': '&#xe607;',
		'se-icon-file': '&#xe608;',
		'se-icon-file2': '&#xe609;',
		'se-icon-copy': '&#xe60a;',
		'se-icon-tag': '&#xe60b;',
		'se-icon-cart': '&#xe60c;',
		'se-icon-cart2': '&#xe60d;',
		'se-icon-coin': '&#xe60e;',
		'se-icon-envelope': '&#xe60f;',
		'se-icon-location': '&#xe610;',
		'se-icon-screen': '&#xe611;',
		'se-icon-mobile': '&#xe612;',
		'se-icon-quotes-left': '&#xe613;',
		'se-icon-expand': '&#xe622;',
		'se-icon-equalizer': '&#xe614;',
		'se-icon-cog': '&#xe615;',
		'se-icon-remove': '&#xe616;',
		'se-icon-remove2': '&#xe617;',
		'se-icon-grin': '&#xe618;',
		'se-icon-insert-template': '&#xe619;',
		'se-icon-paragraph-left': '&#xe61a;',
		'se-icon-embed': '&#xe61b;',
		'se-icon-code': '&#xe61c;',
		'se-icon-googleplus': '&#xe61d;',
		'se-icon-facebook': '&#xe61e;',
		'se-icon-instagram': '&#xe61f;',
		'se-icon-twitter': '&#xe620;',
		'se-icon-file-xml': '&#xe621;',
		'0': 0
		},
		els = document.getElementsByTagName('*'),
		i, c, el;
	for (i = 0; ; i += 1) {
		el = els[i];
		if(!el) {
			break;
		}
		c = el.className;
		c = c.match(/se-icon-[^\s'"]+/);
		if (c && icons[c[0]]) {
			addIcon(el, icons[c[0]]);
		}
	}
}());
