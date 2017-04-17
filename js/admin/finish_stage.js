;(function($) {
	$("#erster").keyup(function() {
		$.ajax({
			type:"POST",
			url: base_url + 'parser/getFahrerInfo/',
			data: {	
				startnummer:$(this).val()
				},
				success: function(data){
					$("#erster_result").html(data);
	  			}
			});	
	});
	 $("#zweiter").keyup(function() {
		$.ajax({
			type:"POST",
			url: base_url + 'parser/getFahrerInfo/',
			data: {	
				startnummer:$(this).val()
				},
				success: function(data){
					$("#zweiter_result").html(data);
	  			}
			});	
	});
	 $("#dritter").keyup(function() {
		$.ajax({
			type:"POST",
			url: base_url + 'parser/getFahrerInfo/',
			data: {	
				startnummer:$(this).val()
				},
				success: function(data){
					$("#dritter_result").html(data);
	  			}
			});	
	});
})(jQuery);