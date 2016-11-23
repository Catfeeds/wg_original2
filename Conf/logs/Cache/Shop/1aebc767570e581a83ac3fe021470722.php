<?php if (!defined('THINK_PATH')) exit();?><script src="<?php echo RES;?>/js/jquery-1.11.1.min.js" type="text/javascript"></script>
<script src="<?php echo RES;?>/printArea/js/jquery.PrintArea.js" type="text/javascript"></script>
<div><button class="btnGreen" id="order-print" style="    font-size: 1rem;
    margin: 0.8rem auto 0;
    display: block;">打印订单</button></div>
<div id="myPrintArea" style="display: none;">
<!-- <link rel="stylesheet" href="<?php echo RES;?>/printArea/css/printarea.css"> -->
<style>
	.clear {
	  clear: both; }

	.order-print-wrap {
	  font-size: 1rem; }
	  .order-print-wrap .header .order-print-logo {
	    float: left;
	    font-size: 1.5rem; }
	  .order-print-wrap .header .orderid {
	    float: right;
	    margin-right: 8rem; }
	  .order-print-wrap .header .header-text {
	    text-align: center;
	    position: relative;
	    padding: 0.4rem 0;
	    border-bottom: 1px solid #000;
	    width: 60%;
	    margin: 0 auto;
	    			/*&:after{
	    				content:'';
	    				position: absolute;
	    				bottom: 0;
	    				left: 0;
	    				right: 0;
	    				bottom: 0;
	    				height: 1px;
	    				width: 70%;
	    				margin: 0 auto;
	    				background:-webkit-gradient(
	    					linear ,0 50%,100% 50%,
	                        color-stop(0,rgb(255,255,255)),
	                        color-stop(0.5,rgba(0,0,0,0.8)),
	                        color-stop(1,rgb(255,255,255))
	                    );
	    			}*/ }
	  .order-print-wrap .content tr {
	    height: 2.4rem; }
	  .order-print-wrap .content .order-print-type-item {
	    display: inline-block;
	    min-width: 14rem;
	    margin: 0.5rem 0.5rem 0.5rem 0; }
	  .order-print-wrap .content .info-item {
	    position: relative;
	    padding: 1rem 0;
	    border-bottom: 1px solid #000;
	    			/*&:after{
	    				content:'';
	    				position: absolute;
	    				bottom: 0;
	    				left: 0;
	    				height: 1px;
	    				width: 100%;
	    				background-color: #000;
	    				background:-webkit-gradient(
	    					linear ,0 50%,100% 50%,
	                        color-stop(0,rgb(255,255,255)),
	                        color-stop(0.5,rgb(0,0,0)),
	                        color-stop(1,rgb(255,255,255))
	                    );
	    			}*/ }
	    .order-print-wrap .content .info-item .order-print-title {
	      font-size: 1.5rem; }
	  .order-print-wrap .content input[type="checkbox"] {
	    vertical-align: middle;
	    width: 1rem;
	    height: 1rem; }
	  .order-print-wrap .content .order-print-vm {
	    vertical-align: middle; }
	  .order-print-wrap .content .border-item {
	    display: inline-block;
	    min-width: 6rem;
	    border-bottom: 1px solid #000;
	    text-align: center; }
	  .order-print-wrap .content .color-item {
	    margin-right: 2rem; }
	  .order-print-wrap .content .print-title2 {
	    margin: 1rem 0; }
	  .order-print-wrap .content .customer-write {
	    float: right;
	    margin: 2rem 0; }
	  .order-print-wrap .content .content-footer {
	    margin: 1.5rem 0 0 0; }
	  .order-print-wrap .content .order-print-ls1 {
	    letter-spacing: 0.34rem; }
	  .order-print-wrap .content .order-print-ls2 {
	    letter-spacing: 0.97rem; }
	  .order-print-wrap table {
	    width: 100%; }

	/*# sourceMappingURL=printarea.css.map */

</style>
	<div class="order-print-wrap">
	<div class="header">
		<span class="order-print-logo">北溟鱼照相馆</span>
		<span class="orderid">
			编号:
			<item class="order-serialNumber"></item>
		</span>
		<div class="clear"></div>
		<div class="header-text">照出人生新高度</div>
	</div>
	<div class="content">
		<div class="info-item">
			<div class="order-print-title">个人信息</div>
				<table>
					<colgroup>
						<col style="width: 50%;">
						<col style="width: 50%;">
					</colgroup>
					<tr>
						<td>
							<label style="letter-spacing: 1.24rem;">姓名</label>：
							<item class="border-item"><?php echo ($thisOrder["truename"]); ?></item>
						</td>
						<td>
							性别：
							<item class="border-item">
								<?php if(($thisOrder["sex"]) == "1"): ?>男<?php else: ?>女<?php endif; ?>
							</item>
						</td>
					</tr>
					<tr>
						<td>
							<label style="letter-spacing: 0.12rem;">联系方式：</label>
							<item class="border-item">
								<?php echo ($thisOrder["tel"]); ?>
							</item>
						</td>
						<td>
							邮箱：
							<item class="border-item">
								<?php echo ($thisOrder["email"]); ?>
							</item>
						</td>
					</tr>
					<tr>
						<td colspan="2">学校/地址：
								<?php echo ($thisOrder["address"]); ?>
						</td>
					</tr>
				</table>
		</div>
		<div class="info-item">
			<div class="order-print-title">订单信息</div>
			<table>
				<colgroup>
					<col style="width: 14%;">
				</colgroup>
				<tr>
					<td class="order-print-ls1">订单号：</td>
					<td><?php echo ($thisOrder["orderid"]); ?></td>
				</tr>
				<tr>
					<td>预约时间：</td>
					<td><?php echo (date("Y-m-d H:i:m",$thisOrder["rtime"])); ?></td>
				</tr>
				<tr>
					<td class="order-print-ls2">背景：</td>
					<td>
						<item class="color-item">
							<label class="order-print-vm" for="grey">灰色</label>
							<input id='grey' type="checkbox">
						</item>
						<item class="color-item">
							<label class="order-print-vm" for="white">白</label>
							<input id='white' type="checkbox">
						</item>
						<item class="color-item">
							<label class="order-print-vm" for="yellow">芽黄</label>
							<input id='yellow' type="checkbox">
						</item>
						<item class="color-item">
							<label class="order-print-vm" for="red">红</label>
							<input id='red' type="checkbox">
						</item>
						<item class="color-item">
							<label class="order-print-vm" for="blue">蓝</label>
							<input id='blue' type="checkbox">
						</item>
					</td>
				</tr>
				<tr class="order-print-ls2">
					<td>备注：<?php echo ($thisOrder["remark"]); ?></td>
					<td></td>
				</tr>
			</table>
			<div class="print-title2">拍摄套系</div>
			<!-- <table> -->
				<!-- <colgroup>
					<col style="width: 33%;">
					<col style="width: 33%;">
					<col style="width: 33%;">
				</colgroup> -->
				<div class="order-print-type">
					<?php if(is_array($products)): $i = 0; $__LIST__ = $products;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($i % 2 );++$i;?><!-- <?php if(($key%3) == 1): ?><tr><?php endif; ?> -->
						<span class="order-print-type-item">
							<input id="half-pic" type="checkbox" checked>
							<label for="half-pic"><?php echo ($list["name"]); ?></label>
							<item class="border-item"><?php echo ($list["price"]); ?>元</item>
						</span>
						<!-- <?php if(($key%3) == 0): ?></tr><?php endif; ?> --><?php endforeach; endif; else: echo "" ;endif; ?>
				</div>
				<!-- <tr>
					<td>
						<input id="half-pic" type="checkbox">
						<label for="half-pic">半身形象照</label>
						<item class="border-item"></item>
					</td>
					<td>
						<input id="body-pic" type="checkbox">
						<label for="body-pic">全身形象照</label>
						<item class="border-item"></item>
					</td>
					<td>
						<input id="profile-pic" type="checkbox">
						<label for="profile-pic">证件照</label>
						<item class="border-item"></item>
					</td>
				</tr>
				<tr>
					<td>
						<input id="marry-pic" type="checkbox">
						<label for="marry-pic">结婚登记照</label>
						<item class="border-item"></item>
					</td>
					<td>
						<input id="na-pic" type="checkbox">
						<label for="na-pic">NaturalFace</label>
						<item class="border-item"></item>
					</td>
					<td>
						<input id="personal-pic" type="checkbox">
						<label for="personal-pic">单人艺术照</label>
						<item class="border-item"></item>
					</td>
				</tr>
				<tr>
					<td>
						<input id="more-pic" type="checkbox">
						<label for="more-pic">亲子照、情侣照、闺蜜照</label>
						<item class="border-item"></item>
					</td>
					<td>
						<input id="team-pic" type="checkbox">
						<label for="team-pic">团队照、全家福</label>
						<item class="border-item"></item>
					</td>
					<td></td>
				</tr> -->
			<!-- </table> -->
			<div class="print-title2">
				付款方式：
				<span class="pay-way">
					<input id="pay-way" type="checkbox" checked>
					<label for="pay-way">微信支付</label>
					<item class="border-item"><?php echo ($thisOrder["price"]); ?>元</item>
				</span>
			</div>
		</div>
		<div class="content-footer">
			<table>
				<tr>
					<td>前台：</td>
					<td><item class="border-item">&nbsp;</item></td>
					<td>化妆师：</td>
					<td><item class="border-item">&nbsp;</item></td>
					<td>摄影师：</td>
					<td><item class="border-item">&nbsp;</item></td>
					<td>后期师：</td>
					<td><item class="border-item">&nbsp;</item></td>
				</tr>
				<tr>
					<td>DS：</td>
					<td><item class="border-item">&nbsp;</item></td>
					<td>DS：</td>
					<td><item class="border-item">&nbsp;</item></td>
					<td>DS：</td>
					<td><item class="border-item">&nbsp;</item></td>
					<td>DS：</td>
					<td><item class="border-item">&nbsp;</item></td>
				</tr>
			</table>
			<div class="customer-write">
				客户签名：<div class="border-item" style="width:8rem;"></div>
			</div>
		</div>
	</div>
	</div>
</div>
<script>
	$(document).ready(function(){
	  $("#order-print").click(function(){

	  $("#myPrintArea").printArea();

	});
	});

	</script>