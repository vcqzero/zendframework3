#formAjax使用
application启动时会将class如下的form设置为ajax提交
class : 'my-form-ajax-submit'

##配置说明
只要将需要ajax提交的form加上class即可

##事件
当ajax提交成功之后，会触发form上的form-ajax-submit:done事件
$('form').on('form-ajax-submit:done', function() {
	//do something
})
当然本框架自动带有监听方法

#submitResult
##方法
App.submitResult: function(page, config)
var config = {
			enabled: true,
			forms: {
				'form-login': {
					//成功
					success: function() {
						var resObj = arguments[0]
						var title = '操作成功'
						var message
						var toastr_option = {
							onHidden: function() {
								location = '/'
							}
						}
						App.toastr('success', title, message, toastr_option)
					},

					//失败
					'error': function() {
						var resObj = arguments[0]
						var message = '用户名或密码错误'
						var container = $('#form-login')
						App.alert({
							container: container, // alerts parent container
							place: 'prepent', // append or prepent in container 
							type: 'danger', // alert's type 
							message: message, // alert's message
							close: true, // make alert closable 
							reset: true, // close all previouse alerts first 
							focus: true, // auto scroll to the alert after shown 
							//						closeInSeconds: 10000, // auto close after defined seconds 
							//						icon : 'fa fa-warning' // put icon class before the message 
						})
					},
				}
			}
		}
