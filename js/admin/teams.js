window.operateEvents = {
        'click .delete': function (e, value, row) {
	        var sortedKeys = Object.keys(row).sort();
	        var teamid = row[sortedKeys[0]];
            $.ajax({
				type: "post",
				url: base_url + 'stammdaten/deleteTeam/',
				data: {teamid: teamid},
				success: function(s) {
					if (s == "ok") {
						alert("Das Team wurde gelöscht!");
						location.reload();
					} else {
						alert("Beim Löschen ist ein Fehler aufgetreten!");
					}
				}
			});
        }
    };
function operateFormatter(value, row, index) {
    return [
        value,
        '<a class="delete btn btn-default" href="javascript:void(0)" title="Delete">',
        '<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>',
        '</a> '
    ].join('');
}
