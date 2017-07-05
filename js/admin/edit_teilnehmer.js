;(function($) {
	$("#team").on("change", function() {
		user_id = $("#user_id").val();
		$.ajax({
			type: "post",
			url: base_url + 'administration/changeTeamOfTeilnehmer/',
			data: {user_id: user_id,
					teamid: $(this).val()
			},
			success: function(s) {
					alert("Das Team wurde gewechselt!");
				}
		});
	});
	$("#rolle").on("change", function() {
		user_id = $("#user_id").val();
		alert(user_id);
		$.ajax({
			type: "post",
			url: base_url + 'administration/changeRolleOfTeilnehmer/',
			data: {user_id: user_id,
					rolle: $(this).val()
			},
			success: function(s) {
					alert("Die Rolle wurde gewechselt!");
				}
		});
	});
})(jQuery);