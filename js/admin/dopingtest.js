;(function($) {
	$('#etappen').on('change', function() {
		window.location.href = base_url + 'dopingtest/index/' + this.value;
	});
	$('.setKaderLastStage').on('click', function() {
		post_data = this.id.split("_");
		$.ajax({
			type: "post",
			url: base_url + 'dopingtest/setKaderLastStage',
			data: {
					user: post_data[0],
					etappen_id: post_data[1]
			},
			async: false,
			success: function(s) {
				alert("Das Kader wurde zurückgesetzt!")
				location.reload();
			}
		});

	});
	$(".freeChange").on('click', function() {
		post_data = this.id.split("_");
		$.ajax({
			type: "post",
			url: base_url + 'dopingtest/freeChange',
			data: {
					user: post_data[0],
					etappen_id: post_data[1]
			}
		});

	});
})(jQuery);