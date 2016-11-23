<?php if (!defined('THINK_PATH')) exit();?><script src="<?php echo RES;?>/js/jquery-1.11.1.min.js" type="text/javascript"></script>
<script src="<?php echo RES;?>/printArea/js/jquery.PrintArea.js" type="text/javascript"></script>
<div><button class="btnGreen" id="order-print" style="    font-size: 1rem;
    margin: 0.8rem auto 0;
    display: block;">打印订单</button></div>
<div id="myPrintArea" style="display: block;">
<link rel="stylesheet" href="<?php echo RES;?>/printArea/css/printarea.css">
<style>
	
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