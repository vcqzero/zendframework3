define(
	['jquery', 'App', 'cropper'],
	function($, App, Cropper) {
		var edit_url = '/api/account/edit'
		var editable = [
			{
				target: $('#realname'),
				option: {
					url: edit_url,
					validate: function(value) {
						if(value.length > 10) return '不可超过10个字符'
						if(value.length < 1) return '不可为空'
					}
				},
			},
			{
				target: $('#tel'),
				option: {
					url: edit_url,
					validate: function(value) {
						var reg = /^[1][3,4,5,7,8][0-9]{9}$/
						if(!reg.test(value)) return '请输入正确手机号'
						if(value.length < 1) return '不可为空'
					}
				},
			},
		]

		var validate = {

			'form-password': {

				rules: {
					'old-password': {
						required: true,
						remote: '/api/account/validPassword',
					},

					password: {
						required: true,
						differ: '#old-password',
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
						remote: '原始密码错误',
					},

					password: {
						required: '请输入新密码',
						differ: '新密码和旧密码不能相同',
					},

					'repeat-password': {
						required: '请再次输入新密码',
						equalTo: "两次密码输入不一致"
					},
				},

				//submit success
				resultSuccess: function() {
					var resObj = arguments[0]
					var form = arguments[1]
					App.toastr('success', '操作成功')
					//					App.pageLoaging.start(true)
					location.reload()
				},

				//submit error
				resultError: function() {
					var resObj = arguments[0]
					var form = arguments[1]
					var msg = resObj['msg']
					App.alert({
						container: form,
						place: 'prepend',
						type: 'danger',
						message: msg,
					})
				}
			},
		}

		var avatarUpload = [{
			target: $('.dropzone'),
			option: {
				url: '/api/account/avatar',
				paramName: 'avatar',
				maxFilesize: '2', //以m为单位
				acceptedFiles: 'image/*', //image
				dictInvalidFileType: '请上传图片文件',
				createImageThumbnails: true,
				clickable: '.upload-file',
				resizeWidth: 300, //将图片截取为300 300 大小
				resizeHeight: 300,
				resizeMethod: 'crop', //已裁剪的方式处理图片
				//				params : {'type' : 'ico'},
				success: function() {
					var file = arguments[0]
					var res = arguments[1]
					var success = res['success']
					var url = res['url']
					if(url) {
						App.toastr('success', '上传成功')
						location.reload()
					} else {
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

		var avatar_cropper = function() {
			var image = $('#avatar')[0]
			var inputImage = $('#select-file')
			var uploadButton = $('#upload-avatar') //listen click
			var uploadUrl = '/api/account/avatar'
			var previewSelector = '.img-preview'
			var cropper
			var type //the image type

			var init_cropper = function() {
				if(cropper) cropper.destroy()
				cropper = new Cropper(image, {
					aspectRatio: 9 / 9,
					viewMode: 1, //限制画布不要超出范围
					preview: previewSelector,
				})

				//show preview 
				$(previewSelector).removeClass('hide')
				//show upload button
				uploadButton.removeClass('hide')
			}

			inputImage.on('change', function() {
				var files = this.files;
				var file;
				if(files && files.length) {
					file = files[0];
					type = file.type
					if(/^image\/\w+/.test(file.type)) {
						image.src = URL.createObjectURL(file)
						init_cropper()
						inputImage.value = null;
					} else {
						App.toastr('error', '请选择图片文件')
					}
				}
			})

			uploadButton.on('click', function() {
				if(typeof cropper == 'undefined') return
				console.log('upload')
				var canvas = cropper.getCroppedCanvas()
				canvas.toBlob(function(blob) {
					//ajax
					var fd = new FormData();
					fd.append("avatar", blob, 'avatar.png');
					$.ajax({
						type: "post",
						url: uploadUrl,
						data: fd,
						processData: false,
						contentType: false,
						async: true,
						beforeSend: function() {
							App.pageLoaging.start()
						},
						error: function() {
							alert('服务器错误')
							location.reload()
						},
					}).done(function(res) {
						App.pageLoaging.stop()
						if(res.success != true) {
							alert('服务器错误')
						} else {
							App.toastr('success', '上传成功')
						}
						location.reload()
					})
				}, type)
			})
		}

		return {
			init: function(pageName, page) {
				App.editable(page, editable)
				App.form.validate(page, validate)
				//avatar cropper
				avatar_cropper()
			}
		}
	})