define(
	['jquery', 'myResult', 'myValidator'], 
	function($, myResult, myValidator) {

	var myPickImage = {
		init: function(page) {
			$(document).on('picked.myPickImage', function() {
				var _image = arguments[1].find('img')
				var _imageCopy 	= _image.clone()
				var thumbnail 	= $('#ico').find('div.thumbnail')
				var ico_url     = _image.attr('src')
				_imageCopy.css('width', '30%')
				thumbnail.children().remove()
				thumbnail.append(_imageCopy)
				$('#ico').find('input').first().val(ico_url)
			})
		}
	}

	var myResultConfig = {
		enabled: true,
		forms: {
			'form-edit-website': {
				//成功
				success: {
					toast: '操作成功',
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
			'form-edit-website': {
				fields: {
					website_record: {
						validators: {
							notEmpty: {
								message: '请输入网站备案信息',
							},

							stringLength: {
								message: '最长36个字符',
								max: 124,
							},
						}
					},
				},
			},
		},
	}
	
	return {
		init: function(pageName, page) {
			myPickImage.init(page)
			myResult.init(page, myResultConfig)
			myValidator.init(page, myValidatorConfig)
		}
	}
})