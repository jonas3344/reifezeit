;(function($) {
	$('#etappen').on('change', function() {
		window.location.href = base_url + 'parser/deleteStage/' + this.value;
	});
	$('#delete').on('click', function() {
		etappe = $('#etappen').val();
		alert(etappe);
		$.ajax({
		    type: "post",
		    url: base_url  +  "parser/deleteStageResults",
		    data: {etappe: etappe},
		    success: function(r) {
			    alert("Die Daten wurden geslöscht!");
		    }
		  });
	});
})(jQuery);