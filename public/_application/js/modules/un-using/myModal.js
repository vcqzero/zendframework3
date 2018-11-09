define(['jquery'], function($) {
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

	return {
		load: function(url) {
			load(url)
		},

		/**
		 * 显示modal
		 * 
		 * @param {Object} title 
		 * @param {Object} settings
		 */
		show: function(title, settings) {
			var modal = getModal(title, settings)
			show(modal)
		},

		hide: function(onHide) {
			hide(onHide)
		},
	}
})