(function($) {

	$('nav a.section-link, nav .active a.page-link').click(function(e) {

		e.preventDefault();
		var link = $(this)
		var href = e.target.href;
		var hash = href.substr(href.indexOf('#'));
		var target = hash.length>1 ? $(hash) : $('main');
		if(!target.length){return}
		var targetTop = target.position().top+'px';
		$('html, body').animate({
			scrollTop: targetTop
		}, 300);
		history.pushState(null, null, hash);

		// if(!link.is('.page-link')){return}
		// var otherLinks = $('.page-link:not([href="'+hash+'"])')
		// $('.section-links').attr('style','')
		// otherLinks.removeClass('active');
		// link.toggleClass('active');
		// if(!link.is('.active')){return}
		// var sub = link.next('.section-links');
		// var inner = sub.find('.links-inner');
		// sub.css({
		// 	height: inner.innerHeight()
		// });

	});

})( jQuery );