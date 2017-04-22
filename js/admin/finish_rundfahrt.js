;(function($) {
	$('#finish').on('click', function() {
		$.ajax({
		    type: "post",
		    url: base_url  +  "parser/finishRundfahrtToDb",
		    success: function(r) {
			    alert("Die Daten wurden gespeichert!");
		    }
		  });
	})
})(jQuery);