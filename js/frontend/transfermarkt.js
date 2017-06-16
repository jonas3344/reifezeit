;(function($) {
	$(".addToShortlist").on("click", function() {
		fahrer_id = $(this).attr('id').split('_')[0];
		shortlist = $(this).attr('id').split('_')[1];
		$.ajax({
			type: "post",
			url: base_url + 'shortlist/addFahrerToShortlist',
			data: {
					fahrer_id: fahrer_id,
					shortlist: shortlist
			},
			success: function(s) {
				if (s == "ok") {
					alert("Der Fahrer wurde der Shortlist hinzugefügt!");
				} else {
					alert("Du hast diesen Fahrer bereits der Shortlist hinzugefügt!");
				}
				
				
			}

		})
	});
})(jQuery);
	