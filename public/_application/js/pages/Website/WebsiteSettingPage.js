define(
	['jquery', 'App'],
	function($, App) {
		var editableConfig = [
			//admin
			{
				target: $('.edit-website-admin'),
				option: {
					url: '/api/website/editWebsite?website=admin',
				},
			},
		]

		var dropzone = [{
			target: $('.admin-upload-ico'),
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
						App.toastr('success', '上传成功')
					} else {
						App.alert({
							container : $('#tab-website-admin-setting'),
							message : 'ico文件上传失败',
							type:'danger',
						})
					}
				},
			}
		}, ]

		return {
			init: function(pageName, page) {
				App.editable(page, editableConfig)
				App.upload.dropzone(dropzone)
			}
		}
	})