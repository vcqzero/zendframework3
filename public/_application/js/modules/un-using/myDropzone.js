define(['jquery', 'dropzone', 'myPnotify'], function($, _, myPnotify) {
	
	var deleteFromServer = function(url, fileID, callback) {
		url = url + '/' + fileID
		$.ajax({
			type:"get",
			url:url,
			async:true
		}).done(function(res) {
			var resObj 	  = JSON.parse(res)
			var isSuccess = resObj['success']
			if (isSuccess) {
				callback()
			} else {
				alert('删除失败')
				location.reload()
			}
		})
	}

	return {
		init: function(container, config) {
			if (typeof config == 'undefined') {
				config = {}
			}
			var upload_url = container.attr('data-dropzone-upload-url')
			var delete_url = container.attr('data-dropzone-delete-url')
			config['url'] = upload_url
			
			config['error'] = function(file, message, xhr) {
				myPnotify.error(message)
				this.removeFile(file)
			}

			config['removedfile'] = function(file) {
				var previewElement = $(file.previewElement)
				var fileID         = previewElement.data('fileID')
				var callback = function() {
					file.previewElement.remove()
					console.log('删除文件')
				}
				deleteFromServer(delete_url, fileID, callback)
			}
			
			config['success'] = function(file, res) {
				var resObj = JSON.parse(res)
				var fileID = resObj['fileID']
				var previewElement = $(file.previewElement)
				previewElement.data('fileID', fileID)
				myPnotify.success('上传成功')
				console.log('上传文件成功')
			}
			
			var dropzone = container.dropzone(config)
		},
	}

})