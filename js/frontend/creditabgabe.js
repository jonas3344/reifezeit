;(function($) {
	$('#submit').click(function() {
		empfaenger = $('select[name="empfaenger"] option:selected').attr("id");
		$.ajax({
			type: "post",
			url: base_url + 'kader/insertCreditabgabe/',
			data: {
					empfaenger: empfaenger
			},
			success: function(s) {
				if (s == 'ok') {
					window.location.href = base_url + 'kader/tag';
				}
			}
		})
	});

})(jQuery);