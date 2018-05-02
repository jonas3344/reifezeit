;(function($) {
	$('#etappen').on('change', function() {
		window.location.href = base_url + 'parser/index/' + this.value;
	})
	$('#ausreisserJa, #ausreisserNein').on('change', function() {
		if (this.value == 1) {
			$('#firstHauptfeldDiv').removeClass('collapse');
		} else if (this.value == 2) {
			$('#firstHauptfeldDiv').addClass('collapse');
		}
	})
	$('#type1, #type2, #type3').on('change', function() {
		if (this.value == 1) {
			$('#ausreisser').removeClass('collapse');
			$("#ausreisserNein").prop("checked", true);
			$('#firstHauptfeldDiv').addClass('collapse');
		} else if (this.value == 2 || this.value == 3) {
			$('#ausreisser').addClass('collapse');
			$('#firstHauptfeldDiv').addClass('collapse');
		}
	})	
})(jQuery);