;(function($) {
	$(".fahrercredit").editable();
	$(".fahrerstartnummer").editable();
	
	$("#openTransfermarkt").click(function() {
		$.ajax({
			type: "post",
			url: base_url + 'administration/openTransfermarkt/',
			success: function(s) {
					if (s == "ok") {
						location.reload();
					} else {
						alert("Es ist ein Fehler aufgetreten!");
					}
				}

		})
	});
	
	$(".remove").click(function() {
		var fahrerid = $(this).attr("id");
		$.ajax({
			type: "post",
			url: base_url + 'administration/removeFahrerFromTransfermarkt/',
			data: {fahrerid: fahrerid},
			success: function(s) {
					if (s == "ok") {
						location.reload();
					} else {
						alert("Es ist ein Fehler aufgetreten!");
					}
				}

		})
	});
})(jQuery);