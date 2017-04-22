;(function($) {
	$('a[rel=popover]').popover({
	  html: true,
	  trigger: 'hover',
	  placement: 'bottom',
	  container: 'body',
	  content: function(){return '<img src="'+$(this).data('img') + '" width="400px" />';}
	});
})(jQuery);