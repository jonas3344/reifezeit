;(function($) {
	$(".add").click(function() {
		var $button = $(this);
		$.ajax({
			type: "post",
			url: base_url + 'administration/addTeamToTransfermarkt/',
			data: {teamid: $button.attr('id')},
			success: function(s) {
					if (s == "ok") {
						alert("Das Tea wurde hinzugefügt!");
						window.location.href = base_url + 'administration/transfermarkt/';
					} else {
						alert("Es ist ein Fehler aufgetreten!");
					}
				}

		})
	});
})(jQuery);