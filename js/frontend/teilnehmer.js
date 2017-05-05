

function detailFormatter(index, row) {
	//alert(index);
    var html = [];
    var data;
    $.each(row, function (key, value) {
	    if (key == "id") {
		    $.ajax({
				type: "post",
				url: base_url + 'teilnahme/getTeilnahmeData',
				data: {
						user_id: value
				},
				async: false,
				success: function(s) {
					data = $.parseJSON(s);
					
				}
			});
	    }
    });
    html.push('<table>');
    html.push('<tr><td width="20%">Verbleibende Creditannahmen:</td><td>' + data.creditempfang + '</td></tr>');
    html.push('<tr><td>Verbleibende Creditabgaben:</td><td>' + data.creditabgabe + '</td></tr>');
    if (data.rolle_id == 3) {
	    html.push('<tr><td>Eingesetzte Punkte Etappenjäger:</td><td>' + data.fex + '</td></tr>');
    }
    if (data.rolle_id == 6) {
	    html.push('<tr><td>Eingesetzte Punkte Bergfex:</td><td>' + data.fex + '</td></tr>');
    }
    if (data.creditmoves > 0) {
	    html.push('<tr><td>Verschieben möglich:</td><td>Ja</td></tr>');
    } else {
	    html.push('<tr><td>Verschieben möglich:</td><td>Nein</td></tr>');
    }
    html.push('</table>')
	return html.join('');
}
