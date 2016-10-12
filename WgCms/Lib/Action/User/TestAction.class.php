<?php
class TestAction extends UserAction{
	public function _initialize(){
	}

	public function hongbao()
	{
		$url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack';
		$key = 'a35f2cb4ccd2c8b01b315e7d321bf83f';//API密钥
		$mch_id = 1266146901;//商户号
		$sub_mch_id = '';//子商户号
		$wxappid = 'wxd0b5a8cba85b8621';//appid
		$nick_name = '佣金提现';//提供方名称
		$send_name = '佣金提现';//商户名称
		$re_openid = 'oJbkIs0dySROFPx7Vf1gXU4rPFsM';//用户openid
		$total_amount = 100;//付款金额
		$min_value = 100;//最小红包金额
		$max_value = 100;//最大红包金额
		$total_num = 1;//红包发放总人数
		$wishing = '恭喜您，提现成功啦';//红包祝福语
		$client_ip = '121.40.231.46';//Ip地址
		$act_name = '佣金提现';//活动名称
		$remark = '备注';//备注
		$logo_imgurl = 'http://tyqc.tzwg.net/uploads/m/mhfcjx1421158741/d/3/5/3/thumb_55fffae4c91a3.jpg';//商户logo的url

		$data = array();
		$data['nonce_str'] = md5(rand(10000000,99999999));
		$data['mch_billno'] = $mch_id.date('Ymd').rand(1000000000,9999999999);
		$data['mch_id'] = $mch_id;
		$data['sub_mch_id'] = $sub_mch_id;
		$data['wxappid'] = $wxappid;
		$data['nick_name'] = $nick_name;
		$data['send_name'] = $send_name;
		$data['re_openid'] = $re_openid;
		$data['total_amount'] = $total_amount;
		$data['min_value'] = $min_value;
		$data['max_value'] = $max_value;
		$data['total_num'] = $total_num;
		$data['wishing'] = $wishing;
		$data['client_ip'] = $client_ip;
		$data['act_name'] = $act_name;
		$data['remark'] = $remark;
		$data['logo_imgurl'] = $logo_imgurl;
		
		$data['sign'] = $this->signValue($data,$key);
		$xml = new SimpleXMLElement('<xml></xml>');
        $this->data2xml($xml, $data);
        $postXML = $xml->asXML();
		$result = $this->api_notice_increment_xml($url,$postXML);
		dump($this->xmlToArray($result));
	}
	private function data2xml($xml, $data, $item = 'item')
    {
        foreach ($data as $key => $value) {
            is_numeric($key) && $key = $item;
            if (is_array($value) || is_object($value)) {
                $child = $xml->addChild($key);
                $this->data2xml($child, $value, $item);
            } else {
                if (is_numeric($value)) {
                    $child = $xml->addChild($key, $value);
                } else {
                    $child = $xml->addChild($key);
                    $node  = dom_import_simplexml($child);
                    $node->appendChild($node->ownerDocument->createCDATASection($value));
                }
            }
        }
    }
	private function signValue($data,$keyStr)
    {
		$str = '';
		ksort($data,SORT_STRING);
		foreach($data as $key=>$value){
			if($value!=''){
				$str .= $key.'='.$value.'&';
			}
		}
		$str .= 'key='.$keyStr;
		$sign = strtoupper(MD5($str));
		return $sign;
    }
	private	function api_notice_increment_xml($url, $data){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);

		//因为微信红包在使用过程中需要验证服务器和域名，故需要设置下面两行
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true); // 只信任CA颁布的证书 
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); // 检查证书中是否设置域名，并且是否与提供的主机名匹配


		curl_setopt($ch, CURLOPT_SSLCERT,CONF_PATH.'hongbao/apiclient_cert.pem');
		curl_setopt($ch, CURLOPT_SSLKEY,CONF_PATH.'hongbao/apiclient_key.pem');
		curl_setopt($ch, CURLOPT_CAINFO,CONF_PATH.'hongbao/rootca.pem'); // CA根证书（用来验证的网站证书是否是CA颁布）


		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		$res = curl_exec($ch);
		curl_close($ch);
		return $res;
	}
	/**
	 * 作用：将xml转为array
	 */
	public function xmlToArray($xml)
	{       
	   //将XML转为array        
	   $array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);       
	   return $array_data;
	}
}

