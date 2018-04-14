$(document).ready(function() {
	(function(i, s, o, g, r, a, m) {
		i['GoogleAnalyticsObject'] = r;
		i[r] = i[r] || function() {
			(i[r].q = i[r].q || []).push(arguments)
		}, i[r].l = 1 * new Date();
		a = s.createElement(o),
		m = s.getElementsByTagName(o)[0];
		a.async = 1;
		a.src = g;
		m.parentNode.insertBefore(a, m)
	})(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

	ga('create', 'UA-102246656-1', 'auto');
	ga('send', 'pageview');

	$(".scroll").click(function() {
		theOffset = $($(this).attr('href')).offset();
		$('body,html').animate({
			scrollTop: theOffset.top
		}); 
		$("nav a").each(function() {
			$(this).removeClass('active');
		});
		$(this).addClass('active');
	});	
});
