define(['jquery'], function($) {
	var EVENT_BEFORE = 'form-ajax-submit:before'
	var EVENT_COMPLETE = 'form-ajax-submit:complete'
	var CLASS_AJAX_FORM = 'form-ajax-submit'

	var getActionUrl = function(form) {
		return form.attr('action')
	}

	var hasData = function(form) {
		var datas = form.serializeArray()
		var hasData = false
		for(var key in datas) {
			var data = datas[key]
			if(data['value'].length > 0) {
				hasData = true
				break
			}
		}

		return hasData
	}

	var doSubmit = function(form) {
		var url = getActionUrl(form)
		var data = form.serialize()
		$.ajax({
			type: "post",
			data: data,
			url: url,
			async: true,
			beforeSend: function() {
				if(hasData(form) !== true) {
					console.log('form is empty do not need submit')
					return false;
				} else {
					console.log('The form is submitting on ajax')
					form.trigger(EVENT_BEFORE)
					disabledSubmitButton(form, true)
				}
			},

			error: function() {
				alert('请求失败')
				location.reload()
			},

		}).done(function(res) {
			var resObj = JSON.parse(res)
			form.trigger(EVENT_COMPLETE, {
				'resObj': resObj
			})
		});
	}

	var disabledSubmitButton = function(form, disabled) {
		var submitButton = form.find('button[type="submit"]')
		submitButton.text('处理中...')
		form.find('button').attr('disabled', disabled === true)
	}

	$('body').on('submit', 'form.' + CLASS_AJAX_FORM, function(e) {
		var form = $(this)
		if(form.hasClass(CLASS_AJAX_FORM) == false) {
			return false;
		}
		e.preventDefault()
		
		//判断是否是需要验证的表单
		//表单有bv-form的class代表是需要验证的表单
		if (form.hasClass('bv-form')) {
			return
		}
		doSubmit(form)
	})

	return {
		doSubmit : function(form) {
			doSubmit(form)
		}
	}
})