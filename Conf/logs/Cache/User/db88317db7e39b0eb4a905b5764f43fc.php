<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title> <?php echo ($f_siteTitle); ?> <?php echo ($f_siteName); ?></title>
<meta name="keywords" content="<?php echo ($f_metaKeyword); ?>" />
<meta name="description" content="<?php echo ($f_metaDes); ?>" />
<meta http-equiv="MSThemeCompatible" content="Yes" />
<script>var SITEURL='';</script>

<script src="<?php echo RES;?>/js/common.js" type="text/javascript"></script>
</head>
<body id="nv_member" class="pg_CURMODULE">
<div id="wp" class="wp"><link href="<?php echo RES;?>/css/style-1.css?id=103" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="<?php echo RES;?>/css/style_2_common.css?BPm" />
<link rel="shortcut icon" href="<?php echo RES;?>/images/favicon.ico" />
<style>
a.a_upload,a.a_choose{border:1px solid #3d810c;box-shadow:0 1px #CCCCCC;-moz-box-shadow:0 1px #CCCCCC;-webkit-box-shadow:0 1px #CCCCCC;cursor:pointer;display:inline-block;text-align:center;vertical-align:bottom;overflow:visible;border-radius:3px;-moz-border-radius:3px;-webkit-border-radius:3px;vertical-align:middle;background-color:#f1f1f1;background-image: -webkit-linear-gradient(bottom, #CCC 0%, #E5E5E5 3%, #FFF 97%, #FFF 100%); background-image: -moz-linear-gradient(bottom, #CCC 0%, #E5E5E5 3%, #FFF 97%, #FFF 100%); background-image: -ms-linear-gradient(bottom, #CCC 0%, #E5E5E5 3%, #FFF 97%, #FFF 100%); color:#000;border:1px solid #AAA;padding:2px 8px 2px 8px;text-shadow: 0 1px #FFFFFF;font-size: 14px;line-height: 1.5;
}

.pages{padding:3px;margin:3px;text-align:center;}
.pages a{border:#eee 1px solid;padding:2px 5px;margin:2px;color:#036cb4;text-decoration:none;}
.pages a:hover{border:#999 1px solid;color:#666;}
.pages a:active{border:#999 1px solid;color:#666;}
.pages .current{border:#036cb4 1px solid;padding:2px 5px;font-weight:bold;margin:2px;color:#fff;background-color:#036cb4;}
.pages .disabled{border:#eee 1px solid;padding:2px 5px;margin:2px;color:#ddd;}
</style>
 <script src="<?php echo STATICS;?>/jquery-1.4.2.min.js" type="text/javascript"></script>
 <?php if(session('isQcloud') == true): ?><link type="text/css" rel="stylesheet" href="http://qzonestyle.gtimg.cn/qcloud/app/open/v1/css/wxcloud.min.css" />


<style>
.px {
	background:#fff;

	border-color:#c9c9c9;

}


input[type=radio] {

	border-radius:55px;

	border: none;

}
.btnGreen {
	border:1px solid #FFFFFF;
	box-shadow:0 1px 1px #0A8DE4;
	-moz-box-shadow:0 1px 1px #0A8DE4;
	-webkit-box-shadow:0 1px 1px #0A8DE4;
	padding:5px 20px;
	cursor:pointer;
	display:inline-block;
	text-align:center;
	vertical-align:bottom;
	overflow:visible;
	border-radius:3px;
	-moz-border-radius:3px;
	-webkit-border-radius:3px;
*zoom:1;
	background-color:#5ba607;
	background-image:linear-gradient(bottom, #107BAD  3%, #18C2D1 97%, #18C2D1 100%);
	background-image:-moz-linear-gradient(bottom, #107BAD  3%, #0A8DE40 97%, #18C2D1 100%);
	background-image:-webkit-linear-gradient(bottom, #107BAD  3%,#0A8DE4 97%, #18C2D1 100%);
	color:#fff; font-size:14px; line-height: 1.5;
}
.btnGreen:hover {
	background-color:#5ba607;
	background-image:linear-gradient(bottom, #2F8BC9 3%, #2F8BC9 97%, #6AA2D6  100%);
	background-image:-moz-linear-gradient(bottom, #2F8BC9 3%, #2F8BC9 97%, #6AA2D6  100%);
	background-image:-webkit-linear-gradient(bottom, #2F8BC9 3%, #2F8BC9 97%, #6AA2D6  100%);
	color:#fff
}
.btnGreen:active {
	background-color:#5ba607;
	background-image:linear-gradient(bottom, #69b310 3%, #3d810c 97%, #fff 100%);
	background-image:-moz-linear-gradient(bottom, #69b310 3%, #3d810c 97%, #fff 100%);
	background-image:-webkit-linear-gradient(bottom, #69b310 3%, #3d810c 97%, #fff 100%);
	color:#fff
}

.btnGreen{border:1px solid #3d810c;box-shadow:0 1px 1px #aaa;-moz-box-shadow:0 1px 1px #aaa;-webkit-box-shadow:0 1px 1px #aaa;padding:5px 20px;cursor:pointer;display:inline-block;text-align:center;vertical-align:bottom;overflow:visible;border-radius:3px;-moz-border-radius:3px;-webkit-border-radius:3px;*zoom:1;background-color:#5ba607;background-image:linear-gradient(bottom,#4d910c 3%,#69b310 97%,#fff 100%);background-image:-moz-linear-gradient(bottom,#4d910c 3%,#69b310 97%,#fff 100%);background-image:-webkit-linear-gradient(bottom,#4d910c 3%,#69b310 97%,#fff 100%);color:#fff;font-size:14px;line-height:1.5;}.btnGreen:hover{background-color:#5ba607;background-image:linear-gradient(bottom,#3d810c 3%,#69b310 97%,#fff 100%);background-image:-moz-linear-gradient(bottom,#3d810c 3%,#69b310 97%,#fff 100%);background-image:-webkit-linear-gradient(bottom,#3d810c 3%,#69b310 97%,#fff 100%);color:#fff}.btnGreen:active{background-color:#5ba607;background-image:linear-gradient(bottom,#69b310 3%,#3d810c 97%,#fff 100%);background-image:-moz-linear-gradient(bottom,#69b310 3%,#3d810c 97%,#fff 100%);background-image:-webkit-linear-gradient(bottom,#69b310 3%,#3d810c 97%,#fff 100%);color:#fff}

</style><?php endif; ?>
<?php
if (!isset($_SESSION['isQcloud'])){ ?>
<div class="topbg">
<div class="top">
<div class="toplink">
<style>
.topbg{background:url(<?php echo RES;?>/images/top.gif) repeat-x; height:30px; /*box-shadow:0 0 10px #000;-moz-box-shadow:0 0 10px #000;-webkit-box-shadow:0 0 10px #000;*/}
.top {
    margin: 0px auto; width: 988px; height: 30px;
}

.top .toplink{ height:30px; line-height:27px;font-size:12px;}
.top .toplink .welcome{ float:left;}
.top .toplink .memberinfo{ float:right;}
.top .toplink .memberinfo a{ /*color:#999;*/}
.top .toplink .memberinfo a:hover{ color:#F90}
.top .toplink .memberinfo a.green{ background:none; color:#0C0}

.top .logo {width: 990px; color: #333; height:70px; font-size:16px;}
.top h1{ font-size:25px;float:left; width:230px; margin:0; padding:0; margin-top:6px; }
.top .navr {WIDTH:750px; float:right; overflow:hidden;}
.top .navr ul{ width:850px;}
.navr li {text-align:center; float: left; height:70px; font-size:1em; width:103px; margin-right:5px;}
.navr li a {width:103px; line-height:70px; float: left; height:100%; color: #333; font-size: 1em; text-decoration:none;}
.navr li a:hover { background:#ebf3e4;}
.navr li.menuon {background:#ebf3e4; display:block; width:103px;}
.navr li.menuon a{color:#333;}
.navr li.menuon a:hover{color:#333;}
.banner{height:200px; text-align:center; border-bottom:2px solid #ddd;}
.hbanner{ background: url(img/h2003070126.jpg) center no-repeat #B4B4B4;}
.gbanner{ background: url(img/h2003070127.jpg) center no-repeat #264C79;}
.jbanner{ background: url(img/h2003070128.jpg) center no-repeat #E2EAD5;}
.dbanner{ background: url(img/h2003070129.jpg) center no-repeat #009ADA;}
.nbanner{ background: url(img/h2003070130.jpg) center no-repeat #ffca22;}
</style>
<div class="welcome">欢迎使用多用户微信营销服务平台!</div>
    <div class="memberinfo"  id="destoon_member">	
		<?php if($_SESSION[uid]==false): ?><a href="<?php echo U('Index/login');?>">登录</a>&nbsp;&nbsp;|&nbsp;&nbsp;
			<a href="<?php echo U('Index/login');?>">注册</a>
			<?php else: ?>
			<a href="<?php echo U('Index/index');?>">>>我的公众号</a>&nbsp;你好,<a href="/#" hidefocus="true"  ><span style="color:red"><?php echo (session('uname')); ?></span></a>（uid:<?php echo (session('uid')); ?>）
			<a href="<?php echo U('System/Admin/logout');?>" >退出</a><?php endif; ?>	
	</div>
	</div>
    </div>
</div>
<div id="aaa"></div>
<?php
} ?>

  <!--中间内容-->
 
  <div class="contentmanage"<?php if (isset($_SESSION['isQcloud'])){?> style="width:100%"<?php }?>>
  <?php
if (!isset($_SESSION['isQcloud'])){ ?>
    <div class="developer">
       <div class="appTitle normalTitle2">
        <div class="vipuser">


<div class="logo">
<img src="<?php echo ($wecha["headerpic"]); ?>" width="100" height="100">
</div>

<div id="nickname">
<strong><?php echo ($wecha["wxname"]); ?></strong><a href="#" target="_blank" class="vipimg vip-icon<?php echo $userinfo['taxisid']-1; ?>" title=""></a></div>
<div id="weixinid">微信号:<?php echo ($wecha["weixin"]); ?></div>
</div>

        <div class="accountInfo">
<table class="vipInfo" width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td><strong>VIP有效期：</strong><?php echo (date("Y-m-d",$thisUser["viptime"])); ?></td>
<!-- <td><strong>图文自定义：</strong><?php echo ($thisUser["diynum"]); ?>/<?php echo ($userinfo["diynum"]); ?></td> -->
<!-- <td><strong>请求数：</strong><?php echo ($thisUser["connectnum"]); ?>/<?php echo ($userinfo["connectnum"]); ?></td> -->
</tr>
<tr>
<!-- <td><strong>余：</strong><?php echo ($userinfo['connectnum']-$_SESSION['connectnum']); ?></td> -->
<!-- <td><strong>已使用：</strong><?php echo $_SESSION['diynum']; ?></td> -->
<!-- <td><strong>当月剩余请求数：</strong><?php echo $userinfo['connectnum']-$_SESSION['connectnum']; ?></td> -->
</tr>

</table>
    </div>
        <div class="clr"></div>
      </div>

      <!--左侧功能菜单-->

 
<style type="text/css">
#sideBar{
border-right: 0px solid #D3D3D3 !important;
float: left;
padding: 0 0 10px 0;
width: 170px;
background: #fff;
}
.tableContent {
background: none repeat scroll 0 0 #f5f6f7;
padding: 0;
}
.tableContent .content {
border-left: 1px solid #D7DDE6 !important;
}
ul#menu, ul#menu ul {
  list-style-type:none;
  margin: 0;
  padding: 0;
  background: #fff;
}

ul#menu a {
  display: block;
  text-decoration: none;
}

ul#menu li {
  margin: 1px;
}
ul#menu li ul li{
  margin: 1px 0;
}
ul#menu li a {
  background: #EBEEF1;
  color: #464D6A;
  padding: 0.5em;
}
ul#menu li .nav-header{
font-size:14px;
border-bottom: 1px solid #D7DDE6;
}
ul#menu li .nav-header:hover {
  background: #DDE4EB;
}

ul#menu li ul li a {
  background: #FCFCFC;
  color: #8288A4;
  padding-left: 20px;
}
ul#menu li ul li:last-child {
    border-bottom: 1px solid #D7DDE6;
}
ul#menu li ul li a:hover {
  background: #fff;
  border-left: 5px #4A98E0 solid;

}
ul#menu li.selected a{
  background: #fff;
  border-left: 5px #4A98E0 solid;
  padding-left: 15px;
  color: #4A98E0;
}
.code { border: 1px solid #ccc; list-style-type: decimal-leading-zero; padding: 5px; margin: 0; }
.code code { display: block; padding: 3px; margin-bottom: 0; }
.code li { background: #ddd; border: 1px solid #ccc; margin: 0 0 2px 2.2em; }
.indent1 { padding-left: 1em; }
.indent2 { padding-left: 2em; }
.tableContent .content{min-height: 1328px;}

a.nav-header{background:url(/tpl/static/images/arrow_click.png) center right no-repeat;cursor:pointer}
a.nav-header-current{background:url(/tpl/static/images/arrow_unclick.png) center right no-repeat;}
</style> 
<?php
} ?>
      <div class="tableContent">
        <?php
if (!isset($_SESSION['isQcloud'])){ ?>
        <!--左侧功能菜单-->
 <div  class="sideBar" id="sideBar">
<div class="catalogList">
<ul id="menu">
<?php
$menus=array( array( 'name'=>'基础设置', 'iconName'=>'base', 'display'=>0, 'subs'=>array( array('name'=>'关注时回复与帮助','link'=>U('Areply/index',array('token'=>$token)),'new'=>0,'selectedCondition'=>array('m'=>'Areply')), array('name'=>'微信－文本回复','link'=>U('Text/index',array('token'=>$token)),'new'=>0,'selectedCondition'=>array('m'=>'Text')), array('name'=>'微信－图文回复','link'=>U('Img/index',array('token'=>$token)),'new'=>0,'selectedCondition'=>array('m'=>'Img','a'=>'index')), array('name'=>'自定义菜单','link'=>U('Diymen/index',array('token'=>$token)),'new'=>0,'selectedCondition'=>array('m'=>'Diymen')), array('name'=>'回答不上来的配置','link'=>U('Other/index',array('token'=>$token)),'new'=>0,'selectedCondition'=>array('m'=>'Other')), )), array( 'name'=>'会员管理', 'iconName'=>'card', 'display'=>0, 'subs'=>array( array('name'=>'账号列表','link'=>U('Distribution/account',array('token'=>$token)),'new'=>0,'selectedCondition'=>array('m'=>'Distribution','a'=>'account')), )), array( 'name'=>'商城系统', 'iconName'=>'store', 'display'=>0, 'subs'=>array( array('name'=>'微信商城系统','link'=>U('Store/index',array('token'=>$token)),'new'=>0,'selectedCondition'=>array('m'=>'Store')), )), array( 'name'=>'分店系统', 'iconName'=>'site', 'display'=>0, 'subs'=>array( array('name'=>'分店管理','link'=>U('Shop/index',array('token'=>$token)),'new'=>0,'selectedCondition'=>array('m'=>'Shop')), )), ); ?>
<?php
$i=0; $parms=$_SERVER['QUERY_STRING']; $parms1=explode('&',$parms); $parmsArr=array(); if ($parms1){ foreach ($parms1 as $p){ $parms2=explode('=',$p); $parmsArr[$parms2[0]]=$parms2[1]; } } $subMenus=array(); $t=0; $currentMenuID=0; $currentParentMenuID=0; foreach ($menus as $m){ $loopContinue=1; if ($m['subs']){ $st=0; foreach ($m['subs'] as $s){ $includeArr=1; if ($s['selectedCondition']){ foreach ($s['selectedCondition'] as $condition){ if (!in_array($condition,$parmsArr)){ $includeArr=0; break; } } } if ($includeArr){ if ($s['exceptCondition']){ foreach ($s['exceptCondition'] as $epkey=>$eptCondition){ if ($epkey=='a'){ $parm_a_values=explode(',',$eptCondition); if ($parm_a_values){ if (in_array($parmsArr['a'],$parm_a_values)){ $includeArr=0; break; } } }else { if (in_array($eptCondition,$parmsArr)){ $includeArr=0; break; } } } } } if ($includeArr){ $currentMenuID=$st; $currentParentMenuID=$t; $loopContinue=0; break; } $st++; } if ($loopContinue==0){ break; } } $t++; } foreach ($menus as $m){ $displayStr=''; if ($currentParentMenuID!=0||0!=$currentMenuID){ $m['display']=0; } if (!$m['display']){ $displayStr=' style="display:none"'; } if ($currentParentMenuID==$i){ $displayStr=''; } $aClassStr=''; if ($displayStr){ $aClassStr=' nav-header-current'; } if($i == 0){ echo '<a class="nav-header'.$aClassStr.'" style="border-top:none !important;"><b class="'.$m['iconName'].'"></b>'.$m['name'].'</a><ul class="ckit"'.$displayStr.'>'; }else{ echo '<a class="nav-header'.$aClassStr.'"><b class="'.$m['iconName'].'"></b>'.$m['name'].'</a><ul class="ckit"'.$displayStr.'>'; } if ($m['subs']){ $j=0; foreach ($m['subs'] as $s){ $selectedClassStr='subCatalogList'; if ($currentParentMenuID==$i&&$j==$currentMenuID){ $selectedClassStr='selected'; } $newStr=''; if ($s['test']){ $newStr.='<span class="test"></span>'; }else { if ($s['new']){ $newStr.='<span class="new"></span>'; } } if ($s['name']!='微信墙'&&$s['name']!='摇一摇'){ echo '<li class="'.$selectedClassStr.'"> <a href="'.$s['link'].'">'.$s['name'].'</a>'.$newStr.'</li>'; }else { switch ($s['name']){ case '微信墙': case '摇一摇': if (file_exists($_SERVER['DOCUMENT_ROOT'].'/PigCms/Lib/Action/User/WallAction.class.php')&&file_exists($_SERVER['DOCUMENT_ROOT'].'/PigCms/Lib/Action/User/ShakeAction.class.php')){ echo '<li class="'.$selectedClassStr.'"> <a href="'.$s['link'].'">'.$s['name'].'</a>'.$newStr.'</li>'; } break; } } if ($s['name']=='模板管理'&&is_dir($_SERVER['DOCUMENT_ROOT'].'/cms')&&!strpos($_SERVER['HTTP_HOST'],'pigcms')){ echo '<li class="subCatalogList"> <a href="/cms/manage/index.php">高级模板</a><span class="new"></span></li>'; } $j++; } } echo '</ul>'; $i++; } ?>


</ul>
</div>
</div>
<?php
} ?>
<script type="text/javascript">

	$(document).ready(function(){
		$(".nav-header").mouseover(function(){
			$(this).addClass('navHover');
		}).mouseout(function(){
			$(this).removeClass('navHover');
		}).click(function(){
			$(this).toggleClass('nav-header-current');
			$(this).next('.ckit').slideToggle();
		})
	});

</script>  
<link rel="stylesheet" type="text/css" href="<?php echo RES;?>/css/cymain.css" />
<script src="<?php echo STATICS;?>/artDialog/jquery.artDialog.js?skin=default"></script>
<script src="<?php echo STATICS;?>/artDialog/plugins/iframeTools.js"></script>
        <div class="content">
<div class="cLineB">
<h4 class="left">订单管理（<a href="<?php echo U('Store/orders',array('token'=>$token,'handled'=>0));?>">未处理订单<span style="color:#f00"><?php echo ($unhandledCount); ?></span>个</a>） (<?php echo ($page); ?>) </h4>
<script>
function selectall(name) {
	var checkItems=$('.cbitem');
	if ($("#check_box").attr('checked')==false) {
		$.each(checkItems, function(i,val){
			val.checked=false;
		});
		
	} else {
		$.each(checkItems, function(i,val){
			val.checked=true;
		});
	}
}
</script>
<div class="clr"></div>
</div>
<!--tab start-->
<div class="tab" id="store_tab">
<ul>
<li class="tabli spfl"     id="tab2"><a href="<?php echo U('Store/index',array('token'=>$token,'dining'=>$isDining));?>">
<?php if($isDining != 1): ?>商品分类<?php else: ?>菜品分类<?php endif; ?>管理</a></li>


<?php if(empty($catid) != true): ?><li class="tabli pro"      id="tab0"><a href="<?php echo U('Store/product',array('token'=>$token));?>">商品管理</a></li><?php endif; ?>


<li class="tabli ord"      id="tab2"><a href="<?php echo U('Store/orders',array('token'=>$token,'dining'=>$isDining));?>">订单管理</a></li>

<li class="tabli sclass"      id="tab2"><a href="<?php echo U('Store/showClass',array('token'=>$token,'dining'=>$isDining));?>">展示分类</a></li>

<li class="tabli coupon"      id="tab2"><a href="<?php echo U('Store/coupon',array('token'=>$token,'dining'=>$isDining));?>">优惠券管理</a></li>

<li class="tabli msg"      id="tab2"><a href="<?php echo U('Store/message',array('token'=>$token,'dining'=>$isDining));?>">反馈列表</a></li>
<li class=" <?php if((ACTION_NAME) == "banner"): ?>current<?php endif; ?> tabli" id="tab2"><a href="<?php echo U('Store/banner',array('token'=>$token));?>">轮播图</a></li>


<!-- <?php if($isDining != 1): ?><li class="tabli" id="tab5"><a href="<?php echo U('Reply_info/set',array('token'=>$token,'infotype'=>'Shop'));?>">商城回复配置</a></li>
<?php else: ?>
<li class="tabli" id="tab5"><a href="<?php echo U('Reply_info/set',array('token'=>$token,'infotype'=>'Dining'));?>">订餐回复配置</a></li><?php endif; ?> -->
</ul>
</div>
<!--tab end-->
<script type="text/javascript">
	(function($){
		var module_name="<?php echo MODULE_NAME;?>";
		var action_name="<?php echo ACTION_NAME;?>";
		var tab=$("#store_tab");
		if(module_name=="Store"){
			if(action_name=="index"){
				tab.find('.spfl').addClass('current');
			}
			if(action_name=="search"){
				tab.find('.sssp').addClass('current');
			}
			if(action_name=="banner"){
				tab.find('.ban').addClass('current');
			}
			if(action_name=="guanggao"){
				tab.find('.ad').addClass('current');
			}
			if(action_name=="product"){
				tab.find('.pro').addClass('current');
			}
			if(action_name=="orders"){
				tab.find('.ord').addClass('current');
			}
			if(action_name=='discount'){
				tab.find('.discount').addClass('current');
			}
			if(action_name=='picDisplay'){
				tab.find('.dis').addClass('current');
			}	

			if(action_name=='showClass'){
				tab.find('.sclass').addClass('current');
			}	

			if(action_name=='coupon'){
				tab.find('.coupon').addClass('current');
			}	

			if(action_name=='message'){
				tab.find('.msg').addClass('current');
			}	
		}
	})(jQuery)
</script>
<div class="msgWrap">
<div class="searchbar" style="margin-top:10px;">
<form method="post" action="">
付款状态：<select name="paid"><option value="">=请选择=</option><option value="0" <?php if(($_REQUEST['paid']) == "0"): ?>selected<?php endif; ?>>待付款</option><option value="1" <?php if(($_REQUEST['paid']) == "1"): ?>selected<?php endif; ?>>已付款</option></select>
<!-- 发货状态：<select name="sent"><option value="">=请选择=</option><option value="0" <?php if(($_REQUEST['sent']) == "0"): ?>selected<?php endif; ?>>待发货</option><option value="1" <?php if(($_REQUEST['sent']) == "1"): ?>selected<?php endif; ?>>已发货</option></select> -->
<!-- 收货状态：<select name="receive"><option value="">=请选择=</option><option value="0" <?php if(($_REQUEST['receive']) == "0"): ?>selected<?php endif; ?>>待收货</option><option value="1" <?php if(($_REQUEST['receive']) == "1"): ?>selected<?php endif; ?>>已收货</option></select> -->
处理状态：<select name="handled"><option value="">=请选择=</option><option value="0" <?php if(($_REQUEST['handled']) == "0"): ?>selected<?php endif; ?>>待处理</option><option value="1" <?php if(($_REQUEST['handled']) == "1"): ?>selected<?php endif; ?>>已处理</option></select>
<input type="text" id="msgSearchInput" class="txt left" placeholder="输入相关信息搜索" name="searchkey" value="<?php echo ($_REQUEST['searchkey']); ?>">
<input type="hidden" name="token" value="<?php echo ($token); ?>">
<input type="submit" value="搜索" class="btnGrayS" />
</form>
</div>
<form method="post" action="" id="info">
<input type="hidden" name="handleOrder" value="1">
<div class="cLine">
<!-- <div class="pageNavigator left"> <a href="###" onclick="$('#info').submit()" title="" class="btnGrayS vm bigbtn"><img src="<?php echo RES;?>/images/product/arrow_switch.png" class="vm">审核处理订单</a>&nbsp;<span style="color:red">付款后订单可进行审核处理，订单处理后分销商佣金累加（退款的订单不能进行审核处理）</span></div> -->
<div class="clr"></div>
</div>

<table class="ListProduct" border="0" cellspacing="0" cellpadding="0" width="100%">
<thead>
<tr>
<!-- <th class="select"><input type="checkbox" value="" id="check_box" onclick="selectall('id[]');"></th> -->
<th width="50">订单号</th>
<th width="50">姓名</th>
<th >电话</th>
<th class="60">数量</th>
<th width="70">总价（元）</th>
<th class="160">付款状态</th>
<th class="60">退款状态</th>
<!-- <th class="60">退款原因</th> -->
<th class="60">处理状态</th>
<!-- <th class="60">付款方式</th> -->
<th >预约时间</th>
<th width="100" class="norightborder">操作</th>
</tr>
</thead>
<tbody>
<tr></tr>
<?php if(is_array($orders)): $i = 0; $__LIST__ = $orders;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$o): $mod = ($i % 2 );++$i;?><tr>
<!-- <td><?php if(($o["handled"]) == "0"): if(($o["paid"]) == "1"): if(($o["returnMoney"]) == "0"): ?><input type="checkbox" value="<?php echo ($o["id"]); ?>" class="cbitem" name="id_<?php echo ($i); ?>"><?php endif; endif; endif; ?></td> -->
<td><?php echo ($o["orderid"]); ?></td>
<td><?php echo ($o["truename"]); ?> <?php if($isDining == 1): ?><span style="color:#f60">[<?php if($o["diningtype"] == 1): ?>点餐<?php elseif($o["diningtype"] == 2): ?>外卖<?php elseif($o["diningtype"] == 3): ?>预定<?php else: endif; ?>]</span><?php endif; ?></td>
<td><?php echo ($o["tel"]); ?></td>
<td><?php echo ($o["total"]); ?></td>
<td><?php echo ($o["price"]); ?></td>
<td><?php if($o["paid"] == 1): ?><span style="color:green">已付款</span><?php else: ?><span style="color:red">未付款</span><?php endif; ?></td>
<td><?php if($o["returnMoney"] == 2): ?><span style="color:green">已退款</span><?php endif; if($o["returnMoney"] == 1): ?><span style="color:red">待退款</span><?php endif; if($o["returnMoney"] == 0): ?><span style="color:blue">未申请</span><?php endif; ?></td>
<!-- <td><?php echo ($o["returnReason"]); ?></td> -->
<td><?php if($o["handled"] == 1): ?><span style="color:green">已处理</span><?php else: ?><span style="color:red">待处理</span><?php endif; ?></td>
<!-- <td>
	<?php if($o['paymode'] == 0): ?><span style="color:green">其他方式</span>
	<?php elseif($o['paymode'] == 1): ?><span style="color:green">在线支付</span>
	<?php else: ?><span style="color:green">货到付款</span><?php endif; ?>
</td> -->
<td><?php echo (date("Y-m-d H:i:s",$o["rtime"])); ?></td> 
<td class="norightborder">
	<?php if(($o["returnMoney"]) == "1"): ?><a href="<?php echo U('Store/returnMoney',array('token'=> $token,'id'=>$o['id']));?>" onclick="return confirm('确定已完成退款了么');">退款完成
		</a>
		&nbsp;&nbsp;<?php endif; ?>
	<a href="###" onclick="showIntroDetail(<?php echo ($o["id"]); ?>)">详细</a>
	&nbsp;&nbsp;
	<a href="###" onclick="showIntroDetail2(<?php echo ($o["id"]); ?>)">打印订单</a>
	&nbsp;&nbsp;
	<a href="javascript:drop_confirm('您确定要删除吗?', '<?php echo U('Store/deleteOrder',array('token'=>$token,'id'=>$o['id'],'dining'=>$isDining));?>');">删除</a>
</td>
</tr><?php endforeach; endif; else: echo "" ;endif; ?>
</tbody>
</table>
<input type="hidden" name="token" value="<?php echo ($_GET['token']); ?>" />
</form>

   <script>
function showIntroDetail(id){
	art.dialog.open('<?php echo U('Store/orderInfo',array('token'=>$token,'dining'=>$isDining));?>&id='+id,{lock:false,title:'订单详情',width:1000,height:620,yesText:'关闭',background: '#000',opacity: 0.87,close: function(){location.reload();}});
}
function showIntroDetail2(id){
	art.dialog.open('<?php echo U('Store/printorder',array('token'=>$token,'dining'=>$isDining));?>&id='+id,{lock:false,title:'订单详情',width:150,height:50,yesText:'关闭',background: '#000',opacity: 0.87,close: function(){location.reload();}});
}
</script>
</div>
<div class="cLine">
<div class="pageNavigator right">
<div class="pages"><?php echo ($page); ?></div>
</div>
<div class="clr"></div>
</div>
</div>
</div>
</div>
</div>

<style>
.IndexFoot {
	BACKGROUND-COLOR: #0A8FDB; WIDTH: 100%; HEIGHT: 39px
}
.foot{ width:988px; margin:0px auto; font-size:12px; line-height:39px;}
.foot .foot_page{ float:left; width:600px;color:white;}
.foot .foot_page a{ color:white; text-decoration:none;}
#copyright{ float:right; width:380px; text-align:right; font-size:12px; color:#FFF;}
</style>
<div class="IndexFoot" style="height:120px;clear:both">
<div class="foot" style="padding-top:20px;">
<div class="foot_page" >
<a href="<?php echo ($f_siteUrl); ?>"><?php echo ($f_siteName); ?>,微信公众平台营销</a><br/>
帮助您快速搭建属于自己的营销平台,构建自己的客户群体。
</div>
<div id="copyright" style="color:white;">
	<?php echo ($f_siteName); ?>(c)版权所有 <a href="http://www.miibeian.gov.cn" target="_blank" style="color:white"><?php echo C('ipc');?></a><br/>
	技术支持：微广互动</a>

</div>
    </div>
</div>
<div style="display:none">
<?php echo ($alert); ?> 
<?php echo base64_decode(C('countsz'));?>
<!-- <script src="http://s15.cnzz.com/stat.php?id=5524076&web_id=5524076" language="JavaScript"></script>
 --></div>

</body>
</html>