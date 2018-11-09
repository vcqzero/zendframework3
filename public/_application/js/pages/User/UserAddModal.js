define(
	['jquery', 'myResult', 'myValidator'],
	function($, myResult, myValidator) {

		var myResultConfig = {
			enabled: true,
			forms: {
				'form-add-user': {
					//成功
					success: {
						toast: '添加成功',
						route: 'reload',
					},

					//失败
					error: {
						toast: '操作失败',
						route: 'reload',
					},
				}
			}
		}

		var myValidatorConfig = {
			enabled: true,
			forms: {
				'form-add-user': {
					fields: {
						username: {
							validators: {

								notEmpty: {
									message: '请输入用户名',
								},

								stringLength: {
									message: '最长12个字符',
									max: 12,
								},

								regexp: {
									regexp: /^[a-zA-Z]{1}([a-zA-Z0-9]|[._-]){3,19}$/,
									message: "字母开头，可含有数字 '.' '_',限4~12位"
								},

								remote: {
									url: '/api/user/validName',
									type: 'POST', //以post的方式发生信息
									message: '名称已存在',
								},
							}
						},

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
									country : 'CN',
									message : '请输入正确手机号',
								},
							}
						},

//						email: {
//
//							validators: {
//								emailAddress: {
//									message: '请输入正确邮箱地址'
//								}
//							}
//						},

						role: {
							validators: {
								notEmpty: {
									message: '请选择角色',
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