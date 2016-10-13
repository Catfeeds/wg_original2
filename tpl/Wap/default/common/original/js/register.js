$('#register-submit').click(function(){
	window.history.go(-1);
	location.href="login.html";
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
			url:'http://127.0.0.1/wg_original2/index.php?g=Wap&m=Distribution&a=register',
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
