;(function($) {
	$('.next, .prev').on('click', function() {
		$.ajax({
			type: "post",
			url: base_url + 'rundfahrt/getEtappenId',
			data:	{ 
					etappen_nr: this.id
			},
			success: function(s) {
				window.location.href = base_url + 'rundfahrt/resultate/' + s;
			}
		})

	})		
})(jQuery);