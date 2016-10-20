<?php
/**
 *关注回复
**/
class AreplyAction extends UserAction{
	public function index(){
		$id=$this->_get('id','intval');
		if (!$id){
			$token=$this->token;
			$info=M('Wxuser')->where(array('token'=>$this->token))->find();
		}else {
			$info=M('Wxuser')->find($id);
		}
		$token=$this->_get('token','trim');	
		if($info==false||$info['token']!=$token){
			$this->error('非法操作',U('Home/Index/index'));
		}
		session('token',$token);
		session('wxid',$info['id']);
		//第一次登陆　创建　功能所有权
		$token_open=M('Token_open');
		$toback=$token_open->field('id,queryname')->where(array('token'=>session('token'),'uid'=>session('uid')))->find();
		$open['uid']=session('uid');
		$open['token']=session('token');
		//遍历功能列表
		if (!C('agent_version')){
			$group=M('User_group')->field('id,name')->where('status=1')->order('id ASC')->select();
		}else {
			$group=M('User_group')->field('id,name')->where('status=1 AND agentid='.$this->agentid)->order('id ASC')->select();
		}
		$check=explode(',',$toback['queryname']);
		$this->assign('check',$check);
		foreach($group as $key=>$vo){
			if (C('agent_version')&&$this->agentid){
				$fun=M('Agent_function')->where(array('status'=>1,'gid'=>$vo['id']))->select();
			}else {
				$fun=M('Function')->where(array('status'=>1,'gid'=>$vo['id']))->select();
			}
			
			foreach($fun as $vkey=>$vos){
				$vos['groupName']=$vo['name'];
				$function[$key][$vkey]=$vos;
			}
		}
		$this->assign('fun',$function);
		//
		$wecha=M('Wxuser')->field('wxname,wxid,headerpic,weixin')->where(array('token'=>session('token'),'uid'=>session('uid')))->find();
		$this->assign('wecha',$wecha);
		$this->assign('token',session('token'));
		//
		$db=D('Areply');
		$where['uid']=$_SESSION['uid'];
		$where['token']=$_SESSION['token'];
		$res=$db->where($where)->find();
		$this->assign('areply',$res);
		$this->display();
	}
	public function insert(){
		C('TOKEN_ON',false);
		$db=D('Areply');
		$where['uid']=$_SESSION['uid'];
		$where['token']=$_SESSION['token'];
		$res=$db->where($where)->find();
		if($res==false){
			$where['content']=html_entity_decode($this->_post('content'));
			if(isset($_POST['keyword'])){
				$where['keyword']=$this->_post('keyword');
			}			
			if($where['content']==false){$this->error('内容必须填写');}
			$where['createtime']=time();
			$id=$db->data($where)->add();
			if($id){
				$this->success('发布成功',U('Areply/index',array('token'=>session('token'))));
			}else{
				$this->error('发布失败',U('Areply/index',array('token'=>session('token'))));
			}
		}else{
			$where['id']=$res['id'];
			$where['content']=html_entity_decode($this->_post('content'));
			$where['home']=intval($this->_post('home'));
			$where['updatetime']=time();
			if(isset($_POST['keyword'])){
				$where['keyword']=$this->_post('keyword');
			}		
			if($db->save($where)){
				$this->success('更新成功',U('Areply/index',array('token'=>session('token'))));
			}else{
				$this->error('更新失败',U('Areply/index',array('token'=>session('token'))));
			}
		}
	}
}
?>