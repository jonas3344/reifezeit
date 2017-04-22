;(function($) {
	$('#etappen').on('change', function() {
		window.location.href = base_url + 'rundfahrt/resultate/' + this.value;
	})		
})(jQuery);