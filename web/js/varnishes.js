$(document).ready(function() {
	$('.assoc-check').change(function() {
		var check = $(this);
		var data = {
			'assoc': check.is(":checked"),
			'varnish_id': check.attr('data-varnish-id'),
			'website_id': check.attr('data-website-id')
		};

		$.post('/varnishes-link', data)
			.done(function() {
				// Uncheck all others for this website (only 1 varnish per website allowed)
				$('.assoc-check[data-website-id="' + data.website_id + '"]').not(check).each(function() {
					$(this).prop('checked', false);
				});
			})
			.fail(function() {
				alert('Error saving link.')
			});
	});
});