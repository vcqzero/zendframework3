define(
	['jquery', 'myResult', 'myValidator'],
	function($, myResult, myValidator) {

		var myResultConfig = {
			enabled: true,
			forms: {
				'form-account-password': {
					//成功
					success: {
						toast: '修改成功,请重新登录',
						route: '/api/auth/logout',
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
				'form-account-password': {
					fields: {

						password_old: {
							validators: {

								notEmpty: {
									message: '请输入原密码',
								},

								remote: {
									url: '/api/user/validPassword',
									type: 'POST', //以post的方式发生信息
									message: '原密码不正确',
								},
							}
						},

						password: {
							validators: {

								notEmpty: {
									message: '请输入新密码',
								},

								stringLength: {
									message: '至少6个字符，最长120字符',
									min : 6,
									max: 120,
								},

								regexp: {
									regexp: /\w/,
									message: "密码不可含中文字符"
								},
							}
						},

						password_repeat: {
							validators: {

								notEmpty: {
									message: '请再次输入新密码',
								},

								identical: {
									field: 'password',
									message: '两次输入密码不一致'
								}
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
			}
		}
	})