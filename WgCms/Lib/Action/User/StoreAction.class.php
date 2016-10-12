<?php
class StoreAction extends UserAction{
	public $token;
	public $product_model;
	public $product_cat_model;
	public $level_cat_id;
	public function _initialize() {
		parent::_initialize();
		$this->canUseFunction('shop');
		
		$this->assign('isDining', 0);
		$this->level_cat_id = 2;
		$this->assign('level_cat_id',$this->level_cat_id);
	}
	
	/**
	 * 分类列表
	 */
	public function index() {
		$parentid = isset($_GET['parentid']) ? intval($_GET['parentid']) : 0;
		$data = M('Product_cat');
		// $where = array('token' => session('token'), 'cid' => $this->_cid, 'parentid' => $parentid, 'id'=>array('neq',$this->level_cat_id));
		$where = array('token' => session('token'), 'cid' => $this->_cid, 'parentid' => $parentid);
        if (IS_POST) {
            $key = $this->_post('searchkey');
            if(empty($key)){
                $this->error("关键词不能为空");
            }

            $map['token'] = $this->get('token'); 
            $map['name|des'] = array('like',"%$key%"); 
            $list = $data->where($map)->order("sort DESC, id ASC")->select(); 
            $count      = $data->where($map)->count();       
            $Page       = new Page($count,20);
        	$show       = $Page->show();
        } else {
        	$count      = $data->where($where)->count();
        	$Page       = new Page($count,20);
        	$show       = $Page->show();
        	$list = $data->where($where)->order("sort DESC, id ASC")->limit($Page->firstRow.','.$Page->listRows)->select();
        }
		$this->assign('page',$show);		
		$this->assign('list',$list);
		if ($parentid){
			$parentCat = $data->where(array('id'=>$parentid))->find();
		}
		$this->assign('parentCat',$parentCat);
		$this->assign('parentid',$parentid);
		$this->display();
	}
	/**
	 * 轮播图列表
	 */
	public function banner() {
		$data = M('Product_banner');
		$where = array('token' => session('token'));
        if (IS_POST) {
            $key = $this->_post('searchkey');
            if(empty($key)){
                $this->error("关键词不能为空");
            }

            $map['token'] = $this->get('token'); 
            $map['name|des'] = array('like',"%$key%"); 
            $list = $data->where($map)->select(); 
            $count      = $data->where($map)->count();       
            $Page       = new Page($count,20);
        	$show       = $Page->show();
        } else {
        	$count      = $data->where($where)->count();
        	$Page       = new Page($count,20);
        	$show       = $Page->show();
        	$list = $data->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('sort desc,id asc')->select();
        }
		$this->assign('page',$show);		
		$this->assign('list',$list);
		if ($parentid){
			$parentCat = $data->where(array('id'=>$parentid))->find();
		}
		$this->assign('parentCat',$parentCat);
		$this->assign('parentid',$parentid);
		$this->display();		
	}
		/**
	 * 创建分类
	 */
	public function bannerAdd(){
		if(IS_POST){
			$this->insert('Product_banner','/banner');
		}else{
			$parentid=intval($_GET['parentid']);
			$parentid=$parentid==''?0:$parentid;
			$this->assign('parentid',$parentid);
			$this->display('bannerSet');
		}
	}
	
	/**
	 * 分类修改
	 */
	public function bannerSet(){
        $id = $this->_get('id'); 
		$checkdata = M('Product_banner')->where(array('id'=>$id))->find();
		if(empty($checkdata)){
            $this->error("没有相应记录.您现在可以添加.",U('Store/bannerAdd'));
        }
		if (IS_POST) {
            $data = D('Product_banner');
            $where=array('id'=>$this->_post('id'),'token'=>session('token'));
			$check=$data->where($where)->find();
			if($check==false)$this->error('非法操作');
			if($data->create()){
				if($data->where($where)->save($_POST)){
					$this->success('修改成功',U('Store/banner',array('token'=>session('token'),'parentid'=>$this->_post('parentid'))));
					
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
	public function bannerDel() {
		if($this->_get('token')!=session('token')){$this->error('非法操作');}
        $id = $this->_get('id');
        if(IS_GET){                              
            $where=array('id'=>$id,'token'=>session('token'));
            $data=M('Product_banner');
            $check=$data->where($where)->find();
            if($check==false)   $this->error('非法操作');
            $back=$data->where($where)->delete();
            if($back==true){
                $this->success('操作成功',U('Store/banner',array('token'=>session('token'),'parentid'=>$check['parentid'])));
            }else{
                 $this->error('服务器繁忙,请稍后再试',U('Store/banner',array('token'=>session('token'))));
            }
        }        
	}
	/**
	 * 广告位列表
	 */
	public function guanggao() {
		$data = M('Product_guanggao');
		$where = array('token' => session('token'));
        if (IS_POST) {
            $key = $this->_post('searchkey');
            if(empty($key)){
                $this->error("关键词不能为空");
            }

            $map['token'] = $this->get('token'); 
            $map['name|des'] = array('like',"%$key%"); 
            $list = $data->where($map)->select(); 
            $count      = $data->where($map)->count();       
            $Page       = new Page($count,20);
        	$show       = $Page->show();
        } else {
        	$count      = $data->where($where)->count();
        	$Page       = new Page($count,20);
        	$show       = $Page->show();
        	$list = $data->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('sort desc,id asc')->select();
        }
		$this->assign('page',$show);		
		$this->assign('list',$list);
		if ($parentid){
			$parentCat = $data->where(array('id'=>$parentid))->find();
		}
		$this->assign('parentCat',$parentCat);
		$this->assign('parentid',$parentid);
		$this->display();		
	}
		/**
	 * 创建分类
	 */
	public function guanggaoAdd(){
		if(IS_POST){
			$this->insert('Product_guanggao','/guanggao');
		}else{
			$parentid=intval($_GET['parentid']);
			$parentid=$parentid==''?0:$parentid;
			$this->assign('parentid',$parentid);
			$this->display('guanggaoSet');
		}
	}
	
	/**
	 * 分类修改
	 */
	public function guanggaoSet(){
        $id = $this->_get('id'); 
		$checkdata = M('Product_guanggao')->where(array('id'=>$id))->find();
		if(empty($checkdata)){
            $this->error("没有相应记录.您现在可以添加.",U('Store/guanggaoAdd'));
        }
		if (IS_POST) {
            $data = D('Product_guanggao');
            $where=array('id'=>$this->_post('id'),'token'=>session('token'));
			$check=$data->where($where)->find();
			if($check==false)$this->error('非法操作');
			if($data->create()){
				if($data->where($where)->save($_POST)){
					$this->success('修改成功',U('Store/guanggao',array('token'=>session('token'),'parentid'=>$this->_post('parentid'))));
					
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
	public function guanggaoDel() {
		if($this->_get('token')!=session('token')){$this->error('非法操作');}
        $id = $this->_get('id');
        if(IS_GET){                              
            $where=array('id'=>$id,'token'=>session('token'));
            $data=M('Product_guanggao');
            $check=$data->where($where)->find();
            if($check==false)   $this->error('非法操作');
            $back=$data->where($where)->delete();
            if($back==true){
                $this->success('操作成功',U('Store/guanggao',array('token'=>session('token'),'parentid'=>$check['parentid'])));
            }else{
                 $this->error('服务器繁忙,请稍后再试',U('Store/guanggao',array('token'=>session('token'))));
            }
        }        
	}
	/**
	 * 创建分类
	 */
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
			if (D('Product_cat')->add($_POST)) {
				D('Product_cat')->where(array('id' => $_POST['parentid']))->save(array('isfinal' => 2));
				$this->success('修改成功', U('Store/index', array('token' => session('token'), 'cid' => $this->_cid, 'parentid' => $parentid)));
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
		if (IS_POST) {
            $data = D('Product_cat');
            $where=array('id'=>$this->_post('id'),'token'=>session('token'));
			$check=$data->where($where)->find();
			if($check==false)$this->error('非法操作');
			if($data->create()){
				if($data->where($where)->save($_POST)){
					if (!$this->isDining){
						$this->success('修改成功',U('Store/index',array('token'=>session('token'),'parentid'=>$this->_post('parentid'))));
					}else {
						$this->success('修改成功',U('Store/index',array('token'=>session('token'),'parentid'=>$this->_post('parentid'),'dining'=>1)));
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
		if($this->_get('token')!=session('token')){$this->error('非法操作');}
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
		$data = M('Attribute');
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
        foreach ($list as $k => $v) {
        	if(!$v['price7']){
        		$list[$k]['price7'] = M('Product_detail')->where('pid='.$v['id'])->order('price7 asc')->getField('price7');
        	}
        }
		$this->assign('page',$show);		
		$this->assign('list',$list);
		$this->assign('isProductPage',1);
		$this->assign('catid', $catid);
		$this->display();		
	}
	
	/**
	 * 添加商品
	 */
	public function addNew() {
		$catid = intval($_GET['catid']);
		$id = intval($_GET['id']);
		if($productCatData = M('Product_cat')->where(array('id' => $catid, 'token' => session('token')))->find()){
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
        if ($id && ($product = M('Product')->where(array('catid' => $catid, 'token' => session('token'), 'id' => $id))->find())) {
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
	function test(){
		// $test = '[{"format":53,"color":54,"colorid":54,"price":"1","price2":"2","price3":"0","price4":"0","price5":"0","price6":"0","price7":"0","num":"0"}]';
		$test = '[{"format":53}]';
		$a = json_decode($test);
		if(is_object($a)){
			echo 'aa';
		}
		echo count($a);
		echo mysql_field_type($a);
		foreach ($a as $v) {
			$v = (array)$v;
			dump($v);
		}
	}
	/**
	 * 增加商品
	 */
	public function productSave() {
		if ($_POST['token'] != session('token')) {
			exit(json_encode(array('error_code' => true, 'msg' => '不合法的数据')));
		}

		$product = M('Product');
		$_POST['time'] = time();
		if ($product->create() === false) {
		    exit(json_encode(array('error_code' => false, 'msg' => $product->getError())));
		}
		//判断更新还是增加
		if ($_POST['id'] && $obj = $product->where(array('id' => $_POST['id'], 'token' => $_POST['token']))->find()) {
			//判断有没更换类别
			$oldcatid = $_POST['oldcatid'];
			$catid = $_POST['catid'];
			if($oldcatid && $oldcatid != $catid && !$_POST['lid']){
				M('Product_detail')->where(array('pid'=>$_POST['id']))->setField('pid',$oldcatid);
			}
			$pid = $product->save($_POST);
		} else {
			$pid = $product->add();
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
				$data_d = array('format' => $row['format'], 'color' => $row['color'], 'num' => $row['num'], 'price' => $row['price'],'price2' => $row['price2'],'price3' => $row['price3'],'price4' => $row['price4'],'price5' => $row['price5'],'price6' => $row['price6'],'price7' => $row['price7']);
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
		if($this->_get('token')!=session('token')){$this->error('非法操作');}
        $id = $this->_get('id');
        if(IS_GET){                              
            $where=array('id'=>$id,'token'=>session('token'));
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
		$where = array('token' => $this->_session('token'), 'groupon' => 0, 'dining' => 0,'active'=>1,'paid'=>1);
		if ($_REQUEST) {
			// if ($this->_request['token'] != $this->_session('token')) {
			// 	exit();
			// }
			$handleOrder = $this->_request('handleOrder');
			if (!$handleOrder) {
				$key = $this->_post('searchkey')!='' ? $this->_post('searchkey'): $this->_request('searchkey');
				$key2 = $this->_post('submitkey')!='' ? $this->_post('submitkey'): $this->_request('submitkey');
				if($key){
					//搜索账号
					$account = D('Account')->where(array('username'=>array('like', "%$key%")))->select();
					$account_str = '';
					if($account){
						foreach ($account as $k2 => $v2) {
							$account_str .=$v2['id'].',';
						}
					}

					// if($account){
					// 	$where['waid'] = array('in',rtrim($account_str,','));
					// }
					$where['_string'] = "waid in ('".rtrim($account_str,',')."') OR truename like '%".$key."%'";
					//$where['_query'] = 'waid=in('.rtrim($account_str,',').') & truename like '%123%'&_logic=or';

					// $where['truename|address|orderid'] = array('like', "%$key%");
					
				}
				if($key2){
					//搜索账号
					$account = D('Account')->where(array('username'=>array('like', "%$key2%")))->select();
					$account_str = '';
					if($account){
						foreach ($account as $k2 => $v2) {
							$account_str .=$v2['id'].',';
						}
					}
					$where['aid'] = array('in',rtrim($account_str,','));
				}
				$paid = $this->_post('paid')!='' ? $this->_post('paid'): $this->_request('paid');
				$sent = $this->_post('sent')!='' ? $this->_post('sent'): $this->_request('sent');
				$receive = $this->_post('receive')!='' ? $this->_post('receive'): $this->_request('receive');
				$handled = $this->_post('handled')!='' ? $this->_post('handled'): $this->_request('handled');
				if($paid != ''){
					$where['paid'] = $paid;
				}
				if($sent != ''){
					$where['sent'] = $sent;
				}
				if($receive != ''){
					$where['receive'] = $receive;
				}
				if($handled != ''){
					$where['handled'] = $handled;
				}
				//时间
				$starttime = $this->_post('starttime')!='' ? $this->_post('starttime'): $this->_request('starttime');
				$endtime = $this->_post('endtime')!='' ? $this->_post('endtime'): $this->_request('endtime');
				$starttime=date(strtotime($starttime));
				$endtime=date(strtotime($endtime))+86400;

				if($starttime && $endtime){
					$where['time'] = array(array('gt',$starttime),array('lt',$endtime),'and');
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
		$Page       = new Page($count,25);
		$show       = $Page->show();
		session('sns_where',serialize($where));
		$orders		= $product_cart_model->where($where)->order('time DESC')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		//遍历处理者
		foreach ($orders as $k => $v) {
			if($v['bindaid']){
				$orders[$k]['dealer'] = D('Account')->where('id='.$v['bindaid'])->getField('nickname');
			}else{
				$orders[$k]['dealer'] = '后台';
			}
		}
		$unHandledCount = $product_cart_model->where(array('token' => $this->_session('token'), 'handled' => 0))->count();
		foreach ($orders as $k => $v) {
			$orders[$k]['username'] = D('Account')->where('id='.$v['waid'])->getField('username');
			$orders[$k]['binduser'] = D('Account')->where('id='.$v['aid'])->getField('username');
		}
		$this->assign('unhandledCount', $unHandledCount);
		$this->assign('orders', $orders);
		$this->assign('page', $show);
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
			$product_cart_model->where(array('orderid'=>$thisOrder['orderid']))->save(array('sent'=>intval($_POST['sent']),'paid'=>intval($_POST['paid']),'receive'=>intval($_POST['receive']),'returnMoney'=>intval($_POST['returnMoney']),'logistics'=>$_POST['logistics'],'logisticsid'=>$_POST['logisticsid'],'handled'=>0));
			//TODO 发货的短信提醒
			if ($_POST['sent']) {
				$company = D('Company')->where(array('token' => $thisOrder['token'], 'isbranch' => 0))->find();
				$userInfo = M('Distribution_member')->where(array('token' => $thisOrder['token'], 'wecha_id' => $thisOrder['wecha_id']))->find();
				Sms::sendSms($this->token, "您在{$company['name']}商城购买的商品，商家已经给您发货了，请您注意查收", $thisOrder['tel']);
			}
			
			//
			/************************************************/
			/*if (intval($_POST['paid'])&&intval($thisOrder['price'])){
				$member_card_create_db=M('Member_card_create');
				$wecha_id=$thisOrder['wecha_id'];
				$userCard=$member_card_create_db->where(array('token'=>$this->token,'wecha_id'=>$wecha_id))->find();
				$member_card_set_db=M('Member_card_set');
				$thisCard=$member_card_set_db->where(array('id'=>intval($userCard['cardid'])))->find();
				$set_exchange = M('Member_card_exchange')->where(array('cardid'=>intval($thisCard['id'])))->find();
				//
				$arr['token']=$this->token;
				$arr['wecha_id']=$wecha_id;
				$arr['expense']=$thisOrder['price'];
				$arr['time']=time();
				$arr['cat']=99;
				$arr['staffid']=0;
				$arr['score']=intval($set_exchange['reward'])*$order['price'];
				M('Member_card_use_record')->add($arr);
				$userinfo_db=M('Userinfo');
				$thisUser = $userinfo_db->where(array('token'=>$thisCard['token'],'wecha_id'=>$arr['wecha_id']))->find();
				$userArr=array();
				$userArr['total_score']=$thisUser['total_score']+$arr['score'];
				$userArr['expensetotal']=$thisUser['expensetotal']+$arr['expense'];
				$userinfo_db->where(array('token'=>$thisCard['token'],'wecha_id'=>$arr['wecha_id']))->save($userArr);
			}*/
			/************************************************/
			//
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
			$carts=unserialize($thisOrder['info']);
			if($thisOrder['classid']){
				import ( "@.Org.TypeFile" );
				$tid = $thisOrder['classid'];
				$TypeFile = new TypeFile ( 'ClassCity' ); //实例化分类类
				$result = $TypeFile->getPathName ( $tid ); //获取分类路径
				$this->assign ( 'typeNumArr', $result );
			}
			//
			$totalFee=0;
			$totalCount=0;
			
			$data = $this->getCat($carts);
			if (isset($data[1])) {
				foreach ($data[1] as $pid => $row) {
					$totalCount += $row['total'];
					$totalFee += $row['totalPrice'];
					$listNum[$pid] = $row['total'];
				}
			}
			$list = $data[0];
//			print_r($list);die;
			$this->assign('products', $list);
			//
			$this->assign('totalFee',$totalFee);
			//
			$this->assign('totalCount',$totalCount);
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
					$this->returnCartOpration($cart['orderid'],0,2);
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
}
?>