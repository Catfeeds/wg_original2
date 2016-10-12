<?php  
	class LevelOrdersModel extends RelationModel{
		public $my;
		protected function _initialize(){
			$this->my = M('Distribution_member')->where(array('wecha_id'=>$_SESSION['qchwecha_id']))->find();
		}
		protected $tableNmae='level_orders';
		protected $_link = array(
			'member' => array(
				'mapping_type' => BELONGS_TO,
				'class_name' => 'Distribution_member',
				'foreign_key' => 'mid',
				'mapping_fields' =>'id,nickname,headimgurl',
			),
			'bindmember' => array(
				'mapping_type' => BELONGS_TO,
				'class_name' => 'Distribution_member',
				'foreign_key' => 'bindmid',
				'mapping_fields' =>'nickname',
			),
			'level' => array(
				'mapping_type' => BELONGS_TO,
				'class_name' => 'Distribution_level',
				'foreign_key' => 'lid',
				'mapping_fields' => 'name',
			),
			'account' => array(
				'mapping_type' => BELONGS_TO,
				'class_name' => 'Distribution_account',
				'foreign_key' => 'aid',
			),
		);
		protected $_auto = array(
			array('mid','getMid',1,'callback'),
			array('wecha_id','getWid',1,'callback'),
			array('bindmid','getBindmid',1,'callback'),
			array('addtime','time',1,'function'),
			array('year','getY',1,'callback'),
			array('month','getM',1,'callback'),
			array('day','getD',1,'callback'),
		);
		protected function getMid(){
			return $this->my['id'];
		}
		protected function getWid(){
			return $this->my['wecha_id'];
		}
		protected function getBindmid(){
			return $this->my['bindmid'];
		}
		protected function getY(){
			return date('Y',time());
		}
		protected function getM(){
			return date('m',time());
		}
		protected function getD(){
			return date('d',time());
		}
	}
?>