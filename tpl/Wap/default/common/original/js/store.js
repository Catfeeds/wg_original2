$(function($) {
	var total_url = 'http://127.0.0.1/wg_original2/';
	//判断账号登陆
	$(document).ready(function($) {
		$.ajax({
			url: total_url + 'index.php?g=Wap&m=Distribution&a=checkLogin',
			dataType: 'json',
			success: function(data) {
				if (data && data.status == -1) {
					location.href = "login.html";
				}
			}
		});
	});
	//选择门店
	$(document).on('pageInit', '#appintment_wrap', function() {
			//获取区域信息
			var areas = [];
			$.getJSON(total_url + 'index.php?g=Wap&m=Store&a=getAreaInfo',function(data){
				$.each(data.data,function(index, el) {
					areas.push(el.name);
				});
			});
			//选择区域
			$(this).find('#area_list').picker({
				toolbarTemplate: '<header class="bar bar-nav">\
					<button class="button button-link pull-right close-picker">确定</button>\
					<h1 class="title">选择区域</h1>\
					</header>',
				cols: [{
					textAlign: 'center',
					values: [1,2],
					displayValues:areas,
				}],
				onClose: function() {
					console.log('11');
				}
			});
			$(this).find('#stores_list').picker({
				toolbarTemplate: '<header class="bar bar-nav">\
					<button class="button button-link pull-right close-picker">确定</button>\
					<h1 class="title">标题</h1>\
					</header>',
				cols: [{
					textAlign: 'center',
					values: ['iPhone 4', 'iPhone 4S', 'iPhone 5', 'iPhone 5S', 'iPhone 6', 'iPhone 6 Plus', 'iPad 2', 'iPad Retina', 'iPad Air', 'iPad mini', 'iPad mini 2', 'iPad mini 3'],
					displayValues: ['iPhone 41', 'iPhone 4S', 'iPhone 5', 'iPhone 5S', 'iPhone 6', 'iPhone 6 Plus', 'iPad 2', 'iPad Retina', 'iPad Air', 'iPad mini', 'iPad mini 2', 'iPad mini 3']
				}]
			});
		})
		//获取产品分类和产品信息
	$(document).on('pageInit', '#select_product_wrap', function() {
		$.showPreloader();
		cookie.set('choose_product', '');
		$.getJSON(total_url + 'index.php?g=Wap&m=Store&a=getCatsProducts', function(json) {
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
			str += '<div>' + item.name + '</div>'
			if (item.products[0].length != 0) {
				$.each(item.products[0], function(index2, item2) {
					str += '&nbsp;<span class="product_item" data-pid="' + item2.id + '" data-price="' + item2.price + '" data-name="' + item2.name + '">' + item2.name + '</span>'
				});
			}
		})
		wrap.html(str);
		//选择商品
		$(document).find('.product_item').off('click').on('click', function() {
			var pid = $(this).data('pid');
			var pname = $(this).data('name');
			var pprice = $(this).data('price');
			if (pid_arr.indexOf(pid) == -1) {
				pid_arr.push({
					'pid': pid,
					'name': pname,
					'price': pprice
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
				str += '<div>' + el.name + '合计:' + el.price + '</div>';
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