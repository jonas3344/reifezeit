;(function($) {
	getTableData('gesamtsieger_tabelle', 'rang_gw');
	getTableData('leadertrikots_tabelle', 1);
	 $("input[name = 'gesamtwertung']").on('change', function() {
		getTableData('gesamtsieger_tabelle', this.id);
	});
	$("input[name = 'leadertrikot']").on('change', function() {
		getTableData('leadertrikots_tabelle', this.id);
	});
	
	function getTableData(table, datatype) {
		if (table == 'gesamtsieger_tabelle') {
			$.ajax({
				type: "post",
				url: base_url + 'historie/getDataForGwTable',
				data: {
						type: datatype
				},
				async: false,
				success: function(s) {
					$("#" + table + " > tbody").html("");
					data = $.parseJSON(s);
					for (var i = 0; i < data.length; i++) {
						$("#" + table + " > tbody").append("<tr id='rowgw_" + data[i].id + "'><td>" + data[i].rang + "</td><td><a href='" + base_url + "historie/timeline/" + data[i].id + "'>" + data[i].name + "</a></td><td>" + data[i].anzahl + "</td></tr>");
						if (data[i].session_user == 1) {
							$("#rowgw_" + data[i].id).addClass('success');
						}
					}
					if (datatype == 'rang_gw') {
						$("#title_gesamtwertung").html("Gesamtsieger");
					} else if (datatype == 'rang_punkte') {
						$("#title_gesamtwertung").html("Sieger Punktwertung");
					} else if (datatype == 'rang_berg') {
						$("#title_gesamtwertung").html("Sieger Bergwertung");
					}
				}
			});
		} else if (table == 'leadertrikots_tabelle') {
			$.ajax({
				type: "post",
				url: base_url + 'historie/getDataForTrikotTable',
				data: {
						type: datatype
				},
				async: false,
				success: function(s) {
					$("#" + table + " > tbody").html("");
					data = $.parseJSON(s);
					for (var i = 0; i < data.length; i++) {
						$("#" + table + " > tbody").append("<tr id='rowleader_" + data[i].id + "'><td>" + data[i].rang + "</td><td><a href='" + base_url + "historie/timeline/" + data[i].id + "'>" + data[i].name + "</a></td><td>" + data[i].anzahl + "</td></tr>");
						if (data[i].session_user == 1) {
							$("#rowleader_" + data[i].id).addClass('success');
						}
					}

					if (datatype == 1) {
						$("#title_leader").html("Tage im Leadertrikot");
					} else if (datatype == 2) {
						$("#title_leader").html("Tage im Punktetrikot");
					} else if (datatype == 3) {
						$("#title_leader").html("Tage im Bergtrikot");
					}
				}
			});

		}
	}
})(jQuery);