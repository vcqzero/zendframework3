/**
 * 框架文件
 * 不涉及模板
 * 
 */
define(function(require) {
	var $ = require('jquery')
	var jQuery = require('jquery')
	var App = (function() {
		// IE mode
		var isRTL = false;
		var isIE8 = false;
		var isIE9 = false;
		var isIE10 = false;

		var resizeHandlers = [];

		var assetsPath = '/_application/';

		var pagePath = '/_application/js/pages/';

		var globalImgPath = 'img/';

		var globalPluginsPath = 'global/plugins/';

		var globalCssPath = 'css/';

		var mainPageClass = 'main-page'

		// theme layout color set

		var brandColors = {
			'blue': '#89C4F4',
			'red': '#F3565D',
			'green': '#1bbc9b',
			'purple': '#9b59b6',
			'grey': '#95a5a6',
			'yellow': '#F8CB00'
		};

		/**
		 * 识别浏览器是哪个ie版本，
		 * 
		 */
		var handleInit = function() {

			if($('body').css('direction') === 'rtl') {
				isRTL = true;
			}

			isIE8 = !!navigator.userAgent.match(/MSIE 8.0/);
			isIE9 = !!navigator.userAgent.match(/MSIE 9.0/);
			isIE10 = !!navigator.userAgent.match(/MSIE 10.0/);

			if(isIE10) {
				$('html').addClass('ie10'); // detect IE10 version
			}

			if(isIE10 || isIE9 || isIE8) {
				$('html').addClass('ie'); // detect IE10 version
			}
		};

		// runs callback functions set by App.addResponsiveHandler().
		var _runResizeHandlers = function() {
			// reinitialize other subscribed elements
			for(var i = 0; i < resizeHandlers.length; i++) {
				var each = resizeHandlers[i];
				each.call();
			}
		};

		// handle the layout reinitialization on window resize
		var handleOnResize = function() {
			var resize;
			if(isIE8) {
				var currheight;
				$(window).resize(function() {
					if(currheight == document.documentElement.clientHeight) {
						return; //quite event since only body resized not window.
					}
					if(resize) {
						clearTimeout(resize);
					}
					resize = setTimeout(function() {
						_runResizeHandlers();
					}, 50); // wait 50ms until window resize finishes.                
					currheight = document.documentElement.clientHeight; // store last body client height
				});
			} else {
				$(window).resize(function() {
					if(resize) {
						clearTimeout(resize);
					}
					resize = setTimeout(function() {
						_runResizeHandlers();
					}, 50); // wait 50ms until window resize finishes.
				});
			}
		};

		// Handles portlet tools & actions
		var handlePortletTools = function() {
			// handle portlet remove
			$('body').on('click', '.portlet > .portlet-title > .tools > a.remove', function(e) {
				e.preventDefault();
				var portlet = $(this).closest(".portlet");

				if($('body').hasClass('page-portlet-fullscreen')) {
					$('body').removeClass('page-portlet-fullscreen');
				}

				portlet.find('.portlet-title .fullscreen').tooltip('destroy');
				portlet.find('.portlet-title > .tools > .reload').tooltip('destroy');
				portlet.find('.portlet-title > .tools > .remove').tooltip('destroy');
				portlet.find('.portlet-title > .tools > .config').tooltip('destroy');
				portlet.find('.portlet-title > .tools > .collapse, .portlet > .portlet-title > .tools > .expand').tooltip('destroy');

				portlet.remove();
			});

			// handle portlet fullscreen
			$('body').on('click', '.portlet > .portlet-title .fullscreen', function(e) {
				e.preventDefault();
				var portlet = $(this).closest(".portlet");
				if(portlet.hasClass('portlet-fullscreen')) {
					$(this).removeClass('on');
					portlet.removeClass('portlet-fullscreen');
					$('body').removeClass('page-portlet-fullscreen');
					portlet.children('.portlet-body').css('height', 'auto');
				} else {
					var height = App.getViewPort().height -
						portlet.children('.portlet-title').outerHeight() -
						parseInt(portlet.children('.portlet-body').css('padding-top')) -
						parseInt(portlet.children('.portlet-body').css('padding-bottom'));

					$(this).addClass('on');
					portlet.addClass('portlet-fullscreen');
					$('body').addClass('page-portlet-fullscreen');
					portlet.children('.portlet-body').css('height', height);
				}
			});

			$('body').on('click', '.portlet > .portlet-title > .tools > a.reload', function(e) {
				e.preventDefault();
				var el = $(this).closest(".portlet").children(".portlet-body");
				var url = $(this).attr("data-url");
				var error = $(this).attr("data-error-display");
				if(url) {
					App.blockUI({
						target: el,
						animate: true,
						overlayColor: 'none'
					});
					$.ajax({
						type: "GET",
						cache: false,
						url: url,
						dataType: "html",
						success: function(res) {
							App.unblockUI(el);
							el.html(res);
							App.initAjax() // reinitialize elements & plugins for newly loaded content
						},
						error: function(xhr, ajaxOptions, thrownError) {
							App.unblockUI(el);
							var msg = 'Error on reloading the content. Please check your connection and try again.';
							if(error == "toastr" && toastr) {
								toastr.error(msg);
							} else if(error == "notific8" && $.notific8) {
								$.notific8('zindex', 11500);
								$.notific8(msg, {
									theme: 'ruby',
									life: 3000
								});
							} else {
								alert(msg);
							}
						}
					});
				} else {
					// for demo purpose
					App.blockUI({
						target: el,
						animate: true,
						overlayColor: 'none'
					});
					window.setTimeout(function() {
						App.unblockUI(el);
					}, 1000);
				}
			});

			// load ajax data on page init
			$('.portlet .portlet-title a.reload[data-load="true"]').click();

			$('body').on('click', '.portlet > .portlet-title > .tools > .collapse, .portlet .portlet-title > .tools > .expand', function(e) {
				e.preventDefault();
				var el = $(this).closest(".portlet").children(".portlet-body");
				if($(this).hasClass("collapse")) {
					$(this).removeClass("collapse").addClass("expand");
					el.slideUp(200);
				} else {
					$(this).removeClass("expand").addClass("collapse");
					el.slideDown(200);
				}
			});
		};

		// Handlesmaterial design checkboxes
		var handleMaterialDesign = function() {

			// Material design ckeckbox and radio effects
			$('body').on('click', '.md-checkbox > label, .md-radio > label', function() {
				var the = $(this);
				// find the first span which is our circle/bubble
				var el = $(this).children('span:first-child');

				// add the bubble class (we do this so it doesnt show on page load)
				el.addClass('inc');

				// clone it
				var newone = el.clone(true);

				// add the cloned version before our original
				el.before(newone);

				// remove the original so that it is ready to run on next click
				$("." + el.attr("class") + ":last", the).remove();
			});

			if($('body').hasClass('page-md')) {
				// Material design click effect
				// credit where credit's due; http://thecodeplayer.com/walkthrough/ripple-click-effect-google-material-design       
				var element, circle, d, x, y;
				$('body').on('click', 'a.btn, button.btn, input.btn, label.btn', function(e) {
					element = $(this);

					if(element.find(".md-click-circle").length == 0) {
						element.prepend("<span class='md-click-circle'></span>");
					}

					circle = element.find(".md-click-circle");
					circle.removeClass("md-click-animate");

					if(!circle.height() && !circle.width()) {
						d = Math.max(element.outerWidth(), element.outerHeight());
						circle.css({
							height: d,
							width: d
						});
					}

					x = e.pageX - element.offset().left - circle.width() / 2;
					y = e.pageY - element.offset().top - circle.height() / 2;

					circle.css({
						top: y + 'px',
						left: x + 'px'
					}).addClass("md-click-animate");

					setTimeout(function() {
						circle.remove();
					}, 1000);
				});
			}

			// Floating labels
			var handleInput = function(el) {
				if(el.val() != "") {
					el.addClass('edited');
				} else {
					el.removeClass('edited');
				}
			}

			$('body').on('keydown', '.form-md-floating-label .form-control', function(e) {
				handleInput($(this));
			});
			$('body').on('blur', '.form-md-floating-label .form-control', function(e) {
				handleInput($(this));
			});

			$('.form-md-floating-label .form-control').each(function() {
				if($(this).val().length > 0) {
					$(this).addClass('edited');
				}
			});
		}

		// Handles custom checkboxes & radios using jQuery iCheck plugin
		var handleiCheck = function() {
			if(!$().iCheck) {
				return;
			}

			$('.icheck').each(function() {
				var checkboxClass = $(this).attr('data-checkbox') ? $(this).attr('data-checkbox') : 'icheckbox_minimal-grey';
				var radioClass = $(this).attr('data-radio') ? $(this).attr('data-radio') : 'iradio_minimal-grey';

				if(checkboxClass.indexOf('_line') > -1 || radioClass.indexOf('_line') > -1) {
					$(this).iCheck({
						checkboxClass: checkboxClass,
						radioClass: radioClass,
						insert: '<div class="icheck_line-icon"></div>' + $(this).attr("data-label")
					});
				} else {
					$(this).iCheck({
						checkboxClass: checkboxClass,
						radioClass: radioClass
					});
				}
			});
		};

		// Handles Bootstrap switches
		var handleBootstrapSwitch = function() {
			if(!$().bootstrapSwitch) {
				return;
			}
			$('.make-switch').bootstrapSwitch();
		};

		// Handles Bootstrap confirmations
		var handleBootstrapConfirmation = function() {
			if(!$().confirmation) {
				return;
			}
			$('[data-toggle=confirmation]').confirmation({
				btnOkClass: 'btn btn-sm btn-success',
				btnCancelClass: 'btn btn-sm btn-danger'
			});
		}

		// Handles Bootstrap Accordions.
		var handleAccordions = function() {
			$('body').on('shown.bs.collapse', '.accordion.scrollable', function(e) {
				App.scrollTo($(e.target));
			});
		};

		// Handles Bootstrap Tabs.
		var handleTabs = function() {
			//activate tab if tab id provided in the URL
			if(location.hash) {
				var tabid = encodeURI(location.hash.substr(1));
				$('a[href="#' + tabid + '"]').parents('.tab-pane:hidden').each(function() {
					var tabid = $(this).attr("id");
					$('a[href="#' + tabid + '"]').click();
				});
				$('a[href="#' + tabid + '"]').click();
			}

			if($().tabdrop) {
				$('.tabbable-tabdrop .nav-pills, .tabbable-tabdrop .nav-tabs').tabdrop({
					text: '<i class="fa fa-ellipsis-v"></i>&nbsp;<i class="fa fa-angle-down"></i>'
				});
			}
		};

		// Handles Bootstrap Modals.
		var handleModals = function() {
			// fix stackable modal issue: when 2 or more modals opened, closing one of modal will remove .modal-open class. 
			$('body').on('hide.bs.modal', function() {
				if($('.modal:visible').length > 1 && $('html').hasClass('modal-open') === false) {
					$('html').addClass('modal-open');
				} else if($('.modal:visible').length <= 1) {
					$('html').removeClass('modal-open');
				}
			});

			// fix page scrollbars issue
			$('body').on('show.bs.modal', '.modal', function() {
				if($(this).hasClass("modal-scroll")) {
					$('body').addClass("modal-open-noscroll");
				}
			});

			// fix page scrollbars issue
			$('body').on('hidden.bs.modal', '.modal', function() {
				$('body').removeClass("modal-open-noscroll");
				$(this).remove()
			});

			// remove ajax content and remove cache on modal closed 
			$('body').on('hidden.bs.modal', '.modal:not(.modal-cached)', function() {
				$(this).removeData('bs.modal');
			});

			$('body').on('click', 'a.click-open-modal', function(e) {
				e.preventDefault()
				var _this = $(this)
				var _url = _this.attr('href')
				if(typeof _url == 'undefined') return
				$.ajax({
					type: "get",
					url: _url,
					async: true,
					beforeSend: function() {
						App.modalLoading.start()
					},

					error: function() {
						App.modalLoading.stop()
						alert('服务器错误')
					},

				}).done(function(modal) {
					App.modalLoading.stop()
					var modal = $(modal)
					if(modal.hasClass('modal') == false) return
					$('body').append(modal)
					modal.modal('show')
					modal.trigger('bs.modal.load', [modal])
				})
			})

		};

		// Handles Bootstrap Tooltips.
		var handleTooltips = function() {
			// global tooltips
			$('.tooltips').tooltip();

			// portlet tooltips
			$('.portlet > .portlet-title .fullscreen').tooltip({
				trigger: 'hover',
				container: 'body',
				title: '全屏'
			});
			$('.portlet > .portlet-title > .tools > .reload').tooltip({
				trigger: 'hover',
				container: 'body',
				title: '重新加载'
			});
			$('.portlet > .portlet-title > .tools > .remove').tooltip({
				trigger: 'hover',
				container: 'body',
				title: '删除'
			});
			$('.portlet > .portlet-title > .tools > .config').tooltip({
				trigger: 'hover',
				container: 'body',
				title: '设置'
			});
			$('.portlet > .portlet-title > .tools > .collapse, .portlet > .portlet-title > .tools > .expand').tooltip({
				trigger: 'hover',
				container: 'body',
				title: '收起/展开'
			});
		};

		// Handles Bootstrap Dropdowns
		var handleDropdowns = function() {
			/*
			  Hold dropdown on click  
			*/
			$('body').on('click', '.dropdown-menu.hold-on-click', function(e) {
				e.stopPropagation();
			});
		};

		var handleAlerts = function() {
			$('body').on('click', '[data-close="alert"]', function(e) {
				$(this).parent('.alert').hide();
				$(this).closest('.note').hide();
				e.preventDefault();
			});

			$('body').on('click', '[data-close="note"]', function(e) {
				$(this).closest('.note').hide();
				e.preventDefault();
			});

			$('body').on('click', '[data-remove="note"]', function(e) {
				$(this).closest('.note').remove();
				e.preventDefault();
			});
		};

		// Handle Hower Dropdowns
		var handleDropdownHover = function() {
			$('[data-hover="dropdown"]').not('.hover-initialized').each(function() {
				$(this).dropdownHover();
				$(this).addClass('hover-initialized');
			});
		};

		// Handle textarea autosize 
		var handleTextareaAutosize = function() {
			if(typeof(autosize) == "function") {
				autosize(document.querySelector('textarea.autosizeme'));
			}
		}

		// Handles Bootstrap Popovers

		// last popep popover
		var lastPopedPopover;

		var handlePopovers = function() {
			$('.popovers').popover();

			// close last displayed popover

			$(document).on('click.bs.popover.data-api', function(e) {
				if(lastPopedPopover) {
					lastPopedPopover.popover('hide');
				}
			});
		};

		// Handles Image Preview using jQuery Fancybox plugin
		var handleFancybox = function() {
			if(!jQuery.fancybox) {
				return;
			}

			if($(".fancybox-button").length > 0) {
				$(".fancybox-button").fancybox({
					groupAttr: 'data-rel',
					prevEffect: 'none',
					nextEffect: 'none',
					closeBtn: true,
					helpers: {
						title: {
							type: 'inside'
						}
					}
				});
			}
		};

		// Fix input placeholder issue for IE8 and IE9
		var handleFixInputPlaceholderForIE = function() {
			//fix html5 placeholder attribute for ie7 & ie8
			if(isIE8 || isIE9) { // ie8 & ie9
				// this is html5 placeholder fix for inputs, inputs with placeholder-no-fix class will be skipped(e.g: we need this for password fields)
				$('input[placeholder]:not(.placeholder-no-fix), textarea[placeholder]:not(.placeholder-no-fix)').each(function() {
					var input = $(this);

					if(input.val() === '' && input.attr("placeholder") !== '') {
						input.addClass("placeholder").val(input.attr('placeholder'));
					}

					input.focus(function() {
						if(input.val() == input.attr('placeholder')) {
							input.val('');
						}
					});

					input.blur(function() {
						if(input.val() === '' || input.val() == input.attr('placeholder')) {
							input.val(input.attr('placeholder'));
						}
					});
				});
			}
		};

		// Handle Select2 Dropdowns
		var handleSelect2 = function() {
			if($().select2) {
				$.fn.select2.defaults.set("theme", "bootstrap");
				$('.select2me').select2({
					placeholder: "Select",
					width: 'auto',
					allowClear: true
				});
			}
		};

		// handle group element heights
		var handleHeight = function() {
			$('[data-auto-height]').each(function() {
				var parent = $(this);
				var items = $('[data-height]', parent);
				var height = 0;
				var mode = parent.attr('data-mode');
				var offset = parseInt(parent.attr('data-offset') ? parent.attr('data-offset') : 0);

				items.each(function() {
					if($(this).attr('data-height') == "height") {
						$(this).css('height', '');
					} else {
						$(this).css('min-height', '');
					}

					var height_ = (mode == 'base-height' ? $(this).outerHeight() : $(this).outerHeight(true));
					if(height_ > height) {
						height = height_;
					}
				});

				height = height + offset;

				items.each(function() {
					if($(this).attr('data-height') == "height") {
						$(this).css('height', height);
					} else {
						$(this).css('min-height', height);
					}
				});

				if(parent.attr('data-related')) {
					$(parent.attr('data-related')).css('height', parent.height());
				}
			});
		}

		var handlePage = function() {
			var init_page = function(page) {
				var name = page.attr('data-name')
				var group = page.attr('data-group')
				var pageName
				if(typeof name == 'undefined' || typeof group == 'undefined') {
					console.log('未加载页面 ，页面名称未定义')
					return false;
				}
				pageName = pagePath + group + '/' + name + '.js'
				require([pageName], function(pageModule) {
					if(typeof pageModule == 'undefined') {
						return
					}
					if($.isFunction(pageModule['init'])) {
						pageModule.init(pageName, page)
					}
					console.log('加载页面完成：' + pageName)
				})
			}

			$(function() {
				var page = $('body').find('div.' + mainPageClass).first()
				init_page(page)
			})

			$(document).on('bs.modal.load', function(e, page) {
				init_page(page)
			})
		}
		//* END:CORE HANDLERS *//

		return {

			//main function to initiate the theme
			init: function() {
				//IMPORTANT!!!: Do not modify the core handlers call order.

				//Core handlers
				handleInit(); // initialize core variables
				handleOnResize(); // set and handle responsive    

				//UI Component handlers     
				handleMaterialDesign(); // handle material design       
				handleiCheck(); // handles custom icheck radio and checkboxes
				handleBootstrapSwitch(); // handle bootstrap switch plugin
				//				handleScrollers(); // handles slim scrolling contents 
				handleFancybox(); // 弹出框方式查看图片
				handleSelect2(); // handle custom Select2 dropdowns
				handlePortletTools(); // handles portlet action bar functionality(refresh, configure, toggle, remove)
				handleAlerts(); //handle closabled alerts
				handleDropdowns(); // handle dropdowns
				handleTabs(); // handle tabs
				handleTooltips(); // handle bootstrap tooltips
				handlePopovers(); // handles bootstrap popovers
				handleAccordions(); //handles accordions 
				handleModals(); // handle modals
				handleBootstrapConfirmation(); // handle bootstrap confirmations
				handleTextareaAutosize(); // handle autosize textareas
				//				handleCounterup(); // handle counterup instances

				//Handle group element heights
				this.addResizeHandler(handleHeight); // handle auto calculating height on window resize

				// Hacks
				handleFixInputPlaceholderForIE(); //IE8 & IE9 input placeholder issue fix
				//handle page
				handlePage()
			},

			//main function to initiate core javascript after ajax complete
			initAjax: function() {
				//handleUniform(); // handles custom radio & checkboxes     
				handleiCheck(); // handles custom icheck radio and checkboxes
				handleBootstrapSwitch(); // handle bootstrap switch plugin
				handleDropdownHover(); // handles dropdown hover       
				handleScrollers(); // handles slim scrolling contents 
				handleSelect2(); // handle custom Select2 dropdowns
				handleFancybox(); // handle fancy box
				handleDropdowns(); // handle dropdowns
				handleTooltips(); // handle bootstrap tooltips
				handlePopovers(); // handles bootstrap popovers
				handleAccordions(); //handles accordions 
				handleBootstrapConfirmation(); // handle bootstrap confirmations
			},

			/**
			 * 
			 * 
			 * @param {Object} $button
			 * @param {Object} loading
			 */
			loadingButtion: function($button, loading) {
				require(['ladda'], function(Ladda) {
					var _ladda = Ladda.create($button[0])
					if(loading) {
						_ladda.start()
					} else {
						_ladda.stop()
						_ladda.remove()
					}
				})
			},

			//init main components 
			initComponents: function() {
				this.initAjax();
			},

			handleCounterup: function() {
				require(['counterup'], function() {
					if(!$().counterUp) {
						return;
					}

					$("[data-counter='counterup']").counterUp({
						delay: 10,
						time: 1000
					});
				})
			},
			//public function to remember last opened popover that needs to be closed on click
			setLastPopedPopover: function(el) {
				lastPopedPopover = el;
			},

			//public function to add callback a function which will be called on window resize
			addResizeHandler: function(func) {
				resizeHandlers.push(func);
			},

			//public functon to call _runresizeHandlers
			runResizeHandlers: function() {
				_runResizeHandlers();
			},

			/**
			 * 聚焦元素
			 * 
			 * @param {Object} el
			 * @param {Object} offeset
			 */
			scrollTo: function(el, offeset) {
				var pos = (el && el.length > 0) ? el.offset().top : 0;

				if(el) {
					if($('body').hasClass('page-header-fixed')) {
						pos = pos - $('.page-header').height();
					} else if($('body').hasClass('page-header-top-fixed')) {
						pos = pos - $('.page-header-top').height();
					} else if($('body').hasClass('page-header-menu-fixed')) {
						pos = pos - $('.page-header-menu').height();
					}
					pos = pos + (offeset ? offeset : -1 * el.height());
				}

				$('html,body').animate({
					scrollTop: pos
				}, 'slow');
			},

			/**
			 * 为给定的元素加上竖向滚动条
			 * height需要设置的元素的data-height中
			 * 
			 * @param {Object} el jQuery集合
			 */
			initSlimScroll: function(el) {
				require(['slimScroll'], function() {
					if(!$().slimScroll) {
						return;
					}

					$(el).each(function() {
						if($(this).attr("data-initialized")) {
							return; // exit
						}

						var height;

						if($(this).attr("data-height")) {
							height = $(this).attr("data-height");
						} else {
							height = $(this).css('height');
						}

						$(this).slimScroll({
							allowPageScroll: true, // allow page scroll when the element scroll is ended
							size: '7px',
							color: ($(this).attr("data-handle-color") ? $(this).attr("data-handle-color") : '#bbb'),
							wrapperClass: ($(this).attr("data-wrapper-class") ? $(this).attr("data-wrapper-class") : 'slimScrollDiv'),
							railColor: ($(this).attr("data-rail-color") ? $(this).attr("data-rail-color") : '#eaeaea'),
							position: isRTL ? 'left' : 'right',
							height: height,
							alwaysVisible: ($(this).attr("data-always-visible") == "1" ? true : false),
							railVisible: ($(this).attr("data-rail-visible") == "1" ? true : false),
							disableFadeOut: true
						});

						$(this).attr("data-initialized", "1");
					});
				})
			},

			destroySlimScroll: function(el) {
				require(['slimScroll'], function() {
					if(!$().slimScroll) {
						return;
					}

					$(el).each(function() {
						if($(this).attr("data-initialized") === "1") { // destroy existing instance before updating the height
							$(this).removeAttr("data-initialized");
							$(this).removeAttr("style");

							var attrList = {};

							// store the custom attribures so later we will reassign.
							if($(this).attr("data-handle-color")) {
								attrList["data-handle-color"] = $(this).attr("data-handle-color");
							}
							if($(this).attr("data-wrapper-class")) {
								attrList["data-wrapper-class"] = $(this).attr("data-wrapper-class");
							}
							if($(this).attr("data-rail-color")) {
								attrList["data-rail-color"] = $(this).attr("data-rail-color");
							}
							if($(this).attr("data-always-visible")) {
								attrList["data-always-visible"] = $(this).attr("data-always-visible");
							}
							if($(this).attr("data-rail-visible")) {
								attrList["data-rail-visible"] = $(this).attr("data-rail-visible");
							}

							$(this).slimScroll({
								wrapperClass: ($(this).attr("data-wrapper-class") ? $(this).attr("data-wrapper-class") : 'slimScrollDiv'),
								destroy: true
							});

							var the = $(this);

							// reassign custom attributes
							$.each(attrList, function(key, value) {
								the.attr(key, value);
							});

						}
					});
				})
			},

			/**
			 * 滚动页面to top
			 * 
			 */
			scrollTop: function() {
				App.scrollTo();
			},

			blockUI: {
				block: function(options) {
					require(['blockUI'], function() {
						_options = {
							//						animate : true,
							////						boxed :true,
						}
						options = $.extend(true, _options, options);
						var html = '';
						if(options.animate) {
							html = '<div class="loading-message ' + (options.boxed ? 'loading-message-boxed' : '') + '">' + '<div class="block-spinner-bar"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div>' + '</div>';
						} else if(options.iconOnly) {
							html = '<div class="loading-message ' + (options.boxed ? 'loading-message-boxed' : '') + '"><img src="' + App.getGlobalImgPath() + 'loading-spinner-grey.gif" align=""></div>';
						} else if(options.textOnly) {
							html = '<div class="loading-message ' + (options.boxed ? 'loading-message-boxed' : '') + '"><span>&nbsp;&nbsp;' + (options.message ? options.message : 'LOADING...') + '</span></div>';
						} else {
							html = '<div class="loading-message ' + (options.boxed ? 'loading-message-boxed' : '') + '"><img src="' + App.getGlobalImgPath() + 'loading-spinner-grey.gif" align=""><span>&nbsp;&nbsp;' + (options.message ? options.message : '提交数据...') + '</span></div>';
						}

						if(options.target) { // element blocking
							var el = $(options.target);
							if(el.height() <= ($(window).height())) {
								options.cenrerY = true;
							}
							el.block({
								message: html,
								baseZ: options.zIndex ? options.zIndex : 1000,
								centerY: options.cenrerY !== undefined ? options.cenrerY : false,
								css: {
									top: '10%',
									border: '0',
									padding: '0',
									backgroundColor: 'none'
								},
								overlayCSS: {
									backgroundColor: options.overlayColor ? options.overlayColor : '#555',
									opacity: options.boxed ? 0.05 : 0.1,
									cursor: 'wait'
								}
							});
						} else { // page blocking
							$.blockUI({
								message: html,
								baseZ: options.zIndex ? options.zIndex : 1000,
								css: {
									border: '0',
									padding: '0',
									backgroundColor: 'none'
								},
								overlayCSS: {
									backgroundColor: options.overlayColor ? options.overlayColor : '#555',
									opacity: options.boxed ? 0.05 : 0.1,
									cursor: 'wait'
								}
							});
						}
					})
				},

				unBlock: function(target) {
					require(['blockUI'], function() {
						if(target) {
							$(target).unblock({
								onUnblock: function() {
									$(target).css('position', '');
									$(target).css('zoom', '');
								}
							});
						} else {
							$.unblockUI();
						}
					})
				},
			},

			pageLoaging: {
				/**
				 * start page loading
				 * 
				 * @param {Object} animate
				 * @param {Object} message
				 */
				start: function(withBlockUI) {
					if(withBlockUI) {
						App.blockUI.block({
							animate: true
						})
					} else {
						$('.page-spinner-bar').remove();
						$('body').append('<div class="page-spinner-bar"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div>');
					}
				},

				/**
				 * stop page loading
				 * 
				 */
				stop: function(withBlockUI, target) {
					if(withBlockUI) {
						App.blockUI.unBlock(target)
					} else {
						$('.page-loading, .page-spinner-bar').remove();
					}
				},
			},

			modalLoading: {
				start: function() {
					var modal = $('<div class="modal modal-loading"  tabindex="-1" role="dialog">' +
						'<div class="modal-dialog modal-sm" role="document">' +
						'<div class="modal-content">' +
						'<div class="modal-header">' +
						'<h4 class="modal-title">加载中...</h4>' +
						'</div>' +
						'<div class="modal-body">' +
						'<div class="progress progress-striped active" style="margin-bottom:0;"><div class="progress-bar" style="width: 100%"></div></div>' +
						'</div>' +
						'<div class="modal-footer">' +
						'</div>' +
						'</div>' +
						'</div>' +
						'</div>')
					$('body').append(modal)
					modal.modal('show', {
						backdrop: 'static',
						keyboard: false,
					})
				},

				stop: function() {
					$('.modal-loading').modal('hide')
				}
			},

			/**
			 * 在页面中显示警告信息
			 * 
			 * @param {Object} options 可参看bootstrap中的设置
			 */
			alert: function(options) {

				options = $.extend(true, {
					container: "", // alerts parent container(by default placed after the page breadcrumbs)
					place: "append", // "append" or "prepend" in container 
					type: 'success', // success danger warnig info
					message: "", // alert's message
					close: true, // make alert closable
					reset: true, // close all previouse alerts first
					focus: true, // auto scroll to the alert after shown
					closeInSeconds: 0, // auto close after defined seconds
					icon: "" // put icon before the message
				}, options);

				var id = App.getUniqueID("App_alert");

				var html = '<div id="' + id + '" class="custom-alerts alert alert-' + options.type + ' fade in">' + (options.close ? '<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>' : '') + (options.icon !== "" ? '<i class="fa-lg fa fa-' + options.icon + '"></i>  ' : '') + options.message + '</div>';

				if(options.reset) {
					$('.custom-alerts').remove();
				}

				if(!options.container) {
					if($('.page-fixed-main-content').length === 1) {
						$('.page-fixed-main-content').prepend(html);
					} else if(($('body').hasClass("page-container-bg-solid") || $('body').hasClass("page-content-white")) && $('.page-head').length === 0) {
						$('.page-title').after(html);
					} else {
						if($('.page-bar').length > 0) {
							$('.page-bar').after(html);
						} else {
							$('.page-breadcrumb, .breadcrumbs').after(html);
						}
					}
				} else {
					if(options.place == "append") {
						$(options.container).append(html);
					} else {
						$(options.container).prepend(html);
					}
				}

				if(options.focus) {
					App.scrollTo($('#' + id));
				}

				if(options.closeInSeconds > 0) {
					setTimeout(function() {
						$('#' + id).remove();
					}, options.closeInSeconds * 1000);
				}

				return id;
			},

			//public function to initialize the fancybox plugin
			initFancybox: function() {
				handleFancybox();
			},

			//public helper function to get actual input value(used in IE9 and IE8 due to placeholder attribute not supported)
			getActualVal: function(el) {
				el = $(el);
				if(el.val() === el.attr("placeholder")) {
					return "";
				}
				return el.val();
			},

			//public function to get a paremeter by name from URL
			getURLParameter: function(paramName) {
				var searchString = window.location.search.substring(1),
					i, val, params = searchString.split("&");

				for(i = 0; i < params.length; i++) {
					val = params[i].split("=");
					if(val[0] == paramName) {
						return unescape(val[1]);
					}
				}
				return null;
			},

			// check for device touch support
			isTouchDevice: function() {
				try {
					document.createEvent("TouchEvent");
					return true;
				} catch(e) {
					return false;
				}
			},

			// To get the correct viewport width based on  http://andylangton.co.uk/articles/javascript/get-viewport-size-javascript/
			getViewPort: function() {
				var e = window,
					a = 'inner';
				if(!('innerWidth' in window)) {
					a = 'client';
					e = document.documentElement || document.body;
				}

				return {
					width: e[a + 'Width'],
					height: e[a + 'Height']
				};
			},

			getUniqueID: function(prefix) {
				return 'prefix_' + Math.floor(Math.random() * (new Date()).getTime());
			},

			// check IE8 mode
			isIE8: function() {
				return isIE8;
			},

			// check IE9 mode
			isIE9: function() {
				return isIE9;
			},

			//check RTL mode
			isRTL: function() {
				return isRTL;
			},

			// check IE8 mode
			isAngularJsApp: function() {
				return(typeof angular == 'undefined') ? false : true;
			},

			getAssetsPath: function() {
				return assetsPath;
			},

			setAssetsPath: function(path) {
				assetsPath = path;
			},

			setGlobalImgPath: function(path) {
				globalImgPath = path;
			},

			getGlobalImgPath: function() {
				return assetsPath + globalImgPath;
			},

			// get layout color code by color name
			getBrandColor: function(name) {
				if(brandColors[name]) {
					return brandColors[name];
				} else {
					return '';
				}
			},

			getResponsiveBreakpoint: function(size) {
				// bootstrap responsive breakpoints
				var sizes = {
					'xs': 480, // extra small
					'sm': 768, // small
					'md': 992, // medium
					'lg': 1200 // large
				};

				return sizes[size] ? sizes[size] : 0;
			},

			toastr: function(type, title, message, _option) {
				require(['toastr'], function(toastr) {
					var options = {
						"closeButton": true,
						"debug": false,
						"positionClass": "toast-top-center",
						"onclick": null,
						"showDuration": "800",
						"hideDuration": "500",
						"timeOut": "1000",
						"extendedTimeOut": "1000", //用户鼠标over时多久自动消失
						"showEasing": "swing",
						"hideEasing": "linear",
						"showMethod": "fadeIn",
						"hideMethod": "fadeOut"
					}
					$.extend(true, options, _option);
					toastr.options = options
					switch(type) {
						case 'success':
							toastr.success(message, title)
							break
						case 'warning':
							toastr.warning(message, title)
							break
						case 'info':
							toastr.info(message, title)
							break
						case 'error':
							toastr.error(message, title)
							break
					}
				})
			},

			form: {
				doAjaxSubmit: function(form) {
					var EVENT_COMPLETE = 'form-ajax-submit:done'

					var getActionUrl = function(form) {
						return form.attr('action')
					}

					var doSubmit = function(form) {
						var url = getActionUrl(form)
						var token = form.attr('data-token')
						var data = form.serialize()
						url = url + '?token=' + token
						$.ajax({
							type: "post",
							data: data,
							dataType: 'json',
							url: url,
							async: true,
							beforeSend: function(xhr, settings) {
								//do something 
								loadingButton(form, true)
							},

							error: function() {
								alert('请求失败,请重试')
								location.reload()
							},

						}).done(function(resObj) {
							//将按钮设置为非加载状态
							loadingButton(form, false)
							form.trigger(EVENT_COMPLETE, {
								'resObj': resObj
							})
						});
					}

					var loadingButton = function(form, loading) {
						var submitButton = form.find('button[type="submit"]')
						submitButton.prop('disabled', loading === true)
						var test = loading ? '处理中...' : '提交保存'
						submitButton.text(test)
						//						App.loadingButtion(submitButton, loading)
					}

					doSubmit(form)
				},

				validate: function(page, config) {
					require(['jqueryValidate'], function() {
						addMethod()
						for(var form_id in config) {
							var _option = config[form_id]
							var form = $('#' + form_id)
							if(form.length < 1) {
								return false
							}
							var option = getOption(_option)
							form.validate(option)
							//将默认的ajax submit监听去掉
							form.attr('data-igonre', 'ignore')
							//将submit按钮enable
							form.find('button').prop('disabled', false)

							//自动增加submit的result
							initResultSumit(page, config)
						}
					})

					var getOption = function(_option) {
						var option = {
							debug: false,
							onsubmit: true, //当点击submit时进行验证
							onfocusout: function(element) {
								$(element).valid();
							},
							//对应input元素，当失去焦点时进行验证
							onkeyup: function(element) {
								$(element).valid();
							},
							//当键盘按键按下
							onclick: false,
							focusInvalid: false, // 
							focusCleanup: true, // clean error on focus
							errorElement: 'span', //default input error message container
							errorClass: 'help-block help-block-error', // default input error message class
							ignore: ":hidden", // validate all fields including form hidden input

							invalidHandler: function(event, validator) { //display error alert on form submit
								//当验证错误时触发此动作
							},

							highlight: function(element) { // hightlight error inputs
								$(element).closest('.form-group').removeClass("has-success").addClass('has-error'); // set error class to the control group   
							},

							unhighlight: function(element) { // revert the change done by hightlight
								$(element).closest('.form-group').removeClass('has-error');
							},

							success: function(label, element) {},

							submitHandler: function(form) {
								App.form.doAjaxSubmit($(form))
							}
						}

						$.extend(true, option, _option)
						return option
					}

					var initResultSumit = function(page, config) {
						App.form.submitResult(page, config)
					}

					var addMethod = function() {
						
						jQuery.validator.addMethod("phone", function(value, element, param) {
							var reg = /^[1][3,4,5,7,8][0-9]{9}$/
							var res = reg.test(value)
							return this.optional(element) || res;
						}, $.validator.format("请输入正确手机号"));
						
						jQuery.validator.addMethod("username", function(value, element, param) {
							var reg = /^[a-zA-Z]{1}([a-zA-Z0-9]|[._-]){3,19}$/
							var res = reg.test(value)
							return this.optional(element) || res;
						}, $.validator.format("字母开头，可包含（._-），4~20个字符"));
						
						jQuery.validator.addMethod("myPassword", function(value, element, param) {
							var reg = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[^]{6,100}$/
							var res = reg.test(value)
							return this.optional(element) || res;
						}, $.validator.format("至少6个字符，至少1个大写字母，1个小写字母和1个数字"));
						
						jQuery.validator.addMethod("differ", function(value, element, param) {
							var selector = param
							var _value = $(selector).val()
							return this.optional(element) || _value != value;
						}, $.validator.format("输入内容必须和{0}不同"));
						
					}
				},

				submitResult: function(page, config) {
					var isModal = function(page) {
						return page.hasClass('modal')
					}

					var doResult = function(resObj, _config, form) {
						var success = resObj['success']
						var callback
						if(success) {
							callback = _config['resultSuccess']
						} else {
							callback = _config['resultError']
						}
						if($.isFunction(callback)) {
							callback(resObj, form)
							return
						}
					}

					$('body').on('form-ajax-submit:done', 'form', function(e) {
						var resObj = arguments[1]['resObj']
						var form = $(e.currentTarget)
						var form_id = form.attr('id')
						var _config = config[form_id]
						if(_config) {
							doResult(resObj, _config, form)
						} else {
							console.log('ERROR 未找到表单提交之后执行的配置文件信息，请确保已配置')
						}
						return
					})
				},

				getSerialize: function(form) {
					var queryArray = form.serializeArray()
					var queryStr = []
					$.each(queryArray, function(k, query) {
						var name = query['name']
						var value = query['value']
						if(value) {
							queryStr.push(name + '=' + value)
						}
					});
					queryStr = queryStr.join('&')
					return queryStr
				},
			},

			table: {
				container: undefined,
				url: undefined,
				init: function(page, config) {
					App.table.container = config['container']
					App.table.url = config['url']
					App.table.doAjax()
					App.table.initPaginator()
					App.table.initSearch()
				},

				reInit: function() {
					App.table.doAjax()
				},

				doAjax: function(page, query, countPerPage) {
					var url = App.table.url
					var container = App.table.container
					var page = page ? page : 1;
					var data = {
						page: page,
						countPerPage: countPerPage,
					}
					url = query ? url + '?' + query : url
					$.ajax({
						type: "post",
						url: url,
						async: true,
						data: data,
						error: function() {
							alert('服务器错误')
						},
						beforeSend: function() {
							App.pageLoaging.start()
						},
					}).done(function(tableHtml) {
						App.pageLoaging.stop()
						var table = container.find('table')
						var _this = $(tableHtml)
						var tbody = _this.find('tbody')
						var pagination = _this.filter('.pagination-control')
						var itemsCount = parseInt(tbody.attr('data-item-count'))
						table.find('tbody').replaceWith(tbody)

						/*如果没有查询到数据*/
						if(itemsCount < 1) {
							table.next().remove()
							container.append("<p class='text-center'>没有查询到数据！</p>")
							return
						}

						//paginator
						var paginatorContainer = container.find('.pagination-control')
						paginatorContainer.children().remove()
						paginatorContainer.append(pagination)
					})
				},

				initPaginator: function() {
					var container = App.table.container
					container.on('click', '.pagination-control ul.control-page a', function() {
						var _a = $(this)
						var page = _a.attr('data-page')
						var countPerPage = $('.pagination-control span.count-per-page').text()
						if(_a.parent().hasClass('disabled')) return
						var form = container.find('from')
						var query = App.form.getSerialize(form)
						App.table.doAjax(page, query, countPerPage)
					})

					container.on('click', '.pagination-control ul.control-count-per-page a', function() {
						var _a = $(this)
						var countPerPage = _a.attr('data-conut-per-page')
						var currtenCountPerPage = $('.pagination-control span.count-per-page').text()
						if(countPerPage == currtenCountPerPage) return
						var form = container.find('from')
						var query = App.form.getSerialize(form)
						App.table.doAjax(1, query, countPerPage)
					})
				},

				initSearch: function() {
					var container = App.table.container
					container.on('submit', 'form', function(e) {
						e.preventDefault()
						var form = $(this)
						var query = App.form.getSerialize(form)
						if(query.length < 1) return
						App.table.doAjax(1, query)
						//enable reset button
						var resetButton = container.find('button[type="reset"]')
						resetButton.prop('disabled', false)
						console.log(resetButton)
					})
					container.on('reset', 'form', function(e) {
						App.table.doAjax(1)
						var resetButton = container.find('button[type="reset"]')
						resetButton.prop('disabled', true)
					})
				},
			},

			editable: function(page, config) {
				require(['editable'], function() {
					if(typeof config === 'undefined') {
						return
					}
					var getOption = function(_option) {
						var option = {
							mode: 'inline',
							emptytext: '未设置',
							success: function(response, newValue) {
								var success = response.success
								if(success !== true) {
									return response.msg
								}
							},
							error: function(response, newValue) {
								if(response.status === 500) {
									return '服务器错误';
								} else {
									return response.responseText;
								}
							},

							validate: function(value) {
								if($.trim(value) == '') {
									return '不可为空';
								}
							},
						}

						$.extend(true, option, _option);

						return option
					}
					for(var id in config) {
						var _dom = page.find('#' + id)
						var _option = config[id]
						var option = getOption(_option)
						_dom.editable(option)
					}
				})
			},

			upload: {
				/**
				 * 通过Dropzone的方式上传文件
				 * 
				 * @param {Object} config
				 */
				dropzone: function(config) {
					require(['dropzone'], function() {
						for(var key in config) {
							var _config = config[key]
							var target = _config['target']
							var option = _config['option']
							var _option = getOption(option);
							target.dropzone(_option)
						}
					})

					var getOption = function(option) {
						var success = option['success']
						delete option['success']
						var _option = {
							addRemoveLinks: true,
							thumbnailWidth: 120, //预览图片宽度
							thumbnailHeight: null,
							//翻译
							dictDefaultMessage: '点击上传文件',
							dictFallbackText: '点击上传文件',
							dictFileTooBig: '文件大小不能超过{{maxFilesize}}M',
							dictRemoveFile: '删除',
							dictCancelUpload: '取消上传',
							dictMaxFilesExceeded: '仅可上传{{maxFiles}}个文件',
							dictInvalidFileType: '文件类型错误',
							//add event
							init: function() {
								this.on("error", function(file, msg) {
									App.toastr('error', msg)
									this.removeFile(file)
								})
								this.on("sending", function(file, res) {
									App.pageLoaging.start()
								})
								this.on("success", function(file, res) {
									if($.isFunction(success)) {
										App.pageLoaging.stop()
										success(file, res)
									}
								})
							},
						}

						$.extend(true, _option, option);

						return _option
					}
				},
			},

		} //end return

	})()
	return App
})