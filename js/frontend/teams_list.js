;(function($) {
	getTableData('gesamtsieger_tabelle', 'rang_team_gw');
	
	function getTableData(table, datatype) {
		if (table == 'gesamtsieger_tabelle') {
			$.ajax({
				type: "post",
				url: base_url + 'historie/getDataForGwTableTeams',
				data: {
						type: datatype
				},
				async: false,
				success: function(s) {
					$("#" + table + " > tbody").html("");
					data = $.parseJSON(s);
					$("#gesamtsieger_tabelle_head").append('<th>Rang</th><th>Team</th><th>Anzahl Siege</th>');
					for (var i = 0; i < data.length; i++) {
						if (datatype == 'rang_team_gw') {
							
							$("#gesamtsieger_tabelle_body").append('<tr><td>' + data[i].rang + '</td><td><a href="' + base_url + 'historie/teams/' + data[i].rzteam_id + '">' + data[i].rzteam_name + '</a></td><td>' + data[i].anzahl + '</td></tr>');
							
						}
/*
						$("#" + table + " > tbody").append("<tr id='rowgw_" + data[i].id + "'><td>" + data[i].rang + "</td><td><a href='" + base_url + "historie/timeline/" + data[i].id + "'>" + data[i].name + "</a></td><td>" + data[i].anzahl + "</td></tr>");
						if (data[i].session_user == 1) {
							$("#rowgw_" + data[i].id).addClass('success');
						}
*/
					}
					if (datatype == 'rang_gw') {
						$("#title_gesamtwertung").html("Gesamtsieger");
					} else if (datatype == 'rang_punkte') {
						$("#title_gesamtwertung").html("Sieger Punktwertung");
					} else if (datatype == 'rang_berg') {
						$("#title_gesamtwertung").html("Sieger Bergwertung");
					} else if (datatype == 'rang_team_gw') {
						$("#title_gesamtwertung").html("Siege Teamwertung");
					}
				}
			});
		}
	}
})(jQuery);