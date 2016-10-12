<?php
    class Votenew_prizeModel extends Model{
    protected $_validate = array(
        array('title','require','名称不能为空',1),
		array('people','require','达到票数不能为空',1),
		array('totalNums','require','奖品总数不能为空',1)
     );
    protected $_auto = array (
		array('token','gettoken',1,'callback'),
		array('addtime','time',1,'function'),
    );
    function gettoken(){
		return session('token');
	}
}

?>