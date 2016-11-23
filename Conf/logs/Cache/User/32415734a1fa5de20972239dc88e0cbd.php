<?php if (!defined('THINK_PATH')) exit(); if($ischild != 1): ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
<<<<<<< HEAD
$menus=array( array( 'name'=>'基础设置', 'iconName'=>'base', 'display'=>0, 'subs'=>array( array('name'=>'关注时回复与帮助','link'=>U('Areply/index',array('token'=>$token)),'new'=>0,'selectedCondition'=>array('m'=>'Areply')), array('name'=>'微信－文本回复','link'=>U('Text/index',array('token'=>$token)),'new'=>0,'selectedCondition'=>array('m'=>'Text')), array('name'=>'微信－图文回复','link'=>U('Img/index',array('token'=>$token)),'new'=>0,'selectedCondition'=>array('m'=>'Img','a'=>'index')), array('name'=>'自定义菜单','link'=>U('Diymen/index',array('token'=>$token)),'new'=>0,'selectedCondition'=>array('m'=>'Diymen')), array('name'=>'回答不上来的配置','link'=>U('Other/index',array('token'=>$token)),'new'=>0,'selectedCondition'=>array('m'=>'Other')), )), array( 'name'=>'会员管理', 'iconName'=>'card', 'display'=>0, 'subs'=>array( array('name'=>'账号列表','link'=>U('Distribution/account',array('token'=>$token)),'new'=>0,'selectedCondition'=>array('m'=>'Distribution','a'=>'account')), )), array( 'name'=>'商城系统', 'iconName'=>'store', 'display'=>0, 'subs'=>array( array('name'=>'微信商城系统','link'=>U('Store/index',array('token'=>$token)),'new'=>0,'selectedCondition'=>array('m'=>'Store')), )), array( 'name'=>'分店系统', 'iconName'=>'site', 'display'=>0, 'subs'=>array( array('name'=>'分店管理','link'=>U('Shop/index',array('token'=>$token)),'new'=>0,'selectedCondition'=>array('m'=>'Shop')), )), ); ?>
=======
$menus=array( array( 'name'=>'基础设置', 'iconName'=>'base', 'display'=>0, 'subs'=>array( array('name'=>'关注时回复与帮助','link'=>U('Areply/index',array('token'=>$token)),'new'=>0,'selectedCondition'=>array('m'=>'Areply')), array('name'=>'微信－文本回复','link'=>U('Text/index',array('token'=>$token)),'new'=>0,'selectedCondition'=>array('m'=>'Text')), array('name'=>'微信－图文回复','link'=>U('Img/index',array('token'=>$token)),'new'=>0,'selectedCondition'=>array('m'=>'Img','a'=>'index')), array('name'=>'自定义LBS回复','link'=>U('Company/index',array('token'=>$token)),'new'=>0,'selectedCondition'=>array('m'=>'Company')), array('name'=>'自定义菜单','link'=>U('Diymen/index',array('token'=>$token)),'new'=>0,'selectedCondition'=>array('m'=>'Diymen')), array('name'=>'回答不上来的配置','link'=>U('Other/index',array('token'=>$token)),'new'=>0,'selectedCondition'=>array('m'=>'Other')), )), array( 'name'=>'分销管理', 'iconName'=>'crm', 'display'=>0, 'subs'=>array( array('name'=>'分销设置','link'=>U('Distribution/set',array('token'=>$token)),'new'=>0,'selectedCondition'=>array('m'=>'Distribution','a'=>'set')), array('name'=>'分销提醒页','link'=>U('Distribution/forwardSet',array('token'=>$token)),'new'=>0,'selectedCondition'=>array('m'=>'Distribution','a'=>'forwardSet')), )), array( 'name'=>'会员管理', 'iconName'=>'card', 'display'=>0, 'subs'=>array( array('name'=>'账号列表','link'=>U('Distribution/account',array('token'=>$token)),'new'=>0,'selectedCondition'=>array('m'=>'Distribution','a'=>'account')), array('name'=>'会员列表','link'=>U('Distribution/member',array('token'=>$token)),'new'=>0,'selectedCondition'=>array('m'=>'Distribution','a'=>'member')), array('name'=>'会员收藏列表','link'=>U('Distribution/collection',array('token'=>$token)),'new'=>0,'selectedCondition'=>array('m'=>'Distribution','a'=>'collection')), array('name'=>'提现记录列表','link'=>U('Distribution/moneylist',array('token'=>$token)),'new'=>0,'selectedCondition'=>array('m'=>'Distribution','a'=>'moneylist')), array('name'=>'收货地址列表','link'=>U('Distribution/address',array('token'=>$token)),'new'=>0,'selectedCondition'=>array('m'=>'Distribution','a'=>'address')), )), array( 'name'=>'分店管理', 'iconName'=>'store', 'display'=>0, 'subs'=>array( array('name'=>'附加时间限制','link'=>U('Branch/additionalTime',array('token'=>$token)),'new'=>0,'selectedCondition'=>array('m'=>'Branch')), )), array( 'name'=>'商城系统', 'iconName'=>'store', 'display'=>0, 'subs'=>array( array('name'=>'微信商城系统','link'=>U('Store/index',array('token'=>$token)),'new'=>0,'selectedCondition'=>array('m'=>'Store')), )), ); ?>
>>>>>>> 01e200e4f8a1295bfce0a15384da812e38d13ba2
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
  <?php else: endif; ?>
<link rel="stylesheet" href="<?php echo STATICS;?>/kindeditor/themes/default/default.css" />
<link rel="stylesheet" href="<?php echo STATICS;?>/kindeditor/plugins/code/prettify.css" />
<script src="<?php echo STATICS;?>/kindeditor/kindeditor.js" type="text/javascript"></script>
<script src="<?php echo STATICS;?>/kindeditor/lang/zh_CN.js" type="text/javascript"></script>
<script src="<?php echo STATICS;?>/kindeditor/plugins/code/prettify.js" type="text/javascript"></script>
<script src="<?php echo STATICS;?>/artDialog/jquery.artDialog.js?skin=default"></script>
<script src="<?php echo STATICS;?>/artDialog/plugins/iframeTools.js"></script>
<script src="<?php echo RES;?>/js/users/jquery-1.11.1.min.js"></script>
<script src="<?php echo RES;?>/js/users/specifications.js" defer></script>
<script>
var editor;
KindEditor.ready(function(K) {
editor = K.create('#intro', {
resizeType : 1,
allowPreviewEmoticons : false,
allowImageUpload : true,
uploadJson : '/index.php?g=User&m=Upyun&a=kindedtiropic',
items : [
'source','undo','clearhtml','hr',
'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
'insertunorderedlist', '|', 'emoticons', 'image', 'multiimage', 'link', 'unlink','baidumap','lineheight','table','anchor','preview','print','template','code','cut', 'music', 'video','|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline','hr', 'fontname', 'fontsize'],
afterBlur: function(){this.sync();}
});
});
</script>
<div class="content">
  <div class="cLineB">
    <h4>商品设置</h4>
    <a href="<?php echo U('Store/product',array('token'=> $token,'catid'=>$catid,'parentid'=>$_GET['parentid']));?>" class="right  btnGreen" style="margin-top:-27px">返回
    </a>
  </div>
  <form method="post" action="" id="formID">
    <input type="hidden" name="token" value="<?php echo ($token); ?>">
    <input type="hidden" name="id" id="id" value="<?php echo ($set["id"]); ?>"/>
    <?php if(!empty($set)): ?><input type="hidden" name="storeid" value="<?php echo ($set["storeid"]); ?>">
      <?php else: ?>
      <input type="hidden" name="storeid" value="<?php echo ($store_info["id"]); ?>"><?php endif; ?>
    <div class="msgWrap bgfc">
      <table class="userinfoArea" style=" margin:0;" border="0" cellspacing="0" cellpadding="0" width="100%">
        <tbody>
          <tr>
            <th>
              所属店铺：
            </th>
            <td>
              <?php if(!empty($set)): ?><input type="text" value="<?php echo ($set["store"]["name"]); ?>" readonly="true"  style="width:400px;" />
                <?php else: ?>
                <input type="text" value="<?php echo ($store_info["name"]); ?>" readonly="true"  style="width:400px;" />
                <!-- <select name="storeid">
                              <?php if(is_array($store_list)): $i = 0; $__LIST__ = $store_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($i % 2 );++$i;?><option value="<?php echo ($list["id"]); ?>"><?php echo ($list["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                            </select> --><?php endif; ?>
            </td>
          </tr>
          <tr>
            <th>
              <span class="red">*</span>
              名称：
            </th>
            <td>
              <input type="text" name="name" id="name" value="<?php echo ($set["name"]); ?>" class="px validate" data-warn="名称不能为空" style="width:400px;" />
            </td>
          </tr>
          <input type="hidden" name="oldcatid" value="<?php echo ($set["catid"]); ?>">
          <tr>
            <th>类别：</th>
            <td>
              <select name="catid" id="catid">
                <?php if(is_array($CatList)): $i = 0; $__LIST__ = $CatList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if(($vo["parentid"]) == "0"): ?><option  value="<?php echo ($vo["id"]); ?>" <?php if(($vo["id"]) == $catid): ?>selected<?php endif; ?>
                    ><?php echo ($vo["name"]); ?>
                  </option>
                  <?php if(is_array($CatList)): $i = 0; $__LIST__ = $CatList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$co): $mod = ($i % 2 );++$i; if(($co["parentid"]) == $vo["id"]): ?><option  value="<?php echo ($co["id"]); ?>" <?php if(($co["id"]) == $catid): ?>selected<?php endif; ?>
                      >&nbsp;&nbsp;<?php echo ($co["name"]); ?>
                    </option><?php endif; endforeach; endif; else: echo "" ;endif; endif; endforeach; endif; else: echo "" ;endif; ?>
          </select>
        </td>
      </tr>
      <tr>
        <th>类型：</th>
        <td>
          <select name="type" id="type">
            <option value="0">--请选择照片类型--</option>
            <option value="1" <?php if(($set["type"]) == "1"): ?>selected<?php endif; ?>>证件照</option>
            <option value="2" <?php if(($set["type"]) == "2"): ?>selected<?php endif; ?>>文艺照</option>
            <option value="3" <?php if(($set["type"]) == "3"): ?>selected<?php endif; ?>>结婚照</option>
          </select>
        </td>
      </tr>
      <style>
      .color-item-wrap{
        margin-right: 10px;
      }
        .color-item{
          display: inline-block;
          width: 23px;
          height: 23px;
          vertical-align: middle;
        }
        .color-price,.art-price,.art-experience-price{
          width: 25px;
        }
      </style>
      <tr class="color-wrap extend-wrap" <?php if(($set["type"]) != "1"): ?>style="display: none;"<?php endif; ?>>
          <th>背景颜色：</th>
          <td id="color-info">
            <span class="color-item-wrap">
              <span class="color-item" style="background-color: #52a5eb;"></span>
              &nbsp;:&nbsp;
              <input class="color-price px" type="text" name='blue' value="<?php echo (($set["colorinfo"]["blue"]["price"])?($set["colorinfo"]["blue"]["price"]):0); ?>">元</span>
            <span class="color-item-wrap">
              <span class="color-item" style="background-color: #fff; border: 1px solid #ccc;"></span>
              &nbsp;:&nbsp;
              <input class="color-price px" type="text" name='white' value="<?php echo (($set["colorinfo"]["white"]["price"])?($set["colorinfo"]["white"]["price"]):0); ?>">元</span>
            <span class="color-item-wrap">
              <span class="color-item" style="background-color: #bc0d14;"></span>
              &nbsp;:&nbsp;
              <input class="color-price px" type="text" name='red' value="<?php echo (($set["colorinfo"]["red"]["price"])?($set["colorinfo"]["red"]["price"]):0); ?>">元</span>
            <span class="color-item-wrap">
              <span class="color-item" style="background-color: #c9b2a0;"></span>
              &nbsp;:&nbsp;
              <input class="color-price px" type="text" name='yellow' value="<?php echo (($set["colorinfo"]["yellow"]["price"])?($set["colorinfo"]["yellow"]["price"]):0); ?>">元</span>
            <span class="color-item-wrap">
              <span class="color-item" style="background-color: #737689;"></span>
              &nbsp;:&nbsp;
              <input class="color-price px" type="text" name='grey' value="<?php echo (($set["colorinfo"]["grey"]["price"])?($set["colorinfo"]["grey"]["price"]):0); ?>">元</span>
          </td>
        </tr>
        <tr class="art-wrap extend-wrap" <?php if(($set["type"]) != "2"): ?>style="display: none;"<?php endif; ?>>
          <th>文艺照类型：</th>
          <td id="art-info">
            <span class="color-item-wrap">
              <span class="art-type-item">单人</span>
              &nbsp;:&nbsp;
              <input class="art-price px" type="text" name='personal' value="<?php echo (($set["artinfo"]["personal"]["price"])?($set["artinfo"]["personal"]["price"]):0); ?>">元</span>
            <span class="color-item-wrap">
              <span class="art-type-item">闺蜜</span>
              &nbsp;:&nbsp;
              <input class="art-price px" type="text" name='friends' value="<?php echo (($set["artinfo"]["friends"]["price"])?($set["artinfo"]["friends"]["price"]):0); ?>">元</span>
            <span class="color-item-wrap">
              <span class="art-type-item">亲子</span>
              &nbsp;:&nbsp;
              <input class="art-price px" type="text" name='childrens' value="<?php echo (($set["artinfo"]["childrens"]["price"])?($set["artinfo"]["childrens"]["price"]):0); ?>">元</span>
            <span class="color-item-wrap">
              <span class="art-type-item">情侣</span>
              &nbsp;:&nbsp;
              <input class="art-price px" type="text" name='lovers' value="<?php echo (($set["artinfo"]["lovers"]["price"])?($set["artinfo"]["lovers"]["price"]):0); ?>">元</span>
          </td>
        </tr>
        <tr class="art-wrap extend-wrap"  <?php if(($set["type"]) != "2"): ?>style="display: none;"<?php endif; ?>>
          <th>升级体验：</th>
          <td id="art-experience">
            <span class="color-item-wrap">
              <span class="art-type-item">四宫格</span>
              &nbsp;:&nbsp;
              <input class="art-experience-price px" type="text" name='four' value="<?php echo (($set["artex"]["four"]["price"])?($set["artex"]["four"]["price"]):0); ?>">元</span>
            <span class="color-item-wrap">
              <span class="art-type-item">九宫格</span>
              &nbsp;:&nbsp;
              <input class="art-experience-price px" type="text" name='nine' value="<?php echo (($set["artex"]["nine"]["price"])?($set["artex"]["nine"]["price"]):0); ?>">元</span>
          </td>
        </tr>
        <tr class="whimsy-merry-wrap extend-wrap" <?php if(($set["type"]) != "3"): ?>style="display: none;"<?php endif; ?>>
          <th>搞怪结婚照:</th>
          <td>
            <input type="text" class="px" name="wmprice" id="whimsy-merry-price" value="<?php echo (($set["wmprice"])?($set["wmprice"]):0); ?>">元
          </td>
        </tr>
      </div>
      <script>
        $('#type').on('change',function(){
         var type = $(this).val();
         // if(type == 1 || type == 2){
         //  $('#re-price-item').hide();
         // }else{
         //  $('#re-price-item').show();
         // }
         if(type == 0){
          $('.color-wrap').hide();
          $('.art-wrap').hide();
          $('.whimsy-merry-wrap').hide();
         }
         if(type == 1){
          $('.color-wrap').show().siblings('.extend-wrap').hide();
         }
         if(type == 2){
          $('.art-wrap').show().siblings('.extend-wrap').not('.art-wrap').hide();
         }
         if(type == 3){
          $('.whimsy-merry-wrap').show().siblings('.extend-wrap').hide();
         }
        })
      </script>
      <!-- 外观 -->
      <?php if(empty($colorData) != true): ?><tr class="norms_tr norms_color" data-index="0">
          <th><?php echo ($productCatData["color"]); ?>：</th>
          <td class="norms_td" style="line-height: 27px;">
            <?php if(is_array($colorData)): $i = 0; $__LIST__ = $colorData;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($i % 2 );++$i;?><input type="checkbox" value="" data-catid="<?php echo ($list["catid"]); ?>" data-id="<?php echo ($list["id"]); ?>" id="norms_<?php echo ($list["id"]); ?>" <?php if((in_array($list['id'], $colorList))): ?>checked<?php endif; ?>
            >
            <label for="norms_<?php echo ($list["id"]); ?>"><?php echo ($list["value"]); ?></label>
            &nbsp;&nbsp;&nbsp;<?php endforeach; endif; else: echo "" ;endif; ?>
        </td>
      </tr><?php endif; ?>
    <!-- 规格 -->
    <?php if(empty($formatData) != true): ?><tr class="norms_tr norms_format" data-index="1">
        <th><?php echo ($productCatData["norms"]); ?>：</th>
        <td class="norms_td" style="line-height: 27px;">
          <?php if(is_array($formatData)): $i = 0; $__LIST__ = $formatData;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($i % 2 );++$i;?><input type="checkbox" value="" data-catid="<?php echo ($list["catid"]); ?>" data-id="<?php echo ($list["id"]); ?>" id="norms_<?php echo ($list["id"]); ?>" <?php if((in_array($list['id'], $formatList))): ?>checked<?php endif; ?>
          >
          <label for="norms_<?php echo ($list["id"]); ?>"><?php echo ($list["value"]); ?></label>
          &nbsp;&nbsp;&nbsp;<?php endforeach; endif; else: echo "" ;endif; ?>
      </td>
    </tr><?php endif; ?>
  <style>
          #norms_wrap table td{
            width: 130px;
          }
          #norms_wrap table td input{
            width: 60px;
          }
       </style>
  <tr id="norms_wrap"></tr>
  <tr></tr>
  <tr id="re-price-item">
    <th>
      <span class="red">*</span>
      预约价：
    </th>
    <td>
      <input type="text" id="price" name="price" value="<?php echo (($set["price"])?($set["price"]):0); ?>" class="norms_control px" data-warn="预约价不能为空"/>
      元
    </td>
  </tr>
  <!-- <tr>
    <th>
      <span class="red">*</span>
      市场价：
    </th>
    <td>
      <input type="text" id="oprice" name="oprice" value="<?php echo (($set["oprice"])?($set["oprice"]):0); ?>" class="px"/>
      元
    </td>
  </tr> -->



  <?php if($isgroup == 1): ?><tr>
      <th>
        <span class="red">*</span>
        所属组别：
      </th>
      <td>
        <select name="gid" id="gid">
          <?php if(is_array($groups)): $i = 0; $__LIST__ = $groups;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$g): $mod = ($i % 2 );++$i;?><option value="<?php echo ($g['id']); ?>" <?php if($set['gid'] == $g['id']): ?>selected<?php endif; ?>
            ><?php echo ($g['name']); ?>
          </option><?php endforeach; endif; else: echo "" ;endif; ?>
      </select>
    </td>
  </tr><?php endif; ?>
<!-- <tr>
  <th>库存：</th>
  <td>
    <input type="text" id="num" name="num" value="<?php echo (($set["num"])?($set["num"]):0); ?>" class="norms_control px" data-warn="库存不能为空"/>
  </td>
</tr> -->
<!-- <tr>
  <th>限购：</th>
  <td>
    <input type="text" id="limitnum" name="limitnum" value="<?php echo (($set["limitnum"])?($set["limitnum"]):0); ?>" class="px"/>
    (0为不限购)
  </td>
</tr> -->
<!-- <tr>
  <th>预约基数：</th>
  <td>
    <input type="text" id="fakemembercount" name="fakemembercount" value="<?php echo ($set["fakemembercount"]); ?>" class="px"/>
    (如果您不做假数据就设置为0)
  </td>
</tr> -->
<tr>
  <th>是否下架：</th>
  <td>
    <input type="radio" name="isopen" class="isopen" value="1" id="isopen_0" <?php if($set['isopen'] == 1): ?>checked<?php endif; ?>
  />
  <label for="isopen_0">正常</label>
  &nbsp;&nbsp;&nbsp;&nbsp;
  <input type="radio" name="isopen" class="isopen" value="0" id="isopen_1" <?php if($set['isopen'] == 0): ?>checked<?php endif; ?>
/>
<label for="isopen_1">下架</label>
</td>
</tr>
<!-- <tr>
<th>邮费：</th>
<td>
<input type="text" id="mailprice" name="mailprice" value="<?php echo ($set["mailprice"]); ?>" class="px"/>
元
</td>
</tr> -->
<!-- <tr>
<th>
<span class="red">*</span>
关键词：
</th>
<td>
<input type="text" name="keyword" id="keyword" value="<?php echo ($set["keyword"]); ?>" class="px" style="width:400px;" />
</td>
</tr> -->
<!-- <tr>
<th>Logo地址：</th>
<td>
<input type="text" name="logourl" value="<?php echo ($set["logourl"]); ?>" class="px" id="pic" style="width:400px;" />
<script src="<?php echo STATICS;?>/upyun.js"></script>
<a href="###" onclick="upyunPicUpload('pic',700,700,'<?php echo ($token); ?>')" class="a_upload">上传</a>
<a href="###" onclick="viewImg('pic')">预览</a>
</td>
</tr> -->
<!-- <tr>
<th>展示图片一：</th>
<td>
<input type="text" value="<?php echo ($imageList[0]["image"]); ?>" class="px" id="image1" imageid="<?php echo ($imageList[0]["id"]); ?>" style="width:400px;" />
<script src="<?php echo STATICS;?>/upyun.js"></script>
<a href="###" onclick="upyunPicUpload('image1',700,700,'<?php echo ($token); ?>')" class="a_upload">上传</a>
<a href="###" onclick="viewImg('image1')">预览</a>
</td>
</tr>
<tr>
<th>展示图片二：</th>
<td>
<input type="text" value="<?php echo ($imageList[1]["image"]); ?>" class="px" id="image2" imageid="<?php echo ($imageList[1]["id"]); ?>" style="width:400px;" />
<script src="<?php echo STATICS;?>/upyun.js"></script>
<a href="###" onclick="upyunPicUpload('image2',700,700,'<?php echo ($token); ?>')" class="a_upload">上传</a>
<a href="###" onclick="viewImg('image2')">预览</a>
</td>
</tr>
<tr>
<th>展示图片三：</th>
<td>
<input type="text" value="<?php echo ($imageList[2]["image"]); ?>" class="px" id="image3" imageid="<?php echo ($imageList[2]["id"]); ?>" style="width:400px;" />
<script src="<?php echo STATICS;?>/upyun.js"></script>
<a href="###" onclick="upyunPicUpload('image3',700,700,'<?php echo ($token); ?>')" class="a_upload">上传</a>
<a href="###" onclick="viewImg('image3')">预览</a>
</td>
</tr>
<tr>
<th>展示图片四：</th>
<td>
<input type="text" value="<?php echo ($imageList[3]["image"]); ?>" class="px" id="image4" imageid="<?php echo ($imageList[3]["id"]); ?>" style="width:400px;" />
<script src="<?php echo STATICS;?>/upyun.js"></script>
<a href="###" onclick="upyunPicUpload('image4',700,700,'<?php echo ($token); ?>')" class="a_upload">上传</a>
<a href="###" onclick="viewImg('image4')">预览</a>
</td>
</tr>
<tr>
<th>展示图片五：</th>
<td>
<input type="text" value="<?php echo ($imageList[4]["image"]); ?>" class="px" id="image5" imageid="<?php echo ($imageList[4]["id"]); ?>" style="width:400px;" />
<script src="<?php echo STATICS;?>/upyun.js"></script>
<a href="###" onclick="upyunPicUpload('image5',700,700,'<?php echo ($token); ?>')" class="a_upload">上传</a>
<a href="###" onclick="viewImg('image5')">预览</a>
</td>
</tr>
<tr>
<th>展示图片六：</th>
<td>
<input type="text" value="<?php echo ($imageList[5]["image"]); ?>" class="px" id="image6" imageid="<?php echo ($imageList[5]["id"]); ?>" style="width:400px;" />
<script src="<?php echo STATICS;?>/upyun.js"></script>
<a href="###" onclick="upyunPicUpload('image6',700,700,'<?php echo ($token); ?>')" class="a_upload">上传</a>
<a href="###" onclick="viewImg('image6')">预览</a>
</td>
</tr>
<input type="hidden" name="images" id="images">
<input type="hidden" name="imagesid" id="imagesid">
<tr> -->
<th>排序：</th>
<td>
<input type="text" id="sort" name="sort" value="<?php echo ($set["sort"]); ?>" class="px" style="width:50px;" />
数字越大排在越前（大于等于0的整数）
</td>
</tr>
<!-- <tr>
<th>简介：</th>
<td>
<input type="text" id="des" name="des" value="<?php echo ($set["des"]); ?>" class="px" style="width:640px;" />
</td>
</tr> -->
<TR>
<TH valign="top">
<label for="info">图文详细页内容：</label>
</TH>
<TD>
<textarea name="intro" id="intro"  rows="5" style="width:590px;height:360px"><?php echo ($set["intro"]); ?></textarea>
</TD>
</TR>
<tr>
<th>&nbsp;</th>
<td>
<button type="button" name="button" class="btnGreen" id="save">保存</button>
&nbsp;
<a href="<?php echo U('Store/index',array('token'=>$token, 'catid' => $catid));?>" class="btnGray vm">取消</a>
</td>
</tr>
</tbody>
</table>
</div>
</form>
</div>
<script type="text/javascript">

var href = "<?php echo U('Store/product',array('token'=>$token,'catid'=>$catid,'parentid'=>$_GET['parentid'],'p'=>$_GET['p']));?>";
var pid = "<?php echo ($set["id"]); ?>";
var pdataJson = <?php echo ($pdataJson); ?>;
var color_name = "<?php echo ($productCatData["color"]); ?>";
var format_name = "<?php echo ($productCatData["norms"]); ?>";

</script>
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