<?php
class WapAction extends BaseAction
{
    public $token;
    public $wecha_id;
    public $fans;
    public $homeInfo;
    public $bottomeMenus;
    public $wxuser;
    public $user;
    public $group;
    public $company;
    public $shareScript;
    public $wx_user_info;
	public $access_str;
	public $ticket_str;
    protected function _initialize()
    {
        parent::_initialize();
        $this->token = 'mhfcjx1421158741';
        $this->assign('token', $this->token);
        $this->wxuser = S('wxuser_' . $this->token);
        $agent = $_SERVER['HTTP_USER_AGENT']; 
        if (!$this->wxuser || 1) {
            $this->wxuser = D('Wxuser')->where(array('token' => $this->token))->find();
            S('wxuser_' . $this->token, $this->wxuser);
        }
		// if($this->_get('wecha_id')){
		// 	session('qchwecha_id',$this->_get('wecha_id'));
		// }
		// session('qchwecha_id',null);
		// if($this->_get('test') == 1){
		// 	session('qchwecha_id',null);
		// 	setcookie('login_user',null);
		// }
		session('qchwecha_id','oR4d_wo0u08EYLH-imb95OZReBdw');
        $this->assign('wxuser', $this->wxuser);
        // strpos($agent,"icroMessenger") && 
        if (!session('qchwecha_id') && $this->wxuser['winxintype'] == 3 && !isset($_GET['code']) && $this->wxuser['oauth']) {
            $customeUrl = 'http://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            $scope = 'snsapi_base';
            $oauth = 'base_oauth';
            $oauthUrl = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $this->wxuser['appid'] . '&redirect_uri=' . urlencode($customeUrl) . '&response_type=code&scope=' . $scope . '&state=' .$oauth. '#wechat_redirect';
            header('Location:' . $oauthUrl);
			exit();
        }
        if (isset($_GET['code']) && isset($_GET['state']) && (isset($_GET['state']) == 'user_oauth'||isset($_GET['state']) == 'base_oauth')) {
            $rt = $this->curlGet('https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $this->wxuser['appid'] . '&secret=' . $this->wxuser['appsecret'] . '&code=' . $_GET['code'] . '&grant_type=authorization_code');
            $jsonrt = json_decode($rt, 1);
            $openid = $jsonrt['openid'];
            $access_token=$jsonrt['access_token'];
            $this->wecha_id = $openid;
			session('qchwecha_id',$openid);


            $my = D('Distribution_member')->where(array('token'=>$this->token,'wecha_id'=>$openid))->find();
            if(!$my&&$_GET['state']!='user_oauth'){
            	$customeUrl = 'http://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
                $scope = 'snsapi_userinfo';
                $oauth = 'user_oauth';
                $oauthUrl = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $this->wxuser['appid'] . '&redirect_uri=' . urlencode($customeUrl) . '&response_type=code&scope=' . $scope . '&state=' .$oauth. '#wechat_redirect';
                header('Location:' . $oauthUrl);
                exit();
            }

            if($_GET['state']=='user_oauth'){
                $wx_user_info = $this->curlGet('https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN');              
                $json_user_info = json_decode($wx_user_info, 1);
                if($json_user_info['openid']!=""){
					if(!$my){
						//插入数据
						$data=array(
							'nickname'=>$json_user_info['nickname'],
							'sex'=>$json_user_info['sex'],
							'token'=>$this->token,
							'province'=>$json_user_info['province'],
							'city'=>$json_user_info['city'],
							'country'=>$json_user_info['country'],
							'headimgurl'=>$json_user_info['headimgurl'],
							'wecha_id'=>$json_user_info['openid'],
							'status'=>1,
							'createtime'=>time(),
						);
						$db = M('Distribution_member');
						$mid = $_GET['mid'];
						if($mid){
						    $data['bindmid'] = $mid;
						    //绑定账号
						    $account = M('Distribution_account')->where(array('mid'=>$mid))->find();
						    if($account){
						        $data['bindaid'] = $account['id'];
						    }
						}
						$myid=$db->add($data);

						if($mid){
							$from_member = $db->where('id='.$mid)->find();
							if($mid&&$from_member){
								$db->where('id='.$from_member['id'])->setInc('followNums');//关注累加
								
								$leveData['handle'] = 1;//处理结束
								$db->where('id='.$myid)->save($leveData);//会员所属绑定
								//上级消息推送
								$access_token_p = $this->get_access_token();
								$data_p = '{"touser":"'.$from_member['wecha_id'].'","msgtype":"news","news":{"articles":[{"title":"您有新朋友加入，赶紧看看吧","description":"亲：新朋友的消费您都将有提成哦","url":"'.C('site_url').U('Wap/Distribution/followList',array('token'=>$this->token,'wecha_id'=>$from_member['wecha_id'])).'","picurl":""}]}}';
								$result_p = $this->api_notice_increment('https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$access_token_p,$data_p);
							}
						}
					}else{
						$mid = $_GET['mid'];
						if($mid){
							if($my['bindmid'] == 0){
								$data2['bindmid'] = $mid;
							}
							// if($my['bindaid'] == 0){
								$account = M('Distribution_account')->where(array('mid'=>$mid))->find();
								if($account){
								    $data2['bindaid'] = $account['id'];
								}
							// }
							if($data2){
								D('Distribution_member')->where(array('token'=>$this->token,'wecha_id'=>$openid))->save($data2);
							}
						}
					}
                }
			}
        } else {
        	if(session('qchwecha_id')){
           	 	$this->wecha_id = session('qchwecha_id');
        	}else{
        		$account = D('Account')->where(array('username'=>$_COOKIE['login_user']))->find();
        		if($account['mid']){
        			$this->wecha_id = M('Distribution_member')->where(array('id'=>$account['mid']))->getField('wecha_id');
        		}
        	}
        }
        //绑定关系
        // if(session('qchwecha_id')){
        // 	$mid = $_GET['mid'];
        // 	if($my['bindmid'] == 0 && $mid){
        // 		$data3['bindmid'] = $mid;
        // 	}
        // 	// if($my['bindaid'] == 0){
        // 		$aid = $_GET['aid'];
        // 		$account = M('Distribution_account')->where(array('id'=>$aid))->find();
        // 		if($account){
        // 		    $data3['bindaid'] = $account['id'];
        // 		}
        // 	// }
        // 	if($data3){
        // 		D('Distribution_member')->where(array('token'=>$this->token,'wecha_id'=>session('qchwecha_id')))->save($data3);
        // 	}
        // }

        $this->assign('wecha_id', $this->wecha_id);
        $fansInfo = S('fans_' . $this->token . '_' . $this->wecha_id);
        if (!$fansInfo || 1) {
            $fansInfo = M('Distribution_member')->where(array('token' => $this->token, 'wecha_id' => $this->wecha_id))->find();
            S('fans_' . $this->token . '_' . $this->wecha_id, $fansInfo);
        }
        $this->fans = $fansInfo;
        $this->assign('fans', $fansInfo);
        if($this->token!=''&&preg_match('/^[0-9a-zA-Z]{3,42}$/', $this->token)){
			$timestamp = time();
			$wxnonceStr = rand(10000000,99999999);
			$wxticket = $this->jsapi_ticket();
			$forwardurl = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
			$wxOri = sprintf("jsapi_ticket=%s&noncestr=%s&timestamp=%s&url=%s",$wxticket, $wxnonceStr, $timestamp, $forwardurl);
			$wxSha1 = sha1($wxOri);
			$this->shareScript = '<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
			<script type="text/javascript">
			 wx.config({
				debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
				appId: "'.$this->wxuser['appid'].'", // 必填，公众号的唯一标识
				timestamp: "'.$timestamp.'", // 必填，生成签名的时间戳
				nonceStr: "'.$wxnonceStr.'", // 必填，生成签名的随机串
				signature: "'.$wxSha1.'",// 必填，签名，见附录1
				jsApiList: [\'onMenuShareTimeline\',\'onMenuShareAppMessage\',\'onMenuShareQQ\',\'onMenuShareWeibo\'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
			});
			wx.ready(function(){
				//获取“分享到朋友圈”按钮点击状态及自定义分享内容接口
				 wx.onMenuShareTimeline({
					title: window.shareData.tTitle, // 分享标题
					link: window.shareData.sendFriendLink, // 分享链接
					imgUrl: window.shareData.imgUrl, // 分享图标
					success: function () {
						// 用户确认分享后执行的回调函数
						//shareHandle(\'friends\');
					},
					cancel: function () { 
						// 用户取消分享后执行的回调函数
					}
				});
				 
				//获取“分享给朋友”按钮点击状态及自定义分享内容接口
				 wx.onMenuShareAppMessage({
					title: window.shareData.tTitle, // 分享标题
					desc: window.shareData.tContent, // 分享描述
					link: window.shareData.sendFriendLink, // 分享链接
					imgUrl: window.shareData.imgUrl, // 分享图标
					success: function () { 
						// 用户确认分享后执行的回调函数
						//shareHandle(\'friend\');
					},
					cancel: function () { 
						// 用户取消分享后执行的回调函数
					}
				});
				 
				//获取“分享到QQ”按钮点击状态及自定义分享内容接口
				 wx.onMenuShareQQ({
					title: window.shareData.tTitle, // 分享标题
					desc: window.shareData.tContent, // 分享描述
					link: window.shareData.sendFriendLink, // 分享链接
					imgUrl: window.shareData.imgUrl, // 分享图标
					success: function () { 
					   // 用户确认分享后执行的回调函数
					   //shareHandle(\'qq\');
					},
					cancel: function () { 
					   // 用户取消分享后执行的回调函数
					}
				});
				 
				//获取“分享到腾讯微博”按钮点击状态及自定义分享内容接口
				 wx.onMenuShareWeibo({
					title: window.shareData.tTitle, // 分享标题
					desc: window.shareData.tContent, // 分享描述
					link: window.shareData.sendFriendLink, // 分享链接
					imgUrl: window.shareData.imgUrl, // 分享图标
					success: function () {
						//shareHandle(\'weibo\');
					   // 用户确认分享后执行的回调函数
					},
					cancel: function () { 
						// 用户取消分享后执行的回调函数
					}
				});
              });
				 wx.error(function (res) {
                   //alert(res.errMsg);
              });
						
						
			/*function shareHandle(to) {
			var submitData = {
				module: window.shareData.moduleName,
				moduleid: window.shareData.moduleID,
				lid: window.shareData.lid,
				mid: window.shareData.mid,
				token:\'' . $this->token . '\',
				wecha_id:\'' . $this->wecha_id . '\',
				url: window.shareData.sendFriendLink,
				to:to
			};
			$.post(\'' . U('Share/shareData', array('token' => $this->token, 'wecha_id' => $this->wecha_id)) . '\',submitData,function (data) {},\'json\')}*/
			</script>';

			$this->assign('shareScript', $this->shareScript);
		}
    }
    //发送信息拼接订单样式
    //type
    //1:下单
    //2：退款
    public function sentMessageFormat($order_id,$type = 1){
    	$order = M('Product_cart')->where(array('id'=>$order_id))->find();
    	$upaccount = D('Account')->where(array('id'=>$order['bindaid']))->find();
    	$downaccount = D('Account')->where(array('id'=>$order['aid']))->find();
    	$ordermoney = $order['gold'] == 0 ? $order['price'].'元' : $order['gold'].'金币';
    	$getmoney = $order['gold'] == 0 ? $order['price'].'元' : $order['gold'].'元';
    	switch ($type) {
    		case '1':
    			$str = '亲。您的下级账号'.$downaccount['nickname'].'下单了，订单详情:\n订单号：'.$order['orderid'].'\n订单金额：'.$ordermoney.'\n获得金额：'.$getmoney.'\n时间：'.date('Y/m/d H:i:s',time()).'\n柒彩汇微信客服：j6464903';
    			break;
    		
    		case '2':
    			$str = '亲,'.$upaccount['nickname'].'。您的下级账号'.$downaccount['nickname'].'申请退款，订单详情:\n订单号：'.$order['orderid'].'\n订单金额：'.$ordermoney.'\n损失金额：'.$getmoney.'\n时间：'.date('Y/m/d H:i:s',time()).'\n柒彩汇微信客服：j6464903';
    			break;
    	}
    	return $str;
    }
    //向上级发送信息
	public function sendupMessage($aid,$title,$content,$url){
		$bindm = D('Account')->where(array('id'=>$aid))->relation(true)->find();
		if($bindm){
			$this->sendMessage($bindm['member']['wecha_id'],$title,$content,$url);
		}
	}
    //发送信息
    public function sendMessage($touser,$title,$content,$url){
    	$access_token_p = $this->get_access_token();
    	$data_p = '{"touser":"'.$touser.'","msgtype":"news","news":{"articles":[{"title":"'.$title.'","description":"'.$content.'","url":"'.C('site_url').$url.'","picurl":""}]}}';
    	// $data_p = '{"touser":"'.$touser.'","msgtype":"text","text":{"content":"'.$content.'"}}';
    	Log::write("ccsql=".$data_p,'DEBUG');
    	$this->api_notice_increment('https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$access_token_p,$data_p);
    }
	private function jsapi_ticket(){
		if($this->token!=''&&preg_match('/^[0-9a-zA-Z]{3,42}$/', $this->token)){
			$jsapi_ticket = M('jsapi_ticket')->where(array('token'=>$this->token))->find();
			if($jsapi_ticket){
				if(($jsapi_ticket['updatetime']+1800)<time()){
					$access_str = $this->get_access_token();
					$ticket = $this->curlGet('https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token='.$access_str.'&type=jsapi');
					$ticketinfo = json_decode($ticket, 1);
					$ticket_str = $ticketinfo['ticket'];
					$data['jsapi_ticket'] = $ticket_str;
					$data['updatetime'] = time();
					M('jsapi_ticket')->where(array('token'=>$this->token))->save($data);
				}else{
					$ticket_str = $jsapi_ticket['jsapi_ticket'];
				}
			}else{
				$access_str = $this->get_access_token();
				$ticket = $this->curlGet('https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token='.$access_str.'&type=jsapi');
				$ticketinfo = json_decode($ticket, 1);
				$ticket_str = $ticketinfo['ticket'];
				$data['token'] = $this->token;
				$data['jsapi_ticket'] = $ticket_str;
				$data['updatetime'] = time();
				M('jsapi_ticket')->add($data);
			}
			if($access_str){
				$this->access_str = $access_str;
			}else{
				$this->access_str = $this->get_access_token();
			}
			$this->ticket_str = $ticket_str;
			return $ticket_str;
		}
	}
	protected function get_access_token(){
		if($this->token!=''&&preg_match('/^[0-9a-zA-Z]{3,42}$/', $this->token)){
			$access_token = M('access_token')->where(array('token'=>$this->token))->find();
			if($access_token){
				if(($access_token['updatetime']+1800)<time()){
					$access_str = $this->get_new_access_token();
					$data['access_token'] = $access_str;
					$data['updatetime'] = time();
					M('access_token')->where(array('token'=>$this->token))->save($data);
				}else{
					$access_str = $access_token['access_token'];
				}
			}else{
				$access_str = $this->get_new_access_token();
				$data['token'] = $this->token;
				$data['access_token'] = $access_str;
				$data['updatetime'] = time();
				M('access_token')->add($data);
			}
			return $access_str;
		}
	}
	protected function get_new_access_token(){
		$rt = $this->curlGet('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . $this->wxuser['appid'] . '&secret=' . $this->wxuser['appsecret']);
        $jsonrt = json_decode($rt, 1);
		return $jsonrt['access_token'];
	}
    public function getLink($url)
    {
        $url = $url ? $url : 'javascript:void(0)';
        $urlArr = explode(' ', $url);
        $urlInfoCount = count($urlArr);
        if ($urlInfoCount > 1) {
            $itemid = intval($urlArr[1]);
        }
        if ($this->strExists($url, '刮刮卡')) {
            $link = '/index.php?g=Wap&m=Guajiang&a=index&token=' . $this->token . '&wecha_id=' . $this->wecha_id;
            if ($itemid) {
                $link .= '&id=' . $itemid;
            }
        } elseif ($this->strExists($url, '大转盘')) {
            $link = '/index.php?g=Wap&m=Lottery&a=index&token=' . $this->token . '&wecha_id=' . $this->wecha_id;
            if ($itemid) {
                $link .= '&id=' . $itemid;
            }
        } elseif ($this->strExists($url, '优惠券')) {
            $link = '/index.php?g=Wap&m=Coupon&a=index&token=' . $this->token . '&wecha_id=' . $this->wecha_id;
            if ($itemid) {
                $link .= '&id=' . $itemid;
            }
        } elseif ($this->strExists($url, '刮刮卡')) {
            $link = '/index.php?g=Wap&m=Guajiang&a=index&token=' . $this->token . '&wecha_id=' . $this->wecha_id;
            if ($itemid) {
                $link .= '&id=' . $itemid;
            }
        } elseif ($this->strExists($url, '商家订单')) {
            if ($itemid) {
                $link = $link = '/index.php?g=Wap&m=Host&a=index&token=' . $this->token . '&wecha_id=' . $this->wecha_id . '&hid=' . $itemid;
            } else {
                $link = '/index.php?g=Wap&m=Host&a=Detail&token=' . $this->token . '&wecha_id=' . $this->wecha_id;
            }
        } elseif ($this->strExists($url, '万能表单')) {
            if ($itemid) {
                $link = $link = '/index.php?g=Wap&m=Selfform&a=index&token=' . $this->token . '&wecha_id=' . $this->wecha_id . '&id=' . $itemid;
            }
        } elseif ($this->strExists($url, '相册')) {
            $link = '/index.php?g=Wap&m=Photo&a=index&token=' . $this->token . '&wecha_id=' . $this->wecha_id;
            if ($itemid) {
                $link = '/index.php?g=Wap&m=Photo&a=plist&token=' . $this->token . '&wecha_id=' . $this->wecha_id . '&id=' . $itemid;
            }
        } elseif ($this->strExists($url, '全景')) {
            $link = '/index.php?g=Wap&m=Panorama&a=index&token=' . $this->token . '&wecha_id=' . $this->wecha_id;
            if ($itemid) {
                $link = '/index.php?g=Wap&m=Panorama&a=item&token=' . $this->token . '&wecha_id=' . $this->wecha_id . '&id=' . $itemid;
            }
        } elseif ($this->strExists($url, '会员卡')) {
            $link = '/index.php?g=Wap&m=Card&a=index&token=' . $this->token . '&wecha_id=' . $this->wecha_id;
        } elseif ($this->strExists($url, '商城')) {
            $link = '/index.php?g=Wap&m=Product&a=index&token=' . $this->token . '&wecha_id=' . $this->wecha_id;
        } elseif ($this->strExists($url, '订餐')) {
            $link = '/index.php?g=Wap&m=Product&a=dining&dining=1&token=' . $this->token . '&wecha_id=' . $this->wecha_id;
        } elseif ($this->strExists($url, '团购')) {
            $link = '/index.php?g=Wap&m=Groupon&a=grouponIndex&token=' . $this->token . '&wecha_id=' . $this->wecha_id;
        } elseif ($this->strExists($url, '首页')) {
            $link = '/index.php?g=Wap&m=Index&a=index&token=' . $this->token . '&wecha_id=' . $this->wecha_id;
        } elseif ($this->strExists($url, '网站分类')) {
            $link = '/index.php?g=Wap&m=Index&a=lists&token=' . $this->token . '&wecha_id=' . $this->wecha_id;
            if ($itemid) {
                $link = '/index.php?g=Wap&m=Index&a=lists&token=' . $this->token . '&wecha_id=' . $this->wecha_id . '&classid=' . $itemid;
            }
        } elseif ($this->strExists($url, '图文回复')) {
            if ($itemid) {
                $link = '/index.php?g=Wap&m=Index&a=index&token=' . $this->token . '&wecha_id=' . $this->wecha_id . '&id=' . $itemid;
            }
        } elseif ($this->strExists($url, 'LBS信息')) {
            $link = '/index.php?g=Wap&m=Company&a=map&token=' . $this->token . '&wecha_id=' . $this->wecha_id;
            if ($itemid) {
                $link = '/index.php?g=Wap&m=Company&a=map&token=' . $this->token . '&wecha_id=' . $this->wecha_id . '&companyid=' . $itemid;
            }
        } elseif ($this->strExists($url, 'DIY宣传页')) {
            $link = '/index.php/show/' . $this->token;
        } elseif ($this->strExists($url, '婚庆喜帖')) {
            if ($itemid) {
                $link = '/index.php?g=Wap&m=Wedding&a=index&token=' . $this->token . '&wecha_id=' . $this->wecha_id . '&id=' . $itemid;
            }
        } elseif ($this->strExists($url, '投票')) {
            if ($itemid) {
                $link = '/index.php?g=Wap&m=Vote&a=index&token=' . $this->token . '&wecha_id=' . $this->wecha_id . '&id=' . $itemid;
            }
        } else {
            $link = str_replace(array('{wechat_id}', '{siteUrl}', '&amp;'), array($this->wecha_id, $this->siteUrl, '&'), $url);
            if (!!(strpos($url, 'tel') === false) && $url != 'javascript:void(0)' && !strpos($url, 'wecha_id=')) {
                if (strpos($url, '?')) {
                    $link = $link . '&wecha_id=' . $this->wecha_id;
                } else {
                    $link = $link . '?wecha_id=' . $this->wecha_id;
                }
            }
        }
        return $link;
    }
    public function strExists($haystack, $needle)
    {
        return !(strpos($haystack, $needle) === FALSE);
    }
	public function getIPLoc_sina($queryIP){    
		$url = 'http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip='.$queryIP;    
		$ch = curl_init($url);     
		curl_setopt($ch,CURLOPT_ENCODING ,'utf8');     
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);   
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; // 获取数据返回  
		$location = curl_exec($ch);    
		$location = json_decode($location);    
		curl_close($ch);         
		$loc = "";   
		if($location===FALSE) return "";     
		if (empty($location->desc)) {    
			$loc = $location->province.$location->city.$location->district.$location->isp;  
		}else{         
			$loc = $location->desc;    
		}    
		return $loc;
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
    public function curlGet($url)
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