<?php
    class Redpacket_prize_manModel extends RelationModel{
	protected $_link = array(
        //关联角色
	    'Redpacket' => array(
            'mapping_type' => BELONGS_TO,
            'class_name' => 'Redpacket',
            'foreign_key' => 'pid',
        )
    );
}

?>