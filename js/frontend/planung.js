;(function($) {
	$('a[rel=popover]').popover({
	  html: true,
	  trigger: 'hover',
	  placement: 'bottom',
	  container: 'body',
	  content: function(){return '<img src="'+$(this).data('img') + '" width="400px" />';}
	});
	$('.change').on('click', function() {
		object_name = this.id.split("_");
		control_name = 'fahrer' + object_name[0] + "_" + object_name[1] + "_" + object_name[2];
		id = $('.fahrer'+this.id).attr('id');
		sort_order = $('input[name=' + object_name[1] + '_sortorder]:checked').val();
		$.ajax({
			type: "post",
			url: base_url + 'kader/getFahrerForDropdown/' + sort_order,
			success: function(s) {
				data = $.parseJSON(s);
				var selectList = "<select name='" + control_name + "' class='form-control'>";
				selectList += "<option id='0'>-----------------------------------------</option>";
				for (var i = 0; i < data.length; i++)
	             {
		             if (id == data[i].fahrer_id) {
			             selected = " selected";
		             } else {
			             selected = "";
		             }
		             selectList += "<option id='" + data[i].fahrer_id + "'" + selected + ">#" + data[i].fahrer_startnummer + " " + data[i].fahrer_vorname + " " + data[i].fahrer_name + " " +  data[i].team_short + " " +  data[i].fahrer_rundfahrt_credits + "</option>";
	             }
				 selectList += "</select>";
				 $('.'+control_name).html(selectList);
			}
		})
	});
	$('.span_select').on('change', function () {
		$span_class = $(this).attr('class');
		single_class = $span_class.split(" ");
		select_name = $('.' + single_class[0]).find('.form-control').attr('name');
		split_text = select_name.split("_");
		fahrerid = $('select[name="' + select_name + '"] option:selected').attr("id");
		$.ajax({
			type: "post",
			url: base_url + 'planung/changeFahrer',
			data: {
					fahrerid: fahrerid,
					fahrerposition: split_text[0],
					planung: split_text[1],
					etappennr: split_text[2]
			},
			async: false,
			success: function(s) {
				window.location.href = base_url + 'planung/index/' + split_text[1];
			}
		});

	});
	$(".nav-tabs").on("click", "a", function (e) {
        e.preventDefault();
        $(this).tab('show');
    });
	$('#new').click(function(e) {
		e.preventDefault();
		name = prompt('Wie soll Deine Planung heissen?');
	    var id = $(".nav-tabs").children().length; //think about it ;);
		id = id+1;
	    var tabId = name;
	    $.ajax({
			type: "post",
			url: base_url + 'planung/createNewPlanung',
			data: {
					name: name
			},
			async: false,
			success: function(s) {
				location.reload();
				
			}
		});
	});
	$('.delete').click(function(e) {
		id = $(this).attr('id');
		$.ajax({
			type: "post",
			url: base_url + 'planung/removePlanung',
			data: {
					id: id
			},
			async: false,
			success: function(s) {
				location.reload();
			}
		});

	})
	$('.rename').click(function(e) {
		id = $(this).attr('id');
		new_name = prompt("Gib einen neuen Namen ein!");
		$.ajax({
			type: "post",
			url: base_url + 'planung/renamePlanung',
			data: {
					id: id,
					new_name: new_name
			},
			async: false,
			success: function(s) {
				window.location.href = base_url + 'planung/index/' + id;
			}
		});

	})
	$('.kaderuebertrag').click(function(e) {
		post_data = $(this).attr('id').split("_");
		$.ajax({
			type: "post",
			url: base_url + 'planung/kaderuebertrag',
			data: {
					etappen_nr: post_data[0],
					planung_id: post_data[1]
			},
			async: false,
			success: function(s) {
				window.location.href = base_url + 'planung/index/' + post_data[1];
			}
		});
	})
	$('.reset').click(function(e) {
		id = $(this).attr('id');
		$.ajax({
			type: "post",
			url: base_url + 'planung/resetPlanung',
			data: {
					id: id
			},
			async: false,
			success: function(s) {
				alert("Die Planung wurde zurückgesetzt!");
				window.location.href = base_url + 'planung/index/' + id;
			}
		});

	})
	$('.saveKaderDay').click(function(e) {
		post_data = $(this).attr('id').split("_");
		$.ajax({
			type: "post",
			url: base_url + 'planung/saveKaderDay',
			data: {
					etappen_nr: post_data[0],
					planung_id: post_data[1]
			},
			async: false,
			success: function(s) {
				alert("Deine Planung der " + s + ".Etappe wurde übernommen!");
			}
		});

	})
})(jQuery);