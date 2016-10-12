<?php
class WeixinAction extends Action
{
    private $token;
    private $fun;
    private $data = array();
    public $fans;
    private $my = '小小';
    public $wxuser;
    public $apiServer;
    public $siteUrl;
    public $user;
    public function index()
    {
        $this->siteUrl = C('site_url');
        if (!class_exists('SimpleXMLElement')) {
            die('SimpleXMLElement class not exist');
        }
        if (!function_exists('dom_import_simplexml')) {
            die('dom_import_simplexml function not exist');
        }
        $this->token = $this->_get('token', 'htmlspecialchars');
        if (!preg_match('/^[0-9a-zA-Z]{3,42}$/', $this->token)) {
            die('error token');
        }
        $this->wxuser = S('wxuser_' . $this->token);
        if (!$this->wxuser || 1) {
            $this->wxuser = D('Wxuser')->where(array('token' => $this->token))->find();
            if (C('agent_version') && intval($this->wxuser['agentid'])) {
                $thisAgent = M('Agent')->where(array('id' => $this->wxuser['agentid']))->find();
                $this->siteUrl = $thisAgent['siteurl'];
            }
            S('wxuser_' . $this->token, $this->wxuser);
        }
        $this->user = M('Users')->where(array('id' => $this->wxuser['uid']))->find();
        //$weixin = new Wechat($this->token,$this->wxuser);
        $weixin = new Wechat($this->token);
        $data = $weixin->request();
        $this->data = $weixin->request();
        $this->fans = S('fans_' . $this->token . '_' . $this->data['FromUserName']);
        if (!$this->fans || 1) {
            $this->fans = M('Userinfo')->where(array('token' => $this->token, 'wecha_id' => $this->data['FromUserName']))->find();
            S('fans_' . $this->token . '_' . $this->data['FromUserName'], $this->fans);
        }
        $this->my = C('site_my');
        $open = M('Token_open')->where(array('token' => $this->_get('token')))->find();
        $this->fun = $open['queryname'];
        list($content, $type) = $this->reply($data);
        $weixin->response($content, $type);
    }
    private function reply($data)
    {
		$this->newstoreRecord($data);//记录最新操作
        if ($this->user['viptime'] < time()) {
            return array('您的账号已经过期，请联系' . $this->siteUrl . '开通', 'text');
        }
        if ('CLICK' == $data['Event']) {
            $data['Content'] = $data['EventKey'];
            $this->data['Content'] = $data['EventKey'];
        } elseif ($data['Event'] == 'SCAN') {
            $this->data['Content'] = $data['Content'];
        } elseif ($data['Event'] == 'MASSSENDJOBFINISH') {
        } elseif ('subscribe' == $data['Event']) {
			//$this->newstoreStatus(1);
			$follow_data = M('Areply')->field('home,keyword,content')->where(array('token' => $this->token))->find();
			$db = M('Distribution_member');
			$my = $db->where(array('token'=>$this->token,'wecha_id'=>$this->data['FromUserName']))->find();
			if (!(strpos($data['EventKey'], 'qrscene_') === FALSE)) {
				$sceneid = str_replace('qrscene_', '', $data['EventKey']);
            }
			if(!$my){
				$user_info = $this->get_user_info();
				$mydata['nickname'] = $user_info['nickname'];
				$mydata['headimgurl'] = $user_info['headimgurl'];
				$mydata['province'] = $user_info['province'];
				$mydata['city'] = $user_info['city'];
				$mydata['country'] = $user_info['country'];
				$mydata['sex'] = $user_info['sex'];
				$mydata['wecha_id'] = $this->data['FromUserName'];
				$mydata['token'] = $this->token;
				$mydata['createtime'] = time();
				$mydata['status'] = 1;
				$mydata['follow'] = 1;//关注
				if($myid = $db->add($mydata)){
					$set = M('Distribution_set')->where(array('token'=>$this->token))->find();
					if($sceneid){//通过二维码
						$from_member = $db->where('id='.$sceneid)->find();
					}else{//通过点击
						$click = M('Distribution_click')->where(array('token'=>$this->token,'wecha_id'=>$this->data['FromUserName']))->order('updatetime desc')->find();
						if($click){
							$from_member = $db->where('id='.$click['mid'])->find();
						}
					}
					if($from_member){
						$memberData['bindmid'] = $from_member['id'];
						$db->where('id='.$myid)->save($memberData);//绑定所属会员id
						if($db->where(array('token'=>$this->token,'wecha_id'=>$this->data['FromUserName'],'id'=>array('neq',$myid)))->find()){
							$db->where('id='.$myid)->delete();
						}else{
							$db->where('id='.$from_member['id'])->setInc('followNums');//关注累加
							$db->where('id='.$from_member['id'])->setInc('firstNums');//一级会员累加
							$leveData['fid'] = $from_member['id'];
							if($from_member['fid']!=0){
								$db->where('id='.$from_member['fid'])->setInc('secondNums');//二级会员累加
								$leveData['sid'] = $from_member['fid'];
							}
							if($from_member['sid']!=0){
								$db->where('id='.$from_member['sid'])->setInc('thirdNums');//三级会员累加
								$leveData['tid'] = $from_member['sid'];
							}
							$leveData['handle'] = 1;//处理结束
							$db->where('id='.$myid)->save($leveData);//会员所属绑定
							//上级消息推送
							$access_token_p = $this->get_access_token();
							$data_p = '{"touser":"'.$from_member['wecha_id'].'","msgtype":"news","news":{"articles":[{"title":"您有新朋友加入，赶紧看看吧","description":"亲：新朋友的消费您都将有提成哦","url":"'.C('site_url').U('Wap/Distribution/followList',array('token'=>$this->token,'wecha_id'=>$from_member['wecha_id'])).'","picurl":""}]}}';
							$result_p = $this->api_notice_increment('https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$access_token_p,$data_p);
						}
					}else{
						if($db->where(array('token'=>$this->token,'wecha_id'=>$this->data['FromUserName'],'id'=>array('neq',$myid)))->find()){
							$db->where('id='.$myid)->delete();
						}
					}
				}
			}else{
				if($my['follow']==0){
					$mydata['follow'] = 1;//关注
					$db->where('id='.$my['id'])->save($mydata);
				}
			}
            if ($follow_data['home'] == 1) {
                return $this->keyword($follow_data['keyword']);
            } else {
				if(strstr($follow_data['content'],'{memberid}')){
					$set = M('Distribution_set')->where(array('token'=>$this->token))->find();
					$my = $db->where(array('token'=>$this->token,'wecha_id'=>$this->data['FromUserName']))->find();
					$follow_data['content'] = str_replace('{memberid}', $set['startNums']+$my['id'], $follow_data['content']);
				}
                return array(html_entity_decode($follow_data['content']), 'text');
            }
        } elseif ('unsubscribe' == $data['Event']) {
			$db = M('Distribution_member');
			$my = $db->where(array('token'=>$this->token,'wecha_id'=>$this->data['FromUserName']))->find();
			if($my['follow']==1){
				$mydata['follow'] = 0;//取消关注
				$db->where('id='.$my['id'])->save($mydata);
			}
			//$this->newstoreStatus(0);
        } elseif ($data['Event'] == 'LOCATION') {
			$datas['Latitude'] = $this->data['Latitude'];
			$datas['Longitude'] = $this->data['Longitude'];
			$datas['Precision'] = $this->data['Precision'];
			$datas['Label'] = '';
			$datas['newtime'] = time();
			$conditions['token'] = $this->token;
			$conditions['wecha_id'] = $this->data['FromUserName'];
			$Lid = M('Location')->where($conditions)->getField('id');
			if($Lid){
				M('Location')->where('id='.$Lid)->save($datas);
			}else{
				$datas['token'] = $this->token;
				$datas['wecha_id'] = $this->data['FromUserName'];
				M('Location')->add($datas);
			}
        } elseif ($data['Event'] == 'scancode_waitmsg') {
        	return array('扫二维码测试', 'text');
        }
        if ('voice' == $data['MsgType']) {
            $data['Content'] = $data['Recognition'];
            if ($data['Recognition']) {
                $this->data['Content'] = $data['Recognition'];
            } else {
            }
        }
		$Pin = new GetPin();
		$key = $data['Content'];
		$datafun = explode(',', $this->fun);
		$tags = $this->get_tags($key);
		$back = explode(',', $tags);
		foreach ($back as $keydata => $data) {
			$string = $Pin->Pinyin($data);
			if (in_array($string, $datafun) && $string) {
				unset($back[$keydata]);
				if (method_exists('WeixinAction', $string)) {
					eval('$return= $this->' . $string . '($back);');
				} else {
				}
				break;
			}
		}
        if (!empty($return)) {
            if (is_array($return)) {
                return $return;
            } else {
                return array($return, 'text');
            }
        } else {
			if($key==''){
				$follow_data = M('Areply')->field('home,keyword,content')->where(array('token' => $this->token))->find();
				if(strstr($follow_data['content'],'{memberid}')){
					$set = M('Distribution_set')->where(array('token'=>$this->token))->find();
					$my = M('Distribution_member')->where(array('token'=>$this->token,'wecha_id'=>$this->data['FromUserName']))->find();
					$follow_data['content'] = str_replace('{memberid}', $set['startNums']+$my['id'], $follow_data['content']);
				}
                return array(html_entity_decode($follow_data['content']), 'text');
			}
            return $this->keyword($key);
        }
    }
    private function keyword($key)
    {
        $like['keyword'] = array('like', '%' . $key . '%');
        $like['token'] = $this->token;
        $data = M('keyword')->where($like)->order('id desc')->find();
        if ($data != false) {
            switch ($data['module']) {
                case 'Img':
                    $img_db = M($data['module']);
                    $back = $img_db->field('id,text,pic,url,title')->limit(9)->order('usort desc')->where($like)->select();
                    $idsWhere = 'id in (';
                    $comma = '';
                    foreach ($back as $keya => $infot) {
                        $idsWhere .= $comma . $infot['id'];
                        $comma = ',';
                        if ($infot['url'] != false) {
                            if (!(strpos($infot['url'], 'http') === FALSE)) {
                                $url = $this->getFuncLink(html_entity_decode($infot['url']));
                            } else {
                                $url = $this->getFuncLink($infot['url']);
                            }
                        } else {
                            $url = rtrim($this->siteUrl, '/') . U('Wap/Index/content', array('token' => $this->token, 'id' => $infot['id'], 'wecha_id' => $this->data['FromUserName']));
                        }
                        $return[] = array($infot['title'], $this->handleIntro($infot['text']), $infot['pic'], $url);
                    }
                    $idsWhere .= ')';
                    if ($back) {
                        $img_db->where($idsWhere)->setInc('click');
                    }
                    return array($return, 'news');
                    break;
                case 'Text':
                    $info = M($data['module'])->order('id desc')->find($data['pid']);
                    return array(htmlspecialchars_decode(str_replace('{wechat_id}', $this->data['FromUserName'], $info['text'])), 'text');
                    break;
                default:
                    $info = M($data['module'])->order('id desc')->find($data['pid']);
                    return array(array($info['title'], $info['keyword'], $info['musicurl'], $info['hqmusicurl']), 'music');
            }
        } else {
			$other = M('Other')->where(array('token' => $this->token))->find();
			if ($other == false) {
				return array('', 'text');
			} else {
				if (empty($other['keyword'])) {
					return array($other['info'], 'text');
				} else {
					$img = M('Img')->field('id,text,pic,url,title')->limit(10)->order('usort desc')->where(array('token' => $this->token, 'keyword' => array('like', '%' . $other['keyword'] . '%')))->select();
					if ($img == false) {
						$multiImgs = M('Img_multi')->where(array('token' => $this->token, 'keywords' => array('like', '%' . $other['keyword'] . '%')))->find();
						if (!$multiImgs) {
							return array('无此图文信息,请提醒商家，重新设定关键词', 'text');
						} else {
							$multiImgClass = new multiImgNews($this->token, $this->data['FromUserName'], $this->siteUrl);
							return $multiImgClass->news($multiImgs['id']);
						}
					}
					foreach ($img as $keya => $infot) {
						if ($infot['url'] != false) {
							if (!(strpos($infot['url'], 'http') === FALSE)) {
								$url = $this->getFuncLink(html_entity_decode($infot['url']));
							} else {
								$url = $this->getFuncLink($infot['url']);
							}
						} else {
							$url = rtrim($this->siteUrl, '/') . U('Wap/Index/content', array('token' => $this->token, 'id' => $infot['id'], 'wecha_id' => $this->data['FromUserName']));
						}
						$return[] = array($infot['title'], $infot['text'], $infot['pic'], $url);
					}
					return array($return, 'news');
				}
			}
        }
    }
    private function getFuncLink($u)
    {
        $urlInfos = explode(' ', $u);
        switch ($urlInfos[0]) {
            default:
                $url = str_replace(array('{wechat_id}', '{siteUrl}', '&amp;'), array($this->data['FromUserName'], $this->siteUrl, '&'), $urlInfos[0]);
                break;
            case '刮刮卡':
                $Lottery = M('Lottery')->where(array('token' => $this->token, 'type' => 2, 'status' => 1))->order('id DESC')->find();
                $url = $this->siteUrl . U('Wap/Guajiang/index', array('token' => $this->token, 'wecha_id' => $this->data['FromUserName'], 'id' => $Lottery['id']));
                break;
            case '大转盘':
                $Lottery = M('Lottery')->where(array('token' => $this->token, 'type' => 1, 'status' => 1))->order('id DESC')->find();
                $url = $this->siteUrl . U('Wap/Lottery/index', array('token' => $this->token, 'wecha_id' => $this->data['FromUserName'], 'id' => $Lottery['id']));
                break;
            case '商家订单':
                $url = $this->siteUrl . '/index.php?g=Wap&m=Host&a=index&token=' . $this->token . '&wecha_id=' . $this->data['FromUserName'] . '&hid=' . $urlInfos[1] . '';
                break;
            case '优惠券':
                $Lottery = M('Lottery')->where(array('token' => $this->token, 'type' => 3, 'status' => 1))->order('id DESC')->find();
                $url = $this->siteUrl . U('Wap/Coupon/index', array('token' => $this->token, 'wecha_id' => $this->data['FromUserName'], 'id' => $Lottery['id']));
                break;
            case '万能表单':
                $url = $this->siteUrl . U('Wap/Selfform/index', array('token' => $this->token, 'wecha_id' => $this->data['FromUserName'], 'id' => $urlInfos[1]));
                break;
            case '会员卡':
                $url = $this->siteUrl . U('Wap/Card/vip', array('token' => $this->token, 'wecha_id' => $this->data['FromUserName']));
                break;
            case '首页':
                $url = rtrim($this->siteUrl, '/') . '/index.php?g=Wap&m=Index&a=index&token=' . $this->token . '&wecha_id=' . $this->data['FromUserName'];
                break;
            case '团购':
                $url = rtrim($this->siteUrl, '/') . '/index.php?g=Wap&m=Groupon&a=grouponIndex&token=' . $this->token . '&wecha_id=' . $this->data['FromUserName'];
                break;
            case '商城':
                $url = rtrim($this->siteUrl, '/') . '/index.php?g=Wap&m=Store&a=products&token=' . $this->token . '&wecha_id=' . $this->data['FromUserName'];
                break;
            case '订餐':
                $url = rtrim($this->siteUrl, '/') . '/index.php?g=Wap&m=Repast&a=index&dining=1&token=' . $this->token . '&wecha_id=' . $this->data['FromUserName'];
                break;
            case '相册':
                $url = rtrim($this->siteUrl, '/') . '/index.php?g=Wap&m=Photo&a=index&token=' . $this->token . '&wecha_id=' . $this->data['FromUserName'];
                break;
            case '网站分类':
                $url = $this->siteUrl . U('Wap/Index/lists', array('token' => $this->token, 'wecha_id' => $this->data['FromUserName'], 'classid' => $urlInfos[1]));
                break;
            case 'LBS信息':
                if ($urlInfos[1]) {
                    $url = $this->siteUrl . U('Wap/Company/map', array('token' => $this->token, 'wecha_id' => $this->data['FromUserName'], 'companyid' => $urlInfos[1]));
                } else {
                    $url = $this->siteUrl . U('Wap/Company/map', array('token' => $this->token, 'wecha_id' => $this->data['FromUserName']));
                }
                break;
            case 'DIY宣传页':
                $url = $this->siteUrl . '/index.php/show/' . $this->token;
                break;
            case '婚庆喜帖':
                $url = $this->siteUrl . U('Wap/Wedding/index', array('token' => $this->token, 'wecha_id' => $this->data['FromUserName'], 'id' => $urlInfos[1]));
                break;
            case '投票':
                $url = $this->siteUrl . U('Wap/Vote/index', array('token' => $this->token, 'wecha_id' => $this->data['FromUserName'], 'id' => $urlInfos[1]));
                break;
        }
        return $url;
    }
    private function error_msg($data)
    {
        return '没有找到' . $data . '相关的数据';
    }
	private function newstoreRecord($data)
    {
		$dataScore['token'] = $this->token;
		$dataScore['wecha_id'] = $this->data['FromUserName'];
		$newscoreId = M('Newstore')->where($dataScore)->getField('id');
		if($newscoreId){
			$dataScore['lasttime'] = time();
			$dataScore['Event'] = $data['Event'];
			$dataScore['Content'] = $data['Content'];
			M('Newstore')->where('id='.$newscoreId)->save($dataScore);
		}else{
			$dataScore['lasttime'] = time();
			$dataScore['Event'] = $data['Event'];
			$dataScore['Content'] = $data['Content'];
			M('Newstore')->add($dataScore);
		}
    }
	private function newstoreStatus($status)
    {
		$dataScore['token'] = $this->token;
		$dataScore['wecha_id'] = $this->data['FromUserName'];
		$data['status'] = $status;
		M('Newstore')->where($dataScore)->save($data);
    }
	private function get_user_info(){
		if($this->token!=''&&preg_match('/^[0-9a-zA-Z]{3,42}$/', $this->token)){
			$access_token = M('access_token')->where(array('token'=>$this->token))->find();
			if($access_token){
				$access_str = $this->get_access_token();
				$data['access_token'] = $access_str;
				$data['updatetime'] = time();
				M('access_token')->where(array('token'=>$this->token))->save($data);
			}else{
				$access_str = $this->get_access_token();
				$data['token'] = $this->token;
				$data['access_token'] = $access_str;
				$data['updatetime'] = time();
				M('access_token')->add($data);
			}
			$info = $this->curlGet('https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_str.'&openid='.$this->data['FromUserName'].'&lang=zh_CN');
			Log::write('API_userinfo='.$info,'DEBUG');
			$infoarr = json_decode($info, 1);
			return $infoarr;
		}
	}
	private function get_access_token(){
		$rt = $this->curlGet('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . $this->wxuser['appid'] . '&secret=' . $this->wxuser['appsecret']);
        $jsonrt = json_decode($rt, 1);
		return $jsonrt['access_token'];
	}
    private function api_notice_increment($url, $data, $converturl = 1)
    {
        $ch = curl_init();
        $header = 'Accept-Charset: utf-8';
        if ($converturl) {
            if (strpos($url, '?')) {
                $url .= '&token=' . $this->token;
            } else {
                $url .= '?token=' . $this->token;
            }
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $tmpInfo = curl_exec($ch);
        if (curl_errno($ch)) {
            return false;
        } else {
            return $tmpInfo;
        }
    }
    private function curlGet($url, $method = 'get', $data = '')
    {
        $ch = curl_init();
        $header = 'Accept-Charset: utf-8';
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
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
	private function get_tags($title, $num = 10)
    {
        vendor('Pscws.Pscws4', '', '.class.php');
        $pscws = new PSCWS4();
        $pscws->set_dict(CONF_PATH . 'etc/dict.utf8.xdb');
        $pscws->set_rule(CONF_PATH . 'etc/rules.utf8.ini');
        $pscws->set_ignore(true);
        $pscws->send_text($title);
        $words = $pscws->get_tops($num);
        $pscws->close();
        $tags = array();
        foreach ($words as $val) {
            $tags[] = $val['word'];
        }
        return implode(',', $tags);
    }
	public function handleIntro($str)
    {
        $search = array('&quot;', '&nbsp;');
        $replace = array('"', '');
        return str_replace($search, $replace, $str);
    }
}