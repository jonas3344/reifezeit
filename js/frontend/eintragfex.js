;(function($) {
	$('#etappen').on('change', function() {
		window.location.href = base_url + 'kader/eintragFex/' + this.value;
	});
	$('#submit').on('click', function() {
		alert($('#punkte').val());
		$.ajax({
			type:	"post",
			url:	base_url + "kader/addFex",
			data:	{ 
					punkte: $('#punkte').val(),
					etappe: $('#etappe').val(),	
			},
			success: function(s) {
				if (s== "ok") {
					location.reload();
				}
			}
		})

	});
})(jQuery);