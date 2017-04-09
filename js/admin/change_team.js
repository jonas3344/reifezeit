;(function($) {
	$('.form-control').change(function() {
		var $select = $(".form-control");
		$.ajax({
			type: "post",
			url: base_url + 'stammdaten/changeTeam/',
			data: {fahrerid: $select.attr('id'),
				teamid: $select.val()
			},
			success: function(s) {
					if (s == "ok") {
						alert("Das Team wurde gewechselt!");
						location.reload();
					} else {
						alert("Beim Wechsel ist ein Fehler aufgetreten!");
					}
				}

		})
	});
})(jQuery);