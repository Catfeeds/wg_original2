<?php
class wxpayAction extends BaseAction{

	public function index(){

		$orderid = $this->_get('order_id');
		Vendor("wxpay.WxPayPubHelper.WxPayPubHelper");
		//使用jsapi接口
		$jsApi = new JsApi_pub();

		//=========步骤1：网页授权获取用户openid============
		//通过code获得openid
		if (!isset($_GET['code']))
		{
			//触发微信返回code码
			$str = '&order_id='.$orderid.'&token='.$_GET['token'];
			$url = $jsApi->createOauthUrlForCode(WxPayConf_pub::JS_API_CALL_URL.urlencode($str));
			Header("Location: $url");
			exit();
		}else
		{
			//获取code码，以获取openid
			$code = $_GET['code'];
			$jsApi->setCode($code);
			$openid = $jsApi->getOpenId();
		}
		
		//=========步骤2：使用统一支付接口，获取prepay_id============
		//使用统一支付接口
		$unifiedOrder = new UnifiedOrder_pub();
		$order = M('product_cart')->where(array('orderid'=>$orderid))->find();
		if(!$order){
			exit('异常访问');
		}
		//设置统一支付接口参数
		//设置必填参数
		//appid已填,商户无需重复填写
		//mch_id已填,商户无需重复填写
		//noncestr已填,商户无需重复填写
		//spbill_create_ip已填,商户无需重复填写
		//sign已填,商户无需重复填写
		$unifiedOrder->setParameter("openid","$openid");//商品描述
		$unifiedOrder->setParameter("body","$orderid");//商品描述
		//自定义订单号，此处仅作举例
		$timeStamp = time();
		$total_fee = $order[price]*100;
		$out_trade_no = WxPayConf_pub::APPID."$timeStamp";
		$unifiedOrder->setParameter("out_trade_no","$orderid");//商户订单号 
		$unifiedOrder->setParameter("total_fee","$total_fee");//总金额
		$notify_url = C('site_url').'/notify.php';
		//$notify_url = urlencode($notify_url);
		$unifiedOrder->setParameter("notify_url",$notify_url);//通知地址
		$unifiedOrder->setParameter("trade_type","JSAPI");//交易类型
		//非必填参数，商户可根据实际情况选填
		//$unifiedOrder->setParameter("sub_mch_id","XXXX");//子商户号  
		//$unifiedOrder->setParameter("device_info","XXXX");//设备号 
		$unifiedOrder->setParameter("attach",$_GET['token'].'|store');//附加数据 
		//$unifiedOrder->setParameter("time_start","XXXX");//交易起始时间
		//$unifiedOrder->setParameter("time_expire","XXXX");//交易结束时间 
		//$unifiedOrder->setParameter("goods_tag","XXXX");//商品标记 
		//$unifiedOrder->setParameter("openid","XXXX");//用户标识
		//$unifiedOrder->setParameter("product_id","XXXX");//商品ID
		
		$prepay_id = $unifiedOrder->getPrepayId();
		//=========步骤3：使用jsapi调起支付============
		$jsApi->setPrepayId($prepay_id);
		$host = C('site_url'); //获取域名
		$returnUrl='/index.php?g=Wap&m=Store&a=payReturn&token='.$_GET['token'].'&wecha_id='.$openid.'&orderid='.$orderid;
		$this->assign('returnUrl',$returnUrl);
		$jsApiParameters = $jsApi->getParameters();
		//echo $jsApiParameters;
		$price = $order['price'];
		echo '<html><head><meta http-equiv="Content-Type"content="text/html; charset=UTF-8"><meta name="viewport"content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=no;"><meta name="apple-mobile-web-app-capable"content="yes"><meta name="apple-mobile-web-app-status-bar-style"content="black"><meta name="format-detection"content="telephone=no"><link href="/tpl/Wap/default/common/css/style/css/hotels.css" rel="stylesheet"type="text/css"><title>微信支付</title></head><script language="javascript">function callpay(){WeixinJSBridge.invoke(\'getBrandWCPayRequest\','.$jsApiParameters.',function(res){WeixinJSBridge.log(res.err_msg);if(res.err_msg==\'get_brand_wcpay_request:ok\'){document.getElementById(\'payDom\').style.display=\'none\';document.getElementById(\'successDom\').style.display=\'\';setTimeout("window.location.href = \''.$returnUrl.'\'",2000);}else{document.getElementById(\'payDom\').style.display=\'none\';document.getElementById(\'failDom\').style.display=\'\';document.getElementById(\'failRt\').innerHTML=res.err_code+\'|\'+res.err_desc+\'|\'+res.err_msg;}});}</script><body style="padding-top:20px;"><style>.deploy_ctype_tip{z-index:1001;width:100%;text-align:center;position:fixed;top:50%;margin-top:-23px;left:0;}.deploy_ctype_tip p{display:inline-block;padding:13px 24px;border:solid#d6d482 1px;background:#f5f4c5;font-size:16px;color:#8f772f;line-height:18px;border-radius:3px;}</style><div id="payDom"class="cardexplain"><ul class="round"><li class="title mb"><span class="none">支付信息</span></li><li class="nob"><table width="100%"border="0"cellspacing="0"cellpadding="0"class="kuang"><tr><th>金额</th><td>'.$price.'元</td></tr></table></li></ul><div class="footReturn"style="text-align:center"><input type="button"style="margin:0 auto 20px auto;width:100%"onclick="callpay()"class="submit"value="点击进行微信支付"/></div></div><div id="failDom"style="display:none"class="cardexplain"><ul class="round"><li class="title mb"><span class="none">支付结果</span></li><li class="nob"><table width="100%"border="0"cellspacing="0"cellpadding="0"class="kuang"><tr><th>支付失败</th><td><div id="failRt"></div></td></tr></table></li></ul><div class="footReturn"style="text-align:center"><input type="button"style="margin:0 auto 20px auto;width:100%"onclick="callpay()"class="submit"value="重新进行支付"/></div></div><div id="successDom"style="display:none"class="cardexplain"><ul class="round"><li class="title mb"><span class="none">支付成功</span></li><li class="nob"><table width="100%"border="0"cellspacing="0"cellpadding="0"class="kuang"><tr><th>您已支付成功，页面正在跳转...</td></tr></table><div id="failRt"></div></td></tr></table></li></ul></div></body></html>';
	}
	/**
	* 微信异步回调
	*/
	function dopay(){
		Vendor("wxpay.WxPayPubHelper.WxPayPubHelper");
		Log::write("TEST",'DEBUG');
		//使用通用通知接口
		$notify = new Notify_pub();
		//存储微信的回调   
		$xml = $GLOBALS['HTTP_RAW_POST_DATA'];	
		$notify->saveData($xml);
		
		$log_name = LOG_PATH."weixin_notify.log";//log文件路径
		Log::write("执行日期：".strftime("%Y-%m-%d-%H：%M：%S",time())."\n【接收到的notify通知】:\n".$xml."\n",'INFO','',$log_name);
		//验证签名，并回应微信。
		//对后台通知交互时，如果微信收到商户的应答不是成功或超时，微信认为通知失败，
		//微信会通过一定的策略（如30分钟共8次）定期重新发起通知，
		//尽可能提高通知的成功率，但微信不保证通知最终能成功。
		if($notify->checkSign() == FALSE){
			$notify->setReturnParameter("return_code","FAIL");//返回状态码
			$notify->setReturnParameter("return_msg","签名失败");//返回信息
		}else{
			if ($notify->data["return_code"] == "FAIL") {
				//此处应该更新一下订单状态，商户自行增删操作
				Log::write("执行日期：".strftime("%Y-%m-%d-%H：%M：%S",time())."【通信出错】:\n".$xml."\n",'INFO','',$log_name);
			}
			elseif($notify->data["result_code"] == "FAIL"){
				//此处应该更新一下订单状态，商户自行增删操作
				Log::write("执行日期：".strftime("%Y-%m-%d-%H：%M：%S",time())."【业务出错】:\n".$xml."\n",'INFO','',$log_name);
			}
			else{
				//此处应该更新一下订单状态，商户自行增删操作
				$out_trade_no = $notify->data["out_trade_no"]; //本地订单号
				
				$payHandel=new payHandle($_GET['token'],$_GET['from']);
				$orderInfo=$payHandel->afterPay($out_trade_no,$this->get('transaction_id'));
				$from=$payHandel->getFrom();
                //订单处理
				$order = M('product_cart')->where(array('orderid'=>$out_trade_no))->find();
				$datas['distritime'] = time();
				M('Distribution_member')->where(array('token' => $_GET['token'],'wecha_id' => $order['wecha_id']))->save($datas);
				$userInfo = M('Distribution_member')->where(array('token' => $_GET['token'], 'wecha_id' => $order['wecha_id']))->find();
				$this->distriOrderStatus($_GET['token'],$order['id'],1);
				M('Distribution_member')->where(array('token' => $_GET['token'], 'wecha_id' => $order['wecha_id']))->setInc('orderNums');//订单累加
				//消息推送
				$access_token = $this->get_access_token($_GET['token']);
				$url = C('site_url').U('Distribution/index',array('token'=>$_GET['token']));
				$set = M('Distribution_forward_set')->where(array('token'=>$_GET['token']))->find();
				$data = '{"touser":"'.$order['wecha_id'].'","msgtype":"news","news":{"articles":[{"title":"亲：恭喜您成为瑞士.梦美春分销股东！","description":"'.$set['fxtext'].'","url":"'.$url.'","picurl":"'.$set['fxpic'].'"]}}';
				$result = $this->api_notice_increment('https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$access_token,$data);

				Log::write("执行日期：".strftime("%Y-%m-%d-%H：%M：%S",time())."【支付成功】:\n".$xml."\n",'INFO','',$log_name);
				}
			$notify->setReturnParameter("return_code","SUCCESS");//设置返回码
		}
		$returnXml = $notify->returnXml();
		echo $returnXml;
	}
	/**
	 * distriOrderStatus
	 */
	private function distriOrderStatus($token,$order_id,$status) {
		$condition['order_id'] = $order_id;
		$condition['token'] = $token;
		$data['status'] = $status;
		$db = M('Distribution_ordermoney');
		$db->where($condition)->save($data);
	}
	private function get_access_token($token){
		if($token!=''&&preg_match('/^[0-9a-zA-Z]{3,42}$/', $token)){
			$access_token = M('access_token')->where(array('token'=>$token))->find();
			if($access_token){
				if(($access_token['updatetime']+1800)<time()){
					$access_str = $this->get_new_access_token($token);
					$data['access_token'] = $access_str;
					$data['updatetime'] = time();
					M('access_token')->where(array('token'=>$token))->save($data);
				}else{
					$access_str = $access_token['access_token'];
				}
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
	private function get_new_access_token($token){
		$wxuser = D('Wxuser')->where(array('token' => $token))->find();
		$rt = $this->curlGet('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . $wxuser['appid'] . '&secret=' . $wxuser['appsecret']);
        $jsonrt = json_decode($rt, 1);
		return $jsonrt['access_token'];
	}
	private function api_notice_increment($url, $data){
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
	//微信同步
	public function showpay() {
		$id = $_GET['id'];
		$orderstatus = D('order')->where(array('id'=>$id))->getField('status');
		if($orderstatus == '1'){
			$status = 1;
			$order_id = $this->_get('id','intval');
			$shop_id = D('order')->where('id='.$order_id)->getField('shop_id');
			$activity_url = D('shop')->where('id='.$shop_id)->getField('activity_url');
			$this->assign('activity_url',$activity_url);
		}else{
			$status = 0;
		}
		$this->assign('status', $status);
		$this->display();
	}
	
	// 打印log
	function  log_result($file,$word) 
	{
	    $fp = fopen($file,"a");
	    flock($fp, LOCK_EX) ;
	    fwrite($fp,"执行日期：".strftime("%Y-%m-%d-%H：%M：%S",time())."\n".$word."\n\n");
	    flock($fp, LOCK_UN);
	    fclose($fp);
	}
}