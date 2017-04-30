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
				} else if (s == 'nok') {
					alert("Leider kann Der Empfänger keinen Credit mehr empfangen!");
					window.location.href = base_url + 'kader/tag';
				}
			}
		})
	});

})(jQuery);