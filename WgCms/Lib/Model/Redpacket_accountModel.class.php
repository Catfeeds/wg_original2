<?php
    class Redpacket_accountModel extends RelationModel{
	protected $_link = array(
        //关联角色
	    'redpacket' => array(
            'mapping_type' => BELONGS_TO,
            'class_name' => 'redpacket',
            'foreign_key' => 'pid',
        )
    );
	protected $_validate = array(
		array('pid','require','所属活动不能为空',1),
        array('username','require','帐号不能为空',1),
		array('password','require','密码不能为空',1),
		array('repassword','password','两次密码不一致',0,'confirm'), 
		array('username','','用户名称已经存在！',1,'unique',1), // 新增修改时候验证username字段是否唯一
     );
    protected $_auto = array (
		array('password','md5',self::MODEL_BOTH,'function'),
		array('token','gettoken',1,'callback'),
		array('createtime','time',self::MODEL_INSERT,'function'),
		array('createip','getip',self::MODEL_INSERT,'callback'),
		//array('viptime','time',self::MODEL_BOTH,'function'),
		array('lasttime','time',self::MODEL_BOTH,'function'),
		array('lastip','getip',self::MODEL_BOTH,'callback'),
    );
	public function getip(){
		return htmlspecialchars(trim(get_client_ip()));
	}
    function gettoken(){
		return session('token');
	}
}

?>