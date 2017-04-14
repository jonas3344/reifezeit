;(function($) {
	$('#submit').click(function() {
		doperid = $('#teilnehmer').val();
		etappenid = $('#etappen_id').val();
		$.ajax({
			type: "post",
			url: base_url + 'dopingtest/InsertDoper/',
			data: {	doperid: doperid,
					etappenid: etappenid
			},
			success: function(s) {
					if (s == "ok") {
						alert("Der Doper wurde eingetragen!");
						location.reload();
					} else if (s=="out") {
						alert("Der Doper wurde aus der Rundfahrt ausgeschlossen!");
						location.reload();
					} else {
						alert("Hat nicht funktioniert!");
					}
				}
		})

	});
})(jQuery);