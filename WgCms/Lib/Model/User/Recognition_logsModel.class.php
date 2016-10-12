<?php
class Recognition_logsModel extends RelationModel{
	protected $_link = array(
			'recognition' => array(
					'mapping_type' => BELONGS_TO,
					'class_name' => 'recognition',
					'foreign_key' => 'recognition_id',
			)
	);
}

?>
