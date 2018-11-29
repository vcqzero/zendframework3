define(
	['jquery', 'App', 'bootbox'],
	function($, App, bootbox) {
		var editableConfig = [
			//mail
			{
				target: $('.edit-api-mail'),
				option: {
					url: '/api/website/editApi?api=mail',
				},
			},

			//weixin
			{
				target: $('.api-edit-weixin'),
				option: {
					url: '/api/website/editApi?api=weixin',
				},
			},
		]

		var testEmail = function(page) {
			var sendEmail = function(email) {
				$.ajax({
					type: "post",
					url: "/api/website/testEmail",
					data: {
						email: email
					},
					async: true,
					beforeSend: function() {
						App.pageLoaging.start()
					},
					error: function() {
						App.pageLoaging.stop()
						alert('服务器错误')
					}
				}).done(function(res) {
					App.pageLoaging.stop()
					container = $('#tab-api-email-settings')
					if(res.success) {
						App.alert({
							container: container,
							message: '已发送测试邮件，请登录邮箱查看是否接收到邮件（邮件有可能在垃圾邮件中）',
							type: 'success'
						})
					} else {
						App.alert({
							container: container,
							message: '发送失败，请检查配置是否正确',
							type: 'danger'
						})
					}
				})
			}

			page.find('.send-test-email').on('click', function() {
				bootbox.prompt({
					title: '请输入邮箱地址',
					inputType: 'email',
					buttons: {
						cancel: {
							label: '取消',
						},
						confirm: {
							label: '发送邮件',
						},
					},
					callback: function(result) {
						if(result == null) return //click cancle or close
						mailReg = /^(\w-*\.*)+@(\w-?)+(\.\w{2,})+$/;
						if(mailReg.test(result) == false) {
							alert('请输入正确邮箱地址')
							return false;
						}
						console.log('doing')
						sendEmail(result)
					}
				})
			})

		}

		var testWeixin = function(page) {
			page.find('.test-weixin').on('click', function() {
				$.ajax({
					type: "post",
					url: "/api/website/testWeixin",
					async: true,
					beforeSend: function() {
						App.pageLoaging.start()
					},
					error: function() {
						App.pageLoaging.stop()
						alert('服务器错误')
					}
				}).done(function(res) {
					App.pageLoaging.stop()
					container = $('#tab-api-weixin-settings')
					var success = res.success
					var msg     = res.msg
					if(res.success) {
						App.alert({
							container: container,
							message: '配置成功',
							type: 'success'
						})
					} else {
						App.alert({
							container: container,
							message: msg,
							type: 'danger'
						})
					}
				})
			})

		}

		return {
			init: function(pageName, page) {
				App.editable(page, editableConfig)
				testEmail(page)
				testWeixin(page)
			}
		}
	})