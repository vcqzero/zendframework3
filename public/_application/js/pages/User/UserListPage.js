define(
	['jquery', 'App'],
	function($, App) {
		var tables = {
				container: $('.table-container'),
				url: '/user/table',
			}
		return {
			init: function(pageName, page) {
				App.table.init(page, tables)
			}
		}
	})