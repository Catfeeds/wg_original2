$(function($){
	$("#footer").load("footer.html");
	$(document).on('pageInit','#reservation',function(){
		setTimeout(function(){
			$('#footer_appointment_tab').addClass('active').siblings().removeClass('active');
		},100)
	});
	$(document).on('pageInit','#index-page',function(){
		setTimeout(function(){
			$('#store_index_tab').addClass('active').siblings().removeClass('active');
		},100)
	});
	$(document).on('pageInit','#distribution_index_wrap',function(){
		setTimeout(function(){
			$('#distribution_index_tab').addClass('active').siblings().removeClass('active');
		},100)
	});
	$(document).on('pageInit','#my-orders',function(){
		setTimeout(function(){
			$('#footer_myorders_tab').addClass('active').siblings().removeClass('active');
		},100)
	});
	$.init();
})
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
