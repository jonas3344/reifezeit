;(function($) {
	$('#fahrer1, #fahrer2, #fahrer3, #fahrer4, #fahrer5').on('change', function () {
		$.ajax({
			type: "post",
			url: base_url + 'dopingtest/freeChangeSubmit',
			data: {
					fahrer: this.id,
					fahrer_id: this.value,
					user: $("#user_id").val(),
					etappen_id: $("#etappen_id").val()
			},
			async: false,
			success: function(s) {
				location.reload();
			}
		});
	})
})(jQuery);