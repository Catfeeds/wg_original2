<?php
class Forward_setModel extends Model{
	protected $_validate = array(
			array('status','require','是否开启必填',1),
			array('starttime','require','开始时间必填',1),
			array('endtime','require','结束时间必填',1),

	 );
	protected $_auto = array (		
		array('token','getToken',Model:: MODEL_BOTH,'callback'),
		array('starttime','strtotime',3,'function'),
		array('endtime','strtotime',3,'function'),
	);
	function getToken(){	
		return $_SESSION['token'];
	}
}

?>
