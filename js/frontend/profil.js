;(function($) {
	$('.rz_name').editable();
	
	$('#changePw').click(function() {
		oldPw = $('#pwOld').val();
		newPw = $('#pwNew').val();
		newPwConfirm = $('#pwNewConfirm').val();
		if (newPw == newPwConfirm) {
			$.ajax({
			type: "post",
			url: base_url + 'profil/changePw/',
			data: {oldPw: oldPw,
					newPw: newPw
			},
			success: function(s) {
				alert(s);
				$('#pwOld').val('');
				$('#pwNew').val('');
				$('#pwNewConfirm').val('');
			}
			});
		} else {
			alert("Dein gewünschtes Passwort stimmt nicht mit der Bestätigung überein!");
		}
	});

})(jQuery);