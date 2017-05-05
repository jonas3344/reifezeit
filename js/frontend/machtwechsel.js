;(function($) {
	$('#submit').click(function() {
		etappen_id = $('#etappen_id').val();
		user_id = $('select[name="empfaenger"] option:selected').attr("id");
		$.ajax({
			type: "post",
			url: base_url + 'kader/insertMachtwechsel/kapitaen',
			data: {
					etappen_id: etappen_id,
					empfaenger: user_id
			},
			success: function(s) {
				if (s == 'ok') {
					alert('Der Machtwechsel wurde eingetragen!');
					window.location.href = base_url + 'kader/tag/' + etappen_id;
				} else if (s == 'nok') {
					alert("Du kannst keinen weiteren Machtwechsel vornehmen!");
					window.location.href = base_url + 'kader/tag/' + etappen_id;
				}
			}
		})
	});

})(jQuery);