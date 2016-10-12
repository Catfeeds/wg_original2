(function($){
	var norms_wrap = $('#norms_wrap');
	var num_wrap = $('#num');
	$('input[id^=norms_]').bind('click',function(){
		var norms_name = $(this).next('label').text();//所点击的属性名
		var other_norms = $(this).parents('.norms_tr').siblings('.norms_tr').find('input[id^=norms_]');//另外总的属性
		var other_norms_details = [];//另一项属性已选项
		var norms_index = $(this).parents('.norms_tr').data('index');//判断点击位置
		var norms_id = $(this).data('id');//属性ID
		var this_norms = $(this).parents('.norms_tr').find('input[id^=norms_]:checked');//点击项已选INPUT
		var this_norms_details = [];
		//获取另一项已选规格
		var i=0;
		other_norms.each(function(index, el) {
			if($(this).is(':checked')){
				other_norms_details[i] = [$(this).next('label').text(),$(this).data('id')];
				i++;
			}
		});

		//判断已勾选的HTML是够生成
		if(norms_index == 0){
			var norms_tr = $('tr[data-colorid='+norms_id+']');
		}else if(norms_index == 1){
			var norms_tr = $('tr[data-formatid='+norms_id+']');
		}
		//当前属性已选项目
		this_norms.each(function(index, el) {
			this_norms_details[index] = [$(this).next('label').text(),$(this).data('id')];
		});
		if($(this).is(':checked')){
			var check_has_norms_tr;
			if(other_norms_details.length > 0){
				$.each(this_norms_details,function(index1, el1) {
					$.each(other_norms_details,function(index2, el2) {
						//判断点击位置
						if(norms_index == 0){
							options = {
								'colorid' : el1[1],
								'formatid' : el2[1],
								'colorName' : el1[0],
								'formatName' : el2[0],
							}
						}else if(norms_index == 1){
							options = {
								'colorid' : el2[1],
								'formatid' : el1[1],
								'colorName' : el2[0],
								'formatName' : el1[0],
							}
						}
						//判断该HTML是否已经生成过
						check_has_norms_tr = $('tr[data-colorid='+options['colorid']+'][data-formatid='+options['formatid']+']');
						if($.type(check_has_norms_tr.html()) == 'undefined'){
							options['id'] = 0;
							normsInjectHtml(normsJoinHtml(options));
						}else{
							check_has_norms_tr.show();
						}
					});
				});
			}else if($.type($('.norms_color').html()) =='undefined' || $.type($('.norms_format').html()) =='undefined'){
				$.each(this_norms_details,function(index1, el1) {
					//判断点击位置
					if($.type($('.norms_format').html()) =='undefined'){
						options = {
							'colorid' : el1[1],
							'colorName' : el1[0],
							'formatid' : 0,
							'formatName' : '',
						}
					}else if($.type($('.norms_color').html()) =='undefined'){
						options = {
							'colorid' : 0,
							'colorName' : '',
							'formatid' : el1[1],
							'formatName' : el1[0],
						}
					}
					//判断该HTML是否已经生成过
					check_has_norms_tr = $('tr[data-colorid='+options['colorid']+'][data-formatid='+options['formatid']+']');
					if($.type(check_has_norms_tr.html()) == 'undefined'){
						options['id'] = 0;
						normsInjectHtml(normsJoinHtml(options));
					}else{
						check_has_norms_tr.show();
					}
				});
			}
		}else{
			norms_tr.hide();
		}
		//判断规格是否显示
		if(this_norms_details.length != 0 || other_norms_details.length != 0){
			normsControl('off');
		}else{
			normsControl('on');
		}
	})
	//控制输入框
	function normsControl(option){
		if(option == 'off'){
			$('.norms_control').attr('readonly',true).css('background','#D0D0D0');
			numAssociated();
		}
		if(option == 'on'){
			$('.norms_control').attr('readonly',false).css('background','#fff');
		}
	}
	//初次登陆时显示属性编辑
	$(document).ready(function($) {
		if(pdataJson != null){
			if(pdataJson.length != 0){
				$.each(pdataJson,function(index, el) {
					options = {
						'colorid' : el['color'],
						'formatid' : el['format'],
					}
					$.extend(true, options, el);
					normsInjectHtml(normsJoinHtml(options));
				});
				normsControl('off');
			}
		}
	});
	//注入HTML
	var default_options = {
		'id' : 0,
		'color_name' : color_name,
		'format_name' : format_name,
		'pid' : pid,
		'price' : 0,
		'num' : 0,
	};
	function normsInjectHtml(content){
		num_wrap.val(0);
		if($.trim(norms_wrap.html()).length == 0){
			var norms_content = $("<td colspan='2'><table><tr><th>"+default_options['color_name']+"</th><th>"+default_options['format_name']+"</th><th>销售价<th>库存</th></tr></table></td>");
			norms_content.find('table').append(content);
			norms_wrap.html(norms_content);
		}else{
			norms_wrap.find('table').append(content);
		}
	}
	//拼接HTML
	function normsJoinHtml(options){
		var new_norms_content ='';
		$.extend(true, default_options, options);
		new_norms_content = "<tr class='norms_data' data-id='"+default_options['id']+"' data-pid='"+default_options['pid']+"' data-colorid="+default_options['colorid']+" data-formatid="+default_options['formatid']+"><td>"+default_options['colorName']+"</td><td>"+default_options['formatName']+"</td>";
		new_norms_content +="<td><input type='text' class='px validate' data-warn='销售价不能为空' value='"+default_options['price']+"' /></td><td><input type='text' class='px norms_num' value='"+default_options['num']+"' /></td></tr>";
		return new_norms_content;
	}

	//编辑规格库存
	$(document).on('keyup','.norms_num',function(){
		numAssociated();
	})
	//关联规格库存和总库存
	function numAssociated(){
		var total_num = 0;
		$('.norms_num').each(function(index, el) {
			var obj = $(el)
			if(obj.parents('.norms_data').is(":visible")){
				total_num += parseInt(obj.val());
			}	
		});
		num_wrap.val(total_num);
	}

	//保存按钮
	$(document).on('click','#save',function(){
		//判断是否填写规格
		if($('.norms_data').is(':visible')){
			$('input[id^=price]').removeClass('validate');
		}else{
			$('input[id^=price]').addClass('validate');
		}
		//基础判断
		var validate_item = $('.validate');
		var check_validata = 0;
		validate_item.each(function(index, el) {
			if (check_validata == 0) {
				validate_con = $(this).val();
				validate_warn = $(this).attr('data-warn');
				if (validate_con.length < 1 || validate_con == 0) {
					art.dialog({
						title: '消息提示',
						ok: true,
						width: 300,
						height: 200,
						content: validate_warn
					});
					check_validata = 1;
					$(el).focus();
				}
			}
		});
		if (check_validata) {
			return;
		}
		//判断库存
		var num = $("#num").val();
		if (isNaN(num)) {
			art.dialog({
				title: '消息提示',
				ok: true,
				width: 300,
				height: 200,
				content: '库存应该是为正整数'
			});
			return false;
		}
		//商品轮播图片
		var images_str = '';
		var imagesid_str = '';
		$('input[id^=image]').each(function($key) {
			if ($(this).val()) {
				images_str += $(this).val() + ',';
				imagesid_str += $(this).attr('imageid') + ',';
			}
		})
		$('#images').val(images_str);
		$('#imagesid').val(imagesid_str);
		//序列化FORM数据
		var data = $('#formID').serialize();
		var norms_data = [];
		$('.norms_data').each(function(index, el) {
			var obj = $(this);
			if(obj.is(':visible')){
				norms_data[index] = {
					format:obj.data('formatid'),
					id:obj.data('id'),
					pid:pid,
					color:obj.data('colorid'),
					price:obj.find('td:eq(2) input').val(),
					num:obj.find('td:eq(3) input').val(),
				};
			}
		});
		//重组FORM数据
		data +="&norms="+JSON.stringify(norms_data);
		//保存
		$.post('index.php?g=User&m=Store&a=productSave', data, function(response) {
			if (response.error_code == false) {
				art.dialog({
					title: '消息提示',
					content: response.msg,
					width: 300,
					height: 200,
					lock: true,
					ok: function() {
						this.time(3);
						location.href = href;
						return false;
					},
					cancelVal: '关闭'
				});
			} else {
				art.dialog({
					title: '消息提示',
					time: 2,
					width: 300,
					height: 200,
					content: response.msg
				});
			}
		}, 'json');
	});
})($)