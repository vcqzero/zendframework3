define(
	['jquery', 'App'],
	function($, App) {
		var edit_url = '/api/account/edit'
		var editable = {
			"realname": {
				url: edit_url,
				validate: function(value) {
					if(value.length > 10) return '不可超过10个字符'
					if(value.length < 1) return '不可为空'
				}
			},

			"tel": {
				url: edit_url,
				validate: function(value) {
					var reg = /^[1][3,4,5,7,8][0-9]{9}$/
					if(!reg.test(value)) return '请输入正确手机号'
					if(value.length < 1) return '不可为空'
				}
			},
		}

		var validate = {

			'form-password': {

				rules: {
					'old-password': {
						required: true,
						remote : '/api/account/validPassword',
					},
					
					password: {
						required: true,
						differ  : '#old-password',
						myPassword: true,
					},
					
					'repeat-password': {
						required: true,
						equalTo: "#password"
					},
				},

				messages: {
					'old-password': {
						required: '请输入原始密码',
						remote  : '原始密码错误',
					},
					
					password: {
						required: '请输入新密码',
						differ  : '新密码和旧密码不能相同',
					},
					
					'repeat-password': {
						required: '请再次输入新密码',
						equalTo : "两次密码输入不一致"
					},
				},

				//submit success
				resultSuccess: function() {
					//					App.pageLoaging.start(true)
					//					location = '/'
				},

				//submit error
				resultError: function() {

				}
			},
		}

		return {
			init: function(pageName, page) {
				App.editable(page, editable)
				App.form.validate(page, validate)
			}
		}
	})