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
		$data = array(
			'banner' => $ba_arr,
			'product' => '',
		);
		exit($this->returnData($data));
	}
	//获取产品信息
	public function getCatsProducts(){
		//城市CODEID
		$storeId = $this->_get('storeId');
		$cats = M('Product_cat')->where(array('sid'=>$storeId))->select();
		$products = M('Product')->where(array('isopen'=>1))->select();
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
		$timeNums = M('Store_list')->where(array('id'=>$storeId))->getField('timeNums');
		//获取已经预约的时间
		$carts = M('Product_cart')->where(array("storeid"=>$storeId,'paid'=>1,'returMoney'=>0))->select();


		//日期
		$date = (int)$m."月".(int)$d."日 星期".$w;
		//当天时间预约
		$starttime = $str_time + 36000;

		for ($i=0; $i < 40; $i++) {
			$t = $starttime + 900 * $i;
			$del = 0;
			foreach ($carts as $k => $v) {
				if($v['rtime'] == $t){
					$del += 1;
				}
			}
			$remain[$t] = array(
				'time'  => date('H:i',$t),
				'count' => $timeNums - $del,
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
		$timeNums = M('Store_list')->where(array('id'=>$storeId))->getField('timeNums');

		//获取已预约日期
		$today = date(strtotime('today'));
		for ($i=0; $i < 36; $i++) {
			$t = $today + 86400 * $i;
			$remain[$t] = array(
				'data' => date('Y-m-d',$t),
				'count' => $timeNums*40,
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
			$storeinfo[$v['id']] = array(
				'S_Address'   => $v['address'],
				'S_Id'        => $v['id'],
				'S_MapUrl'    => "",
				'S_Name'      => $v['name'],
				'S_TELE'      => $v['tele'],
				'S_Photo'     => $v['pic'],
				'S_Service'   => "1111000",
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
}

?>