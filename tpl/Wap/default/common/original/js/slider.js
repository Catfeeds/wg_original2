$(function() {
// 首页模块
// 首页
$(document).on('pageInit', '#index-page', function(e, id, page) {
		var scid = cookie.get('scid');
		$.reinitSwiper('#index-page');
		getProductList(); // 获得产品列表
		if (scid) {
			console.log(scid);
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
			if (navigator.geolocation) {
				navigator.geolocation.getCurrentPosition(getLocationCity, locationError);
			} else {
				$('.index-position em').html('暂无定位信息'); // 处理定位错误
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
				cookie.set('scid', cd); // 获取真实位置id，传值
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
		}
		/**
		 * 产品介绍列表
		 */
		function getProductList() {
			$.showIndicator();
			$.get(m_module + 'index.php?g=Wap&m=Store&a=getBanner',
					function(data) {
						var data = eval('('+data+')');
						console.log(data);
						if (data.error === 0) {
							$.hideIndicator();
							var products = data.msg.product; // 获得产品数据
							var banners = data.msg.banner; // 获得banner数据
							var bannersHtml = '';
							var swiperHtml = '';
							var $banner = $('#slide-index');
							var tabHtml = '';
							var tabUnitHtml = '';
							var tabCHtml = '';
							var tabCUnitHtml = '';
							// banner图
							for (var index in banners) {
								var bannerUrl = banners[index].BA_Url ? banners[index].BA_Url : 'javascript:;';
								bannersHtml += '<div class=\"swiper-slide\"><a class=\"external\" href=\"' +
									bannerUrl + '\"><img src=\"' +
									banners[index].BA_Path + '\" alt=\"banner\" style=\"display:none;\"></a></div>';
							}
							swiperHtml = '<div class=\"swiper-container\" id=\"swiper-banners\"><div class=\"swiper-wrapper\">' +
								bannersHtml + '</div><div class=\"swiper-pagination\"></div><div class=\"wrapper\"><div class=\"loadingWrap\"><div class=\"cupWrap\"><div class=\"coffee_cup\"></div></div></div></div></div></div>';
							$('#slider-index').html(swiperHtml);
							$("#swiper-banners").swiper({ speed: 400, spaceBetween: 0, autoplay: 3000, loop: true });
								//图片加载完成回调
								$('.external img').on('load', function() {
									$(this).parents().find('.wrapper').remove();
									$(this).show();
								});
								for (var j in products) {
									var proUnitHtml = '';
									var pid = parseInt(products[j].PT_Id);
									var pname = products[j].PT_Name;
									// 模块名
									// 隐藏模块
									if (products[j].PT_Product.length !== 0) {
										tabUnitHtml += '<a href=\"#pro-' + pid + '\" class=\"tab-link button ' + (pid === 1 ? 'active' : '') + '\">' + pname + '</a>';
									} else {
										tabUnitHtml += '';
									}
									// 获取首页产品列表
									for (var z in products[j].PT_Product) {
										var _products = products[j].PT_Product[z];
										proUnitHtml += '<div class=\"photo-list\" data-pid=\"' +
											_products.P_Id + '\"><img src=\"' +
											_products.P_ImgPath + '\" alt=\"' +
											_products.P_Name + '\" style=\"display:none\"><div class=\"wrapper\"><div class=\"loadingWrap\"><div class=\"cupWrap\"><div class=\"coffee_cup\"></div></div></div></div></div>';
									}
									tabCUnitHtml += '<div id=\"pro-' + pid + '\" class=\"tab ' + (pid === 1 ? 'active' : '') + '\"><div class=\"content-block\">' + proUnitHtml + '</div></div>';
								}
								tabHtml = '<div class=\"buttons-tab\">' + tabUnitHtml + '</div>'; tabCHtml = '<div class=\"content-block\"><div class=\"tabs\">' + tabCUnitHtml + '</div></div>'; $('#home-content').html(tabHtml + tabCHtml);
								//图片加载完成回调
								$('.photo-list img').on('load', function() {
									$(this).parent().find('.wrapper').remove();
									$(this).show();
								});
							}
							$('.photo-list').on('click', photoHandle); // 列表绑定点击事件
						});
				}
				// 产品点击处理
			var photoHandle = function() {
				var pid = $(this).data('pid');
				cookie.set('pid', pid);
				$.router.load('/Index/productDetails');
			};
		}); $(document).on('pageAnimationStart', '#product-details', function(e, id, page) {
		$('#product-intro').html('');
		$('#product-showList .row').html('');
	});
	// 产品详情页
	$(document).on('pageInit', '#product-details', function(e, id, page) {
		var pid = cookie.get('pid') || 1; // 获得选中的产品p_id
		var paged = 1;
		showProDetails();
		$('.product-appointment').on('click', function() {
			window.location.href = m_module + '/Index/reservation';
		});
		$('#product-showList .product-moreBtn').on('click', function() {
			showMoreHandle();
		});
		/**
		 * 展示产品详情
		 */
		function showProDetails() {
			$('.content').scrollTop(0);
			$.showIndicator();
			$.get(m_module + '/Sapi/getProductDetail/pid/' + pid + '/page/' + paged, function(data) {
				$.hideIndicator();
				if (data.error === 0) {
					var proDetails = data.msg;
					var proIntroHtml = '';
					var picShowHtml = '';
					var picTypeHtml = '';
					var priceHtml = '';
					var scid = cookie.get('scid');
					var pidColArr = ['1', '2', '21', '7', '30', '31', '32', '33']; // 竖图数组
					var imgStatus;
					var priceNow = parseInt(!isSupercity(scid) ? proDetails.P_Price : proDetails.P_Price_Ext1);
					var priceOri = parseInt(!isSupercity(scid) ? proDetails.P_OriPrice : proDetails.P_OriPrice_Ext1);
					var priceDouble = parseInt(!isSupercity(scid) ? proDetails.PD_DoublePrice : proDetails.PD_DoublePrice_Ext1);
					var priceDoublePrint = parseInt(!isSupercity(scid) ? proDetails.PD_DoublePrintPrice : proDetails.PD_DoublePrintPrice_Ext1);
					if (priceNow === priceOri || priceOri === 0) {
						priceHtml = '<span class=\"product-price\">' + priceNow + '元/套</span>';
					} else {
						priceHtml = '<span class=\"product-price\">' + priceNow + '元/套</span></p><span class=\"product-price-origin\">' + priceOri + '元/套</span>';
					}
					proIntroHtml = '<div class=\"product-infoGroup\"><p>' +
						proDetails.P_Name + priceHtml + '<span class=\"product-tip\">' +
						proDetails.PD_Desc + '</span></div><div class=\"product-infoGroup\"><p>加修加印<span class=\"product-price\">' +
						priceDouble + '元/张</span></p><span class=\"product-tip\">' +
						proDetails.PD_DoubleDesc + '</span></div><div class=\"product-infoGroup\"><p>加印<span class=\"product-price\">' +
						priceDoublePrint + ((pid == 1 || pid == 2 || pid == 3) ? '元/版' : '元/张') + '</span></p><span class=\"product-tip\">' +
						proDetails.PD_DoublePrintDesc + (proDetails.PD_Note ? '</span></div><p class=\"product-prompt\"><span>*</span>' +
							proDetails.PD_Note + '</p>' : '</span></div><p class=\"product-prompt\"><span></span></p>');
					// 横图类型判断
					if ($.inArray(pid, pidColArr) >= 0) {
						picTypeHtml = '<div class=\"col-33 img-group\"><img src=\"';
						imgStatus = '!MobileV2Vertical';
					} else {
						picTypeHtml = '<div class=\"cross-img img-group\"><img src=\"';
						imgStatus = '!MobileV2Horizontal';
					}
					for (var index in proDetails.P_Photo) {
						picShowHtml += picTypeHtml + proDetails.P_Photo[index] + imgStatus + '\" alt=\"\" style=\"display:none\"><div class=\"wrapper\"><div class=\"loadingWrap\"><div class=\"cupWrap\"><div class=\"coffee_cup\"></div></div></div></div></div>';
					}
					if (data.msg.P_Photo.length === 9) {
						$('#product-showList .product-moreBtn').html('加载更多图片');
					} else {
						$('#product-showList .product-moreBtn').html('无更多图片');
						$('#product-showList .product-moreBtn').off('click');
					}
					$('#product-intro').html(proIntroHtml); // 产品详情
					$('#product-showList .row').html(picShowHtml); // 客片展示
					$('.img-group img').on('load', function() {
						$(this).parent().find('.wrapper').remove();
						$(this).show();
					});
				} else {
					$.toast(data.msg);
				}
			});
		}
		/**
		 * 展示更多客片
		 */
		function showMoreHandle() {
			paged++;
			$.showIndicator();
			$.get(m_module + '/Sapi/getProductDetail/pid/' + pid + '/page/' + paged, function(data) {
				$.hideIndicator();
				if (data.error === 0) {
					var morePhoto = data.msg.P_Photo;
					var morePhotoHtml = '';
					var picTypeHtml = '';
					var pidColArr = ['1', '2', '21', '7', '30', '31', '32', '33']; // 竖图数组
					var imgStatus;
					// 横图类型判断
					if ($.inArray(pid, pidColArr) >= 0) {
						picTypeHtml = '<div class=\"col-33 img-group\"><img src=\"';
						imgStatus = '!MobileV2Vertical';
					} else {
						picTypeHtml = '<div class=\"cross-img img-group\"><img src=\"';
						imgStatus = '!MobileV2Horizontal';
					}
					for (var index in morePhoto) {
						morePhotoHtml += picTypeHtml + morePhoto[index] + imgStatus + '\" alt=\"\" style=\"display:none\"><div class=\"wrapper\"><div class=\"loadingWrap\"><div class=\"cupWrap\"><div class=\"coffee_cup\"></div></div></div></div></div>';
					}
					if (morePhoto.length < 9) {
						$('#product-showList .product-moreBtn').html('无更多图片');
						$('#product-showList .product-moreBtn').off('click');
						// return false;
					} else {
						$('#product-showList .product-moreBtn').html('加载更多图片');
					}
					$('#product-showList .row').append(morePhotoHtml);
					$('.img-group img').on('load', function() {
						$(this).parent().find('.wrapper').remove();
						$(this).show();
					});
				}
			});
		}
	});
});
