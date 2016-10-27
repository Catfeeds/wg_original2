<?php
class ShopAction extends UserAction{
	

	public function index(){

		$cityModel = M('City_list');

		if(IS_POST){
            $key = $this->_post('searchkey');
            	if(empty($key)){
                	$this->error("关键词不能为空");
        		}else{

        		$map['name|char'] = array('like',"%$key%"); 

        		$cityList = $cityModel->where($map)->select(); 
        		$count 	  = $cityModel->where($map)->count();       
        		$Page     = new Page($count,20);
        		$show     = $Page->show(); 
        		}  
        }else{ 

			$count  = $cityModel->count();       
        	$Page   = new Page($count,10);
        	$show   = $Page->show();
        	//按照char 字典序排序
			$str    = "SELECT * FROM `pigcms_city_list` ORDER BY binary CONVERT(`char` USING GBK) ASC LIMIT ".$Page->firstRow.','.$Page->listRows;
			$cityList = M('City_list')->query($str);
		}

		$this->assign('page',$show);
		$this->assign('list',$cityList);
		$this->display();
	}

	public function cityAdd(){

		if(IS_POST){
			$this->insert('CityList','/index');
		}else{
			$parentid = intval($_GET['parentid']);
			$parentid = $parentid==''?0:$parentid;
			$this->assign('parentid',$parentid);
			$this->display('citySet');
		}
	}


	public function citySet(){

		$set = M('City_list')->where('id='.$_GET['id'])->select();
		
		if(IS_POST){

			$data = D('City_list');
            $where= array('id'=>$this->_post('id'));

			$check= $data->where($where)->find();

			if($check==false)$this->error('非法操作');

			if($check){
				if($data->where($where)->save($_POST)){
					$this->success('修改成功',U('Shop/index',array('token'=>session('token'),'parentid'=>$this->_post('parentid'))));
					
				}else{
					$this->error('操作失败');
				}
			}else{
				$this->error($data->getError());
			}		
		}else{
		$this->assign('set',$set[0]);
		$this->display();
		}
	}	

	public function cityDel(){

		if($this->_get('token')!=session('token')){
			$this->error('非法操作');
		}

        $id = $this->_get('id');

        if(IS_GET){   

            $where['id'] = $id;

            $data  = M('City_list');
            $check = $data->where($where)->find();

            if($check==false)   $this->error('非法操作');
            $back=$data->where($where)->delete();

            if($back==true){
                $this->success('操作成功',U('Store/cityList',array('token'=>session('token'),'parentid'=>$check['parentid'])));
            }else{
                 $this->error('服务器繁忙,请稍后再试',U('Store/cityList',array('token'=>session('token'))));
            }
        }        		
	}

	public function departList(){

		$departModel = M('Store_list');

		if(IS_POST){
            $key = $this->_post('searchkey');
            	if(empty($key)){
                	$this->error("关键词不能为空");
        		}else{

        		$map['name|address'] = array('like',"%$key%"); 
        		$depart  	 = $departModel->where($map)->select(); 
        		}  
        }else{ 

			
			
        	$where['delete']  = 0; 
        	$count  = $departModel->where($where)->count();
        	$Page   = new Page($count,10);    
        	$depart = $departModel->where($where)->order('id asc')->limit($Page->firstRow.','.$Page->listRows)->select(); 
        	$show   = $Page->show();
		}

		$this->assign('page',$show);
		$this->assign('list',$depart);
		$this->display();
	}

	public function departAdd(){

		$city 	  = M('City_list');
		$cityName = $city->field('id,name')->select();	
		$this->assign('city',$cityName);

		//option 
		$char   ='';
		//char 字典序排序
		$sqlstr = "SELECT * FROM `pigcms_city_list` ORDER BY binary CONVERT(`char` USING GBK) ASC ";
		$city1  = M('City_list')->query($sqlstr);	
		$str    ='';

		foreach ($city1 as $key => $value) {
			
			if($value['char']!=$char){
			
			$str.="<option>".$value['char']."</option>";
			$char = $value['char'];
			}

			$str.="<option value=".$value['id'].">&nbsp&nbsp".$value['name']."</option>";
		}

		$this->assign('str',$str);

		if(IS_POST){
		$_POST['password'] = md5($_POST['password']);	
		$worktime = split(",",$_POST['default_time']);
		$_POST['defaultWorkTime'] = serialize($worktime);

		$service = array(
			'photo' => $_POST['photo'],
			'makeup' => $_POST['makeup'],
			'cloth' => $_POST['cloth'],
			'ps' => $_POST['ps'],
			'nail' => $_POST['nail'],
			'sharon' => $_POST['sharon'],
			'coffee' => $_POST['coffee'],
		);
		$_POST['service'] = serialize($service);

		$res = $this->insert('Store_list','/departList');
		
		$city->where('id='.$_POST['cid'])->setInc('snums');
	
		}else{
			$parentid = intval($_GET['parentid']);
			$parentid = $parentid==''?0:$parentid;
			$this->assign('parentid',$parentid);
			$this->display('departSet');
		}
	}


	public function departSet(){


		$set 	  = M('Store_list')->where('id='.$_GET['id'])->find();

		if(!$set){
			$this->error('非法操作');
			exit();
		}

		$city 	  = M('City_list');
		$cityName = $city->field('id,name,char')->select();
		$setCity  = $city->where('id='.$set['cid'])->find();
		$this->assign('city',$cityName);
		$this->assign('setcity',$setCity);
		
		//城市列表
		$char   ='';
		$sqlstr = "SELECT * FROM `pigcms_city_list` ORDER BY binary CONVERT(`char` USING GBK) ASC ";
		$city1  = M('City_list')->query($sqlstr);

		$str   ='';
		foreach ($city1 as $key => $value) {
			
			if($value['char']!=$char){
			
			$str.="<option>".$value['char']."</option>";
			$char = $value['char'];
			}

			$str.="<option value=".$value['id'].">&nbsp&nbsp".$value['name']."</option>";
		}
		$this->assign('str',$str);


		if(IS_POST){

			$_POST['password'] = md5($_POST['password']);
			$data  = D('Store_list');
            $where = array('id'=>$this->_post('id'));
			$check = $data->where($where)->find();

			if($check==false)$this->error('非法操作');

			if($check){
				$worktime = split(",",$_POST['default_time']);
				$_POST['defaultWorkTime'] = serialize($worktime);

				$service = array(
					'photo' => $_POST['photo'],
					'makeup' => $_POST['makeup'],
					'cloth' => $_POST['cloth'],
					'ps' => $_POST['ps'],
					'nail' => $_POST['nail'],
					'sharon' => $_POST['sharon'],
					'coffee' => $_POST['coffee'],
				);
				$_POST['service'] = serialize($service);

				if($data->where($where)->save($_POST)){				
				$this->success('修改成功',U('Shop/departList',array('token'=>session('token'),'parentid'=>$this->_post('parentid'))));	
				}else{
					$this->error('操作失败');
				}
			}else{
				$this->error($data->getError());
			}		
		}else{
		$set['defaultWorkTime'] = json_encode(unserialize($set['defaultWorkTime']));	
		$set['service'] = unserialize($set['service']);	
		$this->assign('set',$set);
		$this->display();
		}
	}	

	public function departDel(){

		if($this->_get('token')!=session('token')){
			$this->error('非法操作');
		}

        $id = $this->_get('id');

        if(IS_GET){   

            $where['id'] = $id;

            $data  = M('Store_list');
            $check = $data->where($where)->find();
            if($check==false)   $this->error('非法操作');

            $back=$data->where($where)->delete();

            if($back==true){

            	M('City_list')->where('id='.$check['cid'])->setDec('snums');
                $this->success('操作成功',U('Shop/departList',array('token'=>session('token'),'parentid'=>$check['parentid'])));
            }else{
                 $this->error('服务器繁忙,请稍后再试',U('Shop/departList',array('token'=>session('token'))));
            }
        }        		
	}	
		
	public	function additionalTime(){

			if(IS_POST){

				$worktime = split(",",$_POST['default_time']);
				$_POST['defaultWorkTime'] = serialize($worktime);
				$limittime = strtotime($_POST['limittime']);
				$sid = $_POST['sid'];
				//查看是否已经添加过
				$condition = array(
					'sid' => $sid,
					'time' => $limittime,
				);

				$limit = M('Store_addtime')->where($condition)->find();

				if($limit){
					$this->error('该日期限制已经添加过',U('Shop/additionalTime'));
				}else{
					$data = array(
						'time' => $limittime,
						'sid' => $sid,
						'defaultWorkTime' => serialize($worktime),
						'addtime' => time(),
						'year' => date('Y',time()),
						'month' => date('m',time()),
						'day' => date('d',time()),
					);

					$re = M('Store_addtime')->add($data);
					if($re){
						$this->success('添加成功',U('Shop/additionalTime'));
					}else{
						$this->error('添加失败',U('Shop/additionalTime'));
					}
				}

			}else{
				$sid = $this->_get('id');
				$stores = M('Store_list')->find();
				$this->assign('stores',$stores);
				$this->assign('sid',$sid);
				$this->display();
			}
		}

	public function dataEdit(){

		if(IS_POST){

				$worktime = split(",",$_POST['default_time']);
				$_POST['defaultWorkTime'] = serialize($worktime);
				$limittime = strtotime($_POST['limittime']);
				
				$data = array(
					'time' => $limittime,
					'defaultWorkTime' => serialize($worktime),
				);

				$re = M('Store_addtime')->where(array('id'=>$_POST['id']))->save($data);
				if($re){
					$this->success('修改成功',U('Shop/additionalTime'));
				}else{
					$this->error('修改失败',U('Shop/additionalTime'));
				}

			}else{
				$where['id']  = $_GET['id']; 
				$addtime = M('Store_addtime')->where($where)->find();
				$addtime['defaultWorkTime'] = json_encode(unserialize($addtime['defaultWorkTime']));
				$set =$addtime;
				$this->assign('set',$set);
				$this->display('additionalTime');
			}
		}



	public function schedule (){


		$db = M('Store_addtime');

		$where['sid'] = $_GET['id'];
		$schedule = $db->where($where)->order('addtime','asc')->select();
		$storename= $_GET['name'];
		$this->assign('storename',$storename);
		$this->assign('list',$schedule);
		$this->display();
	}	
}
?>