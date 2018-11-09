define(['jquery', 'GaodeMap', 'GaodeMapUi'], function($) {
	var init_map = function(_id) {
		var mapObj = new AMap.Map(_id, {
			zoom: 11, //级别
			layers: [ //只显示默认图层的时候，layers可以缺省
				new AMap.TileLayer(), //高德默认标准图层
			],
			//					center: [116.397428, 39.90923], //中心点坐标
			//					viewMode: '3D' //使用3D视图
		})
		return mapObj
	}

	var init_ui = function(mapObj) {
		AMapUI.loadUI(['control/BasicControl'], function(BasicControl) {
			//缩放控件
			mapObj.addControl(new BasicControl.Zoom({
				position: 'lt', //left top，左上角
				showZoomNum: true //显示zoom值
			}));

			//图层切换控件
			mapObj.addControl(new BasicControl.LayerSwitcher({
				position: 'rt' //right top，右上角
			}));

		})
	}

	var init_geolocation = function(mapObj) {

		mapObj.plugin('AMap.Geolocation', function() {
			var geolocation = new AMap.Geolocation({
				// 是否使用高精度定位，默认：true
				enableHighAccuracy: true,
				// 设置定位超时时间，默认：无穷大
				timeout: 10000,
				// 定位按钮的停靠位置的偏移量，默认：Pixel(10, 20)
				buttonOffset: new AMap.Pixel(10, 20),
				//  定位成功后调整地图视野范围使定位位置及精度范围视野内可见，默认：false
				zoomToAccuracy: true,
				//  定位按钮的排放位置,  RB表示右下
				buttonPosition: 'RB'
			})

			geolocation.getCurrentPosition()
			AMap.event.addListener(geolocation, 'complete', onComplete)
			AMap.event.addListener(geolocation, 'error', onError)

			function onComplete(data) {
				// data是具体的定位信息
			}

			function onError(data) {
				// 定位出错
				console.log(data)
			}
		})
	}

	var init_input_search = function(mapObj) {
		mapObj.plugin(['AMap.PlaceSearch'], function() {
			//自动输入框
//			var autoOptions = {
//				input: "tipinput"
//			};
//			var auto = new AMap.Autocomplete(autoOptions);
			
//			AMap.event.addListener(auto, "select", select); //注册监听，当选中某条记录时会触发
//			function select(e) {
//				placeSearch.setCity(e.poi.adcode);
//				placeSearch.search(e.poi.name); //关键字查询查询
//			}
			var placeSearch = new AMap.PlaceSearch({
				map: mapObj
			}); //构造地点查询类
			var _input = $('#tipinput')
			_input.on('change', function() {
				var _this = $(this)
				var val   = _this.val()
				placeSearch.search(val); //关键字查询查询
			})
		})
	}

	var init_satelliteLayer = function() {
		// 构造官方卫星、路网图层
		var satelliteLayer = new AMap.TileLayer.Satellite();
	}

	return {
		init: function(page, config) {
			var container_id = config['container_id']
			if(container_id == undefined) {
				return false;
			}
			var callback = config['callback']
			var mapObj = init_map(container_id)
			init_ui(mapObj)
			init_input_search(mapObj)
			mapObj.on("complete", function() {
				if($.isFunction(callback)) {
					callback(mapObj)
				}
				console.log("地图加载完成！");
			});
			return mapObj
		},
		
		/**
		 * 获得多边形对象的坐标，
		 * 二维数组，先是精度后是维度
		 * 
		 * @param {Object} polygonObj
		 * @return {Array}
		 */
		getPath: function(polygonObj) {
			var paths = polygonObj.getPath()
			var pathsAsArray = []
			for(var key in paths) {
				var path = paths[key]
				var pathAsArray = [path['lng'], path['lat']]
				pathsAsArray.push(pathAsArray)
			}
			return pathsAsArray
		}
	}
})