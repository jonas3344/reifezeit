;(function($) {
	$('.etappen_nr, .etappen_datum, .etappen_start_ziel, .etappen_distanz, .etappen_profil, .etappen_eingabeschluss').editable();
	
	$('.form-control').change(function() {
		var $select = $(".form-control");
		$.ajax({
			type: "post",
			url: base_url + 'stammdaten/setEtappenDetails/',
			data: {pk: $select.attr('id'),
					value: $select.val(),
					name: 'etappen_klassifizierung'
			}
		})
	});

})(jQuery);