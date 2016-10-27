$(function($) {
	//删除数组中指定元素
	var certification = 1;
	//判断账号登陆
	$(document).ready(function($) {
		//判断授权
		$.ajax({
			url: total_url + 'index.php?g=Wap&m=Distribution&a=checkAuth',
			dataType: 'json',
			async:false,
			success: function(data) {
				if (data.data == 'needauth') {
					certification = 0;
					var href = 'http://bmy.tzwg.net/index.php?g=Wap&m=Distribution&a=authorization&bmyquth=1&href='+window.location.href;
					location.href = href;
				}else{
					//判断账号登陆
					$.ajax({
						url: total_url + 'index.php?g=Wap&m=Distribution&a=checkLogin',
						dataType: 'json',
						async:false,
						success: function(data) {
							if (!data || data.status == -1) {
								certification = 0;
								location.href = "login.html";
							}
						}
					});
				}
			}
		});
	});
	if(certification == 0){
		return false;
	}

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
			// 	$.router.load('ChoosePosition.html');
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
			$service.on('click', function() {
				var picIndex = $(this).index() + 1;
				if (!$(this).hasClass('on')) {
					return false;
				}
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
				var data = eval('(' + data + ')');
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
		var pid_arr = {};
		var store_product = [];
		$.each(json.data, function(index, item) {
			str += '<ul class="p0">'
			str += '<li class="product_cat">' + item.name + '</li>'
			if (item.products[0].length != 0) {
				$.each(item.products[0], function(index2, item2) {
					store_product[item2.id] = item2;
					str += '<li class="product_item" data-pid="' + item2.id + '">';
					str += '    <label class="label-checkbox item-content">';
					str += '        <div class="wg_item_pro_pic"><img src="' + item2.logourl + '"></div>';
					str += '        <div class="item-inner">';
					str += '            <div class="item-title-row">';
					str += '                <div class="item-title">' + item2.name + '</div>';
					str += '                <div class="item-after total-color">￥' + item2.price + '</div>';
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
		$(document).find('#reservation-info-btn').off('click').on('click', function() {
			var info = $('#reservation-info');
			info.show();
		})
		$(document).find('#reservation-info .close').off('click').on('click', function() {
				var info = $('#reservation-info');
				info.hide();
			})
			//选择商品
		$(document).find('.product_item').off('click').on('click', function(event) {
			event.preventDefault();
			event.stopPropagation();
			var pid = $(this).data('pid');
			if (!$(this).hasClass('active')) {
				$(this).addClass('active');
				pid_arr[pid] = {
					'pid': pid,
					'name': store_product[pid].name,
					'price': store_product[pid].price,
					'type': store_product[pid].type,
					'colorinfo': store_product[pid].colorinfo,
					'artinfo': store_product[pid].artinfo,
					'artex': store_product[pid].artex,
					'wmprice': store_product[pid].wmprice,
				};
			} else {
				$(this).removeClass('active');
				delete(pid_arr[pid]);
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
			var wrap = $('#select_products_info_wrap');
			var pinfo = cookie.get('choose_product');
			var wrap = $('#choose_products_info');
			var str = '';
			var total_price = 0;
			var colorinfo;
			var artinfo;
			var artex;
			var colorname = '';
			pinfo = eval('(' + pinfo + ')');
			$.each(pinfo, function(index, el) {
				//一般照
				if (el.type == 0) {
					str += '<div class="item-inner"><div class="item-title-row"><div class="item-title wg_order_choose_title">' + el.name + '</div><div class="item-after"><span>合计：</span>￥<span class="item-after-money">' + el.price + '</span></div></div></div>';
					total_price += parseInt(el.price);
				}
				//证件照
				if (el.type == 1) {
					str += '<div class="item-inner" data-pid="' + el.pid + '">';
					str += '    <div class="item-title-row" style="border-bottom: 1px solid #e7e7e7;">';
					str += '        <div class="item-title wg_order_choose_title">' + el.name + '</div>';
					str += '    </div>';
					str += '    <div class="item-title-row">';
					str += '        <div class="item-title">背景颜色</div>';
					str += '        <div class="item-after iconfont choose-meal" data-choose="color">背景颜色说明&#xe60d;</div>';
					str += '    </div>';
					colorinfo = eval('(' + el.colorinfo + ')');
					str += '    <div class="item-title-row">';
					$.each(colorinfo, function(index2, el2) {
						switch (index2) {
							case 'blue':
								colorname = '蓝色';
								break;
							case 'white':
								colorname = '白色';
								break;
							case 'red':
								colorname = '红色';
								break;
							case 'yellow':
								colorname = '芽黄';
								break;
							case 'grey':
								colorname = '灰色';
								break;
						}
						if (index2 != 'blue') {
							str += '<div class="item-title wg_order_choose_color card-product-color card-product-' + index2 + '" data-price="' + el2.price + '" data-choose="' + index2 + '">' + colorname + '</div>';
						} else {
							str += '<div class="item-title wg_order_choose_color card-product-' + index2 + '" data-price="' + el2.price + '" data-choose="' + index2 + '">' + colorname + '</div>';
						}
					});
					str += '    </div>';
					str += '    <div class="item-title-row">';
					str += '        <div class="item-title"></div>';
					str += '        <div class="item-after">';
					str += '            <span>合计:</span>￥';
					str += '            <span class="item-after-money product-price">' + colorinfo.blue.price + '</span>';
					//默认选择蓝色
					pinfo[index]['choose'] = '{"blue":{"price":' + colorinfo.blue.price + '}}';
					total_price += parseInt(colorinfo.blue.price);
					str += '        </div>';
					str += '    </div>';
					str += '</div>';
				}
				//文艺照
				if (el.type == 2) {
					str += '<div class="item-inner" data-pid="' + el.pid + '">';
					str += '    <div class="item-title-row bb_1_s">';
					str += '        <div class="item-title wg_order_choose_title">' + el.name + '</div>';
					str += '    </div>';
					str += '    <div class="item-title-row">';
					str += '        <div class="item-title">选择类型</div>';
					str += '    </div>';
					str += '    <div class="item-title-row bb_1_s_pb_5 extent-flex-start">';
					artinfo = eval('(' + el.artinfo + ')');
					$.each(artinfo, function(index2, el2) {
						switch (index2) {
							case 'personal':
								artname = '单人';
								break;
							case 'friends':
								artname = '闺蜜';
								break;
							case 'childrens':
								artname = '亲子';
								break;
							case 'lovers':
								artname = '情侣';
								break;
						}
						if (index2 != 'personal') {
							str += '<div class="item-title  wg_order_choose lh25 extend-mr7 choose-art-type" data-price="' + el2.price + '" data-choose="' + index2 + '">' + artname + '</div>';
						} else {
							str += '<div class="item-title  wg_order_choose lh25 extend-mr7 choose-active choose-art-type" data-price="' + el2.price + '" data-choose="' + index2 + '">' + artname + '</div>';
						}
					});
					//默认选择单人
					pinfo[index]['choose'] = '{"personal":{"price":' + artinfo.personal.price + '}}';
					total_price += parseInt(artinfo.personal.price);

					str += '    </div>';
					// str += '    <div class="item-title-row bb_1_s">';
					// str += '        <div class="item-title">闺蜜拍摄人数</div>';
					// str += '        <div class="item-after">';
					// str += '            <div class="product-photo-reduce">-</div>';
					// str += '            <div class="product-photo-num">1</div>';
					// str += '            <div class="product-photo-add">+</div>';
					// str += '        </div>';
					// str += '    </div>';
					str += '    <div class="item-title-row">';
					str += '        <div class="item-title">升级体验</div>';
					str += '        <div class="item-after iconfont choose-meal" data-choose="grid">升级体验说明&#xe60d;</div>';
					str += '    </div>';
					artex = eval('(' + el.artex + ')');
					str += '    <div class="item-title-row bb_1_s_pb_5 extent-flex-start">';
					str += '        <div class="item-title  wg_order_choose lh25 extend-mr7 choose-art-gongge" data-price="' + artex.four.price + '"  data-choose="four">四宫格</div>';
					str += '        <div class="item-title  wg_order_choose lh25 extend-mr7 choose-art-gongge" data-price="' + artex.nine.price + '"  data-choose="nine">九宫格</div>';
					//记录选择宫格
					str += '    </div>';
					str += '    <div class="item-title-row">';
					str += '        <div class="item-title"></div>';
					str += '        <div class="item-after">';
					str += '            <span>合计：</span>￥';
					str += '            <span class="item-after-money product-price">' + artinfo.personal.price + '</span>';
					str += '        </div>';
					str += '    </div>';
					str += '</div>';
				}
				//结婚照
				if (el.type == 3) {
					str += '<div class="item-inner" data-pid="' + el.pid + '">';
					str += '	<div class="item-title-row bb_1_s">';
					str += '		<div class="item-title wg_order_choose_title">' + el.name + '</div>';
					str += '	</div>';
					str += '	<div class="item-title-row">';
					str += '		<div class="item-title">升级体验</div>';
					str += '		<div class="item-after iconfont choose-meal" data-choose="whimsy">升级体验说明&#xe60d;</div>';
					str += '	</div>';
					str += '	<div class="item-title-row">';
					str += '		<div class="item-title  wg_order_choose choose-whimsy" data-price="' + el.wmprice + '">搞怪结婚照</div>';
					str += '	</div>';
					str += '	<div class="item-title-row">';
					str += '		<div class="item-title"></div>';
					str += '		<div class="item-after">';
					str += '			<span>合计：</span>￥';
					str += '			<span class="item-after-money product-price">' + el.price + '</span>';
					str += '		</div>';
					str += '	</div>';
					str += '</div>';
					total_price += parseInt(el.price);
				}
			});
			$('#total_price').text(total_price);
			wrap.html(str);
			$(document).on('click','#select_products_info_wrap .choose-meal',function(){
				var type = $(this).data('choose');
				str = '';
				switch(type){
					case 'color':
						img_src = total_url+'tpl/Wap/default/common/original/img/mael-color.png';
						break;
					case 'grid':
						img_src = total_url+'tpl/Wap/default/common/original/img/meal-grid.png';
						break;
					case 'whimsy':
						img_src = total_url+'tpl/Wap/default/common/original/img/meal-marry.png';
						break;
				}
				str += '<div class="meal-choose">';
		        str +=     '<img src="'+img_src+'" alt="">';
		        str +=     '<div class="close-mask iconfont"></div>';
		        str += '</div>';
				wrap.append(CreateMask(str));
			})
			//选择样色
			$(document).on('click', '.wg_order_choose_color', function() {
					var product_price = parseInt($(this).parents('.item-inner').find('.product-price').text());
					var choose_price = parseInt($(this).data('price'));
					var choose_key = $(this).data('choose');
					var pid = $(this).parents('.item-inner').data('pid');
					//记录选择颜色
					var choose_color = eval('(' + pinfo[pid]['choose'] + ')');

					if ($(this).hasClass('card-product-color')) {
						$(this).removeClass('card-product-color');
						product_price += choose_price;
						total_price += choose_price;
						//在选中的产品中记录颜色选择
						if ($.type(choose_color[choose_key]) == 'undefined') {
							choose_color[choose_key] = {
								"price": choose_price
							};
						}
					} else {
						if (product_price != choose_price) {
							$(this).addClass('card-product-color');
							product_price -= choose_price;
							total_price -= choose_price;
							//在选中的产品中删除颜色选择
							delete(choose_color[choose_key]);
						}

					}
					//在选中的产品中记录颜色选择
					pinfo[pid]['choose'] = JSON.stringify(choose_color);
					$('#total_price').text(total_price);
					$(this).parents('.item-inner').find('.product-price').text(product_price);
				})
				//选择文艺照类型
			$(document).on('click', '#choose_products_info .choose-art-type', function() {
					var product_price_item = $(this).parents('.item-inner').find('.product-price');
					var product_price = parseInt(product_price_item.text());
					var choose_price = parseInt($(this).data('price'));
					var pid = $(this).parents('.item-inner').data('pid');
					var choose_name = $(this).data('choose');
					//记录选择单人
					var choose_art = eval('(' + pinfo[pid]['choose'] + ')');
					//宫格价格
					var chooseartex = pinfo[pid]['chooseartex'];
					var choose_artex = eval('(' + pinfo[pid]['chooseartex'] + ')');
					var choose_artex_price = 0;
					if ($.type(chooseartex) != 'undefined') {
						if (chooseartex.indexOf('four') != -1) {
							choose_artex_price += choose_artex['four']['price'];
						}
						if (chooseartex.indexOf('nine') != -1) {
							choose_artex_price += choose_artex['nine']['price'];
						}
					}

					//选择类型
					if ($(this).hasClass('choose-active')) {
						//最后一个还不能点击
						if (product_price != choose_price) {
							$(this).removeClass('choose-active');
							product_price -= choose_price + choose_artex_price;
							total_price -= choose_price + choose_artex_price;
							delete(choose_art[choose_name]);
						}
					} else {
						$(this).addClass('choose-active');
						if ($.type(choose_art[choose_name]) == 'undefined') {
							choose_art[choose_name] = {
								"price": choose_price
							};
						}
						product_price += choose_price + choose_artex_price;
						total_price += choose_price + choose_artex_price;
					}
					pinfo[pid]['choose'] = JSON.stringify(choose_art);
					$('#total_price').text(total_price);
					product_price_item.text(product_price);
				})
				//选择文艺照宫格
			$(document).on('click', '#choose_products_info .choose-art-gongge', function() {
					var product_price_item = $(this).parents('.item-inner').find('.product-price');
					var product_price = parseInt(product_price_item.text());
					var choose_price = parseInt($(this).data('price'));
					var choose_key = $(this).data('choose');
					var pid = $(this).parents('.item-inner').data('pid');
					var choose_artex = {};
					var choose_art = eval('(' + pinfo[pid]['choose'] + ')');
					var arr = Object.keys(choose_art)
					var type_num = arr.length;
					if ($.type(pinfo[pid]['chooseartex']) != 'undefined') {
						choose_artex = eval('(' + pinfo[pid]['chooseartex'] + ')');
					}
					//选择类型
					if ($(this).hasClass('choose-active')) {
						$(this).removeClass('choose-active');
						product_price -= choose_price * type_num;
						total_price -= choose_price * type_num;
						delete(choose_artex[choose_key]);
					} else {
						$(this).addClass('choose-active');
						choose_artex[choose_key] = {
							"price": choose_price
						};
						product_price += choose_price * type_num;
						total_price += choose_price * type_num;
					}
					pinfo[pid]['chooseartex'] = JSON.stringify(choose_artex);
					$('#total_price').text(total_price);
					product_price_item.text(product_price);
				})
				//选择搞怪结婚照
			$(document).on('click', '#choose_products_info .choose-whimsy', function() {
				var product_price_item = $(this).parents('.item-inner').find('.product-price');
				var product_price = parseInt(product_price_item.text());
				var choose_price = parseInt($(this).data('price'));
				var pid = $(this).parents('.item-inner').data('pid');
				//选择类型
				if ($(this).hasClass('choose-active')) {
					$(this).removeClass('choose-active');
					product_price -= choose_price;
					total_price -= choose_price;
					pinfo[pid]['choosew'] = false;
				} else {
					$(this).addClass('choose-active');
					product_price += choose_price;
					total_price += choose_price;
					pinfo[pid]['choosew'] = true;
				}
				$('#total_price').text(total_price);
				product_price_item.text(product_price);
			})

			//下一步
			$(this).find('#next_btn').click(function() {
				if (total_price != 0) {
					cookie.set('choose_product', JSON.stringify(pinfo));
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
		var pinfo = cookie.get('choose_product');
		if(!pinfo){
			$.router.load('myorders.html');
			return false;
		}
		var storeId = cookie.get('storeId');
		var storeName = cookie.get('storeName');
		var orderTime = cookie.get('orderTime')/1000;
		var orderTime2 = getLocalTime(orderTime);
		pinfo = eval('('+pinfo+')');
		var appoint_time_wrap = $('#appoint_time');
		//拍摄门店
		$('#appoint_place').text(storeName);
		//拍摄时间
		appoint_time_wrap.text(orderTime2);
		//拍摄内容
		
		var str = '';
		var attribute = '';
		var pay_price = 0;
		var total_price = 0;
		$.each(pinfo,function(index, el) {
			attribute = '';
			pay_price = 0;
			switch(el['type']){
				//证件照
				case '1':
					if($.type(el.choose) != 'undefined'){
						var choose_color = eval('('+el.choose+')');
						if(Object.keys(choose_color).length != 0){
							attribute +='(';
							$.each(choose_color,function(index2, el2) {
								switch(index2){
									case 'blue':
										attribute += '蓝色,';
										break;
									case 'white':
										attribute += '白色,';
										break;
									case 'red':
										attribute += '红色,';
										break;
									case 'yellow':
										attribute += '芽黄,';
										break;
									case 'grey':
										attribute += '灰色,';
										break;
								}
								pay_price += parseInt(el2.price);
							});
							attribute +=')';
						}
					}
					break;
				//艺术照
				case '2':
					if($.type(el.choose) != 'undefined'){
						var choose_art = eval('('+el.choose+')');
						var artex_price = 0;
						if($.type(el.chooseartex) != 'undefined'){
							var chooseartex = eval('('+el.chooseartex+')');
							if(Object.keys(chooseartex).length != 0){
								attribute +='(';
								$.each(chooseartex,function(index2, el2) {
									switch(index2){
										case 'four':
											attribute += '四宫格,';
											break;
										case 'nine':
											attribute += '九宫格,';
											break;
									}
									artex_price += parseInt(el2.price);
								});
								attribute +=')';
							}
						}
						if(Object.keys(choose_art).length != 0){
							attribute +='(';
							$.each(choose_art,function(index2, el2) {
								switch(index2){
									case 'childrens':
										attribute += '亲子,';
										break;
									case 'friends':
										attribute += '闺蜜,';
										break;
									case 'lovers':
										attribute += '情侣,';
										break;
									case 'personal':
										attribute += '个人,';
										break;
								}
								pay_price += parseInt(el2.price + artex_price);
							});
							attribute +=')';
						}
					}
					break;
				//结婚照
				case '3':
					pay_price += parseInt(el.price);
					if(el.choosew){
						attribute = '(搞怪结婚照)';
						pay_price += parseInt(el.wmprice);
					}
					break;
				default:
					pay_price += parseInt(el.price);
			}
			total_price += pay_price;

			pinfo[index]['total_price'] = pay_price;
			pinfo[index]['attribute'] = attribute;

			str += '<div class="item-title-row">';
			str += '    <div class="item-title color-grey1">'+el.name+attribute+'</div>';
			str += '    <div class="item-after wg_order_pay_money">￥'+pay_price+'</div>';
			str += '</div>';
		});
		//拍摄内容赋值
		$('#choose-reservation-content').html(str);
		//个人信息获取
		var psesonal_info;
		$.getJSON(total_url + 'index.php?g=Wap&m=Distribution&a=myInfo', function(json) {
			psesonal_info = json.data;
			$('#order-name').text(json.data.truename);//姓名
			$('#order-birth').text(json.data.birth);//出生
			$('#order-sex').text(json.data.sex == 0 ? '女' : '男');//性别
		})
		//总价
		$('#total-price').text(total_price);
		//去支付
		$('#pay-btn').click(function(){
			var pay_data = {};
			pay_data['sid'] = storeId;
			pay_data['ordertime'] = orderTime;
			pay_data['con'] = pinfo;
			pay_data['myinfo'] = psesonal_info;
			pay_data['totalPrice'] = total_price;
			pay_data['city'] = getCityName(cookie.get('scid'));
			$.ajax({
				url: total_url + 'index.php?g=Wap&m=Store&a=payOrder',
				data: {data:JSON.stringify(pay_data)},
				type: 'post',
				dataType: 'json',
				success:function(data){
					if(data.status == 1){
						cookie.set('choose_product','');
						var order_data = eval('('+data.data+')');
						location.href = total_url + 'index.php?g=Wap&m=Alipay&a=pay&token='+order_data.token+'&wecha_id='+order_data.wecha_id+'&success=1&from=Store&orderName='+order_data.orderid+'&single_orderid='+order_data.orderid+'&price'+order_data.price;
					}else{
						$.alert(data.info);
					}
				}
			});
		})
		//时间戳转日期格式
		function getLocalTime(nS) {     
		   return new Date(parseInt(nS) * 1000).toLocaleString().replace(/:\d{1,2}$/,' ');     
		}
	});
	//我的订单页面
	$(document).on('pageInit','#my-orders',function(){
		var psesonal_info;
		var all_wrap = $('#all-orders');
		var payed_wrap = $('#payed-orders');
		var finish_wrap = $('#finish-orders');
		$.getJSON(total_url + 'index.php?g=Wap&m=Distribution&a=myOrders', function(json) {
			console.log(json.data);
			$.each(json.data,function(index, el) {
				var str = '';
				var status = '';
				if(el.paid == 0){
					status = '未付款';
				}
				if(el.paid == 1){
					status = '进行中';
				}
				if(el.handled == 1){
					status = '已完成';
				}
				str += '<div class="list-block">';
				str += '    <ul>';
				str += '        <li class="item-content">';
				str += '            <div class="item-inner">';
				str += '                <div class="item-title">订单号：'+el.orderid+'</div>';
				str += '                <div class="item-after">'+status+'</div>';
				str += '            </div>';
				str += '        </li>';
				str += '        <li class="item-content">';
				str += '            <div class="item-inner">';
				str += '                <div class="item-title">';
				str += '                    <p>拍摄门店：<item class="store-name">'+el.sname+'</item></p>';
				str += '                    <p>预约时间：<item class="reservation-time">'+el.rtime+'</item></p>';
				str += '                    <p>门店号码：<item class="store-tele total-color">'+el.stel+'</item></p>';
				str += '                </div>';
				str += '            </div>';
				str += '        </li>';
				str += '        <li class="item-content1" >';
				str += '            <div class="item-inner1">';
				str += '                <div class="item-title"></div>';
				str += '                <div class="item-after1">总计：￥<span class="reservation-price total-color">'+el.price+'</span></div>';
				// str += '                <div class="item-after2 pay_choose">拍摄路线</div>';
				if(el.paid == 0){
					str += '<div class="item-after2 pay_unchoose pay-now" data-id="'+el.id+'">去付款</div>';
				}
				str += '            </div>';
				str += '        </li>';
				str += '    </ul>';
				str += '</div>';
				if(str){
					all_wrap.append(str);
					if(el.paid == 1 && el.finish == 0){
						payed_wrap.append(str);
					}
					if(el.paid == 1 && el.finish == 1){
						finish_wrap.append(str);
					}
				}
			});
			//立即支付
			$(document).on('click','#my-orders .pay-now',function(){
				var id = $(this).data('id');
				location.href = total_url + 'index.php?g=Wap&m=Store&a=payNow&id='+id;
			})
		})
	})
	$.init();
})