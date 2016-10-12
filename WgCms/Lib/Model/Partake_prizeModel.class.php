<?php
    class Partake_prizeModel extends Model{
    protected $_validate = array(
        array('title','require','名称不能为空',1),
		array('chance','require','中奖率不能为空',1),
		array('useScore','require','消耗积分不能为空',1),
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