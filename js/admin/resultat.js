;(function($) {
	$("#etappen").on("change", function() {
		window.location.href = base_url + "forencode/resultate/" + $(this).val();
	})	
	$(".otl").on("click", function() {
		userid = $(this).attr('id').split("_")[0];
		etappenid = $(this).attr('id').split("_")[1];
		$.ajax({
			type: "post",
			url: base_url + 'forencode/setOtl/',
			data: {user: userid,
					etappe:etappenid
			},
			success: function(s) {
					if (s == "ok") {
						alert("Der Teilnehmer wurde ausgeschlossen!");
						location.reload();
					} else {
						alert("Ber Teilnehmer war bereits ausgeschlossen!");
					}
				}
		})
	})
})(jQuery);