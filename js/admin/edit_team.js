;(function($) {

    $('.teamname, .teamshort').editable();

	$('.delete').click(function() {
		$actualElement = $(this);
		$.ajax({
			type: "post",
			url: base_url + 'stammdaten/deleteRider/',
			data: {fahrerid: $actualElement.attr('id')},
			success: function(s) {
					if (s == "ok") {
						alert("Der Fahrer wurde gelöscht!");
						location.reload();
					} else {
						alert("Beim Löschen ist ein Fehler aufgetreten!");
					}
				}

		})
	});
})(jQuery);