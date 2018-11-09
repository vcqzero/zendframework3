define(['jquery', 'moment', 'daterangepicker'], function($, moment, daterangepicker) {

	return {
		/**
		 * 
		 * @param {Object} pageName
		 */
		init: function(_input, _config) {
			if(typeof daterangepicker == 'undefined') {
				throw new Error('请先引入daterangepicker js')
			}
			var config = {
//				singleDatePicker: true,
				startDate: moment(),
				endDate: moment().add('6', 'days'),
				minDate: "01/01/2012",
				maxDate: "12/31/2099",
				dateLimit: {
					days: 60
				},
				showDropdowns: true,
				//					showWeekNumbers: true,
				timePicker: false,
				timePickerIncrement: 1,
				timePicker24Hour: true,
//				ranges: {
//					'今天': [moment(), moment()],
//					'昨天': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
//					'最近7天': [moment().subtract(6, 'days'), moment()],
//					'最近30天': [moment().subtract(29, 'days'), moment()],
//					'本月': [moment().startOf('month'), moment().endOf('month')],
//					'上月': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
//				},
				showCustomRangeLabel: false,
				alwaysShowCalendars: true,
				opens: 'right', //right left center
				buttonClasses: ['btn btn-default'],
				applyClass: 'btn-small btn-primary',
				cancelClass: 'btn-small',
				separator: ' to ',
				locale: {
					applyLabel: '确认',
					cancelLabel: '取消',
					fromLabel: 'From',
					toLabel: 'To',
					customRangeLabel: '自定义',
					daysOfWeek: ['日', '一', '二', '三', '四', '五', '六'],
					monthNames: ['一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月'],
					firstDay: 1,
					//						format: 'YYYY-MM-DD hh:mm:ss',
					format: 'YYYY-MM-DD',
				}
			}
			config = $.extend({}, config, _config)
			_input.daterangepicker(config)
			_input.on('apply.daterangepicker', function(ev, picker) {
				//do something, like clearing an input
				//				console.log(arguments)
			});
			console.log('myDatePicker-> init success')
		}
	}
})