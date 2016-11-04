<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title> 微信公众平台源码,微信机器人源码,微信自动回复源码 PigCms多用户微信营销系统</title>
<meta http-equiv="MSThemeCompatible" content="Yes" />
<link rel="stylesheet" type="text/css" href="<?php echo RES;?>/css/style_2_common.css?BPm" />
<script src="<?php echo RES;?>/js/common.js" type="text/javascript"></script>
<link href="<?php echo RES;?>/css/style.css" rel="stylesheet" type="text/css" />

<link rel="stylesheet" type="text/css" href="<?php echo RES;?>/diyUpload/css/webuploader.css">
<link rel="stylesheet" type="text/css" href="<?php echo RES;?>/diyUpload/css/diyUpload.css">

<script type="text/javascript" src="<?php echo RES;?>/diyUpload/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo RES;?>/diyUpload/js/webuploader.html5only.min.js"></script>
<script type="text/javascript" src="<?php echo RES;?>/diyUpload/js/diyUpload.js"></script>

</head>
<body id="nv_member">
<div style="line-height:200%;padding:10px 20px;">
支付状态：<?php if($thisOrder["paid"] == 1): ?>已付款<?php else: ?>未付款<?php endif; ?><br>
<?php if($thisOrder["paid"] == 1): ?>购买状态：<?php if($thisOrder["beDistri"] == 1): ?><span style="color:green">首次购买</span><?php else: ?><span style="color:red">非首次购买</span><?php endif; ?><br><?php endif; ?>
预约人：<?php echo ($thisOrder["truename"]); ?><br>
电话：<?php echo ($thisOrder["tel"]); ?><br>
地址：<?php echo ($thisOrder["province"]); echo ($thisOrder["city"]); echo ($thisOrder["county"]); echo ($thisOrder["address"]); ?><br>
备注信息：<?php echo ($thisOrder["remark"]); ?><br>
总数：<?php echo ($thisOrder["total"]); ?><br>
总价：<span style="color:#f30;font-size:16px;font-weight:bold"><?php echo ($thisOrder["price"]); ?></span>元
</div>

<form class="form" method="post" id="form" action=""> 
<?php if($isUpdate == 1): ?><input type="hidden" name="id" value="<?php echo ($set["id"]); ?>" /><?php endif; ?>
<input type="hidden" name="discount" id="discount" value="<?php echo ($set["discount"]); ?>" />
    <div class="msgWrap bgfc"> 
     <table class="userinfoArea" style=" margin:0;" border="0" cellspacing="0" cellpadding="0" width="100%"> 
      <tbody> 
      <tr> 
        <th><span class="red">*</span>支付状态：</th> 
        <td><select name="paid"><option value="0" <?php if($thisOrder["paid"] == 0): ?>selected<?php endif; ?>>未付款</option><option value="1" <?php if($thisOrder["paid"] == 1): ?>selected<?php endif; ?>>已付款</option></select></td> 
       </tr> 
       <!-- <tr> 
        <th><span class="red">*</span>发货状态：</th> 
        <td><select name="sent"><option value="0" <?php if($thisOrder["sent"] == 0): ?>selected<?php endif; ?>>未发货</option><option value="1" <?php if($thisOrder["sent"] == 1): ?>selected<?php endif; ?>>已发货</option></select></td> 
       </tr> -->
	   <!-- <tr> 
        <th><span class="red">*</span>收货状态：</th> 
        <td><select name="receive"><option value="0" <?php if($thisOrder["receive"] == 0): ?>selected<?php endif; ?>>未收货</option><option value="1" <?php if($thisOrder["receive"] == 1): ?>selected<?php endif; ?>>已收货</option></select></td> 
       </tr> -->
	   <tr> 
        <th><span class="red">*</span>退款状态：</th> 
        <td><select name="returnMoney"><option value="0" <?php if($thisOrder["returnMoney"] == 0): ?>selected<?php endif; ?>>未申请</option><option value="1" <?php if($thisOrder["returnMoney"] == 1): ?>selected<?php endif; ?>>未退款</option><option value="2" <?php if($thisOrder["returnMoney"] == 2): ?>selected<?php endif; ?>>已退款</option></select></td> 
      </tr>
       <tr> 
        <th><span class="red">*</span>处理状态：</th> 
        <td><select name="handled"><option value="0" <?php if($thisOrder["handled"] == 0): ?>selected<?php endif; ?>>未处理</option><option value="1" <?php if($thisOrder["handled"] == 1): ?>selected<?php endif; ?>>已处理</option></select></td> 
       </tr> 
       <!-- <tr> 
        <th><span class="red">*</span>快递公司：</th>
        <td><input type="text" name="logistics" value="<?php echo ($thisOrder["logistics"]); ?>" class="px" style="width:200px;" /></td> 
       </tr>
        <tr> 
        <th><span class="red">*</span>快递单号：</th>
        <td><input type="text" name="logisticsid" value="<?php echo ($thisOrder["logisticsid"]); ?>" class="px" style="width:200px;" /></td> 
       </tr> -->
       
       <tr>         
       <th>&nbsp;</th>
       <td>
      <input type="hidden" name="groupon" value="1" />
       <button type="submit" name="button" class="btnGreen">保存</button> </td> 
       </tr> 
      </tbody> 
     </table> 
     <?php if(!empty($cart_pics)): ?><div class="parentFileBox">
        <ul class="fileBoxUl">
          <?php if(is_array($cart_pics)): $i = 0; $__LIST__ = $cart_pics;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($i % 2 );++$i;?><li class="diyUploadHover">
            <div class="viewThumb">
              <img src="<?php echo ($list["url"]); ?>"></div>
            <div class="diyCancel"  onclick="deletePic(this)" data-id="<?php echo ($list["id"]); ?>"></div>
            <div class="diySuccess"></div>
            <!-- <div class="diyFileName">test - 副本 (2).jpg</div> -->
            <div class="diyBar">
              <div class="diyProgress"></div>
              <div class="diyProgressText">0%</div>
            </div>
          </li><?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
      </div><?php endif; ?>
   <div id="as"></div>
     </div>
    
   </form> 
   <script>
     function deletePic(obj){
       if(window.confirm('确认删除')){
         var id = $(obj).data('id');
         $.ajax({
           url:"<?php echo U('Store/delCartPic');?>",
           data:{id:id},
           dataType:'json',
           async:false,
           success:function(data){
             if(data.status==1){
               $(obj).parents('.diyUploadHover').remove();
             }
           }
         })
       }
     }
   </script>
   <script type="text/javascript">

   /*
   * 服务器地址,成功返回,失败返回参数格式依照jquery.ajax习惯;
   * 其他参数同WebUploader
   */

   $('#test').diyUpload({
     url:'<?php echo U("Store/fileUpload",array("oid"=>$thisOrder["id"]));?>',
     success:function( data ) {
       console.info( data );
     },
     error:function( err ) {
       console.info( err );  
     }
   });

   $('#as').diyUpload({
     url:'<?php echo U("Store/fileUpload",array("oid"=>$thisOrder["id"]));?>',
     success:function( data ) {
       console.info( data );
     },
     error:function( err ) {
       console.info( err );  
     },
     buttonText : '选择文件',
     chunked:true,
     // 分片大小
     chunkSize:512 * 1024,
     //最大上传的文件数量, 总文件大小,单个文件大小(单位字节);
     fileNumLimit:50,
     fileSizeLimit:500000 * 1024,
     fileSingleSizeLimit:50000 * 1024,
     accept: {}
   });
   </script>
<table class="ListProduct" border="0" cellspacing="0" cellpadding="0" width="100%">
<thead>
<tr>
<th width="120" align="center" style="text-align:center">名称</th>
<th class="60" align="center" style="text-align:center">详情</th>
<th width="160" align="center" style="text-align:center">单价（元）</th>

</tr>
</thead>
<tbody>
<tr></tr>
<?php if(is_array($products)): $i = 0; $__LIST__ = $products;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$o): $mod = ($i % 2 );++$i;?><tr>
<td align="center">
<?php echo ($o["name"]); ?></td>
<td align="center">
  <?php echo ($o["attribute"]); ?>
</td>
<td align="center"><?php echo ($o["total_price"]); ?></td>
</tr><?php endforeach; endif; else: echo "" ;endif; ?>
</tbody>
</table>
</body>
</html>