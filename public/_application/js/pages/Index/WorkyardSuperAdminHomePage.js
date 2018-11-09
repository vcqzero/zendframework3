define(
	['jquery', 'myGaodemap'],
	function($, myGaodemap) {
		var myGaodemapConfig = {
			container_id: 'map_container',
			callback: function() {
				var mapObj = arguments[0]
				var workyard_lis = $('#workyard_info').find('li')
				mapObj.plugin(['AMap.PolyEditor'], function() {
					$.each(workyard_lis, function(k, li) {
						var _this = $(this)
						var path  = _this.attr('data-address-path')
						path = JSON.parse(path)
						var polygon = new AMap.Polygon({
							map: mapObj,
							path: path,
							isOutline: true,
							borderWeight: 3,
							strokeColor: "#FF33FF",
							strokeWeight: 6,
							strokeOpacity: 0.2,
							fillOpacity: 0.4,
							// 线样式还支持 'dashed'
							fillColor: '#1791fc',
							zIndex: 50,
						})
						_this.data('polygon', polygon)
					})
					

					// 缩放地图到合适的视野级别
//					mapObj.setFitView([polygon])
				})
				workyard_lis.on('click', function() {
					var _this = $(this)
					var polygon = _this.data('polygon')
					mapObj.setFitView([polygon])
				})
				
			}
		}
		return {
			init: function(pageName, page) {
				myGaodemap.init(page, myGaodemapConfig)
			}
		}
	})