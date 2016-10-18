<?php
class StoreAction extends WapAction{
	public function __construct(){
		parent::_initialize();
	}
	//获取产品信息
	public function getCatsProducts(){
		$cats = M('Product_cat')->select();
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
		// dump($cats);
		$this->ajaxReturn($cats,'',1);
	}
	//显示上班时间
	function getWorkTime(){
		$sid = $this->_get('storeId');
		$dateFormat = $this->_get('dateFormat');
		$data = array(
			'error' => 0,
			'biz' => 'SAPI_GETSTOREDAYREMAIN',
			'msg' => array(
				'couponLimit' => 10,
				'date' => "11月16日 星期三",
				'needRM' => 0,
				'remain' => array(
					'1476806400' => array(
						'time' => '10:00',
						'count' => 2,
					),
				),
			),
		);
		exit(json_encode($data));
		// $starttime = strtotime('10:00');
		// $timearr = array();
		// for ($i=0; $i < 40; $i++) { 
		// 	array_push($timearr, date('H:i',$starttime + 900 * $i));
		// }
		// $this->ajaxReturn($timearr,'',1);
	}
	//获取区域信息
	function getAreaInfo(){
		$db = M('Area_list');
		$data = $db->select();
		$this->ajaxReturn($data,'',1);
	}
	//获取日期
	function getWorkDate(){
		$data = array(
			'error' => 0,
			'biz' => 'SAPI_GETSTOREREMAIN',
			'msg' => array(
				'couponLimit' => 10,
				'needRM' => 0,
				'remain' => array(
					'1476806400' => array(
						'data' => '2016-10-19',
						'count' => 2,
					),
				),
			),
		);
		exit(json_encode($data));
	}
	
}

?>