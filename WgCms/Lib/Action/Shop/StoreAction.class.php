<?php
class StoreAction extends ShopAction{
	public $token;
	public $product_model;
	public $product_cat_model;
	public $level_cat_id;
	public function _initialize() {
		parent::_initialize();
		$this->assign('isDining', 0);
		$this->level_cat_id = 2;
		$this->assign('level_cat_id',$this->level_cat_id);
	}
	
	/**
	 * 分类列表
	 */
	public function index() {


		$parentid = isset($_GET['parentid']) ? intval($_GET['parentid']) : 0;
		$data  = M('Product_cat');
		$where = array('token' => session('token'), 
						 'cid' => $this->_cid, 
						 );

		$sid = session('sid');
		$where['sid'] = $sid;

        $count = $data->where($where)->count();
        $Page  = new Page($count,20);
        $show  = $Page->show();
        $list  = $data->where($where)
        			  ->order("sort DESC, id ASC")
        			  ->limit($Page->firstRow.','.$Page->listRows)
        			  ->select();
        	
		$this->assign('page',$show);		
		$this->assign('list',$list);
		$this->display();
	}

	public function catAdd(){

		$parentid = isset($_REQUEST['parentid']) ? intval($_REQUEST['parentid']) : 0;

		if ($parentid) {
			$checkdata = M('Product_cat')->where(array('id' => $parentid, 'cid' => $this->_cid))->find();
			$this->assign('parentname', $checkdata['name']);
		}else{
			$list = M('Product_cat')->where(array('parentid' => 0, 'cid' => $this->_cid))->select();
			$this->assign('list', $list);
		}
		if (IS_POST) {
			if($_POST['pc_show']){
				$database_pc_product_category = D('Pc_product_category');
				$data_pc_product_category['cat_name'] = $_POST['name'];
				$data_pc_product_category['token'] = session('token');
				$_POST['pc_cat_id'] = $database_pc_product_category->data($data_pc_product_category)->add();
			}
			
			$_POST['isfinal'] = 0;
			$_POST['time'] = time();
			$_POST['token'] = session('token');
			$_POST['sid'] = session('sid');
			D('Product_cat')->create();
			if (D('Product_cat')->add()) {
				D('Product_cat')->where(array('id' => $_POST['parentid']))->save(array('isfinal' => 2));
				$this->success('操作成功', U('Store/index', array('token' => session('token'), 'cid' => $this->_cid, 'parentid' => $parentid)));
			}
		} else {
			$parentid = isset($_GET['parentid']) ? intval($_GET['parentid']) : 0;
			if ($parentid) {
				$checkdata = M('Product_cat')->where(array('id' => $parentid, 'cid' => $this->_cid))->find();
				$this->assign('parentname', $checkdata['name']);
			}
			$this->assign('parentid', $parentid);
			
			
			$queryname=M('token_open')->where(array('token'=>$this->token))->getField('queryname');
			if(strpos(strtolower($queryname),strtolower('website')) !== false){
				$this->assign('has_website',true);
			}
			
			$this->display('catSet');
		}
	}
	
	/**
	 * 分类修改
	 */
	public function catSet(){

        $id = $this->_get('id'); 

		$checkdata = M('Product_cat')->where(array('id'=>$id))->find();

		if(empty($checkdata)){
            $this->error("没有相应记录.您现在可以添加.",U('Store/catAdd'));
        }
			session('token','mhfcjx1421158741');

		if (IS_POST) {

            $data  = D('Product_cat');

            $where = array('id'=>$this->_post('id'),'token'=>session('token'));
			$check = $data->where($where)->find();

			if($check==false)$this->error('非法操作');

			if($data->create()){

				if($data->where($where)->save()){

					if (!$this->isDining){
						$this->success('操作成功',U('Store/index',array('token'=>session('token'),'parentid'=>$this->_post('parentid'))));
					}else {
						$this->success('操作成功',U('Store/index',array('token'=>session('token'),'parentid'=>$this->_post('parentid'),'dining'=>1)));
					}
					
				}else{
					$this->error('操作失败');
				}
			}else{
				$this->error($data->getError());
			}
		}else{ 
			$this->assign('parentid',$checkdata['parentid']);
			$this->assign('set',$checkdata);
			$this->display();	
		
		}
	}
	
	/**
	 * 删除分类
	 */
	public function catDel() {

		//if($this->_get('token')!=session('token')){$this->error('非法操作');}
        $id = $this->_get('id');
        if(IS_GET){                              
            $where=array('id'=>$id,'token'=>session('token'));
            $data=M('Product_cat');
            $check=$data->where($where)->find();
            if($check==false)   $this->error('非法操作');
            $product_model=M('Product');
            $productsOfCat=$product_model->where(array('catid'=>$id))->select;
            if (count($productsOfCat)){
            	$this->error('本分类下有商品，请删除商品后再删除分类',U('Store/index',array('token'=>session('token'),'dining'=>$this->isDining)));
            }
            $back=$data->where($where)->delete();
            if($back==true){
            	if (!$this->isDining){
                $this->success('操作成功',U('Store/index',array('token'=>session('token'),'parentid'=>$check['parentid'])));
            	}else {
            		$this->success('操作成功',U('Store/index',array('token'=>session('token'),'parentid'=>$check['parentid'],'dining'=>1)));
            	}
            }else{
                 $this->error('服务器繁忙,请稍后再试',U('Store/index',array('token'=>session('token'))));
            }
        }        
	}
	
	/**
	 * 分类属性列表
	 */
	public function norms() {
		$type = isset($_GET['type']) ? intval($_GET['type']) : 0;
		$catid = intval($_GET['catid']);
		if($checkdata = M('Product_cat')->where(array('id' => $catid, 'token' => session('token')))->find()){
			$this->assign('catData', $checkdata);
        } else {
        	$this->error("没有选择相应的分类.",U('Store/index'));
        }
        
		$data = M('Norms');
		$where = array('catid' => $catid, 'type' => $type);
		$count      = $data->where($where)->count();
		$Page       = new Page($count,20);
		$show       = $Page->show();
		$list = $data->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('page', $show);		
		$this->assign('list', $list);
		$this->assign('catid', $catid);
		$this->assign('type', $type);
		$this->display();		
	}
	
	/**
	 * 分类规格的操作
	 */
	public function normsAdd() {
		$type = intval($_REQUEST['type']) ? intval($_REQUEST['type']) : 0;
		if($data = M('Product_cat')->where(array('id' => $this->_get('catid'), 'token' => session('token')))->find()){
			$this->assign('catData', $data);
        } else {
        	$this->error("没有选择相应的分类.", U('Store/index'));
        }
		if (IS_POST) { 
            $data = D('Norms');
            $id = intval($this->_post('id'));
            if ($id) {
	            $where = array('id' => $id, 'type' => $type, 'catid' => $this->_get('catid'));
				$check = $data->where($where)->find();
				if ($check == false) $this->error('非法操作');
            }
			if ($data->create()) {
				if ($id) {
					if ($data->where($where)->save($_POST)) {
						$this->success('修改成功', U('Store/norms',array('token' => session('token'), 'catid' => $this->_post('catid'), 'type' => $type)));
					} else {
						$this->error('操作失败');
					}
				} else {
					if ($data->add($_POST)) {
						$this->success('添加成功', U('Store/norms',array('token' => session('token'), 'catid' => $this->_post('catid'), 'type' => $type)));
					} else {
						$this->error('操作失败');
					}
				}
			} else {
				$this->error($data->getError());
			}
		} else { 
			$data = M('Norms')->where(array('id' => $this->_get('id'), 'type' => $type, 'catid' => $this->_get('catid')))->find();
			//print_r($data);die;
			$this->assign('catid', $this->_get('catid'));
			$this->assign('type', $type);
			$this->assign('token', session('token'));
			$this->assign('set', $data);
			$this->display();	
		}
	}
	
	/**
	 *属性的删除 
	 */
	public function normsDel() {
		if($this->_get('token') != session('token')){$this->error('非法操作');}
        $id = intval($this->_get('id'));
        $catid = intval($this->_get('catid'));
        $type = intval($this->_get('type'));
        if(IS_GET){                              
            $where = array('id' => $id, 'type' => $type, 'catid' => $catid);
            $data = M('Norms');
            $check = $data->where($where)->find();
            if($check == false) $this->error('非法操作');
            if ($back = $data->where($where)->delete()) {
            	$this->success('操作成功',U('Store/norms', array('type' => $type, 'catid' => $check['catid'])));
            } else {
				$this->error('服务器繁忙,请稍后再试',U('Store/norms', array('type' => $type, 'catid' => $check['catid'])));
            }
        }        
	}
	
	/**
	 * 分类属性列表
	 */
	public function attributes() {
		$catid = intval($_GET['catid']);
		if($checkdata = M('Product_cat')->where(array('id' => $catid, 'token' => session('token')))->find()){
			$this->assign('catData', $checkdata);
        } else {
        	$this->error("没有选择相应的分类.",U('Store/index'));
        }
		$data  = M('Attribute');
		$where = array('catid' => $catid, 'token' => session('token'));
		$count      = $data->where($where)->count();
		$Page       = new Page($count,20);
		$show       = $Page->show();
		$list = $data->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('page', $show);		
		$this->assign('list', $list);
		$this->assign('catid', $catid);
		$this->display();		
	}
	
	/**
	 * 分类属性的操作
	 */
	public function attributeAdd() {
		if($checkdata = M('Product_cat')->where(array('id' => $this->_get('catid'), 'token' => session('token')))->find()){
			$this->assign('catData', $checkdata);
        } else {
        	$this->error("没有选择相应的分类.", U('Store/index'));
        }
		if (IS_POST) { 
            $data = D('Attribute');
            $id = intval($this->_post('id'));
            $catid = intval($this->_post('catid'));
            if ($id) {
	            $where = array('id' => $id, 'token' => session('token'), 'catid' => $catid);
				$check = $data->where($where)->find();
				if ($check == false) $this->error('非法操作');
            }
			if ($data->create()) {
				if ($id) {
					if ($data->where($where)->save($_POST)) {
						$this->success('修改成功', U('Store/attributes',array('token' => session('token'), 'catid' => $this->_post('catid'))));
					} else {
						$this->error('操作失败');
					}
				} else {
					if ($data->add($_POST)) {
						$this->success('添加成功', U('Store/attributes',array('token' => session('token'), 'catid' => $this->_post('catid'))));
					} else {
						$this->error('操作失败');
					}
				}
			} else {
				$this->error($data->getError());
			}
		} else { 
			$data = M('Attribute')->where(array('id' => $this->_get('id'), 'token' => session('token'), 'catid' => $this->_get('catid')))->find();
			$this->assign('catid', $this->_get('catid'));
			$this->assign('token', session('token'));
			$this->assign('set', $data);
			$this->display();	
		}
	}
	
	/**
	 *属性的删除 
	 */
	public function attributeDel() {
		if($this->_get('token') != session('token')){$this->error('非法操作');}
        $id = intval($this->_get('id'));
        $catid = intval($this->_get('catid'));
        if(IS_GET){                              
            $where = array('id' => $id, 'token' => session('token'), 'catid' => $catid);
            $data = M('Attribute');
            $check = $data->where($where)->find();
            if($check == false) $this->error('非法操作');
            if ($back = $data->where($where)->delete()) {
            	$this->success('操作成功',U('Store/attributes', array('token' => session('token'), 'catid' => $catid)));
            } else {
				$this->error('服务器繁忙,请稍后再试',U('Store/attributes', array('token' => session('token'), 'catid' => $catid)));
            }
        }        
	}
	
	/**
	 * 商品列表
	 */
	public function product() {	

		$catid = intval($_GET['catid']);
		$product_model = M('Product');
		$product_cat_model = M('Product_cat');
		if ($catid){
			$where['catid'] = $catid;
		}
        if(IS_POST){
            $key = $this->_post('searchkey');
            if(empty($key)){
                $this->error("关键词不能为空");
            }

            $map['token'] = $this->get('token'); 
            $map['name|intro|keyword'] = array('like',"%$key%"); 
            $list = $product_model->where($map)->select(); 
            $count      = $product_model->where($map)->count();       
            $Page       = new Page($count,20);
        	$show       = $Page->show();
        } else {
        	$count      = $product_model->where($where)->count();
        	$Page       = new Page($count,20);
        	$show       = $Page->show();
        	$list = $product_model->where($where)->order('sort desc,id asc')->limit($Page->firstRow.','.$Page->listRows)->select();
        }

		$this->assign('page',$show);		
		$this->assign('list',$list);
		$this->assign('token',session('token'));
		$this->assign('isProductPage',1);
		$this->assign('catid', $catid);
		$this->display();		
	}
	
	/**
	 * 添加商品
	 */
	public function addNew() {

		$catid = intval($_GET['catid']);
		$id    = intval($_GET['id']);

		if($productCatData = M('Product_cat')
			->where(array('id' => $catid, 'token' => session('token')))
			->find()){
			$this->assign('catData', $productCatData);
        } else {
        	$this->error("没有选择相应的分类.", U('Store/index'));
        }
        //产品的规格
        $normsData = M("Norms")->where(array('catid' => $catid))->select();
        $colorData = $formatData = array();

        foreach ($normsData as $row) {
        	if ($row['type']) {
        		$colorData[] = $row;
        	} else {
        		$formatData[] = $row;
        	}
        	$normsList[$row['id']] = $row['value'];
        }

        $attributeData = array();

        if ($id && ($product = M('Product')->where(array('catid' => $catid, 'token' => session('token'), 'id' => $id))->find())) 
        {
        	$attributeData = M("Product_attribute")->where(array('pid' => $id))->select();
        	$productDetailData = M("Product_detail")->where(array('pid' => $id))->select();
        	$productimage = M("Product_image")->where(array('pid' => $id))->order('id asc')->select();
        	$colorList = $formatList = $pData = array();

        	foreach ($productDetailData as $p) {
        		if($normsList[$p['format']] || $normsList[$p['color']]){

	        		$p['formatName'] = $normsList[$p['format']] == ''? '':$normsList[$p['format']];
	        		$p['colorName'] = $normsList[$p['color']] == ''? '':$normsList[$p['color']];
	        		$formatList[] = $p['format'];
	        		$colorList[] = $p['color'];
	        		$pData[] = $p;
        		}
        	}
        	//显示背景颜色信息
        	if($product['colorinfo']){
        		$product['colorinfo'] = json_decode($product['colorinfo'],true);
        	}
        	//显示艺术照信息
        	if($product['artinfo']){
        		$product['artinfo'] = json_decode($product['artinfo'],true);
        	}
        	//显示艺术照升级体验
        	if($product['artex']){
        		$product['artex'] = json_decode($product['artex'],true);
        	}
        	$this->assign('set', $product);
        	$this->assign('formatList', $formatList);
        	$this->assign('colorList', $colorList);
        	$this->assign('imageList', $productimage);
        }
        $array = array();
        if ($attributeData) {
	        foreach ($attributeData as $row) {
	        	$array[$row['aid']] = $row;
	        }
        }
		$data = M("Attribute")->where(array('catid' => $catid))->select();
        $attributeData = array();
        foreach ($data as $row) {
        	if (isset($array[$row['id']])) {
        		$attributeData[] = $array[$row['id']];
        		unset($array[$row['id']]);
        	} else {
	        	$row['aid'] = $row['id'];
	        	$row['id'] = 0;
	        	$attributeData[] = $row;
        	}
        }
        if ($array) {
        	$ids = array();
        	foreach ($array as $v) {
        		$ids[] = $v['id'];
        	}
        	M('Product_attribute')->where(array('id' => array('in', $ids)))->delete();
        }
        //等级商品
        if($catid == $this->level_cat_id){
        	$levels = M('Distribution_level')->where(array('lid'=>array('gt',3)))->select();
        	$this->assign('levels',$levels);
        }
        $CatList = M('Product_cat')->where(array('token' => session('token')))->select();
        $this->assign('token',$this->token);
		$this->assign('CatList', $CatList);
		$this->assign('color', $this->color);
		$this->assign('attributeData', $attributeData);
		$this->assign('normsData', $normsData);
		$this->assign('colorData', $colorData);
		$this->assign('formatData', $formatData);
		$this->assign('productCatData', $productCatData);
		$this->assign('productDetailData', $pData);
		$this->assign('pdataJson', json_encode($pData));
		$this->assign('catid', $catid);
		$this->display('set_new');
	}
	/*
	 * 商品规格
	 */
	public function productAttr($cids,$fids){
		if(is_string($cids)){
			$cid_arr = array_filter(explode(',', rtrim($cids,',')));
			$formatName = M('Norms')->where('id='.$fids)->getField('value');
			foreach ($cid_arr as $k => $v) {
				$data = array(
					'colorName' => M('Norms')->where('id='.$v)->getField('value'),
					'formatName' => $formatName,
				);
				array_push($norms_arr, $data);
				$this->assign('list',$norms_arr);
			}
		}else if(is_string($fids)){
			$fid_arr = array_filter(explode(',', rtrim($cids,',')));
			$colorName = M('Norms')->where('id='.$cids)->getField('value');
			foreach ($fid_arr as $k => $v) {
				$data = array(
					'formatName' => M('Norms')->where('id='.$v)->getField('value'),
					'colorName' => $formatName,
				);
				array_push($norms_arr, $data);
				$this->assign('list',$norms_arr);
			}
		}
	}
	/**
	 * 增加商品
	 */
	public function productSave() {

		/*if ($_POST['token'] != session('token')) {
			exit(json_encode(array('error_code' => true, 'msg' => '不合法的数据')));
		}*/

		$product = M('Product');
		$_POST['time'] = time();
		if ($product->create() === false) {
		    exit(json_encode(array('error_code' => false, 'msg' => $product->getError())));
		}
		//判断颜色价格信息
		$colorinfo = htmlspecialchars_decode($_POST['colorinfo']);
		if (!empty($colorinfo) && $colorinfo != '{}') {
			$_POST['colorinfo'] = $colorinfo;
		}else{
			$_POST['colorinfo'] = '';
		}
		//艺术照信息
		$artinfo = htmlspecialchars_decode($_POST['artinfo']);
		if (!empty($artinfo) && $artinfo != '{}') {
			$_POST['artinfo'] = $artinfo;
		}else{
			$_POST['artinfo'] = '';
		}
		//艺术升级体验
		$artex = htmlspecialchars_decode($_POST['artex']);
		if (!empty($artex) && $artex != '{}') {
			$_POST['artex'] = $artex;
		}else{
			$_POST['artex'] = '';
		}
		//判断更新还是增加
		//UPdata
		if ($_POST['id'] && $obj = $product->where(array('id' => $_POST['id'], 'token' => $_POST['token']))->find()) {
			//判断有没更换类别
			$oldcatid = $_POST['oldcatid'];
			$catid    = $_POST['catid'];

			//dump($obj);
			if($oldcatid && $oldcatid != $catid && !$_POST['lid']){
				M('Product_detail')->where(array('pid'=>$_POST['id']))->setField('pid',$oldcatid);
			}
			//dump($_POST);
			//exit();
			$pid = $product->save($_POST);
		} else {
			//增加商品
			$pid = $product->add($_POST);
		}

		if (empty($pid) && !$_POST['id']) {
			exit(json_encode(array('error_code' => false, 'msg' => '商品添加出错了')));
		}


		//判断是否是升级商品
		if($_POST['lid']){
			$level_pid = $_POST['id']?$_POST['id']:$pid;
			if(M('Distribution_level')->where(array('pid'=>$level_pid))->find()){
				M('Distribution_level')->where(array('pid'=>$level_pid))->setField('pid',0);
			}
			M('Distribution_level')->where(array('lid'=>$_POST['lid']))->setField('pid',$level_pid);
		}
		//修改规格
		$norms = htmlspecialchars_decode($_POST['norms']);
		if (!empty($norms)) {
			$product_detail = M('Product_detail');
			$norms = json_decode($norms, true);
			if($_POST['id']){
				$pid = $_POST['id'];
			}
			$detailList = $product_detail->field('id')->where(array('pid' => $pid))->select();
			$oldDetailId = array();
			foreach ($detailList as $val) {
				$oldDetailId[$val['id']] = $val['id'];
			}
			foreach ($norms as $row) {
				$data_d = array('format' => $row['format'], 'color' => $row['color'], 'num' => $row['num'], 'price' => $row['price']);
				if ($row['id']) {
					unset($oldDetailId[$row['id']]);
					$data_d['pid'] = $row['pid'];
					$product_detail->where(array('id' => $row['id'], 'pid' => $row['pid']))->save($data_d);
				} else {
					$data_d['pid'] = $pid;
					$product_detail->add($data_d);
				}
			}
			// 删除details
			foreach ($oldDetailId as $id) {
				$product_detail->where(array('id' => $id, 'pid' => $pid))->delete();
			}
		}
		//修改图片
		$images = $_POST['images'];
		$imagesid = $_POST['imagesid'];
		if (!empty($images)) {
			$product_image = M('Product_image');
			$images_arr = array_filter(explode(',', rtrim($images,',')));
			$imagesid_arr = array_filter(explode(',', rtrim($imagesid,',')));
			if(!$_POST['id']){//新增
				foreach ($images_arr as $k => $v) {
					$data_d = array('pid' => $pid, 'image' => $v);
					$product_image->add($data_d);
				}
			}else{//修改
				if(count($images_arr) != 0 && count($imagesid_arr) ==0){
					foreach ($images_arr as $k => $v) {
						$data_d = array('pid' => $pid, 'image' => $v);
						$product_image->add($data_d);
					}
				}else if($_POST['id'] && count($imagesid_arr) !=0){
					foreach ($images_arr as $k => $v) {
						if($imagesid_arr[$k]){
							$product_image->where('id='.$imagesid_arr[$k])->setField('image',$images_arr[$k]);
						}else{
							$data_d = array('pid' => $_POST['id'], 'image' => $v);
							$addid = $product_image->add($data_d);
							$imagesid .= $addid.',';
						}
					}
					$condition = array(
						'id' => array('not in',rtrim($imagesid,',')),
						'pid' => $_POST['id'],
					);
					$product_image->where($condition)->delete();
				}
			}
		}else{
			if($_POST['id']){
				M('Product_image')->where('pid='.$_POST['id'])->delete();
			}
		}
		exit(json_encode(array('error_code' => false, 'msg' => '商品操作成功')));
	}
	
	/**
	 * 删除商品
	 */
	public function del(){

		$product_model=M('Product');
        $id = $this->_get('id');
        if(IS_GET){                              
            $where=array('id'=>$id);
            $check=$product_model->where($where)->find();
            if($check==false)   $this->error('非法操作');

            $back=$product_model->where($where)->delete();
            if($back==true){
            	$keyword_model=M('Keyword');
            	$keyword_model->where(array('token'=>session('token'),'pid'=>$id,'module'=>'Product'))->delete();
            	//删除规格
            	M('Product_detail')->where('pid='.$id)->delete();
            	//删除图片
            	M('Product_image')->where('pid='.$id)->delete();
                $this->success('操作成功');
            }else{
                 $this->error('服务器繁忙,请稍后再试');
            }
        }        
	}
	
	public function orders()
	{
		$product_cart_model = M('product_cart');
/*		$where = array('token' => $this->_session('token'), 'groupon' => 0, 'dining' => 0);

*/		
		$where['storeid'] = session('sid');
		if (IS_POST) {
	/*		if ($_POST['token'] != $this->_session('token')) {
				exit();
			}*/
			$handleOrder = $this->_post('handleOrder');
			if (!$handleOrder) {
				$key = $this->_post('searchkey');
				$where['truename|address|orderid'] = array('like', "%$key%");
				if($this->_post('paid')!=''){
					$where['paid'] = $this->_post('paid');
				}
				if($this->_post('sent')!=''){
					$where['sent'] = $this->_post('sent');
				}
				if($this->_post('receive')!=''){
					$where['receive'] = $this->_post('receive');
				}
				if($this->_post('handled')!=''){
					$where['handled'] = $this->_post('handled');
				}
			} else {
				for ($i=0;$i<40;$i++){
					if (isset($_POST['id_'.$i])){
						$thiCartInfo=$product_cart_model->where(array('id'=>intval($_POST['id_'.$i])))->find();
						if ($thiCartInfo['handled']){
							$product_cart_model->where(array('id'=>intval($_POST['id_'.$i])))->save(array('handled'=>0));
						} else {
							$product_cart_model->where(array('id'=>intval($_POST['id_'.$i])))->save(array('handled'=>1));
							$this->distriOrderStatus($this->_session('token'),$thiCartInfo['id'],4);
						}
					}
				}
				$this->success('操作成功',U('Store/orders',array('token' => session('token'))));
				die;
			}
		}
		if (isset($_GET['handled'])) {
			$where['handled'] = intval($_GET['handled']);
		}
		$count      = $product_cart_model->where($where)->count();
		$Page       = new Page($count,20);
		$show       = $Page->show();
		$orders		= $product_cart_model->where($where)->order('time DESC')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		$unHandledCount = $product_cart_model->where(array('token' => $this->_session('token'), 'handled' => 0))->count();
		$this->assign('unhandledCount', $unHandledCount);
		$this->assign('orders', $orders);
		$this->assign('page', $show);
		$this->display();
	}
	public function printorder(){
		$thisOrder = M('product_cart')->where(array('id'=>intval($_GET['id'])))->find();
		$this->assign('thisOrder',$thisOrder);
		$list = unserialize($thisOrder['info']);
		$this->assign('products', $list);
		$this->display();
	}
	public function orderInfo() {
		$this->product_model = M('Product');
		$this->product_cat_model = M('Product_cat');
		$product_cart_model = M('product_cart');
		$thisOrder = $product_cart_model->where(array('id'=>intval($_GET['id'])))->find();
		//检查权限
		if (strtolower($thisOrder['token'])!=strtolower($this->_session('token'))){
			exit();
		}
		if (IS_POST){
			/*if (intval($_POST['sent'])){
				$_POST['handled']=1;
			}*/
			if(intval($_POST['paid'])==1){//已付款/未发货
				$status = 1;
			}
			if(intval($_POST['sent'])==1){//已发货/未收货
				$status = 2;
			}
			if(intval($_POST['receive'])==1){//已收货
				$status = 3;
			}
			$product_cart_model->where(array('orderid'=>$thisOrder['orderid']))->save(array('sent'=>intval($_POST['sent']),'paid'=>intval($_POST['paid']),'receive'=>intval($_POST['receive']),'handled'=>intval($_POST['handled']),'returnMoney'=>intval($_POST['returnMoney']),'logistics'=>$_POST['logistics'],'logisticsid'=>$_POST['logisticsid']));
			//TODO 发货的短信提醒
			if ($_POST['sent']) {
				$company = D('Company')->where(array('token' => $thisOrder['token'], 'isbranch' => 0))->find();
				$userInfo = M('Distribution_member')->where(array('token' => $thisOrder['token'], 'wecha_id' => $thisOrder['wecha_id']))->find();
				Sms::sendSms($this->token, "您在{$company['name']}商城购买的商品，商家已经给您发货了，请您注意查收", $thisOrder['tel']);
			}
			
			$this->distriOrderStatus($thisOrder['token'],$thisOrder['id'],$status);
			$this->success('修改成功',U('Store/orderInfo',array('token'=>session('token'),'id'=>$thisOrder['id'])));
		}else {
			//订餐信息
			// $product_diningtable_model = M('product_diningtable');
			// if ($thisOrder['tableid']) {
			// 	$thisTable=$product_diningtable_model->where(array('id'=>$thisOrder['tableid']))->find();
			// 	$thisOrder['tableName']=$thisTable['name'];
			// }
			//判断有没操作权限
			if($thisOrder['bindaid'] == 0){
				$this->assign('cancontro',1);
			}
			$this->assign('thisOrder',$thisOrder);
			//订单照片
			$cart_pics = M('Cart_pics')->where('oid='.$thisOrder['id'])->select();
			$this->assign('cart_pics',$cart_pics);
			$this->assign('thisOrder',$thisOrder);
			// $carts=unserialize($thisOrder['info']);
			if($thisOrder['classid']){
				import ( "@.Org.TypeFile" );
				$tid = $thisOrder['classid'];
				$TypeFile = new TypeFile ( 'ClassCity' ); //实例化分类类
				$result = $TypeFile->getPathName ( $tid ); //获取分类路径
				$this->assign ( 'typeNumArr', $result );
			}

			$list = unserialize($thisOrder['info']);
//			print_r($list);die;
			$this->assign('products', $list);
			//
			//
			$this->display();
		}
	}
	/**
	 * distriOrderStatus
	 */
	private function distriOrderStatus($token,$order_id,$status) {
		$condition['order_id'] = $order_id;
		$condition['token'] = $token;
		if($status==4){//用户累加
			$condition['status'] = array('neq',4);
			$orders = M('Distribution_ordermoney')->where($condition)->select();
			$db = M('Distribution_member');
			foreach($orders as $key=>$value){
				if($db->where('id='.$value['mid'])->find()){
					$db->where('id='.$value['mid'])->setInc('totalMoney',$value['orderMoney']);//消费总额
					$db->where('id='.$value['mid'])->setInc('totalGetMoney',$value['offerMoney']);//贡献总额
				}
				$data['status'] = $status;
				M('Distribution_ordermoney')->where('id='.$value['id'])->save($data);
			}
		}else{
			$data['status'] = $status;
			$db = M('Distribution_ordermoney');
			$db->where($condition)->save($data);
		}
	}
	/**
	 * 计算一次购物的总的价格与数量
	 * @param array $carts
	 */
	public function getCat($carts)
	{
		if (empty($carts)) {
			return array();
		}
		//邮费
		$mailPrice = 0;
		//商品的IDS
		$pids = array_keys($carts);
		
		//商品分类IDS
		$productList = $cartIds = array();
		if (empty($pids)) {
			return array(array(), array(), array());
		}
		
		$productdata = $this->product_model->where(array('id'=> array('in', $pids)))->select();
		foreach ($productdata as $p) {
			if (!in_array($p['catid'], $cartIds)) {
				$cartIds[] = $p['catid'];
			}
			$mailPrice = max($mailPrice, $p['mailprice']);
			$productList[$p['id']] = $p;
		}
	
		//商品规格参数值
		$catlist = $norms = array();
		if ($cartIds) {
			$normsdata = M('norms')->where(array('catid' => array('in', $cartIds)))->select();
			foreach ($normsdata as $r) {
				$norms[$r['id']] = $r['value'];
			}
			//商品分类
			$catdata = M('Product_cat')-> where(array('id' => array('in', $cartIds)))->select();
			foreach ($catdata as $cat) {
				$catlist[$cat['id']] = $cat;
			}
		}
		$dids = array();
		foreach ($carts as $pid => $rowset) {
			if (is_array($rowset)) {
				$dids = array_merge($dids, array_keys($rowset));
			}
		}
		//商品的详细
		$data = array();
		if ($dids) {
			$dids = array_unique($dids);
			$detail = M('Product_detail')->where(array('id'=> array('in', $dids)))->select();
			foreach ($detail as $row) {
				$row['colorName'] = isset($norms[$row['color']]) ? $norms[$row['color']] : '';
				$row['formatName'] = isset($norms[$row['format']]) ? $norms[$row['format']] : '';
				$row['count'] = isset($carts[$row['pid']][$row['id']]['count']) ? $carts[$row['pid']][$row['id']]['count'] : 0;
				$productList[$row['pid']]['detail'][] = $row;
				$data[$row['pid']]['total'] = isset($data[$row['pid']]['total']) ? intval($data[$row['pid']]['total'] + $row['count']) : $row['count'];
				$data[$row['pid']]['totalPrice'] = isset($data[$row['pid']]['totalPrice']) ? intval($data[$row['pid']]['totalPrice'] + $row['count'] * $row['price']) : $row['count'] * $row['price'];//array('total' => $totalCount, 'totalPrice' => $totalFee);
			}
		}
		//商品的详细列表
		$list = array();
		foreach ($productList as $pid => $row) {
			if (!isset($data[$pid]['total'])) {
				$data[$pid] = array();
				$row['count'] = $data[$pid]['total'] = isset($carts[$pid]['count']) ? $carts[$pid]['count'] : (isset($carts[$pid]) && is_int($carts[$pid]) ? $carts[$pid] : 0);
				$data[$pid]['totalPrice'] = $data[$pid]['total'] * $row['price'];
			}
			$row['formatTitle'] =  isset($catlist[$row['catid']]['norms']) ? $catlist[$row['catid']]['norms'] : '';
			$row['colorTitle'] =  isset($catlist[$row['catid']]['color']) ? $catlist[$row['catid']]['color'] : '';
			$list[] = $row;
		}
		return array($list, $data, $mailPrice);
		die;
		if (empty($carts)) {
			return array();
		}
		$pdata = $data = $list = $ids = $detail_list = $products = array();
		$mailPrice = 0;
		foreach ($carts as $pid => $rowset) {
			$totalCount = $totalFee = 0;
			$tmp = $this->product_model->where(array('id'=> $pid))->find();
			if (is_array($rowset)) {
				$norms = $cntArray = $dids = array();
				foreach ($rowset as $did => $count) {
					if (!in_array($did, $dids)){
						array_push($dids, $did);
						$cntArray[$did] = $count['count'];
					}
					$totalCount += $count['count'];
				}
				$normsdata = M('norms')->where(array('catid' => $tmp['catid']))->select();
				foreach ($normsdata as $r) {
					$norms[$r['id']] = $r['value'];
				}
				if ($dids) {
					$temp = M('Product_detail')->where(array('id'=> array('in', $dids), 'pid' => $pid))->select();
					foreach ($temp as $row) {
						if (isset($rowset[$row['id']])) {
							$row['colorName'] = isset($norms[$row['color']]) ? $norms[$row['color']] : '';
							$row['formatName'] = isset($norms[$row['format']]) ? $norms[$row['format']] : '';
							$row['count'] = $cntArray[$row['id']];
							$totalFee += $cntArray[$row['id']] * $row['price'];
							$detail_list[$pid][] = $row;
						}
					}
				}
			} else {
				$totalCount += $rowset;
				$totalFee += $rowset * $tmp['price'];
				$pdata[$pid] = $rowset;
			}
			$mailPrice = max($mailPrice, $tmp['mailprice']);
			$data[$pid] = array('total' => $totalCount, 'totalPrice' => $totalFee);
		}
		
		$ids = array_keys($carts);
		if (count($ids)) {
			$tmp = $this->product_model->where(array('id'=>array('in', $ids)))->select();
			foreach ($tmp as $row) {
				if (isset($detail_list[$row['id']])) {
					if ($catData = M('Product_cat')-> where(array('id' => $row['catid']))->find()) {
						$row['formatTitle'] =  $catData['norms'];
						$row['colorTitle'] =  $catData['color'];
					}
					$row['detail'] =  $detail_list[$row['id']];
				} else {
					$row['detail'] = '';
					$row['count'] = $pdata[$row['id']];
				}
				$list[] = $row;
			}
		}
		return array($list, $data, $mailPrice);
	
		$list = $ids = $detail_list = $products = array();
		$carts = empty($carts) ? $this->_getCart() : $carts;
		$pdata = $data = array();
		$mailPrice = 0;
		foreach ($carts as $pid => $rowset) {
			$totalCount = $totalFee = 0;
			$tmp = $this->product_model->where(array('id'=> $pid))->find();
			if (is_array($rowset)) {
				$norms = $cntArray = $dids = array();
				foreach ($rowset as $did => $count) {
					if (!in_array($did, $dids)){
						array_push($dids, $did);
						$cntArray[$did] = $count['count'];
					}
					$totalCount += $count['count'];
				}
				$normsdata = M('norms')->where(array('catid' => $tmp['catid']))->select();
				foreach ($normsdata as $r) {
					$norms[$r['id']] = $r['value'];
				}
				if ($dids) {
					$temp = M('Product_detail')->where(array('id'=> array('in', $dids), 'pid' => $pid))->select();
					foreach ($temp as $row) {
						if (isset($rowset[$row['id']])) {
							$row['colorName'] = isset($norms[$row['color']]) ? $norms[$row['color']] : '';
							$row['formatName'] = isset($norms[$row['format']]) ? $norms[$row['format']] : '';
							$row['count'] = $cntArray[$row['id']];
							$totalFee += $cntArray[$row['id']] * $row['price'];
							$detail_list[$pid][] = $row;
						}
					}
				}
			} else {
				$totalCount += $rowset;
				$totalFee += $rowset * $tmp['price'];
				$pdata[$pid] = $rowset;
			}
			$mailPrice = max($mailPrice, $tmp['mailprice']);
			$data[$pid] = array('total' => $totalCount, 'totalPrice' => $totalFee);
		}
		$ids = array_keys($carts);
		if (count($ids)) {
			$tmp = $this->product_model->where(array('id'=>array('in', $ids)))->select();
			foreach ($tmp as $row) {
				if (isset($detail_list[$row['id']])) {
					if ($catData = M('Product_cat')-> where(array('id' => $row['catid']))->find()) {
						$row['formatTitle'] = $catData['norms'];
						$row['colorTitle'] = $catData['color'];
					}
					$row['detail'] =  $detail_list[$row['id']];
				} else {
					$row['detail'] = '';
					$row['count'] = $pdata[$row['id']];
				}
				$list[] = $row;
			}
		}
		return array($list, $data, $mailPrice);
	}
	
	public function deleteOrder(){
		$product_model=M('product');
		$product_cart_model=M('product_cart');
		$product_cart_list_model=M('product_cart_list');
		$thisOrder=$product_cart_model->where(array('id'=>intval($_GET['id'])))->find();
		//检查权限
		$id=$thisOrder['id'];
		if ($thisOrder['token']!=$this->_session('token')){
			exit();
		}
		//
		//删除订单和订单列表
		$cart_list = $product_cart_model->where(array('orderid'=>$thisOrder['orderid']))->select();
		$product_cart_model->where(array('orderid'=>$thisOrder['orderid']))->delete();
		foreach ($cart_list as $k => $v) {
			$product_cart_list_model->where(array('cartid'=>$v['id']))->delete();
		}
		
		//商品销量做相应的减少
		if (empty($thisOrder['paid'])) {
			$carts = unserialize($thisOrder['info']);
			//还原库存
			foreach ($carts as $pid => $rowset) {
				$total = 0;
				if (is_array($rowset)) {
					foreach ($rowset as $did => $row) {
						M('product_detail')->where(array('id' => $did, 'pid' => $pid))->setInc('num', $row['count']);
						$total += $row['count'];
					}
				} else {
					$total = $rowset;
				}
				$product_model->where(array('id' => $pid))->setInc('num', $total);
				$product_model->where(array('id' => $pid))->setDec('salecount', $total);
				$product_model->where(array('id' => $pid))->setDec('fakemembercount', $total);
			}
//			foreach ($carts as $k => $c){
//				if (is_array($c)){
//					$productid=$k;
//					$price=$c['price'];
//					$count=$c['count'];
//					$product_model->where(array('id'=>$k))->setDec('salecount',$c['count']);
//				}
//			}
		}
		$this->success('操作成功',U('Store/orders', array('token' => session('token'))));
		//$this->success('操作成功',$_SERVER['HTTP_REFERER']);
	}

	/**
	 * 完成退款
	 */
	public function returnMoney(){
		$order = M('Product_cart');
		$id = $this->_get('id');
		$token = $this->_session('token');
		if($id){
			$cart = $order->where(array('id'=>$id,'token'=>$token,'returnMoney'=>1))->find();
			if($cart){
				if($cart['bindaid'] != 0){
					$this->error('没有权限操作该订单');
				}
				$data['returnMoney'] = 2;
				if($order->where(array('id'=>$id,'token'=>$token,'returnMoney'=>1))->save($data)){
					$product_cart = $order->where('id='.$id)->find();
					if($product_cart['beDistri']==1){//退款后取消分销
						$productData['distritime'] = 0;
						M('Distribution_member')->where(array('token'=>$token,'wecha_id'=>$product_cart['wecha_id']))->save($productData);
					}
					$moneyData['status'] = -1;
					M('Distribution_ordermoney')->where('order_id='.$id)->save($moneyData);//佣金退回
					/*$ordermoney = M('Distribution_ordermoney')->where('order_id='.$id)->select();//订单数返还
					foreach($ordermoney as $key=>$value){
						M('Distribution_member')->where('id='.$value['mid'])->setDec('orderNums');
					}*/
					// $this->returnCartOpration($cart['orderid'],0,2);
					$this->success('退款完成成功');
				}else{
					$this->error('退款完成失败');
				}
			}else{
				$this->error('异常访问');
			}
		}else{
			$this->error('异常操作');
		}
	}
	
	/**
	 * 商城设置
	 */
	public function setting(){
		$setting = M('Product_setting');
		$obj = $setting->where(array('token' => session('token')))->find();
		if (IS_POST) {
			if ($obj) {
				$t = $setting->where(array('token' => session('token')))->save($_POST);
				if ($t) {
					$this->success('修改成功',U('Store/index',array('token' => session('token'))));
				} else {
					$this->error('操作失败');
				}
			} else {
				$pid = $setting->add($_POST);
				if ($pid) {
					$this->success('增加成功',U('Store/index',array('token' => session('token'))));
				} else {
					$this->error('操作失败');
				}
			}
		} else {
			$this->assign('setting', $obj);
			$this->display();	
		}
	}
	
	public function comment()
	{
		$catid = intval($_GET['catid']);
		$pid = intval($_GET['pid']);
		$product_model = M('Product_comment');
		
		$where = array('token' => $this->token, 'pid' => $pid, 'isdelete' => 0);
		$count      = $product_model->where($where)->count();
		$Page       = new Page($count,20);
		$show       = $Page->show();
		$list = $product_model->where($where)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('page',$show);		
		$this->assign('list',$list);
		$this->assign('pid',$pid);
		$this->assign('catid', $catid);
		$this->display();	
		
	}
	
	/**
	 * 删除商品
	 */
	public function commentdel()
	{
		$catid = intval($_GET['catid']);
		$pid = intval($_GET['pid']);
		if($this->_get('token')!= session('token')){$this->error('非法操作');}
        $id = $this->_get('id');
        if (IS_GET) {
        	M('Product_comment')->where(array('id' => $id,'token' => session('token')))->save(array('isdelete' => 1));
			$this->success('操作成功', U('Store/comment',array('token'=>session('token'),'catid' => $catid,'pid' => $pid)));
        }        
	}

	public function departList(){

		$departModel = M('Store_list');

		if(IS_POST){
            $key = $this->_post('searchkey');
            	if(empty($key)){
                	$this->error("关键词不能为空");
        		}else{

        		$map['name|address'] = array('like',"%$key%"); 
        		$depart  	 = $departModel->where($map)->select(); 
        		$count 	     = $departModel->where($map)->count();       
        		$Page        = new Page($count,20);
        		$show        = $Page->show(); 
        		}  
        }else{ 

			$count  = $departModel->count();       
        	$Page   = new Page($count,10);
        	$show   = $Page->show();
        	$where['delete']  = 0; 
        	$depart = $departModel->where($where)->order('id asc')->limit($Page->firstRow.','.$Page->listRows)->select();
        
		}

		$this->assign('page',$show);
		$this->assign('list',$depart);
		$this->display();
	}

	public function departSet(){

		$set 	  = M('Store_list')->where('id='.$_GET['id'])->find();
		$city 	  = M('City_list');
		$cityName = $city->field('id,name,char')->select();
		$setCity  = $city->where('id='.$set['cid'])->find();
		$this->assign('city',$cityName);
		$this->assign('setcity',$setCity);
		//城市列表
		$char  ='';
		$str      = "SELECT * FROM `pigcms_city_list` ORDER BY binary CONVERT(`char` USING GBK) ASC";
		$cityList = M('City_list')->query($str);

		$city1 = $cityList;
		
		$str   ='';
		foreach ($city1 as $key => $value) {
			
			if($value['char']!=$char){
			
			$str.="<option>".$value['char']."</option>";
			$char = $value['char'];
			}

			$str.="<option value=".$value['id'].">&nbsp&nbsp".$value['name']."</option>";
		}
		$this->assign('str',$str);


		if(IS_POST){
			$_POST['password'] = md5($_POST['password']);
			$data = D('Store_list');
            $where= array('id'=>$this->_post('id'));
			$check= $data->where($where)->find();

			if($check==false)$this->error('非法操作');

			if($check){
				$worktime = split(",",$_POST['default_time']);
				$_POST['defaultWorkTime'] = serialize($worktime);
				if($data->where($where)->save($_POST)){
					
				$this->success('修改成功',U('Store/departList',array('token'=>session('token'),'parentid'=>$this->_post('parentid'))));
					
				}else{
					$this->error('操作失败1');
				}
			}else{
				$this->error($data->getError());
			}		
		}else{
		$set['defaultWorkTime'] = json_encode(unserialize($set['defaultWorkTime']));
		$this->assign('set',$set);
		$this->display();
		}
	}	

	public function departDel(){

		if($this->_get('token')!=session('token')){
			$this->error('非法操作');
		}

        $id = $this->_get('id');

        if(IS_GET){   

            $where['id'] = $id;

            $data  = M('Store_list');
            $check = $data->where($where)->find();
            if($check==false)   $this->error('非法操作');

            $back=$data->where($where)->data('delete=1')->save();

            if($back==true){

            	M('City_list')->where('id='.$check['cid'])->setDec('snums');
                $this->success('操作成功',U('Store/departList',array('token'=>session('token'),'parentid'=>$check['parentid'])));
            }else{
                 $this->error('服务器繁忙,请稍后再试',U('Store/departList',array('token'=>session('token'))));
            }
        }        		
	}

	public function picDisplay(){

		$Model = M('Product_show');

		$where['delete'] = 0;
		$count = $Model->where($where)->count();		
		$Page  = new Page($count,10);				
		$list  = $Model->order('id desc')->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
        $show  = $Page->show();
		$this->assign('page',$show);
		$this->assign('list',$list); 
		$this->display();
	}

	public function picEdit(){

		$id = $_GET['id'];
		$Model = M('Product_show');
		$find = $Model->where('id='.$id)->find();
		if(!$find){

			$this->error('非法操作！');
			exit();
		}
		//分类
		$classify = M('Product_show_classify')->select();
		$this->assign('classify',$classify);

		if(IS_POST){

			$data=D('Product_show');
            $where['id']= $_POST['id'];

			$check= $data->where($where)->find();

			if($check==false)$this->error('非法操作');

			if($check){

				if($data->where($where)->save($_POST)){
					$this->success('修改成功');
					
				}else{
					$this->error('操作失败');
				}
			}else{
				$this->error($data->getError());
			}		
		}else{
		$res = $Model->where('id='.$id)->find();
		//dump($res);
		$this->assign('set',$res);
		$this->display();			
		}

	}


	public function addDisplay(){
		//分类
		$classify = M('Product_show_classify')->select();
		$this->assign('classify',$classify);
		if(IS_POST){
			$this->insert('Product_show','/picDisplay');
		}else{

			$this->display('picEdit');
		}
	}

	public function picDel(){

		$this->display();
	}

	public function delDisplay(){
		$res = M('Product_show')->where('id='.$_GET['id'])->delete();
		if($res){
			$this->success('删除成功');
		} else{
			$this->error('删除失败');
		}
	}
	//批量上传图片
	public function fileUpload(){
		/**
		 * upload.php
		 *
		 * Copyright 2013, Moxiecode Systems AB
		 * Released under GPL License.
		 *
		 * License: http://www.plupload.com/license
		 * Contributing: http://www.plupload.com/contributing
		 */

		#!! IMPORTANT:
		#!! this file is just an example, it doesn't incorporate any security checks and
		#!! is not recommended to be used in production environment as it is. Be sure to
		#!! revise it and customize to your needs.


		// Make sure file is not cached (as it happens for example on iOS devices)
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");


		// Support CORS
		// header("Access-Control-Allow-Origin: *");
		// other CORS headers if any...
		if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
		    exit; // finish preflight CORS requests here
		}


		if ( !empty($_REQUEST[ 'debug' ]) ) {
		    $random = rand(0, intval($_REQUEST[ 'debug' ]) );
		    if ( $random === 0 ) {
		        header("HTTP/1.0 500 Internal Server Error");
		        exit;
		    }
		}

		// header("HTTP/1.0 500 Internal Server Error");
		// exit;


		// 5 minutes execution time
		@set_time_limit(5 * 60);

		// Uncomment this one to fake upload time
		usleep(5000);

		// Settings
		// $targetDir = ini_get("upload_tmp_dir") . DIRECTORY_SEPARATOR . "plupload";
		// $pid = $this->_get('pid');
		$targetDir = 'upload_tmp';
		$uploadDir = 'upload';

		$cleanupTargetDir = true; // Remove old files
		$maxFileAge = 5 * 3600; // Temp file age in seconds


		// Create target dir
		if (!file_exists($targetDir)) {
		    @mkdir($targetDir);
		}

		// Create target dir
		if (!file_exists($uploadDir)) {
		    @mkdir($uploadDir);
		}

		// Get a file name
		// if (isset($_REQUEST["name"])) {
		//     $fileName = $_REQUEST["name"].time();
		//     $fileName = String::randString(6).'.jpg';
		// } elseif (!empty($_FILES)) {
		//     $fileName = $_FILES["file"]["name"];
		// } else {
		//     $fileName = uniqid("file_");
		// }
		$fileName = String::randString(12).'.jpg';

		$md5File = @file('md5list.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		$md5File = $md5File ? $md5File : array();

		if (isset($_REQUEST["md5"]) && array_search($_REQUEST["md5"], $md5File ) !== FALSE ) {
		    die('{"jsonrpc" : "2.0", "result" : null, "id" : "id", "exist": 1}');
		}

		$filePath = $targetDir . DIRECTORY_SEPARATOR . $fileName;
		$uploadPath = $uploadDir . DIRECTORY_SEPARATOR . $fileName;

		// Chunking might be enabled
		$chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
		$chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 1;


		// Remove old temp files
		// if ($cleanupTargetDir) {
		//     if (!is_dir($targetDir) || !$dir = opendir($targetDir)) {
		//         die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
		//     }

		//     while (($file = readdir($dir)) !== false) {
		//         $tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;

		//         // If temp file is current file proceed to the next
		//         if ($tmpfilePath == "{$filePath}_{$chunk}.part" || $tmpfilePath == "{$filePath}_{$chunk}.parttmp") {
		//             continue;
		//         }

		//         // Remove temp file if it is older than the max age and is not the current file
		//         if (preg_match('/\.(part|parttmp)$/', $file) && (@filemtime($tmpfilePath) < time() - $maxFileAge)) {
		//             @unlink($tmpfilePath);
		//         }
		//     }
		//     closedir($dir);
		// }


		// Open temp file
		// if (!$out = @fopen("{$filePath}_{$chunk}.parttmp", "wb")) {
		//     die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
		// }

		// if (!empty($_FILES)) {
		//     if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"])) {
		//         die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
		//     }

		//     // Read binary input stream and append it to temp file
		//     if (!$in = @fopen($_FILES["file"]["tmp_name"], "rb")) {
		//         die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
		//     }
		// } else {
		//     if (!$in = @fopen("php://input", "rb")) {
		//         die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
		//     }
		// }
		$in = @fopen($_FILES["file"]["tmp_name"], "rb");
		$upyun = A('Shop/Upyun');
		$dir_pic = '/'.$this->token.'/'.date('Y').'/'.date('m').'/'.date('d').'/123_'.time().'.jpg';
		$test2 = $upyun->test($dir_pic, $in);
		$img_src = "http://".UNYUN_DOMAIN.$dir_pic;

		// while ($buff = fread($in, 4096)) {
		// 	$upyun = A('User/Upyun');
		// 	$dir_pic = '/'.$this->token.'/'.date('Y').'/'.date('m').'/'.date('d').'/123_'.time().'.jpg';
		// 	$test2 = $upyun->test($dir_pic, $in);
		// 	$img_src = "http://".UNYUN_DOMAIN.$dir_pic;
		//     // fwrite($out, $buff);
		// }

		@fclose($out);
		@fclose($in);

		rename("{$filePath}_{$chunk}.parttmp", "{$filePath}_{$chunk}.part");

		$index = 0;
		$done = true;
		for( $index = 0; $index < $chunks; $index++ ) {
		    if ( !file_exists("{$filePath}_{$index}.part") ) {
		        $done = false;
		        break;
		    }
		}
		if ( $done ) {
		    if (!$out = @fopen($uploadPath, "wb")) {
		        die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
		    }

		    if ( flock($out, LOCK_EX) ) {
		        for( $index = 0; $index < $chunks; $index++ ) {
		            if (!$in = @fopen("{$filePath}_{$index}.part", "rb")) {
		                break;
		            }

		            while ($buff = fread($in, 4096)) {
		                fwrite($out, $buff);
		            }

		            @fclose($in);
		            @unlink("{$filePath}_{$index}.part");
		        }

		        flock($out, LOCK_UN);
		    }
		    @fclose($out);
		}

		// Return Success JSON-RPC response
		//注入店铺照片表
		$pid = $this->_get('pid');
		$oid = $this->_get('oid');
		if($pid){
			$data = array(
				'pid' => $pid,
				'pic' => $img_src,
			);
			M('Products_show_pic')->add($data);
		}
		if($oid){
			$data = array(
				'oid' => $oid,
				'url' => $img_src,
				'addtime' => time(),
				'year' => date('Y',time()),
				'month' => date('m',time()),
				'day' => date('d',time()),
			);
			M('Cart_pics')->add($data);
		}

		die('{'.$img_src.' : "2.0", "result" : null, "id" : "id"}');
	}
}
?>