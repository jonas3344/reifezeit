;(function($) {
	var clipboard = new Clipboard('.btn');
	clipboard.on('success', function(e) {
		
	});
	$("#etappen").on("change", function() {
		window.location.href = base_url + "forencode/index/" + $(this).val();
	})	
	
})(jQuery);