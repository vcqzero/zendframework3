define(
	['jquery', 'myResult', 'myValidator', 'myPnotify'],
	function($, myResult, myValidator, myPnotify) {
		var testEmail = function(page) {
			$('#button_test_email').on('click', function() {
				var _url = '/api/website/testEmail'
				var _button = $(this)
				$.ajax({
					type: "post",

					url: _url,

					async: true,
					
					beforeSend: function() {
						_button.prop('disabled', true)
					},

					error: function() {
						alert('邮件发送失败，请确认各项参数是否正确')
						location.reload()
					},

				}).done(function() {
					alert('发送成功:请登录测试邮箱，查看是否收到文件')
					_button.prop('disabled', false)
				})
			})
		}

		var myResultConfig = {
			enabled: true,
			forms: {
				'form-edit-email': {
					//成功
					success: {
						toast: '操作成功',
						route: 'reload',
					},

					//失败
					error: {
						toast: '操作失败',
						route: 'reload',
					},
				},
			},
		}

		var myValidatorConfig = {
			enabled: true,
			forms: {
				'form-edit-email': {
					fields: {
						name: {
							validators: {
								notEmpty: {
									message: '请输入名称',
								},

								stringLength: {
									message: '最长36个字符',
									max: 124,
								},
							}
						},

						host: {
							validators: {
								notEmpty: {
									message: '请输入发件服务器主机',
								},

								stringLength: {
									message: '最长124个字符',
									max: 124,
								},
							}
						},

						port: {
							validators: {
								notEmpty: {
									message: '请输入发件服务器端口号',
								},
								integer: {
									message: '必须为数字'
								}
							}
						},

						username: {
							validators: {
								notEmpty: {
									message: '请输入邮箱地址',
								},

								emailAddress: {
									message: '请输入正确的邮箱地址'
								},

								stringLength: {
									message: '最长124个字符',
									max: 124,
								},
							}
						},

						password: {
							validators: {
								notEmpty: {
									message: '请输入邮箱密码',
								},
							}
						},

						test_address: {
							validators: {
								notEmpty: {
									message: '请输入测试地址',
								},

								emailAddress: {
									message: '请输入正确的邮箱地址'
								},
							}
						},

					},
				},
			},
		}

		return {
			init: function(pageName, page) {
				myResult.init(page, myResultConfig)
				myValidator.init(page, myValidatorConfig)
				testEmail(page)
			}
		}
	})