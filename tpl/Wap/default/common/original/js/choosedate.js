$(function() {
	// 选择日期
	$(document).on('pageInit', '#reservation-date', function(e, id, page) {
		$.showPreloader();
		arrangeDate('#reservation-date-day'); // 日期排版
		banTouchmove('#reservation-date'); // 禁止页面滑动
		// 选择选择3个月内日期
		$('#otherDate').datetimePicker({
			toolbarTemplate: '<header class=\"bar bar-nav\"> <button class=\"button button-link pull-right\" id=\"get-date\">确定</button> <h1 class=\"title\"></h1> </header>',
			onOpen: function() {
				$('.picker-items-col.picker-items-col-divider').remove();
				$('#get-date').on('click', function() {
					var select = $('.picker-selected');
					var today = new Date();
					var tynum = today.getFullYear();
					var tmnum = today.getMonth();
					var tdnum = today.getDate();
					var ynum = $(select[0]).html();
					var mnum = $(select[1]).html();
					var dnum = $(select[2]).html();
					var maxTs = +new Date(tynum, tmnum + 3, tdnum);
					var selectD = +new Date(ynum, mnum - 1, dnum);
					if (selectD <= maxTs && selectD >= today) {
						cookie.set('orderDate', selectD);
						setTimeout(function() {
							$.router.load('ChooseTime.html');
						}, 500);
					} else {
						$.toast('请选择之后三个月内的时间哦');
					}
				});
			}
		});
		/**
		 * 点击时间处理事件，获取预约时间，并下跳转到时间选择页面。
		 * @default 设置预约日期的时间戳cookie　
		 */
		function getOrderDate() {
			var orderD = $(this).data('date') * 1000;
			$(this).addClass('active');
			cookie.set('orderDate', orderD);
			setTimeout(function() {
				$.router.load('ChooseTime.html');
			}, 50);
		}
		/**
		 * 排日期
		 * @param  {'#idname|.classname|...'} selector 选择插入对象选择器
		 * @return {html}          返回日期列表
		 */
		function arrangeDate(selector) {
			var $reservationDate = $(selector); // 目标容器
			var monthArr = new Array('一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月');
			var storeId = cookie.get('storeId');
			$.showPreloader();
			$.get(total_url + 'index.php?g=Wap&m=Store&a=getWorkDate&storeId=' + storeId, function(data) {
				data = eval('(' + data + ')');
				console.log(data);
				$.hidePreloader();
				if (data.error === 0) {
					var dateK = data.msg;
					var dateS = data.msg.remain;
					var today = new Date(); // 获取今天的日期, 需联调, 获取服务器时间
					var startTime = getInitDate(); // 确定初始日期
					var yearnum = startTime.getFullYear();
					var monthnum = startTime.getMonth();
					var datenum = startTime.getDate();
					var dateArr = []; // 展示的35天的时间戳数组
					var date_count = 35;
					var dateListHtml = '';
					var countPlaceHold = cookie.get('countPlaceHold');
					// 获得当前时间列表的时间戳数组
					for (var i = 0; i < date_count; i++) {
						var dateunitHtml, spareHtml, monthHtml, dateHtml;
						var thisday = new Date(yearnum, monthnum, datenum + i);
						var thisStamp = thisday / 1000;
						var nodeStatus = ''; // 节点状态, 不能预约的为off
						var monthch = monthArr[thisday.getMonth()]; // 中文月份
						var spareCount;
						if (dateS[thisStamp]) {
							spareCount = dateS[thisStamp].count; // 获取余量
						} else {
							spareCount = 0;
						}
						// 判断是否需要风控
						if (!((dateK.couponLimit >= spareCount) && (dateK.needRM === 1))) {
							// 判断是否有余量, 若无余量, 则设置off状态
							if ((spareCount > 0 && spareCount <= 20 && countPlaceHold <= spareCount)) {
								nodeStatus = '';
								spareHtml = '<span class=\"reservation-timeNum\">余 ' + spareCount + '</span>';
							} else if (spareCount > 20) {
								nodeStatus = '';
								spareHtml = '<span class=\"reservation-timeNum\"></span>';
							} else {
								nodeStatus = 'off';
								spareHtml = '<span class=\"reservation-timeNum\"></span>';
							}
						} else {
							nodeStatus = 'off';
							spareHtml = '<span class=\"reservation-timeNum\"></span>';
						}
						//判断产品占位
						if (countPlaceHold > spareCount) {
							nodeStatus = 'off';
						}
						// 判断是否为某月的1号,如果是,则打上红色圆点标记,月份用大写的月份来标识
						if ((thisday.getDate() === 1) || (i === 0)) {
							monthHtml = '<span class=\"reservation-timeMonth\">' + monthch + '<em class=\"reservation-timeMonthIcon\"></em></span>';
						} else {
							monthHtml = '<span class=\"reservation-timeMonth\"></span>';
						}
						// 判断是否为节日
						if (showFestival(thisStamp * 1000)) {
							monthHtml = '<span class=\"reservation-timeMonth\">' + showFestival(thisStamp * 1000) + '<em class=\"reservation-timeMonthIcon\"></em></span>';
						}
						// 判断是否为今天之前的日期
						if ((+today - 3600000 * 24) > thisday) {
							nodeStatus = 'off';
						}
						// 插入日期
						dateHtml = '<span class=\"reservation-timeDate\">' + thisday.getDate() + '</span>';
						dateunitHtml = '<div data-date = ' + thisStamp + ' class=\"' + nodeStatus + '\"\">' + monthHtml + dateHtml + spareHtml + '</div>';
						dateListHtml += dateunitHtml;
					}
					$reservationDate.html(dateListHtml);
					$('#reservation-date-day div').off('click').on('click', getOrderDate); // 日期绑定点击事件，跳转到时间选择页面
					$('#reservation-date-day .off').off('click'); // 禁止之前日期的点击事件
				} else if (data.error === 401) {
					$.toast(data.msg);
					setTimeout(function() {
						$.router.load('/Index/reservation');
					}, 2000);
				} else {
					$.toast(data.msg);
				}
			});
		}
		/**
		 * 节日展示
		 */
		function showFestival(st) {
			var _date = getFormatDate(st);
			var _darr = _date.split('-');
			var _d = _darr[1] + (_darr[2] < 10 ? '0' + _darr[2] : _darr[2]);
			var festObj = {
				'0101': '元旦',
				'0214': '情人节',
				'0308': '妇女节',
				'0312': '植树节',
				'0320': '春分',
				'0401': '愚人节',
				'0404': '清明节',
				'0501': '劳动节',
				'0504': '青年节',
				'0601': '儿童节',
				'0621': '夏至',
				'0701': '建党节',
				'0801': '建军节',
				'0910': '教师节',
				'0922': '秋分',
				'1001': '国庆节',
				'1221': '冬至',
				'1224': '平安夜',
				'1225': '圣诞节'
			};
			if (festObj[_d]) {
				return festObj[_d];
			} else {
				return;
			}
		}
		/**
		 * 获得本周第一天的日期
		 * @return {initdate} 初始化日期列表第一天
		 */
		function getInitDate() {
			var today = new Date(); // 获得当前日期
			var todayYearnum = today.getFullYear();
			var todayMonnum = today.getMonth();
			var todayDatenum = today.getDate();
			var todayWeeknum = today.getDay();
			var initdate = new Date(todayYearnum, todayMonnum, todayDatenum - todayWeeknum);
			return initdate;
		}
		/**
		 * 时间戳格式转化为 yy-mm-dd
		 * @return {String} 2016-6-23
		 */
		function getFormatDate(ts) {
			var date = new Date(ts);
			var monthnum = date.getMonth();
			return date.getFullYear() + '-' + ((monthnum + 1) < 10 ? '0' + (monthnum + 1) : +(monthnum + 1)) + '-' + date.getDate();
		}
	});
	// 选择详细拍摄时间
	$(document).on('pageAnimationStart', '#reservation-timeNode', function(e, id, page) {
		//初始化操作
		$('.reservation-dateNum').html('');
		$('.reservation-timeList').html('');
		$.closeModal(".picker-modal.modal-in");
	});
	$(document).on('pageInit', '#reservation-timeNode', function(e, id, page) {
		$.showPreloader();
		var storeId = cookie.get('storeId');
		var today = new Date();
		var defaultDate = +new Date(today.getFullYear(), today.getMonth(), today.getDate()); // 未选择日期时,设置今天默认
		var weekArr = new Array('星期日', '星期一', '星期二', '星期三', '星期四', '星期五', '星期六');
		var $prevdayBtn = $('.reservation-arrowLeft'),
			$nextdayBtn = $('.reservation-arrowRight'),
			$nextStepBtn = $('.reservation-timeNodeBtn');
		var countPlaceHold = cookie.get('countPlaceHold');
		if (!cookie.get('orderDate') || (cookie.get('orderDate') === 'null')) {
			$.router.load('/Index/reservation');
		}
		arrangeTime();
		// 事件绑定
		$prevdayBtn.on('click', function() {
			preNextHandle(-1);
		}); // 左箭头 前一天
		$nextdayBtn.on('click', function() {
			preNextHandle(1);
		}); // 右箭头 后一天
		/**
		 * 前一天后一天处理事件
		 */
		function preNextHandle(pon) {
			var oneDaySt = 24 * 3600 * 1000;
			var _dst = parseInt(cookie.get('orderDate')) + pon * oneDaySt;
			var lastDay = new Date(today.getFullYear(), today.getMonth() + 3, today.getDate());
			if (((pon > 0) && (_dst <= lastDay)) || ((pon < 0) && (_dst > (today - oneDaySt)))) {
				cookie.set('orderDate', _dst);
				arrangeTime();
			} else {
				$.toast('只能预约今天之后三个月内的时间哦');
			}
		}
		/**
		 * 时间戳格式转化为 yy-mm-dd
		 * @return {String} 2016-6-23
		 */
		function getFormatDate(ts) {
			var date = new Date(ts);
			var monthnum = date.getMonth();
			return date.getFullYear() + '-' + ((monthnum + 1) < 10 ? '0' + (monthnum + 1) : +(monthnum + 1)) + '-' + date.getDate();
		}
		/**
		 * 插入时间列表
		 */
		function arrangeTime() {
			var dateFormat = getFormatDate(parseInt(cookie.get('orderDate')));
			var timeListHtml = '';
			var headDateHtml = '';
			var $headDateNode = $('.reservation-dateNum');
			var $timeListNode = $('.reservation-timeList'); // 时间节点目标容器
			var storeId = cookie.get('storeId');
			console.log(storeId);
			console.log(dateFormat);
			$.get(total_url + 'index.php?g=Wap&m=Store&a=getWorkTime',{storeId:storeId,dateFormat:dateFormat}, function(data) {
				$.hidePreloader();
				data = eval('(' + data + ')');
				console.log(data);
				// 获得时间节点列表
				if (data.error === 0) {
					var dateN = data.msg;
					var thisday = new Date(parseInt(cookie.get('orderDate'))); // 当前预约日期
					var yearnum = thisday.getFullYear(),
						montnum = thisday.getMonth(),
						weeknum = thisday.getDay(),
						datenum = thisday.getDate();
					var weekday = weekArr[weeknum];
					var headDateHtml = '';
					var totalcount = 0; // 获取当天剩余位置总数
					for (var i in dateN.remain) {
						totalcount += dateN.remain[i].count;
					}
					headDateHtml = montnum + 1 + ' 月 ' + datenum + ' 日 ' + '<em>' + weekday + '</em>';
					$headDateNode.html(headDateHtml); // 设置头部显示时间
					for (var key in dateN.remain) {
						var timeUnitHtml;
						if (dateN.remain[key].time) { // 仅当后台有返回时间点时显示
							// 增加风控判断
							if (!((dateN.couponLimit >= totalcount) && (dateN.needRM === 1))) {
								if ((dateN.remain[key].count > 0) && (key * 1000 > +today)) {
									timeUnitHtml = '<a href=\"javascript:;\" data-time=\"' + key + '\" data-count=\"' + dateN.remain[key].count + '\">' + dateN.remain[key].time + '</a>';
								} else {
									timeUnitHtml = '<a href=\"javascript:;\" class=\"off\" data-time=\"' + key + '\">' + dateN.remain[key].time + '</a>';
								}
							} else {
								timeUnitHtml = '<a href=\"javascript:;\" class=\"off\" data-time=\"' + key + '\">' + dateN.remain[key].time + '</a>';
							}
						} else {
							continue;
						}
						timeListHtml += timeUnitHtml;
					}
					$timeListNode.html(timeListHtml); // 插入时间节点
					//时间占位计算
					timePlaceHold(countPlaceHold);
					// 点击时间节点,获得预约时间,并高亮显示
					$('.reservation-timeList a:not(.off)').off('click').on('click', function() {
						var orderTime = $(this).data('time') * 1000;
						var createTime = +new Date();
						$('.reservation-timeList a').removeClass('active');
						$(this).addClass('active');
						setTimeout(function() {
							cookie.set('orderTime', orderTime); // 设置预约时间, 用cookie传到下一页
							cookie.set('createTime', createTime); // 创建订单时间
							$.router.load('orders.html');
						}, 500);
					});
				} else if (data.error === 401) {
					$.toast(data.msg);
					setTimeout(function() {
						$.router.load('/Index/reservation');
					}, 2000);
				} else if (data.error === 400) {
					$.router.load('/Index/login');
				} else {
					$.toast(data.msg);
					isSubmit = false;
				}
			});
		}
	});
		$.init();
});