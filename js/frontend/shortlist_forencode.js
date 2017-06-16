;(function($) {
	var clipboard = new Clipboard('.copy');
	$(".back").on("click", function() {
		window.location.href = base_url + "shortlist";
	});
})(jQuery);