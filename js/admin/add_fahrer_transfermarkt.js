;(function($) {
	$(".add_fahrer").click(function() {
		var fahrerid = $(this).attr("id");
		var teamid = $(".team_id").attr("id");
		$.ajax({
			type: "post",
			url: base_url + 'administration/addFahrerToTransfermarkt/',
			data: {	teamid: teamid,
					fahrerid: fahrerid
			},
			success: function(s) {
					if (s == "ok") {
						alert("Der Fahrer wurde hinzugefügt!");
						location.reload();
					} else {
						alert(s);
					}
				}

		})
	});
})(jQuery);