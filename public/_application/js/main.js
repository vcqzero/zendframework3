/**
 * 站点程序入口文件（采用requireJs规范）
 * 
 */
requirejs.config({
	baseUrl: '/_application/js',

	//已baseUrl为基础定义不同js文件的路径
	//文件路径不可包含后缀名 js
	paths: {
		//引入jQuery和bootstrap
		jquery: 'https://cdn.bootcss.com/jquery/3.3.1/jquery.min',
		bootstrap: 'https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min',
		//鼠标移动到dropdown时自动弹出
		'bootstrap-hover-dropdown': 'https://cdn.bootcss.com/bootstrap-hover-dropdown/2.2.1/bootstrap-hover-dropdown.min',
		//遮罩层，当遮罩层弹出时，处于container中的元素被锁定
		blockUI: 'https://cdn.bootcss.com/jquery.blockUI/2.70.0-2014.11.23/jquery.blockUI.min',
		//导航条插件，可以为指定的元素设置导航条
		//可以要求其在规定的高度内滚动
		slimscroll: 'https://cdn.bootcss.com/jQuery-slimScroll/1.3.8/jquery.slimscroll.min',
		//数组自动增加插件
		counterup: 'https://cdn.bootcss.com/Counter-Up/1.0.0/jquery.counterup.min',
		//表单验证
		bootstrapvalidator: 'https://cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.3/js/bootstrapValidator.min',
		notify: 'https://cdn.bootcss.com/mouse0270-bootstrap-notify/3.1.7/bootstrap-notify.min',
		nprogress: 'https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min',

		
		
		
		
		
//		bootstrapvalidator_language: 'https://cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.3/js/language/zh_CN.min',
//		nprogress: 'https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min',
//		pnotify: 'https://cdnjs.cloudflare.com/ajax/libs/pnotify/3.2.1/pnotify',
		//mouse0270-bootstrap-notify
//		bootstrap_notify: 'https://cdn.bootcss.com/mouse0270-bootstrap-notify/3.1.7/bootstrap-notify.min',
//		GaodeMap : 'https://webapi.amap.com/maps?v=1.4.10&key=bf7d1e214598b146869b101434b3210a',
//		GaodeMapUi : 'https://webapi.amap.com/ui/1.0/main.js?v=1.0.11',
//		datetimepicker : 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min',
//		daterangepicker : 'https://cdn.bootcss.com/bootstrap-daterangepicker/3.0.3/daterangepicker.min',
//		datetimepicker_moment : 'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/moment-with-locales.min',
//		moment : 'https://cdn.bootcss.com/bootstrap-daterangepicker/3.0.3/moment.min',
//		select2 : 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.1/js/select2.min',
//		select2_zh_cn : 'https://cdn.bootcss.com/select2/4.0.6-rc.1/js/i18n/zh-CN',
//		
//		myFramework: 'modules/myFramework',
//		myValidator: 'modules/myValidator',
//		myResult: 'modules/myResult',
////		myPnotify: 'modules/myPnotify',
//		myGaodemap: 'modules/myGaodemap',
	},

	//定义不同js的依赖关系
	//当然可以在define中进行定义，但是对于第三方库需要另一个第三方库的时候
	//在shim中定义是最简单的
	shim: {
		"bootstrap": ["jquery"],
		"bootstrapvalidator": ["bootstrap"],
		"select2": ["bootstrap"],
		"bootstrap_notify": ["bootstrap"],
		"bootstrapvalidator_language": ["bootstrapvalidator"],
		"datetimepicker": ['datetimepicker_moment'],
//		"daterangepicker": ['moment'],
	}
});

// Start the main app 
requirejs(
	['jquery', 
	'bootstrap', 
	],
	function($) {
//		requirejs(['nprogress'], function(NProgress) {
//			NProgress.start()
//			setTimeout(function() {
//				NProgress.done()
//			}, 500)
//		})
		
		requirejs(['App'], function(App) {
			App.init()
		})
		
		requirejs(['Layout'], function(layout) {
			layout.init()
		})
	});