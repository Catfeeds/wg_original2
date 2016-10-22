<?php  
	Class BranchAction extends UserAction{
		function _initialize(){
			parent::_initialize();
		}
		function additionalTime(){
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
					$this->error('该日期限制已经添加过',U('Branch/additionalTime'));
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
						$this->success('添加成功',U('Branch/additionalTime'));
					}else{
						$this->error('添加失败',U('Branch/additionalTime'));
					}
				}

			}else{
				$stores = M('Store_list')->select();
				$this->assign('stores',$stores);
				$this->display();
			}
		}
	}
?>