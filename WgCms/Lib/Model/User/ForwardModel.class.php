<?php
class ForwardModel extends RelationModel{
	protected $_link = array(
			//关联角色
			'forward_classify' => array(
					'mapping_type' => BELONGS_TO,
					'class_name' => 'forward_classify',
					'foreign_key' => 'classify_id',
			)
	);
}

?>
