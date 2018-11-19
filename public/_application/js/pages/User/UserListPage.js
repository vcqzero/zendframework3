define(
	['jquery', 'App'],
	function($, App) {
		var tableCofig = {
			'table_user' : {
//				paging : true
			}
		}
		return {
			init: function(pageName, page) {
				App.table.dataTable(page, tableCofig)
			}
		}
	})