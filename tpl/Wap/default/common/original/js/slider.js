$(function() {
	// 首页
	$(document).on('pageInit', '#index-page', function(e, id, page) {
		//首页显示分享按钮
		function onBridgeReady() {
			WeixinJSBridge.call('showOptionMenu');
		}

		if (typeof WeixinJSBridge == "undefined") {
			if (document.addEventListener) {
				document.addEventListener('WeixinJSBridgeReady', onBridgeReady, false);
			} else if (document.attachEvent) {
				document.attachEvent('WeixinJSBridgeReady', onBridgeReady);
				document.attachEvent('onWeixinJSBridgeReady', onBridgeReady);
			}
		} else {
			onBridgeReady();
		}
		$.reinitSwiper('#index-page');
		getProductList();
		/**
		 * 产品介绍列表
		 */
		function getProductList() {
			$.showIndicator();
			$.get(m_module + 'index.php?g=Wap&m=Store&a=getBanner',
				function(data) {
					var data = eval('(' + data + ')');
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
						$("#swiper-banners").swiper({
							speed: 400,
							spaceBetween: 0,
							autoplay: 3000,
							loop: true
						});
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
								console.log(products[j].PT_Product);
								var _products = products[j].PT_Product[z];
								proUnitHtml += '<div class=\"photo-list\" data-pid=\"' +
									_products.P_Id + '\"><img class=\"w100\" src=\"' +
									_products.P_ImgPath + '\" alt=\"' +
									_products.P_Name + '\" style=\"display:none\"><div class=\"wrapper\"><div class=\"loadingWrap\"><div class=\"cupWrap\"><div class=\"coffee_cup\"></div></div></div></div></div>';
							}
							tabCUnitHtml += '<div id=\"pro-' + pid + '\" class=\"tab ' + (pid === 1 ? 'active' : '') + '\"><div class=\"my-content-block\">' + proUnitHtml + '</div></div>';
						}
						tabHtml = '<div class=\"buttons-tab\">' + tabUnitHtml + '</div>';
						tabCHtml = '<div class=\"my-content-block\"><div class=\"tabs\">' + tabCUnitHtml + '</div></div>';
						$('#home-content').html(tabHtml + tabCHtml);
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
			$.router.load('productDetails.html');
		};
	});
	$(document).on('pageAnimationStart', '#product-details', function(e, id, page) {
		$('#product-intro').html('');
		$('#product-showList .row').html('');
	});
	// 产品详情页
	$(document).on('pageInit', '#product-details', function(e, id, page) {
		var pid = cookie.get('pid') || 1; // 获得选中的产品p_id
		var paged = 1;
		showProDetails();
		$('.product-appointment').on('click', function() {
			window.location.href = 'Appointment.html';
		});

		$(document).on('click', '#product-details .product-moreBtn', function() {
				showMoreHandle();
			})
			// $('#product-showList .product-moreBtn').on('click', function() {
			// 	// console.log('aa');
			// 	// showMoreHandle();
			// });
			/**
			 * 展示产品详情
			 */
		function showProDetails() {
			$('.content').scrollTop(0);
			$.showIndicator();
			$.get(m_module + 'index.php?g=Wap&m=Store&a=getProductDetail&pid=' + pid + '&page=' + paged, function(data) {
				data = eval('(' + data + ')');
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
					picTypeHtml = '<div class=\"col-33 img-group\"><img src=\"';
					// if ($.inArray(pid, pidColArr) >= 0) {
					// 	picTypeHtml = '<div class=\"col-33 img-group\"><img src=\"';
					// 	// imgStatus = '!MobileV2Vertical';
					// 	imgStatus = '';
					// } else {
					// 	picTypeHtml = '<div class=\"cross-img img-group\"><img src=\"';
					// 	// imgStatus = '!MobileV2Horizontal';
					// 	imgStatus = '';
					// }
					for (var index in proDetails.P_Photo) {
						picShowHtml += picTypeHtml + proDetails.P_Photo[index] + '\" alt=\"\" style=\"display:none\"><div class=\"wrapper\"><div class=\"loadingWrap\"><div class=\"cupWrap\"><div class=\"coffee_cup\"></div></div></div></div></div>';
					}
					// if (data.msg.P_Photo.length === 9) {
					// 	$('#product-showList .product-moreBtn').html('加载更多图片');
					// } else {
					// 	$('#product-showList .product-moreBtn').html('无更多图片');
					// 	$('#product-showList .product-moreBtn').off('click');
					// }
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
			$.get(m_module + 'index.php?g=Wap&m=Store&a=getProductDetail&pid=' + pid + '&page=' + paged, function(data) {
				data = eval('(' + data + ')');
				$.hideIndicator();
				if (data.error === 0) {
					var morePhoto = data.msg.P_Photo;
					var morePhotoHtml = '';
					var picTypeHtml = '';
					var pidColArr = ['1', '2', '21', '7', '30', '31', '32', '33']; // 竖图数组
					var imgStatus;
					// 横图类型判断
					picTypeHtml = '<div class=\"col-33 img-group\"><img src=\"';
					// if ($.inArray(pid, pidColArr) >= 0) {
					// 	picTypeHtml = '<div class=\"col-33 img-group\"><img src=\"';
					// 	imgStatus = '!MobileV2Vertical';
					// } else {
					// 	picTypeHtml = '<div class=\"cross-img img-group\"><img src=\"';
					// 	imgStatus = '!MobileV2Horizontal';
					// }
					for (var index in morePhoto) {
						morePhotoHtml += picTypeHtml + morePhoto[index] + '\" alt=\"\" style=\"display:none\"><div class=\"wrapper\"><div class=\"loadingWrap\"><div class=\"cupWrap\"><div class=\"coffee_cup\"></div></div></div></div></div>';
					}
					console.log(morePhoto.length);
					console.log($('#product-showList .row .img-group').length);
					if (morePhoto.length == $('#product-showList .row .img-group').length) {
						$('#product-showList .product-moreBtn').html('无更多图片');
						$('#product-showList .product-moreBtn').off('click');
						return false;
					} else {
						$('#product-showList .product-moreBtn').html('加载更多图片');
					}
					$('#product-showList .row').html(morePhotoHtml);
					$('.img-group img').on('load', function() {
						$(this).parent().find('.wrapper').remove();
						$(this).show();
					});
				}
			});
		}
	});
});