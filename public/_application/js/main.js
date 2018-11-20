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
		slimScroll: 'https://cdn.bootcss.com/jQuery-slimScroll/1.3.8/jquery.slimscroll.min',
		counterup: 'https://cdn.bootcss.com/Counter-Up/1.0.0/jquery.counterup.min',
		toastr: 'https://cdn.bootcss.com/toastr.js/latest/js/toastr.min',
		blockUI: 'https://cdn.bootcss.com/jquery.blockUI/2.70.0-2014.11.23/jquery.blockUI.min',
		//表单验证
		bootstrapvalidator: 'https://cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.3/js/bootstrapValidator.min',
		jqueryValidate: 'https://cdn.bootcss.com/jquery-validate/1.18.0/jquery.validate.min',
		//select2
		select2 : 'https://cdn.bootcss.com/select2/4.0.6-rc.1/js/select2.min',
		//icheck
		iCheck : 'https://cdn.bootcss.com/iCheck/1.0.2/icheck.min',
		//moment
		moment : 'https://cdn.bootcss.com/bootstrap-daterangepicker/3.0.3/moment.min',
		//daterangepicker
		daterangepicker : 'https://cdn.bootcss.com/bootstrap-daterangepicker/3.0.3/daterangepicker.min',
		//editable
		editable : '//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min',
		ladda : 'https://cdn.bootcss.com/Ladda/1.0.6/ladda.min',
		spin : 'https://cdn.bootcss.com/Ladda/1.0.6/spin.min',
		
	},

	map: { //map告诉RequireJS在任何模块之前，都先载入这个模块
		'*': {
			css: 'css.min'//定义require-css文件
		}
	},

	shim: {
		bootstrap: {
			deps: [
				'jquery',
			],
		},
		bootstrapvalidator: {
			deps: [
				'bootstrap',
				'css!https://cdn.bootcss.com/jquery.bootstrapvalidator/0.5.3/css/bootstrapValidator.min',
			],
		},
		select2: {
			deps: [
				'jquery',
				'css!https://cdn.bootcss.com/select2/4.0.6-rc.1/css/select2.min',
			],
		},
		iCheck: {
			deps: [
				'jquery',
				'css!https://cdn.bootcss.com/iCheck/1.0.2/skins/all',
			],
		},
		daterangepicker: {
			deps: [
				'bootstrap',
				'moment',
				'css!https://cdn.bootcss.com/bootstrap-daterangepicker/3.0.3/daterangepicker.min',
			],
		},
		editable: {
			deps: [
				'bootstrap',
				'css!//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable',
			],
		},
		ladda: {
			deps: [
				'spin',
				'css!https://cdn.bootcss.com/Ladda/1.0.6/ladda-themeless.min',
			],
		},
		toastr: {
			deps: [
				'jquery',
				'css!https://cdn.bootcss.com/toastr.js/latest/css/toastr.min',
			],
		},
	},
});

// Start the main app 
requirejs(
	['jquery', 'bootstrap'],
	function($) {
		requirejs(['App'], function(App) {
			App.init()
		})
		requirejs(['Layout'], function(Layout) {
			Layout.init()
		})
		
		//预加载
		requirejs(['blockUI'], function() {})
//		requirejs(['dataTablesBootstrap'], function() {})
	});