;(function($) {
	
	$("#save").on("click", function() {
		var userid = $('#kapitaene').val();
		$.ajax({
			type: 'post',
			url: base_url + 'administration/setkapitaen',
			data: {user: userid},
			success: function(ret) {
				data = $.parseJSON(ret);
				var user = '<tr><td>' + data.name + '</td>';
				user += '<td><button type="button" class="btn btn-default btnDelete" data-id="' + userid + '"><span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span></button></td>';
				$("#kbody").append(user);
			}
		})
	});
	
})(jQuery);