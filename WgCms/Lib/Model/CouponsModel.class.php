<?php  
	class CouponsModel extends RelationModel{
	    protected $_link = array(
	    	'account' => array(
	    		'mapping_type' => BELONGS_TO,
	    		'class_name' => 'Distribution_account',
	    		'foreign_key' => 'aid',
	    		'mapping_fields' => 'username',
	    	),
	    );
	}
?>