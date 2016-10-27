$(function($){
	$(document).on('click','.wg_form_message_wrap',function(){
		$(this).find('input').focus();
	    $(this).addClass('wg_item_icon_input_active').siblings('.wg_form_message_wrap').removeClass('wg_item_icon_input_active');
	})
	//登陆页面
	$(document).on('pageInit','#login',function(){
		var wrap = $('#login');
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
						}
					}
				})
			}
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
	//注册页面
	$(document).on('pageInit','#register',function(){
		//提交注册
		$(document).on('click','#register-submit',function(){
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
})