;(function($) {
	$('#submit').click(function() {
		empfaenger = $('select[name="empfaenger"] option:selected').attr("id");
		$.ajax({
			type: "post",
			url: base_url + 'kader/insertCreditabgabe/',
			data: {
					empfaenger: empfaenger,
					etappen_id: $("#etappenid").val()
			},
			success: function(s) {
				if (s == 'ok') {
					window.location.href = base_url + 'kader/tag/' + $("#etappenid").val();
				} else if (s == 'nok') {
					alert("Leider kann Der Empfänger keinen Credit mehr empfangen!");
					window.location.href = base_url + 'kader/tag/' + $("#etappenid").val();
				}
			}
		})
	});

})(jQuery);