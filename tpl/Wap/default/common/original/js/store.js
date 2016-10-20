$(function($) {
	//判断账号登陆
	// $(document).ready(function($) {
	// 	$.ajax({
	// 		url: total_url + 'index.php?g=Wap&m=Distribution&a=checkLogin',
	// 		dataType: 'json',
	// 		success: function(data) {
	// 			if (data && data.status == -1) {
	// 				location.href = "login.html";
	// 			}
	// 		}
	// 	});
	// });
	
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
			$.router.load('ChoosePosition.html');
			// if (navigator.geolocation) {
			// 	navigator.geolocation.getCurrentPosition(getLocationCity, locationError);
			// } else {
			// 	$.router.load('/Index/reservationPosition1');
			// }
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
				cookie.set('scid', cd); // 获取真实位置id，传值
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
			console.log(cityS);
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
				data = eval('('+data+')');
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
				data = eval('('+data+')');
				console.log(data);
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
						console.log('aa');
						if ($(this).hasClass('btn-disabled')) {
							return false;
						}
						var storeName = $('#reservation-storeInfo .reservation-storename').html();
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
			// var storeServiceArr = storeInfo[storeId].S_Service.split('');
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

			$service.removeClass('off');
			// for (var i in storeServiceArr) {
			// 	if (storeServiceArr[i] === '0') {
			// 		$($service[i]).addClass('off');
			// 	}
			// }
			$service.on('click', function() {
				var picIndex = $(this).index() + 1;
				if ($(this).hasClass('off')) {
					return false;
				}
				$('.tipModelWrap').find('img').attr('src', 'http://cdn.haimati.cn/HMA_M_Static/img/fwtc_' + picIndex + '.png');
				setTimeout(function() {
					$('.tipModelWrap').show();
				}, 50);
			});
			$('#reservation-store .reservation-storename').text(storeInfo[storeId].S_Name); // 门店名
			$('#address-maplink').attr('href', storeInfo[storeId].S_MapUrl); // 地图链接
			$('#address-maplink .addressText').html(storeInfo[storeId].S_Address); // 地址
			$('#tele-wrap').attr('href','tel:'+storeInfo[storeId].S_TELE); // 电话
			$('#tele-wrap .tele').html(storeInfo[storeId].S_TELE); // 电话
			$('#reservation-store .reservation-storeNum').text(storeArr.length); // 门店数量
			$('.reservation-top img').attr('src', storeInfo[storeId].S_Photo);
			$('#reservation-storeInfo').show(); // 门店详情展示出现
			$('.reservation-checkBtn').html(btnHtml); // 门店开放预约时间
			$('.reservation-checkBtn').css('display', 'block'); // 按钮展示出现
		}
	});



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
	//获取产品分类和产品信息
	$(document).on('pageInit', '#select_product_wrap', function() {
		$.showPreloader();
		var storeId = cookie.get('storeId');
		cookie.set('choose_product', '');
		$.getJSON(total_url + 'index.php?g=Wap&m=Store&a=getCatsProducts&storeId=' + storeId, function(json) {
			CatsProductsHtml(json);
		})
		$.hidePreloader();
	});
	//拼接选择产品HTML
	function CatsProductsHtml(json) {
		var wrap = $('#cat_products_wrap');
		var str = '';
		var pid_arr = [];
		$.each(json.data, function(index, item) {
			str += '<ul>'
			str += '<li>' + item.name + '</li>'
			if (item.products[0].length != 0) {
				$.each(item.products[0], function(index2, item2) {
					str += '<li class="product_item" data-pid="' + item2.id + '" data-price="' + item2.price + '" data-name="' + item2.name + '" data-type="' + item2.type + '">';
					str += '    <label class="label-checkbox item-content">';
					str += '        <div class="wg_item_pro_pic"><img src="' + item2.logourl + '"></div>';
					str += '        <div class="item-inner">';
					str += '            <div class="item-title-row">';
					str += '                <div class="item-title">' + item2.name + '</div>';
					str += '                <div class="item-after">￥' + item2.price + '</div>';
					str += '            </div>';
					str += '        </div>';
					str += '        <input type="checkbox" name="my-radio">';
					str += '        <div class="item-media"><i class="icon icon-form-checkbox iconfont"></i></div>';
					str += '        <div class="clear"></div>';
					str += '    </label>';
					str += '</li>';
				});
			}
			str += '</ul>';
		})
		wrap.html(str);
		//预约须知
		$(document).find('#reservation-info-btn').off('click').on('click',function(){
			var info = $('#reservation-info');
			info.show();
		})
		$(document).find('#reservation-info .close').off('click').on('click',function(){
			var info = $('#reservation-info');
			info.hide();
		})
		//删除数组中指定元素
		Array.prototype.indexOf = function(val) {
            for (var i = 0; i < this.length; i++) {
                if (this[i] == val) return i;
            }
            return -1;
        };
        Array.prototype.remove = function(val) {
            var index = this.indexOf(val);
            if (index > -1) {
                this.splice(index, 1);
            }
        };
		//选择商品
		$(document).find('.product_item').off('click').on('click', function(event) {
			event.preventDefault();
			event.stopPropagation();
			var pid = $(this).data('pid');
			var pname = $(this).data('name');
			var pprice = $(this).data('price');
			var ptype = $(this).data('type');
			if(!$(this).hasClass('active')){
				$(this).addClass('active');
				pid_arr.push({
					'pid': pid,
					'name': pname,
					'price': pprice,
					'type': ptype,
				});
			}else{
				$(this).removeClass('active');
				$.each(pid_arr,function(index, el) {
					if(el.pid == pid){
						pid_arr.remove(el);
					}
				});
			}
			cookie.set('choose_product', JSON.stringify(pid_arr));
		})
	}
	//下一步
	$(document).on('click', '#finish_choose_product', function() {
			if (cookie.get('choose_product') != '') {
				$.router.load("SelectProductInfo.html");
			} else {
				$.alert('请选择产品');
			}
		})
		//选择产品信息页面
	$(document).on('pageInit', '#select_products_info_wrap', function() {
			var pinfo = cookie.get('choose_product');
			var wrap = $('#choose_products_info');
			var str = '';
			var total_price = 0;
			pinfo = eval('(' + pinfo + ')');
			$.each(pinfo, function(index, el) {
				if(el.type == 0){
					str += '<div class="item-inner"><div class="item-title-row"><div class="item-title wg_order_choose_title">'+el.name+'</div><div class="item-after"><span>合计：</span>￥<span class="item-after-money">'+el.price+'</span></div></div></div>';
				}
				if(el.type == 1){
					str += '<div class="item-inner">';
					str += '    <div class="item-title-row" style="border-bottom: 1px solid #e7e7e7;">';
					str += '        <div class="item-title wg_order_choose_title">'+el.name+'</div>';
					str += '        <div class="item-after"></div>';
					str += '    </div>';
					str += '    <div class="item-title-row">';
					str += '        <div class="item-title">背景颜色</div>';
					str += '        <div class="item-after iconfont">背景颜色说明&#xe60d;</div>';
					str += '    </div>';
					console.log(el);
					console.log(el.colorinfo);
					str += '    <div class="item-title-row">';
					str += '        <div class="item-title wg_order_choose_color card-product-color card-product-blue">蓝色</div>';
					str += '        <div class="item-title wg_order_choose_color card-product-color card-product-white">白色</div>';
					str += '        <div class="item-title wg_order_choose_color card-product-color card-product-red">红色</div>';
					str += '        <div class="item-title wg_order_choose_color card-product-color card-product-yellow">芽黄</div>';
					str += '        <div class="item-title wg_order_choose_color card-product-color card-product-gray">灰色</div>';
					str += '    </div>';
					str += '    <div class="item-title-row">';
					str += '        <div class="item-title"></div>';
					str += '        <div class="item-after">';
					str += '            <span>合计:</span>￥';
					str += '            <span class="item-after-money" class="product-price">'+el.price+'</span>';
					str += '        </div>';
					str += '    </div>';
					str += '</div>';
				}
				total_price += el.price;
			});
			$('#total_price').text(total_price);
			wrap.html(str);
			//下一步
			$(this).find('#next_btn').click(function() {
				if (total_price != 0) {
					$.router.load("ChooseDate.html");
				} else {
					$.alert('未选择商品');
				}
			})
		})
		//选择日期页面
	$(document).on('pageInit', '#choose_date_wrap', function() {
		var choose_date;
		if (cookie.get('choose_date') != '') {
			$(this).find("#choose_date").val(cookie.get('choose_date'));
			choose_date = cookie.get('choose_date');
		}
		$(this).find("#choose_date").change(function(event) {
			choose_date = $(this).val();
			cookie.set('choose_date', choose_date);
		});
		//获取工作时间
		$.ajax({
			url: total_url + 'index.php?g=Wap&m=Store&a=getWorkTime',
			dataType: 'json',
			data: {
				choose_date: choose_date
			},
			success: function(data) {
				var time_info_wrap = $('#time_info');
				var str = '';
				$.each(data.data, function(index, el) {
					str += '<span class="choose_time" data-time="' + el + '">' + el + '</span>';
				});
				time_info_wrap.html(str);
				$(document).find('.choose_time').off('click').on('click', function() {
					var choose_time = $(this).data('time');
					cookie.set('choose_time', choose_time);
					$.router.load("orders.html");
				})
			}
		});
	});
	//订单页面
	$(document).on('pageInit', '#orders_wrap', function() {
		var appoint_time_wrap = $('#appoint_time');
		appoint_time_wrap.text(cookie.get('choose_date') + cookie.get('choose_time'));
	});
	$.init();
})