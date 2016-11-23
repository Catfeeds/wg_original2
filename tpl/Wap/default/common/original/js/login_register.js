<<<<<<< HEAD
$(function($){
	$(document).on('click','.wg_form_message_wrap',function(){
		$(this).find('input').focus();
	    $(this).addClass('wg_item_icon_input_active').siblings('.wg_form_message_wrap').removeClass('wg_item_icon_input_active');
	})
	//登陆页面
	$(document).on('pageInit','#login',function(){
		var wrap = $('#login');
		//登陆
		wrap.find('#login-submit').off('click').on('click',function(){
			var validation = $('.validation');
			var val = 1;
			validation.each(function(index, el) {
				if($(this).val() == ''){
					var warn = $(this).attr('placeholder');
					 $.alert(warn);
					val = 0;
					return false;
=======
$(document).on('click','.wg_form_message_wrap',function(){
	$(this).find('input').focus();
    $(this).addClass('wg_item_icon_input_active').siblings('.wg_form_message_wrap').removeClass('wg_item_icon_input_active');
})
//登陆
$(document).on('click','#login-submit',function(){
	var validation = $('.validation');
	var val = 1;
	validation.each(function(index, el) {
		if($(this).val() == ''){
			var warn = $(this).attr('placeholder');
			 $.alert(warn);
			val = 0;
			return false;
		}
	});
	if(val == 1){
		var data = $('#login_form').serialize();
		console.log(data);
		$.ajax({
			url:total_url+'index.php?g=Wap&m=Distribution&a=login',
			data:{data:data},
			dataType:'json',
			type:'post',
			success:function(data){
				console.log(data);
				if(data.status == 1){
					location.href="Distribution_index.html";
				}else{
					$.alert(data.info);
>>>>>>> 01e200e4f8a1295bfce0a15384da812e38d13ba2
				}
			});
			if(val == 1){
				var data = $('#login_form').serialize();
				$.ajax({
					url:total_url+'index.php?g=Wap&m=Distribution&a=login',
					data:{data:data},
					dataType:'json',
					type:'post',
					success:function(data){
						if(data.status == 1){
							cookie.set('loginout',2);
							location.href="Distribution_index.html";
						}else{
							$.alert(data.info);
						}
					}
				})
			}
		})
		//注册跳转
		$('#register-btn').click(function(){
			$.router.load('register.html')
		})
		//首页点击忘记密码
		wrap.find('#forgetPasswordBtn').off('click').on('click',function(){
			var buttons1 = [
		        {
		          text: '通过手机号找回密码',
		          onClick: function() {
		            $.router.load('findPassWord.html');
		          }
		        },
		        {
		          text: '账号申诉',
		          bold: true,
		          color: 'danger',
		          onClick: function() {
		            $.router.load('appeal.html');
		          }
		        },
		      ];
		      var buttons2 = [
		        {
		          text: '取消',
		          color: 'blue'
		        }
	      ];
	      var groups = [buttons1, buttons2];
	      $.actions(groups);
		})
	})
	$(document).on('pageAnimationEnd','#register',function(e, id, page){
		console.log('aa');
		$('#login').html('');
	})
	//注册页面
	$(document).on('pageInit','#register',function(){
		var wrap = $('#register');
		var send = wrap.find('#send-sms');
		var control = 1;
		//发送短信验证码
		send.on('click', function() {
			var tele = $("#username").val();
			if (tele == '') {
				alert("请输入手机号码");
				return false;
			}
			var num = cookie.get('count_nums') ? cookie.get('count_nums') : 60;
			if (control) {
				coutdown = setInterval(function() {
					num--;
					cookie.set('count_nums', num);
					if (num == 0) {
						control = 1;
						send.text('发送验证码');
						cookie.set('count_nums', '');
						clearInterval(coutdown);
						return false;
					} else {
						control = 0;
						send.text('(' + num + ')秒后再发送');
					}
				}, 1000);
			}
			if (control) {
				$.ajax({
					url: total_url+'index.php?g=Wap&m=Distribution&a=sendSms',
					data: {
						mobile: tele
					},
					type: "post",
					dataType: "json",
					success: function(data) {
						if(data.info != 0){
							$.alert('发送失败');
						}else{
							$.alert('发送成功');
						}
						console.log(data);
					}
				});
			}
		})
		if (cookie.get('count_nums')) {
			num = cookie.get('count_nums');
			send.text('(' + num + ')秒后再发送');
			coutdown = setInterval(function() {
				num--;
				cookie.set('count_nums', num);
				if (num <= 0) {
					control = 1;
					send.text('发送验证码');
					cookie.set('count_nums', '');
					clearInterval(coutdown);
					return false;
				} else {
					control = 0;
					send.text('(' + num + ')秒后再发送');
				}
			}, 1000);
		}

		wrap.find('#username').on('blur',function(){
			var check_username = $(this).val();
			
		})

		//提交注册
		wrap.find('#register-submit').off('click').on('click',function(){
			var validation = $('.validation');
			var val = 1;
			validation.each(function(index, el) {
				if($(this).val() == ''){
					var warn = $(this).attr('placeholder');
					 $.alert(warn);
					val = 0;
					return false;
				}
			});
			if(val == 1){
				var data = $('#register_from').serialize();
				$.ajax({
					url:total_url+'index.php?g=Wap&m=Distribution&a=register',
					data:{data:data},
					dataType:'json',
					type:'post',
					success:function(data){
						if(data.status ==1){
							$.alert('注册成功',function(){
								$.router.load('login.html');
							})
						}else{
							$.alert(data.info);
						}
					}
				})
			}
		})
	})
	//忘记密码页面
	$(document).on('pageInit','#findPassword',function(){
		//发送短信验证码
		var send = $('#send-sms');
		var control = 1;
		send.on('click', function() {
			var tele = $("#username").val();
			if (tele == '') {
				alert("请输入手机号码");
				return false;
			}
			var num = cookie.get('count_nums') ? cookie.get('count_nums') : 60;
			if (control) {
				coutdown = setInterval(function() {
					num--;
					cookie.set('count_nums', num);
					if (num == 0) {
						control = 1;
						send.text('发送验证码');
						cookie.set('count_nums', '');
						clearInterval(coutdown);
						return false;
					} else {
						control = 0;
						send.text('(' + num + ')秒后再发送');
					}
				}, 1000);
			}
			if (control) {
				$.ajax({
					url: total_url+'index.php?g=Wap&m=Distribution&a=sendSms',
					data: {
						mobile: tele
					},
					type: "post",
					dataType: "json",
					success: function(data) {
						console.log(data);
					}
				});
			}
		})
		if (cookie.get('count_nums')) {
			num = cookie.get('count_nums');
			send.text('(' + num + ')秒后再发送');
			coutdown = setInterval(function() {
				num--;
				cookie.set('count_nums', num);
				if (num <= 0) {
					control = 1;
					send.text('发送验证码');
					cookie.set('count_nums', '');
					clearInterval(coutdown);
					return false;
				} else {
					control = 0;
					send.text('(' + num + ')秒后再发送');
				}
			}, 1000);
		}
<<<<<<< HEAD
		//提交重置
		$(document).on('click','#findPassword-submit',function(){
			console.log('aa');
			var validation = $('.validation');
			var val = 1;
			validation.each(function(index, el) {
				if($(this).val() == ''){
					var warn = $(this).attr('placeholder');
					 $.alert(warn);
					val = 0;
					return false;
				}
			});
			if(val == 1){
				var data = $('#findPassword_from').serialize();
				$.ajax({
					url:total_url+'index.php?g=Wap&m=Distribution&a=findPassword',
					data:{data:data},
					dataType:'json',
					type:'post',
					success:function(data){
						if(data.status ==1){
							$.alert('重置成功',function(){
								$.router.load('login.html');
							})
						}else{
							$.alert(data.info);
						}
					}
				})
			}
		})
	})
	$.init();
=======
	});
	if(val == 1){
		var data = $('#register_from').serialize();
		$.ajax({
			url:total_url+'index.php?g=Wap&m=Distribution&a=register',
			data:{data:data},
			dataType:'json',
			type:'post',
			success:function(data){
				if(data.status ==1){
					$.alert('注册成功',function(){
						$.router.load('login.html');
					})
				}else{
					$.alert(data.info);
				}
			}
		})
	}
>>>>>>> 01e200e4f8a1295bfce0a15384da812e38d13ba2
})