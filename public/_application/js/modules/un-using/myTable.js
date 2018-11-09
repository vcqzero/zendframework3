define(['jquery'], function() {
	$(function() {
		var table = $('body').find('table')
		//设置全选或反选操作
		$('body').on('click', 'table thead input[type="checkbox"]', function() {
			var _checkbox = $(this)
			var checked = this['checked']
			var table = _checkbox.parents('table')
			var itemCheck = table.find('tbody input[type="checkbox"]')
			itemCheck.prop('checked', checked)
		})

		//设置点击动作，获取所选值
		$('body').on('click', 'table input[type="checkbox"]', function() {
			//只有点击了，就重新获取一下当前点击的数据
			var _checkbox = $(this)
			var table = _checkbox.parents('table')
			var itemsChecked = table.find('tbody input[type="checkbox"]:checked')
			if(itemsChecked.length) {
				table.trigger('myTable:checked', [table, itemsChecked])
			} else {
				table.trigger('myTable:unchecked', [table])
			}
		})
	})

	var getChecked = function(tables, table_id) {
		var table = tables[table_id]
		if(typeof table == 'undefined') {
			table = {}
		}
		return table['checked']
	}

	var getUnChecked = function(tables, table_id) {
		var table = tables[table_id]
		if(typeof table == 'undefined') {
			table = {}
		}
		return table['unchecked']
	}
	return {
		init: function(tables) {
			if(typeof tables == 'undefined') {
				tables = {}
			}

			$(document).on('myTable:checked', function() {
				var table = arguments[1]
				var table_id = table.attr('id')
				var checked = getChecked(tables, table_id)
				if($.isFunction(checked)) {
					var inputs = arguments[2]
					var values = []
					$.each(inputs, function(k, _input) {
						var value = $(_input).val()
						values.push(value)
					})
					checked(values, inputs)
				}
			})

			$(document).on('myTable:unchecked', function() {
				var table = arguments[1]
				var table_id = table.attr('id')
				var UnChecked = getUnChecked(tables, table_id)
				if($.isFunction(UnChecked)) {
					UnChecked(table)
				}
			})
		}
	}
})