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
		$.ajax({
			url:total_url+'index.php?g=Wap&m=Distribution&a=login',
			data:{data:data},
			dataType:'json',
			type:'post',
			success:function(data){
				console.log(data);
				if(data.status == 1){
					//location.href="Distribution_index.html";
				}else{
					$.alert(data.info);
				}
			}
		})
	}
})

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
				location.href="login.html";
				console.log(data);
			}
		})
	}
})