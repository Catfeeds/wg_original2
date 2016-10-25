<?php
	class DistributionAction extends WapAction{
		public function __construct(){
			parent::_initialize();
		}
		function test2(){
			dump(session('jump_href'));
		}
		function test(){
			session('bmywecha_id',null);
			session('bmywecha_id','');
			dump(session('bmywecha_id'));
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
				$this->ajaxReturn($re,'注册成功',1);
			}else{
				$this->ajaxReturn($re,'注册失败',-1);
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
					$headimgurl = M('Distribution_member')->where(array('wecha_id'=>session('bmywecha_id')))->getField('headimgurl');
					D('Account')->where($condition)->setField('headimgurl',$headimgurl);
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
				$this->ajaxReturn('','修改失败',2);
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
			$orders = $db->where(array('aid'=>$account['id']))->select();
			foreach ($orders as $k => $v) {
				$orders[$k]['rtime'] = date('Y-m-d H:i',$v['rtime']);
			}
			$this->ajaxReturn($orders,'',1);
		}
		//判断登陆
		function checkLogin($get = ''){
			$username = $_COOKIE['zxg_login_user'];
			$account = D('Account')->where(array('username'=>$username))->find();

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
			}
		}
	}
?>