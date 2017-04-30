;(function($) {
	$('#move').click(function() {
		etappen_id = $('#etappen_id').val();
		$.ajax({
			type: "post",
			url: base_url + 'kader/insertCreditmove/',
			data: {
					etappen_id: etappen_id
			},
			success: function(s) {
				if (s == 'ok') {
					window.location.href = base_url + 'kader/tag/' + etappen_id;
				} else if (s == 'nok') {
					alert("Du hast Deine Möglichkeiten zum Creditverschieben bereits aufgebraucht!");
					window.location.href = base_url + 'kader/tag' + etappen_id;
				}
			}
		})
	});

})(jQuery);