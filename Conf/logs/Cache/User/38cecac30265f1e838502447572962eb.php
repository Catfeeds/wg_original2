<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<script src="<?php echo RES;?>/js/jquery-1.11.1.min.js" type="text/javascript"></script>
	<script src="<?php echo RES;?>/printArea/js/jquery.PrintArea.js" type="text/javascript"></script>
<body>
	 <input id="biuuu_button" type="button" value="打印">
	
	<div id="myPrintArea">aaa</div>
	<script>
	$(document).ready(function(){
	  $("#biuuu_button").click(function(){

	  $("#myPrintArea").printArea();

	});
	});

	</script>
</body>
</html>