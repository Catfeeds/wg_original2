$(function(){
	var certification = 1;
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
						url:total_url+'index.php?g=Wap&m=Distribution&a=checkLogin',
						dataType:'json',
						async:false,
						success:function(data){
							if(!data || data.status == -1){
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
	$(document).on('pageInit','#distribution_index_wrap',function(){
		//获取个人资料
		$.getJSON(total_url+'index.php?g=Wap&m=Distribution&a=myInfo', function(json, textStatus) {
			$('#my-username').text(json.data.username);
			if(json.data.headimgurl != ''){
				$('#my-headimgurl').attr('src',json.data.headimgurl);
				$('#my-coupons-nums').text(json.data.cnums);
			}
		});
		//退出登陆
		$(document).on('click','#login_out',function(){
			var buttons1 = [
		        {
		          text: '退出登陆',
		          bold: true,
		          color: 'danger',
		          onClick: function() {
		            $.ajax({
		            	url:total_url+'index.php?g=Wap&m=Distribution&a=loginOut',
		            	dataType:'json',
		            	success:function(data){
		            		console.log(data);
		            		console.log(data.status);
		            		if(data.status == 1){
		            			cookie.set('loginout',1);
		            			location.href = "login.html";
		            		}else{
		            			$.alert('退出失败');
		            		}
		            	}
		            });
		          }
		        }
		      ];
		      var buttons2 = [
		        {
		          text: '取消',
		          bg: 'danger'
		        }
		      ];
		      var groups = [buttons1, buttons2];
		      $.actions(groups);
		})
	})

	//低价卷
	$(document).on('pageInit','#my-coupons',function(){
		//读取我的抵扣卷
		$.getJSON(total_url+'index.php?g=Wap&m=Distribution&a=myCoupons',function(json){
			var unuselessHtml = '';
			var uselessHtml = '';
			$.each(json.data,function(index,el){
				console.log(el);
				if(el.status == 3){
					unuselessHtml += '<div class=\"content-block\"><div class=\"myCoupons-listWrap\"><span class=\"myCoupons-price\">￥<b>' +
					el.price + '</b></span><div class=\"myCoupons-couponInfo\"><h3>抵价券</h3><p class=\"myCoupons-couponDate\"><span></span>全国门店通用</p><p class=\"myCoupons-couponStore\"><span></span>截止日期: ' +
					el.endtime + '</p><span class=\"myCoupons-expiredIcon\"></span>' + '</div></div></div>';
				}else{
					uselessHtml += '<div class=\"content-block\"><div class=\"myCoupons-listWrap off\"><span class=\"myCoupons-price\">￥<b>' +
					el.price + '</b></span><div class=\"myCoupons-couponInfo\"><h3>抵价券</h3><p class=\"myCoupons-couponDate\"><span></span>全国门店通用</p><p class=\"myCoupons-couponStore\"><span></span>截止日期: ' +
					el.endtime + '</p><span class=\"myCoupons-usedIcon\"></span>' + '</div></div></div>';
				}
			})
			//未使用
			$('#unused-coupons').html(unuselessHtml);
			//已使用
			$('#used-coupons').html(uselessHtml);
		})
		//添加抵扣卷
		$(document).on('click','#my-coupons #add-new-coupons',function(){
			$.prompt('填写兑换码', function (value) {
		        $.ajax({
		        	url: total_url + 'index.php?g=Wap&m=Distribution&a=addCoupons',
		        	data:{code:value},
		        	dataType:'json',
		        	type:'post',
		        	success:function(data){
		        		$.alert(data.info);
		        		if(data.data.status == 0 && data.status == 1){
		        			var unuselessHtml = '';
		        			unuselessHtml += '<div class=\"content-block\"><div class=\"myCoupons-listWrap\"><span class=\"myCoupons-price\">￥<b>' +
		        			data.data.price + '</b></span><div class=\"myCoupons-couponInfo\"><h3>抵价券</h3><p class=\"myCoupons-couponDate\"><span></span>全国门店通用</p><p class=\"myCoupons-couponStore\"><span></span>截止日期: ' +
		        			data.data.endtime + '</p><span class=\"myCoupons-expiredIcon\"></span>' + '</div></div></div>';
		        			$('#unused-coupons').append(unuselessHtml);
		        		}
		        	}
		        });
		    });
		})
	})

	//预约资料
	$(document).on('pageInit', '#myInfo_wrap', function(e, id, page) {
		$.showPreloader();
		//获取预约资料
		$.getJSON(total_url+'index.php?g=Wap&m=Distribution&a=myInfo', function(json, textStatus) {
			$('#myInfo_myTruename').val(json.data.truename);
			$('#myInfo_myBirthday').val(json.data.birth);
			var str = '';
			if(json.data.sex == 1){
				str += '<option class="man" value="1" selected>男</option>';
				str += '<option class="girl" value="0">女</option>';
			}else{
				str += '<option class="man" value="1">男</option>';
				str += '<option class="girl" value="0" selected>女</option>';
			}
			$('#myInfo_mySex').html(str);
			$('#myInfo_myEmail').val(json.data.email);
			// if (json.data.sex == 1) {
			// 	$('#myInfo_mySex').find('.man').attr("selected", true);
			// } else {
			// 	$('#myInfo_mySex').find('.girl').attr("selected", true);
			// }
			$.hidePreloader();
		});
		//保存预约资料修改
		$(document).on('click', '#save_my_info', function() {
			if (validation()) {
				var data = $('#myInfo_form').serialize();
				data = decodeURIComponent(data,true); 
				$.ajax({
					url:total_url+'index.php?g=Wap&m=Distribution&a=saveMyInfo',
					data:{data:data},
					dataType:'json',
					type:'post',
					success:function(data){
						console.log(data);
						if(data.status == 1){
							$.router.load('Distribution_index.html');
						}
					}
				});
			}
		})
	})
	//修改密码
	$(document).on('pageInit','#changePassword',function(){
		$(document).on('click','#change_password_submit',function(){
			if (validation()) {
				if($('#newPassword').val() != $('#rePassword').val()){
					$.alert('两次密码输入不相同');
					return false;
				}
				var data = $('#change_password_form').serialize();
				$.ajax({
					url:total_url+'index.php?g=Wap&m=Distribution&a=changePasswrod',
					data:{data:data},
					dataType:'json',
					type:'post',
					success:function(data){
						console.log(data);
						console.log(data);
						$.alert(data.info);
						if(data.status == 1){
							$.router.load('Distribution_index.html');
						}
					}
				});
			}
		})
	})
	//意见反馈
	$(document).on('pageInit','#feedBack',function(){
		var wrap = $('#feedBack');
		wrap.find('#feedBack-sendBtn').off('click').on('click',function(){
			var con = wrap.find('#feedBack-sendCon').val();
			console.log(con);
			$.ajax({
				url:total_url+'index.php?g=Wap&m=Distribution&a=feedBack',
				data:{con:con},
				dataType:'json',
				type:'post',
				success:function(data){
					console.log(data);
					$.alert(data.info,function(){
						$.router.load('Distribution_index.html');
					});
				}
			})
		})
	})
	//控件点击效果
	$(document).on('click','.wg_form_message_wrap',function(){
		$(this).find('input').focus();
	    $(this).addClass('wg_item_icon_input_active').siblings('.wg_form_message_wrap').removeClass('wg_item_icon_input_active');

	})
	//输入校验
	function validation(){
		var validation = $('.validation');
		var val = 1;
		validation.each(function(index, el) {
			if ($(this).val() == '') {
				var warn = $(this).attr('placeholder');
				$.alert(warn);
				val = 0;
				return false;
			}
		});
		if(val == 1){
			return true;
		}else{
			return false;
		}
	}
	$.init();
})
