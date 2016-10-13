$('.wg_form_message_wrap').click(function(){
	$(this).find('input').focus();
    $(this).addClass('wg_item_icon_input_active').siblings('.wg_form_message_wrap').removeClass('wg_item_icon_input_active');
})
$('#login-submit').click(function(){
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
			url:'http://127.0.0.1/wg_original2/index.php?g=Wap&m=Distribution&a=login',
			data:{data:data},
			dataType:'json',
			type:'post',
			success:function(data){
				console.log(data);
			}
		})
	}
})