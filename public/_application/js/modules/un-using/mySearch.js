define(['jquery'], function() {
	var CLASS_SEARCH_FORM = '.form-search-submit'

	var setFormAction = function(form, path) {
		form.attr('action', path)
		form.attr('method', 'get')
		form.find('a[type="button"]').attr('href', path)
	}

	/**
	 * 如果url带有query
	 * 则将query值填充到搜索框中
	 * 同时显示清空筛选按钮
	 * 
	 * @param {Object} form
	 * @param {Object} search
	 */
	var setFormData = function(form, search) {
		search = search.replace('?', '')
		search = search.split('&')
		$.each(search, function(k, v) {
			if(v.length < 1) {
				return false;
			}
			var query = v.split('=')
			var name = query[0]
			var value = getUrlParam(name)
			if(name != 'page' && value.length > 0) {
				form.find('[name=' + name + ']').first().val(value)
				setResetButton(form)
				disabledSubmitButton(form, false)
			}
		});
	}

	var disabledSubmitButton = function(form, disabled) {
		var submitButton = form.find('button[type="submit"]')
		submitButton.prop('disabled', disabled === true)
	}

	var setFormButton = function(form) {
		var submitButton = form.find('button[type="submit"]')
		submitButton.prop('disabled', true)
		form.on('change', 'input, select, textarea', function() {
			form.trigger('mySearch.search')
		})
	}
	
	var onSearch = function(form) {
		form.on('mySearch.search', function() {
			var submitButton = form.find('button[type="submit"]')
			submitButton.prop('disabled', false)
		})
	}
	
	var enabledSubmitButton = function(form, enabled) {
		var submitButton = form.find('button[type="submit"]')
		submitButton.prop('disabled', enabled != true)
	}

	var setResetButton = function(form) {
		form.find('a[type="button"]').removeClass('hidden')
	}

	var getUrlParam = function(key) {
		// 获取参数
		var search = window.location.search;
		// 正则筛选地址栏
		var reg = new RegExp("(^|&)" + key + "=([^&]*)(&|$)");
		// 匹配目标参数
		var result = search.substr(1).match(reg);
		//返回参数值
		return result ? decodeURIComponent(result[2]) : null;
	}
	
	var init = function() {
		var form = $('body').find('form' + CLASS_SEARCH_FORM).first(),
			path = location.pathname,
			search = window.location.search;
		if(form.length < 1) {
			return false;
		}

		//set form action
		setFormAction(form, path)
		setFormButton(form)
		setFormData(form, search)
		onSearch(form)
		console.log('mySearch is ready')
	}

	init()

	return {
		
		enabledSubmitButton : function(form, enabled) {
			enabledSubmitButton(form, enabled)
		}
	}
})