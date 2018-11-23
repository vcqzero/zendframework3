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
					var resObj = arguments[0]
					var form   = arguments[1]
					App.toastr('success', '操作成功')
//					App.pageLoaging.start(true)
					location.reload()
				},

				//submit error
				resultError: function() {
					var resObj = arguments[0]
					var form   = arguments[1]
					var msg    =resObj['msg']
					App.alert({
						container : form,
						place : 'prepend',
						type : 'danger',
						message :msg,
					})
				}
			},
		}
		
		var avatarUpload = [{
			target: $('.dropzone'),
			option: {
				url: '/api/account/avatar',
				paramName : 'avatar',
				maxFilesize: '2',//以m为单位
				acceptedFiles : 'image/*',//image
				dictInvalidFileType: '请上传图片文件',
				createImageThumbnails: true,
				clickable: '.upload-file',
				resizeWidth : 300,//将图片截取为300 300 大小
				resizeHeight : 300,
				resizeMethod : 'crop',//已裁剪的方式处理图片
//				params : {'type' : 'ico'},
				success : function() {
					var file = arguments[0]
					var res  = arguments[1]
					var success = res['success']
					var url     = res['url']
					if (url) {
						App.toastr('success', '上传成功')
						location.reload()
					}else {
						App.toastr('error', '上传失败')
					}
				},
				
//				transformFile : function(file, done) {
//					file.width = 200
//					console.log(file)
//					done(file)
//				}
			}
		}, ]

		return {
			init: function(pageName, page) {
				App.editable(page, editable)
				App.form.validate(page, validate)
				App.upload.dropzone(avatarUpload)
			}
		}
	})