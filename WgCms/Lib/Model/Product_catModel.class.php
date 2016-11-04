<?php
    class Product_catModel extends RelationModel{
    protected $_validate = array(
            array('name','require','名称不能为空',1)
     );
    protected $_link = array(
        'store' => array(
            'mapping_type' => BELONGS_TO,
            'class_name' => 'Store_list',
            'foreign_key' => 'sid',
            'mapping_fields' => 'name',
        ),
    );
    protected $_auto = array (
    array('token','gettoken',1,'callback'),
        array('time','time',1,'function')
    );
    function gettoken(){
		return session('token');
	}
}

?>