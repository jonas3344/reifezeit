;(function($) {
	$(".nav-tabs").on("click", "a", function (e) {
        e.preventDefault();
        $(this).tab('show');
    });
    $('#new').click(function(e) {
		e.preventDefault();
		name = prompt('Wie soll Deine Shortlist heissen?');
	    $.ajax({
			type: "post",
			url: base_url + 'shortlist/createNewShortlist',
			data: {
					name: name
			},
			async: false,
			success: function(s) {
				location.reload();
				
			}
		});
	});
	$(".remove").on("click", function() {
		shortlist = $(this).attr('id').split('_')[0];
		fahrer = $(this).attr('id').split('_')[1];
		row_id = "row_" + $(this).attr('id');
		$.ajax({
			type: "post",
			url: base_url + 'shortlist/removeFahrerFromShortlist',
			data: {
					shortlist: shortlist,
					fahrer: fahrer
			},
			async: false,
			success: function(s) {
				$("#" + row_id).remove();
			}
		});		
	});
	$(".delete").on("click", function() {
		shortlist = $(this).attr('id');
		$.ajax({
			type: "post",
			url: base_url + 'shortlist/deleteShortlist',
			data: {
					shortlist: shortlist
			},
			success: function(s) {
				alert("Die Shortlist wurde gelöscht!");
				location.reload();
			}
		});
	});
	$(".share").on("click", function() {
		shortlist = $(this).attr("id").split("_")[0];
		value = $(this).attr("id").split("_")[1];
		$.ajax({
			type: "post",
			url: base_url + 'shortlist/changeShare',
			data: {
					shortlist: shortlist,
					value: value
			},
			success: function(s) {
				if (s == 1) {
					htmlvalue = "Shortlist nicht mehr freigeben";
				} else {
					htmlvalue = "Shortlist freigeben";
				}
				$("#" + shortlist + "_" + value).html(htmlvalue);
				$("#" + shortlist + "_" + value).attr("id", shortlist + "_" + s);
			}
		})
	});
	$(".forencode").on("click", function() {
		window.location.href= base_url + "shortlist/forencode/" + $(this).attr("id");
	});
})(jQuery);
