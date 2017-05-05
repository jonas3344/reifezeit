;(function($) {
	$('#submit').click(function() {
		etappen_id = $('#etappen_id').val();
		user_id = $('select[name="empfaenger"] option:selected').attr("id");
		$.ajax({
			type: "post",
			url: base_url + 'kader/insertMachtwechsel/sprinter',
			data: {
					etappen_id: etappen_id,
					empfaenger: user_id
			},
			success: function(s) {
				if (s == 'ok') {
					alert('Dein Anziehen wurde eingetragen!');
					window.location.href = base_url + 'kader/tag/' + etappen_id;
				} else if (s == 'nok') {
					alert("Du kannst keinen Teamkollegen mehr anziehen!");
					window.location.href = base_url + 'kader/tag/' + etappen_id;
				}
			}
		})
	});

})(jQuery);