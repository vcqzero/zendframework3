define(
	['jquery', 'App', 'require'],
	function($, App, require) {
		var editable = function() {
			App.editable('/api/website/edit')
		}
		var editableConfig = {
			"websiteTable" : {
				url : '/api/website/edit',
				mode:'inline',
			}
		}
		
		return {
			init: function(pageName, page) {
				editable()
			}
		}
	})