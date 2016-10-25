<?php
class StoreAction extends WapAction{
	public function __construct(){
		parent::_initialize();
	}
	function returnData($data){
		$data = array(
			'biz' => 'SAPI_GETINDEXDATA',
			'msg' => $data,
			'error' => 0,
		);
		return json_encode($data);
	}
	//获取BANNER
	function getBanner(){
		//BANNER
		$db = M('Product_banner');
		$list = $db->order('sort desc')->select();
		$ba_arr = array();
		foreach ($list as $k => $v) {
			$ba = array(
				'BA_Path' => $v['picurl'],
				'BA_Url' => $v['url'],
			);
			array_push($ba_arr, $ba);
		}
		//首页产品
		$classify = M('Product_show_classify')->select();
		foreach ($classify as $k => $v) {
			$pro = M('Product_show')->where(array('cid'=>$v['id']))->select();
			$PT_Product = array();
			foreach ($pro as $k2 => $v2) {
				$PT_Product[$k2]['P_Id'] = $v2['id'];
				$PT_Product[$k2]['P_ImgPath'] = $v2['pic'];
				$PT_Product[$k2]['P_Name'] = $v2['name'];
				$PT_Product[$k2]['P_Parent'] = '0';
				$PT_Product[$k2]['P_Price'] = $v2['price'];
				$PT_Product[$k2]['P_Price_Ext1'] = '1';
				$PT_Product[$k2]['P_Type'] = '1';
			}

			$product[$v['id']] = array(
				'PT_Id' => $v['id'],
				'PT_Name' => $v['name'],
				'PT_Product' => $PT_Product
			);
		}
		$data = array(
			'banner' => $ba_arr,
			'product' => $product,
		);
		exit($this->returnData($data));
	}
	//展示产品详情
	public function getProductDetail(){
		$pid = $this->_get('pid');
		$pro = M('Product_show')->where(array('id'=>$pid))->find();

		$str .='<div class="list-block media-list">';
		$str .='    <ul>';
		$str .='        <li>';
		$str .='            <a href="#" class="item-link item-content">';
		$str .='                <div class="item-inner">';
		$str .='                    <div class="item-title-row">';
		$str .='                        <div class="item-title">'.$pro['name'].'</div>';
		$str .='                        <div class="item-after total-color">'.$pro['price1'].'元/套</div>';
		$str .='                    </div>';
		$str .='                    <div class="item-text">'.$pro['desc1'].'</div>';
		$str .='                </div>';
		$str .='            </a>';
		$str .='        </li>';
		$str .='    </ul>';
		$str .='</div>';

		$str .='<div class="list-block media-list">';
		$str .='    <ul>';
		$str .='        <li>';
		$str .='            <a href="#" class="item-link item-content">';
		$str .='                <div class="item-inner">';
		$str .='                    <div class="item-title-row">';
		$str .='                        <div class="item-title">加修加印</div>';
		$str .='                        <div class="item-after total-color">'.$pro['price2'].'元/套</div>';
		$str .='                    </div>';
		$str .='                    <div class="item-text">'.$pro['desc2'].'</div>';
		$str .='                </div>';
		$str .='            </a>';
		$str .='        </li>';
		$str .='    </ul>';
		$str .='</div>';

		$str .='<div class="list-block media-list">';
		$str .='    <ul>';
		$str .='        <li>';
		$str .='            <a href="#" class="item-link item-content">';
		$str .='                <div class="item-inner">';
		$str .='                    <div class="item-title-row">';
		$str .='                        <div class="item-title">加印</div>';
		$str .='                        <div class="item-after total-color">'.$pro['price3'].'元/套</div>';
		$str .='                    </div>';
		$str .='                    <div class="item-text">'.$pro['desc3'].'</div>';
		$str .='                </div>';
		$str .='            </a>';
		$str .='        </li>';
		$str .='    </ul>';
		$str .='</div>';

		$str .='<div class="wg_item_order_show">';
		$str .='    <div class="wg_item_order_show_content">';
		$str .='        <div class="wg_item_order_show_title">客片展示</div>';
		$str .='        <div><img class="w100" src="'.$pro['pic'].'"></div>';
		$str .='        <div class="clear"></div>';
		$str .='    </div>';
		$str .='</div>';

		$this->ajaxReturn($str,'',1);
	}

	//获取产品信息
	public function getCatsProducts(){
		//城市CODEID
		$storeId = $this->_get('storeId');
		$cats = M('Product_cat')->where(array('sid'=>$storeId))->select();
		$cats_str = '';
		foreach ($cats as $k => $v) {
			$cats_str .= $v['id'].',';
		}
		$products = M('Product')->where(array('isopen'=>1,'catid'=>array('in',$cats_str)))->select();
		foreach ($cats as $k => $v) {
			$parr = array();
			foreach ($products as $k2 => $v2) {
				$cats[$k]['products'] = array();
				if($v2['catid'] == $v['id']){
					array_push($parr, $products[$k2]);
				}
				array_push($cats[$k]['products'], $parr);
			}
		}
		$this->ajaxReturn($cats,'',1);
	}
	//显示上班时间
	function getWorkTime(){
		$storeId = $this->_get('storeId');
		$dateFormat = $this->_get('dateFormat');
		$str_time = strtotime($dateFormat);
		$m = date('m',$str_time);
		$d = date('d',$str_time);
		$w = $this->numberToCn(date('w',$str_time));
		//查询店铺每个时间段限制预约人数
		$timeNums = M('Store_list')->where(array('id'=>$storeId))->getField('defaultWorkTime');
		$timeNums = unserialize($timeNums);
		//获取已经预约的时间
		$carts = M('Product_cart')->where(array("storeid"=>$storeId,'paid'=>1,'returMoney'=>0))->select();
		//查看有没附加时间
		$condition = array(
			'time' => array('gt',time()),
			'sid' => $storeId,
		);
		$limittimes = M('Store_addtime')->where($condition)->select();
		$addtime = array();
		foreach ($limittimes as $k => $v) {
			$addtime[$v['time']] = $v;
		}

		//日期
		$date = (int)$m."月".(int)$d."日 星期".$w;
		//当天时间预约
		$starttime = $str_time + 36000;

		for ($i=0; $i < 40; $i++) {
			$t = $starttime + 900 * $i;
			$del = 0;
			//减去已预约的时间段
			foreach ($carts as $k => $v) {
				if($v['rtime'] == $t){
					$del += 1;
				}
			}
			//判断有没附加限制时间
			if($addtime[$str_time]){
				$timeNums = unserialize($addtime[$str_time]['defaultWorkTime']);
			}
			$remain[$t] = array(
				'time'  => date('H:i',$t),
				'count' => $timeNums[$i] - $del,
			);
		}

		$msg = array(
			'couponLimit' => 10,
			'date'        => $date,
			'needRM'      => 0,
			'remain'      => $remain,
		);
		exit($this->returnData($msg));
	}
	//数字转中文
	function numberToCn($n){
		switch ($n) {
			case '1':
				return '一';
				break;
			case '2':
				return '二';
				break;
			case '3':
				return '三';
				break;
			case '4':
				return '四';
				break;
			case '5':
				return '五';
				break;
			case '6':
				return '六';
				break;
			case '7':
				return '七';
				break;
		}
	}
	//获取区域信息
	function getAreaInfo(){
		$db = M('Area_list');
		$data = $db->select();
		$this->ajaxReturn($data,'',1);
	}
	//获取日期
	function getWorkDate(){
		$storeId = $this->_get('storeId');
		//查询店铺每个时间段限制预约人数
		$timeNums = M('Store_list')->where(array('id'=>$storeId))->getField('defaultWorkTime');
		$timeNums = unserialize($timeNums);
		foreach ($timeNums as $k => $v) {
			$dailyWorkNums += $v;
		}

		//查看有没附加时间
		$condition = array(
			'time' => array('gt',time()),
			'sid' => $storeId,
		);
		$limittimes = M('Store_addtime')->where($condition)->select();
		$addtime = array();
		foreach ($limittimes as $k => $v) {
			$addtime[$v['time']] = $v;
		}
		//获取已经预约的时间
		$carts = M('Product_cart')->where(array("storeid"=>$storeId,'paid'=>1,'returMoney'=>0))->select();


		//获取已预约日期
		$today = date(strtotime('today'));
		for ($i=0; $i < 36; $i++) {
			$t = $today + 86400 * $i;
			$addlimittime = 0;
			if($addtime[$t]){
				$info = $addtime[$t]['defaultWorkTime'];
				$info = unserialize($info);
				foreach ($info as $k => $v) {
					$addlimittime += $v;
				}
			}
			$del = 0;
			foreach ($carts as $k => $v) {
				if($v['rtime'] == $t){
					$del += 1;
				}
			}
			$remain[$t] = array(
				'data' => date('Y-m-d',$t),
				'count' => $addlimittime == 0 ? $dailyWorkNums-$del : $addlimittime-$del,
			);
		}
		//获取自定义日期
		$msg = array(
				'couponLimit' => 15,
				'needRM' => 0,
				'remain' => $remain,
			);
		exit($this->returnData($msg));
	}
	//获取城市信息
	function getCityList(){
		$citys_data = M('City_list')->select();
		$firstChar = M('City_list')->Distinct(true)->field('char')->select();
		//城市列表
		$citys = array();
		//城市首字母列表
		$cityfirstchar = array();
		foreach ($firstChar as $k => $v) {
			array_push($cityfirstchar, $v['char']);
			$citys[$v['char']] = array();
			foreach ($citys_data as $k2 => $v2) {
				if($v['char'] == $v2['char']){
					$city = array(
						'SC_FirstChar'=> $v['char'],
						'SC_Name'=> $v2['name'],
						'SC_Id'=> $v2['citycode'],
					);
					array_push($citys[$v['char']], $city);
				}
			}
		}
		//热门城市列表
		$hotcity = array();
		foreach ($citys_data as $k => $v) {
			if($v['hot'] == 1){
				$hot = array(
					'SC_Id' => $v['citycode'],
					'SC_Name' => $v['name'],
				);
				array_push($hotcity, $hot);
			}
		}
		$data = array(
			'error' => 0,
			'biz' => 'SAPI_GETCITYLIST',
			'msg' => array(
				'city' => $citys,
				'cityfirstchar' => $cityfirstchar,
				'hotcity' => $hotcity,
			),
		);
		exit(json_encode($data));
	}
	//包装门店
	//通过城市ID获取门店信息
	function getStoreByCity(){
		$cid = $this->_get('cid');
		$db = M('Store_list');
		$city = M('City_list')->where(array('citycode'=>$cid))->find();
		$stores = $db->where(array('cid'=>$city['id']))->select();
		$storeinfo = array();
		$storelist = array();
		foreach ($stores as $k => $v) {
			$s = unserialize($v['service']);
			$S_Service = ($s['photo']==1 ? '1' : '0') . ($s['makeup']==1 ? '1' : '0') . ($s['cloth']==1 ? '1' : '0') . ($s['ps']==1 ? '1' : '0') . ($s['nail']==1 ? '1' : '0') . ($s['sharon']==1 ? '1' : '0') . ($s['coffee']==1 ? '1' : '0');

			$storeinfo[$v['id']] = array(
				'S_Address'   => $v['address'],
				'S_Id'        => $v['id'],
				'S_MapUrl'    => 'http://api.map.baidu.com/marker?location='.$v['longitude'].','.$v['dimension'].'&title=台州时创数码&content=地址：台州市路桥区会展西路2-6号&output=html',
				'S_Name'      => $v['name'],
				'S_TELE'      => $v['tele'],
				'S_Photo'     => $v['pic'],
				'S_Service'   => $S_Service,
				'S_StartTime' => "0",
			);
			$s = array(
				'S_Id'   => $v['id'],
				'S_Name' => $v['name'],
			);
			array_push($storelist, $s);
		}
		$data = array(
			'city' => array(
				'SC_ID' => $city['codeid'],
				'SC_NAME' => $city['name'],
			),
			'storeinfo' => $storeinfo,
			'storelist' => $storelist,
		);
		exit($this->returnData($data));
	}
	//生成订单
	function payOrder(){
		$data = htmlspecialchars_decode($this->_post('data'));
		$data = htmlspecialchars_decode($data);
		// Log::write($data,'DEBUG');
		// $data = '{"sid":"2","ordertime":1477965600,"con":{"80":{"pid":80,"name":"证件照","price":"60","type":"1","colorinfo":"{\"blue\":{\"price\":\"1\"},\"white\":{\"price\":\"2\"},\"red\":{\"price\":\"3\"},\"yellow\":{\"price\":\"4\"},\"grey\":{\"price\":\"5\"}}","artinfo":"","artex":"","wmprice":"1","choose":"{\"blue\":{\"price\":1}}"},"90":{"pid":90,"name":"结婚照","price":"75","type":"3","colorinfo":"","artinfo":"","artex":"","wmprice":"1"},"91":{"pid":91,"name":"文艺照","price":"75","type":"2","colorinfo":"","artinfo":"{\"personal\":{\"price\":\"1\"},\"friends\":{\"price\":\"2\"},\"childrens\":{\"price\":\"3\"},\"lovers\":{\"price\":\"4\"}}","artex":"{\"four\":{\"price\":\"1\"},\"nine\":{\"price\":\"2\"}}","wmprice":"0","choose":"{\"personal\":{\"price\":1}}"},"132":{"pid":132,"name":"一般照","price":"10","type":"0","colorinfo":"","artinfo":"","artex":"","wmprice":"0"}},"myinfo":{"id":"1","areaid":"0","areaname":"","headimgurl":"http://qchpt.b0.upaiyun.com/mhfcjx1421158741/2016/07/28/1469701369_ltmojad1p43nanvx.jpg","truename":"12311","sex":"0","birth":"2016-10-19","editname":"0","tele":"","username":"1","password":"c81e728d9d4c2f636f067f89cc14862c","wecha_id":"","mid":"","ip":"","status":"0","addtime":"0","year":"0","month":"0","day":"0","delete":"0"},"totalPrice":73,"city":"上海市"}';
		$data = json_decode($data,true);
		$rightprice = 0;
		//校验摄影数据信息
		$price1 = 0;//一般照
		$price2 = 0;//证件照
		$price3 = 0;//文艺照
		$price4 = 0;//结婚照
		$info = '';
		foreach ($data['con'] as $k => $v) {
			$product = M('Product')->where(array('id'=>$k))->find();
			switch ($v['type']) {
				//一般照
				case '0':
					if($v['price'] != $product['price']){
						$info .= $product['name'] . '价格错误,';
					}
					$price1 += $product['price'];;
					$rightprice += $product['price'];
					break;
				//证件照
				case '1':
					$rightprice += $product['price'];
					$price2 += $product['price'];
					$rightColorInfo = json_decode($product['colorinfo'],true);
					$checkcolor = json_decode($v['choose'],ture);
					foreach ($checkcolor as $k2 => $v2) {
						if($checkcolor[$k2]['price'] != $rightColorInfo[$k2]['price']){
							$info .= $product['name'] . '价格错误,';
							break;
						}
						$rightprice += $rightColorInfo[$k2]['price'];
						$price2 += $rightColorInfo[$k2]['price'];
					}
					break;
				//文艺照
				case '2':
					if($v['choose'] && $v['choose'] != '{}'){
						$checkart = json_decode($v['choose'],ture);
					}
					if($v['chooseartex'] && $v['chooseartex'] != '{}'){
						$checkartex = json_decode($v['chooseartex'],ture);
					}
					if($checkart){
						$rightArtInfo = json_decode($product['artinfo'],true);
						//宫格价格
						$artExPrice = 0;
						//校验宫格价格
						if($checkartex){
							$rightArtExInfo = json_decode($product['artex'],ture);
							foreach ($checkartex as $k2 => $v2) {
								if($checkartex[$k2]['price'] != $rightArtExInfo[$k2]['price']){
									$info .= $product['name'] . '价格错误,';
									break;
								}
								$artExPrice += $rightArtExInfo[$k2]['price'];
							}
						}
						//校验类型价格
						foreach ($checkart as $k2 => $v2) {
							if($checkart[$k2]['price'] != $rightArtInfo[$k2]['price']){
								$info .= $product['name'] . '价格错误,';
								break;
							}
							$rightprice += $rightArtInfo[$k2]['price'] + $artExPrice;
							$price3 += $rightArtInfo[$k2]['price'] + $artExPrice;
						}

					}
					break;
				//结婚照校验
				case '3':
					if($v['price'] != $product['price']){
						$info .= $product['name'] . '价格错误,';
					}
					$rightprice += $product['price'];
					$price4 += $product['price'];
					if($v['choosew']){
						if($v['wmprice'] != $product['wmprice']){
							$info .= '搞怪结婚照价格错误';
						}
						$rightprice += $product['wmprice'];
						$price4 += $product['wmprice'];
					}
					break;
			}
		}
		//数据验证
		// dump($rightprice);
		// dump('一般照：'.$price1);
		// dump('证件照：'.$price2);
		// dump('文艺照：'.$price3);
		// dump('结婚照：'.$price4);
		// dump($data['totalPrice']);
		// exit();
		if($rightprice != $data['totalPrice']){
			$info .= '总价有误';
		}
		if($info){
			$this->ajaxReturn('',$info,-1);
		}
		//预约内容
		$total = 0;
		foreach ($data['con'] as $k => $v) {
			$price = 0;
			$total ++;
			//计算价格
			switch ($v['type']) {
				//一般照
				case '0':
					$price = $v['price'];
					break;
				//证件照
				case '1':
					$price += $v['price'];
					$checkcolor = json_decode($v['choose'],ture);
					foreach ($checkcolor as $k2 => $v2) {
						$price += $v2['price'];
					}
					break;
				//文艺照
				case '2':
					$checkcolor = json_decode($v['choose'],ture);
					$exprice = 0;
					if($v['chooseartex'] && $v['chooseartex'] != '{}'){
						$checkartex = json_decode($v['chooseartex'],ture);
					}
					if($checkartex){
						foreach ($checkartex as $k2 => $v2) {
							$exprice += $v2['price'];
						}
					}
					foreach ($checkcolor as $k2 => $v2) {
						$price += $v2['price'] + $exprice;
					}
					break;
				case '3':
					$price = $v['price'];
					if($v['choosew']){
						$price += $v['wmprice'];
					}
					break;

			}
			$reservation[$k] = array(
				'name' => $v['name'],
				'type' => $v['type'],
				'choose' => $v['choose'],
				'price' => $price,
				'choosew' => $v['choosew'],
				'chooseartex' => $v['chooseartex'],
				'total_price' => $v['total_price'],
				'attribute' => $v['attribute'],
			);
		}
		//生成订单数据列表
		$orderid = substr(time(), -1, 4) . date("YmdHis");
		$store = M('Store_list')->where(array('id'=>$data['id']))->find();
		$order_data = array(
			'storeid' => $data['sid'],
			'sname' => $store['name'],
			'rtime' => $data['time'],
			'aid' => $data['myinfo']['id'],
			'truename' => $data['myinfo']['truename'],
			'tel' => $data['myinfo']['username'],
			'wecha_id' => $data['myinfo']['wecha_id'],
			'birth' => $data['myinfo']['birth'],
			'stel' => $store['tele'],
			'sex' => $data['myinfo']['sex'],
			'info' => serialize($reservation),
			'total' => $total,
			'price' => $rightprice,
			'city' => $data['city'],
			'time' => time(),
			'year' => date('Y',time()),
			'month' => date('m',time()),
			'day' => date('d',time()),
			'hour' => date('H',time()),
			'orderid' => $orderid,
			'token' => $this->token,
		);
		$re = M('Product_cart')->add($order_data);
		if($re){
			$return_data = array(
				'token' => $this->token,
				'wehcha_id' => $data['myinfo']['wecha_id'],
				'price' => $rightprice,
				'orderid' => $orderid,
			);
			$this->ajaxReturn(json_encode($return_data),'',1);
		}else{
			$this->ajaxReturn('','',-1);
		}
	}
	//立即支付
	function payNow(){
		$id = $this->_get('id');
		$orderid = substr(time(), -1, 4) . date("YmdHis");
		M('Product_cart')->where(array('id'=>$id))->setField('orderid',$orderid);
		$cart = M('Product_cart')->where(array('id'=>$id))->find();
		$this->success('正在提交中...', U('Alipay/pay',array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'success' => 1, 'from'=> 'Store', 'orderName' => $orderid, 'single_orderid' => $orderid, 'price' => $cart['price'])));
	}
}

?>