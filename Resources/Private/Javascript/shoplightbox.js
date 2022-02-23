$('.mfp-inline').magnificPopup({
	type: 'inline',
	tClose: 'Schließen (Esc)',
	tLoading: 'Laden...',
	disableOn: 0,
	mainClass: '',
	preloader: 1,
	focus: '',
	closeOnContentClick: 0,
	closeOnBgClick: 1,
	closeBtnInside: 1,
	showCloseBtn: 1,
	enableEscapeKey: 1,
	modal: 0,
	alignTop: 0,
	fixedContentPos: 'auto',
	fixedBgPos: 'auto',
	overflowY: 'auto',
	removalDelay: 0,
	closeMarkup: '<button title="%title%" class="mfp-close">&times;</button>',
	callbacks: {
		open: function() {
			try {
				$("img.lazyload").responsiveimage('unveil');
			} catch (e) {}
		}
	}
});