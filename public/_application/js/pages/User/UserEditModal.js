define(
	['jquery', 'myResult', 'myValidator'],
	function($, myResult, myValidator) {

		var myResultConfig = {
			enabled: true,
			forms: {
				'form-edit-user': {
					//成功
					success: {
						toast: '修改成功',
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
				'form-edit-user': {
					fields: {

						realname: {
							validators: {
								notEmpty: {
									message: '请输入真实姓名',
								},

								stringLength: {
									message: '最长8个字',
									max: 24,
								},
							}
						},

						tel: {
							validators: {
								notEmpty: {
									message: '请输入手机号',
								},

								phone: {
									country: 'CN',
									message: '请输入正确手机号',
								},
							}
						},


						workyard_id: {
							validators: {
								notEmpty: {
									message: '请选择所辖工地',
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
			}
		}
	})