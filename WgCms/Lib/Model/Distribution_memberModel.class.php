<?php
    class Distribution_memberModel extends RelationModel{
	protected $_link = array(
        //关联角色
	    'level' => array(
            'mapping_type' => BELONGS_TO,
            'class_name' => 'Distribution_level',
            'foreign_key' => 'lid',
            'mapping_fields' => 'name',
        )
    );
}

?>