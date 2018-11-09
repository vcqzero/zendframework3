define(['jquery'], function($) {
	/**
	 * 页面基本框架加载好之后，就要加载实质页面内容
	 * 页面主要数据和交互功能都在这里定义
	 */
	var init_page = function(page) {
		var pageName = page.attr('data-name')
		if (typeof pageName == 'undefined') {
			console.log('page-> 未加载页面 ，页面名称未定义')
			return false;
		}
		requirejs([pageName], function(pageModule) {
			if (typeof pageModule == 'undefined') {
				return
			}
			if ($.isFunction(pageModule['init'])) {
				pageModule.init(pageName, page)
			}
			console.log('PAGE-> 加载页面完成：' + pageName)
		})
	}
	
	$(function() {
		var page = $('body').find('div.page').first()
		init_page(page)
	})
	
	$(document).on('myModal:init', function(e, page) {
		init_page(page)
	})
	
	return {}
})