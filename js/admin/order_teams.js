;(function($) {
	$("#sortable" ).sortable({
		handle: "span.sort",
		update: save_order
	});
	
	function save_order(event, ui) {
		$.ajax({
		    type: "post",
		    url: base_url  +  "administration/saveOrder/",
		    data: $(this).sortable("serialize"),
		    dataType:"json",
		    success: function(r) {}
		  });
	}
})(jQuery);