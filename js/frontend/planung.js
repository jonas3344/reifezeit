;(function($) {
	var wert = 0;
	var wert_dropdown = 0;
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
		if (sort_order == 3) {
			shortlist = $("#" + object_name[1] + "_sortorder_select").val();
		} else {
			shortlist = 0;
		}
		$.ajax({
			type: "get",
			url: base_url + 'kader/getFahrerForDropdown/' + sort_order + '/' + shortlist,
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
    $('.kaderuebertragUp').click(function(e) {
	    post_data = $(this).attr('id').split("_");
		$.ajax({
			type: "post",
			url: base_url + 'planung/kaderuebertragUp',
			data: {
					etappen_nr: post_data[0],
					planung_id: post_data[1]
			},
			async: false,
			success: function(s) {
				window.location.href = base_url + 'planung/index/' + post_data[1];
			}
		});
    });
     $('.kaderuebertragAll').click(function(e) {
	    post_data = $(this).attr('id').split("_");
		$.ajax({
			type: "post",
			url: base_url + 'planung/kaderuebertragAll',
			data: {
					etappen_nr: post_data[0],
					planung_id: post_data[1]
			},
			async: false,
			success: function(s) {
				window.location.href = base_url + 'planung/index/' + post_data[1];
			}
		});
    });
	$('#new').click(function(e) {
		e.preventDefault();
		name = prompt('Wie soll Deine Planung heissen?');
	    var id = $(".nav-tabs").children().length;
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
	$('.save').click(function(e) {
		id = $(this).attr('id');
		$.ajax({
			type: "post",
			url: base_url + 'planung/savePlanung',
			data: {
					planung_id: id
			},
			async: false,
			success: function(s) {
				alert("Deine Kader wurden entsprechend der Planung angepasst!");
			}
		});

	});
	$(".spielfeld").focus(function() {
		var value=$.trim($(this).val());
		if (value.length > 0) {
			if (isNaN(parseInt($(this).val())) == false) {
				wert = parseInt($(this).val());
			}
		} else {
			wert = 0;
		}
	});
	$(".spielfeld").blur(function() {
		id = $(this).attr('id');
		planung = id.split('_')[1];
		etappe = id.split('_')[2];
		
		var value=$.trim($(this).val());
		aktuellerWert = parseInt($("#avCredits_" + planung + "_" + etappe).html());
		if (value.length > 0) {
			if (isNaN($(this).val()) == false) {
				saveWert = parseInt($(this).val());
				neuerWert = aktuellerWert - wert + parseInt($(this).val());
				$("#avCredits_" + planung + "_" + etappe).html(neuerWert);
			}	
		} else {
			neuerWert = aktuellerWert - wert;
			saveWert = 0;
			$("#avCredits_" + planung + "_" + etappe).html(neuerWert);
		}
		$.ajax({
			type:	"post",
			url:	base_url + 'planung/saveSpielfeld',
			data:	{
					planung_id: planung,
					etappen_id: etappe,
					wert: saveWert
			}
		});
	});
	$(".fex").on("focusin", function() {
		$(this).data('val', $(this).val());
	});
	$(".fex").on("change", function() {
		id = $(this).attr('id');
		planung = id.split('_')[1];
		etappe = id.split('_')[2];
		aktuellerWert = parseInt($("#avCredits_" + planung + "_" + etappe).html());
		
		aktuellerWert = aktuellerWert - $(this).data('val');
		aktuellerWert = aktuellerWert + parseInt($(this).val());

		$("#avCredits_" + planung + "_" + etappe).html(aktuellerWert);
		$.ajax({
			type:	"post",
			url:	base_url + 'planung/saveFex',
			data:	{
					planung_id: planung,
					etappen_id: etappe,
					wert: $(this).val()
			}
		});
		$(this).blur();
	});
	$(".sort").on("click", function() {
		planung = $(this).attr("name").split('_')[0];
		$.ajax({
			type: "post",
			url:	base_url + 'planung/changeSort',
			data:	{
				planung_id: planung,
				wert:	$(this).val()
			}
		})
	});
})(jQuery);