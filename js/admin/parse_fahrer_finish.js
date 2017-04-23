;(function($) {
	$('.plus').click(function() {
		id = $(this).attr("id");
		name = $('#' + id + '_name').html();
		vorname = $('#' + id + '_vorname').html();
		nation = $('#' + id + '_nation').html();
		team = $('#team').val();
		$.ajax({
			type: "post",
			url: base_url + 'stammdaten/addFahrerToDb/',
			data: {	fahrer_name: name,
					fahrer_vorname: vorname,
					fahrer_nation: nation,
					fahrer_team_id: team
			},
			success: function(s) {
					if (s == "ok") {
						alert("Der Fahrer wurde der Datenbank hinzugefügt!");
						$('#' + id + '_row').removeClass('danger');
						$('#' + id + '_row').addClass('success');
						$('#' + id).hide();
					} else {
						alert("Beim Wechsel ist ein Fehler aufgetreten!");
					}
				}
		})
	})
})(jQuery);