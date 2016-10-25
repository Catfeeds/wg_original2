$(function($){
	//判断账号登陆
	$(document).ready(function($) {
		//判断授权
		$.ajax({
			url: total_url + 'index.php?g=Wap&m=Distribution&a=checkAuth',
			dataType: 'json',
			success: function(data) {
				if (data.data == 'needauth') {
					var href = 'http://bmy.tzwg.net/index.php?g=Wap&m=Distribution&a=authorization&bmyquth=1&href='+window.location.href;
					location.href = href;
				}
			}
		});
		//判断账号登陆
		$.ajax({
			url:total_url+'index.php?g=Wap&m=Distribution&a=checkLogin',
			dataType:'json',
			success:function(data){
				if(!data || data.status == -1){
					location.href = "login.html";
				}
			}
		});
	});
	$(document).on('pageInit', '#myInfo_wrap', function(e, id, page) {
		$.showPreloader();
		$.getJSON(total_url+'index.php?g=Wap&m=Distribution&a=myInfo', function(json, textStatus) {
			$('#myInfo_myTruename').val(json.data.truename);
			$('#myInfo_myBirthday').val(json.data.birth);
			if (json.data.sex == 1) {
				$('#myInfo_mySex').find('.man').attr("selected", true);
			} else {
				$('#myInfo_mySex').find('.girl').attr("selected", true);
			}
			$.hidePreloader();
		});
	})
	$(document).on('click', '#save_my_info', function() {
		if (validation()) {
			var data = $('#myInfo_form').serialize();
			$.ajax({
				url:total_url+'index.php?g=Wap&m=Distribution&a=saveMyInfo',
				data:{data:data},
				dataType:'json',
				type:'post',
				success:function(data){
					console.log(data);
					$.alert(data.info);
					if(data.status == 1){
						$.router.load('Distribution_index.html');
					}
				}
			});
		}
	})
	$(document).on('click','.wg_form_message_wrap',function(){
		$(this).find('input').focus();
	    $(this).addClass('wg_item_icon_input_active').siblings('.wg_form_message_wrap').removeClass('wg_item_icon_input_active');

	})
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
					$.alert(data.info);
					if(data.status == 1){
						$.router.load('Distribution_index.html');
					}
				}
			});
		}
	})
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