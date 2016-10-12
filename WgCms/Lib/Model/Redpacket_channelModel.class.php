<?php
    class Redpacket_channelModel extends RelationModel{
	protected $_link = array(
        //关联角色
	    'redpacket' => array(
            'mapping_type' => BELONGS_TO,
            'class_name' => 'redpacket',
            'foreign_key' => 'pid',
        )
    );
	protected $_validate = array(
        array('title','require','渠道名称不能为空',1),
		array('pid','require','所属活动未选择',1)
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