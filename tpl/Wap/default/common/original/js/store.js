$(function($) {
	//删除数组中指定元素
	var certification = 1;
	//判断账号登陆
	$(document).ready(function($) {
		function onBridgeReady(){
		 WeixinJSBridge.call('hideOptionMenu');
		}

		if (typeof WeixinJSBridge == "undefined"){
		    if( document.addEventListener ){
		        document.addEventListener('WeixinJSBridgeReady', onBridgeReady, false);
		    }else if (document.attachEvent){
		        document.attachEvent('WeixinJSBridgeReady', onBridgeReady); 
		        document.attachEvent('onWeixinJSBridgeReady', onBridgeReady);
		    }
		}else{
		    onBridgeReady();
		}
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
		if(cookie.get('loginout') == 1){
			location.href = "login.html";
		}
	});
	if(certification == 0){
		return false;
	}

	//获取产品分类和产品信息
	$(document).on('pageInit', '#select_product_wrap', function() {
		if(cookie.get('reservationInoHide') == 1){
			$('#reservation-info').hide();
		}else{
			$('#reservation-info').show();
		}
		$.showPreloader();
		var storeId = cookie.get('storeId');
		cookie.set('choose_product', '');
		cookie.set('order_chang_rtime_id','');
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
		var cat_data = {};
		$.each(json.data, function(index, item) {
			str += '<ul class="p0">'
			str += '<li class="product_cat">' + item.name + '</li>'
			if (item.products[0].length != 0) {
				$.each(item.products[0], function(index2, item2) {
					store_product[item2.id] = item2;
					str += '<li class="product_item" data-pid="' + item2.id + '" data-pre="'+item.pre+'">';
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
			cat_data[item.id] = item.pref;
		})
		console.log(cat_data);
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
		$(document).find('#reservation-info .nowarn').off('click').on('click', function() {
				cookie.set('reservationInoHide',1);
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
					'catid': store_product[pid].catid,
					'name': store_product[pid].name,
					'price': store_product[pid].price,
					'type': store_product[pid].type,
					'colorinfo': store_product[pid].colorinfo,
					'artinfo': store_product[pid].artinfo,
					'artex': store_product[pid].artex,
					'wmprice': store_product[pid].wmprice,
					'intro': store_product[pid].intro,
				};
			} else {
				$(this).removeClass('active');
				delete(pid_arr[pid]);
			}
			//存储优惠数据
			
			cookie.set('choose_product', JSON.stringify(pid_arr));
			cookie.set('cat_data', JSON.stringify(cat_data));
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
	$(document).on('pageAnimationStart','#select_products_info_wrap',function(){
		$('#choose_products_info').html('');
	})
	$(document).on('pageInit', '#select_products_info_wrap', function() {
			// var wrap = $('#select_products_info_wrap');
			var pinfo = cookie.get('choose_product');
			var catinfo = cookie.get('cat_data');
			var wrap = $('#choose_products_info');
			var str = '';
			var total_price = 0;
			var colorinfo;
			var artinfo;
			var artex;
			var colorname = '';
			pinfo = eval('(' + pinfo + ')');
			catinfo = eval('(' + catinfo + ')');
			console.log(catinfo);
			var total_pref = 0;
			//优惠总价
			$.each(catinfo,function(index,el){
				total_pref -= el;
			})
			$.each(pinfo, function(index, el) {
				//计算优惠
				if($.type(catinfo[el['catid']]) != 'undefined'){
					total_pref += parseInt(catinfo[el['catid']]);
				}

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
							str += '<div class="item-title wg_order_choose_color card-product-color test card-product-' + index2 + '" data-price="' + el2.price + '" data-choose="' + index2 + '">' + colorname + '</div>';
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
					var default_key = '';
					var default_price = 0;
					$.each(artinfo, function(index2, el2) {
						if(default_key === ''){
							default_price = el2.price;
							default_key = index2;
						}
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
						if (index2 != default_key) {
							str += '<div class="item-title  wg_order_choose lh25 extend-mr7 choose-art-type" data-price="' + el2.price + '" data-choose="' + index2 + '">' + artname + '</div>';
						} else {
							str += '<div class="item-title  wg_order_choose lh25 extend-mr7 choose-active choose-art-type" data-price="' + el2.price + '" data-choose="' + index2 + '">' + artname + '</div>';
						}
					});
					//默认选择第一个
					pinfo[index]['choose'] = '{"'+default_key+'":{"price":' + default_price + '}}';
					total_price += parseInt(default_price);

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
					str += '            <span class="item-after-money product-price">' + default_price + '</span>';
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
			//赋值优惠价
			if(total_pref > 0){
				var pref_str = '(优惠：￥'+total_pref+')';
				$('#pref-price').html(pref_str);
			}else{
				total_pref = 0;
			}
			//赋值总价
			$('#total_price').text(total_price - total_pref);
			total_price -=total_pref;

			wrap.html(str);
			$(document).on('click','#select_products_info_wrap .choose-meal',function(){
				var pid = $(this).parents('.item-inner').data('pid');
				var popupHTML = '<div class="popup">'+
				                    '<div class="my-content-block">'+
				                      '<header class="bar bar-nav">\
										  <a class="icon icon-left pull-left close-popup"></a>\
										  <h1 class="title">'+pinfo[pid].name+'</h1>\
										</header>'+
				                      '<p class="content-img-100">'+pinfo[pid].intro+'</p>'+
				                    '</div>'+
				                  '</div>'
				  $.popup(popupHTML);
				// var type = $(this).data('choose');
				// str = '';
				// switch(type){
				// 	case 'color':
				// 		img_src = total_url+'tpl/Wap/default/common/original/img/mael-color.png';
				// 		break;
				// 	case 'grid':
				// 		img_src = total_url+'tpl/Wap/default/common/original/img/meal-grid.png';
				// 		break;
				// 	case 'whimsy':
				// 		img_src = total_url+'tpl/Wap/default/common/original/img/meal-marry.png';
				// 		break;
				// }
				// str += '<div class="meal-choose">';
		  //       str +=     '<img src="'+img_src+'" alt="">';
		  //       str +=     '<div class="close-mask iconfont"></div>';
		  //       str += '</div>';
				// wrap.append(CreateMask(str));
			})
			//选择样色
			$(document).find('.wg_order_choose_color').off('click').on('click',function() {
					var product_price = parseInt($(this).parents('.item-inner').find('.product-price').text());
					var choose_price = parseInt($(this).data('price'));
					var choose_key = $(this).data('choose');
					var pid = $(this).parents('.item-inner').data('pid');
					//记录选择颜色
					var choose_color = eval('(' + pinfo[pid]['choose'] + ')');
					if($(this).hasClass('card-product-color')) {
						$(this).removeClass('card-product-color');
						product_price += choose_price;
						total_price += choose_price;
						//在选中的产品中记录颜色选择
						if ($.type(choose_color[choose_key]) == 'undefined') {
							choose_color[choose_key] = {
								"price": choose_price
							};
						}
					}else{
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
			$(document).find('#choose_products_info .choose-art-type').off('click').on('click',function() {
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
			$(document).find('#choose_products_info .choose-art-gongge').off('click').on('click',function() {
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
			$(document).find('#choose_products_info .choose-whimsy').off('click').on('click',function() {
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
					//赋值优惠
					cookie.set('total_pref', total_pref);
					//赋值选择规格之后的商品
					cookie.set('choose_product', JSON.stringify(pinfo));
					$.router.load("ChooseDate.html");
				} else {
					$.alert('未选择商品');
				}
			})
		});
		//选择日期页面
	// $(document).on('pageInit', '#choose_date_wrap', function() {
	// 	var choose_date;
	// 	if (cookie.get('choose_date') != '') {
	// 		$(this).find("#choose_date").val(cookie.get('choose_date'));
	// 		choose_date = cookie.get('choose_date');
	// 	}
	// 	$(this).find("#choose_date").change(function(event) {
	// 		choose_date = $(this).val();
	// 		cookie.set('choose_date', choose_date);
	// 	});
	// 	//获取工作时间
	// 	$.ajax({
	// 		url: total_url + 'index.php?g=Wap&m=Store&a=getWorkTime',
	// 		dataType: 'json',
	// 		data: {
	// 			choose_date: choose_date
	// 		},
	// 		success: function(data) {
	// 			var time_info_wrap = $('#time_info');
	// 			var str = '';
	// 			$.each(data.data, function(index, el) {
	// 				str += '<span class="choose_time" data-time="' + el + '">' + el + '</span>';
	// 			});
	// 			time_info_wrap.html(str);
	// 			$(document).find('.choose_time').off('click').on('click', function() {
	// 				var choose_time = $(this).data('time');
	// 				cookie.set('choose_time', choose_time);
	// 				$.router.load("orders.html");
	// 			})
	// 		}
	// 	});
	// });
	$(document).on('pageAnimationStart','#orders_wrap',function(){
		//订单倒计时清空
		cookie.set('last_time','');
	})
	$(document).on('beforePageSwitch','#orders_wrap',function(){
		//订单倒计时清空
		cookie.set('last_time','');
	})
	//订单页面
	$(document).on('pageInit', '#orders_wrap', function() {
		var pinfo = cookie.get('choose_product');
		var pref = cookie.get('total_pref');
		//赋值优惠价
		$('#total-pref').html('(优惠：￥'+pref+')');
		$('#show-pref-item').text(pref);

		if(!pinfo){
			$.router.load('myorders.html');
			return false;
		}
		var storeId = cookie.get('storeId');
		var storeName = cookie.get('storeName');
		var orderTime = cookie.get('orderTime')/1000;
		var orderTime2 = getLocalTime(orderTime);
		pinfo = eval('('+pinfo+')');
		console.log(pinfo);
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
		var personal_info;
		$.getJSON(total_url + 'index.php?g=Wap&m=Distribution&a=myInfo', function(json) {
			personal_info = json.data;
			$('#order-name').val(json.data.truename);//姓名
			$('#order-birth').val(json.data.birth);//出生
			$('#order-sex .order-sex-item').text(json.data.sex == 0 ? '女' : '男');//性别
			$('#order-sex .order-sex-item').attr('data-sex',json.data.sex);
			$('#order-email').val(json.data.email);//邮箱

			//选择性别
			$('#order-sex').picker({
				toolbarTemplate: '<header class=\"bar bar-nav\">' +
					'<button class=\"button button-link pull-right close-picker\">确定</button>' +
					'<h1 class=\"title\">选择性别</h1></header>',
				cols: [{
					textAlign: 'center',
					values: ['1','0'],
					displayValues: ['男','女'],
				}],
				onClose: function() {
					var _self = $('.picker-selected');
					$('#order-sex .order-sex-item').text(_self.text());
					$('#order-sex .order-sex-item').attr('data-sex',_self.attr('data-picker-value'));
				}
			});
		})
		//判断抵价卷
		var c_price;
		var choose_coupons = '';
		$.ajax({
			url:total_url + 'index.php?g=Wap&m=Distribution&a=shoppingCoupons',
			data:{price:total_price -pref},
			dataType:'json',
			async:false,
			success:function(json){
				var coupons_name = [];
				var coupons_id = [];
				var coupons_item = $('#coupons-item');
				coupons_name.push('不使用');
				coupons_id.push('0');
				if(json.data.length != 0){
					var coupons_data = json.data;
					coupons_item.text(Object.keys(coupons_data).length + '张可用');

					$.each(json.data,function(index, el) {
						coupons_name .push(el.name);
						coupons_id .push(el.id);
					});

					$("#my-coupons").picker({
					  toolbarTemplate: '<header class="bar bar-nav">\
					  <button class="button button-link pull-right close-picker">确定</button>\
					  <h1 class="title">可用抵价卷</h1>\
					  </header>',
					  cols: [
					    {
					      textAlign: 'center',
					      values:  coupons_id,
					      displayValues: coupons_name,
					    }
					  ],
					  onClose: function(data) {
					  		c_price = 0
							var _self = $('.picker-selected');
							coupons_item.text(_self.text());
							var id = _self.attr('data-picker-value');
							if($.type(coupons_data[id]) != 'undefined'){
								c_price = parseInt(coupons_data[id]['price']);
								choose_coupons = coupons_data[id];
							}else{
								choose_coupons = '';
							}
							coupons_item.attr('data-coupons-id',id);

							$('#total-price').text(total_price - pref - c_price);
						}
					});
				}else{
					coupons_item.text('无可用');
				}

			}
		})

		//总价
		$('#total-price').text(total_price - pref);
		//计算支付剩余时间
		var last_time_item = $('#orders_wrap .pay-last-time');
		var last_time = cookie.get('last_time') ? cookie.get('last_time') : 1200;
		var order_control = 1;
		var min;
		var ms;
		if (order_control) {
			coutdown = setInterval(function() {
				last_time--;
				cookie.set('last_time', last_time);
				if (last_time == 0) {
					order_control = 0;
					last_time_item.text('订单过期');

					cookie.set('choose_product','');
					$.router.load('Appointment.html');
					cookie.set('last_time', '');
					clearInterval(coutdown);
					return false;
				} else {
					min = parseInt(last_time/60);
					ms = last_time%60;
					last_time_item.text(min+'分'+ms+'秒');
				}
			}, 1000);
		}
		//去支付
		$('#pay-btn').click(function(){
			if(order_control != 1){
				return false;
			}
			//重新获取编辑后的个人信息
			var new_name = $('#order-name').val();
			var new_sex = $('#order-sex .order-sex-item').attr('data-sex');
			var new_birth = $('#order-birth').val();
			var new_email = $('#order-email').val();
			personal_info ['truename'] = new_name;
			personal_info ['sex'] = new_sex;
			personal_info ['birth'] = new_birth;
			personal_info ['email'] = new_email;
			
			var pay_data = {};
			pay_data['sid'] = storeId;
			pay_data['ordertime'] = orderTime;
			pay_data['con'] = pinfo;
			pay_data['pref'] = pref;
			pay_data['coupons'] = choose_coupons;
			pay_data['myinfo'] = personal_info;
			pay_data['totalPrice'] = total_price;
			pay_data['city'] = getCityName(cookie.get('scid'));
			pay_data['lasttime'] = cookie.get('last_time');
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
		   // return new Date(parseInt(nS) * 1000).toLocaleString().replace(/:\d{1,2}$/,' '); 
		   var time = new Date(nS * 1000);
		   var y = time.getFullYear();
		   var m = time.getMonth()+1;
		   var d = time.getDate();
		   var h = time.getHours();
		   var mm = time.getMinutes();
		   var s = time.getSeconds();
		   return y+'-'+add0(m)+'-'+add0(d)+' '+add0(h)+':'+add0(mm)+':'+add0(s);    
		}
		function add0(m){return m<10?'0'+m:m }
	});
	$(document).on('pageAnimationStart','#my-orders',function(){
		var all_wrap = $('#all-orders');
		all_wrap.html('');
	})
	//我的订单页面
	$(document).on('pageInit','#my-orders',function(){
		var wrap = $('#my-orders');
		var personal_info;
		var all_wrap = $('#all-orders');
		var payed_wrap = $('#payed-orders');
		var finish_wrap = $('#finish-orders');
		var my_orders;
		$.getJSON(total_url + 'index.php?g=Wap&m=Distribution&a=myOrders', function(json) {
			var nowTime = Date.parse(new Date())/1000;
			my_orders = json.info;
			$.each(json.data,function(index, el) {
				var str = '';
				var status = '';
				var last_time = (parseInt(el.time) + parseInt(el.lasttime)) - parseInt(nowTime);
				if(el.paid == 0){
					if(last_time > 0){
						status = '未付款';
					}else{
						status = '订单过期';
					}
				}
				if(el.paid == 1){
					if(el.returnMoney == 1){
						status = '已取消';
					}else{
						status = '进行中';
					}
				}
				if(el.handled == 1){
					status = '已完成';
				}
				str += '<div class="list-block">';
				str += '    <ul>';
				str += '        <li class="item-content">';
				str += '            <div class="item-inner">';
				str += '                <div class="item-title">订单号：'+el.orderid+'</div>';
				str += '                <div class="item-after order-status">'+status+'<item class="last-time-item total-color" data-lastTime="'+last_time+'"></item></div>';
				str += '            </div>';
				str += '        </li>';
				str += '        <li class="item-content">';
				str += '            <div class="item-inner">';
				str += '                <div class="item-title">';
				str += '                    <p>拍摄门店：<item class="store-name">'+el.sname+'</item></p>';
				str += '                    <p>预约时间：<item class="reservation-time">'+el.datertime+'</item></p>';
				str += '                    <p>创建时间：<item class="reservation-time">'+el.datetime+'</item></p>';
				str += '                    <p>门店号码：<item class="store-tele total-color">'+el.stel+'</item></p>';
				str += '                </div>';
				str += '            </div>';
				str += '        </li>';
				str += '        <li class="item-content1" style="padding-bottom:0.1rem;">';
				str += '            <div class="item-inner1">';
				str += '                <div class="item-title"></div>';
				str += '                <div class="item-after1">总计：￥<span class="reservation-price total-color">'+el.price+'</span></div>';
				// str += '                <div class="item-after2 pay_choose">拍摄路线</div>';
				str += '<div class="order-btn-wrap">';
				if(el.paid == 0 && last_time > 0){
					str += '<div class="item-after2 pay_unchoose pay-now" data-id="'+el.id+'">去付款</div>';
				}
				if(el.paid == 1 && el.returnMoney == 0 && el.handled == 0){
					str += '<div class="item-after2 pay_unchoose cancel-reservation" data-id="'+el.id+'">取消预约</div>';
					if(el.rnums < 2){
						str += '<div class="item-after2 pay_unchoose change-retime" data-id="'+el.id+'">改约</div>';
					}
				}
				if(el.paid == 1){
					str += '<a href="'+el.storeurl+'" class="external">';
					str += '<div class="item-after2 pay_unchoose" data-id="'+el.id+'">推荐路线</div>';
					str += '</a>';
				}
				if(el.handled == 1){
					str += '<div class="item-after2 pay_unchoose show-pics" data-id="'+el.id+'">下载图片</div>';
				}
				str += '</div>';
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
					if(el.paid == 0){
						wrap.find('.last-time-item').each(function(index2, el2) {
							var obj = $(this);
							var last_time = obj.data('lasttime');
							if(last_time >0){
								coutdown = setInterval(function() {
									last_time--;
									if (last_time == 0) {
										obj.parents('.list-block').find('.pay-now').remove();
										obj.parents('.item-after').text('订单过期');
										// clearInterval(coutdown);
										return false;
									} else {
										min = parseInt(last_time/60);
										ms = last_time%60;
										obj.text(min+'分'+ms+'秒');
									}
								}, 1000);
							}	
						});
					}
				}
			});
			//立即支付
			$(document).on('click','#my-orders .pay-now',function(){
				var id = $(this).data('id');
				location.href = total_url + 'index.php?g=Wap&m=Store&a=payNow&id='+id;
			})
			//改约
			$(document).on('click','#my-orders .change-retime',function(){
				var id = $(this).data('id');
				if(my_orders[id]['rnums'] >=2){
					$.alert('每人只能改约两次!');
					return false;
				}
				$.confirm('确定改约吗？',function(){
					var now_stamp = new Date();
					var rtime_stamp = new Date(my_orders[id]['datertime']);
					var rtime = parseInt(rtime_stamp.getTime()/1000);
					var nowtime = parseInt(now_stamp.getTime()/1000);
					var rday = rtime_stamp.getDate();
					var nowday = now_stamp.getDate();
					if((rtime - nowtime) < 86400 && rday == nowday){
						$.alert('不能修改当天的预约时间!');
						return false;
					}else{
						cookie.set('storeId',my_orders[id]['storeid']);
						cookie.set('order_chang_rtime_id',id);
						$.router.load('ChooseDate.html');
					}
				})
			})
			//查看订单图片
			$(document).on('click','#my-orders .show-pics',function(){
				var photos = [];
				var oid = $(this).data('id');
				//获取该订单下的图片
				$.ajax({
					url: total_url + 'index.php?g=Wap&m=Distribution&a=getMyCartPics',
					data:{id:oid},
					dataType:'json',
					type:'post',
					async:false,
					success:function(data){
						$.each(data.data,function(index, el) {
							photos.push(el.url);
						});
					}
				});
				var myPhotoBrowserStandalone = $.photoBrowser({
			        photos : photos
			    });
				myPhotoBrowserStandalone.open();
			})
			//取消预约
			$(document).on('click', '#my-orders .cancel-reservation', function() {
				var obj = $(this);
				var oid = obj.data('id');
				$.confirm('取消将扣除20%的费用,您确定取消吗?',
					function() {
						$.ajax({
							url: total_url + 'index.php?g=Wap&m=Distribution&a=cancelOrder',
							data: {
								id: oid
							},
							dataType: 'json',
							type: 'post',
							async: false,
							success: function(data) {
								$.alert(data.info);
								if (data.status == 1) {
									obj.parents('.list-block').find('order-status').text('已取消');
									obj.parents('.order-btn-wrap').remove();
								}
							}
						});
					}
				);
			})
		})
	})
	$.init();
})