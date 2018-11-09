define(['jquery', 'bootstrap_notify'], function($) {
	var init = function(type, title, message) {
		title = '<strong>' + title + '</strong>'
		message = typeof message == 'undefined' ? '' : message;
		$.notify({
			// options
			title: title,
			message: message
		}, {
			// settings
			type: type,
			allow_dismiss: false,
			newest_on_top: true,
			offset : {
				y : 1
			},
			
			placement : {
				from : 'top',//top bottom
				align : 'center',
			},
			delay : 500
		});
	}
	
//	init('success', '测试')
	return {
		success: function(title, message) {
			init('success', title, message)
		},

		error: function(title, message) {
			init('danger', title, message)
		},

		info: function(title, message) {
			init('info', title, message)
		},

		warning: function(title, message) {
			init('warning', title, message)
		}
	}
})