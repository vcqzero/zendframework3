define(
	['jquery', 'App'],
	function($, App) {
		var editableConfig = {
			"websiteTable" : {
				url : '/api/website/edit',
				mode:'inline',
			}
		}
		
		return {
			init: function(pageName, page) {
				App.editable(page, editableConfig)
			}
		}
	})