jQuery(function($) {
	$('#lk-inpsyde-test a').on('click', function(e) {
		e.preventDefault()

		var this2 = this
		$.post(my_ajax_obj.ajax_url, {
			_ajax_nonce: my_ajax_obj.nonce,
			action: 'mv_users_api_test_api_user',
			id: $(this).data('id')
			}, function(data) {
				let output

				if(data) {
					output = '<p>User details:</p><pre>' + JSON.stringify(data, null, 4) + '</pre>'
				} else {
					output = '<p>Error, please contact site administrator</p>'
				}
				$('#lk-modal .uk-modal-body').html(output)
				UIkit.modal($('#lk-modal')).show()
			}
		)
	})
})