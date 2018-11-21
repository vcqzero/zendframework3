define(
	['jquery', 'App'],
	function($, App) {
		var editableConfig = {
			"websiteTable": {
				url: '/api/website/edit',
				mode: 'inline',
			}
		}

		var dropzone = [{
			target: $('.dropzone'),
			url : '/api/website/logo',
		}, ]

		return {
			init: function(pageName, page) {
				App.editable(page, editableConfig)
				App.dropzone(dropzone)
			}
		}
	})