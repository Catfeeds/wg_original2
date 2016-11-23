<?php  
	class FeedBackModel extends RelationModel{
		protected $tableName = "Feedback_list";
		protected $_link = array(
			'account' => array(
				'mapping_type' => BELONGS_TO,
				'class_name' => 'Account',
				'foreign_key' => 'aid',
			),
		);
	}
?>