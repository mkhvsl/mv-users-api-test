jQuery(function($) {
	$('#mv-users-api-test a').on('click', function(e) {
		e.preventDefault()

		var this2 = this
		$.post(my_ajax_obj.ajax_url, {
			_ajax_nonce: my_ajax_obj.nonce,
			action: 'mv_users_api_test_api_user',
			id: $(this).data('id')
			}, function(response) {
				let output

				if(response.data) output = '<p>User details:</p><pre>' + JSON.stringify(response.data, null, 4) + '</pre>'
				if(response.errors) output = '<p>User not found:</p><pre>' + JSON.stringify(response.errors, null, 4) + '</pre>'

				$('#lk-modal .uk-modal-body').html(output)
				UIkit.modal($('#lk-modal')).show()
			}
		)
	})
})