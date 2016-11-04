$(function($){
	//关闭蒙版
	$(document).on('click','.mask-wrap .close-mask',function(){
		$('.mask-wrap').remove();
	})
	//底部加载HTML
	
	// $("#footer").load("footer.html");
	$(document).on('pageInit','#reservation',function(){
		$('#reservation').append(addFooter(2));
	});
	$(document).on('pageInit','#index-page',function(){
		$('#index-page').append(addFooter(1));
	});
	$(document).on('pageInit','#distribution_index_wrap',function(){
		$('#distribution_index_wrap').append(addFooter(4));
	});
	$(document).on('pageInit','#my-orders',function(){
		$('#my-orders').append(addFooter(3));
	});
	$(document).on('pageInit','#product-details',function(){
		$('#product-details').append(addFooter(1));
	});
	/*
		*底部授权和登陆判断
		*check_auth表示需要授权
		*loginout为2是表示已经登陆
		*赋值data_href为了授权之后跳转回原先需要跳转到的页面
	*/
	$(document).on('click','.foot-jump',function(event){
		var data_href
		if(cookie.get('loginout') == 2){
			data_href = $(this).attr('data-href');
		}else{
			data_href = "login.html";
		}
		if($(this).hasClass('check_auth')){
			$.ajax({
				url: total_url + 'index.php?g=Wap&m=Distribution&a=checkAuth',
				dataType: 'json',
				async:false,
				success: function(data) {
					if (data.data == 'needauth') {
						certification = 0;
						var href = 'http://bmy.tzwg.net/index.php?g=Wap&m=Distribution&a=authorization&bmyquth=1&href='+total_url+'tpl/Wap/default/'+data_href;
						location.href = href;
					}else{
						if(cookie.get('loginout') == 2){
							location.href = data_href;
						}else{
							location.href = "login.html";
						}
					}
				}
			});
		}else{
			if(cookie.get('loginout') == 2){
				location.href = data_href;
			}else{
				location.href = "login.html";
			}
		}
	})
	// $.init();
})
function addFooter(num){
	var foot = '';
	var type1 ='',type2='',type3='',type4='';
	switch(num){
		case 1:
			type1 ='active';type2='';type3='';type4='';
			break;
		case 2:
			type1 ='';type2='active';type3='';type4='';
			break;
		case 3:
			type1 ='';type2='';type3='active';type4='';
			break;
		case 4:
			type1 ='';type2='';type3='';type4='active';
			break;
	}
	foot += '<nav class="bar bar-tab">';
	foot += '  <a class="tab-item external '+type1+'" href="Store_index.html"  data-href="Store_index.html" id="store_index_tab">';
	foot += '    <span class="icon icon-home"></span>';
	foot += '    <span class="tab-label">展示</span>';
	foot += '  </a>';
	foot += '  <span class="tab-item external foot-jump check_auth '+type2+'" href="javascript:;" data-href="Appointment.html" id="footer_appointment_tab">';
	foot += '    <span class="icon icon-star"></span>';
	foot += '    <span class="tab-label">预约</span>';
	foot += '  </span>';
	foot += '  <span class="tab-item external foot-jump check_auth '+type3+'" href="javascript:;" data-href="myorders.html" id="footer_myorders_tab">';
	foot += '    <span class="icon icon-cart"></span>';
	foot += '    <span class="tab-label">订单</span>';
	foot += '  </span>';
	foot += '  <span class="tab-item external foot-jump check_auth '+type4+'" href="javascript:;" data-href="Distribution_index.html" id="distribution_index_tab">';
	foot += '    <span class="icon icon-me"></span>';
	foot += '    <span class="tab-label">我的</span>';
	foot += '  </span>';
	foot += '</nav>';
	return foot;
}
function CreateMask(str){
	var html = '';
	html += '<div class="mask-wrap">';
    html += '    <div class="mask-bg"></div>';
    html += '    <div class="mask-content">';
    html += '        <div class="meal-choose">' + str;
    html += '        </div>';
    html += '    </div>';
    html += '</div>';
    return html;
}

//删除数组
	// Array.prototype.indexOf = function(val) {
 //        for (var i = 0; i < this.length; i++) {
 //            if (this[i] == val) return i;
 //        }
 //        return -1;
 //    };
 //    Array.prototype.remove = function(val) {
 //        var index = this.indexOf(val);
 //        if (index > -1) {
 //            this.splice(index, 1);
 //        }
 //    };
