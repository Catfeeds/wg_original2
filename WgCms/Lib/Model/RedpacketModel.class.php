<?php
    class RedpacketModel extends Model{
    protected $_validate = array(
		array('keyword','require','关键词不能为空',1),
        array('title','require','名称不能为空',1),
		array('starttime','require','开始时间不能为空',1),
		array('endtime','require','结束时间不能为空',1),
		array('helpcount','require','单个手机帮助限制不能为空',1),
		//array('endtime', 'checkdate', '结束时间不能小于开始时间',Model::MUST_VALIDATE,'callback',3),
		array('endtitle','require','结束公告主题不能为空',1)
     );
    protected $_auto = array (
		array('token','gettoken',1,'callback'),
		array('addtime','time',1,'function')
    );
    function gettoken(){
		return session('token');
	}
	function checkdate(){	
		 if(strtotime($_POST['endtime'])<strtotime($_POST['starttime'])){
			 return false;
		}else{
			return true;
		}
	}
	function checkKeyword(){
		$where['token'] = session('token');
		$where['keyword'] = $_POST['keyword'];
		if(M('keyword')->where($where)->find()){
			return false;
		}else{
			return true;
		}
	}
}

?>