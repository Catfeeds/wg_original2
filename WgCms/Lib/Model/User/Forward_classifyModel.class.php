<?php
class Forward_classifyModel extends Model{
	protected $_validate = array(
			array('name','require','分类名称必填',1),
			array('key','require','分类表示必填',1),

	 );
	protected $_auto = array (		
		array('token','getToken',Model:: MODEL_BOTH,'callback'),
		array('add_time','time',2,'function'),
	);
	function getToken(){	
		return $_SESSION['token'];
	}
}

?>
