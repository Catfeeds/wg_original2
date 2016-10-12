<?php
class ForwardAction extends UserAction{
	public function _initialize(){
		parent::_initialize();
	}
	
	public function set(){
		if(IS_POST){
			if(M('Forward_set')->where(array('token'=>session('token')))->find()){
				$this->save('Forward_set',U('Forward/set'));
			}else{
			    $this->insert('Forward_set',U('Forward/set'));
			}
		}else{
			$set = M('Forward_set')->where(array('token'=>session('token')))->find();
			$this->assign('set',$set);
			$this->display();
		}
	}
	
	public function index(){
		$db=D('Forward');
		$where=array('token'=>session('token'));
		$count=$db->where($where)->count();
		$page=new Page($count,25);
		$list=$db->where($where)->relation(true)->limit($page->firstRow.','.$page->listRows)->order('id desc')->select();
		$user = new HoomiModel('user','ins_',$connection);
		$item = new HoomiModel('item','ins_',$connection);
		$shop = new HoomiModel('shop','ins_',$connection);
		foreach($list as $key=>$value){
			if(!empty($value['from_uid'])&&$value['from_uid']!=0){
			    $list[$key]['username'] = $user->where(array('id'=>$value['from_uid']))->getField('username');
			}
			if(!empty($value['item_id'])&&$value['item_id']!=0){
				$list[$key]['item_title'] = $item->where(array('id'=>$value['item_id']))->getField('title');
			}
			if(!empty($value['shop_id'])&&$value['shop_id']!=0){
				$list[$key]['shop_title'] = $shop->where(array('id'=>$value['shop_id']))->getField('title');
			}
			if($value['action']=='friend'){
			    $list[$key]['action'] = '朋友';
			}
			if($value['action']=='friends'){
			    $list[$key]['action'] = '朋友圈';
			}
			if($value['action']=='weibo'){
			    $list[$key]['action'] = '微博';
			}

		}
		$this->assign('page',$page->show());
		$this->assign('list',$list);
		$this->display();
	}
	
	public function classify(){
		$db=D('Forward_classify');
		$where=array('token'=>session('token'));
		$count=$db->where($where)->count();
		$page=new Page($count,25);
		$list=$db->where($where)->limit($page->firstRow.','.$page->listRows)->order('id desc')->select();
		$this->assign('page',$page->show());
		$this->assign('list',$list);
		$this->display();
	}
	
	public function add_classify(){
		
		if(IS_POST){
			$this->all_insert('Forward_classify',U('Forward/classify'));
		}else{
			$this->display();
		}
	}
	
	public function edit_classify(){
	
		if(IS_POST){
			$this->save('Forward_classify',U('Forward/classify'));
		}else{
			$info = M('Forward_classify')->where(array('id'=>$_GET['id']))->find();
			$this->assign('info',$info);
			$this->display();
		}
	}
	public function del(){
		$data=D('Forward_classify');
		$where['id']=$this->_get('id','intval');
		if($where['id']==false) $this->error('非法操作');
		$where['token']=$this->token;
		//dump($where);exit;
		$back=$data->where($where)->delete();
		if($back==false){
			$this->error('操作失败');
		}else{
			$this->success('操作成功');
		}
	}	
	public function classify_statistics() {
    	$classify = D('forward_classify')->where(array('token' => session('token')))->select();
    	$xml='<chart borderThickness="0" caption="'.$days.'分类转发统计" baseFontColor="666666" baseFont="宋体" baseFontSize="14" bgColor="FFFFFF" bgAlpha="0" showBorder="0" bgAngle="360" pieYScale="90"  pieSliceDepth="5" smartLineColor="666666">';
    	foreach($classify as $key=>$value){
    		$classify[$key]['count'] = D('forward')->where(array('classify_id' => $value['id'], 'token' => session('token')))->count();
    		$xml.='<set label="'.$value['name'].'" value="'.$classify[$key]['count'].'"/>';
    	}
    	$xml.='</chart>';
    	$this->assign('xml',$xml);
    	$this->display();
    }
    
    public function item_statistics() {
    	if($this->_get('month')==false){
    		$month=date('m');
    	}else{
    		$month=$this->_get('month');
    	}
    	$thisYear=date('Y');
    	if($this->_get('year')==false){
    		$year=$thisYear;
    	}else{
    		$year=$this->_get('year');
    	}
    	$this->assign('month',$month);
    	$this->assign('year',$year);
    	$lastyear=$thisYear-1;
    	if ($year==$lastyear){
    		$yearOption='<option value="'.$lastyear.'" selected>'.$lastyear.'</option><option value="'.$thisYear.'">'.$thisYear.'</option>';
    	}else {
    		$yearOption='<option value="'.$lastyear.'">'.$lastyear.'</option><option value="'.$thisYear.'" selected>'.$thisYear.'</option>';
    	}
    	$this->assign('yearOption',$yearOption);
    	$where=array('month'=>$month,'year'=>$year);
		$where['item_id'] = array('neq',0);
		$where['token'] = session('token');
    	$forward = D('Forward')->where($where)->group('day')->select();
    	//dump($forward);
    	$xml='<chart caption="'.$month.'月统计图" xAxisName="日期" yAxisName="" labelStep="" showNames="" showValues="" rotateNames="" showColumnShadow="1" animation="1" showAlternateHGridColor="0" AlternateHGridColor="ff5904" divLineColor="D0DCE4" divLineAlpha="100" alternateHGridAlpha="5"   formatNumberScale="0"  baseFontColor="666666" baseFont="宋体" baseFontSize="12" outCnvBaseFontSize="12"  canvasBorderThickness="1" canvasBorderColor="D0DCE4"  bgColor="FFFFFF" bgAlpha="0"  showBorder="0"  lineColor="0F6FCF" lineThickness="3"  anchorBorderColor="FFFFFF" anchorBorderThickness="2" anchorBgColor="0F6FCF"   numDivLines="2" numVDivLines="3"><categories>';
    	$fansCountSet='';
    	$imgRequryCountSet='';
    	$list_reverse=array_reverse($forward);
    	foreach ($forward as $li){
    		$day=$li['day'];
    		$xml.='<category label="'.$day.'"/>';
			$daywhere = array('month'=>$month,'year'=>$year,'day'=>$day);
			$daywhere['item_id'] = array('neq',0);
			$daywhere['token'] = session('token');
    		$daycount = D('Forward')->where($daywhere)->count();
    		$forwardCountSet.='<set value="'.$daycount.'"/>';
    	}
    	$xml.='</categories><dataset seriesName="转发数" color="1D8BD1" anchorBorderColor="1D8BD1" anchorBgColor="1D8BD1">'.$forwardCountSet.'</dataset><styles><definition><style name="CaptionFont" type="font" size="12"/></definition><application><apply toObject="CAPTION" styles="CaptionFont"/><apply toObject="SUBCAPTION" styles="CaptionFont"/></application></styles></chart>';
    	$this->assign('xml',$xml);
    	$this->assign('forward',$forward);
    	$this->display();
    }

	public function shop_statistics() {
    	if($this->_get('month')==false){
    		$month=date('m');
    	}else{
    		$month=$this->_get('month');
    	}
    	$thisYear=date('Y');
    	if($this->_get('year')==false){
    		$year=$thisYear;
    	}else{
    		$year=$this->_get('year');
    	}
    	$this->assign('month',$month);
    	$this->assign('year',$year);
    	$lastyear=$thisYear-1;
    	if ($year==$lastyear){
    		$yearOption='<option value="'.$lastyear.'" selected>'.$lastyear.'</option><option value="'.$thisYear.'">'.$thisYear.'</option>';
    	}else {
    		$yearOption='<option value="'.$lastyear.'">'.$lastyear.'</option><option value="'.$thisYear.'" selected>'.$thisYear.'</option>';
    	}
    	$this->assign('yearOption',$yearOption);
    	$where = array('month'=>$month,'year'=>$year);
		$where['shop_id'] = array('neq',0);
		$where['token'] = session('token');
    	$forward = D('Forward')->where($where)->group('day')->select();
    	//dump($forward);
    	$xml='<chart caption="'.$month.'月统计图" xAxisName="日期" yAxisName="" labelStep="" showNames="" showValues="" rotateNames="" showColumnShadow="1" animation="1" showAlternateHGridColor="0" AlternateHGridColor="ff5904" divLineColor="D0DCE4" divLineAlpha="100" alternateHGridAlpha="5"   formatNumberScale="0"  baseFontColor="666666" baseFont="宋体" baseFontSize="12" outCnvBaseFontSize="12"  canvasBorderThickness="1" canvasBorderColor="D0DCE4"  bgColor="FFFFFF" bgAlpha="0"  showBorder="0"  lineColor="0F6FCF" lineThickness="3"  anchorBorderColor="FFFFFF" anchorBorderThickness="2" anchorBgColor="0F6FCF"   numDivLines="2" numVDivLines="3"><categories>';
    	$fansCountSet='';
    	$imgRequryCountSet='';
    	$list_reverse=array_reverse($forward);
    	foreach ($forward as $li){
    		$day=$li['day'];
    		$xml.='<category label="'.$day.'"/>';
			$daywhere = array('month'=>$month,'year'=>$year,'day'=>$day);
			$daywhere['shop_id'] = array('neq',0);
			$daywhere['token'] = session('token');
    		$daycount = D('Forward')->where($daywhere)->count();
    		$forwardCountSet.='<set value="'.$daycount.'"/>';
    	}
    	$xml.='</categories><dataset seriesName="转发数" color="1D8BD1" anchorBorderColor="1D8BD1" anchorBgColor="1D8BD1">'.$forwardCountSet.'</dataset><styles><definition><style name="CaptionFont" type="font" size="12"/></definition><application><apply toObject="CAPTION" styles="CaptionFont"/><apply toObject="SUBCAPTION" styles="CaptionFont"/></application></styles></chart>';
    	$this->assign('xml',$xml);
    	$this->assign('forward',$forward);
    	$this->display();
    }

    public function user_statistics() {
    	if($this->_get('month')==false){
    		$month=date('m');
    	}else{
    		$month=$this->_get('month');
    	}
    	$thisYear=date('Y');
    	if($this->_get('year')==false){
    		$year=$thisYear;
    	}else{
    		$year=$this->_get('year');
    	}
    	$this->assign('month',$month);
    	$this->assign('year',$year);
    	$lastyear=$thisYear-1;
    	if ($year==$lastyear){
    		$yearOption='<option value="'.$lastyear.'" selected>'.$lastyear.'</option><option value="'.$thisYear.'">'.$thisYear.'</option>';
    	}else {
    		$yearOption='<option value="'.$lastyear.'">'.$lastyear.'</option><option value="'.$thisYear.'" selected>'.$thisYear.'</option>';
    	}
    	$this->assign('yearOption',$yearOption);
    	$where = array('month'=>$month,'year'=>$year);
		$where['from_uid'] = array('neq',0);
		$where['token'] = session('token');
    	$forward = D('Forward')->where($where)->group('day')->select();
    	//dump($forward);
    	$xml='<chart caption="'.$month.'月统计图" xAxisName="日期" yAxisName="" labelStep="" showNames="" showValues="" rotateNames="" showColumnShadow="1" animation="1" showAlternateHGridColor="0" AlternateHGridColor="ff5904" divLineColor="D0DCE4" divLineAlpha="100" alternateHGridAlpha="5"   formatNumberScale="0"  baseFontColor="666666" baseFont="宋体" baseFontSize="12" outCnvBaseFontSize="12"  canvasBorderThickness="1" canvasBorderColor="D0DCE4"  bgColor="FFFFFF" bgAlpha="0"  showBorder="0"  lineColor="0F6FCF" lineThickness="3"  anchorBorderColor="FFFFFF" anchorBorderThickness="2" anchorBgColor="0F6FCF"   numDivLines="2" numVDivLines="3"><categories>';
    	$fansCountSet='';
    	$imgRequryCountSet='';
    	$list_reverse=array_reverse($forward);
    	foreach ($forward as $li){
    		$day=$li['day'];
    		$xml.='<category label="'.$day.'"/>';
			$daywhere = array('month'=>$month,'year'=>$year,'day'=>$day);
			$daywhere['from_uid'] = array('neq',0);
			$daywhere['token'] = session('token');
    		$daycount = D('Forward')->where($daywhere)->count();
    		$forwardCountSet.='<set value="'.$daycount.'"/>';
    	}
    	$xml.='</categories><dataset seriesName="转发数" color="1D8BD1" anchorBorderColor="1D8BD1" anchorBgColor="1D8BD1">'.$forwardCountSet.'</dataset><styles><definition><style name="CaptionFont" type="font" size="12"/></definition><application><apply toObject="CAPTION" styles="CaptionFont"/><apply toObject="SUBCAPTION" styles="CaptionFont"/></application></styles></chart>';
    	$this->assign('xml',$xml);
    	$this->assign('forward',$forward);
    	$this->display();
    }
}
	?>