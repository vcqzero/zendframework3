define(
	['jquery', 
	'bootstrapvalidator',
	], function($) {
	var getFilds = function(form_id, config) {
		var formsConfig = config['forms']
		if(formsConfig === undefined) {
			return;
		}
		var _formConfig = formsConfig[form_id]

		if(_formConfig === undefined) {
			return
		}

		return _formConfig['fields']
	}

	var getCallback = function(form_id, config) {
		var formsConfig = config['forms']
		if(formsConfig === undefined) {
			return;
		}
		var _formConfig = formsConfig[form_id]

		if(_formConfig === undefined) {
			return
		}

		return _formConfig['callback']
	}

	/**
	 * 当表单规则验证成功之后，如果有回调函数则进行回调函数的验证
	 * 
	 * @return {Boolean}
	 */
	var callbackAfterValid = function() {

	}

	var setBootstrapValidator = function(form, fields, callback) {
		form
			.bootstrapValidator({
				//				live: 'submitted',
				message: '输入内容有误',
				feedbackIcons: {
					valid: 'glyphicon glyphicon-ok',
					invalid: 'glyphicon glyphicon-remove',
					validating: 'glyphicon glyphicon-refresh'
				},
				fields: fields
			})
			.on('success.form.bv', function(e, data) {
				e.preventDefault()
				var form = $(e.target)
				var bootStrap = form.data('bootstrapValidator')
				var isValid = true
				//是否存在回调函数，如果存在，执行回调验证
				//只有回调验证成功，才可进行提交
				console.log('表单验证成功')
				if($.isFunction(callback)) {
					isValid = callback(form) === true
				}
				bootStrap.disableSubmitButtons(isValid)
				if(isValid) {
					form.trigger('myValidator:valid.success')
//					myForm.doSubmit(form)
				}
				return false;
			})
			.on('init.form.bv', function(e, data) {
				var form = $(e.target)
				console.log('表单验证器设置成功')
			})
		form.data('valid', true)
	}

	var init = function(page, config) {
		var forms = page.find('form')
		if(forms.length < 1) {
			return false;
		}

		$.each(forms, function(k, form) {
			var form = $(this)
			var bootstrapValidator = form.data('bootstrapValidator')
			form.data('valid', false)
			if(bootstrapValidator === undefined) {
				var form_id = form.attr('id')
				if(form_id === undefined) {
					return false
				}
				var filds = getFilds(form_id, config)
				var callback = getCallback(form_id, config)
				if(filds === undefined) {
					return false
				}
				setBootstrapValidator(form, filds, callback)
			}
			
			//设置点击reset form 按钮
			var resetButton = form.find('button[type="reset"]')
			resetButton.on('click', function() {
				form.data('bootstrapValidator').resetForm();
			})
		})
	}

	return {
		/**
		 * 
		 * @param {Object} pageName
		 */
		init: function(page, config) {
			if(config['enabled']) {
				init(page, config)
			}
		},
	}
})