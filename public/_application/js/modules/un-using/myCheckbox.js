define(['jquery'], function() {

	var EVENT_CHECKED = 'myCheckbox:checked'
	var EVENT_UNCHECKED = 'myCheckbox:unchecked'

	var FLAG_CLASS = 'checkbox_switch'
	var toggle = function(checkboxes, isChecked) {
		$.each(checkboxes, function(k, v) {
			$(this).prop('checked', isChecked)
		})
	}

	var getSwitch = function(container) {
		var _inputs = container.find('input[type="checkbox"]')
		var _input
		$.each(_inputs, function(k, v) {
			var value = $(this).val()
			if(value.length < 1) {
				_input = $(this)
				return false;
			}
		})
		return _input
	}

	var getItemChechbox = function(container) {
		var _inputs = container.find('input[type="checkbox"]')
		$.each(_inputs, function(k, v) {
			var _input = $(this)
			if(_input.hasClass(FLAG_CLASS)) {
				delete _inputs[k]
			}
		})
		return _inputs
	}

	return {
		init: function(container, _config) {
			var config = {
				checked: undefined,
				unchecked: unchecked,
			}
			$.extend(true, config, _config)
			var checked = config['checked']
			var unchecked = config['unchecked']
			container = container.first()
			var checkboxes = getItemChechbox(container)
			container.off('click', 'input[type="checkbox"]')
			container.on('click', 'input[type="checkbox"]', function() {
				var _input = $(this)
				if(_input.hasClass(FLAG_CLASS)) {
					toggle(checkboxes, this['checked'])
				}
				checked_input = container.find('input[type="checkbox"]:not(.checkbox_switch):checked')
				if(checked_input.length) {
					container.trigger(EVENT_CHECKED, [checked_input])
				} else {
					container.trigger(EVENT_UNCHECKED)
				}
			})

			container.on(EVENT_CHECKED, function() {
				if($.isFunction(checked)) {
					var inputs = arguments[1]
					var values = []
					$.each(inputs, function(k, _input) {
						var value = $(_input).val()
						values.push(value)
					})
					var ids = values.join(',')
					checked(ids, inputs)
				}
			})

			container.on(EVENT_UNCHECKED, function() {
				if($.isFunction(unchecked)) {
					unchecked()
				}
			})
		}

	}
})