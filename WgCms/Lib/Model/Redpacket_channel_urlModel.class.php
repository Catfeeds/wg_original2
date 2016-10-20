<?php
    class Redpacket_channel_urlModel extends Model{
    protected $_validate = array(
        array('keyword','require','标识不能为空',1),
		array('chance','require','概率不能为空',1),
		array('guide_url','require','地址不能为空',1)
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