<?php
	class DistributionAction extends WapAction{
		public function __construct(){
			parent::_initialize();
		}
		function test2(){
<<<<<<< HEAD
			//dump(session('bmywecha_id'));
			setcookie('zxg_login_user',null);
			dump(cookie('zxg_login_user'));
		}
		function test(){
			session('bmywecha_id','oV52LvzzFN0rrhyNUBTpeDB-eVX0');
=======
			dump(session('jump_href'));
		}
		function test(){
			session('bmywecha_id',null);
			session('bmywecha_id','');
			dump(session('bmywecha_id'));
>>>>>>> 01e200e4f8a1295bfce0a15384da812e38d13ba2
		}
		//授权页面
		public function authorization(){
			$url=session('jump_href'); 
			Header("HTTP/1.1 303 See Other"); 
			Header("Location: $url"); 
			exit;
		}
		public function verify(){
			Image::buildImageVerify();
		}
		function myUnserialize($data){
			$arr1 = explode('&', $data);
			$arr2 = array();
			foreach ($arr1 as $k => $v) {
				$aa = explode('=', $v);
				$arr2[$aa[0]] = $aa[1];
			}
			return $arr2;
		}
		//短信验证
		public function sendSms(){
			$key = String::randString(6,1);
			$register_key = array(
				'key' => $key,
				'time' => time() + 300,
			);
			S('register_key',$register_key);
			$mobile = $this->_post('mobile','trim');
			$content = $key;
			$return = Sms::sendSms($content,$mobile);
			Log::write('phone:'.$mobile,'DEBUG');
			Log::write('sms:'.$return,'DEBUG');
			$smsreturn = explode(',', $return);
			$this->ajaxReturn($key,$smsreturn[0],1);
		}
		//验证账号唯一性
		
		//注册
		function register(){
			$data = $this->htmlSpecial('post');
			$db = D('Account');
			//基本验证
			foreach ($data as $k => $v) {
				switch ($k) {
					case 'username':
						if($v == ''){
							$warn = '用户名不能为空';
						}else{
							//认证账号唯一性
							if(D('Account')->where(array('username'=>$v))->find()){
								$warn = '用户名已存在';
							}
						}
						break;
					case 'img_code':
						if($v == ''){
							$warn = '图片验证码不能为空';
						}else{
							//验证图片验证码
							if(md5($data['img_code']) != session('verify')){
								$this->ajaxReturn(session('verify'),'图片验证码错误',-1);
							}
						}
						break;
					case 'msg_code':
						if($v == ''){
							$warn = '短信验证码不能为空';
						}else{
							if($v != S('register_key')['key']){
								$warn = '短信验证码错误';
							}
							//验证短信验证码
						}
						break;
					case 'password':
						if($v == ''){
							$warn = '密码不能为空';
						}
						break;
					case 'repassword':
						if($v == ''){
							$warn = '确认密码不能为空';
						}
						break;
				}
				if($warn){
					$this->ajaxReturn($k,$warn,-1);
				}
			}
			//写入数据
			$write_data = array(
				'username' => $data['username'],
				'password' => md5($data['password']),
			);
			$re = $db->add($write_data);
			if($re){
				//清除短信验证码和图形验证码缓存
				S('register_key','');
				session('verify','');
				$this->ajaxReturn($re,'注册成功',1);
			}else{
				$this->ajaxReturn($re,'注册失败',-1);
<<<<<<< HEAD
			}
		}
		//找回密码
		function findPassword(){
			$data = $this->htmlSpecial('post');
			$db = D('Account');
			//基本验证
			foreach ($data as $k => $v) {
				switch ($k) {
					case 'username':
						if($v == ''){
							$warn = '用户名不能为空';
						}
						break;
					case 'img_code':
						if($v == ''){
							$warn = '图片验证码不能为空';
						}else{
							//验证图片验证码
							if(md5($data['img_code']) != session('verify')){
								$this->ajaxReturn(session('verify'),'图片验证码错误',-1);
							}
						}
						break;
					case 'msg_code':
						if($v == ''){
							$warn = '短信验证码不能为空';
						}else{
							if($v != S('register_key')['key']){
								$warn = '短信验证码错误';
							}
							//验证短信验证码
						}
						break;
					case 'password':
						if($v == ''){
							$warn = '新密码不能为空';
						}
						break;
					case 'repassword':
						if($v == ''){
							$warn = '确认新密码不能为空';
						}
						break;
				}
				if($warn){
					$this->ajaxReturn($k,$warn,-1);
				}
			}
			//修改数据
			$write_data = array(
				'password' => md5($data['password']),
			);
			$re = $db->where(array('username'=>$data['username']))->save($write_data);
			if($re){
				//清除短信验证码和图形验证码缓存
				S('register_key','');
				session('verify','');
				$this->ajaxReturn($re,'重置成功',1);
			}else{
				$this->ajaxReturn($re,'重置失败',-1);
=======
>>>>>>> 01e200e4f8a1295bfce0a15384da812e38d13ba2
			}
		}
		function login(){
			$data = $this->htmlSpecial('post');
			$condition = array(
				'username' => $data['username'],
				'password' => md5($data['password']),
			);
			if(D('Account')->where($condition)->find()){
				setcookie("zxg_login_user", $data['username'], time()+3600*24*3);
				if(session('bmywecha_id')){
<<<<<<< HEAD
					$member = M('Distribution_member')->where(array('wecha_id'=>session('bmywecha_id')))->find();
					$mdata = array(
						'wecha_id' => $member['wecha_id'],
						'headimgurl' => $member['headimgurl'],
						'mid' => $member['id'],
					);
					
					D('Account')->where($condition)->save($mdata);
=======
					$headimgurl = M('Distribution_member')->where(array('wecha_id'=>session('bmywecha_id')))->getField('headimgurl');
					D('Account')->where($condition)->setField('headimgurl',$headimgurl);
>>>>>>> 01e200e4f8a1295bfce0a15384da812e38d13ba2
				}
				$this->ajaxReturn($_COOKIE['zxg_login_user'],'登陆成功',1);
			}else{
				$this->ajaxReturn('','账号或密码错误',2);
			}
		}
		//解析js的JSON数据
		function htmlSpecial($type){
			switch ($type) {
				case 'post':
					$data = htmlspecialchars_decode(htmlspecialchars_decode($this->_post('data')));
					break;
				
				default:
					$data = htmlspecialchars_decode(htmlspecialchars_decode($this->_get('data')));
					break;
			}
			$data = $this->myUnserialize($data);
			return $data;
		}
		//获取个人信息
		function myInfo(){
			$account = $this->checkLogin('account');
			$account['cnums'] = 0;
			$account['cnums'] = M('Coupons')->where(array('aid'=>$account['id'],'status'=>3))->count();
			$this->ajaxReturn($account,'',1);
		}
		//保存个人信息
		function saveMyInfo(){
			$data = $this->htmlSpecial('post');
			$username = $this->checkLogin('username');
			$re = D('Account')->where(array('username'=>$username))->save($data);
			if($re){
				$this->ajaxReturn('','修改成功',1);
			}else{
				$this->ajaxReturn('','修改成功',2);
			}
		}
		//修改密码
		function changePasswrod(){
			$data = $this->htmlSpecial('post');
			$account = $this->checkLogin('account');
			//判断旧密码
			if(md5($data['oldPassword']) != $account['password']){
				$this->ajaxReturn('','旧密码不正确',-1);
			}
			if($data['newPassword'] != $data['rePassword']){
				$this->ajaxReturn('','两次密码输入不相同',-1);
			}
			$re = D('Account')->where(array('username'=>$account['username']))->save(array('password'=>md5($data['newPassword'])));
			if($re){
				$this->ajaxReturn('','修改成功',1);
			}else{
				$this->ajaxReturn('','修改失败',-1);
			}
		}
		function loginOut(){
			setcookie('zxg_login_user',null);
			$this->ajaxReturn('','',1);
		}
		//我的订单
		function myOrders(){
			$account = $this->checkLogin('account');
			$db = M('Product_cart');
<<<<<<< HEAD
			$orders = $db->where(array('aid'=>$account['id']))->order('id desc')->select();
			$my_orders = array();
			foreach ($orders as $k => $v) {
				$my_orders[$v['id']] = $v;
				$my_orders[$v['id']]['datertime'] = date('Y-m-d H:i',$v['rtime']);
				$my_orders[$v['id']]['datetime'] = date('Y-m-d H:i',$v['time']);
				$orders[$k]['datertime'] = date('Y-m-d H:i',$v['rtime']);
				$orders[$k]['datetime'] = date('Y-m-d H:i',$v['time']);
				$store = M('Store_list')->where('id='.$v['storeid'])->find();
				$orders[$k]['storeurl'] = 'http://api.map.baidu.com/marker?location='.$store['dimension'].','.$store['longitude'].'&title='.$store['name'].'&content=地址：'.$store['address'].'&output=html';
			}
			// dump($my_orders);
			$this->ajaxReturn($orders,$my_orders,1);
=======
			$orders = $db->where(array('aid'=>$account['id']))->select();
			foreach ($orders as $k => $v) {
				$orders[$k]['rtime'] = date('Y-m-d H:i',$v['rtime']);
			}
			$this->ajaxReturn($orders,'',1);
>>>>>>> 01e200e4f8a1295bfce0a15384da812e38d13ba2
		}
		//判断登陆
		function checkLogin($get = ''){
			$username = $_COOKIE['zxg_login_user'];
<<<<<<< HEAD
			if($username){
				$account = D('Account')->where(array('username'=>$username))->find();
			}else{
				//已注册免登陆
				$account = D('Account')->where(array('wecha_id'=>session('bmywecha_id')))->find();
				if($account){
					setcookie("zxg_login_user", $data['username'], time()+3600*24*3);
				}else{

				}
			}
=======
			$account = D('Account')->where(array('username'=>$username))->find();
>>>>>>> 01e200e4f8a1295bfce0a15384da812e38d13ba2

			if($account){
				switch ($get) {
					case 'account':
						return $account;
						break;
					case 'username':
						return $username;
						break;
					default:
						$this->ajaxReturn('','',1);
						break;
				}
			}else{
				$this->ajaxReturn($no_auth,'账号未登陆',-1);
			}
		}
		//判断授权
		function checkAuth(){
			$agent = $_SERVER['HTTP_USER_AGENT']; 
			if(!session('bmywecha_id') && strpos($agent,"icroMessenger")){
				$this->ajaxReturn('needauth','',-1);
			}else{
				$this->ajaxReturn('','',1);
<<<<<<< HEAD
			}
		}
		//意见反馈
		function feedBack(){
			$con = $this->_post('con');
			$db = M('Feedback_list');
			$account = $this->checkLogin('account');
			$data = array(
				'content' => $con,
				'aid' => $account['id'],
				'addtime' => time(),
				'year' => date('Y',time()),
				'month' => date('m',time()),
				'day' => date('d',time()),
			);
			$re = $db->add($data);
			if($re){
				$this->ajaxReturn($con,'提交成功',1);
			}else{
				$this->ajaxReturn('','提交失败',-1);
			}
		}
		//我的低价卷
		function myCoupons(){
			$account = $this->checkLogin('account');
			$db = M('Coupons');
			$coupons = $db->where(array('aid'=>$account['id']))->select();
			foreach ($coupons as $k => $v) {
				//已过期
				if((int)$v['endtime'] < time()){
					$db->where(array('id'=>$coupons[$k]['id']))->setField('status',2);
					$coupons[$k]['status'] = 2;
				}
				$coupons[$k]['endtime'] = date('Y-m-d',$v['endtime']);
			}
			$this->ajaxReturn($coupons,'',1);
		}
		//添加抵扣卷
		function addCoupons(){
			$account = $this->checkLogin('account');
			$code = $this->_post('code');
			$condition = array(
				'aid' => 0,
				'code' => $code,
			);
			$coupon = M('Coupons')->where($condition)->find();
			if($coupon){
				if($coupon['endtime'] < time()){
					$info = '该抵扣卷已过期';
					$coupon['status'] = 2;
					$status = -1;
					$data['status'] = 2;
				}else{
					$data = array(
						'aid' => $account['id'],
						'gettime' => time(),
						'status' => 3,
					);

					$info = '添加成功';
					$status = 1;
				}
				M('Coupons')->where($condition)->save($data);
				$this->ajaxReturn($coupon,$info,$status);
			}else{
				$this->ajaxReturn('','兑换码错误',-1);
			}
		}
		//购物判断可用抵价卷
		function shoppingCoupons(){
			$price = $this->_get('price');
			$account = $this->checkLogin('account');
			$db = M('Coupons');
			$condition = array(
				'aid' => $account['id'],
				'endtime' => array('gt',time()),
				'limitprice' => array('lt',$price),
				'status' => 3,
			);
			$list = $db->where($condition)->select();
			$coupons = array();
			foreach ($list as $k => $v) {
				$coupons[$v['id']] = $v;
			}
			$this->ajaxReturn($coupons,'',1);
		}
		//改约
		function changeOrderRtime(){
			$oid = $this->_post('id');
			$newrtime = $this->_post('newrtime');
			$cart = M('Product_cart')->where('id='.$oid)->find();
			//判断改约次数
			if($cart['rnums'] >=2){
				$this->ajaxReturn('','每人限改两次',-1);
			}
			if($cart){
				$data = array(
					'rtime' => $newrtime,
					'rnums' => $cart['rnums'] + 1,
				);
				$re = M('Product_cart')->where('id='.$oid)->save($data);
				if($re){
					$change_data = array(
						'oid' => $cart['id'],
						'oldtime' => $cart['rtime'],
						'newtime' => $newrtime,
						'addtime' => time(),
						'year' => date('Y',time()),
						'month' => date('m',time()),
						'day' => date('d',time()),
					);
					M('Cart_change_rtime')->add($change_data);
					$this->ajaxReturn('','改约成功',1);
				}else{
					$this->ajaxReturn('','改约失败',-1);
				}
			}else{
				$this->ajaxReturn('','订单不存在',-1);
			}
		}
		//获取我的订单下的图片
		function getMyCartPics(){
			$oid = $this->_post('id');
			$db = M('Cart_pics');
			$pics = $db->where('oid='.$oid)->select();
			if($pics){
				$this->ajaxReturn($pics,'',1);
			}else{
				$this->ajaxReturn('','没有相关照片',1);
			}
		}
		//取消订单
		function cancelOrder(){
			$oid = $this->_post('id');
			$cart = M('Product_cart')->where('id='.$oid)->find();
			//进行订单一般判断
			if($cart['paid'] == 0){
				$info = '订单未支付';
			}else{
				if($cart['returnMoney'] == 1){
					$info = '已取消预约';
				}else if($cart['handled'] == 1){
					$info = '预约已完成不能取消';
				}
			}
			if($info){
				$this->ajaxReturn('',$info,-1);
			}else{
				$data = array(
					'returnMoney' => 1,
					'returnTime' => time(),
				);
				$re = M('Product_cart')->where('id='.$oid)->save($data);
				if($re){
					$this->ajaxReturn('','取消成功',1);
				}else{
					$this->ajaxReturn('','取消失败',-1);
				}
=======
>>>>>>> 01e200e4f8a1295bfce0a15384da812e38d13ba2
			}
		}
	}
?>