<?php
class DistributionAction extends UserAction{
	public function set(){		
		//if($this->_get('token')!=session('token')){$this->error('非法操作');}
		$data=D('Distribution_set');
		$set=$data->where(array('token'=>session('token'),'uid'=>session('uid')))->find();
		$this->assign('set',$set);
		if(IS_POST){
			$_POST['token']=session('token');			
			if($data->create()){
				if($set==false){
					if($data->add()){
						$this->success('操作成功');					
					}else{
						$this->error('服务器繁忙，请稍候再试1');
					}
				}else{
					$_POST['id']=$set['id'];
					if($data->save($_POST)){
						$this->success('操作成功');					
					}else{
						$this->error('服务器繁忙，请稍候再试2');
					}
				}
			}else{
				$this->error($data->getError());
			}
		}else{
			$this->display();
		}
	}
	public function forwardSet(){		
		//if($this->_get('token')!=session('token')){$this->error('非法操作');}
		$data=D('Distribution_forward_set');
		$info=$data->where(array('token'=>session('token'),'uid'=>session('uid')))->find();
		$this->assign('info',$info);
		if(IS_POST){
			$_POST['token']=session('token');			
			if($data->create()){
				if($info==false){
					if($data->add()){
						$this->success('操作成功');					
					}else{
						$this->error('服务器繁忙，请稍候再试1');
					}
				}else{
					$_POST['id']=$info['id'];
					if($data->save($_POST)){
						$this->success('操作成功');					
					}else{
						$this->error('服务器繁忙，请稍候再试2');
					}
				}
			}else{
				$this->error($data->getError());
			}
		}else{
			$this->display();
		}
	}
	public function member(){
		$id = $this->_get('id');
		if($id){
			$token = session('token');
			$db = M('Distribution_member');
			$member = $db->where('id='.$id)->find();
			$from_user = $db->where('id='.$member['bindmid'])->find();
			$db = M('Distribution_ordermoney');
			$order['status_0'] = $db->where(array('token'=>$token,'mid'=>$id,'status'=>0))->sum('offerMoney');//未付款
			$order['status_1'] = $db->where(array('token'=>$token,'mid'=>$id,'status'=>1))->sum('offerMoney');//已付款
			$order['status_2'] = $db->where(array('token'=>$token,'mid'=>$id,'status'=>2))->sum('offerMoney');//未收货
			$order['status_3'] = $db->where(array('token'=>$token,'mid'=>$id,'status'=>3))->sum('offerMoney');//已收货
			$order['status_-1'] = $db->where(array('token'=>$token,'mid'=>$id,'status'=>-1))->sum('offerMoney');//已退款
			$order['status_4'] = $db->where(array('token'=>$token,'mid'=>$id,'status'=>4))->sum('offerMoney');//已审核
			$where['fid'] = $id;
			$where['sid'] = $id;
			$where['tid'] = $id;
			$where['_logic'] = 'or';
			$childmember = M('Distribution_member')->where($where)->select();
			$distriCount = M('Distribution_member')->where(array('token'=>$token,'bindmid'=>$id,'distritime'=>array('neq',0)))->count();
			$totalMoney = $db->where(array('token'=>$token,'mid'=>$id,'status'=>array('gt',0)))->sum('orderMoney');//累计销售
			$totalOfferMoney = $db->where(array('token'=>$token,'mid'=>$id,'status'=>array('gt',0)))->sum('offerMoney');//累计佣金
			$orderNums = $db->where(array('token'=>$token,'mid'=>$id,'status'=>array('gt',0)))->count();
			$totalScore = M('product_cart')->where(array('token'=>$token,'wecha_id'=>$member['wecha_id'],'paid'=>1,'returnMoney'=>0))->sum('price');
			$this->assign('totalScore',$totalScore);
			$this->assign('orderNums',$orderNums);
			$this->assign('totalMoney',$totalMoney);
			$this->assign('totalOfferMoney',$totalOfferMoney);
			$this->assign('distriCount',$distriCount);
            $this->assign('childmember',$childmember);
			$this->assign('order',$order);
			$this->assign('from_user',$from_user);
			$this->assign('member',$member);
			$this->display('memberDetail');
		}else{
			$db = D('Distribution_member');
			if($this->_post('name')!=''){
				$where['name|nickname'] = array('like','%'.$this->_post('name').'%');
			}
			$where['token'] = session('token');
			$count=$db->where($where)->count();
			$page=new Page($count,25);
			$member = $db->relation(true)->where($where)->limit($page->firstRow.','.$page->listRows)->order('id desc')->select();
			$this->assign('page',$page->show());
			$this->assign('member',$member);
			$this->display();
		}
	}
	public function delMember(){
		$db = M('Distribution_member');
		$id = $this->_get('id');
		if($id){
			$wecha_id = $db->where(array('id'=>$id,'token'=>session('token')))->getField('wecha_id');
			if($db->where(array('id'=>$id,'token'=>session('token')))->delete()){
				if($wecha_id){
					M('Membercode')->where(array('wecha_id'=>$wecha_id,'token'=>session('token')))->delete();
				}
				$this->success('删除成功');
			}else{
				$this->error('删除失败');
			}
		}else{
			$this->error('异常操作');
		}
	}
	public function memberDetail(){
		$db = M('Distribution_member');
		$id = $this->_get('id');
		$token = session('token');
		if($id){
			$member = $db->where('id='.$id)->find();
			$from_user = $db->where('id='.$member['bindmid'])->find();
			$db = M('Distribution_ordermoney');
			$order['status_0'] = $db->where(array('token'=>$token,'mid'=>$id,'status'=>0))->sum('offerMoney');//未付款
			$order['status_1'] = $db->where(array('token'=>$token,'mid'=>$id,'status'=>1))->sum('offerMoney');//已付款
			$order['status_2'] = $db->where(array('token'=>$token,'mid'=>$id,'status'=>2))->sum('offerMoney');//未收货
			$order['status_3'] = $db->where(array('token'=>$token,'mid'=>$id,'status'=>3))->sum('offerMoney');//已收货
			$order['status_-1'] = $db->where(array('token'=>$token,'mid'=>$id,'status'=>-1))->sum('offerMoney');//已退款
			$order['status_4'] = $db->where(array('token'=>$token,'mid'=>$id,'status'=>4))->sum('offerMoney');//已审核
			$where['fid'] = $id;
			$where['sid'] = $id;
			$where['tid'] = $id;
			$where['_logic'] = 'or';
			$count=M('Distribution_member')->where($where)->count();
			$page=new Page($count,25);
			$childmember = M('Distribution_member')->where($where)->limit($page->firstRow.','.$page->listRows)->order('id desc')->select();
			$this->assign('page',$page->show());
			$distriCount = M('Distribution_member')->where(array('token'=>$token,'bindmid'=>$id,'distritime'=>array('neq',0)))->count();
			$totalMoney = $db->where(array('token'=>$token,'mid'=>$id,'status'=>array('gt',0)))->sum('orderMoney');//累计销售
			$totalOfferMoney = $db->where(array('token'=>$token,'mid'=>$id,'status'=>array('gt',0)))->sum('offerMoney');//累计佣金
			$orderNums = $db->where(array('token'=>$token,'mid'=>$id,'status'=>array('gt',0)))->count();
			$totalScore = M('product_cart')->where(array('token'=>$token,'wecha_id'=>$member['wecha_id'],'handled'=>1))->sum('price');
			$this->assign('totalScore',$totalScore);
			$this->assign('orderNums',$orderNums);
			$this->assign('totalMoney',$totalMoney);
			$this->assign('totalOfferMoney',$totalOfferMoney);
			$this->assign('distriCount',$distriCount);
            $this->assign('childmember',$childmember);
			$this->assign('order',$order);
			$this->assign('from_user',$from_user);
			$this->assign('member',$member);
			$this->display();
		}else{
			$this->error('异常访问');
		}
	}
	//账号列表
	public function account(){
		$db = D('Account');
		$aid = $this->_get('id');
		$type = $this->_get('type');
		//详情页
		if($aid && $type == 'detail'){
			$account = $db->where(array('id'=>$aid))->relation(true)->find();
			//创建者
			if($account['createtype'] != 1){
				$account['creater'] = M('Distribution_member')->where(array('wecha_id'=>$account['wecha_id']))->getField('nickname');
			}else{
				$account['creater'] = $account['wecha_id'];
			}
			//登陆者
			$account['loginer'] ='';
			if($account['mid'] != 0){
				$account['loginer'] = $account['member']['nickname'];
			}
			if($account['ip']){
				$account['loginer'] = $account['ip'];
			}
			//上级账号
			if($account['bindaid'] != 0){
				$account['bindac'] = M('Distribution_account')->where(array('id'=>$account['bindaid']))->getField('username');
			}else{
				$account['bindac'] = '';
			}
			
			$data = array(
				'totalearn' => $this->statistical('totalearn',$account['id']),//总收入
				'applymoney' => $this->statistical('applymoney',$account['id']),		//已提现
				'gold' => $this->statistical('gold',$account['id']),		//总金币
				'ordernums' => $this->statistical('ordernums',$account['id']),		//处理订单总数
				'shoporders' => $this->statistical('shoporders',$account['id']),		//购买订单总数
			);
			$account['detail'] = $data;
			//子账号
			$account['childs'] = $db->where(array('createid'=>$account['id'],'delete'=>0))->relation(true)->select();
			$this->assign('info',$account);
			$this->display('accountDetail');
			exit();
		}
		//设置等级页面
		if($aid && $type == 'upgrade'){
			$account = $db->where(array('id'=>$aid))->relation(true)->find();
			// if($account['lid']){
			// 	$condition['lid'] = array('lt',$account['lid']);
			// }
			// if($account['lid'] !=0){
			// 	$upgrade_condition = array(
			// 		'lid' => array(array('lt',$account['lid']),array('gt',1),'and'),
			// 	);
			// }else{
			// 	$upgrade_condition['lid'] = array('gt',1);
			// }
			$upgrade_condition['lid'] = array('neq',1);
			$levels = M('Distribution_level')->where($upgrade_condition)->select();
			$this->assign('info',$account);
			$this->assign('list',$levels);
			$this->display('setLevel');
		}
		//充值金币页面
		if($aid && $type == 'topup'){
			$account = $db->where(array('id'=>$aid))->relation(true)->find();
			$account['gold'] = $this->statistical('gold',$account['id']);
			$this->assign('info',$account);
			$this->display('topup');
		}
		//充值现金页面
		if($aid && $type == 'topupmoney'){
			$account = $db->where(array('id'=>$aid))->relation(true)->find();
			$account['balance'] = $this->statistical('balance',$account['id']);
			$this->assign('info',$account);
			$this->display('topupmoney');
		}
		//账号列表
		if(!$aid && !$type){
			$where['delete'] = 0;

			if($this->_post('name')!=''){
				$where['nickname|username'] = array('like','%'.$this->_post('name').'%');
			}
			//列表页
			$count=$db->where($where)->count();
			$page=new Page($count,25);

			$list = $db->where($where)->relation(true)->limit($page->firstRow.','.$page->listRows)->select();
			foreach ($list as $k => $v) {
				$list[$k]['creater'] = M('Distribution_member')->where(array('wecha_id'=>$v['wecha_id']))->getField('nickname');
				$list[$k]['bindac'] = M('Distribution_account')->field('id,nickname')->where(array('id'=>$v['bindaid']))->find();
			}
			$this->assign('page',$page->show());
			$this->assign('list',$list);
			$this->display();
		}
	}
	//删除账号
	public function deleteAccount(){
		$id = $this->_get('id');
		//$re = D('Account')->where('id='.$id)->setField('delete',1);
		$re = D('Account')->where('id='.$id)->delete();
		if($re){
			$this->success('删除成功',U('Distribution/account'));
		}else{
			$this->error('删除成功',U('Distribution/account'));
		}
	}
	public function bank(){
		$db = M('Distribution_bank');
		if($this->_post('name')!=''){
			$where['name|bankName'] = array('like','%'.$this->_post('name').'%');
		}
		$where['token'] = session('token');
		$count=$db->where($where)->count();
		$page=new Page($count,25);
		$list = $db->where($where)->limit($page->firstRow.','.$page->listRows)->order('id desc')->select();
		foreach($list as $key=>$value){
			$member = M('Distribution_member')->where(array('wecha_id'=>$value['wecha_id'],'token'=>$value['token']))->find();
			$list[$key]['nickname'] = $member['nickname'];
			$list[$key]['headimgurl'] = $member['headimgurl'];
			//所属区域
			if($value['classid']){
				import ( "@.Org.TypeFile" );
				$tid = $value['classid'];
				$TypeFile = new TypeFile ( 'ClassCity' ); //实例化分类类
				$result = $TypeFile->getPathName ( $tid ); //获取分类路径
				$list[$key]['typeNumArr']= $result ;
			}
		}
		$this->assign('page',$page->show());
		$this->assign('list',$list);
		$this->display();
	}
	public function moneylist(){
		$db = M('Distribution_applystore');
		if($this->_post('name')!=''){
			$where['name|bankName'] = array('like','%'.$this->_post('name').'%');
		}
		$where['token'] = session('token');
		$count=$db->where($where)->count();
		$page=new Page($count,25);
		$list = $db->where($where)->limit($page->firstRow.','.$page->listRows)->order('id desc')->select();
		foreach($list as $key=>$value){
			$member = M('Distribution_member')->where(array('id'=>$value['mid']))->find();
			$list[$key]['memberid'] = $member['id'];
			$list[$key]['nickname'] = $member['nickname'];
			$list[$key]['headimgurl'] = $member['headimgurl'];
			//所属区域
			if($value['classid']){
				import ( "@.Org.TypeFile" );
				$tid = $value['classid'];
				$TypeFile = new TypeFile ( 'ClassCity' ); //实例化分类类
				$result = $TypeFile->getPathName ( $tid ); //获取分类路径
				$list[$key]['typeNumArr']= $result ;
			}
		}
		$this->assign('page',$page->show());
		$this->assign('list',$list);
		$this->display();
	}
	public function changeStatus(){
		$db = M('Distribution_applystore');
		$id = $this->_get('id');
		$status = $this->_get('status');
		if($status!=1&&$status!=2){
			$this->error('异常操作');
		}
		$data['status'] = $status;
		if($db->where('id='.$id)->save($data)){
			$this->success('处理成功');
		}else{
			$this->error('处理失败');
		}
	}
	public function sendMoney(){
		$id = $this->_get('id');
		if(!$id){
			$this->error('非法操作！');
			exit();
		}
		$result = $this->TXhongbao($id);
		if($result['return_code']=='SUCCESS'){
			M('Distribution_applystore')->where(array('token'=>session('token'),'id'=>$id,'status'=>0))->setField('status',1);
			$this->success('发放成功！');
		}else{
			$this->error($result['return_msg']);
		}
	}
	public function address(){
		$db = D('Address');
		if($this->_post('name')!=''){
			$maps['username'] = array('like','%'.$this->_post('name').'%');
			$accounts = D('Account')->where($maps)->select();
			foreach ($accounts as $k => $v) {
				$astr .= $v['id'].",";
			}
			$astr = rtrim($astr,',');
			$where['aid'] = array('in',$astr);
		}

		$where['choose'] = 1 ;
		$count=$db->where($where)->count();
		$page=new Page($count,25);
		$list = $db->where($where)->limit($page->firstRow.','.$page->listRows)->order('id desc')->relation(true)->select();

		$this->assign('page',$page->show());
		$this->assign('list',$list);
		$this->display();
	}
	public function collection(){
		$db = M('Product_collection');
		$where['token'] = session('token');
		$count=$db->where($where)->count();
		$page=new Page($count,25);
		$list = $db->where($where)->limit($page->firstRow.','.$page->listRows)->order('id desc')->select();
		$memberModel = M('Distribution_member');
		$productModel = M('Product');
		foreach($list as $key=>$value){
			$member = $memberModel->where(array('token'=>$value['token'],'wecha_id'=>$value['wecha_id']))->field('nickname,headimgurl')->find();
			$list[$key]['nickname'] = $member['nickname'];
			$list[$key]['headimgurl'] = $member['headimgurl'];
			$product = $productModel->where('id='.$value['productid'])->field('name')->find();
			$list[$key]['productname'] = $product['name'];
		}
		$this->assign('page',$page->show());
		$this->assign('list',$list);
		$this->display();
	}
	public function beDistribution(){
		$db = M('Distribution_member');
		$id = $this->_get('id');
		if($id){
			$data['distritime'] = time();
			if($db->where(array('id'=>$id,'token'=>session('token')))->save($data)){
				$this->success('设为分销成功');
			}else{
				$this->error('设为分销失败');
			}
		}else{
			$this->error('异常操作');
		}
	}
	private function TXhongbao($id)
	{
		//读取微信支付配置
		$payConfig = M('Alipay_config')->where(array('token'=>session('token')))->find();
		//读取商家信息
		$company = M('company')->where(array('token'=>session('token')))->find();
		//读取提现申请
		$apply = M('Distribution_applystore')->where(array('token'=>session('token'),'id'=>$id,'status'=>0))->find();
		if(!$apply){
			$this->error('异常操作！');
			exit();
		}
		$url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack';
		$key = $payConfig['paysignkey'];//API密钥
		$mch_id = $payConfig['mchid'];//商户号
		$sub_mch_id = '';//子商户号
		$wxappid = $payConfig['appid'];//appid
		$nick_name = '佣金提现';//提供方名称
		$send_name = '佣金提现';//商户名称
		$re_openid = $apply['wecha_id'];//用户openid
		$total_amount = $apply['money'];//付款金额
		$min_value = $apply['money'];//最小红包金额
		$max_value = $apply['money'];//最大红包金额
		$total_num = 1;//红包发放总人数
		$wishing = '恭喜您，提现成功啦';//红包祝福语
		$client_ip = $payConfig['ip'];//Ip地址
		$act_name = '佣金提现';//活动名称
		$remark = '备注';//备注
		$logo_imgurl = $company['logourl'];//商户logo的url

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
		return $this->xmlToArray($result);
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


?>