<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title> <?php echo ($f_siteTitle); ?> <?php echo ($f_siteName); ?></title>
<meta name="keywords" content="<?php echo ($f_metaKeyword); ?>" />
<meta name="description" content="<?php echo ($f_metaDes); ?>" />
<meta http-equiv="MSThemeCompatible" content="Yes" />
<script>var SITEURL='';</script>
<link rel="stylesheet" type="text/css" href="<?php echo RES;?>/css/style_2_common.css?BPm" />
<script src="<?php echo RES;?>/js/common.js" type="text/javascript"></script>
</head>
<body id="nv_member" class="pg_CURMODULE">
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


<div id="mu" class="cl"></div>
</div>
</div>
<div id="aaa"></div>

<div id="wp" class="wp">
    <link href="<?php echo RES;?>/css/style-1.css?id=103" rel="stylesheet" type="text/css" />
 <div class="contentmanage">
    <div class="developer">
       <div class="appTitle normalTitle">
        <h2>管理平台</h2>
        <div class="accountInfo">
        
        </div>
        <div class="clr"></div>
      </div>
      <div class="tableContent">
        <!--左侧功能菜单-->
        <div class="sideBar">
          <div class="catalogList">
            <ul class="newskin">
            	<li class="subCatalogList"> <a class="secnav_1" href="<?php echo U('Index/useredit');?>">修改密码</a> </li>
				<li class=" subCatalogList "> <a class="secnav_2" href="<?php echo U('Index/index');?>">我的公众号</a></li>
				<!--li class=" subCatalogList "> <a class="secnav_3" href="<?php echo U('Index/add');?>">添加公众号</a> </li-->
				<!--li class=" subCatalogList "> <a class="secnav_4" href="<?php echo U('Alipay/index');?>">会员充值</a> </li>
				<li class=" subCatalogList "> <a class="secnav_5" href="<?php echo U('Alipay/vip');?>">升降级</a> </li>
				<li class=" subCatalogList "> <a class="secnav_6" href="<?php echo U('Sms/index');?>">短信管理</a> </li-->
				<?php if($thisUser['invitecode']): ?><li class=" subCatalogList "> <a class="secnav_7" href="<?php echo U('Index/invite');?>">邀请朋友</a> </li><?php endif; ?>
        <!--li class=" subCatalogList "> <a class="secnav_9" href="<?php if(C('open_biz') == 0): ?>javascript:alert('请联系站长在后台开启企业号');<?php else: echo U('Index/add',array('biz'=>1)); endif; ?>">添加企业号</a> </li-->
            </ul>
          </div>
        </div>

<script src="/tpl/static/jquery-1.4.2.min.js"></script>
<script src="/tpl/static/artDialog/jquery.artDialog.js?skin=default"></script>
<script src="/tpl/static/artDialog/plugins/iframeTools.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo Common;?>/default_user_com.css" media="all">
<script language="JavaScript">
if (window.top != self){
	window.top.location = self.location;
}
</script>
<script>
function addFee(){
	art.dialog.open('?g=User&m=Alipay&a=add',{lock:true,title:'充值续费',width:600,height:400,yesText:'关闭',background: '#000',opacity: 0.45});
}
function showApiInfo(id,name){
	art.dialog.open('?g=User&m=Index&a=apiInfo&id='+id,{lock:true,title:name+'接口信息',width:830,height:270,yesText:'关闭',background: '#000',opacity: 0.45});
}
</script>
<div class="content">
<div class="usercontent">

<ul>
<script type="text/javascript">
  function addbiz(){
    <?php if(C('open_biz') == 0): ?>alert('请联系站长在后台开启企业号');
    <?php else: ?>
      location.href='<?php echo U('Index/add',array('biz'=>1));?>';<?php endif; ?>
  }
</script>

<div class="clr"></div>
</ul>


        <div class="clr"></div>
      </div>
          <div class="msgWrap">
            <TABLE class="ListProduct" border="0" cellSpacing="0" cellPadding="0" width="100%">
              <THEAD>
                <TR>
                  <TH>公众号名称</TH>
                  <TH style="text-align:center">VIP等级</TH>
                  <TH>创建时间/到期时间</TH>
                   <TH>已定义/上限</TH>
                   <TH>请求数</TH>
                  <TH>操作</TH>
                </TR>
              </THEAD>
              <TBODY>
                <TR></TR>                
                 <?php if(is_array($info)): $i = 0; $__LIST__ = $info;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><TR>
					  <TD><p><a href="<?php echo U('Function/index',array('id'=>$vo['id'],'token'=>$vo['token']));?>" title="点击进入功能管理"><img src="<?php echo ($vo["headerpic"]); ?>" width="40" height="40"></a></p><p><?php echo ($vo["wxname"]); ?></p></TD>
					  <TD align="center"><?php echo ($thisGroup["name"]); ?></TD>
					  <TD><p>创建时间:<?php echo (date("Y-m-d",$vo["createtime"])); ?></p><p>到期时间:<?php echo (date("Y-m-d",$viptime)); ?></p></Td>
					  <TD><p>图文：<?php echo $_SESSION['diynum'].'/'.$group[$_SESSION['gid']]['did']; ?></p></TD>
					   <TD><p>总请求数:<?php echo $_SESSION['connectnum'] ?></p><p> 本月请求数:<?php echo $group[$_SESSION['gid']]['cid']; ?></p></TD>
					  
					  <TD class="norightborder">　<a href="<?php echo U('Index/edit',array('id'=>$vo['id']));?>">编辑</a>　
					  <a href="<?php echo U('Areply/index',array('id'=>$vo['id'],'token'=>$vo['token']));?>" class="btnGreens" >功能管理</a>
					  <!--a href="<?php echo U('Home/Index/help',array('id'=>$vo['id'],'token'=>$vo['token']));?>" class="btnGreens" >API接口</a-->
					  </TD>
					</TR><?php endforeach; endif; else: echo "" ;endif; ?>

              </TBODY>
            </TABLE>
            
          </div>
          <br>
          <?php if($demo == 1): ?><div class="alert">
          <p><b>欢迎试用微信许昌微信多用户营销系统，为了您测试方便，我们已经自动创建了公众号并填充了各类数据，您只需要按照下面步骤操作即可进行测试：</b></p>
          <p>1、<a href="<?php echo U('Index/edit',array('id'=>$wxinfo['id']));?>">点击这里修改您的公众号信息</a></p>
          <p>2、登录您的微信公众平台，按照说明绑定您的微信公众号(<a href="<?php echo U('User/Index/bindHelp',array('id'=>$wxinfo['id'],'token'=>$wxinfo['token']));?>" target="_blank">点击这里查看帮助说明</a>)</p>
          <p>3、如果您需要测试自定义菜单功能，请<a href="<?php echo U('Function/index',array('id'=>$wxinfo['id'],'token'=>$wxinfo['token']));?>">进入功能管理</a>，然后生成自定义菜单，重新关注公众号就会看到自定义菜单了</p>
          <p>就这些，如果碰到任何问题，请您给我们留言，QQ：800022936</p>
          </div><?php endif; ?>
          <div class="cLine">
            <div class="pageNavigator right">
              <div class="pages"><?php echo ($page); ?></div>
            </div>
            <div class="clr"></div>
          </div>
        </div>
        
        <div class="clr"></div>
      </div>
    </div>
  </div>
  <!--底部-->
  	</div>
  	<!--ad start-->
  	<?php if($thisAD): ?><div id="ad1" style="width: 100%; height: 100%; position: fixed; z-index: 1997; top: 0px; left: 0px; overflow: hidden;"><div style="height: 100%; background: none repeat scroll 0% 0% rgb(0, 0, 0); opacity: 0.65;filter:alpha(opacity=65);">
  	
  	</div></div>
  	<div id="ad2" style="position:fixed; text-align:center; width:100%; top:140px; z-index:30001">
  	<a href="<?php if ($thisAD['url']){ echo ($thisAD["url"]); }else{?>###<?php };?>" target="_blank"><img src="<?php echo ($thisAD["imgs"]); ?>" /></a>
  	</div>
  	<div id="ad3" style="position:fixed; text-align:center; width:100%; top:140px;z-index:30012; background:#f80; opacity:0;filter:alpha(opacity=0);">
  	<div style="height:40px;width:700px;margin:0 auto;z-index:30012;">
  	<div onclick="closeAD()" style="height:45px;width:45px;margin:0 0 0 655px;cursor:pointer;"></div>
  	</div>
  	</div>
  	<script>
  	function closeAD(){
  		$('#ad1').animate({opacity: "hide"}, "slow");
  		$('#ad2').animate({opacity: "hide"}, "slow");
  		$('#ad3').animate({opacity: "hide"}, "slow");
  		$.ajax({url: "/index.php?g=User&m=Index&a=closeAD",dataType: "json"});
  	}
  	</script><?php endif; ?>
  	<!--ad end-->
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