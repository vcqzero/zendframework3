define(
	['jquery', 'App', 'bootbox'],
	function($, App, bootbox) {
		var editableConfig = [
			//edit basic info
			{
				target: $('.edit-basic-info'),
				option: {
					url: '/api/website/edit?website=admin',
				},
			},
			//edit email
			{
				target: $('.editable-email'),
				option: {
					url: '/api/website/edit?website=email',
				},
			},
		]

		var sendTestEmail = function(page) {
			var sendEmail = function(email) {
				$.ajax({
					type:"post",
					url :"/api/website/testEmail",
					data : {email : email},
					async:true,
					beforeSend:function() {
						App.pageLoaging.start()
					},
					error : function() {
						App.pageLoaging.stop()
						alert('服务器错误')
					}
				}).done(function(res) {
					App.pageLoaging.stop()
					if(res.success) {
						App.alert({
							container : $('#tab-email-setting'),
							message : '已发送测试邮件，请登录邮箱查看是否接收到邮件（邮件有可能在垃圾邮件中）',
							type : 'success'
						})
					}else{
						App.alert({
							container : $('#tab-email-setting'),
							message : '发送失败，请检查配置是否正确',
							type : 'danger'
						})
					}
				})
			}
			
			page.find('.send-test-email').on('click', function() {
				bootbox.prompt({
					title: '请输入邮箱地址',
					inputType : 'email',
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

		var dropzone = [{
			target: $('.upload-ico'),
			option: {
				url: '/api/website/upload',
				paramName: 'ico',
				maxFilesize: '0.1', //以m为单位
				acceptedFiles: 'image/x-icon', //image
				dictInvalidFileType: '请上传ico文件',
				createImageThumbnails: true,
				clickable: '.upload-file', //forbid original dom
				params: {
					'name': 'ico', //use in file
					'website': 'admin',
				},
				success: function() {
					var file = arguments[0]
					var res = arguments[1]
					var success = res['success']
					var url = res['url']
					if(url) {
						$('#img_ico').attr('src', url)
					} else {

					}
				},
			}
		}, ]

		return {
			init: function(pageName, page) {
				App.editable(page, editableConfig)
				App.upload.dropzone(dropzone)
				sendTestEmail(page)
			}
		}
	})