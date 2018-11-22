define(
	['jquery', 'App'],
	function($, App) {
		var editableConfig = {
			"websiteTable": {
				url: '/api/website/edit',
				mode: 'inline',
			}
		}

		var dropzone = [{
			target: $('.dropzone'),
			option: {
				url: '/api/website/upload',
				paramName : 'ico',
				maxFilesize: '0.1',//以m为单位
				acceptedFiles : 'image/x-icon',//image
				dictInvalidFileType: '请上传ico文件',
				createImageThumbnails: true,
				clickable: '.upload-file',
				params : {'type' : 'ico'},
				success : function() {
					var file = arguments[0]
					var res  = arguments[1]
					var success = res['success']
					var url     = res['url']
					if (url) {
						$('#img_ico').attr('src', url)
					}else {
						
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