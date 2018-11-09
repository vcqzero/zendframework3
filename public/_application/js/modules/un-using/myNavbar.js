define(['jquery'], function($) {

	/**
	 * To change this license header, choose License Headers in Project Properties.
	 * To change this template file, choose Tools | Templates
	 * and open the template in the editor.
	 */
	$(function() {
		var CURRENT_URL = window.location.href.split('#')[0].split('?')[0],
			$BODY = $('body'),
			$MENU_TOGGLE = $('#menu_toggle'),
			$SIDEBAR_MENU = $('#sidebar-menu'),
			$SIDEBAR_FOOTER = $('.sidebar-footer'),
			$LEFT_COL = $('.left_col'),
			$RIGHT_COL = $('.right_col'),
			$NAV_MENU = $('.nav_menu'),
			$FOOTER = $('footer'),
			$PAGE_NAVBAR = $('body').find('div.page').first().attr('data-narbar')
		var setContentHeight = function() {
			// reset height
			$RIGHT_COL.css('min-height', $(window).height());
			var bodyHeight = $BODY.outerHeight(),
				footerHeight = $BODY.hasClass('footer_fixed') ? -10 : $FOOTER.height(),
				leftColHeight = $LEFT_COL.eq(1).height() + $SIDEBAR_FOOTER.height(),
				contentHeight = bodyHeight < leftColHeight ? leftColHeight : bodyHeight;
			// normalize content
			//			contentHeight -= $NAV_MENU.height() + footerHeight;
			$RIGHT_COL.css('min-height', contentHeight);
		};

		$SIDEBAR_MENU.find('a').on('click', function(ev) {
			var $li = $(this).parent();

			if($li.is('.active')) {
				$li.removeClass('active active-sm');
				$('ul:first', $li).slideUp(function() {
					//					setContentHeight();
				});
			} else {
				// prevent closing menu if we are on child menu
				if(!$li.parent().is('.child_menu')) {
					$SIDEBAR_MENU.find('li').removeClass('active active-sm');
					$SIDEBAR_MENU.find('li ul').slideUp();
				} else {
					if($BODY.is(".nav-sm")) {
						$SIDEBAR_MENU.find("li").removeClass("active active-sm");
						$SIDEBAR_MENU.find("li ul").slideUp();
					}
				}
				$li.addClass('active');

				$('ul:first', $li).slideDown(function() {
					//					setContentHeight();
				});
			}
		});

		// toggle small or large menu 
		$MENU_TOGGLE.on('click', function() {
			console.log('clicked - menu toggle');

			if($BODY.hasClass('nav-md')) {
				$SIDEBAR_MENU.find('li.active ul').hide();
				$SIDEBAR_MENU.find('li.active').addClass('active-sm').removeClass('active');
			} else {
				$SIDEBAR_MENU.find('li.active-sm ul').show();
				$SIDEBAR_MENU.find('li.active-sm').addClass('active').removeClass('active-sm');
			}
			$BODY.toggleClass('nav-md nav-sm');
			setContentHeight();
		});

		// check active menu
		$SIDEBAR_MENU.find('a').filter(function() {
			var href = this.href
			if($PAGE_NAVBAR) {
				return(origin + $PAGE_NAVBAR) == href
			} else {
				return CURRENT_URL === href
			}
		}).parent('li').addClass('current-page').parents('ul').slideDown(function() {
			//			setContentHeight();
		}).parent().addClass('active');

	})
	
	//panel 中的收起按钮

	return {

	}
})