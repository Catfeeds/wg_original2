<?php  
	class AccountModel extends RelationModel{
		public $my;
		protected $tableName = 'Distribution_account';
		protected function _initialize(){
			$this->my = M('Distribution_member')->where(array('wecha_id'=>$_SESSION['qchwecha_id']))->find();
		}
	}
?>