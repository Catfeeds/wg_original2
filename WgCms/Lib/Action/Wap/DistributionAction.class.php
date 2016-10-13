<?php
	class DistributionAction extends WapAction{
		public function __construct(){
			parent::_initialize();
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
		function register(){
			$data = htmlspecialchars_decode(htmlspecialchars_decode($this->_post('data')));
			// $data = htmlspecialchars_decode(htmlspecialchars_decode($this->_get('data')));
			$data = $this->myUnserialize($data);
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
								$this->ajaxReturn(session('verify'),'图片验证码错误',2);
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
					$this->ajaxReturn($k,$warn,2);
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
				$this->ajaxReturn($re,'注册失败',2);
			}
		}
	}
?>