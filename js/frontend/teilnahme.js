;(function($) {
	$('#teilnehmen').click(function() {
		$.ajax({
			type: "post",
			url: base_url + 'teilnahme/insertAnmeldung/',
			data: {
					rolle: $('#rolle').val(),
					team: $('#team').val()
			},
			success: function(s) {
				if (s == 'ok') {
					location.reload();
				}
			}
		})
	});

})(jQuery);