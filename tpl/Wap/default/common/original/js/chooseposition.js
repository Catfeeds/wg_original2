$(function() {
	// 选择门店
	$(document).on('pageAnimationStart', '#reservation', function(e, id, page) {
		//初始化操作
		$('#reservation-city').html('');
	});



	$(document).on('pageInit', '#reservation', function(e, id, page) {
		var scid = cookie.get('scid'); // 选择的城市id
		var $city = $('#reservation-city');
		var storeId; // 当前门店id
		var cityHtml = '';
		$('#reservation .wrapper').css('margin-top', '1.75rem');
		$('.reservation-checkBtn').hide();
		$('.tipButton').on('click', function() {
			$(this).parents('.tipModelWrap').hide();
		});

		if (scid && (scid != 'null')) { //判断是否已经获得定位城市, 若未获得城市, 则跳到城市选择页面, 否则判断获取的城市是否有门店
			checkNoStore(scid); // 如果没有当前城市没有门店, 跳转到选择城市页面
		} else {
			geolocation(); // 定位请求及处理
		}

		// 城市选择
		$city.on('click', function() {
			$.router.load('ChoosePosition.html');
		});


		/**
		 * 定位请求,仅在移动端使用
		 */
		function geolocation() {
			if (navigator.geolocation) {
				navigator.geolocation.getCurrentPosition(getLocationCity, locationError);
			} else {
				$.router.load('ChoosePosition.html');
			}
		}


		/**
		 * 获取当前位置经纬度,通过百度地址逆解析服务获取当前城市
		 * @param  {[type]} position 经纬度信息处理
		 */
		function getLocationCity(position) {
			var longitude = position.coords.longitude;
			var latitude = position.coords.latitude;
			var point = new BMap.Point(longitude, latitude);
			var gc = new BMap.Geocoder();
			gc.getLocation(point, function(rs) {
				var addComp = rs.addressComponents;
				var cd = getCityCode(addComp.city); // 获得定位城市
				cookie.set('scid', cd,100); // 获取真实位置id，传值
				checkNoStore(cd);
			});
		}


		/**
		 * 定位失败时处理方法
		 * @param  {[type]} error 定位失败处理
		 */
		function locationError(error) {
			var msg = '';
			var _cityhtml = '';
			switch (error.code) {
				case 1:
					msg = '用户拒绝了位置服务';
					break;
				case 2:
					msg = '无法获取位置信息';
					break;
				case 3:
					msg = '获取信息超时';
					break;
				default:
					msg = 'code: ' + error.code + 'msg: ' + error.messgae;
			}
			_cityhtml = setTipText('暂无定位信息');
			$city.html(_cityhtml); // 处理定位错误
			$.router.load('ChoosePosition.html');
		}


		/**
		 * 设置当前城市
		 */
		function setCurrentCity() {
			var cityS = getCityName(cookie.get('scid'));
			var _cityHtml = setTipText(cityS.split('市')[0]);
			$city.html(_cityHtml); // 改变显示城市
			$('#reservation .wrapper').show(); //显示加载图
			showStoreInfo(cookie.get('scid')); // 展示店铺信息
		}


		// 设置tip条文字
		function setTipText(text) {
			var tipHtml = text + '<em class=\"icon icon-down\"></em>';
			return tipHtml;
		}


		/**
		 * 根据城市码判断是否已有门店
		 * @param  {String} citycode 城市码
		 */
		function checkNoStore(citycode) {
			var cityHaveStoreArr = [];
			var isUs;
			$.showIndicator();
			$.get(total_url + 'index.php?g=Wap&m=Store&a=getCityList', function(data) {
				$.hideIndicator();
				data = eval('(' + data + ')');
				if (data.error === 0) {
					var cityHaveStoreArr = [];
					var citylist = data.msg.city;
					for (var i in citylist) {
						for (var j in citylist[i]) {
							cityHaveStoreArr.push(citylist[i][j].SC_Id);
						}
					}
					isUs = $.inArray(citycode, cityHaveStoreArr); // 定位到的城市码是否在已有门店的城市码数组中
					if (isUs === -1) {
						$.router.load('ChoosePosition.html');
					} else {
						setCurrentCity();
					}
				}
			});
		}


		/**
		 * 根据百度逆解析获得的城市名得到城市码, 并更改cookie -> scid(当前选择城市)的值
		 * @param  {String} scname 城市名, 要求完整, 比如'杭州市'
		 * @return {Object}        设置cookie对象
		 */
		function getCityCode(scname) {
			var cityDic = {}; // 城市码数组
			var n, returnCookieStr;
			for (var i in cityCodeList) {
				cityDic[cityCodeList[i].cityname] = cityCodeList[i].citycode;
			}
			return cityDic[scname];
		}


		/**
		 * 店铺信息展示, 默认选择当前城市列表下第一家
		 */
		function showStoreInfo(cid) {
			$.showIndicator();
			$.get(total_url + 'index.php?g=Wap&m=Store&a=getStoreByCity&cid=' + cid, function(data) {
				data = eval('(' + data + ')');
				$.hideIndicator();
				if (data.error === 0) {
					var cityInfo = data.msg.city; // 当前选择城市
					var storeList = data.msg.storelist; // 当前选择城市下的门店列表
					var storeInfo = data.msg.storeinfo; // 门店详情
					var storeIdArr = [];
					var currentStoreArr = [];

					for (var i in storeList) {
						currentStoreArr.push(storeList[i].S_Name);
						storeIdArr.push(storeList[i].S_Id);
					}
					// 判断当前选择门店
					if (cookie.get('storeId')) {
						var storeIdTemp = cookie.get('storeId');
						if ($.inArray(storeIdTemp, storeIdArr) != -1) {
							storeId = storeIdTemp;
						} else {
							storeId = storeIdArr[0];
						}
					} else {
						storeId = storeIdArr[0];
					}
					changeStoreInfo(storeInfo, storeId); // 展示门店信息
					//门店选择
					$('#reservation-store').picker({
						toolbarTemplate: '<header class=\"bar bar-nav\">' +
							'<button class=\"button button-link pull-right close-picker\">确定</button>' +
							'<h1 class=\"title\">请选择门店</h1></header>',
						cols: [{
							textAlign: 'center',
							values: currentStoreArr
						}],
						onClose: function() {
							var _self = $('.picker-selected');
							var num = $.inArray(_self.html(), currentStoreArr);
							storeId = storeIdArr[num];
							changeStoreInfo(storeInfo, storeId);
							$('#reservation .wrapper').show();
						}
					});

					$('.reservation-checkBtn').on('click', function() {
						if ($(this).hasClass('btn-disabled')) {
							return false;
						}
						var storeName = $('#reservation-store .reservation-storename').html();
						cookie.set('storeId', storeId);
						cookie.set('storeName', storeName);
						$.router.load('SelectProduct.html');
					});
				} else {
					$.toast(data.msg);
				}
			});
		}


		/**
		 * 改变门店展示信息, 刚进入页面和改变门店时出发
		 */
		function changeStoreInfo(storeInfo, storeId) {
			var storeinfoHtml = '';
			var storeArr = Object.keys(storeInfo);
			var storeServiceArr = storeInfo[storeId].S_Service.split('');
			var $service = $('#reservation-storeService .reservation-serviceList'); //门店服务
			// var opents = parseInt(storeInfo[storeId].S_StartTime);
			var tsNow = parseInt(+new Date() / 1000); // 获取当前的时间戳
			var btnHtml = '选择套餐';
			$('.reservation-checkBtn').removeClass('btn-disabled');

			// if (opents && (tsNow < opents)) {
			// 	var _date = new Date(opents * 1000);
			// 	var _mnum = _date.getMonth();
			// 	var _dnum = _date.getDate();
			// 	var _hnum = _date.getHours();
			// 	var _minum = _date.getMinutes();
			// 	btnHtml = '将于' + (_mnum + 1) + '月' + _dnum + '日' + _hnum + ':' + (_minum < 10 ? '0' + _minum : _minum) + '开放预约,敬请期待';
			// 	$('.reservation-checkBtn').addClass('btn-disabled');
			// }

			$service.removeClass('on');
			for (var i in storeServiceArr) {
				if (storeServiceArr[i] === '1') {
					$($service[i]).addClass('on');
				}
			}
			//门店服务选择
			$service.on('click', function(index) {
				var icon_index = $(this).parents('.wg_service_icon').index();
				var picIndex = $(this).index() + 1;
				if (!$(this).hasClass('on')) {
					return false;
				}
				//选择显示本地服务图片
				$('#service-warn .service-img img').attr('src','common/original/img/service'+icon_index+'.png')
				$('#service-warn').show();
				$('#service-warn .close-warn').click(function(){
					$('#service-warn').hide();
				})
				// $('.tipModelWrap').find('img').attr('src', 'http://cdn.haimati.cn/HMA_M_Static/img/fwtc_' + picIndex + '.png');
				// setTimeout(function() {
				// 	$('.tipModelWrap').show();
				// }, 50);
			});
			$('#reservation-store .reservation-storename').text(storeInfo[storeId].S_Name); // 门店名
			$('#address-maplink').attr('href', storeInfo[storeId].S_MapUrl); // 地图链接
			$('#address-maplink .addressText').html(storeInfo[storeId].S_Address); // 地址
			$('#tele-wrap').attr('href', 'tel:' + storeInfo[storeId].S_TELE); // 电话
			$('#tele-wrap .tele').html(storeInfo[storeId].S_TELE); // 电话
			$('#reservation-store .reservation-storeNum').text(storeArr.length); // 门店数量
			$('.store_logo').attr('src', storeInfo[storeId].S_Photo);
			$('#reservation-storeInfo').show(); // 门店详情展示出现
			$('.reservation-checkBtn').html(btnHtml); // 门店开放预约时间
			$('.reservation-checkBtn').css('display', 'block'); // 按钮展示出现
		}
	});

	$(document).on('pageInit','#index-page',function(){

		var scid = cookie.get('scid');

		if (scid) {
			var _city = getCityName(scid);
			_city = _city + "";
			$('.index-position em').html(_city.split('市')[0]); // 处理定位错误
		} else {
			geolocation(); // 定位请求及处理
		}
		$('.index-position').on('click', function() {
			$.router.load('ChoosePosition.html'); // 前往选择城市页面
		});
		$('nav a').on('click', function() {
			var linkfrom = $(this).data('link');
			cookie.set('linkfrom', linkfrom);
			window.location.href = m_module + linkfrom;
		});
		/**
		 * 根据百度逆解析获得的城市名得到城市码,并更改cookie -> scid(当前选择城市)的值
		 * @param  {String} scname 城市名,要求完整,比如'杭州市'
		 * @return {Object}        设置cookie对象
		 */
		function getCityCode(scname) {
			var cityCodeArr = []; // 城市码数组
			var cityNameArr = []; // 城市名数组,两者一一对应
			var n, returnCookieStr;
			for (var i in cityCodeList) {
				cityCodeArr.push(cityCodeList[i].citycode);
				cityNameArr.push(cityCodeList[i].cityname);
			}
			n = $.inArray(scname, cityNameArr);
			return cityCodeArr[n];
		}
		/**
		 * 定位请求,仅在移动端使用
		 */
		function geolocation() {
			// $.router.load('ChoosePosition.html');
			if (navigator.geolocation) {
				navigator.geolocation.getCurrentPosition(getLocationCity, locationError);
			} else {
				// $('.index-position em').html('暂无定位信息'); // 处理定位错误
				$.router.load('ChoosePosition.html'); // 前往选择城市页面
			}
		}
		/**
		 * 获取当前位置经纬度,通过百度地址逆解析服务获取当前城市
		 * @param  {[type]} position 经纬度信息处理
		 */
		function getLocationCity(position) {
			var longitude = position.coords.longitude;
			var latitude = position.coords.latitude;
			var point = new BMap.Point(longitude, latitude);
			var gc = new BMap.Geocoder();
			gc.getLocation(point, function(rs) {
				var addComp = rs.addressComponents;
				var cd = getCityCode(addComp.city); // 获得定位城市
				var _city = getCityName(cd); // 获得城市名
				cookie.set('scid', cd,100); // 获取真实位置id，传值
				_city = _city + "";
				$('.index-position em').html(_city.split('市')[0]); // 左上角显示定位城市
			});
		}
		/**
		 * 定位失败时处理方法
		 * @param  {[type]} error 定位失败处理
		 */
		function locationError(error) {
			var msg = '';
			switch (error.code) {
				case 1:
					msg = '用户拒绝了位置服务';
					break;
				case 2:
					msg = '无法获取位置信息';
					break;
				case 3:
					msg = '获取信息超时';
					break;
				default:
					msg = 'code: ' + error.code + 'msg: ' + error.messgae;
			}
			$('.index-position em').html('暂无定位信息'); // 处理定位错误
			$.router.load('ChoosePosition.html'); // 前往选择城市页面
		}
	})


	// 选择城市
	$(document).on('pageAnimationStart', '#reservation-position', function(e, id, page) {
		//初始化操作
		$('.check-city-info b').html('');
	});



	$(document).on('pageInit', '#reservation-position', function(e, id, page) {
		var scid = cookie.get('scid');
		// 展示信息列表
		showCityList();


		/**
		 * 获取城市列表, 热门、全部、侧边导航
		 */
		function showCityList() {
			$.showIndicator();
			var cityHaveStoreArr = [];
			var isUs;
			$.get(total_url + 'index.php?g=Wap&m=Store&a=getCityList', function(data) {
				var data = eval('('+data+')');
				console.log(data);
				$.hideIndicator();
				if (data.error === 0) {
					var hotcity = data.msg.hotcity;
					var cityfirstchar = data.msg.cityfirstchar;
					var city = data.msg.city;
					var hotcityHtml = '';
					var cityHtml = '';
					var citySearchHtml = '';
					var cityHaveStoreArr = [];
					var scid = cookie.get('scid');
					var slideNavHandle = function() {
						var target = '#id-' + $(this).html();
						var oldTop = $('.content').scrollTop();
						var top = $(target).offset().top;
						top = top + oldTop;
						$('.content').scrollTop(top);
					};

					for (var k in hotcity) {
						hotcityHtml += '<div class=\"col-33\"><a class=\"city back\" data-scid=\"city-' +
							hotcity[k].SC_Id + '\" href=\"Appointment.html\">' +
							hotcity[k].SC_Name + '</a></div>';
					}
					$('#hotcity .row').html(hotcityHtml); // 插入热门城市表


					for (var i in city) {
						for (var j in city[i]) {
							cityHaveStoreArr.push(city[i][j].SC_Id);
						}
					}
					isUs = $.inArray(scid, cityHaveStoreArr); // 定位到的城市码是否在已有门店的城市码数组中
					if (scid) {
						if (isUs === -1) {
							var data_item = getCityName(scid) + "";
							$('.check-city-info b').html(data_item.split('市')[0] + ' 当前城市没有门店'); // top-tip显示当前选择城市
						} else {
							var data_item = getCityName(scid) + "";
							$('.check-city-info b').html(data_item.split('市')[0]); // top-tip显示当前选择城市
						}
					} else {
						$('.check-city-info b').html('无法获取当前位置');
					}

					for (var index in cityfirstchar) {
						var cityChar = cityfirstchar[index];
						var cityUnitHtml = '';
						citySearchHtml += '<a href=\"javascirpt:;\" class=\"external\">' + cityChar + '</a>';
						cityHtml += '<ul id=\"id-' + cityChar + '\"><h3>' + cityChar + '</h3>';
						for (var indexz in city[cityChar]) {
							cityUnitHtml += '<li><a class=\"city back\" data-scid=\"city-' +
								city[cityChar][indexz].SC_Id + '\" href=\"Appointment.html\">' +
								city[cityChar][indexz].SC_Name + '</a></li>';
						}
						cityHtml += cityUnitHtml + '</ul>';
					}
					$('#reservation-city').html(cityHtml); // 插入城市列表
					$('#city-searchlist').html(citySearchHtml); // 插入城市列表

					$('.external').on('click', slideNavHandle);
					$('.city').on('click', function() {
						cookie.set('scid', $(this).data('scid').split('-')[1], 100);
					});
				}
			});
		}
	});
	$.init();
});