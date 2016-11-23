<?php
class WeixinAction extends BaseAction{
	public $token;
	public $wecha_id;
	public $payConfig;
	public function __construct(){
		
		parent::_initialize();
		$this->token = $this->_get('token');
		$this->wecha_id	= $this->wecha_id;
		if (!$this->token){
			//
			$product_cart_model=M('product_cart');
			$out_trade_no = $this->_get('out_trade_no');
			$order=$product_cart_model->where(array('orderid'=>$out_trade_no))->find();
			if (!$order){
				$order=$product_cart_model->where(array('id'=>intval($this->_get('out_trade_no'))))->find();
			}
			$this->token=$order['token'];
		}
		//读取微信支付配置
		$payConfig = M('Alipay_config')->where(array('token'=>$this->token))->find();
		//$payConfigInfo = unserialize($payConfig['info']);
		$this->payConfig = $payConfig;
		if(ACTION_NAME == 'pay' || ACTION_NAME == 'new_pay'){
			if($this->payConfig['version']==1){
				$this->new_pay();
				exit;
			}else{
				$this->pay();
				exit;
			}
		}

	}
	public function new_pay(){
		import('@.ORG.Weixinnewpay.WxPayPubHelper');

		//使用jsapi接口
		$jsApi = new JsApi_pub($this->payConfig['appid'],$this->payConfig['mchid'],$this->payConfig['paysignkey'],$this->payConfig['appsecret']);

		//=========步骤1：网页授权获取用户openid============
		//通过code获得openid
		if (!isset($_GET['code'])){
			//触发微信返回code码
			$url = $jsApi->createOauthUrlForCode(urlencode($this->siteUrl.'/wxpay/index.php?g=Wap&m=Weixin&a=new_pay&price='.$_GET['price'].'&orderName='.urlencode($_GET['orderName']).'&single_orderid='.$_GET['single_orderid'].'&showwxpaytitle=1&from='.$_GET['from'].'&token='.$_GET['token'].'&wecha_id='.$_GET['wecha_id']));
			Header("Location: $url"); exit();
		}

		//获取code码，以获取openid
	    $code = $_GET['code'];
		$jsApi->setCode($code);
		$openid = $jsApi->getOpenId();
		
		//获取订单信息
		$orderid=$_GET['single_orderid'];
		$payHandel=new payHandle($this->token,$_GET['from'],'weixin');
		$orderInfo=$payHandel->beforePay($orderid);
		$price=$orderInfo['price'];
		
		//判断是否已经支付过
		if($orderInfo['paid']) exit('您已经支付过此次订单！');

		//=========步骤2：使用统一支付接口，获取prepay_id============
		//使用统一支付接口
		$unifiedOrder = new UnifiedOrder_pub($this->payConfig['appid'],$this->payConfig['mchid'],$this->payConfig['paysignkey'],$this->payConfig['appsecret']);	
		$unifiedOrder->setParameter("openid",$openid);//商品描述
		$unifiedOrder->setParameter("body",$orderid);//商品描述
		//自定义订单号，此处仅作举例
		$unifiedOrder->setParameter("out_trade_no",$orderid);//商户订单号 
		$unifiedOrder->setParameter("total_fee",$price*100);//总金额
		$unifiedOrder->setParameter("notify_url",C('site_url').'/wxpay/notice.php');//通知地址 
		$unifiedOrder->setParameter("trade_type","JSAPI");//交易类型
		$unifiedOrder->setParameter("attach",'token='.$_GET['token'].'&wecha_id='.$_GET['wecha_id'].'&from='.$_GET['from']);//附加数据

		$prepay_id = $unifiedOrder->getPrepayId();

		//=========步骤3：使用jsapi调起支付============
		$jsApi->setPrepayId($prepay_id);
		$jsApiParameters = $jsApi->getParameters();
		$this->assign('jsApiParameters',$jsApiParameters);
		
		$from = $_GET['from'];
		$from = $from ? $from : 'Groupon';
		$from = $from!='groupon' ? $from : 'Groupon';
		
		$returnUrl = $this->siteUrl.'/index.php?g=Wap&m='.$from.'&a=payReturn&token='.$_GET['token'].'&wecha_id='.$_GET['wecha_id'].'&orderid='.$orderid;
		$this->assign('returnUrl',$returnUrl);
		//$this->display();
		/*echo '<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><meta name="viewport" content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=no;" /><meta name="apple-mobile-web-app-capable" content="yes" /><meta name="apple-mobile-web-app-status-bar-style" content="black" /><meta name="format-detection" content="telephone=no" /><link href="/tpl/Wap/default/common/css/style/css/hotels.css" rel="stylesheet" type="text/css" /><title>微信支付</title><script language="javascript">function callpay(){WeixinJSBridge.invoke("getBrandWCPayRequest",'.$jsApiParameters.',function(res){WeixinJSBridge.log(res.err_msg);if(res.err_msg=="get_brand_wcpay_request:ok"){document.getElementById("payDom").style.display="none";document.getElementById("successDom").style.display="";setTimeout("window.location.href = \''.$returnUrl.'\'",2000);}else{if(res.err_msg == "get_brand_wcpay_request:cancel"){var err_msg = "您取消了支付";}else if(res.err_msg == "get_brand_wcpay_request:fail"){var err_msg = "支付失败<br/>错误信息："+res.err_desc;}else{var err_msg = res.err_msg +"<br/>"+res.err_desc;}document.getElementById("payDom").style.display="none";document.getElementById("failDom").style.display="";document.getElementById("failRt").innerHTML=err_msg;}});}</script></head><body style="padding-top:20px;"><style>.deploy_ctype_tip{z-index:1001;width:100%;text-align:center;position:fixed;top:50%;margin-top:-23px;left:0;}.deploy_ctype_tip p{display:inline-block;padding:13px 24px;border:solid #d6d482 1px;background:#f5f4c5;font-size:16px;color:#8f772f;line-height:18px;border-radius:3px;}</style><div id="payDom" class="cardexplain"><ul class="round"><li class="title mb"><span class="none">支付信息</span></li><li class="nob"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="kuang"><tr><th>金额</th><td>'.floatval($_GET['price']).'元</td></tr></table></li></ul><div class="footReturn" style="text-align:center"><input type="button" style="margin:0 auto 20px auto;width:100%"  onclick="callpay()"  class="submit" value="点击进行微信支付" /></div></div><div id="failDom" style="display:none" class="cardexplain"><ul class="round"><li class="title mb"><span class="none">支付结果</span></li><li class="nob"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="kuang"><tr><th>支付失败</th><td><div id="failRt"></div></td></tr></table></li></ul><div class="footReturn" style="text-align:center"><input type="button" style="margin:0 auto 20px auto;width:100%"  onclick="callpay()"  class="submit" value="重新进行支付" /></div></div><div id="successDom" style="display:none" class="cardexplain"><ul class="round"><li class="title mb"><span class="none">支付成功</span></li><li class="nob"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="kuang"><tr><td>您已支付成功，页面正在跳转...</td></tr></table><div id="failRt"></div></li></ul></div></body></html>';*/
		echo '<!DOCTYPE html>
<html>
<head>
<title>微信安全支付</title>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" />
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="format-detection" content="telephone=no">
<style type="text/css">
body{padding: 0;margin:0;background-color:#eeeeee;font-family: "黑体";}
.pay-main{background-color: #4cb131;padding-top: 20px;padding-left: 20px;padding-bottom: 20px;}
.pay-main img{margin: 0 auto;display: block;}
.pay-main .lines{margin: 0 auto;text-align: center;color:#cae8c2;font-size:12pt;margin-top: 10px;}
.tips .img{margin: 20px;}
.tips .img img{width:20px;}
.tips span{vertical-align: top;color:#ababab;line-height:18px;padding-left: 10px;padding-top:0px;}
.action{background:#4cb131;padding: 10px 0;color:#ffffff;text-align: center;font-size:14pt;border-radius: 10px 10px; margin: 15px;}
.action:focus{background:#4cb131;}
.action.disabled{background-color:#aeaeae;}
.footer{position: absolute;bottom:0;left:0;right:0;text-align: center;padding-bottom: 20px;font-size:10pt;color:#aeaeae;}
.footer .ct-if{margin-top:6px;font-size:8pt;}
</style>
</head>
<body>
<div class="conainer">
<div class="pay-main">
<img src="/tpl/Wap/default/common/css/style/images/pay_logo.png"/>
<div class="lines"><span>微信安全支付</span></div>
</div>
<div class="tips">
<div class="img" id="payDom">
<img src="/tpl/Wap/default/common/css/style/images/pay_ok.png"/>
<span>已开启支付安全</span>
</div>
<div class="img" id="successDom" style="display:none">
<span>您已支付成功，页面正在跳转...</span>
</div>
<div class="img" id="failDom" style="display:none">
<span id="failRt"></span>
</div>
</div>
<div id="action" class="action" onclick="callpay();">确认支付</div>
<div class="footer"><div>支付安全由中国人民财产保险股份有限公司承保</div><div class="ct-if">7x24小时热线：0755-86010333</div></div>
</div>
<script language="javascript">function callpay(){WeixinJSBridge.invoke("getBrandWCPayRequest",'.$jsApiParameters.',function(res){WeixinJSBridge.log(res.err_msg);if(res.err_msg=="get_brand_wcpay_request:ok"){document.getElementById("payDom").style.display="none";document.getElementById("failDom").style.display="none";document.getElementById("successDom").style.display="block";setTimeout("window.location.href = \''.$returnUrl.'\'",2000);}else{if(res.err_msg == "get_brand_wcpay_request:cancel"){var err_msg = "您取消了支付";document.getElementById("action").innerHTML="重新支付";}else if(res.err_msg == "get_brand_wcpay_request:fail"){var err_msg = "支付失败<br/>错误信息："+res.err_desc;}else{var err_msg = res.err_msg +"<br/>"+res.err_desc;}document.getElementById("payDom").style.display="none";document.getElementById("failDom").style.display="";document.getElementById("failRt").innerHTML=err_msg;}});}</script>
</body>
</html>';
	}
	public function pay(){
		import("@.ORG.Weixinpay.CommonUtil");
		import("@.ORG.Weixinpay.WxPayHelper");
		$commonUtil = new CommonUtil();
		//before
		$orderid=$_GET['single_orderid'];
		$payHandel=new payHandle($this->token,$_GET['from'],'weixin');
		$orderInfo=$payHandel->beforePay($orderid);
		$price=$orderInfo['price'];
		
		//判断是否已经支付过
		if($orderInfo['paid']) exit('您已经支付过此次订单！');
		
		$wxPayHelper = new WxPayHelper($this->payConfig['appid'],$this->payConfig['paysignkey'],$this->payConfig['partnerkey']);

		$wxPayHelper->setParameter("bank_type", "WX");
		$wxPayHelper->setParameter("body", $orderid);
		$wxPayHelper->setParameter("partner", $this->payConfig['partnerid']);
		$wxPayHelper->setParameter("out_trade_no",$orderid);
		$wxPayHelper->setParameter("total_fee", $price*100);
		$wxPayHelper->setParameter("fee_type", "1");
		$wxPayHelper->setParameter("notify_url", $this->siteUrl.'/index.php?g=Wap&m=Weixin&a=return_url&token='.$_GET['token'].'&wecha_id='.$_GET['wecha_id'].'&from='.$_GET['from']);
		//$wxPayHelper->setParameter("notify_url", 'http://www.baidu.com');
		$wxPayHelper->setParameter("spbill_create_ip", $_SERVER['REMOTE_ADDR']);
		$wxPayHelper->setParameter("input_charset", "GBK");
		$url=$wxPayHelper->create_biz_package();
		$this->assign('url',$url);
		//
		$from=$_GET['from'];
		$from=$from?$from:'Groupon';
		$from=$from!='groupon'?$from:'Groupon';
		switch ($from){
			default:
			case 'Groupon':
				break;
		}
		$returnUrl='/index.php?g=Wap&m='.$from.'&a=payReturn&token='.$_GET['token'].'&wecha_id='.$_GET['wecha_id'].'&orderid='.$orderid;
		$this->assign('returnUrl',$returnUrl);
		//$this->display('Weixin_pay.html');
		echo '<html><head><meta http-equiv="Content-Type"content="text/html; charset=UTF-8"><meta name="viewport"content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=no;"><meta name="apple-mobile-web-app-capable"content="yes"><meta name="apple-mobile-web-app-status-bar-style"content="black"><meta name="format-detection"content="telephone=no"><link href="/tpl/Wap/default/common/css/style/css/hotels.css"rel="stylesheet"type="text/css"><title>微信支付</title></head><script language="javascript">function callpay(){WeixinJSBridge.invoke(\'getBrandWCPayRequest\','.$url.',function(res){WeixinJSBridge.log(res.err_msg);if(res.err_msg==\'get_brand_wcpay_request:ok\'){document.getElementById(\'payDom\').style.display=\'none\';document.getElementById(\'successDom\').style.display=\'\';setTimeout("window.location.href = \''.$returnUrl.'\'",500);}else{document.getElementById(\'payDom\').style.display=\'none\';document.getElementById(\'failDom\').style.display=\'\';document.getElementById(\'failRt\').innerHTML=res.err_code+\'|\'+res.err_desc+\'|\'+res.err_msg;}});}</script><body style="padding-top:20px;"><style>.deploy_ctype_tip{z-index:1001;width:100%;text-align:center;position:fixed;top:50%;margin-top:-23px;left:0;}.deploy_ctype_tip p{display:inline-block;padding:13px 24px;border:solid#d6d482 1px;background:#f5f4c5;font-size:16px;color:#8f772f;line-height:18px;border-radius:3px;}</style><div id="payDom"class="cardexplain"><ul class="round"><li class="title mb"><span class="none">支付信息</span></li><li class="nob"><table width="100%"border="0"cellspacing="0"cellpadding="0"class="kuang"><tr><th>金额</th><td>'.$price.'元</td></tr></table></li></ul><div class="footReturn"style="text-align:center"><input type="button"style="margin:0 auto 20px auto;width:100%"onclick="callpay()"class="submit"value="点击进行微信支付"/></div></div><div id="failDom"style="display:none"class="cardexplain"><ul class="round"><li class="title mb"><span class="none">支付结果</span></li><li class="nob"><table width="100%"border="0"cellspacing="0"cellpadding="0"class="kuang"><tr><th>支付失败</th><td><div id="failRt"></div></td></tr></table></li></ul><div class="footReturn"style="text-align:center"><input type="button"style="margin:0 auto 20px auto;width:100%"onclick="callpay()"class="submit"value="重新进行支付"/></div></div><div id="successDom"style="display:none"class="cardexplain"><ul class="round"><li class="title mb"><span class="none">支付成功</span></li><li class="nob"><table width="100%"border="0"cellspacing="0"cellpadding="0"class="kuang"><tr><th>您已支付成功，页面正在跳转...</th></tr></table><div id="failRt"></div></td></tr></table></li></ul></div></body></html>';
	}
	//新版微信支付同步数据处理
	public function new_return_url (){
		$out_trade_no = $this->_get('out_trade_no');
		Log::write('out_trade_no1='.$out_trade_no,'DEBUG');
		if(intval($_GET['total_fee']) && !intval($_GET['trade_state'])) {
			//after
			$payHandel=new payHandle($_GET['token'],$_GET['from'],'weixin');
			$orderInfo=$payHandel->afterPay($out_trade_no,$_GET['transaction_id'],$_GET['transaction_id']);
			//此处应该更新一下订单状态，商户自行增删操作
			$payHandel=new payHandle($_GET['token'],$_GET['from'],'weixin');//0:token,1:from
			$orderInfo=$payHandel->afterPay($out_trade_no,$_GET['transaction_id'],$_GET['transaction_id']);
			Log::write('out_trade_no='.$out_trade_no,'DEBUG');
			//订单处理
			$order = M('product_cart')->where(array('orderid'=>$out_trade_no))->find();
			if($order['setInc']==0){
				$userInfo = M('Distribution_member')->where(array('token' => $_GET['token'], 'wecha_id' => $order['wecha_id']))->find();
				$this->distriOrderStatus($_GET['token'],$order['id'],1);
				/*if($userInfo['fid']!=0){
					M('Distribution_member')->where(array('token' => $attacharray[0], 'id' => $userInfo['fid']))->setInc('orderNums');//一级订单累加
				}
				if($userInfo['sid']!=0){
					M('Distribution_member')->where(array('token' => $attacharray[0], 'id' => $userInfo['sid']))->setInc('orderNums');//二级订单累加
				}
				if($userInfo['tid']!=0){
					M('Distribution_member')->where(array('token' => $attacharray[0], 'id' => $userInfo['tid']))->setInc('orderNums');//三级订单累加
				}*/
				$orderNums = M('product_cart')->where(array('wecha_id'=>$order['wecha_id'],'token'=>$_GET['token'],'paid'=>1))->count();
				if($orderNums==1){
					$dataDistri['beDistri'] = 1;
					M('product_cart')->where(array('orderid'=>$out_trade_no,'token'=>$_GET['token']))->save($dataDistri);
				}
				if($userInfo['distritime']==0){
					$datas['distritime'] = time();
					M('Distribution_member')->where(array('wecha_id' => $order['wecha_id'], 'token' => $_GET['token']))->save($datas);
				}
				//个人消息推送
				$access_token = $this->get_access_token($_GET['token']);
				$data = '{"touser":"'.$order['wecha_id'].'","msgtype":"text","text":{"content":"亲：您已成功付款，等候签收宝贝吧！"}}';
				$result = $this->api_notice_increment('https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$access_token,$data);
				//上级消息推送
				$this->infoSend($_GET['token'],$order['id'],$out_trade_no,$access_token);
				M('product_cart')->where(array('orderid'=>$out_trade_no))->setField('setInc',1);
			}
			$log_name = LOG_PATH."weixin_notify.log";//log文件路径
			Log::write("执行日期：".strftime("%Y-%m-%d-%H：%M：%S",time())."【支付成功】:\n".$xml."\n",'INFO','',$log_name);
			exit('SUCCESS');
		}else {
			exit('付款失败');
		}
	}
	//同步数据处理
	public function return_url (){
		S('pay',$_GET);
		$out_trade_no = $this->_get('out_trade_no');
		if(intval($_GET['total_fee']) && !intval($_GET['trade_state'])) {
			//after
			$payHandel=new payHandle($_GET['token'],$_GET['from'],'weixin');
			$orderInfo=$payHandel->afterPay($out_trade_no,$_GET['transaction_id'],$_GET['transaction_id']);
			exit('SUCCESS');
		}else {
			exit('付款失败');
		}
	}
	public function notify_url(){
		echo "success"; 
		eixt();
	}
	/**
	 * distriOrderStatus
	 */
	function distriOrderStatus($token,$order_id,$status) {
		$condition['order_id'] = $order_id;
		$condition['token'] = $token;
		$data['status'] = $status;
		$db = M('Distribution_ordermoney');
		$db->where($condition)->save($data);
		//删除库存
		//增加已售
		/*$carts = M('Product_cart_list')->where('cartid='.$order_id)->select();
		foreach ($carts as $key => $value) {
			M('Product')->where(array('id' => $value['productid']))->setDec('num', $value['total']);
			M('Product')->where(array('id' => $value['productid']))->setInc('fakemembercount', $value['total']);
			//增加真实销量
			M('Product')->where(array('id' => $value['productid']))->setInc('salecount', $value['total']);
		}*/
	}
	function infoSend($token,$orderid,$order_id,$access_token) {
		$condition['order_id'] = $orderid;
		$condition['token'] = $token;
		$db = M('Distribution_ordermoney');
		$orders = $db->where($condition)->select();
		foreach($orders as $key=>$value){
			$wecha_id = M('Distribution_member')->where('id='.$value['mid'])->getField('wecha_id');
			//$wecha_id = 'o3mw-s2byHCb4QnPLFQlI-6vsOQo';
			$url = "<a href='".C('site_url')."/index.php?g=Wap&m=Distribution&a=followOrder&token=".$token."&wecha_id=".$wecha_id."'>查看</a>";
			$data = '{"touser":"'.$wecha_id.'","msgtype":"text","text":{"content":"订单['.$order_id.']新增推广佣金+'.sprintf("%.2f",$value['offerMoney']/100).'元['.$url.']"}}';
			$this->api_notice_increment('https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$access_token,$data);
		}
	}
	function get_access_token($token){
		if($token!=''&&preg_match('/^[0-9a-zA-Z]{3,42}$/', $token)){
			$access_token = M('access_token')->where(array('token'=>$token))->find();
			if($access_token){
				$access_str = $this->get_new_access_token($token);
				$data['access_token'] = $access_str;
				$data['updatetime'] = time();
				M('access_token')->where(array('token'=>$token))->save($data);
			}else{
				$access_str = $this->get_new_access_token($token);
				$data['token'] = $token;
				$data['access_token'] = $access_str;
				$data['updatetime'] = time();
				M('access_token')->add($data);
			}
			return $access_str;
		}
	}
	function get_new_access_token($token){
		$wxuser = D('Wxuser')->where(array('token' => $token))->find();
		$rt = $this->curlGet('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . $wxuser['appid'] . '&secret=' . $wxuser['appsecret']);
        $jsonrt = json_decode($rt, 1);
		return $jsonrt['access_token'];
	}
	function api_notice_increment($url, $data){
		$ch = curl_init();
		$header = "Accept-Charset: utf-8";
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$tmpInfo = curl_exec($ch);
		$errorno=curl_errno($ch);
		if ($errorno) {
			return array('rt'=>false,'errorno'=>$errorno);
		}else{
			$js=json_decode($tmpInfo,1);
			if ($js['errcode']=='0'){
				return array('rt'=>true,'errorno'=>0);
			}else {
				//$this->error('发生错误：错误代码'.$js['errcode'].',微信返回错误信息：'.$js['errmsg']);
				Log::write('发生错误：错误代码'.$js['errcode'].',微信返回错误信息：'.$js['errmsg'].'openid='.$data->touser,'ERR');
			}
		}
	}
	function curlGet($url)
    {
        $ch = curl_init();
        $header = 'Accept-Charset: utf-8';
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $temp = curl_exec($ch);
        return $temp;
    }
}
?>