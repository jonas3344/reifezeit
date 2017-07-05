;(function($) {
	var $saved;
	$('a[rel=popover]').popover({
	  html: true,
	  trigger: 'hover',
	  placement: 'bottom',
	  container: 'body',
	  content: function(){return '<img src="'+$(this).data('img') + '" width="400px" />';}
	});

	$('#etappen').on('change', function() {
		window.location.href = base_url + 'kader/tag/' + this.value;
	});
	$('.change').on('click', function() {
		span = this.id.split("_");
		id = $('.' + span[0]).attr("id");
		$saved = $('.' + span[0]).html();
		$.ajax({
			type: "post",
			url: base_url + 'kader/getFahrerForDropdown/' + span[1] + '/' + span[2],
			success: function(s) {
				data = $.parseJSON(s);
				var selectList = "<select name='" + span[0] + "' class='form-control " + span + "'>";
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
				 $('.' + span[0]).html(selectList);
			}
		})
		
	});
	$('.fahrer1').on('change', function () {
		var fahrer_id = $('select[name="fahrer1"] option:selected').attr("id");
		ajax_call('fahrer1', fahrer_id);
	});
	$('.fahrer2').on('change', function () {
		var fahrer_id = $('select[name="fahrer2"] option:selected').attr("id");
		ajax_call('fahrer2', fahrer_id);
	});
	$('.fahrer3').on('change', function () {
		var fahrer_id = $('select[name="fahrer3"] option:selected').attr("id");
		ajax_call('fahrer3', fahrer_id);
	});
	$('.fahrer4').on('change', function () {
		var fahrer_id = $('select[name="fahrer4"] option:selected').attr("id");
		ajax_call('fahrer4', fahrer_id);
	});
	$('.fahrer5').on('change', function () {
		var fahrer_id = $('select[name="fahrer5"] option:selected').attr("id");
		ajax_call('fahrer5', fahrer_id);
	});
	$('.resetKader').on('click', function() {
		$.ajax({
			type:	"post",
			url:	base_url + "kader/resetKader",
			data:	{ 
					etappe: this.id
			},
			success: function(s) {
					alert("Dein Kader wurde zurückgesetzt!");
					location.reload();
			}
		})

	});
	$("#backwards").on("click", function() {
		prev = $('#etappen > option:selected').prev('option').val();
		if (prev == undefined) {
			alert("Du bist bereits am Beginn der Rundfahrt!")
		} else {
			window.location.href = base_url + 'kader/tag/' + prev;
		}
	});
	$("#forward").on("click", function() {
		next = $('#etappen > option:selected').next('option').val();
		if (next == undefined) {
			alert("Du bist bereits am Ende der Rundfahrt");
		} else {
			window.location.href = base_url + 'kader/tag/' + next;
		}
		
	});
	$(".removeBc").on("click", function() {
		$.ajax({
			type:	"post",
			url:	base_url + "kader/removeBc",
			data:	{ abgabeId:  $(this).attr("id")},
			success: function(s) {
				alert("Die Creditabgabe wurde wieder gelöscht!");
				location.reload();
			}
		})
	});
	$(".removeFex").on("click", function() {
		alert($(this).attr("id"));
		$.ajax({
			type:	"post",
			url:	base_url + "kader/removeFex",
			data:	{ etappenid:  $(this).attr("id")},
			success: function(s) {
				alert("Deine eingesetzten Fexpunkte wurden wieder gelöscht!");
				location.reload();
			}
		})
	});
	function ajax_call(position, fahrer_id) {
		var etappen_id = $('.etappen_id').attr("id");
		$.ajax({
			type:	"post",
			url:	base_url + "kader/saveKader",
			data:	{ 
					position: position,
					etappe: etappen_id,
					fahrer_id: fahrer_id		
			},
			success: function(s) {
				if (s== "ok") {
					location.reload();
				} else {
					alert(s);
					$('.' + span[0]).html($saved);
				}
			}
		})
	}
})(jQuery);