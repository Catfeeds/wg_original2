<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<script src="<?php echo RES;?>/js/jquery-1.11.1.min.js" type="text/javascript"></script>
	<script src="<?php echo RES;?>/printArea/js/jquery.PrintArea.js" type="text/javascript"></script>
	<link rel="stylesheet" href="<?php echo RES;?>/printArea/css/printarea.css">
</head>
<body>
<!-- <input id="biuuu_button" type="button" value="打印"> -->
<div id="myPrintArea">
	<div class="header">
		<span class="logo">北溟鱼照相馆</span>
		<span class="orderid">编号:<item class="order-orderid"></item></span>
		<div class="clear"></div>
		<div class="header-text">照出人生新高度</div>
	</div>
	<div class="contetn">
		<div class="title">个人信息</div>
		<table>
			<tr>
				<td>姓名：</td>
				<td></td>
				<td>性别</td>
				<td></td>
			</tr>
			<tr>
				<td>联系方式：</td>
				<td></td>
				<td>微信</td>
				<td></td>
			</tr>
			<tr>
				<td>学校/地址</td>
				<td></td>
			</tr>
		</table>
	</div>
</div>
	<script>
	$(document).ready(function(){
	  $("input#biuuu_button").click(function(){

	  $("div#myPrintArea").printArea();

	});
	});

	</script>
</body>
</html>