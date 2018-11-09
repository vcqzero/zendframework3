define(['jquery', 'bootstrap'], function($) {
	var myModal = function() {
		var EVENT_MODAL_INIT = 'myModal:init'
		var current_modal
		var onClose
		var getModal = function(title, settings) {
			var modal = $('<div class="modal"  tabindex="-1" role="dialog">' +
				'<div class="modal-dialog" role="document">' +
				'<div class="modal-content">' +
				'<div class="modal-header">' +
				'<h4 class="modal-title"></h4>' +
				'</div>' +
				'<div class="modal-body"></div>' +
				'<div class="modal-footer">' +
				'</div>' +
				'</div>' +
				'</div>' +
				'</div>')

			//set title
			title = title.length < 1 ? 'title' : title
			modal.find('.modal-title').text(title)

			//set zhe modal size
			var class_modal_size = settings.size === 'sm' ? 'modal-sm' : 'modal-lg'
			modal.find('div.modal-dialog').addClass(class_modal_size)

			//set zhe modal content
			var content = settings.content
			if(typeof content !== 'undefined') {
				modal.find('div.modal-body').append(content)
			}

			//set close
			var close = settings.close
			var closeIcon = '<button type="button" class="close" data-dismiss="modal" aria-label="Close">' +
				'<span aria-hidden="true">&times;</span>' +
				'</button>'
			var closeButton = '<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>'
			if(close === true) {
				modal.find('div.modal-header').prepend(closeIcon)
				modal.find('div.modal-footer').append(closeButton)
			}

			//set confirm
			var confirm = settings.confirm
			var confirmButton = '<button type="button" class="btn btn-primary" data-dismiss="modal">确认</button>'
			if(confirm === true) {
				modal.find('div.modal-footer').append(confirmButton)
			}

			//set callback confirm
			onClose = settings.onClose

			//set callback confirm
			var onConfirm = settings.onConfirm
			modal.on('click', 'button.btn-primary', function() {
				if($.isFunction(onConfirm)) {
					onConfirm()
				}
			})

			return modal
		}

		var show = function(modal, option) {
			modal.modal(option)
			modal.off('hidden.bs.modal')
			modal.on('hidden.bs.modal', function(e) {
				var modal = $(e.target)
				if($.isFunction(onClose)) {
					onClose()
					onClose = undefined
				}
				modal.remove()
			})

			current_modal = modal
		}
		var showLoading = function() {
			var progress_bar = '<div class="progress progress-striped active" style="margin-bottom:0;"><div class="progress-bar" style="width: 100%"></div></div>'
			var settings = {
				size: 'sm', //lg
				content: progress_bar,
				close: false,
				confirm: false,
				//			onClose : '',
				//			onConfirm : '',
			}
			var progress_modal_id = 'progress_modal'
			var modal = getModal('加载中...', settings)
			modal.removeClass('fade')
			modal.css('padding-top', '15%')

			show(modal, {
				backdrop: 'static',
				keyboard: false,
			})
		}

		var hide = function(callback) {
			onClose = callback
			current_modal.modal('hide')
		}

		/**
		 * 远程加载modal并将其显示出来
		 * behaivor 
		 * 如果当前页面已存在modal则不显示
		 * 
		 */
		var load = function(url) {
			$.ajax({
				type: "get",
				url: url,
				async: true,
				beforeSend: function() {
					showLoading()
				},

				error: function() {
					alert('操作失败')
					location.reload()
				},

			}).done(function(modal) {
				var callback = function() {
					var _modal = $(modal)
					if(_modal.hasClass('modal')) {
						$('body').append(_modal)
						show(_modal)
					}
					$(document).trigger(EVENT_MODAL_INIT, [_modal])
				}
				hide(callback)
			})
		}

		$(function() {
			$('body').off('click', '[data-modal-open="true"]')
			$('body').on('click', '[data-modal-open="true"]', function(e) {
				console.log('open modal')
				var _button = $(this)
				var modalUrl = _button.attr('data-modal-url')
				if(modalUrl) {
					load(modalUrl)
				}
			})
		})
	}

	var myPage = function() {
		/**
		 * 页面基本框架加载好之后，就要加载实质页面内容
		 * 页面主要数据和交互功能都在这里定义
		 */
		var init_page = function(page) {
			var pageName = page.attr('data-name')
			if(typeof pageName == 'undefined') {
				console.log('page-> 未加载页面 ，页面名称未定义')
				return false;
			}
			requirejs([pageName], function(pageModule) {
				if(typeof pageModule == 'undefined') {
					return
				}
				if($.isFunction(pageModule['init'])) {
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
	}

	var mySearch = function() {
		var CLASS_SEARCH_FORM = '.form-search-submit'

		var setFormAction = function(form, path) {
			form.attr('action', path)
			form.attr('method', 'get')
			form.find('a[type="button"]').attr('href', path)
		}

		/**
		 * 如果url带有query
		 * 则将query值填充到搜索框中
		 * 同时显示清空筛选按钮
		 * 
		 * @param {Object} form
		 * @param {Object} search
		 */
		var setFormData = function(form, search) {
			search = search.replace('?', '')
			search = search.split('&')
			$.each(search, function(k, v) {
				if(v.length < 1) {
					return false;
				}
				var query = v.split('=')
				var name = query[0]
				var value = getUrlParam(name)
				if(name != 'page' && value.length > 0) {
					form.find('[name=' + name + ']').first().val(value)
					setResetButton(form)
					disabledSubmitButton(form, false)
				}
			});
		}

		var disabledSubmitButton = function(form, disabled) {
			var submitButton = form.find('button[type="submit"]')
			submitButton.prop('disabled', disabled === true)
		}

		var setFormButton = function(form) {
			var submitButton = form.find('button[type="submit"]')
			submitButton.prop('disabled', true)
			form.on('change', 'input, select, textarea', function() {
				form.trigger('mySearch.search')
			})
		}

		var onSearch = function(form) {
			form.on('mySearch.search', function() {
				var submitButton = form.find('button[type="submit"]')
				submitButton.prop('disabled', false)
			})
		}

		var enabledSubmitButton = function(form, enabled) {
			var submitButton = form.find('button[type="submit"]')
			submitButton.prop('disabled', enabled != true)
		}

		var setResetButton = function(form) {
			form.find('a[type="button"]').removeClass('hidden')
		}

		var getUrlParam = function(key) {
			// 获取参数
			var search = window.location.search;
			// 正则筛选地址栏
			var reg = new RegExp("(^|&)" + key + "=([^&]*)(&|$)");
			// 匹配目标参数
			var result = search.substr(1).match(reg);
			//返回参数值
			return result ? decodeURIComponent(result[2]) : null;
		}

		var init = function() {
			var form = $('body').find('form' + CLASS_SEARCH_FORM).first(),
				path = location.pathname,
				search = window.location.search;
			if(form.length < 1) {
				return false;
			}

			//set form action
			setFormAction(form, path)
			setFormButton(form)
			setFormData(form, search)
			onSearch(form)
			console.log('mySearch is ready')
		}

		init()
	}

	var route = {
		getSearchParam: function(key) {
			// 获取参数
			var search = window.location.search;
			// 正则筛选地址栏
			var reg = new RegExp("(^|&)" + key + "=([^&]*)(&|$)");
			// 匹配目标参数
			var result = search.substr(1).match(reg);
			//返回参数值
			return result ? decodeURIComponent(result[2]) : null;
		},
	}

	myNavbar()
	myNoprogress()
	myPage()
	myModal()
	mySearch()
	myForm()
	myPanel()
	console.log('myFramework init')
	return {
		pnotify: pnotify,
		route: route,
	}
})