define(
	['jquery', 'App'],
	function($, App) {
		var result = {
			enabled: true,
			forms: {
				'form-login': {
					//成功
					success: function() {
						App.blockUI({
							'animate' : true
						})
						location = '/'
					},

					//失败
					'error': function() {
						var resObj = arguments[0]
						var message = '用户名或密码错误'
						var container = $('#form-login')
						App.alert({
							container: container, // alerts parent container
							place: 'prepent', // append or prepent in container 
							type: 'danger', // alert's type 
							message: message, // alert's message
							close: true, // make alert closable 
							reset: true, // close all previouse alerts first 
							focus: true, // auto scroll to the alert after shown 
							//						closeInSeconds: 10000, // auto close after defined seconds 
							//						icon : 'fa fa-warning' // put icon class before the message 
						})
					},
				},
			}
		}

		var validator = {
			enabled: true,
			forms: {
				'form-login': {
					rules: {
						username: {
							required: true
						},
						password: {
							required: true,
						},
					},

					messages: {
						username: {
							required: '请输入用户名'
						},
						password: {
							required: '请输入密码',
						},
					}
				}
			}
		}
		return {
			init: function(pageName, page) {
				App.submitResult(page, result)
				App.validate(page, validator)
			},
		} //end return
	})