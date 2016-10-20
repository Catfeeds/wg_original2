<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN""http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		
		<title><?php echo C('site_title');?> <?php echo C('site_name');?></title>
		<meta name="keywords" content="<?php echo C('keyword');?>" />
        <meta name="description" content="<?php echo C('content');?>" />
		<link rel="stylesheet" href="<?php echo RES;?>/css/reset.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="<?php echo RES;?>/css/login.css" type="text/css" media="screen" />
		<script type="text/javascript" src="<?php echo RES;?>/js/jquery.js"></script>
		
	</head>
	<body id="login">
		
		<div id="login-wrapper" class="png_bg">
			<div id="login-top">
			
				<h1>微信公众管理平台</h1>
				<!-- Logo (221px width) -->
				<img id="logo" src="<?php echo RES;?>/images/login_logo.png" alt="Simpla Admin logo" />
			</div> <!-- End #logn-top -->
			
			<div id="login-content">
				
				<form action="<?php echo U('Users/checklogin');?>" enctype="multipart/form-data" id="registerform" name="register" autocomplete="off" method="post">
					
					<p>
						<label>用户名：</label>
						<input class="text-input" name="username" type="text" />
					</p>
					<div class="clear"></div>
					<p>
						<label>密&nbsp;&nbsp;&nbsp;码：</label>
						<input class="text-input" name="password" type="password" />
					</p>
					<div class="clear"></div>
					<p>
						<input class="button" type="submit" value="登录" />
					</p>
					
				</form>
			</div> <!-- End #login-content -->
			
		</div> <!-- End #login-wrapper -->
		
  </body>
  </html>