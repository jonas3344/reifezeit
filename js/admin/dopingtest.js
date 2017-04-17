;(function($) {
	$('#etappen').on('change', function() {
		window.location.href = base_url + 'dopingtest/index/' + this.id;
	})		
})(jQuery);